<?php
// CORE
include($virtualpath.'/_layout/define.php');
//URL
$pegaurl =  "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
// APP
global $app;
is_active( $app['id'] );
global $inparametro;
$back_button = "true";
// Querys
$app_id = $app['id'];
$produto = $inparametro;
// Has content
$query_content = mysqli_query( $db_con, "SELECT * FROM produtos WHERE rel_estabelecimentos_id = '$app_id' AND id = '$produto' AND status = '1' ORDER BY id ASC LIMIT 1" );
$data_content = mysqli_fetch_array( $query_content );
$has_content = mysqli_num_rows( $query_content );
// SEO
$seo_subtitle = $app['title']." - ".$data_content['nome'];
$seo_description = $data_content['descricao'];
$seo_keywords = $app['title'].", ".$seo_title.", ".$data_content['nome'];
$seo_image = thumber( $data_content['destaque'], 400 );
// HEADER
$system_header .= "";
include($virtualpath.'/_layout/head.php');
include($virtualpath.'/_layout/top.php');
include($virtualpath.'/_layout/sidebars.php');
include($virtualpath.'/_layout/modal.php');
instantrender();
?>

<div class="sceneElement">

	<div class="header-interna">

		<div class="locked-bar visible-xs visible-sm">

			<div class="avatar">
				<div class="holder">
					<a href="<?php echo $app['url']; ?>">
						<img src="<?php echo $app['avatar']; ?>"/>
					</a>
				</div>	
			</div>

		</div>

		<div class="holder-interna holder-interna-nopadd visible-xs visible-sm"></div>

	</div>

	<?php if( $has_content ) { ?>

		<div class="middle">

			<div class="container nopaddmobile">

				<div class="row rowtitle hidden-xs hidden-sm">

					<div class="col-md-12">
						<div class="title-icon">
							<span><?php echo $data_content['nome']; ?></span>
						</div>
						<div class="bread-box">
							<div class="bread">
								<a href="<?php echo $app['url']; ?>"><i class="lni lni-home"></i></a>
								<span>/</span>
								<a href="<?php echo $app['url']; ?>/categoria/<?php echo data_info( "categorias", $data_content['rel_categorias_id'], "id" ); ?>">Categorias</a>
								<span>/</span>
								<a href="<?php echo $app['url']; ?>/categoria/<?php echo data_info( "categorias", $data_content['rel_categorias_id'], "id" ); ?>"><?php echo data_info( "categorias", $data_content['rel_categorias_id'], "nome" ); ?></a>
								<span>/</span>
								<a href="<?php echo $app['url']; ?>/produto/<?php echo $produto; ?>"><?php echo $data_content['nome']; ?></a>
							</div>
						</div>
					</div>

					<div class="col-md-12 hidden-xs hidden-sm">
						<div class="clearline"></div>
					</div>

				</div>

				<div class="single-produto nost">

					<div class="row">

						<div class="col-md-5">

							<div class="galeria">

								<div class="row">

									<div class="col-md-12">

										<?php
										$query_galeria = "SELECT * FROM midia WHERE rel_id = '$produto' ORDER BY id ASC";
										?>

										<div id="carouselgaleria" class="carousel slide">

											<div class="carousel-inner">

												<div class="item zoomer active">
													<a data-fancybox="galeria" href="<?php echo imager( $data_content['destaque'] ); ?>">
														<img src="<?php echo imager( $data_content['destaque'] ); ?>" alt="<?php echo $data_content['nome']; ?>" title="<?php echo $data_content['nome']; ?>"/>
													</a>
												</div>

												<?php
												$actual = 0;
												$sql_galeria = mysqli_query( $db_con, $query_galeria );
												$total_galeria = mysqli_num_rows( $sql_galeria );
												while ( $data_galeria = mysqli_fetch_array( $sql_galeria ) ) {
												?>

												<div class="item zoomer">
													<a data-fancybox="galeria" href="<?php echo imager( $data_galeria['url'] ); ?>">
														<img src="<?php echo imager( $data_galeria['url'] ); ?>" alt="<?php echo $data_content['nome']; ?>" title="<?php echo $data_content['nome']; ?>"/>
													</a>
												</div>

												<?php $actual++; } ?>

											</div>

											<?php if( $total_galeria >= 1 ) { ?>

												<a class="left seta seta-esquerda carousel-control" href="#carouselgaleria" data-slide="prev">
													<span class="glyphicon glyphicon-chevron-left"></span>
												</a>
												<a class="right seta seta-direita carousel-control" href="#carouselgaleria" data-slide="next">
													<span class="glyphicon glyphicon-chevron-right"></span>
												</a>

											<?php } ?>

										</div>

									</div>

								</div>

							</div>

						</div>

						<div class="col-md-7">

							<div class="padd-container-mobile">

								<div class="produto-detalhes">

									<div class="row visible-xs visible-sm">
										<div class="col-md-12">
											<div class="nome">
												<span><?php echo htmlclean( $data_content['nome'] ); ?></span>
											</div>
										</div>
									</div>
									
									<div class="row">

										<div class="col-md-3 hidden-xs hidden-sm">

											<span class="thelabel thelabel-normal">Link do Produto:</span>

										</div>

										<div class="col-md-9">

											 
											<div class="row">
												<div class="col-md-12">
													<div class="ref">
														<span><a href="https://api.whatsapp.com/send?text=<?php print $pegaurl; ?>" target="_blank" class="btn btn-success btn-sm" style="color:#FFFFFF"><i class="lni lni-whatsapp"></i> Compartilhe no WhatsApp</a></span>
													</div>
												</div>
											</div>
										 

										</div>

									</div>

									<div class="row">

										<div class="col-md-3 hidden-xs hidden-sm">

											<span class="thelabel thelabel-normal">REF:</span>

										</div>

										<div class="col-md-9">

											<?php if( $data_content['ref'] ) { ?>
											<div class="row">
												<div class="col-md-12">
													<div class="ref">
														<span>#<?php echo htmlclean( $data_content['ref'] ); ?></span>
													</div>
												</div>
											</div>
											<?php } ?>

										</div>

									</div>

									<div class="row">

										<div class="col-md-3 hidden-xs hidden-sm">

											<span class="thelabel thelabel-normal">Detalhes:</span>

										</div>

										<div class="col-md-9">

											<?php if( $data_content['descricao'] ) { ?>
											<div class="row">
												<div class="col-md-12">
													<div class="descricao">
														<span><?php echo nl2br( htmlclean( $data_content['descricao'] ) ); ?></span>
													</div>
												</div>
											</div>
											<?php } ?>
											
											<?php
														// Seta valor
														if( $data_content['oferta'] == "1" ) {
															$valor_final = $data_content['valor_promocional'];
														} else {
															$valor_final = $data_content['valor'];
														}
													?>
											
											<?php if($valor_final > '0.00'){ ?>
											<div class="row">
												<div class="col-md-12">
													
													
													<div class="valor_anterior">
														<span><?php if( $data_content['oferta'] == "1" ) { ?>De: <strike><?php echo dinheiro( $data_content['valor'], "BR" ); ?></strike><?php } ?> <?php if( has_variacao( $data_content['id'] ) ) { echo "por"; } else { echo "Apenas"; }; ?></span>
													</div>
													
													<div class="valor">
														<span>R$ <?php echo dinheiro( $valor_final, "BR" ); ?></span>
													</div>
												</div>
											</div>
											<?php } ?>
											
											

										</div>

									</div>

								</div>

								<form id="the_form" method="POST">

									<?php if( data_info( "estabelecimentos", $app['id'], "funcionamento") == "1" ) { ?>

									<div class="comprar">

										<?php if( has_variacao($data_content['id']) && $app['funcionalidade_variacao'] == "1" ) { ?>

											<?php
											$x = 0;
											$variacao = json_decode( $data_content['variacao'], TRUE );
											for ( $x=0; $x < count( $variacao ); $x++ ){
											?>

											<div class="line line-variacao" min="<?php echo htmljson( $variacao[$x]['escolha_minima'] ); ?>" max="<?php echo htmljson( $variacao[$x]['escolha_maxima'] ); ?>">
												<div class="row">
													<div class="col-md-6">
														<span class="thelabel pull-left">
															<?php echo htmljson( $variacao[$x]['nome'] ) ;?>
														</span>
													</div>
													<div class="col-md-6">
														<span class="escolhas pull-right">
															Minímo: <?php echo htmljson( $variacao[$x]['escolha_minima'] ) ;?> e Máximo: <?php echo htmljson( $variacao[$x]['escolha_maxima'] ) ;?>
														</span>
													</div>
												</div>
												<div class="row">	
													<div class="col-md-12">

														<div class="opcoes">

															<?php
															for( $y=0; $y < count( $variacao[$x]['item'] ); $y++ ){
															?>

															<div class="opcao <?php if( variacao_opcao_ativa( $data_content['id'],$x,$y ) ) { echo 'active'; }; ?>" variacao-item="<?php echo $y; ?>" nomeda-variacao="<?php echo htmljson( $variacao[$x]['nome'] ) ;?>" valor-adicional="<?php echo htmljson( $variacao[$x]['item'][$y]['valor'] ); ?>">

																<div class="check">
																	<i class="lni"></i>
																</div>
																<div class="detalhes">
																	<span class="titulo">
																		<?php echo htmljson( $variacao[$x]['item'][$y]['nome'] ); ?>
																		<?php if( $variacao[$x]['item'][$y]['valor'] ) { ?>
																		(+ R$ <?php echo dinheiro( htmljson( $variacao[$x]['item'][$y]['valor'] ), "BR" ); ?>)
																		<?php } ?>
																	</span>
																	<span class="descricao"><?php echo htmljson( $variacao[$x]['item'][$y]['descricao'] ); ?></span>
																</div>
																<div class="clear"></div>

															</div>
															

															<?php } ?>

														</div>
														<div class="clear"></div>
														<input class="fakehidden variacao_validacao" type="text" name="variacao_<?php echo $x; ?>_validacao" value=""/>
														<input class="fakehidden variacao_escolha" type="text" name="variacao[<?php echo $x; ?>]" value=""/>
														<div class="error-holder"></div>
													</div>
												</div>
											</div>

											<?php } ?>

										<?php } ?>

										<div class="line quantidade">
											<div class="row">
												<div class="col-md-3 col-sm-2 col-xs-2">
													<span class="thelabel hidden-xs hidden-sm">Quantidade:</span>
													<span class="thelabel visible-xs visible-sm">Qntd:</span>
												</div>
												<div class="col-md-9 col-sm-10 col-xs-10">
													<div class="campo-numero pull-right">
														<i class="decrementar lni lni-minus" onclick="decrementar('#quantidade');"></i>
														<input id="quantidade" type="number" name="quantidade" value="<?php if( $_SESSION['sacola'][$app['id']][$data_content['id']]['quantidade'] ){ echo htmlclean( $_SESSION['sacola'][$app['id']][$data_content['id']]['quantidade'] ); } else { echo '1'; } ?>"/>
														<i class="incrementar lni lni-plus" onclick="incrementar('#quantidade');"></i>
													</div>
												</div>
											</div>
										</div>
										<div class="line observacoes">
											<div class="row">
												<div class="col-md-3 col-sm-2 col-xs-2">
													<span class="thelabel hidden-xs hidden-sm">Observações:</span>
													<span class="thelabel visible-xs visible-sm">Obs:</span>
												</div>
												<div class="col-md-9 col-sm-10 col-xs-10">
													<textarea id="observacoes" rows="5" name="observacoes" placeholder="Observações:"><?php echo htmlclean( $_SESSION['sacola'][$app['id']][$data_content['id']]['observacoes'] ); ?></textarea>
												</div>
											</div>
										</div>
										<div class="line botoes subtotal-adicionar">
											<div class="row">
												<div class="col-md-6">
													<div class="subtotal">
														<strong>Valor:</strong>
														<span>R$ <?php echo dinheiro( $data_content['valor_promocional'], "BR"); ?></span>
													</div>
												</div>
												<div class="col-md-6">
													<span class="sacola-adicionar botao-acao pull-right"><i class="icone icone-sacola"></i> <span>Adicionar a sacola</span></span>
												</div>
											</div>
										</div>

									</div>

									<?php } else { ?>

									<div class="comprar">

										<div class="line quantidade">
											<div class="row">
												<div class="col-md-3 col-sm-2 col-xs-2 hidden-xs hidden-sm">
													<span class="thelabel">Aviso:</span>
												</div>
												<div class="col-md-9 col-sm-12 col-xs-12">
													<span>O estabelecimento encontra-se temporariamente fechado, impossibilitando a realização de pedidos no momento!</span>
												</div>
											</div>
										</div>
										<div class="line botoes">
											<div class="row">
												<div class="col-md-12">
													<a target="_blank" href="https://wa.me/55<?php echo clean_str( htmlclean( $app['contato_whatsapp'] ) ); ?>" class="botao-acao pull-right"><i class="lni lni-whatsapp"></i> <span>Entre em contato</span></a>
												</div>
											</div>
										</div>

									</div>

									<?php } ?>

								</form>

							</div>

						</div>

					</div>

				</div>

			</div>

		</div>

		<?php
		$cat_id = $data_content['rel_categorias_id'];
		$eid = $app['id'];
		$query_relacionados = mysqli_query( $db_con, "SELECT * FROM produtos WHERE rel_estabelecimentos_id = '$eid' AND rel_categorias_id = '$cat_id' AND id != '$produto' AND visible = '1' AND status = '1' ORDER BY id DESC LIMIT 4" );
		$total_relacionados = mysqli_num_rows( $query_relacionados );
		?>

		<?php if( $total_relacionados >= 1 ) { ?>

		<div class="container relacionados">

			<div class="categoria">

				<div class="row">

					<div class="col-md-10 col-sm-10 col-xs-10">
						
						<span class="title">Veja também</span>
					
					</div>

					<div class="col-md-2 col-sm-2 col-xs-2">
						
						<a class="vertudo" href="<?php echo $app['url']; ?>/categoria/<?php echo $cat_id; ?>"><i class="lni lni-arrow-right"></i></a>
					
					</div>

				</div>

				<div class="produtos">

					<div class="row tv-grid">

						<div class="tv-infinite">

							<?php
							while ( $data_produtos = mysqli_fetch_array( $query_relacionados ) ) {
							// Seta valor
							if( $data_produtos['oferta'] == "1" ) {
								$valor_final = $data_produtos['valor_promocional'];
							} else {
								$valor_final = $data_produtos['valor'];
							}
							?>

								<div class="col-md-3 col-infinite">

									<div class="produto">

										<a href="<?php echo $app['url']; ?>/produto/<?php echo $data_produtos['id']; ?>" title="<?php echo $data_produtos['nome']; ?>">
											
											<div class="capa" style="background: url(<?php echo thumber( $data_produtos['destaque'], 450 ); ?>) no-repeat center center;">
												<span class="nome"><?php echo htmlclean( $data_produtos['nome'] ); ?></span>
											</div>
											
											<?php if($valor_final > '0.00'){ ?>
											<?php if( $data_produtos['oferta'] == "1" ) { ?>
												<span class="valor_anterior">De: <?php echo dinheiro( $data_produtos['valor'], "BR" ); ?></span>
											<?php } ?>
											<span class="apenas <?php if( $data_produtos['oferta'] != "1" ) { echo 'apenas-single'; }; ?>">
												<?php if( has_variacao( $data_produtos['id'] ) ) { echo "Por apenas"; } else { echo "Por <br/> apenas"; }; ?>
											</span>
											<span class="valor">R$ <?php echo dinheiro( $valor_final, "BR" ); ?></span>
											<div class="detalhes"><i class="icone icone-sacola"></i> <span>Comprar</span></div>
											<?php } else { ?>
											<span class="apenas apenas-single">Selecionar opcionais</span>
											<span class="valor" style="color:#FFFFFF">.</span>
											<div class="detalhes"><i class="icone icone-sacola"></i> <span>Ver Item</span></div>
                                            <?php } ?>
										</a>

									</div>

								</div>

							<?php } ?>

						</div>

					</div>

				</div>

			</div>

		</div>

		<?php } else { ?>

		<div class="fillrelacionados visible-xs visible-sm"></div>

		<?php } ?>

	<?php } else { ?>

		<div class="middle">

			<div class="container">

				<div class="row">

					<span class="nulled nulled-content">Produto inválido ou inativo!</span>

				</div>

			</div>

		</div>

	<?php } ?>

</div>

<?php 
// FOOTER
$system_footer .= "";
include($virtualpath.'/_layout/rdp.php');
include($virtualpath.'/_layout/footer.php');
?>

<script>

$(document).on('change','#the_form',function(e){

	<?php
	if( $data_content['oferta'] == "1" ) {
		$valor_final = $data_content['valor_promocional'];
	} else {
		$valor_final = $data_content['valor'];
	}
	?>

	var total = parseFloat( "<?php echo $valor_final; ?>" );

	var vezes = parseFloat( $("#quantidade").val() );
	total = total * vezes;
	total = parseFloat( total ).toFixed(2);

	$('.subtotal').html('<strong>Total:</strong> R$ ' + total );

});

$("#the_form").trigger("change");

<?php if( has_variacao($data_content['id']) && $app['funcionalidade_variacao'] == "1" ) { ?>

	$(document).ready( function() {
	          
	  var form = $("#the_form");
	  form.validate({
	      focusInvalid: true,
	      invalidHandler: function() {
	      },
	      errorPlacement: function errorPlacement(error, element) { 
	      	element.closest(".line-variacao").find(".error-holder").html(error); 
	      },
	      rules:{
			<?php
			$variacao = json_decode( $data_content['variacao'], TRUE );
			$validacao = "";
			for ( $x=0; $x < count( $variacao ); $x++ ){
				$validacao .= "variacao_".$x."_validacao: {\n";
				if( htmljson( $variacao[$x]['escolha_minima'] ) >= 1 ) {
					$validacao .= "required: true,\n";
				}
				$validacao .= "min: ".htmljson( $variacao[$x]['escolha_minima'] ).",\n";
				$validacao .= "max: ".htmljson( $variacao[$x]['escolha_maxima'] )."\n";
				$validacao .= "},\n";
			}
			$validacao = trim( $validacao, ",\n" );
			echo $validacao;
			echo "\n";
			?>
	      },
	      messages:{
			<?php
			$variacao = json_decode( $data_content['variacao'], TRUE );
			$validacao = "";
			for ( $x=0; $x < count( $variacao ); $x++ ){
				$validacao .= "variacao_".$x."_validacao: {\n";
				if( htmljson( $variacao[$x]['escolha_minima'] ) >= 1 ) {
					$validacao .= "required: 'Campo obrigatório',\n";
				}
				$validacao .= "min: 'Você deve escolher ao menos ".htmljson( $variacao[$x]['escolha_minima'] )."',\n";
				$validacao .= "max: 'Você deve escolher no máximo ".htmljson( $variacao[$x]['escolha_maxima'] )."'\n";
				$validacao .= "},\n";
			}
			$validacao = trim( $validacao, ",\n" );
			echo $validacao;
			echo "\n";
			?>
	      }
	  });

	});

	function ativachecks() {

		$(".line-variacao").each(function() {
			var contachecks = 0;
			var selecteds = "";
			$(this).find(".opcao.active").each(function() {
				contachecks++;
				selecteds = selecteds + $(this).attr("variacao-item") + ",";
			});	
			selecteds = selecteds.substring(0,selecteds.length - 1);
			$(this).find(".variacao_validacao").attr("value",contachecks);
			$(this).find(".variacao_escolha").attr("value",selecteds);
		});
		$("#the_form").trigger("change");
		$(".variacao_validacao").valid();

	}

	$( ".opcoes .opcao" ).click(function() {

		var min = parseInt( $(this).closest(".line-variacao").attr("min") );
		var max = parseInt( $(this).closest(".line-variacao").attr("max") );
		var parcial = parseInt( $(this).closest(".line-variacao").find(".variacao_validacao").val() );

		if( $(this).hasClass("active") ) {
			$( this ).removeClass("active");
		} else {
			if( parcial < max ) {
				$( this ).addClass("active");
			} else {
				alert("Você já escolheu o maximo de opções permitidas, você deve remover alguma caso queira escolher uma nova!");
			}
		}
		ativachecks();
		$( "#the_form" ).trigger("change");

	});
	
	
	
		$(document).on('change','#the_form',function(e){

		var total = parseFloat( "<?php echo $data_content['valor_promocional']; ?>" );

		$( ".opcao.active" ).each(function() {
		
			var adiciona = parseFloat( $( this ).attr("valor-adicional") );
			var nomedavariacao = ( $( this ).attr("nomeda-variacao") );
			
			// Funçao para calcular o maior valor da pizza 
			
			if( nomedavariacao == "Escolha o sabor da pizza abaixo" && adiciona > total && adiciona > 0  ) {
			
				total = adiciona;
				
			}
			
			if( nomedavariacao !== "Escolha o sabor da pizza abaixo" && adiciona > 0  ) {
			
				total = total + adiciona;
				
			}
			
			
			
			
		});

		var vezes = parseFloat( $("#quantidade").val() );
		total = total * vezes;
		total = parseFloat( total ).toFixed(2);

		$('.subtotal').html('<strong>Total:</strong> R$ ' + total );

	});
	
	
	
	

	$( document ).ready(function() {

		$( ".opcoes .opcao .check" ).each(function() {
			var alturacheck = $( this ).parent().height();
			$( this ).css("height",alturacheck);
		});

		ativachecks();

	});

<?php } ?>

$( ".sacola-adicionar" ).click(function() {

	if( $('label.error:visible').length ){

		alert("Existem campos obrigatórios a serem preenchidos!");

		$('html, body').animate({
	        scrollTop: $("label.error:visible").offset().top-150
	    }, 500);

	}

	<?php if( has_variacao($data_content['id']) && $app['funcionalidade_variacao'] == "1" ) { ?>
	if( $(".variacao_validacao").valid() ) {
	<?php } ?>

		var eid = "<?php echo $app['id']; ?>";
		var former = "#the_form";
		var token = "<?php echo session_id(); ?>";
		var produto = "<?php echo $produto; ?>";
		var modo = "adicionar";
		var data = $("#the_form").serialize();

		$("#modalcarrinho .modal-body").html('<div class="adicionado"><i class="loadingicon lni lni-reload rotating"></i><div>');
		$.post( "<?php echo $app['url']; ?>/app/estabelecimento/_ajax/sacola.php", { token: token, produto: produto, modo: modo, data: data })
		.done(function( data ) {
			$( "#modalcarrinho .modal-body" ).html( data );
		});
		$("#modalcarrinho").modal("show");
		sacola_count(eid,token);

	<?php if( has_variacao($data_content['id']) && $app['funcionalidade_variacao'] == "1" ) { ?>
	}
	<?php } ?>

});

</script>

<div class="releaseclick"></div>