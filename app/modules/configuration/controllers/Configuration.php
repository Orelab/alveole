<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Configuration extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('secursession');
		check_identification();		
		
		
		//-- We protect the list of methods only available to the admin

		$admin_only = array(
			'general',			'module',
			'authorization',	'tag',
			'user',				'role',
			'log',				'php'
		);

		if( in_array($this->router->fetch_method(), $admin_only)
			&& ! is_group('admin') )
		{
			die( _('Sorry, you are not allowed') );
		}


		//-- Load language

		$this->loadModuleTranslation('configuration');

		if( $this->router->fetch_method() != 'save' )
		{
			$this->load->view('configuration/menu');
		}
	}




	static public function init()
	{
		return array(
			"definition" => array(
				"title" => "Configuration",
				"version" => "1.0",
				"description" => "This module is for configuration stuffs.",
				"url" => "http://www.idee-lab.fr",
				"author" => "Aurélien Chirot"
			)
		);
	}


	
	public function dashboard()
	{
		$this->preference();
	}
	
	
/*
	For now, it's useless,
	and it's a security hole as it allows to see the mailer's password :/
		
	public function general()
	{
		$this->load->model('configuration/configuration_model');
		
		$this->load->view('configuration/general', array(
			'general' => $this->configuration_model->getGeneral(true)
		));
	}
*/


	public function preference()
	{
		$this->load->model('configuration/configuration_model');
		
		$this->load->view('configuration/preference', array(
			'preferences' => $this->configuration_model->getPreferences()
		));
	}


	public function module()
	{
		$this->load->library('Module_manager');
		$module_cnf = $this->module_manager->configure();
		
		$this->load->view('configuration/module', array(
			'cnf' => $module_cnf
		));
	}


	public function authorization()
	{
		$this->load->model('right_model');
		
		$this->load->view('configuration/authorization', array(
			'right' => $this->right_model->getRights()
		));
	}
	
	
	
	public function tag()
	{
		$this->load->model('tag_model');
		
		$tmp = $this->tag_model->getTags();
		$labels = $tags = array();
		
		foreach( $tmp as $t )
		{
			$o = new stdClass;
			$o->id = 'config-tag-' . $t->group;
			$o->name = $t->group;

			$labels[$t->group] = $o;
			$tags[$t->group][] = $t;
		}
		

		$this->load->view( 'configuration/tag', array(
			'labels' => $labels,
			'tags' => $tags
		));
	}
	
	
	
	public function password()
	{
		echo modules::run( 'user/user/password' );
	}
	
	
	public function user()
	{
		echo modules::run( 'user/user/dashboard' );
	}
	
	
	
	public function role()
	{
		echo modules::run( 'user/role/role' );
	}
	
	
	
	public function recall()
	{
		echo _('<p>Coming soon...</p>');
	}
	
	
	
	public function log()
	{
		$this->load->model('activity_model');
		
		$this->load->view('configuration/log', array(
			'activity'	=> $this->activity_model->get(),
		));
	}
	
	
	
	public function php()
	{
		phpinfo();
		die();
	}
	
	
	public function save()
	{
		$this->load->model('configuration/configuration_model');
		echo $this->configuration_model->save();
	}
	
}




