<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimentacao extends Model
{
    public $timestamps = false;
    protected $table = 'movimentacoes';
    protected $fillable = [
        'cooperativa',
        'agencia',
        'conta',
        'nome',
        'documento',
        'codigo',
        'descricao',
        'debito',
        'credito',
        'data_hora',
    ];

}
