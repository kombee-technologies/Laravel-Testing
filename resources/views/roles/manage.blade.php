@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Assign & Manage Roles for {{ $user->first_name }} {{ $user->last_name }}</h2>

        <!-- Display Success Message -->
        @if(session('success'))
            <div style="color: green; margin-bottom: 15px;">{{ session('success') }}</div>
        @endif

        <!-- Assign Role Form -->
        <form action="{{ route('roles.assign', $user->id) }}" method="POST">
            @csrf
            <label for="role">Select Role:</label>
            <select name="role_id" required>
                <option value="">-- Choose Role --</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
            <button type="submit">Assign Role</button>
        </form>

        <hr>

        <!-- Display Assigned Roles -->
        <h3>Assigned Roles:</h3>
        @if($user->roles && $user->roles->isNotEmpty())
            <ul>
                @foreach ($user->roles as $role)
                    <li>
                        {{ $role->name }}
                        <form action="{{ route('roles.remove', $user->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('POST')
                            <input type="hidden" name="role_id" value="{{ $role->id }}">
                            <button type="submit" onclick="return confirm('Are you sure you want to remove this role?')">
                                Remove
                            </button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @else
            <p>No roles assigned yet.</p>
        @endif
    </div>
@endsection
