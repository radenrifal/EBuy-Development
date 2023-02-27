<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Public_Controller Class
 * 
 * May be used as the parent of order controllers 
 * or any front end public pages controllers
 */
class CLI_Controller extends DDM_Controller {
	
	function __construct() {
		parent::__construct();
		
		if ( ! $this->input->is_cli_request() )
			die( 'Access Denied! No direct script access allowed!' );
	}

	// --------------------------------------------------------------------
}
