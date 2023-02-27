<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Staff Controller.
 * 
 * @class     Staff
 * @version   1.0.0
 */
class Staff extends Admin_Controller {
	
	function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        return $this->manage();
    }

    function edit($id_staff)
    {
        if ($id_staff) {
            $id_staff     = ddm_decrypt($id_staff);
            return $this->formstaff($id_staff);
        } else {
            redirect(base_url('staff'), 'refresh');
        }
    }
	
	function manage() {
		auth_redirect();

        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);

        $headstyles             = ddm_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN
        ));
        $loadscripts            = ddm_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = ddm_scripts_init(array(
            'InputMask.init();',
            'Staff.init();',
            'TableAjaxStaffList.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_setting_staff');
        $data['title_page']     = '<i class="fa fa-user-plus mr-1"></i> ' . lang('menu_setting_staff');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['packages']       = ddm_packages();
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'staff/manage';

        $this->load->view(VIEW_BACK . 'template_index', $data);
	}
	
	function managelist() {
		$current_member     = ddm_get_current_member();
        
        $condition          = 'WHERE %id% > 0 AND %status% = 1';
        $order_by           = '';
        $iTotalRecords      = 0;
        
        $iDisplayLength     = intval($_REQUEST['iDisplayLength']); 
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);
        $sEcho              = intval($_REQUEST['sEcho']);
		$sAction            = isset($_REQUEST['sAction']) ? $_REQUEST['sAction'] : '';
        
        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);
        
        $limit              = ( $iDisplayLength == '-1' ? 0 : $iDisplayLength );
        $offset             = $iDisplayStart;
        
        $s_name           	= $this->input->post('search_name');
        $s_name           	= ddm_isset($s_name, '');
        $s_username         = $this->input->post('search_username');
        $s_username         = ddm_isset($s_username, '');
        $s_date_min         = $this->input->post('search_datecreated_min');
        $s_date_min         = ddm_isset($s_date_min, '');
        $s_date_max         = $this->input->post('search_datecreated_max');
        $s_date_max         = ddm_isset($s_date_max, '');
        
        if( !empty($s_name) )     { $condition .= str_replace('%s%', $s_name, ' AND name LIKE "%%s%%"'); }
        if( !empty($s_username) )     { $condition .= str_replace('%s%', $s_username, ' AND username LIKE "%%s%%"'); }
		
		if ( !empty($s_date_min) )	{ $condition .= ' AND %datecreated% >= '.strtotime($s_date_min).''; }
        if ( !empty($s_date_max) )	{ $condition .= ' AND %datecreated% <= '.strtotime($s_date_max).''; } 
        
        if( $column == 1 )      { $order_by .= '%name% ' . $sort; }
        elseif( $column == 2 )  { $order_by .= '%username% ' . $sort; }
        elseif( $column == 3 )  { $order_by .= '%access% ' . $sort; }
        elseif( $column == 4 )  { $order_by .= '%datecreated% ' . $sort; }
        
        $staffs = $this->Model_Staff->get_all_staff( $limit, $offset, $condition, $order_by );
        
        $records            = array();
        $records["aaData"]  = array(); 
        
        if( !empty($staffs) ){
            $iTotalRecords  = ddm_get_last_found_rows();
            $i = $offset + 1;
			
            foreach ($staffs as $staff) {
                $id_staff    = ddm_encrypt($staff->id);
                $access     = '<b>Semua Fitur</b>';
                if ($staff->access == 'partial') {
                    $access = array();
                    if ($staff->role) {
                        $access = unserialize($staff->role);
                    }
                    if (is_array($access)) {
                        array_walk($access, function (&$val) {
                            $config = config_item('staff_access_text');
                            $val = $config[$val];
                        });
                        $access = implode('<br />', $access);
                    }
                } else {
                    $role = array();
                    if ($staff->role) {
                        $role = unserialize($staff->role);
                    }
                    $config = config_item('staff_access_text');
                    foreach (array(STAFF_ACCESS8, STAFF_ACCESS9) as $val) {
                        if (empty($role) || !in_array($val, $role))
                            $access .= '<br />Tidak bisa akses ' . $config[$val];
                    }
                }

                $reset_pass = '<a href="' . base_url('staff/getstaff/' . $id_staff) . '" class="btn btn-sm btn-block btn-warning grid-reset-password-staff"><i class="fa fa-key"></i> Reset Password</a>';
                $edit = '<a href="' . base_url('staff/edit/' . $id_staff) . '" class="btn btn-sm btn-block btn-default"><i class="fa fa-edit"></i> Edit</a>';
                $del = '<a href="' . base_url('staff/del/' . $id_staff) . '" class="btn btn-sm btn-block btn-outline-danger delstaff"><i class="fa fa-close"></i> Hapus</a>';

                $records["aaData"][]    = array(
                    '<center>' . $i++ . '</center>',
                    '<div style="min-width:150px"><center>' . $staff->username . '</center></div>',
                    '<div style="min-width:150px"><center>' . $staff->name . '</center></div>',
                    $access,
                    '<center>' . $edit . $reset_pass . $del . '</center>',
                );
            }   
        }
        
        $end                = $iDisplayStart + $iDisplayLength;
        $end                = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;
        
        echo json_encode($records);
	}
    
    function formstaff($id_staff = 0)
    {
        auth_redirect();

        $this->form_validation->set_rules( 'staff_username',			'Username', 'trim|required' );
		if ( !$id_staff ) {
			$this->form_validation->set_rules( 'staff_password',			'Password', 'trim|required' );
			$this->form_validation->set_rules( 'staff_password_retype',		'Password Re-Type', 'trim|required|matches[staff_password]' );
		}
		$this->form_validation->set_rules( 'staff_name',				'Nama', 'trim|required' );
		$this->form_validation->set_rules( 'staff_email',				'Email', 'trim' );
		$this->form_validation->set_rules( 'staff_phone',				'Phone', 'trim' );

        if(!$isCheckUsernameStaff = $this->checkusernamestaff()){
            $data['message'] = 'Username anda salah atau sudah terdaftar. Silahkan cek username anda kembali.';
            die(json_encode($data));
        }

        if(!$isCheckEmailStaff = $this->checkemail($id_staff)){
            $data['message'] = 'Email anda salah. Silahkan cek email anda kembali.';
            die(json_encode($data));
        }

        if(!$isCheckPhoneStaff = $this->checkphone($id_staff)){
            $data['message'] = 'No. Phone anda salah. Silahkan cek no phone anda kembali.';
            die(json_encode($data));
        }

        $staff_data             = '';
        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);

        $headstyles             = ddm_headstyles(array(
            // Default CSS Plugin
        ));
        $loadscripts            = ddm_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'form-validation.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = ddm_scripts_init(array(
            'InputMask.init();',
            'Staff.init();',
            'FV_Staff.init();'
        ));
        $scripts_add            = '';
        $menu_title             = lang('add') . ' ' . lang('menu_setting_staff');

        if ($id_staff) {
            $staff_data         = $this->Model_Staff->get($id_staff);
            if ($staff_data) {
                $menu_title     = lang('edit') . ' ' . lang('menu_setting_staff');
            }
        }

        $data['title']          = TITLE . $menu_title;
        $data['title_page']     = '<i class="fa fa-user-plus mr-1"></i> ' . $menu_title;
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['staff']          = $staff_data;
        $data['config']         = config_item('staff_access_text');
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'staff/add';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }
	
	function add( $id_staff = 0 ) {
		$this->form_validation->set_rules( 'staff_username',			'Username', 'trim|required' );
		if ( !$id_staff ) {
			$this->form_validation->set_rules( 'staff_password',			'Password', 'trim|required' );
			$this->form_validation->set_rules( 'staff_password_retype',		'Password Re-Type', 'trim|required|matches[staff_password]' );
		}
		$this->form_validation->set_rules( 'staff_name',				'Nama', 'trim|required' );
		$this->form_validation->set_rules( 'staff_email',				'Email', 'trim' );
		$this->form_validation->set_rules( 'staff_phone',				'Phone', 'trim' );
		
		if ( $post = $this->input->post() ) {
			if ( ! $this->input->is_ajax_request() ) {
			   die( 'No direct script access allowed' );
			}
			
			if ( $this->form_validation->run() == FALSE ) {
				$response = array( 'success' => false, 'msg' => validation_errors() );
				die( json_encode( $response ) );
			}

            if(!$isCheckUsernameStaff = $this->checkusernamestaff()){
                $data['message'] = 'Username anda salah atau sudah terdaftar. Silahkan cek username anda kembali.';
                die(json_encode($data));
            }
    
            if(!$isCheckEmailStaff = $this->checkemail($id_staff)){
                $data['message'] = 'Email anda salah. Silahkan cek email anda kembali.';
                die(json_encode($data));
            }
    
            if(!$isCheckPhoneStaff = $this->checkphone($id_staff)){
                $data['message'] = 'No. Phone anda salah. Silahkan cek no phone anda kembali.';
                die(json_encode($data));
            }
			
			$access = $this->input->post( 'staff_access' );
			$role = $this->input->post( 'staff_access_partial' );
			if ( $access == 'all' ) {
				$role = $this->input->post( 'staff_access_all' );
				$role = $role ? $role : 0;
			} else {
				if ( !$role ) {
					$msg 		= $id_staff ? 'Edit staff tidak berhasil.' : 'Pendaftaran staff tidak berhasil.';
					$response 	= array( 'success' => false, 'msg' => $msg . ' Fitur Harus di pilih !' );
					die( json_encode( $response ) );
				}
			}

			$datetime 			= date( 'Y-m-d H:i:s' );
			// save staff
			$staff = array(
				'username' 		=> $this->input->post( 'staff_username' ),
				'name' 			=> $this->input->post( 'staff_name' ),
				'email' 		=> $this->input->post( 'staff_email' ),
				'access'		=> $access,
				'role'			=> $role,
				'datecreated'	=> $datetime,
				'datemodified'	=> $datetime
			);
			
			if ( $id_staff ) {
				unset($staff['datecreated']);
				if ( $this->Model_Staff->update( $id_staff, $staff ) ) {
					$response = array( 'success' => true );
					die( json_encode( $response ) );
				}
			} else {
				$staff['password'] = $this->input->post( 'staff_password' );
				if ( $insert_id = $this->Model_Staff->insert( $staff ) ) {
					$response = array( 'success' => true );
					die( json_encode( $response ) );
				}
			}
			
			$response = array( 'success' => false, 'msg' => 'Pendaftaran staff tidak berhasil!' );
			die( json_encode( $response ) );
		}
		
		$current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);
		$data['title']          = TITLE . 'Tambah Staff';
        $data['member']         = $this->member;
        $data['is_admin']       = $is_admin;
        $data['main_content']   = 'staff/add';
		$data['config']			= config_item( 'staff_access_text' );
		
		if ( $id_staff && $staff = $this->Model_Staff->get( $id_staff ) ) {
			$data['title']		= TITLE . 'Edit Staff';
			$data['staff']		= $staff;
		}
		
		$this->load->view( VIEW_BACK . 'template', $data );
	}
    
    function savestaff($id_staff = 0)
    {
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('staff'), 'refresh');
        }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $ddm_token          = $this->security->get_csrf_hash();
        $data               = array('status' => 'error', 'token' => $ddm_token, 'message' => 'Simpan data staff tidak berhasil');

        // set variables
        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);
        $datetime           = date('Y-m-d H:i:s');

        // POST Input Form
        $username           = trim($this->input->post('staff_username'));
        $username           = ddm_isset($username, '', '', true);
        $password           = $this->input->post('staff_password');
        $password           = ddm_isset($password, '', '', true);
        $password_confirm   = $this->input->post('staff_password_confirm');
        $password_confirm   = ddm_isset($password_confirm, '', '', true);
        $name               = $this->input->post('staff_name');
        $name               = ddm_isset($name, '', '', true);
        $phone              = $this->input->post('staff_phone');
        $phone              = ddm_isset($phone, '', '', true);
        $email              = $this->input->post('staff_email');
        $email              = ddm_isset($email, '', '', true);

        if (!$id_staff) {
            $this->form_validation->set_rules('staff_username',             'Username', 'trim|required');
            $this->form_validation->set_rules('staff_password',             'Password', 'trim|required');
            $this->form_validation->set_rules('staff_password_confirm',     'Password Confirm', 'trim|required|matches[staff_password]');
        }
        $this->form_validation->set_rules('staff_name',     'Nama', 'trim|required');
        $this->form_validation->set_rules('staff_email',    'Email', 'trim|required');
        $this->form_validation->set_rules('staff_phone',    'Phone', 'trim|required');

        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE) {
            $data['message'] = 'Simpan data staff tidak berhasil disimpan. ' . validation_errors();
            die(json_encode($data));
        }

        if(!$isCheckUsernameStaff = $this->checkusernamestaff()){
            $data['message'] = 'Username anda salah atau sudah terdaftar. Silahkan cek username anda kembali.';
            die(json_encode($data));
        }

        if(!$isCheckEmailStaff = $this->checkemail($id_staff)){
            $data['message'] = 'Email anda salah. Silahkan cek email anda kembali.';
            die(json_encode($data));
        }

        if(!$isCheckPhoneStaff = $this->checkphone($id_staff)){
            $data['message'] = 'No. Phone anda salah. Silahkan cek no phone anda kembali.';
            die(json_encode($data));
        }

        $access         = $this->input->post('staff_access');
        $access         = ddm_isset($access, '', '', true);
        if ($access == 'all') {
            $role       = $this->input->post('staff_access_all');
            $role       = ddm_isset($role, '', '', false, false);
            $role       = $role ? $role : 0;
        } else {
            $role       = $this->input->post('staff_access_partial');
            $role       = ddm_isset($role, '', '', false, false);
            if (!$role) {
                $msg         = $id_staff ? 'Edit staff tidak berhasil.' : 'Pendaftaran staff tidak berhasil.';
                $data['message'] = $msg . ' Fitur Harus di pilih !';
                die(json_encode($data));
            }
        }

        if (substr($phone, 0, 1) != '0') {
            $phone = '0' . $phone;
        }

        // save staff
        $staff = array(
            'username'      => strtolower($username),
            'name'          => strtoupper($name),
            'email'         => strtolower($email),
            'phone'         => $phone,
            'access'        => $access,
            'role'          => $role,
            'datecreated'   => $datetime,
            'datemodified'  => $datetime
        );

        if ($id_staff) {
            $id_staff = ddm_decrypt($id_staff);
            unset($staff['username']);
            unset($staff['datecreated']);
            if ($this->Model_Staff->update($id_staff, $staff)) {
                $data['url']         = base_url('staff');
                $data['status']     = 'success';
                $data['message']     = 'Simpan data Staff berhasil';
            }
        } else {
            $staff['password'] = ddm_password_hash($password);
            if ($insert_id = $this->Model_Staff->insert($staff)) {
                $data['url']         = base_url('staff');
                $data['status']     = 'success';
                $data['message']     = 'Simpan data Staff berhasil';

                $staffdata = $this->Model_Staff->get_staffdata($insert_id);
                $staff_data = isset($staff_data) ? $staff_data : 0;

                if (isset($staff_data)) {
                    $this->ddm_email->send_email_new_staff($staffdata, $password);
                }
            }
        }

        die(json_encode($data));
    }

    /**
     * Check Username function.
     */
    function checkusernamestaff()
    {
        $username = $this->input->post('staff_username');
        $username = ddm_isset($username, '');
        
        if ( !empty($username) ) {
            // if staff with the username exists
            if ( $staff = $this->Model_Staff->get_by( 'username', $username ) ){
                return false;
            }
        }

        return true;
    }

    /**
     * Check Email function.
     */
    function checkemail($id_staff = 0)
    {
        $id     = $id_staff;
        $id     = trim(ddm_isset($id, ''));
        $email  = $this->input->post('staff_email');
        $email  = trim(ddm_isset($email, ''));
        
        if ( !empty($email) ) {
            $staff = $this->Model_Staff->get_by('email', $email);

            if ( $staff ) {
                if ( $id ) {
                    $id = ddm_encrypt($id, 'decrypt');
                    if ( $id != $staff->id ) {
                        return false;
                    }
                } else {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Check Phone function.
     */
    function checkphone($id_staff = 0)
    {
        $id     = $id_staff;
        $id     = trim(ddm_isset($id, ''));
        $phone  = $this->input->post('staff_phone');
        $phone  = trim(ddm_isset($phone, ''));
        
        if ( !empty($phone) ) {
            $staff     = $this->Model_Staff->get_by('phone', $phone);

            if ( $staff ) {
                if ( $id ) {
                    $id = ddm_encrypt($id, 'decrypt');
                    if ( $id != $staff->id ) {
                        return false;
                    }
                } else {
                    return false;
                }
            }
        }

        return true;
    }
	
	function getstaff( $id_staff = 0 ) {
		// This is for AJAX request
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array('status' => 'login', 'message' => base_url('login'));
            die(json_encode($data));
        }

        if (!$id_staff) {
            // Set JSON data
            $data       = array('status' => 'error', 'message' => 'ID staff tidak boleh kosong. Silahkan Pilih Staff lainnya.');
            die(json_encode($data));
        }

        $id_staff       = ddm_decrypt($id_staff);
        if (!$staffdata = $this->Model_Staff->get_staffdata($id_staff)) {
            // Set JSON data
            $data       = array('status' => 'error', 'message' => 'Data Staff tidak ditemukan.');
            die(json_encode($data));
        }

        $staff['id']        = $staffdata->id;
        $staff['username']  = $staffdata->username;
        $staff['name']      = $staffdata->name;

        // Set JSON data
        $data = array('status' => 'success', 'data' => $staff);
        die(json_encode($data));

	}
	
	function resetpassword() {
		// This is for AJAX request
        if ( ! $this->input->is_ajax_request() ) exit('No direct script access allowed');

        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            // Set JSON data
            $data = array('status' => 'login', 'message' => base_url('login') );
            die(json_encode($data));
        }

        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);
        $staff_id               = $this->input->post( 'staff_id' );
        $staff_id               = ddm_isset($staff_id, 0);
        $staff_password         = $this->input->post( 'staff_password' );
        $staff_password         = ddm_isset($staff_password, '');
        $staff_password_retype  = $this->input->post( 'staff_password_confirm' );
        $staff_password_retype  = ddm_isset($staff_password_retype, '');

		$this->form_validation->set_rules( 'staff_password',			'Password', 'trim|required' );
		$this->form_validation->set_rules( 'staff_password_confirm',	'Password Confirm', 'trim|required|matches[staff_password]' );
			
		if ( $this->form_validation->run() == FALSE ) {
			$response = array( 'status' => 'error', 'message' => validation_errors() );
			die( json_encode( $response ) );
		}

        if( !$staff_id ) {
            // Set JSON data
            $data       = array('status' => 'error', 'message' => ddm_alert('ID staff tidak boleh kosong. Silahkan Pilih Staff lainnya.') );
            die(json_encode($data));
        }

        if ( !$staffdata = $this->Model_Staff->get_staffdata($staff_id) ) {
            // Set JSON data
            $data       = array('status' => 'error', 'message' => ddm_alert('Data Staff tidak ditemukan.') );
            die(json_encode($data));
        }

        // save staff
		$staff = array(
			'password' 		=> $staff_password,
			'datecreated'	=> date( 'Y-m-d H:i:s' )
		);
		
		if ( !$reset_pass = $this->Model_Staff->update_data( $staff_id, $staff ) ) {
			$response = array( 'status' => 'error', 'message' => ddm_alert('Reset Password Staff tidak berhasil.') );
			die( json_encode( $response ) );
		}

        // Set JSON data
        $data = array('status' => 'success', 'message' => ddm_alert('Reset Password Staff berhasil.') );
        die(json_encode($data));
	}
	
	function del( $id_staff ) {
		if ( ! $this->input->is_ajax_request() ) {
		   die( 'No direct script access allowed' );
		}

        $id_staff = ddm_decrypt($id_staff);
        $staffdata = $this->Model_Staff->get_staffdata($id_staff);

        if(!$staffdata) {
			$response = array( 'success' => false );
			die( json_encode( $response ) );
        }
		
		$data = array('status' => 0);
		if ( $this->Model_Staff->update_data( $id_staff, $data ) ) {
			$response = array( 'success' => true );
			die( json_encode( $response ) );
		}
		
		$response = array( 'success' => false );
		die( json_encode( $response ) );
	}
}

/* End of file staff.php */
/* Location: ./application/controllers/staff.php */