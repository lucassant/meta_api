<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Vendedor;
use App\Pedido;

class VendedorController extends Controller
{
    public function index($dataInicial, $dataFinal, $codSupervisor){    	

    	$pedidos = function($q) use($dataInicial, $dataFinal){
    		$q->join('cliente', 'ped_saida_venda.codigo_cliente', '=', 'cliente.codigo');
    		$q->select('cliente.razao_social', 'cliente.codigo', 'ped_saida_venda.codigo_vendedor', 'ped_saida_venda.numero_controle', 'ped_saida_venda.data_pedido', 'ped_saida_venda.valor_pedido');
    		$q->where('data_pedido', '>=', $dataInicial);
    		$q->where('data_pedido', '<=', $dataFinal);
    		$q->orderBy('data_pedido', 'desc');
    	};

    	$retorno = Vendedor::where('codigo_supervisor', '=', $codSupervisor)    	
    	->whereHas('pedidos', $pedidos)
    	->with(['pedidos' => $pedidos])
    	->orderBy('nome', 'asc')
    	->get();

    	return $retorno;
    }

    public function retornaTotais($dataInicial, $dataFinal){
        
        $retorno = DB::select('SELECT 
                                    COALESCE(SUM(sv.valor_total_nota), 0) AS total, sv.codigo_vendedor,  ve.nome
                                FROM 
                                    saida_venda sv 
                                    INNER JOIN vendedor ve ON sv.codigo_vendedor = ve.codigo
                                WHERE 
                                    sv.codigo_vendedor IN (SELECT codigo FROM vendedor WHERE ativo = -1) 
                                    AND sv.data_emissao >= \'' . $dataInicial . '\' AND sv.data_emissao <= \' ' . $dataFinal . ' \' GROUP BY sv.codigo_vendedor, ve.nome, ve.codigo ORDER BY total DESC');

        return $retorno;
    }
}
