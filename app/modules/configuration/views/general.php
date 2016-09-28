

<article>
	<h2><?=_('general preferences')?></h2>

	<?php

		foreach( $general as $row )
		{
			
			switch( $row->type )
			{
				case 'text' :
					echo '<label for="' . $row->key . '">' . $row->key . '</label>';
					echo '<input type="text" name="' . $row->key . '" value="' . htmlentities($row->value) . '" />';
					break;

				case 'boolean' :
					$checked = ( $row->value == 1 ) ? ' checked="checked"' : '';
					
					echo '<label for="' . $row->key . '">' . $row->key . '</label>';
					echo '<input type="checkbox" name="' . $row->key . '"' . $checked . '/>';
					break;

				case 'readonly' :
//					echo '<label for="' . $row->key . '">' . $row->key . '</label>';
//					echo '<input type="text" name="' . $row->key . '" value="' . htmlentities($row->value) . '" readonly />';
					break;

				default :
				case 'hidden' :
			}

			echo '<br/>';
		}
	
	?>

	<p><?=_('Please note that for now, these values can only be modified from a database manager like phpMyAdmin.')?></p>
</article>



