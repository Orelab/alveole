
(function($)
{

	/*
		This plugin is made to save automatically the state 
		of some fields when they are changed, as in this way,
		they will appear with the same previous value as the 
		previous time it was changed.
		
		This works with a server page which is in charge of the 
		recording of the values in the user preferences.
	
		To work properly, the given node should implement the
		attribute data-userpref (which contains the key) :
		
		ex : <input data-userpref="akey" value="a val" [...] />
	*/

	$.fn.userpref = function( options )
	{
		var opt = $.extend({
		}, options );

		var me = $(this);


		//-- loading last user values

		var nodelist = $(this);
		var keys = [];

		$.each( nodelist, function( i, obj )
		{
			var key = $(obj).data('userpref');

			if( key )
			{
				keys.push( key );
			}
		});
	
		var getType = function( obj )
		{
			if( $(obj).is('select') ) return 'select';
			if( $(obj).is('textarea') ) return 'textarea';
			if( $(obj).is('[type=checkbox]') ) return 'checkbox';
			if( $(obj).is('[type=radio]') ) return 'radio';
			if( $(obj).is('input') ) return 'text';

			return false;
		}

		
		var doSave = function()
		{
			var key = $(this).data('userpref');
			var val = $(this).val();
		
			switch( getType(this) )
			{
				case 'checkbox':
				case 'radio':
					val = $(this).is(':checked');
					break;
				
				case 'text':
				case 'textarea':
				case 'select':
					val = $(this).val();
					break;
					
				default: return; 
			}

			$.ajax({
				url: 'user/save_userpref',
				method: 'post',
				data: {
					key: key,
					val: val
				}
			}).done(function(data)
			{
				if( data != '1' )
				{
					console.log( 'userpref saving error' );
				}
			});
		}


		var doLoad = function( data )
		{
			$.each( nodelist, function( i, obj )
			{
				var key = $(obj).data('userpref');

				if( typeof data[key] != 'undefined' )
				{
					switch( getType(obj) )
					{
						case 'checkbox':
						case 'radio':
							$(obj)
								.prop('checked', data[key]=='true' )
								//.trigger('change');
							break;
						
						case 'text':
						case 'textarea':
						case 'select':
						$(obj)
							.val( data[key] )
							//.trigger('keyup');
							break;
							
						default: return; 
					}

					/*
						temporary patch
					*/

					if( $(obj).attr('name') == 'searchfield' )
					{
						var that = $(this);

						ohplease = function()
						{
							that.trigger('keyup');
						}
						setTimeout( ohplease, 100 );
					}
				}
			});

			//-- autosaving modified values

			me.on('change', doSave);
		}

		$.ajax({
			url: 'user/get_userpref',
			method: 'post',
			dataType: 'json',
			async: false,
			data: { keylist: JSON.stringify(keys) }
		})
			.done(doLoad);






		return $(this);
	}

}(jQuery));



