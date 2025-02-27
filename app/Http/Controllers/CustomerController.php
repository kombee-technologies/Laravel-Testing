<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomersExport;
use Barryvdh\DomPDF\Facade\Pdf;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(Customer::paginate(4));
        }
        return view('customers.index');
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers|max:255',
            'contact_number' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'company_name' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'gender' => 'nullable|in:Male,Female,Other',
            'date_of_birth' => 'nullable|date',
            'nationality' => 'nullable|string|max:100',
            'customer_type' => 'nullable|in:Regular,VIP,Enterprise',
            'notes' => 'nullable|string|max:1000',
            'preferred_contact_method' => 'nullable|in:Email,Phone,WhatsApp',
            'newsletter_subscription' => 'boolean',
            'account_balance' => 'nullable|numeric|min:0',
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id . '|max:255',
            'contact_number' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'company_name' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'gender' => 'nullable|in:Male,Female,Other',
            'date_of_birth' => 'nullable|date',
            'nationality' => 'nullable|string|max:100',
            'customer_type' => 'nullable|in:Regular,VIP,Enterprise',
            'notes' => 'nullable|string|max:1000',
            'preferred_contact_method' => 'nullable|in:Email,Phone,WhatsApp',
            'newsletter_subscription' => 'boolean',
            'account_balance' => 'nullable|numeric|min:0',
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }

    // CSV Export
    public function exportCSV()
    {
        return Excel::download(new CustomersExport, 'customers.csv');
    }

    // Excel Export
    public function exportExcel()
    {
        return Excel::download(new CustomersExport, 'customers.xlsx');
    }

    // PDF Export
    public function exportPDF()
    {
        $customers = Customer::all();
        $pdf = Pdf::loadView('exports.customers_pdf', compact('customers'));
        return $pdf->download('customers.pdf');
    }
}
