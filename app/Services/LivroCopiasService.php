<?php

namespace App\Services;

use App\Repositories\LivroCopiasRepository;
use App\Services\BaseService;

class LivroCopiasService extends BaseService
{
    protected $repository;

    public function __construct(LivroCopiasRepository $repository)
    {
        $this->repository = $repository;
    }
}
