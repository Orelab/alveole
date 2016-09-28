
$(document).ready(function()
{


	$('body').delegate('#content', 'alveolePageLoaded', function()
	{
		$('.calendrier').calendar();
	});


});