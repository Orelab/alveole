<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Role_model extends MY_Model
{
	public $table = 'role'; // nécessaire pour utiliser MY_Model->clean_array()


	public function getRoles( $idProject=null, $idUser=null, $idStep=null )
	{
		if( $idProject )
		{
			$this->db->where('role.fk_project', $idProject );
		}
		
		if( $idUser )
		{
			$this->db->where('role.fk_user', $idUser );
		}
		
		if( $idStep )
		{
			$this->db->where('role.fk_step', $idStep );
		}

		return $this->db
						->select(array(
							'role.id',
							'role.fk_user',
							'role.fk_project',
							'role.fk_step',
							'role.date',
							'user.uname',
							'user.usurname',
							'user.email',
							'project.name AS pname',
							'tag.name AS sname'
							))
						->from('role')
						->join('user', 'role.fk_user = user.id')
						->join('tag', 'role.fk_step = tag.id' )
						->join('project', 'role.fk_project = project.id' )
						->where('on', 1)
						->get()
						->result();
	}
	
	
	
	/*
		With this method, it is possible to save/update a role in two ways :
		 - by passing an array of values in parameter
		 - with no parameter set, by saving the $_POST values
	*/
	public function save( $data=null )
	{
		$id = $this->input->post('id');

		if( ! $data )
		{
			$data = $this->input->post();
			$data['date'] = time();
		}

		$this->clean_array( $data );
		
		
		//-- saving data
				
		if( $id )
		{
			if( $this->db->update('role', $data, 'id='.$id) )
				return $id;
				else
				return $this->db->_error_message();
		}		
		else
		{
			if( $this->db->insert('role', $data) )
				return $this->db->insert_id();
				else
				return $this->db->_error_message();
		}
	}
	
	
	
	public function delete()
	{
		$id = $this->input->post('id');

		if( $this->db->delete('role', array('id' => $id)) )		
		//if( $this->db->update('role', array('on'=>0),array('id'=>$id) ) ) 
			return $id;
			else
			return $this->db->_error_message();
	}

}
