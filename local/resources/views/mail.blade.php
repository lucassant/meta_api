<!DOCTYPE html>
<html>
<head>
<style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

</style>
</head>
<body>
@foreach ($data as $pedido)

<div>
    <table style="border:2px solid #000000;text-align:center;width:100%;border-collapse:collapse; border-bottom:0">
        <tbody>

         <tr>
            <td style="font-size:14pt;text-align:center;padding:3px;"><b> {{ $pedido->empresa }} </b></td>
          </tr>
          <tr>
            <td style="font-size:14pt;text-align:center;padding:3px;">Orçamento</td>
          </tr>

    </tbody>
</table>


<table style="border:2px solid #000000;width:100%;border-collapse:collapse; border-bottom:0">
   <tbody>
       <tr>
          <td colspan="2" style="padding:3px;">
            <b>Vendedor:</b> {{ $pedido->vendedor }} </td>
        </tr>
    </tbody>
</table>

<table style="border:2px solid #000000;width:100%;border-collapse:collapse; border-bottom:0">
   <tbody>
       <tr>
        <td style="width:60%;padding:3px;"><b>Cliente:</b> {{ $pedido->razao_social }} </td>
        <td style="width:40%;padding:3px;"><b>Nome fantasia:</b> {{ $pedido->nome_fantasia }} </td>
    </tr>
    <tr>
        <td style="padding:3px;" colspan="2"><b>Endereço:</b> {{ $pedido->endereco }} - {{ $pedido->bairro }}</td>
    </tr>
    <tr>
       <td style="width:50%;padding:3px;" colspan="2"><b>Cidade:</b> {{ $pedido->cidade }} - {{ $pedido->estado }}</td>
   </tr>
</tbody>
</table>


<table style="border:2px solid #000000;width:100%;border-collapse:collapse; border-bottom:0 ">
 <tbody>
     <tr>
        <td style="width:50%; border:1px solid #888888;padding:3px;"><b>Produto</b></td>
        <td style="text-align:center;width:10%;border:1px solid #888888;padding:3px;"><b>Unidade</b></td>
        <td style="text-align:center;width:10%;border:1px solid #888888;padding:3px;"><b>Prazo</b></td>
        <td style="text-align:center;width:10%;border:1px solid #888888;padding:3px;"><b>Qtde.</b></td>
        <td style="text-align:right;width:10%;border:1px solid #888888;padding:3px;"><b>Preço</b></td>
        <td style="text-align:right;width:10%;border:1px solid #888888;padding:3px;"><b>Subtotal</b></td>
    </tr>
</tbody>
</table>

@foreach($pedido->lanctos as $lancto)
<table style="padding: 5px;border-left:2px solid black;border-right:2px solid black;width:100%;border-collapse:collapse;page-break-inside:avoid;">
   <tbody>

                       
     <td style="width:50%;border:1px solid #888888;padding:3px;border-bottom:0;border-top:0;"> {{ $lancto->item }} </td>
     <td style="width:10%;text-align:center;border:1px solid #888888;padding:3px;border-bottom:0;border-top:0;"> {{ $lancto->unidade }} </td>
     <td style="width:10%;text-align:center;border:1px solid #888888;padding:3px;border-bottom:0;border-top:0;"> {{ $lancto->prazo }}</td>
     <td style="width:10%;text-align:center;border:1px solid #888888;padding:3px;border-bottom:0;border-top:0;"> {{ $lancto->quantidade_item }} </td>
     <td style="width:10%;text-align:right;border:1px solid #888888;padding:3px;border-bottom:0;border-top:0;"> {{ $lancto->valor_unitario }}</td>
     <td style="width:10%;text-align:right;border:1px solid #888888;padding:3px;border-bottom:0;border-top:0;"> {{ $lancto->valor_total }} </td>

</tbody>
</table>
@endforeach

<table style="border:2px solid black;border-top:1;width:100%;border-collapse:collapse;page-break-after:auto; border-bottom:0">
 <tbody>
     <tr>
     <td style="width:20%;text-align:left;border:1px solid #888888;padding:3px;border-bottom:0;border-top:0;"> <b>Quantidade de itens: </b> {{ $pedido->contador }}</td>
     <td style="width:30%;text-align:left;border:1px solid #888888;padding:3px;border-bottom:0;border-top:0;"> <b>Data do pedido: </b> {{ $pedido->data_pedido }}</td>
     <td style="width:30%;text-align:left;border:1px solid #888888;padding:3px;border-bottom:0;border-top:0;"> <b>Forma de pagamento: </b>{{ $pedido->forma_pagamento }}</td>
     <td style="width:20%;text-align:center;border:1px solid #888888;padding:3px;border-bottom:0;border-top:0;"> <b>Valor total: </b> R$ {{ $pedido->total_pedido }}</td>
     </tr>
 </tbody>
</table>

<table style="border:2px solid #000000;width:100%;border-collapse:collapse;">
    <tbody>
     <tr>
      <td style="padding:3px;text-align:center;">*** Documento sem valor fiscal ***</td>
  </tr>
</tbody>
</table>
</br>
<center>*** Esta é uma mensagem automática. Por favor não responda esse email ***</center>

</div>
@endforeach

</body>
</html>