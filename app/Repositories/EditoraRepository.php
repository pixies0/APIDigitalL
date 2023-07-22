<?php

namespace App\Repositories;

use App\Models\Editora;
use App\Repositories\BaseRepository;

class EditoraRepository extends BaseRepository
{
    protected $model;

    public function __construct(Editora $model)
    {
        $this->model = $model;
    }
}
