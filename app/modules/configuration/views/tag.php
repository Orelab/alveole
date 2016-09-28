


<article class="configtag">
	<h2><?=_('tags')?></h2>
	
	<p>Les étiquettes permettent de mieux vous organiser, et de classer 
	vos différents	contenus (documents, tickets, métiers...).</p>
	
	<p>Notez que pour assurer un fonctionnement correct du programme,
	certaines étiquettes ne peuvent être supprimées.</p>
	
	<p>Par exemple, l'étiquette 'facture' doit impérativement être 
	présente pour assurer la gestion de l'affichage des factures 
	dans votre calendrier.</p>


<?php /* This is used with configuretag.js/tagSelector

	<select name="configuretag"><?=optionsHtml($labels, '') ?></select>
	<br/>

*/?>

<!-- @whitespace

<?php foreach( $tags as $key => $tag ): ?>
	
--><div class="half config-tag-<?=$key ?>">
	
		<h3><?=$key ?></h3>
		
		<table>
			<colgroup>
				<col style="width:5%;">
				<col style="width:40%;">
				<col style="width:40%;">
				<col style="width:15%;">
			</colgroup>
			
			<thead>
				<tr>
					<th><?=_('order')?></th>
					<th><?=_('name')?></th>	
					<th><?=_('color')?></th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			
			<tbody>
			<?php foreach( $tag as $t ): ?>
		
			<tr id="SAVEtag<?=$t->id ?>">
				<td><div class="order ui-icon ui-icon-arrowthick-2-n-s">###</div></td>
				<td><input type="text" name="name" value="<?=$t->name ?>" <?=$t->readonly?'readonly style="color:lightGrey"':'' ?> /></td>
				<td><input class="color" name="color" value="<?=$t->color ?>" /></td>
				<td>
					<input type="hidden" name="id" value="<?=$t->id ?>" />
<!--
					<button class="save">tag|-1|SAVEtag<?=$t->id ?></button>
-->				
					<button class="ajax icosave"
						data-href="tag/save/-1" 
						data-post="SAVEtag<?=$t->id ?>" 
						data-redirect="configuration/tag" 
						data-destination="page"
					></button>

					<?php if( ! $t->readonly): ?>
					<button class="delete">tag|<?=$t->id ?></button>
					<?php else: ?>
					<button class="cantdelete"></button>
					<?php endif; ?>
				</td>
			</tr>
			
			<?php endforeach; ?>
			</tbody>

			<tfoot>
				<tr id="SAVEtag<?=$t->group ?>">
					<td>&nbsp;</td>
					<td><input class="new" type="text" name="name" value="" /></td>
					<td><input class="color" name="color" value="" /></td>
					<td>
						<input type="hidden" name="group" value="<?=$t->group ?>" />
<!--
						<button class="save">tag|-1|SAVEtag<?=$t->group ?></button>
-->
						<button class="ajax icosave"
							data-href="tag/save/-1" 
							data-post="SAVEtag<?=$t->group ?>"
							data-redirect="configuration/tag" 
							data-destination="page"
						></button>
					</td>
				</tr>
			</tfoot>
		</table>

	</div><!-- @whitespace

<?php endforeach; ?>

-->
</article>




