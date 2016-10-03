<?php


//-- Default value for new project


if( ! $id )
{
	$active = '1';
	$date = time();
}



$url_warning = 
  _("CAUTION ! The url must be strictly the same as the "
. "customer's website. Otherwise, the ticket system won't "
. "work properly.");

?>


<article class="card" id="<?=$domid ?>">

	<h2>
		<?=_("Project card")?>
		
		<button class="ajax icosave"
			data-href="project/project/save/<?=$id ? $id : '-1' ?>"
			data-post="<?=$domid ?>"
			data-redirect="<?=$id ? 'project/project/card/'.$id : 'project/project/dashboard' ?>" 
			data-destination="page"
		></button>
	</h2>
	
	<?php if( $id ): ?>
	<input type="hidden" name="id" value="<?=$id ?>" />
	<?php endif ?>

	<p>
		<label for="name"><?=_("project name")?></label>
		<input type="text" name="name" value="<?=$name ?>" />
	</p>
	
	<p>
		<label for="url" title="<?=$url_warning?>"><?=_("url")?></label>
		<input type="text" name="url" value="<?=$url ?>" />
		<?php if($url) : ?>
		<button class="link">http://<?=$url ?></button>
		<?php endif ?>
	</p>
	
	<p>
		<label for="description"><?=_("description")?></label>
		<textarea name="description" class="richtext"><?=$description ?> </textarea>
	</p>

	<p>
		<label for="contact"><?=_("API key")?></label>
		<input type="text" name="apikey" value="<?=$apikey ?>" />
		<a href="./" class="apigen"><?=_("generate")?></a>
	</p>
	
	<p>
		<label for="isactive"><?=_("is active")?></label>
		<span class="ui-buttonset">
			<input type="radio" id="active" name="active" value="1" <?php if($active=='1') echo 'checked="checked"'; ?>>
			<label for="active"><?=_("in progress")?></label>
			<input type="radio" id="inactive" name="active" value="0" <?php if($active=='0') echo 'checked="checked"'; ?>>
			<label for="inactive"><?=_("closed")?></label>
		</span>

	</p>
	
	<p>
		<label for="date"><?=_("year")?></label>
		<input type="text" name="date" value="<?=date('Y',$date) ?>" class="disabled" />
	</p>

</article>

