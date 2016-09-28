<?php

$CI = get_instance();



function find_step_deprecated( $array, $key )
{
	foreach( $array as $a )
	{
		if( $a->id == $key )
		{
			return trim(str_replace( '--', '', $a->name ));
		}
	}
	return '';
}


?>




<article class="ticketdetail">

	<table>
	
		<colgroup>
			<col class="col-one">
			<col class="col-two">
			<col class="col-three">
			<col class="col-four">
			<col class="col-five">
			<col class="col-six">
			<col class="col-seven">
		</colgroup>
	
		<tr>
			<th><?=_('author')?></th>
			<th><?=_('date')?></th>
			<th><?=_('message')?></th>
			<th><?=_('status')?></th>
			<th><?=_('file')?></th>
			<th><?=_('cost')?></th>
			<th>&nbsp;</th>
		</tr>	

		<?php foreach( $tickets as $t ): ?>
		
		<tr id="SAVEdetailticket<?=$t->id ?>">
			<td class="crop"><?=$t->uname .' '. $t->usurname ?></td>
			<td><?=dayOrHour($t->date) ?></td>
			<td><textarea name="text"><?=$t->text ?></textarea></td>
			<td><select name="fk_step"><?=optionsHtml( $tag, $t->fk_step ) ?></select></td>
			<td>
				<?php if( $t->path ): ?>
				<a href="<?=base_url() ?>document/get/<?=$t->fk_document ?>" download="<?=$t->file_name ?>" target="_blank" class="downloadable">
					<?=@array_pop(explode('/',$t->file_type)) ?>
				</a>
				<?php endif ?>
			</td>
			<td><input type="number" name="price" value="<?=$t->price ?>" /></td>
			<td>
				<?php if( ! is_group('client') ): ?>
				<input type="hidden" name="id" value="<?=$t->id ?>" />
<!--
				<button class="save">ticket|<?=$t->id?>|SAVEdetailticket<?=$t->id?></button>
-->
				<button class="ajax icosave" 
					data-href="project/ticket/save/<?=$t->id?>" 
					data-post="SAVEdetailticket<?=$t->id?>" 
					data-redirect="" 
					data-destination="donothing"
					data-overlay=""
				>SAVE</button>
				<?php endif ?>
			</td>
		</tr>	
	
		<?php endforeach ?>
		
		<tr id="SAVEdetailticketNew">
			<td><?=_('me')?></td>
			<td><?=_('now')?></td>
			<td><textarea name="text" class="new"></textarea></td>
			<td><select name="fk_step" class="new"><?=optionsHtml( $tag, 14 ) ?></select></td>
			<td><?=fileHtml( 'file', '', '' ) ?></td>
			<td><input type="number" name="price" value="" class="new" /></td>
			<td>
				<input type="hidden" name="fk_parent" value="<?=$id ?>" />
				<input type="hidden" name="fk_project" value="<?=$detail->fk_project ?>" />

<!--
				<button class="save">project/ticket|-1|SAVEdetailticketNew</button>
-->
				<button class="ajax icosave" 
					data-href="project/ticket/save/-1" 
					data-post="SAVEdetailticketNew" 
					data-redirect="" 
					data-destination="close"
					data-overlay=""
				>SAVE</button>
			</td>
		</tr>	
	

	</table>
	
</article>

