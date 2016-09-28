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


	$fields = array(
		'text',
		'textarea',
		'date',
		'mail',
		'url'
	)
?>







<article class="contact">

	<h2>
		<?=_('contact card')?>
<!--
		<button class="save" data-destination="<?=$id ? 'contact/card/'.$id : 'contact/dashboard' ?>">contact|<?=$id ?>|<?=$domid ?></button>
-->
		<button class="ajax icosave" 
			data-href="contact/contact/save"
			data-post="CONTACToriginal<?=$domid ?>" 
			data-redirect="<?=$id ? 'contact/index/'.$id : 'contact/dashboard' ?>" 
			data-destination="page"
			data-overlay=""
		></button>
	</h2>
	
	<div id="CONTACToriginal<?=$domid ?>">
	
		<?php if( $id ): ?>
		<input type="hidden" name="id" value="<?=$id ?>" />
		<?php endif ?>
		
		<p>
			<label for="name"><?=_('first name')?></label>
			<input type="text" name="name" value="<?=$name ?>" />
		</p>
		
		<p>
			<label for="surname"><?=_('last name')?></label>
			<input type="text" name="surname" value="<?=$surname ?>" />
		</p>
	
		<p>
			<label for="mail"><?=_('email')?></label>
			<input type="text" name="mail" value="<?=$mail ?>" />
		</p>
	</div>


	<?php if( $id ): ?>

	<hr/>

	<div class="ui-sortable">
		<?php foreach( $meta as $m ): ?>

		<div id="SAVEmeta<?=$m->id ?>">
			<input type="text" name="key" value="<?=$m->key ?>" />
			
			<?php

			switch( $m->field )
			{
				case 'textarea' :
					echo '<textarea name="value">' . $m->value . '</textarea>';
					break;

				case 'text' :
				case 'url' :
				case 'email' :
				case 'date' :
					echo '<input type="' . $m->field . '" name="value" value="' . $m->value . '" />';
			}

			?>
		
			<input type="hidden" name="field" value="<?=$m->field ?>" />			
			<input type="hidden" name="id" value="<?=$m->id ?>" />
<!--
			<button class="save">contact/meta|<?=$id ?>|SAVEmeta<?=$m->id ?></button>
-->
			<button class="ajax icosave"
				data-href="contact/meta/save"
				data-post="SAVEmeta<?=$m->id ?>" 
				data-redirect="<?=$id ? 'contact/index/'.$id : 'contact/dashboard' ?>" 
				data-destination="page"
				data-overlay=""
			></button>
			<button class="delete">contact/meta|<?=$m->id ?></button>
			<button class="order"></button>
		</div>
		
		<?php endforeach; ?>
	</div>

	<div id="SAVEmetaNew">
		<select name="field">
			<option></option>
			<option value="text"><?=_('compagny')?></option>
			<option value="text"><?=_('nickname')?></option>
			<option value="text"><?=_('phone')?></option>
			<option value="text"><?=_('mobile')?></option>
			<option value="email"><?=_('email')?></option>
			<option value="url"><?=_('url')?></option>
			<option value="textarea"><?=_('address')?></option>
			<option value="textarea"><?=_('memo')?></option>
			<option value="date"><?=_('birthday')?></option>
			<option value="date"><?=_('date')?></option>
		</select>
	
		<input type="text" name="value" value="" />
		<input type="hidden" name="key" value="" />
			
		<input type="hidden" name="fk_contact" value="<?=$id ?>" />		
<!--
		<button class="save">contact/meta|-1|SAVEmetaNew</button>
-->
		<button class="ajax icosave"
			data-href="contact/meta/save"
			data-post="SAVEmetaNew" 
			data-redirect="<?=$id ? 'contact/index/'.$id : 'contact/dashboard' ?>" 
			data-destination="page"
			data-overlay=""
		></button>
	</div>

	<?php endif ?>

</article>

