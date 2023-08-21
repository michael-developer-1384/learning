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

        Schema::create('filter_criteria', function (Blueprint $table) {
            $table->id();
            $table->tenantAndCreatedBy();
            $table->foreignId('filter_id')->constrained()->onDelete('cascade');
            $table->enum('model', ['User', 'Company', 'Department', 'LearningType', 'Position', 'Role']); // z.B. 'User', 'Post', etc.
            $table->string('column');
            $table->enum('operator', ['=', '!=', '<', '>', '<=', '>=', 'LIKE', 'NOT LIKE', 'IN', 'NOT IN']); // Liste der verfügbaren Operatoren
            $table->string('value'); // Der Wert, gegen den gefiltert wird
            $table->enum('chain_operator', ['AND', 'OR'])->nullable(); // Verkettungsoperator für das nächste Kriterium
            $table->integer('sort_order')->default(0); // Um die Reihenfolge der Kriterien zu bestimmen
            $table->boolean('group_start')->default(false); // Beginn einer Klammergruppe
            $table->boolean('group_end')->default(false); // Ende einer Klammergruppe
            $table->timestamps();
        });

        // PIVOT TYBLES


    }

    public function down(): void
    {
        Schema::dropIfExists('filter_criteria');
        Schema::dropIfExists('filters');
    }
};
