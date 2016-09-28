<?php


require('configuration.php');

define('ENVIRONMENT', 'development');

error_reporting(-1);
ini_set('display_errors', 1);

define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('FCPATH', dirname(__FILE__).'/');

define('APPPATH', $alveole_cfg['app_folder'] );
define('BASEPATH', $alveole_cfg['app_folder'] . 'system/' );
define('VIEWPATH', $alveole_cfg['app_folder'] . 'views/' );


$assign_to_config = $alveole_cfg;

require_once BASEPATH.'core/CodeIgniter.php';



