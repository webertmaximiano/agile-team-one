<?php
// CORE
include('../../../_core/_includes/config.php');
// RESTRICT
restrict(2);
// SEO
$seo_subtitle = "Exclusão";
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

  $uid = $_SESSION['user']['id'];
  $eid = $_SESSION['estabelecimento']['id'];
  global $db_con;

  // Checar se formulário foi executado

  $formdata = $_POST['formdata'];

  if( $formdata ) {

    // Setar campos

    $pass = mysqli_real_escape_string( $db_con, $_POST['pass'] );
    $repass = mysqli_real_escape_string( $db_con, $_POST['repass'] );
    $termos = mysqli_real_escape_string( $db_con, $_POST['termos'] );

    // Checar Erros

    $checkerrors = 0;
    $errormessage = array();

      // Senha

      if( $pass != $repass ) {
        $checkerrors++;
        $errormessage[] = "As senhas não coincidem";
      }

      if( md5( $pass ) != data_info("users",$uid,"password") ) {
        $checkerrors++;
        $errormessage[] = "A senha é inválida";
      }

      // -- Termos

      if( $termos ) {
        $checkerrors++;
        $errormessage[] = "Você deve aceitar os termos de Exclusão";
      }

    // Executar registro

    if( !$checkerrors ) {

      $datetime = date('Y-m-d H:i:s');
      if( mysqli_query( $db_con, "UPDATE estabelecimentos SET excluded = '1',excluded_date = '$datetime' WHERE id = '$eid'") ) {
        session_destroy();
        header("Location: ".get_just_url()."/login");
      }

    } else {

        // header("Location: index.php?msg=erro");

    }

  }
  
?>

<div class="middle minfit bg-gray">

  <div class="container">

    <div class="row">

      <div class="col-md-12">

        <div class="title-icon pull-left">
          <i class="lni lni-close"></i>
          <span>Exclusão</span>
        </div>

        <div class="bread-box pull-right">
          <div class="bread">
            <a href="<?php panel_url(); ?>"><i class="lni lni-home"></i></a>
            <span>/</span>
            <a href="<?php panel_url(); ?>/configuracoes/exclusao">Exclusão</a>
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

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="title-line mt-0 pd-0">
                <i class="lni lni-warning"></i>
                <span>Atenção</span>
                <div class="clear"></div>
              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <span class="warning-tip warning-tip-exclusao">Ao remover o seu catálogo todas as suas informações serão perdidas.</span><br/><br/>

            </div>

          </div>

      		<div class="row">

      		  <div class="col-md-12">

      		    <div class="title-line mt-0 pd-0">
      		      <i class="lni lni-question-circle"></i>
      		      <span>Confirmação</span>
      		      <div class="clear"></div>
      		    </div>

      		  </div>

      		</div>

          <div class="row">

            <div class="col-md-6 col-sm-6 col-xs-6">

              <div class="form-field-default">

                  <label>Senha</label>
                  <input type="password" name="pass" placeholder="******">

              </div>

            </div>

            <div class="col-md-6 col-sm-6 col-xs-6">

              <div class="form-field-default">

                  <label>Redigite</label>
                  <input type="password" name="repass" placeholder="******">

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <div class="form-field-terms">
                    <input type="hidden" name="afiliado" value="<?php echo htmlclean( $_GET['afiliado'] ); ?>"/>
                    <input type="hidden" name="formdata" value="1"/>
                    <input type="radio" name="terms" value="1" <?php if( $_POST['terms'] ){ echo 'CHECKED'; }; ?>> Confirmo que desejo remover meu catálogo
                  </div>

              </div>

            </div>

          </div>

          <div class="row lowpadd">

            <div class="col-md-6 col-sm-5 col-xs-5">
              <div class="form-field form-field-submit">
                <a href="<?php panel_url(); ?>" class="backbutton pull-left">
                  <span><i class="lni lni-chevron-left"></i> Voltar</span>
                </a>
              </div>
            </div>

            <div class="col-md-6 col-sm-7 col-xs-7">
              <input type="hidden" name="formdata" value="true"/>
              <div class="form-field form-field-submit">
                <button class="pull-right">
                  <span>Remover meu catálogo <i class="lni lni-chevron-right"></i></span>
                </button>
              </div>
            </div>

          </div>

      </form>

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

  var form = $("#the_form");
  form.validate({
      focusInvalid: true,
      invalidHandler: function() {
        // alert("Existem campos obrigatórios a serem preenchidos!");
      },
      errorPlacement: function errorPlacement(error, element) { element.after(error); },
      rules:{

      	/* REGRAS DE VALIDAÇÃO DO FORMULÁRIO */
        pass: {
            minlength: 6,
            maxlength: 40
        },
        repass: {
            minlength: 6,
            maxlength: 40,
            equalTo: "input[name=pass]"
        },
        terms:{
        required: true
        }

      },
          
      /* DEFINIÇÃO DAS MENSAGENS DE ERRO */
              
      messages:{

        pass: {
            minlength: "A senha é muito curta"
        },
        repass: {
            minlength: "A senha é muito curta",
            equalTo: "As senhas não coincidem"
        },
        terms:{
          required: "Esse campo é obrigatório"
        }

      }

    });

  });

</script>