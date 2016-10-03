<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Configuration_model extends MY_Model
{
	public $table = 'configuration';




	public function get( $key )
	{
		$row = $this->db->select()
						->from('configuration')
						->where('configuration.`key`', $key )
						->order_by('key', 'asc')
						->get()
						->row();

		return isset($row->value) ? $row->value : false;
	}



	/*
		This method allows to retrieve the general configuration.
		General configuration allows to get all the data stored
		in the configuration table, except all the keys starting
		with 'user*'.
		
		getGeneral() returns the data in an associative array ( k=>v, k=>v )
		getGeneral(true) returns the data in an array of recordset ( (k,v,t), (k,v,t) )
	*/
	public function getGeneral( $full_result=false )
	{
		$config = array();

		$recordset = $this->db->select()
						->from('configuration')
						->where('configuration.`type` !=', 'hidden')
						->order_by('key', 'asc')
						->get()
						->result();

		if( $full_result )
		{
			return $recordset;
		}

		foreach( $recordset as $r )
		{
			$config[$r->key] = $r->value;
		}

		return $config;
	}



	//-- preferences = user configuration

	public function getPreferences( $idUser=null )
	{
		if( ! $idUser )
		{
			$user = currentUser();

			if( isset($user->id) )
				$idUser = $user->id;
				else
				$idUser = 'default';	// no user connected : returning default config
		}



		if( $idUser == '*' )
		{
			$this->db->where('configuration.key LIKE', 'user%' );
		}
		else
		{
			$this->db->where_in('configuration.key', array('user'.$idUser,'userdefault') );
		}
		
		$recordset = $this->db
						->select()
						->from('configuration')
						->order_by('key ASC')
						->get()
						->result();

		if( count($recordset) )
		{
			$prefs = json_decode( $recordset[0]->value );
			$prefs->key = $recordset[0]->key;

			return $prefs;
		}
		else
		{
			return null;
		}
	}



	public function get_userpref( $keylist )
	{
		if( ! is_array($keylist) )
		{
			return false;
		}

		//-- retrieve current user
		
		$this->load->model('user_model');
		$user = $this->user_model->currentUser();
		$userid = $user->id;


		//-- get info

		$recordset = $this->db->select()
						->from('configuration')
						->where('key', 'userpref'.$userid)
						->get()
						->row();

		if( ! ($recordset) )
		{
			return array();
		}
		
		$pref = json_decode( $recordset->value );

		foreach( $pref as $key => &$val )
		{
			if( ! in_array($key, $keylist) )
			{
				unset( $pref->{$key} );
			}
		}
		return $pref;
	}



	public function save_userpref( $key, $val )
	{
		//-- retrieve current user
		
		$user = currentUser();
		$userid = $user->id;

		$data = $this->db->select()
						->from('configuration')
						->where('key', 'userpref'.$userid)
						->get()
						->row();
		
		if( ! $data )
		{
			$data = array($key => $val);

			if( $this->db->insert('configuration', array(
					'key'=>'userpref'.$userid,
					'value'=>json_encode($data))
			))
				return $this->db->insert_id();
				else
				return $this->db->_error_message();
		}

		else
		{
			$data = json_decode( $data->value );
			$data->{$key} = $val;
	
			if( $this->db->update('configuration', 
					array('value'=>json_encode($data,JSON_FORCE_OBJECT)),
					'key="userpref'.$userid.'"'
			))
				return '1';
				else
				return $this->db->_error_message();
		}
		
	}

	
	public function save()
	{
		//-- retrieve user id

		$user = currentUser();
		$userid = $user->id;
		$userkey = 'user'.$user->id;
		
		//-- retrieve and merge data
		
		$prevdata = $this->getPreferences( $userid );
		$newdata = $this->input->post();


		// For signature only :
		// We disable XSS filtering to allow the inclusion of style 
		// attribute, necessary to align images, for example...		
		$newdata['signature'] = $this->input->post('signature', false);


		//-- don't save the line 'new' if no data typed

		end($newdata['mbox']);

		$last = key($newdata['mbox']);

		if( $newdata['mbox'][$last]['address'] == '' )
		{
			array_pop( $newdata['mbox'] );
		}


		//-- saving data

		if( $prevdata->key != 'userdefault' )
		{
			if( $this->db->update('configuration', array('value'=>json_encode($newdata,JSON_FORCE_OBJECT)), 'key="'.$userkey.'"') )
				return '1';
				else
				return $this->db->_error_message();
		}

		else
		{
			if( $this->db->insert('configuration', array('key'=>$userkey,'value'=>json_encode($newdata))) )
				return $this->db->insert_id();
				else
				return $this->db->_error_message();
		}
	}
}





