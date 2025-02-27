<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer List</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <h2>Customer List</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Contact Number</th>
                <th>Company Name</th>
                <th>Job Title</th>
                <th>Gender</th>
                <th>Date of Birth</th>
                <th>Nationality</th>
                <th>Customer Type</th>
                <th>Preferred Contact Method</th>
                <th>Newsletter Subscription</th>
                <th>Account Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $customer)
                <tr>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->contact_number }}</td>
                    <td>{{ $customer->company_name }}</td>
                    <td>{{ $customer->job_title }}</td>
                    <td>{{ $customer->gender }}</td>
                    <td>{{ $customer->date_of_birth }}</td>
                    <td>{{ $customer->nationality }}</td>
                    <td>{{ $customer->customer_type }}</td>
                    <td>{{ $customer->preferred_contact_method }}</td>
                    <td>{{ $customer->newsletter_subscription ? 'Yes' : 'No' }}</td>
                    <td>{{ $customer->account_balance }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
