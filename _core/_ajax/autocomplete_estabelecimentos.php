<?php 
include('../_includes/config.php'); 
session_start();
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
?>

<?php
$x = 0;
$exclude = mysqli_real_escape_string( $db_con, $_GET['exclude'] );
$consulta = mysqli_real_escape_string( $db_con, $_GET['consulta'] );
$query = "SELECT * FROM estabelecimentos WHERE nome LIKE '$consulta%' ";
$query .= " ORDER BY nome ASC LIMIT 18";
$cquery = mysqli_query( $db_con, $query );

while( $cdata = mysqli_fetch_array($cquery) ) {
	$resultados['suggestions'][$x] =  array( "value" => $cdata['nome'], "data" => $cdata['id']  );
	$x++;
}

echo json_encode($resultados);

?>