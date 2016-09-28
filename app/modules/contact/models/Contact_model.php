<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Contact_model extends MY_Model
{
	public $table = 'contact_meta'; // nécessaire pour utiliser MY_Model->clean_array()






	public function getContacts( $id=null, $addRoles=false )
	{
		$me = currentUser();		

		if( $id )
		{
			$this->db->where('contact.id', $id );
		}
		
		$recordset = $this->db
			->where('fk_owner', $me->id)
			->get('contact')
			->result();


		//-- adding meta

		foreach( $recordset as &$r )
		{
			$meta = $this->db
				->from('contact_meta')
				->where('fk_contact', $r->id)
				->order_by('order')
				->get()
				->result();
				
			add_object_property( $r, 'meta', json_encode($meta) );
		}


		//-- add roles if needed
		
		if( $addRoles )
		{
			$this->load->model('contact/role_model');

			foreach( $recordset as $key => $val )
			{
				$roles = $this->role_model->getRoles( null, $val->id );

				$step_name_list = array();
				$step_id_list = array();
				
				foreach( $roles as $r )
				{
					$step_name_list[] = $r->sname;
					$step_id_list[] = $r->sname;
				}
				add_object_property( $recordset[$key], 'role', implode('|',array_unique($step_name_list))   );
				add_object_property( $recordset[$key], 'roleid', implode('|',array_unique($step_id_list))   );
			}
		}

		return $recordset;
	}


	
	
	
	public function save()
	{
		$me = currentUser();		

		$id = $this->input->post('id');
		$data = $this->input->post();

		if( ! isset($data['fk_owner']) )
		{
			$data['fk_owner'] = $me->id;
		}

		$this->table = 'contact';
		$this->clean_array( $data );


		if( $id )
			$response = $this->db->update('contact', $data, 'id = ' . $id );
			else
			$response = $this->db->insert('contact', $data );
			
		die( $this->db->last_query() );
		
		return $response;
	}






	public function save_meta()
	{
		$id = $this->input->post('id');
		$data = $this->input->post();

		$this->clean_array( $data );

		if( $id )
			$response = $this->db->update('contact_meta', $data, 'id = ' . $id );
			else
			$response = $this->db->insert('contact_meta', $data );
			
		//die( $this->db->last_query() );
		
		return $response;
	}





	public function save_order()
	{
		$list = $this->input->post();
		$rank = 1;

		if( isset($list['order']) )
			$list = json_decode( $list['order'] );
			else
			return false;

		foreach( $list as $l )
		{
			$id = str_replace('SAVEmeta', '', $l);

			$this->db
				->set('order', $rank++ )
				->where('id', $id)
				->update('contact_meta');
		}
	}






	public function delete_meta()
	{
		$id = $this->input->post('id');
		$me = currentUser();

		//-- current user is owner and allowed ?

		$nb = $this->db
			->from('contact_meta')
			->join('contact', 'contact_meta.fk_contact = contact.id')
			->where('contact_meta.id', $id)
			->where('contact.fk_owner', $me->id)
			->get()
			->num_rows();

		if( $nb == 1 )
		{
			return $this->db
				->where('id', $id)
				->delete('contact_meta');
		}

		return false;
	}

}





