<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SincronizarController extends Controller
{

    public function icmsEstado()
    {
        //Icms
        $icms_estado = DB::select('SELECT 
                                    codigo_estado, 
                                    codigo_icms, 
                                    encargo_tabela_preco_pocket AS encargo_tabela 
                                FROM 
                                    icms_estado');

        //Monta o json de retorno
        $data = json_encode((object) array(
            'icms_estado' => $icms_estado
        ));

        return $data;
    }
}
