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
        Schema::create('payment_bills', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('payment_id')->constrained()->onDelete('cascade');
            $table->foreignId('bill_id')->constrained()->onDelete('cascade');
            $table->decimal('amount_paid', 10, 2);
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
        Schema::dropIfExists('payment_bills');
    }
};
