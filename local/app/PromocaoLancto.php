<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromocaoLancto extends Model
{
    protected $table = 'promocao_lanctos';

    protected $hidden = ['numero_controle'];

    public function promocao(){
    	return $this->belongsTo('App\Promocao');
    }
}
