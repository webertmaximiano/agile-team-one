<?php
header("Content-type: text/css");
include('../../../_core/_includes/config.php');
$id = mysqli_real_escape_string( $db_con, $_GET['id'] );
$define_query = mysqli_query( $db_con, "SELECT cor FROM estabelecimentos WHERE id = '$id' LIMIT 1");
$define_data = mysqli_fetch_array( $define_query );
$cor = $define_data['cor'];
if( !$cor ) {
	$cor = "#27293E";
}
?>

.colored,
.shop-bag i,
.naver .navbar a i,
.header .naver .navbar .social a:hover i,
.naver .navbar a:hover,
.user-menu i,
.search-bar-mobile button i,
.categoria .vertudo i,
.categoria .counter,
.bread i,
.produto-detalhes .categoria a,
.campo-numero i,
.sacola-table .sacola-remover i,
.sacola-table .sacola-change i,
.adicionado .checkicon,
.title-line i,
.back-button i,
.sidebar-info i,
.filter-select .outside,
.filter-select .fake-select i,
.pagination i,
.funcionamento-mobile i,
.fake-select i,
.search-bar button i,
.holder-shop-bag i
 {
color: <?php echo $cor; ?> !important;
}

.top {
border-color: <?php echo $cor; ?> !important;
}

.footer-info,
.categoria .produto .detalhes,
.cover,
.carousel-indicators .active,
.botao-acao,
.sidebar .sidebar-header,
.minitop,
.opcoes .opcao.active .check,
.floatbar {
background: <?php echo $cor; ?> !important;
}

.pagination > li > a:hover, .pagination > .active > a, .pagination > .active > a:focus, .pagination > .active > a:hover, .pagination > .active > span, .pagination > .active > span:focus, .pagination > .active > span:hover {
background: <?php echo $cor; ?> !important;
color: #fff !important;
}

.is-sticky .avatar {
height: 70px !important;
width: 70px !important;
}

.tv-infinite-menu a.active,
.tv-infinite-menu a:hover,
.fancybox-thumbs__list a::before {
border-color: <?php echo $cor; ?> !important;
}

/* ALL MOBILE */

@media (max-width: 991px) {

	.user-menu i {
	color: #fff !important;
	}

	.shop-bag i {
	color: #fff !important;
	}

	.shop-bag .counter {
	border: 0;
	padding-top: 2px;
	}

	.top {
	border-top: 0;
	background: <?php echo $cor; ?> !important;
	}

}

/* ALL DESK */

@media (min-width: 991px) {

}