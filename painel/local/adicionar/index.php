<?php
// CORE
include('../../../_core/_includes/config.php');
// RESTRICT
restrict_estabelecimento();
restrict_expirado();
// SEO
$seo_subtitle = "Adicionar Opções de Entrega";
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

  // Checar se formulário foi executado

  $formdata = $_POST['formdata'];

  if( $formdata ) {

    // Setar campos

    $estabelecimento = $_SESSION['estabelecimento']['id'];
    $nome = mysqli_real_escape_string( $db_con, $_POST['nome'] );
    $valor = dinheiro( mysqli_real_escape_string( $db_con, $_POST['valor'] ) );
    if( !$valor ) {
      $valor = "0.00";
    }
    $outros = '1';
    
    // Checar Erros

    $checkerrors = 0;
    $errormessage = array();

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

    // Executar registro

    if( !$checkerrors ) {

      if( new_fretec( $estabelecimento,$nome,$valor,$outros ) ) {

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
          <i class="lni lni-delivery"></i>
          <span>Adicionar Opções de Entrega</span>
        </div>

        <div class="bread-box pull-right">
          <div class="bread">
            <a href="<?php panel_url(); ?>"><i class="lni lni-home"></i></a>
            <span>/</span>
            <a href="<?php panel_url(); ?>/local">Opções de Entrega</a>
            <span>/</span>
            <a href="<?php panel_url(); ?>/local/adicionar">Adicionar</a>
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

                  <label>Nome:</label>
                  <input type="text" id="input-nome" name="nome" placeholder="Nome" value="<?php echo htmlclean( $_POST['nome'] ); ?>">

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Exemplo:</label>
                  <p>Se este local for uma mesa informe > <b>Mesa 01</b> e assim por diante.</p>
                  <p>Se este local for um quarto ou apartamento informe > <b>Apto 01</b> e assim por diante.</p>
                  <p>Você poderá inserir qualquer tipo de local.</p>
                  <input type="hidden" name="valor" value="<?php echo htmlclean( $_POST['valor'] ); ?>">

              </div>

            </div>

          </div>

          <div class="row lowpadd">

          	<div class="col-md-6 col-sm-5 col-xs-5">
          		<div class="form-field form-field-submit">
  							<a href="<?php panel_url(); ?>/frete" class="backbutton pull-left">
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
        }

      },
          
      /* DEFINIÇÃO DAS MENSAGENS DE ERRO */
              
      messages:{

        estabelecimento_id:{
          required: "Esse campo é obrigatório"
        },
        nome:{
          required: "Esse campo é obrigatório"
        }

      }

    });

  });

</script>