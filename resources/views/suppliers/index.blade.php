@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Suppliers</h2>

        @can('manage-suppliers')
            <a href="{{ route('suppliers.create') }}" class="btn btn-primary">Add Supplier</a>
        @endcan

        <div class="mb-3">
            <button class="btn btn-success" id="exportCSV">Export CSV</button>
            <button class="btn btn-info" id="exportExcel">Export Excel</button>
            <button class="btn btn-danger" id="exportPDF">Export PDF</button>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="suppliersTable">
                <thead class="table-dark">
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
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div id="paginationLinks" class="mt-3"></div>
    </div>

    <script>
        function fetchSuppliers(page = 1) {
            let token = localStorage.getItem("access_token"); // Retrieve token from local storage

            $.ajax({
                url: "/api/suppliers?page=" + page,
                method: "GET",
                headers: {
                    Authorization: "Bearer " + token
                },
                success: function (response) {
                    console.log("API Response:", response); // Debugging log

                    let tableBody = $("#suppliersTable tbody");
                    tableBody.empty(); // Clear existing table data

                    if (response.data && response.data.length > 0) {
                        response.data.forEach(function (supplier) {
                            tableBody.append(`
                                <tr>
                                    <td>${supplier.name}</td>
                                    <td>${supplier.email}</td>
                                    <td>${supplier.contact_number || 'N/A'}</td>
                                    <td>${supplier.address || 'N/A'}</td>
                                    <td>${supplier.company_name || 'N/A'}</td>
                                    <td>${supplier.gst_number || 'N/A'}</td>
                                    <td><a href="${supplier.website || '#'}" target="_blank">${supplier.website || 'N/A'}</a></td>
                                    <td>${supplier.country || 'N/A'}</td>
                                    <td>${supplier.state || 'N/A'}</td>
                                    <td>${supplier.city || 'N/A'}</td>
                                    <td>${supplier.postal_code || 'N/A'}</td>
                                    <td>${supplier.contact_person || 'N/A'}</td>
                                    <td><span class="badge ${supplier.status === 'active' ? 'bg-success' : 'bg-danger'}">
                                        ${supplier.status.charAt(0).toUpperCase() + supplier.status.slice(1)}</span>
                                    </td>
                                    <td>${supplier.contract_start_date ? new Date(supplier.contract_start_date).toLocaleDateString() : 'N/A'}</td>
                                    <td>${supplier.contract_end_date ? new Date(supplier.contract_end_date).toLocaleDateString() : 'N/A'}</td>
                                    <td>
                                        @can('manage-suppliers')
                                            <a href="/suppliers/${supplier.id}/edit" class="btn btn-warning btn-sm">Edit</a>
                                            <button class="btn btn-danger btn-sm" onclick="deleteSupplier(${supplier.id})">Delete</button>
                                        @endcan
                                    </td>
                                </tr>
                            `);
                        });
                    } else {
                        tableBody.append(`<tr><td colspan="16" class="text-center">No suppliers found.</td></tr>`);
                    }

                    // Update Pagination Links
                    let paginationLinks = $('#paginationLinks');
                    paginationLinks.empty();
                    if (response.links) {
                        response.links.forEach(link => {
                            paginationLinks.append(`<a href="#" onclick="fetchSuppliers(${link.label})" class="btn btn-sm ${link.active ? 'btn-primary' : 'btn-light'}">${link.label}</a> `);
                        });
                    }
                },
                error: function (xhr) {
                    console.error("XHR Response:", xhr);
                    alert("Error fetching data: " + (xhr.responseJSON?.error || xhr.responseText || "Unknown error"));
                }
            });
        }

        function deleteSupplier(id) {
    if (!confirm('Are you sure you want to delete this supplier?')) return;

    $.ajax({
        url: `/api/suppliers/${id}`,
        method: 'POST',  // Change from DELETE to POST
        data: { _method: 'DELETE' },  // Laravel will recognize this as a DELETE request
        headers: {
            'Authorization': "Bearer " + localStorage.getItem("access_token"),
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        success: function () {
            alert('Supplier deleted successfully!');
            fetchSuppliers(); // Refresh supplier list
        },
        error: function (xhr) {
            alert(xhr.responseJSON?.message || "Failed to delete supplier. Ensure you are authenticated.");
            console.error("Error:", xhr.responseText);
        }
    });
}
// ✅ Open Edit Supplier Modal (Prefill Data)
function editSupplier(id) {
    let token = localStorage.getItem("access_token");

    $.ajax({
        url: `/api/suppliers/${id}`,
        method: "GET",
        headers: {
            Authorization: "Bearer " + token
        },
        success: function (supplier) {
            $("#editSupplierModal").modal("show"); // Show modal

            // Prefill form fields
            $("#editSupplierId").val(supplier.id);
            $("#editName").val(supplier.name);
            $("#editEmail").val(supplier.email);
            $("#editContactNumber").val(supplier.contact_number);
            $("#editAddress").val(supplier.address);
            $("#editCompanyName").val(supplier.company_name);
            $("#editGstNumber").val(supplier.gst_number);
            $("#editWebsite").val(supplier.website);
            $("#editCountry").val(supplier.country);
            $("#editState").val(supplier.state);
            $("#editCity").val(supplier.city);
            $("#editPostalCode").val(supplier.postal_code);
            $("#editContactPerson").val(supplier.contact_person);
            $("#editStatus").val(supplier.status);
            $("#editContractStartDate").val(supplier.contract_start_date);
            $("#editContractEndDate").val(supplier.contract_end_date);
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            alert("Error fetching supplier details.");
        }
    });
}

function updateSupplier() {
    let token = localStorage.getItem("access_token");
    let supplierId = $("#editSupplierId").val();
    let formData = $("#editSupplierForm").serialize() + "&_method=PUT";

    console.log("Updating Supplier ID:", supplierId);  // Debugging log
    console.log("Form Data:", formData);               // Debugging log

    $.ajax({
        url: `/api/suppliers/${supplierId}`,
        method: "POST", // Laravel understands this as PUT because of _method=PUT
        data: formData,
        headers: {
            'Authorization': "Bearer " + token,
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        success: function (response) {
            console.log("Update Success:", response);
            alert("Supplier updated successfully!");
            $("#editSupplierModal").modal("hide");
            fetchSuppliers(); // Refresh the list
        },
        error: function (xhr) {
            console.error("Update Error:", xhr.responseText);
            alert("Failed to update supplier. Check console logs.");
        }
    });
}


        $(document).ready(function () {
            fetchSuppliers();

            $('#exportCSV').click(function () {
                window.location.href = "/api/suppliers/export/csv?token=" + localStorage.getItem("access_token");
            });

            $('#exportExcel').click(function () {
                window.location.href = "/api/suppliers/export/excel?token=" + localStorage.getItem("access_token");
            });

            $('#exportPDF').click(function () {
                window.location.href = "/api/suppliers/export/pdf?token=" + localStorage.getItem("access_token");
            });
        });
    </script>
@endsection
