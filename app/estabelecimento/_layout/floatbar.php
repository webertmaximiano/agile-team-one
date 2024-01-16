<div class="floatbar-holder visible-xs visible-sm"></div>

<div class="floatbar visible-xs visible-sm">

	<div class="floatitem">
		<span class="floaticon"><i class="lni lni-frame-expand" onclick="telacheia();"></i></span>
	</div>
	<div class="floatitem addtohome">
		<span class="floaticon"><i class="lni lni-star"></i></span>
	</div>
	<div class="floatitem">
		<?php $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
		<a href="https://wa.me?text=<?php echo $actual_link; ?>" class="floaticon"><i class="lni lni-bullhorn"></i></a>
	</div>

</div>