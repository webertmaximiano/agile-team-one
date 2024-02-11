<?php
// CORE
include('../../../_core/_includes/config.php');
// RESTRICT
restrict_estabelecimento();
restrict_expirado();
// SEO
$seo_subtitle = "Adicionar produto";
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
  global $gallery_max_files;

  // Checar se formulário foi executado

  $formdata = $_POST['formdata'];

  if( $formdata ) {

    // Setar campos

    $estabelecimento = mysqli_real_escape_string( $db_con, $_POST['estabelecimento_id'] );
    $categoria = mysqli_real_escape_string( $db_con, $_POST['categoria'] );
    $nome = mysqli_real_escape_string( $db_con, $_POST['nome'] );
    $descricao = mysqli_real_escape_string( $db_con, $_POST['descricao'] );
    $valor = dinheiro( mysqli_real_escape_string( $db_con, $_POST['valor'] ) );
    $oferta = mysqli_real_escape_string( $db_con, $_POST['oferta'] );
    $valor_promocional = dinheiro( mysqli_real_escape_string( $db_con, $_POST['valor_promocional'] ) );
    if( !$valor_promocional ) {
      $valor_promocional = "0.00";
    }
    $visible = mysqli_real_escape_string( $db_con, $_POST['visible'] );
    $status = "1";

    // Checar Erros

    $checkerrors = 0;
    $errormessage = array();

      if( $_FILES['destaque']['name'] ) {

        $upload = upload_image( $estabelecimento, $_FILES['destaque'] );
        
        if ( $upload['status'] == "1" ) {
          $destaque = $upload['url'];
        } else {
          $checkerrors++;
          for( $x=0; $x < count( $upload['errors'] ); $x++ ) {
            $errormessage[] = $upload['errors'][$x];
          }
        }

      }

      // -- Estabelecimento

      if( !$estabelecimento ) {
        $checkerrors++;
        $errormessage[] = "O estabelecimento não pode ser nulo";
      }

      // -- Nome

      if( !$nome ) {
        $checkerrors++;
        $errormessage[] = "O nome não pode ser nulo";
      }

      // -- Destaque

      if( !$destaque ) {
        $checkerrors++;
        $errormessage[] = "A imagem destaque não pode ser nula";
      }

      // -- Valor

      if( !$valor ) {
        $checkerrors++;
        $errormessage[] = "O valor não pode ser nulo";
      }

    // Executar registro

    if( !$checkerrors ) {

      if( $pid = new_produto( $estabelecimento,$categoria,$destaque,$nome,$descricao,$valor,$oferta,$valor_promocional,$status ) ) {

        if ( $_FILES['file'] ) {

            for ($i = 0; $i < count( $_FILES['file']['name'] ); $i++) {

                $file_name = $_FILES['file']['name'][$i];
                $file_size = $_FILES['file']['size'][$i];
                $file_tmp = $_FILES['file']['tmp_name'][$i];
                $file_type = $_FILES['file']['type'][$i];
                $upload = upload_image_direct( $estabelecimento,$file_name,$file_size,$file_tmp,$file_type );

                if ( $upload['status'] == "1" ) {
                
                  $url = $upload['url'];

                  mysqli_query( $db_con, "INSERT INTO midia (type,rel_estabelecimentos_id,rel_id,url) VALUES ('1','$estabelecimento','$pid','$url')");

                }             
            }

        }

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
          <i class="lni lni-shopping-basket"></i>
          <span>Adicionar produto</span>
        </div>

        <div class="bread-box pull-right">
          <div class="bread">
            <a href="<?php admin_url(); ?>"><i class="lni lni-home"></i></a>
            <span>/</span>
            <a href="<?php admin_url(); ?>/produtos">Produtos</a>
            <span>/</span>
            <a href="<?php admin_url(); ?>/produtos/adicionar">Adicionar</a>
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

                  <label>Estabelecimento:</label>
                  <input class="autocompleter <?php if( $_POST['estabelecimento'] && $_POST['estabelecimento_id'] ) { echo "autocomplete-selected"; } ?>" type="text" name="estabelecimento" placeholder="Estabelecimento" value="<?php echo htmlclean( $_POST['estabelecimento'] ); ?>" completer_url="<?php just_url(); ?>/_core/_ajax/autocomplete_estabelecimentos.php" completer_field="estabelecimento_id"/>
                  <input class="fakehidden" type="text" name="estabelecimento_id" value="<?php echo htmlclean( $_POST['estabelecimento_id'] ); ?>"/>

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

               <label>Categoria:</label>
                  <div class="fake-select">
                    <i class="lni lni-chevron-down"></i>
                    <select id="input-categoria" name="categoria">

                      <option value=""></option>
                      <?php 
                      $quicksql = mysqli_query( $db_con, "SELECT * FROM categorias ORDER BY nome ASC LIMIT 10" );
                      while( $quickdata = mysqli_fetch_array( $quicksql ) ) {
                      ?>

                      <option <?php if( $_POST['categoria'] == $quickdata['id'] ) { echo "SELECTED"; }; ?> value="<?php echo $quickdata['id']; ?>"><?php echo $quickdata['nome']; ?></option>

                      <?php } ?>

                    </select>
                    <div class="clear"></div>
                  </div>
              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">
              <label>Foto destaque:</label>
              <div class="file-preview">

                <div class="image-preview image-preview-product" id="image-preview" style='background: url("") no-repeat center center; background-size: auto 102%;'>
                  <label for="image-upload" id="image-label">Clique ou arraste</label>
                  <input type="file" name="destaque" id="image-upload"/>
                </div>
                <span class="explain">Selecione a foto destaque clicando no campo ou arrastando o arquivo!</span>

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                <label>Galeria:</label>
                <div class="field-gallery">
                  <div class="input-gallery" style="padding-top: .5rem;">
                  </div>
                </div>

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Nome:</label>
                  <input type="text" id="input-nome" name="nome" placeholder="Nome" value="<?php echo htmlclean( $_POST['nome'] ); ?>">

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Descrição:</label>
                  <textarea rows="6" name="descricao" placeholder="Descrição do seu estabelecimento"><?php echo htmlclean( $_POST['descricao'] ); ?></textarea>

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Valor:</label>
                  <input class="maskmoney" type="text" name="valor" placeholder="Valor" value="<?php echo htmlclean( $_POST['valor'] ); ?>">

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Este produto está em oferta?</label>
                  <div class="form-field-radio">
                    <input type="radio" name="oferta" value="1" element-show=".elemento-promocional" <?php if( $_POST['oferta'] == 1 ){ echo 'CHECKED'; }; ?>> Sim
                  </div>
                  <div class="form-field-radio">
                    <input type="radio" name="oferta" value="2" element-hide=".elemento-promocional" <?php if( $_POST['oferta'] == 2 OR !$_POST['oferta']  ){ echo 'CHECKED'; }; ?>> Não
                  </div>
                  <div class="clear"></div>

              </div>

            </div>

          </div>

          <div class="row elemento-promocional elemento-oculto">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Valor promocional:</label>
                  <input class="maskmoney" type="text" name="valor_promocional" placeholder="Valor promocional" value="<?php echo htmlclean( $_POST['valor_promocional'] ); ?>">

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Produto visível?</label>
                  <span class="form-tip">Se habilitado o produto irá aparecer em todo site, caso contrário, irá aparecer apenas para quem você compartilhar o link.</span>
                  <div class="radios">
                    <div class="spacer"></div>
                    <div class="form-field-radio">
                      <input type="radio" name="visible" value="1" <?php if( $_POST['visible'] == 1 OR !$_POST['visible'] ){ echo 'CHECKED'; }; ?>> Sim
                    </div>
                    <div class="form-field-radio">
                      <input type="radio" name="visible" value="2" <?php if( $_POST['visible'] == 2 ){ echo 'CHECKED'; }; ?>> Não
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

$(document).ready( function() {
          
  // Globais

  $("#the_form").validate({

      /* REGRAS DE VALIDAÇÃO DO FORMULÁRIO */

      rules:{

        estabelecimento_id:{
        required: true
        },
        nome:{
        required: true
        },
        destaque:{
        required: true
        },
        valor:{
        required: true
        }

      },
          
      /* DEFINIÇÃO DAS MENSAGENS DE ERRO */
              
      messages:{

        estabelecimento_id:{
          required: "Esse campo é obrigatório"
        },
        nome:{
          required: "Esse campo é obrigatório"
        },
        destaque:{
          required: "Obrigatório"
        },
        valor:{
          required: "Esse campo é obrigatório"
        }

      }

    });

  });

</script>

<script>

$( document ).ready(function() {

  $( "input[name=estabelecimento_id]" ).change(function() {
      var estabelecimento = $(this).val();
      $("#input-categoria").html("<option>-- Carregando categorias --</option>");
      $("#input-categoria").load("<?php just_url(); ?>/_core/_ajax/categorias.php?estabelecimento="+estabelecimento);
  });

  $( "#input-categoria" ).click(function() {
    $( "input[name=estabelecimento_id]" ).trigger("change");
  });

  $( "input[name=estabelecimento_id]" ).trigger("change");

});

</script>

<script>

var themaxfiles = <?php echo $gallery_max_files; ?>;

function watchadd() {

  $('.add-gallery-button').remove();
  var thetotal = $('.uploaded-image').size();
  if( thetotal < themaxfiles ) {
    $('.field-gallery .uploaded').append("<div class='add-gallery-button' title='Adicionar imagem'></div>");
  }

}

function kill_image(fileid) {
    // alert(fileid);
    watchadd();
}

</script>

<script type="text/javascript" src="src/image-uploader.js"></script>

<script>
    $(function () {

        let preloaded = [];

        $('.input-gallery').imageUploader({
            preloaded: preloaded,
            imagesInputName: 'file',
            preloadedInputName: 'old',
            maxFiles: themaxfiles
        });

        $( ".addnew" ).click(function() {

            $( ".input-gallery input[type=file]" ).trigger("click");

        });

        watchadd();

    });
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