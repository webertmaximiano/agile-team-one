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

		<div class="estabelecimentos">

			<div class="row">

				<div class="tv-infinite">

					<?php
					$exibir = "4";
					$query = "";
					$query .= 
					"
					SELECT estabelecimento.id as estabelecimento_id, estabelecimento.nome as estabelecimento_nome, estabelecimento.perfil as estabelecimento_perfil, estabelecimento.capa as estabelecimento_capa, estabelecimento.funcionamento as estabelecimento_funcionamento , estabelecimento.cor as estabelecimento_cor, estabelecimento.subdominio as estabelecimento_subdominio 
					FROM estabelecimentos AS estabelecimento 

					INNER JOIN segmentos AS segmentos 
					ON estabelecimento.segmento = segmentos.id  

					INNER JOIN cidades AS cidades 
					ON estabelecimento.cidade = cidades.id  

					INNER JOIN produtos AS produtos 
					ON produtos.rel_estabelecimentos_id = estabelecimento.id  

					WHERE 
					estabelecimento.funcionalidade_marketplace = '1' AND 
					estabelecimento.status = '1' AND 
					estabelecimento.status_force != '1' AND 
					cidades.id = '$cidade' AND
					segmentos.id = '$categoria' 
					GROUP BY estabelecimento.id 
					ORDER BY produtos.last_modified 
					LIMIT $exibir
					";					
					$query_estabelecimentos = mysqli_query( $db_con, $query );
					while ( $data_estabelecimentos = mysqli_fetch_array( $query_estabelecimentos ) ) {
					$gourl = $httprotocol.$data_estabelecimentos['estabelecimento_subdominio'].".".$simple_url;
					?>

						<div class="col-md-3 col-infinite">

							<div class="produto estabelecimento">

								<a href="<?php echo $gourl; ?>" title="<?php echo $data_estabelecimentos['estabelecimento_nome']; ?>">
									
									<div class="capa" style="background: <?php echo $data_estabelecimentos['estabelecimento_cor']; ?> url(<?php echo thumber( $data_estabelecimentos['estabelecimento_capa'], 450 ); ?>) no-repeat center center;">
										<div class="nome">
											<div class="perfil">
												<div class="holder">
													<img src="<?php echo thumber( $data_estabelecimentos['estabelecimento_perfil'], 400 ); ?>"/>
												</div>
											</div>
											<span class="subnome"><?php echo htmlclean( $data_estabelecimentos['estabelecimento_nome'] ); ?></span>
										</div>
									</div>
									<div class="status">
										<?php if( $data_estabelecimentos['estabelecimento_funcionamento'] == "1" ) { ?>
											<div class="aberto">
												<span><i class="lni lni lni-checkmark-circle"></i> Aberto</span>
											</div>
										<?php } else { ?>
											<div class="fechado">
												<span><i class="lni lni lni-cross-circle"></i> Fechado</span>
											</div>
										<?php } ?>
									</div>
									<div class="detalhes"><i class="icone icone-sacola"></i> <span>Comprar</span></div>

								</a>

							</div>

						</div>

					<?php } ?>

					<div class="col-md-3 col-infinite col-infinite-last visible-xs visible-sm">

						<a class="vertudo" href="<?php echo $app['url']; ?>/estabelecimentos?categoria=<?php echo $data_categoria['categoria_id']; ?>">Ver tudo <i class="lni lni-arrow-right"></i></a>

					</div>

				</div>

			</div>

		</div>

	</div>

	<?php } ?>

</div>