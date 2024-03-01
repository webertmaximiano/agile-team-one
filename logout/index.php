<?php include('../_core/_includes/config.php'); ?>

<?php
	$uid = $_SESSION['user']['id'];
	mysqli_query( $db_con, "UPDATE users SET keepalive='' WHERE id = '$uid'");
	setcookie('keepalive', "kill", time()-3600);
	session_destroy();
	header("Location: ../login");
?>