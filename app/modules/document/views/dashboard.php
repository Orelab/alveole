

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
			<col style="width:35%;">
			<col style="width:35%">
			<col style="width:10%">
			<col style="width:10%">
			<col style="width:10%">
			<col style="width:10%">
		</colgroup>
		
		<thead>
			<tr>
				<th><?=_('title')?></th>	
				<th><?=_('file')?></th>
				<th><?=_('read')?></th>
				<th><?=_('share')?></th>
				<th><?=_('tag')?></th>
<?php /*
				<th><?=_('ressource')?></th>
*/ ?>
			</tr>
		</thead>
		
		<?php foreach( $document as $d ): ?>
	
		<tr>
			<td>
				<a href="document/index/<?=$d->id ?>" class="visit" rel="<?=shorter($d->name)?>" title="<?=$d->name?>">
					<?=$d->name ? shorter($d->name) : shorter($d->path) ?>
				</a>
			</td>	
			<td title="<?=$d->path ?>"><?=shorter($d->path) ?></td>
			<td><?=$d->count ? $d->count : '' ?></td>
			<td><?=$d->share ? $d->share : '' ?></td>
			<td><?=$d->sname ?></td>
<?php /*
			<td>
				<?php
				if( $d->ressource && $d->fk_ressource )
				{
					//echo '<nav><a href="' . $d->ressource . '/index/' . $d->fk_ressource . '">'; 
					echo $d->ressource . $d->fk_ressource;
					//echo '</a></nav>'; 
				}
				?>
			</td>
*/ ?>
		</tr>
		
		<?php endforeach ?>
	</table>

</nav>
