<?php

namespace Database\Factories;

use App\Models\Editora;
use App\Models\Livro;
use Illuminate\Database\Eloquent\Factories\Factory;

class LivroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Livro::class;
    public function definition(): array
    {
        $editora = Editora::inRandomOrder()->first();
        return [
            'titulo' => $this->faker->sentence(),
            'editora_id' => $editora->id,
            'nome_editora' => $editora->nome
        ];
    }
}
