<option value="">Cidade</option>
<?php
include('../_includes/config.php'); 
$estado = mysqli_real_escape_string( $db_con, $_GET['estado'] );
$cidade = mysqli_real_escape_string( $db_con, $_GET['cidade'] );
if( $estado ) {
	$sql = mysqli_query( $db_con, "SELECT * FROM cidades WHERE estado = '$estado' ORDER BY nome ASC" );
} else {
	$sql = mysqli_query( $db_con, "SELECT * FROM cidades ORDER BY nome ASC" );
}
while( $quickdata = mysqli_fetch_array( $sql ) ) {
?>
<option value="<?php echo $quickdata['id']; ?>" <?php if( $cidade == $quickdata['id'] ) { echo 'SELECTED'; }?>><?php echo $quickdata['nome']; ?></option>
<?php } ?>