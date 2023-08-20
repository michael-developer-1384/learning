<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('filters', function (Blueprint $table) {
            $table->id();
            $table->tenantAndCreatedBy();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('private')->default(true);
            $table->boolean('live')->default(false);
            $table->timestamps();
        });


        // PIVOT TYBLES


    }

    public function down(): void
    {
        Schema::dropIfExists('filters');

    }
};
