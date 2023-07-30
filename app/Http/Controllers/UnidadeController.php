<?php

namespace App\Http\Controllers;

use App\Http\Requests\UnidadeRequest;
use App\Services\UnidadeService;
use Illuminate\Http\Request;

class UnidadeController extends Controller
{

    protected $service;

    public function __construct(UnidadeService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $result = $this->service->findAll();
        return response()->json($result);
    }

    public function store(UnidadeRequest $request)
    {
        $validated = $request->all();
        $result = $this->service->create($validated);
        return response()->json(['message' => 'Registro Inserido', 'result' => $result]);
    }

    public function show(int $id)
    {
        $result = $this->service->find($id);
        return response()->json($result);
    }

    public function update(UnidadeRequest $request, int $id)
    {
        $validated = $request->all();
        $result = $this->service->update($id, $validated);
        return response()->json(['message' => 'Registro Atualizado', 'result' => $result]);
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);
        return response()->json(['message' => 'Registro Excluído']);
    }
}