<?php

	$reclist = array(
		(object)array('id'=>'none', 'name' => _('none') ),
		(object)array('id'=>'day', 'name' => _('day') ),
		(object)array('id'=>'week', 'name' => _('week') ),
		(object)array('id'=>'month', 'name' => _('month') ),
		(object)array('id'=>'year', 'name' => _('year') )
	);


?>

<form>
	<article class="savenew">
	
		<div class="half">
			<label for="title">Titre</label>
			<input type="text" name="title" value="<?=$title ?>" />
			<br/>
			
			<label for="description"><?=_('description')?></label>
			<textarea name="description" class="richtext"><?=$description ?></textarea>
		</div><!--
		
		--><div class="half">
			<label for="fk_step"><?=_('tag')?></label>
			<select name="fk_step">
				<?php echo optionsHtml( $taglist, $tag ? $tag : $taglist[0]->id, false ); ?>
			</select>
			<hr/>
	
			<label for="allDay"><?=_('whole day')?></label>
			<input type="checkbox" name="allDay" <?=$allDay ? 'checked' : '' ?> />
			<br/>
	
			<label for="start"><?=_('start')?></label>
			<input type="datetime-local" name="start" value="<?=$start ? date('d-m-Y H:i O', $start) : '' ?>" />
			<br/>
			
			<label for="end"><?=_('end')?></label>
			<input type="datetime-local" name="end" value="<?=$end ? date('d-m-Y H:i O', $end) : '' ?>" />
			<hr/>
			
			<label for="recurrence"><?=_('recurrence')?></label>
			<select name="recurrence">
				<?php echo optionsHtml( $reclist, $recurrence, false ); ?>
			</select>
			<br/>
			
			<label for="interval"><?=_('step')?></label>
			<input type="number" min="1" step="1" name="interval" value="<?=$interval ?>" />
			<br/>
			
			<label for="recend"><?=_('term')?></label>
			<input type="datetime-local" name="recend" value="<?=$recend ? date('d-m-Y H:i O', $recend) : '' ?>" />
			<br/>
			
			<input type="hidden" name="id" value="<?=$id ?>" />
			<input type="hidden" name="fk_user" value="<?=$fk_user ?>" disabled />
		</div>
	</article>
</form>



