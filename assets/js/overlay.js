

	overlay = {
		init:function()
		{
			$('body').delegate('#overlay', 'click', function()
			{
					$(this).remove();
			});
			
			$('body').delegate('#progressbtn .cancel','click', function(e)
			{
				e.stopPropagation();

				if( ! confirm('Etes-vous bien certain de vouloir annuler l\'envoi du fichier ?') ) return;

				$.post( 'p.php', {cancel:true,PHP_SESSION_UPLOAD_PROGRESS:overlay.progressid} ).done(function(data)
				{
					overlay.hide();
				});
			});
			
			$('body').delegate('#progressbtn .background','click', function(e)
			{
				e.stopPropagation();
				overlay.hide();
			});
		},
		
		show: function()
		{
			if( $('#overlay').length ) return;
			
			$('<div id="overlay">																'
			+ '	<div>																				'
			+ '		<div id="bee"></div>														'
			+ '		<p>Wait for it...</p>													'
			+ '	</div>																			'
			+ '</div>				').appendTo('body');
		},
		
		showWithProgress: function()
		{
			$('<div id="overlay">																'
			+ '	<div>																				'
			+ '		<div id="bee"></div>														'
			+ '		<div id="progressbar"><div/></div>									'
			+ '		<div id="progressbtn">													'
			+ '			<button class="cancel">Annuler</button>						'
			+ '			<button class="background">Arrière plan</button>			'
			+ '		</div>																		'
			+ '	</div>																			'
			+ '</div>				').appendTo('body');
		},
		
		showCustom: function( html )
		{
			$('<div id="overlay">' + html + '</div>').appendTo('body')
		},
		
		hide: function()
		{
			$('#overlay').remove();
		//	setTimeout( "$('#overlay').remove()", 300 );
		
			clearInterval( overlay.objprogress );
		},

		progression: function()
		{
			$.ajax({
				type: 'post',
				url: 'p.php',
				data: {PHP_SESSION_UPLOAD_PROGRESS: overlay.progressid}
			}).done(function(data)
			{
				$('#progressbar>div').animate({'width':parseInt(data)+'px'}, 2000 );
				
				if( data==100 || !data )
				{
					overlay.hide();
				}
			});
		},
			
		progressid: null,
	
		objprogress: null
	}




