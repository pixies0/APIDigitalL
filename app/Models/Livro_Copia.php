<?php

namespace App\Models;

use Database\Factories\Livro_CopiasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livro_Copia extends Model
{
    use HasFactory;
    protected $table = 'livro_copias'; // Adicione esta linha ao seu modelo

     protected $guarded = [];

     protected $fillable = [];

     protected $hidden = ['created_at', 'updated_at'];

         protected static function newFactory(): Factory
    {
        return Livro_CopiasFactory::new();
    }
}
