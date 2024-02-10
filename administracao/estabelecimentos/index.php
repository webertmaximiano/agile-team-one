<?php
// CORE
include('../../_core/_includes/config.php');
// RESTRICT
restrict('1');
// SEO
$seo_subtitle = "Estabelecimentos";
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
global $simple_url;
global $httprotocol;

// Variables

$subdominio = mysqli_real_escape_string($db_con, isset($_GET['subdominio']));
$nome = mysqli_real_escape_string($db_con, isset($_GET['nome']));
$estado = mysqli_real_escape_string($db_con, isset($_GET['estado']));
$cidade = mysqli_real_escape_string($db_con, isset($_GET['cidade']));
$excluded = mysqli_real_escape_string($db_con, isset($_GET['excluded']));

$getdata = "";

foreach($_GET as $query_string_variable => $value) {
    if($query_string_variable != "pagina") {
        $getdata .= "&$query_string_variable=" . htmlclean($value);
    }
}

// Config

$limite = 20;
$pagina = isset($_GET["pagina"]) == "" ? 1 : isset($_GET["pagina"]);
$inicio = ($pagina * $limite) - $limite;

// Query
$query = '';
$query .= "SELECT e.id, e.nome, e.perfil, c.nome AS cidade, es.nome AS estado, e.status, e.excluded, e.subdominio ";
$query .= "FROM estabelecimentos AS e ";
$query .= "JOIN cidades AS c ON e.cidade = c.id ";
$query .= "JOIN estados AS es ON e.estado = es.id ";
$query .= "WHERE 1=1 ";

if($oper != 1) {
    $query .= "AND cidade = $cidop ";
} else {
    $query .= "AND 1=1 ";
}


if($subdominio) {
    $query .= "AND subdominio LIKE '$nome%' ";
}

if($nome) {
    $query .= "AND nome LIKE '$nome%' ";
}

if($estado) {
    $query .= "AND level = '$estado' ";
}

if($cidade) {
    $query .= "AND cidade = '$cidade' ";
}

if($excluded) {
    $query .= "AND excluded = '$excluded' ";
} else {
    $query .= "AND excluded != '1' ";
}


$query_full = $query;

if($oper == 1) {
    $query .= "ORDER BY created DESC LIMIT $inicio,$limite";
} else {
    $query .= "ORDER BY created DESC LIMIT $inicio,$limite";
}

// Run
//var_dump($query);
$sql = mysqli_query($db_con, $query);

$total_results = mysqli_num_rows($sql);

$sql_full = mysqli_query($db_con, $query_full);

$total_results_full = mysqli_num_rows($sql_full);

$total_paginas = Ceil($total_results_full / $limite) + ($limite / $limite);

if(!$pagina or $pagina > $total_paginas or !is_numeric($pagina)) {

    $pagina = 1;

}


?>

<?php if(isset($_GET['msg']) == "erro") { ?>

<?php modal_alerta("Erro, tente novamente!", "erro"); ?>

<?php } ?>

<?php if(isset($_GET['msg']) == "sucesso") { ?>

<?php modal_alerta("Ação efetuada com sucesso!", "sucesso"); ?>

<?php } ?>

<div class="middle minfit bg-gray">

	<div class="container">

		<div class="row">

			<div class="col-md-12">

				<div class="title-icon pull-left">
					<i class="lni lni-home"></i>
					<span>Estabelecimentos</span>
				</div>

				<div class="bread-box pull-right">
					<div class="bread">
						<a href="<?php admin_url(); ?>"><i class="lni lni-home"></i></a>
						<span>/</span>
						<a href="<?php admin_url(); ?>/estabelecimentos">Estabelecimentos</a>
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
						<div id="collapse-filtros" class="panel-collapse collapse <?php if(isset($_GET['filtered'])) {
						    echo 'in';
						}; ?>">
							<div class="panel-body">

								<form class="form-filters form-100" method="GET">

									<div class="row">
										<div class="col-md-6">
											<div class="form-field-default">
												<label>Subdominio:</label>
												<input type="text" name="subdominio" placeholder="Subdominio" value="<?php echo htmlclean($subdominio); ?>"/>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-field-default">
												<label>Nome:</label>
												<input type="text" name="nome" placeholder="Nome" value="<?php echo htmlclean($nome); ?>"/>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3">
											<div class="form-field-default">
												<label>Estado:</label>
												<div class="fake-select">
													<i class="lni lni-chevron-down"></i>
													<select id="input-estado" name="estado">

													    <option value="">Estado</option>
													    <?php
                                                        $quicksql = mysqli_query($db_con, "SELECT * FROM estados ORDER BY nome ASC LIMIT 999");
														while($quickdata = mysqli_fetch_array($quicksql)) {
															?>

													      <option <?php if(isset($_POST['estado']) == $quickdata['id']) {
													          echo "SELECTED";
													      }; ?> value="<?php echo $quickdata['id']; ?>"><?php echo $quickdata['nome']; ?></option>

													    <?php } ?>

													</select>
													<div class="clear"></div>
												</div>
											</div>
										</div>
										<div class="col-md-3">
						                    <div class="form-field-default">
						                        <label>Cidade:</label>
						                        <div class="fake-select">
						                          <i class="lni lni-chevron-down"></i>
						                          <select id="input-cidade" name="cidade">
						                            <option value="">Cidade</option>
						                          </select>
						                          <div class="clear"></div>
						                      </div>
						                    </div>
										</div>
										<div class="col-md-3">
						                    <div class="form-field-default">
						                        <label>Excluido:</label>
						                        <div class="fake-select">
						                          <i class="lni lni-chevron-down"></i>
						                          <select name="excluded">
						                            <option value=""></option>
						                            <option <?php if($excluded == "1") {
						                                echo "SELECTED";
						                            }; ?> value="1">Sim</option>
						                            <option <?php if($excluded == "") {
						                                echo "SELECTED";
						                            }; ?> value="">Não</option>
						                          </select>
						                          <div class="clear"></div>
						                      </div>
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
									<?php if(isset($_GET['filtered'])) { ?>
									<div class="row">
										<div class="col-md-12">
										    <a href="<?php admin_url(); ?>/estabelecimentos" class="limpafiltros"><i class="lni lni-close"></i> Limpar filtros</a>
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

						<a href="<?php admin_url(); ?>/estabelecimentos/adicionar">
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
							<th class="hidden-xs hidden-sm">Subdominio</th>
							<th>Nome</th>
							<th class="hidden-xs hidden-sm">Cidade</th>
							<th class="hidden-xs hidden-sm">Estado</th>
							<th></th>
						</thead>
						<tbody>

							<?php
                            while ($data = mysqli_fetch_array($sql)) {
                                $gourl = $httprotocol . $data['subdominio'] . "." . $simple_url;
                                $gourlclean = $data['subdominio'] . "." . $simple_url;
                                ?>

							<tr>
								<td>
                                    <div class="fake-table-data">
                                    	<a target="_blank" href="<?php echo $gourl; ?>">
	                                    	<div class="rounded-thumb-holder">
		                                    	<div class="rounded-thumb">
		                                    		<img src="<?php echo thumber($data['perfil'], "100"); ?>"/>
		                                    		<img class="blurred" src="<?php echo thumber($data['perfil'], "300"); ?>"/>
		                                    	</div>
	                                    	</div>
                                    	</a>
                                    </div>
                                    <div class="fake-table-break"></div>
								</td>
								<td class="hidden-xs hidden-sm">
									<a target="_blank" href="<?php echo $gourl; ?>">
	                                    <span class="fake-table-title">Subdominio</span>
	                                    <div class="fake-table-data"><?php echo $data['subdominio']; ?></div>
	                                    <div class="fake-table-break"></div>
                                	</a>
								</td>
								<td>
									<a target="_blank" href="<?php echo $gourl; ?>">
	                                    <span class="fake-table-title hidden-xs hidden-sm">Nome</span>
	                                    <div class="fake-table-data"><?php echo htmlclean($data['nome']); ?></div>
	                                    <div class="fake-table-break"></div>
                                	</a>
								</td>
								<td class="hidden-xs hidden-sm">
									<span class="fake-table-title hidden-xs hidden-sm">Cidade</span>
									<div class="fake-table-data"><?php echo $data['cidade']; ?></div>
									<div class="fake-table-break"></div>
								</td>
								<td class="hidden-xs hidden-sm">
									<span class="fake-table-title hidden-xs hidden-sm">Estado</span>
									<div class="fake-table-data"><?php echo $data['estado']; ?></div>
									<div class="fake-table-break"></div>
								</td>
								<td>
									<span class="fake-table-title">Ações</span>
                                    <div class="fake-table-data">
										<div class="form-actions pull-right">
											<a target="_blank" class="color-white" href="<?php admin_url(); ?>/estabelecimentos/gerenciar?id=<?php echo $data['id']; ?>" title="Gerenciar"><i class="lni lni-lock"></i></a>
											<a class="color-yellow" href="<?php admin_url(); ?>/assinaturas?estabelecimento_id=<?php echo $data['id']; ?>&filtered=1" title="Assinaturas"><i class="lni lni-star"></i></a>
											<a class="color-red" onclick="if(confirm('Tem certeza que deseja remover este estabelecimento?')) document.location = '<?php admin_url(); ?>/estabelecimentos/deletar/?id=<?php echo isset($data['id']); ?>&mode=<?php echo isset($_GET['mode']); ?>'" href="#" title="Ed"><i class="lni lni-trash"></i></a>
										</div>
                                    </div>
                                    <div class="fake-table-break"></div>
								</td>
							</tr>

                            <?php } ?>

                            <?php if($total_results == 0) { ?>

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
            $paginationpath = "estabelecimentos";
if($pagina > 1) {
    $back = $pagina - 1;
    echo '<li class="page-item pagination-back"><a class="page-link" href=" ' . get_system_url() . '/' . $paginationpath . '/?pagina=' . $back . $getdata . ' "><i class="lni lni-chevron-left"></i></a></li>';
}

for($i = $pagina - 1; $i <= $pagina - 1; $i++) {

    if($i > 0) {

        echo '<li class="page-item pages-before"><a class="page-link" href=" ' . get_system_url() . '/' . $paginationpath . '/?pagina=' . $i . $getdata . ' ">' . $i . '</a></li>';
    }

}

if($pagina >= 1) {

    echo '<li class="page-item active"><a class="page-link" href=" ' . get_system_url() . '/' . $paginationpath . '/?pagina=' . $i . $getdata . '" class="page-link">' . $i . '</a></li>';

}

for($i = $pagina + 1; $i <= $pagina + 1; $i++) {

    if($i >= $total_paginas) {
        break;
    } else {
        echo '<li class="page-item pages-after"><a class="page-link" href=" ' . get_system_url() . '/' . $paginationpath . '/?pagina=' . $i . $getdata . ' ">' . $i . '</a></li> ';
    }

}

if($pagina < $total_paginas - 1) {
    $next = $pagina + 1;
    echo '<li class="page-item pagination-next"><a class="page-link" href=" ' . get_system_url() . '/' . $paginationpath . '/?pagina=' . $next . $getdata . ' "><i class="lni lni-chevron-right"></i></a></li>';
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

  function exibe_cidades() {
    var estado = $("#input-estado").children("option:selected").val();
    $("#input-cidade").html("<option>-- Carregando cidades --</option>");
    $("#input-cidade").load("<?php just_url(); ?>/_core/_ajax/cidades.php?estado="+estado);
  }

  // Autopreenchimento de estado
  $( "#input-estado" ).change(function() {
    exibe_cidades();
  });
  <?php if(isset($_POST['estado'])) { ?>
    exibe_cidades();
  <?php } ?>

</script>