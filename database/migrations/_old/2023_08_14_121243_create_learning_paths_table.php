<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_paths', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('created_by_user')->nullable();
            $table->string('name');
            $table->text('description');
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_mandatory')->default(false);
            $table->timestamps();
        
            $table->foreign('tenant_id')->references('id')->on('tenants');
            $table->foreign('created_by_user')->references('id')->on('users');
        });

        Schema::create('course_learning_path', function (Blueprint $table) {
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('learning_path_id');

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('learning_path_id')->references('id')->on('learning_paths')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_learning_path');
        Schema::dropIfExists('learning_paths');
    }
};
