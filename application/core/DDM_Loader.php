<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Loader Class
 *
 * Loads views and files
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @author		ExpressionEngine Dev Team
 * @category	Loader
 * @link		http://codeigniter.com/user_guide/libraries/loader.html
 */
class DDM_Loader extends CI_Loader {

	/**
	 * Database Loader
	 *
	 * @param	string	the DB credentials
	 * @param	bool	whether to return the DB object
	 * @param	bool	whether to enable active record (this allows us to override the config setting)
	 * @return	object
	 */
	public function database($params = '', $return = FALSE, $active_record = NULL)
	{
		// Grab the super object
		$CI =& get_instance();
		
		//------------------------------------------------
		// CUSTOM CODE: load DB master (write)
		//------------------------------------------------

		// Do we even need to load the database class?
		if (class_exists('CI_DB') AND $return == FALSE AND $active_record == NULL AND isset($CI->db) AND is_object($CI->db))
		{
			return FALSE;
		}

		require_once(BASEPATH.'database/DB.php');
		
		// Load the DB class
		$db =& DB($params, $active_record);
		
		$my_driver = config_item('subclass_prefix') . 'DB_' . $db->dbdriver . '_driver';
        $my_driver_file = APPPATH . 'libraries/' . $my_driver . '.php';

        if ( file_exists( $my_driver_file ) )
        {
            require_once( $my_driver_file );
            $db_obj = new $my_driver( get_object_vars( $db ) );
            $db =& $db_obj;
        }

		if ( $return === TRUE )
		{
			return $db;
		}

		// Initialize the db variable.  Needed to prevent
		// reference errors with some configurations
		$CI->db = $db;
	}

	// --------------------------------------------------------------------
}

/* End of file Loader.php */
/* Location: ./system/core/Loader.php */