<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

use App\Models\Livro_Emprestimo;
use App\Models\Livro_Copia;
use App\Models\Unidade;
use App\Models\Livro;
use App\Models\User;

class LivroEmprestimoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Livro_Emprestimo::class;


    public function definition(): array
    {
        $livroCopia = Livro_Copia::where('quantidade', '>', 0)->inRandomOrder()->first();

        if (!$livroCopia) {
            throw new \Exception('Não há cópias disponíveis para empréstimo.');
        }

        $dataEmprestimo = $this->faker->dateTimeBetween('-30 days', 'now');
        $dataDevolucao = (clone $dataEmprestimo)->modify('+7 days');

        return [
            'livro_id' => $livroCopia->livro_id,
            'unidade_id' => $livroCopia->unidade_id,
            'usuario_id' => User::inRandomOrder()->first()->id,
            'data_emprestimo' => Carbon::instance($dataEmprestimo)->toDateString(),
            'data_devolucao' => Carbon::instance($dataDevolucao)->toDateString(),
        ];
    }
}
