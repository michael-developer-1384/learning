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
        Schema::create('imported_users', function (Blueprint $table) {
            $table->id();
            $table->string('import_id'); // Identische ID für alle Einträge dieses Imports
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->bigInteger('phone')->nullable();
            $table->text('address')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('company_name')->nullable();
            $table->text('role_name')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('role_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('tenant_id')->nullable()->default(1);
            $table->enum('test_result', ['new', 'missing', 'updated', 'invalid'])->nullable(); // Testergebnis
            $table->text('test_result_description')->nullable();
            $table->enum('user_action', ['accepted', 'rejected', 'pending'])->default('pending'); // Benutzerinteraktion
            $table->timestamps();

            // Fremdschlüsselbeziehungen, falls benötigt
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('set null');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imported_users');
    }
};
