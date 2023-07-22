<?php

namespace App\Http\Controllers;

use App\Http\Requests\LivroRequest;
use App\Services\LivroService;
use Illuminate\Http\Request;

class LivroController extends Controller
{

    protected $service;

    public function __construct(LivroService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $result = $this->service->findAll();
        return response()->json($result);
    }

    public function store(LivroRequest $request)
    {
        $validated = $request->all();
        $result = $this->service->advancedCreate($validated);
        return response()->json(['message' => 'Registro Inserido', 'result' => $result]);
    }

    public function show(int $id)
    {
        $result = $this->service->find($id);
        return response()->json($result);
    }

    public function update(LivroRequest $request, int $id)
    {
        $validated = $request->all();
        $result = $this->service->advancedUpdate($id, $validated);
        return response()->json(['message' => 'Registro Atualizado', 'result' => $result]);
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);
        return response()->json(['message' => 'Registro Excluído']);
    }
}
