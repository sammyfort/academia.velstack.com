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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('phone2');
            $table->string('region');
            $table->string('district');
            $table->string('town');
            $table->string('postal_address');
            $table->string('gps');
            $table->string('favicon')->nullable();
            $table->string('cover_image')->nullable();

            $table->boolean('suspended')->default(false);
            $table->text('suspended_reason')->nullable();

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
        Schema::dropIfExists('schools');
    }
};
