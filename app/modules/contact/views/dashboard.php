<?php

	$CI =& get_instance();
	$CI->load->model('user_model');
	$u = $CI->user_model->currentUser();	

?>

<h1><?=_('directory')?></h1>

<nav class="submenu">
	<p><a href="contact/card/-1"><?=_('new contact')?></a></p>
</nav>




<div id="search">
	<label for="searchfield"><?=_('search')?>&nbsp;:</label>
	<input type="text" name="searchfield" value="" data-userpref="contactsearch" />

</div>




<nav>
	<table id="datatable" class="autoclickbutton">
		<colgroup>
			<col style="width:30%">
			<col style="width:30%">
			<col style="width:40%">
		</colgroup>
		
		<thead>
			<tr>
				<th><?=_('last name')?></th>	
				<th><?=_('first name')?></th>
				<th><?=_('email')?></th>
			</tr>
		</thead>
		
		<?php foreach( $contact as $u ): 
		
			$rname = $u->surname. ' ' . $u->name;
			$name = $u->name. ' ' . $u->surname;
		?>
	
		<tr>
			<td><a href="contact/index/<?=$u->id ?>" class="visit" rel="<?=$name ?>" class="watch"><?=$u->surname ?></a></td>	
			<td><a href="contact/index/<?=$u->id ?>" class="visit" rel="<?=$name ?>"><?=$u->name ?></a></td>
			<td><?=$u->mail ?></td>
		</tr>
		
		<?php endforeach ?>
	</table>

</nav>
