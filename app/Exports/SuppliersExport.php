<?php

namespace App\Exports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SuppliersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Supplier::select([
            'name', 'email', 'contact_number', 'address', 'company_name',
            'gst_number', 'website', 'country', 'state', 'city', 
            'postal_code', 'contact_person', 'status', 'contract_start_date', 
            'contract_end_date'
        ])->paginate(10);  // Apply pagination
    }

    public function headings(): array
    {
        return [
            'Name', 'Email', 'Contact Number', 'Address', 'Company Name',
            'GST Number', 'Website', 'Country', 'State', 'City', 
            'Postal Code', 'Contact Person', 'Status', 'Contract Start Date', 
            'Contract End Date'
        ];
    }
}
