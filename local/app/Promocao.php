<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promocao extends Model
{
    protected $table = 'promocao';
    protected $primaryKey = 'numero_controle';

    public function lancamentos(){
    	return $this->hasMany('App\PromocaoLancto', 'numero_controle');
    }
}
