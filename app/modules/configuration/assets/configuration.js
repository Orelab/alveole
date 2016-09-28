
$(document).ready(function()
{


	/*
	
		Sortable tags

		Wallpaper selector
			
	*/

	$('body').delegate('#content.configuration', 'alveolePageLoaded', function()
	{
		$('.configtag tbody:first-of-type').aSortable();

		$('#content.configuration .wallpapers')
			.find(':radio')
			.each(function(){
				var input = $(this);
				
				if( input.attr('id') == 'rotate' )
					input.button();
					else
					input.button({ label: '<img src="' + url + 'assets/img/wallpapers_light/' + input.val() + '" />' });
			})
	});




	/*

		Tag selector
		
		This is actually disabled and useless as we prefer to draw all 
		the tags in one simple page.
	
	*/

	var tagSelector = function()
	{
		$(this).parents('section').find('.half').hide();
		
		/*
			Testing the number of arguments allow us to know if we are in the context of a
			jQuery object ( delegate ) or if the function is called directly ( tagSelector() )
		*/
		if( arguments.length ) 
		{
			var tag = $(this).val();
			$(this).parents('section').find('.' + tag).show();
		}
	}

	$('body').delegate('#content.configuration select[name=configuretag]', 'change', tagSelector);
	tagSelector();





});