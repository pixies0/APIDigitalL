<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

use App\Repositories\LivroCopiasRepository;
use App\Repositories\LivroEmprestimosRepository;
use App\Services\BaseService;


class LivroEmprestimosService extends BaseService
{
    protected $repository;
    protected $livroCopiarepository;

    public function __construct(LivroEmprestimosRepository $repository, LivroCopiasRepository $livroCopia)
    {
        $this->repository = $repository;
        $this->livroCopiarepository = $livroCopia;
    }

    public function create(array $dados, array $relations = [])
    {
        return DB::transaction(function () use ($dados) {
            $this->livroCopiarepository->decrementQuantidade($dados['livro_id'], $dados['unidade_id']);
            return $this->repository->create($dados);
        });
    }

    public function delete(int $id, array $relationsCount = [])
    {
        return DB::transaction(function () use ($id) {
            $emprestimo = $this->repository->findById($id);

            if (!$emprestimo) {
                throw new \Exception('Empréstimo não encontrado');
            }

            $this->livroCopiarepository->incrementQuantidade($emprestimo->livro_id, $emprestimo->unidade_id);
            return $this->repository->delete($id);
        });
    }


}
