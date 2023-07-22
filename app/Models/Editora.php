<?php

namespace App\Models;

use Database\Factories\EditoraFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Editora extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'endereco', 'telefone'];
    protected static function newFactory(): Factory
    {
        return EditoraFactory::new();
    }
}
