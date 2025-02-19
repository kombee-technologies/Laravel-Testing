<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('first_name');
        $table->string('last_name');
        $table->string('email')->unique();
        $table->string('contact_number');
        $table->string('postcode');
        $table->string('password');
        $table->enum('gender', ['male', 'female']);
        $table->unsignedBigInteger('state_id');
        $table->unsignedBigInteger('city_id');
        $table->json('roles');
        $table->json('hobbies')->nullable(); 
        $table->json('uploaded_files')->nullable();
        $table->timestamps();
        
        $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
        $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
    });

   
        
       


        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
