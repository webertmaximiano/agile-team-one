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
    
  // MERCADO PAGO
    global $mp_sandbox;
    global $mp_public_key;
    global $mp_acess_token;
    global $mp_client_id;
    global $mp_client_secret;
    global $external_token;

    $accesstoken = $mp_acess_token;

    $body = json_decode(file_get_contents("php://input"));

    // se nao for requisição do formulario do cartao
    if (!isset($body->token)) {

        if (!isset($_GET['vl'])) {
            die('vl nao existe');
        } else {
            if ($_GET['vl'] == "" || !is_numeric($_GET['vl'])) {
                die('vl não pode ser vazio, e tem que ser numerico');
            } else {
                if ($_GET['vl'] < 1 && $_GET['vl'] > 100) {
                    die('valor deve ser entre 1 e 100');
                }
            }
        }


        // captura  o valor
        $amount = (float) trim($_GET['vl']);

        // instancia a classe pagamento
        $payment = new Payment(1);

        // criação do pagamento
        $payCreate = $payment->addPayment($amount);

        if ($payCreate) {

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.mercadopago.com/checkout/preferences',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                    "back_urls": {
                        "success": "https://google.com/success",
                        "pending": "https://google.com/pending",
                        "failure": "https://google.com/failure"
                    },
                    "external_reference": "' . $payCreate . '",
                    "notification_url": "https://google.com",
                    "auto_return": "approved",
                    "items": [
                        {
                        "title": "Dummy Title",
                        "description": "Dummy description",
                        "picture_url": "http://www.myapp.com/myimage.jpg",
                        "category_id": "car_electronics",
                        "quantity": 1,
                        "currency_id": "BRL",
                        "unit_price": ' . $amount . '
                        }
                    ],
                    "payment_methods": {
                        "excluded_payment_methods": [
                        {"id": "pix"}
                        ],
                        "excluded_payment_types": [
                        {"id": "ticket"}
                        ]
                    }
                    }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $accesstoken
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $obj = json_decode($response);

            if (isset($obj->id)) {
                if ($obj->id != NULL) {

                    if (isset($card)) {
                        $preference_id = $obj->id;
                    } else {

                        $link_externo = $obj->init_point;
                        $external_reference = $obj->external_reference;

                        echo "<h3>{$amount} #{$external_reference}</h3> <br />";
                        echo "<a href='{$link_externo}' target='_blank' >Link externo</a>";

                    }
                }
            }
        }

    }
}