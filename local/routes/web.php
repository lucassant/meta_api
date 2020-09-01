<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/vendedor/{dataIni}/{dataFin}/{codSupervisor}', 'VendedorController@index');
Route::get('/api/supervisor/{senha}', 'SupervisorController@index');

Route::get('/api/email/{to}', 'MailController@email');
Route::get('/api/pedidos/{dataIni}/{dataFin}/{codVendedor}/{codCliente}', 'PedidosController@pedidosFaturados');
Route::get('/api/pedidos_todos/{dataIni}/{dataFin}/{codVendedor}/{codCliente}', 'PedidosController@pedidos');
Route::get('/api/lancamentos/{numPedido}', 'PedidosController@lancamentos');

Route::get('/api/itens', 'ItemController@index');
Route::get('/api/loja/{dataIni}/{dataFin}', 'PedidosController@loja');
Route::get('/api/loja2/{dataIni}/{dataFin}', 'PedidosController@loja2');
Route::get('/api/totais-vendedores/{dataIni}/{dataFin}', 'VendedorController@retornaTotais');

Route::get('/api/total-receber/{dataIni}/{dataFin}', 'RelatoriosController@aReceber');
Route::get('/api/total-recebidos/{dataIni}/{dataFin}', 'RelatoriosController@recebidos');
Route::get('/api/total-faturados/{dataIni}/{dataFin}', 'RelatoriosController@faturados');

Route::get('/api/prazo-receber/{dataIni}/{dataFin}', 'RelatoriosController@receberPrazo');
Route::get('/api/prazo-recebido/{dataIni}/{dataFin}', 'RelatoriosController@recebidosPrazo');
Route::get('/api/prazo-faturado/{dataIni}/{dataFin}', 'RelatoriosController@faturadosPrazo');

Route::get('/api/ranking-clientes/{dataIni}/{dataFin}/{codVendedor}', 'RelatoriosController@rankingClientes');
Route::get('/api/ranking-itens/{dataIni}/{dataFin}/{codVendedor}', 'RelatoriosController@rankingItens');

Route::get('/api/cheques/{codVendedor}', 'RelatoriosController@chequesAtrasados');
Route::get('/api/titulos/{codVendedor}', 'RelatoriosController@titulosAtrasados');

Route::get('/api/configuracoes', 'ConfiguracoesController@configuracoes');

Route::get('/api/icms_estado', 'SincronizarController@icmsEstado');
