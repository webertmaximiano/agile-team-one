<?php
// CORE
include('../../_core/_includes/config.php');
// RESTRICT
restrict(2);
atualiza_estabelecimento( $_SESSION['estabelecimento']['id'], "online" );
// SEO
$seo_subtitle = "Impressão";
$seo_description = "";
$seo_keywords = "";
// HEADER
$system_header .= "";
include('../_layout/head.php');
include('../_layout/top.php');
include('../_layout/sidebars.php');
include('../_layout/modal.php');

global $db_con;
$eid = $_SESSION['estabelecimento']['id'];
$meudominio = "https://".$simple_url."/api/?token=";

$sql = mysqli_query( $db_con, "SELECT * FROM impressao WHERE ide = '$eid'" );
$total_results = mysqli_num_rows( $sql );

if($total_results <=0 ){

for ($i = 0; $i < 20; $i++)
{ $token = uniqid(rand(), false); }

$token = "MDT".$eid.$token;

$sql = mysqli_query( $db_con, "INSERT INTO impressao (ide,status,token) VALUES ('$eid','2','$token');" );

$data = array(
    "Url" => $meudominio,
    "Token" => $token
);

$arquivo = 'login.json';
$json = json_encode($data);
$file = fopen($arquivo,'w');
fwrite($file, $json);
fclose($file);

header("Location: index.php?msg=sucesso");

} else { 

$datatoken = mysqli_fetch_array( $sql );

}

?>

<div class="middle minfit bg-gray">

	<div class="container">

		<div class="row">

			<div class="col-md-12">

				<div class="title-icon pull-left">
					<i class="lni lni-database"></i>
					<span>Impressão Automática</span>
				</div>

				<div class="bread-box pull-right">
					<div class="bread">
						<a href="<?php panel_url(); ?>"><i class="lni lni-home"></i></a>
						<span>/</span>
						<a href="<?php panel_url(); ?>/impressao">Impressão Automática</a>
					</div>
				</div>

			</div>

		</div>

		<div class="integracao">

			<div class="data box-white mt-16">

	            <div class="row">

	              <div class="col-md-12">

	                <div class="title-line pd-0">
	                  <i class="lni lni-printer"></i>
	                  <span>Impressão Automática</span>
	                  <div class="clear"></div>
	                </div>

	              </div>

	            </div>

 	            <div class="row">

	              <div class="col-md-12">

		              <div class="form-field-default">

		                  <label>Tutorial (Passo a passo):</label>
		                  
						  1 - Solicite o arquivo de instalação do sistema com o administrador.<br/>
						  
						  2 - Após instalar o sistema clique no botão abaixo para baixar o arquivo de liberação e coloque dentro da pasta do sistema em:<br/>
						  <br/>
						  <b>C:\Program Files (x86)\impressao</b> ou <b>C:\Program Files\impressao</b><br/>
						  <br/>
						  3 - Após isso execute o sistema como administrador.
		                

		              </div>

	              </div>

	            </div> 
<div class="row">
 
	            <div class="col-md-3">
	            	<label></label>
					<a href="login.json" download>
	              	 <button>
	              		<span>
	              			<i class="lni lni-clipboard"></i> Baixar Arquivo
	              		</span>
						</button>
	              	 </a>
	              </div>

	          </div>
			
			<div class="row">
 
	            <div class="col-md-3">
	            	<label></label>
					<a href="https://wa.me/5511981760591" target="_blank">
	              	 <button>
	              		<span>
	              			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
  <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
</svg> Chamar o suporte
	              		</span>
						</button>
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