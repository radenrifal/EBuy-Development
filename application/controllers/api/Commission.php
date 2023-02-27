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
class Commission extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
    }

    public function typebonus_post()
    {
        $bonus_type = config_item('bonus_type');
        if ( $bonus_type ) {
            // Set the response and exit
            $this->response([
                'success'       => TRUE,
                'status'        => REST_Controller::HTTP_OK,
                'message'       => 'Data Tipe Bonus ditemukan',
                'data'          => $bonus_type,
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'success'       => FALSE,
                'status'        => REST_Controller::HTTP_NOT_FOUND,
                'message'       => 'Data Tipe Bonus ditemukan',
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function bonus_post()
    {
        $id_agent       = $this->post('id_agent');
        $response       = array(
            'success'   => FALSE,
            'status'    => REST_Controller::HTTP_NOT_FOUND,
            'message'   => 'Data Bonus tidak ditemukan'
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

        $condition      = '';
        $order_by       = '';

        // Get Condition POST
        $date_min       = $this->post('date_min');
        $date_max       = $this->post('date_max');
        $nominal_min    = $this->post('nominal_min');
        $nominal_max    = $this->post('nominal_max');
        $type           = $this->post('type');
        $description    = $this->post('description');

        $limit          = $this->post('limit');
        $offset         = $this->post('offset');
        $sortBy         = $this->post('sortby');
        $orderBy        = $this->post('orderby');

        if ( !empty($date_min) )    { $condition .= ' AND DATE(%datecreated%) >= "'.$date_min.'"'; }
        if ( !empty($date_max) )    { $condition .= ' AND DATE(%datecreated%) <= "'.$date_max.'"'; }
        if ( !empty($nominal_min) ) { $condition .= ' AND %amount% >= '.$nominal_min.''; }
        if ( !empty($nominal_max) ) { $condition .= ' AND %amount% <= '.$nominal_max.''; }
        if ( !empty($type) )        { $condition .= str_replace('%s%', $type, ' AND %type% = "%s%"'); }
        if ( !empty($description) ) { $condition .= str_replace('%s%', $description, ' AND %desc% LIKE "%%s%%"'); }

        if ( $sortBy && $orderBy ) {
            if ( strtolower($sortBy) == 'date')         { $order_by = '%datecreated% '. $orderBy; }
            if ( strtolower($sortBy) == 'nominal')      { $order_by = '%amount% '. $orderBy; }
            if ( strtolower($sortBy) == 'type')         { $order_by = '%type% '. $orderBy; }
            if ( strtolower($sortBy) == 'description')  { $order_by = '%desc% '. $orderBy; }
        }

        $total_data     = 0;
        $results        = array();
        $data_list      = $this->Model_Bonus->get_all_my_bonus($member->id, $limit, $offset, $condition, $order_by);
        $currency       = config_item('currency');
        $cfg_bonus_type = config_item('bonus_type');

        if ( $data_list ) {
            $total_data     = ddm_get_last_found_rows();
            $i = $offset + 1;
            foreach ($data_list as $key => $row) {
                $type       = $row->type;
                if ( $cfg_bonus_type ) {
                    foreach ($cfg_bonus_type as $key => $bonus_type) {
                        if ( $key == $row->type ) {
                            $type = strtoupper($bonus_type);
                        }
                    }
                }
                
                $results[] = array(
                    'id_agent'      => $row->id_member,
                    'date'          => $row->datecreated,
                    'type'          => $type,
                    'nominal'       => ddm_accounting($row->amount, $currency),
                    'description'   => $row->desc,
                );
            }
        }

        $bonus_total        = $this->Model_Bonus->get_total_bonus_member($member->id);

        // Set the response and exit
        $this->response([
            'success'       => TRUE,
            'status'        => REST_Controller::HTTP_OK,
            'message'       => ( $total_data > 1 ) ? 'Data Bonus ditemukan' : 'Data Bonus tidak ditemukan',
            'total_bonus'   => ddm_accounting($bonus_total, $currency),
            'type_bonus'    => $cfg_bonus_type,
            'total_data'    =>  ( $results ) ? $total_data : 0,
            'data'          => $results,
        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function deposite_post()
    {
        $id_agent       = $this->post('id_agent');
        $response       = array(
            'success'   => FALSE,
            'status'    => REST_Controller::HTTP_NOT_FOUND,
            'message'   => 'Data Deposite tidak ditemukan'
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

        $condition      = ' AND %id_member% = ' . $member->id;
        $order_by       = '';

        // Get Condition POST
        $date_min       = $this->post('date_min');
        $date_max       = $this->post('date_max');
        $nominal_min    = $this->post('nominal_min');
        $nominal_max    = $this->post('nominal_max');
        $type           = $this->post('type');
        $status         = $this->post('status');
        $description    = $this->post('description');

        $limit          = $this->post('limit');
        $offset         = $this->post('offset');
        $sortBy         = $this->post('sortby');
        $orderBy        = $this->post('orderby');

        if ( !empty($date_min) )    { $condition .= ' AND DATE(%datecreated%) >= "'.$date_min.'"'; }
        if ( !empty($date_max) )    { $condition .= ' AND DATE(%datecreated%) <= "'.$date_max.'"'; }
        if ( !empty($nominal_min) ) { $condition .= ' AND %amount% >= '.$nominal_min.''; }
        if ( !empty($nominal_max) ) { $condition .= ' AND %amount% <= '.$nominal_max.''; }
        if ( !empty($type) )        { $condition .= str_replace('%s%', $type, ' AND %source% = "%s%"'); }
        if ( !empty($status) )      { $condition .= str_replace('%s%', $status, ' AND %type% = "%s%"'); }
        if ( !empty($description) ) { $condition .= str_replace('%s%', $description, ' AND %description% LIKE "%%s%%"'); }

        if ( $sortBy && $orderBy ) {
            if ( strtolower($sortBy) == 'date')         { $order_by = '%datecreated% '. $orderBy; }
            if ( strtolower($sortBy) == 'nominal')      { $order_by = '%amount% '. $orderBy; }
            if ( strtolower($sortBy) == 'type')         { $order_by = '%source% '. $orderBy; }
            if ( strtolower($sortBy) == 'status')       { $order_by = '%type% '. $orderBy; }
            if ( strtolower($sortBy) == 'description')  { $order_by = '%description% '. $orderBy; }
        }

        $total_data     = 0;
        $results        = array();
        $status_deposit = array('IN','OUT');
        $type_deposite  = array('bonus','withdraw','register');
        $data_list      = $this->Model_Bonus->get_all_ewallet_member($limit, $offset, $condition, $order_by);
        $currency       = config_item('currency');

        if ( $data_list ) {
            $total_data     = ddm_get_last_found_rows();
            $i = $offset + 1;
            foreach ($data_list as $key => $row) {
                $type   = strtoupper($row->source);
                $status = strtoupper($row->type);
                
                $results[] = array(
                    'id_agent'      => $row->id_member,
                    'date'          => $row->datecreated,
                    'type'          => $type,
                    'status'        => $status,
                    'nominal'       => ddm_accounting($row->amount, $currency),
                    'description'   => $row->description,
                );
            }
        }

        $deposite_in        = $this->Model_Bonus->get_ewallet_total($member->id, 'IN'); 
        $deposite_out       = $this->Model_Bonus->get_ewallet_total($member->id, 'OUT');
        $deposite_saldo     = $deposite_in - $deposite_out;

        // Set the response and exit
        $this->response([
            'success'           => TRUE,
            'status'            => REST_Controller::HTTP_OK,
            'message'           => ( $total_data > 1 ) ? 'Data Deposite ditemukan' : 'Data Deposite tidak ditemukan',
            'deposite_in'       => ddm_accounting($deposite_in, $currency),
            'deposite_out'      => ddm_accounting($deposite_out, $currency),
            'deposite_saldo'    => ddm_accounting($deposite_saldo, $currency),
            'type_deposite'     => $type_deposite,
            'status_deposite'   => $status_deposit,
            'total_data'        => ( $results ) ? $total_data : 0,
            'data'              => $results,
        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function withdraw_post()
    {
        $id_agent       = $this->post('id_agent');
        $response       = array(
            'success'   => FALSE,
            'status'    => REST_Controller::HTTP_NOT_FOUND,
            'message'   => 'Data Bonus tidak ditemukan'
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

        $condition          = ' WHERE %id_member% = ' . $member->id;
        $order_by           = '';

        // Get Condition POST
        $date_min           = $this->post('date_min');
        $date_max           = $this->post('date_max');
        $date_confirm_min   = $this->post('date_confirm_min');
        $date_confirm_max   = $this->post('date_confirm_max');
        $nominal_min        = $this->post('nominal_min');
        $nominal_max        = $this->post('nominal_max');
        $bank               = $this->post('bank');
        $billno             = $this->post('billno');
        $billname           = $this->post('billname');
        $bank               = $this->post('bank');
        $status             = $this->post('status');

        $limit              = $this->post('limit');
        $offset             = $this->post('offset');
        $sortBy             = $this->post('sortby');
        $orderBy            = $this->post('orderby');

        if ( !empty($date_min) )            { $condition .= ' AND DATE(%datecreated%) >= "'.$date_min.'"'; }
        if ( !empty($date_max) )            { $condition .= ' AND DATE(%datecreated%) <= "'.$date_max.'"'; }
        if ( !empty($date_confirm_min) )    { $condition .= ' AND DATE(%dateconfirm%) >= "'.$date_confirm_min.'"'; }
        if ( !empty($date_confirm_max) )    { $condition .= ' AND DATE(%dateconfirm%) <= "'.$date_confirm_max.'"'; }
        if ( !empty($nominal_min) )         { $condition .= ' AND %nominal_receipt% >= '.$nominal_min.''; }
        if ( !empty($nominal_max) )         { $condition .= ' AND %nominal_receipt% <= '.$nominal_max.''; }
        if ( !empty($bank) )                { $condition .= str_replace('%s%', $bank, ' AND %bank% = "%s%"'); }
        if ( !empty($billno) )              { $condition .= str_replace('%s%', $billno, ' AND %bill% LIKE "%%s%%"'); }
        if ( !empty($billname) )            { $condition .= str_replace('%s%', $billname, ' AND %bill_name% LIKE "%%s%%"'); }
        if ( !empty($status) )              { 
            if ( strtolower($status) == 'pending' ) {
                $condition .= str_replace('%s%', 0, ' AND %status% = %s%'); 
            } elseif ( strtolower($status) == 'transfered' ) {
                $condition .= str_replace('%s%', 1, ' AND %status% = %s%'); 
            } else {
                $condition .= str_replace('%s%', $status, ' AND %status% = "%s%"'); 
            }
        }

        if ( $sortBy && $orderBy ) {
            if ( strtolower($sortBy) == 'date')         { $order_by = '%datecreated% '. $orderBy; }
            if ( strtolower($sortBy) == 'date_confirm') { $order_by = '%dateconfirm% '. $orderBy; }
            if ( strtolower($sortBy) == 'nominal')      { $order_by = '%nominal_receipt% '. $orderBy; }
            if ( strtolower($sortBy) == 'status')       { $order_by = '%status% '. $orderBy; }
            if ( strtolower($sortBy) == 'bank')         { $order_by = '%bank% '. $orderBy; }
            if ( strtolower($sortBy) == 'billno')       { $order_by = '%bill% '. $orderBy; }
            if ( strtolower($sortBy) == 'billname')     { $order_by = '%bill_name% '. $orderBy; }
        }

        $total_data     = 0;
        $results        = array();
        $data_list      = $this->Model_Bonus->get_all_member_withdraw($limit, $offset, $condition, $order_by);
        $currency       = config_item('currency');

        if ( $data_list ) {
            $total_data     = ddm_get_last_found_rows();
            $i = $offset + 1;
            foreach ($data_list as $key => $row) {
                $bank_name          = '-';
                $bank               = ddm_banks($row->bank);
                if ( ! empty( $bank->kode ) || ! empty( $bank->nama ) ){
                    $bank_name      = strtoupper($bank->kode .' - '. $bank->nama);
                }
                $status         = ( $row->status == 1 ) ? 'TRANSFERED' : 'PENDING';
                
                $results[] = array(
                    'id_agent'      => $row->id_member,
                    'bank'          => $bank_name,
                    'billno'        => $row->bill,
                    'billname'      => strtoupper($row->bill_name),
                    'withdrawal'    => ddm_accounting($row->nominal, $currency),
                    'admin_fund'    => ddm_accounting($row->admin_fund, $currency),
                    'nominal'       => ddm_accounting($row->nominal_receipt, $currency),
                    'status'        => $status,
                    'date'          => $row->datecreated,
                    'date_confirm'  => ($row->status == 0) ? '' : $row->dateconfirm,
                );
            }
        }

        $total_withdraw         = $total_transfer = $total_bonus = $total_deposite = 0;
        if ( $data_deposite = $this->Model_Bonus->get_total_deposite_bonus($member->id) ) {
            $total_bonus        = $data_deposite->total_bonus;
            $total_withdraw     = $data_deposite->total_wd;
            $total_transfer     = $data_deposite->total_wd_transfer;
            $total_deposite     = $data_deposite->total_deposite;
        }

        // Set the response and exit
        $this->response([
            'success'           => TRUE,
            'status'            => REST_Controller::HTTP_OK,
            'message'           => ( $total_data > 1 ) ? 'Data withdraw ditemukan' : 'Data withdraw tidak ditemukan',
            'total_bonus'       => ddm_accounting($total_bonus, $currency),
            'total_withdraw'    => ddm_accounting($total_withdraw, $currency),
            'total_transfer'    => ddm_accounting($total_transfer, $currency),
            'total_deposite'    => ddm_accounting($total_deposite, $currency),
            'total_data'        => ( $results ) ? $total_data : 0,
            'data'              => $results,
        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function mydeposite_post()
    {
        $id_agent       = $this->post('id_agent');
        $response       = array(
            'success'   => FALSE,
            'status'    => REST_Controller::HTTP_NOT_FOUND,
            'message'   => 'Data Deposite tidak ditemukan'
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

        $deposite_in        = $this->Model_Bonus->get_ewallet_total($member->id, 'IN'); 
        $deposite_out       = $this->Model_Bonus->get_ewallet_total($member->id, 'OUT');
        $deposite_saldo     = $deposite_in - $deposite_out;

        // Set the response and exit
        $this->response([
            'success'       => TRUE,
            'status'        => REST_Controller::HTTP_OK,
            'message'       => 'Saldo Deposite saya ('. $member->username.')',
            'total_in'      => ($deposite_in),
            'total_out'     => ($deposite_out),
            'saldo'         => ($deposite_saldo),
        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

}
