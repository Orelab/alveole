
<h1><?=$name ?></h1>


<nav class="submenu user">
	<p><a href="user/card/<?=$id?>"><?=_('user card')?></a></p>
	<p><a href="user/getRole/<?=$id?>"><?=_('roles')?></a></p>
	<p><a href="user/email/<?=$id?>"><?=_('mails')?></a></p>
	<p><a href="user/password/<?=$id?>"><?=_('password')?></a></p>
<?php /*
	<p><a href="<?=$mail ?>" class="noajax special newmail"><?=_('send a mail')?></a></p>
*/ ?>
</nav>