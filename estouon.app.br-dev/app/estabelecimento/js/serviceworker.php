<?php
global $insubdominioid;
$id = $insubdominioid;
global $simple_url;
include("../../../_core/_includes/config.php");
$define_query = mysqli_query( $db_con, "SELECT nome,perfil,subdominio FROM estabelecimentos WHERE id = '$id' LIMIT 1");
$define_data = mysqli_fetch_array( $define_query );
$url = "https://".$define_data['subdominio'].".".$simple_url;
header("Service-Worker-Allowed: ".$url);
header("Content-Type: application/javascript");
?>
self.addEventListener('install', function(e) {

});

self.addEventListener('fetch', function(e) {

});
