<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('secursession');
		check_identification();		

		$id = $this->uri->segment(4);

		//-- Load language
		$this->loadModuleTranslation('project');


		//-- Method which need to load the menu

		$withMenu = array(
			'index', 'dashboard', 'getList', 'card', 
			'log', 'ticket', 'bill', 'role', 'licence'
		);

		if( $id>0 && in_array($this->router->fetch_method(), $withMenu) )
		{
			$this->load->model('project_model');
			$project = $this->project_model->getProjects( $id );
			$project = $project ? $project[0] : null;

			//$this->menu( $id, $project->name?$project->name:'Projets' );
			$this->load->view('menu', array('id'=>$id, 'name'=>$project->name));
		}
	}



	static public function init()
	{
		Project::loadModuleTranslation('project');

		return array(

			"definition" => array(
				"title" => "Project manager",
				"version" => "1.0",
				"description" => "A project management module which includes history, 
					issue management, billing...",
				"url" => "http://www.idee-lab.fr",
				"author" => "Aurélien Chirot"
			),

			"dependences" => array(
				array(
					"require" => "user",
					"version" => "1.0"
				)
			),
			
			"menu" => array(
				array(
					"title" => _("projects"),
					"url" => "project/dashboard",
					"order" => "10"
				)
			),
			
			"widget" => array(
				array(
					"title" => _("Historic"),
					"url" => "project/project/widget_historic",
					"order" => "20"
				),
				array(
					"title" => _("Opened tickets"),
					"url" => "project/ticket/widget_ticket",
					"order" => "50"
				),
				array(
					"title" => _("late payment"),
					"url" => "project/bill/widget_unpaid_bill",
					"order" => "60"
				)
			)
		);
	}



	public function index( $id )
	{
		$this->card( $id );
	}


	public function widget_historic()
	{
		$this->load->model('project_model');
		$this->load->model('tag_model');

		$this->load->view('project/historic', array(
			'project'		=> $this->project_model->getProjects(),
			'tag'				=> $this->tag_model->getTags('log')
		));
	}





	public function dashboard()
	{
		$this->load->model('project_model');

		$this->load->view('dashboard', array(
			'projet'		=> $this->project_model->getProjects()
		));
	}



	public function getList()
	{
		$this->load->model('project_model');
		$projects = $this->project_model->getProjects();

		$data = new stdClass();
		$data->data = $projects;
		$data->recordsTotal = count( $projects );
		$data->recordsFiltered = count( $projects );

		die( json_encode($data) );
	}
	
	
	public function card( $id )
	{
		$this->load->model('project_model');
		$project = $this->project_model->getProjects( $id );
		$project = $project ? $project[0] : null;

		$this->load->view('card', array(
			'domid'			=> $project ? 'SAVEcard' : 'SAVEcardNew',
			'id'				=> $project ? $project->id : '',
			'name'			=> $project ? $project->name : '',
			'url'				=> $project ? $project->url : '',
			'description'	=> $project ? $project->description : '',
			'apikey'			=> $project ? $project->apikey : '',
			'date'			=> $project ? $project->date : '',
			'active'			=> $project ? $project->active : ''
		));
	}


	public function log( $id )
	{
		$this->load->model('project/log_model');
		$this->load->model('tag_model');
				
		$this->load->view('project/log', array(
			'id'				=> $id,
			'ressource'		=> 'project',
			'title'			=> _('Project monitoring'),
			'data'			=> $this->log_model->getLogs( 'project', $id ),
			'tag'				=> $this->tag_model->getTags('log')
		));
	}



	
	public function role( $id )
	{
		$this->load->model('project_model');
		$this->load->model('user/user_model');
		$this->load->model('contact/role_model');
		$this->load->model('tag_model');

		$projects = $this->project_model->getProjects( $id );

		if( ! count($projects) )
		{
			return false;
		}

		//-- check if current user is project owner

		$c = currentUser();
		$projadmin = ( $projects[0]->fk_owner == $c->id );


		$this->load->view('project/role', array(
			'id'				=> $id,
			'allprojects'	=> $projects,
			'allusers'		=> $this->user_model->getUsers(),
			'users'			=> $this->role_model->getRoles( $id ),
			'projadmin'		=> $projadmin,
			'tag'				=> $this->tag_model->getTags('job')
		));
	}



	public function licence( $id )
	{
		$this->load->model('project/project_model');
		$this->load->model('licence/plugin_model');
		$this->load->model('licence/licence_model');
	
		$this->load->view('plugin/licence', array(
			'projects'	=> $this->project_model->getProjects($id),
			'plugins'	=> $this->plugin_model->getPlugins(),
			'licences'	=> $this->licence_model->getLicences()
		));
	}

	
	
	
	public function save()
	{
		//-- saving the project

		$this->load->model('project_model');
		$fk_project = $this->project_model->save();


		if( $this->input->post('id') )
		{
			die( "updating $fk_project" );
		}


		//-- creating a Calendar tag for the project (if new project)

		$this->load->model('tag_model');

		$tag = array(
			'group'		=> 'project',
			'name'		=> $this->input->post('name'),
			'order'		=> 0,
			'readonly'	=> 1,
			'misc'		=> '{"fk_project":' . $fk_project . '}'
		);
		$this->tag_model->save($tag);


		//-- creating an Administrator role for the current user

		$this->load->model('user/role_model');
		$this->load->model('user/user_model');
		$user = $this->user_model->currentUser();

		$role = array(
			'fk_project'	=> "$fk_project",
			'fk_user'		=> $user->id,
			'fk_step'		=> '1',		// administrator
			'date'			=>	time(),
			'on'				=> '1'
		);
		$this->role_model->save($role);


		die( "saving $fk_project" );
	}

}
