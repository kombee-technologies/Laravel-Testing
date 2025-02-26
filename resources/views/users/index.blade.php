@extends('layouts.app')

@section('title', 'Users List')

@section('content')
<div class="container mt-4">
    @php
        $loggedInUser = Auth::user();
        $userRoles = $loggedInUser->roles->pluck('name')->implode(', '); // Get all roles as a comma-separated string
    @endphp

    <div class="alert alert-info">
        <strong>Logged in as:</strong> {{ $loggedInUser->first_name }} {{ $loggedInUser->last_name }}
        <span class="badge bg-secondary">({{ $userRoles ?: 'User' }})</span>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Users List</h4>
            <div>
                <a href="{{ route('suppliers.index') }}" class="btn btn-primary me-2">
                    <i class="bi bi-box-seam"></i> Suppliers
                </a>
                <a href="{{ route('customers.index') }}" class="btn btn-info">
                    <i class="bi bi-people"></i> Customers
                </a>
            </div>
        </div>
        <div class="card-body">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error') || $errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') ?? $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="d-flex justify-content-between mb-3">
                {{-- Add User Button --}}
                @can('manage-users')
                <div>
                    <a href="{{ route('users.create') }}" class="btn btn-success">
                        <i class="bi bi-person-plus"></i> Add User
                    </a>
                </div>
                @endcan

                {{-- Manage User Roles Button --}}
                <div>
                    @can('manage-users')
                    <a href="{{ url('/user-roles') }}" class="btn btn-warning">
                        <i class="bi bi-shield-lock"></i> Manage User Roles
                    </a>
                    @endcan
                </div>
                
                {{-- Export Buttons --}}
                <div class="btn-group" role="group">
                    <button id="exportDropdown" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-download"></i> Export
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                        <li><a class="dropdown-item" href="{{ route('users.export.csv') }}" id="exportCsv">
                            <i class="bi bi-filetype-csv"></i> CSV
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('users.export.excel') }}" id="exportExcel">
                            <i class="bi bi-file-earmark-excel"></i> Excel
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('users.export.pdf') }}" id="exportPdf">
                            <i class="bi bi-file-earmark-pdf"></i> PDF
                        </a></li>
                    </ul>
                </div>
            </div>

            {{-- Users Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Contact Number</th>
                            <th>Postcode</th>
                            <th>Gender</th>
                            <th>State</th>
                            <th>City</th>
                            <th>Roles</th>
                            <th>Hobbies</th>
                            <th>Uploaded Files</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->first_name }}</td>
                            <td>{{ $user->last_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->contact_number }}</td>
                            <td>{{ $user->postcode }}</td>
                            <td>{{ ucfirst($user->gender) }}</td>
                            <td>{{ $user->state->name ?? 'N/A' }}</td>
                            <td>{{ $user->city->name ?? 'N/A' }}</td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge bg-primary">{{ $role->name }}</span>
                                @endforeach
                            </td>
                             <td>
                                @foreach(json_decode($user->hobbies, true) ?? [] as $hobby)
                                    <span class="badge bg-secondary">{{ $hobby }}</span>
                                @endforeach
                            </td>
                            <td>
                                @if($user->uploaded_files)
                                    @foreach(json_decode($user->uploaded_files, true) as $file)
                                        <a href="{{ asset('storage/' . $file) }}" target="_blank">{{ basename($file) }}</a><br>
                                    @endforeach
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @can('manage-users')
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>

                                @if($user->id != Auth::id())
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                                @endif
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="13" class="text-center">No users found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div id="pagination" class="d-flex justify-content-center mt-4">
                <nav>
                    <ul class="pagination">
                        {{-- Previous Page Link --}}
                        <li class="page-item {{ $users->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $users->previousPageUrl() }}" tabindex="-1" aria-disabled="{{ $users->onFirstPage() ? 'true' : 'false' }}">
                                <i class="bi bi-chevron-left"></i> Prev
                            </a>
                        </li>

                        {{-- Page Number Links --}}
                        @for ($i = 1; $i <= $users->lastPage(); $i++)
                            <li class="page-item {{ $i == $users->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $users->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        {{-- Next Page Link --}}
                        <li class="page-item {{ $users->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link" href="{{ $users->nextPageUrl() }}">
                                Next <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript for Delete Confirmation and AJAX --}}
{{-- JavaScript for Delete Confirmation and AJAX Pagination --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const deleteForms = document.querySelectorAll(".delete-form");
        deleteForms.forEach((form) => {
            form.addEventListener("submit", function (event) {
                event.preventDefault();
                if (confirm("Are you sure you want to delete this user?")) {
                    this.submit();
                }
            });
        });

        // Initial Load Users
        loadUsers();

        // Load users dynamically via AJAX
        function loadUsers(page = 1) {
            fetch(`/users?page=${page}`)
                .then(response => response.json())
                .then(data => {
                    updateUsersTable(data.data);
                    updatePagination(data.links, data.current_page, data.last_page);
                })
                .catch(error => {
                    console.error('Error loading users:', error);
                });
        }

        // Update the Users Table
        function updateUsersTable(users) {
            const tbody = document.getElementById('usersTableBody');
            tbody.innerHTML = '';

            if (users.length === 0) {
                const tr = document.createElement('tr');
                tr.innerHTML = '<td colspan="13" class="text-center">No users found.</td>';
                tbody.appendChild(tr);
                return;
            }

            users.forEach(user => {
                const tr = document.createElement('tr');
                let rolesHtml = user.roles.map(role => `<span class="badge bg-primary">${role.name}</span>`).join(' ');
                let hobbiesHtml = (user.hobbies || []).map(hobby => `<span class="badge bg-secondary">${hobby}</span>`).join(' ');
                let filesHtml = (user.uploaded_files || []).map(file => `<a href="/storage/${file}" target="_blank">${file.split('/').pop()}</a><br>`).join(' ') || 'N/A';

                let actionsHtml = '';
                if (user.id !== {{ Auth::id() }}) {
                    actionsHtml = `
                        <a href="/users/${user.id}/edit" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i> Edit</a>
                        <form action="/users/${user.id}" method="POST" class="d-inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Delete</button>
                        </form>
                    `;
                }

                tr.innerHTML = `
                    <td>${user.id}</td>
                    <td>${user.first_name}</td>
                    <td>${user.last_name}</td>
                    <td>${user.email}</td>
                    <td>${user.contact_number}</td>
                    <td>${user.postcode}</td>
                    <td>${user.gender}</td>
                    <td>${user.state_name || 'N/A'}</td>
                    <td>${user.city_name || 'N/A'}</td>
                    <td>${rolesHtml}</td>
                    <td>${hobbiesHtml}</td>
                    <td>${filesHtml}</td>
                    <td>${actionsHtml}</td>
                `;
                tbody.appendChild(tr);
            });
        }

        // Update Pagination Links
        function updatePagination(links, currentPage, lastPage) {
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = links;

            const paginationLinks = pagination.querySelectorAll('.page-link');
            paginationLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    const page = this.getAttribute('href').split('page=')[1];
                    loadUsers(page);
                });
            });
        }
    });
</script>

@endsection
