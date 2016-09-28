(function($)
{

	/*
		This is a useless code, just to play one more time with
		jQuery and make some fun stuffs with the "About" page ;)
	*/

	$.fn.about = function( options )
	{
		var opt = $.extend({
		}, options );

			
		return $(this).each(function()
		{
			var me = $(this);
			var generic = null;


			var buildGeneric = function( data )
			{
				overlay.showCustom( data );
				
				var height = $(window).height();

				generic = $('#overlay>article>div');
				generic.css('top', height-30);
				generic.animate({top:-2000}, 60000, 'linear');
			}


			var doLoad = function( e )
			{
				e.preventDefault();

				var url = me.attr('href');

				$.ajax(url).done(buildGeneric);
			}

			me.on('click', doLoad);

		});
	}
	
}(jQuery));


