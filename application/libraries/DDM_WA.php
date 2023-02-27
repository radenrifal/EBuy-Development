<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * WA (Woonotif) class.
 *
 * @class WhatsApps
 */
class DDM_WA 
{
	var $CI;
    var $url;
    var $token;
    var $active;
    
	/**
	 * Constructor - Sets up the object properties.
	 */
	function __construct()
    {
        // Set Get CI Instance
        $this->CI       =& get_instance();
        // Set Woonotif URL
        $this->url      = trim(config_item('woonotif_url'));
        // Set Woonotif Token
        $this->token    = trim(config_item('woonotif_token'));
        // Set Woonotif Active/Not Active
        $this->active   = config_item('woonotif_active');
	}
    
    /**
	 * Send WA function.
	 *
     * @param string    $to         (Required)  To WA destination
     * @param string    $message    (Required)  Message of WA
	 * @return Mixed
	 */
	function send_wa($to, $message){
		if ( !$this->active ) return false;

        $data = array(
            'phone_no'  => $to, 
            'key'       => $this->token, 
            'message'   => $message
        );
        $data_string = json_encode($data);
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
            )
        );
        $result = curl_exec($ch);
        return $result;
	}
    
    /**
     * Send WA to New Member function.
     *
     * @param   Object  $member     (Required)  Member Data of Downline
     * @param   Object  $sponsor    (Required)  Member Data of Sponsor
     * @param   Object  $password   (Required)  Password of Downline
     * @return  Mixed
     */
    function send_wa_new_member($member, $sponsor, $password, $view = false){
        if ( !$member ) return false;
        if ( !$sponsor ) return false;
        if ( !$password ) return false;
        if ( empty($member->phone) ) return false;
        
        $to     = $member->phone;
        $pos    = strpos( $to, '0' );
        if ($pos !== false) {
            $to = substr_replace( $to, '+62', $pos, strlen( '0' ) );
        }
        
        $login_url  = base_url('login');
        $message    = trim(get_option('sms_format_new_member'));

        if( !$message ) return false;

        $message    = str_replace("%username%",     $member->username, $message);
        $message    = str_replace("%name%",         $member->name, $message);
        $message    = str_replace("%password%",     $password, $message);
        $message    = str_replace("%login_url%",    $login_url, $message);

        if ( $view ) {
            return $message;
        }

        return $this->send_wa($to, $message);
    }
}