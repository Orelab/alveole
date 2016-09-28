<?php


foreach( $unpaidbills as &$b )
{
	$b->id = $b->id . '|' . $b->date;
	$lib = $b->amount . '€ du '. date('d-m-Y', (integer)$b->date);
	add_object_property( $b, 'name', $lib );
}


foreach( $allbills as &$b )
{
	$b->id = $b->id . '|' . $b->date;
	$lib = $b->amount . '€ du '. date('d-m-Y', (integer)$b->date);
	add_object_property( $b, 'name', $lib );
}




?>


<article class="bill">
	<h2><?=isset($title)?$title:_('payments') ?></h2>

	<table>

		<colgroup>
			<col class="col-one">
			<col class="col-two">
			<col class="col-three">
			<col class="col-four">
		</colgroup>
		
		<tr>
			<th><?=_('amount')?></th>
			<th><?=_('date')?></th>
			<th><?=_('bill')?></th>
			<th>&nbsp;</th>
		</tr>
		
		<?php foreach( $payments as $p ): ?>
		
		<tr id="SAVEpayment<?=$p->id ?>">
			<td><input type="number" name="amount" value="<?=$p->amount ?>" /></td>
			<td><input type="text" class="date" name="date" value="<?=date('d-m-Y', (integer)$p->date) ?>" /></td>
			<td><select name="fk_bill"><?=optionsHtml( $allbills, $p->fk_bill.'|'.$p->fk_date, true )?></select></td>
			<td>
				<input type="hidden" name="id" value="<?=$p->id ?>" />
<!--
				<button class="save">project/payment|<?=$p->id ?>|SAVEpayment<?=$p->id ?></button>
-->
				<button class="ajax icosave" 
					data-href="project/payment/save/<?=$p->id ?>" 
					data-post="SAVEpayment<?=$p->id ?>" 
					data-redirect="project/bill/dashboard/<?=$fk_project ?>" 
					data-destination="page"
					data-overlay=""
				></button>
			</td>
		</tr>



		<?php endforeach ?>
		
		<tr id="SAVEpaymentNew">
			<td><input type="number" class="new" name="amount" value="" /></td>
			<td><input type="text" class="date new" name="date" value="" /></td>
			<td><select name="fk_bill" class="new"><?=optionsHtml( $unpaidbills, '' )?></select></td>
			<td>
<!--
				<button class="save">project/payment|-1|SAVEpaymentNew</button>
-->
				<button class="ajax icosave" 
					data-href="project/payment/save/-1" 
					data-post="SAVEpaymentNew" 
					data-redirect="project/bill/dashboard/<?=$fk_project ?>" 
					data-destination="page"
					data-overlay=""
				></button>
			</td>
		</tr>

	</table>
	
	
</article>
