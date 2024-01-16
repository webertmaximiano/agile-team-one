<?php
global $app;
$app_id = $app['id'];
?>
<nav class="navbar pull-left">
	<ul class="nav navbar-nav">
		<li class="active"><a href="<?php echo $app['url']; ?>">Ínicio</a></li>
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
				Categorias
				<i class="lni lni-chevron-down icon-right"></i>
			</a>
			<ul class="dropdown-menu">
				<li><a href="<?php echo $app['url']; ?>/categoria">Todas</a></li>
				<?php
				$query_categoria = 	"SELECT * FROM categorias WHERE rel_estabelecimentos_id = '$app_id' AND visible = '1' AND status = '1' ORDER BY ordem ASC";
				$query_categoria = mysqli_query( $db_con, $query_categoria );
				while ( $data_categoria = mysqli_fetch_array( $query_categoria ) ) {
				?>
				<li><a href="<?php echo $app['url']; ?>/categoria/<?php echo $data_categoria['id']; ?>"><?php echo $data_categoria['nome']; ?></a></li>
				<?php } ?>
			</ul>
		</li>
		<li><a href="<?php echo $app['url']; ?>/categoria?filtro=4">Ofertas</a></li>
		<li><span class="sidrLeft">Fale conosco</span></li>
		<!-- <li class="visible-sm visible-xs"><a href="#">Suporte</a></li> -->
	</ul>
</nav> 

<nav class="navbar pull-right hidden-xs hidden-sm">
	<div class="social">
		<?php if( $app['contato_whatsapp'] ) { ?>
		<a href="https://wa.me/55<?php echo $app['contato_whatsapp']; ?>" target="_blank"><i class="lni lni-whatsapp"></i></a>
		<?php } ?>
		<?php if( $app['contato_facebook'] ) { ?>
		<a href="<?php echo linker( $app['contato_facebook'] ); ?>" target="_blank"><i class="lni lni-facebook-filled"></i></a>
		<?php } ?>
		<?php if( $app['contato_instagram'] ) { ?>
		<a href="<?php echo linker( $app['contato_instagram'] ); ?>" target="_blank"><i class="lni lni-instagram-original"></i></a>
		<?php } ?>
		<?php if( $app['contato_youtube'] ) { ?>
		<a href="<?php echo linker( $app['contato_youtube'] ); ?>" target="_blank"><i class="lni lni-youtube"></i></a>
		<?php } ?>
	</div>
</nav> 

<div class="clear"></div>