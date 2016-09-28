<?php

	$u = currentUser();



?>

<h1>Configuration</h1>

<nav class="submenu configuration">
<!--
	<p><a href="configuration/general"><?=_('system configuration')?></a></p>
-->
	<p><a href="configuration/preference"><?=_('my own preferences')?></a></p>
	<p><a href="configuration/password/<?=$u->id?>"><?=_('my password')?></a></p>

	<hr/>

	<p><a href="configuration/tag"><?=_('manage tags')?></a></p>
	<p><a href="configuration/user"><?=_('manage users')?></a></p>
<?php /*
	<p><a href="configuration/role" rel="Rôles">Gérer les rôles</a></p>
	<p><a href="configuration/authorization" rel="Droits">Gérer les autorisations</a></p>

	<p><a href="configuration/module" rel="Modules">Gérer les modules</a></p>
	<p><a href="configuration/recall" rel="Configuration des relances">Gérer les relances</a></p>
	<p><a href="configuration/log" rel="Journal d'activité">Voir le journal d'activité</a></p>

	<hr/>

	<p><a href="plugin/index/-1" rel="Nouveau plugin">Nouveau plugin</a></p>
	<p><a href="licence/gestion" rel="Gestion des licences">Gestion des licences</a></p>

	<hr/>


	<p><a href="maintenance/import">Importations</a></p>
	<p><a href="maintenance/export">Exportations</a></p>
	<p><a href="online/dashboard" class="special"><?=_('alveole online')?></a></p>

*/ ?>

</nav>




