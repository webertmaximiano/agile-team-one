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

	$edit = mysqli_query( $db_con, "SELECT * FROM categorias WHERE id = '$id' AND rel_estabelecimentos_id = '$eid' LIMIT 1");
	$hasdata = mysqli_num_rows( $edit );
	$data = mysqli_fetch_array( $edit );

	$cando = 0;

	if( $data['rel_estabelecimentos_id'] == $eid && $id ) {
		$cando = 1;
	}

	if( $cando )  {
	
		if( delete_categoria( $id ) ) {

			header("Location: ../index.php?msg=sucesso");

		} else {

			header("Location: ../index.php?msg=erro");

		}

	} else {

		header("Location: ../index.php?msg=erro");

	}

?>



