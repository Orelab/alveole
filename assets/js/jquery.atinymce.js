(function($)
{

	$.fn.atinymce = function( options )
	{
		var opt = $.extend({
		}, options );



		return $(this).each(function()
		{
			if( $(this).data('hasTinyMCE') == true )
			{
				return;
			}


			/*
				This is a f****** way to reset TinyMCE
				But at this time, we don't have any NODE ID and node are yet destroyed
			*/
			tinyMCE.editors = [];

			$(this)
				.data('hasTinyMCE', true)
				.tinymce({
					language: lang(),
					menubar: false,
					statusbar: false,
					toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code',
					plugins: ['link','image','code'],
					setup: function(ed){
						ed.on('change', function(ed){
							console.log('TinyMCE updates textarea');
							tinyMCE.triggerSave();
						})
					},
					init_instance_callback: function(){ footerPosition(); }
				});
		});



	}


	
}(jQuery));


