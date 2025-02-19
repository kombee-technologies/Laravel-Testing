@extends('layouts.app')

@section('title', 'Users List')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Users List</h4>
        </div>
        <div class="card-body">
            
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            {{-- Add User Button --}}
            <a href="{{ route('users.create') }}" class="btn btn-success mb-3">
                <i class="bi bi-person-plus"></i> Add User
            </a>

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
                            {{-- <th>Roles</th> --}}
                            <th>Hobbies</th>
                            <th>Uploaded Files</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
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
                            {{-- <td>
                                @foreach(json_decode($user->roles, true) ?? [] as $role)
                                    <span class="badge bg-primary">{{ $role }}</span>
                                @endforeach
                            </td> --}}
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
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>

                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
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

        </div>
    </div>
</div>

{{-- JavaScript for Delete Confirmation --}}
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
    });
</script>

@endsection
