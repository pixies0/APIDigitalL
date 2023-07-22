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
        Schema::create('livros', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->unsignedBigInteger('editora_id')->foreing()->references('id')->on('editoras');
            $table->string('nome_editora')->foreign()->references('nome')->on('editoras')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('livros', function (Blueprint $table) {
        //     $table->dropForeign(['editora_id']);
        //     $table->dropColumn('editora_id');
        // });
        Schema::dropIfExists('livros');
    }
};
