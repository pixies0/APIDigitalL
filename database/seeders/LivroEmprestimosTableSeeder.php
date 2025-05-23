<?php

namespace Database\Seeders;

use App\Models\Livro_Copia;
use App\Models\Livro_Emprestimo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LivroEmprestimosTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $quantidadeEmprestimos = 20;

            for ($i = 0; $i < $quantidadeEmprestimos; $i++) {
                $livroCopia = Livro_Copia::where('quantidade', '>', 0)->inRandomOrder()->first();

                if (!$livroCopia) {
                    Log::warning('Não há mais cópias disponíveis para empréstimo.');
                    break;
                }

                $emprestimo = Livro_Emprestimo::factory()->create([
                    'livro_id' => $livroCopia->livro_id,
                    'unidade_id' => $livroCopia->unidade_id,
                ]);

                $livroCopia->decrement('quantidade');

                Log::info("Empréstimo criado para livro_id: {$livroCopia->livro_id} na unidade_id: {$livroCopia->unidade_id}");
            }
        });
    }
}
