


$(document).ready(function()
{

	$('body').delegate('#content.contact', 'alveolePageLoaded', function()
	{



		//-- make meta contact sortable

			var saveOrder = function(ev, ui)
			{
				var order = JSON.stringify( $(this).sortable('toArray') );
				
				$.ajax({
					url: 'contact/meta/save_order',
					method: 'post',
					data: { order: order }
				});
			}

			var opt = {
				handle:'.order',
				cancel: '',					// prevent a bug with handle:button
				update: saveOrder
			};

			$('article.contact .ui-sortable').sortable(opt);




			//-- make meta contact dynamic

			$('article.contact select[name="field"]').on('change', function()
			{
				var type = $(this).val();
				var key = $(this).find('option:selected').text();

				switch( type )
				{
					case 'textarea' :
						$(this).next().replaceWith('<textarea name="value"></textarea>');
						$(this).next().next().val(key);
						break;

					case 'text' :
					case 'url' :
					case 'email' :
					case 'date' :
						$(this).next().replaceWith('<input type="' + type + '" name="value" value="" />');
						$(this).next().next().val(key);
						break;

					default :
						return;
				}
			});

	});
	
});