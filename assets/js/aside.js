
	
	aside = {
		show: function( width, speed )
		{
			var w = width ? parseInt(width) : 350;
			var s = speed ? parseInt(speed) : 500;

			if( $('#menu').css('width')!= w+'px' )
			{
				$('aside').show().stop().animate({width:w}, speed);
				$('main').stop().animate({backgroundPositionX:w-1000}, speed);
				$('#content').stop().animate({paddingLeft:w+30}, speed);
			}
		},

		hide: function( speed )
		{
			var w = parseInt( $('#menu').css('width').replace('px','') );
			var s = speed ? parseInt(speed) : 500;

			if( $('#menu').css('width')!='0px' )
			{
				$('aside').stop().animate({width:0}, speed, function(){ $(this).hide() });
				$('main').stop().animate({backgroundPositionX:-1000}, speed);
				$('#content').stop().animate({paddingLeft:30}, speed);
			}
		}
	};
