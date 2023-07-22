<?php

namespace App\Repositories;

use App\Models\Livro;
use App\Repositories\BaseRepository;

class LivroRepository extends BaseRepository
{
    protected $model;

    public function __construct(Livro $model)
    {
        $this->model = $model;
    }
}
