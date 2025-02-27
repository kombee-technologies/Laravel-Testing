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
        Schema::table('customers', function (Blueprint $table) {
            $table->string('company_name')->nullable();
            $table->string('job_title')->nullable();
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('nationality')->nullable();
            $table->string('customer_type')->nullable(); // e.g., Regular, VIP, Corporate
            $table->text('notes')->nullable();
            $table->string('preferred_contact_method')->nullable(); // e.g., Phone, Email
            $table->boolean('newsletter_subscription')->default(false);
            $table->decimal('account_balance', 10, 2)->default(0.00); // Financial balance related to customer
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'company_name',
                'job_title',
                'gender',
                'date_of_birth',
                'nationality',
                'customer_type',
                'notes',
                'preferred_contact_method',
                'newsletter_subscription',
                'account_balance',
            ]);
        });
    }
};
