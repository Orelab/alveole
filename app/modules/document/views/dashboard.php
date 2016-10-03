<?php

	$c = currentUser();

?>

<?php if( ! is_group('client') ): ?>

<h1>Documents</h1>

<nav class="submenu">
	<p><a href="document/index/-1"><?=_('new file')?></a></p>
</nav>

<?php endif ?>




<div id="search">
	<label for="searchfield"><?=_('search')?>&nbsp;:</label>
	<input type="text" name="searchfield" value="" data-userpref="documentsearch" />

</div>




<nav>
	<table id="datatable" class="autoclickbutton">
		<colgroup>
			<col style="width:40%">
			<col style="width:20%">
			<col style="width:10%">
			<col style="width:10%">
			<col style="width:10%">
		</colgroup>
		
		<thead>
			<tr>
				<th><?=_('title')?></th>	
				<th><?=_('owner')?></th>
				<th><?=_('read')?></th>
				<th><?=_('share')?></th>
				<th><?=_('tag')?></th>
			</tr>
		</thead>
		
		<?php foreach( $document as $d ): ?>
	
		<tr>
			<td>
				<a href="document/index/<?=$d->id ?>" class="visit" rel="<?=shorter($d->name)?>" title="<?=$d->name?>">
					<?=$d->name ? shorter($d->name) : shorter($d->path) ?>
				</a>
			</td>	
			<td class="crop"><?=($d->fk_owner == $c->id) ? _('me') : $d->uname . ' ' . $d->usurname ?></td>
			<td><?=$d->count ? $d->count : '' ?></td>
			<td><?=$d->share ? $d->share : '' ?></td>
			<td><?=$d->sname ?></td>
		</tr>
		
		<?php endforeach ?>
	</table>

</nav>
