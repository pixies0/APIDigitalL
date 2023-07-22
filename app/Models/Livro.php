<?php

namespace App\Models;

use Database\Factories\LivroFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Livro extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $fillable = ['titulo', 'nome_editora', 'editora_id'];

    protected static function newFactory(): Factory
    {
        return LivroFactory::new();
    }

    public function editora(): BelongsTo
    {
        return $this->belongsTo(Editora::class);
    }
}
