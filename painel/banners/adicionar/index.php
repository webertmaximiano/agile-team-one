<?php
// CORE
include('../../../_core/_includes/config.php');
// RESTRICT
restrict_estabelecimento();
restrict_funcionalidade('funcionalidade_banners');
// SEO
$seo_subtitle = "Adicionar banner";
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
  $image_max_width = 2800;
  $image_max_height = 2800;

  // Checar se formulário foi executado

  $formdata = $_POST['formdata'];

  if( $formdata ) {

    // print_r($_POST);

    // Setar campos

    $estabelecimento = $_SESSION['estabelecimento']['id'];
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

    // -- Destaque

    if( !$desktop ) {
      $checkerrors++;
      $errormessage[] = "A imagem desktop não pode ser nula";
    }

    // Executar registro

    if( !$checkerrors ) {

      if( new_banner( $estabelecimento,$titulo,$desktop,$mobile,$link,$status ) ) {

        header("Location: index.php?msg=sucesso");

      } else {

        header("Location: index.php?msg=erro");

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
          <span>Adicionar banner</span>
        </div>

        <div class="bread-box pull-right">
          <div class="bread">
            <a href="<?php admin_url(); ?>"><i class="lni lni-home"></i></a>
            <span>/</span>
            <a href="<?php admin_url(); ?>/banners">Banners</a>
            <span>/</span>
            <a href="<?php admin_url(); ?>/banners/adicionar">Adicionar</a>
          </div>
        </div>
        
			</div>

		</div>

		<!-- Content -->

		<div class="data box-white mt-16">

      <form id="the_form" class="form-default" method="POST" enctype="multipart/form-data">

          <div class="row">

            <div class="col-md-12">

              <?php if( $checkerrors ) { list_errors(); } ?>

              <?php if( $_GET['msg'] == "erro" ) { ?>

                <?php modal_alerta("Erro, tente novamente!","erro"); ?>

              <?php } ?>

              <?php if( $_GET['msg'] == "sucesso" ) { ?>

                <?php modal_alerta("Cadastro efetuado com sucesso!","sucesso"); ?>

              <?php } ?>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Título:</label>
                  <input type="text" name="titulo" placeholder="titulo" value="<?php echo htmlclean( $_POST['titulo'] ); ?>">

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">
            <label>Arte para computadores:</label>
              <div class="file-preview">

                <div class="image-preview image-preview-cover" id="image-preview" style='background: url("") no-repeat center center; background-size: auto 102%;'>
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

                <div class="image-preview image-preview-mobile" id="image-preview2" style='background: url("") no-repeat center center; background-size: auto 102%;'>
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
                  <input type="text" name="link" placeholder="link" value="<?php echo htmlclean( $_POST['link'] ); ?>">

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
                      <input type="radio" name="status" value="1" <?php if( $_POST['status'] == 1 OR !$_POST['status'] ){ echo 'CHECKED'; }; ?>> Sim
                    </div>
                    <div class="form-field-radio">
                      <input type="radio" name="status" value="2" <?php if( $_POST['status'] == 2 ){ echo 'CHECKED'; }; ?>> Não
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
  								<span>Cadastrar <i class="lni lni-chevron-right"></i></span>
  							</button>
  						</div>
  					</div>

          </div>

      </form>

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

$( "#the_form" ).change(function() {

  var form = "";
          
  // Globais
  var form = $("#the_form");
  form.validate({

    /* REGRAS DE VALIDAÇÃO DO FORMULÁRIO */

    rules:{

      desktop:{
      required: true
      }

    },
        
    /* DEFINIÇÃO DAS MENSAGENS DE ERRO */
            
    messages:{

      desktop:{
        required: "Esse campo é obrigatório"
      }

    }

  });

  form.validate().settings.ignore = ":hidden";

});

$("#the_form").trigger("change");

</script>

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