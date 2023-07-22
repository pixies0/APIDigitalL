<?php

namespace Database\Factories;

use App\Models\Editora;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Editora>
 */
class EditoraFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Editora::class;

    public function definition()
    {
        return [
            'nome' => $this->faker->word(),
            'endereco' => $this->faker->address(),
            'telefone' => $this->faker->phoneNumber(),
        ];
    }
}
