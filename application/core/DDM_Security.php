<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * My Security
 * 
 * Extends CodeIgniters core Security with a honey pot anti-spam protection
 *
 * @author Martin Langenberg
 */

class DDM_Security extends CI_Security {
 
    protected $_honey_pot_name; // The honey pot cookie name
    protected $_honey_pot_token_name; // The hashed honey pot field name
 
    public function __construct() {
        log_message('debug', "Class ".get_class($this)." Initialized.");
 
        parent::__construct();
 
        $this->_honey_pot_name = config_item('spam_protection_name');
    }
 
    // --------------------------------------------------------------------
 
    /**
     * Honey pot Verify
     *
     * @return MY_Security
     */
    public function honey_pot_verify()
    {
        // If it's not a POST request, set the honey pot and return
        if (strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST') {
            return $this->_spam_protection_set_honey_pot();
        }
 
        // It's a post, get the stored token name to use with the available $_POST data
        $this->_honey_pot_token_name = $_COOKIE[$this->_honey_pot_name];
 
        // Do the tokens exist in the _POST
        if ( ! isset( $_POST[$this->_honey_pot_token_name] )
            OR strlen($_POST[$this->_honey_pot_token_name]) > 0 ) // Is the honey pot empty?
        {
            // Log a clear error, but don't print clear honey pot errors to screen
            log_message('error', 'The honey pot was invalid OR not empty!');
            $this->csrf_show_error();
        }
 
        // We kill this since we're done and we don't want to polute the _POST array
        unset($_POST[$this->_honey_pot_token_name]);
 
        // Nothing should last forever
        unset($_COOKIE[$this->_honey_pot_name]);
        $this->_honey_pot_token_name = NULL;
 
        $this->_spam_protection_set_honey_pot();
 
        log_message('info', 'Honey pot verified');
        return $this;
    }
 
    // --------------------------------------------------------------------
 
    /**
     * Set Honey pot name and value
     *
     * @return string
     */
    protected function _spam_protection_set_honey_pot() {
 
        if ($this->_honey_pot_token_name === NULL) {
            // If the cookie exists we will use its value.
            // We don't necessarily want to regenerate it with
            // each page load since a page could contain embedded
            // sub-pages causing this feature to fail
            if (isset($_COOKIE[$this->_honey_pot_name]) && is_string($_COOKIE[$this->_honey_pot_name])
                && preg_match('#^[0-9a-f]{32}$#iS', $_COOKIE[$this->_honey_pot_name]) === 1)
            {
                return $this->_honey_pot_token_name = $_COOKIE[$this->_honey_pot_name];
            }
 
            $rand = $this->get_random_bytes(16);
            $this->_honey_pot_token_name = ($rand === FALSE)
            ? md5(uniqid(mt_rand(), TRUE))
            : bin2hex($rand);
        }
 
        $this->honey_pot_set_cookie();
 
        return $this->_honey_pot_token_name;
    }
 
    // --------------------------------------------------------------------
 
    /**
     * Honey pot Set Cookie
     * 
     * @return CI_Security
     */
    public function honey_pot_set_cookie() {
 
        $expire = time() + 600;
        $secure_cookie = (bool) config_item('cookie_secure');
 
        setcookie(
            $this->_honey_pot_name,
            $this->_honey_pot_token_name,
            $expire,
            config_item('cookie_path'),
            config_item('cookie_domain'),
            $secure_cookie,
            config_item('cookie_httponly')
        );
        log_message('info', 'Honey Pot cookie sent');
 
        return $this;
     }
 
     // --------------------------------------------------------------------
 
    /**
     * return the set honey pot token name
     * @return string
     */
    public function get_honey_pot_token_name() {
        return $this->_honey_pot_token_name;
    }

	// --------------------------------------------------------------------

	/**
	 * Get random bytes
	 *
	 * @param	int	$length	Output length
	 * @return	string
	 */
	public function get_random_bytes($length)
	{
		if (empty($length) OR ! ctype_digit((string) $length))
		{
			return FALSE;
		}

		// Unfortunately, none of the following PRNGs is guaranteed to exist ...
		if (defined(MCRYPT_DEV_URANDOM) && ($output = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM)) !== FALSE)
		{
			return $output;
		}


		if (is_readable('/dev/urandom') && ($fp = fopen('/dev/urandom', 'rb')) !== FALSE)
		{
			$output = fread($fp, $length);
			fclose($fp);
			if ($output !== FALSE)
			{
				return $output;
			}
		}

		if (function_exists('openssl_random_pseudo_bytes'))
		{
			return openssl_random_pseudo_bytes($length);
		}

		return FALSE;
	}
 
    // --------------------------------------------------------------------
 
}