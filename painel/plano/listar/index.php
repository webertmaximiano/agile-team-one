<?php
// CORE
include('../../../_core/_includes/config.php');
// RESTRICT
restrict_estabelecimento();
// SEO
$seo_subtitle = "Contratar plano";
$seo_description = "";
$seo_keywords = "";
// HEADER
$system_header .= "";
include('../../_layout/head.php');
include('../../_layout/top.php');
include('../../_layout/sidebars.php');
include('../../_layout/modal.php');
?>

<?php

global $db_con;
$eid = $_SESSION['estabelecimento']['id'];

// Variables

$estabelecimento = mysqli_real_escape_string( $db_con, $_GET['estabelecimento_id'] );
$numero = mysqli_real_escape_string( $db_con, $_GET['numero'] );
$nome = mysqli_real_escape_string( $db_con, $_GET['nome'] );
$status = mysqli_real_escape_string( $db_con, $_GET['status'] );

$getdata = "";

foreach($_GET as $query_string_variable => $value) {
  if( $query_string_variable != "pagina" ) {
    $getdata .= "&$query_string_variable=".htmlclean($value);
  }
}

// Config

$limite = 20;
$pagina = $_GET["pagina"] == "" ? 1 : $_GET["pagina"];
$inicio = ($pagina * $limite) - $limite;

// Query

$query .= "SELECT * FROM planos ";

$query .= "WHERE 1=1 ";

$query .= "AND status = '1' AND visible = '1' ";

$query_full = $query;

$query .= "ORDER BY id DESC LIMIT $inicio,$limite";

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

<?php if( $_GET['msg'] == "erro" ) { ?>

<?php modal_alerta("Erro, tente novamente!","erro"); ?>

<?php } ?>

<?php if( $_GET['msg'] == "sucesso" ) { ?>

<?php modal_alerta("Ação efetuada com sucesso!","sucesso"); ?>

<?php } ?>

<?php if( $_GET['msg'] == "bemvindo" ) { ?>

<?php modal_alerta("Seu catálogo foi criado com sucesso, contrate um plano para poder usufruir de todas as funcionalidades, ou aproveite o seu período de testes!","sucesso"); ?>

<?php } ?>

<div class="middle minfit bg-gray">

	<div class="container">

		<div class="row">

			<div class="col-md-12">

				<div class="title-icon pull-left">
					<i class="lni lni-star"></i>
					<span>Contratar plano</span>
				</div>

				<div class="bread-box pull-right">
					<div class="bread">
						<a href="<?php panel_url(); ?>"><i class="lni lni-home"></i></a>
						<span>/</span>
						<a href="<?php panel_url(); ?>/plano/listar">Contratar plano</a>
					</div>
				</div>

			</div>

		</div>

		<!-- Atual -->

		<div class="row">

			<?php
	        while ( $data = mysqli_fetch_array( $sql ) ) {
	        ?>

			<div class="col-md-4">

				<div class="panel-group panel-filters">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" href="#collapse-atual_">
									<span class="desc"><?php echo $data['nome']; ?></span>
									<i class="lni lni-star"></i>
									<div class="clear"></div>
								</a>
							</h4>
						</div>
						<div id="collapse-atual" class="panel-collapse collapse in">
							<div class="panel-body panel-body-planos">

								<div class="plano plano-interna">
									<div class="row">
										<div class="col-md-12">
											<div class="cover">
<!-- 												<div class="foto">
													<img src="<?php echo imager( $data['destaque'] ); ?>"/>
												</div>
												<span class="titulo"><?php echo $data['nome']; ?></span> -->
												<div class="desc">
													<?php echo nl2br( bbzap( $data['descricao'] ) ); ?>
												</div>
												<div class="valor">
													<span class="parcela"><?php echo $data['duracao_meses']; ?>x de</span>
													<span class="mensal">R$ <?php echo dinheiro( $data['valor_mensal'], "BR" ); ?> por mês</span>
													<span class="total">ou R$ <?php echo dinheiro( $data['valor_total'], "BR" ); ?> a vista</span>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="add-new add-center text-center">
												<a href="<?php panel_url(); ?>/plano/contratar?plano=<?php echo $data['id']; ?>">
													<span>Escolher plano</span>
													<i class="lni lni-plus"></i>
												</a>
											</div>
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div> 

			</div>

			<?php } ?>

		</div>

		<!-- / Atual -->

		<!-- Voucher -->

		<div class="row">

			<div class="col-md-12">

				<div class="panel-group panel-filters voucher">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" href="#collapse-voucher">
									<span class="desc">Utilizar voucher</span>
									<i class="lni lni-ticket"></i>
									<div class="clear"></div>
								</a>
							</h4>
						</div>
						<div id="collapse-voucher" class="panel-collapse collapse">
							<div class="panel-body panel-body-planos">
								
								<form action="<?php panel_url(); ?>/plano/contratar" method="GET">
									<div class="row">
										<div class="col-md-9">
											<div class="form-field-default">
												<input type="text" name="voucher" placeholder="Código do voucher" value="<?php echo htmlclean( $_GET['voucher'] ); ?>"/>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-field-default">
												<button>
													<span>Resgatar</span>
													<i class="lni lni-ticket"></i>
												</button>
											</div>
										</div>
									</div>
								</form>

							</div>
						</div>
					</div>
				</div> 


			</div>

		</div>

		<!-- / Voucher -->

	</div>

</div>

<?php 
// FOOTER
$system_footer .= "";
include('../../_layout/rdp.php');
include('../../_layout/footer.php');
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