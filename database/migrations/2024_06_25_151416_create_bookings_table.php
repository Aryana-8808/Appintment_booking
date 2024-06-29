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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->dateTime('appointment_date');
            $table->integer('duration'); // Duration in minutes
            $table->string('phone');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending'); // Define enum values here
            $table->dateTime('end_time')->nullable()->default(null); // Added end_time column with default value of NULL
            $table->timestamps();
            $table->unique(['appointment_date', 'end_time']);
            $table->unique(['user_id', 'appointment_date']);   // Ensure a user can only have one appointment per day
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
