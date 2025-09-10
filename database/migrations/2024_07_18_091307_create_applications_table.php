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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('school_name');
            $table->string('region');
            $table->string('district');
            $table->string('physical_address');
            $table->string('digital_address');
            $table->string('postal_address');
            $table->string('tel_number');
            $table->string('email_address');
            $table->date('date_established');
            $table->float('est_revenue');
            $table->string('est_students');
            $table->string('est_staff');
            $table->string('applicant');
            $table->string('applicant_position');
            $table->string('applicant_phone');
            $table->string('applicant_email');
            $table->string('status');
            $table->foreignId('created_by')->nullable();
            $table->foreignId('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
