

<article class="log">
	<h2><?=$title ?></h2>


	<table>
		<colgroup>
			<col class="col-one">
			<col class="col-two">
			<col class="col-three">
			<col class="col-four">
			<col class="col-five">
		</colgroup>
		
		<tr>
			<th><?=_("tag")?></th>
			<th><?=_("date")?></th>
			<th><?=_("description")?></th>
			<th><?=_("file")?></th>
			<th></th>
		</tr>
		
		<?php foreach( $data as $r ): ?>
		<tr id="SAVElog<?=$r->id ?>">
			<td>
				<select name="fk_step"><?=optionsHtml( $tag, $r->fk_step ) ?></select>
			</td>
			<td><input type="text" class="date" name="date" value="<?=date('d-m-Y', (integer)$r->date) ?>" /></td>
			<td>
				<textarea name="text"><?=$r->text ?></textarea>
			</td>
			<td>
				<?php if( $r->file ): ?>
				<a href="<?=base_url() ?>document/get/<?=$r->fk_document ?>" download="<?=$r->file ?>" target="_blank" class="downloadable">
					<?=@array_pop(explode('/',$r->type)) ?>
				</a>
				<?php endif ?>
			</td>
			<td>
				<input type="hidden" name="id" value="<?=$r->id ?>" />
				<input type="hidden" name="fk_ressource" value="<?=$r->fk_ressource ?>" />
				<input type="hidden" name="ressource" value="<?=$r->ressource ?>" />
				
				<?php /*
				<button class="save">project/log|<?=$r->id ?>|SAVElog<?=$r->id ?></button>
				*/ ?>
				<button class="ajax icosave" 
					data-href="project/log/save/<?=$r->id ?>" 
					data-post="SAVElog<?=$r->id ?>" 
					data-destination="donothing"
				>SAVE</button>
			</td>
		</tr>
		<?php endforeach; ?>
		
		<tr id="SAVElogNew">
			<td>
				<select name="fk_step" class="new">
					<?=optionsHtml( $tag, '') ?>
				</select>
			</td>
			<td><input type="text" class="date" name="date" value="" class="new" /></td>
			<td>
				<textarea name="text" class="new"></textarea>
			</td>
			<td>
				<!--
				<input type="file" name="fk_document" value="document|<?=$ressource ?>|<?=$id ?>" class="new addressource" />
				<button name="fk_document" value="" class="styled-file addressource">Fichier<span>document|<?=$ressource ?>|<?=$id ?></span></button>
				<input type="hidden" name="MAX_FILE_SIZE" value="20000000"> <?php /* en octets */ ?>
				-->
				<?=fileHtml( 'file', '', '' ) ?>
			</td>
			<td>
				<input type="hidden" name="fk_ressource" value="<?=$id ?>" />
				<input type="hidden" name="ressource" value="<?=$ressource ?>" />

				<?php /*
				<button class="save">project/log|-1|SAVElogNew</button>
				*/ ?>
				<button class="ajax icosave" 
					href="" 
					data-href="project/log/save" 
					data-post="SAVElogNew" 
					data-redirect="project/project/log/<?=$id ?>" 
					data-destination="page"
					data-overlay=""
				>SAVE</button>
			</td>
		</tr>
	</table>
	
</article>

