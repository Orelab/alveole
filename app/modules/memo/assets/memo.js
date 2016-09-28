
$(document).ready(function()
{


	$('body').delegate('#content.dashboard', 'alveolePageLoaded', function()
	{

		$('.memo-list').memo();

	});


});