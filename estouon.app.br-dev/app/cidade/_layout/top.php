<?php
global $back_button;
global $categoria;
global $app;
global $simple_url;
global $httprotocol;
$justurl = $httprotocol.$simple_url;
if( $inacao && ($inacao == "estabelecimentos" OR $inacao == "produtos" ) ) {
	$gopath = $inacao;
} else {
	$gopath = "produtos";
}

?>

<div class="header">

	<div class="minitop hidden-xs hidden-sm">

		<div class="container">

			<div class="row">

				<div class="col-md-6">

					<div class="seletor-cidade" onclick="escolhe_cidade();">
						<i class="lni lni-map-marker"></i>
						<span><?php echo $app['title']; ?> - <?php echo ucfirst( $app['uf'] ); ?></span>
						<i class="lni lni-chevron-down"></i>
						<div class="clear"></div>
					</div>

				</div>

				<div class="col-md-6">

					<div class="mini-links pull-right">
						<a target="_blank" href="https://conheca.<?php echo $simple_url; ?>"><i class="lni lni-heart"></i> Conheça</a>
						<a target="_blank" href="https://wa.me?text=<?php just_url(); ?> - Compre sem sair de casa!"><i class="lni lni-bullhorn"></i> Indique</a>
						<a target="_blank" href="https://api.whatsapp.com/send?phone=55<?php echo $whatsapp; ?>&text=Como%20posso%20te%20ajudar%20%3B%20"><i class="lni lni-headphone-alt"></i> Fale conosco</a>
						<a target="_blank" href="<?php echo $justurl; ?>/login?redirect=<?php echo $app['url']; ?>/login"><i class="lni lni-lock"></i> Painel</a>
					</div>

				</div>

			</div>

		</div>

	</div>

	<div class="top">

		<div class="container">

			<div class="row align-middle hidden-sm hidden-xs">

				<div class="col-md-3">

					<div class="brand-cidade">
						<a href="<?php echo $app['url']; ?>">
							<img src="<?php echo $app['url']; ?>/_core/_cdn/img/logo.png"/>
						</a>
					</div>

				</div>

				<div class="col-md-9">

					<div class="row">

						<div class="col-md-9">

							<div class="search-bar">

								<div class="clear"></div>

								<form class="align-middle" method="GET" action="<?php echo $app['url']; ?>/<?php echo $gopath; ?>">

									<input type="text" name="busca" placeholder="Digite sua busca..." value="<?php echo htmlclean( $_GET['busca'] ); ?>"/>
									<input type="hidden" name="categoria" value="<?php echo $app['categoria']; ?>"/>
									<button>
										<i class="lni lni-search-alt"></i>
									</button>
									<div class="clear"></div>

								</form>

							</div>

						</div>

						<div class="col-md-3">

							<a class="mini-vender-bt pull-right" href="<?php echo $justurl; ?>/comece">Quero vender <i class="lni lni-rocket"></i></a>

						</div>

					</div>

				</div>

			</div>

			<div class="row align-middle-mobile visible-sm visible-xs">

				<div class="col-md-3 col-sm-3 col-xs-3">

					<div class="user-menu pull-left">
						<a href="#" class="sidrLeft" href="#sidebarLeft" title="Menu">
							<i class="lni lni-menu"></i>
						</a>
					</div>

				</div>

				<div class="col-md-6 col-sm-6 col-xs-6 nopadd">

					<div class="brand-cidade">
						<a href="<?php echo $app['url']; ?>">
							<img src="<?php echo $app['url']; ?>/_core/_cdn/img/logowhite.png"/>
						</a>
					</div>

				</div>

				<div class="col-md-3 col-sm-3 col-xs-3">

					<div class="user-menu pull-right">
						<a href="#" title="Escolha sua cidade" onclick="escolhe_cidade()">
							<i class="lni lni-map-marker"></i>
						</a>
					</div>

				</div>

			</div>


		</div>

	</div>

<!-- 	<div class="seletor-cidade seletor-cidade-mobile visible-xs visible-sm" onclick="escolhe_cidade()">
		<div class="holder">
			<span>
				<i class="lni lni-map-marker"></i>
				<?php echo $app['cidade_message']; ?> <strong><?php echo $app['title']; ?> - <?php echo $app['uf']; ?></strong>
				<i class="lni lni-chevron-down"></i>
			</span>
			<div class="clear"></div>
		</div>
	</div> -->

	<div class="espacer-cidade visible-xs visible-sm"></div>

	<div class="segmentos">

		<div class="container">

			<div class="row">

				<div class="col-md-12">
					
					<div class="tv-infinite tv-infinite-menu">
						<?php include('segmentos.php'); ?>
					</div>

				</div>

			</div>

		</div>

	</div>

</div>