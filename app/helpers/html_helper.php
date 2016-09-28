<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');



/*
	Generate a list of <option> ready to place in a <select> node, usually
	in a 'view' file.

	@param $options : An array of objects contains the following properties : 
			id, name, selectable (this one is optionnal)
	@param $value : the default selected value
	@param $allowEmptyValue (optional) : if set to true, an empty option 
			will be added
	@param $colorize (optional) : if the array contains a field 'color',
			and if this parameter if set to true, we'll set up the background 
			color with it.
	
	@return a string containing the html
*/
if ( ! function_exists('optionsHtml') )
{
	function optionsHtml( $options, $value, $allowEmptyValue=true, $colorize=true )
	{
		$html = $allowEmptyValue ? '<option value=""></option>' . "\r\n" : '';

		foreach( $options as $o )
		{
			if( property_exists($o, 'selectable') )
				$selectable = $o->selectable;
				else 
				$selectable = 1;

			if( isset($o->color) )
				$color = ' style="background-color:' . $o->color . ';"';
				else
				$color = '';


			$html .= '<option' . $color . ' value="' . $o->id . '"' 
						. ($o->id==$value ? ' selected="selected"' : '') 
						. (!$selectable ? ' disabled="disabled"' : '') 
						. '>' . $o->name . '</option>' . "\r\n";
		}
		return $html;
	}
}	



/*
	Generate an elegant <input type="file">. As this box is hard
	to style, we like to use this function :)
	
	@param $name : the <input> name
	@param $value (facultative) :  the default <input> name
	@param $class (facultative) : an additional class name (facultative)
	
	@return a string containing the html
*/
if ( ! function_exists('fileHtml') )
{
	function fileHtml( $name, $value=null, $class=null )
	{
		return '
		<div class="styled-file">
			<input type="file" name="' . $name . '" value="' . $value . '" class="' . $class . '" />
		</div>';
	}
}	



/*
	Return ether the date, or the hour, depending that the given date is
	for today (shos the hour) or from another day (shows the date).
	
	@param $timestamp  : A timestamp, like the one given to the date() function
	@param $dateFormat : (optional). Default : 'd/m/y'
	@param $timeFormat : (optional). Default : 'H\hi'
	
	@return a string containing the html
*/
if ( ! function_exists('dateOrTime') )
{
	function dayOrHour( $timestamp, $dateFormat='d/m/y', $timeFormat='H\hi' )
	{
		$today = date($dateFormat);
		$date = date($dateFormat, $timestamp);
		$hour = date($timeFormat, $timestamp);

		return ( $today == $date ) ? $hour : $date;
	}
}	




if ( ! function_exists('shorter') )
{
	function shorter( $text, $maxlength=25 )
	{
		$extension = '[...].' . pathinfo($text, PATHINFO_EXTENSION);
		
		if( strlen($text) >= $maxlength )
		{
			$text = substr($text,0,$maxlength-strlen($extension)) . $extension;
		}
		
		return $text;
	}
}	


