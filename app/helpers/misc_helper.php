<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');



if ( ! function_exists('add_object_property') )
{
	function add_object_property( &$object, $key, $value )
	{
		if( ! $key ) return;
		
		$tab = (array)$object;
		$tab[$key] = $value;
		$object = (object)$tab;
	}
}	



if ( ! function_exists('trace') )
{
	function trace( $text )
	{
		$CI = get_instance();
		$CI->load->model('trace_model');

		$CI->trace_model->save( $text );
	}
}	




if ( ! function_exists('mime_decode') )
{
	function mime_decode( $text )
	{
		$data = imap_mime_header_decode( $text );
		$string = '';
	
		foreach( $data as $d )
		{
			$string .= $d->charset=='UTF-8' ? $d->text : utf8_encode($d->text);
		}
		
		//$string = stripslashes( $string );
		//$string = trim( $string, '"- <>' );
		//$string = stripslashes( $string );
		
		//$string = imap_qprint( $string );
		//$string = quoted_printable_decode( $string );
		
		//$string = mb_decode_mimeheader( $string );	// decoding encoded-word : =?encodage?méthode?texte?=
		
		return $string;
	}
}






if ( ! function_exists('send_email') )
{
	function send_email( $subject, $body, $to )
	{
		$CI = get_instance();
		$CI->load->model('configuration/configuration_model');
		$config = $CI->configuration_model->getGeneral();

		require APPPATH . 'libraries/PHPMailer-master/PHPMailerAutoload.php';
		
		$mail = new PHPMailer;

		/*
			0 = debug disabled
			1 = errors + server responses
			2 = errors + server responses + client messages
		*/
		$mail->SMTPDebug = 0;

		$mail->isSMTP();
		$mail->isHTML(true);
		$mail->SMTPAuth = true;

		$mail->Host = $config['email_server'];
		$mail->Username = $config['email_user'];
		$mail->Password = $config['email_password'];
		$mail->SMTPSecure = $config['email_security'];
		$mail->Port = $config['email_port'];
		
		$mail->From = $config['email_user'];
		$mail->FromName = 'Alveole';

		$mail->addAddress( $to );
		$mail->Subject = $subject;
		$mail->Body    = $body;
		$mail->AltBody = strip_tags( $body );

		$mail->setLanguage('fr', 'application/libraries/PHPMailer-master/language/phpmailer.lang-fr.php');		


		// return true, or the error message
		
		return ( $mail->send()==1 ) ?: $mail->ErrorInfo;
	}
}



