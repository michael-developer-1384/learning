<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paths', function (Blueprint $table) {
            $table->id();
            $table->tenantAndCreatedBy();
            $table->string('name');
            $table->text('description');
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_mandatory')->default(false);
            $table->timestamps();
        });

        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->tenantAndCreatedBy();
            $table->string('name');
            $table->text('description');
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_mandatory')->default(false);
            $table->timestamps();
        });

        Schema::create('chapters', function (Blueprint $table) {
            $table->id();
            $table->tenantAndCreatedBy();
            $table->string('name');
            $table->text('description');
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_mandatory')->default(false);
            $table->timestamps();
        });

        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->tenantAndCreatedBy();
            $table->string('name');
            $table->text('description');
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_mandatory')->default(false);
            $table->timestamps();
        });

        Schema::create('content_type_lesson', function (Blueprint $table) {
            $table->unsignedBigInteger('content_type_id');
            $table->unsignedBigInteger('lesson_id');
            $table->timestamps();
        
            $table->foreign('content_type_id')->references('id')->on('content_types')->onDelete('cascade');
            $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('cascade');
        });


        Schema::create('path_units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('path_id');
            $table->unsignedBigInteger('unit_id');
            $table->string('unit_type');
            $table->timestamps();

            $table->index(['unit_id', 'unit_type']);
            $table->foreign('path_id')->references('id')->on('paths')->onDelete('cascade');
        });

        

        // Polymorphe Tabelle für Course
        Schema::create('path_children', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('path_id');
            $table->unsignedBigInteger('childable_id');
            $table->string('childable_type');
            $table->integer('order')->nullable();
            $table->timestamps();

            $table->index(['path_id', 'childable_id', 'childable_type']);
        });

        // Polymorphe Tabelle für Course
        Schema::create('course_children', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('childable_id');
            $table->string('childable_type');
            $table->integer('order')->nullable();
            $table->timestamps();

            $table->index(['course_id', 'childable_id', 'childable_type']);
        });

        // Polymorphe Tabelle für Chapter
        Schema::create('chapter_children', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chapter_id');
            $table->unsignedBigInteger('childable_id');
            $table->string('childable_type');
            $table->integer('order')->nullable();
            $table->timestamps();

            $table->index(['chapter_id', 'childable_id', 'childable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('path_children');
        Schema::dropIfExists('course_children');
        Schema::dropIfExists('chapter_children');
        
        Schema::dropIfExists('content_type_lesson');

        Schema::dropIfExists('paths');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('chapters');
        Schema::dropIfExists('lessons');
    }
};
