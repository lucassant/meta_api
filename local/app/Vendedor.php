<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    protected $table = 'vendedor';
    protected $primaryKey = 'codigo';

    protected $visible = ['codigo', 'nome', 'pedidos'];

    public function pedidos(){
    	return $this->hasMany('App\Pedido', 'codigo_vendedor', 'codigo');
    }
}
