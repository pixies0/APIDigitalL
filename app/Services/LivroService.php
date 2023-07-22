<?php

namespace App\Services;

use App\Models\Editora;
use App\Exceptions\AppError;
use App\Repositories\LivroRepository;
use App\Services\BaseService;
use Illuminate\Validation\ValidationException;

class LivroService extends BaseService
{
    protected $repository;

    public function __construct(LivroRepository $repository)
    {
        $this->repository = $repository;
    }

    public function validEditora(array $dados): array
    {
        if (isset($dados['nome_editora'])) {

            $editora = Editora::where('nome', $dados['nome_editora'])->first();
            if (!$editora) {
                throw ValidationException::withMessages(['nome_editora' => 'Editora não existe']);
            }

            $dados['editora'] = $editora;
            $dados['editora_id'] = $editora->id;
        }
        return $dados;
    }
    public function beforeCreate(array $dados): array
    {
        return $this->validEditora($dados);
    }

    public function beforeUpdate(array $dados, int $id): array
    {
        return $this->validEditora($dados);
    }
}
