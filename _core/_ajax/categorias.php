<option value=""></option>
<?php
include('../_includes/config.php'); 
$categoria = mysqli_real_escape_string( $db_con, $_GET['categoria'] );
$estabelecimento = mysqli_real_escape_string( $db_con, $_GET['estabelecimento'] );
if( $estabelecimento ) {
	$sql = mysqli_query( $db_con, "SELECT * FROM categorias WHERE rel_estabelecimentos_id = '$estabelecimento' ORDER BY nome ASC LIMIT 9999" );
}
while( $quickdata = mysqli_fetch_array( $sql ) ) {
?>
<option value="<?php echo $quickdata['id']; ?>" <?php if( $categoria == $quickdata['id'] ) { echo 'SELECTED'; }?>><?php echo $quickdata['nome']; ?></option>
<?php } ?>