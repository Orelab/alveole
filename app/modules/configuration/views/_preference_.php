<?php

	/*
		$key MUST be set to more than 0 (see bellow : 0+1) as it will
		be saved in JSON format. If we save an array of ONE LINE, and
		if this line as a key named "0", json_encode will encode an
		array. If the same first key is "1", it will be encoded as an
		object. So we prefer to force an object format by starting with
		a first key set to "1"...
		
		Note that if the user configuration contains an array of mail
		configurations, this value will be rewritten.
	*/
	$key = 0;


	$keys = array_keys( (array)$preferences->address );

?>

			<article id="SAVEuserConfig">
				<h2>Langue</h2>
				
				<select name="language">
					<option value="fr_FR.utf8" <?=$preferences->language=='fr_FR.utf8' ? 'selected' : '' ?>>français</option>
					<option value="en_EN.utf8" <?=$preferences->language=='en_EN.utf8' ? 'selected' : '' ?>>english</option>
				</select>	


				<h2>Emails</h2>
				
				<table>
					<thead>
						<tr>
							<th>type</th>
							<th>sécurité</th>
							<th>adresse</th>
							<th>port</th>
							<th>identifiant</th>
							<th>mot de passe</th>
							<th>&nbsp;</th>
						</tr>
					</thead>

					<?php foreach( $keys as $key ): ?>

					<tr>
						<td>
							<select name="type[<?=$key ?>]" >
								<option <?php echo ( $preferences->type->{$key}=='imap' ? 'selected' : '' ) ?> >imap</option>
								<?php /*
								<option <?php echo ( $preferences->type->{$key}=='pop' ? 'selected' : '' ) ?> >pop</option>
								*/ ?>
								<option <?php echo ( $preferences->type->{$key}=='smtp' ? 'selected' : '' ) ?> >smtp</option>
							</select>
						</td>
						<td>
							<select name="security[<?=$key ?>]">
								<option <?=$preferences->security->{$key}=='normal' ? 'selected' : '' ?>>normal</option>
								<option <?=$preferences->security->{$key}=='ssl' ? 'selected' : '' ?>>ssl</option>
								<option <?=$preferences->security->{$key}=='tls' ? 'selected' : '' ?>>tls</option>
							</select>
						</td>
						<td><input type="text" name="address[<?=$key ?>]" value="<?=$preferences->address->{$key} ?>" /></td>
						<td><input type="text" name="port[<?=$key ?>]" value="<?=$preferences->port->{$key} ?>" /></td>
						<td><input type="text" name="login[<?=$key ?>]" value="<?=$preferences->login->{$key} ?>" /></td>
						<td><input type="password" name="password[<?=$key ?>]" value="<?=$preferences->password->{$key} ?>" /></td>
						<td>
							<button class="drop"></button>
						</td>
					</tr>
					
					<?php endforeach; ?>
					
					<tr>
						<td>
							<select name="type[<?=$key+1 ?>]" class="new">
								<option>imap</option>
								<option>smtp</option>
							</select>
						</td>
						<td>
							<select name="security[<?=$key+1 ?>]" class="new">
								<option>normal</option>
								<option>ssl</option>
								<option>tls</option>
							</select>
						</td>
						<td><input type="text" name="address[<?=$key+1 ?>]" value="" class="new" /></td>
						<td><input type="text" name="port[<?=$key+1 ?>]" value="" class="new" /></td>
						<td><input type="text" name="login[<?=$key+1 ?>]" value="" class="new" /></td>
						<td><input type="password" name="password[<?=$key+1 ?>]" value="" class="new" /></td>
						<td>&nbsp;</td>
					</tr>

				</table>

				<br/>


				<h2>Signature des emails</h2>
				
				<textarea name="signature" class="new richtext"><?=$preferences->signature ?></textarea>
			

				<br/><br/>

				<button class="save">configuration|-1|SAVEuserConfig</button>

			</article>


