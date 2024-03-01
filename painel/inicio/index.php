<?php
// CORE
include('../../_core/_includes/config.php');
// RESTRICT
restrict_estabelecimento();
atualiza_estabelecimento( $_SESSION['estabelecimento']['id'], "online" );
// SEO
$seo_subtitle = "Ínicio";
$seo_description = "";
$seo_keywords = "";
// HEADER
$system_header .= "";
include('../_layout/head.php');
include('../_layout/top.php');
include('../_layout/sidebars.php');
include('../_layout/modal.php');


// Globals
  $id = $_SESSION['estabelecimento']['id'];

  $queryestabelecimento = mysqli_query( $db_con, "SELECT * FROM estabelecimentos WHERE id = '$id' LIMIT 1");
  $dataestabelecimento = mysqli_fetch_array( $queryestabelecimento );
  
  $idcidade = $dataestabelecimento['cidade'];

  $queryusr = mysqli_query( $db_con, "SELECT * FROM users_data WHERE cidade = '$idcidade' LIMIT 1");
  $hasusr = mysqli_num_rows( $queryusr );
  
  // pega dados do suporte
  

  
  // soma vendas
  
  //$querytotalvendas = mysqli_query( $db_con, "SELECT v_pedido, SUM(v_pedido) AS soma1 FROM pedidos WHERE rel_estabelecimentos_id = '$id' AND status = '2'");
  $querytotalvendas = mysqli_query( $db_con, "SELECT id, SUM(v_pedido) AS soma1 FROM pedidos WHERE rel_estabelecimentos_id = '$id' AND status = '2' GROUP BY id");

  $datatotalvendas = mysqli_fetch_array( $querytotalvendas );
  
  // total de pedidos
  
  $querypedidos = mysqli_query( $db_con, "SELECT id FROM pedidos WHERE rel_estabelecimentos_id = '$id'");
  $datapedidos = mysqli_num_rows( $querypedidos );
  
    // soma vendas mês
  $mesatual = date("m");
  $querytotalvendasm = mysqli_query($db_con, "SELECT MONTH(data_hora) AS mes, SUM(v_pedido) AS soma2 FROM pedidos WHERE rel_estabelecimentos_id = '$id' AND status = '2' GROUP BY mes");
  $datatotalvendasm = mysqli_fetch_array( $querytotalvendasm );
  
  
?>

<?php if( isset($_GET['msg']) == "inativo" ) { ?>

<?php modal_alerta("Seu plano encontrasse inativo, contrate um novo plano para continuar a usar os serviços!","erro"); ?>

<?php } ?>

<?php if( isset($_GET['msg']) == "funcaodesativada" ) { ?>

<?php modal_alerta("Seu plano não tem acesso a essa funcionalidade, contrate um correspondente verificando a aba meu plano!","erro"); ?>

<?php } ?>

<?php if( isset($_GET['msg']) == "bemvindo" ) { ?>

<?php modal_alerta("Seu catálogo foi criado com sucesso. Aproveite o seu período de testes!<br/><br/>Ao final do período você deve escolher um plano para continuar utilizando o sistema.","sucesso"); ?>

<?php } ?>

<div class="middle home-middle minfit bg-gray">
    
    <div class="container visible-xs visible-sm">
        <div class="row">
			<div class="lista-menus">
				<div class="col-md-4">
					<div class="lista-menus-menu">
						<a class="bt" href="<?php echo isset($meudominio); ?>" target="_blank">
							<i class="lni lni-home"></i>
							<span>Ver meu catalogo</span>
							<i class="lni lni-chevron-right"></i>
							<div class="clear"></div>
						</a>
					</div>
				</div>
			</div>
		</div>
		<hr/>
    </div>
    
    <div class="container">
        <div class="row">

			<div class="lista-menus">
				    <div class="col-md-4">
						<div class="lista-menus-menu lista-menus-nocounter">
							<a class="bt" href="#">
								<i class="lni lni-calculator"></i>
								<span>Faturado na Plataforma<br/>R$: <?php print number_format(isset($datatotalvendas['soma1']), 2, ',', '.');?></span>
								<div class="clear"></div>
							</a>
						</div>
					</div>
				    <div class="col-md-4">
						<div class="lista-menus-menu lista-menus-nocounter">
							<a class="bt" href="#">
								<i class="lni lni-coin" style="color:#FFFFFF"></i>
								<span>Vendas do mês <?php print date("m");?>/<?php print date("Y");?><br/>R$: <?php print number_format(isset($datatotalvendasm['soma2']), 2, ',', '.');?></span>
								<div class="clear"></div>
							</a>
						</div>
					</div>
					<div class="col-md-4">
						<div class="lista-menus-menu lista-menus-nocounter">
							<a class="bt" href="#">
								<i class="lni lni-restaurant"></i>
								<span>Pedidos em <?php print date("m");?>/<?php print date("Y");?><br/><?php print $datapedidos; ?></span>
								<div class="clear"></div>
							</a>
						</div>
					</div>
					
			</div>

		</div>
    </div>
    
    

	<div class="container">

		<?php
		$expiracao = $_SESSION['estabelecimento']['expiracao'];
		if( $expiracao <= 0 ) {
			$expiration_msg = "Seu plano expirou, contrate um novo para continuar usufruindo dos nossos serviços após esse prazo.";
		} else {
			$expiration_msg = "Seu plano expira em <strong>".$expiracao." dias</strong>, contrate ou renove para continuar usufruindo dos nossos serviços após esse prazo.";
		}
		if( $expiracao <= 15 ) {
		?>

		<div class="panel-group panel-filters panel-avisos">
			
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" href="#collapse-avisos<?php if( $expiracao < 3 ) { echo "_"; }; ?>">
							<span class="desc">Aviso Importante.</span>
							<i class="lni lni-angle-double-down"></i>
							<div class="clear"></div>
						</a>
					</h4>
				</div>
				<div id="collapse-avisos" class="panel-collapse collapse <?php if( $expiracao < 3 ) { echo "in"; }; ?>">
					<div class="panel-body panel-body-pendentes">
						<div class="expiration-info">
							<div class="row">
								<div class="col-md-9">
									<span class="msg"><?php echo $expiration_msg; ?></span>
								</div>
								<div class="col-md-3">
									<div class="add-new add-center text-center">
										<a href="<?php panel_url(); ?>/plano/listar">
											<span>Contratar/Renovar</span>
											<i class="lni lni-arrow-right"></i>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div> 

		<?php } ?>
		
		

		
		
		 
		<hr/>
		 

		<div class="lista-menus">

			<div class="row">

				<div class="col-md-4">

					<div class="funcionamento">

						<?php if( data_info( "estabelecimentos", $_SESSION['estabelecimento']['id'], "funcionamento" ) == "1" ) { ?>

						<div class="aberto">

							<div class="lista-menus-menu">
								<div class="bt">
									<i class="open-status"></i>
									<span>Aberto para Pedidos</span>
									<i class="lni lni-shuffle"></i>
									<div class="clear"></div>
								</div>
							</div>
							<span class="funcionamento-msg">O seu estabelecimento está disponível<br class="hidden-xs hidden-sm"/> para receber pedidos</span>

						</div>

						<?php } else { ?>

						<div class="fechado">

							<div class="lista-menus-menu">
								<div class="bt">
									<i class="open-status"></i>
									<span>Fechado para Pedidos</span>
									<i class="lni lni-shuffle"></i>
									<div class="clear"></div>
								</div>
							</div>
							<span class="funcionamento-msg">O seu estabelecimento não está disponível<br class="hidden-xs hidden-sm"/> para receber pedidos</span>

						</div>

						<?php } ?>

					</div>

				</div>

				<?php if( $_SESSION['estabelecimento']['status'] == "1" ) { ?>

					<div class="col-md-4">
						<div class="lista-menus-menu">
							<a class="bt" href="<?php panel_url(); ?>/pedidos">
								<i class="lni lni-radio-button"></i>
								<span>Ver Pedidos</span>
								<strong><?php echo counter( $_SESSION['estabelecimento']['id'], "pedido" ); ?></strong>
								<div class="clear"></div>
							</a>
						</div>
					</div>

					<div class="col-md-4">
						<div class="lista-menus-menu">
							<a class="bt" href="<?php panel_url(); ?>/categorias">
								<i class="lni lni-radio-button"></i>
								<span>Categorias</span>
								<strong><?php echo counter( $_SESSION['estabelecimento']['id'], "categoria" ); ?></strong>
								<div class="clear"></div>
							</a>
						</div>
					</div>

					<div class="col-md-4">
						<div class="lista-menus-menu">
							<a class="bt" href="<?php panel_url(); ?>/produtos">
								<i class="lni lni-star"></i>
								<span>Produtos</span>
								<strong><?php echo counter( $_SESSION['estabelecimento']['id'], "produto" ); ?></strong>
								<div class="clear"></div>
							</a>
						</div>
					</div>

					<?php if( $_SESSION['estabelecimento']['funcionalidade_banners'] == "1" ) { ?>
					<div class="col-md-4">
						<div class="lista-menus-menu">
							<a class="bt" href="<?php panel_url(); ?>/banners">
								<i class="lni lni-star"></i>
								<span>Banners</span>
								<strong><?php echo counter( $_SESSION['estabelecimento']['id'], "banner" ); ?></strong>
								<div class="clear"></div>
							</a>
						</div>
					</div>
					<?php } ?>

				<?php } ?>

				<div class="col-md-4">

					<div class="lista-menus-menu lista-menus-nocounter">
						<a class="bt" href="<?php panel_url(); ?>/plano">
							<i class="lni lni-radio-button"></i>
							<span>Meu plano</span>
							<div class="clear"></div>
						</a>
					</div>

				</div>

				<div class="col-md-4">
					<div class="lista-menus-menu lista-menus-nocounter">
						<a class="bt" href="<?php panel_url(); ?>/configuracoes">
							<i class="lni lni-cog"></i>
							<span>Configurações</span>
							<div class="clear"></div>
						</a>
					</div>
				</div>

				<div class="col-md-4">
					<div class="lista-menus-menu lista-menus-nocounter">
						<a class="bt" href="<?php panel_url(); ?>/frete">
							<i class="lni lni-delivery"></i>
							<span>Delivery</span>
							<div class="clear"></div>
						</a>
					</div>
				</div>

				<div class="col-md-4">
					<div class="lista-menus-menu lista-menus-nocounter">
						<a class="bt" href="<?php panel_url(); ?>/cupons">
							<i class="lni lni-ticket"></i>
							<span>Cupons</span>
							<div class="clear"></div>
						</a>
					</div>
				</div>

				<div class="col-md-4">
					<div class="lista-menus-menu lista-menus-nocounter">
						<a class="bt" href="<?php panel_url(); ?>/integracao">
							<i class="lni lni-database"></i>
							<span>Integração</span>
							<div class="clear"></div>
						</a>
					</div>
				</div>

				<div class="col-md-4">
					<div class="lista-menus-menu lista-menus-nocounter">
						<a class="bt" href="<?php panel_url(); ?>/qrcode">
							<i class="lni lni-frame-expand"></i>
							<span>QRCode da Empresa</span>
							<div class="clear"></div>
						</a>
					</div>
				</div>
				
				<?php if($dataestabelecimento['outros'] == 1) { ?>
				<div class="col-md-4">
					<div class="lista-menus-menu lista-menus-nocounter">
						<a class="bt" href="<?php panel_url(); ?>/local">
							<i class="lni lni-handshake"></i>
							<span>Opções de Entrega</span>
							<div class="clear"></div>
						</a>
					</div>
				</div>
				<?php } ?>
				
				<div class="col-md-4">
					<div class="lista-menus-menu lista-menus-nocounter">
						<a class="bt" href="<?php panel_url(); ?>/relatorio">
							<i class="lni lni-printer"></i>
							<span>Relatórios</span>
							<div class="clear"></div>
						</a>
					</div>
				</div>
				
				<div class="col-md-4">
					<div class="lista-menus-menu lista-menus-nocounter">
						<a class="bt" href="<?php panel_url(); ?>/impressao">
							<i class="lni lni-printer"></i>
							<span>Impressão Automática</span>
							<div class="clear"></div>
						</a>
					</div>
				</div>

				<div class="col-md-4">
					<div class="lista-menus-menu lista-menus-nocounter">
						<a class="bt" href="https://wa.me/55<?php print $usrtelefone;?>" target="_blank">
							<i class="lni lni-whatsapp"></i>
							<span>Suporte técnico</span>
							<div class="clear"></div>
						</a>
					</div>
				</div>
				
				<div class="col-md-4">
					<div class="lista-menus-menu lista-menus-nocounter">
						<a class="bt" href="../../logout">
							<i class="lni lni-power-switch"></i>
							<span>Sair</span>
							<div class="clear"></div>
						</a>
					</div>
				</div>

			</div>

		</div>

	</div>

</div>

<?php 
// FOOTER
$system_footer .= "";
include('../_layout/rdp.php');
include('../_layout/footer.php');
?>

<script>

$(".funcionamento").click(function() {
	
	$( this ).html("<div class='atualizando'><i class='lni lni-reload rotating'></i></div>");
	setTimeout(() => { 
		$( this ).load("<?php panel_url(); ?>/_ajax/funcionamento.php?eid=<?php echo $_SESSION['estabelecimento']['id']; ?>&token=<?php echo session_id(); ?>");
    }, 400);

});

</script>