<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'email', 
        'contact_number', 
        'address', 
        'company_name', 
        'gst_number', 
        'website', 
        'country', 
        'state', 
        'city', 
        'postal_code', 
        'contact_person', 
        'status', 
        'contract_start_date', 
        'contract_end_date'
    ];
}
