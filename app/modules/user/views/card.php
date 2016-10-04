<?php


	$radio = array(
		(object)array('id'=>0,'name'=>_('no')),
		(object)array('id'=>1,'name'=>_('yes'))
	);
	
	
	$groupes = array(
		(object)array('id'=>'admin','name'=>_('administrators')),
		(object)array('id'=>'client','name'=>_('customers')),
		(object)array('id'=>'team','name'=>_('team'))
	);


	if( ! is_group('admin') )	
		$readonly = 'disabled="disabled"';
		else
		$readonly = '';


?>







<article class="user" id="<?=$domid ?>">

	<h2>
		<?=_('user card')?>
<!--
		<button class="save" data-destination="<?=$id ? 'user/card/'.$id : 'user/dashboard' ?>">user|<?=$id ?>|<?=$domid ?></button>
-->
		<button class="ajax icosave"
			data-href="user/user/save/<?=$id ?>" 
			data-post="<?=$domid ?>" 
			data-redirect="<?=$id ? 'user/card/'.$id : 'user/dashboard' ?>" 
			data-destination="page"
		></button>
	</h2>
	
	<?php if( $id ): ?>
	<input type="hidden" name="id" value="<?=$id ?>" />
	<?php endif ?>
	
	<p>
		<label for="uname"><?=_('first name')?></label>
		<input type="text" name="uname" value="<?=$uname ?>" />
	</p>
	
	<p>
		<label for="usurname"><?=_('last name')?></label>
		<input type="text" name="usurname" value="<?=$usurname ?>" />
	</p>

	<p>
		<label for="business"><?=_('company')?></label>
		<input type="text" name="business" value="<?=$business ?>" />
	</p>

	<p>
		<label for="email"><?=_('email')?></label>
		<input type="text" name="email" value="<?=$email ?>" <?=$readonly?> />
	</p>

	<p>
		<label for="phone"><?=_('phone')?></label>
		<input type="text" name="phone" value="<?=$phone ?>" />
	</p>

	<p>
		<label for="address"><?=_('address')?></label>
		<textarea name="address"><?=$address ?></textarea>
	</p>
	
	<?php if( ! is_group('client') ): ?>

	<p>
		<label for="text"><?=_('memo')?></label>
		<textarea name="text"><?=$text ?></textarea>
	</p>
	
	
	<hr/>

	
	<p>
		<label for="can_connect"><?=_('allowed to connect')?></label>
		<select name="can_connect" <?=$readonly?> >		
			<?=optionsHtml( $radio, $can_connect, false ) ?>
		</select>
	</p>
	
	<p>
		<label for="group"><?=_('group')?></label>
		<select name="group" <?=$readonly?> >		
			<?=optionsHtml( $groupes, $group, true ) ?>
		</select>
	</p>

	<div class="help">
		<?=_('<b>Administrators</b> can see everything, and modify everything.<br/>'
		.'The <b>team</b> members can see and modify their own projects, and the projects they are included.<br/>'
		.'The <b>customers</b> can only see a partial view of the projects they are included (readonly).')?>

	</div>

	
	<?php endif ?>

</article>

