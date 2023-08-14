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
        Schema::table('companies', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_user')->nullable()->after('id');
            $table->foreign('created_by_user')->references('id')->on('users');
        });
    
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_user')->nullable()->after('id');
            $table->foreign('created_by_user')->references('id')->on('users');
        });

        Schema::table('imported_users', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_user')->nullable()->after('id');
            $table->foreign('created_by_user')->references('id')->on('users');
        });
    }
    
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(['created_by_user']);
            $table->dropColumn('created_by_user');
        });
    
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['created_by_user']);
            $table->dropColumn('created_by_user');
        });
        
        Schema::table('imported_users', function (Blueprint $table) {
            $table->dropForeign(['created_by_user']);
            $table->dropColumn('created_by_user');
        });
    }  
};
