(function($)
{

	/*



	*/

	$.fn.pDataTable = function( options )
	{
		var opt = $.extend({
			order: [[1,'desc']],
			serverSide: true,
			processing: true,
			ajax: {
				url:'project/getlist',
			},
			columns: [
				{ data: "name" },
				{ data: "openedticket" },
				{ data: "status" },
				{ data: "date" }
			]
		}, options );

			
		return $(this).each(function()
		{
			var me = $(this).aDataTable(opt);
			
			var doSearch = function()
			{
				var val = $(this).val();
				me.search(val).draw();
			}
	
			$('body').delegate('input[name=searchfield]', 'keyup', doSearch);
	

			me.on( 'draw.dt', function()
			{
				// This is necessary for a correct footer positionning
				$(window).trigger('resize');
			})

		});
	}
	
}(jQuery));


