<?php
global $httprotocol;
global $simple_url;
$full_simple_url = $simple_url;
?>

<div class="sidebars">

	<div id="sidebarLeft">

		<div class="sidebar">

			<div class="sidebar-header">
				<i class="close-sidebar lni lni-close" onclick="$.sidr('close', 'sidrLeft');"></i>
				<div class="clear"></div>
			</div>

			<div class="sidebar-content">

				<div class="sidebar-info">

					<div class="sidebar-links">
						<a target="_blank" href="https://<?php echo $full_simple_url; ?>/comece"><i class="lni lni-rocket"></i> Comece a vender</a>
						<a target="_blank" href="https://conheca.<?php echo $full_simple_url; ?>"><i class="lni lni-heart"></i> Conheça</a>
						<a target="_blank" href="https://wa.me?text=https://<?php echo $full_simple_url; ?>"><i class="lni lni-bullhorn"></i> Indique</a>
						<a target="_blank" href="https://<?php echo $simple_url; ?>/login"><i class="lni lni-lock"></i> Painel</a>
						<a target="_blank" href="https://conheca.<?php echo $full_simple_url; ?>#contato"><i class="lni lni-headphone-alt"></i> Fale conosco</a>
					</div>
					
				</div>

			</div>

		</div>

	</div>

</div>