<?php if (!defined("BASEPATH")) exit("No direct script access allowed");

// -------------------------------------------------------------------------
// Login functions helper
// -------------------------------------------------------------------------

if ( !function_exists('is_logged_in') ){
    /**
     * Checks if the current visitor is a logged in user.
     *
     * @return bool True if user is logged in, false if not logged in.
     */
    function is_logged_in()
    {
        $CI =& get_instance();
        $id_member  = ddm_get_current_member_id();

        if ( !$id_member ){
            if ($id = ddm_isset($_COOKIE['logged_in_'.md5('nonssl')], false, true)){
                $member     = $CI->Model_Auth->get_userdata($id);
                $id_member  = $member->id;
            }
            return false;
        }

        return true;
    }
}

/*
|--------------------------------------------------------------------------
| Get user login info | get $data = userid / username
|--------------------------------------------------------------------------
*/
if (!function_exists('user_info')) {

    function user_info($field = '')
    {
        $CI = &get_instance();

        if (!is_logged_in()) {
            return FALSE;
        } else {
            $user = ddm_get_current_member();
            $info = $user->username;   
            if ( $field ) {
                $info = isset($user->$field) ? $user->$field : $user->username;   
            }

            if ( $staff = ddm_get_current_staff() ) {
                $info = $staff->username;   
                if ( $field ) {
                    $info = isset($staff->$field) ? $staff->$field : $staff->username;   
                }  

            }
            return $info;
        }
    }
}

if ( !function_exists('is_member_logged_in') ){
    /**
     * Checks if the current visitor is a logged in user.
     *
     * @return bool True if user is logged in, false if not logged in.
     */
    function is_member_logged_in()
    {
        $CI =& get_instance();
        $id_member  = ddm_get_current_member_id();

        if ( !$id_member ){
            if ($id = ddm_isset($_COOKIE['logged_in_'.md5('nonssl')], false, true)){
                $member     = $CI->Model_Auth->get_userdata($id);
                $id_member  = $member->id;
            }
            return false;
        }

        return true;
    }
}

if (!function_exists('ddm_get_last_logged_in')){
    /**
     * Get last login member via cookies
     * @return member id
     */
    function ddm_get_last_logged_in()
    {
        $CI     =& get_instance();
        $name   = 'last_login_'.strtolower(date('F'));
        $cookie = $CI->input->cookie($name);

        if(!$cookie) return false;

        return $cookie;
    }
}

if (!function_exists('ddm_get_current_member_id')){
    /**
     *
     * Get current logged in member id
     * @param none
     * @return integer member id
     */
    function ddm_get_current_member_id()
    {
        $auth_cookie = ddm_parse_auth_cookie( ddm_isset( $_COOKIE[LOGGED_IN_COOKIE] ), 'logged_in');
        if( !is_array($auth_cookie) ) return false;

        return $auth_cookie['id_member'];
    }
}

if ( !function_exists('ddm_set_current_member') ){
    /**
     * set the current member by ID.
     *
     * Some Kopi Ampuh functionality is based on the current member and not based on
     * the signed in member. Therefore, it opens the ability to edit and perform
     * actions on members who aren't signed in.
     *
     * @param   int     $id     Member ID
     * @return  ddm_member Current member ddm_member object
     */
    function ddm_set_current_member($id)
    {
        $CI =& get_instance();

        $current_member = $CI->ddm_member->member($id);
        unset($current_member->password);

        return $current_member;
    }
}

if ( !function_exists('ddm_get_current_member') ){
    /**
     * Retrieve the current member object.
     *
     * @return ddm_member Current member ddm_member object
     */
    function ddm_get_current_member()
    {
        $CI =& get_instance();
        if(!empty($CI->current_member)) return $CI->current_member;

        $current_member = get_currentmemberinfo();

        if ( !$current_member ){
            if ($id = ddm_isset($_COOKIE['logged_in_'.md5('nonssl')], false, true)){
                $session_userdata = $CI->session->userdata('member_logged_in');
                if (ddm_isset($session_userdata) != "")
                    return ddm_set_current_member($id);
            }
            return false;
        }
        return $current_member;
    }
}

if ( !function_exists('ddm_get_current_staff') ){
    /**
     * Retrieve the current member object.
     *
     * @return ddm_Member Current member ddm_Member object
     */
    function ddm_get_current_staff()
    {
        $CI =& get_instance();
        if ( $id_staff = ddm_validate_auth_cookie_staff() ) {
            $CI->load->library( 'ddm_staff' );
            return $CI->ddm_staff->staff( $id_staff );
        }

        return false;
    }
}

if ( !function_exists('get_currentmemberinfo') ){
    /**
     * Populate global variables with information about the currently logged in member.
     *
     * Will set the current member, if the current member is not set. The current member
     * will be set to the logged in person. If no member is logged in, then it will
     * set the current member to 0, which is invalid and won't have any permissions.
     *
     * @uses ddm_validate_auth_cookie() Retrieves current logged in member.
     *
     */
    function get_currentmemberinfo()
    {
        if ( !$id_member = ddm_validate_auth_cookie() ) {
             if ( empty($_COOKIE[LOGGED_IN_COOKIE]) || !$id_member = ddm_validate_auth_cookie($_COOKIE[LOGGED_IN_COOKIE], 'logged_in') ) {
                ddm_set_current_member(0);
                return false;
             }
        }
        return ddm_set_current_member($id_member);
    }
}

if ( !function_exists('ddm_parse_auth_cookie') ){
    /**
     * Parse a cookie into its components
     *
     * @param string $cookie
     * @param string $scheme Optional. The cookie scheme to use: auth, secure_auth, or logged_in
     * @return array Authentication cookie components
     */
    function ddm_parse_auth_cookie($cookie = '', $scheme = '')
    {
        $CI =& get_instance();

        if( empty($cookie) ) {
            switch ($scheme) {
                case 'auth':
                    $cookie_name        = AUTH_COOKIE;
                    break;
                case 'secure_auth':
                    $cookie_name        = SECURE_AUTH_COOKIE;
                    break;
                case 'logged_in':
                    $cookie_name        = LOGGED_IN_COOKIE;
                    break;
                default:
                    if ( is_ssl() ) {
                        $cookie_name    = SECURE_AUTH_COOKIE;
                        $scheme         = 'secure_auth';
                    } else {
                        $cookie_name    = AUTH_COOKIE;
                        $scheme         = 'auth';
                    }
                    break;
            }

            if ( empty($_COOKIE[$cookie_name]) )
                return false;
            $cookie = $_COOKIE[$cookie_name];
        }

        $cookie_elements = explode('|', $cookie);
        if ( count($cookie_elements) != 4 && count($cookie_elements) != 5 ) return false;

        if ( count($cookie_elements) == 5 ) {
            list($username, $expiration, $hmac, $id_member, $id_staff) = $cookie_elements;
            return compact('username', 'expiration', 'hmac', 'id_member', 'id_staff', 'scheme');
        }

        list($username, $expiration, $hmac, $id_member) = $cookie_elements;
        return compact('username', 'expiration', 'hmac', 'id_member', 'scheme');
    }
}

if ( !function_exists('ddm_salt') ){
    /**
     * Get salt to add to hashes to help prevent attacks.
     *
     * @param   string $scheme Authentication scheme
     * @return  string Salt value
     */
    function ddm_salt($scheme = 'auth') {

        $CI =& get_instance();

        $secret_key = $CI->config->item('encryption_key');

        if ( 'auth' == $scheme ) {
            if ( defined('AUTH_KEY') && ('' != AUTH_KEY) ) {
                $secret_key = AUTH_KEY;
            }
            if ( defined('AUTH_SALT') && ('' != AUTH_SALT) ) {
                $salt = AUTH_SALT;
            }
        } else if ( 'secure_auth' == $scheme ) {
            if ( defined('SECURE_AUTH_KEY') && ('' != SECURE_AUTH_KEY) ) {
                $secret_key = SECURE_AUTH_KEY;
            }
            if ( defined('SECURE_AUTH_SALT') && ('' != SECURE_AUTH_SALT) ) {
                $salt = SECURE_AUTH_SALT;
            }
        } else if ( 'logged_in' == $scheme ) {
            if ( defined('LOGGED_IN_KEY') && ('' != LOGGED_IN_KEY) ) {
                $secret_key = LOGGED_IN_KEY;
            }
            if ( defined('LOGGED_IN_SALT') && ('' != LOGGED_IN_SALT) ) {
                $salt = LOGGED_IN_SALT;
            }
        } else if ( 'nonce' == $scheme ) {
            if ( defined('NONCE_KEY') && ('' != NONCE_KEY) ) {
                $secret_key = NONCE_KEY;
            }
            if ( defined('NONCE_SALT') && ('' != NONCE_SALT) ) {
                $salt = NONCE_SALT;
            }
        } else {
            // ensure each auth scheme has its own unique salt
            $salt = hash_hmac('md5', $scheme, $secret_key);
        }

        return $secret_key . $salt;
    }
}

if ( !function_exists('ddm_hash') ){
    /**
     * Get hash of given string.
     *
     * @param   string $data Plain text to hash
     * @return  string Hash of $data
     */
    function ddm_hash($data, $scheme = 'auth') {
        $salt = ddm_salt($scheme);
        return hash_hmac('md5', $data, $salt);
    }
}

/*
|--------------------------------------------------------------------------
| Auth Redirect
|--------------------------------------------------------------------------
*/
if ( !function_exists('auth_redirect') )
{
    /**
     * Checks if a user is logged in, if not it redirects them to the login page.
     *
     * @param none
     * @return none
     */
    function auth_redirect($ajax_request = false)
    {
        $CI =& get_instance();

        $time       = time();
        $login_url  = base_url('login');

        if ( $member_id = ddm_validate_auth_cookie('', 'logged_in') ) {
            $_member   = ddm_get_memberdata_by_id( $member_id );
            if ( ! $_member ) {
                // clear cookie to prevent redirection loops
                ddm_clear_auth_cookie();

                if( $ajax_request ) return false;
                redirect($login_url);
                exit();
            }

            $username_login = $_member->username;
            if ( $staff_id = ddm_validate_auth_cookie_staff('', 'logged_in') ) {
                if ( $staffdata = $CI->Model_Staff->get_staffdata($staff_id) ) {
                    $username_login = $staffdata->username;
                }
            }

            if ( $id_assume = ddm_is_assuming() ) {
                $username_admin = '';
                if ( $_member_assume = ddm_get_memberdata_by_id( $id_assume ) ) {
                    $username_admin = $_member_assume->username;
                }

                if ( $id_assume_staff = $CI->session->userdata( 'assuming_as_staff' ) ) {
                    if ( $staffdata_assume = $CI->Model_Staff->get_staffdata($id_assume_staff) ) {
                        $username_admin = $staffdata_assume->username;
                    }
                }

                $username_login = 'ddm_assume_' . $_member->username .'_'. $username_admin;
            }

            if ( $auth_session = $CI->Model_Auth->get_session_by('username', $username_login) ) {

                if ( ! $validate_auth_session = ddm_validate_auth_session( $auth_session ) ) {
                    ddm_clear_auth_cookie();
                    if( $ajax_request ) return false;
                    redirect($login_url);
                    exit();
                }

                $not_validate = array('not_device_id', 'not_cookie_session');

                if ( in_array($validate_auth_session, $not_validate) ) {
                    ddm_clear_auth_cookie();
                    if( $ajax_request ) return false;
                    redirect($login_url);
                    exit();
                }

                $expired        = $auth_session->expiration;
                $sess_expired   = $time - $auth_session->expiration;
                if ( $sess_expired > 0 && $sess_expired < 600 ) {
                    $expired += 1800;
                    $data_expired = array('expiration' => $expired); 
                    $CI->db->where('session_id', $auth_session->session_id);
                    $CI->db->update(TBL_SESSIONS, $data_expired);
                }

                if ( $time > $expired ) {
                    ddm_clear_auth_session($auth_session);
                    ddm_clear_auth_cookie();
                    if( $ajax_request ) return false;
                    redirect($login_url);
                    exit();
                }

            } else {
                ddm_clear_auth_cookie();
                if( $ajax_request ) return false;
                redirect($login_url);
                exit();
            }
            
            return TRUE;  // The cookie is good so we're done
        }

        // clear cookie to prevent redirection loops
        ddm_clear_auth_cookie();

        if( $ajax_request ) return false;
        redirect($login_url);
        exit();

    }
}

/*
|--------------------------------------------------------------------------
| Auth Cookie
|--------------------------------------------------------------------------
*/
if ( !function_exists('ddm_generate_auth_cookie') ){
    /**
     * Generate authentication cookie contents.
     *
     * @param int       $id_member      (Required)      Member ID
     * @param int       $expiration     (Required)      Cookie expiration in seconds
     * @param string    $scheme         (Optional}      The cookie scheme to use: auth, secure_auth, or logged_in
     * @return string Authentication cookie contents
     */
    function ddm_generate_auth_cookie($id_member, $expiration, $scheme = 'auth', $id_staff = 0 ) {
        $CI =& get_instance();

        $member     = $CI->Model_Auth->get_userdata($id_member);
        $pass_frag  = substr($member->password, 8, 4);

        if ( $id_staff ) {
            $staff  = $CI->Model_Staff->get( $id_staff );
            $pass_frag .= substr( $staff->password, 8, 4 );
        }

        $username   = ddm_encrypt($member->username);
        $key        = ddm_hash($username . $pass_frag . '|' . $expiration, $scheme);
        $hash       = hash_hmac('md5', $username . '|' . $expiration, $key);

        $cookie     = $username . '|' . $expiration . '|' . $hash . '|' . $id_member . '|' . $id_staff;
        return $cookie;
    }
}

if ( !function_exists('ddm_set_auth_cookie') ){
    /**
     * Sets the authentication cookies based Member ID.
     *
     * The $remember parameter increases the time that the cookie will be kept. The
     * default the cookie is kept without remembering is two days. When $remember is
     * set, the cookies will be kept for 14 days or two weeks.
     *
     *
     * @param int $id_member Member ID
     * @param bool $remember Whether to remember the member
     */

    function ddm_set_auth_cookie( $id_member, $remember = false, $secure = '', $id_staff = 0, $time = '')
    {
        $CI =& get_instance();

        if ( $remember ) {
            $expiration = $expire = 28800; // maximum expired value (8 Hour)
        } else {
            $time       = !empty($time) ? $time : time();
            $expiration = $time + config_item('session_timeout');
            $expire = 0;
        }

        if ( '' === $secure ) $secure = is_ssl();

        if ( $secure ) {
            $auth_cookie_name   = SECURE_AUTH_COOKIE;
            $scheme             = 'secure_auth';
        } else {
            $auth_cookie_name   = AUTH_COOKIE;
            $scheme             = 'auth';
        }

        $auth_cookie            = ddm_generate_auth_cookie($id_member, $expiration, $scheme, $id_staff);
        $logged_in_cookie       = ddm_generate_auth_cookie($id_member, $expiration, 'logged_in', $id_staff);

        if(preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', base_url(), $regs)){
            $cookie_domain      = '.' . $regs['domain'];
        }else{
            $cookie_domain      = str_replace(array('http://', 'https://', 'www.'), '', base_url());
            $cookie_domain      = '.' . str_replace('/', '', $cookie_domain);
        }

        $cookie = array(
            'name'   => $auth_cookie_name,
            'value'  => $auth_cookie,
            'expire' => $expire,
            'path'   => '/',
            'domain' => $cookie_domain,
            'secure' => false
        );

        $CI->input->set_cookie($cookie);

        unset($cookie);

        $cookie = array(
            'name'   => LOGGED_IN_COOKIE,
            'value'  => $logged_in_cookie,
            'expire' => $expire,
            'path'   => '/',
            'domain' => $cookie_domain,
            'secure' => false
        );

        $CI->input->set_cookie($cookie);
    }
}

if ( !function_exists('ddm_clear_auth_cookie') ){
    /**
     * Removes all of the cookies associated with authentication.
     *
     * @since 2.5
     */
    function ddm_clear_auth_cookie()
    {
        $CI =& get_instance();
        $logged = false;

        if(preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', base_url(), $regs))
            $cookie_domain  = '.'.$regs['domain'];
        else{
            $cookie_domain  = str_replace(array('http://', 'https://', 'www.'), '', base_url());
            $cookie_domain  = '.'.str_replace('/', '', $cookie_domain);
        }

        $id_member  = ddm_get_current_member_id();

        if ( !$id_member ){
            if ($id = ddm_isset($_COOKIE['logged_in_'.md5('nonssl')], false, true)){
                if (ddm_isset($CI->session->userdata('member_logged_in')) != "") $logged = true;
            }
        }else{
            $logged = true;
        }

        if( $logged ) {

            $CI =& get_instance();

            $member     = ddm_get_current_member();
            $name       = 'last_login_'.strtolower(date('F'));
            $cookie     = array(
                'name'      => md5($name),
                'value'     => $id_member . '-'.time(),
                'expire'    => time()+60*60*24*30,
                'path'      => '/',
                'domain'    => $cookie_domain,
                'secure'    => false
            );

            $CI->input->set_cookie($cookie);
        }

        setcookie(AUTH_COOKIE, ' ', time() - 31536000, '/', $cookie_domain);
        setcookie(SECURE_AUTH_COOKIE, ' ', time() - 31536000, '/', $cookie_domain);
        setcookie(LOGGED_IN_COOKIE, ' ', time() - 31536000, '/', $cookie_domain);

        // Old cookies
        setcookie(AUTH_COOKIE, ' ', time() - 31536000, '/', $cookie_domain);
        setcookie(SECURE_AUTH_COOKIE, ' ', time() - 31536000, '/', $cookie_domain);

        // Even older cookies
        setcookie(MEMBER_COOKIE, ' ', time() - 31536000, '/', $cookie_domain);
        setcookie(PASS_COOKIE, ' ', time() - 31536000, '/', $cookie_domain);

        // Logged in unsecure
        setcookie('logged_in_'.md5('nonssl'), ' ', time() - 31536000, '/', $cookie_domain);
    }
}

/**
 * Assume as member
 * @author  Yuda
 */
if ( !function_exists('ddm_assume') )
{
    function ddm_assume( $user_id ) {
        $CI =& get_instance();

        if ( ! $current_user = ddm_get_current_member() )
            return;

        if ( ! $user = ddm_get_memberdata_by_id( $user_id ) )
            return;

        $CI->session->set_userdata( 'assuming', $current_user->id );

        if ( $staff = ddm_get_current_staff() ){
            $current_user = $staff;
            $CI->session->set_userdata( 'assuming_as_staff', $staff->id );
        }

        ddm_set_auth_cookie( $user->id );
        ddm_auth_session_assume( $user, $current_user );

        ddm_log( 'ASSUME', $user->id, maybe_serialize(array( 'cookie' => $_COOKIE, 'member' => $user, 'admin_user' => $current_user, 'ip' => ddm_get_current_ip() )));

        $CI->load->helper('shop_helper');
        $CI->cart->destroy();
        remove_code_discount();
        remove_code_seller();

        redirect( 'dashboard' );
    }
}

/**
 * Revert from assuming
 * @author  Yuda
 */
if ( !function_exists('ddm_revert') )
{
    function ddm_revert() {
        $CI =& get_instance();

        $id_member = 0;
        if ( $current_user = ddm_get_current_member() ) {
            $id_member = $current_user->id;
        }
        $time = time();

        ddm_auth_session_revert($time);
        ddm_clear_auth_cookie();

        $CI->load->helper('shop_helper');
        $CI->cart->destroy();
        remove_code_discount();
        remove_code_seller();

        if ( $id = ddm_is_assuming() ) {
            $id_assume      = $id; 
            $type_assume    = 'admin';
            $CI->session->unset_userdata( 'assuming' );

            if ( $id_staff = $CI->session->userdata( 'assuming_as_staff' ) ){
                $id_assume      = $id_staff;
                if ( $id_staff > 1 ) { $type_assume = 'staff'; } 
                $CI->session->unset_userdata( 'assuming_as_staff' );
            }

            ddm_set_auth_cookie( $id, false, '', $id_staff, $time );
            ddm_log( 'REVERT', $id_member, maybe_serialize(array( 'cookie' => $_COOKIE, 'id_assume' => $id_assume, 'type_assume' => $type_assume, 'ip' => ddm_get_current_ip() )));
            redirect( 'member/lists' );
        }

        redirect();
    }
}

/**
 * Returns member is assuming
 * @author  Yuda
 */
if ( !function_exists('ddm_is_assuming') )
{
    function ddm_is_assuming() {
        $CI =& get_instance();
        return $CI->session->userdata( 'assuming' );
    }
}

/*
|--------------------------------------------------------------------------
| Validate Auth Cookie
|--------------------------------------------------------------------------
*/
if ( !function_exists('ddm_validate_auth_cookie') ){
    /**
     * Validates authentication cookie.
     *
     * The checks include making sure that the authentication cookie is set and
     * pulling in the contents (if $cookie is not used).
     *
     * Makes sure the cookie is not expired. Verifies the hash in cookie is what is
     * should be and compares the two.
     *
     * @param string $cookie Optional. If used, will validate contents instead of cookie's
     * @param string $scheme Optional. The cookie scheme to use: auth, secure_auth, or logged_in
     * @return bool|int False if invalid cookie, Member ID if valid.
     */
    function ddm_validate_auth_cookie( $cookie = '', $scheme = '', $get_staff = false ) {
        if ( !$cookie_elements = ddm_parse_auth_cookie($cookie, $scheme) )
            return false;

        extract($cookie_elements, EXTR_OVERWRITE);

        $expired = $expiration;

        if ( $_SERVER['REQUEST_METHOD'] == 'POST' ){
            $expired += 1800;
        }

        if ( $expired < time() )
            return false;

        $CI =& get_instance();

        $username   = ddm_decrypt($username);
        $member     = $CI->Model_Auth->get_user_by('login', $username);

        if ( !$member || empty($member)) return false;

        $pass_frag = substr($member->password, 8, 4);
        
        if ( ! empty( $id_staff ) ) {
            $staff = $CI->Model_Staff->get( $id_staff );
            $pass_frag .= substr( $staff->password, 8, 4 );
        }

        $user_encrypt   = ddm_encrypt($username);
        $key            = ddm_hash($user_encrypt . $pass_frag . '|' . $expiration, $scheme);
        $hash           = hash_hmac('md5', $user_encrypt . '|' . $expiration, $key);

        if ( $hmac != $hash )
            return false;

        if ( $get_staff ) {
            return ddm_isset( $id_staff, 0 );
        }

        return $member->id;
    }
}

if ( ! function_exists('ddm_validate_auth_cookie_staff') ) {
    function ddm_validate_auth_cookie_staff( $cookie = '', $scheme = '' ) {
        return ddm_validate_auth_cookie( $cookie, $scheme, TRUE );
    }
}

/*
|--------------------------------------------------------------------------
| Auth Session
|--------------------------------------------------------------------------
*/
if ( !function_exists('ddm_set_auth_session') )
{
    /**
     * Set Auth Session.
     *
     * @author Yuda
     * @param  string  $username        Username Login
     * @param  Object  $memberdata      Data Member
     * @return void
     */
    function ddm_set_auth_session($username='', $memberdata='', $remember = '', $secure = '', $time = '') {
        if ( ! $username ) return false;
        if ( ! $memberdata ) return false;

        if ( $remember ) {
            $expiration = $expire = 28800;
        } else {
            $time       = !empty($time) ? $time : time();
            $expiration = $time + config_item('session_timeout');
            $expire = 0;
        }

        if ( '' === $secure ) $secure = is_ssl();

        if ( $secure ) {
            $scheme             = 'secure_auth';
        } else {
            $scheme             = 'auth';
        }

        $CI = &get_instance();

        $CI->load->library('user_agent');

        if ($CI->agent->is_browser()) {
            $agent = $CI->agent->browser() . ' ' . $CI->agent->version();
        } elseif ($CI->agent->is_robot()) {
            $agent = $CI->agent->robot();
        } elseif ($CI->agent->is_mobile()) {
            $agent = $CI->agent->mobile();
        } else {
            $agent = 'Unidentified User Agent';
        }

        $user_id            = $memberdata->id;
        $user_agent         = array('agent' => $agent, 'agent_string' => $CI->agent->agent_string());
        $user_data          = array(
            'username'      => $memberdata->username,
            'name'          => $memberdata->name,
            'email'         => $memberdata->email,
            'phone'         => $memberdata->phone,
        );

        $pass_frag  = substr($memberdata->password, 8, 4);
        $id_staff   = isset($memberdata->staff->id) ? isset($memberdata->staff->id) : 0;
        if ( isset($memberdata->staff) ) {
            $user_data['userstaff']     = $memberdata->staff->username;
            $user_data['namestaff']     = $memberdata->staff->name;
            $user_data['emailstaff']    = $memberdata->staff->email;
            $user_data['access']        = $memberdata->staff->access;
            $user_id                    = $memberdata->staff->id;
            $pass_frag                 .= substr( $memberdata->staff->password, 8, 4 );
        }

        $user_encrypt   = ddm_encrypt($memberdata->username);
        $key            = ddm_hash($user_encrypt . $pass_frag . '|' . $expiration, $scheme);
        $session_code   = hash_hmac('md5', $user_encrypt . '|' . $expiration, $key);
        $session_id     = $username .'|'. $user_id;


        // Set Data Auth Session
        $data_session       = array(
            'session_id'    => $session_id,
            'username'      => $username,
            'session'       => $session_code,
            'id_member'     => $memberdata->id,
            'id_staff'      => $id_staff,
            'ip_address'    => ddm_get_current_ip(),
            'platform'      => $CI->agent->platform(),
            'browser'       => $CI->agent->browser() . ' ' . $CI->agent->version(),
            'user_agent'    => json_encode($user_agent),
            'expiration'    => $expiration,
            'otp'           => '',
            'otp_expiration'=> '',
            'user_data'     => serialize($user_data),
            'datecreated'   => date('Y-m-d H:i:s'),
        );

        if ( $auth_session = $CI->Model_Auth->get_session_by('username', $username) ) {
            unset($data_session['session_id']);
            unset($data_session['datecreated']);
            $CI->db->where('username', $username);
            $CI->db->update(TBL_SESSIONS, $data_session);
        } else {
            $CI->db->insert(TBL_SESSIONS, $data_session);
        }

        return true;
    }
}

if ( !function_exists('ddm_auth_session_assume') )
{
    /**
     * Set Auth Session Assume.
     *
     * @author Yuda
     * @param  string  $username        Username Login
     * @param  Object  $admindata       Data Admin
     * @return void
     */
    function ddm_auth_session_assume($memberdata='', $admindata='') {
        if ( ! $memberdata ) return false;
        if ( ! $admindata ) return false;

        $time       = time();
        $expiration = $time + config_item('session_timeout');
        $expire     = 0;
        $secure     = is_ssl();

        if ( $secure ) {
            $scheme             = 'secure_auth';
        } else {
            $scheme             = 'auth';
        }

        $CI = &get_instance();

        $CI->load->library('user_agent');

        if ($CI->agent->is_browser()) {
            $agent = $CI->agent->browser() . ' ' . $CI->agent->version();
        } elseif ($CI->agent->is_robot()) {
            $agent = $CI->agent->robot();
        } elseif ($CI->agent->is_mobile()) {
            $agent = $CI->agent->mobile();
        } else {
            $agent = 'Unidentified User Agent';
        }

        $pass_frag          = substr($memberdata->password, 8, 4);
        $user_agent         = array('agent' => $agent, 'agent_string' => $CI->agent->agent_string());
        $user_data          = array(
            'username'      => $memberdata->username,
            'name'          => $memberdata->name,
            'email'         => $memberdata->email,
            'phone'         => $memberdata->phone,
            'id_admin'      => $admindata->id,
            'user_admin'    => $admindata->username,
            'name_admin'    => $admindata->name,
        );

        $user_encrypt   = ddm_encrypt($memberdata->username);
        $key            = ddm_hash($user_encrypt . $pass_frag . '|' . $expiration, $scheme);
        $session_code   = hash_hmac('md5', $user_encrypt . '|' . $expiration, $key);
        $session_id     = 'ddm_assume_' . $memberdata->username .'|'. $memberdata->id .'|'. $admindata->username .'|'. $admindata->id;
        $username       = 'ddm_assume_' . $memberdata->username .'_'. $admindata->username;

        // Set Data Auth Session
        $data_session       = array(
            'session_id'    => $session_id,
            'username'      => $username,
            'session'       => $session_code,
            'id_member'     => $memberdata->id,
            'id_staff'      => $admindata->id,
            'ip_address'    => ddm_get_current_ip(),
            'platform'      => $CI->agent->platform(),
            'browser'       => $CI->agent->browser() . ' ' . $CI->agent->version(),
            'user_agent'    => json_encode($user_agent),
            'expiration'    => $expiration,
            'otp'           => '',
            'otp_expiration'=> '',
            'user_data'     => serialize($user_data),
            'datecreated'   => date('Y-m-d H:i:s'),
        );

        if ( $auth_session = $CI->Model_Auth->get_session_by('username', $username) ) {
            unset($data_session['session_id']);
            unset($data_session['datecreated']);
            $CI->db->where('username', $username);
            $CI->db->update(TBL_SESSIONS, $data_session);
        } else {
            $CI->db->insert(TBL_SESSIONS, $data_session);
        }

        return true;
    }
}

if ( !function_exists('ddm_auth_session_revert') )
{
    /**
     * Set Auth Session Revert.
     *
     * @author Yuda
     * @param  string  $username        Username Login
     * @param  Object  $memberdata      Data Member
     * @return void
     */
    function ddm_auth_session_revert($time = '') {
        if ( ! $current_user = ddm_get_current_member() ) return false;

        $CI = &get_instance();

        $username_admin     = '';
        $memberdata         = '';

        if ( $id_assume = ddm_is_assuming() ) {
            if ( $_member_assume = ddm_get_memberdata_by_id( $id_assume ) ) {
                $memberdata     = $_member_assume;
                $username_admin = $_member_assume->username;
            }

            if ( $id_assume_staff = $CI->session->userdata( 'assuming_as_staff' ) ) {
                if ( $staffdata_assume = $CI->Model_Staff->get_staffdata($id_assume_staff) ) {
                    $memberdata->staff  = $staffdata_assume;
                    $username_admin     = $staffdata_assume->username;
                }
            }
        }

        if ( ! $memberdata ) return false;

        $username_assume    = 'ddm_assume_' . $current_user->username .'_'. $username_admin;

        $time               = !empty($time) ? $time : time();
        $expiration         = $time + config_item('session_timeout');
        $expire             = 0;

        $secure             = is_ssl();

        if ( $secure ) {
            $scheme         = 'secure_auth';
        } else {
            $scheme         = 'auth';
        }

        $CI->load->library('user_agent');

        if ($CI->agent->is_browser()) {
            $agent = $CI->agent->browser() . ' ' . $CI->agent->version();
        } elseif ($CI->agent->is_robot()) {
            $agent = $CI->agent->robot();
        } elseif ($CI->agent->is_mobile()) {
            $agent = $CI->agent->mobile();
        } else {
            $agent = 'Unidentified User Agent';
        }

        $user_id            = $memberdata->id;
        $user_agent         = array('agent' => $agent, 'agent_string' => $CI->agent->agent_string());
        $user_data          = array(
            'username'      => $memberdata->username,
            'name'          => $memberdata->name,
            'email'         => $memberdata->email,
            'phone'         => $memberdata->phone,
        );

        $pass_frag  = substr($memberdata->password, 8, 4);
        $id_staff   = isset($memberdata->staff->id) ? isset($memberdata->staff->id) : 0;
        if ( isset($memberdata->staff) ) {
            $user_data['userstaff']     = $memberdata->staff->username;
            $user_data['namestaff']     = $memberdata->staff->name;
            $user_data['emailstaff']    = $memberdata->staff->email;
            $user_data['access']        = $memberdata->staff->access;
            $user_id                    = $memberdata->staff->id;
            $pass_frag                 .= substr( $memberdata->staff->password, 8, 4 );
        }

        $user_encrypt   = ddm_encrypt($memberdata->username);
        $key            = ddm_hash($user_encrypt . $pass_frag . '|' . $expiration, $scheme);
        $session_code   = hash_hmac('md5', $user_encrypt . '|' . $expiration, $key);
        $session_id     = $username_admin .'|'. $user_id;

        // Set Data Auth Session
        $data_session       = array(
            'session_id'    => $session_id,
            'username'      => $username_admin,
            'session'       => $session_code,
            'id_member'     => $memberdata->id,
            'id_staff'      => $id_staff,
            'ip_address'    => ddm_get_current_ip(),
            'platform'      => $CI->agent->platform(),
            'browser'       => $CI->agent->browser() . ' ' . $CI->agent->version(),
            'user_agent'    => json_encode($user_agent),
            'expiration'    => $expiration,
            'otp'           => '',
            'otp_expiration'=> '',
            'user_data'     => serialize($user_data),
            'datecreated'   => date('Y-m-d H:i:s'),
        );

        if ( $auth_session = $CI->Model_Auth->get_session_by('username', $username_admin) ) {
            unset($data_session['session_id']);
            unset($data_session['datecreated']);
            $CI->db->where('username', $username_admin);
            $CI->db->update(TBL_SESSIONS, $data_session);
        } else {
            $CI->db->insert(TBL_SESSIONS, $data_session);
        }

        if ( $auth_session_assume = $CI->Model_Auth->get_session_by('username', $username_assume) ) {
            ddm_clear_auth_session($auth_session_assume);
        }

        return true;
    }
}

if ( !function_exists('ddm_clear_auth_session') )
{
    function ddm_clear_auth_session($auth_session) {
        if ( ! $auth_session ) return false;
        $CI = &get_instance();

        // Clear Data Auth Session
        $data               = array(
            'session'       => '',
            // 'ip_address'    => '',
            // 'platform'      => '',
            // 'browser'       => '',
            // 'user_agent'    => '',
            'otp'           => '',
            'expiration'    => 0,
            'otp_expiration'=> 0,
        );

        $CI->db->where('session_id', $auth_session->session_id);
        $CI->db->update(TBL_SESSIONS, $data);
        return true;
    }
}

/*
|--------------------------------------------------------------------------
| Validate Auth Cookie
|--------------------------------------------------------------------------
*/
if ( !function_exists('ddm_check_auth_session') ){
    /**
     * Check authentication session
     *
     * @param int   $id_member Member ID
     * @param time  $time 
     */

    function ddm_check_auth_session( $auth_session, $time = '') {
        if ( ! $auth_session ) return false;

        $time       = !empty($time) ? $time : time();
        if ( $time > $auth_session->expiration ) return false;
        // return $auth_session;

        $CI =& get_instance();

        $CI->load->library('user_agent');

        $platform       = $CI->agent->platform();
        $browser        = $CI->agent->browser() . ' ' . $CI->agent->version();
        $ip_address     = ddm_get_current_ip();

        $device_id      = strtolower($auth_session->ip_address) .'|'. strtolower($auth_session->platform) .'|'. strtolower($auth_session->browser);
        $device_login   = strtolower($ip_address) .'|'. strtolower($platform) .'|'. strtolower($browser);

        if ( $device_id == $device_login ) {
            return false;
        }

        return $auth_session;

    }
}

if ( !function_exists('ddm_validate_auth_session') ){
    /**
     * Check authentication session
     *
     * @param int $id_member Member ID
     * @param bool $remember Whether to remember the member
     */

    function ddm_validate_auth_session( $auth_session) {
        if ( ! $auth_session ) return false;

        $CI =& get_instance();

        $CI->load->library('user_agent');

        $platform       = $CI->agent->platform();
        $browser        = $CI->agent->browser() . ' ' . $CI->agent->version();
        $ip_address     = ddm_get_current_ip();

        $device_id      = strtolower($auth_session->ip_address) .'|'. strtolower($auth_session->platform) .'|'. strtolower($auth_session->browser);
        $device_login   = strtolower($ip_address) .'|'. strtolower($platform) .'|'. strtolower($browser);

        if ( $device_id != $device_login ) {
            return 'not_device_id';
        }

        if ( $cookie_elements = ddm_parse_auth_cookie() ) {
            extract($cookie_elements, EXTR_OVERWRITE);
            $cookie_session     = isset($cookie_elements['hmac']) ? $cookie_elements['hmac'] : '';
            $expiration         = isset($cookie_elements['expiration']) ? $cookie_elements['expiration'] : '';
            if ( $auth_session->session != $cookie_session ) {
                return 'not_cookie_session';
            }
        }

        return $auth_session;

    }
}

/*
|--------------------------------------------------------------------------
| Logout Function
|--------------------------------------------------------------------------
*/
if ( !function_exists('ddm_logout') ) {
    /**
     * Logout
     * @author  Yuda
     */
    function ddm_logout()
    {
        $CI =& get_instance();

        $username   = '';
        if ( $current_member = ddm_get_current_member() ) {
            $username   = $current_member->username;
        }
        if ( $staff = ddm_get_current_staff() ) {
            $username   = $staff->username;
        }

        if ( $username ) {
            if ( $auth_session = $CI->Model_Auth->get_session_by('username', $username) ) {
                ddm_clear_auth_session($auth_session);
            }
        }

        if ( $CI->session->userdata( 'member_logged_in' ) ) {
            $CI->session->unset_userdata( 'member_logged_in' );
            $CI->session->sess_destroy();
        }        

        if ( $assuming = ddm_is_assuming() ) {
            $CI->session->unset_userdata( 'assuming' );
            if ( $CI->session->userdata( 'assuming_as_staff' ) ){
                $CI->session->unset_userdata( 'assuming_as_staff' );
            }
        }

        $CI->load->helper('shop_helper');
        $CI->cart->destroy();
        remove_code_discount();
        remove_code_seller();

        ddm_clear_auth_cookie();
    }
}