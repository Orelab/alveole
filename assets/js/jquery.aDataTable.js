(function($)
{

	/*
		This plugin is just a container for the jQuery dataTables plugin.
		It allows us to pre-configure some stuffs, and set a custom search
		INPUT field.
		 
		Please, note that it is always possible to erase theses configurations
		at use.
		Example : $('box').aDataTable({paging:false}); 
	*/

	$.fn.aDataTable = function( options )
	{
		var opt = $.extend({
			paging:true,
			searching: true,
			autoWidth: false,
			info: false,
			iDisplayLength: 20,
			language:{url:'assets/js/datatable_' + lang(true) + '.json'}
		}, options );
			
		return $(this).each(function()
		{
			var me = $(this);
			var dt = me.DataTable(opt);
			
			var doSearch = function()
			{
				var val = $(this).val();
				dt.search(val).draw();
			}
	
			$('body').delegate('input[name=searchfield]', 'keyup', doSearch);
	

			dt.on( 'draw.dt', function()
			{
				//-- Highlight searched text

				var str = $('input[name=searchfield]').val();
				var searched = new RegExp( '('+str+')', "gi" );
				
				me.find('a').each(function(e)
				{
					$(this).html( str
						? $(this).text().replace(searched, '<strong>$&</strong>')
						: $(this).text()
					);
				});
				

				//-- This is necessary for a correct footer positionning

				//$(window).trigger('resize');
				footerPosition();
			})

		});
	}
	
}(jQuery));


