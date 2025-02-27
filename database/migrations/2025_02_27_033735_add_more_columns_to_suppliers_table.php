<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('company_name')->after('address');
            $table->string('gst_number')->nullable()->after('company_name');
            $table->string('website')->nullable()->after('gst_number');
            $table->string('country')->after('website');
            $table->string('state')->after('country');
            $table->string('city')->after('state');
            $table->string('postal_code')->after('city');
            $table->string('contact_person')->nullable()->after('postal_code');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('contact_person');
            $table->date('contract_start_date')->nullable()->after('status');
            $table->date('contract_end_date')->nullable()->after('contract_start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn([
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
            ]);
        });
    }
};
