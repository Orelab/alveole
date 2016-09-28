

$(document).ready(function()
{



/*
	// When a click is made on a table row, we try to find a link and fake a click on it.
	//	This is replaced by autoclickbutton

	$('body').delegate('article.ticket tbody tr', 'click', function(e)
	{
		e.stopPropagation();
		$(this).find('.ajax').trigger('click');
		return false;
	});
*/




	//-- API key generation
	
	$('body').delegate('.apigen', 'click', function()
	{
		var api = $(this).parent().parent().find('input[name=apikey]');
		var key = '';
		var values = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

		for( var i=0 ; i<=24 ; i++ )
		{
			key += values.charAt(Math.floor(Math.random() * values.length));
		}
		
		api.val( key );
		return false;
	});



	$('body').delegate('#SAVElogNew.historic .save', 'click',function(e)
	{
		var f = $(this).parent().parent().find('textarea,select,input.date').toArray();

		for( o in f )
		{
			if( ! $(f[o]).val() )
			{
				alert( 'Please fill-in all the fields' );
				e.stopImmediatePropagation();
				return false;
			}
		}
	});




});