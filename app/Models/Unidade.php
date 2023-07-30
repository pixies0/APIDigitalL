<?php

namespace App\Models;

use Database\Factories\UnidadeFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidade extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $fillable = ['nome', 'endereco'];

    protected $hidden = ['created_at', 'updated_at'];

    protected static function newFactory(): Factory
    {
        return UnidadeFactory::new();
    }
}
