<?php
// CORE
include('../../_core/_includes/config.php');
// RESTRICT
restrict(2);
atualiza_estabelecimento( $_SESSION['estabelecimento']['id'], "online" );
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
$eid = $_SESSION['estabelecimento']['id'];
$meudominio = $httprotocol.data_info("estabelecimentos",$_SESSION['estabelecimento']['id'],"subdominio").".".$simple_url;

?>

<div class="middle minfit bg-gray">

	<div class="container">

		<div class="row">

			<div class="col-md-12">

				<div class="title-icon pull-left">
					<i class="lni lni-database"></i>
					<span>QRCode</span>
				</div>

				<div class="bread-box pull-right">
					<div class="bread">
						<a href="<?php panel_url(); ?>"><i class="lni lni-home"></i></a>
						<span>/</span>
						<a href="<?php panel_url(); ?>/qrcode">QRCode</a>
					</div>
				</div>

			</div>

		</div>

		<div class="integracao">

			<div class="data box-white mt-16">

	            <div class="row">

	              <div class="col-md-12">

	                <div class="title-line pd-0">
	                  <i class="lni lni-frame-expand"></i>
	                  <span>QRCode do estabelecimento</span>
	                  <div class="clear"></div>
	                </div>

	              </div>

	            </div>


	          <div class="row">

	            <div class="col-md-4" align="center">
 
	              <a href="https://api.qrserver.com/v1/create-qr-code/?data=<?php echo $meudominio; ?>&amp;size=400x400" target="_blank"><img src="https://api.qrserver.com/v1/create-qr-code/?data=<?php echo $meudominio; ?>&amp;size=200x200" alt="" title="" class="img-thumbnail"/></a>

	            </div>

	            <div class="col-md-8" align="justify">
	                <br/>
	            	 <span>Este é o QR code para seus clientes acessarem o seu sistema. Utilize-o em materiais, impressos de acordo com a sua necessidade!<br/><br/>Caso precise ampliar a imagem do QRCode basta clicar em cima.</span>
	              	 
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