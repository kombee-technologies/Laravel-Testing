<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SuppliersExport;

class SupplierController extends Controller
{
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(Supplier::paginate(4));
        }
        return view('suppliers.index');
    }
    



    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email',
            'contact_number' => 'required|string|max:15',
            'address' => 'nullable|string|max:500',
            'company_name' => 'nullable|string|max:255',
            'gst_number' => 'nullable|string|max:50|unique:suppliers,gst_number',
            'website' => 'nullable|url|max:255',
            'country' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'contact_person' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'contract_start_date' => 'nullable|date',
            'contract_end_date' => 'nullable|date|after_or_equal:contract_start_date',
        ]);

        Supplier::create($request->all());

        return redirect()->route('suppliers.index')->with('success', 'Supplier added successfully!');
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email,' . $supplier->id,
            'contact_number' => 'required|string|max:15',
            'address' => 'nullable|string|max:500',
            'company_name' => 'nullable|string|max:255',
            'gst_number' => 'nullable|string|max:50|unique:suppliers,gst_number,' . $supplier->id,
            'website' => 'nullable|url|max:255',
            'country' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'contact_person' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'contract_start_date' => 'nullable|date',
            'contract_end_date' => 'nullable|date|after_or_equal:contract_start_date',
        ]);

        $supplier->update($request->all());
 
        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully!');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully!');
    }




    public function exportCSV()
{
    return Excel::download(new SuppliersExport, 'suppliers.csv');
}

public function exportExcel()
{
    return Excel::download(new SuppliersExport, 'suppliers.xlsx');
}

public function exportPDF()
{
    $suppliers = Supplier::all();
    $pdf = Pdf::loadView('suppliers.pdf', compact('suppliers'));
    return $pdf->download('suppliers.pdf');
}
}
