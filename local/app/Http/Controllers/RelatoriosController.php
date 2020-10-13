<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class RelatoriosController extends Controller
{

	public function aReceber($dataIni, $dataFim)
	{
		$retorno = DB::select('SELECT SUM(valor_debito - valor_credito) AS total, 
		\'Loja 1\' AS descricao FROM titulo_receber WHERE data_pagamento IS NULL AND data_vencimento >= \' ' . $dataIni . ' \' AND data_vencimento <= \' ' . $dataFim . ' \'');

		return $retorno;
	}

	public function recebidos($dataIni, $dataFim)
	{
		$retorno = DB::select('SELECT SUM(valor_lancamento) AS total, \'Loja 1\' AS descricao FROM lancamento_receber WHERE data_lancamento >= \' ' . $dataIni . ' \' AND data_lancamento <= \' ' . $dataFim . ' \'');

		return $retorno;
	}

	public function faturados($dataIni, $dataFim)
	{
		$valor = 0;

		$retorno = DB::select('SELECT SUM(valor_total_nota) AS total FROM saida_venda WHERE data_emissao >= \' ' . $dataIni . ' \' AND data_emissao <= \' ' . $dataFim . ' \'');

		if ($retorno) {
			$valor = $retorno[0]->total;

			$retorno = DB::select('SELECT COALESCE(SUM(valor_total_itens),0) AS total FROM saida_venda_consumidor WHERE data_documento >= \' ' . $dataIni . ' \' AND data_documento <= \' ' . $dataFim . ' \'');

			if ($retorno) {
				$valor += $retorno[0]->total;

				$retorno = DB::select('SELECT COALESCE(SUM(valor_pagamento),0) AS total FROM venda_consumidor_pagamento WHERE data_vencimento >= \' ' . $dataIni . ' \' AND data_vencimento <= \' ' . $dataFim . ' \'');

				if ($retorno) {
					$valor += $retorno[0]->total;
				}
			}
		}

		$json[] = [
			'total' => $valor,
			'descricao' => 'Loja 1'
		];

		return $json;
	}

	public function receberPrazo($dataIni, $dataFim)
	{
	}

	public function recebidosPrazo($dataIni, $dataFim)
	{
		$retorno = DB::select('SELECT 
									lc.descricao, 
									lr.codigo_lancamento AS codigo, 
									COALESCE(SUM(lr.valor_lancamento), 0) AS total 
								FROM lancamento_receber lr 
									INNER JOIN lancamento lc ON lr.codigo_lancamento = lc.codigo
								WHERE 
									lr.data_lancamento >= \' ' . $dataIni . ' \' AND lr.data_lancamento <= \' ' . $dataFim . ' \'
								GROUP BY 
									lr.codigo_lancamento, lc.descricao
								ORDER BY 
									total DESC');

		return $retorno;
	}

	public function faturadosPrazo($dataIni, $dataFim)
	{
		$retorno = DB::select('SELECT 
									sum(valor) AS total, 
									lanc.codigo, 
									lanc.descricao 
								FROM (
									SELECT 
										data_emissao, 
										valor_total_itens AS valor, 
										codigo_lancamento 
									FROM 
										saida_venda
									UNION ALL
									SELECT 
										data_documento AS data_lancamento, 
										vcp.valor_pagamento AS valor, 
										codigo_lancamento 
									FROM 
										venda_consumidor_pagamento vcp, 
										saida_venda_consumidor svc 
									WHERE 
										svc.numero_controle = vcp.numero_controle
									) AS mov, lancamento lanc
								WHERE 
									lanc.codigo = mov.codigo_lancamento AND 
									mov.data_emissao >= \' ' . $dataIni . ' \' AND mov.data_emissao <= \' ' . $dataFim . ' \'
								GROUP BY
									lanc.codigo,
									lanc.descricao,
									mov.codigo_lancamento
								ORDER BY 
									total DESC');

		return $retorno;
	}

	public function rankingClientes($dataIni, $dataFim, $codVendedor)
	{

		$retorno = DB::select('SELECT 
						SUM(sv.valor_total_nota) AS total, 
						COUNT(sv.numero_controle) AS quantidade, 
						sv.codigo_cliente AS codigo,  
						cl.razao_social AS descricao  
					FROM 
						saida_venda sv 
						INNER JOIN cliente cl ON cl.codigo = sv.codigo_cliente 
					WHERE 
						sv.codigo_vendedor = ' . $codVendedor . '
						AND data_emissao >= \' ' . $dataIni . ' \' 
						AND data_emissao <= \' ' . $dataFim . ' \'
					GROUP BY 
						sv.codigo_cliente, 
						cl.razao_social
					ORDER BY 
						total DESC');

		return $retorno;
	}

	public function rankingItens($dataIni, $dataFim, $codVendedor)
	{

		$retorno = DB::select('SELECT 
						SUM(sv.valor_total_nota) AS total, 
						COUNT(sv.numero_controle) AS quantidade, 
						svl.codigo_item AS codigo,  
						it.descricao AS descricao  
					FROM 
						saida_venda sv 
						INNER JOIN saida_venda_lanctos svl ON sv.numero_controle = svl.numero_controle
						INNER JOIN item it ON it.codigo = svl.codigo_item
					WHERE 
						sv.codigo_vendedor = ' . $codVendedor . '
						AND data_emissao >= \' ' . $dataIni . ' \' 
						AND data_emissao <= \' ' . $dataFim . ' \'
					GROUP BY 
						svl.codigo_item, 
						it.descricao
					ORDER BY 
						total DESC');

		return $retorno;
	}

	public function chequesAtrasados($codVendedor)
	{

		$retorno = '';


		$retorno = DB::select('
		    						SELECT 
		    							cl.codigo,
		    							cr.codigo_banco,
		    							cr.codigo_agencia,
		    							cr.codigo_conta,
		    							cr.numero_cheque,
		    							cd.data_devolucao,
		    							ba.descricao AS banco,
		    							cr.valor_debito - cr.valor_credito AS saldo
		    						FROM 
		    							cheque_recebido cr 
		    							INNER JOIN cheque_devolvido cd ON cd.numero_controle = cr.numero_controle AND cd.numero_ordem = cr.qtde_devolucoes 
               							INNER JOIN cliente cl ON cl.codigo = cr.codigo_cliente
               							INNER JOIN banco ba ON cr.codigo_banco = ba.codigo 
               						WHERE 
               							cr.valor_debito - cr.valor_credito > 0');


		return $retorno;
	}

	public function titulosAtrasados($codVendedor)
	{
		$verificaConfgCliente = 0;
		$retorno = '';

		$verificaConfgCliente = DB::select('SELECT cliente_atend_mais_de_um_vdr FROM configuracoes LIMIT 1');

		//Verifica se o cliente é atendido por mais de um vendedor
		if ($verificaConfgCliente[0]->cliente_atend_mais_de_um_vdr == -1) {
			$retorno = DB::select('SELECT 
										cl.codigo As codigo_cliente,
              							dc.abreviatura,
              							tr.numero_titulo,
              							tr.numero_ordem,
              							tr.data_vencimento,
              							(tr.valor_debito - tr.valor_credito) AS saldo
              						FROM 
              							documento_receber dr
              							INNER JOIN titulo_receber tr ON dr.numero_controle = tr.numero_controle 
              							INNER JOIN documento dc ON dc.codigo = tr.codigo_titulo
              							INNER JOIN vendedor_cliente vc ON vc.codigo_cliente = dr.codigo_cliente AND vc.codigo_vendedor = ' . $codVendedor . ' 
              							INNER JOIN cliente cl ON cl.codigo = dr.codigo_cliente 
              						WHERE 
              							(tr.valor_debito - tr.valor_credito) > 0 AND
              							tr.data_vencimento <=  CURRENT_DATE  AND
              							vc.codigo_vendedor = ' . $codVendedor);
		} else {
			$retorno = DB::select('SELECT 
										cl.codigo As codigo_cliente,
              							dc.abreviatura,
              							tr.numero_titulo,
              							tr.numero_ordem,
              							tr.data_vencimento,
              							(tr.valor_debito - tr.valor_credito) AS saldo
              						FROM 
              							documento_receber dr
              							INNER JOIN titulo_receber tr ON dr.numero_controle = tr.numero_controle 
              							INNER JOIN documento dc ON dc.codigo = tr.codigo_titulo              							
              							INNER JOIN cliente cl ON cl.codigo = dr.codigo_cliente 
              						WHERE 
              							(tr.valor_debito - tr.valor_credito) > 0 AND
              							tr.data_vencimento <=  CURRENT_DATE  AND
              							dr.codigo_vendedor = ' . $codVendedor);
		}

		return $retorno;
	}

	public function tabelaDePrecos(Request $request)
	{
		try {
			//obtem o prazo padrão
			$prazoPadrao = DB::select('select prazo_preco_base from configuracoes limit 1', []);
			$prazoPadrao = $prazoPadrao[0]->prazo_preco_base;

			$produtos = DB::select(
				'SELECT
							ip.codigo_item, 
							it.descricao, 
							ip.preco, 
							(it.estoque - it.reserva) as estoque,
							it.referencia,
							un.abreviatura, 
							un.quantidade as quantidade_unidade							
						from 
							item it 
							inner join item_preco ip on it.codigo = ip.codigo_item and ip.codigo_unidade = it.unidade_padrao_saida
							inner join unidade un on it.unidade_padrao_saida = un.codigo
						where 
							it.ativo = -1 
							and ip.codigo_prazo = ? 
							and (lower(it.descricao) like ? or lower(it.referencia) like ?)
						order by 
							it.descricao',
				[$prazoPadrao, $request->search . '%', $request->search]
			);

			return json_encode((object) array('produtos' => $produtos));
		} catch (\Throwable $th) {
			return $th;
		}
	}
}
