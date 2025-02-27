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
            <div>
                <input type="text" id="searchInput" class="form-control" placeholder="Search users...">
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
                        {{-- Users will be loaded dynamically via AJAX --}}
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div id="pagination" class="d-flex justify-content-center mt-4">
                <nav>
                    <ul class="pagination"></ul>
                </nav>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript for Delete Confirmation and AJAX --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Initial Load Users
        loadUsers(1);

        // Load users dynamically via AJAX
        function loadUsers(page = 1, search = '') {
            $.ajax({
                url: `/api/users?page=${page}&search=${search}`,
                type: "GET",
                dataType: "json",
                headers: {
                    Authorization: "Bearer " + localStorage.getItem("access_token")
                },
                success: function(response) {
                    updateUsersTable(response.data);
                    updatePagination(response);
                },
                error: function(xhr) {
                    console.error("Error loading users:", xhr.responseText);
                }
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
                
                // Format roles as badges
                let rolesHtml = user.roles.map(role => 
                    `<span class="badge bg-primary">${role.name}</span>`
                ).join(' ');
                
                // Format hobbies as badges
                let hobbiesArray = Array.isArray(user.hobbies) ? user.hobbies : 
                                  (typeof user.hobbies === 'string' ? JSON.parse(user.hobbies) : []);
                let hobbiesHtml = hobbiesArray.map(hobby => 
                    `<span class="badge bg-secondary">${hobby}</span>`
                ).join(' ');
                
                // Format uploaded files as links
                let filesArray = user.uploaded_files ? 
                               (Array.isArray(user.uploaded_files) ? user.uploaded_files : 
                               JSON.parse(user.uploaded_files)) : [];
                let filesHtml = filesArray.length > 0 ? 
                              filesArray.map(file => 
                                `<a href="/storage/${file}" target="_blank">${file.split('/').pop()}</a><br>`
                              ).join('') : 'N/A';

                // Create actions buttons based on permissions
                let actionsHtml = `
                @can('manage-users')
                    <a href="/users/${user.id}/edit" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>
                    @endcan
                    `;
                
                if (user.id != localStorage.getItem("user_id")) {
                    actionsHtml += `
                     @can('manage-users')
                        <button class="btn btn-danger btn-sm delete-user" data-id="${user.id}">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                                            @endcan

                    `;
                }

                tr.innerHTML = `
                    <td>${user.id}</td>
                    <td>${user.first_name}</td>
                    <td>${user.last_name}</td>
                    <td>${user.email}</td>
                    <td>${user.contact_number}</td>
                    <td>${user.postcode}</td>
                    <td>${ucFirst(user.gender)}</td>
                    <td>${user.state?.name || 'N/A'}</td>
                    <td>${user.city?.name || 'N/A'}</td>
                    <td>${rolesHtml}</td>
                    <td>${hobbiesHtml}</td>
                    <td>${filesHtml}</td>
                    <td>${actionsHtml}</td>
                `;
                tbody.appendChild(tr);
            });

            // Add event listeners to delete buttons
            document.querySelectorAll(".delete-user").forEach(button => {
                button.addEventListener("click", function() {
                    deleteUser(this.getAttribute("data-id"));
                });
            });
        }

        // Helper function to capitalize first letter
        function ucFirst(string) {
            if (!string) return '';
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        // Update Pagination Links
        function updatePagination(response) {
            const paginationElement = document.querySelector('#pagination ul');
            paginationElement.innerHTML = '';

            if (response.total <= response.per_page) return;

            // Previous button
            const prevDisabled = response.current_page === 1 ? "disabled" : "";
            const prevLi = document.createElement('li');
            prevLi.className = `page-item ${prevDisabled}`;
            prevLi.innerHTML = `
                <a class="page-link" href="#" data-page="${response.current_page - 1}">
                    <i class="bi bi-chevron-left"></i> Prev
                </a>
            `;
            paginationElement.appendChild(prevLi);

            // Page numbers
            for (let i = 1; i <= response.last_page; i++) {
                const activeClass = i === response.current_page ? "active" : "";
                const pageLi = document.createElement('li');
                pageLi.className = `page-item ${activeClass}`;
                pageLi.innerHTML = `
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                `;
                paginationElement.appendChild(pageLi);
            }

            // Next button
            const nextDisabled = response.current_page === response.last_page ? "disabled" : "";
            const nextLi = document.createElement('li');
            nextLi.className = `page-item ${nextDisabled}`;
            nextLi.innerHTML = `
                <a class="page-link" href="#" data-page="${response.current_page + 1}">
                    Next <i class="bi bi-chevron-right"></i>
                </a>
            `;
            paginationElement.appendChild(nextLi);

            // Add event listeners to pagination links
            document.querySelectorAll(".page-link").forEach(link => {
                link.addEventListener("click", function(e) {
                    e.preventDefault();
                    loadUsers(this.getAttribute("data-page"), document.getElementById("searchInput").value);
                });
            });
        }

        // Handle user deletion
        function deleteUser(userId) {
            if (!confirm("Are you sure you want to delete this user?")) return;

            $.ajax({
                url: `/api/users/${userId}`,
                type: "DELETE",
                headers: {
                    Authorization: "Bearer " + localStorage.getItem("access_token")
                },
                success: function() {
                    alert("User deleted successfully.");
                    loadUsers(1, document.getElementById("searchInput").value);
                },
                error: function(xhr) {
                    alert("Error deleting user: " + xhr.responseText);
                    console.error("Error deleting user:", xhr.responseText);
                }
            });
        }

        // Search functionality
        document.getElementById("searchInput").addEventListener("keyup", function() {
            loadUsers(1, this.value);
        });
    });
</script>

@endsection