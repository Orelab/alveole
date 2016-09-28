<?php


$recurrence = array(
	(object)array( "id" => "none", "name" => _("none")),
	(object)array( "id" => "daily", "name" => _("daily")),
	(object)array( "id" => "weekly", "name" => _("weekly")),
	(object)array( "id" => "monthly", "name" => _("monthly")),
	(object)array( "id" => "yearly", "name" => _("yearly"))
);



?>







<article class="bill-unpaid full">
	<h2><?=isset($title)?$title:_('bills') ?></h2>
		
	<table class="hover">
		
		<tr>
			<th><?=_("customer")?></th>
			<th><?=_("amount")?></th>
			<th><?=_("outstanding")?></th>
			<th><?=_("date")?></th>
			<th><?=_("payment period")?></th>
			<th><?=_("bill")?></th>
			<th>&nbsp;</th>
		</tr>
		
		<?php foreach( $bills as $b ): ?>

	
			<?php
			?>
	
			<tr id="SAVEbill<?=$b->id ?>">
				<td><?=$b->name ?></td>
				<td><?=$b->amount ?></td>
				<td><?=$b->amount - $b->paidbycustomer ?></td>
				<td><?=date('d-m-Y', (integer)$b->date) ?></td>
				<td>
					<?php
					$retard = floor( ( (time()-$b->date) - ($b->term*60*60*24) ) / (60*60*24) );
					echo "$retard "._("days");
					?>
				</td>
				<td>
					<?php if( isset($b->file) ): ?>
					<a href="<?=base_url() ?>document/get/<?=$b->fk_document ?>" download="<?=$b->file ?>" target="_blank" class="downloadable">
						<?=@array_pop(explode('/',$b->type)) ?>
					</a>
					<?php endif ?>
				</td>
				<td><a class="ajax" href="project/bill/dashboard/<?=$b->fk_project ?>">voir</a></td>
			</tr>
		
		<?php endforeach ?>
		
	</table>
	
	
</article>
