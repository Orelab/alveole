<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Log extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('secursession');
		check_identification();		
	}

	
	public function save()
	{
		$this->load->model('log_model');
		echo $this->log_model->save();
	}

}
