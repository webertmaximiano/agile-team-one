<?php
// CORE
include('../../../_core/_includes/config.php');
// RESTRICT
restrict_estabelecimento();
restrict_expirado();
// SEO
$seo_subtitle = "Editar cupom";
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
  $eid = $_SESSION['estabelecimento']['id'];

  $id = mysqli_real_escape_string( $db_con, $_GET['id'] );
  $edit = mysqli_query( $db_con, "SELECT * FROM cupons WHERE id = '$id' AND rel_estabelecimentos_id = '$eid' LIMIT 1");
  $hasdata = mysqli_num_rows( $edit );
  $data = mysqli_fetch_array( $edit );

  // Checar se formulário foi executado

  $formdata = $_POST['formdata'];

  if( $formdata ) {

    $estabelecimento = $_SESSION['estabelecimento']['id'];
    $nome = mysqli_real_escape_string( $db_con, $_POST['nome'] );
    $descricao = mysqli_real_escape_string( $db_con, $_POST['descricao'] );
    $codigo = strtoupper( mysqli_real_escape_string( $db_con, $_POST['codigo'] ) );
    $tipo = mysqli_real_escape_string( $db_con, $_POST['tipo'] );
    $desconto_porcentagem = clean_str( mysqli_real_escape_string( $db_con, $_POST['desconto_porcentagem'] ) );
    $desconto_fixo = dinheiro( mysqli_real_escape_string( $db_con, $_POST['desconto_fixo'] ) );
    if( !$desconto_fixo ) {
      $desconto_fixo = "0.00";
    }
    $valor_maximo = dinheiro( mysqli_real_escape_string( $db_con, $_POST['valor_maximo'] ) );
    if( !$valor_maximo ) {
      $valor_maximo = "0.00";
    }
    $quantidade = clean_str( mysqli_real_escape_string( $db_con, $_POST['quantidade'] ) );
    $validade_data = datausa_min( mysqli_real_escape_string( $db_con, $_POST['validade_data'] ) );
    $validade_hora = mysqli_real_escape_string( $db_con, $_POST['validade_hora'] );
    $validade = $validade_data." ".$validade_hora;

    // Checar Erros

    $checkerrors = 0;
    $errormessage = array();

      // -- Estabelecimento

      if( !$estabelecimento ) {
        $checkerrors++;
        $errormessage[] = "O estabelecimento não pode ser nulo";
      }

      // -- codigo

      if( !$codigo ) {
        $checkerrors++;
        $errormessage[] = "O código não pode ser nulo";
      }

      // -- quantidade

      if( !$quantidade ) {
        $checkerrors++;
        $errormessage[] = "A quantidade não pode ser nula";
      }

      // -- Validade

      if( !$validade_data ) {
        $checkerrors++;
        $errormessage[] = "A validade (data) não pode ser nula";
      }

      if( !$validade_data ) {
        $checkerrors++;
        $errormessage[] = "A validade (hora) não pode ser nula";
      }


    // Executar registro

    if( !$checkerrors ) {

      if( edit_cupom( $id,$nome,$descricao,$codigo,$tipo,$desconto_porcentagem,$desconto_fixo,$valor_maximo,$quantidade,$validade ) ) {

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
          <i class="lni lni-ticket"></i>
          <span>Editar cupom</span>
        </div>

        <div class="bread-box pull-right">
          <div class="bread">
            <a href="<?php panel_url(); ?>"><i class="lni lni-home"></i></a>
            <span>/</span>
            <a href="<?php panel_url(); ?>/cupom">Cupons</a>
            <span>/</span>
            <a href="<?php panel_url(); ?>/cupom/editar?id=<?php echo $id; ?>">Editar</a>
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

              <div class="form-field-default">

                  <label>Nome:</label>
                  <input type="text" id="input-nome" name="nome" placeholder="Nome" value="<?php echo htmlclean( $data['nome'] ); ?>">

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Descrição:</label>
                  <textarea rows="6" name="descricao" placeholder="Descrição do seu cupom"><?php echo htmlclean( $data['descricao'] ); ?></textarea>

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Código:</label>
                  <input class="strupper" type="text" id="input-codigo" name="codigo" placeholder="Código" value="<?php echo htmlclean( $data['codigo'] ); ?>">

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Tipo de desconto:</label>
                  <div class="form-field-radio">
                    <input type="radio" name="tipo" value="1" element-show=".elemento-porcentagem" element-hide=".elemento-fixo" <?php if( $data['tipo'] == 1 OR !$data['tipo'] ){ echo 'CHECKED'; }; ?>> Porcentagem
                  </div>
                  <div class="form-field-radio">
                    <input type="radio" name="tipo" value="2" element-show=".elemento-fixo" element-hide=".elemento-porcentagem" <?php if( $data['tipo'] == 2  ){ echo 'CHECKED'; }; ?>> Fixo
                  </div>
                  <div class="clear"></div>

              </div>

            </div>

          </div>

          <div class="row elemento-porcentagem <?php if( $data['tipo'] != "1" ) { echo 'elemento-oculto'; }; ?>">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Desconto em porcentagem:</label>
                  <input type="number" min="0" max="100" name="desconto_porcentagem" placeholder="Desconto em porcentagem" value="<?php echo htmlclean( $data['desconto_porcentagem'] ); ?>">

              </div>

            </div>

          </div>

          <div class="row elemento-fixo <?php if( $data['tipo'] != "2" ) { echo 'elemento-oculto'; }; ?>">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Desconto fixo:</label>
                  <input class="maskmoney" type="text" name="desconto_fixo" placeholder="Desconto Fixo" value="<?php echo htmlclean( dinheiro( $data['desconto_fixo'], "BR") ); ?>">

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Valor máximo (teto do desconto):</label>
                  <input class="maskmoney" type="text" name="valor_maximo" placeholder="Valor máximo" value="<?php echo htmlclean( dinheiro( $data['valor_maximo'], "BR") ); ?>">

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Quantidade:</label>
                  <input type="number" min="1" name="quantidade" placeholder="Quantidade" value="<?php echo htmlclean( $data['quantidade'] ); ?>">

              </div>

            </div>

          </div>

          <div class="row">

            <div class="col-md-6">

              <div class="form-field-default">

                  <label>Validade (data):</label>
                  <?php
                  $validade_data = databr_min( substr($data['validade'], 0,10) );
                  ?>
                  <input class="maskdate" type="text" name="validade_data" placeholder="Validade (data)" value="<?php echo htmlclean( $validade_data ); ?>">

              </div>

            </div>

            <div class="col-md-6">

              <div class="form-field-default">

                  <?php
                  $validade_hora = substr($data['validade'], 10,6);
                  ?>
                  <label>Validade (hora):</label>
                  <input class="masktimemin" type="text" name="validade_hora" placeholder="Validade (hora)" value="<?php echo htmlclean( $validade_hora ); ?>">

              </div>

            </div>

          </div>


          <div class="row lowpadd">

            <div class="col-md-6 col-sm-5 col-xs-5">
              <div class="form-field form-field-submit">
                <a href="<?php panel_url(); ?>/cupons" class="backbutton pull-left">
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

$(document).ready( function() {
          
  // Globais

  $("#the_form").validate({

      /* REGRAS DE VALIDAÇÃO DO FORMULÁRIO */

      rules:{

        estabelecimento:{
        required: true
        },
        nome:{
        required: true
        },
        codigo:{
        required: true
        },
        quantidade:{
        required: true
        },
        desconto_porcentagem:{
        min: 1,
        required: true
        },
        desconto_fixo:{
        required: true
        },
        validade_data:{
        required: true
        },
        validade_hora:{
        required: true
        }

      },
          
      /* DEFINIÇÃO DAS MENSAGENS DE ERRO */
              
      messages:{

        estabelecimento:{
          required: "Esse campo é obrigatório"
        },
        nome:{
          required: "Esse campo é obrigatório"
        },
        codigo:{
          required: "Esse campo é obrigatório"
        },
        desconto_porcentagem:{
          min: "Esse campo é obrigatório",
          required: "Esse campo é obrigatório"
        },
        desconto_fixo:{
          min: "Esse campo é obrigatório",
          required: "Esse campo é obrigatório"
        },
        quantidade:{
          required: "Esse campo é obrigatório"
        },
        validade_data:{
          required: "Esse campo é obrigatório"
        },
        validade_hora:{
          required: "Esse campo é obrigatório"
        }

      }

    });

  });

</script>