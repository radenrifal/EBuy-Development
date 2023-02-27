<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Global error variable
 */
$error_msg = array();

/**
 * Converts value to nonnegative integer.
 *
 * @param mixed $maybeint Data you wish to have convered to an nonnegative integer
 * @return int An nonnegative integer
 */
if ( !function_exists('absint') )
{
    function absint( $number )
    {
        return abs( intval( $number ) );
    }
}

/**
 * Serialize data, if needed.
 *
 * @param mixed $data Data that might be serialized.
 * @return mixed A scalar data
 */
if ( !function_exists('maybe_serialize') )
{
    function maybe_serialize( $data )
    {
        if ( is_array( $data ) || is_object( $data ) )
            return serialize( $data );

        if ( is_serialized( $data ) )
            return serialize( $data );

        return $data;
    }
}

/**
 * Unserialize value only if it was serialized.
 *
 *
 * @param string $original Maybe unserialized original, if is needed.
 * @return mixed Unserialized data can be any type.
 */
if ( !function_exists('maybe_unserialize') )
{
    function maybe_unserialize( $original )
    {
        if ( is_serialized( $original ) ) // don't attempt to unserialize data that wasn't serialized going in
            return @unserialize( $original );
        return $original;
    }
}

/**
 * Check value to find if it was serialized.
 *
 * If $data is not an string, then returned value will always be false.
 * Serialized data is always a string.
 *
 *
 * @param mixed $data Value to check to see if was serialized.
 * @return bool False if not serialized and true if it was.
 */
if ( !function_exists('is_serialized') )
{
    function is_serialized( $data )
    {
        // if it isn't a string, it isn't serialized
        if ( ! is_string( $data ) )
            return false;
        $data = trim( $data );
        if ( 'N;' == $data )
            return true;
        $length = strlen( $data );
        if ( $length < 4 )
            return false;
        if ( ':' !== $data[1] )
            return false;
        $lastc = $data[$length-1];
        if ( ';' !== $lastc && '}' !== $lastc )
            return false;
        $token = $data[0];
        switch ( $token ) {
            case 's' :
                if ( '"' !== $data[$length-2] )
                        return false;
            case 'a' :
            case 'O' :
                return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
            case 'b' :
            case 'i' :
            case 'd' :
                return (bool) preg_match( "/^{$token}:[0-9.E-]+;\$/", $data );
        }
        return false;
    }
}

/**
 * Check value to find if it was json.
 *
 * If $data is not an string, then returned value will always be false.
 * Json data is always a string.
 *
 *
 * @param mixed $data Value to check to see if was json.
 * @return bool False if not serialized and true if it was.
 */
if ( !function_exists('is_json') )
{
    function is_json( $string, $return_data = false ) 
    {
        // if it isn't a string, it isn't serialized
        if ( ! is_string( $string ) )
            return false;

        $string = trim( $string );
        $data   = json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE) ? ($return_data ? $data : TRUE) : FALSE;
    }
}

/**
 * Check whether variable exist or not
 *
 * If current variable does not exist, return false then set default return value
 *
 * @param variable name, default value, default on set variable but empty
 * @return value if variable is set, false, or specified default value
 */
if ( !function_exists('ddm_isset') )
{
    function ddm_isset( &$val, $default=NULL, $default_on_empty=false )
    {
        if( isset($val) )
            $tmp = ($default_on_empty && empty($val) ? $default : $val);
        else
            $tmp = $default;
        return $tmp;
    }
}

/**
 * get date of datetime.
 * @param variable date, default value, default on set variable but empty
 * @return string of date, false if date param is empty.
 */
if ( !function_exists('get_date') )
{
    function get_date($date='')
    {
        if(!$date) return false;

        $day    = date('d', strtotime($date));
        $month  = date('M', strtotime($date));
        $year   = date('y', strtotime($date));

        return '<span class="h2">' . $day . '</span><span>' . $month . ' ' . $year . '</span>';
    }
}

/**
 * get time of datetime.
 * @param variable date, default value, default on set variable but empty
 * @return string of time, false if date param is empty.
 */
if ( !function_exists('get_time') )
{
    function get_time($date='')
    {
        if(!$date) return false;

        $time   = date('h:i', strtotime($date));
        $format = date('A', strtotime($date));

        return $time . ' ' . $format;
    }
}

/**
 * Add a new option.
 *
 * You do not need to serialize values. If the value needs to be serialized, then
 * it will be serialized before it is inserted into the database. Remember,
 * resources can not be serialized or added as an option.
 *
 * You can create options without values and then add values later. Does not
 * check whether the option has already been added, but does check that you
 * aren't adding a protected WordPress option. Care should be taken to not name
 * options the same as the ones which are protected and to not add options
 * that were already added.
 *
 * @param string $option Name of option to add. Expected to not be SQL-escaped.
 * @param mixed $value Optional. Option value, can be anything. Expected to not be SQL-escaped.
 * @param mixed $deprecated Optional. Description. Not used anymore.
 * @param bool $autoload Optional. Default is enabled. Whether to load the option when WordPress starts up.
 * @return null returns when finished.
 */
if ( !function_exists('add_option') )
{
    function add_option($option, $value = '')
    {
        $CI =& get_instance();

        $option = trim($option);
        if ( empty($option) ) return false;

        $value  = maybe_serialize( $value );

        $data   = array(
            'name'  => $option,
            'value' => $value,
        );

        $result = $CI->Model_Option->add_option($data);

        if ( $result ) return true;

        return false;
    }
}

/**
 * Retrieve option value based on name of option.
 *
 * If the option does not exist or does not have a value, then the return value
 * will be false. This is useful to check whether you need to install an option
 * and is commonly used during installation of plugin options and to test
 * whether upgrading is required.
 *
 * If the option was serialized then it will be unserialized when it is returned.
 *
 *
 * @param string $option Name of option to retrieve. Expected to not be SQL-escaped.
 * @return mixed Value set for the option.
 */
if ( !function_exists('get_option') )
{
    function get_option( $option, $default = false ) {
        $CI     =& get_instance();
        $option = trim($option);

        if ( empty($option) ) return false;
        $value  = array();

        $CI->db->select('value');
        $CI->db->where('name', $option);
        $CI->db->limit(1);

        $query  = $CI->db->get(TBL_OPTIONS);
        $row    = $query->row();

        if ( is_object( $row ) ) {
            $value[$option] = $row->value;
        }

        return maybe_unserialize( ddm_isset($value[$option], '') );
    }
}

/**
 * Update the value of an option that was already added.
 *
 * You do not need to serialize values. If the value needs to be serialized, then
 * it will be serialized before it is inserted into the database. Remember,
 * resources can not be serialized or added as an option.
 *
 * If the option does not exist, then the option will be added with the option
 * value, but you will not be able to set whether it is autoloaded. If you want
 * to set whether an option is autoloaded, then you need to use the add_option().
 *
 * @param string $option Option name. Expected to not be SQL-escaped.
 * @param mixed $newvalue Option value. Expected to not be SQL-escaped.
 * @return bool False if value was not updated and true if value was updated.
 */
if ( !function_exists('update_option') )
{
    function update_option( $option, $newvalue )
    {
        $CI =& get_instance();

        $option = trim($option);
        if ( empty($option) ) return false;

        if ( is_object($newvalue) )

        // if ( false == $oldvalue )
        //     return add_option( $option, $newvalue );

        $_newvalue  = $newvalue;
        $newvalue   = maybe_serialize( $newvalue );

        $data       = array('value' => $newvalue);
        $CI->db->where('name', $option);

        $result     = $CI->db->update(TBL_OPTIONS, $data);

        if ( $result )
            return true;

        return false;
    }
}

/**
 * Retrieve last total rows found in database
 * Useful when we use limit database function
 *
 * Ref: http://stackoverflow.com/questions/2439829/how-to-count-all-rows-when-using-select-with-limit-in-mysql-query
 *
 * Please be careful when calls this function, make sure that the query executed before is the right one.
 *
 * @author  Yuda
 * @return  Integer
 */
if ( !function_exists('ddm_get_last_found_rows') )
{
    function ddm_get_last_found_rows(){
        $CI =& get_instance();

        $total_row  = 0;
        $query      = $CI->db->query('SELECT FOUND_ROWS() AS total_rows');

        if($query && $query->num_rows())
            $total_row = $query->row()->total_rows;

        return $total_row;
    }
}

/**
 * Generate randon string
 * @author  Yuda
 * @param   Int     $length     Random String Length
 * @param   String  $type       Random String Type
 * @return  String
 */
if ( !function_exists('ddm_generate_rand_string') )
{
    function ddm_generate_rand_string($length = 0, $type='') {
        if( $type == 'num' ){
            $characters = '0123456789';
        }elseif( $type == 'char' ){
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }else{
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }

        $randomString   = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}

/**
 * Generate Key number
 * @author  Yuda
 * @return  String
 */
if ( !function_exists('ddm_generate_key') )
{
    function ddm_generate_key(){
        $CI =& get_instance();
        $CI->load->config('rest');

        do {
            // Generate a random salt
            $salt = base_convert(bin2hex($CI->security->get_random_bytes(64)), 16, 36);

            // If an error occurred, then fall back to the previous method
            if ($salt === FALSE)
            {
                $salt = hash('sha256', time() . mt_rand());
            }
            $new_key = substr($salt, 0, config_item('rest_key_length'));
        } while (ddm_generate_key_exists($new_key));

        return $new_key;
    }
}

if ( !function_exists('ddm_generate_key_exists') )
{
    function ddm_generate_key_exists($key){
        if ( !$key ) return false;

        $CI =& get_instance();
        $CI->load->config('rest');
        return $CI->db->where(config_item('rest_key_column'), $key)->count_all_results(config_item('rest_keys_table')) > 0;
    }
}

if ( !function_exists('ddm_generate_key_insert') )
{
    function ddm_generate_key_insert($key, $data){
        if ( !$key ) return false;
        if ( !$data ) return false;

        $CI =& get_instance();
        $CI->load->config('rest');

        $data[config_item('rest_key_column')] = $key;
        $data['datecreated']    = date('Y-m-d H:i:s');
        $data['datemodified']   = date('Y-m-d H:i:s');

        return $CI->db->set($data)->insert(config_item('rest_keys_table'));
    }
}

if ( !function_exists('ddm_member_api_key') )
{
    function ddm_member_api_key($id_member){
        if ( !$id_member ) return false;

        $CI =& get_instance();
        $CI->load->config('rest');
        
        $query = $CI->db->get_where(config_item('rest_keys_table'), array('id_member' => $id_member));
        if ( !$query->num_rows() )
            return false;

        return $query->row();
    }
}

if ( !function_exists('ddm_member_api_key_valid') )
{
    function ddm_member_api_key_valid($key){
        if ( !$key ) return false;

        $CI =& get_instance();
        $CI->load->config('rest');

        $query = $CI->db->get_where(config_item('rest_keys_table'), array(config_item('rest_key_column') => $key));
        if ( !$query->num_rows() )
            return false;

        $data = $query->row();
        if ( !$data )
            return false;

        $data->member = $CI->Model_Auth->get_userdata($data->id_member);
        return $data;
    }
}

/**
 * Generate unique number
 * @author  Yuda
 * @return  String
 */
if ( !function_exists('ddm_generate_unique') )
{
    function ddm_generate_unique(){
        $CI =& get_instance();

        $sql = 'SELECT value FROM ' . TBL_OPTIONS . ' WHERE name LIKE "unique_number" FOR UPDATE';
        $qry = $CI->db->query($sql);
        $row = $qry->row();

        $number = intval($row->value);
        $unique_number = str_pad($number + 1, 3, '0', STR_PAD_LEFT);

        if($unique_number == 999) {
            $sql_update = 'UPDATE ' . TBL_OPTIONS . ' SET value = 0 WHERE name LIKE "unique_number"';
        } else {
            $sql_update = 'UPDATE ' . TBL_OPTIONS . ' SET value = value + 1 WHERE name LIKE "unique_number"';
        }

        $CI->db->query($sql_update);
        return $unique_number;
    }
}

if ( !function_exists('ddm_generate_invoice') )
{
    function ddm_generate_invoice(){
        $CI =& get_instance();

        $sql = 'SELECT value FROM ' . TBL_OPTIONS . ' WHERE name LIKE "unique_number_invoice" FOR UPDATE';
        $qry = $CI->db->query($sql);
        $row = $qry->row();

        $number         = intval($row->value);
        $unique_number  = str_pad($number + 1, 8, '0', STR_PAD_LEFT);

        if($unique_number == 99999999) {
            $sql_update = 'UPDATE ' . TBL_OPTIONS . ' SET value = 0 WHERE name LIKE "unique_number_invoice"';
        } else {
            $sql_update = 'UPDATE ' . TBL_OPTIONS . ' SET value = value + 1 WHERE name LIKE "unique_number_invoice"';
        }

        $CI->db->query($sql_update);
        return $unique_number;
    }
}

if ( !function_exists('ddm_generate_receipt') )
{
    function ddm_generate_receipt(){
        $CI =& get_instance();

        $sql = 'SELECT value FROM ' . TBL_OPTIONS . ' WHERE name LIKE "unique_number_receipt" FOR UPDATE';
        $qry = $CI->db->query($sql);
        $row = $qry->row();

        $number         = intval($row->value);
        $unique_number  = str_pad($number + 1, 8, '0', STR_PAD_LEFT);

        if($unique_number == 99999999) {
            $sql_update = 'UPDATE ' . TBL_OPTIONS . ' SET value = 0 WHERE name LIKE "unique_number_receipt"';
        } else {
            $sql_update = 'UPDATE ' . TBL_OPTIONS . ' SET value = value + 1 WHERE name LIKE "unique_number_receipt"';
        }

        $CI->db->query($sql_update);
        return $unique_number;
    }
}

if ( !function_exists('ddm_generate_shop_order') )
{
    function ddm_generate_shop_order(){
        $CI =& get_instance();

        $sql = 'SELECT value FROM ' . TBL_OPTIONS . ' WHERE name LIKE "unique_number_shop_order" FOR UPDATE';
        $qry = $CI->db->query($sql);
        $row = $qry->row();

        $number         = intval($row->value);
        $unique_number  = str_pad($number + 1, 3, '0', STR_PAD_LEFT);

        if($unique_number == 999) {
            $sql_update = 'UPDATE ' . TBL_OPTIONS . ' SET value = 0 WHERE name LIKE "unique_number_shop_order"';
        } else {
            $sql_update = 'UPDATE ' . TBL_OPTIONS . ' SET value = value + 1 WHERE name LIKE "unique_number_shop_order"';
        }

        $CI->db->query($sql_update);
        return $unique_number;
    }
}

/**
 * Write any event to log table.
 *
 * @author Yuda
 * @param  string  $log_name        Log name
 * @param  string  $log_status      Log status
 * @param  string  $log_desc        Log description
 * @return void
 */
if ( !function_exists('ddm_log') )
{
    function ddm_log($log_name='',$log_status='',$log_desc='')
    {
        $log_name = trim($log_name);
        if (empty($log_name))
        {
            return false;
        }

        $time  = date('Y-m-d H:i:s');
        $ci    =& get_instance();

        $param = array($log_name, $time, $log_status, $log_desc);

        $ci->db->query(
            "INSERT INTO " . TBL_LOG . "(log_name,log_time,log_status,log_desc)" .
            "VALUES(?, ?, ?, ?)", $param
        );

        return true;
    }
}

/**
 * Write any event to log table.
 *
 * @author Yuda
 * @param  string  $log_name        Log name
 * @param  string  $log_status      Log status
 * @param  string  $log_desc        Log description
 * @return void
 */
if ( !function_exists('ddm_log_cron') )
{
    function ddm_log_cron($cron_name='',$status='',$log_desc='')
    {
        $cron_name = trim($cron_name);
        if (empty($cron_name))
        {
            return false;
        }

        $date  = date('Y-m-d');
        $time  = date('Y-m-d H:i:s');
        $CI    =& get_instance();


        if ( strtoupper($status) == 'STARTED' ) {
            $param = array($cron_name, $status, $time, $log_desc);
            $CI->db->query(
                "INSERT INTO " . TBL_LOG_CRON . "(cron_name,status,start_time,log_desc)" .
                "VALUES(?, ?, ?, ?)", $param
            );
        }

        if ( strtoupper($status) == 'ENDED' ) {
            $query = $CI->db->query('SELECT * FROM ' . TBL_LOG_CRON . ' WHERE cron_name = ? AND DATE(start_time) = ? ORDER BY id DESC LIMIT 1', array($cron_name, $date));
            if($query && $query->num_rows()) {
                $param = array('SUCCESS', $time, $log_desc, $query->row()->id);
                $CI->db->query(
                    "UPDATE " . TBL_LOG_CRON . " SET status = ?, end_time = ?, log_desc = ? " .
                    "WHERE id = ? ", $param
                );
            }
        }

        return true;
    }
}

/**
 * Write any event to log table.
 *
 * @author Yuda
 * @param  string  $log_name        Log name
 * @param  string  $log_status      Log status
 * @param  string  $log_desc        Log description
 * @return void
 */
if ( !function_exists('ddm_log_action') )
{
    function ddm_log_action($log_name='', $log_status='', $username='', $log_desc='')
    {
        $log_name = trim($log_name);
        if ( empty($log_name) ) return false;

        $CI =& get_instance();

        $CI->load->library('user_agent');
        if ($CI->agent->is_browser()) {
            $agent = $CI->agent->browser().' '.$CI->agent->version();
        }elseif ($CI->agent->is_robot()){
            $agent = $CI->agent->robot();
        }elseif ($CI->agent->is_mobile()){
            $agent = $CI->agent->mobile();
        }else{
            $agent = 'Unidentified User Agent';
        }

        $user_agent = array('agent' => $agent, 'agent_string' => $CI->agent->agent_string());
        $log_desc   = $log_desc ? $log_desc : $_COOKIE;

        // Set Data Log Action
        $data               = array(
            'name'          => strtoupper($log_name),
            'status'        => strtoupper($log_status),
            'username'      => strtolower($username),
            'ip'            => ddm_get_current_ip(),
            'user_agent'    => json_encode($user_agent),
            'platform'      => $CI->agent->platform(),
            'assum'         => ddm_is_assuming(),
            'desc'          => $log_desc,
            'assum_staff'   => $CI->session->userdata( 'assuming_as_staff' ),
            'datetime'      => date('Y-m-d H:i:s'),
        );

        $CI->db->insert(TBL_LOG_ACTIONS, $data);
        return true;
    }
}

/**
 * Write any event to log table.
 *
 * @author Yuda
 * @param  string  $log_name        Log name
 * @param  string  $log_status      Log status
 * @param  string  $log_desc		Log description
 * @return void
 */
if ( !function_exists('ddm_log_notif') )
{
    function ddm_log_notif($category='',$title='',$send='',$message='',$status='')
    {
        $category = trim($category);
        if (empty($category)) { return false; }
        $title = trim($title);
        if (empty($title)) { return false; }
    	$send = trim($send);
    	if (empty($send)) { return false; }

        $time               = date('Y-m-d H:i:s');
        $current_member     = ddm_get_current_member();
        $user_id            = '';
        if ( $current_member ) {
            $user_id        = $current_member->id;
        }
    	$ci                =& get_instance();

        $param = array($category, $title, $send, $message, $status, $time, $user_id);

    	$ci->db->query(
    		"INSERT INTO " . TBL_LOG_NOTIF. "(category,title,send,message,status,`date`,user_id)" .
    		"VALUES(?, ?, ?, ?, ?, ?, ?)", $param
    	);

    	return true;
    }
}

/**
 * Sanitises various option values based on the nature of the option.
 *
 * This is basically a switch statement which will pass $value through a number
 * of functions depending on the $option.
 *
 * @param string $option The name of the option.
 * @param string $value The unsanitised value.
 * @return string Sanitized value.
 */
if ( !function_exists('sanitize_option') )
{
    function sanitize_option($option='', $value='')
    {
    	$option = trim($option);

    	if (empty($option))
    	{
    		return '';
    	}

    	if (is_string($value))
    	{
    		$value = trim($value);
    	}

    	switch ($option)
    	{
    		case 'admin_email':
    			$value = sanitize_email($value);

    			if (!is_email($value))
    			{
    				// Resets option to stored value in the case of failed sanitization
    				$value = get_option($option);
    			}

    			break;

    		case 'new_admin_email':
    			$value = sanitize_email($value);

    			if (!is_email($value))
    			{
    				// Resets option to stored value in the case of failed sanitization
    				$value = get_option($option);
    			}

    			break;

    		case 'thumbnail_size_w':
    		case 'thumbnail_size_h':
    		case 'medium_size_w':
    		case 'medium_size_h':
    		case 'large_size_w':
    		case 'large_size_h':
    		case 'mailserver_port':
    		case 'users_can_register':
    		case 'start_of_week':
    			$value = absint($value);
    			break;

    		case 'date_format':
    		case 'time_format':
    		case 'mailserver_url':
    		case 'mailserver_login':
    		case 'mailserver_pass':
    		case 'upload_path':
    			$value = strip_tags($value);
    			$value = addslashes($value);
    			// calls stripslashes then addslashes
    			$value = stripslashes($value);
    			break;

    		case 'gmt_offset':
    			// strips slashes
    			$value = preg_replace('/[^0-9:.-]/', '', $value);
    			break;

    		case 'timezone_string':
    			$allowed_zones = timezone_identifiers_list();

    			if (! in_array($value, $allowed_zones) && !empty($value))
    			{
    				// Resets option to stored value in the case of failed sanitization
    				$value = get_option( $option );
    			}

    			break;

    		default :
    			$value = $value;
    			break;
    	}

    	return $value;
    }
}

/**
 * Strips out all characters that are not allowable in an email.
 *
 * @param string $email Email address to filter.
 * @return string Filtered email address.
 */
if ( !function_exists('sanitize_email') )
{
    function sanitize_email($email='')
    {
    	// Test for the minimum length the email can be
    	if (strlen( $email ) < 3)
    	{
    		return '';
    	}

    	// Test for an @ character after the first position
    	if (strpos($email, '@', 1) === false)
    	{
    		return '';
    	}

    	// Split out the local and domain parts
    	list($local, $domain) = explode('@', $email, 2);

    	// LOCAL PART
    	// Test for invalid characters
    	$local = preg_replace('/[^a-zA-Z0-9!#$%&\'*+\/=?^_`{|}~\.-]/', '', $local);
    	if ('' === $local)
    	{
    		return '';
    	}

    	// DOMAIN PART
    	// Test for sequences of periods
    	$domain = preg_replace('/\.{2,}/', '', $domain);
    	if ('' === $domain)
    	{
    		return '';
    	}

    	// Test for leading and trailing periods and whitespace
    	$domain = trim($domain, " \t\n\r\0\x0B.");
    	if ('' === $domain)
    	{
    		return '';
    	}

    	// Split the domain into subs
    	$subs = explode('.', $domain);

    	// Assume the domain will have at least two subs
    	if (2 > count($subs))
    	{
    		return '';
    	}

    	// Create an array that will contain valid subs
    	$new_subs = array();

    	// Loop through each sub
    	foreach ($subs as $sub)
    	{
    		// Test for leading and trailing hyphens
    		$sub = trim($sub, " \t\n\r\0\x0B-");

    		// Test for invalid characters
    		$sub = preg_replace('/[^a-z0-9-]+/i', '', $sub);

    		// If there's anything left, add it to the valid subs
    		if ('' !== $sub)
    		{
    			$new_subs[] = $sub;
    		}
    	}

    	// If there aren't 2 or more valid subs
    	if (2 > count($new_subs))
    	{
    		return '';
    	}

    	// Join valid subs into the new domain
    	$domain = join('.', $new_subs);

    	// Put the email back together
    	$email = $local . '@' . $domain;

    	// Congratulations your email made it!
    	return $email;
    }
}

/**
 * Sanitize username stripping out unsafe characters.
 *
 * Removes tags, octets, entities, and if strict is enabled, will only keep
 * alphanumeric, _, space, ., -, @. After sanitizing, it passes the username,
 * raw username (the username in the parameter), and the value of $strict as
 * parameters for the 'sanitize_user' filter.
 *
 * @param string $username The username to be sanitized
 * @param bool $strict If set limits $username to specific characters. Default false
 * @return string The sanitized username, after passing through filters
 */
if ( !function_exists('sanitize_user') )
{
    function sanitize_user($username='', $strict=false)
    {
    	$username = trim($username);

    	if (empty($username))
    	{
    		return '';
    	}

    	$username = strip_tags($username);
    	$username = remove_accents($username);

    	// Kill octets
    	$username = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '', $username);

    	// Kill entities
    	$username = preg_replace('/&.+?;/', '', $username);

    	// If strict, reduce to ASCII for max portability.
    	if ($strict)
    	{
    		$username = preg_replace('|[^a-z0-9 _\-@]|i', '', $username);
    	}

    	$username = trim($username);

    	// Consolidate contiguous whitespace
    	$username = preg_replace('|\s+|', ' ', $username);

    	return $username;
    }
}

/**
 * Converts all accent characters to ASCII characters.
 *
 * If there are no accent characters, then the string given is just returned.
 * Currently not use.
 *
 * @param string $string Text that might have accent characters
 * @return string Filtered string with replaced "nice" characters.
 */
if ( !function_exists('remove_accents') )
{
    function remove_accents( $string = '' ) {
    	if ( !preg_match('/[\x80-\xff]/', $string) ) {
    		return $string;
    	}

    	if ( seems_utf8($string) ) {
    		$chars = array(
    			// Decompositions for Latin-1 Supplement
    			chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
    			chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
    			chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
    			chr(195).chr(134) => 'AE',chr(195).chr(135) => 'C',
    			chr(195).chr(136) => 'E', chr(195).chr(137) => 'E',
    			chr(195).chr(138) => 'E', chr(195).chr(139) => 'E',
    			chr(195).chr(140) => 'I', chr(195).chr(141) => 'I',
    			chr(195).chr(142) => 'I', chr(195).chr(143) => 'I',
    			chr(195).chr(144) => 'D', chr(195).chr(145) => 'N',
    			chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
    			chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
    			chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
    			chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
    			chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
    			chr(195).chr(158) => 'TH',chr(195).chr(159) => 's',
    			chr(195).chr(160) => 'a', chr(195).chr(161) => 'a',
    			chr(195).chr(162) => 'a', chr(195).chr(163) => 'a',
    			chr(195).chr(164) => 'a', chr(195).chr(165) => 'a',
    			chr(195).chr(166) => 'ae',chr(195).chr(167) => 'c',
    			chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
    			chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
    			chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
    			chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
    			chr(195).chr(176) => 'd', chr(195).chr(177) => 'n',
    			chr(195).chr(178) => 'o', chr(195).chr(179) => 'o',
    			chr(195).chr(180) => 'o', chr(195).chr(181) => 'o',
    			chr(195).chr(182) => 'o', chr(195).chr(184) => 'o',
    			chr(195).chr(185) => 'u', chr(195).chr(186) => 'u',
    			chr(195).chr(187) => 'u', chr(195).chr(188) => 'u',
    			chr(195).chr(189) => 'y', chr(195).chr(190) => 'th',
    			chr(195).chr(191) => 'y',
    			// Decompositions for Latin Extended-A
    			chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
    			chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
    			chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
    			chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
    			chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
    			chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
    			chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
    			chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
    			chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
    			chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
    			chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
    			chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
    			chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
    			chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
    			chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
    			chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
    			chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
    			chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
    			chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
    			chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
    			chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
    			chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
    			chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
    			chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
    			chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
    			chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
    			chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
    			chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
    			chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
    			chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
    			chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
    			chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
    			chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
    			chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
    			chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
    			chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
    			chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
    			chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
    			chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
    			chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
    			chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
    			chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
    			chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
    			chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
    			chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
    			chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
    			chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
    			chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
    			chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
    			chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
    			chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
    			chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
    			chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
    			chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
    			chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
    			chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
    			chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
    			chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
    			chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
    			chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
    			chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
    			chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
    			chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
    			chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
    			// Decompositions for Latin Extended-B
    			chr(200).chr(152) => 'S', chr(200).chr(153) => 's',
    			chr(200).chr(154) => 'T', chr(200).chr(155) => 't',
    			// Euro Sign
    			chr(226).chr(130).chr(172) => 'E',
    			// GBP (Pound) Sign
    			chr(194).chr(163) => '',
    			// Vowels with diacritic (Vietnamese)
    			// unmarked
    			chr(198).chr(160) => 'O', chr(198).chr(161) => 'o',
    			chr(198).chr(175) => 'U', chr(198).chr(176) => 'u',
    			// grave accent
    			chr(225).chr(186).chr(166) => 'A', chr(225).chr(186).chr(167) => 'a',
    			chr(225).chr(186).chr(176) => 'A', chr(225).chr(186).chr(177) => 'a',
    			chr(225).chr(187).chr(128) => 'E', chr(225).chr(187).chr(129) => 'e',
    			chr(225).chr(187).chr(146) => 'O', chr(225).chr(187).chr(147) => 'o',
    			chr(225).chr(187).chr(156) => 'O', chr(225).chr(187).chr(157) => 'o',
    			chr(225).chr(187).chr(170) => 'U', chr(225).chr(187).chr(171) => 'u',
    			chr(225).chr(187).chr(178) => 'Y', chr(225).chr(187).chr(179) => 'y',
    			// hook
    			chr(225).chr(186).chr(162) => 'A', chr(225).chr(186).chr(163) => 'a',
    			chr(225).chr(186).chr(168) => 'A', chr(225).chr(186).chr(169) => 'a',
    			chr(225).chr(186).chr(178) => 'A', chr(225).chr(186).chr(179) => 'a',
    			chr(225).chr(186).chr(186) => 'E', chr(225).chr(186).chr(187) => 'e',
    			chr(225).chr(187).chr(130) => 'E', chr(225).chr(187).chr(131) => 'e',
    			chr(225).chr(187).chr(136) => 'I', chr(225).chr(187).chr(137) => 'i',
    			chr(225).chr(187).chr(142) => 'O', chr(225).chr(187).chr(143) => 'o',
    			chr(225).chr(187).chr(148) => 'O', chr(225).chr(187).chr(149) => 'o',
    			chr(225).chr(187).chr(158) => 'O', chr(225).chr(187).chr(159) => 'o',
    			chr(225).chr(187).chr(166) => 'U', chr(225).chr(187).chr(167) => 'u',
    			chr(225).chr(187).chr(172) => 'U', chr(225).chr(187).chr(173) => 'u',
    			chr(225).chr(187).chr(182) => 'Y', chr(225).chr(187).chr(183) => 'y',
    			// tilde
    			chr(225).chr(186).chr(170) => 'A', chr(225).chr(186).chr(171) => 'a',
    			chr(225).chr(186).chr(180) => 'A', chr(225).chr(186).chr(181) => 'a',
    			chr(225).chr(186).chr(188) => 'E', chr(225).chr(186).chr(189) => 'e',
    			chr(225).chr(187).chr(132) => 'E', chr(225).chr(187).chr(133) => 'e',
    			chr(225).chr(187).chr(150) => 'O', chr(225).chr(187).chr(151) => 'o',
    			chr(225).chr(187).chr(160) => 'O', chr(225).chr(187).chr(161) => 'o',
    			chr(225).chr(187).chr(174) => 'U', chr(225).chr(187).chr(175) => 'u',
    			chr(225).chr(187).chr(184) => 'Y', chr(225).chr(187).chr(185) => 'y',
    			// acute accent
    			chr(225).chr(186).chr(164) => 'A', chr(225).chr(186).chr(165) => 'a',
    			chr(225).chr(186).chr(174) => 'A', chr(225).chr(186).chr(175) => 'a',
    			chr(225).chr(186).chr(190) => 'E', chr(225).chr(186).chr(191) => 'e',
    			chr(225).chr(187).chr(144) => 'O', chr(225).chr(187).chr(145) => 'o',
    			chr(225).chr(187).chr(154) => 'O', chr(225).chr(187).chr(155) => 'o',
    			chr(225).chr(187).chr(168) => 'U', chr(225).chr(187).chr(169) => 'u',
    			// dot below
    			chr(225).chr(186).chr(160) => 'A', chr(225).chr(186).chr(161) => 'a',
    			chr(225).chr(186).chr(172) => 'A', chr(225).chr(186).chr(173) => 'a',
    			chr(225).chr(186).chr(182) => 'A', chr(225).chr(186).chr(183) => 'a',
    			chr(225).chr(186).chr(184) => 'E', chr(225).chr(186).chr(185) => 'e',
    			chr(225).chr(187).chr(134) => 'E', chr(225).chr(187).chr(135) => 'e',
    			chr(225).chr(187).chr(138) => 'I', chr(225).chr(187).chr(139) => 'i',
    			chr(225).chr(187).chr(140) => 'O', chr(225).chr(187).chr(141) => 'o',
    			chr(225).chr(187).chr(152) => 'O', chr(225).chr(187).chr(153) => 'o',
    			chr(225).chr(187).chr(162) => 'O', chr(225).chr(187).chr(163) => 'o',
    			chr(225).chr(187).chr(164) => 'U', chr(225).chr(187).chr(165) => 'u',
    			chr(225).chr(187).chr(176) => 'U', chr(225).chr(187).chr(177) => 'u',
    			chr(225).chr(187).chr(180) => 'Y', chr(225).chr(187).chr(181) => 'y',
    			// Vowels with diacritic (Chinese, Hanyu Pinyin)
    			chr(201).chr(145) => 'a',
    			// macron
    			chr(199).chr(149) => 'U', chr(199).chr(150) => 'u',
    			// acute accent
    			chr(199).chr(151) => 'U', chr(199).chr(152) => 'u',
    			// caron
    			chr(199).chr(141) => 'A', chr(199).chr(142) => 'a',
    			chr(199).chr(143) => 'I', chr(199).chr(144) => 'i',
    			chr(199).chr(145) => 'O', chr(199).chr(146) => 'o',
    			chr(199).chr(147) => 'U', chr(199).chr(148) => 'u',
    			chr(199).chr(153) => 'U', chr(199).chr(154) => 'u',
    			// grave accent
    			chr(199).chr(155) => 'U', chr(199).chr(156) => 'u',
    			);

    		$string = strtr($string, $chars);
    	}
    	else
    	{
    		// Assume ISO-8859-1 if not UTF-8
    		$chars['in'] = chr(128).chr(131).chr(138).chr(142).chr(154).chr(158)
    			.chr(159).chr(162).chr(165).chr(181).chr(192).chr(193).chr(194)
    			.chr(195).chr(196).chr(197).chr(199).chr(200).chr(201).chr(202)
    			.chr(203).chr(204).chr(205).chr(206).chr(207).chr(209).chr(210)
    			.chr(211).chr(212).chr(213).chr(214).chr(216).chr(217).chr(218)
    			.chr(219).chr(220).chr(221).chr(224).chr(225).chr(226).chr(227)
    			.chr(228).chr(229).chr(231).chr(232).chr(233).chr(234).chr(235)
    			.chr(236).chr(237).chr(238).chr(239).chr(241).chr(242).chr(243)
    			.chr(244).chr(245).chr(246).chr(248).chr(249).chr(250).chr(251)
    			.chr(252).chr(253).chr(255);

    		$chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

    		$string = strtr($string, $chars['in'], $chars['out']);
    		$double_chars['in'] = array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254));
    		$double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
    		$string = str_replace($double_chars['in'], $double_chars['out'], $string);
    	}

    	return $string;
    }
}

/**
 * Get full current URL
 * @param none
 * @return String
 */
if ( !function_exists('get_full_current_url') )
{
    function get_full_current_url() {
    	 if ( isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) {
            $_SERVER['HTTPS'] = 'on';
            $_SERVER['SERVER_PORT'] = '443';
        }

    	$pageURL = 'http';
     	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}

     	$pageURL .= "://";
     	if ($_SERVER["SERVER_PORT"] != "80") {
      		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
     	} else {
      		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
     	}

     	return $pageURL;
    }
}

/**
* Determine if SSL is used.
*
* @return bool True if SSL, false if not used.
*/
if ( !function_exists('is_ssl') ){
    function is_ssl() {
        // modified by Mr. Adi on 2012-10-18
        if ( isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) {
            $_SERVER['HTTPS'] = 'on';
            $_SERVER['SERVER_PORT'] = '443';
        }

        if ( isset($_SERVER['HTTPS']) ) {
            if ( 'on' == strtolower($_SERVER['HTTPS']) )
                return true;
            if ( '1' == $_SERVER['HTTPS'] )
                return true;
        } elseif ( isset($_SERVER['SERVER_PORT']) && ( '443' == $_SERVER['SERVER_PORT'] ) ) {
            return true;
        }
        return false;
    }
}

/**
 * Check if this site was maintenance or not.
 * @return Boolean.
 */
if ( !function_exists('is_maintenance') )
{
    function is_maintenance()
    {
        return config_item('maintenance');
    }
}

/**
 * Check if this site still coming soon or not.
 * @return Boolean.
 */
if ( !function_exists('is_coming_soon') )
{
    function is_coming_soon()
    {
        return config_item('coming_soon');
    }
}

/**
 * This function takes a path to a file to output ($file),  the filename that the browser will see ($name) and  the MIME type of the file ($mime_type, optional).
 *
 * @param string $file      (Required)  File path
 * @param string $name      (Required)  File name
 * @param string $mime_type (Optional)  File mime type
 * @return none
 */

if ( !function_exists('output_file') )
{
    function output_file($file, $name, $mime_type='')
    {
        //Check the file premission
        if(!is_readable($file)) die('File not found or inaccessible!');

        $size       = filesize($file);
        $name       = rawurldecode($name);

        /* Figure out the MIME type | Check in array */
        $known_mime_types=array(
            "pdf"   => "application/pdf",
            "txt"   => "text/plain",
            "html"  => "text/html",
            "htm"   => "text/html",
            "exe"   => "application/octet-stream",
            "zip"   => "application/zip",
            "doc"   => "application/msword",
            "xls"   => "application/vnd.ms-excel",
            "ppt"   => "application/vnd.ms-powerpoint",
            "gif"   => "image/gif",
            "png"   => "image/png",
            "jpeg"  => "image/jpg",
            "jpg"   => "image/jpg",
            "php"   => "text/plain"
        );

        if($mime_type==''){
            $file_extension     = strtolower(substr(strrchr($file,"."),1));
            if(array_key_exists($file_extension, $known_mime_types)){
                $mime_type      = $known_mime_types[$file_extension];
            } else {
                $mime_type      = "application/force-download";
            };
        };

        //turn off output buffering to decrease cpu usage
        @ob_end_clean();

        // required for IE, otherwise Content-Disposition may be ignored
        if(ini_get('zlib.output_compression')) ini_set('zlib.output_compression', 'Off');

        header('Content-Type: ' . $mime_type);
        header('Content-Disposition: attachment; filename="'.$name.'"');
        header("Content-Transfer-Encoding: binary");
        header('Accept-Ranges: bytes');

        /* The three lines below basically make the
        download non-cacheable */
        header("Cache-control: private");
        header('Pragma: private');
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

        // multipart-download and download resuming support
        if(isset($_SERVER['HTTP_RANGE']))
        {
            list($a, $range)            = explode("=",$_SERVER['HTTP_RANGE'],2);
            list($range)                = explode(",",$range,2);
            list($range, $range_end)    = explode("-", $range);

            $range = intval($range);

            if(!$range_end) {
                $range_end  = $size-1;
            } else {
                $range_end  = intval($range_end);
            }

            /*
            ------------------------------------------------------------------------------------------------------
            //This application is developed by www.webinfopedia.com
            //visit www.webinfopedia.com for PHP,Mysql,html5 and Designing tutorials for FREE!!!
            ------------------------------------------------------------------------------------------------------
            */
            $new_length     = $range_end-$range+1;
            header("HTTP/1.1 206 Partial Content");
            header("Content-Length: $new_length");
            header("Content-Range: bytes $range-$range_end/$size");
        } else {
            $new_length     = $size;
            header("Content-Length: ".$size);
        }

        /* Will output the file itself */
        $chunksize  = 1*(1024*1024); //you may want to change this
        $bytes_send = 0;
        if ($file = fopen($file, 'r'))
        {
            if(isset($_SERVER['HTTP_RANGE']))
                fseek($file, $range);

                while(!feof($file) && (!connection_aborted()) && ($bytes_send<$new_length))
                {
                    $buffer = fread($file, $chunksize);
                    print($buffer); //echo($buffer); // can also possible
                    flush();
                    $bytes_send += strlen($buffer);
                }
                fclose($file);
        } else {
            //If no permissiion
            die('Error - can not open file.');
        }

        //die
        die();
    }
}


/**
 * Is development
 */
if ( !function_exists('is_development') )
{
    function is_development()
    {
    	return ENVIRONMENT == 'development';
    }
}

/**
 * Returns HTML <center>...</center>
 * @author  Yuda
 */
if ( !function_exists('ddm_center') )
{
    function ddm_center($str)
    {
        return '<center>' . $str . '</center>';
    }
}

/**
 * Returns HTML pull right
 * @author  Yuda
 */
if ( !function_exists('ddm_right') )
{
    function ddm_right($str)
    {
        return '<div class="text-right">' . $str . '</div>';
    }
}

/**
 * Returns HTML pull right
 * @author  Yuda
 */
if ( !function_exists('ddm_left') )
{
    function ddm_left($str)
    {
        return '<span class="pull-left">' . $str . '</span>';
    }
}

/**
 * Returns HTML <strong>...</strong>
 * @author  Yuda
 */
if ( !function_exists('ddm_strong') )
{
    function ddm_strong($str)
    {
        return '<strong>' . $str . '</strong>';
    }
}

/**
 * Returns HTML <i>...</i>
 * @author  Yuda
 */
if ( !function_exists('ddm_italic') )
{
    function ddm_italic($str)
    {
        return '<i>' . $str . '</i>';
    }
}

/**
 * Returns number format
 * @author  Yuda
 */
if ( !function_exists('ddm_number') )
{
    function ddm_number($number, $decimals = 2, $dec_point = "." , $thousands_sep = ",")
    {
        return number_format($number, $decimals, $dec_point, $thousands_sep);
    }
}

/**
 * Returns number format with currency
 * @author  Yuda
 */
if ( !function_exists('ddm_accounting') )
{
    function ddm_accounting($amount, $currency = '', $justified=false)
    {
    	if ($justified)
            return '<div style="text-align: left;"><div style="display: inline-block; float: right; margin-left: 1px;">' .
                ddm_number($amount,0,',','.') . '</div>' . $currency . '</div>';
        return trim( $currency . ' '. ddm_number($amount,0,',','.') );
    }
}


/**
 * Directly output without buffering
 *
 * @param none
 * @return none
 */
if ( !function_exists('ddm_flush') )
{
    function ddm_flush($text='')
    {
    	echo $text; ob_flush(); flush();
    }
}

/**
 * Retrieve unique identifier for this user.
 *
 * @param none.
 * @return ip address
 */
if ( !function_exists('ddm_get_current_ip') )
{
    function ddm_get_current_ip()
    {
        $unique_ip = trim( getenv( 'HTTP_X_FORWARDED_FOR' ) );

        if ( ! preg_match("/^((1?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(1?\d{1,2}|2[0-4]\d|25[0-5])$/", $unique_ip ) ) {
        	if ( ! empty( $_SERVER['REMOTE_ADDR'] ) )
            	$unique_ip = $_SERVER['REMOTE_ADDR'];
        }

        return $unique_ip;
    }
}

/**
 * Validation for input captcha
 * @author  Yuda
 */
if ( !function_exists('ddm_validate_captcha') )
{
    function ddm_validate_captcha( $captcha_response = '' ) {
    	$CI =& get_instance();

    	if ( empty( $captcha_response ) )
    		$captcha_response = $CI->input->post( 'g-recaptcha-response' );

    	$CI->load->helper( 'curl_helper' );
    	return ddm_curl_post( config_item( 'captcha_verify_url' ), array(
    		'secret' => config_item( 'captcha_secret_key' ),
    		'response' => $captcha_response,
    		'remoteip' => ddm_get_current_ip()
    	));
    }
}

if (!function_exists('ddm_packages')) {
    /**
     * Get Package data
     * @author  Yuda
     * @param   Varchar         $package     (Optional)  Package
     * @return  Package data
     */
    function ddm_packages($package = '', $is_active = true)
    {
        $CI = &get_instance();
        $packages = $CI->Model_Option->get_packages($package, $is_active);
        return $packages;
    }
}

if (!function_exists('ddm_banks')) {
    /**
     * Get bank data
     * @author  Yuda
     * @param   Int         $id     (Optional)  ID of bank
     * @return  Bank data
     */
    function ddm_banks($id = '')
    {
        $CI = &get_instance();
        $banks = $CI->Model_Bank->get_bank($id);
        return $banks;
    }
}

if (!function_exists('ddm_products')) {
    /**
     * Get product data
     * @author  Yuda
     * @param   Int     $id     (Optional)  product id
     * @return  Data
     */
    function ddm_products($id = '', $is_active = false)
    {
        $CI = &get_instance();
        $products = $CI->Model_Product->get_products($id, $is_active);
        return $products;
    }
}

if (!function_exists('ddm_product_by')) {
    /**
     * Get product by field data
     * @author  Yuda
     * @param   String     $field     (Required)  field of data
     * @param   String     $value     (Required)  value of field
     * @return  Data
     */
    function ddm_product_by($field='', $value='', $conditions='', $limit = 0)
    {
        $CI = &get_instance();
        $products = $CI->Model_Product->get_product_by($field, $value, $conditions, $limit);
        return $products;
    }
}

if (!function_exists('ddm_product_category')) {
    /**
     * Get product category data
     * @author  Yuda
     * @param   Int     $id     (Optional)  product category id
     * @return  Data
     */
    function ddm_product_category($id = '', $is_active = false)
    {
        $CI = &get_instance();
        $product_category = $CI->Model_Product->get_product_category($id, $is_active);
        return $product_category;
    }
}

if (!function_exists('ddm_product_package')) {
    /**
     * Get product package data
     * @author  Yuda
     * @param   Int     $id     (Optional)  product package id
     * @return  Data
     */
    function ddm_product_package($field = '', $value = '', $condition = '', $limit = 0)
    {
        $CI = &get_instance();
        $product_package = $CI->Model_Product->get_product_package_by($field, $value, $condition, $limit);
        return $product_package;
    }
}

if (!function_exists('ddm_product_point_by')) {
    /**
     * Get product by field data
     * @author  Yuda
     * @param   String     $field     (Required)  field of data
     * @param   String     $value     (Required)  value of field
     * @return  Data
     */
    function ddm_product_point_by($field='', $value='', $conditions='', $limit = 0)
    {
        $CI = &get_instance();
        $products = $CI->Model_Product->get_product_point_by($field, $value, $conditions, $limit);
        return $products;
    }
}

if ( !function_exists('ddm_provinces') )
{
    /**
     * Get province data
     * @author  Yuda
     * @param   Int         $id     (Optional)  ID of Province
     * @return  Province data
     */
    function ddm_provinces($id='') {
        $CI =& get_instance();
        return $CI->Model_Address->get_provinces($id);
    }
}

if ( !function_exists('ddm_districts') )
{
    /**
     * Get district data
     * @author  Yuda
     * @param   Int         $id         (Optional)  ID of district
     * @return  district data
     */
	function ddm_districts($id='') {
        $CI =& get_instance();
		return $CI->Model_Address->get_districts($id);
	}
}

if ( !function_exists('ddm_districts_by_province') )
{
    /**
     * Get district by province data
     * @author  Yuda
     * @param   Int         $province_id    (Required)  ID of Province
     * @param   String      $district_name  (Optional)  district name
     * @return  Cities data
     */
	function ddm_districts_by_province($province_id, $district_name = '' ) {
        if ( !$province_id ) return false;
        $CI =& get_instance();
		return $CI->Model_Address->get_districts_by_province($province_id, $district_name);
	}
}

if ( !function_exists('ddm_subdistricts') )
{
    /**
     * Get subdistrict data
     * @author  Yuda
     * @param   Int         $id     (Optional)  ID of subdistrict
     * @return  subdistrict data
     */
    function ddm_subdistricts($id='') {
        $CI =& get_instance();
        return $CI->Model_Address->get_subdistricts($id);
    }
}

if ( !function_exists('ddm_subdistricts_by_district') )
{
    /**
     * Get subdistrict by city data
     * @author  Yuda
     * @param   Int         $district_id    (Required)  ID of district
     * @return  subdistrict data
     */
	function ddm_subdistricts_by_district($district_id) {
        if ( !$district_id ) return false;
        $CI =& get_instance();
        return $CI->Model_Address->get_subdistricts_by_district($district_id);
	}
}

if ( !function_exists('ddm_villages') )
{
    /**
     * Get village data
     * @author  Yuda
     * @param   Int         $id     (Optional)  ID of village
     * @return  village data
     */
    function ddm_villages($id='') {
        $CI =& get_instance();
        return $CI->Model_Address->get_villages($id);
    }
}

if ( !function_exists('ddm_villages_by_subdistrict') )
{
    /**
     * Get subdistrict by city data
     * @author  Yuda
     * @param   Int     $subdistrict_id     (Required)  ID of subdistrict
     * @return  subdistrict data
     */
    function ddm_villages_by_subdistrict($subdistrict_id) {
        if ( !$subdistrict_id ) return false;
        $CI =& get_instance();
        return $CI->Model_Address->get_villages_by_subdistrict($subdistrict_id);
    }
}

if ( !function_exists('ddm_alert') )
{
    /**
     * Alert element
     * @author  Yuda
     * @return  Alert element
     */
	function ddm_alert($str)
    {
        return '<button class="close" data-close="alert" type="button"><i class="fa fa-times"></i></button>' . $str;
	}
}

if ( !function_exists('ddm_debug') )
{
    /**
     * Set debug mode
     */
    function ddm_debug( $debug = true ) {
    	$CI =& get_instance();
    	$CI->debug = $debug;
    }
}

if ( !function_exists('ddm_is_debug') )
{
    /**
     * Check debug mode
     */
    function ddm_is_debug() {
    	$CI =& get_instance();
    	return ! empty( $CI->debug );
    }
}

if ( !function_exists('get_parseURL') ){
    function get_parseURL($url = ''){
        if ( !$url ) return false;
        // $url = substr($url,0,4)=='http'? $url: 'http://'.$url;          //assume http if not supplied
        $parse_url  = parse_url($url);
        if ( ! isset($parse_url['scheme']) ) {
            $url    = SCHEMA . $url;
        }

        if ( $urldata = parse_url(str_replace('&amp;','&',$url)) ){
            $path_parts = pathinfo($urldata['host']);
            $tmp        = explode('.',$urldata['host']); $n = count($tmp);
            if ($n>=2){
                if ($n==4 || ($n==3 && strlen($tmp[($n-2)])<=3)){
                    $urldata['domain']      = $tmp[($n-3)].".".$tmp[($n-2)].".".$tmp[($n-1)];
                    $urldata['tld']         = $tmp[($n-2)].".".$tmp[($n-1)];                        //top-level domain
                    $urldata['root']        = $tmp[($n-3)];                                         //second-level domain
                    $urldata['subdomain']   = $n==4? $tmp[0]: ($n==3 && strlen($tmp[($n-2)])<=3)? $tmp[0]: '';
                } else {
                    $urldata['domain']      = $tmp[($n-2)].".".$tmp[($n-1)];
                    $urldata['tld']         = $tmp[($n-1)];
                    $urldata['root']        = $tmp[($n-2)];
                    $urldata['subdomain']   = $n==3? $tmp[0]: '';
                }
            }

            if ( !isset($urldata['domain']) ) {
                return false;
            }

            //$urldata['dirname'] = $path_parts['dirname'];
            $urldata['basename']    = $path_parts['basename'];
            $urldata['filename']    = $path_parts['filename'];
            $urldata['extension']   = (isset($path_parts['extension'])) ? $path_parts['extension'] : '';
            $urldata['base']        = $urldata['scheme']."://".$urldata['host'];
            // $urldata['abs']         = (isset($urldata['path']) && strlen($urldata['path']))? $urldata['path']: '';
            $urldata['abs']         = (isset($urldata['query']) && strlen($urldata['query']))? '?'.$urldata['query']: '';

            //Set data
            return $urldata;
        } else {
            //invalid URL
            return false;
        }
    }
}

if ( !function_exists('ddm_format_size_units') )
{
    /**
     * Count Member Sponsored
     *
     * @author  Yuda
     * @param   Int $id_member  ID Member
     * @return  Boolean Int 0 on failed process or invalid data, otherwise member count
     */
    function ddm_format_size_units($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' Bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' Byte';
        }
        else
        {
            $bytes = '0 Bytes';
        }

        return $bytes;
    }
}

if ( !function_exists('ddm_html2text') )
{
    /**
     * Convert HTML to plain text
     * 
     * @author Yuda
     * @since 1.0.0
     * @access public
     * @param int $html
     * @return string 
     * 
     */
    function ddm_html2text( $html ) {
        $rules = array('@<script[^>]*?>.*?</script>@si',
            '@<[\/\!]*?[^<>]*?>@si',
            '@([\r\n])[\s]+@',
            '@&(quot|#34);@i',
            '@&(amp|#38);@i',
            '@&(lt|#60);@i',
            '@&(gt|#62);@i',
            '@&(nbsp|#160);@i',
            '@&(iexcl|#161);@i',
            '@&(cent|#162);@i',
            '@&(pound|#163);@i',
            '@&(copy|#169);@i',
            '@&(reg|#174);@i',
            '@&#(d+);@i'
        );
        
        $replace = array('',
            '',
            '',
            '',
            '&',
            '<',
            '>',
            ' ',
            chr(161),
            chr(162),
            chr(163),
            chr(169),
            chr(174),
            'chr()'
        );
        
        return preg_replace($rules, $replace, $html);
    }
}

if ( !function_exists('upload_file') ){
    function upload_file($input_name, $file_type, $destination){
        $file = $_FILES[$input_name];
        $filename = explode(".", $file["name"]);
		$file_name = $file['name'];
        $file_extension = $filename[count($filename)-1];
        $pic_ext = array('jpg','png','jpeg', 'bmp', 'ico');
        $doc_ext = array('doc', 'docx', 'pdf');
        $spd_ext = array('xls', 'xslx', 'csv');
        $ok_ext  = "";
        if($file_type=="images") $ok_ext = $pic_ext;
        if($file_type=="document") $ok_ext = $doc_ext;
        if($file_type=="spreadsheet") $ok_ext = $spd_ext;
        if ($file['tmp_name']!='') {
		// If there is no error
			if( $file['error'] == 0 ){
				// check if the extension is accepted
				if( in_array(strtolower($file_extension), $ok_ext)){
					// check if the size is not beyond expected size
					// rename the file
					$fileNewName = str_replace(" ","_",strtolower(uniqid())).'.'.$file_extension ;
					// and move it to the destination folder
					if( move_uploaded_file($file['tmp_name'], $destination.$fileNewName) ){
						$foto_path = $destination.$fileNewName;
                        return $fileNewName;
					} else return 'error_upload';

				} else return 'error_extension';

			} else return 'error';
		} else return 'empty';
    }
}

/**
 *
 */
function ddm_download($file, $name='', $mime_type='') {
    if( !is_readable($file) ) die('File not found or inaccessible!');

    $size = filesize($file);
    $name = rawurldecode($name);

    /* Figure out the MIME type (if not specified) */
    $known_mime_types = array(
        "pdf" => "application/pdf",
        "exe" => "application/octet-stream",
        "zip" => "application/zip",
        "rar" => "application/rar",
        "doc" => "application/msword",
        "docx"=> "application/msword",
        "xls" => "application/vnd.ms-excel",
        "xlsx"=> "application/vnd.ms-excel",
        "ppt" => "application/vnd.ms-powerpoint",
        "swf" => "application/x-shockwave-flash",
        "mmap"=> "application/mmap",
        "mp3" => "audio/mpeg",
        "wav" => "audio/x-wav",
        "gif" => "image/gif",
        "png" => "image/png",
        "jpeg"=> "image/jpg",
        "jpg" => "image/jpg",
        "txt" => "text/plain",
        "flv" => "video/x-flv",
        "mp4" => "video/mpeg",
        "mpeg"=> "video/mpeg",
        "mpg" => "video/mpeg",
        "mov" => "video/quicktime",
        "avi" => "video/x-msvideo",
        "wmv" => "video/x-ms-wmv",

    );

    if ( $mime_type == '' ) {
        $file_extension = strtolower(substr(strrchr($file,"."),1));
        if(array_key_exists($file_extension, $known_mime_types)){
            $mime_type = $known_mime_types[$file_extension];
        } else {
            $mime_type = "application/force-download";
        }
    }

    @ob_end_clean(); //turn off output buffering to decrease cpu usage

    if ( $mime_type == ( 'flv' || 'mp4' || 'mov' || 'mp3' ) ) {
        $disposition = 'inline';
    } else {
        $disposition = 'attachment';
    }

    // required for IE, otherwise Content-Disposition may be ignored
    if(ini_get('zlib.output_compression'))
        ini_set('zlib.output_compression', 'Off');

    header('Content-Type: ' . $mime_type);
    header('Content-Disposition: ' . $disposition . '; filename="'.$name.'"');
    header("Content-Transfer-Encoding: binary");
    header('Accept-Ranges: bytes');

    /* The three lines below basically make the download non-cacheable */
    header("Cache-control: private");
    header('Pragma: private');
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

    // multipart-download and download resuming support
    if(isset($_SERVER['HTTP_RANGE'])) {
        list($a, $range) = explode("=",$_SERVER['HTTP_RANGE'],2);
        list($range) = explode(",",$range,2);
        list($range, $range_end) = explode("-", $range);
        $range=intval($range);
        if(!$range_end) {
            $range_end=$size-1;
        } else {
            $range_end=intval($range_end);
        }

        $new_length = $range_end-$range+1;
        header("HTTP/1.1 206 Partial Content");
        header("Content-Length: $new_length");
        header("Content-Range: bytes $range-$range_end/$size");
    } elseif (isset($_SERVER['HTTPS_RANGE'])) {
        list($a, $range) = explode("=",$_SERVER['HTTPS_RANGE'],2);
        list($range) = explode(",",$range,2);
        list($range, $range_end) = explode("-", $range);
        $range=intval($range);
        if(!$range_end) {
            $range_end=$size-1;
        } else {
            $range_end=intval($range_end);
        }

        $new_length = $range_end-$range+1;
        header("HTTPS/1.1 206 Partial Content");
        header("Content-Length: $new_length");
        header("Content-Range: bytes $range-$range_end/$size");
    } else {
        $new_length=$size;
        header("Content-Length: ".$size);
    }

    /* output the file itself */
    $chunksize = 1*(1024*1024); //you may want to change this
    $bytes_send = 0;
    if ($file = fopen($file, 'r')) {
        if(isset($_SERVER['HTTP_RANGE']))
            fseek($file, $range);

        if(isset($_SERVER['HTTPS_RANGE']))
            fseek($file, $range);

        while( !feof($file) && (!connection_aborted()) && ($bytes_send<$new_length) ) {
            $buffer = fread($file, $chunksize);
            print($buffer); // echo($buffer); // is also possible
            flush();
            $bytes_send += strlen($buffer);
        }
        fclose($file);
    } else die('Error - can not open file.');

    die();
}

/*
|--------------------------------------------------------------------------
| Create Thumbnail Image
|--------------------------------------------------------------------------
*/
if (!function_exists('create_thumbnail')) {

    function create_thumbnail($filename, $img_path)
    {
        $CI = &get_instance();
        if (is_array($filename)) {
            foreach ($filename as $image) {
                $source_path = $img_path . '/' . $image['image'];
                $target_path = $img_path . '/thumbnail/' . $image['image'];

                $config_img = array(
                    'image_library' => 'gd2',
                    'source_image'  => $source_path,
                    'new_image'     => $target_path,
                    'maintain_ratio' => FALSE,
                    'width'         => 350,
                    'height'        => 350
                );

                $CI->load->library('image_lib', $config_img);
                $CI->image_lib->initialize($config_img);
                if (!$CI->image_lib->resize()) {
                    return false;
                }
                $CI->image_lib->clear();
            }
        } else {
            $source_path = $img_path . '/' . $filename;
            $target_path = $img_path . '/thumbnail/' . $filename;

            $config_img = array(
                'image_library'     => 'gd2',
                'source_image'      => $source_path,
                'new_image'         => $target_path,
                'maintain_ratio'    => FALSE,
                'width'             => 350,
                'height'            => 350
            );

            $CI->load->library('image_lib', $config_img);
            $CI->image_lib->initialize($config_img);
            if (!$CI->image_lib->resize()) {
                echo 'Create thumbnail failed';
            }
            $CI->image_lib->clear();
        }
    }
}

/*
CHANGELOG
---------
Insert new changelog at the top of the list.
-----------------------------------------------
Version YYYY/MM/DD  Person Name     Description
-----------------------------------------------
1.0.0   2016/06/01  Yuda           - Create this changelog.
*/
