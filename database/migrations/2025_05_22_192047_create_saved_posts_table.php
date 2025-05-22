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
        Schema::create('saved_posts', function (Blueprint $table) {
            $table->id(); // ID primario de la tabla
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Clave foránea del usuario
            $table->foreignId('post_id')->constrained()->onDelete('cascade'); // Clave foránea de la publicación
            $table->timestamps(); // Columnas created_at y updated_at

            // Asegura que un usuario no pueda guardar la misma publicación dos veces
            $table->unique(['user_id', 'post_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saved_posts');
    }
};