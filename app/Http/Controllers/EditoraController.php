<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditoraRequest;
use App\Services\EditoraService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class EditoraController extends Controller
{

    protected $service;

    public function __construct(EditoraService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $result = $this->service->findAll();
        return response()->json($result);
    }

    public function store(EditoraRequest $request)
    {
        $validated = $request->validated();
        $result = $this->service->create($validated);
        return response()->json(['message' => 'Registro Inserido', 'result' => $result]);
    }

    public function show(int $id)
    {
        $result = $this->service->find($id);
        return response()->json($result);
    }

    public function update(EditoraRequest $request, int $id)
    {
        $validated = $request->validated();
        $result = $this->service->update($id, $validated);
        return response()->json(['message' => 'Registro Atualizado', 'result' => $result]);
    }

    public function destroy(int $id)
    {
        try {
            $editora = $this->service->find($id);

            if ($editora->livros()->exists()) {
                return response()->json(['message' => 'Não é possivel remover editora, pois existe livros associados a ela']);
            }
            $this->service->delete($id, ['livros']);
            return response()->json(['message' => 'Registro Excluído']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Editora não existe']);
        }
    }
}
