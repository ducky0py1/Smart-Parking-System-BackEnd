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
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        // Le Wallet est obligatoire
        $table->string('wallet_address')->unique();
        
        // Les infos de profil sont optionnelles (nullable)
        $table->string('first_name')->nullable();
        $table->string('last_name')->nullable();
        $table->string('email')->nullable()->unique();
        
        $table->timestamps();
    });
}
};
