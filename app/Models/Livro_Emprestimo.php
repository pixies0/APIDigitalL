<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Database\Factories\LivroEmprestimoFactory;

class Livro_Emprestimo extends Model
{
    use HasFactory;
    protected $table = 'livro_emprestimos';

     protected $guarded = [];

     protected $fillable = [
        'livro_id',
        'unidade_id',
        'usuario_id',
        'data_emprestimo',
        'data_devolucao',
     ];

     protected $hidden = ['created_at', 'updated_at'];

         protected static function newFactory(): Factory
    {
        return LivroEmprestimoFactory::new();
    }
}
