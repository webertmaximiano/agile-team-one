<div class="sidebars">
    
	<div id="sidebarLeft">

		<div class="sidebar">

			<div class="sidebar-header">
				<i class="close-sidebar lni lni-close" onclick="$.sidr('close', 'sidrLeft');"></i>
				<div class="clear"></div>
			</div>

			<div class="sidebar-content">
			    
			    <div class="sidebar-info">

					<div class="title">
						<i class="lni lni-cart-full"></i>
						<span>Meus Pedidos</span>
					</div>

					<div class="content">
						<a href="./pedidosabertos"><i class="lni lni-shift-right"></i> Abertos</a>
					</div>
					
					<div class="content">
						<a href="./pedidosfechados"><i class="lni lni-shift-right"></i> Finalizados</a>
					</div>

				</div>

				<div class="sidebar-info">

					<div class="title">
						<i class="lni lni-alarm-clock"></i>
						<span>Funcionamento</span>
					</div>

					<div class="content">
						<?php echo $app['horario_funcionamento']; ?>
					</div>

				</div>

				<div class="sidebar-info">

					<div class="title">
						<i class="lni lni-map-marker"></i>
						<span>Endereço</span>
					</div>

					<div class="content">
						<?php echo $app['endereco_completo']; ?>
					</div>

				</div>

				<div class="sidebar-info">

					<div class="title">
						<i class="lni lni-headphone-alt"></i>
						<span>Contato</span>
					</div>

					<div class="content">
						<div class="listitem">
							<i class="lni lni-whatsapp"></i>
							<span><?php echo $app['contato_whatsapp']; ?></span>
						</div>
						<?php if( $app['contato_email'] ) { ?>
						<div class="listitem">
							<i class="lni lni-envelope"></i>
							<span><?php echo $app['contato_email']; ?></span>
						</div>
						<?php } ?>
					</div>

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

</div>