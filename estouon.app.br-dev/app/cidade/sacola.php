<?php
// CORE
include($virtualpath.'/_layout/define.php');
// APP
global $app;
$back_button = "true";
// Querys
$exibir = "8";
$app_id = $app['id'];
$has_content = count( $_SESSION['sacola'][$app_id] );
// SEO
$seo_subtitle = $app['title']." - Minha sacola";
$seo_description = "Minha sacola de compras ".$app['title']." no ".$seo_title;
$seo_keywords = $app['title'].", ".$seo_title;
$seo_image = thumber( $app['avatar'], 400 );
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

			<div class="back-gray">

				<div class="container">

					<div class="row rowtitle hidden-xs hidden-sm">

						<div class="col-md-12">
							<div class="title-icon">
								<span>Minhas sacolas</span>
							</div>
							<div class="bread-box">
								<div class="bread">
									<a href="<?php echo $app['url']; ?>"><i class="lni lni-home"></i></a>
									<span>/</span>
									<a href="<?php echo $app['url']; ?>/<?php echo $gopath; ?>/<?php echo $inparametro; ?><?php echo $getdata; ?>">Categorias</a>
									<span>/</span>
									<a href="<?php echo $app['url']; ?>/<?php echo $gopath; ?>/<?php echo $inparametro; ?><?php echo $getdata; ?>"><?php echo $categoria_name; ?></a>
								</div>
							</div>
						</div>

						<div class="col-md-12 hidden-xs hidden-sm">
							<div class="clearline"></div>
						</div>

					</div>

				</div>

			</div>	

			<div class="container visible-sm visible-xs">

				<div class="row rowtitle">

					<div class="col-md-12">
						<div class="title-icon">
							<span>Minhas sacolas</span>
						</div>
						<div class="bread-box">
							<div class="bread">
								<a href="<?php echo $app['url']; ?>"><i class="lni lni-home"></i></a>
								<span>/</span>
								<a href="<?php echo $app['url']; ?>/<?php echo $gopath; ?>/<?php echo $inparametro; ?><?php echo $getdata; ?>">Categorias</a>
								<span>/</span>
								<a href="<?php echo $app['url']; ?>/<?php echo $gopath; ?>/<?php echo $inparametro; ?><?php echo $getdata; ?>"><?php echo $categoria_name; ?></a>
							</div>
						</div>
					</div>

				</div>

			</div>

			<div class="lista-sacolas">

				<div class="container">

					<div class="row">

						<div class="col-md-4">

							<div class="sacola">

								<div class="titulo">

									<div class="row">

										<div class="col-md-4">

										</div>

										<div class="col-md-8">

										</div>

									</div>

								</div>

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