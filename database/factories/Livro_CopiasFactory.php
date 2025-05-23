<?php

namespace Database\Factories;

use App\Models\Livro;
use App\Models\Livro_Copia;
use App\Models\Unidade;
use Illuminate\Database\Eloquent\Factories\Factory;

class Livro_CopiasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Livro_Copia::class;
    public function definition(): array
    {
        $livro_ids = Livro::pluck('id')->toArray();
        $unidade_ids = Unidade::pluck('id')->toArray();

        $livro_id = $this->faker->randomElement($livro_ids);
        $unidade_id = $this->faker->randomElement(array_diff($unidade_ids, [$livro_id]));

        return [
            'livro_id' => $livro_id,
            'unidade_id' => $unidade_id,
            'quantidade' => $this->faker->numberBetween(0, 200)
        ];
    }
}
