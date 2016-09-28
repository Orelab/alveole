<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Share extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('secursession');
		check_identification();		
	}



	/*
		Really shouldn't be here...
		(see Document module)
	*/
	
	public function save()
	{
		$this->load->model('document/share_model');
		echo $this->share_model->save();
	}
}




