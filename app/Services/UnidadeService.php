<?php

namespace App\Services;

use App\Repositories\UnidadeRepository;
use App\Services\BaseService;

class UnidadeService extends BaseService
{
    protected $repository;

    public function __construct(UnidadeRepository $repository)
    {
        $this->repository = $repository;
    }
}
