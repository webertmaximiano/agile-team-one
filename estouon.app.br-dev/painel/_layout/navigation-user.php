<div class="user-badge">

	<div class="avatar-thumb-holder">
		<div class="avatar-thumb">
			<img src="<?php echo thumber( $_SESSION['estabelecimento']['perfil'], 300 ); ?>"/>
		</div>
	</div>

	<div class="info">
		<span><i class="lni lni-user"></i> <?php echo user_info('email'); ?></span>
	</div>

</div>

<nav class="navbar">
	<ul class="nav navbar-nav">
		<li><a href="<?php panel_url(); ?>/configuracoes"><i class="lni lni-cog icon-left"></i> Configurações</a></li>
		<li><a href="<?php just_url(); ?>/logout"><i class="lni lni-power-switch icon-left"></i> Sair</a></li>
	</ul>
</nav> 

<div class="clear"></div>