<option value="">Cidade</option>
<?php
include('../_includes/config.php'); 
global $simple_url;
global $httprotocol;
$estado = mysqli_real_escape_string( $db_con, $_GET['estado'] );
$cidade = mysqli_real_escape_string( $db_con, $_GET['cidade'] );

$query_cidades = 
"
SELECT *, count(*) as total, cidades.nome as cidade_nome, cidades.estado as cidade_estado , cidades.id as cidade_id
FROM cidades AS cidades 

INNER JOIN estabelecimentos AS estabelecimentos 
ON cidades.id = estabelecimentos.cidade 

WHERE 
estabelecimentos.funcionalidade_marketplace = '1' AND 
estabelecimentos.status = '1' AND 
estabelecimentos.status_force != '1' AND 
estabelecimentos.excluded != '1' 

";

if( $estado ) {
	$query_cidades .= "AND cidades.estado = '$estado' ";
}

$query_cidades .= " 
GROUP BY cidades.id 
ORDER BY cidades.nome ASC
";
$query_cidades = mysqli_query( $db_con, $query_cidades );
while ( $data_cidade = mysqli_fetch_array( $query_cidades ) ) {
?>
<option value="<?php echo $httprotocol.data_info( "cidades",$data_cidade['cidade_id'],"subdominio" ).".".$simple_url; ?>" <?php if( $cidade == $data_cidade['cidade_id'] ) { echo 'SELECTED'; }?>><?php echo $data_cidade['cidade_nome']; ?></option>
<?php } ?>