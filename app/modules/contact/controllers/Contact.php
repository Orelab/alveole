<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('secursession');
		check_identification();		

		//-- Load language
		$this->loadModuleTranslation('contact');


		$id = $this->uri->segment(3);

		if( $id>0 )
		{
			$this->load->model('contact_model');
			$contact = $this->contact_model->getContacts( $id );
			$contact = $contact ? $contact[0] : null;
			$name = $contact->name .' '. $contact->surname;

		//	$this->menu( $id, $name?$name:'Contacts' );

			$this->load->view('contact/menu', array(
				'name' => $name?$name:_('contact'),
				'mail' => $contact->mail,
				'id' => $id
			));

		}
	}


	static public function init()
	{
		Contact::loadModuleTranslation('contact');

		return array(
			"definition" => array(
				"title" => "Contact manager (new)",
				"version" => "1.0",
				"description" => "This module rules your contacts.",
				"url" => "http://www.idee-lab.fr",
				"author" => "Aurélien Chirot"
			),

			"menu" => array(
				array(
					"title" => _("contacts"),
					"url" => "contact/dashboard",
					"order" => "21"
				)
			)
		);
	}


	public function index( $id )
	{
		$this->card( $id );
	}
	
	
	public function dashboard()
	{
		$this->load->model('contact/contact_model');

		$this->load->view('contact/dashboard', array(
			'contact'		=> $this->contact_model->getContacts(null, true)
		));
	}


	
	public function card( $id )
	{
		$this->load->model('project/project_model');
		$this->load->model('contact/contact_model');
		$contact = $this->contact_model->getContacts( $id );
		$contact = $contact ? $contact[0] : null;

		$this->load->view('contact/card', array(
			'domid'				=> $contact ? 'SAVEcontact' : 'SAVEcontactNew',
			'id'					=> $contact ? $contact->id : '',
			'name'				=> $contact ? $contact->name : '',
			'surname'			=> $contact ? $contact->surname : '',
			'mail'				=> $contact ? $contact->mail : '',
			'meta'				=> $contact ? json_decode($contact->meta) : array()
		));
	}




	/*
		TODO : when it will be possible for modules to integrate
		second level menus, the following function should be removed
		and added directly in the neowebmail module.
	*/
	public function email( $id )
	{
		$this->load->model('contact/contact_model');
		$contact = $this->contact_model->getContacts( $id );
		$contact = $contact ? $contact[0] : null;
		
		$this->load->view('neowebmail/mailsearch', array(
			'address' => $contact->mail
		));

		$this->load->view('neowebmail/dashboard');
	}



	public function get_contactpref()
	{
		$keylist = json_decode( $this->input->post('keylist') );

		if( ! is_array($keylist) )
		{
			$keylist = array($keylist);
		}

		$this->load->model('configuration/configuration_model');
		$pref = $this->configuration_model->get_contactpref( $keylist );

		die( json_encode($pref) );
	}



	public function save_contactpref()
	{
		$key = $this->input->post('key');
		$val = $this->input->post('val');
		
		$this->load->model('configuration/configuration_model');
		$res = $this->configuration_model->save_contactpref( $key, $val );
		
		die( $res );
	}




	public function save()
	{
		$this->load->model('contact/contact_model');
		echo $this->contact_model->save();
	}


}
