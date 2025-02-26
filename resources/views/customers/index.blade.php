@extends('layouts.app')

@section('content')
    <h2>Customers</h2>

    @can('manage-customers')
        <a href="{{ route('customers.create') }}" class="btn btn-primary">Add Customer</a>
    @endcan

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Contact Number</th>
                @can('manage-customers')
                    <th>Actions</th>
                @endcan
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $customer)
            <tr>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->contact_number }}</td>
                @can('manage-customers')
                <td>
                    <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
                @endcan
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
