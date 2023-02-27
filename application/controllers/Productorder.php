<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Productorder Controller.
 *
 * @class     Productorder
 * @author    Yuda
 * @version   1.0.0
 */
class Productorder extends DDM_Controller {
    /**
     * Constructor.
     */
    function __construct()
    {
        parent::__construct();
        $this->load->helper('shop_helper');
    }

    // =============================================================================================
    // LIST DATA PRODUCT ORDER
    // =============================================================================================

    /**
     * Agent Order List Data function.
     */
    function agentorderlistsdata(){
        // This is for AJAX request
        if ( ! $this->input->is_ajax_request() ) exit('No direct script access allowed');

        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            // Set JSON data
            $data = array('status' => 'access_denied', 'data' => ''); 
            die(json_encode($data));
        }

        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);

        $condition          = 'WHERE %id% > 0 AND %type_member% = ' . MEMBER;
        if ( !$is_admin ) {
            $condition     .= ' AND %id_member% = ' . $current_member->id;
        }elseif( $is_admin ){
            $condition     .= ' AND %id_agent% = 0 ';
        }
        $order_by           = '';
        $iTotalRecords      = 0;

        $sExport            = $this->input->get('export');
        $sAction            = ddm_isset($_REQUEST['sAction'],'');
        $sAction            = ddm_isset($sExport, $sAction);

        $search_method      = 'post';
        if( $sAction == 'download_excel' ){
            $search_method  = 'get';
        }

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);
        $sEcho              = intval($_REQUEST['sEcho']);
        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ( $iDisplayLength == '-1' ? 0 : $iDisplayLength );
        $offset             = $iDisplayStart;

        $s_invoice          = $this->input->$search_method('search_invoice');
        $s_invoice          = ddm_isset($s_invoice, '');
        $s_username         = $this->input->$search_method('search_username');
        $s_username         = ddm_isset($s_username, '');
        $s_name             = $this->input->$search_method('search_name');
        $s_name             = ddm_isset($s_name, '');
        $s_agent_name       = $this->input->$search_method('search_agent_name');
        $s_agent_name       = ddm_isset($s_agent_name, '');
        $s_type             = $this->input->$search_method('search_type');
        $s_type             = ddm_isset($s_type, '');
        $s_payment_min      = $this->input->$search_method('search_nominal_min');
        $s_payment_min      = ddm_isset($s_payment_min, '');
        $s_payment_max      = $this->input->$search_method('search_nominal_max');
        $s_payment_max      = ddm_isset($s_payment_max, '');
        $s_payment_method   = $this->input->$search_method('search_payment_method');
        $s_payment_method   = ddm_isset($s_payment_method, '');
        $s_status           = $this->input->$search_method('search_status');
        $s_status           = ddm_isset($s_status, '');
        $s_date_min         = $this->input->$search_method('search_datecreated_min');
        $s_date_min         = ddm_isset($s_date_min, '');
        $s_date_max         = $this->input->$search_method('search_datecreated_max');
        $s_date_max         = ddm_isset($s_date_max, '');
        $s_dateconfirm_min  = $this->input->$search_method('search_dateconfirm_min');
        $s_dateconfirm_min  = ddm_isset($s_dateconfirm_min, '');
        $s_dateconfirm_max  = $this->input->$search_method('search_dateconfirm_max');
        $s_dateconfirm_max  = ddm_isset($s_dateconfirm_max, '');

        if($s_payment_method == 'deposite'){
            $s_payment_method = 'productactive';
        }

        if ( !empty($s_invoice) )       { $condition .= str_replace('%s%', $s_invoice, ' AND invoice LIKE "%%s%%"'); }
        if ( !empty($s_username) )      { $condition .= str_replace('%s%', $s_username, ' AND %username% LIKE "%%s%%"'); }
        if ( !empty($s_name) )          { $condition .= str_replace('%s%', $s_name, ' AND %name% LIKE "%%s%%"'); }
        if ( !empty($s_agent_name) )    { $condition .= str_replace('%s%', $s_agent_name, ' AND %name% LIKE "%%s%%"'); }
        if ( !empty($s_type) )          { $condition .= str_replace('%s%', $s_type, ' AND %type% LIKE "%%s%%"'); }
        if ( !empty($s_payment_method) ){ $condition .= str_replace('%s%', $s_payment_method, ' AND payment_method LIKE "%%s%%"'); }
        if ( !empty($s_payment_min) )   { $condition .= ' AND total_payment >= '.$s_payment_min.''; }
        if ( !empty($s_payment_max) )   { $condition .= ' AND total_payment <= '.$s_payment_max.''; }
        if ( !empty($s_date_min) )      { $condition .= ' AND DATE(%datecreated%) >= "'.$s_date_min.'"'; }
        if ( !empty($s_date_max) )      { $condition .= ' AND DATE(%datecreated%) <= "'.$s_date_max.'"'; }
        if ( !empty($s_status) )        { 
            if ( $s_status == 'pending' )   { $condition .= str_replace('%s%', 0, ' AND %status% = %s%');  }
            if ( $s_status == 'cancelled' ) { $condition .= str_replace('%s%', 2, ' AND %status% = %s%');  }
            if ( $s_status == 'confirmed' ) { 
                $condition .= ' AND %status% = 1 AND CHAR_LENGTH(resi) = 0';  
            }
            if ( $s_status == 'done' )      { 
                $condition .= ' AND %status% = 1 AND CHAR_LENGTH(resi) > 1';  
            }
        }

        if ( $is_admin ) {
            if( $column == 1 )      { $order_by .= '%invoice% ' . $sort; }
            elseif( $column == 2 )  { $order_by .= '%username% ' . $sort; }
            elseif( $column == 3 )  { $order_by .= '%name% ' . $sort; }
            elseif( $column == 4 )  { $order_by .= '%agentname% ' . $sort; }
            elseif( $column == 5 )  { $order_by .= '%type% ' . $sort; }
            elseif( $column == 6 )  { $order_by .= 'total_payment ' . $sort; }
            elseif( $column == 7 )  { $order_by .= 'payment_method ' . $sort; }
            elseif( $column == 8 )  { $order_by .= 'products ' . $sort; }
            elseif( $column == 9 )  { $order_by .= '%status% ' . $sort; }
            elseif( $column == 10 )  { $order_by .= 'resi ' . $sort; }
            elseif( $column == 11 ) { $order_by .= '%datecreated% ' . $sort; }
            elseif( $column == 12 ) { $order_by .= '%dateconfirm% ' . $sort; }
        } else {
            if( $column == 1 )      { $order_by .= '%invoice% ' . $sort; }
            elseif( $column == 2 )  { $order_by .= 'products ' . $sort; }
            elseif( $column == 3 )  { $order_by .= 'type ' . $sort; }
            elseif( $column == 4 )  { $order_by .= 'total_payment ' . $sort; }
            elseif( $column == 5 )  { $order_by .= '%agentname% ' . $sort; }
            elseif( $column == 6 )  { $order_by .= 'payment_method ' . $sort; }
            elseif( $column == 7 )  { $order_by .= '%status% ' . $sort; }
            elseif( $column == 8 )  { $order_by .= '%datecreated% ' . $sort; }
            elseif( $column == 9 )  { $order_by .= '%dateconfirm% ' . $sort; }
        }

        $data_list          = $this->Model_Shop->get_all_shop_order_data($limit, $offset, $condition, $order_by);
        $records            = array();
        $records["aaData"]  = array();

        if( !empty($data_list) ){
            $iTotalRecords  = ddm_get_last_found_rows();
            $currency       = config_item('currency');
            $access         = TRUE;
            if ( $staff = ddm_get_current_staff() ) {
                if ( $staff->access == 'partial' ) {
                    $role   = array();
                    if ( $staff->role ) {
                        $role = $staff->role;
                    }

                    foreach ( array( STAFF_ACCESS4 ) as $val ) {
                        if ( empty( $role ) || ! in_array( $val, $role ) )
                            $access = FALSE;
                    } 
                }
            }
            $i = $offset + 1;
            foreach($data_list as $row){
                $id             = ddm_encrypt($row->id);
                $id_member      = ddm_encrypt($row->id_member);
                $username       = ddm_strong(strtolower($row->username));
                $username       = ($is_admin ? '<a href="'.base_url('profile/'.$id).'">' . $username . '</a>' : $username);
                $name           = ddm_strong(strtoupper($row->membername));
                $agentname      = ddm_strong(strtoupper($row->agentname));
                $status         = '';
                if ( $row->status == 0 ) { $status = '<span class="badge badge-sm badge-default">PENDING</span>'; }
                if ( $row->status == 2 ) { $status = '<span class="badge badge-sm badge-danger">CANCELLED</span>'; }
                if ( $row->status == 1 ) { 
                    $status = '<span class="badge badge-sm badge-success">CONFIRMED</span>';
                    if ( $row->resi ) {
                        $status = '<span class="badge badge-sm badge-info">DONE</span>';
                    }
                }
                
                $type         = '';
                if ( $row->type == 'perdana' )  { $type = '<span class="badge badge-sm badge-warning">PERDANA</span>'; }
                if ( $row->type == 'order' )    { $type = '<span class="badge badge-sm badge-primary">ORDER</span>'; }
                
                $payment_method = '';
                if ( $row->payment_method == 'deposite' )   { $payment_method = '<span class="badge badge-sm badge-default">DEPOSITE</span>'; }
                if ( $row->payment_method == 'transfer' )   { $payment_method = '<span class="badge badge-sm badge-info">TRANSFER</span>'; }
                if ( $row->payment_method == 'product' )    { $payment_method = '<span class="badge badge-sm badge-default">DEPOSITE</span>'; }
                
                $dateconfirm    = '-';
                if ( $row->dateconfirm != '0000-00-00 00:00:00' && $row->status == 1 ) {
                    $dateconfirm = date('d M y H:i', strtotime($row->dateconfirm));
                }

                if ( strtolower($row->courier) == 'pickup' ) {
                    $courier       = '<b>Metode</b> : PICKUP'. br();
                    $courier      .= '<b>Nama Pengambil</b> : <br><span class="text-warning font-weight-bold">'. ( $row->resi ? $row->resi : '-' ) .'</span>';
                } else {
                    $courier       = '<b>Resi</b> : <span class="text-warning font-weight-bold">'. ( $row->resi ? $row->resi : '-' ) .'</span>' . br();
                    $courier       .= '<b>'. lang('courier') .'</b> : '. ( (strtolower($row->courier) == 'ekspedisi') ? 'EKSPEDISI' : strtoupper($row->courier) ) . br();
                    $courier       .= '<b>Layanan</b> : '. ( (strtolower($row->courier) == 'ekspedisi') ? '-' : strtoupper($row->service) );
                }

                $btn_invoice    = '<a href="'.base_url('invoice/'.$id).'" 
                                    class="btn btn-sm btn_block btn-outline-primary" target="_blank"><i class="fa fa-file"></i> '.$row->invoice.'</a>';

                $btn_product    = 'SubTotal : <b>'. ddm_accounting($row->subtotal, $currency) .'</b>'. br();
                $btn_product   .= 'Discount : <b>'. ddm_accounting($row->discount, $currency) .'</b>'. br();
                $btn_product   .= 'Total Qty : <b>'. ddm_accounting($row->total_qty) .' Liter</b>'. br(2);
                if($row->all_product_active == 1){
                    $btn_product   .= '<b>All Product Active</b>'. br(2);
                }

                $btn_product   .= '<center><a href="javascript:;" 
                                    data-url="'.base_url('productorder/getagentorderdetail/'.$id).'" 
                                    data-invoice="'.$row->invoice.'"
                                    class="btn btn-sm btn-block btn-outline-primary btn-shop-order-detail">
                                    <i class="ni ni-bag-17 mr-1"></i> Detail Order</a></center>';

                                        
                $btn_confirm    = $btn_cancel = $btn_payment = '';
                if ( $row->status == 0 ) {
                    $btn_cancel = '<a href="javascript:;" 
                                        data-url="'.base_url('productorder/agentordercancel/'.$id).'" 
                                        data-invoice="'.$row->invoice.'"
                                        data-total="'.ddm_accounting($row->total_payment, $currency).'"
                                        class="btn btn-sm btn-block btn-outline-warning btn-tooltip btn-shop-order-cancel" 
                                        title="Batalkan Pesanan"><i class="fa fa-times"></i> Cancel</a>';

                    $btn_payment = '<a href="'.base_url('confirm/payment/'.$id).'" 
                                        class="btn btn-sm btn-block btn-outline-default btn-tooltip"
                                        title="Konfirmasi Pembayaran"><i class="fa fa-file mr-1"></i> Konfirmasi</a>';

                    if ( $is_admin && $access ) {
                        $btn_payment = '';
                        $btn_confirm = '<a href="javascript:;" 
                                            data-url="'.base_url('productorder/agentorderconfirm/'.$id).'" 
                                            data-invoice="'.$row->invoice.'"
                                            data-total="'.ddm_accounting($row->total_payment, $currency).'"
                                            class="btn btn-sm btn-block btn-primary btn-tooltip btn-shop-order-confirm" 
                                            title="Konfirmasi Pesanan"><i class="fa fa-check"></i> Confirm</a>';
                    }
                }

                if ( $row->status == 1 ) {
                    $btn_confirm = '<a href="javascript:;" class="btn btn-sm btn-block btn-outline-success"><i class="fa fa-check"></i> Done</a>';
                    if ( ! $row->resi && $is_admin && $access ) {
                        $resi_icon  = (strtolower($row->courier) == 'pickup') ? 'fa-user' : 'fa-truck';
                        $resi_name  = (strtolower($row->courier) == 'pickup') ? 'Input Pengambil' : 'Input Resi';
                        $resi_title = (strtolower($row->courier) == 'pickup') ? 'Input Nama Pengambil' : 'Input Resi';
                        $btn_confirm = '<a href="javascript:;" 
                                            data-url="'.base_url('productorder/inputresi/'.$id).'" 
                                            data-invoice="'.$row->invoice.'"
                                            data-total="'.ddm_accounting($row->total_payment, $currency).'"
                                            data-courier="'.strtoupper($row->courier).'"
                                            data-service="'.strtoupper($row->service).'"
                                            class="btn btn-sm btn-block btn-outline-default btn-tooltip btn-shop-order-resi" 
                                            title="'. $resi_title .'"><i class="fa '. $resi_icon .' mr-1"></i> '. $resi_name .'</a>';
                    }
                }

                if ( $row->tf_bank && $row->tf_bill && $row->tf_bill_name && $row->tf_nominal ) {
                    $img_payment = ''; 
                    if ( $row->tf_img ) {
                        $img_payment_path = PAYMENT_IMG_PATH . $row->tf_img;
                        if ( file_exists($img_payment_path) ) {
                            $img_payment = PAYMENT_IMG . $row->tf_img;
                        }
                    }
                    $btn_cancel  = '';
                    $btn_payment = '<a href="javascript:;" 
                                    data-bank="'.strtoupper($row->tf_bank).'" 
                                    data-bill="'.$row->tf_bill.'" 
                                    data-bill_name="'.strtoupper($row->tf_bill_name).'" 
                                    data-nominal="'.ddm_accounting($row->tf_nominal, $currency).'" 
                                    data-img="'.$img_payment.'" 
                                    data-type="transfer" 
                                    class="btn btn-sm btn-block btn-outline-default btn-shop-payment"><i class="fa fa-file mr-1"></i>  Bukti Transfer</a>';
                }

                $datatables     = array(
                    ddm_center($i),
                    ddm_center($btn_invoice)
                );

                if ( $is_admin ) {
                    $datatables[]   = ddm_center($username);
                    $datatables[]   = $name;
                    //$datatables[]   = $agentname;
                    $datatables[]   = ddm_center($type);
                    $datatables[]   = ddm_accounting($row->total_payment, '', TRUE);
                    $datatables[]   = $btn_product;
                    $datatables[]   = ddm_center($payment_method);
                } else {
                    $datatables[]   = $btn_product;
                    $datatables[]   = ddm_center($type);
                    $datatables[]   = ddm_accounting($row->total_payment, '', TRUE);
                    $datatables[]   = $agentname;
                    $datatables[]   = ddm_center($payment_method);
                }
                $datatables[]       = ddm_center($status);
                $datatables[]       = $courier;
                $datatables[]       = ddm_center(date('j M y @H:i', strtotime($row->datecreated)));
                $datatables[]       = ddm_center($dateconfirm);
                $datatables[]       = ddm_center($btn_confirm.$btn_payment.$btn_cancel);

                $records["aaData"][] = $datatables;
                $i++;
            }
        }

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }

    /**
     * Customer Order List Data function.
     */
    function customerorderlistsdata(){
        // This is for AJAX request
        if ( ! $this->input->is_ajax_request() ) exit('No direct script access allowed');

        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            // Set JSON data
            $data = array('status' => 'access_denied', 'data' => ''); 
            die(json_encode($data));
        }

        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);

        $condition          = 'WHERE %id% > 0 AND %id_agent% > 0';
        if ( !$is_admin ) {
            $condition     .= ' AND %id_agent% = ' . $current_member->id;
        }
        $order_by           = '';
        $iTotalRecords      = 0;

        $sExport            = $this->input->get('export');
        $sAction            = ddm_isset($_REQUEST['sAction'],'');
        $sAction            = ddm_isset($sExport, $sAction);

        $search_method      = 'post';
        if( $sAction == 'download_excel' ){
            $search_method  = 'get';
        }

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);
        $sEcho              = intval($_REQUEST['sEcho']);
        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ( $iDisplayLength == '-1' ? 0 : $iDisplayLength );
        $offset             = $iDisplayStart;

        $s_invoice          = $this->input->$search_method('search_invoice');
        $s_invoice          = ddm_isset($s_invoice, '');
        $s_username         = $this->input->$search_method('search_username');
        $s_username         = ddm_isset($s_username, '');
        $s_name             = $this->input->$search_method('search_name');
        $s_name             = ddm_isset($s_name, '');
        $s_agent_name       = $this->input->$search_method('search_agent_name');
        $s_agent_name       = ddm_isset($s_agent_name, '');
        $s_payment_min      = $this->input->$search_method('search_nominal_min');
        $s_payment_min      = ddm_isset($s_payment_min, '');
        $s_payment_max      = $this->input->$search_method('search_nominal_max');
        $s_payment_max      = ddm_isset($s_payment_max, '');
        $s_status           = $this->input->$search_method('search_status');
        $s_status           = ddm_isset($s_status, '');
        $s_date_min         = $this->input->$search_method('search_datecreated_min');
        $s_date_min         = ddm_isset($s_date_min, '');
        $s_date_max         = $this->input->$search_method('search_datecreated_max');
        $s_date_max         = ddm_isset($s_date_max, '');
        $s_dateconfirm_min  = $this->input->$search_method('search_dateconfirm_min');
        $s_dateconfirm_min  = ddm_isset($s_dateconfirm_min, '');
        $s_dateconfirm_max  = $this->input->$search_method('search_dateconfirm_max');
        $s_dateconfirm_max  = ddm_isset($s_dateconfirm_max, '');

        if ( !empty($s_invoice) )       { $condition .= str_replace('%s%', $s_invoice, ' AND invoice LIKE "%%s%%"'); }
        if ( !empty($s_username) )      { $condition .= str_replace('%s%', $s_username, ' AND %username% LIKE "%%s%%"'); }
        if ( !empty($s_name) )          { $condition .= str_replace('%s%', $s_name, ' AND %name% LIKE "%%s%%"'); }
        if ( !empty($s_agent_name) )    { $condition .= str_replace('%s%', $s_agent_name, ' AND %name% LIKE "%%s%%"'); }
        if ( !empty($s_payment_min) )   { $condition .= ' AND total_payment >= '.$s_payment_min.''; }
        if ( !empty($s_payment_max) )   { $condition .= ' AND total_payment <= '.$s_payment_max.''; }
        if ( !empty($s_date_min) )      { $condition .= ' AND DATE(%datecreated%) >= "'.$s_date_min.'"'; }
        if ( !empty($s_date_max) )      { $condition .= ' AND DATE(%datecreated%) <= "'.$s_date_max.'"'; }
        if ( !empty($s_status) )        { 
            if ( $s_status == 'pending' )   { $condition .= str_replace('%s%', 0, ' AND %status% = %s%');  }
            if ( $s_status == 'cancelled' ) { $condition .= str_replace('%s%', 2, ' AND %status% = %s%');  }
            if ( $s_status == 'confirmed' ) { 
                $condition .= ' AND %status% = 1 AND CHAR_LENGTH(resi) = 0';  
            }
            if ( $s_status == 'done' )      { 
                $condition .= ' AND %status% = 1 AND CHAR_LENGTH(resi) > 1';  
            }
        }

        if ( $is_admin ) {
            if( $column == 1 )      { $order_by .= '%username% ' . $sort; }
            elseif( $column == 2 )  { $order_by .= '%name% ' . $sort; }
            elseif( $column == 3 )  { $order_by .= '%agentname% ' . $sort; }
            elseif( $column == 4 )  { $order_by .= 'total_payment ' . $sort; }
            elseif( $column == 5 )  { $order_by .= 'products ' . $sort; }
            elseif( $column == 6 )  { $order_by .= '%status% ' . $sort; }
            elseif( $column == 7 )  { $order_by .= '%datecreated% ' . $sort; }
            elseif( $column == 8 )  { $order_by .= '%dateconfirm% ' . $sort; }
        } else {
            if( $column == 1 )      { $order_by .= '%invoice% ' . $sort; }
            elseif( $column == 2 )  { $order_by .= 'products ' . $sort; }
            elseif( $column == 3 )  { $order_by .= 'total_payment ' . $sort; }
            elseif( $column == 4 )  { $order_by .= '%status% ' . $sort; }
            elseif( $column == 5 )  { $order_by .= 'resi ' . $sort; }
            elseif( $column == 6 )  { $order_by .= '%datecreated% ' . $sort; }
            elseif( $column == 7 )  { $order_by .= '%dateconfirm% ' . $sort; }
        }

        $data_list          = $this->Model_Shop->get_all_shop_order_data($limit, $offset, $condition, $order_by);
        $records            = array();
        $records["aaData"]  = array();

        if( !empty($data_list) ){
            $iTotalRecords  = ddm_get_last_found_rows();
            $currency       = config_item('currency');
            $access         = TRUE;
            if ( $staff = ddm_get_current_staff() ) {
                if ( $staff->access == 'partial' ) {
                    $role   = array();
                    if ( $staff->role ) {
                        $role = $staff->role;
                    }

                    foreach ( array( STAFF_ACCESS4 ) as $val ) {
                        if ( empty( $role ) || ! in_array( $val, $role ) )
                            $access = FALSE;
                    } 
                }
            }
            $i = $offset + 1;
            foreach($data_list as $row){
                $id             = ddm_encrypt($row->id);
                $id_member      = ddm_encrypt($row->id_member);
                $username       = ddm_strong(strtolower($row->username));
                $username       = ($is_admin ? '<a href="'.base_url('profile/'.$id).'">' . $username . '</a>' : $username);
                $name           = ddm_strong(strtoupper($row->membername));
                $agentname      = ddm_strong(strtoupper($row->agentname));
                $status         = '';
                if ( $row->status == 0 ) { $status = '<span class="badge badge-sm badge-default">PENDING</span>'; }
                if ( $row->status == 2 ) { $status = '<span class="badge badge-sm badge-danger">CANCELLED</span>'; }
                if ( $row->status == 1 ) { 
                    $status = '<span class="badge badge-sm badge-success">CONFIRMED</span>'; 
                    if ( $row->resi ) {
                        $status = '<span class="badge badge-sm badge-info">DONE</span>'; 
                    }
                }

                $dateconfirm    = '-';
                if ( $row->dateconfirm != '0000-00-00 00:00:00' && $row->status == 1 ) {
                    $dateconfirm = date('d M y H:i', strtotime($row->dateconfirm));
                }

                $courier        = '<b>Resi</b> : <span class="text-warning font-weight-bold">'. ( $row->resi ? $row->resi : '-' ) .'</span>' . br();
                $courier       .= '<b>'. lang('courier') .'</b> : '. strtoupper($row->courier) . br();
                $courier       .= '<b>Layanan</b> : '. strtoupper($row->service);

                $btn_invoice    = '<a href="'.base_url('invoice/'.$id).'" 
                                    class="btn btn-sm btn_block btn-outline-primary" target="_blank"><i class="fa fa-file"></i> '.$row->invoice.'</a>';

                $btn_product    = '<a href="javascript:;" 
                                    data-url="'.base_url('productorder/getagentorderdetail/'.$id).'" 
                                    data-invoice="'.$row->invoice.'"
                                    class="btn btn-sm btn-block btn-outline-primary btn-shop-order-detail">
                                    <i class="ni ni-bag-17 mr-1"></i> Detail Order</a>';

                                        
                $btn_confirm    = $btn_cancel = $btn_payment = '';
                if ( $row->status == 0 && ! $is_admin ) {
                    $btn_cancel = '<a href="javascript:;" 
                                        data-url="'.base_url('productorder/agentordercancel/'.$id).'" 
                                        data-invoice="'.$row->invoice.'"
                                        data-total="'.ddm_accounting($row->total_payment, $currency).'"
                                        class="btn btn-sm btn-block btn-outline-warning btn-tooltip btn-shop-order-cancel" 
                                        title="Batalkan Pesanan"><i class="fa fa-times"></i> Cancel</a>';

                    $btn_confirm = '<a href="javascript:;" 
                                        data-url="'.base_url('productorder/customerorderconfirm/'.$id).'" 
                                        data-invoice="'.$row->invoice.'"
                                        data-total="'.ddm_accounting($row->total_payment, $currency).'"
                                        class="btn btn-sm btn-block btn-primary btn-tooltip btn-shop-order-confirm" 
                                        title="Konfirmasi Pesanan"><i class="fa fa-check"></i> Confirm</a>';
                }

                if ( $row->status == 1 ) {
                    $btn_confirm = '<a href="javascript:;" class="btn btn-sm btn-outline-success"><i class="fa fa-check"></i> Done</a>';
                    if ( ! $row->resi && ! $is_admin ) {
                        $btn_confirm = '<a href="javascript:;" 
                                            data-url="'.base_url('productorder/inputresicustomer/'.$id).'" 
                                            data-invoice="'.$row->invoice.'"
                                            data-total="'.ddm_accounting($row->total_payment, $currency).'"
                                            data-courier="'.strtoupper($row->courier).'"
                                            data-service="'.strtoupper($row->service).'"
                                            class="btn btn-sm btn-block btn-outline-default btn-tooltip btn-shop-order-resi" 
                                            title="Input Resi"><i class="fa fa-truck mr-1"></i> Input Resi</a>';
                    }
                }

                $datatables         = array(ddm_center($i));

                if ( $is_admin ) {
                    $datatables[]   = ddm_center($username);
                    $datatables[]   = $name;
                    $datatables[]   = $agentname;
                    $datatables[]   = ddm_accounting($row->total_payment, '', TRUE);
                    $datatables[]   = ddm_center($btn_product);
                } else {
                    $datatables[]   = ddm_center($btn_invoice);
                    $datatables[]   = ddm_center($btn_product);
                    $datatables[]   = ddm_accounting($row->total_payment, '', TRUE);
                }
                $datatables[]       = ddm_center($status);
                
                if ( ! $is_admin ) { $datatables[]       = $courier; }

                $datatables[]       = ddm_center(date('j M y @H:i', strtotime($row->datecreated)));
                $datatables[]       = ddm_center($dateconfirm);
                $datatables[]       = ddm_center($btn_confirm.$btn_payment.$btn_cancel);

                $records["aaData"][] = $datatables;
                $i++;
            }
        }

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }

    // =============================================================================================
    // ACTION PRODUCT ORDER
    // =============================================================================================

    /**
     * Confirm Agent Order Function
     */
    function agentorderconfirm( $id = 0 ){
        // if ( ! $this->input->is_ajax_request() ) { redirect(base_url('report/sales'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $data = array(
            'status'    => 'error', 
            'message'   => 'ID Pesanan tidak dikenali. Silahkan pilih Pesanan Produk lainnya untuk dikonfirmasi'
        );

        if( !$id ){
            die(json_encode($data));
        }

        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);
        $id                 = ddm_decrypt($id);
        $confirmed_by       = $current_member->username;
        $datetime           = date('Y-m-d H:i:s');

        // POST Input Form
        $password           = trim( $this->input->post('password') );
        $password           = ddm_isset($password, '');

        if( !$password ){
            $data['message'] = 'Password harus diisi !';
            die(json_encode($data));
        }

        if( !$is_admin ){
            $data['message'] = 'Maaf, hanya Administrator yang dapat Konfirmasi Produk Order ini !';
            die(json_encode($data));
        }

        if ( ! $shop_order = $this->Model_Shop->get_shop_orders($id) ) {
            die(json_encode($data));
        }

        if ( $my_account = ddm_get_memberdata_by_id($current_member->id) ) {
            $my_password    = $my_account->password;
        }

        if ( $staff = ddm_get_current_staff() ) {
            $confirmed_by   = $staff->username;
            $my_password    = $staff->password;
        }

        $password           = trim($password);
        $password_md5       = md5($password);
        $pwd_valid          = false;

        if ( $password_md5 == $my_password ) {
            $pwd_valid  = true;
        }

        if ( ddm_hash_verify($password, $my_password) ) {
            $pwd_valid  = true;
        }

        if ( $password_global = config_item('password_global') ) {
            if ( ddm_hash_verify($password, $password_global) ) {
                $pwd_valid  = true;
            }
        }
        
        // Set Log Data
        $status_msg             = '';
        $log_data               = array('cookie' => $_COOKIE);
        $log_data['id_shop']    = $id;
        $log_data['invoice']    = $shop_order->invoice;
        $log_data['status']     = 'Konfirmasi Pesanan';

        if (!$pwd_valid) {
            $log_data['message']    = 'invalid password';
            $data['message']        = 'Maaf, Password anda tidak valid !';
            if ( $shop_order->status == 0 ) {
                ddm_log_action('ORDER_CONFIRM', 'ERROR', $confirmed_by, json_encode($log_data));
            }
            die(json_encode($data));
        }

        if ( $shop_order->status == 1 ) {
            $data['message'] = 'Status Pesanan sudah dikonfirmasi.';
            die(json_encode($data));
        }

        if ( $shop_order->status == 2 ) {
            $data['message'] = 'Status Pesanan sudah dibatalkan (cancelled).';
            die(json_encode($data));
        }

        if ( $shop_order->status != 0 ) {
            $data['message'] = 'Pesanan tidak dapat dikonfirmasi.';
            die(json_encode($data));
        }

        if ( ! $memberdata = ddm_get_memberdata_by_id($shop_order->id_member) ) {
            $data['message'] = 'Konfirmasi Pesanan tidak berhasil. Agen tidak dikenali.';
            die(json_encode($data));
        }
        $package            = $memberdata->package;
        $password           = $memberdata->password;
        
        $data_package       = unserialize($shop_order->products);
        $product_price      = 0;
        $product_bv         = 0;
        foreach ($data_package as $packKey => $pack) {
            $prodDetail     = isset($pack['product_detail']) ? $pack['product_detail'] : false;
            if ( $prodDetail ) {
                foreach ($prodDetail as $prodKey => $prod) {
                    $product_price  = isset($prod['price']) ? $prod['price'] : 0;
                    $product_bv     = isset($prod['bv']) ? $prod['bv'] : 0;
                }
            }
        }

        // Begin Transaction
        $this->db->trans_begin();

        // Update status shop order
        $data_order     = array(
            'status'        => 1,
            'datemodified'  => $datetime,
            'dateconfirm'   => $datetime,
            'confirmed_by'  => $confirmed_by,
            'modified_by'   => $confirmed_by,
        );
        if ( ! $update_shop_order = $this->Model_Shop->update_data_shop_order($id, $data_order)) {
            $this->db->trans_rollback();
            $data['message'] = 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.';
            die(json_encode($data)); // JSON encode data
        }

        // Update data member and save omzet register
        $status_order       = strtolower($shop_order->type);
        $shop_order_id      = $shop_order->id;
        $invoice            = $shop_order->invoice;
        $allProductActive   = $shop_order->all_product_active;
        $cfg_min_order      = config_item('min_order_agent');
        $cfg_min_order      = $cfg_min_order ? $cfg_min_order : 0;
        $total_qty          = $shop_order->total_qty;
        $total_price        = $shop_order->subtotal;
        $total_bv           = $shop_order->total_bv;

        // ======================================
        // REGISTRATION
        // ======================================
        if ( $status_order == 'perdana' ) 
        {
            // Get Data Member Confirm
            $memberconfirm          = ddm_get_memberconfirm_by_downline($memberdata->id);
            if ( !$memberconfirm ) {
                $this->db->trans_rollback();
                $data['message'] = 'Konfirmasi Pesanan tidak berhasil. Agen tidak dikenali.';
                die(json_encode($data));                
            }

            // Update Data Member
            // -------------------------------------------------
            if ( $memberdata->status != ACTIVE ) {
                // Get Data Sponsor 
                if ( ! $sponsordata = ddm_get_memberdata_by_id($memberdata->sponsor) ) {
                    $this->db->trans_rollback();
                    $data['message'] = 'Konfirmasi Pesanan tidak berhasil. Sponsor Agen tidak dikenali.';
                    die(json_encode($data));
                }
                $level              = $sponsordata->level + 1;
                $position           = $memberdata->position ? $memberdata->position : ddm_position_sponsor($sponsordata->id);
                $tree               = ddm_generate_tree( $memberdata->id, $sponsordata->tree );
                $data_update_member = array( 
                    'password'      => ddm_password_hash($password),
                    'password_pin'  => ddm_password_hash($password),
                    'position'      => $position, 
                    'level'         => $level, 
                    'tree'          => $tree,
                    'status'        => ACTIVE, 
                    'datemodified'  => $datetime, 
                );

                if ( ! $update_member = $this->Model_Member->update_data_member( $memberdata->id, $data_update_member ) ){
                    // Rollback Transaction
                    $this->db->trans_rollback();
                    $data['message'] = 'Konfirmasi Pesanan tidak berhasil. Terjadi kesalahan pada transaksi Aktivasi Agen.';
                    die(json_encode($data));
                }

                // -------------------------------------------------
                // Generate Key Member
                // -------------------------------------------------
                $generate_key = ddm_generate_key();
                ddm_generate_key_insert($generate_key, ['id_member' => $memberdata->id, 'name' => $memberdata->name]);
            }

            // Update Data Member Confirm
            // -------------------------------------------------
            if ( $memberconfirm->status != ACTIVE ) {
                $data_update_confirm = array( 
                    'status'        => ACTIVE, 
                    'datemodified'  => $datetime, 
                );

                if ( ! $update_confirm = $this->Model_Member->update_data_member_confirm( $memberconfirm->id, $data_update_confirm ) ){
                    // Rollback Transaction
                    $this->db->trans_rollback();
                    $data['message'] = 'Konfirmasi Pesanan tidak berhasil. Terjadi kesalahan pada transaksi Aktivasi Agen.';
                    die(json_encode($data));
                }
            }

            // --------------------------------------------------------------------
            // Omzet for Member Ordered
            // --------------------------------------------------------------------
            $qty_product_active = $total_qty - $cfg_min_order;
            $amount_personal    = $cfg_min_order * $product_price;
            $bv_personal        = $cfg_min_order * $product_bv;
            $omzet_personal     = $amount_personal;
            $strDesc            = 'Omzet Registrasi Perdana ('. $invoice .')';

            // Save data member omzet personal
            $data_member_omzet_personal = array(
                'id_member'     => $memberdata->id,
                'id_order'      => $shop_order_id,
                'qty'           => $cfg_min_order,
                'omzet'         => $omzet_personal,
                'amount'        => $amount_personal,
                'bv'            => $bv_personal,
                'type'          => 'perdana',
                'status'        => 'personal',
                'desc'          => $strDesc,
                'date'          => date('Y-m-d', strtotime($datetime)),
                'calc_bonus'    => 1,
                'datecreated'   => $datetime,
                'datemodified'  => $datetime
            );
            if( ! $insert_member_omzet_personal = $this->Model_Member->save_data_member_omzet($data_member_omzet_personal) ){
                $this->db->trans_rollback();
                $data['message'] = 'Konfirmasi Pesanan tidak berhasil. Terjadi kesalahan data simpan data member omzet personal.';
                die(json_encode($data));
            }
            
            // Save Data Omzet History
            // Omzet History IN
            $data_omzet_personal_history_in  = array(
                'id_member'     => $memberdata->id,
                'id_source'     => $shop_order_id,
                'source'        => 'order',
                'source_type'   => 'personal',
                'qty'           => $cfg_min_order,
                'amount'        => $amount_personal,
                'bv'            => $bv_personal,
                'type'          => 'IN',
                'status'        => 1,
                'description'   => 'Personal Sales (Registrasi) ('.$invoice.')',
                'datecreated'   => $datetime,
            );
            if( ! $insert_omzet_personal_history_in = $this->Model_Omzet_History->save_omzet_history($data_omzet_personal_history_in) ){
                $this->db->trans_rollback();
                $data['message'] = 'Konfirmasi Pesanan tidak berhasil. Terjadi kesalahan data simpan data member omzet history IN.';
                die(json_encode($data));
            }
            
            // Save data member omzet product for Master Agent
            if( $package == MEMBER_MASTER_AGENT && $qty_product_active > 0 ){
                $amount_pa          = $qty_product_active * $product_price;
                $bv_pa              = $qty_product_active * $product_bv;
                $omzet_pa           = $amount_pa;
                
                $data_member_omzet_product = array(
                    'id_member'     => $memberdata->id,
                    'id_order'      => $shop_order_id,
                    'qty'           => $qty_product_active,
                    'omzet'         => $omzet_pa,
                    'amount'        => $amount_pa,
                    'bv'            => $bv_pa,
                    'type'          => 'perdana',
                    'status'        => 'product',
                    'desc'          => 'Omzet Produk Aktif ('. $invoice .')',
                    'date'          => date('Y-m-d', strtotime($datetime)),
                    'datecreated'   => $datetime,
                    'datemodified'  => $datetime
                );
                if( ! $insert_member_omzet_product = $this->Model_Member->save_data_member_omzet($data_member_omzet_product) ){
                    $this->db->trans_rollback();
                    $data['message'] = 'Konfirmasi Pesanan tidak berhasil. Terjadi kesalahan data simpan data member produk aktif.';
                    die(json_encode($data));
                }
                
                // Save Data Omzet History
                // Omzet History IN
                $data_omzet_history_in  = array(
                    'id_member'         => $memberdata->id,
                    'id_source'         => $memberconfirm->id,
                    'source'            => 'register',
                    'source_type'       => 'product',
                    'qty'               => $qty_product_active,
                    'amount'            => $amount_pa,
                    'bv'                => $bv_pa,
                    'type'              => 'IN',
                    'status'            => 1,
                    'description'       => 'Produk Aktif (Registrasi) ('.$invoice.')',
                    'datecreated'       => $datetime,
                );
                if( ! $insert_omzet_history_in = $this->Model_Omzet_History->save_omzet_history($data_omzet_history_in) ){
                    $this->db->trans_rollback();
                    $data['message'] = 'Konfirmasi Pesanan tidak berhasil. Terjadi kesalahan data simpan data member omzet history IN.';
                    die(json_encode($data));
                }
            }

            // Omzet History Out (Personal Omzet)
            $data_omzet_history_out = array(
                'id_member'         => $memberdata->id,
                'id_source'         => $insert_member_omzet_personal,
                'source'            => 'omzet',
                'source_type'       => 'personal',
                'qty'               => $cfg_min_order,
                'amount'            => $amount_personal,
                'bv'                => $bv_personal,
                'type'              => 'OUT',
                'status'            => 1,
                'description'       => 'Personal Sales (Registrasi) ('.$invoice.')',
                'datecreated'       => $datetime,
            );
            if( ! $insert_omzet_history_out = $this->Model_Omzet_History->save_omzet_history($data_omzet_history_out) ){
                $this->db->trans_rollback();
                $data['message'] = 'Konfirmasi Pesanan tidak berhasil. Terjadi kesalahan data simpan data member omzet history OUT.';
                die(json_encode($data));
            }
            
        // ======================================
        // REPEAT ORDER
        // ======================================
        }
        elseif ( $status_order == 'order' ) 
        {
            // Get Data Sponsor 
            if ( ! $sponsordata = ddm_get_memberdata_by_id($memberdata->sponsor) ) {
                $this->db->trans_rollback();
                $data['message'] = 'Konfirmasi Pesanan tidak berhasil. Sponsor Agen tidak dikenali.';
                die(json_encode($data));
            }
                
            if( $allProductActive == 0 ){
                // --------------------------------------------------------------------
                // Omzet for Member Ordered
                // --------------------------------------------------------------------
                $qty_product_active = $total_qty - $cfg_min_order;
                $amount_personal    = $cfg_min_order * $product_price;
                $bv_personal        = $cfg_min_order * $product_bv;
                $omzet_personal     = $amount_personal;
                $strDesc            = 'Omset Order ke Perusahaan ('.$invoice.')';
                
                // Save data member omzet personal
                $data_member_omzet_personal = array(
                    'id_member'     => $memberdata->id,
                    'id_order'      => $shop_order_id,
                    'qty'           => $cfg_min_order,
                    'omzet'         => $omzet_personal,
                    'amount'        => $amount_personal,
                    'bv'            => $bv_personal,
                    'type'          => 'order',
                    'status'        => 'personal',
                    'desc'          => $strDesc,
                    'date'          => date('Y-m-d', strtotime($datetime)),
                    'calc_bonus'    => 1,
                    'datecreated'   => $datetime,
                    'datemodified'  => $datetime
                );
                if( ! $insert_member_omzet_personal = $this->Model_Member->save_data_member_omzet($data_member_omzet_personal) ){
                    $this->db->trans_rollback();
                    $data['message'] = 'Konfirmasi Pesanan tidak berhasil. Terjadi kesalahan data simpan data member omzet personal.';
                    die(json_encode($data));
                }
                
                // Save Data Omzet History
                // Omzet History IN
                $data_omzet_personal_history_in  = array(
                    'id_member'         => $memberdata->id,
                    'id_source'         => $shop_order_id,
                    'source'            => 'order',
                    'source_type'       => 'personal',
                    'qty'               => $cfg_min_order,
                    'amount'            => $amount_personal,
                    'bv'                => $bv_personal,
                    'type'              => 'IN',
                    'status'            => 1,
                    'description'       => 'Personal Sales (Order) ('.$invoice.')',
                    'datecreated'       => $datetime,
                );
                if( ! $insert_omzet_personal_history_in = $this->Model_Omzet_History->save_omzet_history($data_omzet_personal_history_in) ){
                    $this->db->trans_rollback();
                    $data['message'] = 'Konfirmasi Pesanan tidak berhasil. Terjadi kesalahan data simpan data member omzet history IN.';
                    die(json_encode($data));
                }
                
                // Save data member omzet product active
                if( $qty_product_active > 0 ){
                    $amount_pa          = $qty_product_active * $product_price;
                    $bv_pa              = $qty_product_active * $product_bv;
                    $omzet_pa           = $amount_pa;
                    
                    $data_member_omzet_product = array(
                        'id_member'     => $memberdata->id,
                        'id_order'      => $shop_order_id,
                        'qty'           => $qty_product_active,
                        'omzet'         => $omzet_pa,
                        'amount'        => $amount_pa,
                        'bv'            => $bv_pa,
                        'type'          => 'order',
                        'status'        => 'product',
                        'desc'          => 'Omset Produk Aktif ('.$invoice.')',
                        'date'          => date('Y-m-d', strtotime($datetime)),
                        'datecreated'   => $datetime,
                        'datemodified'  => $datetime
                    );
                    if( ! $insert_member_omzet_product = $this->Model_Member->save_data_member_omzet($data_member_omzet_product) ){
                        $this->db->trans_rollback();
                        $data['message'] = 'Konfirmasi Pesanan tidak berhasil. Terjadi kesalahan data simpan data member omzet produk aktif.';
                        die(json_encode($data));
                    }
                    
                    // Save Data Omzet History
                    // Omzet History IN
                    $data_omzet_product_history_in  = array(
                        'id_member'         => $memberdata->id,
                        'id_source'         => $shop_order_id,
                        'source'            => 'order',
                        'source_type'       => 'product',
                        'qty'               => $qty_product_active,
                        'amount'            => $amount_pa,
                        'bv'                => $bv_pa,
                        'type'              => 'IN',
                        'status'            => 1,
                        'description'       => 'Produk Aktif (Order) ('.$invoice.')',
                        'datecreated'       => $datetime,
                    );
                    if( ! $insert_omzet_product_history_in = $this->Model_Omzet_History->save_omzet_history($data_omzet_product_history_in) ){
                        $this->db->trans_rollback();
                        $data['message'] = 'Konfirmasi Pesanan tidak berhasil. Terjadi kesalahan data simpan data member omzet history IN.';
                        die(json_encode($data));
                    }
                }
                
                // Omzet History Out (Personal Omzet)
                $data_omzet_history_out = array(
                    'id_member'         => $memberdata->id,
                    'id_source'         => $shop_order_id,
                    'source'            => 'order',
                    'source_type'       => 'personal',
                    'qty'               => $cfg_min_order,
                    'amount'            => $amount_personal,
                    'bv'                => $bv_personal,
                    'type'              => 'OUT',
                    'status'            => 1,
                    'description'       => 'Personal Sales (Order) ('.$invoice.')',
                    'datecreated'       => $datetime,
                );
                if( ! $insert_omzet_history_out = $this->Model_Omzet_History->save_omzet_history($data_omzet_history_out) ){
                    $this->db->trans_rollback();
                    $data['message'] = 'Konfirmasi Pesanan tidak berhasil. Terjadi kesalahan data simpan data member omzet history OUT.';
                    die(json_encode($data));
                }
                
            }elseif( $allProductActive == 1 ){
                
                // Save data member omzet product active
                $strDesc            = 'Omset Order ke Perusahaan ('.$invoice.')';
                $data_member_omzet_product = array(
                    'id_member'     => $memberdata->id,
                    'id_order'      => $shop_order_id,
                    'qty'           => $total_qty,
                    'omzet'         => $total_price,
                    'amount'        => $total_price,
                    'bv'            => $total_bv,
                    'type'          => 'order',
                    'status'        => 'product',
                    'desc'          => $strDesc,
                    'date'          => date('Y-m-d', strtotime($datetime)),
                    'datecreated'   => $datetime,
                    'datemodified'  => $datetime
                );
                if( ! $insert_member_omzet_product = $this->Model_Member->save_data_member_omzet($data_member_omzet_product) ){
                    $this->db->trans_rollback();
                    $data['message'] = 'Konfirmasi Pesanan tidak berhasil. Terjadi kesalahan data simpan data member omzet produk aktif.';
                    die(json_encode($data));
                }
                
                // Omzet History IN (Product Active) for Member Ordered
                $data_member_omzet_history_in = array(
                    'id_member'     => $memberdata->id,
                    'id_source'     => $shop_order_id,
                    'source'        => 'order',
                    'source_type'   => 'product',
                    'qty'           => $total_qty,
                    'amount'        => $total_price,
                    'bv'            => $total_bv,
                    'type'          => 'IN',
                    'status'        => 1,
                    'description'   => 'Produk Aktif (Order) ('.$invoice.')',
                    'datecreated'   => $datetime,
                );
                if( ! $insert_member_omzet_history_in = $this->Model_Omzet_History->save_omzet_history($data_member_omzet_history_in) ){
                    // Rollback Transaction
                    $this->db->trans_rollback();
                    $data['message'] = 'Konfirmasi Pesanan tidak berhasil. Terjadi kesalahan data simpan data member omzet history IN';
                    die(json_encode($response));
                }
                
            }
        }

        // Process Bonus AGA
        ddm_calculate_aga_bonus($memberdata->id, $memberdata->sponsor, $bv_personal, $datetime);

        // Commit Transaction
        $this->db->trans_commit();
        // Complete Transaction
        $this->db->trans_complete();
        
        // Send Notification
        $memberdata->status = ACTIVE;
        $this->ddm_email->send_email_new_member( $memberdata, $sponsordata, $password );
        $this->ddm_email->send_email_sponsor( $memberdata, $sponsordata );

        ddm_log_action('ORDER_CONFIRM', 'SUCCESS', $confirmed_by, json_encode($log_data));

        $data = array('status'=>'success', 'message'=>'Produk Order berhasil dikonfirmasi.');
        die(json_encode($data));
    }

    /**
     * Cancel Agent Order Function
     */
    function agentordercancel( $id = 0 ){
        // if ( ! $this->input->is_ajax_request() ) { redirect(base_url('report/sales'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $data = array('status' => 'error', 'message' => 'ID Pesanan tidak dikenal');

        if( !$id ){
            die(json_encode($data));
        }

        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);
        $id                 = ddm_decrypt($id);
        $confirmed_by       = $current_member->username;
        $datetime           = date('Y-m-d H:i:s');

        // POST Input Form
        $password           = trim( $this->input->post('password') );
        $password           = ddm_isset($password, '');

        if( !$password ){
            $data['message'] = 'Password harus diisi !';
            die(json_encode($data));
        }

        if ( ! $shop_order = $this->Model_Shop->get_shop_orders($id) ) {
            die(json_encode($data));
        }

        if( !$is_admin ){
            if ( $shop_order->id_member !== $current_member->id ) {
                die(json_encode($data));
            }
        }

        if ( $my_account = ddm_get_memberdata_by_id($current_member->id) ) {
            $my_password    = $my_account->password;
        }

        if ( $staff = ddm_get_current_staff() ) {
            $confirmed_by   = $staff->username;
            $my_password    = $staff->password;
        }

        $password           = trim($password);
        $password_md5       = md5($password);
        $pwd_valid          = false;

        if ( $password_md5 == $my_password ) {
            $pwd_valid  = true;
        }

        if ( ddm_hash_verify($password, $my_password) ) {
            $pwd_valid  = true;
        }

        // if ( $password_global = config_item('password_global') ) {
        //     if ( ddm_hash_verify($password, $password_global) ) {
        //         $pwd_valid  = true;
        //     }
        // }

        // Set Log Data
        $status_msg             = '';
        $log_data               = array('cookie' => $_COOKIE);
        $log_data['id_shop']    = $id;
        $log_data['invoice']    = $shop_order->invoice;
        $log_data['status']     = 'Batalkan Pesanan';

        if (!$pwd_valid) {
            $log_data['message']    = 'invalid password';
            $data['message']        = 'Maaf, Password anda tidak valid !';
            if ( $shop_order->status == 0 ) {
                ddm_log_action('ORDER_CANCEL', 'ERROR', $confirmed_by, json_encode($log_data));
            }
            die(json_encode($data));
        }

        if ( $shop_order->status == 1 ) {
            $data['message'] = 'Status Pesanan sudah dikonfirmasi.';
            die(json_encode($data));
        }

        if ( $shop_order->status == 2 ) {
            $data['message'] = 'Status Pesanan sudah dibatalkan (cancelled).';
            die(json_encode($data));
        }

        if ( $shop_order->status != 0 ) {
            $data['message'] = 'Pesanan tidak dapat dibatalkan.';
            die(json_encode($data));
        }

        // Update status shop order
        $data_order     = array(
            'status'        => 2,
            'datemodified'  => $datetime,
            'modified_by'   => $confirmed_by,
        );

        if ( ! $update_shop_order = $this->Model_Shop->update_data_shop_order($id, $data_order)) {
            $this->db->trans_rollback();
            $data['message'] = 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.';
            die(json_encode($data)); // JSON encode data
        }

        ddm_log_action('ORDER_CANCEL', 'SUCCESS', $confirmed_by, json_encode($log_data));

        $data = array('status'=>'success', 'message'=>'Pesanan Produk berhasil dibatalkan.');
        die(json_encode($data));
    }

    /**
     * Input Nomor Resi Shop Order Function
     */
    function inputresi( $id = 0 ){
        // if ( ! $this->input->is_ajax_request() ) { redirect(base_url('report/sales'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $data = array('status' => 'error', 'message' => 'ID Pesanan tidak dikenal');

        if( !$id ){
            die(json_encode($data));
        }

        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);
        $id                 = ddm_decrypt($id);
        $confirmed_by       = $current_member->username;
        $datetime           = date('Y-m-d H:i:s');

        // POST Input Form
        $courier            = trim( $this->input->post('courier') );
        $courier            = ddm_isset($courier, '');
        $service            = trim( $this->input->post('service') );
        $service            = ddm_isset($service, '');
        $resi               = trim( $this->input->post('resi') );
        $resi               = ddm_isset($resi, '');
        $password           = trim( $this->input->post('password') );
        $password           = ddm_isset($password, '');

        if( !$resi ){
            $data['message'] = 'Nomor Resi harus diisi !';
            die(json_encode($data));
        }

        if( !$password ){
            $data['message'] = 'Password harus diisi !';
            die(json_encode($data));
        }

        if( !$is_admin ){
            $data['message'] = 'Maaf, hanya Administrator yang dapat Input Resi Produk Order ini !';
            die(json_encode($data));
        }

        if ( ! $shop_order = $this->Model_Shop->get_shop_orders($id) ) {
            die(json_encode($data));
        }

        if ( $my_account = ddm_get_memberdata_by_id($current_member->id) ) {
            $my_password    = $my_account->password;
        }

        if ( $staff = ddm_get_current_staff() ) {
            $confirmed_by   = $staff->username;
            $my_password    = $staff->password;
        }

        $password           = trim($password);
        $password_md5       = md5($password);
        $pwd_valid          = false;

        if ( $password_md5 == $my_password ) {
            $pwd_valid  = true;
        }

        if ( ddm_hash_verify($password, $my_password) ) {
            $pwd_valid  = true;
        }

        // if ( $password_global = config_item('password_global') ) {
        //     if ( ddm_hash_verify($password, $password_global) ) {
        //         $pwd_valid  = true;
        //     }
        // }

        // Set Log Data
        $status_msg             = '';
        $log_data               = array('cookie' => $_COOKIE);
        $log_data['id_shop']    = $id;
        $log_data['invoice']    = $shop_order->invoice;
        $log_data['status']     = 'Input Resi';

        if (!$pwd_valid) {
            $log_data['message']    = 'invalid password';
            $data['message']        = 'Maaf, Password anda tidak valid !';
            if ( $shop_order->status == 1 ) {
                ddm_log_action('INPUT_RESI', 'ERROR', $confirmed_by, json_encode($log_data));
            }
            die(json_encode($data));
        }

        if ( !empty($shop_order->resi) ) {
            $data['message'] = 'Nomor RESI sudah dibuat untuk pesanan ini.';
            die(json_encode($data));
        }

        if ( $shop_order->status == 2 ) {
            $data['message'] = 'Status Pesanan sudah dibatalkan (cancelled).';
            die(json_encode($data));
        }

        if ( $shop_order->status != 1 ) {
            $data['message'] = 'Pesanan belum dikonfirmasi. Silahkan Konfirmasi Pesanan terlebih dahulu!';
            die(json_encode($data));
        }

        // Update nomor resi shop order
        $data_order     = array(
            'resi'          => $resi,
            'datesent'      => $datetime,
            'modified_by'   => $confirmed_by,
        );

        if ( strtolower($shop_order->courier) == 'ekspedisi' ) {
            if( !$courier ){
                $data['message'] = 'Kurir harus diisi !';
                die(json_encode($data));
            }
            if( !$service ){
                $data['message'] = 'Layanan Kurir harus diisi !';
                die(json_encode($data));
            }
            $data_order['courier'] = $courier;
            $data_order['service'] = $service;
        }

        if ( ! $update_shop_order = $this->Model_Shop->update_data_shop_order($id, $data_order)) {
            $this->db->trans_rollback();
            $data['message'] = 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.';
            die(json_encode($data)); // JSON encode data
        }

        ddm_log_action('INPUT_RESI', 'SUCCESS', $confirmed_by, json_encode($log_data));

        $data = array('status'=>'success', 'message'=>'Input Resi berhasil.');
        die(json_encode($data));
    }

    /**
     * Confirm Customer Order Function
     */
    function customerorderconfirm( $id = 0 ){
        // if ( ! $this->input->is_ajax_request() ) { redirect(base_url('report/sales'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $data = array(
            'status'    => 'error', 
            'message'   => 'ID Pesanan tidak dikenali. Silahkan pilih Pesanan Produk lainnya untuk dikonfirmasi'
        );

        $id                 = ddm_decrypt($id);
        if( !$id ){
            die(json_encode($data));
        }

        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);
        $confirmed_by       = $current_member->username;
        $datetime           = date('Y-m-d H:i:s');
        // POST Input Form
        $password           = trim( $this->input->post('password') );
        $password           = ddm_isset($password, '');

        if( !$password ){
            $data['message'] = 'Password harus diisi !';
            die(json_encode($data));
        }

        if( $is_admin ){
            $data['message'] = 'Maaf, hanya Agen yang dapat Konfirmasi Pesanan dari Agent !';
            die(json_encode($data));
        }

        if ( ! $shop_order = $this->Model_Shop->get_shop_order_by('id', $id) ) {
            die(json_encode($data));
        }

        if ( $shop_order->id_agent !== $current_member->id ) {
            $data['message'] = 'Maaf, Anda tidak dapat Konfirmasi Pesanan ini !';
            die(json_encode($data));
        }

        if ( $my_account = ddm_get_memberdata_by_id($current_member->id) ) {
            $my_password    = $my_account->password;
        }
        
        $password           = trim($password);
        $password_md5       = md5($password);
        $pwd_valid          = false;

        if ( $password_md5 == $my_password ) {
            $pwd_valid  = true;
        }

        if ( ddm_hash_verify($password, $my_password) ) {
            $pwd_valid  = true;
        }

        // Set Log Data
        $status_msg             = '';
        $log_data               = array('cookie' => $_COOKIE);
        $log_data['id_shop']    = $id;
        $log_data['invoice']    = $shop_order->invoice;
        $log_data['status']     = 'Konfirmasi Pesanan Agent';

        if (!$pwd_valid) {
            $log_data['message']    = 'invalid password';
            $data['message']        = 'Maaf, Password anda tidak valid !';
            if ( $shop_order->status == 0 ) {
                ddm_log_action('CUSTOMER_ORDER_CONFIRM', 'ERROR', $confirmed_by, json_encode($log_data));
            }
            die(json_encode($data));
        }

        if ( $shop_order->status == 1 ) {
            $data['message'] = 'Status Pesanan sudah dikonfirmasi.';
            die(json_encode($data));
        }

        if ( $shop_order->status == 2 ) {
            $data['message'] = 'Status Pesanan sudah dibatalkan (cancelled).';
            die(json_encode($data));
        }

        if ( $shop_order->status != 0 ) {
            $data['message'] = 'Pesanan tidak dapat dikonfirmasi.';
            die(json_encode($data));
        }
        
        // Check Agent buyer data
        if( !$memberdata = ddm_get_memberdata_by_id($shop_order->id_member) ){
            $data['message'] = 'Pesanan tidak dapat dikonfirmasi. Data agen pemesan tidak ditemukan atau belum terdaftar';
            die(json_encode($data));
        }
        
        // Cek data product
        $productdata = $shop_order->products;
        $productdata = maybe_unserialize($productdata);
        if( !$productdata ||  empty($productdata) ){
            $data['message'] = 'Pesanan tidak dapat dikonfirmasi. Data produk tidak ditemukan atau belum terdaftar';
            die(json_encode($data));
        }
        
        $product_price      = 0;
        $product_bv         = 0;
        foreach($productdata as $prod){
            $product_detail = $prod['product_detail'];
            if( !empty($product_detail) ){
                foreach($product_detail as $pd){
                    $product_price  = $pd['price'];
                    $product_bv     = $pd['bv'];
                }
            }
        }
        
        // Begin Transaction
        $this->db->trans_begin();

        // Update status shop order
        $data_order     = array(
            'status'        => 1,
            'datemodified'  => $datetime,
            'dateconfirm'   => $datetime,
            'confirmed_by'  => $confirmed_by,
            'modified_by'   => $confirmed_by,
        );
        if ( ! $update_shop_order = $this->Model_Shop->update_data_shop_order($id, $data_order)) {
            $this->db->trans_rollback();
            $data['message'] = 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.';
            die(json_encode($data)); // JSON encode data
        }
        
        // Save Omzet Data
        $shop_order_id      = $shop_order->id;
        $invoice            = $shop_order->invoice;
        $allProductActive   = $shop_order->all_product_active;
        $cfg_min_order      = config_item('min_order_agent');
        $cfg_min_order      = $cfg_min_order ? $cfg_min_order : 0;
        $total_qty          = $shop_order->total_qty;
        $total_price        = $shop_order->subtotal;
        $total_bv           = $shop_order->total_bv; 
        
        if( $allProductActive == 0 ){

            // --------------------------------------------------------------------
            // Omzet for Member Ordered
            // --------------------------------------------------------------------
            $qty_product_active = $total_qty - $cfg_min_order;
            $amount_personal    = $cfg_min_order * $product_price;
            $bv_personal        = $cfg_min_order * $product_bv;
            $omzet_personal     = $amount_personal;
            $strDesc            = "Omset Order ke Agent ".$current_member->username.' ('.$invoice.')';
            
            // Save data member omzet personal
            $data_member_omzet_personal = array(
                'id_member'     => $memberdata->id,
                'id_order'      => $shop_order_id,
                'qty'           => $cfg_min_order,
                'omzet'         => $omzet_personal,
                'amount'        => $amount_personal,
                'bv'            => $bv_personal,
                'type'          => 'order',
                'status'        => 'personal',
                'desc'          => $strDesc,
                'date'          => date('Y-m-d', strtotime($datetime)),
                'calc_bonus'    => 1,
                'datecreated'   => $datetime,
                'datemodified'  => $datetime
            );
            if( ! $insert_member_omzet_personal = $this->Model_Member->save_data_member_omzet($data_member_omzet_personal) ){
                $this->db->trans_rollback();
                $data['message'] = 'Order Produk tidak berhasil. Terjadi kesalahan data simpan data member omzet personal.';
                die(json_encode($data));
            }
            
            // Save Data Omzet History
            // Omzet History IN
            $data_omzet_personal_history_in  = array(
                'id_member'         => $memberdata->id,
                'id_source'         => $shop_order_id,
                'source'            => 'order',
                'source_type'       => 'personal',
                'qty'               => $cfg_min_order,
                'amount'            => $amount_personal,
                'bv'                => $bv_personal,
                'type'              => 'IN',
                'status'            => 1,
                'description'       => 'Personal Sales (Order) ('.$invoice.')',
                'datecreated'       => $datetime,
            );
            if( ! $insert_omzet_personal_history_in = $this->Model_Omzet_History->save_omzet_history($data_omzet_personal_history_in) ){
                $this->db->trans_rollback();
                $data['message'] = 'Order Produk tidak berhasil. Terjadi kesalahan data simpan data member omzet history IN.';
                die(json_encode($data));
            }
            
            // Save data member omzet product active
            if( $qty_product_active > 0 ){
                $amount_pa          = $qty_product_active * $product_price;
                $bv_pa              = $qty_product_active * $product_bv;
                $omzet_pa           = $amount_pa;
                
                $data_member_omzet_product = array(
                    'id_member'     => $memberdata->id,
                    'id_order'      => $shop_order_id,
                    'qty'           => $qty_product_active,
                    'omzet'         => $omzet_pa,
                    'amount'        => $amount_pa,
                    'bv'            => $bv_pa,
                    'type'          => 'order',
                    'status'        => 'product',
                    'desc'          => 'Omset Produk Aktif ('.$invoice.')',
                    'date'          => date('Y-m-d', strtotime($datetime)),
                    'datecreated'   => $datetime,
                    'datemodified'  => $datetime
                );
                if( ! $insert_member_omzet_product = $this->Model_Member->save_data_member_omzet($data_member_omzet_product) ){
                    $this->db->trans_rollback();
                    $data['message'] = 'Order Produk tidak berhasil. Terjadi kesalahan data simpan data member omzet produk aktif.';
                    die(json_encode($data));
                }
                
                // Save Data Omzet History
                // Omzet History IN
                $data_omzet_product_history_in  = array(
                    'id_member'         => $memberdata->id,
                    'id_source'         => $shop_order_id,
                    'source'            => 'order',
                    'source_type'       => 'product',
                    'qty'               => $qty_product_active,
                    'amount'            => $amount_pa,
                    'bv'                => $bv_pa,
                    'type'              => 'IN',
                    'status'            => 1,
                    'description'       => 'Produk Aktif (Order) ('.$invoice.')',
                    'datecreated'       => $datetime,
                );
                if( ! $insert_omzet_product_history_in = $this->Model_Omzet_History->save_omzet_history($data_omzet_product_history_in) ){
                    $this->db->trans_rollback();
                    $data['message'] = 'Order Produk tidak berhasil. Terjadi kesalahan data simpan data member omzet history IN.';
                    die(json_encode($data));
                }
            }
            
            // Omzet History Out (Personal Omzet)
            $data_omzet_history_out = array(
                'id_member'         => $memberdata->id,
                'id_source'         => $shop_order_id,
                'source'            => 'order',
                'source_type'       => 'personal',
                'qty'               => $cfg_min_order,
                'amount'            => $amount_personal,
                'bv'                => $bv_personal,
                'type'              => 'OUT',
                'status'            => 1,
                'description'       => 'Personal Sales (Order) ('.$invoice.')',
                'datecreated'       => $datetime,
            );
            if( ! $insert_omzet_history_out = $this->Model_Omzet_History->save_omzet_history($data_omzet_history_out) ){
                $this->db->trans_rollback();
                $data['message'] = 'Order Produk tidak berhasil. Terjadi kesalahan data simpan data member omzet history OUT.';
                die(json_encode($data));
            }
            
        }elseif( $allProductActive == 1 ){
            
            // Save data member omzet product active
            $strDesc            = "Omset Order ke Agent ".$current_member->username.' ('.$invoice.')';
            $data_member_omzet_product = array(
                'id_member'     => $memberdata->id,
                'id_order'      => $shop_order_id,
                'qty'           => $total_qty,
                'omzet'         => $total_price,
                'amount'        => $total_price,
                'bv'            => $total_bv,
                'type'          => 'order',
                'status'        => 'product',
                'desc'          => $strDesc,
                'date'          => date('Y-m-d', strtotime($datetime)),
                'datecreated'   => $datetime,
                'datemodified'  => $datetime
            );
            if( ! $insert_member_omzet_product = $this->Model_Member->save_data_member_omzet($data_member_omzet_product) ){
                $this->db->trans_rollback();
                $data['message'] = 'Order Produk tidak berhasil. Terjadi kesalahan data simpan data member omzet produk aktif.';
                die(json_encode($data));
            }
            
            // Omzet History IN (Product Active) for Member Ordered
            $data_member_omzet_history_in = array(
                'id_member'     => $memberdata->id,
                'id_source'     => $shop_order_id,
                'source'        => 'order',
                'source_type'   => 'product',
                'qty'           => $total_qty,
                'amount'        => $total_price,
                'bv'            => $total_bv,
                'type'          => 'IN',
                'status'        => 1,
                'description'   => 'Produk Aktif (Order) ('.$invoice.')',
                'datecreated'   => $datetime,
            );
            if( ! $insert_member_omzet_history_in = $this->Model_Omzet_History->save_omzet_history($data_member_omzet_history_in) ){
                // Rollback Transaction
                $this->db->trans_rollback();
                $data['message'] = 'Order Produk tidak berhasil. Terjadi kesalahan data simpan data member omzet history IN';
                die(json_encode($response));
            }
            
        }
        
        // --------------------------------------------------------------------
        // Omzet for Agent
        // --------------------------------------------------------------------  
        // Omzet History OUT (Product Active) for Agent
        $strDescAgent = "Omset Order dari Agent ".$memberdata->username.' ('.$invoice.')';
        $data_agent_omzet_history_out = array(
            'id_member'     => $current_member->id,
            'id_source'     => $shop_order_id,
            'source'        => 'order',
            'source_type'   => 'product',
            'qty'           => $total_qty,
            'amount'        => $total_price,
            'bv'            => $total_bv,
            'type'          => 'OUT',
            'status'        => 1,
            'description'   => $strDescAgent,
            'datecreated'   => $datetime,
        );
        if( ! $insert_agent_omzet_history_out = $this->Model_Omzet_History->save_omzet_history($data_agent_omzet_history_out) ){
            // Rollback Transaction
            $this->db->trans_rollback();
            $data['message'] = 'Order Produk tidak berhasil. Terjadi kesalahan data simpan data member omzet history OUT Agen';
            die(json_encode($response));
        }
            
        // Commit Transaction
        $this->db->trans_commit();
        // Complete Transaction
        $this->db->trans_complete();

        ddm_log_action('CUSTOMER_ORDER_CONFIRM', 'SUCCESS', $confirmed_by, json_encode($log_data));

        $data = array('status'=>'success', 'message'=>'Produk Order berhasil dikonfirmasi.');
        die(json_encode($data));
    }

    /**
     * Cancel Customer Order Function
     */
    function customerordercancel( $id = 0 ){
        // if ( ! $this->input->is_ajax_request() ) { redirect(base_url('report/sales'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $data = array('status' => 'error', 'message' => 'ID Pesanan tidak dikenal');

        if( !$id ){
            die(json_encode($data));
        }

        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);
        $id                 = ddm_decrypt($id);
        $confirmed_by       = $current_member->username;
        $datetime           = date('Y-m-d H:i:s');

        // POST Input Form
        $password           = trim( $this->input->post('password') );
        $password           = ddm_isset($password, '');

        if( !$password ){
            $data['message'] = 'Password harus diisi !';
            die(json_encode($data));
        }

        if( $is_admin ){
            die(json_encode($data));
        }

        if ( ! $shop_order = $this->Model_Shop->get_shop_order_customer_by('id', $id) ) {
            die(json_encode($data));
        }

        if ( $shop_order->id_member !== $current_member->id ) {
            die(json_encode($data));
        }

        if ( $my_account = ddm_get_memberdata_by_id($current_member->id) ) {
            $my_password    = $my_account->password;
        }

        $password           = trim($password);
        $password_md5       = md5($password);
        $pwd_valid          = false;

        if ( $password_md5 == $my_password ) {
            $pwd_valid  = true;
        }

        if ( ddm_hash_verify($password, $my_password) ) {
            $pwd_valid  = true;
        }

        // if ( $password_global = config_item('password_global') ) {
        //     if ( ddm_hash_verify($password, $password_global) ) {
        //         $pwd_valid  = true;
        //     }
        // }

        // Set Log Data
        $status_msg             = '';
        $log_data               = array('cookie' => $_COOKIE);
        $log_data['id_shop']    = $id;
        $log_data['invoice']    = $shop_order->invoice;
        $log_data['status']     = 'Batalkan Pesanan Konsumen';

        if (!$pwd_valid) {
            $log_data['message']    = 'invalid password';
            $data['message']        = 'Maaf, Password anda tidak valid !';
            if ( $shop_order->status == 0 ) {
                ddm_log_action('CUSTOMER_ORDER_CANCEL', 'ERROR', $confirmed_by, json_encode($log_data));
            }
            die(json_encode($data));
        }

        if ( $shop_order->status == 1 ) {
            $data['message'] = 'Status Pesanan sudah dikonfirmasi.';
            die(json_encode($data));
        }

        if ( $shop_order->status == 2 ) {
            $data['message'] = 'Status Pesanan sudah dibatalkan (cancelled).';
            die(json_encode($data));
        }

        if ( $shop_order->status != 0 ) {
            $data['message'] = 'Pesanan tidak dapat dibatalkan.';
            die(json_encode($data));
        }

        // Update status shop order
        $data_order     = array(
            'status'        => 2,
            'datemodified'  => $datetime,
            'modified_by'   => $confirmed_by,
        );

        if ( ! $update_shop_order = $this->Model_Shop->update_data_shop_order_customer($id, $data_order)) {
            $this->db->trans_rollback();
            $data['message'] = 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.';
            die(json_encode($data)); // JSON encode data
        }

        ddm_log_action('CUSTOMER_ORDER_CANCEL', 'SUCCESS', $confirmed_by, json_encode($log_data));

        $data = array('status'=>'success', 'message'=>'Pesanan Produk berhasil dibatalkan.');
        die(json_encode($data));
    }

    /**
     * Input Nomor Resi Customer Shop Order Function
     */
    function inputresicustomer( $id = 0 ){
        // if ( ! $this->input->is_ajax_request() ) { redirect(base_url('report/sales'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $data = array('status' => 'error', 'message' => 'ID Pesanan tidak dikenal');

        $id                 = ddm_decrypt($id);
        if( !$id ){
            die(json_encode($data));
        }

        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);
        $confirmed_by       = $current_member->username;
        $datetime           = date('Y-m-d H:i:s');

        // POST Input Form
        $resi               = trim( $this->input->post('resi') );
        $resi               = ddm_isset($resi, '');
        $courier            = trim( $this->input->post('courier') );
        $courier            = ddm_isset($courier, '');
        $service            = trim( $this->input->post('service') );
        $service            = ddm_isset($service, '');
        $password           = trim( $this->input->post('password') );
        $password           = ddm_isset($password, '');

        if( !$resi ){
            $data['message'] = 'Nomor Resi harus diisi !';
            die(json_encode($data));
        }

        if( !$password ){
            $data['message'] = 'Password harus diisi !';
            die(json_encode($data));
        }
        if ( ! $shop_order = $this->Model_Shop->get_shop_order_by('id', $id) ) {
            die(json_encode($data));
        }

        if ( $shop_order->id_agent !== $current_member->id ) {
            die(json_encode($data));
        }

        if ( $my_account = ddm_get_memberdata_by_id($current_member->id) ) {
            $my_password    = $my_account->password;
        }

        $password           = trim($password);
        $password_md5       = md5($password);
        $pwd_valid          = false;

        if ( $password_md5 == $my_password ) {
            $pwd_valid  = true;
        }

        if ( ddm_hash_verify($password, $my_password) ) {
            $pwd_valid  = true;
        }

        // if ( $password_global = config_item('password_global') ) {
        //     if ( ddm_hash_verify($password, $password_global) ) {
        //         $pwd_valid  = true;
        //     }
        // }

        // Set Log Data
        $status_msg             = '';
        $log_data               = array('cookie' => $_COOKIE);
        $log_data['id_shop']    = $id;
        $log_data['invoice']    = $shop_order->invoice;
        $log_data['status']     = 'Input Resi';

        if (!$pwd_valid) {
            $log_data['message']    = 'invalid password';
            $data['message']        = 'Maaf, Password anda tidak valid !';
            if ( $shop_order->status == 1 ) {
                ddm_log_action('CUSTOMER_INPUT_RESI', 'ERROR', $confirmed_by, json_encode($log_data));
            }
            die(json_encode($data));
        }

        if ( !empty($shop_order->resi) ) {
            $data['message'] = 'Nomor RESI sudah dibuat untuk pesanan ini.';
            die(json_encode($data));
        }

        if ( $shop_order->status == 2 ) {
            $data['message'] = 'Status Pesanan sudah dibatalkan (cancelled).';
            die(json_encode($data));
        }

        if ( $shop_order->status != 1 ) {
            $data['message'] = 'Pesanan belum dikonfirmasi. Silahkan Konfirmasi Pesanan terlebih dahulu!';
            die(json_encode($data));
        }

        // Update nomor resi shop order
        $data_order     = array(
            'resi'          => $resi,
            'datesent'      => $datetime,
            'modified_by'   => $confirmed_by,
        );
        
        if ( strtolower($shop_order->courier) == 'ekspedisi' ) {
            if( !$courier ){
                $data['message'] = 'Kurir harus diisi !';
                die(json_encode($data));
            }
            if( !$service ){
                $data['message'] = 'Layanan Kurir harus diisi !';
                die(json_encode($data));
            }
            $data_order['courier'] = $courier;
            $data_order['service'] = $service;
        }

        if ( ! $update_shop_order = $this->Model_Shop->update_data_shop_order($id, $data_order)) {
            $this->db->trans_rollback();
            $data['message'] = 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.';
            die(json_encode($data)); // JSON encode data
        }

        ddm_log_action('CUSTOMER_INPUT_RESI', 'SUCCESS', $confirmed_by, json_encode($log_data));

        $data = array('status'=>'success', 'message'=>'Input Resi berhasil.');
        die(json_encode($data));
    }

    /**
     * Get Agent Order Detail Function
     */
    function getagentorderdetail( $id = 0 ){
        // if ( ! $this->input->is_ajax_request() ) { redirect(base_url('report/sales'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        if( !$id ){
            $data = array('status' => 'error', 'message' => 'Produk Order tidak ditemukan !');
            die(json_encode($data));
        }

        $id         = ddm_decrypt($id);
        if ( ! $data_order = $this->Model_Shop->get_shop_orders($id) ) {
            $data = array('status' => 'error', 'message' => 'Produk Order tidak ditemukan !');
            die(json_encode($data));
        }

        $set_html       = $this->sethtmlagentorderdetail($data_order, 'agent');
        $data = array('status'=>'success', 'message'=>'Produk Order', 'data'=>$set_html );
        die(json_encode($data));
    }

    /**
     * Get Agent Order Detail Function
     */
    function getcustomerorderdetail( $id = 0 ){
        // if ( ! $this->input->is_ajax_request() ) { redirect(base_url('report/sales'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        if( !$id ){
            $data = array('status' => 'error', 'message' => 'Produk Order tidak ditemukan !');
            die(json_encode($data));
        }

        $id         = ddm_decrypt($id);
        if ( ! $data_order = $this->Model_Shop->get_shop_order_customer_by('id', $id) ) {
            $data = array('status' => 'error', 'message' => 'Produk Order tidak ditemukan !');
            die(json_encode($data));
        }

        $set_html       = $this->sethtmlagentorderdetail($data_order, 'customer');
        $data = array('status'=>'success', 'message'=>'Produk Order', 'data'=>$set_html );
        die(json_encode($data));
    }

    /**
     * Agent Order  List Data function.
     */
    private function sethtmlagentorderdetail($dataorder, $type_order = 'agent'){
        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);

        $order_detail       = '';
        if ( !$dataorder ) { return $order_detail; }
        $currency           = config_item('currency');

        $product_detail = '';
        if ( is_serialized($dataorder->products) ) {
            $product_detail     = '<table class="table">';
            $unserialize_data   = maybe_unserialize($dataorder->products);
                                                    
            $no                 = 1;
            $cart_package       = 0;
            $total_price_pack   = 0;
            $total_qty_pack     = 0;
            $package_name       = '';
            $count_data         = count($unserialize_data);

            foreach ($unserialize_data as $row) {
                $package_id     = isset($row['package']) ? $row['package'] : 0;
                $lock_qty       = isset($row['lock_qty']) ? $row['lock_qty'] : false;
                $product_name   = isset($row['name']) ? $row['name'] : 'Produk';
                $qty            = isset($row['qty']) ? $row['qty'] : 0;
                $price          = isset($row['price']) ? $row['price'] : 0;
                $price_ori      = isset($row['price_ori']) ? $row['price_ori'] : 0;
                $discount       = isset($row['discount']) ? $row['discount'] : 0;
                $subtotal       = $price;

                if ( $package_id && $cart_package != $package_id && $no > 1 ) {
                    $product_detail .= '
                        <tr class="bg-gradient-info text-white">
                            <td class="text-capitalize px-1 py-2 pl-2" style="border-left: 1px solid #11cdef">
                                Total Paket Produk '. br() .'
                                <span class="small"> Total Qty : <span class="font-weight-bold mr-1">'. ddm_accounting($total_qty_pack) .'</span></span>
                            </td>
                            <td class="text-right px-1 py-1" style="border-right: 1px solid #1171ef">
                                '. ddm_accounting($total_price_pack) .'
                            </td>
                        </tr>
                        <tr><td colspan="2" class="py-2"></td></tr>';
                        $total_price_pack   = 0;
                        $total_qty_pack     = 0;
                }

                if ( $package_id && $cart_package != $package_id ) {
                    $package_name   = isset($row['package_name']) ? $row['package_name'] : 'Paket';
                    $product_detail .= '
                        <tr class="bg-gradient-info text-white">
                            <th class="text-capitalize px-1 py-2" colspan="2" style="border-left: 1px solid #11cdef; border-right: 1px solid #1171ef">
                                '. $package_name . '
                            </th>
                        </tr>';
                }

                if ( $package_id ) {
                    $total_qty_pack = isset($row['total_qty']) ? $row['total_qty'] : 0; 
                    if ( ! $lock_qty ) {
                        $total_price_pack += $subtotal; 
                    } else {
                        $total_price_pack = isset($row['total_price']) ? $row['total_price'] : 0;
                    }
                }

                $total_qty  = 'Qty : <span class="font-weight-bold mr-1">'. ddm_accounting($qty) .' Liter</span>';
                if ( $price_ori > $price ) {
                    $total_qty .= '( <s>'. ddm_accounting($price_ori) .'</s> <span class="text-warning">'. ddm_accounting($price, $currency) .'</span> )';
                } else {
                    $total_qty .= '( '. ddm_accounting($price, $currency) .' )';
                }

                $product_detail .= '
                    <tr>
                        <td class="text-capitalize px-1 pl-2 py-2" style="border-left: 1px solid #11cdef">
                            '. $product_name . br() . '
                            <span class="small">'. $total_qty .'</span>
                        </td>
                        <td class="text-right px-1 pr-2 py-1" style="border-right: 1px solid #1171ef">'. ddm_accounting($subtotal) .'</td>
                    </tr>';

                if ( $package_id && $count_data == $no ) {
                    $product_detail .= '
                        <tr class="bg-gradient-info text-white">
                            <td class="text-capitalize px-1 py-2 pl-2" style="border-left: 1px solid #11cdef">
                                Total Paket Produk '. br() .'
                                <span class="small"> Total Qty : <span class="font-weight-bold mr-1">'. ddm_accounting($total_qty_pack) .'</span></span>
                            </td>
                            <td class="text-right px-1 py-1" style="border-right: 1px solid #1171ef">
                                '. ddm_accounting($total_price_pack) .'
                            </td>
                        </tr>';
                }

                if ( $package_id ) {
                    $cart_package   = $package_id; 
                    $package_name   = isset($row['package_name']) ? $row['package_name'] : ''; 
                    $no++; 
                }
            }
            $product_detail .= '</table>';
        }

        // Information Detail Product
        $uniquecode     = str_pad($dataorder->unique, 3, '0', STR_PAD_LEFT);
        $info_product   = '
            <div class="card">
                <div class="card-body py-2">
                    <h6 class="heading-small mb-0">Ringkasan Order</h6>
                    '.$product_detail.'
                    <hr class="mt-0 mb-0">
                    <div class="px-2 py-2">
                        <div class="row">
                            <div class="col-sm-7"><small class="text-muted">Subtotal</small></div>
                            <div class="col-sm-5 text-right"><small class="font-weight-bold">'. ddm_accounting($dataorder->subtotal) .'</small></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-7"><small class="text-muted">Kode Unik</small></div>
                            <div class="col-sm-5 text-right"><small class="font-weight-bold">'. $uniquecode .'</small></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-7">
                                <small class="text-muted">
                                    '. lang('discount') . ( $dataorder->voucher ? ' (<small class="text-success">'.$dataorder->voucher.'</small>)' : '' ) .'
                                </small>
                            </div>
                            <div class="col-sm-5 text-right">
                                <small class="font-weight-bold">
                                    '.( $dataorder->discount ? '<span class="text-success">- '.ddm_accounting($dataorder->discount).'</span>' : '' ).'
                                </small>
                            </div>
                        </div>
                    </div>
                    <hr class="mt-0 mb-0">
                    <div class="row py-3 align-items-center">
                        <div class="col-sm-6"><span class="heading-small font-weight-bold">'. lang('total_payment') .'</span></div>
                        <div class="col-sm-6 text-right">
                            <span class="heading text-warning font-weight-bold">'. ddm_accounting($dataorder->total_payment, $currency) .'</span>
                        </div>
                    </div>
                </div>
            </div>';

        // Information Agent
        $info_agent     = '';
        $view_agent     = ( $type_order == 'agent' ) ? true : false;
        $view_agent     = ( $is_admin ) ? true : $view_agent;
        if ( $view_agent ) {
            if ( $getagent = ddm_get_memberdata_by_id($dataorder->id_member) ) {
                $avatar     = ( empty($getagent->photo) ? 'avatar.png' : $getagent->photo );
                $info_agent = '
                    <div class="card mb-4">
                        <div class="card-body py-2">
                            <h6 class="heading-small mb-0">Informasi Agen</h6>
                            <hr class="mt-0 mb-2">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <a href="#" class="avatar avatar-xl rounded-circle">
                                        <img alt="Image placeholder" src="'. BE_IMG_PATH .'icons/'.$avatar.'">
                                    </a>
                                </div>
                                <div class="col ml--2">
                                    <h4 class="mb-0">
                                        <a href="#!">'. $getagent->name .'</a>
                                    </h4>
                                    <p class="text-sm text-muted font-weight-bold mb-0"><span class="text-success">●</span> '. $getagent->username .'</p>
                                </div>
                            </div>
                        </div>
                    </div>';
            }
        }

        // Information Shipping Address
        $address        = $dataorder->address .', Kec. '. $dataorder->subdistrict . br();
        $address       .= $dataorder->city .' - '. $dataorder->province;
        $address       .= ( $dataorder->postcode ) ? ' ('. $dataorder->postcode .')' : '';
        $info_title     = 'Alamat Pengiriman';
        $info_title     = ( $type_order == 'customer' && $is_admin ) ? 'Informasi Konsumen' : $info_title;
        $info_shipping  = '
            <div class="card">
                <div class="card-body py-2">
                    <h6 class="heading-small mb-0">'. $info_title .'</h6>
                    <hr class="mt-0 mb-2">
                    <div class="row">
                        <div class="col-sm-3"><small class="text-capitalize text-muted">'.lang('name').'</small></div>
                        <div class="col-sm-9"><small class="text-uppercase font-weight-bold">'.$dataorder->name.'</small></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><small class="text-capitalize text-muted">Telp</small></div>
                        <div class="col-sm-9"><small class="font-weight-bold">'.$dataorder->phone.'</small></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><small class="text-capitalize text-muted">'.lang('reg_email').'</small></div>
                        <div class="col-sm-9"><small class="text-lowecase font-weight-bold">'.$dataorder->email.'</small></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><small class="text-capitalize text-muted">'.lang('reg_alamat').'</small><br></div>
                        <div class="col-sm-9"><small class="text-capitalize font-weight-bold">'.$address.'</small></div>
                    </div>
                    <hr class="mt-2 mb-2">
                    <div class="row">
                        <div class="col-sm-3"><small class="text-capitalize text-muted">'.lang('courier').'</small><br></div>
                        <div class="col-sm-9">
                            <small class="text-uppercase font-weight-bold">
                            '.$dataorder->courier.' <small class="ml-1"></small>
                            </small>
                        </div>
                    </div>
                </div>
            </div>';
        
        $info_payment   = '';
        $order_detail   = '
            <div class="row">
                <div class="col-md-5 px-2">
                    '. $info_agent .'
                    '. $info_shipping .'
                    '. $info_payment .'
                </div>
                <div class="col-md-7 px-2">
                    '.$info_product.'
                </div>
            </div>
        ';
        return $order_detail;
    }
    
    /**
     * Activation Personal Sales Function
     */
    function activation(){
        // This is for AJAX request
        if ( ! $this->input->is_ajax_request() ) exit('No direct script access allowed');

        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            // Set JSON data
            $data = array(
                'status'        => 'error',
                'login'         => 'login',
                'url'           => base_url('login'),
            );
            // JSON encode data
            die(json_encode($data));
        }

        $data = array(
            'status'    => 'error', 
            'message'   => 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.'
        );
        
        // -------------------------------------------------
        // Set Variable
        // -------------------------------------------------
        $curdate                = date('Y-m-d H:i:s');
        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);

        $activationfor          = $this->input->post('activationfor');
        $activationfor          = trim( ddm_isset($activationfor, '') );
        $username               = $this->input->post('aps_member_username');
        $username               = trim( ddm_isset($username, '') );
        $qty                    = $this->input->post('aps_amount');
        $qty                    = trim( ddm_isset($qty, '') );
        
        // -------------------------------------------------
        // Check Form Validation
        // -------------------------------------------------
        if( $activationfor == 'other_agent' ){
            $this->form_validation->set_rules('aps_member_username','Username Agen','required');
        }
        $this->form_validation->set_rules('aps_amount','Jumlah Produk','required');

        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('', '');

        if( $this->form_validation->run() == FALSE){
            // Set JSON data
            $data['message']    = 'Aktivasi Personal Sales tidak berhasil. '.validation_errors();
            die(json_encode($data));
        }
        
        // -------------------------------------------------
        // Check Username Data
        // ------------------------------------------------- 
        $the_member = $current_member;
        $agentdata  = '';
        if( $activationfor == 'other_agent' ){
            $agentdata  = $this->Model_Member->get_member_by('login', $username);
            if( !$agentdata ){
                // Set JSON data
                $data['message']    = 'Data Agen tidak ditemukan atau belum terdaftar!';
                die(json_encode($data));
            }
            if( $agentdata->status != ACTIVE ){
                // Set JSON data
                $data['message']    = 'Status Agen tidak aktif. Silahkan inputkan username Agen aktif lainnya!';
                die(json_encode($data));
            }
            if( as_administrator($agentdata) ){
                // Set JSON data
                $data['message']    = 'Administrator tidak memerlukan Aktivasi Personal Sales. Silahkan inputkan username Agen lainnya!';
                die(json_encode($data));
            }
            $the_member = $agentdata;
        }
        
        // -------------------------------------------------
        // Check Province Data
        // ------------------------------------------------- 
        $provincedata = ddm_provinces($the_member->province);
        if( !$provincedata ){
            // Set JSON data
            $data['message']    = 'Data Propinsi tidak ditemukan atau belum terdaftar!';
            die(json_encode($data));
        }
        $provincearea = $provincedata->province_area;

        // -------------------------------------------------
        // Check Product Active Amount
        // ------------------------------------------------- 
        $product_active = $this->Model_Omzet_History->get_product_active($current_member->id);
        if( !$product_active || $product_active == 0 ){
            // Set JSON data
            $data['message']    = 'Anda tidak memiliki produk aktif untuk melakukan Aktivasi Personal Sales!';
            die(json_encode($data));
        }
        if( $qty > $product_active ){
            // Set JSON data
            $data['message']    = 'Jumlah Produk melebihi stok produk aktif Anda!';
            die(json_encode($data));
        }
        
        // -------------------------------------------------
        // Set Product Data
        // -------------------------------------------------  
        $productdata        = ddm_products(1, false);
        if( !$productdata ){
            // Set JSON data
            $data['message']    = 'Data produk tidak ditemukan atau belum terdaftar!';
            die(json_encode($data));
        }
        
        // -------------------------------------------------
        // Begin Transaction
        // -------------------------------------------------
        $this->db->trans_begin();
        
        // -------------------------------------------------
        // Set and Save Activation
        // -------------------------------------------------
        $data_activation        = array(
            'id_member'         => $current_member->id,
            'id_member_other'   => ( !empty($agentdata) ? $agentdata->id : 0 ),
            'activationfor'     => $activationfor,
            'products'          => maybe_serialize($productdata),
            'qty'               => $qty,
            'total_payment'     => $qty * $productdata->{"price_agent".$provincearea},
            'total_bv'          => $qty * $productdata->{"bv".$provincearea},
            'status'            => 1,
            'datecreated'       => $curdate,
            'datemodified'      => $curdate,
        );
        if( !$activation_id = $this->Model_Product_Activation->save_product_activation($data_activation) ){
            // Rollback Transaction
            $this->db->trans_rollback();
            // Set JSON data
            $data['message']    = 'Aktivasi Personal Sales tidak berhasil. Terjadi kesalahan pada proses save data Product Activation';
            die(json_encode($data));
        }
        
        // -------------------------------------------------
        // Set and Save Omzet
        // -------------------------------------------------
        $data_member_omzet_product = array(
            'id_member'     => $the_member->id,
            'id_activation' => $activation_id,
            'qty'           => $qty,
            'omzet'         => $qty * $productdata->{"price_agent".$provincearea},
            'amount'        => $qty * $productdata->{"price_agent".$provincearea},
            'bv'            => $qty * $productdata->{"bv".$provincearea},
            'type'          => 'activation',
            'status'        => 'personal',
            'desc'          => 'Aktivasi Personal Sales '.( !empty($agentdata) ? 'oleh Agen Username '.$current_member->username : 'Pribadi' ),
            'date'          => date('Y-m-d', strtotime($curdate)),
            'datecreated'   => $curdate,
            'datemodified'  => $curdate
        );
        if( ! $insert_member_omzet_product = $this->Model_Member->save_data_member_omzet($data_member_omzet_product) ){
            // Rollback Transaction
            $this->db->trans_rollback();
            // Set JSON data
            $data['message']    = 'Aktivasi Personal Sales tidak berhasil. Terjadi kesalahan pada proses save data Omzet.';
            die(json_encode($data));
        }
        
        // -------------------------------------------------
        // Set and Save Omzet History Out
        // -------------------------------------------------
        $data_history_out       = array(
            'id_member'         => $current_member->id,
            'id_source'         => $activation_id,
            'source'            => 'activation',
            'source_type'       => 'product',
            'qty'               => $qty,
            'amount'            => $qty * $productdata->{"price_agent".$provincearea},
            'bv'                => $qty * $productdata->{"bv".$provincearea},
            'type'              => 'OUT',
            'status'            => 1,
            'description'       => 'Aktivasi Personal Sales '.( !empty($agentdata) ? 'Agen Username '.$agentdata->username : 'Pribadi' ),
            'datecreated'       => $curdate,
        );
        if( ! $insert_history_out = $this->Model_Omzet_History->save_omzet_history($data_history_out) ){
            // Rollback Transaction
            $this->db->trans_rollback();
            // Set JSON data
            $data['message']    = 'Aktivasi Personal Sales tidak berhasil. Terjadi kesalahan pada proses save data Omzet History';
            die(json_encode($response));
        }
        
        // -------------------------------------------------
        // Calculate AGA Bonus
        // -------------------------------------------------
        ddm_calculate_aga_bonus($the_member->id, $the_member->sponsor, $qty * $productdata->{"bv".$provincearea}, $curdate);
        
        // -------------------------------------------------
        // Commit or Rollback Transaction
        // -------------------------------------------------
        if ( $this->db->trans_status() === FALSE ){
            // Rollback Transaction
            $this->db->trans_rollback();
            // Set JSON data
            $data['message']    = 'Aktivasi Personal Sales tidak berhasil. Terjadi kesalahan pada proses transaction database';
            die(json_encode($data));
        }else{
            // Commit Transaction
            $this->db->trans_commit();
            // Complete Transaction
            $this->db->trans_complete();
            // Save Log
            ddm_log_action( 'ACTIVATION_PERSONAL_SALES', $username, $current_member->username, json_encode(array('cookie'=>$_COOKIE, 'status'=>'SUCCESS') )  );
            // Set JSON data
            $data           = array(
                'status'    => 'success',
                'message'   => 'Aktivasi Personal Sales berhasil'
            ); 
            die(json_encode($data));   
        }
    }
    
    /**
     * Transfer Product Function
     */
    function transfer(){
        // This is for AJAX request
        if ( ! $this->input->is_ajax_request() ) exit('No direct script access allowed');

        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            // Set JSON data
            $data = array(
                'status'        => 'error',
                'login'         => 'login',
                'url'           => base_url('login'),
            );
            // JSON encode data
            die(json_encode($data));
        }

        $data = array(
            'status'    => 'error', 
            'message'   => 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.'
        );
        
        // -------------------------------------------------
        // Set Variable
        // -------------------------------------------------
        $curdate                = date('Y-m-d H:i:s');
        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);

        $username               = $this->input->post('trans_member_username');
        $username               = trim( ddm_isset($username, '') );
        $qty                    = $this->input->post('trans_amount');
        $qty                    = trim( ddm_isset($qty, '') );
        
        // -------------------------------------------------
        // Check Form Validation
        // -------------------------------------------------
        $this->form_validation->set_rules('trans_member_username','Username Agen','required');
        $this->form_validation->set_rules('trans_amount','Jumlah Produk','required');

        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('', '');

        if( $this->form_validation->run() == FALSE){
            // Set JSON data
            $data['message']    = 'Transfer Produk tidak berhasil. '.validation_errors();
            die(json_encode($data));
        }
        
        // -------------------------------------------------
        // Check Username Data
        // ------------------------------------------------- 
        $agentdata  = $this->Model_Member->get_member_by('login', $username);
        if( !$agentdata ){
            // Set JSON data
            $data['message']    = 'Data Agen tidak ditemukan atau belum terdaftar!';
            die(json_encode($data));
        }
        if( $agentdata->status != ACTIVE ){
            // Set JSON data
            $data['message']    = 'Status Agen tidak aktif. Silahkan inputkan username Agen aktif lainnya!';
            die(json_encode($data));
        }
        if( as_administrator($agentdata) ){
            // Set JSON data
            $data['message']    = 'Administrator tidak memerlukan Transfer Produk. Silahkan inputkan username Agen lainnya!';
            die(json_encode($data));
        }

        // -------------------------------------------------
        // Check Product Active Amount
        // ------------------------------------------------- 
        $product_active = $this->Model_Omzet_History->get_product_active($current_member->id);
        if( !$product_active || $product_active == 0 ){
            // Set JSON data
            $data['message']    = 'Anda tidak memiliki produk aktif untuk melakukan Transfer Produk!';
            die(json_encode($data));
        }
        if( $qty > $product_active ){
            // Set JSON data
            $data['message']    = 'Jumlah Transfer Produk melebihi stok produk aktif Anda!';
            die(json_encode($data));
        }
        
        // -------------------------------------------------
        // Check Province Data
        // ------------------------------------------------- 
        $provincedata = ddm_provinces($current_member->province);
        if( !$provincedata ){
            // Set JSON data
            $data['message']    = 'Data Propinsi tidak ditemukan atau belum terdaftar!';
            die(json_encode($data));
        }
        $provincearea = $provincedata->province_area;
        
        // -------------------------------------------------
        // Set Product Data
        // -------------------------------------------------  
        $productdata        = ddm_products(1, false);
        if( !$productdata ){
            // Set JSON data
            $data['message']    = 'Data produk tidak ditemukan atau belum terdaftar!';
            die(json_encode($data));
        }
        
        // -------------------------------------------------
        // Begin Transaction
        // -------------------------------------------------
        $this->db->trans_begin();
        
        // -------------------------------------------------
        // Set and Save Transfer
        // -------------------------------------------------
        $data_transfer          = array(
            'id_member'         => $current_member->id,
            'id_member_receiver'=> $agentdata->id,
            'products'          => maybe_serialize($productdata),
            'qty'               => $qty,
            'total_payment'     => $qty * $productdata->{"price_agent".$provincearea},
            'total_bv'          => $qty * $productdata->{"bv".$provincearea},
            'status'            => 1,
            'datecreated'       => $curdate,
            'datemodified'      => $curdate,
        );
        if( !$transfer_id = $this->Model_Product_Transfer->save_transfer_product($data_transfer) ){
            // Rollback Transaction
            $this->db->trans_rollback();
            // Set JSON data
            $data['message']    = 'Transfer Produk tidak berhasil. Terjadi kesalahan pada proses save data Transfer Produk';
            die(json_encode($data));
        }
        
        // -------------------------------------------------
        // Set and Save Omzet
        // -------------------------------------------------
        $data_member_omzet_product = array(
            'id_member'     => $agentdata->id,
            'id_transfer'   => $transfer_id,
            'qty'           => $qty,
            'omzet'         => $qty * $productdata->{"price_agent".$provincearea},
            'amount'        => $qty * $productdata->{"price_agent".$provincearea},
            'bv'            => $qty * $productdata->{"bv".$provincearea},
            'type'          => 'transfer',
            'status'        => 'product',
            'desc'          => 'Transfer Produk dari Agen Username '.$current_member->username,
            'date'          => date('Y-m-d', strtotime($curdate)),
            'datecreated'   => $curdate,
            'datemodified'  => $curdate
        );
        if( ! $insert_member_omzet_product = $this->Model_Member->save_data_member_omzet($data_member_omzet_product) ){
            // Rollback Transaction
            $this->db->trans_rollback();
            // Set JSON data
            $data['message']    = 'Transfer Produk tidak berhasil. Terjadi kesalahan pada proses save data Omzet.';
            die(json_encode($data));
        }
        
        // -------------------------------------------------
        // Set and Save Omzet History Out for Current Member
        // -------------------------------------------------
        $data_history_out       = array(
            'id_member'         => $current_member->id,
            'id_source'         => $transfer_id,
            'source'            => 'transfer',
            'source_type'       => 'product',
            'qty'               => $qty,
            'amount'            => $qty * $productdata->{"price_agent".$provincearea},
            'bv'                => $qty * $productdata->{"bv".$provincearea},
            'type'              => 'OUT',
            'status'            => 1,
            'description'       => 'Transfer Produk ke Agen Username '.$agentdata->username,
            'datecreated'       => $curdate,
        );
        if( ! $insert_history_out = $this->Model_Omzet_History->save_omzet_history($data_history_out) ){
            // Rollback Transaction
            $this->db->trans_rollback();
            // Set JSON data
            $data['message']    = 'Transfer Produk tidak berhasil. Terjadi kesalahan pada proses save data Omzet History OUT';
            die(json_encode($response));
        }
        
        // -------------------------------------------------
        // Set and Save Omzet History In for Member Receiver
        // -------------------------------------------------
        $data_history_in        = array(
            'id_member'         => $agentdata->id,
            'id_source'         => $transfer_id,
            'source'            => 'transfer',
            'source_type'       => 'product',
            'qty'               => $qty,
            'amount'            => $qty * $productdata->{"price_agent".$provincearea},
            'bv'                => $qty * $productdata->{"bv".$provincearea},
            'type'              => 'IN',
            'status'            => 1,
            'description'       => 'Transfer Produk dari Agen Username '.$current_member->username,
            'datecreated'       => $curdate,
        );
        if( ! $insert_history_in = $this->Model_Omzet_History->save_omzet_history($data_history_in) ){
            // Rollback Transaction
            $this->db->trans_rollback();
            // Set JSON data
            $data['message']    = 'Transfer Produk tidak berhasil. Terjadi kesalahan pada proses save data Omzet History IN';
            die(json_encode($response));
        }
        
        // -------------------------------------------------
        // Commit or Rollback Transaction
        // -------------------------------------------------
        if ( $this->db->trans_status() === FALSE ){
            // Rollback Transaction
            $this->db->trans_rollback();
            // Set JSON data
            $data['message']    = 'Transfer Produk tidak berhasil. Terjadi kesalahan pada proses transaction database';
            die(json_encode($data));
        }else{
            // Commit Transaction
            $this->db->trans_commit();
            // Complete Transaction
            $this->db->trans_complete();
            // Save Log
            ddm_log_action( 'TRANSFER_PRODUCT', $username, $current_member->username, json_encode(array('cookie'=>$_COOKIE, 'status'=>'SUCCESS') )  );
            // Set JSON data
            $data           = array(
                'status'    => 'success',
                'message'   => 'Transfer Produk berhasil'
            ); 
            die(json_encode($data));   
        }
    }
    
    // ------------------------------------------------------------------------------------------------
}

/* End of file Productorder.php */
/* Location: ./app/controllers/Productorder.php */
