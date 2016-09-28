<?php defined('BASEPATH') OR exit('No direct script access allowed');

	$CI =& get_instance();
	$CI->load->helper('secursession');


	$CI->load->model('configuration/configuration_model');
	$config = $CI->configuration_model->getGeneral();


?><!DOCTYPE html>
<html lang="<?=$lang?>">
	<head>
		<title>Alveole</title>
	
		<meta name="author" content="Aurélien" />
		<meta name="description" content="" />
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width" />
		
		<link rel="canonical" href="<?php echo site_url() ?>" />
		<link rel="icon" href="<?php echo site_url() ?>assets/img/favicon.ico" />
		<link rel="stylesheet" href="<?php echo site_url() ?>assets/css/jquery-ui.min0.css" />
		<link rel="stylesheet" href="<?php echo site_url() ?>assets/css/jquery.datetimepicker.css" />
		<link rel="stylesheet" href="<?php echo site_url() ?>assets/css/jquery.dataTables.min.css" />
		<link rel="stylesheet" href="<?php echo site_url() ?>assets/fonts/stylesheet.css" />
		<link rel="stylesheet" href="<?php echo site_url() ?>assets/css/style.css" />
		<link rel="stylesheet" href="<?php echo site_url() ?>assets/css/mobile.css" />
		<link rel="stylesheet" href="<?php echo site_url() ?>assets/css/modules.css" />
		
		<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jqColorPicker.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url() ?>assets/js/tinymce/tinymce.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url() ?>assets/js/tinymce/jquery.tinymce.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.atinymce.js"></script>
		<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.wallpaperRotate.js"></script>
		<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.datetimepicker.js"></script>
		<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.aDataTable.js"></script>
		<script type="text/javascript" src="<?php echo base_url() ?>assets/js/bgpos.js"></script>
		<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.noty.packaged.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.adialog.js"></script>
		<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.userpref.js"></script>
		<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.about.js"></script>
		<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.responsiveMenu.js"></script>
		<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.autoCropField.js"></script>
		<script type="text/javascript" src="<?php echo base_url() ?>assets/js/overlay.js"></script>
		<script type="text/javascript" src="<?php echo base_url() ?>assets/js/aside.js"></script>
		<script type="text/javascript" src="<?php echo base_url() ?>assets/js/connection.js"></script>
		<script type="text/javascript" src="<?php echo base_url() ?>assets/js/modules.js"></script>
		<script type="text/javascript" src="<?php echo base_url() ?>assets/js/app.js"></script>

	</head>
	<body id="interface">


		<header>
			<?php
	
				/*
					Check if the APP folder is inside or outside the webroot path
					and if show_warning configuration value is set to 'true'.
					If yes, we draw a security warning message.
				*/
				if( substr( APPPATH, 0, strlen(FCPATH) ) === FCPATH
					&& $config['show_warning'] )
				{
					echo '<div id="security_warning">'
					. _('Warning, the APP folder is located inside the WEB folder.'
					. ' This is a security hole. Please contact an administrator.')
					. '</div>';
				}
	
			?>

			<div id="logo"
					class="ajax"
					data-href="dashboard/gui" 
					data-destination="page"
					data-logo="<?=$logo?>"
			></div>

			<nav>
				<?php

				foreach( $modules["menu"] as $m )
				{
					echo '<a href="' . $m['url'] . '">' . $m['title'] . "</a>\r\n				";
				}

				?>

			</nav>	
		</header>



		<main>
			<aside id="menu">
				<h1>Dashboard</h1>
			</aside>
	
			<section id="content">
			</section>
		</main>



		<footer>
<!--
			<?php

				$slogans = array(
					'qui vous réussit',
					'qui buzze à mort',
					'qui donne le ton',
					'qui fait mouche',
					'qui butine les success stories',
					'qui travaille comme un pro',
					'qui fertilise les beaux projets'
				);
			?>
			<p>Idée Lab, l'<span class="ideelab">agence <span class="digital">DIGITALE</span> créative</span> <?=$slogans[array_rand($slogans)]?> !</p>
-->
			<p><span class="digital">Alveole</span> <?=_('takes you to the moon !')?></p>
			
			<p>
				<a href="dashboard/about/" class="noajax about" rel="A propos"><?=_('about')?></a>

				<?php if( is_group('admin') ): ?>
				- <a href="configuration/dashboard" rel="Configuration" class="ajax"><?=_('configuration')?></a>
				<?php endif ?> 

				<?php if( connected() ): ?>
				- <a href="<?=base_url()?>dashboard/disconnect/"><?=_('disconnect')?></a>
				<?php endif; ?>
			</p>
			
			<?php if( $CI->session->userid ): ?>
			<div id="endsession"></div>
			<?php endif ?>
			
			<input id="controller" type="hidden" name="controller" value="" />
			<input id="name" type="hidden" name="name" value="" />
			<input id="slug" type="hidden" name="slug" value="" />

		</footer>


	
		<div id="wallpapers">
			<?php
				$folder = 'assets/img/wallpapers/';
				$wallpapers = glob( FCPATH."$folder*.{jpg,png,gif}", GLOB_BRACE );

				foreach( $wallpapers as $w )
				{
					$url = site_url() . $folder . basename($w);

					if( isset($preferences->wallpaper) )
					{
						if( basename($w) == $preferences->wallpaper )
							$checked = 'data-selected="true"';
							else
							$checked = '';
					}

					echo "<span $checked>" . $url . "</span>\r\n			";
				}
			?>
		</div>

	
		<div id="wallpapers_light">
			<?php
				$folder = 'assets/img/wallpapers_light/';
				$wallpapers = glob( FCPATH."$folder*.{jpg,png,gif}", GLOB_BRACE );

				foreach( $wallpapers as $w )
				{
					$url = site_url() . $folder . basename($w);

					if( isset($preferences->wallpaper) )
					{
						if( basename($w) == $preferences->wallpaper )
							$checked = 'data-selected="true"';
							else
							$checked = '';
					}

					echo "<span $checked>" . $url . "</span>\r\n			";
				}
			?>
		</div>
		
	</body>
</html>





