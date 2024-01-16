<?php
// CORE
include($virtualpath.'/_layout/define.php');
// APP
global $app;
$status_force = data_info( "estabelecimentos", $app['id'], "status_force" );
$status = data_info( "estabelecimentos", $app['id'], "status" );
if( $status_force != "1" && $status == "1" ) {
	header("Location: ".$app['url']);
}
$back_button = "true";
// Querys
$exibir = "8";
$app_id = $app['id'];
// SEO
$seo_subtitle = "Desativado";
$seo_description = "Desativado";
$seo_keywords = $app['title'].", ".$seo_title;
$seo_image = thumber( $app['avatar_clean'], 400 );
// HEADER
$system_header .= "";
include($virtualpath.'/_layout/head.php');
include($virtualpath.'/_layout/top.php');
include($virtualpath.'/_layout/sidebars.php');
include($virtualpath.'/_layout/modal.php');
instantrender();
?>

<div class="sceneElement">

	<div class="header-interna">

		<div class="locked-bar visible-xs visible-sm">

			<div class="avatar">
				<div class="holder">
					<a href="<?php echo $app['url']; ?>">
						<img src="<?php echo $app['avatar']; ?>"/>
					</a>
				</div>	
			</div>

		</div>

		<div class="holder-interna holder-interna-nopadd holder-interna-sacola visible-xs visible-sm"></div>

	</div>

	<div class="minfit">

		<div class="middle">

			<div class="container nopaddmobile paginaerro">

				<div class="row rowtitle">

					<div class="col-md-12">
						<div class="title-icon">
							<span>Desativado</span>
						</div>
						<div class="bread-box">
							<div class="bread">
								<a href="<?php echo $app['url']; ?>"><i class="lni lni-home"></i></a>
								<span>/</span>
								<a href="<?php echo $app['url']; ?>/desativado">Desativado</a>
							</div>
						</div>
					</div>

					<div class="col-md-12 hidden-xs hidden-sm">
						<div class="clearline"></div>
					</div>

				</div>

				<div class="obrigado">

					<div class="row">

						<div class="col-md-12">

							<div class="adicionado">

								<i class="checkicon lni lni-cross-circle"></i>
								<span class="text">O estabelecimento que você acessou encontra-se temporariamente desativado!</span>
								<a target="_blank" href="https://wa.me/55<?php echo clean_str( htmlclean( $app['contato_whatsapp'] ) ); ?>" class="botao-acao"><i class="lni lni-whatsapp"></i> <span>Entre em contato</span></a>

							</div>

						</div>

					</div>

				</div>

			</div>

		</div>

	</div>

</div>

<?php 
// FOOTER
$system_footer .= "";
include($virtualpath.'/_layout/rdp.php');
include($virtualpath.'/_layout/footer.php');
?>