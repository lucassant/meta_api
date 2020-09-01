<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

use App\Item;

class ItemController extends Controller{

	public function index(){
		$itens = DB::select('select encode(foto::bytea, \'base64\') as imagem, codigo from item WHERE foto IS NOT NULL');
		
		foreach ($itens as $key => $item) {
			$location = public_path('images\\' . $item->codigo . '.jpg');
			Image::make(base64_decode($item->imagem))->resize(300,300)->save($location);
			
			$data = file_get_contents($location);
			$base64 = 'data:image/jpg;base64,' . base64_encode($data);
			$item->imagem = $base64;
			/*$image = (string) Image::make(base64_decode($item->imagem), true)->resize(200,200)->encode('jpg');
			$item->imagem = base64_encode($image);*/
		}
		
		return $itens;	
		
	}
}
