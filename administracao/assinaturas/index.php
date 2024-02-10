<?php
// CORE
include('../../_core/_includes/config.php');
// RESTRICT
restrict('1');
// SEO
$seo_subtitle = "Assinaturas";
$seo_description = "";
$seo_keywords = "";
// HEADER
$system_header .= "";
include('../_layout/head.php');
include('../_layout/top.php');
include('../_layout/sidebars.php');
include('../_layout/modal.php');
?>

<?php

global $db_con;

// Variables

$numero = mysqli_real_escape_string( $db_con, isset($_GET['numero']) );
$status = mysqli_real_escape_string( $db_con, isset($_GET['status']) );
$uso = mysqli_real_escape_string( $db_con, isset($_GET['uso']) );
$estabelecimento = mysqli_real_escape_string( $db_con, isset($_GET['estabelecimento_id']) );
if( !isset($_GET['estabelecimento']) ) {
	$_GET['estabelecimento'] = htmlclean( data_info( "estabelecimentos",$estabelecimento,"nome" ) );
}
$plano = mysqli_real_escape_string( $db_con, isset($_GET['plano']) );

$getdata = "";

foreach($_GET as $query_string_variable => $value) {
  if( $query_string_variable != "pagina" ) {
    $getdata .= "&$query_string_variable=".htmlclean($value);
  }
}

// Config

$limite = 20;
$pagina = isset($_GET["pagina"]) == "" ? 1 : $_GET["pagina"];
$inicio = ($pagina * $limite) - $limite;

// Query
$query = '';
$query .= "SELECT * FROM assinaturas ";
$query .= "WHERE 1=1 ";

if( $numero ) {
  $query .= "AND id = '$numero' ";
}

if( strlen($status) >= 1 ) {
  $query .= "AND status = '$status' ";
}

if( strlen($uso) >= 1 ) {
  $query .= "AND used = '$uso' ";
}

if( strlen($plano) >= 1 ) {
  $query .= "AND rel_planos_id = '$plano' ";
}

if( $estabelecimento ) {
  $query .= "AND rel_estabelecimentos_id = '$estabelecimento' ";
}

$data_inicial = mysqli_real_escape_string( $db_con, isset($_GET['data_inicial']) );
if( !isset($data_inicial) ) { $data_inicial = date("d/m/").(date(Y)-1); }
$data_inicial_sql = datausa_min( $data_inicial );
$data_inicial_sql = $data_inicial_sql." 00:00:00";

$data_final = mysqli_real_escape_string( $db_con, isset($_GET['data_final']) );
if( !isset($data_final) ) { $data_final = date("d/m/Y"); }
$data_final_sql = datausa_min( $data_final );
$data_final_sql = $data_final_sql." 23:59:59";

if( strlen( $data_inicial ) >= 1 OR strlen( $data_final ) >= 1 ) {
  $query .= "AND (created > '$data_inicial_sql' AND created < '$data_final_sql') ";
}

if( $estabelecimento ) {
  $query .= "AND rel_estabelecimentos_id = '$estabelecimento' ";
}

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
<?php if( isset($_GET['msg'])) { ?>
	<?php if( $_GET['msg'] == "erro" ) { ?>

		<?php modal_alerta("Erro, tente novamente!","erro"); ?>

	<?php } ?>

	<?php if( $_GET['msg'] == "sucesso" ) { ?>

		<?php modal_alerta("Ação efetuada com sucesso!","sucesso"); ?>

	<?php } ?>
<?php } ?>
<div class="middle minfit bg-gray">

	<div class="container">

		<div class="row">

			<div class="col-md-12">

				<div class="title-icon pull-left">
					<i class="lni lni-star"></i>
					<span>Assinaturas</span>
				</div>

				<div class="bread-box pull-right">
					<div class="bread">
						<a href="<?php admin_url(); ?>"><i class="lni lni-home"></i></a>
						<span>/</span>
						<a href="<?php admin_url(); ?>/assinaturas">Assinaturas</a>
					</div>
				</div>

			</div>

		</div>

		<!-- Filters -->

		<div class="row">

			<div class="col-md-12">

				<div class="panel-group panel-filters">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" href="#collapse-filtros">
									<span class="desc">Filtrar</span>
									<i class="lni lni-funnel"></i>
									<div class="clear"></div>
								</a>
							</h4>
						</div>
						<div id="collapse-filtros" class="panel-collapse collapse <?php if( $_GET['filtered'] ) { echo 'in'; }; ?>">
							<div class="panel-body">

								<form class="form-filters form-100" method="GET">

									<div class="row">
										<div class="col-md-3">
											<div class="form-field-default">
												<label>Nº:</label>
												<input type="text" name="numero" placeholder="Numero" value="<?php echo htmlclean( $numero ); ?>"/>
											</div>
										</div>
										<div class="col-md-3">
							              <div class="form-field-default">
							                  <label>Estabelecimento:</label>
							                  <input class="autocompleter <?php if( $_GET['estabelecimento'] && $_GET['estabelecimento_id'] ) { echo "autocomplete-selected"; } ?>" type="text" name="estabelecimento" placeholder="Estabelecimento" value="<?php echo htmlclean( $_GET['estabelecimento'] ); ?>" completer_url="<?php just_url(); ?>/_core/_ajax/autocomplete_estabelecimentos.php" completer_field="estabelecimento_id"/>
							                  <input class="fakehidden" type="text" name="estabelecimento_id" value="<?php echo htmlclean( $_GET['estabelecimento_id'] ); ?>"/>
							              </div>
										</div>
										<div class="col-md-3">
							              <div class="form-field-default">
							               <label>Status:</label>
											<div class="fake-select">
												<i class="lni lni-chevron-down"></i>
												<select name="status">
													<option></option>
		                                            <?php for( $x = 0; $x < count( $numeric_data['assinatura_status'] ); $x++ ) { ?>
		                                            <option value="<?php echo $numeric_data['assinatura_status'][$x]['value']; ?>" <?php if( $_GET['status'] == $numeric_data['assinatura_status'][$x]['value'] ) { echo 'SELECTED'; }; ?>><?php echo $numeric_data['assinatura_status'][$x]['name']; ?></option>
		                                            <?php } ?>
												</select>
												<div class="clear"></div>
											</div>
							              </div>
										</div>
										<div class="col-md-3">
							              <div class="form-field-default">
							               <label>Uso:</label>
											<div class="fake-select">
												<i class="lni lni-chevron-down"></i>
												<select name="uso">
													<option></option>
		                                            <?php for( $x = 0; $x < count( $numeric_data['assinatura_use'] ); $x++ ) { ?>
		                                            <option value="<?php echo $numeric_data['assinatura_use'][$x]['value']; ?>" <?php if( $_GET['uso'] == $numeric_data['assinatura_use'][$x]['value'] ) { echo 'SELECTED'; }; ?>><?php echo $numeric_data['assinatura_use'][$x]['name']; ?></option>
		                                            <?php } ?>
												</select>
												<div class="clear"></div>
											</div>
							              </div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3">
							              <div class="form-field-default">
							               <label>Plano:</label>
											<div class="fake-select">
												<i class="lni lni-chevron-down"></i>
												<select name="plano">
							                      <option value=""></option>
							                      <?php 
							                      $quicksql = mysqli_query( $db_con, "SELECT * FROM planos ORDER BY nome ASC LIMIT 10" );
							                      while( $quickdata = mysqli_fetch_array( $quicksql ) ) {
							                      ?>
							                      <option <?php if( $plano == $quickdata['id'] ) { echo "SELECTED"; }; ?> value="<?php echo $quickdata['id']; ?>"><?php echo $quickdata['nome']; ?></option>
							                      <?php } ?>
												</select>
												<div class="clear"></div>
											</div>
							              </div>
										</div>
										<div class="col-md-3">
											<div class="form-field-default">
												<label>Data inicial:</label>
												<input class="maskdate datepicker" type="text" name="data_inicial" placeholder="Data inicial" value="<?php echo htmlclean( $data_inicial ); ?>"/>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-field-default">
												<label>Data final:</label>
												<input class="maskdate datepicker" type="text" name="data_final" placeholder="Data inicial" value="<?php echo htmlclean( $data_final ); ?>"/>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-field-default">
												<label class="hidden-xs hidden-sm"></label>
												<input type="hidden" name="filtered" value="1"/>
												<button>
													<span>Buscar</span>
													<i class="lni lni-search-alt"></i>
												</button>
											</div>
										</div>
									</div>
									<?php if( $_GET['filtered'] ) { ?>
									<div class="row">
										<div class="col-md-12">
										    <a href="<?php admin_url(); ?>/categorias" class="limpafiltros"><i class="lni lni-close"></i> Limpar filtros</a>
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

		<!-- / Filters -->

		<!-- Content -->

		<div class="listing">

			<div class="row">
				<div class="col-md-6 col-sm-6 col-xs-6">
					<span class="listing-title"><strong class="counter"><?php echo $total_results_full; ?></strong> Registros:</span>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-6">
					<div class="add-new pull-right">

						<a href="<?php admin_url(); ?>/planos">
							<span>Gerenciar planos</span>
							<i class="lni lni-list"></i>
						</a>

					</div>
				</div>
			</div>

			<div class="row">

				<div class="col-md-12">

					<table class="listing-table fake-table clean-table">
						<thead>
							<th></th>
							<th>Nº</th>
							<th>Estabelecimento</th>
							<th>Plano</th>
							<th>Status</th>
							<th>Uso</th>
							<th>Registrado</th>
							<th></th>
						</thead>
						<tbody>

							<?php
                            while ( $data = mysqli_fetch_array( $sql ) ) {
                            ?>

							<tr>
								<td>
                                    <div class="fake-table-data">
                                    	<i class="lni lni-star colored"></i>
                                    </div>
                                    <div class="fake-table-break"></div>
								</td>
								<td>
                                    <span class="fake-table-title hidden-xs hidden-sm">Nº</span>
                                    <div class="fake-table-data">#<?php echo htmlclean( $data['id'] ); ?></div>
                                    <div class="fake-table-break"></div>
								</td>
								<td class="hidden-xs hidden-sm">
                                    <span class="fake-table-title">Estabelecimento</span>
                                    <?php
                                    global $httprotocol;
                                    global $simple_url;
                                    if( data_info("estabelecimentos",$data['rel_estabelecimentos_id'],"nome") ) {
                                    	$enome = data_info("estabelecimentos",$data['rel_estabelecimentos_id'],"nome");
                                    } else {
                                    	$enome = $data['rel_estabelecimentos_nome'];
                                    }
                                    if( data_info("estabelecimentos",$data['rel_estabelecimentos_id'],"subdominio") ) {
                                    	$eurl = $httprotocol.data_info("estabelecimentos",$data['rel_estabelecimentos_id'],"subdominio").".".$simple_url;
                                    } else {
                                    	$eurl = $httprotocol.$data['rel_estabelecimentos_subdominio'].".".$simple_url;
                                    }
                                    ?>
                                    <div class="fake-table-data"><a target="_blank" href="<?php echo $eurl; ?>"><?php echo $enome; ?></a></div>
                                    <div class="fake-table-break"></div>
								</td>
								<td>
                                    <span class="fake-table-title hidden-xs hidden-sm">Plano</span>
                                    <div class="fake-table-data"><?php echo $data['nome']; ?></div>
                                    <div class="fake-table-break"></div>
								</td>
								<td class="hidden-xs hidden-sm">
                                    <span class="fake-table-title">Status</span>
                                    <div class="fake-table-data"><?php echo numeric_data( "assinatura_status", $data['status'] ); ?></div>
                                    <div class="fake-table-break"></div>
								</td>
								<td class="hidden-xs hidden-sm">
                                    <span class="fake-table-title">Uso</span>
                                    <div class="fake-table-data"><?php echo numeric_data( "assinatura_use", $data['used'] ); ?></div>
                                    <div class="fake-table-break"></div>
								</td>
								<td class="hidden-xs hidden-sm">
                                    <span class="fake-table-title">Registrado</span>
                                    <div class="fake-table-data"><?php echo databr( $data['created'] ); ?></div>
                                    <div class="fake-table-break"></div>
								</td>
								<td>
									<span class="fake-table-title hidden-xs hidden-sm">Ações</span>
                                    <div class="fake-table-data">
										<div class="form-actions pull-right">
											<a class="color-yellow pull-right" href="<?php admin_url(); ?>/assinaturas/editar?id=<?php echo $data['id']; ?>" title="Editar"><i class="lni lni-pencil"></i></a>
											<a class="color-red" onclick="if(confirm('Tem certeza que deseja remover esta assinatura?')) document.location = '<?php admin_url(); ?>/assinaturas/deletar/?id=<?php echo $data['id']; ?>'" href="#" title="Excluir"><i class="lni lni-trash"></i></a>
										</div>
                                    </div>
                                    <div class="fake-table-break"></div>
								</td>
							</tr>

                            <?php } ?>

                            <?php if( $total_results == 0 ) { ?>

                               <tr>
                                <td colspan="10">
                                  <div class="fake-table-data">
                                    <span class="nulled">Nenhum registro cadastrado ou compatível com a sua filtragem!</span>
                                  </div>
                                  <div class="fake-table-break"></div>
                                </td>
                               </tr>

                            <?php } ?>

						</tbody>
					</table>

				</div>

			</div>

		</div>

		<!-- / Content -->

		<!-- Pagination -->

        <div class="paginacao">

          <ul class="pagination">

            <?php
            $paginationpath = "assinaturas";
            if($pagina > 1) {
              $back = $pagina-1;
              echo '<li class="page-item pagination-back"><a class="page-link" href=" '.get_system_url().'/'.$paginationpath.'/?pagina='.$back.$getdata.' "><i class="lni lni-chevron-left"></i></a></li>';
            }
     
              for($i=$pagina-1; $i <= $pagina-1; $i++) {

                  if($i > 0) {
                  
                      echo '<li class="page-item pages-before"><a class="page-link" href=" '.get_system_url().'/'.$paginationpath.'/?pagina='.$i.$getdata.' ">'.$i.'</a></li>';
                  }

              }

              if( $pagina >= 1 ) {

                echo '<li class="page-item active"><a class="page-link" href=" '.get_system_url().'/'.$paginationpath.'/?pagina='.$i.$getdata.'" class="page-link">'.$i.'</a></li>';

              }

              for($i=$pagina+1; $i <= $pagina+1; $i++) {

                  if($i >= $total_paginas) {
                    break;
                  }  else {
                      echo '<li class="page-item pages-after"><a class="page-link" href=" '.get_system_url().'/'.$paginationpath.'/?pagina='.$i.$getdata.' ">'.$i.'</a></li> ';
                  }
              
              }

            if($pagina < $total_paginas-1) {
              $next = $pagina+1;
              echo '<li class="page-item pagination-next"><a class="page-link" href=" '.get_system_url().'/'.$paginationpath.'/?pagina='.$next.$getdata.' "><i class="lni lni-chevron-right"></i></a></li>';
            }

            ?>

          </ul>

        </div>

		<!-- / Pagination -->

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