<?php


/*

	How does modules works ?
	
	First of all, each module MUST contain a static function called init()
	which returns an array defining four stuff :
	
	Definition : an array containing at least theses fields : name, version, 
		description, url, author.
	
	Dependences : a simple array of module names which are necessary if you
		want to see the module running correctly.

	Menu : A module can register itself in the main menu.
	
	Widget : The dashboard (the page which is shown when you connect to the
		program) is composed of widgets. A module can register its own Widget.

	Here is an example of array that can be returned by an init() function
	(This one is taken from the Project module) :

		array(

			"definition" => array(
				"title" => "Project management",
				"version" => "1.0",
				"description" => "",
				"url" => "http://www.idee-lab.fr",
				"author" => "Aurélien Chirot"
			),

			"dependences" => array(
				array(
					"require" => "user",
					"version" => "1.0"
				)
			),
			
			"menu" => array(
				array(
					"title" => "Projets",
					"url" => "project/dashboard",
					"order" => "10"
				)
			),
			
			"widget" => array(
				array(
					"title" => "Saisie historique",
					"url" => "project/widget_historic",
					"order" => "20"
				),
	
				array(
					"title" => "Tickets en cours",
					"url" => "project/widget_ticket",
					"order" => "50"
				),
	
				array(
					"title" => "Factures impayées",
					"url" => "project/bill/widget_unpaid_bill",
					"order" => "60"
				)
			);
		);


	Note that if a module have to define more than ONE of these elements, you can
	add a number in the key (see the example).


	Module composition (each element is optional) :
	
		assets/
		controllers/
		language/
		models/
		views/

	Controllers/, views/ and models/ contain PHP object files, like the
	ones available in Code Igniter.
	
	The folder assets/ can contain CSS and JS files. Each of these files
	are loaded dynamically (and compressed if needed) by the interface.
	
	The folder contains translation files (see gettext for more documentation
	about how these stuff works).

*/

class Module_manager
{


	public function preload_hooks()
	{
		$config = $this->configure();
	}




	/*
		This function is costly as it call each main module controller,
		but happily, we should have to launch it only once, for the main
		application template.
	*/
	public function configure()
	{
		$config = array(
			"modules"		=> array(),
			"dependences"	=> array(),	// not implemented yet ;(
			"menu"			=> array(),
			"widget"			=> array(),
			"hook"			=> array()	// not implemented yet ;(
		);

		/*
			Parsing module configurations
		*/
		$dir = glob( APPPATH.'modules/*', GLOB_ONLYDIR );

		foreach( $dir as $d )
		{
			if( is_dir($d) && $this->is_active($d) )
			{
				$folders = explode('/',$d);
				$module = ucfirst(end($folders));
				$class = $d.'/controllers/'.$module.'.php';

				if( is_file($class) )
				{
					include_once($class);

					if( method_exists($module, 'init') )
					{
						$module_cnf = call_user_func($module . '::init');

						if( isset($module_cnf['definition']) )
						{
							$module_cnf['definition']['_module'] = $module;
							$config['modules'][$module] = $module_cnf['definition'];
							unset($module_cnf['definition']);
						}
	
						foreach( $module_cnf as $key => $definition )
						{
							foreach($definition as &$val)
							{
								$val['_module'] = $module;
								$config[$key][] = $val;
							}
						}
					}
				}
			}
		}

		//-- sorting the menu and widgets
		if( ! function_exists('sort_by_order') )
		{
			function sort_by_order( $a, $b )
			{
				if( intval($a['order']) == intval($b['order']) )
				{
					return 0;
				}
				return intval($a['order']) > intval($b['order']) ? +1 : -1;
			}
		}
		usort( $config["menu"], 'sort_by_order' );
		usort( $config["widget"], 'sort_by_order' );

		
		//-- auto-register the modules
		/*

		foreach( $config['modules'] as $c )
		{
			$this->register( $c );
		}

		*/

		return $config;
	}



	/*
		This function check if the given module is yet registered in the database,
		or if it needs to be registered/updated.
		
		@param $module : an array of values which could be registered in the database
		@return true (updated) or false (not updated)
	*/
	public function register( $module )
	{
		if( ! isset($module['_module']) )
		{
			return false;
		}

		$CI =& get_instance();
		$CI->load->model('online/module_model');

		$recordset = $CI->module_model->get( $module['_module'] );

		//-- register the new module

		if( count($recordset) <= 0 )
		{
			$module['name'] = $module['_module'];
			$module['status'] = "disabled";

			$CI->module_model->save( $module );
			return true;
		}

		//-- update the module

		elseif( version_compare( $recordset[0]->version, $module['version'] ) )
		{
			$module['id'] = $recordset[0]->id;
			$CI->module_model->save( $module );
			return true;
		}
		
		return false;
	}



	/*
		This method concatenate each stylesheet we can find in the assets/
		folder of each module.
		
		@param $minify : will the CSS be minimized ? (default true, boolean field)
		@return a string containing the module's css
	*/
	public function css( $minify=true )
	{
		header('Content-type: text/css');

		$module_css = "";


		//-- Module styling

		$modules_directory = glob( APPPATH.'modules/*', GLOB_ONLYDIR );

		foreach( $modules_directory as $d )
		{
			if( is_dir("$d/assets") && $this->is_active(basename($d)) )
			{
				$styles = glob( "$d/assets/*.css" );

				foreach( $styles as $s )
				{
					$module_css .= file_get_contents( $s ) . "\r\n\r\n";
				}
			}
		}

		//-- Task styling

		$CI =& get_instance();

		$CI->load->model('tag_model');
		$taglist = $CI->tag_model->getTags();

		foreach( $taglist as $t )
		{
			if( isset($t->color) )
			{
				if( $t->color )
				{
					$module_css .= '.' . $t->group . $t->id . " { background-color: " . $t->color . "; }\r\n			";
				}
			}
		}

		//-- compression

		/*
			Thanks to Manas Tungare for the following source :)
			http://manas.tungare.name/software/css-compression-in-php/
			
			1 - remove comments
			2 - remove spaces after colons
			3 - remove whitespaces
		*/

		if( $minify )
		{
			$module_css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $module_css);
			$module_css = str_replace(': ', ':', $module_css);
			$module_css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $module_css);
		}

		return $module_css;
	}




	/*
		This method concatenate each javascript file we can find in the assets/
		folder of each module.
		
		@return a string containing the module's css
	*/
	public function js()
	{
		header('Content-type: text/javascript');

		$module_js = "";

		$modules_directory = glob( APPPATH.'modules/*', GLOB_ONLYDIR );

		foreach( $modules_directory as $d )
		{
			if( is_dir("$d/assets") && $this->is_active(basename($d)) )
			{
				$javascripts = glob( "$d/assets/*.js" );

				foreach( $javascripts as $j )
				{
					$module_js .= file_get_contents( $j ) . "\r\n\r\n";
				}
			}
		}
		return $module_js;
	}


	/*
		If a module name starts with "_", we consider it as inactive
	*/
	private function is_active( $module_name )
	{
		return ( substr($module_name,0,1) != '_' );
	}

}

