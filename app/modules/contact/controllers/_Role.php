<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('secursession');
		check_identification();		

		//-- Load language
		$this->loadModuleTranslation('contact');
	}


	public function role()
	{
		$this->load->model('project/project_model');
		$this->load->model('contact_model');
		$this->load->model('role_model');
		$this->load->model('step_model');

		$this->load->view('role', array(
			'allprojects'	=> $this->project_model->getProjects(),
			'allcontacts'		=> $this->contact_model->getContacts(),
			'contacts'			=> $this->role_model->getRoles(),
			'step'			=> $this->step_model->getSteps( 60 )
		));
	}


	public function save()
	{
		//-- check if current contact is allowed to alter roles (only admin can)

		$this->load->model('project/project_model');
		$id = $this->input->post('id');
		$projects = $this->project_model->getProjects( $id );		

		if( ! count($projects) )
		{
			return false;
		}

		if( $projects[0]->fk_step != 1 )
		{
			die( _('Sorry, you are not allowed to change permissions.') );
		}

		//-- and save

		$this->load->model('role_model');
		echo $this->role_model->save();
	}


	public function delete()
	{
		$this->load->model('role_model');
		echo $this->role_model->delete();
	}

}