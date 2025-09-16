<?php

use App\Models\School;
use App\Models\Staff;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('email')->nullable()->unique();
            $table->string('phone')->unique();
            $table->string('staff_id')->unique();
            $table->string('password')->nullable();
            $table->decimal('basic_salary', 10, 2);
            $table->string('designation');
            $table->string('national_id');

            $table->string('gender');
            $table->string('bio')->nullable();
            $table->date('dob');
            $table->string('religion');
            $table->string('region');
            $table->string('city');
            $table->string('marital_status');
            $table->string('licence_no')->nullable()->unique();
            $table->string('qualification');
            $table->string('experience');


            $table->string('status')->nullable();
            $table->text('fingerprint_hash')->nullable();

            $table->text('suspended_reason')->nullable();

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
        Schema::dropIfExists('staff');
    }
};
