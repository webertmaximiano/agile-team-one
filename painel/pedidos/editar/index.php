<?php
// CORE
include('../../../_core/_includes/config.php');
// RESTRICT
restrict_estabelecimento();
restrict_expirado();
// SEO
$seo_subtitle = "Editar pedido";
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
  $eid = $_SESSION['estabelecimento']['id'];
  $id = mysqli_real_escape_string( $db_con, $_GET['id'] );
  $edit = mysqli_query( $db_con, "SELECT * FROM pedidos WHERE id = '$id' LIMIT 1");
  $hasdata = mysqli_num_rows( $edit );
  $data = mysqli_fetch_array( $edit );

  // Checar se formulário foi executado

  $formdata = $_POST['formdata'];

  if( $formdata ) {

    // Setar campos

    $status = mysqli_real_escape_string( $db_con, $_POST['status'] );

    // Checar Erros

    $checkerrors = 0;
    $errormessage = array();

      // -- Statis

      if( !$status ) {
        $checkerrors++;
        $errormessage[] = "O status não pode ser nulo";
      }

      // -- Estabelecimento

      if( $data['rel_estabelecimentos_id'] != $eid ) {
        $checkerrors++;
        $errormessage[] = "Ação inválida";
      }

    // Executar registro

    if( !$checkerrors ) {

      if( edit_pedido( $id,$status ) ) {

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
          <span>Editar pedido</span>
        </div>

        <div class="bread-box pull-right">
          <div class="bread">
            <a href="<?php admin_url(); ?>"><i class="lni lni-home"></i></a>
            <span>/</span>
            <a href="<?php admin_url(); ?>/pedidos">Pedidos</a>
            <span>/</span>
            <a href="<?php admin_url(); ?>/pedidos/editar?id=<?php echo $id; ?>">Editar</a>
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

                <?php modal_alerta("Editado com sucesso!","sucesso"); ?>

              <?php } ?>

            </div>

          </div>

          <div class="row">

            <div class="col-md-6">

              <div class="comprovante">
                <div class="content">
                  <?php echo nl2br( bbzap( $data['comprovante'] ) ); ?>
                </div>
              </div>

            </div>

            <div class="col-md-6">

              <div class="form-field-default">
                <a target="_blank" href="<?php panel_url(); ?>/pedidos/imprimir?id=<?php echo $id; ?>" class="botao-acao botao-whatsapp"><i class="lni lni-printer"></i> Imprimir</a>
              </div>


                                            <?php if( $data['status'] == "1" ) { $statuspedido = "Pendente"; }
                                            else {
                                                if( $data['status'] == "2" ) { $statuspedido = "Concluído"; }
                                            else {   
                                                if( $data['status'] == "3" ) { $statuspedido = "Cancelado"; } 
                                            else {
                                                if( $data['status'] == "4" ) { $statuspedido = "Aceito"; } 
                                            else {
                                                if( $data['status'] == "5" ) { $statuspedido = "Saiu Para Entrega"; } 
                                            else {
                                                if( $data['status'] == "6" ) { $statuspedido = "Disponível p/ Retirada"; } 
                                            else {
                                                if( $data['status'] == "7" ) { $statuspedido = "Aceito/Impresso"; }
                                            }}}}}} ?>
                                                



              <div class="form-field-default">
                <a target="_blank" href="https://wa.me/55<?php echo $data['whatsapp']; echo '?text=+%2A'; echo $data['nome']; echo '%2A%0D%0AHouve+uma+mudança+no+status+do+seu+pedido:+%2A'; echo $statuspedido; echo '%2A%0D%0AEstamos%20a%20disposi%C3%A7%C3%A3o%20para%20mais%20informa%C3%A7%C3%B5es%21';?>" class="botao-acao botao-whatsapp"><i class="lni lni-whatsapp"></i> Reenviar no whatsapp</a>
              </div>

              <div class="form-field-default">

                  <label>Status:</label>
                  <div class="fake-select">
                    <i class="lni lni-chevron-down"></i>
                    <select name="status">
                      <option></option>
                      <?php for( $x = 0; $x < count( $numeric_data['status_pedido'] ); $x++ ) { ?>
                      <option value="<?php echo $numeric_data['status_pedido'][$x]['value']; ?>" <?php if( $data['status'] == $numeric_data['status_pedido'][$x]['value'] ) { echo 'SELECTED'; }; ?>><?php echo $numeric_data['status_pedido'][$x]['name']; ?></option>
                      <?php } ?>
                    </select>
                    <div class="clear"></div>
                </div>

              </div>

            </div>

          </div>

          <div class="row lowpadd">

            <div class="col-md-6 col-sm-5 col-xs-5">
              <div class="form-field form-field-submit">
                <a href="<?php panel_url(); ?>/pedidos" class="backbutton pull-left">
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

        status:{
        required: true
        }

      },
          
      /* DEFINIÇÃO DAS MENSAGENS DE ERRO */
              
      messages:{

        status:{
          required: "Esse campo é obrigatório"
        }

      }

    });

  });

</script>