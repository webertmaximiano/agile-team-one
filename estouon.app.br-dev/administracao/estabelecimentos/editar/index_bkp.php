<?php
// CORE
include('../../../_core/_includes/config.php');
// RESTRICT
restrict('1');
// SEO
$seo_subtitle = "Editar estabelecimento";
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

  $queryestabelecimento = mysqli_query( $db_con, "SELECT * FROM estabelecimentos WHERE id = '$id' LIMIT 1");
  $hasdataestabelecimento = mysqli_num_rows( $queryestabelecimento );
  $dataestabelecimento = mysqli_fetch_array( $queryestabelecimento );

  $uid = $dataestabelecimento['rel_users_id'];
  $queryusuario = mysqli_query( $db_con, "SELECT * FROM users WHERE id = '$uid' LIMIT 1");
  $hasdatausuario = mysqli_num_rows( $queryusuario );
  $datausuario = mysqli_fetch_array( $queryusuario );

  $querysubdominio = mysqli_query( $db_con, "SELECT * FROM subdominios WHERE rel_id = '$id' LIMIT 1");
  $hasdatasubdominio = mysqli_num_rows( $querysubdominio );
  $datasubdominio = mysqli_fetch_array( $querysubdominio );
  $sid = $datasubdominio['id'];

  // Checar se formulário foi executado

  $formdata = $_POST['formdata'];

  if( $formdata ) {

    // Setar campos

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
        $pagamento_cartao = mysqli_real_escape_string( $db_con, $_POST['pagamento_cartao'] );
        $pagamento_cartao_bandeiras = mysqli_real_escape_string( $db_con, $_POST['pagamento_cartao_bandeiras'] );
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
        $entrega_entrega_tipo = mysqli_real_escape_string( $db_con, $_POST['entrega_tipo'] );
        $entrega_entrega_valor = dinheiro( mysqli_real_escape_string( $db_con, $_POST['entrega_entrega_valor'] ) );
        if( !$entrega_entrega_valor ) {
          $entrega_entrega_valor = "0.00";
        }

      // Contato

        $contato_whatsapp = mysqli_real_escape_string( $db_con, $_POST['contato_whatsapp'] );
        $contato_instagram = mysqli_real_escape_string( $db_con, $_POST['contato_instagram'] );
        $contato_facebook = mysqli_real_escape_string( $db_con, $_POST['contato_facebook'] );
        $contato_youtube = mysqli_real_escape_string( $db_con, $_POST['contato_youtube'] );

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
            $errormessage[] = "O Segmento não é valido.";
          }

        // -- Cidade

          $data_exists = "";
          $results = "";
          $results = mysqli_query( $db_con, "SELECT * FROM cidades WHERE id = '$cidade'");
          $data_exists = mysqli_num_rows($results);
          if( !$data_exists ) {
            $checkerrors++;
            $errormessage[] = "O Segmento não é valido.";
          }

        // -- Subdominio

          if( data_info( "subdominios", $sid, "subdominio" ) != $subdominio ) {
            $data_exists = "";
            $results = "";
            $results = mysqli_query( $db_con, "SELECT * FROM subdominios WHERE subdominio = '$subdominio'");
            $data_exists = mysqli_num_rows($results);
            if( $data_exists ) {
              $checkerrors++;
              $errormessage[] = "O subdominio não é valido ou já está registrado.";
            }
          }

      // Aparência


        // Perfil

        if( $_FILES['perfil']['name'] ) {

          $upload = upload_image( $id, $_FILES['perfil'] );
          
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

          $upload = upload_image( $id, $_FILES['capa'] );
          
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

        if( $pagamento_cartao == "1" ) {
          if( !$pagamento_cartao_bandeiras ) {
            $checkerrors++;
            $errormessage[] = "Você deve especificar as bandeiras de cartões aceitas.";
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

        // -- E-mail

          if( data_info( "users", $uid, "email" ) != $email ) {
            $data_exists = "";
            $results = "";
            $results = mysqli_query( $db_con, "SELECT * FROM users WHERE email = '$email'");
            $data_exists = mysqli_num_rows($results);
            if( $data_exists ) {
              $checkerrors++;
              $errormessage[] = "O e-mail já está registrado no sistema, por favor tente outro ou faça login!";
            }
          }

        // -- Senhas

        if( $pass != $repass ) {
          $checkerrors++;
          $errormessage[] = "As senhas não coincidem.";
        }

    // Executar registro

    if( !$checkerrors ) {

      if( edit_estabelecimento( $id, $nome, $descricao, $segmento, $estado, $cidade, $subdominio, $perfil, $capa, $cor, $pedido_minimo, $pagamento_dinheiro, $pagamento_cartao, $pagamento_cartao_bandeiras, $pagamento_outros, $pagamento_outros_descricao, $endereco_cep, $endereco_numero, $endereco_bairro, $endereco_rua, $endereco_complemento, $endereco_referencia, $horario_funcionamento, $entrega_retirada, $entrega_entrega, $entrega_entrega_tipo, $entrega_entrega_valor, $contato_whatsapp, $contato_instagram, $contato_facebook, $contato_youtube, $email, $pass ) ) {

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
          <i class="lni lni-home"></i>
          <span>Editar Estabelecimento</span>
        </div>

        <div class="bread-box pull-right">
          <div class="bread">
            <a href="<?php admin_url(); ?>"><i class="lni lni-home"></i></a>
            <span>/</span>
            <a href="<?php admin_url(); ?>/estabelecimentos">Estabelecimentos</a>
            <span>/</span>
            <a href="<?php admin_url(); ?>/estabelecimentos/editar?id=<?php echo $id; ?>">Editar</a>
          </div>
        </div>
        
			</div>

		</div>

		<!-- Content -->

		<div class="data box-white mt-16">

      <?php if( $hasdataestabelecimento ) { ?>

      <form id="the_form" class="form-wizard" method="POST" enctype="multipart/form-data">

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
                        <input type="text" name="nome" placeholder="Nome do seu estabelecimento" value="<?php echo htmlclean( $dataestabelecimento['nome'] ); ?>">

                    </div>

                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">

                    <div class="form-field-default">

                        <label>Descrição:</label>
                        <textarea rows="6" name="descricao" placeholder="Descrição do seu estabelecimento"><?php echo htmlclean( $dataestabelecimento['descricao'] ); ?></textarea>

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

                                <option <?php if( $dataestabelecimento['segmento'] == $quickdata['id'] ) { echo "SELECTED"; }; ?> value="<?php echo $quickdata['id']; ?>"><?php echo $quickdata['nome']; ?></option>

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

                                <option <?php if( $dataestabelecimento['estado'] == $quickdata['id'] ) { echo "SELECTED"; }; ?> value="<?php echo $quickdata['id']; ?>"><?php echo $quickdata['nome']; ?></option>

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
                            <input class="subdomain" type="text" name="subdominio" placeholder="estabelecimento" value="<?php echo subdomain( htmlclean( $dataestabelecimento['subdominio'] ) ); ?>">
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

                      <div class="image-preview" id="image-preview" style='background: url("<?php echo thumber( $dataestabelecimento['perfil'], 200 ); ?>") no-repeat center center; background-size: auto 102%;'>
                        <label for="image-upload" id="image-label">Enviar imagem</label>
                        <input type="file" name="perfil" id="image-upload"/>
                      </div>
                      <span class="explain">Selecione sua foto de perfil clicando no campo ou arrastando o arquivo!</span>

                    </div>

                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">
                  <label>Capa:</label>
                    <div class="file-preview">

                      <div class="image-preview image-preview-cover" id="image-preview2" style='background: url("<?php echo imager( $dataestabelecimento['capa'] ); ?>") no-repeat center center; background-size: auto 102%;'>
                        <label for="image-upload2" id="image-label">Enviar imagem</label>
                        <input type="file" name="capa" id="image-upload2"/>
                      </div>
                      <span class="explain">Selecione sua capa clicando no campo ou arrastando o arquivo!</span>

                    </div>

                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">

                    <div class="form-field-default">

                        <label>Cor personalizada:</label>
                        <input class="thecolorpicker" type="text" name="cor" placeholder="Cor" value="<?php echo htmlclean( $dataestabelecimento['cor'] ); if( !$dataestabelecimento['cor'] ){ echo '#27293e'; } ?>">

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
                        <input class="maskmoney" type="text" name="pedido_minimo" placeholder="Valor de pedido minímo" value="<?php echo dinheiro( $dataestabelecimento['pedido_minimo'], "BR" ); ?>"/>

                    </div>

                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">

                    <div class="form-field-default">

                        <label>O estabelecimento aceita dinheiro?</label>
                        <div class="form-field-radio">
                          <input type="radio" name="pagamento_dinheiro" value="1" <?php if( $dataestabelecimento['pagamento_dinheiro'] == 1 OR !$dataestabelecimento['pagamento_dinheiro'] ){ echo 'CHECKED'; }; ?>> Sim
                        </div>
                        <div class="form-field-radio">
                          <input type="radio" name="pagamento_dinheiro" value="2" <?php if( $dataestabelecimento['pagamento_dinheiro'] == 2 ){ echo 'CHECKED'; }; ?>> Não
                        </div>
                        <div class="clear"></div>

                    </div>

                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">

                    <div class="form-field-default">

                        <label>O estabelecimento aceita cartão?</label>
                        <div class="form-field-radio">
                          <input type="radio" name="pagamento_cartao" value="1" element-show=".elemento-bandeiras" <?php if( $dataestabelecimento['pagamento_cartao'] == 1 OR !$dataestabelecimento['pagamento_cartao'] ){ echo 'CHECKED'; }; ?>> Sim
                        </div>
                        <div class="form-field-radio">
                          <input type="radio" name="pagamento_cartao" value="2" element-hide=".elemento-bandeiras" <?php if( $dataestabelecimento['pagamento_cartao'] == 2 ){ echo 'CHECKED'; }; ?>> Não
                        </div>
                        <div class="clear"></div>

                    </div>

                  </div>

                </div>

                <div class="row elemento-bandeiras <?php if( $dataestabelecimento['pagamento_cartao'] == 2 ) { echo "elemento-oculto"; }; ?>">

                  <div class="col-md-12">

                    <div class="form-field-default">

                        <label>Quais bandeiras de cartão aceitas?:</label>
                        <input type="text" name="pagamento_cartao_bandeiras" placeholder="Visa, Mastercard e Elo" value="<?php echo htmlclean( $dataestabelecimento['pagamento_cartao_bandeiras'] ); ?>">

                    </div>

                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">

                    <div class="form-field-default">

                        <label>O estabelecimento aceita alguma outra forma de pagamento?</label>
                        <div class="form-field-radio">
                          <input type="radio" name="pagamento_outros" value="1" element-show=".elemento-outraforma" <?php if( $dataestabelecimento['pagamento_outros'] == 1 ){ echo 'CHECKED'; }; ?>> Sim
                        </div>
                        <div class="form-field-radio">
                          <input type="radio" name="pagamento_outros" value="2" element-hide=".elemento-outraforma" <?php if( $dataestabelecimento['pagamento_outros'] == 2 OR !$dataestabelecimento['pagamento_outros'] ){ echo 'CHECKED'; }; ?>> Não
                        </div>
                        <div class="clear"></div>

                    </div>

                  </div>

                </div>

                <div class="row <?php if( $dataestabelecimento['pagamento_outros'] == 2 ) { echo "elemento-oculto"; }; ?> elemento-outraforma">

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
                        <input type="text" name="pagamento_outros_descricao" placeholder="Cheque, transferência e permuta" value="<?php echo htmlclean( $dataestabelecimento['pagamento_outros_descricao'] ); ?>">

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
                          <input type="text" name="endereco_cep" placeholder="CEP" value="<?php echo htmlclean( $dataestabelecimento['endereco_cep'] ); ?>">

                      </div>

                    </div>

                    <div class="col-md-6 col-sm-6 col-xs-6">

                      <div class="form-field-default">

                          <label>Nº</label>
                          <input type="text" name="endereco_numero" placeholder="Nº" value="<?php echo htmlclean( $dataestabelecimento['endereco_numero'] ); ?>">

                      </div>

                    </div>

                  </div>

                  <div class="row">

                    <div class="col-md-6">

                      <div class="form-field-default">

                          <label>Bairro</label>
                          <input type="text" name="endereco_bairro" placeholder="Bairro" value="<?php echo htmlclean( $dataestabelecimento['endereco_bairro'] ); ?>">

                      </div>

                    </div>

                    <div class="col-md-6">

                      <div class="form-field-default">

                          <label>Rua</label>
                          <input type="text" name="endereco_rua" placeholder="Rua" value="<?php echo htmlclean( $dataestabelecimento['endereco_rua'] ); ?>">

                      </div>

                    </div>

                  </div>

                  <div class="row">

                    <div class="col-md-12">

                      <div class="form-field-default">

                          <label>Complemento</label>
                          <input type="text" name="endereco_complemento" placeholder="Complemento" value="<?php echo htmlclean( $dataestabelecimento['endereco_complemento'] ); ?>">

                      </div>

                    </div>

                  </div>

                  <div class="row">

                    <div class="col-md-12">

                      <div class="form-field-default">

                          <label>Ponto de referência</label>
                          <input type="text" name="endereco_referencia" placeholder="Ponto de referência" value="<?php echo htmlclean( $dataestabelecimento['endereco_referencia'] ); ?>">

                      </div>

                    </div>

                  </div>

                </div>

                  <div class="row">

                    <div class="col-md-12">

                      <div class="form-field-default">

                          <label>Horário de funcionamento</label>
                          <textarea rows="7" name="horario_funcionamento" placeholder="Horário de funcionamento"><?php echo htmlclean( $dataestabelecimento['horario_funcionamento'] ); ?></textarea>

                      </div>

                    </div>

                  </div>

                <div class="row">

                  <div class="col-md-12">

                    <div class="form-field-default">

                        <label>O estabelecimento permite retirada no local?</label>
                        <div class="form-field-radio">
                          <input type="radio" name="entrega_retirada" value="1" <?php if( $dataestabelecimento['entrega_retirada'] == 1 OR !$dataestabelecimento['entrega_retirada'] ){ echo 'CHECKED'; }; ?>> Sim
                        </div>
                        <div class="form-field-radio">
                          <input type="radio" name="entrega_retirada" value="2" <?php if( $dataestabelecimento['entrega_retirada'] == 2 ){ echo 'CHECKED'; }; ?>> Não
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
                          <input type="radio" name="entrega_entrega" value="1" element-show=".elemento-frete" <?php if( $dataestabelecimento['entrega_entrega'] == 1 OR !$dataestabelecimento['entrega_entrega'] ){ echo 'CHECKED'; }; ?>> Sim
                        </div>
                        <div class="form-field-radio">
                          <input type="radio" name="entrega_entrega" value="2" element-hide=".elemento-frete" <?php if( $dataestabelecimento['entrega_entrega'] == 2 ){ echo 'CHECKED'; }; ?>> Não
                        </div>
                        <div class="clear"></div>

                    </div>

                  </div>

                </div>

                <div class="elemento-frete <?php if( $dataestabelecimento['entrega_entrega'] == 2 ) { echo "elemento-oculto"; }; ?>">

                  <div class="row">

                    <div class="col-md-12">

                      <div class="form-field-default">

                          <label>Qual tipo de frete?</label>
                          <div class="form-field-radio">
                            <input type="radio" name="entrega_tipo" value="1" element-show=".elemento-frete-valor" <?php if( $dataestabelecimento['entrega_entrega_tipo'] == 1 OR !$dataestabelecimento['entrega_entrega_tipo'] ){ echo 'CHECKED'; }; ?>> Valor fixo
                          </div>
                          <div class="form-field-radio">
                            <input type="radio" name="entrega_tipo" value="2" element-hide=".elemento-frete-valor" <?php if( $dataestabelecimento['entrega_entrega_tipo'] == 2 ){ echo 'CHECKED'; }; ?>> Sob consulta
                          </div>
                          <div class="clear"></div>

                      </div>

                    </div>

                  </div>

                  <div class="elemento-frete-valor  <?php if( $dataestabelecimento['entrega_entrega_tipo'] == 2 ) { echo "elemento-oculto"; }; ?>">

                    <div class="row">

                      <div class="col-md-12">

                        <div class="form-field-default">

                            <label>Qual valor de entrega?</label>
                            <input class="maskmoney" type="text" name="entrega_entrega_valor" placeholder="Valor de entrega" value="<?php echo dinheiro( $dataestabelecimento['entrega_entrega_valor'], "BR" ); ?>">

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
                        <input class="maskcel" type="text" name="contato_whatsapp" placeholder="Whatsapp" value="<?php echo htmlclean( $dataestabelecimento['contato_whatsapp'] ); ?>">

                    </div>

                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">

                    <div class="form-field-default">

                        <label>Instagram</label>
                        <input type="text" name="contato_instagram" placeholder="Instagram" value="<?php echo htmlclean( $dataestabelecimento['contato_instagram'] ); ?>">

                    </div>

                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">

                    <div class="form-field-default">

                        <label>Facebook</label>
                        <input type="text" name="contato_facebook" placeholder="Facebook" value="<?php echo htmlclean( $dataestabelecimento['contato_facebook'] ); ?>">

                    </div>

                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">

                    <div class="form-field-default">

                        <label>Youtube</label>
                        <input type="text" name="contato_youtube" placeholder="Youtube" value="<?php echo htmlclean( $dataestabelecimento['contato_youtube'] ); ?>">

                    </div>

                  </div>

                </div>

              <!-- / Informações de entrega -->

            </section>

            <h3>Login</h3>
            <section>

              <!-- Informações de entrega -->

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
                        <input type="text" name="email" placeholder="E-mail" value="<?php echo htmlclean( $datausuario['email'] ); ?>">

                    </div>

                  </div>

                </div>

                <div class="row">

                  <div class="col-md-6 col-sm-6 col-xs-6">

                    <div class="form-field-default">

                        <label>Senha</label>
                        <input class="clearfield" type="password" name="pass" placeholder="******">

                    </div>

                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-6">

                    <div class="form-field-default">

                        <label>Redigite</label>
                        <input class="clearfield" type="password" name="repass" placeholder="******">

                    </div>

                  </div>

                  <input type="hidden" name="formdata" value="1"/>

                </div>

              <!-- / Informações de entrega -->

            </section>

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
            remote: "<?php just_url(); ?>/_core/_ajax/check_subdominio_actual.php?id=<?php echo $sid; ?>"
        },
        cor: {
            required: true
        },
        pedido_minimo: {
            required: true
        },
        pagamento_dinheiro: {
            required: true
        },
        pagamento_cartao: {
            required: true
        },
        pagamento_cartao_bandeiras: {
            required: true
        },
        pagamento_outros: {
            required: true
        },
        pagamento_outros_descricao: {
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
        entrega_retirada: {
            required: true
        },
        entrega_entrega: {
            required: true
        },
        entrega_entrega_valor: {
            required: true
        },
        contato_whatsapp: {
            required: true
        },
        email: {
            required: true,
            minlength: 4,
            maxlength: 50,
            email: true,
            remote: "<?php just_url(); ?>/_core/_ajax/check_email_actual.php?id=<?php echo $uid; ?>"
        },
        pass: {
            minlength: 6,
            maxlength: 40
        },
        repass: {
            minlength: 6,
            maxlength: 40,
            equalTo: "input[name=pass]"
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
        pedido_minimo: {
            required: "Esse campo é obrigatório"
        },
        pagamento_dinheiro: {
            required: "Esse campo é obrigatório"
        },
        pagamento_cartao: {
            required: "Esse campo é obrigatório"
        },
        pagamento_cartao_bandeiras: {
            required: "Esse campo é obrigatório"
        },
        pagamento_outros: {
            required: "Esse campo é obrigatório"
        },
        pagamento_outros_descricao: {
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
        entrega_retirada: {
            required: "Esse campo é obrigatório"
        },
        entrega_entrega: {
            required: "Esse campo é obrigatório"
        },
        entrega_entrega_valor: {
            required: "Esse campo é obrigatório"
        },
        contato_whatsapp: {
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
            minlength: "A senha é muito curta"
        },
        repass: {
            minlength: "A senha é muito curta",
            equalTo: "As senhas não coincidem"
        }

      }
  });
  $("#wizard-estabelecimento").steps({
      headerTag: "h3",
      enableAllSteps: true,
      showFinishButtonAlways: true,
      bodyTag: "section",
      transitionEffect: "slideLeft",
      transitionEffectSpeed: 600,
      labels: {
        previous: "Anterior",
        next: "Próximo",
        finish: "Salvar"
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

  // Autopreenchimento de estado
  $( "#input-estado" ).change(function() {
    var estado = $(this).children("option:selected").val();
    $("#input-cidade").html("<option>-- Carregando cidades --</option>");
    $("#input-cidade").load("<?php just_url(); ?>/_core/_ajax/cidades.php?estado="+estado+"&cidade=<?php echo $dataestabelecimento['cidade']; ?>");
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

  $( ".clearfield" ).val("");


});

</script>