





$(document).ready(function()
{
	url = $('head>link[rel=canonical]').attr('href');



	//-- wallpaper

	$('body').wallpaperRotate();



	//-- overlay

	overlay.init();



	//-- misc
	
	var sessionDuration = 14400;

	notification = function( text, timeout )
	{
		noty({
			text: text,
			layout: 'bottomRight',
			theme: 'defaultTheme',
			type: 'information',
			timeout: timeout || 10000	// 10 secondes
		});
	}




	//-- user language
	
	/*

		lang() returns something like 'fr_FR.utf8'
		lang(true) returns something like 'fr'
	
	*/
	
	lang = function( simple )
	{
		if( simple )
			return $('html').attr('lang').split('_').shift();
			else
			return $('html').attr('lang');
	}
	$.datepicker.setDefaults( $.datepicker.regional[lang(true)] );




	//-- configure custom logo
	
	var logo_url = $('#logo').data('logo');

	if( logo_url )
	{
		$('#logo').css('background', 'url('+logo_url+') no-repeat center center / 40px auto');
	}



	//-- set menu as responsive
	
	$('header>nav').responsiveMenu({width:600});





	//-- NEW LOAD
	
	/*
		Actually, the problem is that we used to differentiate the 
		load() and the save() functions. It means that, for example,
		the option "load in a dialog" available in the save function
		was unavailable in the legacy load() function...
		
		In fact, the "load in a dialog" was only available with the
		genericPopup() function :/
		
		So, here is a brand new all-in-one function, which should 
		rule'en all...
		
		
		<NODE [href||data-href]>
	*/

	var doLoad = function(e)
	{
		if( $(this).hasClass('noajax') ) return true;

		e.stopPropagation();
		e.preventDefault();

		if( notSaved() ) return false;		
		
		//-- get vars

		var slug			= $(this).attr('href') || $(this).data('href') || $(this).val();
		var controller		= slug.split('/')[0];
		var name			= $(this).attr('rel');
		var ol				= $(this).data('overlay');
		var postID			= $(this).data('post');
		var destination		= $(this).data('destination') || 'page'; // page, dialog, hard, null, close, donothing, disconnect
		var redirect		= $(this).data('redirect');

		/*
		console.log({
			slug:slug,
			controller:controller,
			name:name,
			ol:ol,
			postID:postID,
			detination:destination,
			redirect:redirect
		});
		*/

		//-- overlay

		if( typeof ol === 'undefined' )
		{
			overlay.show();
		}


		//-- post data

		var data = new FormData();
		var contains_file = false;

		if( postID )
		{
			var form = $('#' + postID ).find('input:not(.disabled),textarea:not(.disabled),select:not(.disabled)');
			progressid = Date.now();

			for( id in form )
			{
				if( ! /^\d+$/.test(id) )	// ID contains only numbers
				{
					continue;
				}

				obj = form[id];

				switch(obj.type)
				{
					case 'file':
						val = $(obj).get(0).files[0];
						contains_file = true;
						data.append( obj.name, val );
						break;

					case 'radio':
						if( $(obj).is(':checked') )
						{
							val = obj.value;
							data.append( obj.name, val );
						}
						break;

					case 'checkbox':
						val = $(obj).is(':checked') ? 'on' : 'off';
						data.append( obj.name, val );
						break;

					default:
						val = obj.value;
						data.append( obj.name, val );
						break;
				}

				if( $(obj).hasClass('notnull') && ! val )
				{
					alert('Pleaser fill-in the required fields !');
					overlay.hide();
					return;
				}
			}

			if( contains_file )
			{
				data.append('PHP_SESSION_UPLOAD_PROGRESS', progressid);
	
				overlay.showWithProgress();
				
				overlay.progressid = progressid;
				overlay.objprogress = setInterval("overlay.progression()", 2000);
			}
			else overlay.show();
		}

		$('#content').trigger('alveolePageUnloaded');


		//-- send the request

		var jqXHR = $.ajax({
			url: slug,
			data: data,
			cache: false,
			contentType: false,
			processData: false,
			timeout: 3600000,	// 1H
			type: 'POST',
			success: function(data)
			{
				//-- change the page

				if( destination == 'hard' )
				{
					/*
						Process a non ajax page loading.

						It's different from the noajax class use in the way 
						that here, we first process javascript stuff, and 
						next, we process the hard page reloading.

						For example, it is usefull on the configuration pages,
						where we save new data about wallpapers and language,
						and it requires at this moment the complete interface
						reloading.
						
						Note that we use the slug to process the previous ajax
						call, we then use redirect to process the hard reload.
						
								=== Not implemented yet ! ===
					*/
				}

				if( destination == 'disconnect' )
				{
					$.ajax({url:'dashboard/disconnect'});
					window.location.href=url;
				}

				if( redirect )
				{
					$('#controller').val(controller);
					$('#name').val(name);
					$('#slug').val(slug);

					$('#content').attr('class', controller=='gui'?'dashboard':controller);
					$('#content').load(redirect, function()
					{
						$('#content').trigger('alveolePageLoaded');
					});
					return;
				}

				if( destination == 'page' )
				{
					$('#controller').val(controller);
					$('#name').val(name);
					$('#slug').val(slug);

					$('#menu').attr('class', controller=='gui'?'dashboard':controller);
					$('#content').attr('class', controller=='gui'?'dashboard':controller);
					$('#content').html( data );
					$('#content').trigger('alveolePageLoaded');
					return;
				}

				if( destination == 'dialog' )
				{
					$('<div/>')
						.appendTo('body')
						.html(data)
						.data('slug', slug)
						.adialog({title: name});
					return;
				}

				if( destination == 'close' )
				{
					$('.ui-dialog-content').dialog( "destroy" ).remove();
				}
				
			},
			error: function(xhr, str)
			{
				console.log( xhr );
				alert('Il y a eu un problème lors de l\'enregistrement');
			},
			complete: function(xhr, str)
			{
				overlay.hide();
			}
		});
	}
	$('body').delegate('.ajax,nav a,aside a', 'click', doLoad);
	$('body').delegate('aside select', 'change', doLoad);
	$('body').delegate('header>nav>select', 'change', doLoad);	// deals with jquery.responsiveMenu.js
	//$('body').delegate('.save', 'click', doLoad);






	//-- OLD LOAD
	
	load = function( controller, name, slug, ol )
	{
		console.log( 'Deprecated way for loading a page : please use the "ajax" class instead of the "save" class.' );

		if( ol !== false )
		{
			overlay.show();
		}

		$('#content').trigger('alveolePageUnloaded');

		$('#content').load( slug, function(text, status, xhr)
		{
			$(this).attr('class', controller);
			$('#controller').val(controller);
			$('#name').val(name);
			$('#slug').val(slug);

			$('#content').trigger('alveolePageLoaded');
		});
	}




	/*
		On Alveole page load complete
	*/

	var onAlveolePageLoaded = function()
	{
		var classname = $('#content').attr('class');
		var submenu = $('#content').find('.submenu').remove();
		var title = $('#content').find('h1').remove();

		if( submenu.length )
		{
			$('#menu').attr('class', classname);
			$('#menu').html( '<h1>' + title.html() + '</h1>' );

			submenu.each(function()
			{
				$('#menu').append( $(this).html() );
			})
			aside.show( submenu.data('width') );
		}
		else aside.hide();

		$('#endsession').attr('class', sessionDuration);

		$('[data-userpref]').userpref();

		$('#content input[type=date],#content .date').datepicker({
			dateFormat:'dd-mm-yy'//'@'
		});

		$('.color').colorPicker();
		$('#datatable').aDataTable();
		$('.ui-buttonset').buttonset();
		$('.richtext').atinymce();
		$('.crop').autocropfield();

		footerPosition();
		overlay.hide();
	}

	$('body').delegate('#content', 'alveolePageLoaded', onAlveolePageLoaded);









	notSaved = function()
	{
		if( $('.changed').length )
		{
			return ( ! confirm("Vous n'avez pas enregistré vos modifications... \r\nPoursuivre quand-même ?") );
		}
		return false;
	}



	/*
		If a table contains the class .autoclickbutton,
		The click on the row triggers the click on the first found link.
	*/

	$('body').delegate('.autoclickbutton tbody tr', 'click', function(e)
	{
		e.stopPropagation();
		$(this).find('.visit,.watch,.ajax').first().trigger('click');
		return false;
	});




	/*
		Hover on menus
	*/

	$('body').delegate('nav a,aside a', 'click', function(e)
	{
		if( notSaved() ) return false;
		if( $(this).hasClass('noajax') ) return true;
		
		$(this).addClass('current').siblings().removeClass('current');
		return false;
	})




	/*
		Click on the logo
	*/

	$('#logo').on('click', function()
	{
		if( notSaved() ) return false;
		$('header a').removeClass('current');
	})







	/*
		Index loading
	*/

	$('#logo').trigger('click');
	





	/*
		save
	*/

	$('body').delegate('.save', 'click', function(e)
	{
		var contains_file = false;
		
		e.preventDefault();

		var me = $(this);
		var key = $(this).html().split('|');
		var table = key[0];
		var id = key[1];
		var dom = key[2];

		$('.ui-buttonset').buttonset('refresh');

		// http://stackoverflow.com/a/5976031
		
		var form = $('#' + dom).find('input:not(.disabled),textarea:not(.disabled),select:not(.disabled)');
		progressid = Date.now();

		var data = new FormData();
		
		$.each( form, function(id, obj)
		{
			switch(obj.type)
			{
				case 'file':
					data.append( obj.name, $(obj).get(0).files[0] );
					contains_file = true;
					break;
					
				case 'radio':
					if( $(obj).is(':checked') )
					{
						data.append( obj.name, obj.value );
					}
					break;
					
				case 'checkbox':
					data.append( obj.name, $(obj).is(':checked')?'on':'off' );
					break;
				 
				default:
					data.append( obj.name, obj.value );
					break;
			}
		});
	
		
		if( contains_file )
		{
			data.append('PHP_SESSION_UPLOAD_PROGRESS', progressid);

			overlay.showWithProgress();
			
			overlay.progressid = progressid;
			overlay.objprogress = setInterval("overlay.progression()", 2000);
		}
		else overlay.show();
		

		
		var jqXHR = $.ajax({
			url: table+'/save/'+id,
			data: data,
			cache: false,
			contentType: false,
			processData: false,
			timeout: 3600000,	// 1H
			type: 'POST',
			success: function(data)
			{
				if( data==parseInt(data) )
				{
					if( contains_file )
					{
						notification('Enregistrement terminé !');
					}
				}
				if( me.data('destination') )
					load( $('#controller').val(), $('#name').val(), me.data('destination') );
					else
					load( $('#controller').val(), $('#name').val(), $('#slug').val() );
			},
			error: function(xhr, str)
			{
				console.log( xhr );
				alert('Il y a eu un problème lors de l\'enregistrement');
			},
			complete: function(xhr, str)
			{
				overlay.hide();
			}
		});

	});





	//-- checkbox save
	
	$('body').delegate('input.cbsave', 'change', function()
	{
		overlay.show();
		
		data = $(this).attr('name').split('|');
		
		$.post( data[0]+'/save/',{
			key1:	data[1],
			key2:	data[2],
			value:	$(this).is(':checked') ? 1 : 0
		}, function(data){
				if( data == parseInt(data) )
					load( $('#controller').val(), $('#name').val(), $('#slug').val() );
					else
					alert('Erreur lors de la sauvegarde');

			overlay.hide();
		});
	});


	
	
	//-- delete
	
	$('body').delegate('.delete,.del', 'click', function()
	{
		if( ! confirm('Supprimer, vraiment ?') ) return;
		
		overlay.show();
		
		var key = $(this).html().split('|');
		var controller = key[0];
		var id = key[1];
	
		$.ajax({
			url: controller+'/delete/'+id,
			data:{id:id},
			type: 'POST',
			success: function(data)
			{
				if( data == parseInt(data) )
					load( $('#controller').val(), $('#name').val(), $('#slug').val() );
					else
					alert('Erreur lors de la suppression');
			},
			complete: function(xhr, str)
			{
				overlay.hide();
			}
		});
	});

	
	
	
	//-- link
	
	$('body').delegate('.link', 'click', function()
	{
		var url = $(this).html().substr(0,4)=='http' 
						? $(this).html() 
						: 'http://' + $(this).html();
						 
		window.open( url );
	});


	

	
	//-- About
	
	$('footer .about').about();
	
	
	

	
	
	//-- authorization : project/view management

	$('body').delegate('.right select[name=controller]', 'change', function()
	{
		var controller = $(this).val();
		var views = $(this).parent().parent().find('select[name=view]');

		views.find('option').css('display','none');
		views.find('option[value^='+controller+']').css('display','block');
	});
	
	
	
	//-- red frame on change
	
	$('#content:not(nav)').delegate('input:not([type=search]),select,textarea', 'change', function()
	{
	//	$(this).addClass('changed');
	});
	


	//-- advertisement on disabled form fields
		
	$('body').delegate('.disabled',
	{
		keypress : function()
			{
				return false;
			},
		mouseenter : function()
			{
				$(this).data('value', $(this).val() );
				$(this).val('Ce champ n\'est pas modifiable');
			},
		mouseleave : function()
			{
				$(this).val( $(this).data('value') );
			}
	});




	//-- page reload
	
	/*
		If ol is false, there will be no overlay during reloading
		If anything else (null, undefined, 1 true, 0, potato...), the overlay will be present
	*/
	doReload = function( ol )
	{
		if( notSaved() ) return false;
		load( $('#controller').val()=='gui'?'dashboard':$('#controller').val(), $('#name').val(), $('#slug').val(), ol );
		return false;
	}
	
	$('#pagereload').on('click', doReload);



	//-- session countdown

	decreaseSessionCountdown = function()
	{
		seconds = $('#endsession').attr('class') - 1;
		
		if( seconds < 0 )
		{
				setTimeout("document.location.href='.'", 3000);
				clearInterval(dsc);
		}
		else
			$('#endsession').attr('class',seconds).html( 'Votre session va expirer dans <b>' + seconds + ' secondes</b>.' );
	}

	$('#endsession').attr('class',sessionDuration);
	//var dsc = setInterval("decreaseSessionCountdown()", 1000);





	//-- user config
	
	$('body').delegate('#SAVEuserConfig .drop', 'click', function()
	{
		if( confirm('Vraiment ?') )
		{
			$(this).parent().parent().remove();
		}
	});



	//-- generic popup
	
	genericPopup = function( slug, name )
	{
		slug.stopPropagation();

		if( typeof slug !== 'string' )
		{
			slug = $(this).html().replace(/\|/g,'/');
			name = $(this).attr('name');
		}

		$('<div/>')
			.appendTo('body')
			.load(slug)
			.data('slug', slug)
			.adialog({title: name});
	}
	$('body').delegate('.watch', 'click', genericPopup);



	//-- footer position
	
	footerPosition = function()
	{
		$('main').css('height', 'auto');
		
		var w = $(window).height();
		var h = $('header').outerHeight();
		var m = $('main').outerHeight();
		var f = $('footer').outerHeight();
		
		if( h+m+f < w )
		{
			$('main').css('height', w-h-f);
		}
	}
	$(window).on( 'resize', footerPosition );



});






