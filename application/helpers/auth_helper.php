<?php if (!defined("BASEPATH")) exit("No direct script access allowed");

// -------------------------------------------------------------------------
// Auth General functions
// -------------------------------------------------------------------------

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

// -------------------------------------------------------------------------
// Auth Member functions
// -------------------------------------------------------------------------

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

// -------------------------------------------------------------------------
// Auth Logout functions
// -------------------------------------------------------------------------

if ( !function_exists('ddm_logout') ) {
    /**
     * Logout
     * @author  Yuda
     */
    function ddm_logout()
    {
        $CI =& get_instance();

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

        ddm_clear_auth_cookie();
    }
}

// -------------------------------------------------------------------------
// Auth Redirect functions
// -------------------------------------------------------------------------

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
            return TRUE;  // The cookie is good so we're done
        }

        // clear cookie to prevent redirection loops
        ddm_clear_auth_cookie();

        if( $ajax_request ) return false;
        redirect($login_url);
        exit();

    }
}

// -------------------------------------------------------------------------
// Auth Cookie functions
// -------------------------------------------------------------------------

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
            $expiration = $expire = 86400; // maximum expired value (8 Hour)
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

// -------------------------------------------------------------------------
// Auth Assume functions
// -------------------------------------------------------------------------

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

        ddm_clear_auth_cookie();

        $CI->load->helper('shop_helper');
        $CI->cart->destroy();

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