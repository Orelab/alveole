

<article class="historic half" id="SAVElogNew">

	<h2>
		<?=_("Note an historic")?>
		
		<button class="ajax icosave" 
			href="" 
			data-href="project/log/save/-1" 
			data-post="SAVElogNew" 
			data-redirect="" 
			data-destination="donothing"
			data-overlay=""
		>SEE</button>
	</h2>

	<p>
		<label><?=_("description")?></label>
		<textarea name="text" style="width:100%; min-height: 70px;"></textarea>
	</p>		

	<p>
		<label><?=_("project")?></label>
		<select name="fk_ressource"><?=optionsHtml( $project, '' )?></select>
		<input type="hidden" name="ressource" value="project" />
	</p>		

	<p>
		<label><?=_("tag")?></label>
		<select name="fk_step"><?=optionsHtml( $tag, '' )?></select>
	</p>		

	<p>
		<label><?=_("date")?></label>
		<input type="datetime-local" class="date" name="date" value="<?=date('d-m-Y') ?>" />
	</p>		

	<div>
		<label><?=_("attachment")?></label>
		<?=fileHtml( 'file', '', true ) ?>
	</div>

</article>
