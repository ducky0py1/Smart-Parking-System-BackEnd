<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
{
    Schema::create('reservations', function (Blueprint $table) {
        $table->id();
        
        // Clés étrangères vers users et parking_spots
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('parking_spot_id')->constrained('parking_spots')->onDelete('cascade');
        
        $table->string('transaction_hash'); // Preuve Blockchain
        
        $table->dateTime('start_time');
        $table->dateTime('end_time')->nullable();
        $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
        
        $table->timestamps();
    });
}

    // /**
    //  * Run the migrations.
    //  */
    // public function up(): void
    // {
    //     Schema::create('reservations', function (Blueprint $table) {
    //         $table->id();
    //         $table->timestamps();
    //     });
    // }

    // /**
    //  * Reverse the migrations.
    //  */
    // public function down(): void
    // {
    //     Schema::dropIfExists('reservations');
    // }
};
