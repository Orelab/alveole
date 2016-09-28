


<h1><?=_('calendar') ?></h1>


<nav class="submenu calnav">
	<?php foreach( $generictag as $p ): ?>
	
	<p>
		<input type="checkbox" id="project<?=$p->id ?>" 
				name="tag[]" value="<?=$p->id ?>" 
				data-userpref="caltag<?=$p->id ?>" checked="checked"/>

		<label for="project<?=$p->id ?>"><?=$p->name ?></label>
	</p>
	
	<?php endforeach ?>
	
	<hr/>
	
	<?php foreach( $projecttag as $p ): ?>
	
	<p>
		<input type="checkbox" id="project<?=$p->id ?>" 
				name="tag[]" value="<?=$p->id ?>"
				data-userpref="caltag<?=$p->id ?>" checked="checked"/>

		<label for="project<?=$p->id ?>"><?=$p->name ?></label>
	</p>
	
	<?php endforeach ?>
</nav>





<article class="calendrier">
</article>


