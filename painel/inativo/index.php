<?php
// CORE
include('../../_core/_includes/config.php');
// RESTRICT
restrict(2);
// SEO
$seo_subtitle = "Inativo";
$seo_description = "";
$seo_keywords = "";
// HEADER
$system_header .= "";
include('../_layout/head.php');
include('../_layout/top.php');
include('../_layout/sidebars.php');
include('../_layout/modal.php');

global $httprotocol;
global $simple_url;
$full_simple_url = $httprotocol.$simple_url;

?>

<div class="middle minfit bg-gray">

	<div class="container">

		<div class="row">

			<div class="col-md-12">

				<div class="title-icon pull-left">
					<i class="lni lni-warning"></i>
					<span>Inativo</span>
				</div>

				<div class="bread-box pull-right">
					<div class="bread">
						<a href="<?php panel_url(); ?>"><i class="lni lni-home"></i></a>
						<span>/</span>
						<a href="<?php panel_url(); ?>/inativo">Inativo</a>
					</div>
				</div>

			</div>

		</div>

		<!-- Content -->

		<div class="listing">

			<div class="row">

				<div class="col-md-12">

					<div class="inativo">

						<span>O seu estabelecimento está temporiamente inativo, entre em contato com nossa equipe para solucionar o problema.</span>
						
						<div class="add-new">

							<a href="https://conheca.<?php echo $full_simple_url; ?>/contato">
								<span>Entrar em contato</span>
								<i class="lni lni-headphone-alt"></i>
							</a>

						</div>

					</div>

				</div>

			</div>

		</div>

		<!-- / Content -->

	</div>

</div>

<?php 
// FOOTER
$system_footer .= "";
include('../_layout/rdp.php');
include('../_layout/footer.php');
?>