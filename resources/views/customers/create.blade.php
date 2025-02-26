@extends('layouts.app')

@section('content')
    <h2>Add Customer</h2>

    <form action="{{ route('customers.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Contact Number:</label>
            <input type="text" name="contact_number" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Save</button>
    </form>
@endsection
