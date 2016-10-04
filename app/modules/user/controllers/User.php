<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('secursession');
		check_identification();		

		//-- Load language
		$this->loadModuleTranslation('user');


/*
		$id = $this->uri->segment(3);

		if( $id>0 )
		{
			$this->load->model('user_model');
			$user = $this->user_model->getUsers( $id );
			$user = $user ? $user[0] : null;
			$name = $user->uname .' '. $user->usurname;

		//	$this->menu( $id, $name?$name:'Contacts' );

			$this->load->view('user/menu', array(
				'name' => $name?$name:'Contact',
				'mail' => $user->email,
				'id' => $id
			));
		}
*/
	}


	static public function init()
	{
		User::loadModuleTranslation('user');


		return array(
			"definition" => array(
				"title" => "User manager",
				"version" => "1.0",
				"description" => "This module manage the user list.",
				"url" => "http://www.idee-lab.fr",
				"author" => "Aurélien Chirot"
			),

			"menu" => array(
/*
				array(
					"title" => "Contacts",
					"url" => "user/dashboard",
					"order" => "20"
				)
*/
			)
		);
	}


	public function index( $id )
	{
		$this->card( $id );
	}
	

	public function current()
	{
		$user = currentUser();
		$this->card( $user->id );
	}
	
	
	public function dashboard()
	{
		$this->load->model('user/user_model');
		$this->load->model('user/role_model');

	//	$this->menu();
		
		$this->load->view('user/dashboard', array(
			'user'		=> $this->user_model->getUsers(null, true),
			'role'		=> $this->role_model->getRoles()
		));
	}



	public static function menu( $id, $title='blabla' )
	{
		$CI =& get_instance();
		$CI->load->view('user/menu', array(
			'id' => $id,
			'name' => $title
		));
	}

	
	public function card( $id )
	{
		//-- trick to load a menu from another module with its translation
		
		$this->loadModuleTranslation('configuration');
		$this->load->view('configuration/menu');
		$this->loadModuleTranslation('user');

		$this->load->model('project/project_model');
		$this->load->model('user_model');
		$user = $this->user_model->getUsers( $id );
		$user = $user ? $user[0] : null;

		$this->load->view('user/card', array(
			'domid'				=> $user ? 'SAVEuser' : 'SAVEuserNew',
			'id'					=> $user ? $user->id : '',
			'uname'				=> $user ? $user->uname : '',
			'usurname'			=> $user ? $user->usurname : '',
			'email'				=> $user ? $user->email : '',
			'phone'				=> $user ? $user->phone : '',
			'address'			=> $user ? $user->address : '',
			'text'				=> $user ? $user->text : '',
			'can_connect'		=> $user ? $user->can_connect : '',
			'group'				=> $user ? $user->group : '',
			'fk_project'		=> $user ? $user->fk_project : '',
			'md5pass'			=> $user ? $user->md5pass : '',
			'business'			=> $user ? $user->business : '',
			'projects'			=>$this->project_model->getProjects()
		));
	}



	public function getRole( $id )
	{
		$this->load->model('project/project_model');
		$this->load->model('user_model');
		$this->load->model('role_model');
		$this->load->model('tag_model');

		$this->load->view('role', array(
			'id'				=> $id,
			'allprojects'	=> $this->project_model->getProjects(),
			'allusers'		=> $this->user_model->getUsers($id),
			'users'			=> $this->role_model->getRoles( null, $id ),
			'tag'			=> $this->tag_model->getTags('job')
		));
	}



	public function email( $id )
	{
		$this->load->model('user_model');
		$user = $this->user_model->getUsers( $id );
		$user = $user ? $user[0] : null;
		
		$this->load->view('user/mailsearch', array(
			'address' => $user->email
		));

		$this->load->view('neowebmail/dashboard');
	}
	
	
	
	public function password()
	{
		$user = currentUser();

		$this->load->view('user/password', array(
			'domid'				=> $user ? 'SAVEpass' : 'SAVEpassNew',
			'id'					=> $user ? $user->id : '',
			'md5pass'			=> $user ? $user->md5pass : '',
		));
	}



	public function get_userpref()
	{
		$keylist = json_decode( $this->input->post('keylist') );

		if( ! is_array($keylist) )
		{
			$keylist = array($keylist);
		}

		$this->load->model('configuration/configuration_model');
		$pref = $this->configuration_model->get_userpref( $keylist );

		die( json_encode($pref) );
	}



	public function save_userpref()
	{
		$key = $this->input->post('key');
		$val = $this->input->post('val');
		
		$this->load->model('configuration/configuration_model');
		$res = $this->configuration_model->save_userpref( $key, $val );
		
		die( $res );
	}




	public function save()
	{
		$this->load->model('user_model');
		echo $this->user_model->save();
	}


}
