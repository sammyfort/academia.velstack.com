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

        Schema::create('preferences', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();

            $table->boolean('show_student_image_on_report')->default(true);
            $table->boolean('show_school_image_on_report')->default(true);

            $table->boolean('show_class_average')->default(true);
            $table->boolean('show_overall_position')->default(true);
            $table->boolean('show_overall_percentage')->default(true);
            $table->boolean('show_student_attendance')->default(true);

            $table->boolean('show_staff_attendance_on_payslip')->default(false);
            $table->boolean('open_for_transfer')->default(false);

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
        Schema::dropIfExists('preferences');
    }
};
