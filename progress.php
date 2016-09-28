<?php


if( isset($_REQUEST['progress']) )
{
	session_start();

	$key = ini_get("session.upload_progress.prefix") . "myForm";
	if (!empty($_SESSION[$key])) {
		$current = $_SESSION[$key]["bytes_processed"];
		$total = $_SESSION[$key]["content_length"];
		echo $current < $total ? ceil($current / $total * 100) : 100;
	}
	else {
		echo 100;
	}
	die();
}



if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_FILES["userfile"]))
{
	move_uploaded_file($_FILES["userfile"]["tmp_name"], "./" . $_FILES["userfile"]["name"]);
}




?><html>
<head>
<title>File Upload Progress Bar</title>
<style type="text/css">
	#bar_blank {
	border: solid 1px #000;
	height: 20px;
	width: 300px;
	}
	 
	#bar_color {
	background-color: #006666;
	height: 20px;
	width: 0px;
	}
	 
	#bar_blank, #hidden_iframe {
	display: none;
	}
</style>
</head>
<body>


	<div id="bar_blank">
		<div id="bar_color"></div>
	</div>
	
	<div id="status"></div>
	
	<form action="progress.php" method="post" id="myForm" enctype="multipart/form-data">
		<input type="hidden" value="myForm" name="<?php echo ini_get("session.upload_progress.name"); ?>">
		<input type="file" name="userfile"><br>
		<input type="submit" value="Start Upload">
	</form>
	 
	<script type="text/javascript">
	function toggleBarVisibility() {
		var e = document.getElementById("bar_blank");
		e.style.display = (e.style.display == "block") ? "none" : "block";
	}
	 
	function createRequestObject() {
		var http;
		if (navigator.appName == "Microsoft Internet Explorer") {
			http = new ActiveXObject("Microsoft.XMLHTTP");
		}
		else {
			http = new XMLHttpRequest();
		}
		return http;
	}
	 
	function sendRequest() {
		var http = createRequestObject();
		http.open("GET", "progress.php?progress=on");
		http.onreadystatechange = function () { handleResponse(http); };
		http.send(null);
	}
	 
	function handleResponse(http) {
		var response;
		if (http.readyState == 4) {
			response = http.responseText;
			console.log( response )
			document.getElementById("bar_color").style.width = response + "%";
			document.getElementById("status").innerHTML = response + "%";
			 
			if (response < 100) {
				setTimeout("sendRequest()", 1000);
			}
			else {
				toggleBarVisibility();
				document.getElementById("status").innerHTML = "Done.";
			}
		}
	}
	 
	function startUpload() {
		toggleBarVisibility();
		setTimeout("sendRequest()", 1000);
	}
	 
	(function () {
		document.getElementById("myForm").onsubmit = startUpload;
	})();
	</script>
	
	
	
</body>
</html>