<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Module;


return new class extends Migration
{
    public function up(): void
    {
        
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->tenantAndCreatedBy();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name');
            $table->enum('category', Module::LEARNING_CATEGORIES)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true); 
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('modules')->onDelete('set null');
        });

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


        // PIVOT TYBLES

        Schema::create('module_module', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_module_id');
            $table->unsignedBigInteger('child_module_id');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_assigned_to_module')->default(true);
            $table->timestamps();

            $table->foreign('parent_module_id')->references('id')->on('modules');
            $table->foreign('child_module_id')->references('id')->on('modules');
        });

        Schema::create('learning_type_user', function (Blueprint $table) {
            $table->unsignedBigInteger('learning_type_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('is_assigned_to_user')->default(true); 
            $table->timestamps();
    
            $table->foreign('learning_type_id')->references('id')->on('learning_types');
            $table->foreign('user_id')->references('id')->on('users');
        });
        
        Schema::create('content_type_module', function (Blueprint $table) {
            $table->unsignedBigInteger('content_type_id');
            $table->unsignedBigInteger('module_id');
            $table->boolean('is_assigned_to_module')->default(true); 
            $table->timestamps();
        
            $table->foreign('content_type_id')->references('id')->on('content_types');
            $table->foreign('module_id')->references('id')->on('modules');
        });

        Schema::create('module_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('is_assigned_to_user')->default(false);
            $table->boolean('is_mandatory')->default(false);
            $table->date('valid_from')->nullable();
            $table->date('to_be_done_until')->nullable();
            $table->unsignedBigInteger('filter_id')->nullable();
            $table->timestamps();

            $table->foreign('filter_id')->references('id')->on('filters');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_type_module');
        Schema::dropIfExists('learning_type_user');
        Schema::dropIfExists('module_module');

        Schema::dropIfExists('content_types');
        Schema::dropIfExists('learning_types');
        Schema::dropIfExists('modules');
    }
};
