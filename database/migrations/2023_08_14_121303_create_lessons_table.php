<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chapter_id');
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('content_type_id');
            $table->unsignedBigInteger('created_by_user')->nullable();
            $table->string('name');
            $table->text('description');
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_mandatory')->default(false);
            $table->unsignedBigInteger('previous_lesson_id')->nullable();
            $table->boolean('must_complete_previous')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        
            $table->foreign('chapter_id')->references('id')->on('chapters');
            $table->foreign('content_type_id')->references('id')->on('content_types');
            $table->foreign('previous_lesson_id')->references('id')->on('lessons');
            $table->foreign('created_by_user')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
