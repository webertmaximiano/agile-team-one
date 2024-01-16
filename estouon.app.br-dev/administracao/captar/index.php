<?php
// CORE
include('../../_core/_includes/config.php');
// RESTRICT
restrict('1');
// SEO
$seo_subtitle = "Usuários";
$seo_description = "";
$seo_keywords = "";
// HEADER
$system_header .= "";
include('../_layout/head.php');
include('../_layout/top.php');
include('../_layout/sidebars.php');
include('../_layout/modal.php');
?>

<?php

global $db_con;

// $lat = $_POST['latitude'];
// $lng = $_POST['longitude'];
$radius = "10";
$key = "AIzaSyBXQ9nkYb95_z-Vp1D9pE6Yqy574q943To";
$location = $_GET['location'];
$type = $_GET['type'];
$filtered = $_GET['filtered'];
?>

<div class="middle minfit bg-gray">

	<div class="container">

		<div class="row">

			<div class="col-md-12">

				<div class="title-icon pull-left">
					<i class="lni lni-codepen"></i>
					<span>Captar</span>
				</div>

				<div class="bread-box pull-right">
					<div class="bread">
						<a href="<?php admin_url(); ?>"><i class="lni lni-home"></i></a>
						<span>/</span>
						<a href="<?php admin_url(); ?>">Captar</a>
					</div>
				</div>

			</div>

		</div>

		<!-- Filters -->

		<div class="row">

			<div class="col-md-12">

				<div class="panel-group panel-filters">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" href="#collapse-filtros">
									<span class="desc">Filtrar</span>
									<i class="lni lni-funnel"></i>
									<div class="clear"></div>
								</a>
							</h4>
						</div>
						<div id="collapse-filtros" class="panel-collapse collapse <?php if( $_GET['filtered'] ) { echo 'in'; }; ?>">
							<div class="panel-body">

								<form class="form-filters form-100" method="GET">

									<div class="row">

										<div class="col-md-4">
											<div class="form-field-default">
												<label>Cidade:</label>
												<input type="text" name="location" placeholder="Cidade" value="<?php echo htmlclean( $location ); ?>"/>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-field-default">
												<label>Tipo:</label>
												<input type="text" name="type" placeholder="Tipo" value="<?php echo htmlclean( $type ); ?>"/>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-field-default">
												<label class="hidden-xs hidden-sm"></label>
												<input type="hidden" name="filtered" value="1"/>
												<button>
													<span>Buscar</span>
													<i class="lni lni-search-alt"></i>
												</button>
											</div>
										</div>
									</div>
									<?php if( $_GET['filtered'] ) { ?>
									<div class="row">
										<div class="col-md-12">
										    <a href="<?php admin_url(); ?>/usuarios" class="limpafiltros"><i class="lni lni-close"></i> Limpar filtros</a>
										</div>
									</div>
									<?php } ?>
								</form>

							</div>
						</div>
					</div>
				</div> 

			</div>

		</div>

		<!-- / Filters -->

		<!-- Content -->

		<div class="listing">

			<div class="row">
				<div class="col-md-12">
					<span class="listing-title">Registros:</span>
				</div>
			</div>

			<div class="row">

				<div class="col-md-12">

					<?php if( $filtered == 1 ) { ?>

						<?php
						//Radar search google api url 
						// $url="https://maps.googleapis.com/maps/api/place/radarsearch/json?location=".$lat.",".$lng."&radius=".$radius."&types=".$type."&key=".$key;
						$url="https://maps.googleapis.com/maps/api/place/textsearch/json?query=".$types." ".$location."&key=".$key;

						//Php curl code
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $url);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
						curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
						$response = curl_exec($ch);
						curl_close($ch);

						$response = json_decode($response);

						print_r( $response  );

						$result = count($response->results);
						if($result == 0){
							echo "The API key has expired please enter a new key.";
						} else {

							// for($i=0;$i<$result;$i++){

							for($i=0;$i<10;$i++){

								$place_id_url="https://maps.googleapis.com/maps/api/place/details/json?placeid=".$response->results[$i]->place_id."&key=".$key;
								//Php curl code
								$ch = curl_init();
								curl_setopt($ch, CURLOPT_URL, $place_id_url);
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
								curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
								curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
								curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
								$response_detail = curl_exec($ch);
								curl_close($ch);

								$response_detail = json_decode($response_detail);

								$name = $response_detail->result->name;
								$address = $response_detail->result->formatted_address;
								$phone = $response_detail->result->formatted_phone_number;
								$lng = $response_detail->result->geometry->location->lng;
								$lat = $response_detail->result->geometry->location->lat;
								$place_id = $response_detail->result->place_id;
								$user_ratings_total = $response_detail->result->rating;
								$types = implode(",",$response_detail->result->types);
								$website = $response_detail->result->website;
						?>

						<div class="col-md-4">

							<div class="captar-local">
								<span><?php echo $name; ?></span><br/>
								<span><?php echo $address; ?></span><br/>
								<span><?php echo $phone; ?></span><br/>
								<span><?php echo $website; ?></span><br/>
								<span><?php echo $types; ?></span>
							</div>

						</div>

						<?php }} ?>

					<?php } ?>

				</div>

			</div>

		</div>

		<!-- / Content -->

	</div>

</div>

<?php 
// FOOTER
$system_footer .= "";
include('../_layout/rdp.php');
include('../_layout/footer.php');
?>