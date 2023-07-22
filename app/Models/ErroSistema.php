<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErroSistema extends Model
{
    protected $table = 'erros_sistema';
    protected $primaryKey = 'id';

    protected $fillable = [
        'mensagem',
        'erro'
    ];
}
