<?php
include('../../../_core/_includes/config.php');
restrict('1');
$subtitle = "Deletar";
$mode = $_GET['mode'];
?>

<!-- Aditional Header's -->

<?php

	$id = $_GET['id'];

	if( $id )  {

		if( $mode == "shift" ) {
	
			if( delete_estabelecimento( $id ) ) {

				header("Location: ../index.php?msg=sucesso");

			} else {

				header("Location: ../index.php?msg=erro");

			}

		} else {

			if( delete_estabelecimento( $id ) ) {

				header("Location: ../index.php?msg=sucesso");

			} else {

				header("Location: ../index.php?msg=erro");

			}

		}

	}

?>



