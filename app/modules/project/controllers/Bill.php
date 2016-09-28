<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Bill extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('secursession');
		check_identification();		

		//-- Load language

		$this->loadModuleTranslation('project');
	}



	public function widget_unpaid_bill()
	{
		$this->load->model('bill_model');

		$bill_list = $this->bill_model->getBills( null, true, true, true, null, time() );

		if( ! count($bill_list) )
		{
			return;
		}

		$this->load->view('project/bill-unpaid', array(
			'title'			=> _('Unpaid bills'),
			'fk_project'	=> 0,
			'bills'			=> $bill_list
		));
	}


	public function dashboard( $id )
	{
		//- load the project menu

		$this->load->model('project_model');
		$project = $this->project_model->getProjects( $id );
		
		if( ! count($project) )
		{
			return;
		}
		$this->load->view('menu', array('id'=>$id, 'name'=>$project[0]->name));


		//-- Bill form

		$this->load->model('bill_model');

		$now = time();
		$nextyear = $now + 60*60*24*365;
		
		$this->load->view('bill', array(
			'fk_project'	=> $id,
			'bills'			=> $this->bill_model->getBills( $id, false, false, false )
		));

		if( ! is_group('client') )
		{
			$this->load->model('payment_model');
	
			$this->load->view('payment', array(
				'fk_project'	=> $id,
				'unpaidbills'	=> $this->bill_model->getBills( $id, true ),	// for new payments (unpaid bills only)
				'allbills'		=> $this->bill_model->getBills( $id ),	// necessary for previously paid bills
				'payments'		=> $this->payment_model->getPayments( $id )
			));
		}
	}

	
	public function save( $id )
	{
		$this->load->model('project/bill_model');
		echo $this->bill_model->save( $id );
	}

}
