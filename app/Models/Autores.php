<?php

namespace App\Models;

use Database\Factories\AutoresFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Autores extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected static function newFactory(): Factory
    {
        return AutoresFactory::new();
    }

    public function livro(): HasMany
    {
        return $this->hasMany(Livro::class);
    }
}
