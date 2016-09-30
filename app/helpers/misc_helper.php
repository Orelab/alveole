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



/*
	Previous translation system (CI) replaced by PHP gettext)

if ( ! function_exists('trad') )
{
	function trad( $original )
	{
		$CI = get_instance();
		$traduction = $CI->lang->line( $original );
		
		return $traduction ? $traduction : $original;
	}
}	
*/


/*
if ( ! function_exists('extension') )
{
	function extension( $file )
	{
		return pathinfo($file, PATHINFO_EXTENSION);
		
		$spl = new SplFileInfo( $file );
		return $spl->getExtension();
	}
}	
*/



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

