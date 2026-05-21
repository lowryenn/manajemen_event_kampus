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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->string('type', 50)->default('offline'); // online/offline
            $table->string('location', 255);
            $table->dateTime('date')->nullable();
            $table->integer('price')->default(0);
            $table->integer('quota')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
