<?php

namespace App\Services;

use App\Repositories\LivroEmprestimosRepository;
use App\Services\BaseService;

class LivroEmprestimosService extends BaseService
{
    protected $repository;

    public function __construct(LivroEmprestimosRepository $repository)
    {
        $this->repository = $repository;
    }
}
