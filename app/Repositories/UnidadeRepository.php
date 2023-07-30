<?php

namespace App\Repositories;

use App\Models\Unidade;
use App\Repositories\BaseRepository;

class UnidadeRepository extends BaseRepository
{
    protected $model;

    public function __construct(Unidade $model)
    {
        $this->model = $model;
    }
}
