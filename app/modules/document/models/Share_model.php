<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Share_model extends MY_Model
{
	public $table = 'share'; // nécessaire pour utiliser MY_Model->clean_array()

	
	
	public function getShares( $id )
	{
		if( $id )
		{
			$this->db->where('fk_document', $id );
		}

		return $this->db->from('share')->get()->result();
	}


	
	public function save()
	{
		$data = array(
			'fk_document'		=> $this->input->post('key1'),
			'fk_user'			=> $this->input->post('key2')
		);

		//-- add right
		
		if( $this->input->post('value') )
		{
			if( $this->db->insert('share', $data) )
				return $this->db->insert_id();
				else
				return $this->db->_error_message();
		}
		
		//-- remove right
		else
		{
			if( $this->db->delete('share', $data) )
				return 0;
				else
				return $this->db->_error_message();
		}
	}
	

}




