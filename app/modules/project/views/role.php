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
			<th title="Ajouter au minimum un rôle CLIENT pour activer les tickets.">interlocuteur</th>
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
				<?php if( $projadmin ): ?>

				<input type="hidden" name="id" value="<?=$u->id ?>" />

				<button class="ajax icosave" 
					data-href="user/role/save/<?=$u->id ?>" 
					data-post="SAVErole<?=$u->id ?>" 
					data-redirect="project/project/role/<?=$id ?>" 
					data-destination="page"
					data-overlay=""
				></button>
				<button class="delete">user/role|<?=$u->id ?></button>

				<?php endif; ?>
			</td>
		</tr>
	
		<?php endforeach ?>


		<?php if( $projadmin ): ?>
		
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
				<button class="ajax icosave" 
					data-href="user/role/save/-1" 
					data-post="SAVEroleNew" 
					data-redirect="project/project/role/<?=$id ?>" 
					data-destination="page"
					data-overlay=""
				></button>
			</td>
		</tr>
		
		<?php endif; ?>

	</table>
		
</article>
	