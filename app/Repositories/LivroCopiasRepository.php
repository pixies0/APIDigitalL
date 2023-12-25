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
}
