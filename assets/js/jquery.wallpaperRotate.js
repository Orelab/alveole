(function($)
{

	$.fn.wallpaperRotate = function( options )
	{

/*
		if( options == 'next' )
		{
			var pointer = $(this).data('wallpaper_pointer');
			wallpaperRotate();
			return;		
		}
*/

		var opt = $.extend({
			speed: 30000,
			transition: 10000,
			wplist: '#wallpapers>span',			// a node containing the images
			wplight: '#wallpapers_light>span',	// a node containing the same images, but lighter for mobile (they must gave the same name)
			start: false,								// false = start with a random image ; other : start with the given image number
			run: true
		}, options );



		/*
			List all the available wallpapers.

			We make use of this task to check if we really
			have to launch the wallpaper rotation, or not
			(if the user configured a preferred wallpaper or
			not).
		*/
		
		if( $(window).width() > 820 )
			list = opt.wplist
			else
			list = opt.wplight
			
		var wallpaper_list = $( list ).map(function(i, el)
		{
			if( $(el).data('selected') == true )
			{
				opt.start = i;
				opt.run = false;
			}
			return $(el).text();
		}).get();


		return $(this).each(function()
		{
			var me = $(this);


			//-- HTML and CSS stuff

			me.css({
				backgroundAttachment: 'fixed',
				backgroundSize: 'cover',
				position: 'relative'
			});

			me.children().css({
				zIndex: 2
			});

			$('<div/>')
				.css({
					position: 'fixed',
					top: 0,
					right: 0,
					bottom: 0,
					left: 0,
					background: 'url(assets/img/bg-connect.png) repeat fixed',
					zIndex: -1
				})
				.prependTo(me);

			var hoverme = $('<div/>')
				.css({
					position: 'fixed',
					top: 0,
					right: 0,
					bottom: 0,
					left: 0,
					backgroundAttachment: 'fixed',
					backgroundSize: 'cover',
					zIndex: -2
				})
				.prependTo(me);




			//-- Rule them all

			var pointer = typeof opt.start != 'boolean'  
				? opt.start 
				: Math.floor( Math.random() * wallpaper_list.length );

			wallpaperRotate = function()
			{
				var w = $(list).eq(pointer).html();
				
				if( pointer%2 == 0 )
				{
					me.css( 'background-image', 'url(' + w + ')' );
					hoverme.fadeOut(opt.transition);
				}
				else
				{
					hoverme.css( 'background-image', 'url(' + w + ')' ).fadeIn(opt.transition);
				}

				// move the pointer
				wallpaper_list[pointer+1] ? pointer++ : pointer=0;
			}

			wallpaperRotate();

			if( opt.run )
			{
				setInterval('wallpaperRotate()', opt.speed);
			}


			//-- run/stop control

			var autoconfRun = function()
			{
				var isChecked = $('#rollwallpaper').is(':checked');
				opt.run = isChecked;
			}
			$('body').delegate('#rollwallpaper', 'change', autoconfRun);
			autoconfRun();
		});


	}
	
}(jQuery));


