<?php

namespace App\Models;

use Database\Factories\Livro_AutorFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Livro_Autor extends Model
{
    use HasFactory;

    protected $table = 'livro_autores';
    protected $guarded = [];

    protected $fillable = ['livro_id', 'nome_autor'];

    protected $hidden = ['created_at', 'updated_at'];

    protected static function newFactory(): Factory
    {
        return Livro_AutorFactory::new();
    }
}
