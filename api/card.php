<?php 

 $card = true;
 require_once 'preference.php';

if(isset($body->token)){

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.mercadopago.com/v1/payments',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
        "description": "Payment for product",
        "installments": '.$body->installments.',
        "payer": {
        "email": "'.$body->payer->email.'",
        "identification": {
            "type": "'.$body->payer->identification->type.'",
            "number": "'.$body->payer->identification->number.'"
        }
        },
        "issuer_id": "'.$body->issuer_id.'",
        "payment_method_id": "'.$body->payment_method_id.'",
        "token": "'.$body->token.'",
        "transaction_amount": '.$body->transaction_amount.'
      }',
        CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Authorization: Bearer '.$accesstoken
        ),
    ));
    
    $response = curl_exec($curl);
    curl_close($curl);

    echo $response;

    die;

}



?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

     <link rel="stylesheet" href="../css/style.css">

    <title>Cartão</title>

    <script src="https://sdk.mercadopago.com/js/v2"></script>


</head>
<body>

   <input type="hidden" id="valor_payment" value="<?= $amount; ?>" >
   <input type="hidden" id="preference_id" value="<?= $preference_id; ?>">
    
    
    <div class="card-page">
        <div id="statusScreenBrick_container"></div>
         <div id="paymentBrick_container"></div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="/js/card.js"></script>

</body>
</html>