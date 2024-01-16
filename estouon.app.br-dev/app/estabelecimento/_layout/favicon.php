<?php
include($virtualpath.'/_layout/define.php');
global $insubdominioid;
global $roopath;
$id = $insubdominioid;
$define_query = mysqli_query( $db_con, "SELECT nome,perfil,subdominio FROM estabelecimentos WHERE id = '$id' LIMIT 1");
$define_data = mysqli_fetch_array( $define_query );
?>
<?php 
$favicon = base64_encode($define_data['perfil']);
$faviconw = "192";
include( $rootpath."/_core/_cdn/thumb.php"); 
?>