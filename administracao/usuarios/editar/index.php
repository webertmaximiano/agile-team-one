<?php
// CORE
include('../../../_core/_includes/config.php');
// RESTRICT
restrict('1');
// SEO
$seo_subtitle = "Editar usuário";
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
  $id = mysqli_real_escape_string( $db_con, $_GET['id'] );
  $edit = mysqli_query( $db_con, "SELECT * FROM users WHERE id = '$id' LIMIT 1");
  $hasdata = mysqli_num_rows( $edit );
  $data = mysqli_fetch_array( $edit );
  $edit2 = mysqli_query( $db_con, "SELECT * FROM users_data WHERE rel_users_id = '$id' LIMIT 1");
  $data2 = mysqli_fetch_array( $edit2 );

  // Checar se formulário foi executado

  $formdata = $_POST['formdata'];

  if( $formdata ) {

    // Setar campos

    $nome = mysqli_real_escape_string( $db_con, $_POST['nome'] );
    $nascimento = mysqli_real_escape_string( $db_con, $_POST['nascimento'] );
    $documento_tipo = mysqli_real_escape_string( $db_con, $_POST['documento_tipo'] );
    $documento = clean_str( mysqli_real_escape_string( $db_con, $_POST['documento'] ) );
    $estado = mysqli_real_escape_string( $db_con, $_POST['estado'] );
    $cidade = mysqli_real_escape_string( $db_con, $_POST['cidade'] );
    $telefone = clean_str( mysqli_real_escape_string( $db_con, $_POST['telefone'] ) );
    $email = mysqli_real_escape_string( $db_con, $_POST['email'] );
    $pass = mysqli_real_escape_string( $db_con, $_POST['pass'] );
    $repass = mysqli_real_escape_string( $db_con, $_POST['repass'] );
    $level = "1";

    // Checar Erros

    $checkerrors = 0;
    $errormessage = array();

      // -- Nome

      if( !$nome ) {
        $checkerrors++;
        $errormessage[] = "O nome não pode ser nulo";
      }

      // -- E-mail

      if( $email != $_SESSION['email'] ) {
        $results = mysqli_query( $db_con, "SELECT * FROM users WHERE email = '$email' AND users_id != '$id'");
        $email_exists = mysqli_num_rows($results);
        if( $email_exists ) {
          $checkerrors++;
          $errormessage[] = "O endereço de e-mail já esta registrado.";
        }
      }

      // -- Senhas

      if( $pass != $repass ) {
        $checkerrors++;
        $errormessage[] = "As senhas não coincidem.";
      }

    // Executar registro

    if( !$checkerrors ) {

      if( edit_user( $id,$level,$nome,$nascimento,$documento_tipo,$documento,$estado,$cidade,$telefone,$email,$pass ) ) {

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
          <i class="lni lni-user"></i>
          <span>Editar Usuário</span>
        </div>

        <div class="bread-box pull-right">
          <div class="bread">
            <a href="<?php admin_url(); ?>"><i class="lni lni-home"></i></a>
            <span>/</span>
            <a href="<?php admin_url(); ?>/usuarios">Usuários</a>
            <span>/</span>
            <a href="<?php admin_url(); ?>/usuarios/editar?id=<?php echo $id; ?>">Editar</a>
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

                <?php modal_alerta("Alterado com sucesso!","sucesso"); ?>

              <?php } ?>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="title-line mt-0 pd-0">
                <i class="lni lni-user"></i>
                <span>Dados gerais</span>
                <div class="clear"></div>
              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Nome completo:</label>
                  <input type="text" id="input-nome" name="nome" placeholder="Nome completo" value="<?php echo htmlclean( $data['nome'] ); ?>">

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

                <div class="form-field-default">

                  <label>Data de nascimento:</label>
                  <input type="text" class="maskdate" id="input-nascimento" name="nascimento" placeholder="Data de nascimento" value="<?php echo htmlclean( $data2['nascimento'] ); ?>">

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-6">

              <div class="form-field-default">

                  <label>Tipo de documento:</label>
                  <div class="fake-select">
                    <i class="lni lni-chevron-down"></i>
                    <select id="input-documento_tipo" name="documento_tipo">
                      <option></option>
                      <?php for( $x = 0; $x < count( $numeric_data['documento_tipo'] ); $x++ ) { ?>
                      <option value="<?php echo $numeric_data['documento_tipo'][$x]['value']; ?>" <?php if( $data2['documento_tipo'] == $numeric_data['documento_tipo'][$x]['value'] ) { echo 'SELECTED'; }; ?>><?php echo $numeric_data['documento_tipo'][$x]['name']; ?></option>
                      <?php } ?>
                    </select>
                    <div class="clear"></div>
                </div>

              </div>

            </div>

            <div class="col-md-6">

              <div class="form-field-default">

                  <label>Nº do documento:</label>
                  <input type="text" id="input-documento" name="documento" placeholder="Nº do documento" value="<?php echo htmlclean( $data2['documento'] ); ?>">

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-6">

              <div class="form-field-default">

                  <label>Estado:</label>
                  <div class="fake-select">
                    <i class="lni lni-chevron-down"></i>
                    <select id="input-estado" name="estado">

                        <option value="">Estado</option>
                        <?php 
                        $sql = mysqli_query( $db_con, "SELECT * FROM estados ORDER BY nome ASC LIMIT 999" );
                        while( $quickdata = mysqli_fetch_array( $sql ) ) {
                        ?>

                          <option <?php if( $data2['estado'] == $quickdata['id'] ) { echo "SELECTED"; }; ?> value="<?php echo $quickdata['id']; ?>"><?php echo $quickdata['nome']; ?></option>

                        <?php } ?>

                    </select>
                    <div class="clear"></div>
                </div>

              </div>

            </div>

            <div class="col-md-6">

              <div class="form-field-default">

                  <label>Cidade:</label>
                  <div class="fake-select">
                    <i class="lni lni-chevron-down"></i>
                    <select id="input-cidade" name="cidade">

                        <option value="">Cidade</option>

                    </select>
                    <div class="clear"></div>
                </div>

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Celular:</label>
                  <input type="text" class="maskcel" id="input-telefone" name="telefone" placeholder="Telefone" value="<?php echo htmlclean( $data2['telefone'] ); ?>">

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="title-line">
                <i class="lni lni-lock"></i>
                <span>Dados de acesso</span>
                <div class="clear"></div>
              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>E-mail:</label>
                  <input type="text" id="input-email" name="email" placeholder="E-mail" value="<?php echo htmlclean( $data['email'] ); ?>">

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-6">

              <div class="form-field-default">

                  <label>Senha:</label>
                  <input type="password" id="input-pass" name="pass" placeholder="Senha">

              </div>

            </div>

            <div class="col-md-6">

              <div class="form-field-default">

                  <label>Redigite a senha:</label>
                  <input type="password" id="input-repass" name="repass" placeholder="Redigite sua senha">

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

  function exibe_cidades() {
    var estado = $("#input-estado").children("option:selected").val();
    $("#input-cidade").html("<option>-- Carregando cidades --</option>");
    $("#input-cidade").load("<?php just_url(); ?>/_core/_ajax/cidades.php?cidade=<?php echo $data2['cidade']; ?>&estado="+estado);
  }

  // Autopreenchimento de estado
  $( "#input-estado" ).change(function() {
    exibe_cidades();
  });
  <?php if( $data2['estado'] ) { ?>
    exibe_cidades();
  <?php } ?>

</script>

<script>

$(document).ready( function() {
          
  // Globais

  $("#the_form").validate({

      /* REGRAS DE VALIDAÇÃO DO FORMULÁRIO */

      rules:{

        nome:{
        required: true
        },

        email:{
        required: true,
        minlength: 5,
        maxlength: 100,
        email: true,
          remote: "<?php just_url(); ?>/_core/_ajax/check_email_actual.php?id=<?php echo $id; ?>"
        },

        pass:{
        },

        repass:{
        equalTo: "input[name=pass]"
        }

      },
          
      /* DEFINIÇÃO DAS MENSAGENS DE ERRO */
              
      messages:{

        nome:{
          required: "Esse campo é obrigatório"
        },

        email:{
          email: "Por favor escolha um e-mail válido!",
          remote: "E-mail já registrado no sistema, por favor escolha outro!",
          required: "Este campo é obrigatório!",
          minlength: "Mínimo de 5 caracteres",
          maxlength: "Maximo de 40 caracteres"
        },

        pass:{
          required: "Este campo é obrigatório!",
          minlength: "Mínimo 6 caracteres",
          maxlength: "Maximo 40 caracteres"
        },

        repass:{
          required: "Este campo é obrigatório!",
          minlength: "Mínimo 6 caracteres",
          maxlength: "Maximo 40 caracteres",
          equalTo: "Senhas não coincidem!"
        }

      }

    });

  });

</script>