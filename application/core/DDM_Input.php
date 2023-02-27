<?php  if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * My Input
 * 
 * Extends CodeIgniters core Input with a honey pot anti-spam protection
 * Added config items for use.
 *
 * @author  Martin Langenberg
 */

class DDM_Input extends CI_Input {
 
    /**
     * Enable Honey pot flag
     *
     * Enables a honey pot token name to be set.
     * Set automatically based on config setting.
     *
     * @var bool
     */
    protected $_enable_honey_pot = FALSE;
    protected $router;
 
    public function __construct() {
        log_message('debug', "Class ".get_class($this)." Initialized.");
 
        parent::__construct();
 
        $this->_enable_honey_pot = (config_item('spam_protection') === TRUE);
 
        $this->_resanitize_globals();
    }
 
    // --------------------------------------------------------------------
 
    /**
     * Resanitize Globals
     *
     * Internal method serving for the following purposes:
     *
     * - Sets the honey pot and validates on POST request
     *
     * @return void
     */
    protected function _resanitize_globals()
    {
    	global $RTR;
		$this->router =& $RTR;
		
    	$current_path = $this->router->fetch_class() . '/' . $this->router->fetch_method();
		if ( ! $spam_protection_paths = config_item( 'spam_protection_paths' ) )
			return;
		
		if ( ! is_array( $spam_protection_paths ) || ! in_array( $current_path, $spam_protection_paths ) )
			return;
		
        // Honey pot protection check
        if ($this->_enable_honey_pot === TRUE && ! $this->is_cli_request())
        {
            $this->security->honey_pot_verify();
        }
 
        log_message('debug', 'Honey pot set and verified');
    }
 
    // --------------------------------------------------------------------

	/**
	* Fetch the IP Address
	*
	* @return	string
	*/
	public function ip_address()
	{
		if ($this->ip_address !== FALSE)
		{
			return $this->ip_address;
		}

		$this->ip_address = ddm_get_current_ip();

		return $this->ip_address;
	}

	// --------------------------------------------------------------------
 
} 