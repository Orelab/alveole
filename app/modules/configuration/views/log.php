


<h1><?=_('activity log')?></h1>





<div id="search">
	<label for="searchfield"><?=_('search')?>&nbsp;:</label>
	<input type="text" name="searchfield" value="" />

</div>





<nav>
	<table id="datatable">
		<colgroup>
			<col class="col-one">
			<col class="col-two">
			<col class="col-three">
			<col class="col-four">
			<col class="col-five">
		</colgroup>
		
		<thead>
			<tr>
				<th><?=_('date')?></th>	
				<th><?=_('user')?></th>
				<th><?=_('controller')?></th>
				<th><?=_('view')?></th>
				<th><?=_('parameters')?></th>
			</tr>
		</thead>
		
		<?php foreach( $activity as $a ):
		
			$user =  $a->uname.' '.$a->usurname;
			?>
	
		<tr>
			<td><?=date('d-m-Y H:i:s', $a->date) ?></td>
			<td><a href="user/index/<?=$a->fk_user ?>" rel="<?=$user?>"><?=$user?></a></td>	
			<td><?=$a->controller ?></td>
			<td><?=$a->view ?></td>
			<td><textarea><?=print_r(unserialize($a->request),1) ?></textarea></td>
		</tr>
		
		<?php endforeach ?>
	</table>

</nav>
