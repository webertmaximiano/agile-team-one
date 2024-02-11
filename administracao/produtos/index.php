<?php
// CORE
include('../../_core/_includes/config.php');
// RESTRICT
restrict('1');
// SEO
$seo_subtitle = "Produtos";
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

$estabelecimento = mysqli_real_escape_string( $db_con, $_GET['estabelecimento_id'] ?? 0 );
$categoria = mysqli_real_escape_string( $db_con, $_GET['categoria_id'] ?? 0);
$nome = mysqli_real_escape_string( $db_con, $_GET['nome'] ?? '');
$visible = mysqli_real_escape_string( $db_con, $_GET['visible'] ?? '');
$status = mysqli_real_escape_string( $db_con, $_GET['status'] ?? '');

$getdata = "";

foreach($_GET as $query_string_variable => $value) {
  if( $query_string_variable != "pagina" ) {
    $getdata .= "&$query_string_variable=".htmlclean($value);
  }
}

// Config

$limite = 20;
$pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$inicio = ($pagina * $limite) - $limite;

// Query
$query = '';
$query .= "SELECT * FROM produtos ";

$query .= "WHERE 1=1 ";

if( $estabelecimento ) {
  $query .= "AND rel_estabelecimentos_id = '$estabelecimento' ";
}

if( $categoria ) {
  $query .= "AND rel_categorias_id = '$categoria' ";
}

if( $nome ) {
  $query .= "AND nome LIKE '$nome%' ";
}

if( $visible ) {
  $query .= "AND visible = '$visible' ";
}

if( $status ) {
  $query .= "AND status = '$status' ";
}

$query_full = $query;

$query .= "ORDER BY last_modified DESC LIMIT $inicio,$limite";

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

<?php if( isset($_GET['msg']) == "erro" ) { ?>

<?php modal_alerta("Erro, tente novamente!","erro"); ?>

<?php } ?>

<?php if( isset($_GET['msg']) == "sucesso" ) { ?>

<?php modal_alerta("Ação efetuada com sucesso!","sucesso"); ?>

<?php } ?>

<div class="middle minfit bg-gray">

	<div class="container">

		<div class="row">

			<div class="col-md-12">

				<div class="title-icon pull-left">
					<i class="lni lni-shopping-basket"></i>
					<span>Produtos</span>
				</div>

				<div class="bread-box pull-right">
					<div class="bread">
						<a href="<?php admin_url(); ?>"><i class="lni lni-home"></i></a>
						<span>/</span>
						<a href="<?php admin_url(); ?>/produtos">Produtos</a>
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
						<div id="collapse-filtros" class="panel-collapse collapse <?php if( isset($_GET['filtered']) ) { echo 'in'; }; ?>">
							<div class="panel-body">

								<form class="form-filters form-100" method="GET">

									<div class="row">
										<div class="col-md-2">
											<div class="form-field-default">
												<label>Nome:</label>
												<input type="text" name="nome" placeholder="Nome" value="<?php echo htmlclean( $nome ); ?>"/>
											</div>
										</div>
										<div class="col-md-2">
							              <div class="form-field-default">
							                  <label>Estabelecimento:</label>
							                  <input class="autocompleter <?php if( isset($_GET['estabelecimento']) && isset($_GET['estabelecimento_id']) ) { echo "autocomplete-selected"; } ?>" type="text" name="estabelecimento" placeholder="Estabelecimento" value="<?php echo htmlclean( isset($_GET['estabelecimento']) ); ?>" completer_url="<?php just_url(); ?>/_core/_ajax/autocomplete_estabelecimentos.php" completer_field="estabelecimento_id"/>
							                  <input class="fakehidden" type="text" name="estabelecimento_id" value="<?php echo htmlclean( isset($_GET['estabelecimento_id']) ); ?>"/>
							              </div>
										</div>
										<div class="col-md-2">
							              <div class="form-field-default">
							               <label>Categoria:</label>
					                        <div class="fake-select">
					                          <i class="lni lni-chevron-down"></i>
					                          <select id="input-categoria" name="categoria">

							                      <option value=""></option>
							                      <?php 
							                      $quicksql = mysqli_query( $db_con, "SELECT * FROM categorias ORDER BY nome ASC LIMIT 10" );
							                      while( $quickdata = mysqli_fetch_array( $quicksql ) ) {
							                      ?>

							                      <option <?php if( isset($_POST['categoria']) == $quickdata['id'] ) { echo "SELECTED"; }; ?> value="<?php echo $quickdata['id']; ?>"><?php echo $quickdata['nome']; ?></option>

							                      <?php } ?>

					                          </select>
					                          <div class="clear"></div>
					                        </div>
							              </div>
										</div>
										<div class="col-md-2 col-xs-6 col-sm-6">
							              <div class="form-field-default">
							               <label>Visibilidade:</label>
											<div class="fake-select">
												<i class="lni lni-chevron-down"></i>
												<select name="visible">
													<option></option>
		                                            <?php for( $x = 0; $x < count( $numeric_data['visibilidade'] ); $x++ ) { ?>
		                                            <option value="<?php echo $numeric_data['visibilidade'][$x]['value']; ?>" <?php if( isset($_GET['visible']) == $numeric_data['visibilidade'][$x]['value'] ) { echo 'SELECTED'; }; ?>><?php echo $numeric_data['visibilidade'][$x]['name']; ?></option>
		                                            <?php } ?>
												</select>
												<div class="clear"></div>
											</div>
							              </div>
										</div>
										<div class="col-md-2 col-xs-6 col-sm-6">
							              <div class="form-field-default">
							               <label>Status:</label>
											<div class="fake-select">
												<i class="lni lni-chevron-down"></i>
												<select name="status">
													<option></option>
		                                            <?php for( $x = 0; $x < count( $numeric_data['status'] ); $x++ ) { ?>
		                                            <option value="<?php echo $numeric_data['status'][$x]['value']; ?>" <?php if( isset($_GET['status']) == $numeric_data['status'][$x]['value'] ) { echo 'SELECTED'; }; ?>><?php echo $numeric_data['status'][$x]['name']; ?></option>
		                                            <?php } ?>
												</select>
												<div class="clear"></div>
											</div>
							              </div>
										</div>
										<div class="col-md-2">
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
									<?php if( isset($_GET['filtered']) ) { ?>
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

						<a href="<?php admin_url(); ?>/produtos/adicionar">
							<span>Adicionar</span>
							<i class="lni lni-plus"></i>
						</a>

					</div>
				</div>
			</div>

			<div class="row">

				<div class="col-md-12">

					<table class="listing-table fake-table clean-table">
						<thead>
							<th></th>
							<th>Nome</th>
							<th>Categoria</th>
							<th>Estabelecimento</th>
							<th>Visibilidade</th>
							<th>Status</th>
							<th></th>
						</thead>
						<tbody>

							<?php
                            while ( $data = mysqli_fetch_array( $sql ) ) {
                            ?>

							<tr>
								<td>
                                    <div class="fake-table-data">
                                    	<div class="rounded-thumb-holder">
	                                    	<div class="rounded-thumb">
	                                    		<img src="<?php echo thumber( $data['destaque'], "200" ); ?>"/>
	                                    		<img class="blurred" src="<?php echo thumber( $data['destaque'], "300" ); ?>"/>
	                                    	</div>
                                    	</div>
                                    </div>
                                    <div class="fake-table-break"></div>
								</td>
								<td>
                                    <span class="fake-table-title hidden-xs hidden-sm">Nome</span>
                                    <div class="fake-table-data"><?php echo htmlclean( $data['nome'] ); ?></div>
                                    <div class="fake-table-break"></div>
								</td>
								<td class="hidden-xs hidden-sm">
                                    <span class="fake-table-title">Categoria</span>
                                    <div class="fake-table-data"><?php echo data_info( "categorias",$data['rel_categorias_id'], "nome" ); ?></div>
                                    <div class="fake-table-break"></div>
								</td>
								<td class="hidden-xs hidden-sm">
                                    <span class="fake-table-title">Estabelecimento</span>
                                    <div class="fake-table-data"><?php echo htmlclean( data_info("estabelecimentos",$data['rel_estabelecimentos_id'],"nome") ); ?></div>
                                    <div class="fake-table-break"></div>
								</td>
								<td class="hidden-xs hidden-sm">
                                    <span class="fake-table-title">Visiblidade</span>
                                    <div class="fake-table-data"><?php echo numeric_data( "visibilidade", $data['visible'] ); ?></div>
                                    <div class="fake-table-break"></div>
								</td>
								<td class="hidden-xs hidden-sm">
                                    <span class="fake-table-title">Status</span>
                                    <div class="fake-table-data"><?php echo numeric_data( "status", $data['status'] ); ?></div>
                                    <div class="fake-table-break"></div>
								</td>
								<td>
									<span class="fake-table-title hidden-xs hidden-sm">Ações</span>
                                    <div class="fake-table-data">
										<div class="form-actions pull-right">
											<a class="color-white" href="<?php admin_url(); ?>/produtos/copiar?id=<?php echo $data['id']; ?>" title="Adicionar"><i class="lni lni-files"></i></a>
											<a class="color-yellow" href="<?php admin_url(); ?>/produtos/editar?id=<?php echo $data['id']; ?>" title="Editar"><i class="lni lni-pencil"></i></a>
											<a class="color-red" onclick="if(confirm('Tem certeza que deseja remover este produto?')) document.location = '<?php admin_url(); ?>/produtos/deletar/?id=<?php echo $data['id']; ?>'" href="#" title="Excluir"><i class="lni lni-trash"></i></a>
										</div>
                                    </div>
                                    <div class="fake-table-break"></div>
								</td>
							</tr>

                            <?php } ?>

                            <?php if( $total_results == 0 ) { ?>

                               <tr>
                                <td colspan="6">
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
            $paginationpath = "produtos";
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