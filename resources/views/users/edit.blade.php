@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4>Edit User</h4>
        </div>
        <div class="card-body">
            {{-- Display Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Edit User Form --}}
            <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- First Name --}}
                    <div class="col-md-6 mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control" id="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                    </div>

                    {{-- Last Name --}}
                    <div class="col-md-6 mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control" id="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" id="email" value="{{ old('email', $user->email) }}" required>
                    </div>

                    {{-- Contact Number --}}
                    <div class="col-md-6 mb-3">
                        <label for="contact_number" class="form-label">Contact Number</label>
                        <input type="text" name="contact_number" class="form-control" id="contact_number" value="{{ old('contact_number', $user->contact_number) }}" required>
                    </div>

                    {{-- Postcode --}}
                    <div class="col-md-6 mb-3">
                        <label for="postcode" class="form-label">Postcode</label>
                        <input type="text" name="postcode" class="form-control" id="postcode" value="{{ old('postcode', $user->postcode) }}" required>
                    </div>

                    {{-- Gender --}}
                    <div class="col-md-6 mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select name="gender" class="form-control" id="gender">
                            <option value="Male" {{ $user->gender == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ $user->gender == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ $user->gender == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    {{-- State Dropdown --}}
                    <div class="col-md-6 mb-3">
                        <label for="state_id" class="form-label">State</label>
                        <select name="state_id" id="state_id" class="form-control">
                            <option value="">Select State</option>
                            @foreach ($states as $state)
                                <option value="{{ $state->id }}" {{ $user->state_id == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- City Dropdown --}}
                    <div class="col-md-6 mb-3">
                        <label for="city_id" class="form-label">City</label>
                        <select name="city_id" id="city_id" class="form-control">
                            <option value="">Select City</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}" {{ $user->city_id == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Roles --}}
                    <div class="col-md-12 mb-3">
                        <label for="roles" class="form-label">Roles</label>
                        <select name="roles[]" id="roles" class="form-control" multiple>
                            <option value="Admin" {{ in_array('Admin', json_decode($user->roles, true) ?? []) ? 'selected' : '' }}>Admin</option>
                            <option value="Editor" {{ in_array('Editor', json_decode($user->roles, true) ?? []) ? 'selected' : '' }}>Editor</option>
                            <option value="User" {{ in_array('User', json_decode($user->roles, true) ?? []) ? 'selected' : '' }}>User</option>
                        </select>
                    </div>

                    {{-- File Uploads --}}
                    <div class="col-md-12 mb-3">
                        <label for="files" class="form-label">Upload Files</label>
                        <input type="file" name="files[]" class="form-control" id="files" multiple>
                        @if (!empty(json_decode($user->uploaded_files, true)))
                            <div class="mt-2">
                                <strong>Previously Uploaded Files:</strong>
                                <ul>
                                    @foreach (json_decode($user->uploaded_files, true) as $file)
                                        <li><a href="{{ asset('storage/' . $file) }}" target="_blank">{{ basename($file) }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    {{-- Submit Button --}}
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Update User</button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JavaScript for AJAX Dynamic City Dropdown --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#state_id').on('change', function() {
            var stateId = $(this).val();
            if (stateId) {
                $.ajax({
                    url: "{{ route('get.cities') }}",
                    type: "GET",
                    data: { state_id: stateId },
                    success: function(response) {
                        $('#city_id').empty().append('<option value="">Select City</option>');
                        $.each(response, function(key, city) {
                            $('#city_id').append('<option value="' + city.id + '">' + city.name + '</option>');
                        });
                    }
                });
            } else {
                $('#city_id').empty().append('<option value="">Select City</option>');
            }
        });

        // Trigger change event on page load if editing a user
        var selectedState = $('#state_id').val();
        if (selectedState) {
            $('#state_id').trigger('change');
        }
    });
</script>

@endsection
