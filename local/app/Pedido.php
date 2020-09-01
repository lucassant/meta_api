<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'ped_saida_venda';
    protected $primaryKey = 'numero_controle';

    public function saidas(){
    	return $this->hasMany('App\PedidoLancto', 'numero_controle', 'numero_controle');
    }

    public function vendedor(){
    	return $this->belongsTo('App\Vendedor', 'codigo_vendedor');
    }

    public function cliente(){
    	return $this->belongsTo('App\Cliente', 'codigo_cliente');
    }

}
