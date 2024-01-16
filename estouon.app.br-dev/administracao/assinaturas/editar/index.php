<?php
// CORE
include('../../../_core/_includes/config.php');
// RESTRICT
restrict('1');
// SEO
$seo_subtitle = "Editar assinatura";
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
  $edit = mysqli_query( $db_con, "SELECT * FROM assinaturas WHERE id = '$id' LIMIT 1");
  $hasdata = mysqli_num_rows( $edit );
  $data = mysqli_fetch_array( $edit );

  // Checar se formulário foi executado

  $formdata = isset($_POST['formdata']);

  if( $formdata ) {

    // Setar campos

    $funcionalidade_marketplace = mysqli_real_escape_string( $db_con, $_POST['funcionalidade_marketplace'] );
    $funcionalidade_variacao = mysqli_real_escape_string( $db_con, $_POST['funcionalidade_variacao'] );
    $funcionalidade_banners = mysqli_real_escape_string( $db_con, $_POST['funcionalidade_banners'] );
    if( $data['expiration'] ) {
      $expiration = datausa_min( mysqli_real_escape_string( $db_con, $_POST['expiration'] ) );
    } else {
      $expiraton = "";
    }

    $limite_produtos = mysqli_real_escape_string( $db_con, $_POST['limite_produtos'] );

    // Checar Erros

    $checkerrors = 0;
    $errormessage = array();

      // -- Expiration

      if( $data['expiration'] ) {
        if( !$expiration ) {
          $checkerrors++;
          $errormessage[] = "A expiração não pode ser nula";
        }
      }

    // Executar registro

      if( !$checkerrors ) {

        if( edit_assinatura( $id,$funcionalidade_marketplace,$funcionalidade_variacao,$funcionalidade_banners,$expiration,$limite_produtos ) ) {

          $eid = data_info("assinaturas",$id,"rel_estabelecimentos_id");
          atualiza_estabelecimento($eid,"offline");
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
          <i class="lni lni-star"></i>
          <span>Editar assinatura</span>
        </div>

        <div class="bread-box pull-right">
          <div class="bread">
            <a href="<?php admin_url(); ?>"><i class="lni lni-home"></i></a>
            <span>/</span>
            <a href="<?php admin_url(); ?>/assinaturas">Assinaturas</a>
            <span>/</span>
            <a href="<?php admin_url(); ?>/assinaturas/editar?id=<?php echo $id; ?>">Editar</a>
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

                  <?php modal_alerta("Cadastro alterado com sucesso!","sucesso"); ?>

                <?php } ?>
              <?php } ?>
            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Estabelecimento:</label>
                  <input type="text" id="input-estabelecimento" name="estabelecimento" placeholder="Estabelecimento" value="<?php echo htmlclean( data_info( "estabelecimentos", $data['rel_estabelecimentos_id'], "nome" ) ); ?>" DISABLED>

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Nº:</label>
                  <input type="text" id="input-numero" name="numero" placeholder="Nº" value="Assinatura #<?php echo htmlclean( $data['id'] ); ?>" DISABLED>

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>REF:</label>
                  <input type="text" id="input-ref" name="ref" placeholder="REF" value="<?php echo htmlclean( $data['gateway_ref'] ); ?>" DISABLED>

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Plano:</label>
                  <input type="text" id="input-nome" name="nome" placeholder="Nome" value="<?php echo htmlclean( $data['nome'] ); ?>" DISABLED>

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-6 col-sm-6 col-xs-6">

              <div class="form-field-default">

                  <label>Duração (em meses):</label>
                  <input type="text" name="duracao_meses" placeholder="Duração (em meses)" value="<?php echo htmlclean( $data['duracao_meses'] ); ?>" DISABLED>

              </div>

            </div>

            <div class="col-md-6 col-sm-6 col-xs-6">

              <div class="form-field-default">

                  <label>Duração (em dias):</label>
                  <input type="text" name="duracao_dias" placeholder="Duração (em dias)" value="<?php echo htmlclean( $data['duracao_dias'] ); ?>" DISABLED>

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-6 col-sm-6 col-xs-6">

              <div class="form-field-default">

                  <label>Valor total:</label>
                  <input class="maskmoney" type="text" name="valor_total" placeholder="Valor total" value="<?php echo htmlclean( dinheiro( $data['valor_total'], "BR") ); ?>" DISABLED>

              </div>

            </div>

            <div class="col-md-6 col-sm-6 col-xs-6">

              <div class="form-field-default">

                  <label>Valor mensal:</label>
                  <input class="maskmoney" type="text" name="valor_mensal" placeholder="Valor mensal" value="<?php echo htmlclean( dinheiro( $data['valor_mensal'], "BR") ); ?>" DISABLED>

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-6 col-sm-6 col-xs-6">

              <div class="form-field-default">

                  <label>Status:</label>
                  <input type="text" name="status" placeholder="Status" value="<?php echo numeric_data( "assinatura_status", $data['status'] ); ?>" DISABLED>

              </div>

            </div>

            <div class="col-md-6 col-sm-6 col-xs-6">

              <div class="form-field-default">

                  <label>Uso:</label>
                  <input type="text" name="uso" placeholder="Uso" value="<?php echo numeric_data( "assinatura_use", $data['used'] ); ?>" DISABLED>

              </div>

            </div>

          </div>


          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Habilita marketplace?</label>
                  <div class="radios">
                    <div class="spacer"></div>
                    <div class="form-field-radio">
                      <input type="radio" name="funcionalidade_marketplace" value="1" <?php if( $data['funcionalidade_marketplace'] == 1 ){ echo 'CHECKED'; }; ?>> Sim
                    </div>
                    <div class="form-field-radio">
                      <input type="radio" name="funcionalidade_marketplace" value="2" <?php if( $data['funcionalidade_marketplace'] == 2 OR !$data['funcionalidade_marketplace'] ){ echo 'CHECKED'; }; ?>> Não
                    </div>
                    <div class="clear"></div>
                  </div>

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Habilita variação de produto?</label>
                  <div class="radios">
                    <div class="spacer"></div>
                    <div class="form-field-radio">
                      <input type="radio" name="funcionalidade_variacao" value="1" <?php if( $data['funcionalidade_variacao'] == 1 ){ echo 'CHECKED'; }; ?>> Sim
                    </div>
                    <div class="form-field-radio">
                      <input type="radio" name="funcionalidade_variacao" value="2" <?php if( $data['funcionalidade_variacao'] == 2 OR !$data['funcionalidade_variacao'] ){ echo 'CHECKED'; }; ?>> Não
                    </div>
                    <div class="clear"></div>
                  </div>

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Habilita banners?</label>
                  <div class="radios">
                    <div class="spacer"></div>
                    <div class="form-field-radio">
                      <input type="radio" name="funcionalidade_banners" value="1" <?php if( $data['funcionalidade_banners'] == 1 ){ echo 'CHECKED'; }; ?>> Sim
                    </div>
                    <div class="form-field-radio">
                      <input type="radio" name="funcionalidade_banners" value="2" <?php if( $data['funcionalidade_banners'] == 2 OR !$data['funcionalidade_banners'] ){ echo 'CHECKED'; }; ?>> Não
                    </div>
                    <div class="clear"></div>
                  </div>

              </div>

            </div>

          </div>

          <?php if( $data['expiration'] ) { ?>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Expiração:</label>
                  <input class="maskdate" type="text" id="input-expiration" name="expiration" placeholder="Expiração" value="<?php echo databr_min( $data['expiration'] ); ?>">

              </div>

            </div>

          </div>

          <?php } ?>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Limite de produtos:</label>
                  <input type="number" name="limite_produtos" placeholder="Limite de produtos" value="<?php echo htmlclean( $data['limite_produtos'] ); ?>">

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

<?php if( $data['expiration'] ) { ?>

<script>

$(document).ready( function() {
          
  // Globais

  $("#the_form").validate({

      /* REGRAS DE VALIDAÇÃO DO FORMULÁRIO */

      rules:{

        expiration:{
        required: true
        }

      },
          
      /* DEFINIÇÃO DAS MENSAGENS DE ERRO */
              
      messages:{

        expiration:{
          required: "Esse campo é obrigatório"
        }

      }

    });

  });

</script>

<?php } ?>