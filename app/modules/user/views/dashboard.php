<?php

	$CI =& get_instance();
	$CI->load->model('user_model');
	$u = $CI->user_model->currentUser();	

?>



<div id="search">
	<label for="searchfield"><?=_('search')?>&nbsp;:</label>
	<input type="text" name="searchfield" value="" data-userpref="contactsearch" />

</div>




<nav>
	<table id="datatable" class="autoclickbutton">
		<colgroup>
			<col style="width:25%">
			<col style="width:25%">
			<col style="width:25%">
			<col style="width:10%">
			<col style="width:15%">
		</colgroup>
		
		<thead>
			<tr>
				<th><?=_('name')?></th>	
				<th><?=_('email')?></th>
				<th><?=_('company')?></th>
				<th title="<?=_('The site is different depending on the group in which you are classified')?>"><?=_('group')?></th>
				<th title="<?=_('The list of roles in projects')?>"><?=_('roles')?></th>
			</tr>
		</thead>
		
		<?php foreach( $user as $u ): 
		
			$rname = $u->usurname. ' ' . $u->uname;
			$name = $u->uname. ' ' . $u->usurname;
		?>
	
		<tr>
			<td>
				<a href="user/index/<?=$u->id ?>" class="visit crop" rel="<?=$name ?>"><?=$rname ?></a>
			</td>	
			<td class="crop"><?=$u->email ?></td>
			<td class="crop"><?=$u->business ?></td>
			<td><?=$u->group ?></td>
			<td><?=str_replace('|', ', ', $u->role) ?></td>
		</tr>
		
		<?php endforeach ?>
	</table>

</nav>
