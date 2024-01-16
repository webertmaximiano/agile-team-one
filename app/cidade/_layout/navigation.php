<?php
global $app;
$app_id = $app['id'];
?>
<nav class="navbar alignmiddle">
	<ul class="nav navbar-nav">
		<li><a href="<?php echo $app['url']; ?>">Ínicio</a></li>
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
				Estabelecimentos
				<i class="lni lni-chevron-down icon-right"></i>
			</a>
			<ul class="dropdown-menu">
				<li><a href="<?php echo $app['url']; ?>/categoria">Todas</a></li>
				<?php
				$query_categoria = 	"SELECT * FROM categorias WHERE rel_estabelecimentos_id = '$app_id' AND visible = '1' AND status = '1' ORDER BY nome ASC";
				$query_categoria = mysqli_query( $db_con, $query_categoria );
				while ( $data_categoria = mysqli_fetch_array( $query_categoria ) ) {
				?>
				<li><a href="<?php echo $app['url']; ?>/categoria/<?php echo $data_categoria['id']; ?>"><?php echo $data_categoria['nome']; ?></a></li>
				<?php } ?>
			</ul>
		</li>
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
				Produtos
				<i class="lni lni-chevron-down icon-right"></i>
			</a>
			<ul class="dropdown-menu">
				<li><a href="<?php echo $app['url']; ?>/categoria">Todas</a></li>
				<?php
				$query_categoria = 	"SELECT * FROM categorias WHERE rel_estabelecimentos_id = '$app_id' AND visible = '1' AND status = '1' ORDER BY nome ASC";
				$query_categoria = mysqli_query( $db_con, $query_categoria );
				while ( $data_categoria = mysqli_fetch_array( $query_categoria ) ) {
				?>
				<li><a href="<?php echo $app['url']; ?>/categoria/<?php echo $data_categoria['id']; ?>"><?php echo $data_categoria['nome']; ?></a></li>
				<?php } ?>
			</ul>
		</li>
		<li><a href="<?php echo $app['url']; ?>/categoria?filtro=4">Ofertas</a></li>
	</ul>
</nav> 

<div class="clear"></div>