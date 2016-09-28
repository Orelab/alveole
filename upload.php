<?php
/*

http://phpcodeur.net/articles/php/upload
http://stackoverflow.com/questions/5392344/sending-multipart-formdata-with-jquery-ajax/5976031#5976031
http://stackoverflow.com/questions/13630618/ajax-uploading-not-waiting-for-response-before-continuing

*/

if( count($_FILES) )
{
    $content_dir = './';

    $tmp_file = $_FILES['fichier']['tmp_name'];

    if( !is_uploaded_file($tmp_file) )
    {
        die("File not found");
    }

    $name_file = $_FILES['fichier']['name'];

    if( !move_uploaded_file($tmp_file, $content_dir . $name_file) )
    {
        die("Impossible to copy the file in $content_dir");
    }
    die("File uploaded successfully");
}
	
?><html>
<head>
		<title>file upload</title>
		<script src="assets/js/jquery-2.1.4.min.js"></script>
</head>
<body>


	<form method="post" enctype="multipart/form-data" action="upload.php">
		<input type="file" name="fichier">
		<br/>
		<input type="submit" name="upload" value="HTML upload">
	</form>
	<button>AJAX Upload</buthttp://phpcodeur.net/articles/php/uploadton>


	<script type="text/javascript">
	
		$('button').on('click', function(e)
		{
			var data = new FormData()
		
			$.each( $('input[type=file]'), function(id, obj)
			{
				data.append( obj.name, obj.files[0] )
			})
	
			$.ajax({
				url: 'upload.php',
				data: data,
				cache: false,
				contentType: false,
				processData: false,
				timeout: 300,
				type: 'POST',
				success: function(msg)
				{
					alert(msg)
				},
				error: function(xhr, str)
				{
					console.log( xhr )
					alert('Error : ' + str)
				}
			})
		})
	</script>


</body>
</html>

