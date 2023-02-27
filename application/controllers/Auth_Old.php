<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Auth Controller.
 * 
 * @class     Auth
 * @author    Yuda
 * @version   1.0.0
 */
class Auth extends DDM_Controller {
    /**
	 * Constructor.
	 */
    function __construct()
    {       
        parent::__construct();
    }
    
    // ------------------------------------------------------------------------------------------------
    // Login Process
    // ------------------------------------------------------------------------------------------------
    
    /**
	 * Login function.
	 */
    public function login()
    {
        if( is_member_logged_in() ){
            if ( ! $member = ddm_get_current_member() ) {
               ddm_logout();
               redirect('login', 'location'); die();
            }
            redirect('dashboard', 'location'); die();
        }
        
        if ( $forget = $this->input->get( 'forget' ) ) {
			if ( ! empty( $forget['notfound'] ) ) {
				$data['error_msg'] = 'Username belum terdaftar!';
			} elseif ( ! empty( $forget['wrongemail'] ) ) {
				$data['error_msg'] = 'Email belum terdaftar!';
			} elseif ( ! empty( $forget['fail'] ) ) {
				$data['error_msg'] = 'Reset password gagal! Silakan ulangi lagi.';
			} elseif ( ! empty( $forget['success'] ) ) {
				$data['msg'] = 'Reset password sudah dikirimkan ke email Anda';
			}
		}
        
        $headstyles             = ddm_headstyles(array(
            // Default CSS Plugin
            ASSET_PATH . 'auth/css/login.css?ver=' . CSS_VER_AUTH,
        ));
        
        $loadscripts            = ddm_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js',
            // Always placed at bottom
            ASSET_PATH . 'auth/js/login.js?ver=' . JS_VER_AUTH,
        ));
        
        $scripts_init           = '';
        $scripts_add            = '';
        
        $data['title']          = TITLE . 'Member Login';
        $data['v']              = mt_rand(100000, 999999);
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $this->load->view(VIEW_AUTH . 'login_template', $data);
    }
    
    /**
	 * Logout member function.
     * @return URL redirect page
	 */
    public function logout()
    {        
        ddm_logout();
        redirect( base_url(), 'refresh' );
    }

    /**
     * Validate Login function.
     * @return AJAX String
     */
    public function validate()
    {
        // This is for AJAX request
        if ( ! $this->input->is_ajax_request() ) {
            // exit('No direct script access allowed');
            redirect(base_url('login?page[error]=1'), 'refresh');
        } 

        // Set credential variable param
        $post_username  = $this->input->post("username");
        $post_password  = $this->input->post("password");
        $post_remember  = $this->input->post("remember");

        $username       = ddm_isset( $post_username, '' );
        $password       = ddm_isset( $post_password, '' );
        $remember       = ddm_isset( $post_remember, '' );

        $post_callback  = $this->input->post("callback");
        $callback       = ddm_isset( $post_callback, '' );

        // Set Credential for login
        $credentials['username']    = $username;
        $credentials['password']    = strtolower($password);
        $credentials['remember']    = $remember;

        $status     = 'Failed';
        $response   = array(
            'success'   => false,
            'msg'       => 'Failed',
        );

        // Sign On member
        $time           = time();
        $membersignon   = $this->Model_Auth->signon($credentials, $time);

        // Response of signon member
        if ( $membersignon == 'not_active' ){
            $status             = $membersignon;
            $response['msg']    = 'Not Active';
        } elseif ( $membersignon == 'banned' ){
            $status             = $membersignon;
            $response['msg']    = 'Banned';
        } elseif ( $membersignon == 'deleted' ){
            $status             = $membersignon;
            $response['msg']    = 'Deleted';
        } elseif ( $membersignon == 'login_other_device' ){
            if ( $auth_session = $this->Model_Auth->get_session_by('username', $credentials['username']) ) {
                $status             = $membersignon;
                $response['msg']    = 'Login Other Device';
                $response['auth_session'] = $auth_session;
                // Set session
                $this->session->set_userdata('member_login_other_device', $auth_session);
            }
        } elseif ( $membersignon ) {
            $status         = 'Success';
            $member         = $this->ddm_member->member($membersignon->id);
            $last_activity  = date('Y-m-d H:i:s', time() );

            $login_update   = array( 'last_login' => $last_activity );
            $this->Model_Auth->update_data($member->id, $login_update);

            // Set session data
            $session_data   = array(
                'id'            => $member->id,
                'username'      => $member->username,
                'name'          => $member->name,
                'email'         => $member->email,
                'last_login'    => $member->last_login
            );

            // Set session
            $this->session->set_userdata('member_logged_in', $session_data);

            // Set cookie domain
            $cookie_domain  = str_replace(array('http://', 'https://', 'www.'), '', base_url());
            $cookie_domain  = '.' . str_replace('/', '', $cookie_domain);
            $expire         = 0;
            // Set cookie data
            $cookie         = array(
                'name'      => 'logged_in_'.md5('nonssl'),
                'value'     => $member->id,
                'expire'    => $expire,
                'domain'    => $cookie_domain,
                'path'      => '/',
                'secure'    => false,
            );
            // set cookie
            setcookie($cookie['name'], $cookie['value'],$cookie['expire'],$cookie['path'],$cookie['domain'],$cookie['secure']);

            if ( $assuming = ddm_is_assuming() ) {
                $this->session->unset_userdata( 'assuming' );
                if ( $this->session->userdata( 'assuming_as_staff' ) ){
                    $this->session->unset_userdata( 'assuming_as_staff' );
                }
            }

            // Save Auth Session
            ddm_set_auth_session($username, $membersignon, $remember, '', $time);

            // log logged in user
            ddm_log( 'LOGGED_IN', $username, maybe_serialize( array( 'creds' => $credentials, 'membersignon' => $membersignon, 'ip' => ddm_get_current_ip(), 'cookie' => $_COOKIE ) ) );

            $this->load->helper('shop_helper');
            $this->cart->destroy();
            remove_code_discount();
            remove_code_seller();

            $response['success'] = true;
            $response['msg']     = base_url('dashboard');
        }

        ddm_log_action( 'LOGGED_IN', $status, $username, json_encode(array('cookie'=>$_COOKIE, 'membersignon'=>$membersignon, 'credentials'=>$credentials )) );

        // print response in JSON format
        die( json_encode( $response ) );
    }

    /**
     * Forget Password member function.
     * @return AJAX String
     */
    public function forgetpassword()
    {
        // This is for AJAX request
        if ( ! $this->input->is_ajax_request() ) exit('No direct script access allowed');

        // Set credential variable param
        // $post_id        = $this->input->post("id");
        $post_username      = $this->input->post("forget_username");
        $post_email         = $this->input->post("email");
        $post_captcha       = $this->input->post("capt_forget");
        $post_capt_ses      = $this->input->post("capt_ses_forget");

        $username           = ddm_isset( $post_username, '' );
        $email              = ddm_isset( $post_email, '' );
        $captcha            = ddm_isset( $post_captcha, '' );
        $capt_ses           = ddm_isset( $post_capt_ses, '' );


        $response           = array(
            'url'           => base_url('capt'),
            'v'             => mt_rand(100000, 999999)
        );

        if ( !$username || !$email ) {
            $response['success'] = false;
            $response['msg']     = 'validate';
            die(json_encode($response));
        }

        if ( $captcha !== $capt_ses ) {
            $capt = $this->session->userdata('ddm_capt');
            if( $captcha !== $capt ) {
                $response['success'] = false;
                $response['msg']     = 'captcha';
                die(json_encode($response));
            }
        }

        $memberdata = $this->Model_Auth->get_member_by('login', $username);
        if ( ! $memberdata ) {
            $response['success'] = false;
            $response['msg']     = 'not_found';
            die(json_encode($response));
        }

        if( $memberdata && $memberdata->id == 1 ) {
            $response['success'] = false;
            $response['msg']     = 'failed';
            die(json_encode($response));
        }

        if( $memberdata && $memberdata->status == 0 ) {
            $response['success'] = false;
            $response['msg']     = 'not_active';
            die(json_encode($response));
        }

        if( $memberdata && $memberdata->status == 2 ) {
            $response['success'] = false;
            $response['msg']     = 'banned';
            die(json_encode($response));
        }

        if( $memberdata && $memberdata->status == 3 ) {
            $response['success'] = false;
            $response['msg']     = 'deleted';
            die(json_encode($response));
        }

        // check blacklist
        if ( ddm_is_username_blacklisted( $memberdata->username ) ) {
            $response['success'] = false;
            $response['msg']     = 'banned';
            die(json_encode($response));
        }

        if ( ddm_is_email_blacklisted( $memberdata->email ) ) {
            $response['success'] = false;
            $response['msg']     = 'banned';
            die(json_encode($response));
        }

        if ( $memberdata && $memberdata->email !== $email ) {
            $response['success'] = false;
            $response['msg']     = 'email_not_match';
            die(json_encode($response));
        }

        $pass               = strtolower(random_string( 'alnum', 8 ));
        $password           = password_hash($pass, PASSWORD_BCRYPT);
        $passdata           = array(
            'password'      => $password,
            'password_pin'  => $password,
            'datemodified'  => date('Y-m-d H:i:S')
        );

        if( $save_pass      = $this->Model_Auth->update_data($memberdata->id, $passdata) ){
            ddm_log_action( 'FORGET_PASSWORD', 'SUCCESS', $memberdata->username, json_encode(array('cookie'=>$_COOKIE, 'status'=>'SUCCESS', 'password'=>$pass )) );

            $type_password      = 'Login dan Transfer PIN/Produk';
            $data_notif         = array(
                'password'      => $pass,
                'type_password' => $type_password
            );

            // Send Notif Email
            $this->ddm_email->send_email_forget_password( $memberdata, $data_notif );
            // Send Notif WA
            // $this->ddm_wa->send_wa_reset_password_by_member( $memberdata, $data_wa );

            // Set JSON data
            $response['success'] = true;
            $response['msg']     = 'Reset password Username <strong>'.$username.'</strong> berhasil. Password Baru akan di kirim via Email akun anda. ';
        }else{
            // Set JSON data
            $response['success'] = false;
            $response['msg']     = 'failed';
        }
        // print response in JSON format
        die( json_encode( $response ) );
    }
    
    // ------------------------------------------------------------------------------------------------
    
}

/* End of file Auth.php */
/* Location: ./app/controllers/Auth.php */