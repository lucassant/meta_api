<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Mail;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MailController extends Controller {

  public function email($endereco, Request $request){
  	//Decode base64
  	$conteudo = $request->header('conteudo');    
    $b64 = base64_decode($conteudo);
    $json = json_decode($b64);

  	$data = array ( 'email' => $endereco, 'from' => 'tscpedidos@gmail.com', 'from_name' => 'TSC Importados', 'data' => $json );

    Mail::send('mail', $data, function($message) use ($data) {
       		
       		$message->to( $data['email'] )->from( $data['from'], $data['from_name'] )->subject( 'Orçamento' );
    	});

    echo $json[0]->lanctos[1]->item;
  }

}