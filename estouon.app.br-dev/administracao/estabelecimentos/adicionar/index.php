<?php
// CORE
include('../../../_core/_includes/config.php');
// RESTRICT
restrict('1');
// SEO
$seo_subtitle = "Adicionar estabelecimento";
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

  // Checar se formulário foi executado

  $formdata = $_POST['formdata'];

  if( $formdata ) {

    // Setar campos

      // Afiliado

        $afiliado = mysqli_real_escape_string( $db_con, $_POST['afiliado'] );

      // Dados gerais

        $nome = mysqli_real_escape_string( $db_con, $_POST['nome'] );
        $descricao = mysqli_real_escape_string( $db_con, $_POST['descricao'] );
        $segmento = mysqli_real_escape_string( $db_con, $_POST['segmento'] );
        $estado = mysqli_real_escape_string( $db_con, $_POST['estado'] );
        $cidade = mysqli_real_escape_string( $db_con, $_POST['cidade'] );
        $subdominio = subdomain( mysqli_real_escape_string( $db_con, $_POST['subdominio'] ) );

      // Aparência

        $cor = mysqli_real_escape_string( $db_con, $_POST['cor'] );

      // Pagamento

        $pedido_minimo = dinheiro( mysqli_real_escape_string( $db_con, $_POST['pedido_minimo'] ) );
        if( !$pedido_minimo ) {
          $pedido_minimo = "0.00";
        }

        $pagamento_dinheiro = mysqli_real_escape_string( $db_con, $_POST['pagamento_dinheiro'] );

        // DEBITO
        $pagamento_cartao_debito = mysqli_real_escape_string( $db_con, $_POST['pagamento_cartao_debito'] );
        $pagamento_cartao_debito_bandeiras = mysqli_real_escape_string( $db_con, $_POST['pagamento_cartao_debito_bandeiras'] );
        if( $pagamento_cartao_debito == "2" ) {
          $pagamento_cartao_debito_bandeiras = "";
        }

        // CREDITO
        $pagamento_cartao_credito = mysqli_real_escape_string( $db_con, $_POST['pagamento_cartao_credito'] );
        $pagamento_cartao_credito_bandeiras = mysqli_real_escape_string( $db_con, $_POST['pagamento_cartao_credito_bandeiras'] );
        if( $pagamento_cartao_credito == "2" ) {
          $pagamento_cartao_credito_bandeiras = "";
        }

        // ALIMENTACAO
        $pagamento_cartao_alimentacao = mysqli_real_escape_string( $db_con, $_POST['pagamento_cartao_alimentacao'] );
        $pagamento_cartao_alimentacao_bandeiras = mysqli_real_escape_string( $db_con, $_POST['pagamento_cartao_alimentacao_bandeiras'] );
        if( $pagamento_cartao_alimentacao == "2" ) {
          $pagamento_cartao_alimentacao_bandeiras = "";
        }

        // OUTROS
        $pagamento_outros = mysqli_real_escape_string( $db_con, $_POST['pagamento_outros'] );
        $pagamento_outros_descricao = mysqli_real_escape_string( $db_con, $_POST['pagamento_outros_descricao'] );

      // Entrega

        $endereco_cep = mysqli_real_escape_string( $db_con, $_POST['endereco_cep'] );
        $endereco_numero = mysqli_real_escape_string( $db_con, $_POST['endereco_numero'] );
        $endereco_bairro = mysqli_real_escape_string( $db_con, $_POST['endereco_bairro'] );
        $endereco_rua = mysqli_real_escape_string( $db_con, $_POST['endereco_rua'] );
        $endereco_complemento = mysqli_real_escape_string( $db_con, $_POST['endereco_complemento'] );
        $endereco_referencia = mysqli_real_escape_string( $db_con, $_POST['endereco_referencia'] );

        $horario_funcionamento = mysqli_real_escape_string( $db_con, $_POST['horario_funcionamento'] );

        $entrega_retirada = mysqli_real_escape_string( $db_con, $_POST['entrega_retirada'] );
        $entrega_entrega = mysqli_real_escape_string( $db_con, $_POST['entrega_entrega'] );
        $entrega_entrega_tipo = mysqli_real_escape_string( $db_con, $_POST['entrega_entrega_tipo'] );
        
        $entrega_entrega_valor = dinheiro( mysqli_real_escape_string( $db_con, $_POST['entrega_entrega_valor'] ) );
        if( !$entrega_entrega_valor ) {
          $entrega_entrega_valor = "0.00";
        }

      // Contato

        $contato_whatsapp = mysqli_real_escape_string( $db_con, $_POST['contato_whatsapp'] );
        $contato_email = mysqli_real_escape_string( $db_con, $_POST['contato_email'] );
        $contato_instagram = mysqli_real_escape_string( $db_con, $_POST['contato_instagram'] );
        $contato_facebook = mysqli_real_escape_string( $db_con, $_POST['contato_facebook'] );
        $contato_youtube = mysqli_real_escape_string( $db_con, $_POST['contato_youtube'] );

      // Responsável

        $responsavel_nome = mysqli_real_escape_string( $db_con, $_POST['responsavel_nome'] );
        $responsavel_nascimento = mysqli_real_escape_string( $db_con, $_POST['responsavel_nascimento'] );
        $responsavel_documento_tipo = mysqli_real_escape_string( $db_con, $_POST['responsavel_documento_tipo'] );
        $responsavel_documento = clean_str( mysqli_real_escape_string( $db_con, $_POST['responsavel_documento'] ) );

      // Acesso

        $email = mysqli_real_escape_string( $db_con, $_POST['email'] );
        $pass = mysqli_real_escape_string( $db_con, $_POST['pass'] );
        $repass = mysqli_real_escape_string( $db_con, $_POST['repass'] );

      // Terms

        $terms = mysqli_real_escape_string( $db_con, $_POST['terms'] );

    // Checar Erros

    $checkerrors = 0;
    $errormessage = array();

      // Geral

        // -- Nome

          if( !$nome ) {
            $checkerrors++;
            $errormessage[] = "O nome não pode ser nulo";
          }

        // -- Descrição

          if( !$descricao ) {
            $checkerrors++;
            $errormessage[] = "A descrição não pode ser nula";
          }

        // -- Segmento

          $data_exists = "";
          $results = "";
          $results = mysqli_query( $db_con, "SELECT * FROM segmentos WHERE id = '$segmento'");
          $data_exists = mysqli_num_rows($results);
          if( !$data_exists ) {
            $checkerrors++;
            $errormessage[] = "O Segmento não é valido.";
          }

        // -- Estado

          $data_exists = "";
          $results = "";
          $results = mysqli_query( $db_con, "SELECT * FROM estados WHERE id = '$estado'");
          $data_exists = mysqli_num_rows($results);
          if( !$data_exists ) {
            $checkerrors++;
            $errormessage[] = "O estado não é valido.";
          }

        // -- Cidade

          $data_exists = "";
          $results = "";
          $results = mysqli_query( $db_con, "SELECT * FROM cidades WHERE id = '$cidade'");
          $data_exists = mysqli_num_rows($results);
          if( !$data_exists ) {
            $checkerrors++;
            $errormessage[] = "A cidade não é valida.";
          }

        // -- Subdominio

          $data_exists = "";
          $results = "";
          $results = mysqli_query( $db_con, "SELECT * FROM subdominios WHERE subdominio = '$subdominio'");
          $data_exists = mysqli_num_rows($results);
          if( $data_exists ) {
            $checkerrors++;
            $errormessage[] = "O subdominio não é valido.";
          }

      // Aparência


        // Perfil

        if( $_FILES['perfil']['name'] ) {

          $upload = upload_image( "cadastro", $_FILES['perfil'] );
          
          if ( $upload['status'] == "1" ) {
            $perfil = $upload['url'];
          } else {
            $checkerrors++;
            for( $x=0; $x < count( $upload['errors'] ); $x++ ) {
              $errormessage[] = $upload['errors'][$x];
            }
          }

        }

        // Capa

        if( $_FILES['capa']['name'] ) {

          $upload = upload_image( "cadastro", $_FILES['capa'] );
          
          if ( $upload['status'] == "1" ) {
            $capa = $upload['url'];
          } else {
            $checkerrors++;
            for( $x=0; $x < count( $upload['errors'] ); $x++ ) {
              $errormessage[] = $upload['errors'][$x];
            }
          }

        }

        // -- Cor

          if( !$cor ) {
            $checkerrors++;
            $errormessage[] = "A cor não pode ser nula";
          }

      // Pagamento

        if( $pagamento_cartao_debito == "1" ) {
          if( !$pagamento_cartao_debito_bandeiras ) {
            $checkerrors++;
            $errormessage[] = "Você deve especificar as bandeiras de cartões de débito aceitas.";
          }
        }

        if( $pagamento_cartao_credito == "1" ) {
          if( !$pagamento_cartao_credito_bandeiras ) {
            $checkerrors++;
            $errormessage[] = "Você deve especificar as bandeiras de cartões de crédito aceitas.";
          }
        }

        if( $pagamento_cartao_alimentacao == "1" ) {
          if( !$pagamento_cartao_alimentacao_bandeiras ) {
            $checkerrors++;
            $errormessage[] = "Você deve especificar as bandeiras do ticket alimentação aceitas.";
          }
        }

        if( $pagamento_outros == "1" ) {
          if( !$pagamento_outros_descricao ) {
            $checkerrors++;
            $errormessage[] = "Você deve especificar as outras formas de pagamento aceitas.";
          }
        }

        if( $pagamento_dinheiro == "2" && $pagamento_cartao == "2" && $pagamento_outros == "2" ) {
          $checkerrors++;
          $errormessage[] = "Você deve permitir ao menos uma forma de pagamento";
        }

      // Entrega

        // -- Endereço completo

        if( !$endereco_rua OR !$endereco_numero OR !$endereco_bairro ) {
          $checkerrors++;
          $errormessage[] = "Você deve preencher o endereço";
        }

        if( $entrega_retirada == "2" && $entrega_entrega == "2" ) {
          $checkerrors++;
          $errormessage[] = "Você deve fazer ou entrega ou retirada.";
        }

        if( $entrega_entrega == "1" ) {
          if( !$entrega_entrega_valor ) {
            $checkerrors++;
            $errormessage[] = "O valor de entrega não pode ser nulo";
          }
        }

      // Acesso

        // -- Responsavel

          if( !$responsavel_nome ) {
            $checkerrors++;
            $errormessage[] = "O nome do responsável não pode ser nulo";
          }

          if( !$responsavel_nascimento ) {
            $checkerrors++;
            $errormessage[] = "A data de nascimento do responsável não pode ser nula";
          }

          if( !$responsavel_documento_tipo ) {
            $checkerrors++;
            $errormessage[] = "O tipo de documento do responsável não pode ser nulo";
          }

          if( !$responsavel_documento ) {
            $checkerrors++;
            $errormessage[] = "O documento do responsável não pode ser nulo";
          }

        // -- E-mail

          $data_exists = "";
          $results = "";
          $results = mysqli_query( $db_con, "SELECT * FROM users WHERE email = '$email'");
          $data_exists = mysqli_num_rows($results);
          if( $data_exists ) {
            $checkerrors++;
            $errormessage[] = "O e-mail já está registrado no sistema, por favor tente outro ou faça login!";
          }

        // -- Senhas

        if( $pass != $repass ) {
          $checkerrors++;
          $errormessage[] = "As senhas não coincidem.";
        }

      // Terms

        // if( !$terms ) {
        //   $checkerrors++;
        //   $errormessage[] = "Você deve aceitar os termos de uso";
        // }

    // Executar registro

    if( !$checkerrors ) {

      if( new_estabelecimento( 
            $afiliado,
            $nome,
            $descricao,
            $segmento,
            $estado,
            $cidade,
            $subdominio,
            $perfil,
            $capa,
            $cor,
            $pedido_minimo,
            $pagamento_dinheiro,
            $pagamento_cartao_debito,
            $pagamento_cartao_debito_bandeiras,
            $pagamento_cartao_credito,
            $pagamento_cartao_credito_bandeiras,
            $pagamento_cartao_alimentacao,
            $pagamento_cartao_alimentacao_bandeiras,
            $pagamento_outros,
            $pagamento_outros_descricao,
            $endereco_cep,
            $endereco_numero,
            $endereco_bairro,
            $endereco_rua,
            $endereco_complemento,
            $endereco_referencia,
            $horario_funcionamento,
            $entrega_retirada,
            $entrega_entrega,
            $entrega_entrega_tipo,
            $entrega_entrega_valor,
            $contato_whatsapp,
            $contato_email,
            $contato_instagram,
            $contato_facebook,
            $contato_youtube,
            $responsavel_nome,
            $responsavel_nascimento,
            $responsavel_documento_tipo,
            $responsavel_documento,
            $email,
            $pass
       ) ) {

        header("Location: index.php?msg=sucesso");
        // echo "Cadastrou";

      } else {

        header("Location: index.php?msg=erro");
        // echo "Não cadastrou";

      }

    }

  }
  
?>

<div class="middle minfit bg-gray">

	<div class="container">

		<div class="row">

			<div class="col-md-12">

        <div class="title-icon pull-left">
          <i class="lni lni-home"></i>
          <span>Adicionar Estabelecimento</span>
        </div>

        <div class="bread-box pull-right">
          <div class="bread">
            <a href="<?php admin_url(); ?>"><i class="lni lni-home"></i></a>
            <span>/</span>
            <a href="<?php admin_url(); ?>/estabelecimentos">Estabelecimentos</a>
            <span>/</span>
            <a href="<?php admin_url(); ?>/estabelecimentos/adicionar">Adicionar</a>
          </div>
        </div>
        
			</div>

		</div>

		<!-- Content -->

		<div class="data box-white mt-16">

      <form id="the_form" class="form-wizard" method="POST" enctype="multipart/form-data">

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

          <div id="wizard-estabelecimento">

            <h3>Geral</h3>
            <section>
              <!-- Dados Gerais -->

                <div class="row">

                  <div class="col-md-12">

                    <div class="title-line mt-0 pd-0">
                      <i class="lni lni-question-circle"></i>
                      <span>Dados gerais</span>
                      <div class="clear"></div>
                    </div>

                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">

                    <div class="form-field-default">

                        <label>Nome:</label>
                        <input type="text" name="nome" placeholder="Nome do seu estabelecimento" value="<?php echo htmlclean( $_POST['nome'] ); ?>">

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

                  <div class="col-md-4">

                    <div class="form-field-default">

                        <label>Segmento:</label>
                        <div class="fake-select">
                          <i class="lni lni-chevron-down"></i>
                          <select id="input-segmento" name="segmento">

                              <option value="">Segmento</option>
                              <?php 
                              $quicksql = mysqli_query( $db_con, "SELECT * FROM segmentos ORDER BY nome ASC LIMIT 999" );
                              while( $quickdata = mysqli_fetch_array( $quicksql ) ) {
                              ?>

                                <option <?php if( $_POST['segmento'] == $quickdata['id'] ) { echo "SELECTED"; }; ?> value="<?php echo $quickdata['id']; ?>"><?php echo $quickdata['nome']; ?></option>

                              <?php } ?>

                          </select>
                          <div class="clear"></div>
                      </div>

                    </div>

                  </div>

                  <div class="col-md-4">

                    <div class="form-field-default">

                        <label>Estado:</label>
                        <div class="fake-select">
                          <i class="lni lni-chevron-down"></i>
                          <select id="input-estado" name="estado">

                              <option value="">Estado</option>
                              <?php 
                              $quicksql = mysqli_query( $db_con, "SELECT * FROM estados ORDER BY nome ASC LIMIT 999" );
                              while( $quickdata = mysqli_fetch_array( $quicksql ) ) {
                              ?>

                                <option <?php if( $_POST['estado'] == $quickdata['id'] ) { echo "SELECTED"; }; ?> value="<?php echo $quickdata['id']; ?>"><?php echo $quickdata['nome']; ?></option>

                              <?php } ?>

                          </select>
                          <div class="clear"></div>
                      </div>

                    </div>

                  </div>

                  <div class="col-md-4">

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

                        <label>URL:</label>
                        <span class="form-tip">A URL que seus clientes usarão para acessar a estabelecimento, não serão permitidos acentos, cedilha, pontos e caracteres especiais.</span>
                        <div class="row lowpadd">
                          <div class="col-md-3 col-xs-6 col-sm-6">
                            <input class="subdomain" type="text" name="subdominio" placeholder="estabelecimento" value="<?php echo subdomain( htmlclean( $_POST['subdominio'] ) ); ?>">
                          </div>
                          <div class="col-md-9 col-xs-6 col-sm-6">
                            <input type="text" id="input-nome" name="url" value=".<?php echo $simple_url; ?>" DISABLED>
                          </div>
                        </div>
                    </div>

                  </div>

                </div>

              <!-- / Dados Gerais -->
            </section>

            <h3>Aparência</h3>
            <section>
              <!-- Aparência -->

                <div class="row">

                  <div class="col-md-12">

                    <div class="title-line pd-0">
                      <i class="lni lni-construction-hammer"></i>
                      <span>Aparência</span>
                      <div class="clear"></div>
                    </div>

                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">
                  <label>Foto de perfil:</label>
                    <div class="file-preview">

                      <div class="image-preview" id="image-preview" style='background: url("") no-repeat center center; background-size: auto 102%;'>
                        <label for="image-upload" id="image-label">Enviar imagem</label>
                        <input type="file" name="perfil" id="image-upload"/>
                      </div>
                      <span class="explain">Selecione sua foto de perfil! Tamanho recomendado: 600x600px</span>

                    </div>

                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">
                  <label>Capa:</label>
                    <div class="file-preview">

                      <div class="image-preview image-preview-cover" id="image-preview2" style='background: url("") no-repeat center center; background-size: auto 102%;'>
                        <label for="image-upload2" id="image-label">Enviar imagem</label>
                        <input type="file" name="capa" id="image-upload2"/>
                      </div>
                      <span class="explain">Selecione sua capa! Tamanho recomendado: 1000x400px</span>

                    </div>

                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">

                    <div class="form-field-default">

                        <label>Cor personalizada:</label>
                        <input class="thecolorpicker" type="text" name="cor" placeholder="Cor" value="<?php echo htmlclean( $_POST['cor'] ); if( !$_POST['cor'] ){ echo '#27293e'; } ?>">

                    </div>

                  </div>

                </div>

              <!-- / Aparência -->
            </section>

            <h3>Pagamento</h3>
            <section>
              <!-- Informações de pagamento -->

                <div class="row">

                  <div class="col-md-12">

                    <div class="title-line mt-0 pd-0">
                      <i class="lni lni-coin"></i>
                      <span>Pagamento</span>
                      <div class="clear"></div>
                    </div>

                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">

                    <div class="form-field-default">

                        <label>Qual valor de pedido minímo?:</label>
                        <input class="maskmoney" type="text" name="pedido_minimo" placeholder="Valor de pedido minímo" value="<?php echo htmlclean( $_POST['pedido_minimo'] ); ?>">

                    </div>

                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">

                    <div class="form-field-default">

                        <label>O estabelecimento aceita dinheiro?</label>
                        <div class="form-field-radio">
                          <input type="radio" name="pagamento_dinheiro" value="1" <?php if( $_POST['pagamento_dinheiro'] == 1 OR !$_POST['pagamento_dinheiro'] ){ echo 'CHECKED'; }; ?>> Sim
                        </div>
                        <div class="form-field-radio">
                          <input type="radio" name="pagamento_dinheiro" value="2" <?php if( $_POST['pagamento_dinheiro'] == 2 ){ echo 'CHECKED'; }; ?>> Não
                        </div>
                        <div class="clear"></div>

                    </div>

                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">

                    <div class="form-field-default">

                        <label>O estabelecimento aceita cartão de débito?</label>
                        <div class="form-field-radio">
                          <input type="radio" name="pagamento_cartao_debito" value="1" element-show=".elemento-bandeiras-debito" <?php if( $_POST['pagamento_cartao_debito'] == 1 OR !$_POST['pagamento_cartao_debito'] ){ echo 'CHECKED'; }; ?>> Sim
                        </div>
                        <div class="form-field-radio">
                          <input type="radio" name="pagamento_cartao_debito" value="2" element-hide=".elemento-bandeiras-debito" <?php if( $_POST['pagamento_cartao_debito'] == 2 ){ echo 'CHECKED'; }; ?>> Não
                        </div>
                        <div class="clear"></div>

                    </div>

                  </div>

                </div>

                <div class="row elemento-bandeiras-debito">

                  <div class="col-md-12">

                    <div class="form-field-default">

                        <?php
                        if( $_POST['pagamento_cartao_debito_bandeiras'] ) {
                          $field_pagamento_debito_bandeiras = $_POST['pagamento_cartao_debito_bandeiras'];
                        } else {
                          $field_pagamento_debito_bandeiras = "Visa, Mastercard e Elo";
                        }
                        ?>
                        <label>Quais bandeiras de cartão de débito aceitas?:</label>
                        <input type="text" name="pagamento_cartao_debito_bandeiras" placeholder="Visa, Mastercard e Elo" value="<?php echo htmlclean( $field_pagamento_debito_bandeiras ); ?>">

                    </div>

                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">

                    <div class="form-field-default">

                        <label>O estabelecimento aceita cartão de crédito?</label>
                        <div class="form-field-radio">
                          <input type="radio" name="pagamento_cartao_credito" value="1" element-show=".elemento-bandeiras-credito" <?php if( $_POST['pagamento_cartao_credito'] == 1 OR !$_POST['pagamento_cartao_credito'] ){ echo 'CHECKED'; }; ?>> Sim
                        </div>
                        <div class="form-field-radio">
                          <input type="radio" name="pagamento_cartao_credito" value="2" element-hide=".elemento-bandeiras-credito" <?php if( $_POST['pagamento_cartao_credito'] == 2 ){ echo 'CHECKED'; }; ?>> Não
                        </div>
                        <div class="clear"></div>

                    </div>

                  </div>

                </div>

                <div class="row elemento-bandeiras-credito">

                  <div class="col-md-12">

                    <div class="form-field-default">

                        <?php
                        if( $_POST['pagamento_cartao_credito_bandeiras'] ) {
                          $field_pagamento_credito_bandeiras = $_POST['pagamento_cartao_credito_bandeiras'];
                        } else {
                          $field_pagamento_credito_bandeiras = "Visa, Mastercard e Elo";
                        }
                        ?>
                        <label>Quais bandeiras de cartão de crédito aceitas?:</label>
                        <input type="text" name="pagamento_cartao_credito_bandeiras" placeholder="Visa, Mastercard e Elo" value="<?php echo htmlclean( $field_pagamento_credito_bandeiras ); ?>">

                    </div>

                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">

                    <div class="form-field-default">

                        <label>O estabelecimento aceita ticket alimentação?</label>
                        <div class="form-field-radio">
                          <input type="radio" name="pagamento_cartao_alimentacao" value="1" element-show=".elemento-bandeiras-alimentacao" <?php if( $_POST['pagamento_cartao_alimentacao'] == 1 ){ echo 'CHECKED'; }; ?>> Sim
                        </div>
                        <div class="form-field-radio">
                          <input type="radio" name="pagamento_cartao_alimentacao" value="2" element-hide=".elemento-bandeiras-alimentacao" <?php if( $_POST['pagamento_cartao_alimentacao'] == 2 OR !$_POST['pagamento_cartao_alimentacao'] ){ echo 'CHECKED'; }; ?>> Não
                        </div>
                        <div class="clear"></div>

                    </div>

                  </div>

                </div>

                <div class="row elemento-bandeiras-alimentacao elemento-oculto">

                  <div class="col-md-12">

                    <div class="form-field-default">

                        <?php
                        if( $_POST['pagamento_cartao_alimentacao_bandeiras'] ) {
                          $field_pagamento_alimentacao_bandeiras = $_POST['pagamento_cartao_alimentacao_bandeiras'];
                        } else {
                          $field_pagamento_alimentacao_bandeiras = "Alelo e sodexo";
                        }
                        ?>
                        <label>Quais bandeiras de cartão de crédito aceitas?:</label>
                        <input type="text" name="pagamento_cartao_alimentacao_bandeiras" placeholder="Alelo e sodexo" value="<?php echo htmlclean( $field_pagamento_alimentacao_bandeiras ); ?>">

                    </div>

                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">

                    <div class="form-field-default">

                        <label>O estabelecimento aceita alguma outra forma de pagamento?</label>
                        <div class="form-field-radio">
                          <input type="radio" name="pagamento_outros" value="1" element-show=".elemento-outraforma" <?php if( $_POST['pagamento_outros'] == 1 ){ echo 'CHECKED'; }; ?>> Sim
                        </div>
                        <div class="form-field-radio">
                          <input type="radio" name="pagamento_outros" value="2" element-hide=".elemento-outraforma" <?php if( $_POST['pagamento_outros'] == 2 OR !$_POST['pagamento_outros'] ){ echo 'CHECKED'; }; ?>> Não
                        </div>
                        <div class="clear"></div>

                    </div>

                  </div>

                </div>

                <div class="row elemento-oculto elemento-outraforma">

                  <div class="col-md-12">

                    <div class="form-field-default">  

                        <?php
                        if( $_POST['pagamento_outros_descricao'] ) {
                          $field_outros_descricao = $_POST['pagamento_outros_descricao'];
                        } else {
                          $field_outros_descricao = "Cheque, transferência e permuta";
                        }
                        ?>
                        <label>Quais as outras formas de pagamento aceitas?:</label>
                        <input type="text" name="pagamento_outros_descricao" placeholder="Cheque, transferência e permuta" value="<?php echo htmlclean( $field_outros_descricao ); ?>">

                    </div>

                  </div>

                </div>

              <!-- / Informações de pagamento -->
            </section>

            <h3>Entrega</h3>
            <section>

              <!-- Informações de entrega -->

                <div class="row">

                  <div class="col-md-12">

                    <div class="title-line mt-0 pd-0">
                      <i class="lni lni-pin"></i>
                      <span>Entrega</span>
                      <div class="clear"></div>
                    </div>

                  </div>

                </div>

                <div class="elemento-endereco">

                  <div class="row">

                    <div class="col-md-6 col-sm-6 col-xs-6">

                      <div class="form-field-default">

                          <label>CEP</label>
                          <input class="maskcep" type="text" name="endereco_cep" placeholder="CEP" value="<?php echo htmlclean( $_POST['endereco_cep'] ); ?>">

                      </div>

                    </div>

                    <div class="col-md-6 col-sm-6 col-xs-6">

                      <div class="form-field-default">

                          <label>Nº</label>
                          <input type="text" name="endereco_numero" placeholder="Nº" value="<?php echo htmlclean( $_POST['endereco_numero'] ); ?>">

                      </div>

                    </div>

                  </div>

                  <div class="row">

                    <div class="col-md-6">

                      <div class="form-field-default">

                          <label>Bairro</label>
                          <input type="text" name="endereco_bairro" placeholder="Bairro" value="<?php echo htmlclean( $_POST['endereco_bairro'] ); ?>">

                      </div>

                    </div>

                    <div class="col-md-6">

                      <div class="form-field-default">

                          <label>Rua</label>
                          <input type="text" name="endereco_rua" placeholder="Rua" value="<?php echo htmlclean( $_POST['endereco_rua'] ); ?>">

                      </div>

                    </div>

                  </div>

                  <div class="row">

                    <div class="col-md-12">

                      <div class="form-field-default">

                          <label>Complemento</label>
                          <input type="text" name="endereco_complemento" placeholder="Complemento" value="<?php echo htmlclean( $_POST['endereco_complemento'] ); ?>">

                      </div>

                    </div>

                  </div>

                  <div class="row">

                    <div class="col-md-12">

                      <div class="form-field-default">

                          <label>Ponto de referência</label>
                          <input type="text" name="endereco_referencia" placeholder="Complemento" value="<?php echo htmlclean( $_POST['endereco_referencia'] ); ?>">

                      </div>

                    </div>

                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">

                    <div class="form-field-default">

                        <label>Horário de funcionamento</label>
                        <textarea rows="7" name="horario_funcionamento" placeholder="Horário de funcionamento"><?php echo htmlclean( $_POST['horario_funcionamento'] ); ?></textarea>

                    </div>

                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">

                    <div class="form-field-default">

                        <label>O estabelecimento permite retirada no local?</label>
                        <div class="form-field-radio">
                          <input type="radio" name="entrega_retirada" value="1" <?php if( $_POST['entrega_retirada'] == 1 OR !$_POST['entrega_retirada'] ){ echo 'CHECKED'; }; ?>> Sim
                        </div>
                        <div class="form-field-radio">
                          <input type="radio" name="entrega_retirada" value="2" <?php if( $_POST['entrega_retirada'] == 2 ){ echo 'CHECKED'; }; ?>> Não
                        </div>
                        <div class="clear"></div>

                    </div>

                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">

                    <div class="form-field-default">

                        <label>O estabelecimento faz entregas?</label>
                        <div class="form-field-radio">
                          <input type="radio" name="entrega_entrega" value="1" element-show=".elemento-frete" <?php if( $_POST['entrega_entrega'] == 1 OR !$_POST['entrega_entrega'] ){ echo 'CHECKED'; }; ?>> Sim
                        </div>
                        <div class="form-field-radio">
                          <input type="radio" name="entrega_entrega" value="2" element-hide=".elemento-frete" <?php if( $_POST['entrega_entrega'] == 2 ){ echo 'CHECKED'; }; ?>> Não
                        </div>
                        <div class="clear"></div>

                    </div>

                  </div>

                </div>

                <div class="elemento-frete">

                  <div class="row">

                    <div class="col-md-12">

                      <div class="form-field-default">

                          <label>Qual tipo de frete?</label>
                          <div class="form-field-radio">
                            <input type="radio" name="entrega_entrega_tipo" value="1" element-show=".elemento-frete-valor" <?php if( $_POST['entrega_entrega_tipo'] == 1 OR !$_POST['entrega_entrega_tipo'] ){ echo 'CHECKED'; }; ?>> Valor fixo
                          </div>
                          <div class="form-field-radio">
                            <input type="radio" name="entrega_entrega_tipo" value="2" element-hide=".elemento-frete-valor" <?php if( $_POST['entrega_entrega_tipo'] == 2 ){ echo 'CHECKED'; }; ?>> Sob consulta
                          </div>
                          <div class="clear"></div>

                      </div>

                    </div>

                  </div>

                  <div class="elemento-frete-valor">

                    <div class="row">

                      <div class="col-md-12">

                        <div class="form-field-default">

                            <?php
                            if( $_POST['entrega_entrega_valor'] ) {
                              $field_entrega_valor = $_POST['entrega_entrega_valor'];
                            } else {
                              $field_entrega_valor = "0.00";
                            }
                            ?>
                            <label>Qual valor de entrega?</label>
                            <input class="maskmoney" type="text" name="entrega_entrega_valor" placeholder="Valor de entrega" value="<?php echo htmlclean( $field_entrega_valor ); ?>">

                        </div>

                      </div>

                    </div>

                  </div>

                </div>

                <!-- / Informações de entrega -->

            </section>

            <h3>Contato</h3>
            <section>

              <!-- Informações de entrega -->

                <div class="row">

                  <div class="col-md-12">

                    <div class="title-line mt-0 pd-0">
                      <i class="lni lni-headphone-alt"></i>
                      <span>Contato</span>
                      <div class="clear"></div>
                    </div>

                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">

                    <div class="form-field-default">

                        <label>Whatsapp</label>
                        <span class="form-tip">Será o número no qual você receberá os pedidos</span>
                        <input class="maskcel" type="text" name="contato_whatsapp" placeholder="Whatsapp" value="<?php echo htmlclean( $_POST['contato_whatsapp'] ); ?>">

                    </div>

                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">

                    <div class="form-field-default">

                        <label>E-mail de contato</label>
                        <input type="text" name="contato_email" placeholder="E-mail" value="<?php echo htmlclean( $_POST['contato_email'] ); ?>">

                    </div>

                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">

                    <div class="form-field-default">

                        <label>Instagram</label>
                        <input type="text" name="contato_instagram" placeholder="Instagram" value="<?php echo htmlclean( $_POST['contato_instagram'] ); ?>">

                    </div>

                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">

                    <div class="form-field-default">

                        <label>Facebook</label>
                        <input type="text" name="contato_facebook" placeholder="Facebook" value="<?php echo htmlclean( $_POST['contato_facebook'] ); ?>">

                    </div>

                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">

                    <div class="form-field-default">

                        <label>Youtube</label>
                        <input type="text" name="contato_youtube" placeholder="Youtube" value="<?php echo htmlclean( $_POST['contato_youtube'] ); ?>">

                    </div>

                  </div>

                </div>

              <!-- / Informações de entrega -->

            </section>

            <h3>Usuário</h3>
            <section>

              <!-- Informações de usuario -->

                <div class="row">

                  <div class="col-md-12">

                    <div class="title-line mt-0 pd-0">
                      <i class="lni lni-user"></i>
                      <span>Responsável</span>
                      <div class="clear"></div>
                    </div>

                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">

                    <div class="form-field-default">

                        <label>Nome completo:</label>
                        <input type="text" id="input-nome" name="responsavel_nome" placeholder="Nome completo" value="<?php echo htmlclean( $_POST['responsavel_nome'] ); ?>">

                    </div>

                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">

                      <div class="form-field-default">

                        <label>Data de nascimento:</label>
                        <input type="text" class="maskdate" id="input-nascimento" name="responsavel_nascimento" placeholder="Data de nascimento" value="<?php echo htmlclean( $_POST['responsavel_nascimento'] ); ?>">

                    </div>

                  </div>

                </div>

                <div class="row">

                  <div class="col-md-6">

                    <div class="form-field-default">

                        <label>Tipo de documento:</label>
                        <div class="fake-select">
                          <i class="lni lni-chevron-down"></i>
                          <select id="input-documento_tipo" name="responsavel_documento_tipo">
                            <option></option>
                            <?php for( $x = 0; $x < count( $numeric_data['documento_tipo'] ); $x++ ) { ?>
                            <option value="<?php echo $numeric_data['documento_tipo'][$x]['value']; ?>" <?php if( $_POST['responsavel_documento_tipo'] == $numeric_data['documento_tipo'][$x]['value'] ) { echo 'SELECTED'; }; ?>><?php echo $numeric_data['documento_tipo'][$x]['name']; ?></option>
                            <?php } ?>
                          </select>
                          <div class="clear"></div>
                      </div>

                    </div>

                  </div>

                  <div class="col-md-6">

                    <div class="form-field-default">

                        <label>Nº do documento:</label>
                        <input type="text" id="input-documento" name="responsavel_documento" placeholder="Nº do documento" value="<?php echo htmlclean( $_POST['responsavel_documento'] ); ?>">

                    </div>

                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">

                    <div class="title-line mt-0 pd-0">
                      <i class="lni lni-user"></i>
                      <span>Login</span>
                      <div class="clear"></div>
                    </div>

                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">

                    <div class="form-field-default">

                        <label>E-mail</label>
                        <input type="text" name="email" placeholder="E-mail" value="<?php echo htmlclean( $_POST['email'] ); ?>">

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

<!--                         <label>Termos de uso</label>
                        <textarea rows="8" DISABLED>
                          Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                        </textarea>
                        <br/><br/> -->

                        <div class="form-field-terms">
                          <input type="hidden" name="afiliado" value="<?php echo htmlclean( $_GET['afiliado'] ); ?>"/>
                          <input type="hidden" name="formdata" value="1"/>
                          <!-- <input type="radio" name="terms" value="1" <?php if( $_POST['terms'] ){ echo 'CHECKED'; }; ?>> Eu aceito os termos de uso -->
                        </div>

                    </div>

                  </div>

                </div>

              <!-- / Informações de usuario -->

            </section>

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

  function exibe_cidades() {
    var estado = $("#input-estado").children("option:selected").val();
    $("#input-cidade").html("<option>-- Carregando cidades --</option>");
    $("#input-cidade").load("<?php just_url(); ?>/_core/_ajax/cidades.php?estado="+estado);
  }

  // Autopreenchimento de estado
  $( "#input-estado" ).change(function() {
    exibe_cidades();
  });
  <?php if( $_POST['estado'] ) { ?>
    exibe_cidades();
  <?php } ?>

</script>

<script>

$(document).ready( function() {
          
  var form = $("#the_form");
  form.validate({
      focusInvalid: true,
      invalidHandler: function() {
        alert("Existem campos obrigatórios a serem preenchidos!");
      },
      errorPlacement: function errorPlacement(error, element) { element.after(error); },
      rules:{

        nome: {
            required: true
        },
        descricao: {
            required: true
        },
        segmento: {
            required: true
        },
        estado: {
            required: true
        },
        cidade: {
            required: true
        },
        subdominio: {
            required: true,
            minlength: 2,
            maxlength: 40,
            remote: "<?php just_url(); ?>/_core/_ajax/check_subdominio.php"
        },
        perfil: {
            required: true
        },
        cor: {
            required: true
        },
        pedido_minimo: {
            required: true
        },
        endereco_rua: {
            required: true
        },
        endereco_bairro: {
            required: true
        },
        endereco_numero: {
            required: true
        },
        contato_whatsapp: {
            required: true
        },
        responsavel_nome: {
            required: true
        },
        responsavel_nascimento: {
            required: true
        },
        responsavel_documento_tipo: {
            required: true
        },
        responsavel_documento: {
            required: true
        },
        email: {
            required: true,
            minlength: 4,
            maxlength: 50,
            email: true,
            remote: "<?php just_url(); ?>/_core/_ajax/check_email.php"
        },
        pass: {
            required: true,
            minlength: 6,
            maxlength: 40
        },
        repass: {
            required: true,
            minlength: 6,
            maxlength: 40,
            equalTo: "input[name=pass]"
        },
        terms: {
            required: true
        }

      },
      messages:{

        nome: {
            required: "Esse campo é obrigatório"
        },
        descricao: {
            required: "Esse campo é obrigatório"
        },
        segmento: {
            required: "Esse campo é obrigatório"
        },
        estado: {
            required: "Esse campo é obrigatório"
        },
        cidade: {
            required: "Esse campo é obrigatório"
        },
        subdominio: {
            required: "Esse campo é obrigatório",
            remote: "Subdominio já registrado no sistema, por favor escolha outro!",
            minlength: "Mínimo de 2 caracteres",
            maxlength: "Maximo de 40 caracteres"
        },
        perfil: {
            required: "Obrigatório"
        },
        pedido_minimo: {
            required: "Esse campo é obrigatório"
        },
        endereco_rua: {
            required: "Esse campo é obrigatório"
        },
        endereco_bairro: {
            required: "Esse campo é obrigatório"
        },
        endereco_numero: {
            required: "Esse campo é obrigatório"
        },
        contato_whatsapp: {
            required: "Esse campo é obrigatório"
        },
        responsavel_nome: {
            required: "Esse campo é obrigatório"
        },
        responsavel_nascimento: {
            required: "Esse campo é obrigatório"
        },
        responsavel_documento_tipo: {
            required: "Esse campo é obrigatório"
        },
        responsavel_documento: {
            required: "Esse campo é obrigatório"
        },
        email: {
            required: "Esse campo é obrigatório",
            email: "Por favor escolha um e-mail válido!",
            remote: "E-mail já registrado no sistema, por favor escolha outro!",
            minlength: "Mínimo de 4 caracteres",
            maxlength: "Maximo de 50 caracteres"
        },
        pass: {
            required: "Esse campo é obrigatório",
            minlength: "A senha é muito curta"
        },
        repass: {
            required: "Esse campo é obrigatório",
            minlength: "A senha é muito curta",
            equalTo: "As senhas não coincidem"
        },
        terms: {
            required: "Esse campo é obrigatório"
        }

      }
  });
  $("#wizard-estabelecimento").steps({
      headerTag: "h3",
      bodyTag: "section",
      transitionEffect: "slideLeft",
      transitionEffectSpeed: 600,
      labels: {
        previous: "Anterior",
        next: "Próximo",
        finish: "Cadastrar"
      },
      onStepChanging: function (event, currentIndex, newIndex) {
          form.validate().settings.ignore = ":disabled,:hidden";
          return form.valid();
          $('#the_form').trigger("change");
      },
      onFinishing: function (event, currentIndex){
          form.validate().settings.ignore = ":disabled";
          return form.valid();
          $('#the_form').trigger("change");
      },
      onFinished: function (event, currentIndex){
          form.submit();
      }
  });

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

  $(".subdomain").keyup(function(e) {
    var re = /[^a-zA-Z0-9\-]/;
      var strreplacer = $(this).val();
      strreplacer = strreplacer.replace(re, '');
      strreplacer = strreplacer.toLowerCase();
      $(this).val( strreplacer );
  });

  $( ".elemento-oculto" ).fadeOut(0);

  $(".form-field-radio").click(function() {
    var showlement = $(this).children('input').attr("element-show");
    var hidelement = $(this).children('input').attr("element-hide");
    $( showlement ).fadeIn(100);
    $( hidelement ).fadeOut(100);
    $(this).children('input').prop('checked',true);
  });

  $('#the_form input[type=password]').val('')

  $('.thecolorpicker').spectrum({
    type: "text",
    showPalette: "false",
    showInitial: "true",
    showAlpha: "false",
    cancelText: "Cancelar",
    chooseText: "Escolher"
  });

  $(window).trigger("resize");

  // Autopreenchimento de estado
  $( "#input-estado" ).change(function() {
    var estado = $(this).children("option:selected").val();
    $("#input-cidade").html("<option>-- Carregando cidades --</option>");
    $("#input-cidade").load("<?php just_url(); ?>/_core/_ajax/cidades.php?estado="+estado+"&cidade=<?php echo htmlclean( $_POST['cidade'] ); ?>");
  });

  $( "#input-estado" ).trigger("change");

  $(".maskdate").mask("99/99/9999",{placeholder:""});
  $(".maskrg").mask("99999999-99",{placeholder:""});
  $(".maskcpf").mask("999.999.999-99",{placeholder:""});
  $(".maskcnpj").mask("99.999.999/9999-99",{placeholder:""});
  $(".maskcel").mask("(99) 99999-9999");
  $(".maskcep").mask("99999-999");
  $(".dater").mask("99/99/9999");
  $(".masktime").mask("99:99:99");
  $(".maskmoney").maskMoney({
      prefix: "R$ ",
      decimal: ",",
      thousands: "."
  });


});

</script>

<script src="<?php just_url(); ?>/_core/_cdn/cep/cep.js"></script>