<?php defined('BASEPATH') OR exit('No direct script access allowed');

?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Project index</title>

</head>
<body>

<div id="container">
	<h1>Gestion des projets</h1>

	<div id="body">
	
		<ul>
		
		<?php foreach( $projects  as $project ): ?>
		
			<li><a href="ajax/<?=$plugin->id?>"><?=$plugin->name?></a></li>		
		
		<?php endforeach ?>
		
		</ul>
		
	</div>

	<p class="footer">
	Page rendered in <strong>{elapsed_time}</strong> seconds. 
	<?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?>
	</p>
</div>

</body>
</html>