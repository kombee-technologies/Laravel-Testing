@extends('layouts.app')

@section('content')
    <div class="container">
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

            <div class="form-group">
                <label>Company Name:</label>
                <input type="text" name="company_name" class="form-control">
            </div>

            <div class="form-group">
                <label>Job Title:</label>
                <input type="text" name="job_title" class="form-control">
            </div>

            <div class="form-group">
                <label>Gender:</label>
                <select name="gender" class="form-control">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label>Date of Birth:</label>
                <input type="date" name="date_of_birth" class="form-control">
            </div>

            <div class="form-group">
                <label>Nationality:</label>
                <input type="text" name="nationality" class="form-control">
            </div>

            <div class="form-group">
                <label>Customer Type:</label>
                <select name="customer_type" class="form-control">
                    <option value="Regular">Regular</option>
                    <option value="VIP">VIP</option>
                    <option value="Corporate">Corporate</option>
                </select>
            </div>

            <div class="form-group">
                <label>Preferred Contact Method:</label>
                <select name="preferred_contact_method" class="form-control">
                    <option value="Email">Email</option>
                    <option value="Phone">Phone</option>
                    <option value="WhatsApp">WhatsApp</option>
                </select>
            </div>

            <div class="form-group">
                <label>Newsletter Subscription:</label>
                <select name="newsletter_subscription" class="form-control">
                    <option value="1">Subscribed</option>
                    <option value="0">Not Subscribed</option>
                </select>
            </div>

            <div class="form-group">
                <label>Account Balance ($):</label>
                <input type="number" step="0.01" name="account_balance" class="form-control">
            </div>

            <button type="submit" class="btn btn-success">Save</button>
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
