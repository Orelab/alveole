<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');








//-- return the currently connected user

if ( ! function_exists('connected') )
{
	function currentUser()
	{
		$CI =& get_instance();

		$CI->load->library('session');

		if( ! $CI->session->userid )
		{
			return false;
		}

		$CI->load->model('user/user_model');

		$user = $CI->user_model->getUsers($CI->session->userid);

		return ( ! $user || count($user)!=1 ) ? false :  $user[0];
	}
}




//-- return the currently connected user

if ( ! function_exists('connected') )
{
	function connected()
	{
		$CI =& get_instance();

		$CI->load->library('session');

		if( ! $CI->session->userid )
			return false;
			else
			return true;

		/*

		// enforced controls (remove previous 'else return true;')

		$CI->load->model('user/user_model');

		$user = $CI->user_model->getUsers($CI->session->userid);

		if( ! $user || count($user)!=1 )
		{
			return false;
		}

		return ( $user[0]->can_connect == '1' );

		*/
	}
}



//-- die() if not connected
	
if ( ! function_exists('check_identification') )
{
	function check_identification()
	{
		if( ! connected() )
		{
			session_destroy();
			//redirect( "/?message=Identifiant ou mot de passe invalide." );
			die('<script>window.location.href="' . base_url() . '"</script>');
		}
	}
}




//-- return the currently connected user

if ( ! function_exists('is_group') )
{
	function is_group($group)
	{
		$CI =& get_instance();
		$CI->load->model('user/user_model');
		$user = $CI->user_model->currentUser();

		if( isset($user->group) )
			return ($user->group == $group );
			else
			return false;
	}
}	





//-- Does the given view accessible to the current user ?

if ( ! function_exists('allowed') )
{
	function allowed( $view )
	{
		$CI =& get_instance();
		
		$CI->load->model('user/user_model');
		$CI->load->model('right_model');

		$user = $CI->user_model->currentUser();
		$right = $CI->right_model->allowed( isset($user->group)?$user->group:'', $view );

		return $right ? true : false;
	}
}





//-- API authentification (return the project ID, or false)
	
if ( ! function_exists('api_authentification') )
{
	function api_authentification()
	{
		//-- récupération du domaine du projet
		
		$CI = get_instance();
		$CI->load->model('project_model');
		$project = $this->getProjects( $id );

		
		//-- comparaison de l'IP client avec l'IP du du domaine pingué
		
		return $_SERVER['REMOTE_ADDR']==gethostbyname($project->url) ? $project->id : false;
	}
}





