<?php defined('BASEPATH') OR exit('No direct script access allowed');



class MY_model extends CI_Model
{
	public $table = '';
	


	public function __construct()
	{
		parent::__construct();
	}
	
	
	/**
	 *
	 *	Cette méthode permet de supprimer toutes les clés du tableau fourni,
	 *	qui ne sont pas un champ de la table courante ($this->table).
	 *
	 */
	public function clean_array( &$arr )
	{
		/*
			SQLite query to get columns list
		*/
		//$fields = $this->db->query("PRAGMA table_info(" . $this->table . ")")->result();
		
		/*
			MySQL query to do the same thing
		*/
		$fields = $this->db->query("SHOW COLUMNS FROM " . $this->table)->result();

		$fields = array_values( array_map(['MY_Model','extract_name'], $fields) );	// Late Static Binding, >=PHP5.3

		foreach( $arr as $key => &$val )
		{
			if( ! in_array($key, $fields) ) unset( $arr[$key] );
		}
	}
	
	private static function extract_name( $row )
	{
		return $row->Field;	// MySQL
		return $row->name;	// SQLite
	}
	
}	


