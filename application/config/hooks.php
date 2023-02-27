<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['post_controller_constructor'][] = array(
	'function' => 'switch_lang',
	'filename' => 'post_controller_constructor.php',
	'filepath' => 'hooks',
);

$hook['post_controller_constructor'][] = array(
	'function' => 'load_lang',
	'filename' => 'post_controller_constructor.php',
	'filepath' => 'hooks',
);

/* End of file hooks.php */
/* Location: ./app/config/hooks.php */