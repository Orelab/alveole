

<article class="ticket full">
	<h2><?=_('tickets')?></h2>

	<table class="hover autoclickbutton">
<!--	
		<colgroup>
			<col style="width:10%;">
			<col style="width:10%;">
			<col style="width:10%;">
			<col style="width:50%;">
			<col style="width:10%;">
			<col style="width:10%;">
			<col style="width:0;">
		</colgroup>
-->
		<thead>
			<tr>
				<th><?=_('project')?></th>
				<th><?=_('author')?></th>
				<th><?=_('date')?></th>
				<th><?=_('message')?></th>
				<th><?=_('last status')?></th>
				<th><?=_('answers')?></th>
				<th>&nbsp;</th>
			</tr>
		</thead>	

		<tbody>
			<?php foreach( $tickets as $t ): ?>
			
			<tr id="SAVEticket<?=$t->id ?>" class="ticket<?=$t->last_step?>">
				<td class="crop"><<?=$t->pname ?></td>
				<td class="crop"><?=$t->uname .' '. $t->usurname ?></td>
				<td><?=dayOrHour($t->date) ?></td>
				<td class="crop"><?=$t->text ?></td>
				<td><?=$t->last_step_name ?></td>
				<td><?=$t->replies ? $t->replies : '' ?></td>
				<td>
					<a class="ajax"
						href="project/ticket/detail/<?=$t->id?>"
						data-destination="dialog"
					></a>
					
					<!--
					<button class="watch" name="Ticket client">project|ticket/detail|<?=$t->id?></button>
					-->
				</td>
			</tr>	
		
			<?php endforeach ?>
		</tbody>

		<tfoot>
			<tr id="SAVEticketNew">
				<td><?=_('project')?></td>
				<td><?=_('me')?></td>
				<td><?=_('now')?></td>
				<td><textarea name="text" class="new notnull"></textarea></td>
				<td>
					<select name="fk_step"><?=optionsHtml( $tag, 13 ) ?></select>
				</td>
				<td></td>
				<td>
					<input type="hidden" name="fk_project" value="<?=$id ?>" />
<!--
					<button class="save">ticket|-1|SAVEticketNew</button>
-->
					<button class="ajax icosave" 
						data-href="project/ticket/save" 
						data-post="SAVEticketNew" 
						data-redirect="project/ticket/dashboard/<?=$id ?>" 
						data-destination="page"
						data-overlay=""
					>SAVE</button>
				</td>
			</tr>
		</tfoot>
	

	</table>
	
</article>

