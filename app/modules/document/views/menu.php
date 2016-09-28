
<h1><?=$name ?></h1>

<nav class="submenu">
	<p><a href="document/card/<?=$id?>"><?=_('file')?></a></p>
	<p><a href="document/share/<?=$id?>"><?=_('shares')?></a></p>
	<p><a href="document/download/<?=$id?>"><?=_('downloads')?></a></p>
	<p class="button">
		<a class="noajax" href="document/get/<?=$id ?>" target="_blank"><?=_('download') ?></a>
	</p>
</nav>
