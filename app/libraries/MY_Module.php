<?php defined('BASEPATH') OR exit('No direct script access allowed');


class MY_Module
{
	
	/*
	 *	$m must be formed as :
	 *
	 *		array(
	 *			"title" => "",
	 *			"url" => "",
	 *			"order" => "",
	 *			"right" => ""
	 *		);
	 *
	 */
	public function add_menu( $m )
	{
	}

	protected $CI;
	
	public function __construct()
	{
		$this->CI =& get_instance();
	}
	
	public function foo()
	{
		$this->CI->load->helper('url');
		redirect();
	}
	
	public function bar()
	{
		$this->CI->config->item('base_url');
	}

	
	
}


