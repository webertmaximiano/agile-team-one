<?php
header("Content-type: application/javascript");
include('../../../_core/_includes/config.php');
$insubdominiourl = mysqli_real_escape_string( $db_con, $_GET['insubdominiourl'] );
?>

function escolhe_cidade() {
	
	$("#modalcidade").modal("show");

}