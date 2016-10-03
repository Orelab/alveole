

			<article id="SAVEuserConfig">
				<h2>
					<?=_('my own preferences')?>
<!--
					<button class="save">configuration|-1|SAVEuserConfig</button>
-->
					<button
						class="ajax icosave"
						data-href="configuration/configuration/save/-1" 
						data-post="SAVEuserConfig" 
						data-redirect="configuration/configuration/preference" 
						data-destination="page"
					></button>
				</h2>


				<h3><?=_('language')?></h3>
				
				<select name="language">
					<option value="fr_FR.utf8" <?=$preferences->language=='fr_FR.utf8' ? 'selected' : '' ?>>français</option>
					<option value="en_EN.utf8" <?=$preferences->language=='en_EN.utf8' ? 'selected' : '' ?>>english</option>
				</select>


				<h3><?=_('wallpaper')?></h3>

				<div class="wallpapers">
					<?php
					
						$checked = '';
						
						if( isset($preferences->wallpaper) )
						{
							if( $preferences->wallpaper=="rotate" )
							{
								$checked = 'checked="checked"';
							}
						}
						else
						{
							$checked = 'checked="checked"';
						}
						
					?>
					<label for="rotate"><?=_('all (cyclic)')?></label><!--
					--><input type="radio" id="rotate" name="wallpaper" value="rotate" <?=$checked ?> /><?php

						$folder = 'assets/img/wallpapers/';
						$wallpapers = glob( FCPATH."$folder*.{jpg,png,gif}", GLOB_BRACE );

						foreach( $wallpapers as $w )
						{
							$file = basename($w);
							$checked = '';

							if( isset($preferences->wallpaper) )
							{
								if( $file == $preferences->wallpaper )
									$checked = 'checked="checked"';
									else
									$checked = '';
							}

							echo '<label for="' . $file . '">' . $file . '</label>';
							echo '<input type="radio" id="' . $file . '" name="wallpaper" value="' . $file . '" ' . $checked . '/>';
						}
					?>
				</div>


				<h3><?=_('mail')?></h3>
				
				<table>
					<thead>
						<tr>
							<th><?=_('type')?></th>
							<th><?=_('security')?></th>
							<th><?=_('server')?></th>
							<th><?=_('port')?></th>
							<th><?=_('login')?></th>
							<th><?=_('password')?></th>
							<th>&nbsp;</th>
						</tr>
					</thead>
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
					
						$keys = array_keys( (array)$preferences->mbox );
					

						foreach( $keys as $key ): ?>

					<tr>
						<td>
							<select name="mbox[<?=$key ?>][type]" >
								<option <?php echo ( $preferences->mbox->{$key}->type=='imap' ? 'selected' : '' ) ?> >imap</option>
								<?php /*
								<option <?php echo ( $preferences->mbox->{$key}->type=='pop' ? 'selected' : '' ) ?> >pop</option>
								*/ ?>
								<option <?php echo ( $preferences->mbox->{$key}->type=='smtp' ? 'selected' : '' ) ?> >smtp</option>
							</select>
						</td>
						<td>
							<select name="mbox[<?=$key ?>][security]">
								<option <?=$preferences->mbox->{$key}->security=='normal' ? 'selected' : '' ?>>normal</option>
								<option <?=$preferences->mbox->{$key}->security=='ssl' ? 'selected' : '' ?>>ssl</option>
								<option <?=$preferences->mbox->{$key}->security=='tls' ? 'selected' : '' ?>>tls</option>
							</select>
						</td>
						<td><input type="text" name="mbox[<?=$key ?>][address]" value="<?=$preferences->mbox->{$key}->address ?>" /></td>
						<td><input type="text" name="mbox[<?=$key ?>][port]" value="<?=$preferences->mbox->{$key}->port ?>" /></td>
						<td><input type="text" name="mbox[<?=$key ?>][login]" value="<?=$preferences->mbox->{$key}->login ?>" /></td>
						<td><input type="password" name="mbox[<?=$key ?>][password]" value="<?=$preferences->mbox->{$key}->password ?>" /></td>
						<td>
							<button class="drop"></button>
						</td>
					</tr>
					
					<?php endforeach; ?>
					
					<tr>
						<td>
							<select name="mbox[<?=$key+1 ?>][type]" class="new">
								<option>imap</option>
								<option>smtp</option>
							</select>
						</td>
						<td>
							<select name="mbox[<?=$key+1 ?>][security]" class="new">
								<option><?=_('normal')?></option>
								<option>ssl</option>
								<option>tls</option>
							</select>
						</td>
						<td><input type="text" name="mbox[<?=$key+1 ?>][address]" value="" class="new" /></td>
						<td><input type="text" name="mbox[<?=$key+1 ?>][port]" value="" class="new" /></td>
						<td><input type="text" name="mbox[<?=$key+1 ?>][login]" value="" class="new" /></td>
						<td><input type="password" name="mbox[<?=$key+1 ?>][password]" value="" class="new" /></td>
						<td>&nbsp;</td>
					</tr>

				</table>

				<br/>


				<h3><?=_('mail signature')?></h3>
				
				<textarea name="signature" class="new richtext"><?=$preferences->signature ?></textarea>

			</article>


