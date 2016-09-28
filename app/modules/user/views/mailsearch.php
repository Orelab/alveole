<?php /*


	It is necessary to biuild a specific search engine for user
	mails, because the generic search engine is populated with
	userpref data. It means that it is impossible to preset the
	search engine with the user email address.

*/ ?>


<div id="neowebmail_filter" class="dataTables_filter">

	<label>
		<?=_('search')?>&nbsp;:
		<input type="search" id="neowebmail_search" name="searchfield" value="<?=$address ?>" />
		<br/>
		<br/>
		<p><?=_('search in')?>&nbsp;:</p>
		<input type="checkbox" id="from" name="location[0]" value="from" checked="checked" />
		<label for="from"><?=_('from')?></label>
		
		<input type="checkbox" id="to" name="location[1]" value="to" />
		<label for="to"><?=_('to')?></label>

		<input type="checkbox" id="subject" name="location[2]" value="subject" />
		<label for="subject"><?=_('subject')?></label>

		<span>
			<label for="radio1"><?=_('unread')?></label>
			<input type="radio" id="radio1" name="msgorder" value="0" />
			
			<label for="radio2"><?=_('all')?></label>
			<input type="radio" id="radio2" name="msgorder" value="2" checked="checked" />
		</span>
	</label>

</div>