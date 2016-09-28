<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Right_model extends MY_Model
{
	public $table = 'right'; // nécessaire pour utiliser MY_Model->clean_array()



	public function getRights()
	{
		return $this->db
						->from('right')
						->order_by('group')
						->order_by('view')
						->get()
						->result();
	}
	
	
	
	public function allowed( $group, $view )
	{
		$alt = explode('/', $view);
		if( count($alt)>1 )
		{
			array_pop($alt);
			$alt[] = '*';
		}
		$alt = implode('/', $alt);
		
		
		$query = $this->db
						->from('right')
						->where_in('group', array('*',$group))
						->where_in('view', array($view, $alt, '*'))
						->get();

		//echo $this->db->last_query() . "<br/>";

		$data = $query->num_rows();

		return $data>=1 ? true : false;
	}
	
	
	
	
	public function save()
	{
		$id = $this->input->post('id');
		
		$data = array(
			'group'			=> $this->input->post('group'),
			'view'			=> $this->input->post('view')
		);

		if( $id )
			return $this->db->update('right', $data, 'id = ' . $id );
			else
			return $this->db->insert('right', $data );
	}
	
	
	
	public function delete()
	{
		$id = $this->input->post('id');
		
		if( $this->db->delete('right', array('id'=>$id) ) ) 
			return $id;
			else
			return $this->db->_error_message();
	}

}
