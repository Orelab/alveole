<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Log_model extends MY_Model
{
	public $table = 'log'; // nécessaire pour utiliser MY_Model->clean_array()


	public function getLogs( $ressource, $id )
	{
		return $this->db
						->select(array(
								'log.id as id',
								'log.date as date',
								'log.text as text',
			
								'log.fk_ressource',
								'log.ressource',
			
								'log.fk_document',
								'document.name as document',
								'document.file_name as file',
								'document.file_type as type',
			
								'log.fk_step',
								'tag.name as step'
							))
						->from('log')
						->where('log.ressource', $ressource)
						->where('log.fk_ressource', $id)
						->join('tag','log.fk_step=tag.id', 'left')
						->join('document','log.fk_document=document.id', 'left')
						->order_by('log.date asc')
						->get()
						->result();
	}

	
	
	public function save()
	{
		$id = $this->input->post('id');
		$data = $this->input->post();


		//-- document backup

		$this->load->model('document/document_model' );
		$docid = $this->document_model->save();

		if( is_int($docid) )
		{
			$data['fk_document'] = $docid;
		}
		unset( $data['file'] );


		//-- log backup

		if( isset($data['date']) )
		{
			$data['date'] = strtotime($data['date']);
		}
		
		$this->clean_array( $data );

		if( $id )
		{
			if( $this->db->update('log', $data, 'id = ' . $id ) )
				return $id;
				else
				return $this->db->_error_message();
		}
		else
		{
			if( ! $data['fk_ressource'] )
			{
			//	return 'Error : please select a valid project';
			}

			if( $this->db->insert('log', $data ) )
				return $this->db->insert_id();
				else
				return $this->db->_error_message();
		}

	}


}
