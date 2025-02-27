@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Customers</h2>

        @can('manage-customers')
            <a href="{{ route('customers.create') }}" class="btn btn-primary">Add Customer</a>
        @endcan

        <div class="mb-3">
            <button class="btn btn-success" id="exportCSV">Export CSV</button>
            <button class="btn btn-info" id="exportExcel">Export Excel</button>
            <button class="btn btn-danger" id="exportPDF">Export PDF</button>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="customersTable">
                <thead class="table-dark">
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
                        @can('manage-customers')
                            <th>Actions</th>
                        @endcan
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div id="paginationLinks" class="mt-3"></div>
    </div>

    <script>
        function fetchCustomers(page = 1) {
            let token = localStorage.getItem("access_token"); // Retrieve API token

            $.ajax({
                url: "/api/customers?page=" + page,
                method: "GET",
                headers: {
                    Authorization: "Bearer " + token
                },
                success: function (response) {
                    console.log("API Response:", response); // Debugging log

                    let tableBody = $("#customersTable tbody");
                    tableBody.empty();

                    if (response.data && response.data.length > 0) {
                        response.data.forEach(function (customer) {
                            tableBody.append(`
                                <tr>
                                    <td>${customer.name}</td>
                                    <td>${customer.email}</td>
                                    <td>${customer.contact_number || 'N/A'}</td>
                                    <td>${customer.company_name || 'N/A'}</td>
                                    <td>${customer.job_title || 'N/A'}</td>
                                    <td>${customer.gender || 'N/A'}</td>
                                    <td>${customer.date_of_birth || 'N/A'}</td>
                                    <td>${customer.nationality || 'N/A'}</td>
                                    <td>${customer.customer_type || 'N/A'}</td>
                                    <td>${customer.preferred_contact_method || 'N/A'}</td>
                                    <td>${customer.newsletter_subscription ? 'Subscribed' : 'Not Subscribed'}</td>
                                    <td>${customer.account_balance ? '$' + customer.account_balance : 'N/A'}</td>
                                    @can('manage-customers')
                                    <td>
                                        <a href="/customers/${customer.id}/edit" class="btn btn-warning btn-sm">Edit</a>
                                        <button class="btn btn-danger btn-sm" onclick="deleteCustomer(${customer.id})">Delete</button>
                                    </td>
                                    @endcan
                                </tr>
                            `);
                        });
                    } else {
                        tableBody.append(`<tr><td colspan="13" class="text-center">No customers found.</td></tr>`);
                    }

                    // Update Pagination Links
                    let paginationLinks = $('#paginationLinks');
                    paginationLinks.empty();
                    if (response.links) {
                        response.links.forEach(link => {
                            paginationLinks.append(`<a href="#" onclick="fetchCustomers(${link.label})" class="btn btn-sm ${link.active ? 'btn-primary' : 'btn-light'}">${link.label}</a> `);
                        });
                    }
                },
                error: function (xhr) {
                    console.error("XHR Response:", xhr);
                    alert("Error fetching data: " + (xhr.responseJSON?.error || xhr.responseText || "Unknown error"));
                }
            });
        }





        function deleteCustomer(id) {
            if (!confirm('Are you sure you want to delete this customer?')) return;

    $.ajax({
        url: `/api/customers/${id}`,
        method: 'POST',  // Change from DELETE to POST
        data: { _method: 'DELETE' },  // Laravel will recognize this as a DELETE request
        headers: {
            'Authorization': "Bearer " + localStorage.getItem("access_token"),
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        success: function () {
            alert('Customer deleted successfully!');
            fetchCustomers(); // Refresh supplier list
        },
        error: function (xhr) {
            alert(xhr.responseJSON?.message || "Failed to delete Customer. Ensure you are authenticated.");
            console.error("Error:", xhr.responseText);
        }
    });
}

        // function deleteCustomer(id) {
        //     if (!confirm('Are you sure you want to delete this customer?')) return;

        //     $.ajax({
        //         url: `/api/customers/${id}`,
        //         method: 'DELETE',
        //         headers: {
        //             'Authorization': "Bearer " + localStorage.getItem("access_token")
        //         },
        //         success: function () {
        //             fetchCustomers();
        //         },
        //         error: function (xhr) {
        //             alert("Failed to delete customer. Ensure you are authenticated.");
        //             console.error(xhr.responseText);
        //         }
        //     });
        // }

        $(document).ready(function () {
            fetchCustomers();

            $('#exportCSV').click(function () {
                window.location.href = "/api/customers/export/csv?token=" + localStorage.getItem("access_token");
            });

            $('#exportExcel').click(function () {
                window.location.href = "/api/customers/export/excel?token=" + localStorage.getItem("access_token");
            });

            $('#exportPDF').click(function () {
                window.location.href = "/api/customers/export/pdf?token=" + localStorage.getItem("access_token");
            });
        });
    </script>
@endsection
