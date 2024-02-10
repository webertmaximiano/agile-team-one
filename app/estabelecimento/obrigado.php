<?php
// CORE
include($virtualpath.'/_layout/define.php');
// APP
global $app;
is_active( $app['id'] );
$back_button = "true";
// Querys
$exibir = "8";
$app_id = $app['id'];
$pedido = mysqli_real_escape_string( $db_con, $_GET['pedido'] );
$forma_pagamento = mysqli_real_escape_string( $db_con, $_GET['forma'] );
$vpedido = mysqli_real_escape_string( $db_con, $_GET['codex'] );

//ALTERAÇÃO PARA SOMAR VALOR DO FRETE JUNTO DO TOTAL FEITA EM 25/05/22
$fid = $_SESSION['checkout']['forma_entrega'];
	$query_content = mysqli_query( $db_con, "SELECT * FROM frete WHERE id = '$fid' ORDER BY id ASC LIMIT 1" );
	$data_content = mysqli_fetch_array( $query_content );
	$frete_valor = $data_content['valor'];
	$frete = "R$ ".dinheiro( $frete_valor, "BR" );
	$frete_desc = htmlclean( $data_content['nome'] );
	if( $frete_valor >= 0.01 ) {
		$frete_desc .= " (+ R$".dinheiro( $frete_valor, "BR" ).")";
	}
	if( $fid == "retirada" ) {
		$frete_desc = "Retirar no Balcão";
	}

	$total = "R$ ".( dinheiro( $vpedido + $frete_valor - $cupom_descontado , "BR") );


$whatsapp_linkx = whatsapp_link( $pedido );

if($forma_pagamento == 6) {
    
$msg1="\n";
$msg1.="*O cliente confirmou o pagamento deste pedido via PIX*\n";
$msg1;
$text1 = urlencode($msg1);

$msg2="\n";
$msg2.="*Período de confirmação do PIX foi finalizado. Confirme com o cliente via WhatsAPP*\n";
$msg2;
$text2 = urlencode($msg2);

  $whatsapp_link = $whatsapp_linkx."".$text1."";
  
  $whatsapp_linF = $whatsapp_linkx."".$text2."";

} else {
  
  $whatsapp_link = whatsapp_link( $pedido );  
    
}


// SEO
$seo_subtitle = $app['title']." - Pedido efetuado com sucesso!";
$seo_description = "Seu pedido ".$app['title']." no ".$seo_title." foi efetuado com sucesso!";
$seo_keywords = $app['title'].", ".$seo_title;
$seo_image = thumber( $app['avatar_clean'], 400 );
// HEADER
$system_header .= "";
include($virtualpath.'/_layout/head.php');
include($virtualpath.'/_layout/top.php');
include($virtualpath.'/_layout/sidebars.php');
include($virtualpath.'/_layout/modal.php');
?>

<div class="sceneElement">

	<div class="header-interna">

		<div class="locked-bar visible-xs visible-sm">

			<div class="avatar">
				<div class="holder">
					<a href="<?php echo $app['url']; ?>">
						<img src="<?php echo $app['avatar']; ?>"/>
					</a>
				</div>	
			</div>

		</div>

		<div class="holder-interna holder-interna-nopadd holder-interna-sacola visible-xs visible-sm"></div>

	</div>

	<div class="minfit">

			<div class="middle">

				<div class="container nopaddmobile">

					<div class="row rowtitle">

						<div class="col-md-12">
							<div class="title-icon">
								<span>Já recebemos seu Pedido</span>
							</div>
							<div class="bread-box">
								<div class="bread">
									<a href="<?php echo $app['url']; ?>"><i class="lni lni-home"></i></a>
									<span>/</span>
									<a href="<?php echo $app['url']; ?>/sacola.php">Minha sacola</a>
									<span>/</span>
									<a href="<?php echo $app['url']; ?>/pedido.php">Pedido</a>
									<span>/</span>
									<a href="<?php echo $app['url']; ?>/obrigado.php?pedido=<?php echo $pedido; ?>">Efetuado</a>
								</div>
							</div>
						</div>

						<div class="col-md-12 hidden-xs hidden-sm">
							<div class="clearline"></div>
						</div>

					</div>

					<div class="obrigado">

						<div class="row">

							<div class="col-md-12" align="center">
							    
							    <?php if($forma_pagamento == 6) { ?>
							    <div class="adicionado">
							        
							        <i class="checkicon lni lni-checkmark-circle"></i>  
							        <h4>Pague agora via PIX</h4>
							        <label>Valor de R$: <?php echo number_format($vpedido + $frete_valor,2,',','.'); ?></label>
							        <!--<?=$total?>-->
							         
							        
							        <div class="row">

									<div class="col-md-7">

									  <div class="form-field-default">
									      <label>Chave Pix <?php echo $app['beneficiario_pix']; ?></label>
									      <input class="strupper" type="text" id="brcodepix" name="cupom" value="<?php echo $app['chave_pix']; ?>" style="text-align:center">

									  </div>

									</div>

									<div class="col-md-5">

									  <div class="form-field-default">
									      <label class="hidden-xs hidden-sm" style="color:#FFFFFF">.</label>
									      <span id="clip_btn" class="btn btn-primary btn-block btn-lg" style="color:#FFFFFF; font-size:14px;" onClick="copiar()">Copiar Chave</span>

									  </div>

									</div>

								</div>
							        
							    <p><strong>Após realizar o pagamento clique no botão abaixo para confirmar</strong></p>
							    
							    <a target="_blank" href="<?php echo $whatsapp_link; ?>" class="botao-acao"><i class="lni lni-whatsapp"></i> <span>Já fiz o PIX</span></a>
                                <br/>
                                <p><strong>Se for pagar somente no recebimento do pedido clique abaixo</strong></p>
                                <a target="_blank" href="<?php echo $whatsapp_linkx; ?>" class="botao-acao"><i class="lni lni-whatsapp"></i> <span>Farei ao Receber</span></a>
                                <br/>
                                <p><strong>Confirme uma das opções acima<br/>em até 5 minutos</strong></p>
                                <div class="progress">
          <div id="cont" class="progress-bar progress-bar-striped text-center active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" >
              <label id="contador"></label>%
          </div>
        </div>
                                
                                </div>
								<?php } else { ?>
								
								<div class="adicionado">

									<i class="checkicon lni lni-checkmark-circle"></i>
									<span class="text">O seu pedido foi efetuado com sucesso. Aguarde que iremos enviar para o whatsapp ou clique abaixo!</span>
									<a target="_blank" href="<?php echo $whatsapp_link; ?>" class="botao-acao"><i class="lni lni-whatsapp"></i> <span>Enviar pedido</span></a>

								</div>
								
								<?php } ?>

							</div>

						</div>

					</div>

				</div>

			</div>

	</div>

</div>

<?php 
// FOOTER
$system_footer .= "";
include($virtualpath.'/_layout/rdp.php');
include($virtualpath.'/_layout/footer.php');
?>

<?php if($forma_pagamento == 6) { ?>

<script type="text/javascript">
        var contador = 0;
        function contar()
        {

            document.getElementById('contador').innerHTML = contador;
            if (contador < 100)
           {
               contador++
               document.getElementById('cont').style.width = contador + "%";
           }
       }
       function redirecionar()
       {
           contar();
           if (contador == 100)
           {
               window.location.href="<?php echo $whatsapp_linF; ?>";
           }
       }

       setInterval(redirecionar, 6000);
       setInterval(contar, 6000);
       </script>

 

<?php } else { ?>

<script>

$(document).ready( function() {
       
	setTimeout(function(){ window.location = "<?php echo $whatsapp_link; ?>"; }, 2000);

}); 

</script>

<?php } ?>


<script>
    
    function copiar() {
  var copyText = document.getElementById("brcodepix");
  copyText.select();
  copyText.setSelectionRange(0, 99999); /* For mobile devices */
  document.execCommand("copy");
  document.getElementById("clip_btn").innerHTML='Chave Copiada';
}
    
</script>