<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Sabre\DAV;

class Calendar extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('secursession');
		check_identification();		

		//-- Load language
		$this->loadModuleTranslation('calendar');
	}



	static public function init()
	{
		Calendar::loadModuleTranslation('calendar');

		return array(
			"definition" => array(
				"title" => "Calendar",
				"version" => "1.0",
				"description" => "This is your personal and professionnal calendar.",
				"url" => "http://www.idee-lab.fr",
				"author" => "Aurélien Chirot"
			),

			"menu" => array(
				array(
					"title" => _('calendar'),
					"url" => "calendar/dashboard",
					"order" => "40"
				)
			),
		
			"widget" => array(
				array(
					"title" => _('today'),
					"url" => "calendar/widget_today",
					"order" => "40"
				)
			)
		);
	}

	
	public function dashboard()
	{
		$this->load->model('project/project_model');
		$project = $this->project_model->getProjects();

		$this->load->model('tag_model');
		$generic_tag = $this->tag_model->getTags('calendar');
		$project_tag = $this->tag_model->getTags('project');


		//-- remove disabled projects
		
		foreach( $project_tag as $key => $pt )
		{
			foreach( $project as $p )
			{
				$id = json_decode($pt->misc);

				if( isset($id->fk_project) )
				{
					if( $id->fk_project==$p->id && $p->active!='1' )
					{
						unset( $project_tag[$key] );
					}
				}
			}
		}

		$this->load->view('dashboard', array(
			'project' => $project,
			'generictag' => $generic_tag,
			'projecttag' => $project_tag
		));
	}


	public function widget_today()
	{
		echo '
			<article class="today half">
				<h2>' . _('today') . '</h2>
				<div class="calendrier jour"></div>
			</article>
		';
	}


	public function getCal()
	{
		//-- get parameters

		$this->load->model('user/user_model');
		$user = $this->user_model->currentUser();

		$url = $this->input->post('cal');
		parse_str($url, $cal);
		
		$start = $this->input->post('start');
		$end = $this->input->post('end');


		//-- find calendars if none given (for example : in the main dashboard)

		if( ! isset($cal['tag'])  )
		{
			$this->load->model('tag_model');
			$cal = $this->tag_model->getTags('calendar');

			foreach( $cal as $c )
			{
				$cal['tag'][] = $c->id;
			}

			$proj = $this->tag_model->getTags('project');

			foreach( $proj as $p )
			{
				$cal['tag'][] = $p->id;
			}
		}

		//-- generate data

		$this->load->model('calendar_model');
		$data = $this->calendar_model->getCalendar( $user->id, $cal['tag'], $start, $end );

		echo json_encode( $data );
	}



	public function getEvent()
	{
		$this->load->model('tag_model');
		$generic_tag = $this->tag_model->getTags('calendar');
		$project_tag = $this->tag_model->getTags('project');


		$idEvent = $this->input->post('id');

		if( empty($idEvent) )	// new event
		{
			$event = array(
				'id'				=> '',
				'fk_user'		=> '',
				'title'			=> '',
				'description'	=> '',
				'start'			=> '',
				'end'				=> '',
				'recurrence'	=> '',
				'interval'		=> '',
				'recend'			=> '',
				'fk_step'		=> '',
				'allDay'			=> ''
			);
		}


		elseif( is_numeric($idEvent) )	// load event
		{
			$this->load->model('user/user_model');
			$user = $this->user_model->currentUser();
	
			$this->load->model('calendar_model');
			$event = (array)$this->calendar_model->getEvent( $idEvent, $user->id );
		}


		else	// virtual billing event
		{
			$param = explode( '|', $idEvent);
			
			$this->load->model('project/bill_model');
			$event = (array)$this->bill_model->getBillAsEvent( $param[1], $param[2] );
		}




		/*
			setting bill tag as readonly
			It must be impossible to create an event in this calendar
			because these evens are generated automatically from the
			'bill' table.
		*/
		foreach( $generic_tag as $key => &$t )
		{
			if( $t->name == 'facture' )
			{
				$t->selectable = 0;
			}
		}

		$this->load->view('event', array(
			'id'				=> $event['id'],
			'fk_user'		=> $event['fk_user'],
			'title'			=> $event['title'],
			'description'	=> $event['description'],
			'start'			=> $event['start'],
			'end'				=> $event['end'],
			'recurrence'	=> $event['recurrence'],
			'interval'		=> $event['interval'],
			'recend'			=> $event['recend'],
			'tag'				=> $event['fk_step'],
			'taglist'		=> array_merge( $generic_tag, $project_tag),
			'allDay'			=> $event['allDay']
		));
	}


	public function caldav( $principal='aurelien.chirot@idee-lab.fr' )
	{
		require( APPPATH . 'libraries/SabreDAV/vendor/autoload.php' );

		$this->load->database();

		$pdo = new PDO(
			'mysql:dbname='.$this->db->database.';host='.$this->db->hostname,
			$this->db->username,
			$this->db->password
		);
//		$pdo = new \PDO('sqlite:db.sqlite');
		
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		function exception_error_handler($errno, $errstr, $errfile, $errline)
		{
		    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
		}
		set_error_handler("exception_error_handler");

		$authBackend = new \Sabre\DAV\Auth\Backend\PDO($pdo);
		$principalBackend = new \Sabre\DAVACL\PrincipalBackend\PDO($pdo);
		$carddavBackend = new \Sabre\CardDAV\Backend\PDO($pdo);
		$caldavBackend = new \Sabre\CalDAV\Backend\PDO($pdo);
		
		$nodes = [
		    new \Sabre\CalDAV\Principal\Collection($principalBackend),
		    new \Sabre\CalDAV\CalendarRoot($principalBackend, $caldavBackend),
		    new \Sabre\CardDAV\AddressBookRoot($principalBackend, $carddavBackend),
		];
		
		$server = new \Sabre\DAV\Server($nodes);

		$server->addPlugin(new \Sabre\DAV\Auth\Plugin($authBackend));
		$server->addPlugin(new \Sabre\DAV\Browser\Plugin());
		$server->addPlugin(new \Sabre\CalDAV\Plugin());
		$server->addPlugin(new \Sabre\CardDAV\Plugin());
		$server->addPlugin(new \Sabre\DAVACL\Plugin());
		$server->addPlugin(new \Sabre\DAV\Sync\Plugin());

		$server->exec();
	}
	
	
	public function save()
	{
		$this->load->model('calendar_model');
		echo $this->calendar_model->save();
	}


	public function delete()
	{
		$this->load->model('calendar_model');
		echo $this->calendar_model->delete();
	}
}
