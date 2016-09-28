<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH."third_party/MX/Loader.php";

class MY_Loader extends MX_Loader
{

	/**
	 *	Author : Aurélien Chirot
	 *	Date : 2015
	 *
	 */
	function view($view, $vars = array(), $return = FALSE)
	{
	
		$CI =& get_instance();
		$CI->load->helper('secursession');

		if( ! allowed($view) ) return;
		
			
		/*
			below is the original part of HMVC found in third_party/MX/Loader.php
		*/


		list($path, $_view) = Modules::find($view, $this->_module, 'views/');

		if ($path != FALSE)
		{
			$this->_ci_view_paths = array($path => TRUE) + $this->_ci_view_paths;
			$view = $_view;
		}

		return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
	}


}