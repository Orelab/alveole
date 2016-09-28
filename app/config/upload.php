<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File Uploading
|--------------------------------------------------------------------------
|
| Section ajoutée le 26 juin 2015 pour gérer l'upload de fichiers.
| http://www.codeigniter.com/user_guide/libraries/file_uploading.html?highlight=upload
|
*/

$config['upload_path']  = APPPATH . 'files/';

//$config['allowed_types'] =  'gif|jpg|png|jpeg|bmp|csv|pdf|xml|json|zip|rar|gz|txt|rtf|odt|ods|doc|docx|docm|xls|xlsx|xlsm';
								// ODT = application/vnd.oasis.opendocument.text

//$config['allowed_types'] =  'application/*|image/*|text/plain';

$config['allowed_types'] =  '*';

$config['overwrite'] = false;

$config['max_size'] = 204800;		//200 MO

//$config['max_width'] = 8192;

//$config['max_height'] = 6144;




