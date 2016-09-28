

<article class="download">
	<h2><?=_('download log')?></h2>
	
	<table>
		<colgroup>
			<col class="col-one">
			<col class="col-two">
			<col class="col-three">
			<col class="col-four">
			<col class="col-five">
			<col class="col-six">
		</colgroup>
		
		<tr>
			<th><?=_('file id')?></th>
			<th><?=_('date')?></th>
			<th><?=_('IP')?></th>
			<th><?=_('HTTP request')?></th>
			<th><?=_('user')?></th>
			<th title="<?=_('latitude/longitude')?>"><?=_('location')?></th>
		</tr>
		
		<?php foreach( $downloads as $d ): ?>
		<tr>
			<td><?=$d->fk_document ?></td>
			<td><?=date('d-m-Y H:i:s', (integer)$d->date) ?></td>
			<td><?=$d->ip ?></td>
			<td><textarea><?=print_r(unserialize($d->request)) ?></textarea></td>
			<td><?=$d->fk_user ? $d->uname .' '. $d->usurname : _('not connected') ?></td>
			<td><?=isset($d->latitude) ? $d->latitude . '/' . $d->longitude : _('unknown') ?></td>
		</tr>
		<?php endforeach; ?>
		
	</table>
	
	
</article>
	