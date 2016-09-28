<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Ticket_model extends MY_Model
{
	public $table = 'ticket'; // nécessaire pour utiliser MY_Model->clean_array()


	public function getTicket( $id )
	{
		return $this->db->from('ticket')->get()->row();
	}



	
	public function getTickets( $idProject=null, $idParent=null, $addChildren=false, $openOnly=false, $from=null, $to=null )
	{
		if( $idProject )
		{
			$this->db->where('ticket.fk_project', $idProject);
		}

		if( $idParent )
		{
			$this->db->where('ticket.fk_parent', $idParent);
			$this->db->or_where('ticket.id', $idParent);
		}

		if( ! $addChildren )
		{
			$this->db->where('ticket.fk_parent IS NULL');
		}

		if( $from )
		{
			$this->db->where('ticket.date >', $from );
		}

		if( $to )
		{
			$this->db->where('ticket.date <=', $to );
		}

		$result = $this->db
						->select(array(
								'ticket.id',
								'ticket.fk_parent',
								'ticket.fk_step',
								
								'ticket.fk_user',
								'user.uname',
								'user.usurname',
								'user.email',
								
								'ticket.fk_document',
								'document.name AS dname',
								'document.path',
								'document.file_type',
								'document.file_name',
								
								'ticket.fk_project',
								'project.name AS pname',
								
								'ticket.date',
								'ticket.text',
								'ticket.price',
								'COALESCE(ticket.fk_parent,0) || ticket.id AS ordering',		// CONCAT( id, fk_parent )
								
								'COUNT(answers.id) AS replies'
							))
						->from('ticket')
						->join('user', 'ticket.fk_user = user.id', 'left')
						->join('document', 'ticket.fk_document = document.id', 'left')
						->join('project', 'ticket.fk_project = project.id', 'left')
						->join('ticket answers', 'answers.fk_parent = ticket.id', 'left')
						->order_by('ticket.date asc')
						->group_by('ticket.id', 'fk_parent','fk_step',
								'fk_user','uname','usurname','email',
								'fk_document','dname','path','file_type','file_name',
								'fk_project','pname',
								'date','text','price','ordering'
							)
						->get()
						->result();

//print_r( $result ); die('fin');
						
			foreach( $result as $key => &$r )
			{
				$last = $this->db
								->from('ticket')
								->where('ticket.fk_parent', $r->id)
								->or_where('ticket.id', $r->id)
								->order_by('ticket.id desc')
								->join('tag','ticket.fk_step = tag.id')
								->get()
								->row();

				$last_step = !empty($last->fk_step) ? $last->fk_step : null;
				$last_step_name = !empty($last->name) ? $last->name : null;
				$last_text = !empty($last->text) ? $last->text : null;
				
				add_object_property( $result[$key], 'last_step', $last_step );
				add_object_property( $result[$key], 'last_step_name', $last_step_name );
				add_object_property( $result[$key], 'last_text', $last_text );

				if( $openOnly && in_array($r->last_step,array(16)) ) // solved (16) ; opened (13) ; in progress (14) ...
				{
					unset( $result[$key] );
				}
			}

			return $result;
	}





	
	public function save()
	{
		$data = $this->input->post();
		$id = $this->input->post('id');

		//-- document backup

		$this->load->model('document/document_model' );
		$docid = $this->document_model->save();

		if( is_int($docid) )
		{
			$data['fk_document'] = $docid;
		}
		unset( $data['file'] );

		foreach( $data as $key => $val ) $data[$key] = urldecode($val);	// tickets provenants de Wordpress (a vérifier ?)
		

		//-- log backup

		$this->clean_array( $data );


		if( $id>0 )
		{
			if( $this->db->update('ticket', $data, 'id='.$id) )
				return $id;
				else
				return $this->db->_error_message();
		}		
		else
		{
			if( ! isset($data['fk_user']) )
			{
				$this->load->model('user/user_model' );
				$user = $this->user_model->currentUser();
				$data['fk_user'] = $user->id;
			}

			$data['date'] = time();

			if( $this->db->insert('ticket', $data) )
				return $this->db->insert_id();
				else
				return $this->db->_error_message();
		}
	}

	
}




