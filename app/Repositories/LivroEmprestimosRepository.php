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
}
