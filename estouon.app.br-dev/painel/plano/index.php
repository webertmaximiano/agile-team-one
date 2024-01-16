<?php
// CORE
include('../../_core/_includes/config.php');
// RESTRICT
restrict(2);
atualiza_estabelecimento( $_SESSION['estabelecimento']['id'], "online" );
// SEO
$seo_subtitle = "Meu plano";
$seo_description = "";
$seo_keywords = "";
// HEADER
$system_header .= "";
include('../_layout/head.php');
include('../_layout/top.php');
include('../_layout/sidebars.php');
include('../_layout/modal.php');

$acao = $_GET['acao'];
$eid = $_SESSION['estabelecimento']['id'];
$id = mysqli_real_escape_string( $db_con, $_GET['id'] );

if( $acao == "remover" ) {

	$cando = 0;

	if( data_info( "assinaturas",$id,"rel_estabelecimentos_id" ) == $eid ) {
		$cando = 1;
	}

	if( $cando )  {
	
		if( remover_assinatura( $id ) ) {

			header("Location: index.php?msg=deletada");

		} else {

			header("Location: index.php?msg=erro");

		}

	} else {

		header("Location: index.php?msg=erro");

	}

}

?>

<?php

global $db_con;
$eid = $_SESSION['estabelecimento']['id'];

?>

<?php if( $_GET['msg'] == "naoaplicado" ) { ?>

<?php modal_alerta("Erro ao tentar utilizar voucher!","erro"); ?>

<?php } ?>

<?php if( $_GET['msg'] == "aplicado" ) { ?>

<?php modal_alerta("Voucher aplicado com sucesso!","sucesso"); ?>

<?php } ?>

<?php if( $_GET['msg'] == "obrigado" ) { ?>

<?php modal_alerta("Seu plano foi contratado com sucesso e será liberado logo após processarmos o pagamento!","sucesso"); ?>

<?php } ?>

<?php if( $_GET['msg'] == "deletada" ) { ?>

<?php modal_alerta("Compra removida com sucesso!","sucesso"); ?>

<?php } ?>

<?php if( $_GET['msg'] == "erro" ) { ?>

<?php modal_alerta("Erro ao tentar realizar ação, tente novamente mais tarde!","erro"); ?>

<?php } ?>

<div class="middle minfit bg-gray">

	<div class="container">

		<div class="row">

			<div class="col-md-12">

				<div class="title-icon pull-left">
					<i class="lni lni-star"></i>
					<span>Meu plano</span>
				</div>

				<div class="bread-box pull-right">
					<div class="bread">
						<a href="<?php panel_url(); ?>"><i class="lni lni-home"></i></a>
						<span>/</span>
						<a href="<?php panel_url(); ?>/plano">Meu plano</a>
					</div>
				</div>

			</div>

		</div>

		<!-- Atual -->

		<div class="row">

			<div class="col-md-12">

				<?php
				$hoje = date("Y-m-d");
				$eid = $_SESSION['estabelecimento']['id'];
				$query_pendentes = mysqli_query( $db_con, "SELECT * FROM assinaturas WHERE gateway_expiration >= '$hoje' AND status = '0' AND rel_estabelecimentos_id = '$eid' AND excluded != '1' ORDER BY id DESC" );
				$total_pendentes = mysqli_num_rows( $query_pendentes );
				?>

				<?php if( $total_pendentes >= 1 ) { ?>

					<div class="panel-group panel-filters panel-pendentes">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4 class="panel-title">
									<a data-toggle="collapse" href="#collapse-pendentes">
										<span class="desc"><?php echo $total_pendentes; ?> Fatura<?php if( $total_pendentes > 1 ) { echo 's'; }; ?> pendente<?php if( $total_pendentes > 1 ) { echo 's'; }; ?></span>
										<i class="lni lni-ticket-alt"></i>
										<div class="clear"></div>
									</a>
								</h4>
							</div>
							<div id="collapse-pendentes" class="panel-collapse collapse">
								<div class="panel-body panel-body-pendentes">
									<div class="row">
										<div class="col-md-12">
											<span class="pendente-tip">Os planos que não tiverem o seu pagamento efetuado serão automaticamente cancelados em 7 dias corridos, caso já tenha efetuado o pagamento do boleto, espere o tempo de compensação de até 3 dis úteis.</span>
										</div>
									</div>
									<div class="row">

										<div class="col-md-12">

											<table class="listing-table fake-table clean-table table-pedidos">
												<thead>
													<th></th>
													<th>Nº</th>
													<th>Plano</th>
													<th>Pagável até</th>
													<th>Valor</th>
													<th>Status</th>
													<th></th>
												</thead>
												<tbody>

													<?php
						                            while ( $data_pendentes = mysqli_fetch_array( $query_pendentes ) ) {
						                            ?>

													<tr class="fullwidth">
														<td>
															<a class="color-red cancelar-compra" onclick="if(confirm('Tem certeza que cancelar essa compra?')) document.location = '<?php panel_url(); ?>/plano/?acao=remover&id=<?php echo $data_pendentes['id']; ?>'" href="#" title="Excluir"><i class="lni lni-trash"></i></a>
														</td>
														<td>
						                                    <div class="fake-table-data fake-table-data-zero"><span class="pendente-title">Assinatura #<?php echo $data_pendentes['id']; ?></span></div>
						                                    <div class="fake-table-break"></div>
														</td>
														<td>
						                                    <span class="fake-table-title hidden-xs hidden-sm">Plano</span>
						                                    <div class="fake-table-data"><?php echo htmlclean( $data_pendentes['nome'] ); ?></div>
						                                    <div class="fake-table-break"></div>
														</td>
														<td>
						                                    <span class="fake-table-title">Pagável até</span>
						                                    <div class="fake-table-data">
						                                    	<span><?php echo databr_min( $data_pendentes['gateway_payable'] ); ?></span>
						                                    </div>
						                                    <div class="fake-table-break"></div>
														</td>
														<td>
						                                    <span class="fake-table-title">Valor</span>
						                                    <div class="fake-table-data">
						                                    	<span>R$ <?php echo dinheiro( $data_pendentes['valor_total'],"BR" ); ?></span>
						                                    </div>
						                                    <div class="fake-table-break"></div>
														</td>
														<td <?php if( $hoje > $data_pendentes['gateway_payable'] ) { echo "colspan='2'"; }; ?>>
						                                    <span class="fake-table-title hidden-xs hidden-sm">Status</span>
						                                    <div class="fake-table-data">
						                                    	<?php if( $data_pendentes['status'] == "0" ) { ?>
						                                    		<span class="badge badge-pendente">Aguardando pagamento</span>
						                                    	<?php } ?>
						                                    </div>
						                                    <div class="fake-table-break"></div>
														</td>
														<?php if( $hoje <= $data_pendentes['gateway_payable'] ) { ?>
														<td>
															<span class="fake-table-title hidden-xs hidden-sm">Pagar</span>
						                                    <div class="fake-table-data">
																<div class="add-new add-table text-center">
																	<a target="_blank" href="<?php echo $data_pendentes['gateway_link']; ?>">
																		<span>Efetuar pagamento</span>
																		<i class="lni lni-coin"></i>
																	</a>
																</div>
						                                    </div>
						                                    <div class="fake-table-break"></div>
														</td>
														<?php } ?>
													</tr>

						                            <?php } ?>

												</tbody>
											</table>

										</div>

									</div>
								</div>
							</div>
						</div>
					</div> 

				<?php } ?>

				<div class="panel-group panel-filters">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" href="#collapse-atual_">
									<span class="desc">Plano atual</span>
									<i class="lni lni-star"></i>
									<div class="clear"></div>
								</a>
							</h4>
						</div>
						<div id="collapse-atual" class="panel-collapse collapse in">
							<div class="panel-body panel-body-planos">

								<div class="plano plano-interna">
									<div class="titulo-min">
										<i class="lni lni-text-align-justify"></i>
										<span>Situação:</span>
									</div>
									<div class="content">
										<div class="row">
											<div class="col-md-6 col-sm-6 col-xs-6">
												<span class="info">Status:</span>
											</div>
											<div class="col-md-6 col-sm-6 col-xs-6">
												<?php if( $_SESSION['estabelecimento']['status'] == "1" ) { ?>
													<span class="badge badge-concluido pull-right">Ativo</span>
												<?php } else { ?>
													<span class="badge badge-cancelado pull-right">Inativo</span>
												<?php } ?>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6 col-sm-6 col-xs-6">
												<span class="info">Expiração:</span>
											</div>
											<div class="col-md-6 col-sm-6 col-xs-6">
												<span class="pull-right"><?php if( $_SESSION['estabelecimento']['expiracao'] >= 1 ) { echo $_SESSION['estabelecimento']['expiracao']." dias"; } else { echo "Expirado"; }  ?></span>
											</div>
										</div>
									</div>
									<?php if( $_SESSION['estabelecimento']['status'] == "1" ) { ?>
									<div class="titulo-min">
										<i class="lni lni-text-align-justify"></i>
										<span>Funcionalidades:</span>
									</div>
									<div class="content">
										<div class="row">
											<div class="col-md-9 col-sm-9 col-xs-9">
												<span class="info">Limite de produtos:</span>
											</div>
											<div class="col-md-3 col-sm-3 col-xs-3">
												<span class="badge badge-concluido pull-right"><?php echo data_info( "estabelecimentos", $_SESSION['estabelecimento']['id'],"limite_produtos" ); ?></span>
											</div>
										</div>
										<div class="row">
											<div class="col-md-9 col-sm-9 col-xs-9">
												<span class="info">Pedidos ilimitados:</span>
											</div>
											<div class="col-md-3 col-sm-3 col-xs-3">
												<span class="badge badge-concluido pull-right">Sim</span>
											</div>
										</div>
										<div class="row">
											<div class="col-md-9 col-sm-9 col-xs-9">
												<span class="info">Categorias ilimitadas:</span>
											</div>
											<div class="col-md-3 col-sm-3 col-xs-3">
												<span class="badge badge-concluido pull-right">Sim</span>
											</div>
										</div>
										<div class="row">
											<div class="col-md-9 col-sm-9 col-xs-9">
												<span class="info">Marketplace:</span>
											</div>
											<div class="col-md-3 col-sm-3 col-xs-3">
												<?php if( $_SESSION['estabelecimento']['funcionalidade_marketplace'] == "1" ) { ?>
													<span class="badge badge-concluido pull-right">Sim</span>
												<?php } else { ?>
													<span class="badge badge-cancelado pull-right">Não</span>
												<?php } ?>
											</div>
										</div>
										<div class="row">
											<div class="col-md-9 col-sm-9 col-xs-9">
												<span class="info">Variação de produtos:</span>
											</div>
											<div class="col-md-3 col-sm-3 col-xs-3">
												<?php if( $_SESSION['estabelecimento']['funcionalidade_variacao'] == "1" ) { ?>
													<span class="badge badge-concluido pull-right">Sim</span>
												<?php } else { ?>
													<span class="badge badge-cancelado pull-right">Não</span>
												<?php } ?>
											</div>
										</div>
										<div class="row">
											<div class="col-md-9 col-sm-9 col-xs-9">
												<span class="info">Banners:</span>
											</div>
											<div class="col-md-3 col-sm-3 col-xs-3">
												<?php if( $_SESSION['estabelecimento']['funcionalidade_banners'] == "1" ) { ?>
													<span class="badge badge-concluido pull-right">Sim</span>
												<?php } else { ?>
													<span class="badge badge-cancelado pull-right">Não</span>
												<?php } ?>
											</div>
										</div>
									</div>
									<?php } ?>
								</div>

							</div>
						</div>
					</div>
				</div> 

			</div>

		</div>

		<!-- / Atual -->

		<div class="row">
			<div class="col-md-12">
				<span class="text-center tip-plan">
					Ao contratar um novo plano, os dias e funcionalidades contratados nos seus planos atuais serão mantidos.
				</span>
				<div class="add-new add-center text-center">

					<a href="<?php panel_url(); ?>/plano/listar">
						<span>Contratar novo</span>
						<i class="lni lni-plus"></i>
					</a>

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

$( document ).ready(function() {

	$( "input[name=estabelecimento]" ).change(function() {
		$( "input[name=estabelecimento_id]" ).trigger("change");
	});

	$( "input[name=estabelecimento_id]" ).change(function() {
	    var estabelecimento = $(this).val();
	    $("#input-categoria").html("<option>-- Carregando categorias --</option>");
	    $("#input-categoria").load("<?php just_url(); ?>/_core/_ajax/categorias.php?estabelecimento="+estabelecimento);
	});

});


</script>