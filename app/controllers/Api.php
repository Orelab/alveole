<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('secursession');
	}


	
	
	public function json()
	{
		$this->load->model('project/project_model');
		$this->load->model('user_model');

		/*
		$arg = func_get_args();
		print_r( $arg );
		$key = $arg[0];
		$model = $arg[1];
		$id = $arg[2];
		*/
		
		$key = $this->uri->segment(3);
		$model = $this->uri->segment(4);
		$id = $this->uri->segment(5);

		if( ! $key ) return;
		if( ! $model ) return;

		$project = $this->project_model->getProjects( null, $key );
		$project = $project ? $project[0] : null;

		if( ! $project ) return;

		$_POST['fk_project'] = $project->id;
		$_POST['fk_user'] = $this->user_model->currentUser( $project->id );

		switch( $model )
		{
			case 'ticket' :
				/*
					If an ID is not set, the API returns a list of tickets
					If an ID is set, the API return a ticket (with its responses) 
				*/
				$this->load->model('ticket_model');
				$data = $this->ticket_model->getTickets(		// $idProject, $idParent, $addChildren
								isset($id) ? null : $project->id,
								isset($id) ? $id : null,
								isset($id) ? true : false
							);
				break;
				
			case 'saveticket' :
				$this->load->model('ticket_model');
				$data = $this->ticket_model->save();
				break;
				
			case 'tag' :
				$this->load->model('tag_model');
				$data = $this->tag_model->getTags('ticket');
				break;
				
		}

		die( json_encode( $data ) );
	}

	
	
	
	
	
	
	


	public function plugin( $slug=null )
	{
		$this->load->model('plugin_model');
		$this->load->model('version_model');

		$action = $this->input->get_post('action');
		$request = $this->input->get_post('request');
		$apikey = $this->input->get_post('api-key');

		if( $request )
		{
			$request = (object)unserialize( $request );
		}
	
		if( isset($request->slug) && ! $slug )
		{
			$slug = $request->slug;
		}
		
		if( ! $slug )
		{
			return;
		}

		trace( $apikey );

		
		$plugin = $this->plugin_model->getPlugins( null, $slug );
		
		if( count($plugin) )
			$plugin = $plugin[0];
			else
			die();

		$versions = $this->version_model->getVersions($plugin->id);
		$first_version = reset( $versions );
		$last_version = end( $versions );



		header( 'Content-Type: text/plain' );

		$data = new stdClass;
		
		if( true || $action=='basic_check' || $action=='query_plugins' || $action=='' )
		{
			$data->id						= '713705' . $plugin->id;
			$data->name						= $plugin->name;
			$data->slug						= $plugin->slug;
			$data->plugin					= $plugin->slug .'/'. $plugin->slug . '.php';
			$data->new_version			= $last_version->version;
		//	$data->url						= 'http://www.idee-lab.fr';
			$data->package					= $last_version->package;
		}
		
		if( $action=='plugin_information' )
		{
			$data->version					= $last_version->version;
			$data->last_updated			= $last_version->date;
			$data->download_link			= $last_version->package;
			$data->tested					= $last_version->tested ? $last_version->tested : '4.0';
			$data->requires				= $last_version->requires ? $last_version->requires : '4.0';
			$data->new_version			= $last_version->version;

			$data->author					= 'Aurélien Chirot';
			$data->author_profile		= 'http://www.idee-lab.fr/aurelien-chirot';
			$data->contributors			= array( 'funworks' => 'Aurélien Chirot' );
	
			$data->compatibility			= array(
													'4.2.2' => array(
														'1.0' => array(100,0,0),
														'1.1' => array(100,0,0),
														'1.2' => array(100,0,0),
													)
												);
	
			$data->rating					= 100;
			$data->num_rating				= 100;
			$data->downloaded				= 100;
			$data->last_updated			= date('Y-m-d', $last_version->date);
			$data->added					= date('Y-m-d', $first_version->date);
			$data->homepage				= 'http://www.idee-lab.fr';

			$data->sections				= array(
													'description' => $plugin->text,
													'Auteur' => '
														<h2>Aurélien Chirot @Idée Lab &copy;2015</h2>
														<p>Chez Idée Lab, vous trouverez des développeurs créatifs et ouverts d\'esprit : n\'hésitez 
														pas à nous contacter si vous souhaitez adapter ou améliorer nos programmes ;)</p>
														<p><a href="mailto:aurelien.chirot@idee-lab.fr">aurelien.chirot@idee-lab.fr</a></p>',
													'licence' => '
														<p>Vous trouverez ci-dessous votre numéro de licence à fournir en cas de réclamation.
														Veuillez noter que ce code est associé au nom de domaine de votre site web. Toute réplication
														sur un site tiers implique une résiliation de vos droits de licence : </p>
														<h4>' . $this->input->get_post('api-key') . '</h4>',
										//			'installation' => '',
										//			'changelog' => '',
										//			'faq' => '',
										//			'download_link' => '',
										//			'tags' => array(),
										//			'donate_link' => 'http://www.idee-lab.fr'
												);
		}

		die( serialize($data) );
	}









/*
	public function ticket( $task )
	{
		$this->load->model('project/project_model');
		$this->load->model('ticket_model');
		$this->load->model('step_model');
		
		
		//-- identification du projet

		$this->project = $this->project_model->getProjectFromAPI();
		
		if( ! $this->project ) return;


		//-- URL projet == IP client ?
		
		//if( ! api_authentification($this->projet->url) ) return;

		
		switch( $task )
		{
			case 'get' :
				$this->load->view('ticket/ticket', array(
					'id'			=> $this->project->id,
					'tickets'	=> $this->ticket_model->getTickets($this->project->id),
					'step'		=> $this->step_model->getsteps(30,null,false)
				));
				break;

			case 'new' :

			case 'reply' :
				die( $task . ' : coming soon...');
				break;

			default : die('error');
		}
	}


	public function _basic_check( $args, $versions )
	{
		$last_version = array_pop( $versions );
		
		$last_version->slug = $args->slug;
		
		if( version_compare($args->version, $last_version->version, '<') )
		{
			$last_version->new_version = $last_version->version;
		}

		//print_r( $data );die();
		die( serialize($last_version) );
	}
	
	
	
	public function _plugin_information( $args, $versions )
	{
		$last_version = array_pop( $versions );

		$data = new stdClass;
		$data->slug				= $args->slug;
		$data->version			= $last_version->version;
		$data->last_updated	= $last_version->date;
		$data->download_link	= $last_version->package;
		$data->tested			= $last_version->tested;
		$data->requires		= $last_version->requires;
		$data->new_version	= $last_version->version;
		
		$data->sections = array(
			'description' => $last_version->detail,
				
			'Auteur' => '
				<h2>Aurélien Chirot @Idée Lab &copy;2015</h2>
				<p>Chez Idée Lab, vous trouverez des développeurs créatifs et ouverts d\'esprit : n\'hésitez 
				pas à nous contacter si vous souhaitez adapter ou améliorer nos programmes ;)</p>
				<p><a href="mailto:aurelien.chirot@idee-lab.fr">aurelien.chirot@idee-lab.fr</a></p>',
				
			'licence' => '
				<p>Vous trouverez ci-dessous votre numéro de licence à fournir en cas de réclamation.
				Veuillez noter que ce code est associé au nom de domaine de votre site web. Toute réplication
				sur un site tiers implique une résiliation de vos droits de licence : </p>
				<h3>' . $this->input->get_post('api-key') . '</h3>'
		);
	
		//print_r( $data );die();
		die( serialize($data) );
	}
	
	
	
	public function _theme_update( $version, $versions )
	{
		$update_info = (object)$version;
		
		//$update_data = new stdClass;
		$data = array();
		$data['package'] = $update_info->package;	
		$data['new_version'] = $update_info->version;
		$data['url'] = $versions[$args->slug]['info']['url'];
			
		if( version_compare($args->version, $version['version'], '<') )
		{
			die( serialize($data) );
		}
		die();
	}
*/

}







