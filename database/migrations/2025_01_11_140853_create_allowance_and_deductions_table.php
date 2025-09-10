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
        Schema::create('allowance_and_deductions', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->string('name');
            $table->string('calculation_type');
            $table->decimal('amount', 10, 2);
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
        Schema::dropIfExists('allowance_and_deductions');
    }
};
