<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\PedidoFaturado;
use App\Pedido;
use App\PedidoLancto;

class PedidosController extends Controller
{
    public function index($dataIni, $dataFin, $codVendedor){
    	return Pedido::where('data_pedido', '>=', $dataIni)->get();
    }

    public function pedidos($dataIni, $dataFin, $codVendedor, $codCliente){


    	if($codCliente == '0'){
    		$retorno = Pedido::join('cliente', 'ped_saida_venda.codigo_cliente', '=', 'cliente.codigo')
			->select('cliente.razao_social', 'cliente.codigo', 'ped_saida_venda.codigo_vendedor', 'ped_saida_venda.numero_controle', 'ped_saida_venda.data_pedido', 'ped_saida_venda.valor_pedido', 'ped_saida_venda.data_emissao', 'ped_saida_venda.quantidade_itens', 'ped_saida_venda.situacao_pedido')
			->where('ped_saida_venda.codigo_vendedor', '=', $codVendedor)
			->where('ped_saida_venda.data_pedido', '>=', $dataIni)
    		->where('ped_saida_venda.data_pedido', '<=', $dataFin)     	
    		->orderBy('ped_saida_venda.data_pedido', 'desc')
			->get();	
    	}else{
    		$retorno = Pedido::join('cliente', 'ped_saida_venda.codigo_cliente', '=', 'cliente.codigo')
			->select('cliente.razao_social', 'cliente.codigo', 'ped_saida_venda.codigo_vendedor', 'ped_saida_venda.numero_controle', 'ped_saida_venda.data_pedido', 'ped_saida_venda.valor_pedido', 'ped_saida_venda.data_emissao', 'ped_saida_venda.quantidade_itens', 'ped_saida_venda.situacao_pedido')
			->where('ped_saida_venda.codigo_vendedor', '=', $codVendedor)
			->where('ped_saida_venda.data_pedido', '>=', $dataIni)
    		->where('ped_saida_venda.data_pedido', '<=', $dataFin)     	
    		->where('ped_saida_venda.codigo_cliente', '=', $codCliente)
    		->orderBy('ped_saida_venda.data_pedido', 'desc')
			->get();	
    	}
		

		return $retorno;
    }

    public function pedidosFaturados($dataIni, $dataFin, $codVendedor, $codCliente){
    	
    	if($codCliente == '0'){

    		$retorno = PedidoFaturado::join('cliente', 'saida_venda.codigo_cliente', '=', 'cliente.codigo')
				->join('ped_saida_venda', 'ped_saida_venda.numero_controle', '=', 'saida_venda.numero_controle')
				->select('cliente.razao_social', 'cliente.codigo', 'saida_venda.codigo_vendedor', 'saida_venda.numero_controle', 'ped_saida_venda.data_pedido', 'saida_venda.valor_total_itens', 'saida_venda.numero_documento', 'saida_venda.data_emissao', 'saida_venda.quantidade_itens', 'saida_venda.valor_total_nota')
				->where('saida_venda.codigo_vendedor', '=', $codVendedor)
				->where('saida_venda.data_emissao', '>=', $dataIni)
    			->where('saida_venda.data_emissao', '<=', $dataFin)      			
    			->orderBy('ped_saida_venda.data_pedido', 'desc')
				->get();

    	}else{
			$retorno = PedidoFaturado::join('cliente', 'saida_venda.codigo_cliente', '=', 'cliente.codigo')
				->join('ped_saida_venda', 'ped_saida_venda.numero_controle', '=', 'saida_venda.numero_controle')
				->select('cliente.razao_social', 'cliente.codigo', 'saida_venda.codigo_vendedor', 'saida_venda.numero_controle', 'ped_saida_venda.data_pedido', 'saida_venda.valor_total_itens', 'saida_venda.numero_documento', 'saida_venda.data_emissao', 'saida_venda.quantidade_itens', 'saida_venda.valor_total_nota')
				->where('saida_venda.codigo_vendedor', '=', $codVendedor)
				->where('saida_venda.data_emissao', '>=', $dataIni)
    			->where('saida_venda.data_emissao', '<=', $dataFin)      			
    			->where('saida_venda.codigo_cliente', '=', $codCliente)
    			->orderBy('ped_saida_venda.data_pedido', 'desc')
				->get();

    	}
		
		return $retorno;
    }

    public function lancamentos($numPedido){

		$retorno = PedidoLancto::join('item', 'ped_saida_venda_lanctos.codigo_item', '=', 'item.codigo')
		->join('prazo_pagamento', 'ped_saida_venda_lanctos.codigo_prazo', '=', 'prazo_pagamento.codigo')
		->join('unidade', 'ped_saida_venda_lanctos.codigo_unidade', '=', 'unidade.codigo')
		->select('item.codigo as codigo_item', 'item.descricao as item', 'prazo_pagamento.descricao as prazo', 'unidade.descricao as unidade', 'ped_saida_venda_lanctos.numero_controle', 'ped_saida_venda_lanctos.preco_unitario', 'ped_saida_venda_lanctos.quantidade')
		->where('ped_saida_venda_lanctos.numero_controle', '=', $numPedido)
		->orderBy('item.descricao')
		->get();

		return $retorno;
    }

    public function loja($dataIni, $dataFin){

        $retorno = DB::select('SELECT SUM(valor_total_nota) AS total, \'Loja 1\' AS descricao, \'#5cbae6\' AS cor FROM saida_venda WHERE data_emissao >= \' ' . $dataIni . ' \' AND data_emissao <= \' ' . $dataFin . ' \'');

    	return $retorno;
    }



    public function loja2($dataIni, $dataFin){

    	$retorno = DB::select('SELECT SUM(valor_total_itens) AS total, \'Loja 2\' AS descricao, \'#b6d957\' AS cor FROM saida_venda WHERE data_emissao >= \' ' . $dataIni . ' \' AND data_emissao <= \' ' . $dataFin . ' \'');

    	return $retorno;
    }
}
