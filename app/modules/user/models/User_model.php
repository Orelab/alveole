<?php defined('BASEPATH') OR exit('No direct script access allowed');



class User_model extends MY_Model
{
	public $table = 'user'; // nécessaire pour utiliser MY_Model->clean_array()



	/*
		This method is only used in Dashboard>register()
	*/

	public function getUser( $id=null, $mail=null )
	{
		$this->load->model('user/role_model');

		if( $id )
		{
			$this->db->where('id', $id );
		}

		if( $mail )
		{
			$this->db->where('email', $mail );
		}
		
		$recordset = $this->db->get('user')->row();

		return $recordset;
	}



	public function getUsers( $id=null, $addRoles=false, $group=null )
	{
		$this->load->model('user/role_model');

		if( $id )
		{
			$this->db->where('id', $id );
		}
		
		if( $group )
		{
			$this->db->where('group', $group );
		}
		
		$recordset = $this->db->get('user')->result();
		
	
		// add roles if needed
		
		if( $addRoles )
		{
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


	public function activate( $key )
	{
		return $this->db
			->where('activation_key', $key)
			->update('user', array('can_connect'=>1));
	}
	
	
	public function currentUser( $fk_project=null )
	{
		//-- first, try to retrieve the customer associated to the given project
		
		if( $fk_project )
		{
			$this->load->model('user/role_model');
			$roles = $this->role_model->getRoles( $fk_project, null, 62 );

			if( count($roles) )
			{
				return $roles[0]->fk_user;
			}
		}


		//-- otherwise, try to retrieve the currently connected user
		
		$this->load->helper('secursession');

		if( connected() )
		{
			$this->load->library('session');
			return $this->db->from('user')->where('id', $this->session->userid)->get()->row();
		}

		return null;
	}
	




	public function identify( $email, $password )
	{
		$recordset = $this->db
						->from('user')
						->where(array(
								'email'			=> $email,
								'can_connect'	=> '1'
							))
						->get()
						->result();

		foreach( $recordset as $contact )
		{
			if( password_verify($password, $contact->md5pass) )
			{
				return $contact;
			}
		}
		return false;
	}

	
	
	
	public function save()
	{
		$this->load->model('user/user_model');
		$me = $this->user_model->currentUser();		

		$id = $this->input->post('id');
		$data = array();
		
		foreach( $this->input->post() as $key => $val )
		{
			switch( $key )
			{
				case 'md5pass' :
					if( $val!=$this->input->post('verif') )
						return false;
						else
						{
							$data[$key] = password_hash($val, PASSWORD_DEFAULT);

							if(isset($me->id) )
							{
								if( $me->id == $id )
								{
									$this->session->set_userdata('password',$val);
								}
							}
						}
					break;
				
				case 'verif' :
					break;
				
				

				/*
					'group' and 'can_connect' can only be changed by an admin for security reasons
					'email' too for administrative reasons (it is used as the login and must be unique)
				*/

				case 'group' :
				case 'can_connect' :
				case 'email' :
					if( is_group('admin') )
					{
						$data[$key] = $val;
					}


				default :
					$data[$key] = $val;
			}
		}
		
		$this->clean_array( $data );
		
		if( $id )
			return $this->db->update('user', $data, 'id = ' . $id );
			else
			return $this->db->insert('user', $data );
	}

}
