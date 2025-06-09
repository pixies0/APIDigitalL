<?php

namespace App\Services;

use App\Repositories\EditoraRepository;
use App\Services\BaseService;
use App\Exceptions\AppError; // Presumindo que você tenha essa classe de exceção personalizada

class EditoraService extends BaseService
{
    protected $repository; // Declare a propriedade aqui se ela não for declarada em BaseService.

    public function __construct(EditoraRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createEditora(array $data)
    {

        if (empty($data['nome'])) {
            throw new AppError('O nome da editora é obrigatório.', 400);
        } else if(empty($data['endereco'])) {
            throw new AppError('O endereço da editora é obrigatório.', 400);
        } else if(empty($data['telefone'])) {
            throw new AppError('O telefone da editora é obrigatório.', 400);
        }

        return $this->repository->create($data);
    }

    public function getEditoraById(int $id)
    {

        return $this->repository->findByKey(key: 'id', find: $id, exists: true);
    }

    public function getEditoraByName(string $name)
    {
        return $this->repository->findByKey(key: 'nome', find: $name, exists: true);
    }

    public function updateEditora(int $id, array $data)
    {
        $editora = $this->getEditoraById($id);

        if (!$editora) {
            throw new AppError('Editora não encontrada.', 404);
        }

        $editora->fill($data);
        $editora->save();

        return $editora;
    }

    public function deleteEditora(int $id)
    {
        $editora = $this->getEditoraById($id);

        if (!$editora) {
            throw new AppError('Editora não encontrada.', 404);
        }

        return $this->repository->delete($id);
    }

    public function getAllEditoras()
    {
        return $this->repository->findAll();
    }

    public function searchEditorasByName(string $name)
    {
        return $this->repository->findByKey(key: 'nome', find: $name, exists: false);
    }

    public function editoraExists(int $id): bool
    {
        return $this->repository->getModel()->where('id', $id)->exists();
    }

    public function editoraNameExists(string $name): bool
    {
        return $this->repository->getModel()->where('nome', $name)->exists();
    }

}
