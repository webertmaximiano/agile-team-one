<?php
global $inacao;
$categoria = $app['categoria'];
if( $busca ) {
	$query_busca = "&busca=".$busca;
}
if( $filtro ) {
	$query_busca = "&filtro=".$filtro;
}
?>
<a class="<?php if( !$inacao ){ echo 'active'; }; ?>" href="<?php echo $app['url']; ?>">
	<span><i class="lni lni-layers explorer colored"></i> Explorar</span>
</a>
<a class="<?php if( ($inacao == "estabelecimentos" OR $inacao == "produtos") && !$categoria ){ echo 'active'; }; ?>" href="<?php echo $app['url']; ?>/<?php echo $gopath;?>?<?php echo $query_busca; ?>">
	<span>Geral</span>
</a>
<?php
$cidade = $app['id'];
$query_categorias = 
"
SELECT segmentos.nome as segmento_nome,segmentos.id as segmento_id 
FROM estabelecimentos AS estabelecimentos 

INNER JOIN produtos AS produtos 
ON produtos.rel_estabelecimentos_id = estabelecimentos.id 

INNER JOIN segmentos AS segmentos 
ON estabelecimentos.segmento = segmentos.id 

WHERE estabelecimentos.cidade = '$cidade' 
AND estabelecimentos.funcionalidade_marketplace = '1' 

GROUP BY segmentos.id 
ORDER BY produtos.last_modified ASC
";
$query_categorias = mysqli_query( $db_con, $query_categorias );
while ( $data_categoria = mysqli_fetch_array( $query_categorias ) ) {
?>
<a class="<?php if( $data_categoria['segmento_id'] == $categoria ){ echo 'active'; }; ?>" href="<?php echo $app['url']; ?>/<?php echo $gopath; ?>/?categoria=<?php echo $data_categoria['segmento_id']; ?><?php if( $query_busca ) { echo "&".$query_busca; }; ?>">
	<span><?php echo $data_categoria['segmento_nome']; ?></span>
</a>
<?php } ?>
