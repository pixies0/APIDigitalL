<?php

namespace App\Http\Controllers;

use App\Services\EditoraService;
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

    public function store(Request $request)
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

    public function update(Request $request, int $id)
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
