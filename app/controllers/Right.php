<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Right extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('secursession');
		check_identification();		
	}


	public function save()
	{
		$this->load->model('right_model');
		echo $this->right_model->save();
	}


	public function delete()
	{
		$this->load->model('right_model');
		echo $this->right_model->delete();
	}

}