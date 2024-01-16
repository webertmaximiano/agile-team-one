<div class="header">

	<div class="top">

		<div class="container">

			<div class="row align-middle hidden-sm hidden-xs">

				<div class="col-md-3">

					<div class="brand brand-image pull-left">
						<a href="<?php just_url(); ?>">
						<div class="logofont logofont-dark">
						   <span><?php echo $titulo_topo; ?></span>
						   </div>
						</a>
					</div>

				</div>

				<div class="col-md-6">

					<div class="search-bar">

						<div class="clear"></div>

						<!--<form class="align-middle" action="https://conheca.veloximports.com.br">

							<input type="text" name="busca" placeholder="Digite sua busca..." value="<?php echo htmlclean( $_GET['busca'] ); ?>"/>
							<button>
								<i class="lni lni-search-alt"></i>
							</button>
							<div class="clear"></div>

						</form>-->

					</div>

				</div>

				<div class="col-md-3">

					<div class="user-info pull-right">

						<div class="user-info-login">
							<a href="<?php just_url(); ?>/login" title="Faça login ou cadastre-se">
								<i class="lni lni-user"></i>
								<span>Faça login ou cadastre-se</span>
							</a>
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
						<a href="<?php echo $app['url']; ?>">
						<div class="logofont logofont-city brand-cidade">
							<span><?php echo $titulo_topo; ?></span>
							</div>
					    </a>

				    </div>

				<div class="col-md-3 col-sm-3 col-xs-3">

					<div class="user-info pull-right">

						<div class="user-info-login">
							<a href="#" class="sidrRight" href="#sidebarRight" title="Minha conta">
								<i class="lni lni-user"></i>
							</a>
						</div>

					</div>

				</div>

			</div>


		</div>

	</div>

	<div class="navigator naver hidden-sm hidden-xs">

		<div class="container">

			<div class="row">

				<div class="col-md-12">

					<?php include('navigation.php'); ?>

				</div>

			</div>

		</div>

	</div>

</div>