<div class="header">

	<div class="top">

		<div class="container">

			<div class="row align-middle hidden-sm hidden-xs">

				<div class="col-md-3">

					<div class="brand pull-left">
						<a href="<?php admin_url(); ?>">
							<img src="<?php just_url(); ?>/_core/_cdn/img/logo.png"/>
						</a>
					</div>

				</div>

				 

				<div class="col-md-9">

					<div class="user-info pull-right">

						<div class="user-info-login">
							<a href="<?php admin_url(); ?>/configuracoes" title="Minha conta">
								<i class="lni lni-user"></i>
								<span><?php echo user_info('email'); ?></span>
							</a>
						</div>
						<div class="user-info-config">
							<a href="<?php echo admin_url(); ?>/configuracoes" title="Configurações">
								<i class="lni lni-cog"></i>
							</a>
						</div>
						<div class="user-info-logout">
							<a href="<?php echo just_url(); ?>/logout" title="Sair">
								<i class="lni lni-power-switch"></i>
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

					<div class="brand">
						<a href="<?php admin_url(); ?>">
							<img src="<?php just_url(); ?>/_core/_cdn/img/logowhite.png"/>
						</a>
					</div>

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