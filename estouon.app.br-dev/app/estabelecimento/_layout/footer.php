		</div>

		<script src="<?php echo $app['url']; ?>/_core/_cdn/bootstrap/js/bootstrap.min.js"></script>
		<script src="<?php echo $app['url']; ?>/_core/_cdn/sidr/js/jquery.sidr.min.js"></script>
		<script src="<?php echo $app['url']; ?>/_core/_cdn/maskMoney/js/maskmoney.min.js"></script>
		<script src="<?php echo $app['url']; ?>/_core/_cdn/maskedInput/js/jquery.maskedinput.min.js"></script>
		<script src="<?php echo $app['url']; ?>/_core/_cdn/validate/js/jquery.validate.min.js"></script>
		<script src="<?php echo $app['url']; ?>/_core/_cdn/sticky/js/jquery.sticky.min.js"></script>
		<script src="<?php echo $app['url']; ?>/_core/_cdn/app/js/template.js"></script>
		<?php system_footer(); ?>

	</body>

	<script>

		if('serviceWorker' in navigator) {
		  navigator.serviceWorker
		           .register('serviceworker.js')
		           .then(function() { console.log('Service Worker Registered'); });
		}

		let deferredPrompt;

		window.addEventListener('beforeinstallprompt', (e) => {
			deferredPrompt = e;
			addBtn.style.display = 'block';
			deferredPrompt.prompt();
			deferredPrompt.userChoice.then((choiceResult) => {
				if (choiceResult.outcome === 'accepted') {
					console.log('User accepted the A2HS prompt');
				} else {
					console.log('User dismissed the A2HS prompt');
				}
				deferredPrompt = null;
			});
		});

	</script>

</html>