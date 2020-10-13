<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic as Image;

class ProdutosController extends Controller
{
    public function getProdutoDetalhes($codigo)
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
							un.quantidade as quantidade_unidade,
                            encode(it.foto::bytea, \'base64\') as imagem
						from 
							item it 
							inner join item_preco ip on it.codigo = ip.codigo_item and ip.codigo_unidade = it.unidade_padrao_saida
							inner join unidade un on it.unidade_padrao_saida = un.codigo
						where 
							it.codigo = ? 
							and ip.codigo_prazo = ?
						order by 
							it.descricao',
                [$codigo, $prazoPadrao]
            );

            try {
                $location = public_path('images\\' . $codigo . '.jpg');
                Image::make(base64_decode($produtos[0]->imagem))->resize(300, 300)->save($location);

                $data = file_get_contents($location);
                $base64 = 'data:image/jpg;base64,' . base64_encode($data);
                $produtos[0]->imagem = $base64;
            } catch (\Throwable $th) {
            }

            return json_encode((object) array('produtos' => $produtos));
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function prazosProduto($codigoProduto, $codigoUnidade)
    {
        try {
            $prazos = DB::select(
                'SELECT 
                    pp.codigo,
                    pp.descricao,
                    ip.preco
                from 
                    prazo_pagamento pp
                    inner join item_preco ip on ip.codigo_prazo = pp.codigo
                where 
                    pp.ativo = -1 
                    and ip.codigo_item = ? 
                    and ip.codigo_unidade = ?',
                [$codigoProduto, $codigoUnidade]
            );

            return json_encode((object) array('prazos' => $prazos));
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function unidadesProduto($codigoProduto)
    {
        try {
            $unidades = DB::select('SELECT distinct 
                                        un.codigo, 
                                        un.descricao 
                                    from 
                                        unidade un 
                                        inner join item_preco ip on ip.codigo_unidade = un.codigo
                                    where 
                                        ip.codigo_item = ?', [$codigoProduto]);

            return json_encode((object) array('unidades' => $unidades));
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
