<?php
// CORE
include('../../../_core/_includes/config.php');
// RESTRICT
restrict_estabelecimento();
// SEO
$seo_subtitle = "Limite atingido";
$seo_description = "";
$seo_keywords = "";
// HEADER
$system_header .= "";
include('../../_layout/head.php');
include('../../_layout/top.php');
include('../../_layout/sidebars.php');
include('../../_layout/modal.php');

?>

<div class="middle minfit bg-gray">

  <div class="container">

    <div class="row">

      <div class="col-md-12">

        <div class="title-icon pull-left">
          <i class="lni lni-shopping-basket"></i>
          <span>Limite de produtos atingido</span>
        </div>

        <div class="bread-box pull-right">
          <div class="bread">
            <a href="<?php panel_url(); ?>"><i class="lni lni-home"></i></a>
            <span>/</span>
            <a href="<?php panel_url(); ?>/produtos">Produtos</a>
            <span>/</span>
            <a href="<?php panel_url(); ?>/produtos/editar?id=<?php echo $id; ?>">Editar</a>
          </div>
        </div>
        
      </div>

    </div>

    <!-- Content -->

    <div class="data box-white mt-16">

      <div class="expiration-info variacao-hire variacao-limitado">
        <div class="row">
          <div class="col-md-9">
            <span class="msg msg-limitado">Você atingiu o seu limite de produtos cadastrados, para continuar contrate um plano com limite maior.</span>
          </div>
          <div class="col-md-3">
            <div class="add-new add-center text-center">
              <a href="<?php panel_url(); ?>/plano/listar">
                <span>Ver planos</span>
                <i class="lni lni-plus"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="clear"></div>

    </div>

    <!-- / Content -->

  </div>

</div>

<div class="just-ajax"></div>

<?php 
// FOOTER
$system_footer .= "";
include('../../_layout/rdp.php');
include('../../_layout/footer.php');
?>