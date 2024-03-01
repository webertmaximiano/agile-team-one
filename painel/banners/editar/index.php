<?php
// CORE
include('../../../_core/_includes/config.php');
// RESTRICT
restrict_estabelecimento();
restrict_funcionalidade('funcionalidade_banners');
// SEO
$seo_subtitle = "Editar banner";
$seo_description = "";
$seo_keywords = "";
// HEADER
$system_header .= "";
include('../../_layout/head.php');
include('../../_layout/top.php');
include('../../_layout/sidebars.php');
include('../../_layout/modal.php');
?>

<?php

  // Globals

  global $numeric_data;
  $estabelecimento = $_SESSION['estabelecimento']['id'];
  $id = mysqli_real_escape_string( $db_con, $_GET['id'] );
  $edit = mysqli_query( $db_con, "SELECT * FROM banners WHERE id = '$id' AND rel_estabelecimentos_id = '$estabelecimento' LIMIT 1");
  $hasdata = mysqli_num_rows( $edit );
  $data = mysqli_fetch_array( $edit );

  $image_max_width = 2800;
  $image_max_height = 2800;

  // Checar se formulário foi executado

  $formdata = $_POST['formdata'];

  if( $formdata ) {

    // Setar campos

    $titulo = mysqli_real_escape_string( $db_con, $_POST['titulo'] );
    $link = mysqli_real_escape_string( $db_con, $_POST['link'] );
    $status = mysqli_real_escape_string( $db_con, $_POST['status'] );

    // Checar Erros

    $checkerrors = 0;
    $errormessage = array();

    if( $_FILES['desktop']['name'] ) {

      $upload = upload_image( $estabelecimento, $_FILES['desktop'] );
      
      if ( $upload['status'] == "1" ) {
        $desktop = $upload['url'];
      } else {
        $checkerrors++;
        for( $x=0; $x < count( $upload['errors'] ); $x++ ) {
          $errormessage[] = $upload['errors'][$x];
        }
      }

    }

    if( $_FILES['mobile']['name'] ) {

      $upload = upload_image( $estabelecimento, $_FILES['mobile'] );
      
      if ( $upload['status'] == "1" ) {
        $mobile = $upload['url'];
      } else {
        $checkerrors++;
        for( $x=0; $x < count( $upload['errors'] ); $x++ ) {
          $errormessage[] = $upload['errors'][$x];
        }
      }

    }

    // -- Estabelecimento

    if( $data['rel_estabelecimentos_id'] != $estabelecimento ) {
      $checkerrors++;
      $errormessage[] = "Ação inválida";
    }

    // Executar registro

    if( !$checkerrors ) {

      if( edit_banner( $id,$estabelecimento,$titulo,$desktop,$mobile,$link,$status ) ) {

        header("Location: index.php?msg=sucesso&id=".$id);

      } else {

        header("Location: index.php?msg=erro&id=".$id);

      }

    }

  }
  
?>

<div class="middle minfit bg-gray">

  <div class="container">

    <div class="row">

      <div class="col-md-12">

        <div class="title-icon pull-left">
          <i class="lni lni-image"></i>
          <span>Editar banner</span>
        </div>

        <div class="bread-box pull-right">
          <div class="bread">
            <a href="<?php admin_url(); ?>"><i class="lni lni-home"></i></a>
            <span>/</span>
            <a href="<?php admin_url(); ?>/banners">Banners</a>
            <span>/</span>
            <a href="<?php admin_url(); ?>/banners/editar?id=<?php echo $id; ?>">Editar</a>
          </div>
        </div>
        
      </div>

    </div>

    <!-- Content -->

    <div class="data box-white mt-16">

      <?php if( $hasdata ) { ?>

      <form id="the_form" class="form-default" method="POST" enctype="multipart/form-data">

          <div class="row">

            <div class="col-md-12">

              <?php if( $checkerrors ) { list_errors(); } ?>

              <?php if( $_GET['msg'] == "erro" ) { ?>

                <?php modal_alerta("Erro, tente novamente!","erro"); ?>

              <?php } ?>

              <?php if( $_GET['msg'] == "sucesso" ) { ?>

                <?php modal_alerta("Cadastro alterado com sucesso!","sucesso"); ?>

              <?php } ?>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Título:</label>
                  <input type="text" name="titulo" placeholder="titulo" value="<?php echo htmlclean( $data['titulo'] ); ?>">

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">
            <label>Arte para computadores:</label>
              <div class="file-preview">

                <div class="image-preview image-preview-cover" id="image-preview" style='background: url("<?php echo imager( $data['desktop'] ); ?>") no-repeat center center; background-size: auto 102%;'>
                  <label for="image-upload" id="image-label">Enviar imagem</label>
                  <input type="file" name="desktop" id="image-upload"/>
                </div>
                <span class="explain">
                  Selecione sua capa clicando no campo ou arrastando o arquivo!<br/>
                  Tamanho recomendado: 1280x300 pixels
                </span>

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">
            <label>Arte para dispositivos móveis:</label>
              <div class="file-preview">

                <div class="image-preview image-preview-mobile" id="image-preview2" style='background: url("<?php echo imager( $data['mobile'] ); ?>") no-repeat center center; background-size: auto 102%;'>
                  <label for="image-upload2" id="image-label">Enviar imagem</label>
                  <input type="file" name="mobile" id="image-upload2"/>
                </div>
                <span class="explain">
                  Selecione sua capa clicando no campo ou arrastando o arquivo!<br/>
                  Tamanho recomendado: 800x350 pixels
                </span>

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Link:</label>
                  <input type="text" name="link" placeholder="link" value="<?php echo htmlclean( $data['link'] ); ?>">

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Ativo?</label>
                  <div class="radios">
                    <div class="spacer"></div>
                    <div class="form-field-radio">
                      <input type="radio" name="status" value="1" <?php if( $data['status'] == 1 OR !$data['status'] ){ echo 'CHECKED'; }; ?>> Sim
                    </div>
                    <div class="form-field-radio">
                      <input type="radio" name="status" value="2" <?php if( $data['status'] == 2 ){ echo 'CHECKED'; }; ?>> Não
                    </div>
                    <div class="clear"></div>
                  </div>

              </div>

            </div>

          </div>

          <div class="row lowpadd">

            <div class="col-md-6 col-sm-5 col-xs-5">
              <div class="form-field form-field-submit">
                <a href="javascript:history.back(1)" class="backbutton pull-left">
                  <span><i class="lni lni-chevron-left"></i> Voltar</span>
                </a>
              </div>
            </div>

            <div class="col-md-6 col-sm-7 col-xs-7">
              <input type="hidden" name="formdata" value="true"/>
              <div class="form-field form-field-submit">
                <button class="pull-right">
                  <span>Salvar <i class="lni lni-chevron-right"></i></span>
                </button>
              </div>
            </div>

          </div>

      </form>

      <?php } else { ?>

        <span class="nulled nulled-edit color-red">Erro, inválido ou não encontrado!</span>

      <?php } ?>

    </div>

    <!-- / Content -->

  </div>

</div>

<?php 
// FOOTER
$system_footer .= "";
include('../../_layout/rdp.php');
include('../../_layout/footer.php');
?>

<script>
  
$(document).ready(function() {
    
  // Preview avatar
  $.uploadPreview({
    input_field: "#image-upload",
    preview_box: "#image-preview",
    label_field: "#image-label",
    label_default: "Envie ou arraste",
    label_selected: "Trocar imagem",
    no_label: false
  });

  // Preview capa
  $.uploadPreview({
    input_field: "#image-upload2",
    preview_box: "#image-preview2",
    label_field: "#image-label2",
    label_default: "Envie ou arraste",
    label_selected: "Trocar imagem",
    no_label: false
  });

});

</script>