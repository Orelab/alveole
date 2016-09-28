<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Tag extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('secursession');
		check_identification();		
	}



	
	public function delete()
	{
		$this->load->model('tag_model');
		echo $this->tag_model->delete();
	}


	
	public function save()
	{
		$this->load->model('tag_model');
		echo $this->tag_model->save();
	}


	
	public function saveorder()
	{
		$this->load->model('tag_model');
		echo $this->tag_model->saveorder();
	}
}




