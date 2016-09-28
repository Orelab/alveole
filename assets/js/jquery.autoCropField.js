(function($)
{

	/*
		This module resize the string when they seem to be too long
		according to the box size.

		The full string is first stored in the attribute data-fullstring,
		then each time the window is resized, we calculate how much 
		characters can be showed.
	
	*/

	$.fn.autocropfield = function( options )
	{
		var opt = $.extend({
			charLen:10			// How many pixels does a character width measure ?
		}, options );



		return $(this).each(function()
		{
			var me = $(this);
			var fullstring = me.text();
			
			var doCropField = function()
			{
				var numchar = me.text().length;
				var nodewidth = me.width();
				var maxchar = nodewidth / opt.charLen;
				var croppedstring = fullstring.substr(0,Math.floor(maxchar));
				var points = (numchar<=maxchar) ? '' : '..';

				me.text(croppedstring + points);
			}

			$(window).on('resize',doCropField);
			doCropField();
		});



	}


	
}(jQuery));


