



<article class="password" id="<?=$domid ?>">
		<?php /*
		
			Le bouton "save" contient une chaîne de caractère qui permet
			un enregistrement AJAX en ligne. Cette chaîne est constituée
			des éléments suivants, séparés par le caractère "tube" (\) :
			
			1 : nom de la table
			2 : identifiant de l'enregistrement
			3 : balise dans le dom qui conteint les valeurs à mettre à jour
			    On retrouve les éléments avec jquery de la manière suivante :
			    $(SAVEcard).find('input').serialize()
		
		*/ ?>
	<h2>
		<?=_('change my password')?> 
<!--
		<button class="save">user|<?=$id ?>|<?=$domid ?></button>
-->
		<button class="ajax icosave"
			data-href="user/user/save/<?=$id ?>" 
			data-post="<?=$domid ?>" 
			data-destination="disconnect"
		></button>
	</h2>
	
	<?php if( $id ): ?>
	<input type="hidden" name="id" value="<?=$id ?>" />
	<?php endif ?>
	
	<p>
		<label for="md5pass">nouveau mot de passe</label>
		<input type="password" name="md5pass" value="" class="new" />
	</p>
	
	<p>
		<label for="verif">vérification</label>
		<input type="password" name="verif" value="" class="new" />
	</p>

</article>

