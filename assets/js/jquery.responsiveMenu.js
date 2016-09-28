(function($)
{

	/*
		This plugin assume that you set it on a NODE which contains some A links 

	*/

	$.fn.responsiveMenu = function( options )
	{

		var opt = $.extend({
			width: 820
		}, options );



		return $(this).each(function()
		{
			var me = $(this);
			var select;


			var buildHtml = function( node )
			{
				select = $('<select name="responsiveMenu"></select');
				
				node.children().each(function(i,e)
				{
					var url = $(e).attr('href');
					var name = $(e).html();
	
					select.append('<option value="'+url+'">'+name+'</option>');
				});

				select.appendTo(node);
			}
	
	
			var showHide = function()
			{
				if( $(window).width() <= opt.width )
				{
					me.find('select').show();
					me.find('a').hide();
				}
				else
				{
					me.find('select').hide();
					me.find('a').show();
				}
			}


			buildHtml(me);
			showHide();

			$(window).on('resize', showHide);
			

			/*
				Reset the select value so that whatever the event 'change' 
				will be triggered whatever the use select.
				In this way, it becomes possible to reload the current page,
				where the 'change' event don't trigger on this case.
			*/
			select.on('focus',function(ev)
			{
				select.val('');
			});

		});

	}
	
}(jQuery));


