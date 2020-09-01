<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Promocao;

class PromocaoController extends Controller
{
    public function index(){    
    	return Promocao::orderBy('promocao.numero_controle', 'asc')->with('lancamentos')->get();
    }
}
