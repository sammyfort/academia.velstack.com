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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('previous_school_id')->nullable()->constrained('schools');
            $table->foreignId('class_id')->constrained('classrooms');
            $table->foreignId('transportation_id')->nullable()->constrained()->nullOnDelete();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('index_number')->unique();


            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('gender')->nullable();
            $table->dateTime('dob')->nullable();
            $table->string('religion')->nullable();
            $table->string('region')->nullable();
            $table->string('city')->nullable();
            $table->string('bio')->nullable();
            $table->string('allergies')->nullable();

            $table->string('status')->nullable();

            $table->text('fingerprint_hash')->nullable();
            $table->text('suspended_reason')->nullable();



            $table->string('password');
            $table->foreignId('created_by')->nullable();
            $table->foreignId('deleted_by')->nullable();
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
