@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Assign Role to {{ $user->first_name }} {{ $user->last_name }}</h2>

        @if(session('success'))
            <div style="color: green;">{{ session('success') }}</div>
        @endif

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

        <h3>Assigned Roles:</h3>
        @if($user->roles->isEmpty())
            <p>No roles assigned yet.</p>
        @else
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
        @endif
    </div>
@endsection
