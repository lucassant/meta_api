<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PedidoLancto extends Model
{
    protected $table = 'ped_saida_venda_lanctos';  
    

    public function pedido(){
    	return $this->belongsTo('App\Pedido');
    }
}
