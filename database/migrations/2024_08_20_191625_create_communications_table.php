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
        Schema::create('communications', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();

            $table->boolean('send_after_payment')->default(false);
            $table->boolean('send_upcoming_events')->default(false);
            $table->boolean('send_student_attendance')->default(false);

            $table->boolean('send_admission_alert')->default(false);


            $table->string('sender_id')->nullable();
            $table->string('api_key')->nullable();

            $table->timestamps();
            $table->foreignId('created_by')->nullable();
            $table->foreignId('deleted_by')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communications');
    }
};
