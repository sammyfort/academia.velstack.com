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
        Schema::table('signboards', function (Blueprint $table) {
            $table->dropForeign(['business_id']);
            $table->renameColumn('business_id', 'service_id');
        });

        Schema::table('signboards', function (Blueprint $table) {
            $table->foreign('service_id')
                ->references('id')
                ->on('services')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('signboards', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->renameColumn('service_id', 'business_id');
        });

        Schema::table('signboards', function (Blueprint $table) {
            $table->foreign('business_id')
                ->references('id')
                ->on('businesses')
                ->onDelete('cascade');
        });
    }
};
