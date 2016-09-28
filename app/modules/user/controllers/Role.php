<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('secursession');
		check_identification();		

		//-- Load language
		$this->loadModuleTranslation('user');
	}


	public function role()
	{
		$this->load->model('project/project_model');
		$this->load->model('user/user_model');
		$this->load->model('user/role_model');
		$this->load->model('tag_model');

		$this->load->view('user/role', array(
			'allprojects'	=> $this->project_model->getProjects(),
			'allusers'		=> $this->user_model->getUsers(),
			'users'			=> $this->role_model->getRoles(),
			'tag'			=> $this->tag_model->getTags( 'job' )
		));
	}


	public function save()
	{
		//-- check if current user is allowed to alter roles (only admin can)
/*
		$uid = $this->input->post('id'); // problem it's pid and we need uid
		$projects = $this->project_model->getProjects( $uid );		

		if( ! count($projects) )
		{
			return false;
		}

		if( $projects[0]->fk_step != 1 )
		{
			header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
			die( _('Sorry, you are not allowed to change permissions.') );
		}
*/
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