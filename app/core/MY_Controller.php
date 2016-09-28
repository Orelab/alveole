<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends MX_Controller
{

	private static $exceptions = array(
		'__construct',
		'init',
		'get_instance',
		'loadModuleTranslation',
		'index',
		'dashboard',
		'menu',
		'save',
		'delete',
		'get',
		'__get',
		'progress'
	);



	public function __construct()
	{
		parent::__construct();


		//-- Debug mode

		$this->output->enable_profiler( $this->config->item('show_profiler') );


		//-- Current User

		$this->load->model('user/user_model');
		$this->user = $this->user_model->currentUser();


		//-- Current user preferences

		$this->load->model('configuration/configuration_model');

		if( $this->user )
		{
			$this->preferences = $this->configuration_model->getPreferences();

			$this->lang = $GLOBALS['lang'] = $this->preferences->language;
		}
		else
		{
			$this->lang = $this->configuration_model->get('default_language');
		}
	}


	/*
		Call the general translation file
	*/
	public function loadTranslation()
	{
		putenv('LANG=' . $this->lang);
		setlocale(LC_ALL, $this->lang);
		bindtextdomain($this->lang, APPPATH . 'language');
		textdomain($this->lang);
		bind_textdomain_codeset($this->lang, 'UTF-8');
	}


	/*
		Call a module translation file
		
		This method can be called by instance, or statically.
		A static call is usefull during the interface construction, as
		Module_manager call the init() functions of each controllers.
	*/
	public static function loadModuleTranslation( $module_name )
	{
		global $lang;
		$domain = $lang ?: 'en_EN.utf8';

		$language_path = APPPATH . 'modules/' . $module_name . '/language';

		if( ! is_dir($language_path) )
		{
			return false;
		}

		putenv('LANG=' . $domain);
		setlocale(LC_ALL, $domain);

		bindtextdomain($domain, $language_path);
		textdomain($domain);
		bind_textdomain_codeset($domain, 'UTF-8');
	}





	public static function menu( $id, $title=null )
	{
		$CI =& get_instance();
		$controller = $CI->router->fetch_class();

	//	$controller = strtolower( get_called_class() );	// Late Static Binding - PHP >=5.3
		$methods = get_class_methods( $controller );

		$html = '';

		foreach( $methods as $method )
		{
			if( ! in_array($method, self::$exceptions) )
			{
				//$lib = trad( 'aside_' . $controller . '_' . $method );	// previous translation method (with CI system)
				$lib = 'aside_' . $controller . '_' . $method;
				$html .= '<p><a href="' . $controller . '/' . $method . '/' . $id . '">' . $lib . '</a></p>';
			}
		}
		
		echo '<h1>' . $title . '</h1><nav class="submenu">' . $html . '</nav>';
	}
	

}




