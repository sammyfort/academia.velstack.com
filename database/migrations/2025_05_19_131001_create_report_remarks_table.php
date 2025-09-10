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
        Schema::create('report_remarks', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('school_id')->constrained();
            $table->foreignId('student_id')->constrained();
            $table->foreignId('term_id')->constrained();
            $table->foreignId('class_id')->constrained('classrooms');

            $table->string('interest')->nullable();
            $table->string('attitude')->nullable();
            $table->string('conduct')->nullable();
            $table->string('remarks')->nullable();

            $table->unique(['school_id', 'student_id', 'term_id', 'class_id'], 'report_remarks_unique');

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
        Schema::dropIfExists('report_remarks');
    }
};
