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
        Schema::create('lent_books', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('school_id')->constrained('schools');
            $table->foreignId('book_id')->constrained();
            $table->morphs('lentable');
            $table->date('lent_on');
            $table->date('due_on');
            $table->date('returned_on')->nullable();
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
        Schema::dropIfExists('lent_books');
    }
};
