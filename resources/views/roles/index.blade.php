@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Manage User Roles</h2>

    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Roles</th>
            <th>Assign Role</th>
            <th>Remove Role</th>
        </tr>
        @foreach ($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->first_name }} {{ $user->last_name }}</td>
            <td>{{ $user->email }}</td>
            <td>
                @if($user->roles && $user->roles->isNotEmpty())
                    {{ implode(', ', $user->roles->pluck('name')->toArray()) }}
                @else
                    No roles assigned
                @endif
            </td>
            <td>
                <form action="{{ route('roles.assign', $user->id) }}" method="POST">
                    @csrf
                    <select name="role_id" required>
                        <option value="">-- Choose Role --</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit">Assign</button>
                </form>
            </td>
            <td>
                @if($user->roles && $user->roles->isNotEmpty())
                    @foreach ($user->roles as $role)
                        <form action="{{ route('roles.remove', [$user->id, $role->id]) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Remove {{ $role->name }}</button>
                        </form>
                    @endforeach
                @else
                    No roles to remove
                @endif
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
