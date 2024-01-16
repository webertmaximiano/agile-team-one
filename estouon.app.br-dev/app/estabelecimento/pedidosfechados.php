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
$seo_subtitle = $app['title']." - Pedidos Finalizados!";
$seo_description = "Pedidos finalizados pelo estabelecimento";
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
								<span>Pedidos Fechados</span>
							</div>
							<div class="bread-box">
								<div class="bread">
									<a href="<?php echo $app['url']; ?>"><i class="lni lni-home"></i></a>
									<span>/</span>
									<a href="<?php echo $app['url']; ?>/pedido.php">Pedidos Fechados</a>
								</div>
							</div>
						</div>

						<div class="col-md-12 hidden-xs hidden-sm">
							<div class="clearline"></div>
						</div>
					</div>

					<div class="obrigado">

						<?php
							$query = "SELECT * FROM pedidos WHERE whatsapp = '".$_COOKIE['celcli']."' AND rel_estabelecimentos_id = '$app_id' AND (status = '2' OR status = '3') ORDER BY id ASC";
							
							$sql = mysqli_query( $db_con, $query );
                            while ( $data = mysqli_fetch_array( $sql ) ) {
                                
                            $msgdelivery = urlencode($data['comprovante']);
                            ?>
                            
						<div class="row">

							<div class="col-md-12">

								<div class="adicionado" align="center">

									<i class="checkicon lni lni-alarm-clock"></i>
									<span class="text">O seu pedido #<?php echo $data['id']; ?> feito em <?php echo databr( $data['data_hora'] ); ?> foi finalizado.</span>
									
									<?php if($data['status'] == 2 ){ ?>
									<span class="badge badge-pendente" style="background-color:#009900">Pedido Finalizado</span>
									<?php } ?>
									<?php if($data['status'] == 3 ){ ?>
									<span class="badge badge-pendente" style="background-color:#FF3300">Pedido Cancelado</span>
									<?php } ?>
									<a target="_blank" href="https://api.whatsapp.com/send?phone=55<?php echo $_COOKIE['celcli']; ?>&text=*SEGUNDA VIA DO PEDIDO:*%0A%0A<?php echo $msgdelivery; ?>" class="botao-acao"><i class="lni lni-whatsapp"></i> <span>Ver Pedido</span></a>
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