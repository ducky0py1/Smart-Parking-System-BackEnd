<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('parking_spots', function (Blueprint $table) {
        $table->id();
        $table->string('label'); // Ex: A-01
        // Enum: free, reserved, occupied
        $table->enum('status', ['free', 'reserved', 'occupied'])->default('free');
        // Prix avec précision pour la crypto (10 chiffres, 5 après la virgule)
        $table->decimal('price', 10, 5); 
        $table->string('sensor_id')->nullable(); // ID du Pico
        $table->timestamps();
    });
}


    /**
     * Run the migrations.
     */
    // public function up(): void
    // {
    //     Schema::create('parking_spots', function (Blueprint $table) {
    //         $table->id();
    //         $table->timestamps();
    //     });
    // }

    // /**
    //  * Reverse the migrations.
    //  */
    // public function down(): void
    // {
    //     Schema::dropIfExists('parking_spots');
    // }
};
