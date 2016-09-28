

$(document).ready(function()
{
	
	
	find = function()
	{
		switch($(this).attr('id') )
		{
			case 'google'		: url = 'https://www.google.fr/#q='; break;
			case 'bing'			: url = 'http://www.bing.com/search?q='; break;
			case 'yahoo'		: url = 'https://fr.search.yahoo.com/search?p='; break;
			case 'duckduckgo'	: url = 'https://duckduckgo.com/?q='; break;
		}
		var query = $('#ilquery').val();
		//$('#ilquery').val('');
		
		window.open( url + query );
	}
	
	upButton = function(){ $(this).css('backgroundColor', 'white') }
	downButton = function(){ $(this).css('backgroundColor', 'lightGrey') }
	
	$('body').delegate('#search.engine button', {
		click : find,
		mousedown : downButton,
		mouseup : upButton,
		mouseenter : downButton,
		mouseleave : upButton
	});
	


});

