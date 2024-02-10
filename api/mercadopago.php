<?php

function consulta_pagamento( $gateway_ref ) {

global $mp_acess_token;

$url = "https://api.mercadopago.com/merchant_orders?";
$url .= "access_token=".$mp_acess_token;
$url .= "&external_reference=".$gateway_ref;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$res = curl_exec($ch);
$dados = json_decode($res,1);
// print("<pre>".print_r($dados,true)."</pre>");

if( isset($dados['elements'][0]) ) {
    $consulta = $dados['elements'][0];
    $retorno['gateway_ref'] = $consulta['external_reference'];
    $retorno['status'] = $consulta['order_status'];
    return $retorno;
} else {
    return false;
}

}

function createPreference() {
    
}