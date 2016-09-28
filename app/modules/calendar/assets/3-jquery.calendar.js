(function($)
{

	$.fn.calendar = function( options )
	{
		
		var opt = $.extend({
			date: 'DD-MM-YYYY HH:mm ZZ'	// It's important to have timezone in it (ZZ)
		}, options );

		
		
		return this.each(function(e)
		{
			var me = $(this);
			
			var myevent = null;					// currently editing event



			//
			//		Moment() to UnixTime conversion
			//		unexpected fields removing
			//	
			save = function()
			{
				var sanitized = {};
				allowed_keys = ['id','title','description','start','end','recurrence','interval','recend','fk_step','fk_user','allDay'];

				for( prop in myevent )
				{
					if( allowed_keys.indexOf(prop) < 0 ) continue;
					
					switch( prop )
					{
						case 'start':
							if( myevent.start )
							{
								sanitized.start = myevent.start.format('X');
							}
							break;
							
						case 'end':
							if( myevent.end )
							{
								sanitized.end = myevent.end.format('X');
							}
							break;
							
						case 'recend':
							if( myevent.recend )
							{
								sanitized.recend = myevent.recend.format('X');
							}
							break;
							
						case 'allDay':
							sanitized.allDay = ( myevent[prop]==true ? 1 : 0 );
							break;

						default:
							if( myevent[prop] )
							{
								sanitized[prop] = myevent[prop];
							}
					}
				}
				
				$.post('calendar/save', sanitized, function( id )
				{
					if( parseInt(id)=='NaN' )
						alert('Erreur d\'enregistrement, Veuillez recharger cette page');
						else
						myevent.id = id;

					me.fullCalendar( 'refetchEvents' );
				})
			}



			shakeItBaby = function( event, jsEvent, view)
			{
				$(this).effect( 'shake', {}, 500/*, callback*/ );
				return false;	// prevent visit eventData.url tag
			}



			updateEvent = function( event )
			{
				myevent = event;

				if( ! myevent.title || ! myevent.fk_step )
					alert( 'Veuillez saisir au minimum un titre et sélectionner une étiquette !' );
					else
					save();
			}



			removeEvent = function()
			{
				me.fullCalendar('removeEvents', myevent.id );
				$.post('calendar/delete', {id:myevent.id} );
				
				$(this).dialog('destroy').remove();
			}



			saveEvent = function()
			{
				delete myevent.allDay;

				$.each( $(this).serializeArray(), function( key, obj )
				{
					name = $(obj).attr('name');
					value = $(obj).attr('value');
									
					if( name=='allDay' ) value = true;
					if( name=='fk_step' ) myevent.className = 'color' + value;
					if( name=='start' ) value = moment( value, opt.date );
					if( name=='end' ) value = moment( value, opt.date );
					if( name=='recend' ) value = moment( value, opt.date );

					myevent[name] = value;
				})

				if( myevent.id>0 )
					me.fullCalendar('updateEvent', myevent );
					else
					me.fullCalendar('renderEvent', myevent );


				if( ! myevent.title || ! myevent.fk_step )
				{
					alert( 'Veuillez saisir au minimum un titre et sélectionner une étiquette !' );
					return;
				}
				else
				{
					save();
				
					me.fullCalendar('unselect');
					$(this).dialog('destroy').remove();
				}
			}
			
			
			afterOpenDialog = function(xhr, html)
			{
				var dialog = $(xhr.responseText);

				dialog.appendTo('body');
				
				if( ! myevent.id )
				{
					// It's a new event, we have to initialize it 
					
					for( i in myevent )
					{
						switch(i)
						{
							case 'start':
							case 'end':
								dialog.find('[name='+i+']').val( (myevent[i]).format(opt.date) );
								break;

							case 'recend':
								break;

							default:
								dialog.find('[name='+i+']').val( myevent[i] );
							
						}
					}
				}

				if( myevent.readonly )
					but = {};
					else
					but = {'Supprimer':removeEvent,'Enregistrer':saveEvent};

				dialog.adialog({
					title: 'Événement',
					buttons: but
				});

				dialog.find('input[type=datetime-local]').datetimepicker({
					lang: 'fr',
					format: 'd-m-Y H:i O',
					step: 30,
					dayOfWeekStart: 1,
				//	mask: true
				});

				dialog.find('.richtext').tinymce({
					menubar: false,
					statusbar: false,
					language: lang(),
					toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code',
					plugins: ['link','image','code'],
					setup: function(ed){
						ed.on('change', function(ed){
							tinyMCE.triggerSave();
						})
					}
				});
			}




			eventForm = function()
			{
				// When triggered by eventClick : event, jsEvent, view	=> update event
				// When triggered by select : start, end, jsEvent, view	=> new event

				if( arguments.length==4 )
					myevent = {title:'',description:'',start:arguments[0],end:arguments[1],recurrence:'none',interval:0,recend:arguments[2],fk_step:'',fk_user:'',allDay:0};
					else
					myevent = arguments[0];

				var box = $.ajax({
					url: 'calendar/getEvent',
					method: 'post',
					dataType: 'html',
					data:{id:myevent.id},
					complete: afterOpenDialog
				});
			}



			getEvents = function( start, end, timezone, callback )
			{
				$.ajax({
					url: 'calendar/getCal',
					method: 'post',
					dataType: 'json',
					data: {
						start: start.unix(),
						end: end.unix(),
						cal: $('#menu input[type=checkbox]').serialize()
					},
					success: function(data)
					{
						for( e in data )
						{
							data[e].start = moment( data[e].start, 'X' ).format();
							data[e].end = moment( data[e].end, 'X' ).format();
							data[e].recend = moment( data[e].recend, 'X' );
							data[e].className = data[e].group + data[e].fk_step;
							data[e].allDay = parseInt( data[e].allDay );
						}
						callback(data);
					}
				});
			}

			me.fullCalendar({
				header: me.hasClass('jour') ? null : {
					left: 'prev,next today',
					center: 'title',
					right: 'year,month,agendaWeek,basicDay'
				},
				defaultView: me.hasClass('jour') ? 'basicDay' : 'agendaWeek',
				firstDay: 1,
				lang: lang(true),
				selectable: me.hasClass('jour') ? false : true,
				selectHelper: me.hasClass('jour') ? false : true,
				select: eventForm,
				eventClick: me.hasClass('jour') ? false : eventForm,
				eventDrop: me.hasClass('jour') ? false : updateEvent,
				eventResize: me.hasClass('jour') ? false : updateEvent,
				editable: me.hasClass('jour') ? false : true,
				eventLimit: me.hasClass('jour') ? false : true,
				events: getEvents,
				eventAfterAllRender: footerPosition,	// reposition footer
				timezone: 'UTC',
				
				scrollTime: '08:00',
				height: me.hasClass('jour') ? 'auto' : 700,
/*
				businessHours: {
					start:'09:00',
					end: '17:00'
				},

				minTime: '05:00',
				maxTime: '23:00'
*/
			});




			//-- Force basicDay view on little screens

			var calendarDoResize = function()
			{
				if( $(window).width() <= 820 )
					me.fullCalendar( 'changeView', 'basicDay' );
					else
					me.fullCalendar( 'changeView', me.hasClass('jour') ? 'basicDay' : 'agendaWeek' );
			}
			//$(window).resize(calendarDoResize);	// intempestive changings if uncommented :/
			calendarDoResize();



			$('body').delegate('aside.calnav label', 'dblclick', function()
			{
				$(this)
					.parents('aside')
					.find('input[type=checkbox]')
					.prop('checked', false);

				$(this)
					.trigger('click')
					.trigger('change');
			});

			$('body').delegate('aside.calendar input', 'change', function()
			{
				me.fullCalendar( 'refetchEvents' );
			});
	
		});
	}


}(jQuery));



