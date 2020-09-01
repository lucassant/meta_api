<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ConfiguracoesController extends Controller
{

    public function configuracoes()
    {

        $dados = DB::select('
                                SELECT 
                                    exibe_item_estoque_pocket,
                                    vende_somente_for_pag_cliente,
                                    vende_somente_pra_pag_cliente 
                                FROM 
                                    configuracoes 
                                LIMIT 1');

        //Monta o json de retorno
        $retorno = json_encode((object) array(
            'configuracoes' => $dados
        ));

        return $retorno;
    }
}
