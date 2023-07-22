<?php

namespace App\Services;

use App\Repositories\EditoraRepository;
use App\Services\BaseService;

class EditoraService extends BaseService
{
    protected $repository;

    public function __construct(EditoraRepository $repository)
    {
        $this->repository = $repository;
    }
}
