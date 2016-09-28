<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Version extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('secursession');
		check_identification();		
	}

	
	public function save()
	{
		$this->load->model('version_model');
		echo $this->version_model->save();
	}

}
