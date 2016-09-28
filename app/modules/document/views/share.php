<?php


function can_access( $uid, $did, $arr )
{
	
	foreach( $arr as $s )
	{
//echo "test $uid+$did<br/>";
		if( $s->fk_user==$uid && $s->fk_document==$did ) return true;
	}
	return false;
}

?>



<article class="share">
	<h2><?=_('shares')?></h2>
	
	<?php foreach( $user as $u ): 
	
		$name = $u->uname.' '.$u->usurname;
		?>
		<input type="checkbox" class="cbsave" id="user<?=$u->id?>" name="share|<?=$id?>|<?=$u->id?>" <?=can_access($u->id,$id,$share) ? 'checked' : '' ?> />
		<label for="user<?=$u->id?>"><?=$name ?></label>
		<a href="user/index/<?=$u->id?>" class="ajax" rel="<?=$name ?>"><?=_('see')?></a>
		<br/>
	
	<?php endforeach ?>
	
	<hr/>
	
	<input type="checkbox" class="cbsave" id="public" name="share|<?=$id?>|*" <?=can_access('*',$id,$share) ? 'checked' : '' ?> />
	<label for="public"><?=_('public acces')?></label>
	
</article>
	