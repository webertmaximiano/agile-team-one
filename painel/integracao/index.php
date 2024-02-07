<?php
// CORE
include('../../_core/_includes/config.php');
// RESTRICT
restrict(2);
atualiza_estabelecimento( isset($_SESSION['estabelecimento']['id']), "online" );
// SEO
$seo_subtitle = "Integração";
$seo_description = "";
$seo_keywords = "";
// HEADER
$system_header .= "";
include('../_layout/head.php');
include('../_layout/top.php');
include('../_layout/sidebars.php');
include('../_layout/modal.php');

global $db_con;
$eid = isset($_SESSION['estabelecimento']['id']); //estabelecimento logado
$meudominio = $httprotocol.data_info("estabelecimentos",$_SESSION['estabelecimento']['id'],"subdominio").".".$simple_url;

//pega os dados do estabelecimento que sera atualizado 
$queryestabelecimento = mysqli_query( $db_con, "SELECT * FROM estabelecimentos WHERE id = '$eid' LIMIT 1");
$hasdataestabelecimento = mysqli_num_rows( $queryestabelecimento );
$dataestabelecimento = mysqli_fetch_array( $queryestabelecimento );
var_dump($dataestabelecimento );

//cria a funcao para atualizar a tabela estabelecimento
function update_estabelecimento( $db_con, $public_key, $secret_key, $id)
{
	// Sanitizar os dados
    $public_key = mysqli_real_escape_string($db_con, $public_key);
    $secret_key = mysqli_real_escape_string($db_con, $secret_key);

    // Preparar a consulta SQL
    $sql = "UPDATE estabelecimentos SET public_key = ?, secret_key = ? WHERE id = ?";

    // Criar um statement
    $stmt = mysqli_prepare($db_con, $sql);

    // Vincular os parâmetros
    mysqli_stmt_bind_param($stmt, "sss", $public_key, $secret_key, $id);

    // Executar a consulta
    mysqli_stmt_execute($stmt);

    // Fechar o statement
    mysqli_stmt_close($stmt);

    // Retornar o número de linhas afetadas
    return mysqli_affected_rows($db_con);

}

// Checar se formulário foi executado
$formdata = $_POST['formdata'];
//se o botao salvar for clicado vai executar o post do form
if ($formdata) {
	// Checar Erros gerados se nao enviar os dados
	 $checkerrors = 0;
	 $errormessage = []; //declara um array

	 if( !$public_key ) {
		$checkerrors++;
		$errormessage[] = "A Public Key não pode ser nulo";
	  }

	// verificar se a secret_key foi informada
	  if( !$secret_key ) {
		$checkerrors++;
		$errormessage[] = "A Secret Key não pode ser nula";
	  }

	  // Executar registro

	  if( !$checkerrors ) {
		//tem como atualizar sem passar todas as colunas?
		if( update_estabelecimento( $db_con, $id, $public_key, $secret_key ) ) {
  
		  header("Location: index.php?msg=sucesso&id=".$id);
  
		} else {
  
		  header("Location: index.php?msg=erro&id=".$id);
  
		}
  
	  }

}
var_dump($public_key);
var_dump($secret_key);
?>

<div class="middle minfit bg-gray">

	<div class="container">

		<div class="row">

			<div class="col-md-12">

				<div class="title-icon pull-left">
					<i class="lni lni-database"></i>
					<span>Integração</span>
				</div>

				<div class="bread-box pull-right">
					<div class="bread">
						<a href="<?php panel_url(); ?>"><i class="lni lni-home"></i></a>
						<span>/</span>
						<a href="<?php panel_url(); ?>/integracao">Integração</a>
					</div>
				</div>

			</div>

		</div>

		<div class="integracao">

			<div class="data box-white mt-16">

	            <div class="row">

	              <div class="col-md-12">

	                <div class="title-line pd-0">
	                  <i class="lni lni-instagram"></i>
	                  <span>Facebook / Instagram Shopping</span>
	                  <div class="clear"></div>
	                </div>

	              </div>

	            </div>
					
				<!-- Sacolinha Instagram -->
				<div class="row">

					<div class="col-md-9">

					<div class="form-field-default">

						<label>URL de importação:</label>
						<input id="copyme" type="text" value="<?php echo isset($meudominio); ?>/shopping.xml" DISABLED/>

					</div>

					</div>

					<div class="col-md-3">
						<label></label>
						<button class="fullwidth" data-clipboard-text="<?php echo isset($meudominio); ?>/shopping.xml">
							<span>
								<i class="lni lni-clipboard"></i> Copiar
							</span>
						</button>
					</div>

				</div>
			<!--
				<div class="row">

					<div class="col-md-9">

					<div class="form-field-default">

						<label>Tutorial (Passo a passo):</label>
						<span class="form-tip">Assista o vídeo para aprender como importar os seus produtos automaticamente par ao seu facebook / instagram shopping.</span>
					</div>

					</div>

					<div class="col-md-3">
						<label></label>
						<button class="fullwidth" >
							<span>
								<i class="lni lni-clipboard"></i> Assistir
							</span>
						</button>
					</div>

				</div>
			-->
			</div>

		</div>

	</div>

	<div class="container">

		

		<div class="integracao">

			<div class="data box-white mt-16">

	            <div class="row">

	              <div class="col-md-12">

	                <div class="title-line pd-0">
						<i class="lni lni-credit-cards"></i>
	                  <span>Mercado Pago</span>
	                  <div class="clear"></div>
	                </div>

	              </div>

	            </div>
					
				<!-- Mercado Pago -->
				<div class="row">

					<form id="the_form" class="form-default" method="POST" enctype="multipart/form-data">

						<div class="col-md-9">
							
							<div class="form-field-default">

								<label>Sua Public Key:</label>
								<input type="text" id="input-public-key" name="input-public-key" value="<?php echo htmlclean( $_POST['input-public-key'] ); ?>">

								<label>Sua Secret Key:</label>
								<input type="text" id="input-secret-key" name="input-secret-key" value="<?php echo htmlclean( $_POST['input-secret-key'] ); ?>">

							</div>

							</div>

							<div class="col-md-3">
							<input type="hidden" name="formdata" value="true"/>
							<div class="form-default form-field-submit">
								<button class="pull-right">
									<span>Salvar <i class="lni lni-chevron-right"></i></span>
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

<?php 
// FOOTER
$system_footer .= "";
include('../_layout/rdp.php');
include('../_layout/footer.php');
?>