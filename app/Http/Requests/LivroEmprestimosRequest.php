<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LivroEmprestimosRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'livro_id' => ['required', 'exists:livros,id'],
            'unidade_id' => ['required', 'exists:unidades,id'],
            'usuario_id' => ['required', 'exists:users,id'],
            'data_emprestimo' => ['required', 'date'],
            'data_devolucao' => ['nullable', 'date', 'after_or_equal:data_emprestimo'],
        ];
    }
}
