<?php

	$c = currentUser();

?>

<?php if( ! is_group('client') ): ?>

<h1><?=_("Project")?></h1>

<nav class="submenu">
	<p><a href="project/index/-1"><?=_("new project")?></a></p>
</nav>

<?php endif ?>




<div id="search">
	<label for="searchfield"><?=_("search")?>&nbsp;:</label>
	<input type="text" name="searchfield" value="" data-userpref="projectsearch" />
</div>


<nav>
	<table id="datatable" class="autoclickbutton">
		<colgroup>
			<col style="width:55%;">
			<col style="width:15%; text-align: center;">
			<col style="width:15%;">
			<col style="width:15%;">
		</colgroup>

		<thead>
			<tr>
				<th><?=_("name")?></th>	
				<th title="<?=_('opened/totals')?>"><?=_("ticket")?></th>
				<th><?=_("status")?></th>
				<th><?=_("year")?></th>
				<th><?=_("owner")?></th>
			</tr>
		</thead>
		
		<?php foreach( $projet as $p ): ?>
	
		<tr>
			<td>
				<a href="project/project/index/<?=$p->id ?>" class="visit" rel="<?=$p->name ?>"><?=$p->name ?></a>
				<button class="link"><?=$p->url ?></button>
			</td>	
			<td><?=$p->openedticket . '/'. $p->totalticket ?></td>
			<td><?=$p->status ?></td>
			<td><?=date('Y', $p->date) ?></td>
			<td><?=($p->fk_owner == $c->id) ? _('me') : $p->uname . ' ' . $p->usurname ?></td>
		</tr>
		
		<?php endforeach ?>
	</table>

</nav>

<?php /*

<script type="text/javascript">

	$('#pdatatable').pDataTable();

</script>

*/ ?>
