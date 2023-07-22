<?php

namespace App\Models;

use Database\Factories\EditoraFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Editora extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'endereco', 'telefone'];
    protected static function newFactory(): Factory
    {
        return EditoraFactory::new();
    }

    public function livros(): HasMany
    {
        return $this->hasMany(Livro::class);
    }
}
