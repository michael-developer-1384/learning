<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->text('description')->nullable(); 
            $table->boolean('is_active')->default(true); 
            $table->timestamps();
        });

        Schema::create('content_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->text('description')->nullable(); 
            $table->boolean('is_active')->default(true); 
            $table->timestamps();
        });

        Schema::create('learning_type_user', function (Blueprint $table) {
            $table->unsignedBigInteger('learning_type_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
    
            $table->foreign('learning_type_id')->references('id')->on('learning_types')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('content_types');
        Schema::dropIfExists('learning_type_user');
    }
};
