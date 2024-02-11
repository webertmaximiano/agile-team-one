<?php
// CORE
include($virtualpath.'/_layout/define.php');
// APP
global $app;
is_active( $app['id'] );
$back_button = "true";
// Querys
$exibir = "8";
$app_id = $app['id'];
$has_content = count( $_SESSION['sacola'][$app_id] );
// SEO
$seo_subtitle = $app['title']." - Minha sacola";
$seo_description = "Minha sacola de compras ".$app['title']." no ".$seo_title;
$seo_keywords = $app['title'].", ".$seo_title;
$seo_image = thumber( $app['avatar'], 400 );
// HEADER
$system_header .= "";
include($virtualpath.'/_layout/head.php');
include($virtualpath.'/_layout/top.php');
include($virtualpath.'/_layout/sidebars.php');
include($virtualpath.'/_layout/modal.php');
instantrender();

// print_r($_SESSION['sacola']);
if( $_GET['acao'] == "flush" ) {
	unset( $_SESSION['sacola'] );
}

$locall = '0';
$locall = $_SESSION["local"];
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

		<div class="holder-interna holder-interna-nopadd holder-interna-sacola visible-xs visible-sm"></div>

	</div>

	<div class="minfit">

			<div class="middle">

				<div class="container nopaddmobile">

					<div class="row rowtitle">

						<div class="col-md-12">
							<div class="title-icon">
								<span>Fechar Pedido</span>
							</div>
							<div class="bread-box">
								<div class="bread">
									<a href="<?php echo $app['url']; ?>"><i class="lni lni-home"></i></a>
									<span>/</span>
									<a href="<?php echo $app['url']; ?>/sacola.php">Minha sacola</a>
								</div>
							</div>
						</div>

						<div class="col-md-12 hidden-xs hidden-sm">
							<div class="clearline"></div>
						</div>

					</div>

					<div class="sacola">

						
<form id="the_form" method="POST" action="<?php echo $app['url']; ?>/pedido_delivery">
							<div class="row">

								<div class="col-md-12">

									<table class="listing-table sacola-table">
										<thead>
											<th></th>
											<th>Nome</th>
											<th>Qntd</th>
											<th>Valor</th>
											<th>Detalhes</th>
											<th></th>
										</thead>
										<tbody>
										<?php

										foreach($_SESSION['sacola'][$app_id] AS $key => $value) {
										$produto = $value['id'];
										$newpid = $key;
										$query_content = mysqli_query( $db_con, "SELECT * FROM produtos WHERE id = '$produto' AND status = '1' ORDER BY id ASC LIMIT 1" );
										$data_content = mysqli_fetch_array( $query_content );
										?>

										<tr class="sacola-alterar sacola-<?php echo $newpid; ?>" sacola-eid="<?php echo $app_id; ?>" sacola-pid="<?php echo $newpid; ?>" valor="<?php echo $data_content['valor_promocional']; ?>" valor-adicional="<?php echo $_SESSION['sacola'][$app['id']][$newpid]['valor_adicional']; ?>" valor-somado="">
											<td class="td-foto">
												<a href="<?php echo $app['url']; ?>/produto/<?php echo $produto; ?>">
													<div class="imagem">
														<img src="<?php echo imager( $data_content['destaque'] ); ?>"/>
													</div>
												</a>	
											</td>	
											<td class="td-nome">
												<a href="<?php echo $app['url']; ?>/produto/<?php echo $produto; ?>">
													<span class="nome"><?php echo htmlclean( $data_content['nome'] ); ?></span>
												</a>
											</td>
											<td class="td-detalhes visible-xs visible-sm">
												<div class="line detalhes">
													<?php if( $_SESSION['sacola'][$app['id']][$newpid]['variacoes_texto'] ) { ?>
													<span>
														<?php 
														echo nl2br( bbzap( htmlcleanbb( $_SESSION['sacola'][$app['id']][$newpid]['variacoes_texto'] ) ) ); 
														?>
													</span>
													<?php } ?>
												</div>
											</td>
											<td class="td-quantidade">
												<div class="clear"></div>
												<div class="holder-acao">
													<div class="item-acao visible-xs visible-sm">
														<a class="sacola-change" href="<?php echo $app['url']; ?>/produto/<?php echo $data_content['id']; ?>">
															<i class="lni lni-pencil"></i> 
														</a>
													</div>
													<div class="item-acao">
														<div class="line quantidade">
															<div class="clear"></div>
															<div class="campo-numero">
																<i class="decrementar lni lni-minus" onclick="decrementar('#quantidade');"></i>
																<input id="quantidade" type="number" name="quantidade" value="<?php echo htmlclean( $_SESSION['sacola'][$app_id][$newpid]['quantidade'] ); ?>"/>
																<i class="incrementar lni lni-plus" onclick="incrementar('#quantidade');"></i>
															</div>
															<div class="clear"></div>
														</div>
													</div>
													<div class="item-acao visible-xs visible-sm">
														<a class="sacola-remover" href="#" sacola-eid="<?php echo $app_id; ?>" sacola-pid="<?php echo $newpid; ?>">
															<i class="lni lni-trash"></i> 
														</a>
													</div>
												</div>
											</td>
											<td class="td-valor">
												<span>Valor:</span>
												<div class="line valor">
													<span></span>
												</div>
											</td>
											<td class="td-detalhes hidden-xs hidden-sm">
												<div class="line detalhes">
													<?php if( $_SESSION['sacola'][$app['id']][$newpid]['variacoes_texto'] ) { ?>
													<span>
														<?php 
														echo nl2br( bbzap( htmlcleanbb( $_SESSION['sacola'][$app['id']][$newpid]['variacoes_texto'] ) ) ); 
														?>
													</span>
													<?php } ?>
												</div>
											</td>
											<td class="td-acoes hidden-xs hidden-sm">
												<div class="holder">
													<a class="sacola-remover" href="#" sacola-eid="<?php echo $app_id; ?>" sacola-pid="<?php echo $newpid; ?>">
														<i class="lni lni-trash"></i> 
														<span class="visible-xs">Excluir</span>
													</a>
												</div>
												<div class="clear visible-xs visible-sm"></div>
											</td>
										</tr>

										<?php } ?>
										<tr class="sacola-null">
											<td colspan="5"><span class="nulled">Sua sacola de compras está vazia, adicione produtos para poder fazer o seu pedido!</span></td>
										</tr>
										</tbody>
									</table>

								</div>

							</div>

							<div class="linha-subtotal">

								<div class="row error-pedido-minimo error-pedido-minimo-sacola">

									<div class="col-md-12">
										<input class="fake-hidden" name="pedido_minimo" value=""/>
									</div>

								</div>
</form>
								
								 
									
								<div class="row">
									
									
									<div class="col-md-<?php if($locall == 0) { echo '4'; } else { echo '9';}?>">

										<div class="subtotal"><strong>Subtotal:</strong> <span><?php echo "R$ ".dinheiro( $subtotal, "BR"); ?></span></div>

									</div>
									
									
									
									<div class="clear visible-xs visible-sm"><br/></div>
                                    
                                    <?php if($locall == 0) {?>
									
									<?php if($app['delivery'] == 1) { ?>
                                    <div class="col-md-4">
								    <form id="the_form" method="POST" action="<?php echo $app['url']; ?>/pedido_delivery">
									<input class="fake-hidden" name="pedido_minimo" value=""/>
									
										<button class="botao-acao"><i class="lni lni-bi-cycle"></i> <span>Delivery</span></button>
									
									</form>
									</div>
									
									<?php } ?>
									
									<div class="clear visible-xs visible-sm"><br/></div>
									
									<?php if($app['balcao'] == 1) { ?>
									<div class="col-md-4">
									<form id="the_form" method="POST" action="<?php echo $app['url']; ?>/pedido_balcao">
									<input class="fake-hidden" name="pedido_minimo" value=""/>
									
										<button class="botao-acao"><i class="lni lni-helmet"></i> <span>Retirada Balcão</span></button>
									
									</form>
									</div>
									<?php } ?>
									
									<?php } else { ?>
									
									<?php if($app['outros'] == 1) { ?>
									<div class="col-md-3">
									<form id="the_form" method="POST" action="<?php echo $app['url']; ?>/pedido_outros">
									<input class="fake-hidden" name="pedido_minimo" value=""/>
									
										<button class="botao-acao"><i class="lni lni-consulting"></i> <span><?php print $app['nomeoutros']; ?></span></button>
									
									</form>
									</div>
									<?php } ?>
									<?php } ?>

								</div>

							</div>

						</form>

					</div>

				</div>

			</div>

	</div>

</div>

<?php 
// FOOTER
$system_footer .= "";
include($virtualpath.'/_layout/rdp.php');
include($virtualpath.'/_layout/footer.php');
?>

<script>

$(document).ready( function() {
          
  var form = $("#the_form");
  form.validate({
      focusInvalid: true,
      invalidHandler: function() {
      },
      errorPlacement: function errorPlacement(error, element) { element.after(error); },
      rules:{
        pedido_minimo: {
        	required: true
        }
      },
      messages:{
        pedido_minimo: {
            required: "Você deve ter no minimo R$ <?php echo $app['pedido_minimo']; ?> na sacola para poder efetuar a compra"
        },
      }
  });

});

</script>

<script>

function decimaltoreal( valor ){
	valor = valor + '';
	valor = parseInt(valor.replace(/[\D]+/g,''));
	valor = valor + '';
	valor = valor.replace(/([0-9]{2})$/g, ",$1");

	if (valor.length > 6) {
	valor = valor.replace(/([0-9]{3}),([0-9]{2}$)/g, ".$1,$2");
	}

	return valor;
}

var token = "<?php echo session_id(); ?>";

$( ".sacola-alterar" ).change(function() {

	var eid = $(this).attr('sacola-eid');
	var produto = $(this).attr('sacola-pid');
	var modo = "alterar";
	var quantidade = $(this).find("input[name='quantidade']").val();
	var data = "quantidade="+quantidade;

	$.post( "<?php $app['url'] ?>/app/estabelecimento/_ajax/sacola.php", { token: token, eid: eid,produto: produto, modo: modo, data: data })
	.done(function( data ) {
	});
	$("#the_form").trigger("change");

});

$( ".sacola-remover" ).click(function() {

	var eid = $(this).attr('sacola-eid');
	var produto = $(this).attr('sacola-pid');
	var modo = "remover";

	if( confirm('Deseja remover o produto da sua sacola?') ) {

		$.post( "<?php $app['url'] ?>/app/estabelecimento/_ajax/sacola.php", { token: token, eid: eid,produto: produto, modo: modo })
		.done(function( data ) {
			console.log("removeu produto da sacola");
			$(".sacola-"+produto).fadeOut("800", function() {
		        $(this).remove();
				$("#the_form").trigger("change");
				sacola_count(eid,token);
		    });
		});

	}

});

$(document).on('change','#the_form',function(e){


	$( ".sacola-alterar" ).each(function() {
		var total = 0;
		var valor = parseFloat( $( this ).attr("valor") );
		var adiciona = parseFloat( $( this ).attr("valor-adicional") );
		total = valor + adiciona;
		var vezes = parseFloat( $(this).find("#quantidade").val() );
		total = total * vezes;
		total = parseFloat( total ).toFixed(2);
		var totalbr = decimaltoreal(total);
		// total = total.replace('.', ',');
		$(this).attr("valor-somado",total);
		$(this).find(".valor span").html("R$ "+totalbr);
	});

	var total = 0;

	$( ".sacola-alterar" ).each(function() {
		total = total + parseFloat( $( this ).attr("valor-somado") );
	});
	total = parseFloat( total ).toFixed(2);
	var totalbr = decimaltoreal(total);
	total = total;
	$(this).find(".subtotal").html("<strong>Subtotal:</strong> R$ "+totalbr);

	if( total >= <?php echo $app['pedido_minimo_valor']; ?> ) {
		$("input[name='pedido_minimo']").val(1);
	} else {
		$("input[name='pedido_minimo']").val("");
	}

});

$("#the_form").trigger("change");

</script>