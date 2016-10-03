<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Document_model extends MY_Model
{
	public $table = 'document'; // nécessaire pour utiliser MY_Model->clean_array()

	
	/*

		getDocuments(id) : get a document by ID
		getDocuments(null,string) : get a document by name
		getDocuments(null,null,id) : get all documents only visible by a specific user
		getDocuments(id,null,id) : get a documents only if visible by the given user

	*/
	public function getDocuments( $id=null, $file=null, $restrict_user=null )
	{
		if( is_group('client') )
		{
			$this->load->model('user_model');
			$user = $this->user_model->currentUser();

			$this->db->where('share.fk_user', $user->id )
						->join('share', 'document.id = share.fk_document', 'left');
		}

		if( $id )
		{
			$this->db->where('document.id', $id );
		}

		if( $file )
		{
			$this->db->where('document.file_name', $file );
		}

		$recordset = $this->db
							->select(array(
								'document.id',
								'document.name',
								'document.path',
								'document.online_date',
								'document.last_update',
								'document.count',
								'document.fk_step',
								'document.file_type',
								'document.file_name',
								'document.ressource',
								'document.fk_ressource',
								'document.fk_owner',
								'user.id AS uid',
								'user.uname AS uname',
								'user.usurname AS usurname',
								'user.email AS email',
								'tag.name AS sname'
								))
							->from('document')
							->join('tag', 'document.fk_step = tag.id', 'left')
							->join('user', 'document.fk_owner = user.id', 'left')
							->get()
							->result();


		// append total shares
										
		foreach( $recordset as $key => $val )
		{
			$totalshare = $this->db
						->select('COUNT(fk_user) AS share')
						->from('share')
						->where('fk_document', $val->id )
						->get()
						->row();
						
			add_object_property( $recordset[$key], 'share', $totalshare->share );
		}


		// public share ?
										
		foreach( $recordset as $key => $val )
		{
			$public = $this->db
						->from('share')
						->where('fk_document', $val->id )
						->where('fk_user', '*')
						->count_all_results();
						
			if( $public )
			{		
				$recordset[$key]->share = 'public';
			}
		}



		//-- check rights
		
		/*
			We have to check four cases to know if a user is allowed to access the file :
				- Does the current user is an admin ?
				- Does the current user the owner ?			!!!!!!!!!   NEED TO CODE HERE ! ! ! OWNER NOT SAVED YET ^^'   !!!!!!!!!
				- Does the file accessible to public ?
				- Does the file shared with me ?
		* /

		$allowed = false;

		if( is_group('admin') )
		{
			$allowed = true;
		}

		elseif( $doc[0]->share == "public" )
		{
			$allowed = true;
		}

		else
		{
			$this->load->model('share_model');
			$shares  = $this->share_model->getShares( $doc[0]->id );
	
			$user = currentUser();
			$uid = isset($user->id) ? $user->id : null;

			foreach( $shares as $s )
			{
				if( $s->fk_user == $uid )
				{
					$allowed = true;
				}
			}
		}

		if( ! $allowed )
		{
			die( _('Sorry, you are not allowed.') );
		}

*/

		return $recordset;
	}
	
	
	
	
	
	public function increaseCounter( $id )
	{
		$this->db
				->where('id', $id)
				->set('count', 'count + 1', false)
				->update('document');
	}
	
	
	
	
	
	
	public function save()
	{
		$this->load->library('upload' );
		$updateOnly = false;

		$data = $this->input->post();
		$id = $this->input->post('id');


		//-- upload and add document

//die( $this->check_file() );

		if( $this->upload->do_upload('file') )
		{
			$fileinfo = $this->upload->data();

			$data['path'] = $fileinfo['orig_name'];
			$data['file_name'] = $fileinfo['file_name'];
			$data['file_type'] = $fileinfo['file_type'];
		}
		else
		{
			$updateOnly = true;
		}




		//-- remove non-existing fields on table

		$this->clean_array( $data );

		
		
		//-- misc optimizations

		if( ! array_key_exists('online_date',$data) ) $data['online_date'] = time();
		if( ! array_key_exists('last_update',$data) ) $data['last_update'] = time();


		//-- DB recording
		
		if( $id )
		{
			unset( $data['online_date'] );
			
			if( $this->db->update('document', $data, 'id='.$id) )
				return $id;
				else
				return $this->db->_error_message();
		}		
		else
		{
			if( ! $updateOnly )
			{
				$user = currentUser();
				$data['fk_owner'] = $user->id;

				if( ! array_key_exists('name',$data) ) $data['name'] = $data['path'];
				
				if( $this->db->insert('document', $data) )
					return $this->db->insert_id();
					else
					return $this->db->_error_message();
			}
//			else return 'error';
		}
	}


	private function check_file( $file=null )
	{
		//$config['max_size'];
		return ini_get('upload_max_filesize');
	}
}
