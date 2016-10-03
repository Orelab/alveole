<?php defined('BASEPATH') OR exit('No direct script access allowed');


?><!DOCTYPE html>
<html id="connexion">
	<head>
		<title>Alveole</title>
	
		<meta name="author" content="Aurélien" >
		<meta name="description" content="">
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width">
		
		<link rel="canonical" href="<?php echo site_url() ?>" />
		<link rel="icon" href="<?php echo site_url() ?>assets/img/favicon.ico" />
		<link rel="stylesheet" href="<?php echo site_url() ?>assets/fonts/stylesheet.css" />
		<link rel="stylesheet" href="<?php echo site_url() ?>assets/css/style.css">
		<link rel="stylesheet" href="<?php echo site_url() ?>assets/css/mobile.css">

		<script src="<?php echo base_url() ?>assets/js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.wallpaperRotate.js"></script>
		<script type="text/javascript" src="<?php echo base_url() ?>assets/js/connection.js"></script>

	</head>
	<body>


	<div>
	
		<article>
			
			<div id="logo">alveole</div>
			<br/>

			<div id="slider">

				<div class="diapo">
					<form method="post" action="dashboard/identification">
						<p>
							<label for="email"><?=_('email') ?></label>
							<input type="text" name="email" value="" class="new" />
						</p>
						
						<p>
							<label for="password"><?=_('password') ?></label>
							<input type="password" name="password" value="" class="new" />
						</p>
						
						<p>
							<label for="submit">&nbsp;</label>
							<input type="submit" name="submit" value="<?=_('Let\'s go working !') ?>" />
						</p>

						<?php if( $register ): ?>
						
						<p class="slide"><?=_('No account yet ?') ?></p>

						<?php endif; ?>
					</form>
				</div>

				<?php if( $register ): ?>
				
				<div class="diapo">
					<form id="register" method="post" action="dashboard/register">
						<p>
							<label for="uname"><?=_('first name') ?></label>
							<input type="text" name="uname" value="" class="new" required />
						</p>
		
						<p>
							<label for="usurname"><?=_('name') ?></label>
							<input type="text" name="usurname" value="" class="new" required />
						</p>
		
						<p>
							<label for="mail"><?=_('email') ?></label>
							<input type="email" name="email" value="" class="new" required />
						</p>
			
						<p>
							<label for="submit" class="unslide"><- <?=_('cancel') ?></label>
							<input type="submit" name="submit" value="<?=_('subscribe') ?>" />
						</p>
					</form>
				</div>

				<div class="diapo">
					<p><?=_('Thanks for registering !') ?></p>
					<p><?=_('Please confirm your email before continue.') ?></p>
				</div>
				
				<?php endif; ?>
			
			</div>
		
		</article>
	
	</div>

	</body>
</html>

