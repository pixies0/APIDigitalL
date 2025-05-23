<?php

namespace Database\Factories;

use App\Models\Unidade;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnidadeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Unidade::class;
    public function definition(): array
    {
        return [
            'nome' => $this->faker->domainWord(),
            'endereco' => $this->faker->address(),
        ];
    }
}
