<?php
// CORE
include('../../../_core/_includes/config.php');
// RESTRICT
restrict('1');
// SEO
$seo_subtitle = "Editar voucher";
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
  $id = mysqli_real_escape_string( $db_con, $_GET['id'] );
  $edit = mysqli_query( $db_con, "SELECT * FROM vouchers WHERE id = '$id' LIMIT 1");
  $hasdata = mysqli_num_rows( $edit );
  $data = mysqli_fetch_array( $edit );

  // Checar se formulário foi executado

  $formdata = isset($_POST['formdata']);

  if( $formdata ) {

    // Setar campos

    $plano = mysqli_real_escape_string( $db_con, $_POST['plano'] );
    $descricao = mysqli_real_escape_string( $db_con, $_POST['descricao'] );

    // Checar Erros

    $checkerrors = 0;
    $errormessage = array();

      // -- Já utilizado

      if( $data['status'] == "2" ) {
        $checkerrors++;
        $errormessage[] = "O voucher ja foi utilizado e não pode ser alterado, apenas removido.";
      }

      // -- Plano

      if( !$plano ) {
        $checkerrors++;
        $errormessage[] = "O plano não pode ser nulo";
      }

      // -- Descrição

      if( !$descricao ) {
        $checkerrors++;
        $errormessage[] = "A Descrição não pode ser nula";
      }

    // Executar registro

    if( !$checkerrors ) {

      if( edit_voucher( $id,$plano,$descricao ) ) {

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
          <i class="lni lni-shopping-basket"></i>
          <span>Editar voucher</span>
        </div>

        <div class="bread-box pull-right">
          <div class="bread">
            <a href="<?php admin_url(); ?>"><i class="lni lni-home"></i></a>
            <span>/</span>
            <a href="<?php admin_url(); ?>/vouchers">Vouchers</a>
            <span>/</span>
            <a href="<?php admin_url(); ?>/vouchers/editar?id=<?php echo $id; ?>">Editar</a>
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

              <?php if( isset($checkerrors) ) { list_errors(); } ?>
                <?php if( isset($_GET['msg'])) { ?>
                  <?php if( $_GET['msg'] == "erro" ) { ?>

                    <?php modal_alerta("Erro, tente novamente!","erro"); ?>

                  <?php } ?>

                  <?php if( $_GET['msg'] == "sucesso" ) { ?>

                    <?php modal_alerta("Cadastro efetuado com sucesso!","sucesso"); ?>

                  <?php } ?>
                <?php } ?>
            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

               <label>Plano:</label>
                  <div class="fake-select">
                    <i class="lni lni-chevron-down"></i>
                    <select id="input-plano" name="plano">

                      <option value=""></option>
                      <?php 
                      $quicksql = mysqli_query( $db_con, "SELECT * FROM planos ORDER BY id ASC" );
                      while( $quickdata = mysqli_fetch_array( $quicksql ) ) {
                      ?>

                      <option <?php if( $data['rel_planos_id'] == $quickdata['id'] ) { echo "SELECTED"; }; ?> value="<?php echo $quickdata['id']; ?>"><?php echo $quickdata['nome']; ?></option>

                      <?php } ?>

                    </select>
                    <div class="clear"></div>
                  </div>
              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Descrição:</label>
                  <input type="text" id="input-descricao" name="descricao" placeholder="Descrição" value="<?php echo htmlclean( $data['descricao'] ); ?>">

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

<div class="just-ajax"></div>

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

        plano:{
        required: true
        },
        descricao:{
        required: true
        }

      },
          
      /* DEFINIÇÃO DAS MENSAGENS DE ERRO */
              
      messages:{

        plano:{
          required: "Esse campo é obrigatório"
        },
        descricao:{
          required: "Esse campo é obrigatório"
        }

      }

    });

  });

</script>