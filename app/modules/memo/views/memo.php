<?php

	$caution = _("Drag your memos here to delete them.");
	$memo = _("Memo");

?>

<article class="task half">
	<h2><?=$memo?> <button class="trash" title="<?=$caution?>"></button></h2>

	<div class="memo-list">
	
		<?php foreach( $tasks as $t ): ?>
		
		<div id="SAVEtask<?=$t->id ?>" class="memo memo<?=$t->priority ?>">
			<input type="hidden" name="priority" value="<?=$t->priority ?>" />
			<input type="hidden" name="id" value="<?=$t->id ?>" />
			<input type="hidden" name="fk_user" value="<?=$t->fk_user ?>" />
			<!--<button class="delete">memo|<?=$t->id ?></button>-->
			<div class="grip">|||</div>
			<textarea name="task" class="task"><?=$t->task?></textarea>
		</div>
		
		<?php endforeach ?>

	
		<div id="SAVEtaskNew" class="memo">
			<input type="hidden" name="priority" value="36" />
			<input type="hidden" name="fk_user" value="<?=get_instance()->session->login ?>" />
			<textarea name="task"></textarea>
		</div>

	</div>
	
</article>

