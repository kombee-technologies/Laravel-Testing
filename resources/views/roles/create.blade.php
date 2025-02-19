@extends('layouts.app')

@section('content')
    <h2>Create Role</h2>
    <form action="{{ route('roles.store') }}" method="POST">
        @csrf
        <input type="text" name="name" placeholder="Role Name">
        <button type="submit">Create</button>
    </form>
@endsection
