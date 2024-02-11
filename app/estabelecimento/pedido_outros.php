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
$query_content = mysqli_query( $db_con, "SELECT * FROM estabelecimentos WHERE id = '$app_id' ORDER BY id ASC LIMIT 1" );
$data_content = mysqli_fetch_array( $query_content );
$has_content = mysqli_num_rows( $query_content );

// SEO
$seo_subtitle = $app['title']." - Meu pedido";
$seo_description = "Meu pedido ".$app['title']." no ".$seo_title;
$seo_keywords = $app['title'].", ".$seo_title;
$seo_image = thumber( $app['avatar_clean'], 400 );

// HEADER
$system_header .= "";
include($virtualpath.'/_layout/head.php');
include($virtualpath.'/_layout/top.php');
include($virtualpath.'/_layout/sidebars.php');
include($virtualpath.'/_layout/modal.php');

// busca outros

$locall = "0";
$eid = $app['id'];
$locall = $_SESSION["local"];
$query_outros = mysqli_query( $db_con, "SELECT * FROM frete WHERE rel_estabelecimentos_id = '$eid' AND id = '$locall'" );
$data_outros = mysqli_fetch_array( $query_outros );


if($locall == 0) {
    
    header("Location: ".$app['url']."");
}

?>

<?php

  // Globals

  $eid = $app['id'];
  global $numeric_data;

	// Cupom
  	$datetime = date("Y-m-d H:i:s");
	$cupom = strtoupper( mysqli_real_escape_string( $db_con, $_GET['cupom'] ) );

	if( $cupom ) {

		$checkcupom = mysqli_query( $db_con, "SELECT * FROM cupons WHERE codigo = '$cupom' AND rel_estabelecimentos_id = '$eid' LIMIT 1");
		$hascupom = mysqli_num_rows( $checkcupom );
		$datacupom = mysqli_fetch_array( $checkcupom );

		if( !$hascupom ) {
			$cupom_use = "0";
			$cupom_msg = "Cupom inválido ou expirado";
		}

		if( $hascupom ) {
			if( $datacupom['quantidade'] <= 0 OR $datetime >= $datacupom['validade'] ) {
				$cupom_use = "0";
				$cupom_msg = "Cupom inválido ou expirado!";
			} else {
				if( $datacupom['tipo'] == "1" ) {
					$cupom_desconto = $datacupom['desconto_porcentagem']."%";
				}
				if( $datacupom['tipo'] == "2" ) {
					$cupom_desconto = "R$ ".dinheiro( $datacupom['desconto_fixo'], "BR");
				}
				$cupom_use = "1";
				$cupom_msg = "Cupom ativo (".$cupom_desconto." de desconto)!";
			}
		}

	}


  // Checar se formulário foi executado

  $formdata = $_POST['formdata'];

  if( $formdata ) {

  	$token = session_id();

    // Setar campos

		$datetime = date('Y-m-d H:i:s');

	// Dados gerais

		$rel_estabelecimentos_id = $app['id'];
		$rel_segmentos_id = data_info( "estabelecimentos",$rel_estabelecimentos_id,"segmento" );

		$nome = mysqli_real_escape_string( $db_con, $_POST['nome'] );
		$cookie_name = "nomecli";
		$cookie_value1 = $nome;
		setcookie($cookie_name, $cookie_value1, time() + (86400 * 90));
		
		$whatsapp = clean_str( mysqli_real_escape_string( $db_con, $_POST['whatsapp'] ) );
		$cookie_cel = "celcli";
		$cookie_value2 = $whatsapp;
		setcookie($cookie_cel, $cookie_value2, time() + (86400 * 90));
		
		$forma_entrega = mysqli_real_escape_string( $db_con, $_POST['forma_entrega'] );
		$estado = mysqli_real_escape_string( $db_con, $_POST['estado'] );
		$cidade = mysqli_real_escape_string( $db_con, $_POST['cidade'] );
		
		$endereco_cep = mysqli_real_escape_string( $db_con, $_POST['endereco_cep'] );
		$cookie_num = "cep";
		$cookie_value3 = $endereco_cep;
		setcookie($cookie_num, $cookie_value3, time() + (86400 * 90));
		
		$endereco_numero = mysqli_real_escape_string( $db_con, $_POST['endereco_numero'] );
		$cookie_num = "numero";
		$cookie_value3 = $endereco_numero;
		setcookie($cookie_num, $cookie_value3, time() + (86400 * 90));
		
		$endereco_bairro = mysqli_real_escape_string( $db_con, $_POST['endereco_bairro'] );
		$endereco_rua = mysqli_real_escape_string( $db_con, $_POST['endereco_rua'] );
		$endereco_complemento = mysqli_real_escape_string( $db_con, $_POST['endereco_complemento'] );
		$endereco_referencia = mysqli_real_escape_string( $db_con, $_POST['endereco_referencia'] );
		$forma_pagamento = mysqli_real_escape_string( $db_con, $_POST['forma_pagamento'] );
		$forma_pagamento_informacao = mysqli_real_escape_string( $db_con, $_POST['forma_pagamento_informacao'] );
		
		if(!$forma_pagamento_informacao) {
		    
		    $forma_pagamento_informacao = "Não preciso de troco";
		}
		
		$vpedido = mysqli_real_escape_string( $db_con, $_POST['vpedido'] );
		$data_hora = $datetime;

    // Checar Erros

    $checkerrors = 0;
    $errormessage = array();

	// Geral

		// -- Nome

		if( !$nome ) {
			$checkerrors++;
			$errormessage[] = "Informe seu nome";
		}

		// -- Whatsapp

		if( !$whatsapp ) {
			$checkerrors++;
			$errormessage[] = "Informe seu nº de whatsapp";
		}

		// -- Endereço

		if( $forma_entrega == "2" ) {

			if( !$endereco_rua && !$endereco_bairro && !$endereco_numero ) {
				$checkerrors++;
				$errormessage[] = "O endereço não pode estar incompleto";
			}

		}

    // Executar registro

    if( !$checkerrors ) {

      if( $pedido = new_pedido(
      	$token,
		$rel_segmentos_id,
		$rel_estabelecimentos_id,
		$nome,
		$whatsapp,
		$estado,
		$cidade,
		$forma_entrega,
		$endereco_cep,
		$endereco_numero,
		$endereco_bairro,
		$endereco_rua,
		$endereco_complemento,
		$endereco_referencia,
		$forma_pagamento,
		$forma_pagamento_informacao,
		$data_hora,
		$cupom,
		$vpedido
       ) ) {

      	unset( $_SESSION['sacola'][$app['id']] );
      	unset( $_SESSION['local']);
        header("Location: ".$app['url']."/obrigado?pedido=".$pedido."&forma=".$forma_pagamento."&codex=".$vpedido."");

      } else {

        header("Location: ".$app['url']."/pedido?msg=error");

      }

    }

  }
  
?>

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

<div class="minfit sceneElement">

		<div class="middle">

			<div class="container nopaddmobile">

				<div class="row rowtitle">

					<div class="col-md-12">
						<div class="title-icon">
							<span>Pedido <?php print $data_outros["nome"]; ?></span>
						</div>
						<div class="bread-box">
							<div class="bread">
								<a href="<?php echo $app['url']; ?>"><i class="lni lni-home"></i></a>
								<span>/</span>
								<a href="<?php echo $app['url']; ?>/sacola.php">Minha sacola</a>
								<span>/</span>
								<a href="<?php echo $app['url']; ?>/pedido.php">Pedido Delivery</a>
							</div>
						</div>
					</div>

					<div class="col-md-12 hidden-xs hidden-sm">
						<div class="clearline"></div>
					</div>

				</div>

				<div class="row">

					<div class="col-md-12">

					  <?php if( $checkerrors ) { list_errors(); } ?>

					  <?php if( $_GET['msg'] == "erro" ) { ?>

					    <?php modal_alerta("Erro, tente novamente!","erro"); ?>

					  <?php } ?>

					  <?php if( $_GET['msg'] == "sucesso" ) { ?>

					    <?php modal_alerta("Cadastro efetuado com sucesso!","sucesso"); ?>

					  <?php } ?>

					</div>

				</div>

                
				<div class="pedido">

					<form id="the_form" method="POST">

						<div class="row">

							<div class="col-md-8 muda-checkout">

								<div class="titler">

									<div class="row">

										<div class="col-md-12">

											<div class="title-line mt-0 pd-0">
												<i class="lni lni-user"></i>
												<span>Dados do cliente</span>
												<div class="clear"></div>
											</div>

										</div>

									</div>

								</div>

								<div class="elemento-usuario">

									<div class="row">

										<div class="col-md-12">

										  <div class="form-field-default">

										      <label>Nome completo:</label>
										      <input type="text" name="nome" placeholder="Nome:" <?php if(isset($_COOKIE['nomecli'])){ ?> value="<?php print $_COOKIE['nomecli']; ?>" <?php } else { ?> value="<?php echo htmlclean( $_SESSION['checkout']['nome'] ); ?>" <?php } ?>>

										  </div>

										</div>

									</div>

									<div class="row">

										<div class="col-md-12">

										  <div class="form-field-default">

										      <label>Whatsapp:</label>
										      <input class="maskcel" type="text" name="whatsapp" placeholder="Whatsapp:" <?php if(isset($_COOKIE['celcli'])){ ?> value="<?php print $_COOKIE['celcli']; ?>" <?php } else { ?> value="<?php echo htmlclean( $_SESSION['checkout']['whatsapp'] ); ?>" <?php } ?>>

										  </div>

										</div>

									</div>

								</div>

								 
								 
										  

									  		<?php
									  		if( $data_content['entrega_entrega_tipo'] == "1" ) {
									  			$frete_valor = "R$ ".dinheiro( $data_content['entrega_entrega_valor'], "BR" );
									  		} else {
									  			$frete_valor = "Sob consulta";
									  		}
									  		?>
									<input type="hidden" name="forma_entrega" value="<?php print $data_outros["id"]; ?>">
									<input type="hidden" name="estado" value="<?php echo $app['estado']; ?>">
									<input type="hidden" name="cidade" value="<?php echo $app['cidade']; ?>">
									<input type="hidden" name="endereco_cep" value="">
									<input type="hidden" name="endereco_numero" value="">
									<input type="hidden" name="endereco_numero" value="">
									<input type="hidden" name="endereco_numero" value="">
									<input type="hidden" name="endereco_bairro" value="">
									<input type="hidden" name="endereco_rua" value="">
									<input type="hidden" name="endereco_complemento" value="">
									<input type="hidden" name="endereco_referencia" value="">
									<input type="hidden" name="forma_pagamento" value="5">
									<input type="hidden" name="forma_pagamento_informacao" value="">
									<input type="hidden" name="cupom" value="">
									 
							</div>

							<div class="col-md-4 muda-comprovante">

								<div class="titler titlerzero">

									<div class="row">

										<div class="col-md-12">

											<div class="title-line mt-0 pd-0">
												<i class="lni lni-ticket-alt"></i>
												<span>Resumo do pedido</span>
												<div class="clear"></div>
											</div>

										</div>

									</div>

								</div>

								<div class="comprovante-parent grudado-desktop">

									<div class="comprovante">
										<div class="content"></div>
									</div>

									<span class="alerta-comprovante">
										O seu pedido será enviado para o
										<br/>nosso Whatsapp
									</span>

								</div>

								<div class="clear"></div>

							</div>

						</div>

						<div class="pedido-actions">

							<div class="row error-pedido-minimo">

								<div class="col-md-12">
									<?php
									$eid = $app['id'];
									$subtotal = array();
									foreach( $_SESSION['sacola'][$eid] AS $key => $value ) {
										$produto = $value['id'];
										$query_produtos = mysqli_query( $db_con, "SELECT * FROM produtos WHERE id = '$produto' AND status = '1' ORDER BY id ASC LIMIT 1" );
										$data_produtos = mysqli_fetch_array( $query_produtos );
										
										if( $data_produtos['oferta'] == "1" ) {
											$valor_final = $data_produtos['valor_promocional'];
										} else {
											$valor_final = $data_produtos['valor'];
										}
										$subtotal[] = ( ( $valor_final + $_SESSION['sacola'][$eid][$key]['valor_adicional'] ) * $_SESSION['sacola'][$eid][$key]['quantidade'] );
									}

									$subtotal = array_sum( $subtotal );
									if( $subtotal >= $app['pedido_minimo_valor'] ) {
										$field_minimo = "1";
									}
									?>
									 
									<input type="text" class="hidden" name="vpedido" value="<?php echo $subtotal; ?>"/>
									<input type="text" class="fake-hidden" name="pedido_minimo" value="<?php echo $field_minimo; ?>"/>
								</div>

							</div>

							<div class="row">

								<div class="col-md-3 col-xs-5 col-sm-5">
									<a class="back-button" href="<?php echo $app['url']; ?>/sacola"><i class="lni lni-arrow-left"></i> <span>Alterar</span></a>
								</div>

								<div class="col-md-6 hidden-xs hidden-sm"></div>

								<div class="col-md-3 col-xs-7 col-sm-7">
								    <input type="hidden" name="formdata" value="1"/>
									<button class="botao-acao"><i class="lni lni-whatsapp"></i> <span>Enviar pedido</span></button>
								</div>

							</div>

						</div>

					</form>

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

$( ".botao-aplicar" ).click(function() {

	var cupom = $("input[name='cupom']").val();
	var gourl = "<?php echo $app['url'].'/pedido?cupom=';?>"+cupom;
	window.location.href = gourl;

});

// Autopreenchimento de estado
$( "#input-estado" ).change(function() {
	<?php
	if( $_SESSION['checkout']['cidade'] && is_numeric( $_SESSION['checkout']['cidade'] ) ) {
		$cidade = mysqli_real_escape_string( $db_con, $_SESSION['checkout']['cidade'] );
	} else {
		$cidade = $app['cidade'];
	}
	?>
	var estado = $(this).children("option:selected").val();
	var cidade = "<?php echo $cidade; ?>";
	$("#input-cidade").html("<option>-- Carregando cidades --</option>");
	$("#input-cidade").load("<?php $app['url'] ?>/_core/_ajax/cidades.php?estado="+estado+"&cidade="+cidade);
});

$( "#input-estado" ).trigger("change");

$( window ).resize(function() {

	var window_width = parseInt( $( window ).width(), 10);
	var height_muda_checkout = parseInt( ( $(".muda-checkout").height() - 150 ), 10);
	var height_muda_comprovante = parseInt( $(".comprovante").height(), 10);
	if( height_muda_comprovante == 0 ) {
		var height_muda_comprovante = parseInt( height_muda_checkout, 10);
	}

	if( window_width >= 980 ) {
		var footer_height = $('.footer').height(); 
		var actions_height = $('.pedido-actions').height();
		var limit_bottom = ( actions_height + footer_height + 50 );
		if( height_muda_checkout > height_muda_comprovante  ) {
			$('.grudado-desktop').sticky({topSpacing:0, bottomSpacing:limit_bottom});
		} else {
			if( $(".sticky-wrapper").hasClass("is-sticky") ) {
				$('.grudado-desktop').unstick();
				$('.muda-comprovante').css("margin-bottom","64px");
			}
		}
	}

});

$( window ).trigger("resize");

$(document).ready( function() {
          
  var form = $("#the_form");
  form.validate({
      focusInvalid: true,
      invalidHandler: function() {
      },
      errorPlacement: function errorPlacement(error, element) { element.after(error); },
      rules:{

        nome: {
            required: true
        },
        whatsapp: {
            required: true
        },
        forma_entrega: {
            required: true
        },
        endereco_bairro: {
            required: true
        },
        endereco_rua: {
            required: true
        },
        forma_pagamento: {
            required: true
        },
        pedido_minimo: {
        	required: true
        }
      },
      messages:{
        nome: {
            required: "Esse campo é obrigatório"
        },
        whatsapp: {
            required: "Esse campo é obrigatório"
        },
        forma_entrega: {
            required: "Esse campo é obrigatório"
        },
        endereco_bairro: {
            required: "Esse campo é obrigatório"
        },
        endereco_rua: {
            required: "Esse campo é obrigatório"
        },
        forma_pagamento: {
            required: "Esse campo é obrigatório"
        },
        pedido_minimo: {
            required: "Você deve ter no minimo R$ <?php echo $app['pedido_minimo']; ?> na sacola para poder efetuar a compra"
        }
      }
  });

});

</script>

<script>

var token = "<?php echo session_id(); ?>";

$( ".muda-checkout" ).change(function() {

	var nome = $( "input[name='nome']" ).val();
	var whatsapp = $( "input[name='whatsapp']" ).val();
	var forma_entrega = $( "input[name='forma_entrega']" ).val();
	var estado = $( "select[name='estado'] option:selected" ).val();
	var cidade = $( "select[name='cidade'] option:selected" ).val();
	var endereco_cep = $( "input[name='endereco_cep']" ).val();
	var endereco_numero = $( "input[name='endereco_numero']" ).val();
	var endereco_bairro = $( "input[name='endereco_bairro']" ).val();
	var endereco_rua = $( "input[name='endereco_rua']" ).val();
	var endereco_complemento = $( "input[name='endereco_complemento']" ).val();
	var endereco_referencia = $( "input[name='endereco_referencia']" ).val();
	var forma_pagamento = $( "select[name='forma_pagamento'] option:selected" ).val();
	var forma_pagamento_informacao = $( "input[name='forma_pagamento_informacao']" ).val();
	var modo = "checkout";
	var quantidade = $(this).find("input[name=quantidade]").val();
	var observacoes = $(this).find("textarea[name=observacoes]").val();
	var cupom = $( "input[name='cupom']" ).val();

	$.post( "<?php $app['url'] ?>/app/estabelecimento/_ajax/sacola.php", { 
		token: token,
		modo: modo,
		nome: nome,
		whatsapp: whatsapp,
		forma_entrega: forma_entrega,
		cidade: cidade,
		estado: estado,
		endereco_cep: endereco_cep,
		endereco_numero: endereco_numero,
		endereco_bairro: endereco_bairro,
		endereco_rua: endereco_rua,
		endereco_complemento: endereco_complemento,
		endereco_referencia: endereco_referencia,
		forma_pagamento: forma_pagamento,
		forma_pagamento_informacao: forma_pagamento_informacao,
		cupom: cupom
	})
	.done(function( data ) {
		console.log("alterou checkout da sacola");
	});

	var eid = "<?php echo $app['id']; ?>";

	atualiza_comprovante(eid,token);
	form.validate().settings.ignore = ":disabled,:hidden";

});

$( "#input-forma-pagamento" ).change(function() {

	var forma_pagamento = $(this).val();

	if( forma_pagamento == "1" ) {
		$( ".elemento-forma-pagamento-descricao" ).show();
		$( ".elemento-forma-pagamento-descricao label" ).html("Deseja troco para:");
		$( ".elemento-forma-pagamento-descricao input" ).attr("placeholder","Deixe em branco caso não precise");
		$( ".elemento-forma-pagamento-descricao .form-tip" ).hide();
	}

	if( forma_pagamento == "2"  ) {
		$( ".elemento-forma-pagamento-descricao" ).show();
		$( ".elemento-forma-pagamento-descricao label" ).html("Bandeira do cartão:");
		$( ".elemento-forma-pagamento-descricao input" ).attr("placeholder","Bandeira do cartão:");
		$( ".elemento-forma-pagamento-descricao .form-tip" ).html("Bandeiras aceitas: <?php echo $data_content['pagamento_cartao_debito_bandeiras']; ?>");
		$( ".elemento-forma-pagamento-descricao .form-tip" ).show();
	}

	if( forma_pagamento == "3"  ) {
		$( ".elemento-forma-pagamento-descricao" ).show();
		$( ".elemento-forma-pagamento-descricao label" ).html("Bandeira do cartão:");
		$( ".elemento-forma-pagamento-descricao input" ).attr("placeholder","Bandeira do cartão:");
		$( ".elemento-forma-pagamento-descricao .form-tip" ).html("Bandeiras aceitas: <?php echo $data_content['pagamento_cartao_credito_bandeiras']; ?>");
		$( ".elemento-forma-pagamento-descricao .form-tip" ).show();
	}

	if( forma_pagamento == "4"  ) {

		$( ".elemento-forma-pagamento-descricao" ).show();
		$( ".elemento-forma-pagamento-descricao label" ).html("Bandeira do ticket alimentação:");
		$( ".elemento-forma-pagamento-descricao input" ).attr("placeholder","Bandeira do ticket alimentação:");
		$( ".elemento-forma-pagamento-descricao .form-tip" ).html("Bandeiras aceitas: <?php echo $data_content['pagamento_cartao_alimentacao_bandeiras']; ?>");
		$( ".elemento-forma-pagamento-descricao .form-tip" ).show();
	}

	if( forma_pagamento == "5"  ) {
		$( ".elemento-forma-pagamento-descricao" ).show();
		$( ".elemento-forma-pagamento-descricao label" ).html("Forma de pagamento:");
		$( ".elemento-forma-pagamento-descricao input" ).attr("placeholder","Forma de pagamento:");
		$( ".elemento-forma-pagamento-descricao .form-tip" ).html("Formas aceitas: <?php echo $data_content['pagamento_outros_descricao']; ?>");
		$( ".elemento-forma-pagamento-descricao .form-tip" ).show();
	}

	if( forma_pagamento == "6"  ) {
		$( ".elemento-forma-pagamento-descricao" ).hide();
	}		

});

$( "#input-forma-entrega" ).trigger("change");
$( ".muda-checkout" ).trigger("change");
$( "#input-forma-pagamento" ).trigger("change");

</script>

<script src="<?php just_url(); ?>/_core/_cdn/cep/cep.js"></script>