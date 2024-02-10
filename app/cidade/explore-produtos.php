<div class="categorias">

	<?php
	$cidade = $app['id'];
	$query_categoria = 
	"
	SELECT segmentos.nome as segmento_nome,segmentos.id as segmento_id 
	FROM estabelecimentos AS estabelecimentos 

	INNER JOIN produtos AS produtos 
	ON produtos.rel_estabelecimentos_id = estabelecimentos.id 

	INNER JOIN segmentos AS segmentos 
	ON estabelecimentos.segmento = segmentos.id 

	WHERE estabelecimentos.cidade = '$cidade' AND 
	estabelecimentos.funcionalidade_marketplace = '1' AND 
	estabelecimentos.status = '1' AND 
	estabelecimentos.status_force != '1' AND 
	estabelecimentos.excluded != '1' AND 
	segmentos.censura != '1' 

	GROUP BY segmentos.id 
	ORDER BY produtos.last_modified ASC

	LIMIT 999
	";
	$query_categoria = mysqli_query( $db_con, $query_categoria );
	while ( $data_categoria = mysqli_fetch_array( $query_categoria ) ) {
	$categoria = $data_categoria['segmento_id'];
	?>

	<div class="categoria">

		<div class="row">

			<div class="col-md-10 col-sm-10 col-xs-10">
				
				<span class="title"><?php echo htmlclean( $data_categoria['segmento_nome'] ); ?></span>
			
			</div>

			<div class="col-md-2 col-sm-2 col-xs-2">
				
				<a class="vertudo" href="<?php echo $app['url']; ?>/produtos/?categoria=<?php echo $categoria; ?>"><i class="lni lni-arrow-right"></i></a>
			
			</div>

		</div>

		<div class="produtos">

			<div class="row">

				<div class="tv-infinite">

					<?php
					$exibir = "4";
					$query = "";
					$query .= 
					"
					SELECT produtos.destaque as produto_destaque, produtos.id as produto_id, produtos.valor as produto_valor, produtos.valor_promocional as produto_valor_promocional, produtos.nome as produto_nome , estabelecimentos.subdominio as subdominio  
					FROM produtos AS produtos 

					INNER JOIN estabelecimentos AS estabelecimentos 
					ON produtos.rel_estabelecimentos_id = estabelecimentos.id  

					INNER JOIN segmentos AS segmentos 
					ON estabelecimentos.segmento = segmentos.id  

					INNER JOIN cidades AS cidades 
					ON estabelecimentos.cidade = cidades.id  

					INNER JOIN categorias AS categorias 
					ON produtos.rel_categorias_id = categorias.id  

					WHERE 
					estabelecimentos.funcionalidade_marketplace = '1' AND 
					estabelecimentos.status = '1' AND 
					estabelecimentos.status_force != '1' AND 
					segmentos.censura != '1' AND 
					cidades.id = '$cidade' AND 
					estabelecimentos.segmento = '$categoria' AND 
					produtos.visible = '1' AND 
					produtos.status = '1' 
					GROUP BY produtos.id 
					ORDER BY categorias.last_modified DESC 
					LIMIT $exibir
					";
					$query_produtos = mysqli_query( $db_con, $query );
					while ( $data_produtos = mysqli_fetch_array( $query_produtos ) ) {
						// Seta valor
						if( $data_produtos['oferta'] == "1" ) {
							$valor_final = $data_produtos['produto_valor_promocional'];
						} else {
							$valor_final = $data_produtos['produto_valor'];
						}
					$gourl = $httprotocol.$data_produtos['subdominio'].".".$simple_url;
					?>

						<div class="col-md-3 col-infinite">

								<div class="produto">

									<a href="<?php echo $gourl; ?>/produto/<?php echo $data_produtos['produto_id']; ?>" title="<?php echo $data_produtos['produto_nome']; ?>">
										
										<div class="capa" style="background: url(<?php echo thumber( $data_produtos['produto_destaque'], 450 ); ?>) no-repeat center center;">
											<span class="nome"><?php echo htmlclean( $data_produtos['produto_nome'] ); ?></span>
										</div>
										<?php if( $data_produtos['oferta'] == "1" ) { ?>
											<span class="valor_anterior">De: <?php echo dinheiro( $data_produtos['produto_valor'], "BR" ); ?></span>
										<?php } ?>
										<span class="apenas <?php if( $data_produtos['oferta'] != "1" ) { echo 'apenas-single'; }; ?>">
											<?php if( has_variacao( $data_produtos['produto_id'] ) ) { echo "A partir de apenas"; } else { echo "Por <br/> apenas"; }; ?>
										</span>
										<span class="valor">R$ <?php echo dinheiro( $valor_final, "BR" ); ?></span>
										<div class="detalhes"><i class="icone icone-sacola"></i> <span>Comprar</span></div>

									</a>

								</div>

						</div>

					<?php } ?>

					<div class="col-md-3 col-infinite col-infinite-last visible-xs visible-sm">

						<a class="vertudo" href="<?php echo $app['url']; ?>/produtos?categoria=<?php echo $data_categoria['categoria_id']; ?>">Ver tudo <i class="lni lni-arrow-right"></i></a>

					</div>

				</div>

			</div>

		</div>

	</div>

	<?php } ?>

</div>