<?php
// CORE
include('../../../_core/_includes/config.php');
// RESTRICT
restrict('1');
// SEO
$seo_subtitle = "Editar subdomínio";
$seo_description = "";
$seo_keywords = "";
// HEADER
$system_header .= "";
include('../../_layout/head.php');
include('../../_layout/top.php');
include('../../_layout/sidebars.php');
include('../../_layout/modal.php');
global $simple_url;
?>

<?php

  // Globals

  global $numeric_data;
  $id = mysqli_real_escape_string( $db_con, $_GET['id'] );
  $edit = mysqli_query( $db_con, "SELECT * FROM subdominios WHERE id = '$id' LIMIT 1");
  $hasdata = mysqli_num_rows( $edit );
  $data = mysqli_fetch_array( $edit );

  // Checar se formulário foi executado

  $formdata = $_POST['formdata'];

  if( $formdata ) {

    // print_r($_POST);

    // Setar campos

    $subdominio = subdomain( mysqli_real_escape_string( $db_con, $_POST['subdominio'] ) );
    $tipo = mysqli_real_escape_string( $db_con, $_POST['tipo'] );
    $estabelecimento = mysqli_real_escape_string( $db_con, $_POST['estabelecimento_id'] );
    $cidade = mysqli_real_escape_string( $db_con, $_POST['cidade_id'] );
    $rel_id = "";
    if( $tipo == "1" ) {
      $rel_id = $estabelecimento;
    }
    if( $tipo == "2" ) {
      $rel_id = $cidade;
    }
    $url = mysqli_real_escape_string( $db_con, $_POST['url'] );

    // Checar Erros

    $checkerrors = 0;
    $errormessage = array();

      // -- Subdominio

      if( !$subdominio ) {
        $checkerrors++;
        $errormessage[] = "O subdominio não pode ser nulo";
      }

      // -- Tipo

      if( !$tipo ) {
        $checkerrors++;
        $errormessage[] = "O tipo não pode ser nulo";
      }

      if( $tipo == 1 ) {

        // -- Loja

        if( !$estabelecimento ) {
          $checkerrors++;
          $errormessage[] = "A estabelecimento não pode ser nula";
        }

      }

      if( $tipo == 2 ) {

        // -- Cidade

        if( !$cidade ) {
          $checkerrors++;
          $errormessage[] = "A cidade não pode ser nula";
        }

      }

      if( $tipo == 3 OR $tipo == 4 ) {

        // -- URL

        if( !$url ) {
          $checkerrors++;
          $errormessage[] = "A url não pode ser nula";
        }

      }

    // Executar registro

    if( !$checkerrors ) {

      if( edit_subdominio( $id,$subdominio,$tipo,$rel_id,$url ) ) {

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
          <i class="lni lni-domain"></i>
          <span>Editar subdomínio</span>
        </div>

        <div class="bread-box pull-right">
          <div class="bread">
            <a href="<?php admin_url(); ?>"><i class="lni lni-home"></i></a>
            <span>/</span>
            <a href="<?php admin_url(); ?>/subdominios">Subdomínios</a>
            <span>/</span>
            <a href="<?php admin_url(); ?>/subdominios/editar">Editar</a>
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

                <?php modal_alerta("Cadastro efetuado com sucesso!","sucesso"); ?>

              <?php } ?>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Subdomínio:</label>
                  <div class="row lowpadd">
                    <div class="col-md-3 col-xs-6 col-sm-6">
                      <input class="subdomain" type="text" name="subdominio" placeholder="subdominio" value="<?php echo htmlclean( $data['subdominio'] ); ?>">
                    </div>
                    <div class="col-md-9 col-xs-6 col-sm-6">
                      <input type="text" value=".<?php echo $simple_url; ?>" DISABLED>
                    </div>
                  </div>
              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Tipo:</label>
                  <div class="fake-select">
                    <i class="lni lni-chevron-down"></i>
                    <select name="tipo" id="input-tipo" onchange="campo_dependente('#input-tipo')" class="campo-dependente">
                      <option></option>
                      <?php for( $x = 0; $x < count( $numeric_data['subdominio_tipo'] ); $x++ ) { ?>
                      <option value="<?php echo $numeric_data['subdominio_tipo'][$x]['value']; ?>" <?php if( $data['tipo'] == $numeric_data['subdominio_tipo'][$x]['value'] ) { echo 'SELECTED'; }; ?>><?php echo $numeric_data['subdominio_tipo'][$x]['name']; ?></option>
                      <?php } ?>
                    </select>
                    <div class="clear"></div>
                </div>

              </div>

            </div>

          </div>

          <div class="row elemento-dependente" dependente="#input-tipo" dependente_value="1">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Estabelecimento:</label>
                  <input class="autocompleter <?php if( $data['rel_id'] ) { echo "autocomplete-selected"; } ?>" type="text" name="estabelecimento" placeholder="Loja" value="<?php echo htmlclean( data_info( "estabelecimentos",$data['rel_id'],"nome" ) ); ?>" completer_url="<?php just_url(); ?>/_core/_ajax/autocomplete_estabelecimentos.php" completer_field="estabelecimento_id">
                  <input class="fakehidden" type="text" name="estabelecimento_id" value="<?php echo $data['rel_id']; ?>"/>

              </div>

            </div>

          </div>

          <div class="row elemento-dependente" dependente="#input-tipo" dependente_value="2">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Cidade:</label>
                  <input class="autocompleter <?php if( $data['rel_id'] ) { echo "autocomplete-selected"; } ?>" type="text" name="cidade" placeholder="Cidade" value="<?php echo htmlclean( data_info( "cidades",$data['rel_id'],"nome" ) ); ?>" completer_url="<?php just_url(); ?>/_core/_ajax/autocomplete_cidades.php" completer_field="cidade_id">
                  <input class="fakehidden" type="text" name="cidade_id" value="<?php echo $data['rel_id']; ?>"/>

              </div>

            </div>

          </div>

          <div class="row elemento-dependente" dependente="#input-tipo" dependente_value="3" dependente_value_2="4">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>URL:</label>
                  <input type="text" name="url" placeholder="URL" value="<?php echo htmlclean( $data['url'] ); ?>">

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

$( "#the_form" ).change(function() {

  var form = "";
          
  // Globais
  var form = $("#the_form");
  form.validate({

    /* REGRAS DE VALIDAÇÃO DO FORMULÁRIO */

    rules:{

      subdominio:{
      required: true,
      remote: "<?php just_url(); ?>/_core/_ajax/check_subdominio_actual.php?id=<?php echo $id; ?>"
      },
      tipo:{
      required: true
      },
      estabelecimento_id:{
      required: true
      },
      cidade_id:{
      required: true
      },
      url:{
      required: true
      }

    },
        
    /* DEFINIÇÃO DAS MENSAGENS DE ERRO */
            
    messages:{

      subdominio:{
        required: "Esse campo é obrigatório",
        remote: "Subdomínio já registrado, por favor escolha outro"
      },
      tipo:{
        required: "Esse campo é obrigatório"
      },
      estabelecimento_id:{
        required: "Esse campo é obrigatório"
      },
      cidade_id:{
        required: "Esse campo é obrigatório"
      },
      url:{
        required: "Esse campo é obrigatório"
      }

    }

  });

  form.validate().settings.ignore = ":hidden";

});

$("#the_form").trigger("change");

</script>