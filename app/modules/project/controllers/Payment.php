<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('secursession');
		check_identification();		
	}

	
	public function save( $id )
	{
		$this->load->model('payment_model');
		echo $this->payment_model->save( $id );
	}

}
