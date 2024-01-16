<?php
// CORE
include($virtualpath.'/_layout/define.php');
// APP
global $app;
is_active( $app['id'] );
$back_button = "true";
// Querys
//$exibir = "8";
$app_id = $app['id'];
//$pedido = mysqli_real_escape_string( $db_con, $_GET['pedido'] );
//$whatsapp_link = whatsapp_link( $pedido );
// SEO
$seo_subtitle = $app['title']." - Pedidos em Aberto!";
$seo_description = "Pedidos aguardando confirmação";
$seo_keywords = $app['title'].", ".$seo_title;
$seo_image = thumber( $app['avatar_clean'], 400 );
// HEADER
$system_header .= "";
include($virtualpath.'/_layout/head.php');
include($virtualpath.'/_layout/top.php');
include($virtualpath.'/_layout/sidebars.php');
include($virtualpath.'/_layout/modal.php');

if(!isset($_COOKIE['celcli'])){

header("Location: ".$app['url']."");

} ?>

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
								<span>Pedidos em Aberto</span>
							</div>
							<div class="bread-box">
								<div class="bread">
									<a href="<?php echo $app['url']; ?>"><i class="lni lni-home"></i></a>
									<span>/</span>
									<a href="<?php echo $app['url']; ?>/pedido.php">Pedidos</a>
								</div>
							</div>
						</div>

						<div class="col-md-12 hidden-xs hidden-sm">
							<div class="clearline"></div>
						</div>

					</div>

					<div class="obrigado">

						<?php
							$query = "SELECT * FROM pedidos WHERE whatsapp = '".$_COOKIE['celcli']."' AND rel_estabelecimentos_id = '$app_id' AND (status = '1' OR status = '4' OR status = '5' OR status = '6') ORDER BY id DESC LIMIT 3";
							$sql = mysqli_query( $db_con, $query );
                            while ( $data = mysqli_fetch_array( $sql ) ) {
                                
                            $msgdelivery = urlencode($data['comprovante']);
                            ?>
                            
						<div class="row">

							<div class="col-md-12">

								<div class="adicionado" align="center">

									<i class="checkicon lni lni-alarm-clock"></i>
									<span class="text">O seu pedido #<?php echo $data['id']; ?> foi recebido em <?php echo databr( $data['data_hora'] ); ?>.</span>
									
									<?php if($data['status'] == 1 ){ ?>
									<span class="badge badge-pendente" style="background-color:#FF0000">Aguardando Confirmação</span>
									<a target="_blank" href="https://api.whatsapp.com/send?phone=55<?php echo $app['contato_whatsapp']; ?>&text=*ESTE É UM REENVIO DE PEDIDO PARA:*%0A%0A<?php echo $msgdelivery; ?>" class="botao-acao"><i class="lni lni-whatsapp"></i> <span>Reenviar Pedido</span></a>
									<?php } ?>
									
									<?php if($data['status'] == 4 ){ ?>
									<span class="badge badge-pendente" style="background-color:#009900">Pedido Aceito</span>
									<?php } ?>
									
									<?php if($data['status'] == 5 ){ ?>
									<span class="badge badge-pendente" style="background-color:#003399">Saiu para Entrega</span>
									<?php } ?>
									
									<?php if($data['status'] == 6 ){ ?>
									<span class="badge badge-pendente" style="background-color:#FF6600">Disponível para Retirada</span>
									<?php } ?>

									
								</div>

							</div>

						</div>
						<hr/>
						<?php } ?>

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

<script>

$(document).ready( function() {
       
	setTimeout(function(){ window.location = "pedidosabertos"; }, 20000);

});

</script>