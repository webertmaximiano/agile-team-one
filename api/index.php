<?php

// CORE
include('../_core/_includes/config.php');

// APP 
global $app;

$token = mysqli_real_escape_string( $db_con, $_GET['token'] );

$contatoken = strlen($token);

if($contatoken <= 10) {
    
     echo '300';
     exit;
    
}


if (preg_match("/^([a-zA-Z0-9]+)$/", $token)) {

$vertoken = mysqli_query( $db_con, "SELECT ide FROM impressao WHERE token = '$token'");

$hastoken = mysqli_num_rows( $vertoken );

if($hastoken >= 1) {

$datatoken = mysqli_fetch_array( $vertoken );

$iduser = $datatoken['ide'];

$data_i = date("Y-m-d");

$verpedido = mysqli_query( $db_con, "SELECT id, comprovante FROM pedidos WHERE rel_estabelecimentos_id = '$iduser' AND status = '4' AND data_hora LIKE '%$data_i%' ORDER BY id ASC LIMIT 1"); //STATUS é o numero do status do pedido que ele irá imrpimir

$haspedido = mysqli_num_rows( $verpedido );

if($haspedido >= 1) {

$datacomprovante = mysqli_fetch_array( $verpedido );

//echo $datacomprovante['comprovante'];

echo str_replace("*", "", $datacomprovante['comprovante']);
 

$idx = $datacomprovante['id'];

$editarpedido = mysqli_query( $db_con, "UPDATE pedidos SET status = '7' WHERE id = '$idx'"); //DESMARCAR PARA ALTERAR O STATUS DO PEDIDO AUTOMATICAMENTE APÓS IMPRESSO

} else {
    
    echo '400';
    
}

    
} else {
    
    echo '300';
}


} else {
    
    echo '300';
}