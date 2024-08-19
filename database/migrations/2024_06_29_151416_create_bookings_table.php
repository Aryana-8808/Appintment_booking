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
            $table->foreignId('slot_id')->constrained('appointment_slots')->onDelete('cascade');
            $table->string('name');
            $table->dateTime('appointment_date');
            $table->integer('duration'); // Duration in minutes
            $table->string('phone');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->boolean('waitlist')->default(false);
            $table->dateTime('end_time')->nullable()->default(null);
            $table->timestamps();
            
            // Unique constraints
            $table->unique(['user_id', 'appointment_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};

