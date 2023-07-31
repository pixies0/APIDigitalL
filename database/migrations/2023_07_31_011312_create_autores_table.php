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

        Schema::create('autores', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });

        Schema::create('livro_autores', function (Blueprint $table) {
            $table->id();
            $table->string('nome_livro');
            $table->unsignedBigInteger('autor_id');
            $table->foreign('autor_id')->references('id')->on('autores')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('livro_autores', function (Blueprint $table) {
            $table->dropForeign(['autor_id']);
            $table->dropColumn('autor_id');
        });

        Schema::dropIfExists('autores');
    }
};
