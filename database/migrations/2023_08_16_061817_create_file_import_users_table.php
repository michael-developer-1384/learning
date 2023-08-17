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
        Schema::create('file_import_users', function (Blueprint $table) {
            $table->id();
            $table->string('file_import_id'); 
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->bigInteger('phone')->nullable();
            $table->text('address')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('company_name')->nullable();
            $table->text('role_names')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('role_ids')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('tenant_id')->nullable()->default(1);
            $table->enum('test_result', ['new', 'missing', 'updated', 'invalid'])->nullable(); // Testergebnis
            $table->text('test_result_description')->nullable(); // Testergebnis Beschreibung
            $table->enum('user_action', ['accepted', 'rejected', 'pending'])->default('pending'); // Benutzerinteraktion
            $table->timestamps();

            // Fremdschlüsselbeziehungen, falls benötigt
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('set null');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_import_users');
    }
};