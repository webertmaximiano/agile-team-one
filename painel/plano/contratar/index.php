<?php
// CORE
include "../../../_core/_includes/config.php";
// RESTRICT
restrict_estabelecimento();
// SEO
global $seo_title;
global $just_url;
$seo_subtitle = "Plano";
$seo_description = "";
$seo_keywords = "";
// HEADER
$system_header .= "";
include "../../_layout/head.php";
include "../../_layout/top.php";
include "../../_layout/sidebars.php";
include "../../_layout/modal.php";
?>

<?php
// MERCADO PAGO
global $mp_sandbox;
global $mp_public_key;
global $mp_acess_token;
global $mp_client_id;
global $mp_client_secret;
global $external_token;

$preference_id = '';

require_once "../../../vendor/autoload.php";

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
?>

<?php
// Globals $has_voucher

global $numeric_data;
global $gallery_max_files;
$has_voucher = '';


//se in informar um voucher isset($_GET["voucher"])
$codigo_voucher = isset($_GET["voucher"]);
if ($codigo_voucher ){
  
  //verifica e trata o codigo enviado evitando sql inject
  $voucher = mysqli_real_escape_string($db_con, $_GET["voucher"] );
  //resultado da consulta no Banco pelo voucher 
  $voucher_query = mysqli_query(
      $db_con,
      "SELECT * FROM vouchers WHERE codigo = '$voucher' AND status = '1' LIMIT 1"
  );

  // define se exite ou nao o voucher se retornar uma linha 
  $has_voucher = mysqli_num_rows($voucher_query) > 0;

  //Dados do Voucher
  $data_voucher = mysqli_fetch_array($voucher_query);
  
  //se existi pega o ID do plano do voucher informado
  if ($has_voucher) {
    $id = $data_voucher["rel_planos_id"];
  } else {
      $id = mysqli_real_escape_string($db_con, isset($_GET["plano"]));
  }

  //pega o ID do estabelecimento logado
  $eid = $_SESSION["estabelecimento"]["id"];

  //acao de editar o estabelecimento
  $edit = mysqli_query(
    $db_con,
    "SELECT * FROM planos WHERE id = '$id' AND status = '1' LIMIT 1"
  );

  $hasdata = mysqli_num_rows($edit);
  $data = mysqli_fetch_array($edit);
  //print("<pre>".print_r($data)."</pre>");

}

// Checar se formulário foi executado

$formdata = isset($_POST["formdata"]);

if ($formdata) {
    // Setar campos
    $termos = mysqli_real_escape_string($db_con, isset($_POST["termos"]));

    // Checar Erros
    $checkerrors = 0;
    $errormessage = [];

    // -- Compravel
    if ($data["status"] != "1") {
        $checkerrors++;
        $errormessage[] = "Ação inválida";
    }

    // -- Termos
    if (($termos)) {
        $checkerrors++;
        $errormessage[] = "Você deve aceitar os termos";
    }

    // Executar registro
    if (!$checkerrors) {
       //se tiver um voucher aplica como pagamento
        if ($has_voucher) {
            if (aplicar_voucher($eid, $voucher)) {
                atualiza_estabelecimento(
                    $_SESSION["estabelecimento"]["id"],
                    "offline"
                );

                header("Location: ../index.php?msg=aplicado");
            } else {
                header("Location: ../index.php?msg=naoaplicado");
            }
           // se nao tiver voucher executa o mercado pago 
        } else {
            $eid = $_SESSION["estabelecimento"]["id"];
            $define_query = mysqli_query(
                $db_con,
                "SELECT email FROM estabelecimentos WHERE id = '$eid' LIMIT 1"
            );
            $define_data = mysqli_fetch_array($define_query);
            $email_cliente = $define_data["email"];

            $transaction_ref =
                "REF-" .
                $_SESSION["user"]["id"] .
                "-" .
                date("dmYHis") .
                "-" .
                random_key(4);

            // Executar compra
            $assinatura_id = $data["id"];
            $assinatura_nome = $data["nome"] . " - " . $seo_title;
            $assinatura_valor = $data["valor_total"];
            $assinatura_parcelas = intval($data["duracao_meses"]);

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.mercadopago.com/checkout/preferences",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode([
                    "payer" => [
                      "email" => $email_cliente,
                    ],
                    "back_urls" => [
                        "success" =>
                            get_just_url() . "/painel/plano?msg=obrigado",
                        "pending" =>
                            get_just_url() . "/painel/plano?msg=obrigado",
                        "failure" => get_just_url() . "/painel/plano?msg=erro",
                    ],
                    "external_reference" => $transaction_ref,
                    "notification_url" =>
                        get_just_url() .
                        "/postback.php?token=" .
                        $external_token,
                    "auto_return" => "approved",
                    "items" => [
                        [
                            "id" => $assinatura_id,
                            "title" => $assinatura_nome,
                            //"description" => "Dummy description",
                            //"picture_url" => "http://www.myapp.com/myimage.jpg",
                            //"category_id" => "car_electronics",
                            "quantity" => 1,
                            "currency_id" => "BRL",
                            "unit_price" => floatval($assinatura_valor),
                        ],
                    ],
                    "payment_methods" => [
                        "excluded_payment_methods" => [],
                        "excluded_payment_types" => [["id" => "ticket"]],
                        "installments" => $assinatura_parcelas,
                    ],
                ]),
                CURLOPT_HTTPHEADER => [
                    // Headers da requisição
                    "Content-Type: application/json",
                    "Authorization: Bearer " . $mp_acess_token,
                ],
            ]);
            $response = curl_exec($curl);
             //var_dump($response);
            curl_close($curl);

            $obj = json_decode($response);

            if (isset($obj->id)) {
                if ($obj->id != null) {
                 
                  // Setar gateway
                  //Numero do Pedido
                  $gateway_ref = $obj->external_reference;
                  //numero da referencia criada
                  $gateway_transaction = $obj->id;
                  
                  //Link de Pagamento de acordo com a configuracao teste ou producao
                  if( $mp_sandbox == true ) {
                    $gateway_link = $obj->sandbox_init_point;
                  } else {
                    $gateway_link = $obj->init_point;
                  }

                  //ver retorno
                  //print("<pre>".print_r($obj,true)."</pre>");

                  if( $gateway_link ) {
                    
                    if( contratar_plano( $eid, $id, $gateway_transaction,$gateway_ref,$gateway_link ) ) {
          
                      unset( $_POST );
                      header("Location: ".$gateway_link);
          
                    } else {
          
                      header("Location: ../index.php?msg=erro&plano=".$id);
          
                    }
          
                  } else {
          
                    header("Location: ../index.php?msg=erro&plano=".$id);
          
                  }          

                }
            }
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
          <span>Plano</span>
        </div>

        <div class="bread-box pull-right">
          <div class="bread">
            <a href="<?php panel_url(); ?>"><i class="lni lni-home"></i></a>
            <span>/</span>
            <a href="<?php panel_url(); ?>/plano">Planos</a>
            <span>/</span>
            <a href="<?php panel_url(); ?>/plano/contratar?id=<?php echo isset($id); ?>&voucher=<?php echo isset($voucher); ?>">Plano</a>
          </div>
        </div>
        
      </div>

    </div>

    <!-- Content -->

    <div class="data box-white mt-16">

      <?php if (isset($hasdata)) { ?>

      <form id="the_form" class="form-default" method="POST" enctype="multipart/form-data">

          <div class="row">

            <div class="col-md-12">

              <?php if (isset($checkerrors)) {
                  list_errors();
              } ?>

              <?php if (isset($_GET["msg"] )== "erro") { ?>

                <?php modal_alerta(
                    "Erro, tente novamente mais tarde!",
                    "erro"
                ); ?>

              <?php } ?>

              <?php if (isset($_GET["msg"]) == "sucesso") { ?>

                <?php modal_alerta("Editado com sucesso!", "sucesso"); ?>

              <?php } ?>

            </div>

          </div>

      		<div class="row">

      		  <div class="col-md-12">

      		    <div class="title-line mt-0 pd-0">
      		      <i class="lni lni-question-circle"></i>
      		      <span>Plano escolhido</span>
      		      <div class="clear"></div>
      		    </div>

      		  </div>

      		</div>

          <div class="row">

            <div class="col-md-12">

      				<div class="plano plano-interna plano-compra">
      					<div class="row">
      						<div class="col-md-12">
      							<div class="cover">
<!--       								<div class="foto">
      									<img src="<?php echo imager($data["destaque"]); ?>"/>
      								</div> -->
      								<span class="titulo"><?php echo $data["nome"]; ?></span>
      								<div class="desc <?php if ($has_voucher) {
                  echo "noborderbottom";
              } ?>">
      									<?php echo nl2br(bbzap($data["descricao"])); ?>
      								</div>
                      <?php if (!$has_voucher) { ?>
      								<div class="valor">
      									<span class="parcela"><?php echo $data["duracao_meses"]; ?>x de</span>
      									<span class="mensal">R$ <?php echo dinheiro(
                   $data["valor_mensal"],
                   "BR"
               ); ?> por mês</span>
      									<span class="total">sem juros ou R$ <?php echo dinheiro(
                   $data["valor_total"],
                   "BR"
               ); ?> á vista</span>
      								</div>
                      <?php } ?>
      							</div>
      						</div>
      					</div>
      				</div>

            </div>

          </div>

      		<div class="row">


      		  <div class="col-md-12">

      		    <div class="title-line mt-0 pd-0">
      		      <i class="lni lni-question-circle"></i>
      		      <span>Termos de <?php if ($has_voucher) {
                  echo "adesão";
              } else {
                  echo "compra";
              } ?></span>
      		      <div class="clear"></div>
      		    </div>

      		  </div>

      		</div>

          <div class="row">

            <div class="col-md-12">

              <div class="form-field-default">

                  <label>Termos de uso</label>
                  <textarea rows="6" DISABLED>
                  	<?php echo $data["termos"]; ?>
                  </textarea>
                  <br/><br/>

                  <div class="form-field-terms">
                    <input type="hidden" name="afiliado" value="<?php echo htmlclean(
                        isset($_GET["afiliado"])
                    ); ?>"/>
                    <input type="hidden" name="formdata" value="1"/>
                    <input type="radio" name="terms" value="1" <?php if (
                        isset($_POST["terms"])
                    ) {
                        echo "CHECKED";
                    } ?>> Eu aceito os termos de <?php if ($has_voucher) {
                        echo "adesão";
                    } else {
                        echo "compra";
                    } ?>
                  </div>

              </div>

            </div>

          </div>

          <div class="row lowpadd">

            <div class="col-md-6 col-sm-5 col-xs-5">
              <div class="form-field form-field-submit">
                <a href="<?php panel_url(); ?>/plano/listar" class="backbutton pull-left">
                  <span><i class="lni lni-chevron-left"></i> Voltar</span>
                </a>
              </div>
            </div>

            <div class="col-md-6 col-sm-7 col-xs-7">
              <input type="hidden" name="formdata" value="true"/>
              <div class="form-field form-field-submit">
                <button class="pull-right">
                  <span><?php if ($has_voucher) {
                      echo "Aderir";
                  } else {
                      echo "Contratar";
                  } ?> <i class="lni lni-chevron-right"></i></span>
                </button>
              </div>
            </div>

          </div>

    </form>

      <?php } else { ?>

        <span class="nulled nulled-edit color-red">Erro, inválido ou não encontrado!</span>

      <?php } ?>

    </div>
    <!-- Se obter uma preference id renderiza o checkout pro
    <?php //if ($preference_id) { ?>
      <div id="wallet_container">
    <?php //} ?>
    <script>
      const mp = new MercadoPago($mp_public_key, {
        locale: 'pt-BR'
      });

      mp.bricks().create("wallet", "wallet_container", {
        initialization: {
            preferenceId: $preference_id,
        },
      });
  </script>
  -->
    <!-- / Content -->

  </div>

</div>

<div class="just-ajax"></div>

<?php
// FOOTER
$system_footer .= "";
include "../../_layout/rdp.php";
include "../../_layout/footer.php";
?>


<script>
  const mp = new MercadoPago($mp_public_key);
</script>

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

          terms:{
          required: true
          }

        },
            
        /* DEFINIÇÃO DAS MENSAGENS DE ERRO */
                
        messages:{

          terms:{
            required: "Esse campo é obrigatório"
          }

        }

      });

    });

</script>