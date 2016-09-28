<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Plugin extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('secursession');
		check_identification();		


		$id = $this->uri->segment(3);

		if( $id>0 )
		{
			$this->load->model('plugin_model');
			$plugin = $this->plugin_model->getPlugins( $id );
			$plugin = $plugin ? $plugin[0] : null;

			$this->menu( $id, $plugin->name?$plugin->name:'Plugins' );
		}
		
	}


	public function index( $id )
	{
		$this->card( $id );
	}

	
	
	public function dashboard()
	{
		$this->load->model('plugin_model');

		$this->load->view('plugin/dashboard', array(
			'plugin' => $this->plugin_model->getPlugins()
		));
	}


	
/*
	public static function menu( $id )
	{
		$CI =& get_instance();
		$CI->load->view('plugin/menu');
	}

	
	
	public function versioncontrol()
	{
		$this->load->view('plugin/versioncontrol', array(
		));
	}
*/


	
	public function card( $id )
	{
		$this->load->model('plugin_model');
		$plugin = $this->plugin_model->getPlugins( $id );
		$plugin = $plugin ? $plugin[0] : null;

		$this->load->view('plugin/card', array(
			'domid'		=> $plugin ? 'SAVEplugin' : 'SAVEpluginNew',
			'id'			=> $plugin ? $plugin->id : '',
			'name'		=> $plugin ? $plugin->name : '',
			'slug'		=> $plugin ? $plugin->slug : '',
			'text'		=> $plugin ? $plugin->text : '',
		));
	}
	
	
	public function version( $id )
	{
		$this->load->model('plugin_model');
		$this->load->model('version_model');
		$plugin = $this->plugin_model->getPlugins($id);
		$plugin = $plugin ? $plugin[0] : null;
		
		$this->load->view( 'plugin/version', array(
			'id'			=> $id,
			'name'		=> $plugin->name,
			'ressource' => $this->version_model->getVersions($id)
		));
	}


	public function  licence( $id )
	{
		$this->load->model('project/project_model');
		$this->load->model('plugin_model');
		$this->load->model('licence_model');
	
		$this->load->view('plugin/licence', array(
			'projects'	=> $this->project_model->getProjects(),
			'plugins'	=> $this->plugin_model->getPlugins($id),
			'licences'	=> $this->licence_model->getLicences()
		));
	}



	public function versioncontrol()
	{
		die( '<p>Coming soon...</p>');
	}
	
	
	
	public function save()
	{
		$this->load->model('plugin_model');
		echo $this->plugin_model->save();
	}

}
