<?php
include('../../../_core/_includes/config.php');
restrict_estabelecimento();
restrict_expirado();
$subtitle = "Aceitar";
?>

<!-- Aditional Header's -->

<?php

	$id = $_GET['id'];
	$eid = $_SESSION['estabelecimento']['id'];

	// VERIFICA SE O USUARIO TEM DIREITOS

	$edit = mysqli_query( $db_con, "UPDATE pedidos SET status = '2' WHERE id = '$id' AND rel_estabelecimentos_id = '$eid'");
	
	if( $edit ) {

		header("Location: ../index.php?msg=sucesso");
	
	} else {

		header("Location: ../index.php?msg=erro");

	}

?>



