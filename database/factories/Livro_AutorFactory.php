<?php

namespace Database\Factories;

use App\Models\Autores;
use App\Models\Livro;
use App\Models\Livro_Autor;
use Illuminate\Database\Eloquent\Factories\Factory;

class Livro_AutorFactory extends Factory
{

    protected $model = Livro_Autor::class;

    public function definition(): array
    {

        $livro = Livro::inRandomOrder()->first();
        $autor = Autores::inRandomOrder()->first();
        return [
            'livro_id' => $livro->id,
            'livro_titulo' => $livro->titulo,
            'autor_id' => $autor->id
        ];
    }
}
