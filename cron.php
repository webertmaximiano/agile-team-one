<?php 
include('_core/_includes/config.php'); 
global $external_token;
global $db_con;

$eid = mysqli_real_escape_string( $db_con, $_GET['eid'] );
$token = mysqli_real_escape_string( $db_con, $_GET['token'] );
$acao = $_GET['acao'];

if( $token == $external_token ) {

	$hoje = date("Y-m-d");

	if( $acao == "sync" ) {

		// Remote assinaturas
		$query = "SELECT rel_estabelecimentos_id,gateway_ref FROM assinaturas WHERE status = '0'";
		if( $eid ) {
			$query = "SELECT rel_estabelecimentos_id,gateway_ref FROM assinaturas WHERE (status = '0' OR status = '1') AND rel_estabelecimentos_id = '$eid'";
		}
		$sql = mysqli_query( $db_con, $query );
		while( $quickdata = mysqli_fetch_array( $sql ) ) {

			$retorno = consulta_pagamento( $quickdata['gateway_ref'] );
			if( $retorno ) {
				$reference = $quickdata['gateway_ref'];
				$status = $retorno['status'];
				change_assinatura_status( $reference,$status );
			}

		}

		// Cancela assinaturas não pagas
		$query_naopagas = mysqli_query( $db_con, "SELECT * FROM assinaturas WHERE expiration < '$hoje' AND status = '1' AND used = '1'" );
		while( $data_naopagas = mysqli_fetch_array( $query_naopagas ) ) {
			$aid = $data_naopagas['id'];
			$reid = $data_naopagas['rel_estabelecimentos_id'];
			mysqli_query( $db_con, "UPDATE assinaturas SET status = '3',used = '3' WHERE id = '$aid' " );
			atualiza_estabelecimento($reid,"offline");
		}

		// Cancela assinaturas expiradas e define nova assinatura
		$query_expiradas = mysqli_query( $db_con, "SELECT id,rel_estabelecimentos_id,duracao_dias FROM assinaturas WHERE expiration < '$hoje' AND status = '1' AND used = '1' ORDER BY id ASC" );
		while ( $data_expiradas = mysqli_fetch_array( $query_expiradas ) ) {
			$reid = $data_expiradas['rel_estabelecimentos_id'];
			atualiza_estabelecimento($reid,"offline");
		}

	}

	if( $acao == "reindex" ) {

		$query = "SELECT id FROM estabelecimentos";
		$sql = mysqli_query( $db_con, $query );
		while( $quickdata = mysqli_fetch_array( $sql ) ) {
			$reid = $quickdata['id'];
			atualiza_estabelecimento( $reid, "online" );
		}

	}


} else {

  echo "Token inválido";

}

// if( $acao == "mapearcidade" ) {

// 	$query_cidades = mysqli_query( $db_con, "SELECT * FROM cidades ORDER BY id ASC" );
// 	$total_cidades = mysqli_num_rows( $query_cidades );
// 	$atual = 0;
// 	while ( $data_cidades = mysqli_fetch_array( $query_cidades ) ) {
// 		$id = $data_cidades['id'];
// 		$subdominio = slugify( $data_cidades['nome'].data_info("estados",$data_cidades['estado'],"uf") );
// 		mysqli_query( $db_con,"UPDATE cidades SET subdominio = '$subdominio' WHERE id = '$id'" );
// 		echo $subdominio." (".$atual."/".$total_cidades."), ";
// 		$atual++;
// 		flush();
// 		ob_flush();
// 		sleep(0,.1);
// 	}

// }

?>
