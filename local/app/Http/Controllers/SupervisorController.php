<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Supervisor;

class SupervisorController extends Controller
{
	public function index($senha)
	{
		if ($senha == '1234') {
			return Supervisor::where('ativo', '=', '-1')->get();
		} else {
			return 'senha';
		}
	}
}
