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
        Schema::table('services', function (Blueprint $table) {
            $table->foreignId('country_id')->nullable();
        });
        Schema::table('user_jobs', function (Blueprint $table) {
            $table->foreignId('country_id')->nullable();
        });
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('country_id')->nullable();
        });
        Schema::table('signboards', function (Blueprint $table) {
            $table->foreignId('country_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('existing_tables', function (Blueprint $table) {
            //
        });
    }
};
