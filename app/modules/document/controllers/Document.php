<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Document extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('secursession');

		/*
			La méthode get doit être accessible depuis l'extérieur
			pour les mises à jour de plugin Wordpress.

			Cependant, une méthode d'identification alternative,
			sans cookie, est employée (vérification du domaine).
		*/
		if( $this->router->fetch_method() != 'get' )
		{	
			check_identification();
		}

		//-- Load language
		$this->loadModuleTranslation('document');
	}



	static public function init()
	{
		
		Document::loadModuleTranslation('document');

		return array(
			"definition" => array(
				"title" => "Document manager",
				"version" => "1.0",
				"description" => "A module to manage and share documents.",
				"url" => "http://www.idee-lab.fr",
				"author" => "Aurélien Chirot"
			),

			"menu" => array(
				array(
					"title" => _("files"),
					"url" => "document/dashboard",
					"order" => "30"
				)
			)
		);
	}



	public static function menu( $id=null, $title=null )
	{
		$CI =& get_instance();
		$id = $CI->uri->segment(3);

		if( $id>0 )
		{
			$CI->load->model('document_model');
			$doc = $CI->document_model->getDocuments( $id );
			$doc = $doc ? $doc[0] : null;

			parent::menu( $id, $doc->name?$doc->name:_('files') );
			
			echo '
			<nav class="submenu">
				<p class="button">
					<a class="noajax" href="' . base_url() . 'document/get/' . $id . '">' . _('download') . '</a>
				</p>
			</nav>';
		}
	}



	public function index( $id )
	{
		$this->card( $id );
	}
	
	
		
	public function dashboard()
	{
		$this->menu();
		$this->load->model('document_model');

		$this->load->view('dashboard', array(
			'document'	=> $this->document_model->getDocuments()
		));
	}



	public function card( $id )
	{
		$this->load->model('tag_model');
		$this->load->model('document_model');
		$doc = $this->document_model->getDocuments($id);
		$doc = $doc ? $doc[0] : null;

		if( $id>0 )
		{
			$this->load->view( 'menu', array(
				'id'					=> $id,
				'name'				=> $doc ? $doc->name : ''
			));
		}

		
		$this->load->view( 'card', array(
			'domid'				=> $doc ? 'SAVEdoc' : 'SAVEdocNew',
			'id'					=> $doc ? $doc->id : '',
			'name'				=> $doc ? $doc->name : '',
			'path'				=> $doc ? $doc->path : '',
			'online_date'		=> $doc ? $doc->online_date : '',
			'last_update'		=> $doc ? $doc->last_update : '',
			'fk_step'			=> $doc ? $doc->fk_step : '',
			'file_type'			=> $doc ? $doc->file_type : '',
			'file_name'			=> $doc ? $doc->file_name : '',
			'step'				=> $this->tag_model->getTags('document'),
			'ndownloads'		=> $doc ? $doc->count : 0
		));
	}


/*
	public static function menu( $id )
	{
		//echo parent::menu( $id );
		$CI =& get_instance();
		$CI->load->view('document/menu');
	}
*/
	
	

	public function share( $id )
	{
		$this->load->model('document_model');
		$doc = $this->document_model->getDocuments($id);
		$doc = $doc ? $doc[0] : null;

		//$this->menu();
		$this->load->view( 'menu', array(
			'id'					=> $id,
			'name'				=> $doc ? $doc->name : ''
		));

		$this->load->model('share_model');
		$this->load->model('user/user_model');

		$this->load->view('share', array(
			'id' => $id,
			'share' => $this->share_model->getShares($id),
			'user' => $this->user_model->getUsers()
		));
	}
	
	
	
	/*
	public function version( $id )
	{
		$this->menu();
		die('<p>' . _('Coming soon') . '...</p>');
	}

	*/
	
	

	public function download( $id )
	{
		$this->load->model('document_model');
		$doc = $this->document_model->getDocuments($id);
		$doc = $doc ? $doc[0] : null;

		//$this->menu();
		$this->load->view( 'menu', array(
			'id'					=> $id,
			'name'				=> $doc ? $doc->name : ''
		));

		$this->load->model('download_model');

		$this->load->view('download', array(
			'downloads'		=> $this->download_model->getDownloads( $id )
		));
	}
	


	
	
	public function get( $id )
	{
		if( ! connected() )
		{
		//	if( ! api_authentification() ) return;		
		}
		
		$user = currentUser();
		
		$this->load->model('document_model');
		$this->load->model('download_model');

		if( is_numeric($id) )
			$doc = $this->document_model->getDocuments($id, null, $user->id);
			else
			$doc = $this->document_model->getDocuments(null, $id, $user->id);	// get document by filename (but which one ?)


		if( ! count($doc) )
		{
			die( _("This file has been deleted !") );
		}

		$dir = $this->config->item('upload_path');
		$file = $dir . $doc[0]->file_name;
		$name = $doc[0]->path;
		$mime = $doc[0]->file_type;

		if( file_exists($file) )
		{
			$this->document_model->increaseCounter($id);
			$this->download_model->logDownload($id);
			
			header( 'Accept-Ranges: bytes' );
			header( 'Cache-Control: private' );
			header( 'Content-Disposition: attachment; filename=' . $name );
			header( 'Content-Length: ' . filesize($file) );
		
			readfile( $file );
			exit;
		}
		die( _('file deleted') );
	}




	/*

		The $_SESSION is altered by CodeIgniter so we can't retrieve $_SESSION[upload_progress_*]
		With PHP 5.6 and after, a function  session_reset() could save us, but for the moment we
		prefer to keep the compatibility with PHP5.3 and PHP5.4
		
		So, please use p.php instead of this method.

	*/
	public function progress()
	{
		//	session_reset();
		
		$key = ini_get("session.upload_progress.prefix") . $_REQUEST[ini_get("session.upload_progress.name")];

		//print_r( $_REQUEST );
		//print_r( $_SESSION );
		//echo $this->session->userdata[$key];
		die( $key );
	}
	
	
	public function save()
	{
		$this->load->model('document_model');
		echo $this->document_model->save();
	}

}
