<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->loadTranslation();
	}


	public function index()
	{
		$this->load->helper('secursession');

		if( connected() )
		{
			$this->load->model('configuration/configuration_model');
			$prefs = $this->configuration_model->getPreferences();

			$this->load->library('Module_manager');
			$modules = $this->module_manager->configure();

			/*
				module_manager->configure() call controllers, and controllers
				change the textdomain. So we have to call again loadTranslation().
			*/
			$this->loadTranslation();
			
			$this->load->view('interface', array(
				'preferences' => $prefs,
				'modules' => $modules,
				'lang' => $this->lang,
				'logo' => $this->configuration_model->get('logo')
			));
		}
		else
		{
			$this->load->model('configuration/configuration_model');
			$config = $this->configuration_model->getGeneral();

			$this->load->view( 'connexion', array(
				'register' => ( $config['allow_self_registration']=='1' )
			));
		}
	}



	public function gui()
	{
		$this->load->helper('secursession');
		check_identification();

		//-- Autoload module widgets

		$this->load->library('Module_manager');
		$module_configuration = $this->module_manager->configure();

		foreach( $module_configuration['widget'] as $key => $w )
		{
			echo modules::run( $w['url'] );
		}		
	}
	
	
	
	public function css()
	{
		$this->load->library('Module_manager');
		die( $this->module_manager->css() );
	}
	
	
	
	public function js()
	{
		$this->load->library('Module_manager');
		die( $this->module_manager->js() );
	}



	public function img( $module, $file )
	{
		$image = realpath( APPPATH . 'modules/' . $module . '/assets/' . $file );
		
		if( strpos($image, APPPATH.'modules/') !== 0 )
		{
			die();	// security problem : certainly ../ in the path
		}

		$extension = pathinfo( $image, PATHINFO_EXTENSION );
		
		if( ! in_array($extension, array('gif','png','jpg')) )
		{
			return;
		}
		
		header('Content-Type: image/' . $extension);
		readfile( $image );
	}



	public function about()
	{
		$this->loadTranslation();
		$this->load->view('about');
	}
	
	
		
	public function identification()
	{
		$this->load->helper('secursession');

		/*
			La classe Session est déjà préchargée, mais un chargement traditionnel
			permet de configurer la durée de session.
		
			https://ellislab.com/codeigniter/user-guide/libraries/sessions.html
		
			$config['sess_driver'] = 'files';
			$config['sess_cookie_name'] = 'il_session';
			$config['sess_expiration'] = 0;	// 1H
			$config['sess_save_path'] = NULL;
			$config['sess_match_ip'] = FALSE;
			$config['sess_time_to_update'] = 300;
			$config['sess_regenerate_destroy'] = FALSE;
			
			$config2 = array(
				'sess_driver'					=> 'files',
				'sess_cookie_name'			=> 'il_session',
				'sess_expiration'				=> 10,
				'sess_save_path'				=> null,
				'sess_match_ip'				=> false,
				'sess_time_to_update'		=> 300,
				'sess_regenerate_destroy'	=> false
			);
			
			$this->load->library('session', $config);
		*/

		$email = $this->input->post('email');
		$password = $this->input->post('password');

		$this->load->model('user/user_model');
		$user = $this->user_model->identify( $email, $password );

		if( $user )
		{
			$this->session->set_userdata( 'userid', $user->id );
		}

		if( ! connected() )
		{
			$this->session->unset_userdata('userid');
			$this->session->sess_destroy();
			redirect('/?error=Sorry, you are not allowed');
		}
		else
		{
			redirect('/');
		}
	}


	public function register()
	{
		if( connected() )
		{
			redirect('/');
		}

		//-- self-registration allowed by configuration ?

		$this->load->model('configuration/configuration_model');
		$config = $this->configuration_model->getGeneral();

		if( $config['allow_self_registration'] != 1 )
		{
			die( _('Sorry, you are not allowed to register by yourself !') );
		}


		//-- sending confirmation mail

		$_POST['group'] = 'team';
		$_POST['md5pass'] = $_POST['verif'] = bin2hex( openssl_random_pseudo_bytes(5) );
		$_POST['can_connect'] = '0';
		$_POST['activation_key'] =  bin2hex( openssl_random_pseudo_bytes(25) );
	
		$email = $this->input->post('email');

		$this->load->model('user/user_model');
		$user = $this->user_model->getUser(null, $email);

		if( count($user) )
		{
			die( _('Sorry, you should use another mail address !') );
		}

		$url = site_url() . 'dashboard/activate/' . $_POST['activation_key'];

		$body = '
			<p>' . _('Thank you for registering ! Here are your personal data :') . '</p>
			<ul>
				<li>' . _('Email') . ' : ' . $email . '</li>
				<li>' . _('Password') . ' : ' . $_POST['md5pass'] . '</li>
			</ul>
			<p>' . _('Activate your account and enjoy Alveole at :') . '<br/>
			<a href="' . $url . '">' . $url . '</a></p>
		';

		require APPPATH . 'libraries/PHPMailer-master/PHPMailerAutoload.php';
		
		$mail = new PHPMailer;

		$mail->isSMTP();
		$mail->Host = $config['email_server'];
		$mail->SMTPAuth = true;
		$mail->Username = $config['email_user'];
		$mail->Password = $config['email_password'];
		$mail->SMTPSecure = $config['email_security'];
		$mail->Port = $config['email_port'];
		
		$mail->From = $config['email_user'];
		$mail->FromName = 'Alveole';
		$mail->addAddress( $email );
		$mail->isHTML(true);
		
		$mail->Subject = _('Thanks for registering');
		$mail->Body    = $body;
		$mail->AltBody = strip_tags( $body );

		$mail->setLanguage('fr', 'application/libraries/PHPMailer-master/language/phpmailer.lang-fr.php');		

		if( $mail->send() != '1' )
		{
			$mail->ErrorInfo;
		}

		$save = $this->user_model->save();
		die( $save=='1' ? 'ok' : $save );
	}


	public function activate( $key )
	{
		$this->load->model('user/user_model');
		$this->user_model->activate($key);
		
		redirect('/');
	}


	public function disconnect()
	{
		$this->load->library('session');
		session_destroy();
		redirect('/');
	}

}
