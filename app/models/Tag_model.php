<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Tag_model extends MY_Model
{
	public $table = 'tag'; // nécessaire pour utiliser MY_Model->clean_array()



	/*
		get the tag id, according to the project id
	*/
	public function getTagIDFromProjectID( $idProject )
	{
		$recordset = $this->db
						->from('tag')
						->where('group', 'project')
						->get()
						->result();

		foreach( $recordset as $r )
		{
			$misc = json_decode( $r->misc );
			
			if( $misc->fk_project == $idProject )
			{
				return $r->id;
			}		
		}
		return false;
	}



	public function getTags( $group=null, $restriction=null )
	{
		if( $group )
		{
			$this->db->where('group', $group);		
		}		
		
		return $this->db
						->from('tag')
						->order_by('order')
						->get()
						->result();
	}

	
	
	public function delete()
	{
		$id = $this->input->post('id');

		if( $id )
		{
			if( $this->db->delete('tag', 'id = ' . $id ) )
				return $id;
				else
				return $this->db->_error_message();
		}
	}
	
	
	public function save( $data=null )
	{
		$id = $this->input->post('id');
		
		if( ! $data )
		{
			$data = $this->input->post();
		}

		$this->clean_array( $data );


		if( $id )
		{
			if( $this->db->update('tag', $data, 'id = ' . $id ) )
				return $id;
				else
				return $this->db->_error_message();
		}
		else
		{
			if( $this->db->insert('tag', $data ) )
				return $this->db->insert_id();
				else
				return $this->db->_error_message();
		}
	}



	public function saveorder()
	{
		//-- retrieve the tag list
		
		$idlist = $this->input->post('order');
		$idlist = json_decode( $idlist );

		foreach( $idlist as &$idtag )
		{
			$idtag = str_replace('SAVEtag', '', $idtag );
		}


		//-- verify that the tags are from the same group

		$tags = $this->db
					->from('tag')
					->where_in('id', $idlist )
					->get()
					->result();

		function getGroup( &$item )
		{
			$item = $item->group;
		}
		array_walk( $tags, 'getGroup');

		if( count(array_unique($tags)) != 1 ) // only one group allowed
		{
			return false;
		}


		//-- saving the order
		
		foreach( $idlist as $order => $id )
		{
			$this->db
				->where('id', $id)
				->update('tag', array('order'=>$order));
		}
	}

}



