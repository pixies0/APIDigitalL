<?php

namespace App\Services;

use App\Repositories\LivroAutorRepository;
use App\Services\BaseService;

class LivroAutorService extends BaseService
{
    protected $repository;

    public function __construct(LivroAutorRepository $repository)
    {
        $this->repository = $repository;
    }
}
