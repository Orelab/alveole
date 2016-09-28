<?php


$recurrence = array(
	(object)array( "id" => "none", "name" => _("none")),
	(object)array( "id" => "daily", "name" => _("daily")),
	(object)array( "id" => "weekly", "name" => _("weekly")),
	(object)array( "id" => "monthly", "name" => _("monthly")),
	(object)array( "id" => "yearly", "name" => _("yearly"))
);



?>







<article class="bill">
	<h2><?=isset($title)?$title:_('bills') ?></h2>
		
	<table>

		<colgroup>
			<col class="col-one">
			<col class="col-two">
			<col class="col-three">
			<col class="col-four">
			
			<?php if( ! is_group('client') ): ?>
			<col class="col-five">
			<col class="col-six">
			<col class="col-seven">
			<?php endif ?>
			
		</colgroup>
		
		<tr>
			<th><?=_("amount")?></th>
			<th><?=_("date")?></th>
			<th><?=_("payment period")?></th>
			<th><?=_("bill")?></th>

			<?php if( ! is_group('client') ): ?>
			<th><?=_("outstanding")?></th>
			<th><?=_("recurrence")?></th>
			<th>&nbsp;</th>
			<?php endif ?>
			
		</tr>
		
		<?php foreach( $bills as $b ): ?>
		
		<tr id="SAVEbill<?=$b->id ?>">
			<td><input type="number" name="amount" value="<?=$b->amount ?>" /></td>
			<td><input type="text" name="date" value="<?=date('d-m-Y', (integer)$b->date) ?>" /></td>
			<td><input type="number" name="term" value="<?=$b->term ?>" /></td>
			<td>
				<?php if( isset($b->file) ): ?>
				<a href="<?=base_url() ?>document/get/<?=$b->fk_document ?>" download="<?=$b->file ?>" target="_blank" class="downloadable">
					<?=@array_pop(explode('/',$b->type)) ?>
				</a>
				<?php endif ?>
			</td>

			<?php if( ! is_group('client') ): ?>

			<td><?=$b->amount - $b->paidbycustomer ?></td>
			<td><select name="recurrence"><?=optionsHtml( $recurrence, $b->recurrence, false )?></select></td>
			<td>
				<input type="hidden" name="id" value="<?=$b->id ?>" />
<!--
				<button class="save">project/bill|<?=$b->id ?>|SAVEbill<?=$b->id ?></button>
-->
				<button class="ajax icosave" 
					data-href="project/bill/save/<?=$b->id ?>" 
					data-post="SAVEbill<?=$b->id ?>" 
					data-redirect="project/bill/dashboard/<?=$b->id ?>" 
					data-destination="page"
					data-overlay=""
				></button>

			</td>

			<?php endif ?>
			
		</tr>
		
		<?php endforeach ?>
		

		<?php if( $fk_project && ! is_group('client') ): ?>

		<tr id="SAVEbillNew">
			<td><input type="number" name="amount" value="" class="new" /></td>
			<td><input type="text" class="date new" name="date" value="" /></td>
			<td><input type="number" class="new" name="term" value="" /></td>
			<td><?=fileHtml( 'file', '', '' ) ?></td>
			<td><!-- <input type="checkbox" name="paid" /> --></td>
			<td><select name="recurrence"><?=optionsHtml( $recurrence, null, false )?></select></td>
			<td>
				<input type="hidden" name="fk_project" value="<?=$fk_project ?>" />
<!--
				<button class="save">project/bill|-1|SAVEbillNew</button>
-->
				<button class="ajax icosave" 
					data-href="project/bill/save/-1" 
					data-post="SAVEbillNew" 
					data-redirect="project/bill/dashboard/<?=$b->id ?>" 
					data-destination="page"
					data-overlay=""
				></button>
			</td>
		</tr>

		<?php endif ?>
		
	</table>
	
	
</article>
