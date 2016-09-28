<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Meta extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('secursession');
		check_identification();		

		$this->load->model('contact_model');
	}


	public function save_order()
	{
		echo $this->contact_model->save_order();
	}


	public function delete()
	{
		echo $this->contact_model->delete_meta() ? '1' : 'error';
	}

	public function save()
	{
		echo $this->contact_model->save_meta();
	}


}
