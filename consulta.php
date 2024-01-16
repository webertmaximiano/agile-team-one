<?php 
include('_core/_includes/config.php'); 
global $db_con;

// Remote assinaturas
$query = "SELECT rel_estabelecimentos_id,gateway_ref FROM assinaturas WHERE status = '0'";
if( $eid ) {
	$query = "SELECT rel_estabelecimentos_id,gateway_ref FROM assinaturas WHERE (status = '0' OR status = '1') AND rel_estabelecimentos_id = '$eid'";
}
$sql = mysqli_query( $db_con, $query );
while( $quickdata = mysqli_fetch_array( $sql ) ) {

	$retorno = consulta_pagamento( $quickdata['gateway_ref'] );
	if( $retorno ) {
		change_assinatura_status( $reference,$status );
	}

}

?>
