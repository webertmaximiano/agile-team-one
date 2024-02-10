<?php
// CORE
include('../../_core/_includes/config.php');
// RESTRICT
restrict('1');
// SEO
$seo_subtitle = "Ínicio";
$seo_description = "";
$seo_keywords = "";
// HEADER
$system_header .= "";
include('../_layout/head.php');
include('../_layout/top.php');
include('../_layout/sidebars.php');
include('../_layout/modal.php');

//pega estado
$queryestados = mysqli_query( $db_con, "SELECT * FROM estados WHERE id = '{$estop}'");
$dataest = mysqli_fetch_array( $queryestados );

//pega cidade
$querycidades = mysqli_query($db_con, "SELECT * FROM cidades WHERE id = '{$cidop}'");

$datacid = mysqli_fetch_array($querycidades );


?>

<div class="middle minfit bg-gray">

	<div class="container">

		<div class="row">

			<div class="col-md-12">

				<span class="welcome-message">
					<h3>
            			Administrativo 
            			<?php
                		if (isset($dataest)) {
                    		print $dataest['uf'];
                		} else {
                    		print "(Estado não encontrado)";
                		}
            			?>
       		 		</h3>

				<a href="https://wa.me/5511" target="_blank">
				<button type="button" class="btn btn-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
					<path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
				</svg>
                Chamar Suporte
                </button></a>
				
				 </span>

			</div>

		</div>

		<div class="lista-menus">

			<div class="row">
                <?php if($oper == 1) { ?>
				<div class="col-md-4">
					<div class="lista-menus-menu">
						<a class="bt" href="<?php admin_url(); ?>/usuarios">
							<i class="lni lni-radio-button"></i>
							<span>Usuários</span>
							<strong><i class="lni lni-chevron-right text-center"></i></strong>
							<div class="clear"></div>
						</a>
					</div>
				</div>
				

				<div class="col-md-4">
					<div class="lista-menus-menu">
						<a class="bt" href="<?php admin_url(); ?>/subdominios">
							<i class="lni lni-domain"></i>
							<span>Subdominios</span>
							<strong><i class="lni lni-chevron-right text-center"></i></strong>
							<div class="clear"></div>
						</a>
					</div>
				</div>
                <?php } ?>
                
                <?php if($oper == 2) { ?>
                <div class="col-md-4">
					<div class="lista-menus-menu">
						<a class="bt" href="<?php admin_url(); ?>/segmentos">
							<i class="lni lni-domain"></i>
							<span>Segmentos</span>
							<strong><i class="lni lni-chevron-right text-center"></i></strong>
							<div class="clear"></div>
						</a>
					</div>
				</div>
				<?php } ?>
                
				<div class="col-md-4">
					<div class="lista-menus-menu">
						<a class="bt" href="<?php admin_url(); ?>/estabelecimentos">
							<i class="lni lni-home"></i>
							<span>Estabelecimentos</span>
							<strong><i class="lni lni-chevron-right text-center"></i></strong>
							<div class="clear"></div>
						</a>
					</div>
				</div>
                <?php if($oper == 1) { ?>
				<div class="col-md-4">
					<div class="lista-menus-menu">
						<a class="bt" href="<?php admin_url(); ?>/assinaturas">
							<i class="lni lni-star"></i>
							<span>Assinaturas</span>
							<strong><i class="lni lni-chevron-right text-center"></i></strong>
							<div class="clear"></div>
						</a>
					</div>
				</div>
                <?php } ?>
				<div class="col-md-4">
					<div class="lista-menus-menu">
						<a class="bt" href="<?php admin_url(); ?>/vouchers">
							<i class="lni lni-ticket-alt"></i>
							<span>Vouchers</span>
							<strong><i class="lni lni-chevron-right text-center"></i></strong>
							<div class="clear"></div>
						</a>
					</div>
				</div>
                <?php if($oper == 1) { ?>
				<div class="col-md-4">
					<div class="lista-menus-menu">
						<a class="bt" href="<?php admin_url(); ?>/planos">
							<i class="lni lni-list"></i>
							<span>Planos</span>
							<strong><i class="lni lni-chevron-right text-center"></i></strong>
							<div class="clear"></div>
						</a>
					</div>
				</div>
                <?php } ?>
			</div>

		</div>

	</div>

</div>

<?php 
// FOOTER
$system_footer .= "";
include('../_layout/rdp.php');
include('../_layout/footer.php');
?>