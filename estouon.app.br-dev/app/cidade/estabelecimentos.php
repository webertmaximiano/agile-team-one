<?php
// CORE
include($virtualpath.'//_layout/define.php');
// APP
global $app;
global $inparametro;
global $gopath;
global $just_url;
global $httprotocol;
$back_button = "true";
// Querys
$app_id = $app['id'];
$busca = mysqli_real_escape_string( $db_con, $_GET['busca'] );
$categoria = mysqli_real_escape_string( $db_con, $_GET['categoria'] );
$filtro = mysqli_real_escape_string( $db_con, $_GET['filtro'] );
// Has content
$query_content = mysqli_query( $db_con, "SELECT * FROM segmentos WHERE id = '$categoria' ORDER BY nome ASC LIMIT 1" );
$has_content = mysqli_num_rows( $query_content );
if( !$categoria ) {
	$has_content = "true";
}
$getdata = "?";
if( $app['categoria'] ) {
	$getdata .= "categoria=".$app['categoria']."&";
}
if( $busca ) {
	$getdata .= "busca=".$busca."&";
}
$getdata_unfilter = substr( $getdata , 0, -1);
if( $getdata_unfilter == "" ) {
	$getdata_unfilter = "?";
}
if( $filtro ) {
	$getdata .= "filtro=".$filtro."&";
}

$getdata = substr( $getdata , 0, -1);


// SEO
$categoria_nome = data_info( "segmentos", $categoria, "nome" );
if( !$categoria_nome && $categoria ) {
	$categoria_nome = "Categoria inválida";
} else {
	if( !$categoria_nome ) {
		$categoria_nome = "Todas as categorias";
	}
}
if( $filtro == "4" ) {
	$categoria_nome .= " em oferta";
}
if( $busca ) {
	$categoria_nome = "Buscar";
}
$seo_subtitle = $app['title']."-".$app['uf']." - ".$categoria_nome;
$seo_description = $categoria_nome." ".$app['title']." no ".$seo_title;
$seo_keywords = $app['title'].", ".$seo_title;
$seo_image = thumber( $app['avatar_clean'], 400 );
// HEADER
$system_header .= "";
include($virtualpath.'/_layout/head.php');
include($virtualpath.'/_layout/top.php');
include($virtualpath.'/_layout/sidebars.php');
include($virtualpath.'/_layout/modal.php');
instantrender();
?>

<div class="sceneElement">

	<div class="minfit">

		<?php if( $has_content ) { ?>

			<div class="middle">

				<div class="back-gray">

					<div class="container">

						<div class="row rowtitle hidden-xs hidden-sm">

							<?php 
							if( !$busca ) { 
							$categoria_name = htmlclean( data_info( "segmentos", $categoria, "nome" ) );
							if( !$categoria_name ) { 
								$categoria_name = "Geral"; 
							}
							?>

								<div class="col-md-12">
									<div class="title-icon">
										<span><?php echo $categoria_name; ?></span>
									</div>
									<div class="bread-box">
										<div class="bread">
											<a href="<?php echo $app['url']; ?>"><i class="lni lni-home"></i></a>
											<span>/</span>
											<a href="<?php echo $app['url']; ?>/<?php echo $gopath; ?>/<?php echo $inparametro; ?><?php echo $getdata; ?>">Categorias</a>
											<span>/</span>
											<a href="<?php echo $app['url']; ?>/<?php echo $gopath; ?>/<?php echo $inparametro; ?><?php echo $getdata; ?>"><?php echo $categoria_name; ?></a>
										</div>
									</div>
								</div>

							<?php } else { ?>

								<div class="col-md-12">
									<div class="title-icon">
										<span>Buscar:</span>
									</div>
									<div class="bread-box">
										<div class="bread">
											<a href="<?php echo $app['url']; ?>"><i class="lni lni-home"></i></a>
											<span>/</span>
											<a href="<?php echo $app['url']; ?>/<?php echo $gopath; ?>/<?php echo $inparametro; ?><?php echo $getdata; ?>">Buscar: <u><?php echo htmlclean( $busca ); ?></u></a>
										</div>
									</div>
								</div>

							<?php } ?>

							<div class="col-md-12 hidden-xs hidden-sm">
								<div class="clearline"></div>
							</div>

						</div>

					</div>

				</div>

				<div class="container nopadd">

					<div class="breadcrumb-gray">

						<div class="row">

							<div class="col-md-12">
					
						 		<div class="search-bar-mobile visible-xs visible-sm">

									<form class="align-middle" action="<?php echo $app['url']; ?>/<?php echo $gopath; ?>" method="GET">

										<input type="text" name="busca" placeholder="Digite sua busca..." value="<?php echo htmlclean( $_GET['busca'] ); ?>"/>
										<input type="hidden" name="categoria" value="<?php echo $categoria; ?>"/>
										<button>
											<i class="lni lni-search-alt"></i>
										</button>
										<div class="clear"></div>

									</form>

								</div>

							</div>

						</div>

					</div>

				</div>

				<div class="border-bottom">

					<div class="container">

						<div class="row">

							<div class="col-md-12">
								<div class="tv-infinite tv-infinite-menu">
									<?php
									$categoria = $app['categoria'];
									if( $busca ) {
										$query_busca = "&busca=".$busca;
									}
									if( $filtro ) {
										$query_busca = "&filtro=".$filtro;
									}
									?>
									<div class="tv-infinite tv-infinite-menu tv-tabs">
										<a href="<?php echo $app['url']; ?>/produtos?categoria=<?php echo $categoria.$query_busca; ?>"><i class="lni lni-shopping-basket colored"></i> Produtos</a>
										<a class="active" href="<?php echo $app['url']; ?>/estabelecimentos?categoria=<?php echo $categoria.$query_busca; ?>"><i class="lni lni-home colored"></i> Estabelecimentos</a>
									</div>
								</div>
							</div>

						</div>

					</div>

				</div>

				<div class="container">

					<div class="categorias no-bottom-mobile">

						<?php
						// Config
						$categoria = $app['categoria'];
						$cidade = $app['id'];
						$limite = 16;
						$pagina = $_GET["pagina"] == "" ? 1 : $_GET["pagina"];
						$inicio = ($pagina * $limite) - $limite;

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
						estabelecimento.excluded != '1' AND 
						cidades.id = '$cidade' 
						";

						if( $categoria ) {
							$query .= "AND estabelecimento.segmento = '$categoria' ";
						} else {
							$query .= "AND segmentos.censura != '1' ";
						}

						if( $busca ) {
						  $query .= "AND estabelecimento.nome LIKE '%$busca%' ";
						}

						$query .= "
						GROUP BY estabelecimento.id 
						";

						$query_full = $query;

						if( $filtro == "1" OR !$filtro ) {
							$query .= "ORDER BY produtos.last_modified DESC LIMIT $inicio,$limite";
						}

						if( $filtro == "2" ) {
							$query .= "ORDER BY estabelecimento.nome ASC LIMIT $inicio,$limite";
						}

						// Run

						$sql = mysqli_query( $db_con, $query );

						$total_results = mysqli_num_rows( $sql );

						$sql_full = mysqli_query( $db_con, $query_full );

						$total_results_full = mysqli_num_rows( $sql_full );

						$total_paginas = Ceil($total_results_full / $limite) + ($limite / $limite);

						if( !$pagina OR $pagina > $total_paginas OR !is_numeric($pagina) ) {

						    $pagina = 1;

						}

						?>

						<div class="categoria no-bottom-mobile">

							<div class="row">

								<div class="col-md-6 col-sm-6 col-xs-6">
									
									<strong class="counter"><?php echo $total_results_full; ?></strong>
									<span class="title">Itens:</span>
								
								</div>

								<div class="col-md-6 col-sm-6 col-xs-6">
									
									<div class="filter-select pull-right">
										<i class="outside lni lni-funnel"></i>
										<div class="fake-select">
											<i class="lni lni-chevron-down"></i>
											<select name="filtro" onchange="selecturl()">
											    <option <?php if( $_GET['filtro'] == "1" ) { echo "SELECTED"; }; ?> value="<?php echo $app['url']; ?>/<?php echo $gopath; ?><?php echo $getdata_unfilter; ?>&filtro=1">Recente</option>
											    <option <?php if( $_GET['filtro'] == "2" ) { echo "SELECTED"; }; ?> value="<?php echo $app['url']; ?>/<?php echo $gopath; ?><?php echo $getdata_unfilter; ?>&filtro=2">Nome</option>
											</select>
											<div class="clear"></div>
										</div>
										<div class="clear"></div>
									</div>
								
								</div>

							</div>

							<div class="estabelecimentos">

								<div class="row tv-grid">

									<?php
									while ( $data_estabelecimentos = mysqli_fetch_array( $sql ) ) {
									$gourl = $httprotocol.$data_estabelecimentos['estabelecimento_subdominio'].".".$simple_url;
									?>

										<div class="col-md-3 col-sm-6 col-xs-6">

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

									<?php if( $total_results == 0 ) { ?>

										<div class="col-md-12">

											<span class="nulled nulled-categoria">Nenhum item encontrado! Tente novamente!</span>

										</div>

									<?php } ?>

								</div>

							</div>

						</div>

					</div>

					<?php 
					if( $total_paginas > 2 ) { ?>

			        <div class="paginacao">

			          <ul class="pagination">

			            <?php
			            $paginationpath = "estabelecimentos/".$inparametro;
			            if($pagina > 1) {
			              $back = $pagina-1;
			              echo '<li class="page-item pagination-back"><a class="page-link" href=" '.$app['url'].'/'.$paginationpath.'/?pagina='.$back.$getdata.' "><i class="lni lni-chevron-left"></i></a></li>';
			            }
			     
			              for($i=$pagina-1; $i <= $pagina-1; $i++) {

			                  if($i > 0) {
			                  
			                      echo '<li class="page-item pages-before"><a class="page-link" href=" '.$app['url'].'/'.$paginationpath.'/?pagina='.$i.$getdata.' ">'.$i.'</a></li>';
			                  }

			              }

			              if( $pagina >= 1 ) {

			                echo '<li class="page-item active"><a class="page-link" href=" '.$app['url'].'/'.$paginationpath.'/?pagina='.$i.$getdata.'" class="page-link">'.$i.'</a></li>';

			              }

			              for($i=$pagina+1; $i <= $pagina+1; $i++) {

			                  if($i >= $total_paginas) {
			                    break;
			                  }  else {
			                      echo '<li class="page-item pages-after"><a class="page-link" href=" '.$app['url'].'/'.$paginationpath.'/?pagina='.$i.$getdata.' ">'.$i.'</a></li> ';
			                  }
			              
			              }

			            if($pagina < $total_paginas-1) {
			              $next = $pagina+1;
			              echo '<li class="page-item pagination-next"><a class="page-link" href=" '.$app['url'].'/'.$paginationpath.'/?pagina='.$next.$getdata.' "><i class="lni lni-chevron-right"></i></a></li>';
			            }

			            ?>

			          </ul>

			        </div>

			    	<?php } ?>

				</div>

			</div>

		<?php } else { ?>

			<div class="middle">

				<div class="container">

					<div class="row">

						<span class="nulled nulled-content">Categoria inválida ou removida!</span>

					</div>

				</div>

			</div>

		<?php } ?>

	</div>

</div>

<?php 
// FOOTER
$system_footer .= "";
include($virtualpath.'/_layout/rdp.php');
include($virtualpath.'/_layout/footer.php');
?>

<script>

$(document).ready(function(){
	var active = $(".tv-infinite-menu .active");
	var activeWidth = active.width();
	// var pos = active.position().left + activeWidth;
	var pos = active.position().left-15;
	$('.tv-infinite-menu').animate({ scrollLeft: pos  }, 500 );
});

$("select[name='filtro']").change(function(){
	var theurl = $(this).children("option:selected").val();
	window.location.href = theurl;
});


</script>