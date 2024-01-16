<div class="footer">

	<div class="footer-info">

		<div class="container">

			<div class="row">

				<div class="col-md-12">

					<span><?php echo $app['endereco_completo']; ?></span>

				</div>

			</div>

			<div class="row">

				<div class="col-md-12">

					<div class="social">
						<?php if( $app['contato_whatsapp'] ) { ?>
						<a href="https://wa.me/55<?php echo $app['contato_whatsapp']; ?>" target="_blank"><i class="lni lni-whatsapp"></i></a>
						<?php } ?>
						<?php if( $app['contato_facebook'] ) { ?>
						<a href="<?php echo linker( $app['contato_facebook'] ); ?>" target="_blank"><i class="lni lni-facebook-filled"></i></a>
						<?php } ?>
						<?php if( $app['contato_instagram'] ) { ?>
						<a href="<?php echo linker( $app['contato_instagram'] ); ?>" target="_blank"><i class="lni lni-instagram-original"></i></a>
						<?php } ?>
						<?php if( $app['contato_youtube'] ) { ?>
						<a href="<?php echo linker( $app['contato_youtube'] ); ?>" target="_blank"><i class="lni lni-youtube"></i></a>
						<?php } ?>
					</div>

				</div>

			</div>

		</div>

	</div>

	<div class="copyright">

		<div class="container">

			<div class="row">

				<div class="col-md-12">

					<a class="watermark" href="https://conheca.<?php global $simple_url; echo $simple_url; ?>" target="_blank">
						<img src="<?php echo $app['url']; ?>/_core/_cdn/img/logo.png"/>
					</a>

				</div>

			</div>

		</div>

	</div>

</div>