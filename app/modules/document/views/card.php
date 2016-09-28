<!--

<h1>CGV_FORFAIT_hors_opt_20160705.pdf</h1>

<nav class="submenu">
	<p><a href="document/card/1">Fiche document</a></p>
	<p><a href="document/share/1">Partages</a></p>
	<p><a href="document/version/1">Versions</a></p>
	<p><a href="document/download/1">Téléchargements</a></p>
	<p class="button">
		<a class="noajax" href="alveole/document/get/1">download</a>
	</p>
</nav>

-->

<article class="document" id="<?=$domid ?>">

	<h2>
		<?=_('Download a new file')?>
<!--
		<button class="save" data-destination="<?=$id ? 'document/card/'.$id : 'document/dashboard' ?>">document|<?=$id ?>|<?=$domid ?></button>
-->
		<button class="ajax icosave"
			data-href="document/document/save/<?=$id ?>" 
			data-post="<?=$domid ?>" 
			data-redirect="<?=$id ? 'document/card/'.$id : 'document/dashboard' ?>" 
			data-destination="page"
		></button>
	</h2>

	<?php if( $id ): ?>
	<input type="hidden" name="id" value="<?=$id ?>" />
	<?php endif ?>
	
	<input type="hidden" name="file_name" value="<?=$file_name ?>" />

	<p>
		<label for="name"><?=_('title')?></label>
		<input type="text" name="name" value="<?=$name ?>" />
	</p>
	
	<?php if( ! is_group('client') ): ?>
	
	<p>
		<label for="fk_step"><?=_('tag')?></label>
		<select name="fk_step">
			<?=optionsHtml( $step, $fk_step) ?>
		</select>
	</p>
	
	<div>
		<label for="file"><?=_('file')?></label>
		<?=fileHtml( 'file', $path ) ?>
	</div>


	<?php endif ?>


	
	<?php if( $id ): ?>
	
	<hr/>
	
	<p>
		<label for="name"><?=_('format')?></label>
		<input type="text" name="file_type" value="<?=$file_type ?>" class="disabled" />
	</p>
	
	<p>
		<label for="online_date"><?=_('posting date')?></label>
		<input type="text" name="online_date" value="<?=is_int($online_date) ? date('d-m-Y',$online_date) : '' ?>" class="disabled" />
	</p>
	
	<p>
		<label for="last_update"><?=_('last update')?></label>
		<input type="text" name="last_update" value="<?=is_int($last_update) ? date('d-m-Y',$last_update) : '' ?>" class="disabled" />
	</p>
	
	<p>
		<label for="download"><?=_('number of downloads')?></label>
		<input type="text" name="download" value="<?= $ndownloads ?: _('none') ?>" class="disabled" />
	</p>

	<?php endif ?>
	
</article>


