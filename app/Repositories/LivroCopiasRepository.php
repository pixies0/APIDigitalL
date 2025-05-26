<?php

namespace App\Repositories;

use App\Models\Livro_Copia;
use App\Repositories\BaseRepository;

class LivroCopiasRepository extends BaseRepository
{
    protected $model;

    public function __construct(Livro_Copia $model)
    {
        $this->model = $model;
    }

     public function findByLivroAndUnidade(int $livroId, int $unidadeId): ?Livro_Copia
    {
        return $this->model->where('livro_id', $livroId)
                           ->where('unidade_id', $unidadeId)
                           ->first();
    }

    public function decrementQuantidade(int $livroId, int $unidadeId): void
    {
        $copia = $this->findByLivroAndUnidade($livroId, $unidadeId);

        if (!$copia || $copia->quantidade < 1) {
            throw new \Exception('Não há cópias disponíveis para este livro nesta unidade.');
        }

        $copia->decrement('quantidade');
    }

    public function incrementQuantidade(int $livroId, int $unidadeId): void
    {
        $copia = $this->findByLivroAndUnidade($livroId, $unidadeId);

        if (!$copia) {
            throw new \Exception('Cópia não encontrada para este livro nesta unidade.');
        }

        $copia->increment('quantidade');
    }
}
