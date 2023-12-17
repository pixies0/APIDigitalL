<?php

namespace Database\Factories;

use App\Models\Autores;
use App\Models\Livro;
use App\Models\Livro_Autor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Livro_Autor>
 */
class Livro_AutorFactory extends Factory
{

    protected $model = Livro_Autor::class;

    public function definition(): array
    {

        $autor = Autores::inRandomOrder()->first();
        return [
            'nome_livro' => $this->faker->words(4, true),
            'autor_id' => $autor->id
        ];
    }
}
