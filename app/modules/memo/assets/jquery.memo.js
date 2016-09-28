(function($)
{

	$.fn.memo = function()
	{

		var dropped = false;
		var draggable_sibling;
		
		var me = $(this);
		
		$(this).sortable({
			handle:'.grip',
			start: function(ev,ui)
			{
				draggable_sibling = $(ui.item).prev();
			},
			stop: function(ev,ui)
			{
				if(dropped)
				{
					if (draggable_sibling.length == 0)
					{
						$(this).prepend(ui.item);
					}
					draggable_sibling.after(ui.item);
					dropped = false;
				}
				ui.item.removeClass('willremove');
			},
			update:function(ev,ui)
			{
				var order = me.sortable('toArray');
				order.pop();
				order = JSON.stringify( order );

				$.ajax({
					url: 'memo/save_order',
					method: 'post',
					data: { order: order }
				});
			}
		});
		
		$(".trash").droppable({
			activeClass: 'active',
			hoverClass:'hovered',
			activeClass: 'imhere',
			tolerance: 'touch',
			drop:function(ev,ui)
			{
				dropped = true;
				var id = ui.draggable.find('input[name=id]').val();
				ui.draggable.find('.delete').trigger('click');
			},
			over: function(ev,ui)
			{
				ui.draggable.addClass('willremove');
			}
		});
 
    
    

		$(this).children('div').each(function()
		{
			var me = $(this);

			
			//-- expend the memo
			
			doIncrease = function()
			{
			//	me.addClass('animation');
			}
			
			onIncrease = function()
			{
				var inc = setTimeout( doIncrease, 1000 );
				$(this).data('inc', inc);
			}
			
			onDecrease = function()
			{
				var inc = $(this).data('inc');
				clearTimeout( inc );

				me.removeClass('animation');
			}
			
			$(this)
				.css({'transform': 'rotate(' + (Math.random()*8-4) + 'deg)'})
				.on({
					mouseenter: onIncrease,
					mouseleave: onDecrease,
					mouseout: onDecrease
				});
			
			
			//-- autosave the memo

			$(this).find('textarea').on('change', function()
			{
				var id = me.attr('id').replace('SAVEtask','');
				var form = me.find('input,textarea,select').serialize();
				
				console.log( form );

				var jqXHR = $.ajax({
					url: 'memo/save/'+id,
					data: form,
					type: 'post',
					success: function(data)
					{
						doReload(false);	// false means no overlay during reloading
					},
					error: function(xhr, str)
					{
						console.log( xhr );
						alert('Il y a eu un problème lors de l\'enregistrement du mémo.');
					}
				});
			});
			
			
			//-- change the memo's color
			
			$(this).find('textarea').on('dblclick', function()
			{
				var priority = me.find('input[name=priority]');
				
				switch( priority.val() )
				{
					case '35': priority.val('36'); break;
					case '36': priority.val('37'); break;
					default:
					case '37': priority.val('35');
				}
				
				me.find('textarea').trigger('change');
			});
		});
		
		
		return $(this);

	}

}(jQuery))



