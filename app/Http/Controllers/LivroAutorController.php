<?php

namespace App\Http\Controllers;

use App\Http\Requests\LivroAutorRequest;
use App\Services\LivroAutorService;
use Illuminate\Http\Request;

class LivroAutorController extends Controller
{

    protected $service;

    public function __construct(LivroAutorService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $result = $this->service->findAll();
        return response()->json($result);
    }

    public function store(LivroAutorRequest $request)
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

    public function update(LivroAutorRequest $request, int $id)
    {
        $validated = $request->validated();
        $result = $this->service->update($id, $validated);
        return response()->json(['message' => 'Registro Atualizado', 'result' => $result]);
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);
        return response()->json(['message' => 'Registro Excluído']);
    }
}
