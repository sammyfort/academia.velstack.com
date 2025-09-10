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
        Schema::create('terms', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('name');
            $table->string('status');
            $table->boolean('reporting')->default(false)->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('days');
            $table->date('next_term_begins');
            $table->unique(['school_id', 'name']);
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
        Schema::dropIfExists('terms');
    }
};
