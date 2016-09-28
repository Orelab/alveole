(function($)
{

	/*
		This plugin is just a container for the jQuery UI Sortable plugin.
		It allows us to pre-configure some stuffs, and set a custom search
		INPUT field.
		 
		Please, note that it is always possible to erase theses configurations
		at use.
	*/

	$.fn.aSortable = function( options )
	{
			
		return $(this).each(function()
		{
			var me = $(this);
	
			var saveOrder = function(ev, ui)
			{
				var order = JSON.stringify( me.sortable('toArray') );
				
				$.ajax({
					url: 'tag/saveorder',
					method: 'post',
					data: { order: order }
				});
			}

			var opt = $.extend({
				handle:'.order',
				update: saveOrder
			}, options );

			me.sortable(opt);
		});
	}
	
}(jQuery));


