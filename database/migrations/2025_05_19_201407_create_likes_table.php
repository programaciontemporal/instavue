<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // El usuario que da 'me gusta'
            $table->foreignId('post_id')->constrained()->onDelete('cascade'); // La publicación que recibe 'me gusta'
            $table->timestamps();

            // Asegura que un usuario no pueda dar 'me gusta' a la misma publicación más de una vez
            $table->unique(['user_id', 'post_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};