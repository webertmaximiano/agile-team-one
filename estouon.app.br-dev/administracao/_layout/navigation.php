<?php
$oper = user_info('operacao');
$cidop = user_info('cidade');
$estop = user_info('estado');
?>
<nav class="navbar pull-left">
	<ul class="nav navbar-nav">
		<li class="active"><a href="#">Ínicio</a></li>
		<?php if($oper == 1) { ?>
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
				Usuários
				<i class="lni lni-chevron-down icon-right"></i>
			</a>
			<ul class="dropdown-menu">
				<li><a href="<?php admin_url(); ?>/usuarios/adicionar"><i class="lni lni-circle-plus"></i> Adicionar</a></li>
				<li><a href="<?php admin_url(); ?>/usuarios"><i class="lni lni-radio-button"></i> Gerenciar</a></li>
			</ul>
		</li>
		<?php } ?>
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
				Segmentação
				<i class="lni lni-chevron-down icon-right"></i>
			</a>
			<ul class="dropdown-menu">
				<?php if($oper == 1) { ?>
				<li><a href="<?php admin_url(); ?>/cidades"><i class="lni lni-radio-button"></i> Cidades</a></li>
				<li><a href="<?php admin_url(); ?>/estados"><i class="lni lni-radio-button"></i> Estados</a></li>
				<?php } ?>
				<li><a href="<?php admin_url(); ?>/segmentos"><i class="lni lni-radio-button"></i> Segmentos</a></li>
			</ul>
		</li>
		<?php if($oper == 1) { ?>
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
				Subdomínios
				<i class="lni lni-chevron-down icon-right"></i>
			</a>
			<ul class="dropdown-menu">
				<li><a href="<?php admin_url(); ?>/subdominios/adicionar"><i class="lni lni-circle-plus"></i> Adicionar novo</a></li>
				<li><a href="<?php admin_url(); ?>/subdominios"><i class="lni lni-radio-button"></i> Gerenciar</a></li>
			</ul>
		</li>
		<?php } ?>
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
				Estabelecimentos
				<i class="lni lni-chevron-down icon-right"></i>
			</a>
			<ul class="dropdown-menu">
				<li><a href="<?php admin_url(); ?>/estabelecimentos/adicionar"><i class="lni lni-circle-plus"></i> Adicionar novo</a></li>
				<li><a href="<?php admin_url(); ?>/estabelecimentos"><i class="lni lni-radio-button"></i> Gerenciar</a></li>
				<?php if($oper == 1) { ?>
				<li><a href="<?php admin_url(); ?>/categorias"><i class="lni lni-radio-button"></i> Categorias</a></li>
				<li><a href="<?php admin_url(); ?>/produtos"><i class="lni lni-shopping-basket"></i> Produtos</a></li>
				<li><a href="<?php admin_url(); ?>/pedidos"><i class="lni lni-ticket-alt"></i> Pedidos</a></li>
				<li><a href="<?php admin_url(); ?>/banners"><i class="lni lni-image"></i> Banners</a></li>
				<?php } ?>
			</ul>
		</li>
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
				Planos
				<i class="lni lni-chevron-down icon-right"></i>
			</a>
			<ul class="dropdown-menu">
				<?php if($oper == 1) { ?>
				<li><a href="<?php admin_url(); ?>/planos/adicionar"><i class="lni lni-circle-plus"></i> Adicionar novo</a></li>
				<li><a href="<?php admin_url(); ?>/planos"><i class="lni lni-radio-button"></i> Gerenciar</a></li>
				<li><a href="<?php admin_url(); ?>/assinaturas"><i class="lni lni-star"></i> Assinaturas</a></li>
				<?php } ?>
				<li><a href="<?php admin_url(); ?>/vouchers"><i class="lni lni-ticket"></i> Vouchers</a></li>
				
			</ul>
		</li>
		<!-- <li class="visible-sm visible-xs"><a href="#">Suporte</a></li> -->
	</ul>
</nav> 

<nav class="navbar pull-right hidden-xs hidden-sm">
	<ul class="nav navbar-nav">
		<li class="active"><a target="_blank" href="<?php just_url(); ?>"><i class="lni lni-home"></i> Portal</a></li>
	</ul>
</nav> 

<div class="clear"></div>