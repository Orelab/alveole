


$(document).ready(function()
{
	var url = $('head>link[rel=canonical]').attr('href');



	nbg = Math.ceil(Math.random()*6);	// random
	nbg = (new Date()).getDay();		// one for each day of week (need 7 pictures)



	$('#connexion').css('background-image','url('+url+'assets/img/background/bg-light'+nbg+'.jpg)');

	bgMove = function()
	{
		if( $(window).width() > 820 )
		{
			$('#connexion').css('background-position','-=1px');
		}
	}
	setInterval('bgMove()', 100 );




	$(window).on('resize', function()
	{
		var paddingtop = $(window).height() / 2 - $('#connexion article').height();
		var boxwidth = $('#connexion article').width();
		var diapo = ( $('#slider').data('diapo') || 0 ) * ( $('#connexion article').width() + 80 );

		$('#connexion>body>div').css( 'padding-top', paddingtop>30 ? paddingtop : 30 );
		$('#slider').css('margin-left', '-'+diapo+'px')
		$('.diapo').css('width', boxwidth);
		
	}).trigger('resize');



	$('#connexion .slide').on('click', function()
	{
		n = ( $('#slider').data('diapo') || 0 ) + 1;
		w = ( $('#connexion article').width() + 80 );

		$('#slider')
			.animate({'marginLeft': '-='+w}, 400, 'easeOutBounce')
			.data('diapo', n);
	});



	$('#connexion .unslide').on('click', function()
	{
		n = ( $('#slider').data('diapo') || 0 ) - 1;
		w = ( $('#connexion article').width() + 80 );

		$('#slider')
			.animate({'marginLeft': '+='+w}, 400, 'easeOutBounce')
			.data('diapo', n);
	});



	$('#connexion #register').on('submit', function(e)
	{
		e.preventDefault();
		var data = $('#register').serializeArray();

		$.post( 'dashboard/register',data, function(data){
			if( data == 'ok' )
				$('#connexion .slide').trigger('click');
				else
				alert( data );
		});
	});


});

