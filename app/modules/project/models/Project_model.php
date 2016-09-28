<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Project_model extends MY_Model
{
	public $table = 'project'; // nécessaire pour utiliser MY_Model->clean_array()
	

	public function getProjects( $id=null, $apikey=null )
	{
		$this->load->model('project/ticket_model');

		
		// restrictions for all
		/*
		suppression de ce morceau de code car il interfère avec mysql 5.7 (full group by)
		et ne semble plus nécessaire étant donné que l'on utilise maintenant fk_owner

		if( ! $apikey /* ! is_group('admin')* / )
		{
			$this->load->model('user/user_model');
			$user = $this->user_model->currentUser();

			$this->db->select('role.fk_step')
						->where('role.fk_user', $user->id )
						->where('role.on', '1')
						->join('role', 'project.id = role.fk_project', 'left');
		}
		*/


		// retrieve a specific project

		if( $id )
		{
			$this->db->where('project.id', $id );
		}


		// find a project by apikey

		if( $apikey != null )
		{
			$this->db->where('project.apikey', $apikey );
		}

		
		// make the query

		$recordset = $this->db
								->select(array('project.*','user.uname','user.usurname'))
								->from('project')
								
								/*
									In case a user get many roles, we prevent 
									duplicate content by grouping the projects
								*/
								->join('user','project.fk_owner = user.id')
								->group_by('project.id')
								->get()
								->result();

		//die( $this->db->last_query() );


		// append opened ticket number
										
		foreach( $recordset as $key => $val )
		{
			$ticketlist = $this->ticket_model->getTickets( $val->id, null, false, true );
			add_object_property( $recordset[$key], 'openedticket', count($ticketlist)  );
		}


		// append total ticket number
										
		foreach( $recordset as $key => $val )
		{
			$totalticket = $this->db
						->select('COUNT(id) AS totalticket')
						->from('ticket')
						->where(array(
								'fk_project' => $val->id,
								'fk_parent' => null
							))
						->get()
						->row();

			add_object_property( $recordset[$key], 'totalticket', $totalticket->totalticket );
		}
		

		// append status (prospect OR client)

		foreach( $recordset as $key => $val )
		{
			$status = $this->db
						->select('COUNT(*) AS nb')
						->where('log.fk_ressource', $val->id)
						->where('log.ressource', 'project')
						->where('log.fk_step IN (13,14,15,16)')
						->order_by('log.date', 'desc')
						->get('log')
						->row();
			
			$status = $status->nb>0 ? 'client' : 'prospect';
			add_object_property( $recordset[$key], 'status', $status );
		}

		return $recordset;
	}
	
	

	public function getProjectFromAPI()
	{
		$api = $this->input->get_post('key');
		return $this->db->from('project')->where('apikey', $api )->get()->row();
	}
	
	
	public function save()
	{
		$id = $this->input->post('id');
		$data = $this->input->post();
		
		if( ! array_key_exists( 'date', $data) )
		{
			$data['date'] = time();
		}
		
		if( ! isset($data['fk_owner']) )
		{
			$me = currentUser();
			$data['fk_owner'] = $me->id;
		}
		
		$this->clean_array( $data );

		if( $id )
		{
			return $this->db->update('project', $data, 'id = ' . $id );
		}
		else
		{
			$this->db->insert('project', $data );
			return $this->db->insert_id();
		}
	}

}
