<?php 
include('../_includes/config.php'); 
session_start();

global $db_con;

$token = mysqli_real_escape_string( $db_con, $_GET['token'] );
$fileid = mysqli_real_escape_string( $db_con, $_GET['fileid'] );
$owner = data_info("midia",$fileid,"rel_estabelecimentos_id");

$token = base64_decode($token);
$token = explode(":", $token);

$token_id = $token[0];
$token_pass = strrev( $token[1] );

$level = user_token_info( $token_id,$token_pass,"level" );

// Validate

$cando = 0;

if( $level == "1" OR user_token_info('rel_estabelecimentos_id') == $owner ) {
	$cando = 1;
}

if( $cando ) {
	$quicksql = mysqli_query( $db_con, "SELECT * FROM midia WHERE id = '$fileid' ORDER BY id DESC LIMIT 999" );
	while( $quickdata = mysqli_fetch_array( $quicksql ) ) {
		
		$mid = $quickdata['id'];
		$midia = $quickdata['url'];

		unlink( $rootpath."/_core/_uploads/".$midia );
		mysqli_query( $db_con, "DELETE FROM midia WHERE id = '$mid'");

	}
}

?>