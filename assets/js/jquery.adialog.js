(function($)
{

	/*
		This plugin is just a container for the common jQuery-ui dialog plugin.
		It allow us to pre-configure some stuffs we like :
		 - the box size fits automatically to the page when redim the page
		 - a close button is added in the bottom panel
		 - we like modal boxes
		 
		Please, note that it is always possible to erase theses configurations
		at use.
		Example : $('box').adialog({modal:false}); 
	*/

	$.fn.adialog = function( options )
	{
		var calc_width = function()
		{
			return $(window).width() * ( $(window).width()>820 ? 0.75 : 0.95 );
		}

		var calc_height = function()
		{
			var height = $(window).height() * ( $(window).height()>820 ? 0.75 : 0.95 );
			return height > 500 ? height : 500;
		}


		var opt = $.extend({
			draggable: false,
			width: calc_width(),
			height: calc_height(),
			modal: true,
			close: function(ev,ui){ $(this).dialog('destroy').remove() },
			buttons: {'Fermer':function(){$(this).dialog('destroy').remove()}},
			
			// closeOnEscape() does the trick 
			// (but destroy the dialog instead of simply hiding it)
//			closeOnEscape: false
			
		}, options );


		var me = $(this);

		me.find('.crop').autocropfield();

		var doResize = function()
		{
			if( me.dialog() )
			{
				me.dialog({
					width: calc_width(),
					height: calc_height(),
				});
			}
		}
//		$(window).on('resize', doResize);


		var destroyOnEscape = function( ev )
		{
			if( ev.keyCode === $.ui.keyCode.ESCAPE )
			{
				$(this).dialog('destroy').remove();
			}                
			ev.stopPropagation();
		}


		return $(this)
			.appendTo('body')
			.dialog( opt )
//			.on('keydown', destroyOnEscape);

	}
	
}(jQuery));


