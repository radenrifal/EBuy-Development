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
class Shop extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Load Shop helper 
        $this->load->helper('shop_helper');

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
    }

    public function banks_post()
    {
        $id     = $this->post('id');
        $banks  = ddm_banks($id);
        if ( $banks ) {
            // Set the response and exit
            $this->response([
                'success'       => TRUE,
                'status'        => REST_Controller::HTTP_OK,
                'message'       => 'Data Bank ditemukan',
                'data'          => $banks,
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'success'       => FALSE,
                'status'        => REST_Controller::HTTP_NOT_FOUND,
                'message'       => 'Data Bank ditemukan',
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function registerfee_post()
    {
        $cfg_reg_fee    = get_option('register_fee');
        $cfg_reg_fee    = $cfg_reg_fee ? $cfg_reg_fee : 0;
        // Set the response and exit
        $this->response([
            'success'       => TRUE,
            'status'        => REST_Controller::HTTP_OK,
            'message'       => 'Biaya registrasi',
            'nominal'       => $cfg_reg_fee,
        ], REST_Controller::HTTP_OK);
    }

    public function agentorder_post()
    {
        $limit      = $this->post('limit');
        $offset     = $this->post('offset');
        $sortBy     = $this->post('sortby');
        $orderBy    = $this->post('orderby');
        $orderBy    = ddm_isset($orderBy, 'ASC');

        // Get Condition
        $id         = $this->post('id');
        $id_agent   = $this->post('id_agent');
        $invoice    = $this->post('invoice');
        $type       = $this->post('type');
        $status     = $this->post('status');
        $date_min   = $this->post('date_min');
        $date_max   = $this->post('date_max');

        $data       = false;
        $totalRow   = 0;
        $condition  = 'WHERE %id% > 0 AND %type_member% = ' . MEMBER;
        $order_by   = '%datecreated% DESC';

        $response           = array(
            'success'       => FALSE,
            'status'        => REST_Controller::HTTP_NOT_FOUND,
            'message'       => 'Data Produk Order tidak ditemukan'
        );

        if ( !$id_agent ) {
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( !empty($id) )          { $condition .= str_replace('%s%', $id, ' AND %id% = %s%'); }
        if ( !empty($id_agent) )    { $condition .= str_replace('%s%', $id_agent, ' AND %id_member% = %s%'); }
        if ( !empty($invoice) )     { $condition .= str_replace('%s%', $invoice, ' AND %invoice% LIKE "%%s%%"'); }
        if ( !empty($type) )        { $condition .= str_replace('%s%', $type, ' AND %type% LIKE "%%s%%"'); }
        if ( !empty($date_min) )    { $condition .= ' AND DATE(%datecreated%) >= "'.$date_min.'"'; }
        if ( !empty($date_max) )    { $condition .= ' AND DATE(%datecreated%) <= "'.$date_max.'"'; }
        if ( !empty($status) )      { 
            if ( $status == 'pending' )   { $condition .= str_replace('%s%', 0, ' AND %status% = %s%');  }
            if ( $status == 'cancelled' ) { $condition .= str_replace('%s%', 2, ' AND %status% = %s%');  }
            if ( $status == 'confirmed' ) { 
                $condition .= ' AND %status% = 1 AND CHAR_LENGTH(resi) = 0';  
            }
            if ( $status == 'done' )      { 
                $condition .= ' AND %status% = 1 AND CHAR_LENGTH(resi) > 1';  
            }
        }

        if ( $sortBy && $orderBy ) {
            $order_by = $sortBy .' '. $orderBy;
            if ( strtolower($sortBy) ==  'type' )   { $order_by = '%type% '. $orderBy; }
            if ( strtolower($sortBy) ==  'status' ) { $order_by = '%status% '. $orderBy; }
            if ( strtolower($sortBy) ==  'datecreated') { $order_by = '%datecreated% '. $orderBy; }
        }

        $data_list  = $this->Model_Shop->get_all_shop_order_data($limit, $offset, $condition, $order_by);
        if ( !$data_list ) {
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        $totalRow   = ddm_get_last_found_rows();
        $results    = array();
        foreach ($data_list as $key => $row) {
            $row->fee_registration = $row->registration;
            $row->product_packages   = maybe_unserialize($row->products);
            if ( $row->status == 0 ) { $row->status = 'PENDING'; }
            if ( $row->status == 2 ) { $row->status = 'CANCELLED'; }
            if ( $row->status == 1 ) { 
                $row->status = ( $row->resi ) ? 'DONE' : 'CONFIRMED';
            }

            if ( $row->tf_img ) {
                $img_payment_path = PAYMENT_IMG_PATH . $row->tf_img;
                if ( file_exists($img_payment_path) ) {
                    $row->tf_img = PAYMENT_IMG . $row->tf_img;
                }
            }

            unset($row->registration);
            unset($row->products);
            $results[] = $row;
        }
        $response   = array(
            'success'       => TRUE,
            'status'        => REST_Controller::HTTP_OK,
            'message'       => 'Data Produk Order ditemukan'
        );

        if ( $id ) {
            $bill_bank  = '';
            $bill_no    = get_option('company_bill');
            $bill_name  = get_option('company_bill_name');
            if ( $company_bank = get_option('company_bank') ) {
                if ( $getBank = ddm_banks($company_bank) ) {
                    $bill_bank = $getBank->nama;
                }
            }

            if ( $bill_no ) {
                $bill_format = '';
                $arr_bill    = str_split($bill_no, 4);
                foreach ($arr_bill as $no) {
                    $bill_format .= $no .' ';
                }
                $bill_no = $bill_format ? $bill_format : $bill_no;;
            }

            $response['invoice']        = $results[0]->invoice;
            $response['company_bill']   = array(
                'bank'                  => $bill_bank,
                'bill_no'               => $bill_no,
                'bill_name'             => $bill_name,
            );
            $response['data']           = $results[0];
        } else {
            $response['total_data'] = $totalRow;
            $response['data']       = $results;
        }

        // Set the response and exit\
        $this->response($response, REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
    }

    public function customerorder_post()
    {
        $limit          = $this->post('limit');
        $offset         = $this->post('offset');
        $sortBy         = $this->post('sortby');
        $orderBy        = $this->post('orderby');
        $orderBy        = ddm_isset($orderBy, 'ASC');

        // Get Condition
        $id             = $this->post('id');
        $id_agent       = $this->post('id_agent');
        $id_customer    = $this->post('id_customer');
        $invoice        = $this->post('invoice');
        $status         = $this->post('status');
        $date_min       = $this->post('date_min');
        $date_max       = $this->post('date_max');

        $data           = false;
        $totalRow       = 0;
        $condition      = 'WHERE %id% > 0 ';
        $order_by       = '%datecreated% DESC';

        $response           = array(
            'success'       => FALSE,
            'status'        => REST_Controller::HTTP_NOT_FOUND,
            'message'       => 'Data Produk Order tidak ditemukan'
        );

        if ( !empty($id) )          { $condition .= str_replace('%s%', $id, ' AND %id% = %s%'); }
        if ( !empty($id_agent) )    { $condition .= str_replace('%s%', $id_agent, ' AND %id_member% = %s%'); }
        if ( !empty($id_customer) ) { $condition .= str_replace('%s%', $id_customer, ' AND %id_customer% = %s%'); }
        if ( !empty($invoice) )     { $condition .= str_replace('%s%', $invoice, ' AND %invoice% LIKE "%%s%%"'); }
        if ( !empty($date_min) )    { $condition .= ' AND DATE(%datecreated%) >= "'.$date_min.'"'; }
        if ( !empty($date_max) )    { $condition .= ' AND DATE(%datecreated%) <= "'.$date_max.'"'; }
        if ( !empty($status) )      { 
            if ( $status == 'pending' )   { $condition .= str_replace('%s%', 0, ' AND %status% = %s%');  }
            if ( $status == 'cancelled' ) { $condition .= str_replace('%s%', 2, ' AND %status% = %s%');  }
            if ( $status == 'confirmed' ) { 
                $condition .= ' AND %status% = 1 AND CHAR_LENGTH(resi) = 0';  
            }
            if ( $status == 'done' )      { 
                $condition .= ' AND %status% = 1 AND CHAR_LENGTH(resi) > 1';  
            }
        }

        if ( $sortBy && $orderBy ) {
            $order_by = $sortBy .' '. $orderBy;
            if ( strtolower($sortBy) ==  'type' )   { $order_by = '%type% '. $orderBy; }
            if ( strtolower($sortBy) ==  'status' ) { $order_by = '%status% '. $orderBy; }
            if ( strtolower($sortBy) ==  'datecreated') { $order_by = '%datecreated% '. $orderBy; }
        }

        $data_list  = $this->Model_Shop->get_all_shop_order_customer_data($limit, $offset, $condition, $order_by);
        if ( !$data_list ) {
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        $totalRow   = ddm_get_last_found_rows();
        $results    = array();
        foreach ($data_list as $key => $row) {
            $row->products   = maybe_unserialize($row->products);
            if ( $row->status == 0 ) { $row->status = 'PENDING'; }
            if ( $row->status == 2 ) { $row->status = 'CANCELLED'; }
            if ( $row->status == 1 ) { 
                $row->status = ( $row->resi ) ? 'DONE' : 'CONFIRMED';
            }
            $results[] = $row;
        }
        $response   = array(
            'success'       => TRUE,
            'status'        => REST_Controller::HTTP_OK,
            'message'       => 'Data Produk Order ditemukan'
        );

        if ( $id ) {
            $response['invoice']    = $results[0]->invoice;
            // Check Data Agent
            if ( $member = ddm_get_memberdata_by_id($results[0]->id_member) ) {
                $response['data_agent']     = array(
                    'name'                  => $member->name,
                    'phone'                 => $member->phone,
                    'email'                 => $member->email,
                );
            }
            $response['data']       = $results[0];
        } else {
            $response['total_data'] = $totalRow;
            $response['data']       = $results;
        }

        // Set the response and exit\
        $this->response($response, REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
    }

    public function confirmpayment_post()
    {
        $id_order           = sanitize($this->post('id_order'));
        $id_agent           = sanitize($this->post('id_agent'));
        $bank               = sanitize($this->post('bank'));
        $billno             = sanitize($this->post('billno'));
        $billname           = sanitize($this->post('billname'));
        $nominal            = sanitize($this->post('nominal'));

        $response           = array(
            'success'       => FALSE,
            'status'        => REST_Controller::HTTP_NOT_FOUND,
            'message'       => 'Checkout tidak berhasil. Silahkan periksa kembali keranjang belanjaan anda!'
        );

        ## Validation Global --------------------------------------------------------------
        $this->form_validation->set_rules('id_order', 'id_order', 'required');
        $this->form_validation->set_rules('id_agent', 'id_agent', 'required');
        $this->form_validation->set_rules('bank', 'bank', 'required');
        $this->form_validation->set_rules('billno', 'billno', 'numeric|required');
        $this->form_validation->set_rules('billname', 'billname', 'required');
        $this->form_validation->set_rules('nominal', 'nominal', 'numeric|required');

        $this->form_validation->set_error_delimiters('', ' ');
        if ($this->form_validation->run() == FALSE) {
            // Set the response and exit
            $response['message'] = validation_errors();
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        // Check Data Order
        $order  = $this->Model_Shop->get_shop_order_by('id', $id_order);
        if ( ! $order ) {
            // Set the response and exit
            $response['message'] = 'Produk Order tidak ditemukan';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( $order->id_member != $id_agent ) {
            $response['message'] = 'Produk Order ini bukan Orderan Anda !';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( $order->status == 2 ) {
            $response['message'] = 'Konfirmasi Pesanan tidak berhasil. Pesanan sudah dibatalkan.';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( $order->status > 0 ) {
            $response['message'] = 'Konfirmasi Pesanan tidak berhasil. Pesanan sudah diproses.';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        $condition  = array('type' => 'shop');
        if ( $getPayment = $this->Model_Shop->get_payment_evidence_by('id_source', $id_order, $condition, 1) ) {
            $response['message'] = 'Konfirmasi Pesanan tidak berhasil. Pesanan sudah diproses.';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        // Check Data Agent
        $agent = ddm_get_memberdata_by_id($id_agent);
        if ( ! $agent ) {
            $response['message'] = 'Agen tidak valid';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( $agent->type != MEMBER ) {
            $response['message'] = 'Agen tidak valid';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( strtolower(trim($order->type)) == 'ro' ) {
            if ( $agent->status != ACTIVE ) {
                $response['message'] = 'Agen tidak valid';
                $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }

        $bank_name  = '';
        $banks      = ddm_banks($bank);
        if ( ! $banks ) {
            $response['message'] = 'Bank tidak valid';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
        $bank_name  = $banks->nama;

        // Config Upload Image
        $invoice                    = str_replace('/', '-', $order->invoice);
        $img_upload                 = true;
        $img_name                   = $invoice.'-'.time();

        $config['upload_path']      = PAYMENT_IMG_PATH;
        $config['allowed_types']    = 'jpg|png|jpeg';
        $config['max_size']         = '2048';
        $config['overwrite']        = true;
        $config['file_name']        = $img_name;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if( ! $this->upload->do_upload("upload_img")) {
            $img_upload             = false;
            $img_msg                = $this->upload->display_errors();
        }

        // -------------------------------------------------
        // Transaction Begin
        // -------------------------------------------------
        $this->db->trans_begin();

        // -------------------------------------------------
        // Set Data Confirm Payment Order
        // -------------------------------------------------
        $data               = array(
            'type'          => 'shop',
            'id_source'     => $order->id,
            'id_member'     => $order->id_member,
            'bill_bank'     => strtoupper($bank_name),
            'bill_no'       => $billno,
            'bill_name'     => strtolower($billname),
            'amount'        => $nominal,
            'image'         => '',
        );

        if ( $img_upload ) {
            $get_data_img       = $this->upload->data();
            $img_msg            = 'upload success';
            $data['image']      = $get_data_img['file_name'];
        }

        if ( ! $payment_saved_id = $this->Model_Shop->save_data_payment_evidence($data) ) {
            $this->db->trans_rollback();
            $response['message'] = 'Konfirmasi tidak berhasil. Terjadi kesalahan simpan data pada sistem.';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        ## Confirm Payment Order Success -------------------------------------------------------
        $this->db->trans_commit();
        $this->db->trans_complete(); //  complete database transactions  

        ddm_log_action( 'CONFIRM_PAYMENT', $order->invoice, 'MOBILE_APP', json_encode(array('cookie'=>$_COOKIE, 'status'=>'SUCCESS', 'shop_order_id'=>$order->id)) );

        // Set the response and exit
        $this->response([
            'success'       => TRUE,
            'status'        => REST_Controller::HTTP_OK,
            'message'       => 'Konfirmasi pembayaran berhasil. Silahkan tunggu konfirmasi dari admin',
        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function confirmorder_post()
    {
        $response       = array(
            'success'   => FALSE,
            'status'    => REST_Controller::HTTP_NOT_FOUND,
            'message'   => 'Data Produk Order tidak ditemukan'
        );

        // Get Input POST
        $id_agent       = sanitize($this->post('id_agent'));
        $id_order       = sanitize($this->post('id_order'));
        $password       = sanitize($this->post('password'));

        ## Validation Global --------------------------------------------------------------
        $this->form_validation->set_rules('id_agent', 'id_agent', 'required');
        $this->form_validation->set_rules('id_order', 'id_order', 'required');
        $this->form_validation->set_rules('password', 'password', 'required');
        
        $this->form_validation->set_error_delimiters('', ' ');
        if ($this->form_validation->run() == FALSE) {
            // Set the response and exit
            $response['message'] = validation_errors();
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        // Check Data Agent
        $member = ddm_get_memberdata_by_id($id_agent);
        if ( ! $member ) {
            $response['message'] = 'Agen tidak valid';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( ! $shop_order = $this->Model_Shop->get_shop_order_customer_by('id', $id_order) ) {
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( $shop_order->id_member !== $member->id ) {
            $response['message'] = 'Maaf, Anda tidak dapat Konfirmasi Pesanan ini !';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        $password           = trim($password);
        $password_md5       = md5($password);
        $pwd_valid          = false;

        if ( $password_md5 == $member->password ) {
            $pwd_valid      = true;
        }

        if ( ddm_hash_verify($password, $member->password) ) {
            $pwd_valid      = true;
        }

        if ( !$pwd_valid ) {
            $response['message'] = 'Maaf, Password anda tidak valid !';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( $shop_order->status == 1 ) {
            $response['message'] = 'Status Pesanan sudah dikonfirmasi.';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( $shop_order->status == 2 ) {
            $response['message'] = 'Status Pesanan sudah dibatalkan (cancelled).';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( $shop_order->status != 0 ) {
            $response['message'] = 'Pesanan tidak dapat dikonfirmasi.';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        // Update status shop order
        $datetime           = date('Y-m-d H:i:s');
        $data_order         = array(
            'status'        => 1,
            'datemodified'  => $datetime,
            'dateconfirm'   => $datetime,
            'confirmed_by'  => $member->username,
            'modified_by'   => $member->username,
        );

        if ( ! $update_shop_order = $this->Model_Shop->update_data_shop_order_customer($id_order, $data_order)) {
            $data['message'] = 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        $log_data           = array(
            'cookie'        => $_COOKIE,
            'id_shop'       => $id_order,
            'invoice'       => $shop_order->invoice,
            'confirmed_by'  => $member->username,
        ); ddm_log_action('CUSTOMER_ORDER_CONFIRM', 'SUCCESS', 'MOBILE_APP', json_encode($log_data));

        // Set the response and exit
        $this->response([
            'success'       => TRUE,
            'status'        => REST_Controller::HTTP_OK,
            'message'       => 'Produk Order berhasil dikonfirmasi.',
        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function cancelorder_post()
    {
        // Get Input POST
        $type           = sanitize($this->post('type'));
        $id_agent       = sanitize($this->post('id_agent'));
        $id_order       = sanitize($this->post('id_order'));
        $password       = sanitize($this->post('password'));

        $response       = array(
            'success'   => FALSE,
            'status'    => REST_Controller::HTTP_NOT_FOUND,
            'message'   => 'Data Produk Order tidak ditemukan'
        );

        // Get Input POST
        $id_agent       = sanitize($this->post('id_agent'));
        $id_order       = sanitize($this->post('id_order'));
        $password       = sanitize($this->post('password'));

        ## Validation Global --------------------------------------------------------------
        $this->form_validation->set_rules('type', 'type', 'required');
        $this->form_validation->set_rules('id_agent', 'id_agent', 'required');
        $this->form_validation->set_rules('id_order', 'id_order', 'required');
        $this->form_validation->set_rules('password', 'password', 'required');
        
        $this->form_validation->set_error_delimiters('', ' ');
        if ($this->form_validation->run() == FALSE) {
            // Set the response and exit
            $response['message'] = validation_errors();
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        $type = strtolower($type);
        if ( $type !== 'agent' && $type !== 'customer' ) {
            $response['message'] = 'type order tidak valid';
            $this->response($response, REST_Controller::HTTP_FORBIDDEN); // the HTTP response code
        }

        // Check Data Agent
        $member = ddm_get_memberdata_by_id($id_agent);
        if ( ! $member ) {
            $response['message'] = 'Agen tidak valid';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( $type == 'agent' ) {
            $shop_order = $this->Model_Shop->get_shop_orders($id_order);
        } else {
            $shop_order = $this->Model_Shop->get_shop_order_customer_by('id', $id_order);
        }

        if ( ! $shop_order ) {
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( $shop_order->id_member !== $member->id ) {
            $response['message'] = 'Maaf, Anda tidak dapat Batalkan Pesanan ini !';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        $password           = trim($password);
        $password_md5       = md5($password);
        $pwd_valid          = false;

        if ( $password_md5 == $member->password ) {
            $pwd_valid      = true;
        }

        if ( ddm_hash_verify($password, $member->password) ) {
            $pwd_valid      = true;
        }

        if ( !$pwd_valid ) {
            $response['message'] = 'Maaf, Password anda tidak valid !';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( $shop_order->status == 1 ) {
            $response['message'] = 'Status Pesanan sudah dikonfirmasi.';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( $shop_order->status == 2 ) {
            $response['message'] = 'Status Pesanan sudah dibatalkan (cancelled).';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( $shop_order->status != 0 ) {
            $response['message'] = 'Pesanan tidak dapat dibatalkan.';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        // Update status shop order
        $datetime           = date('Y-m-d H:i:s');
        $data_order         = array(
            'status'        => 2,
            'datemodified'  => $datetime,
            'modified_by'   => $member->username,
        );

        if ( $type == 'agent' ) {
            $update_shop_order  = $this->Model_Shop->update_data_shop_order($id_order, $data_order);
            $title_log          = 'ORDER_CANCEL';
        } else {
            $update_shop_order = $this->Model_Shop->update_data_shop_order_customer($id_order, $data_order);
            $title_log          = 'CUSTOMER_ORDER_CANCEL';
        }

        if ( ! $update_shop_order ) {
            $data['message'] = 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        $log_data           = array(
            'cookie'        => $_COOKIE,
            'id_shop'       => $id_order,
            'invoice'       => $shop_order->invoice,
            'cancelled_by'  => $member->username,
        ); ddm_log_action($title_log, 'SUCCESS', 'MOBILE_APP', json_encode($log_data));

        // Set the response and exit
        $this->response([
            'success'       => TRUE,
            'status'        => REST_Controller::HTTP_OK,
            'message'       => 'Produk Order berhasil dibatalkan.',
        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function inputresi_post()
    {
        // Get Input POST
        $resi           = sanitize($this->post('resi'));
        $id_agent       = sanitize($this->post('id_agent'));
        $id_order       = sanitize($this->post('id_order'));
        $password       = sanitize($this->post('password'));

        $response       = array(
            'success'   => FALSE,
            'status'    => REST_Controller::HTTP_NOT_FOUND,
            'message'   => 'Data Produk Order tidak ditemukan'
        );

        ## Validation Global --------------------------------------------------------------
        $this->form_validation->set_rules('resi', 'resi', 'required');
        $this->form_validation->set_rules('id_agent', 'id_agent', 'required');
        $this->form_validation->set_rules('id_order', 'id_order', 'required');
        $this->form_validation->set_rules('password', 'password', 'required');
        
        $this->form_validation->set_error_delimiters('', ' ');
        if ($this->form_validation->run() == FALSE) {
            // Set the response and exit
            $response['message'] = validation_errors();
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        // Check Data Agent
        $member = ddm_get_memberdata_by_id($id_agent);
        if ( ! $member ) {
            $response['message'] = 'Agen tidak valid';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        $shop_order = $this->Model_Shop->get_shop_order_customer_by('id', $id_order);
        if ( ! $shop_order ) {
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( $shop_order->id_member !== $member->id ) {
            $response['message'] = 'Maaf, Anda tidak dapat input resi pesanan ini !';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        $password           = trim($password);
        $password_md5       = md5($password);
        $pwd_valid          = false;

        if ( $password_md5 == $member->password ) {
            $pwd_valid      = true;
        }

        if ( ddm_hash_verify($password, $member->password) ) {
            $pwd_valid      = true;
        }

        if ( !$pwd_valid ) {
            $response['message'] = 'Maaf, Password anda tidak valid !';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( !empty(trim($shop_order->resi)) ) {
            $response['message'] = 'Nomor RESI sudah dibuat untuk pesanan ini.';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( $shop_order->status == 2 ) {
            $response['message'] = 'Status Pesanan sudah dibatalkan (cancelled).';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( $shop_order->status != 1 ) {
            $response['message'] = 'Pesanan belum dikonfirmasi. Silahkan Konfirmasi Pesanan terlebih dahulu!';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        // Update status shop order
        $datetime           = date('Y-m-d H:i:s');
        $data_order         = array(
            'resi'          => $resi,
            'datesent'      => $datetime,
            'modified_by'   => $member->username,
        );

        $update_shop_order = $this->Model_Shop->update_data_shop_order_customer($id_order, $data_order);
        if ( ! $update_shop_order ) {
            $data['message'] = 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        $log_data           = array(
            'cookie'        => $_COOKIE,
            'id_shop'       => $id_order,
            'invoice'       => $shop_order->invoice,
            'modified_by'   => $member->username,
        ); ddm_log_action('CUSTOMER_INPUT_RESI', 'SUCCESS', 'MOBILE_APP', json_encode($log_data));

        // Set the response and exit
        $this->response([
            'success'       => TRUE,
            'status'        => REST_Controller::HTTP_OK,
            'message'       => 'Resi berhasil dibuat.',
        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function trackingorder_post()
    {
        // Get Input POST
        $invoice        = sanitize($this->post('invoice'));
        $email          = sanitize($this->post('email'));

        $response       = array(
            'success'   => FALSE,
            'status'    => REST_Controller::HTTP_NOT_FOUND,
            'message'   => 'Data Produk Order tidak ditemukan'
        );

        ## Validation Global --------------------------------------------------------------
        $this->form_validation->set_rules('invoice', 'invoice', 'required');
        $this->form_validation->set_rules('email', 'email', 'required');
        
        $this->form_validation->set_error_delimiters('', ' ');
        if ($this->form_validation->run() == FALSE) {
            // Set the response and exit
            $response['message'] = validation_errors();
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        // Check Invoice
        $invoice        = trim($invoice);
        $checkOrder     = explode('/', $invoice);
        $condition      = array('email' => trim($email));

        if ( count($checkOrder) > 2) {
            $getOrder = $this->Model_Shop->get_shop_order_customer_by ('invoice', $invoice, $condition);
            $orderBy  = 'customer';
        } else {
            $getOrder = $this->Model_Shop->get_shop_order_by ('invoice', $invoice, $condition);
            $orderBy  = 'agent';
        }

        if ( ! $getOrder ) {
            $response['type_order'] = $orderBy;
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        $condition  = array('type' => 'shop');
        if ( $orderBy == 'agent' ) {
            $order = $this->Model_Shop->get_shop_order_by ('id', $getOrder->id);
            $paymentEvidence = $this->Model_Shop->get_payment_evidence_by('id_source', $getOrder->id, $condition, 1);
        } else {
            $order = $this->Model_Shop->get_shop_order_customer_by ('id', $getOrder->id);
            $paymentEvidence = false;
        }

        $status_sent = false;
        if ( $getOrder->status == 1 && $getOrder->datesent && $getOrder->datesent != '0000-00-00 00:00:00' ) {
            $status_sent = true;
        }

        $number_resi    = '';
        $status_order   = status_order($getOrder->id, $getOrder->status, $orderBy, $status_sent);

        $info_address   = array(
            'name'          => $getOrder->name,
            'phone'         => $getOrder->phone,
            'address'       => $getOrder->address,
            'subdistrict'   => $getOrder->subdistrict,
            'city'          => $getOrder->city,
            'province'      => $getOrder->province,
            'postcode'      => $getOrder->postcode,
        );

        $info_courier   = array(
            'courier'       => strtoupper($getOrder->courier),
            'service'       => strtoupper($getOrder->service),
        );

        $data_history   = array(
            'created'   => array(
                'name'          => 'Pesanan Dibuat',
                'description'   => 'Pesanan anda berhasil dibuat',
                'date'          => $getOrder->datecreated
            )
        );

        if ( $paymentEvidence ) {
            $data_history   += array(
                'confirmed_payment' => array(
                    'name'          => 'Pembayaran berhasil',
                    'description'   => 'Konfirmasi pembayaran berhasil. Pesanan sedang dalam proses.',
                    'date'          => $paymentEvidence->datecreated
                )
            );
        }

        if ( $order->status == 0 ) {
            // Check expired
            $currdate   = date('Y-m-d H:i:s');
            $timediff   = strtotime($currdate) - strtotime($getOrder->datecreated);
            if ( $timediff > 86400 && !$paymentEvidence ) {
                $datecancelled  = date('Y-m-d H:i:s', strtotime($getOrder->datecreated. ' +1 day'));
                $data_history   += array(
                    'cancelled' => array(
                        'name'          => 'Pesanan Dibatalkan',
                        'description'   => 'Pesanan anda dibatalkan. Pesanan anda sudah kadaluarsa.',
                        'date'          => $datecancelled
                    )
                );
            }

        } else if ( $order->status == 2 ) {
            $data_history   += array(
                'cancelled' => array(
                    'name'          => 'Pesanan Dibatalkan',
                    'description'   => 'Pesanan anda telah dibatalkan.',
                    'date'          => $getOrder->datemodified
                )
            );
        } else if ( $order->status == 1 ) {
            $data_history   += array(
                'confirmed_order' => array(
                    'name'          => 'Pesanan Dikonfirmasi',
                    'description'   => 'Pesanan anda telah dikonfirmasi.',
                    'date'          => $getOrder->dateconfirm
                )
            );

            if ( $status_sent ) {
                $number_resi    = $getOrder->resi;
                $data_history   += array(
                    'sent' => array(
                        'name'          => 'Pesanan Dikirim',
                        'description'   => 'Pesanan anda telah dikirim dengan nomor resi '. $getOrder->resi,
                        'date'          => $getOrder->datesent
                    )
                );
            }
        }

        // Set the response and exit
        $this->response([
            'success'           => TRUE,
            'status'            => REST_Controller::HTTP_OK,
            'message'           => 'Data Produk Order ditemukan',
            'invoce'            => $invoice,
            'type_order'        => $orderBy,
            'status_order'      => $status_order,
            'resi'              => $number_resi,
            'shipping_address'  => $info_address,
            'shipping_courier'  => $info_courier,
            'tracking_order'    => $data_history,
        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

}
