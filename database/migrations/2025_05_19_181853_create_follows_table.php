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
        Schema::create('follows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // El usuario que sigue
            $table->foreignId('following_user_id')->constrained('users')->onDelete('cascade'); // El usuario seguido
            $table->timestamps();

            // Asegura que un usuario no pueda seguir al mismo usuario más de una vez
            $table->unique(['user_id', 'following_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follows');
    }
};