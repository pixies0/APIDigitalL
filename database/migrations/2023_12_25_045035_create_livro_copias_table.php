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
        Schema::create('livro_copias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('livro_id')->references('id')->on('livros')->onDelete('cascade');
            $table->foreignId('unidade_id')->references('id')->on('unidades')->onDelete('cascade');
            $table->integer('quantidade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livro_copias');
    }
};
