<?php

namespace App\Repositories;

use App\Models\Livro_Autor;
use App\Repositories\BaseRepository;

class LivroAutorRepository extends BaseRepository
{
    protected $model;

    public function __construct(Livro_Autor $model)
    {
        $this->model = $model;
    }
}
