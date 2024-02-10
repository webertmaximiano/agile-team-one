<?php
include('_core/_includes/config.php'); 

// Defina a URL da API do Mercado Pago
$api_url = "https://api.mercadopago.com/v1/notifications/";

// Obtenha o token da URL
$token = $_GET["token"];

// Verifique se o token é igual a assinatura da notificacao do webhook
if ($token != $mp_assinatura) {
  echo "Token inválido";
  exit;
}

// Leia o corpo da requisição
$body = file_get_contents("php://input");

// Verifique a assinatura da notificação
$headers = getallheaders();
$signature = $headers["HTTP_X_MP_SIGNATURE"];
$calculated_signature = hash_hmac("sha256", $body, $mp_assinatura);

if ($signature != $calculated_signature) {
  echo "Assinatura inválida";
  exit;
}

// Decodifica os dados da notificação
$data = json_decode($body);

// Obtenha o ID da transação
$transaction_id = $data["id"];

// Obtenha o status da transação
$status = $data["status"];

// Realize as ações necessárias de acordo com o status da transação
switch ($status) {
  case "approved":
    // A transação foi aprovada
    echo "Pagamento aprovado!";
    break;
  case "pending":
    // A transação está pendente
    echo "Pagamento pendente";
    break;
  case "rejected":
    // A transação foi rejeitada
    echo "Pagamento rejeitado";
    break;
  default:
    // Status desconhecido
    echo "Status desconhecido: " . $status;
    break;
}

?>