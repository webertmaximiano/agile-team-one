<?php
include('../../../_core/_includes/config.php');
restrict('1');
$subtitle = "Gerenciar";
?>

<!-- Aditional Header's -->

<?php

	$id = $_GET['id'];
	$id = mysqli_real_escape_string( $db_con, $_GET['id'] );

	$queryestabelecimento = mysqli_query( $db_con, "SELECT * FROM estabelecimentos WHERE id = '$id' LIMIT 1");
	$hasdataestabelecimento = mysqli_num_rows( $queryestabelecimento );
	$dataestabelecimento = mysqli_fetch_array( $queryestabelecimento );

	if( $dataestabelecimento ) {

		$_SESSION['estabelecimento']['id'] = $dataestabelecimento['id'];
		$_SESSION['estabelecimento']['avatar'] = $dataestabelecimento['avatar'];
		$_SESSION['estabelecimento']['perfil'] = $dataestabelecimento['perfil'];
		$_SESSION['estabelecimento']['nome'] = $dataestabelecimento['nome'];
		$_SESSION['estabelecimento']['subdominio'] = $dataestabelecimento['subdominio'];
		$_SESSION['estabelecimento']['logged'] = 1;
		$_SESSION['estabelecimento']['level'] = $data['level'];
		$_SESSION['estabelecimento']['funcionalidade_marketplace'] = $dataestabelecimento['funcionalidade_marketplace'];
		$_SESSION['estabelecimento']['funcionalidade_banners'] = $dataestabelecimento['funcionalidade_banners'];
		$_SESSION['estabelecimento']['funcionalidade_variacao'] = $dataestabelecimento['funcionalidade_variacao'];
		$_SESSION['estabelecimento']['status'] = $dataestabelecimento['status'];
		$_SESSION['estabelecimento']['status_force'] = $dataestabelecimento['status_force'];
		$_SESSION['estabelecimento']['excluded'] = $dataestabelecimento['excluded'];
		$_SESSION['estabelecimento']['expiracao'] = $dataestabelecimento['expiracao'];
		header("Location: ".get_just_url()."/painel");

	}

?>



