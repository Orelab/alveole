<?php


foreach( $allusers as &$au )
{
	$au->name = $au->uname . ' '. $au->usurname;
}


if( isset($me) )
{

}


?>




<article class="role">
	<h2><?=_('roles')?></h2>

	<table>
		<colgroup>
			<col class="col-one">
			<col class="col-two">
			<col class="col-three">
			<col class="col-four">
		</colgroup>
		
		<tr>
			<th><?=_('project')?></th>
			<th title="<?=_('Please, add at least a role for customer to activate the ticket management system.')?>"><?=_('user')?></th>
			<th><?=_('role')?></th>
			<th></th>
		</tr>
	
		<?php foreach( $users as $u ): ?>
		
		<tr id="SAVErole<?=$u->id ?>">
			<td>
				<select name="fk_project">
					<?=optionsHtml( $allprojects, $u->fk_project, true ) ?>
				</select>
			</td>
			<td>
				<select name="fk_user">
					<?=optionsHtml( $allusers, $u->fk_user, false ) ?>
				</select>
			</td>
			<td>
				<select name="fk_step">
					<?=optionsHtml( $tag, $u->fk_step ) ?>
				</select>
			</td>
			<td>
				<?php if( isset($projadmin) ): ?>

				<input type="hidden" name="id" value="<?=$u->id ?>" />
<!--
				<button class="save">contact/role|<?=$u->id ?>|SAVErole<?=$u->id ?></button>
-->
				<button class="ajax icosave" 
					data-href="contact/role/save/<?=$u->id ?>" 
					data-post="SAVErole<?=$u->id ?>" 
					data-destination="donothing"
					data-overlay=""
				></button>
				<button class="delete">contact/role|<?=$u->id ?></button>

				<?php endif; ?>
			</td>
		</tr>
	
		<?php endforeach ?>


		<?php if( isset($projadmin) ): ?>
		
		<tr id="SAVEroleNew">
			<td>
				<select name="fk_project">
					<?=optionsHtml( $allprojects, null, count($allprojects)==1?false:true ) ?>
				</select>
			</td>
			<td>
				<select name="fk_user">
					<?=optionsHtml( $allusers, null, count($allusers)==1?false:true ) ?>
				</select>
			</td>
			<td>
				<select name="fk_step">
					<?=optionsHtml( $tag, null ) ?>
				</select>
			</td>
			<td>
<!--				
				<button class="save">contact/role|-1|SAVEroleNew</button>
-->
				<button class="ajax icosave" 
					data-href="contact/role/save/-1" 
					data-post="SAVEroleNew" 
					data-destination="donothing"
					data-overlay=""
				></button>
			</td>
		</tr>
		
		<?php endif; ?>

	</table>
		
</article>
	