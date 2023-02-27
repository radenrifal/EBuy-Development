<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Member extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
    }

    public function auth_post()
    {
        $apiKey     = ddm_member_api_key_valid($this->rest->key);
        $nameKey    = isset($apiKey->name) ? $apiKey->name : '';
        $nameKey    = str_replace(' ', '_', $nameKey);

        if ( strtolower($nameKey) !== 'mobile_app' ) {
            $this->response([
                'success'   => false,
                'status'    => REST_Controller::HTTP_FORBIDDEN,
                'error'     => str_replace("%s", "", lang('text_rest_invalid_api_key')),
            ], REST_Controller::HTTP_FORBIDDEN); // the HTTP response code
        }

        $status     = false;
        $message    = 'FAILED! Silahkan cek username atau password Anda.';
        $resp_code  = REST_Controller::HTTP_NOT_FOUND;

        $username   = $this->post('username');
        $password   = $this->post('password');
        $member     = $this->Model_Auth->authenticate( $username, $password, true );

        // Response of signon member
        if ( $member == 'not_active' ){
            $message    = 'AKUN BELUM AKTIF! Silakan hubungi Administrator.';
        } elseif ( $member == 'banned' ){
            $message    = 'AKUN TELAH DI BANNED! Info lebih lengkap, hubungi manajemen.';
        } elseif ( $member == 'deleted' ){
            $message    = 'AKUN TIDAK DITEMUKAN! Silakan hubungi Administrator.';
        } elseif ( $member ) {
            $status     = true;
            $message    = 'LOGIN SUCCESSFULLY';
            $resp_code  = REST_Controller::HTTP_OK;
        }

        $response = ['success' => $status, 'status' => $resp_code, 'message' => $message];

        if ( $status ) {
            $key        = '';
            $getKey     = ddm_member_api_key($member->id);
            if ( $getKey ) {
                $key        = $getKey->key;
            }
            $response['apikey'] = $key;
            $response['data']   = ddm_unset_clone_member_data( $member );
        }

        // Set the response and exit
        $this->response($response, $resp_code); // the HTTP response code
    }

    public function index_post()
    {
        $limit          = $this->post('limit');
        $offset         = $this->post('offset');
        $sortBy         = $this->post('sortby');
        $orderBy        = $this->post('orderby');

        $id             = $this->post('id');
        $username       = $this->post('username');
        $name           = $this->post('name');
        $sponsor        = $this->post('sponsor');
        $phone          = $this->post('phone');
        $email          = $this->post('email');
        $datecreated    = $this->post('datecreated');

        $condition      = 'WHERE %type% = ' . MEMBER . ' AND %status% = ' . ACTIVE;
        $order_by       = '';

        if ( !empty($id) )          { $condition .= str_replace('%s%', $id, ' AND %id% = %s%'); }
        if ( !empty($username) )    { $condition .= str_replace('%s%', $username, ' AND %username% LIKE "%%s%%"'); }
        if ( !empty($name) )        { $condition .= str_replace('%s%', $name, ' AND %name% LIKE "%%s%%"'); }
        if ( !empty($phone) )       { $condition .= str_replace('%s%', $phone, ' AND %phone% LIKE "%%s%%"'); }
        if ( !empty($email) )       { $condition .= str_replace('%s%', $email, ' AND %email% LIKE "%%s%%"'); }
        if ( !empty($sponsor) )     { $condition .= str_replace('%s%', $sponsor, ' AND %sponsor_username% LIKE "%%s%%"'); }

        if ( $sortBy && $orderBy ) {
            if ( strtolower($sortBy) == 'username')     { $order_by = '%username% '. $orderBy; }
            if ( strtolower($sortBy) == 'name')         { $order_by = '%name% '. $orderBy; }
            if ( strtolower($sortBy) == 'phone')        { $order_by = '%phone% '. $orderBy; }
            if ( strtolower($sortBy) == 'email')        { $order_by = '%email% '. $orderBy; }
            if ( strtolower($sortBy) == 'sponsor')      { $order_by = '%sponsor_username% '. $orderBy; }
            if ( strtolower($sortBy) == 'datecreated')  { $order_by = '%datecreated% '. $orderBy; }
        }

        if ( $data = $this->Model_Member->get_all_member_data($limit, $offset, $condition, $order_by) ) {
            $totalRow   = ddm_get_last_found_rows();
            $results    = array();
            foreach ($data as $key => $row) {
                $results[] = array(
                    'id'            => $row->id,
                    'username'      => $row->username,
                    'name'          => $row->name,
                    'sponsor'       => $row->sponsor_username,
                    'phone'         => $row->phone,
                    'email'         => $row->email,
                    'datecreated'   => $row->datecreated,
                );
            }

            if ( $id ) {
                // Set the response and exit
                $this->response([
                    'success'       => TRUE,
                    'status'        => REST_Controller::HTTP_OK,
                    'message'       => 'Data Agen ditemukan',
                    'data'          => $results[0],
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            } else {
                // Set the response and exit
                $this->response([
                    'success'       => TRUE,
                    'status'        => REST_Controller::HTTP_OK,
                    'message'       => 'Data Agen ditemukan',
                    'total_data'    => $totalRow,
                    'data'          => $results,
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
        } else {
            // Set the response and exit
            $this->response([
                'success'   => FALSE,
                'status'    => REST_Controller::HTTP_NOT_FOUND,
                'message'   => 'Data Agen tidak ditemukan'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }

    public function dashboard_post()
    {
        $id_agent       = $this->post('id_agent');
        $response       = array(
            'success'   => FALSE,
            'status'    => REST_Controller::HTTP_NOT_FOUND,
            'message'   => 'Agen tidak valid'
        );

        if ( ! $id_agent ) {
            $response['message'] = 'id_agent field is required';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        // Check Data Agent
        $member = ddm_get_memberdata_by_id($id_agent);
        if ( ! $member ) {
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        $deposite           = $this->Model_Bonus->get_ewallet_deposite($member->id);
        $bonus              = $this->Model_Bonus->get_total_bonus_member($member->id);

        $condition          = ' AND ( sponsor = '. $member->id . ' OR id_member = '. $member->id .' ) '; 
        $dataOmzet          = $this->Model_Member->get_total_member_omzet_group($condition);
        $poin_group         = isset($dataOmzet->total_point) ? $dataOmzet->total_point : 0;
        $total_qty_group    = isset($dataOmzet->total_qty) ? $dataOmzet->total_qty : 0;

        $condition          = ' AND id_member = '. $member->id .' AND `status` = 1'; 
        $dataOrder          = $this->Model_Shop->get_total_shop_order($condition);
        $buying             = isset($dataOrder->total_payment) ? $dataOrder->total_payment : 0;
        $total_qty_package  = isset($dataOrder->total_qty) ? $dataOrder->total_qty : 0;

        $sales_pending  = 0;
        $condition      = 'WHERE %id_member% = ' . $member->id .' AND %status% = 0';
        $data_pending   = $this->Model_Shop->get_all_shop_order_customer_data(0, 0, $condition);
        if ( $data_pending ) {
            $sales_pending = ddm_get_last_found_rows();
        }

        // List Pembelian Pending
        $buying_pending = array();
        $condition      = 'WHERE %id_member% = ' . $member->id .' AND %status% = 0';
        $data_buying    = $this->Model_Shop->get_all_shop_order_data(10, 0, $condition);
        if ( $data_buying ) {
            foreach ($data_buying as $key => $row) {
                $type         = '';
                if ( $row->type == 'perdana' )  { $type = 'PERDANA'; }
                if ( $row->type == 'ro' )       { $type = 'REPEAT ORDER'; }
                $buying_pending[]   = array(
                    'id'            => $row->id,
                    'datecreated'   => $row->datecreated,
                    'invoice'       => $row->invoice,
                    'type'          => $type,
                    'total_payment' => $row->total_payment,
                );
            }
        }

        // Set the response and exit
        $this->response([
            'success'           => TRUE,
            'status'            => REST_Controller::HTTP_OK,
            'message'           => 'Infomasi Dashboard saya ('. $member->username.')',
            'deposite'          => $deposite,
            'bonus'             => $bonus,
            'poin_group'        => $poin_group,
            'total_qty_group'   => $total_qty_group,
            'buying'            => $buying,
            'my_qty_package'    => $total_qty_package,
            'sales_pending'     => $sales_pending,
            'list_buying_pending' => $buying_pending,
        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function find_agent_post()
    {

        $apiKey     = ddm_member_api_key_valid($this->rest->key);
        $nameKey    = isset($apiKey->name) ? $apiKey->name : '';
        $nameKey    = str_replace(' ', '_', $nameKey);

        if ( strtolower($nameKey) !== 'mobile_app' ) {
            $this->response([
                'success'   => false,
                'status'    => REST_Controller::HTTP_FORBIDDEN,
                'error'     => str_replace("%s", "", lang('text_rest_invalid_api_key')),
            ], REST_Controller::HTTP_FORBIDDEN); // the HTTP response code
        }
        
        $data_agent     = array();
        $dataAgent      = '';
        $agent_by       = '';

        $tracking_by    = $this->post('tracking_by');
        $username       = $this->post('username');
        $province       = $this->post('province_id');
        $city           = $this->post('city_id');
        $subdistrict    = $this->post('subdistrict_id');

        $condition      = ' AND %status% = '. ACTIVE .' AND %type% = '. MEMBER;
        $order_by       = '';

        if ( strtolower($tracking_by) == 'location' ) {
            if ( $city ) {
                $agent_by       = 'Agent by city';
                $condition     .= ' AND %district_id% = '. $city;
                if ( $subdistrict ) {
                    $condition .= ' AND %subdistrict_id% = '. $subdistrict;
                }
                $dataAgent      = $this->Model_Member->get_all_member_address(0, 0, $condition, '%name% ASC');
            } else if ( $province ) {
                $agent_by       = 'Agen by province';
                $condition     .= ' AND %province_id% = '. $province;
                $dataAgent      = $this->Model_Member->get_all_member_address(0, 0, $condition, '%name% ASC');            
            }
        } else if ( strtolower($tracking_by) == 'code' ) {
            $agent_by       = 'Agent by code';
            $condition     .= ' AND %username% = "'. trim($username) . '"';
            $dataAgent      = $this->Model_Member->get_all_member_address(0, 0, $condition, '%name% ASC');  
        }

        if ( ! $dataAgent ) {
            // Set the response and exit
            $this->response([
                'success'   => FALSE,
                'status'    => REST_Controller::HTTP_NOT_FOUND,
                'message'   => 'Agen tidak ditemukan, silahkan ganti pencarian anda'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        // Set the response and exit
        $this->response([
            'success'       => TRUE,
            'status'        => REST_Controller::HTTP_OK,
            'message'       => 'Data Agen ditemukan. '. $agent_by,
            'total_data'    => ddm_get_last_found_rows(),
            'data'          => $dataAgent,
        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function searchsponsor_post()
    {
        $id_agent       = $this->post('id_agent');
        $sponsor        = $this->post('sponsor');

        $response       = array(
            'success'   => FALSE,
            'status'    => REST_Controller::HTTP_NOT_FOUND,
            'message'   => 'Agen tidak valid'
        );

        ## Validation Global --------------------------------------------------------------
        $this->form_validation->set_rules('id_agent', 'id_agent', 'required');
        $this->form_validation->set_rules('sponsor', 'sponsor', 'required');
        
        $this->form_validation->set_error_delimiters('', ' ');
        if ($this->form_validation->run() == FALSE) {
            // Set the response and exit
            $response['message'] = validation_errors();
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        // Check Data Agent
        $member = ddm_get_memberdata_by_id($id_agent);
        if ( ! $member ) {
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        $memberdata         = $this->Model_Member->get_member_by('login', trim($sponsor));
        if( !$memberdata || empty($memberdata) ){
            $response['message'] = 'Data Sponsor tidak ditemukan atau belum terdaftar!';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
        
        if( $memberdata->status == 0 ){
            $response['message'] = 'Data Sponsor tidak ditemukan atau belum terdaftar!';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if( $memberdata->status > 1 ){
            $response['message'] = 'Data Sponsor tersebut di banned. Silahkan ketik Username Sponsor lain !';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        $is_down    = $this->Model_Member->get_is_downline($memberdata->id, $member->tree);
        if( !$is_down ){
            $response['message'] = 'Username Sponsor ini tidak berada di jaringan Anda. Silahkan ketik Username Sponsor lain!';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        $sponsordata    = array(
            'id'        => $memberdata->id,
            'username'  => $memberdata->username,
            'name'      => $memberdata->name,
            'phone'     => $memberdata->phone,
            'email'     => $memberdata->email,
        );

        // Set the response and exit
        $this->response([
            'success'       => TRUE,
            'status'        => REST_Controller::HTTP_OK,
            'message'       => 'Data Sponsor ditemukan',
            'data'          => $sponsordata,
        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function generation_post()
    {
        $id_agent       = $this->post('id_agent');
        $response       = array(
            'success'   => FALSE,
            'status'    => REST_Controller::HTTP_NOT_FOUND,
            'message'   => 'Data Generasi tidak ditemukan'
        );

        if ( ! $id_agent ) {
            $response['message'] = 'id_agent field is required';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        // Check Data Agent
        $member = ddm_get_memberdata_by_id($id_agent);
        if ( ! $member ) {
            $response['message'] = 'Agen tidak valid';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        $my_gen         = $member->level;
        $max_gen        = $my_gen + 3;
        $condition      = ' AND %tree% LIKE "' . $member->tree .'-%" AND %level% <= '. $max_gen;
        $order_by       = '%level% ASC, %username% ASC';

        // Get Condition POST
        $username       = $this->post('username');
        $name           = $this->post('name');
        $sponsor        = $this->post('sponsor');
        $generation     = $this->post('generation');

        $limit          = $this->post('limit');
        $offset         = $this->post('offset');
        $sortBy         = $this->post('sortby');
        $orderBy        = $this->post('orderby');

        if ( !empty($username) )    { $condition .= str_replace('%s%', $username, ' AND %username% LIKE "%%s%%"'); }
        if ( !empty($name) )        { $condition .= str_replace('%s%', $name, ' AND %name% LIKE "%%s%%"'); }
        if ( !empty($sponsor) )     { $condition .= str_replace('%s%', $sponsor, ' AND %sponsor_username% LIKE "%%s%%"'); }
        if ( !empty($generation) )  { $condition .= str_replace('%s%', ($generation + $my_gen), ' AND %level% = %s%'); }

        if ( $sortBy && $orderBy ) {
            if ( strtolower($sortBy) == 'username')     { $order_by = '%username% '. $orderBy; }
            if ( strtolower($sortBy) == 'name')         { $order_by = '%name% '. $orderBy; }
            if ( strtolower($sortBy) == 'sponsor')      { $order_by = '%sponsor_username% '. $orderBy; }
            if ( strtolower($sortBy) == 'generation')   { $order_by = '%level% '. $orderBy; }
        }

        $total_data     = 0;
        $results        = array();
        $data_list      = $this->Model_Member->get_all_member_generation_omzet($limit, $offset, $condition, $order_by);

        if ( $data_list ) {
            $total_data = ddm_get_last_found_rows();
            $i = $offset + 1;
            foreach ($data_list as $key => $row) {
                $gen            = $row->level - $my_gen;
                $results[] = array(
                    'id'            => $row->id,
                    'username'      => $row->username,
                    'name'          => $row->name,
                    'sponsor'       => strtolower($row->sponsor_username) .' ('. strtoupper($row->sponsor_name) .')',
                    'generation'    => 'Gen-'. $gen,
                    'omzet_perdana' => $row->omzet_perdana,
                    'omzet_ro'      => $row->omzet_ro,
                    'join_date'     => date('Y-m-d', strtotime($row->date_join)),
                );
            }
        }

        // Set the response and exit
        $this->response([
            'success'       => TRUE,
            'status'        => REST_Controller::HTTP_OK,
            'message'       => ( $total_data > 1 ) ? 'Data Generasi ditemukan' : 'Data Generasi tidak ditemukan',
            'total_data'    => ( $results ) ? $total_data : 0,
            'data'          => $results,
        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function registration_post()
    {
        $id_agent       = $this->post('id_agent');
        $response       = array(
            'success'   => FALSE,
            'status'    => REST_Controller::HTTP_NOT_FOUND,
            'message'   => 'Data Generasi tidak ditemukan'
        );

        if ( ! $id_agent ) {
            $response['message'] = 'id_agent field is required';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        // Check Data Agent
        $member = ddm_get_memberdata_by_id($id_agent);
        if ( ! $member ) {
            $response['message'] = 'Agen tidak valid';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        $condition          = ' WHERE %type% = '.MEMBER.' AND %id_member% = ' . $member->id;
        $order_by           = '';

        // Get Condition POST
        $registrant         = $this->post('registrant');
        $sponsor            = $this->post('sponsor');
        $username           = $this->post('username');
        $name               = $this->post('name');
        $status             = $this->post('status');
        $nominal_min        = $this->post('nominal_min');
        $nominal_max        = $this->post('nominal_max');
        $date_min           = $this->post('date_min');
        $date_max           = $this->post('date_max');
        $dateconfirm_min    = $this->post('dateconfirm_min');
        $dateconfirm_max    = $this->post('dateconfirm_max');

        $limit              = $this->post('limit');
        $offset             = $this->post('offset');
        $sortBy             = $this->post('sortby');
        $orderBy            = $this->post('orderby');

        if ( !empty($registrant) )      { $condition .= str_replace('%s%', $registrant, ' AND %member% LIKE "%%s%%"'); }
        if ( !empty($sponsor) )         { $condition .= str_replace('%s%', $sponsor, ' AND %sponsor% LIKE "%%s%%"'); }
        if ( !empty($username) )        { $condition .= str_replace('%s%', $username, ' AND %downline% LIKE "%%s%%"'); }
        if ( !empty($name) )            { $condition .= str_replace('%s%', $name, ' AND %name% LIKE "%%s%%"'); }
        if ( !empty($nominal_min) )     { $condition .= ' AND %nominal% >= '.$nominal_min.''; }
        if ( !empty($nominal_max) )     { $condition .= ' AND %nominal% <= '.$nominal_max.''; }
        if ( !empty($date_min) )        { $condition .= ' AND %datecreated% >= "'.$date_min.'"'; }
        if ( !empty($date_max) )        { $condition .= ' AND %datecreated% <= "'.$date_max.'"'; }
        if ( !empty($status) )          {
            if( $status == 'cancelled' )  { $condition .= str_replace('%s%', 2, ' AND %status% = %s%'); }
            if( $status == 'confirmed' )  { $condition .= str_replace('%s%', 1, ' AND %status% = %s%'); }
            if( $status == 'pending' )    { $condition .= str_replace('%s%', 0, ' AND %status% = %s%'); }
        }
        if ( !empty($dateconfirm_min) )   { 
            $condition .= ' AND %dateconfirm% >= "'.$dateconfirm_min.'"'; 
            $condition .= str_replace('%s%', 1, ' AND %status% = %s%');
        }
        if ( !empty($dateconfirm_max) )   { 
            $condition .= ' AND %dateconfirm% <= "'.$dateconfirm_max.'"'; 
            $condition .= str_replace('%s%', 1, ' AND %status% = %s%');
        }

        if ( $sortBy && $orderBy ) {
            if ( strtolower($sortBy) == 'registrant')   { $order_by = '%member% '. $orderBy; }
            if ( strtolower($sortBy) == 'sponsor')      { $order_by = '%sponsor% '. $orderBy; }
            if ( strtolower($sortBy) == 'username')     { $order_by = '%downline% '. $orderBy; }
            if ( strtolower($sortBy) == 'name')         { $order_by = '%name% '. $orderBy; }
            if ( strtolower($sortBy) == 'nominal')      { $order_by = '%nominal% '. $orderBy; }
            if ( strtolower($sortBy) == 'date')         { $order_by = '%datecreated% '. $orderBy; }
            if ( strtolower($sortBy) == 'dateconfirm')  { $order_by = '%dateconfirm% '. $orderBy; $condition .= str_replace('%s%', 1, ' AND %status% = %s%'); }
        }

        $total_data     = 0;
        $results        = array();
        $data_list      = $this->Model_Member->get_all_member_confirm($limit, $offset, $condition, $order_by);

        if ( $data_list ) {
            $total_data = ddm_get_last_found_rows();
            foreach ($data_list as $key => $row) {
                $status = '';
                if($row->status == 0)       { $status = 'PENDING'; }
                elseif($row->status == 1)   { $status = 'CONFIRMED'; }
                elseif($row->status == 2)   { $status = 'CANCELLED'; }

                $dateconfirm    = ( $row->status == 1 ) ? $row->datemodified : '-';
                $results[]      = array(
                    'registrant'    => strtolower($row->member),
                    'sponsor'       => strtolower($row->sponsor),
                    'username'      => strtolower($row->downline),
                    'name'          => strtoupper($row->name),
                    'nominal'       => $row->nominal,
                    'status'        => $status,
                    'date'          => $row->datecreated,
                    'dateconfirm'   => $dateconfirm,
                );
            }
        }

        // Set the response and exit
        $this->response([
            'success'       => TRUE,
            'status'        => REST_Controller::HTTP_OK,
            'message'       => ( $total_data > 1 ) ? 'Data Pendaftaran ditemukan' : 'Data Pendaftaran tidak ditemukan',
            'total_data'    => ( $results ) ? $total_data : 0,
            'data'          => $results,
        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function reward_post()
    {
        $id_agent       = $this->post('id_agent');
        $response       = array(
            'success'   => FALSE,
            'status'    => REST_Controller::HTTP_NOT_FOUND,
            'message'   => 'Data Reward Agen'
        );

        if ( ! $id_agent ) {
            $response['message'] = 'id_agent field is required';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        // Check Data Agent
        $member = ddm_get_memberdata_by_id($id_agent);
        if ( ! $member ) {
            $response['message'] = 'Agen tidak valid';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        $condition          = ' AND %id_member% = ' . $member->id;
        $order_by           = '';

        // Get Condition POST
        $reward             = $this->post('reward');
        $status             = $this->post('status');
        $nominal_min        = $this->post('nominal_min');
        $nominal_max        = $this->post('nominal_max');
        $date_min           = $this->post('date_min');
        $date_max           = $this->post('date_max');
        $dateconfirm_min    = $this->post('dateconfirm_min');
        $dateconfirm_max    = $this->post('dateconfirm_max');

        $limit              = $this->post('limit');
        $offset             = $this->post('offset');
        $sortBy             = $this->post('sortby');
        $orderBy            = $this->post('orderby');

        if ( !empty($reward) )          { $condition .= ' AND %id_reward% = '.$reward.''; }
        if ( !empty($nominal_min) )     { $condition .= ' AND %nominal% >= '.$nominal_min.''; }
        if ( !empty($nominal_max) )     { $condition .= ' AND %nominal% <= '.$nominal_max.''; }
        if ( !empty($date_min) )        { $condition .= ' AND %datecreated% >= "'.$date_min.'"'; }
        if ( !empty($date_max) )        { $condition .= ' AND %datecreated% <= "'.$date_max.'"'; }
        if ( !empty($status) )          {
            if( $status == 'confirmed' )  { $condition .= str_replace('%s%', 1, ' AND %status% = %s%'); }
            if( $status == 'pending' )    { $condition .= str_replace('%s%', 0, ' AND %status% = %s%'); }
        }
        if ( !empty($dateconfirm_min) )   { 
            $condition .= ' AND %datemodified% >= "'.$dateconfirm_min.'"'; 
            $condition .= str_replace('%s%', 1, ' AND %status% = %s%');
        }
        if ( !empty($dateconfirm_max) )   { 
            $condition .= ' AND %datemodified% <= "'.$dateconfirm_max.'"'; 
            $condition .= str_replace('%s%', 1, ' AND %status% = %s%');
        }

        if ( $sortBy && $orderBy ) {
            if ( strtolower($sortBy) == 'reward')       { $order_by = '%message% '. $orderBy; }
            if ( strtolower($sortBy) == 'status')       { $order_by = '%status% '. $orderBy; }
            if ( strtolower($sortBy) == 'nominal')      { $order_by = '%nominal% '. $orderBy; }
            if ( strtolower($sortBy) == 'date')         { $order_by = '%datecreated% '. $orderBy; }
            if ( strtolower($sortBy) == 'dateconfirm')  { $order_by = '%datemodified% '. $orderBy; $condition .= str_replace('%s%', 1, ' AND %status% = %s%'); }
        }

        $total_data     = 0;
        $my_reward      = array();
        $data_list      = $this->Model_Member->get_all_member_reward($limit, $offset, $condition, $order_by);

        if ( $data_list ) {
            $total_data = ddm_get_last_found_rows();
            foreach ($data_list as $key => $row) {
                $status = '';
                if($row->status == 0)       { $status = 'PENDING'; }
                elseif($row->status == 1)   { $status = 'CONFIRMED'; }

                $dateconfirm    = ( $row->status == 1 ) ? $row->datemodified : '-';
                $my_reward[]    = array(
                    'reward'        => $row->message,
                    'nominal'       => $row->nominal,
                    'status'        => $status,
                    'date'          => $row->datecreated,
                    'dateconfirm'   => $dateconfirm,
                );
            }
        }

        $info_reward        = array();
        $cfg_reward         = $this->Model_Option->get_all_reward_data();
        if ( $cfg_reward ) {
            foreach ($cfg_reward as $key => $row) {
                if ( $row->is_active == 0 ) { continue; }
                $period     = 'Periode Reward : '. date('d-M-Y', strtotime($row->start_date)) .' s/d '. date('d-M-Y', strtotime($row->end_date));
                if ( $row->is_lifetime > 0 ) {
                    $period = 'Lifetime Reward';
                }
                $info_reward[]  = array(
                    'reward'        => $row->reward,
                    'nominal'       => $row->nominal,
                    'point'         => $row->point,
                    'period'        => $period,
                );
            }
        }

        // Set the response and exit
        $this->response([
            'success'       => TRUE,
            'status'        => REST_Controller::HTTP_OK,
            'total_data'    => ( $my_reward ) ? $total_data : 0,
            'my_reward'     => $my_reward,
            'info_reward'   => $info_reward,
        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function intro_post()
    {
        $data_intro = array();
        $intro      = $this->Model_Option->get_all_intro_data();
        if ( $intro ) {
            foreach ($intro as $key => $row) {
                $data_intro[]   = array(
                    'file_type' => $row->file_type,
                    'file_size' => $row->file_size,
                    'file_url'  => $row->file_url,
                );
            }
        }
        if ( $data_intro ) {
            // Set the response and exit
            $this->response([
                'success'       => TRUE,
                'status'        => REST_Controller::HTTP_OK,
                'message'       => 'Data Intro ditemukan',
                'data'          => $data_intro,
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'success'       => FALSE,
                'status'        => REST_Controller::HTTP_NOT_FOUND,
                'message'       => 'Data Intro ditemukan',
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

}
