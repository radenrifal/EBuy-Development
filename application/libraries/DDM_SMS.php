<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * SMS Class
 *
 * @subpackage	Libraries
 */
class DDM_SMS 
{
	var $CI;
    var $username;
    var $password;
    var $url;
    var $type;
	var $active;
	var $masking;
	var $masking_user;
	var $masking_pass;
	var $masking_send_url;
	var $masking_rpt_url;
    
	/**
	 * Constructor - Sets up the object properties.
	 */
	function __construct()
    {
        // Set Get CI Instance
        $this->CI       =& get_instance();
        // Set Username
        $this->username = trim(config_item('sms_userkey'));
        // Set Password
        $this->password = trim(config_item('sms_passkey'));
        // Set SMS URL
        $this->url      = trim(config_item('sms_url'));
        // Set SMS Type
        $this->type     = trim(config_item('sms_type'));
		// Set if service is active
		$this->active	= config_item('sms_active');
		// Masking config
		$this->masking			= config_item( 'sms_masking_active' );
		$this->masking_user 	= config_item( 'sms_masking_user' );
		$this->masking_pass		= config_item( 'sms_masking_pass' );
		$this->masking_send_url	= config_item( 'sms_masking_send_url' );
		$this->masking_rpt_url	= config_item( 'sms_masking_rpt_url' );
	}
    
    /**
	 * Send SMS function.
	 *
     * @param string    $to         (Required)  To SMS destination
     * @param string    $message    (Required)  Message of SMS
	 * @return Mixed
	 */
	function send_sms($to, $message){
		if ( $this->masking ) return $this->send_sms_masking($to, $message);
		if ( !$this->active ) return false;
        if ( !$to ) return false;
        
        $api_url = $this->url . '?' . http_build_query( array(
			'userkey' 	=> $this->username,
			'passkey' 	=> $this->password,
			'nohp' 		=> $to,
            'tipe'      => $this->type,
            'pesan'     => $message,
		));
        
        $this->CI->load->helper('curl_helper');
		$report = dha_curl_get($api_url);
        
        // Log report
        return $report;
	}
	
	/**
	 * Semd SMS Masking
     * 
     * @param string    $to         (Required)  To SMS destination
     * @param string    $message    (Required)  Message of SMS
	 * @return Mixed
	 */
	function send_sms_masking($to, $message) {
		if (empty($to) || empty($message)) return false;
		
		// Trim char +
		$to = ltrim($to, '+');
		
		// Replace zero to Indonesian country code 62
		$pos = strpos($to, '0');
		if ($pos !== false) {
		    $to = substr_replace($to, '62', $pos, strlen('0'));
		}
		
		$api_url = $this->masking_send_url . '?' . http_build_query( array(
			'username' 	=> $this->masking_user,
			'password' 	=> $this->masking_pass,
			'hp' 		=> $to,
			'message' 	=> $message
		));
		
		$this->CI->load->helper('curl_helper');
		$report = dha_curl_get($api_url);
		
		// Log report
		return $report;
	}
	
	/**
	 * Get Status SMS Masking
     * 
     * @param string    $report_number      (Required)  Report Number of SMS
	 * @return Mixed
	 */
	function get_status_masking($report_number) {
		if (empty($report_number)) return false;
		
		$this->CI->load->helper('curl_helper');
		return dha_curl_get($this->masking_rpt_url . '?rpt=' . $report_number);
	}
    
    /**
	 * Send SMS New Member function.
	 *
     * @param string    $to         (Required)  SMS Destination
     * @param string    $password   (Required)  Password of New Member
     * @param string    $memberuid  (Required)  ID Member of New Member
	 * @return Mixed
	 */
    function sms_new_member($to, $username, $password, $view = false){
        if ( !$to ) return false;
        if ( !$username ) return false;
        if ( !$password ) return false;

        $login_url  = base_url('login');
        
        $message    = trim(get_option('sms_format_new_member'));
        $message    = str_replace("%username%",     $username, $message);
        $message    = str_replace("%password%",     $password, $message);
        $message    = str_replace("%login_url%",    $login_url, $message);

        if ( $view ) {
            return $message;
        } else {
            $send   = $this->send_sms($to, $message);
            return true;
        }
    }
}