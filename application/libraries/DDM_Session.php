<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Session Class
 *
 * @subpackage	Libraries
 */
class DDM_Session extends CI_Session {
	
	var $compatible = TRUE;
	var $db;
    
    /**
     * Session Constructor
     *
     * The constructor runs the session routines automatically
     * whenever the class is instantiated.
     */
    public function __construct($params = array())
    {
        $this->CI =& get_instance();
        $this->db = $this->CI->db;
        parent::__construct($params);
    }

	// --------------------------------------------------------------------
	
	/**
     * Fetch the current session data if it exists
     *
     * @access  public
     * @return  bool
     */
    function sess_read()
    {
        $db = $this->CI->db;
        $this->CI->db = $this->db;
		
		$sess_read = parent::sess_read();
        
        $this->CI->db = $db;
        return $sess_read;
    }

    // --------------------------------------------------------------------
	
	/**
     * Write the session data
     *
     * @access  public
     * @return  void
     */
    function sess_write()
    {
        $db = $this->CI->db;
        $this->CI->db = $this->db;
		
        parent::sess_write();
        
        $this->CI->db = $db;
    }

    // --------------------------------------------------------------------
    
    /**
     * Create a new session
	 * 
	 * This function is override from parent function
	 * It adds recovery function to prevent EMPTY COOKIE error on order process
	 * 
	 * @since 1.1.4
     * @access  public
	 * 
     * @return  void
     */
    function sess_create()
    {
        $db = $this->CI->db;
        $this->CI->db = $this->db;
		
		parent::sess_create();
        
        $this->CI->db = $db;
    }
    
    // --------------------------------------------------------------------

    /**
     * Update an existing session
     *
     * @access  public
     * @return  void
     */
    function sess_update()
    {
        $db = $this->CI->db;
        $this->CI->db = $this->db;
		
		parent::sess_update();
        
        $this->CI->db = $db;
    }

    // --------------------------------------------------------------------
    
    /**
     * Destroy the current session
     *
     * @access  public
     * @return  void
     */
    function sess_destroy()
    {
        $db = $this->CI->db;
        $this->CI->db = $this->db;
		
        parent::sess_destroy();
        
        $this->CI->db = $db;
    }

    // --------------------------------------------------------------------
    
    /**
     * Garbage collection
     *
     * This deletes expired session rows from database
     * if the probability percentage is met
     *
     * @access  public
     * @return  void
     */
    function _sess_gc()
    {
        $db = $this->CI->db;
        $this->CI->db = $this->db;
        
        parent::_sess_gc();
        
        $this->CI->db = $db;
    }

    // --------------------------------------------------------------------

	/**
	 * Write the session cookie
	 *
	 * @access	public
	 * @return	void
	 */
	function _set_cookie($cookie_data = NULL)
	{
		global $IN;
		$this->input =& $IN;
		
		if ( $this->input->is_cli_request() )
			return false;
		
		if (is_null($cookie_data))
		{
			$cookie_data = $this->userdata;
		}

		// Serialize the userdata for the cookie
		$cookie_data = $this->_serialize($cookie_data);

		if ($this->sess_encrypt_cookie == TRUE)
		{
			$cookie_data = $this->CI->encrypt->encode($cookie_data);
		}

		$cookie_data .= hash_hmac('sha1', $cookie_data, $this->encryption_key);

		$expire = ($this->sess_expire_on_close === TRUE) ? 0 : $this->sess_expiration + time();

		// Set the cookie
		setcookie(
			$this->sess_cookie_name,
			$cookie_data,
			$expire,
			$this->cookie_path,
			$this->cookie_domain,
			$this->cookie_secure
		);
	}

}
// END Session Class
