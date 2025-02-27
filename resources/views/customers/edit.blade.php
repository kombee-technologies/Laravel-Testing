@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Customer</h2>

        <form action="{{ route('customers.update', $customer->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="name" class="form-control" value="{{ $customer->name }}" required>
            </div>

            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" value="{{ $customer->email }}" required>
            </div>

            <div class="form-group">
                <label>Contact Number:</label>
                <input type="text" name="contact_number" class="form-control" value="{{ $customer->contact_number }}" required>
            </div>

            <div class="form-group">
                <label>Company Name:</label>
                <input type="text" name="company_name" class="form-control" value="{{ $customer->company_name }}">
            </div>

            <div class="form-group">
                <label>Job Title:</label>
                <input type="text" name="job_title" class="form-control" value="{{ $customer->job_title }}">
            </div>

            <div class="form-group">
                <label>Gender:</label>
                <select name="gender" class="form-control">
                    <option value="Male" {{ $customer->gender == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ $customer->gender == 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Other" {{ $customer->gender == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="form-group">
                <label>Date of Birth:</label>
                <input type="date" name="date_of_birth" class="form-control" value="{{ $customer->date_of_birth }}">
            </div>

            <div class="form-group">
                <label>Nationality:</label>
                <input type="text" name="nationality" class="form-control" value="{{ $customer->nationality }}">
            </div>

            <div class="form-group">
                <label>Customer Type:</label>
                <select name="customer_type" class="form-control">
                    <option value="Regular" {{ $customer->customer_type == 'Regular' ? 'selected' : '' }}>Regular</option>
                    <option value="VIP" {{ $customer->customer_type == 'VIP' ? 'selected' : '' }}>VIP</option>
                    <option value="Corporate" {{ $customer->customer_type == 'Corporate' ? 'selected' : '' }}>Corporate</option>
                </select>
            </div>

            <div class="form-group">
                <label>Preferred Contact Method:</label>
                <select name="preferred_contact_method" class="form-control">
                    <option value="Email" {{ $customer->preferred_contact_method == 'Email' ? 'selected' : '' }}>Email</option>
                    <option value="Phone" {{ $customer->preferred_contact_method == 'Phone' ? 'selected' : '' }}>Phone</option>
                    <option value="WhatsApp" {{ $customer->preferred_contact_method == 'WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                </select>
            </div>

            <div class="form-group">
                <label>Newsletter Subscription:</label>
                <select name="newsletter_subscription" class="form-control">
                    <option value="1" {{ $customer->newsletter_subscription ? 'selected' : '' }}>Subscribed</option>
                    <option value="0" {{ !$customer->newsletter_subscription ? 'selected' : '' }}>Not Subscribed</option>
                </select>
            </div>

            <div class="form-group">
                <label>Account Balance ($):</label>
                <input type="number" step="0.01" name="account_balance" class="form-control" value="{{ $customer->account_balance }}">
            </div>

            <button type="submit" class="btn btn-primary">Update Customer</button>
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
