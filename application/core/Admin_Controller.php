<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Admin_Controller Class
 */
class Admin_Controller extends DDM_Controller {
	
	function __construct() {
		parent::__construct();
		$this->auth( true );
	}

	// --------------------------------------------------------------------
}
