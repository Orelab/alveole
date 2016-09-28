<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Memo extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->helper('secursession');
		check_identification();		

		//-- Load language
		$this->loadModuleTranslation('memo');
	}


	static public function init()
	{
		Memo::loadModuleTranslation('memo');

		return array(
			"definition" => array(
				"title" => "Memo widget",
				"version" => "1.0",
				"description" => "Just add a simple memo on your dashboard ;)",
				"url" => "http://www.idee-lab.fr",
				"author" => "Aurélien Chirot"
			),

			"widget" => array(
				array(
					"title" => _("Memo"),
					"url" => "memo/widget_memo",
					"order" => "10"
				)
			)
		);
	}

	
	public function widget_memo()
	{
		$this->load->model('memo/memo_model');
		$this->load->model('tag_model');

		$this->load->view('memo', array(
			'tasks'			=> $this->memo_model->getMemo(),
			'tag'				=> $this->tag_model->getTags('memo')
		));
	}
	



	
	public function save()
	{
		$this->load->model('memo/memo_model');
		echo $this->memo_model->save();
	}
	
	
	public function save_order()
	{
		$this->load->model('memo/memo_model');
		echo $this->memo_model->save_order();
	}


	public function delete()
	{
		$this->load->model('memo/memo_model');
		echo $this->memo_model->delete();
	}
	
}




