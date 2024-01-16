<?php
require_once("_core/_includes/config.php");
// SEO
$seo_subtitle = "Página não encontrada";
$seo_description = "Página não encontrada";
$seo_keywords = $app['title'].", ".$seo_title;
$seo_image = get_just_url()."/_core/_cdn/img/favicon.png";
// HEADER
$system_header .= "";
include('_core/_layout/head.php');
include('_core/_layout/top.php');
include('_core/_layout/sidebars.php');
include('_core/_layout/modal.php');
?>

<?php if( $_GET['msg'] == "erro" ) { ?>

<?php modal_alerta("Erro, tente novamente!","erro"); ?>

<?php } ?>

<?php if( $_GET['msg'] == "sucesso" ) { ?>

<?php modal_alerta("Ação efetuada com sucesso!","sucesso"); ?>

<?php } ?>

<div class="middle minfit bg-gray">

	<div class="container">

		<div class="row">

			<div class="col-md-12">

				<div class="title-icon pull-left">
					<i class="lni lni-close"></i>
					<span>Página não encontrada</span>
				</div>

				<div class="bread-box pull-right">
					<div class="bread">
						<a href="<?php just_url(); ?>"><i class="lni lni-home"></i></a>
						<span>/</span>
						<a href="<?php just_url(); ?>/404">Não encontrado</a>
					</div>
				</div>

			</div>

		</div>

		<div class="row">

			<div class="col-md-12">

				<div class="adicionado">

					<i class="checkicon lni lni-cross-circle"></i>
					<span class="text">A pagina que você esta tentando acessa é inválida ou não existe mais!</span>
					<a href="<?php echo $app['url']; ?>" class="botao-acao"><i class="lni lni-home"></i> <span>Voltar para o início</span></a>

				</div>

			</div>

		</div>

	</div>

</div>

<?php 
// FOOTER
$system_footer .= "";
include('_core/_layout/rdp.php');
include('_core/_layout/footer.php');
?>