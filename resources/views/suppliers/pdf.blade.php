<!DOCTYPE html>
<html>
<head>
    <title>Suppliers List</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Suppliers List</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Contact Number</th>
                <th>Address</th>
                <th>Company Name</th>
                <th>GST Number</th>
                <th>Website</th>
                <th>Country</th>
                <th>State</th>
                <th>City</th>
                <th>Postal Code</th>
                <th>Contact Person</th>
                <th>Status</th>
                <th>Contract Start Date</th>
                <th>Contract End Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suppliers as $supplier)
                <tr>
                    <td>{{ $supplier->name }}</td>
                    <td>{{ $supplier->email }}</td>
                    <td>{{ $supplier->contact_number }}</td>
                    <td>{{ $supplier->address }}</td>
                    <td>{{ $supplier->company_name }}</td>
                    <td>{{ $supplier->gst_number }}</td>
                    <td>{{ $supplier->website }}</td>
                    <td>{{ $supplier->country }}</td>
                    <td>{{ $supplier->state }}</td>
                    <td>{{ $supplier->city }}</td>
                    <td>{{ $supplier->postal_code }}</td>
                    <td>{{ $supplier->contact_person }}</td>
                    <td>{{ ucfirst($supplier->status) }}</td>
                    <td>{{ $supplier->contract_start_date }}</td>
                    <td>{{ $supplier->contract_end_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
