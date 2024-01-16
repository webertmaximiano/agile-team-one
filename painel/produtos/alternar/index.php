<?php
include('../../../_core/_includes/config.php');
restrict_estabelecimento();
restrict_expirado();
$subtitle = "Deletar";
?>

<!-- Aditional Header's -->

<?php

	$id = $_GET['id'];
	$eid = $_SESSION['estabelecimento']['id'];

	// VERIFICA SE O USUARIO TEM DIREITOS

	$edit = mysqli_query( $db_con, "SELECT * FROM produtos WHERE id = '$id' AND rel_estabelecimentos_id = '$eid' LIMIT 1");
	$hasdata = mysqli_num_rows( $edit );
	$data = mysqli_fetch_array( $edit );

	$cando = 0;

	if( $data['rel_estabelecimentos_id'] == $eid && $id ) {
		$cando = 1;
	}

	if( $cando )  {

		if( $data['status'] == "1" ) {
			$status = "2";
		} else {
			$status = "1";
		}
	
		if( mysqli_query( $db_con, "UPDATE produtos SET status = '$status' WHERE id = '$id'") ) {

			header("Location: ../index.php?msg=sucesso");

		} else {

			header("Location: ../index.php?msg=erro");

		}

	} else {

		header("Location: ../index.php?msg=erro");

	}

?>



