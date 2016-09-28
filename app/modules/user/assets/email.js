
$(document).ready(function()
{

	if( typeof newmailPopup !== 'undefined' )
	{
		$('body').delegate('#menu.user .special', 'click', newmailPopup);
	}



});