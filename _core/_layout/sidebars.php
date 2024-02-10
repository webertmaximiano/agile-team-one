<div class="sidebars">

	<div id="sidebarLeft">

		<div class="sidebar">

			<div class="sidebar-header">
				<i class="close-sidebar lni lni-close" onclick="$.sidr('close', 'sidrLeft');"></i>
				<div class="clear"></div>
			</div>

			<div class="sidebar-content">

				<div class="search-bar">

					<div class="clear"></div>

					<form class="align-middle">

						<input type="text" name="busca" placeholder="Digite sua busca..." value="<?php echo htmlclean( $_GET['busca'] ); ?>"/>
						<button>
							<i class="lni lni-search-alt"></i>
						</button>
						<div class="clear"></div>

					</form>

				</div>

				<div class="naver navbar-mobile">

					<?php include('navigation.php'); ?>

				</div>

			</div>

		</div>

	</div>

	<div id="sidebarRight">

		<div class="sidebar">

			<div class="sidebar-header">
				<i class="close-sidebar lni lni-close" onclick="$.sidr('close', 'sidrRight');"></i>
				<div class="clear"></div>
			</div>

			<div class="sidebar-content">

				<div class="naver navbar-mobile">

					<?php include('navigation-user.php'); ?>

				</div>

			</div>

		</div>

	</div>

</div>