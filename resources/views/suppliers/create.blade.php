@extends('layouts.app')

@section('content')
    <h2>Add Supplier</h2>

    <form action="{{ route('suppliers.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Contact Number</label>
            <input type="text" name="contact_number" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Address</label>
            <textarea name="address" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label>Company Name</label>
            <input type="text" name="company_name" class="form-control">
        </div>

        <div class="form-group">
            <label>GST Number</label>
            <input type="text" name="gst_number" class="form-control">
        </div>

        <div class="form-group">
            <label>Website</label>
            <input type="url" name="website" class="form-control">
        </div>

        <div class="form-group">
            <label>Country</label>
            <input type="text" name="country" class="form-control">
        </div>

        <div class="form-group">
            <label>State</label>
            <input type="text" name="state" class="form-control">
        </div>

        <div class="form-group">
            <label>City</label>
            <input type="text" name="city" class="form-control">
        </div>

        <div class="form-group">
            <label>Postal Code</label>
            <input type="text" name="postal_code" class="form-control">
        </div>

        <div class="form-group">
            <label>Contact Person</label>
            <input type="text" name="contact_person" class="form-control">
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <div class="form-group">
            <label>Contract Start Date</label>
            <input type="date" name="contract_start_date" class="form-control">
        </div>

        <div class="form-group">
            <label>Contract End Date</label>
            <input type="date" name="contract_end_date" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Save</button>
    </form>
@endsection
