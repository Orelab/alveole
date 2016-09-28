<?php
/**
 *
 *	This standalone script is used to get a feedback on the file download
 *	progression.
 *
 *	As it is ruled by the PHP SESSION mechanism and CI destroy the original
 *	$_SESSION array, we have to use this sort of script... for the moment !
 *
 *	session_reset could help us in the futur, as it could allow us to retrieve
 *	the original data. Unfornatelly, this funciton is available in PHP >=5.6
 *
 */


session_start();

$key = ini_get("session.upload_progress.prefix") . $_REQUEST[ini_get("session.upload_progress.name")];




if( isset($_REQUEST['cancel']) )
{
	$_SESSION[$key]['cancel_upload'] = true;
	print_r( $_SESSION[$key] );
	die( 'canceled : ' . $key );
}




if( ! isset($_REQUEST['PHP_SESSION_UPLOAD_PROGRESS']) )
{
	die( 'error : ' . $key );
	header('Location: p.php?PHP_SESSION_UPLOAD_PROGRESS=myForm');
}




if( isset($_SESSION[$key]) )
{
	$current = $_SESSION[$key]["bytes_processed"];
	$total = $_SESSION[$key]["content_length"];
	echo $current<$total ? ceil($current/$total*100) : 100;
}
else die(100);



