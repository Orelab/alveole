<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Memo_model extends MY_Model
{
	public $table = 'memo'; // nécessaire pour utiliser MY_Model->clean_array()

	
	
	public function getMemo()
	{
		return $this->db
						->select('memo.*')
						->from('memo')
						->where('user.id', $this->session->userid)
						->join('user', 'memo.fk_user = user.id')
						->order_by('order asc')
						->get()
						->result();
	}


	
	public function save()
	{
		$data = $this->input->post();
		$id = $this->input->post('id');


		//-- retrieve user id and update $data
				
		$this->load->model('user_model');
		$user = $this->user_model->currentUser();

		$data['fk_user'] = $user->id;
		unset( $data['name_user'] );

		$this->clean_array( $data );
		
		
		//-- saving data
				
		if( $id )
		{
			if( $this->db->update('memo', $data, 'id='.$id) )
				return $id;
				else
				return $this->db->_error_message();
		}		
		else
		{
			if( $this->db->insert('memo', $data) )
				return $this->db->insert_id();
				else
				return $this->db->_error_message();
		}
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
			$id = str_replace('SAVEtask', '', $l);

			$this->db
				->set('order', $rank++ )
				->where('id', $id)
				->update('memo');
		}
	}


	public function delete()
	{
		$id = $this->input->post('id');
		
		if( $this->db->delete('memo', array('id'=>$id) ) ) 
			return $id;
			else
			return $this->db->_error_message();
	}

}




