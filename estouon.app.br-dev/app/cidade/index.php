<?php
// CORE
include($virtualpath.'/_layout/define.php');
// APP
global $app;
global $seo_title;
// SEO
$seo_subtitle = $app['title']."-".$app['uf'];
$seo_description = $app['title'];
$seo_keywords = $app['title'].", ".$seo_title;
$seo_image = get_just_url()."/_core/_cdn/img/favicon.png";
// HEADER
$system_header .= "";
include($virtualpath.'/_layout/head.php');
include($virtualpath.'/_layout/top.php');
include($virtualpath.'/_layout/sidebars.php');
include($virtualpath.'/_layout/modal.php');
instantrender();
?>

<div class="sceneElement">

	<div class="middle minfit">

		<div class="container nopadd visible-xs visible-sm">

			<div class="breadcrumb-gray">

				<div class="row">

					<div class="col-md-12">
			
				 		<div class="search-bar-mobile visible-xs visible-sm">

							<form class="align-middle" action="<?php echo $app['url']; ?>/<?php echo $gopath; ?>" method="GET">

								<input type="text" name="busca" placeholder="Digite sua busca..." value="<?php echo htmlclean( $_GET['busca'] ); ?>"/>
								<input type="hidden" name="categoria" value="<?php echo $categoria; ?>"/>
								<button>
									<i class="lni lni-search-alt"></i>
								</button>
								<div class="clear"></div>

							</form>

						</div>

					</div>

				</div>

			</div>

		</div>

		<div class="back-gray hidden-xs hidden-sm">

			<div class="row rowtitle">

				<div class="col-md-12">
					<div class="title-icon">
						<span>Explore <?php echo $app['title']; ?>-<?php echo $app['uf']; ?></span>
					</div>
					<div class="bread-box">
						<div class="bread">
							<a href="<?php echo $app['url']; ?>"><i class="lni lni-home"></i></a>
							<span>/</span>
							<a href="<?php just_url(); ?>/">Cidades</a>
							<span>/</span>
							<a href="<?php echo $app['url']; ?>"><?php echo $app['title']; ?>/<?php echo $app['uf']; ?></a>
						</div>
					</div>
				</div>

				<div class="col-md-12 hidden-xs hidden-sm">
					<div class="clearline"></div>
				</div>

			</div>

		</div>

		<div class="border-bottom">

			<div class="container">

				<div class="row">

					<div class="col-md-12">
						
						<div class="tv-infinite tv-infinite-menu">
							<?php
							$aba = $_GET['aba'];
							if( !$aba ) {
								$aba = "produtos";
							}
							?>
							<div class="tv-infinite tv-infinite-menu tv-tabs">
								<a <?php if( $aba == "produtos" ) { echo 'class="active"'; }; ?> href="<?php echo $app['url']; ?>?aba=produtos"><i class="lni lni-shopping-basket colored"></i> Produtos</a>
								<a <?php if( $aba == "estabelecimentos" ) { echo 'class="active"'; }; ?> href="<?php echo $app['url']; ?>?aba=estabelecimentos"><i class="lni lni-home colored"></i> Estabelecimentos</a>
							</div>
						</div>

					</div>

				</div>

			</div>

		</div>

		<div class="container">

			<?php
			if( $aba == "produtos" OR $aba == "estabelecimentos" ) {
				include( "explore-".$aba.".php" );
			} else {
				include( "explore-produtos.php" );
			}
			?>

		</div>

	</div>

</div>

<?php 
// FOOTER
$system_footer .= "";
include($virtualpath.'/_layout/rdp.php');
include($virtualpath.'/_layout/footer.php');
?>