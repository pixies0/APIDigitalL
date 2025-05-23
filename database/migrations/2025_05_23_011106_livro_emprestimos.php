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
        Schema::create('livro_emprestimos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('livro_id')->references('id')->on('livros')->onDelete('cascade');
            $table->foreignId('unidade_id')->references('id')->on('unidades')->onDelete('cascade');
            $table->foreignId('usuario_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('data_emprestimo');
            $table->date('data_devolucao')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livro_emprestimos');
    }
};
