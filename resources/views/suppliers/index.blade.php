@extends('layouts.app')

@section('content')
    <h2>Suppliers</h2>

    @can('manage-suppliers')
        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">Add Supplier</a>
    @endcan

    <table class="table">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Contact Number</th>
            <th>Actions</th>
        </tr>
        @foreach($suppliers as $supplier)
        <tr>
            <td>{{ $supplier->name }}</td>
            <td>{{ $supplier->email }}</td>
            <td>{{ $supplier->contact_number }}</td>
            <td>
                @can('manage-suppliers')
                    <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                @endcan
            </td>
        </tr>
        @endforeach
    </table>
@endsection
