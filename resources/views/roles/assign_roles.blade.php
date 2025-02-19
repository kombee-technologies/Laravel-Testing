@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Assign Role to {{ $user->first_name }} {{ $user->last_name }}</h2>

        @if(session('success'))
            <div style="color: green;">{{ session('success') }}</div>
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

        <!-- Remove Role Form -->
        <h3>Assigned Roles:</h3>
        @if($user->roles && $user->roles->isEmpty())
            <p>No roles assigned yet.</p>
        @else
            <ul>
                @if($user->roles && $user->roles->count() > 0)
                <ul>
                    @foreach ($user->roles as $role)
                        <li>
                            {{ $role->name }}
                            <form action="{{ route('roles.remove', $user->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <input type="hidden" name="role_id" value="{{ $role->id }}">
                                <button type="submit">Remove</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>No roles assigned yet.</p>
            @endif
            
            </ul>
        @endif
    </div>
@endsection
