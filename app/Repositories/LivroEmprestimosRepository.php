<?php

namespace App\Repositories;

use App\Models\Livro_Emprestimo;
use App\Repositories\BaseRepository;

class LivroEmprestimosRepository extends BaseRepository
{
    protected $model;

    public function __construct(Livro_Emprestimo $model)
    {
        $this->model = $model;
    }

    public function create($dados): Livro_Emprestimo
    {
        return $this->model->create($dados);
    }

    public function findById(int $id): ?Livro_Emprestimo
    {
        return $this->model->where('id', $id)->first();
    }

    public function delete(int $id): bool
    {
        return $this->model->destroy($id) > 0;
    }
}
