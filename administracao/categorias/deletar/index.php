<?php
include('../../../_core/_includes/config.php');
restrict('1');
$subtitle = "Deletar";
?>

<!-- Aditional Header's -->

<?php

	$id = $_GET['id'];

	if( $id )  {
	
		if( delete_categoria( $id ) ) {

			header("Location: ../index.php?msg=sucesso");

		} else {

			header("Location: ../index.php?msg=erro");

		}

	}

?>



