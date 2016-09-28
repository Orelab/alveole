<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ticket extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('secursession');
		check_identification();		

		//-- Load language
		$this->loadModuleTranslation('project');


		$id = $this->uri->segment(4);

		if( ! in_array($this->router->fetch_method(), array('save','detail','gui')) )
		{
			$this->load->model('project_model');
			$project = $this->project_model->getProjects( $id );
			$project = $project ? $project[0] : null;

			$this->load->view('menu', array('id'=>$id, 'name'=>$project->name));
		}
	}


	public function widget_ticket()
	{
		if( is_group('client') )
		{
			return;
		}
		$this->load->model('project/ticket_model');
		$this->load->model('tag_model');

		$ticket_list = $this->ticket_model->getTickets(null, null, false, true);

		if( ! count($ticket_list) )
		{
			return;
		}

		$this->load->view('project/ticket', array(
			'id'			=> 0,
			'tickets'	=> $ticket_list,
			'tag'			=> $this->tag_model->getTags('ticket')
		));
	}


	public function dashboard( $id )
	{
		$this->load->model('ticket_model');
		$this->load->model('tag_model');

		$this->load->view('project/ticket', array(
			'id'			=> $id,
			'tickets'	=> $this->ticket_model->getTickets($id),
			'tag'			=> $this->tag_model->getTags('ticket')
		));
	}

	
	
	public function detail( $id )
	{
		$this->load->model('ticket_model');
		$this->load->model('tag_model');

		$this->load->view('project/ticketdetail', array(
			'id'				=> $id,
			'detail'			=> $this->ticket_model->getTicket( $id ),
			'tickets'		=> $this->ticket_model->getTickets(null, $id, true),
			'tag'			=> $this->tag_model->getTags('ticket')
		));
	}

	
	
	
	public function save()
	{
		//-- saving the ticket

		$this->load->model('project/ticket_model');
		$idTicket = $this->ticket_model->save();


		/*
			Buggy notifications (seems not to find the proper $idProject,
			and so sending to all the contacts (included the ones who are
			not concerned by the project :/ )
		*/
		die( $idTicket );


		//-- send a notification by email

		$idProject = $this->input->post('fk_project');
die( $idProject );

		$this->load->model('role_model');
		$receivers = $this->role_model->getRoles( $idProject );

		$to = array();

		foreach( $receivers as $r )
		{
			$to[] = $r->uname . ' ' . $r->usurname . ' <' . $r->email . '>';
		}
		$to = implode(', ', $to);

		$subject = $this->input->post('fk_parent')
			? _("The incident as been updated") 
			: _("A new incident as been created");

		$message = $this->input->post('text')
			. "\r\n\r\n"
			. _("Check the incident history")
			. "&nbsp;:\r\n"
			. site_url() . 'ticket/detail/' . $idTicket;

		$this->load->model('user_model');
		$user = $this->user_model->currentUser();

		$headers = 'From: no-reply@idee-lab.fr' . "\r\n"
			. 'Reply-To: ' . $user->email;

		mail( $to, $subject, $message, $headers );
		echo $idTicket;
	}


}




