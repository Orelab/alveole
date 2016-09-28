<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Download_model extends MY_Model
{
	public $table = 'download'; // nécessaire pour utiliser MY_Model->clean_array()



	public function  getDownloads( $idDocument=null )
	{
		if( $idDocument )
		{
			$this->db->where('download.fk_document', $idDocument );
		}

		$recordset = $this->db
							->select('*')
							->from('download')
							->join('user', 'download.fk_user=user.id')
							->get()
							->result();

		return $recordset;
	}
	
	
	
	
	public function logDownload( $id )
	{
		$this->load->model('user_model');
		$user = $this->user_model->currentUser();		

		$ip = $this->input->ip_address();
		
		if( function_exists('geoip_record_by_name') )
		{
			$geoip = geoip_record_by_name( $ip );
		}
		
		$data = array(
			'fk_document'	=> $id,
			'date'			=> time(),
			'ip'				=> $ip,
			'request'		=> serialize( function_exists('getallheaders') ? getallheaders() : '' ),
			'fk_user'		=> isset($user->id) ? $user->id : null,
			'latitude'		=> isset($geoip->latitude) ? $geoip->latitude : null,
			'longitude'		=> isset($geoip->longitude) ? $geoip->longitude : null
		);
		
		$this->db->insert( 'download', $data ); 
	}
	

}
