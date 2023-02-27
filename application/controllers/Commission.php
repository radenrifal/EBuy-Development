<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Commission Controller.
 *
 * @class     Commission
 * @version   1.0.0
 */
class Commission extends Member_Controller {
    /**
	 * Constructor.
	 */
    function __construct()
    {
        parent::__construct();
    }

    // =============================================================================================
    // LIST DATA COMMISSION
    // =============================================================================================

    /**
     * Total Bonus List Data function.
     */
    function totalbonuslistdata()
    {
        // This is for AJAX request
        if ( ! $this->input->is_ajax_request() ) exit('No direct script access allowed');

        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            // Set JSON data
            $data = array('status' => 'access_denied', 'url' => base_url('login')); 
            die(json_encode($data));
        }

        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);

        $condition          = 'WHERE %type% = '. MEMBER .' AND %total% > 0';
        $order_by           = '';
        $iTotalRecords      = 0;

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);
        $sEcho              = intval($_REQUEST['sEcho']);

        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ( $iDisplayLength == '-1' ? 0 : $iDisplayLength );
        $offset             = $iDisplayStart;

        $s_memberid         = $this->input->post('search_memberid');
        $s_memberid         = ddm_isset($s_memberid, '');
        $s_username         = $this->input->post('search_username');
        $s_username         = ddm_isset($s_username, '');
        $s_name             = $this->input->post('search_name');
        $s_name             = ddm_isset($s_name, '');
        $s_nominal_min      = $this->input->post('search_nominal_min');
        $s_nominal_min      = ddm_isset($s_nominal_min, '');
        $s_nominal_max      = $this->input->post('search_nominal_max');
        $s_nominal_max      = ddm_isset($s_nominal_max, '');

        if ( !empty($s_memberid) )      { $condition .= str_replace('%s%', $s_memberid, ' AND %id% = %s%'); }
        if ( !empty($s_username) )      { $condition .= str_replace('%s%', $s_username, ' AND %username% LIKE "%%s%%"'); }
        if ( !empty($s_name) )          { $condition .= str_replace('%s%', $s_name, ' AND %name% LIKE "%%s%%"'); }
        if ( !empty($s_nominal_min) )   { $condition .= ' AND %total% >= '.$s_nominal_min.''; }
        if ( !empty($s_nominal_max) )   { $condition .= ' AND %total% <= '.$s_nominal_max.''; }

        if( $column == 1 )      { $order_by .= '%id% ' . $sort; }
        elseif( $column == 2 )  { $order_by .= '%username% ' . $sort; }
        elseif( $column == 3 )  { $order_by .= '%name% ' . $sort; }
        elseif( $column == 4 )  { $order_by .= '%total% ' . $sort; }

        if ( $is_admin ) {
            $data_list      = $this->Model_Bonus->get_all_member_bonus($limit, $offset, $condition, $order_by);
        } else {
            $data_list      = array();
        }

        $records            = array();
        $records["aaData"]  = array();

        if( !empty($data_list) ){
            $iTotalRecords  = ddm_get_last_found_rows();
            $currency       = config_item('currency');
            $i = $offset + 1;
            foreach($data_list as $row){
                $id         = ddm_encrypt($row->id);
                $btn_detail = '<a href="'.base_url('commission/bonus/'.$id).'" class="btn btn-sm btn-primary">Detail</a>';

                $records["aaData"][]    = array(
                    ddm_center($i),
                    ddm_center('<a href="'.base_url('profile/'.$id).'">' . ddm_strong(strtolower($row->username)) . '</a>'),
                    '<a href="'.base_url('profile/'.$id).'">' . strtoupper($row->name) . '</a>',
                    ddm_right(ddm_accounting($row->total)),
                    ddm_center($btn_detail),
                );
                $i++;
            }
        }

        $end                = $iDisplayStart + $iDisplayLength;
        $end                = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }

    /**
     * History Bonus List Data function.
     */
    function historybonuslistdata()
    {
        // This is for AJAX request
        if ( ! $this->input->is_ajax_request() ) exit('No direct script access allowed');

        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            // Set JSON data
            $data = array('status' => 'access_denied', 'url' => base_url('login')); 
            die(json_encode($data));
        }

        $member_data        = '';
        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);

        $condition          = '';
        $order_by           = '';
        $iTotalRecords      = 0;

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);

        $sEcho              = intval($_REQUEST['sEcho']);
        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ( $iDisplayLength == '-1' ? 0 : $iDisplayLength );
        $offset             = $iDisplayStart;

        $s_date_min         = $this->input->post('search_datecreated_min');
        $s_date_min         = ddm_isset($s_date_min, '');
        $s_date_max         = $this->input->post('search_datecreated_max');
        $s_date_max         = ddm_isset($s_date_max, '');
        $s_nominal_min      = $this->input->post('search_nominal_min');
        $s_nominal_min      = ddm_isset($s_nominal_min, '');
        $s_nominal_max      = $this->input->post('search_nominal_max');
        $s_nominal_max      = ddm_isset($s_nominal_max, '');
        $s_username         = $this->input->post('search_username');
        $s_username         = ddm_isset($s_username, '');
        $s_name             = $this->input->post('search_name');
        $s_name             = ddm_isset($s_name, '');
        $s_desc             = $this->input->post('search_desc');
        $s_desc             = ddm_isset($s_desc, '');
        $s_type             = $this->input->post('search_type');
        $s_type             = ddm_isset($s_type, '');
        $s_type             = (empty($s_type)?$this->input->get('bonus_type'):$s_type);

        if ( !empty($s_username) )      { $condition .= str_replace('%s%', $s_username, ' AND %username% LIKE "%%s%%"'); }
        if ( !empty($s_name) )          { $condition .= str_replace('%s%', $s_name, ' AND %name% LIKE "%%s%%"'); }
        if ( !empty($s_desc) )          { $condition .= str_replace('%s%', $s_desc, ' AND %desc% LIKE "%%s%%"'); }
        if ( !empty($s_type) )          { $condition .= str_replace('%s%', $s_type, ' AND %type% = %s%'); }
        if ( !empty($s_nominal_min) )   { $condition .= ' AND %nominal% >= '.$s_nominal_min.''; }
        if ( !empty($s_nominal_max) )   { $condition .= ' AND %nominal% <= '.$s_nominal_max.''; }
        if ( !empty($s_date_min) )      { $condition .= ' AND %datecreated% >= "'.$s_date_min.'"'; }
        if ( !empty($s_date_max) )      { $condition .= ' AND %datecreated% <= "'.$s_date_max.'"'; }

        if( $column == 1 )      { $order_by .= '%datecreated% ' . $sort; }
        elseif( $column == 2 )  { $order_by .= '%username% ' . $sort; }
        elseif( $column == 3 )  { $order_by .= '%name% ' . $sort; }
        elseif( $column == 4 )  { $order_by .= '%nominal% ' . $sort; }
        elseif( $column == 5 )  { $order_by .= '%type% ' . $sort; }
        elseif( $column == 6 )  { $order_by .= '%desc% ' . $sort; }

        if ( $is_admin ) {
            $data_list      = $this->Model_Bonus->get_all_history_bonus($limit, $offset, $condition, $order_by);
        } else {
            $data_list      = array();
        }

        $records            = array();
        $records["aaData"]  = array();

        if( !empty($data_list) ){
            $iTotalRecords  = ddm_get_last_found_rows();
            $cfg_bonus_type = config_item('bonus_type');
            $currency       = config_item('currency');
            $i = $offset + 1;
            foreach($data_list as $row){
                $amount     = ddm_accounting( ($row->amount == "" ? 0 : $row->amount), '', true );
                $type       = '';
                $lbl_class  = 'default';
                if ( $cfg_bonus_type ) {
                    foreach ($cfg_bonus_type as $key => $bonus_type) {
                        if ( $key == $row->type ) {
                            if ( $key == 1 ) { $lbl_class = 'primary'; }
                            if ( $key == 2 ) { $lbl_class = 'info'; }
                            if ( $key == 3 ) { $lbl_class = 'success'; }
                            if ( $key == 4 ) { $lbl_class = 'warning'; }
                            $type = '<span class="badge badge-sm badge-'.$lbl_class.'">'.strtoupper($bonus_type).'</span>';
                        }
                    }
                }
                
                $records["aaData"][]    = array(
                    ddm_center($i),
                    ddm_center(date('Y-m-d @H:i', strtotime($row->datecreated))),
                    ddm_center('<a href="'.base_url('profile/'.$row->id_member).'">' . ddm_strong(strtolower($row->username)) . '</a>'),
                    '<a href="'.base_url('profile/'.$row->id_member).'">' . strtoupper($row->name) . '</a>',
                    '<div style="min-width:100px">'.$amount.'</div>',
                    ddm_center($type),
                    $row->desc,
                    '',
                );
                $i++;
            }
        }

        $end                = $iDisplayStart + $iDisplayLength;
        $end                = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }

    /**
     * Bonus Member List Data function.
     */
    function memberbonuslistdata( $id=0 )
    {
        // This is for AJAX request
        if ( ! $this->input->is_ajax_request() ) exit('No direct script access allowed');

        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            // Set JSON data
            $data = array('status' => 'access_denied', 'url' => base_url('login')); 
            die(json_encode($data));
        }

        $member_data        = '';
        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);

        $id_member          = $current_member->id;
        if ( $is_admin && $id  ) {
            $id_member      = ddm_decrypt($id);
            if ( $member_data = ddm_get_memberdata_by_id($id_member) ) {
                $id_member = $member_data->id;
            }
        }

        $condition          = '';
        $order_by           = '';
        $iTotalRecords      = 0;

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);

        $sEcho              = intval($_REQUEST['sEcho']);
        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ( $iDisplayLength == '-1' ? 0 : $iDisplayLength );
        $offset             = $iDisplayStart;

        $s_date_min         = $this->input->post('search_datecreated_min');
        $s_date_min         = ddm_isset($s_date_min, '');
        $s_date_max         = $this->input->post('search_datecreated_max');
        $s_date_max         = ddm_isset($s_date_max, '');
        $s_nominal_min      = $this->input->post('search_nominal_min');
        $s_nominal_min      = ddm_isset($s_nominal_min, '');
        $s_nominal_max      = $this->input->post('search_nominal_max');
        $s_nominal_max      = ddm_isset($s_nominal_max, '');
        $s_desc             = $this->input->post('search_desc');
        $s_desc             = ddm_isset($s_desc, '');
        $s_type             = $this->input->post('search_type');
        $s_type             = ddm_isset($s_type, '');
        $s_type             = (empty($s_type)?$this->input->get('bonus_type'):$s_type);

        if ( !empty($s_type) )          { $condition .= str_replace('%s%', $s_type, ' AND %type% = %s%'); }
        if ( !empty($s_desc) )          { $condition .= str_replace('%s%', $s_desc, ' AND %desc% LIKE "%%s%%"'); }
        if ( !empty($s_nominal_min) )   { $condition .= ' AND %amount% >= '.$s_nominal_min.''; }
        if ( !empty($s_nominal_max) )   { $condition .= ' AND %amount% <= '.$s_nominal_max.''; }
        if ( !empty($s_date_min) )      { $condition .= ' AND DATE(%datecreated%) >= "'.$s_date_min.'"'; }
        if ( !empty($s_date_max) )      { $condition .= ' AND DATE(%datecreated%) <= "'.$s_date_max.'"'; }

        if( $column == 1 )      { $order_by .= '%datecreated% ' . $sort; }
        elseif( $column == 2 )  { $order_by .= '%amount% ' . $sort; }
        elseif( $column == 3 )  { $order_by .= '%type% ' . $sort; }
        elseif( $column == 4 )  { $order_by .= '%desc% ' . $sort; }

        $data_list          = $this->Model_Bonus->get_all_my_bonus($id_member, $limit, $offset, $condition, $order_by);
        $records            = array();
        $records["aaData"]  = array();

        if( !empty($data_list) ){
            $iTotalRecords  = ddm_get_last_found_rows();
            $cfg_bonus_type = config_item('bonus_type');
            $currency       = config_item('currency');
            $i = $offset + 1;
            foreach($data_list as $row){
                $type       = '';
                $lbl_class  = 'default';
                if ( $cfg_bonus_type ) {
                    foreach ($cfg_bonus_type as $key => $bonus_type) {
                        if ( $key == $row->type ) {
                            if ( $key == 1 ) { $lbl_class = 'primary'; }
                            if ( $key == 2 ) { $lbl_class = 'info'; }
                            if ( $key == 3 ) { $lbl_class = 'success'; }
                            if ( $key == 4 ) { $lbl_class = 'warning'; }
                            $type = '<span class="badge badge-sm badge-'.$lbl_class.'">'.strtoupper($bonus_type).'</span>';
                        }
                    }
                }

                $records["aaData"][]    = array(
                    ddm_center($i),
                    ddm_center($row->datecreated),
                    ddm_accounting($row->amount, '', true),
                    ddm_center($type),
                    $row->desc,
                    '',
                );
                $i++;
            }
        }

        $end                = $iDisplayStart + $iDisplayLength;
        $end                = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }

    /**
     * Deposite List function.
     */
    function depositelistdata()
    {
        // This is for AJAX request
        if ( ! $this->input->is_ajax_request() ) exit('No direct script access allowed');

        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            // Set JSON data
            $data = array('status' => 'access_denied', 'url' => base_url('login')); 
            die(json_encode($data));
        }

        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);

        $condition          = '';
        $total_condition    = '';
        $order_by           = '';
        $iTotalRecords      = 0;

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);
        $sEcho              = intval($_REQUEST['sEcho']);
        $sAction            = ddm_isset($_REQUEST['sAction'], '');

        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_username         = $this->input->post('search_username');
        $s_username         = ddm_isset($s_username, '');
        $s_name             = $this->input->post('search_name');
        $s_name             = ddm_isset($s_name, '');
        $s_nominal_min      = $this->input->post('search_nominal_min');
        $s_nominal_min      = ddm_isset($s_nominal_min, '');
        $s_nominal_max      = $this->input->post('search_nominal_max');
        $s_nominal_max      = ddm_isset($s_nominal_max, '');
        
        if(!empty($s_username))     { $condition .= str_replace('%s%', $s_username, ' AND %username% LIKE "%%s%%"'); }
        if(!empty($s_name))         { $condition .= str_replace('%s%', $s_name, ' AND %name% LIKE "%%s%%"'); }

        if(!empty($s_nominal_min))  { $total_condition .= ' AND %total% >= ' . $s_nominal_min . ''; }
        if(!empty($s_nominal_max))  { $total_condition .= ' AND %total% <= ' . $s_nominal_max . ''; }

        if($column == 1)            { $order_by .= '%username% ' . $sort; } 
        elseif($column == 2)        { $order_by .= '%name% ' . $sort; } 
        elseif($column == 3)        { $order_by .= '%total% ' . $sort; }

        if ( $is_admin ) {
            $data_list      = $this->Model_Bonus->get_all_total_ewallet_member($limit, $offset, $condition, $order_by, $total_condition);
        } else {
            $data_list      = array();
        }

        $records            = array();
        $records["aaData"]  = array();

        if(!empty($data_list)) {
            $iTotalRecords = ddm_get_last_found_rows();
            $i = $offset + 1;
            $currency = config_item('currency');
            foreach($data_list as $row) {
                $id         = ddm_encrypt($row->id);
                $btn_detail = '<a href="'.base_url('commission/deposite/'.$id).'" class="btn btn-sm btn-primary">Detail</a>';

                $records["aaData"][] = array(
                    ddm_center($i),
                    ddm_center('<a href="'.base_url('profile/'.$id).'">' . ddm_strong(strtolower($row->username)) . '</a>'),
                    strtoupper($row->name),
                    ddm_right(ddm_accounting($row->total_deposite)),
                    ddm_center($btn_detail)
                );
                $i++;
            }
        }

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"] = $sEcho;
        $records["iTotalRecords"] = $iTotalRecords;
        $records["iTotalDisplayRecords"] = $iTotalRecords;

        echo json_encode($records);
    }

    /**
     * Deposite Member List Data function.
     */
    function memberdepositelistdata($type = 'all', $id = 0)
    {
        // This is for AJAX request
        if ( ! $this->input->is_ajax_request() ) exit('No direct script access allowed');

        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            // Set JSON data
            $data = array('status' => 'access_denied', 'url' => base_url('login')); 
            die(json_encode($data));
        }

        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);
        $id_member          = 0;
        if ( $is_admin ) {
            if ( $id ) {
                $id_member  = ddm_decrypt($id);
                if ( $member_data = ddm_get_memberdata_by_id($id_member) ) {
                    $id_member = $member_data->id;
                }
            }
        } else {
            $id_member      = $current_member->id; 
        }

        $show_type          = true;
        $condition          = '';
        if ( $id_member )                   { $condition .= ' AND %id_member% = ' . $id_member; }
        if ( strtoupper($type) == 'IN' )    { $condition .= ' AND %type% = "IN"'; $show_type = false; }
        if ( strtoupper($type) == 'OUT' )   { $condition .= ' AND %type% = "OUT"'; $show_type = false; }

        $order_by           = '';
        $iTotalRecords      = 0;
        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);
        $sEcho              = intval($_REQUEST['sEcho']);
        $sAction            = ddm_isset($_REQUEST['sAction'], '');

        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_username         = $this->input->post('search_username');
        $s_username         = ddm_isset($s_username, '');
        $s_name             = $this->input->post('search_name');
        $s_name             = ddm_isset($s_name, '');
        $s_source           = $this->input->post('search_source');
        $s_source           = ddm_isset($s_source, '');
        $s_status           = $this->input->post('search_status');
        $s_status           = ddm_isset($s_status, '');
        $s_nominal_min      = $this->input->post('search_nominal_min');
        $s_nominal_min      = ddm_isset($s_nominal_min, '');
        $s_nominal_max      = $this->input->post('search_nominal_max');
        $s_nominal_max      = ddm_isset($s_nominal_max, '');
        $s_date_min         = $this->input->post('search_datecreated_min');
        $s_date_min         = ddm_isset($s_date_min, '');
        $s_date_max         = $this->input->post('search_datecreated_max');
        $s_date_max         = ddm_isset($s_date_max, '');
        
        if(!empty($s_username))     { $condition .= str_replace('%s%', $s_username, ' AND %username% LIKE "%%s%%"'); }
        if(!empty($s_name))         { $condition .= str_replace('%s%', $s_name, ' AND %name% LIKE "%%s%%"'); }
        if(!empty($s_source))       { $condition .= str_replace('%s%', $s_source, ' AND %source% = "%s%"'); }
        if(!empty($s_status))       { $condition .= str_replace('%s%', $s_status, ' AND %type% = "%s%"'); }
        if(!empty($s_nominal_min))  { $condition .= ' AND %amount% >= ' . $s_nominal_min . ''; }
        if(!empty($s_nominal_max))  { $condition .= ' AND %amount% <= ' . $s_nominal_max . ''; }
        if(!empty($s_date_min))     { $condition .= ' AND DATE(%datecreated%) >= "' . $s_date_min . '"'; }
        if(!empty($s_date_max))     { $condition .= ' AND DATE(%datecreated%) <= "' . $s_date_max . '"'; }

        if($column == 1)            { $order_by .= '%datecreated% ' . $sort; } 
        elseif($column == 2)        { $order_by .= '%source% ' . $sort; } 
        elseif($column == 3)        { $order_by .= '%amount% ' . $sort; } 
        elseif($column == 4)        { $order_by .= '%description% ' . $sort; }

        $data_list          = $this->Model_Bonus->get_all_ewallet_member($limit, $offset, $condition, $order_by);
        $records            = array();
        $records["aaData"]  = array();

        if(!empty($data_list)) {
            $iTotalRecords = ddm_get_last_found_rows();
            $i = $offset + 1;
            $currency = config_item('currency');
            foreach($data_list as $row) {
                $source = strtoupper($row->source);
                if ( $source == 'BONUS' )   { $source = '<span class="badge badge-info">'.$source.'</span>'; }
                if ( $source == 'WITHDRAW') { $source = '<span class="badge badge-danger">'.$source.'</span>'; }
                if ( $source == 'REGISTER') { $source = '<span class="badge badge-warning">'.$source.'</span>'; }

                $datatabled = array(
                    '<center>' . $i . '</center>',
                    '<center>' . date('Y-m-d @H:i', strtotime($row->datecreated)) . '</center>',
                    '<center>' . $source . '</center>',
                );

                if ( $show_type ) {
                    $datatabled[] = '<center><span class="badge badge-'.((strtoupper($row->type) == 'IN') ? 'info' : 'danger').'">'. strtoupper($row->type) .'</span></center>';
                }

                $datatabled[] = ddm_right(ddm_accounting($row->amount));
                $datatabled[] = $row->description;
                $datatabled[] = '';

                $records["aaData"][] = $datatabled;
                $i++;
            }
        }

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"] = $sEcho;
        $records["iTotalRecords"] = $iTotalRecords;
        $records["iTotalDisplayRecords"] = $iTotalRecords;

        echo json_encode($records);
    }

    /**
     * Commission List Data function.
     */
    function commissionlistdata()
    {
        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);

        $condition          = '';
        $total_condition    = '';
        $order_by           = '';
        $iTotalRecords      = 0;

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);

        $sAction            = ddm_isset($_REQUEST['sAction'],'');
        $sEcho              = intval($_REQUEST['sEcho']);
        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ( $iDisplayLength == '-1' ? 0 : $iDisplayLength );
        $offset             = $iDisplayStart;

        $s_username         = $this->input->post('search_username');
        $s_username         = ddm_isset($s_username, '');
        $s_name             = $this->input->post('search_name');
        $s_name             = ddm_isset($s_name, '');
        $s_nominal_min      = $this->input->post('search_nominal_min');
        $s_nominal_min      = ddm_isset($s_nominal_min, '');
        $s_nominal_max      = $this->input->post('search_nominal_max');
        $s_nominal_max      = ddm_isset($s_nominal_max, '');
        $s_date             = $this->input->post('search_date_commission');
        $s_date             = ddm_isset($s_date, date('Y-m-d').'|'.date('Y-m-d'));

        if ( !empty($s_username) )  { $condition .= ' AND %username% LIKE "%'.$s_username.'%"'; }
        if ( !empty($s_name) )      { $condition .= ' AND %name% LIKE "%'.$s_name.'%"'; }
        if ( !empty($s_date) )      {
            $_date = explode('|', $s_date);
            $condition .= ' AND DATE(%datecreated%) >= "' . $_date[0] . '" AND DATE(%datecreated%) <= "' . $_date[1] . '"';
        }
        if ( !empty($s_nominal_min) )   { $total_condition .= ' AND %total% >= '.$s_nominal_min.''; }
        if ( !empty($s_nominal_max) )   { $total_condition .= ' AND %total% <= '.$s_nominal_max.''; }

        if( $column == 1 )      { $order_by .= '%username% ' . $sort; }
        elseif( $column == 2 )  { $order_by .= '%name% ' . $sort; }
        elseif( $column == 3 )  { $order_by .= '%total% ' . $sort; }

        if ( $is_admin ) {
            $data_list      = $this->Model_Bonus->get_all_member_commission($limit, $offset, $condition, $order_by, $total_condition);
        } else {
            $data_list      = array();
        }

        $records            = array();
        $records["aaData"]  = array();

        if( !empty($data_list) ){
            $iTotalRecords  = ddm_get_last_found_rows();
            $i = $offset + 1;
            $currency = config_item('currency');
            foreach($data_list as $row){
                $id         = ddm_encrypt($row->id);
                $link       = base_url('financial/commission/'.$id.'?daterange=' . $s_date);
                $btn_detail = '<a href="'.$link.'" class="btn btn-xs btn-flat btn-block bg-blue">Detail</a>';

                $records["aaData"][] = array(
                    ddm_center($i),
                    ddm_center('<a href="'.base_url('profile/'.$id).'">' . ddm_strong(strtolower($row->username)) . '</a>'),
                    strtoupper($row->name),
                    ddm_right(ddm_accounting($row->total)),
                    ddm_center($btn_detail),
                );
                $i++;
            }
        }

        $end                = $iDisplayStart + $iDisplayLength;
        $end                = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }

    /**
     * Withdrawal List function.
     */
    function withdrawlistdata()
    {
        // This is for AJAX request
        $sExport            = $this->input->get('export');
        $sAction            = isset($_REQUEST['sAction']) ? $_REQUEST['sAction'] : '';
        $sAction            = isset($sExport) ? $sExport : $sAction;
        
        if( $sAction != 'export_excel' ){
            // This is for AJAX request
            if ( ! $this->input->is_ajax_request() ) exit('No direct script access allowed');
        }

        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            // Set JSON data
            $data = array('status' => 'access_denied', 'url' => base_url('login')); 
            die(json_encode($data));
        }

        $member_id          = 0;
        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);

        if ( ! $is_admin ) {
            $member_id      = $current_member->id;
        }

        $condition          = ( $member_id > 0 ) ? ' AND %id_member% = ' . $member_id : '';
        $order_by           = '';
        $iTotalRecords      = 0;

        $sExport            = $this->input->get('export');
        $sAction            = isset($_REQUEST['sAction']) ? $_REQUEST['sAction'] : '';
        $sAction            = isset($sExport) ? $sExport : $sAction;

        $search_method      = 'post';
        if( $sAction == 'export_excel' ){
            $search_method  = 'get';
        }

        $iDisplayLength     = isset($_REQUEST['iDisplayLength']) ? intval($_REQUEST['iDisplayLength']) : 0;
        $iDisplayStart      = isset($_REQUEST['iDisplayStart']) ? intval($_REQUEST['iDisplayStart']) : 0;
        $sEcho              = isset($_REQUEST['sEcho']) ? intval($_REQUEST['sEcho']) : '';
        $sort               = isset($_REQUEST['sSortDir_0']) ? $_REQUEST['sSortDir_0'] : '';
        $column             = isset($_REQUEST['iSortCol_0']) ? intval($_REQUEST['iSortCol_0']) : '';

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_username         = $this->input->$search_method('search_username');
        $s_username         = ddm_isset($s_username, '');
        $s_name             = $this->input->$search_method('search_name');
        $s_name             = ddm_isset($s_name, '');
        $s_bank             = $this->input->$search_method('search_bank');
        $s_bank             = ddm_isset($s_bank, '');
        $s_bill             = $this->input->$search_method('search_bill');
        $s_bill             = ddm_isset($s_bill, '');
        $s_bill_name        = $this->input->$search_method('search_bill_name');
        $s_bill_name        = ddm_isset($s_bill_name, '');
        $s_status           = $this->input->$search_method('search_status');
        $s_status           = ddm_isset($s_status, '');
        $s_nominal_min      = $this->input->$search_method('search_nominal_min');
        $s_nominal_min      = ddm_isset($s_nominal_min, '');
        $s_nominal_max      = $this->input->$search_method('search_nominal_max');
        $s_nominal_max      = ddm_isset($s_nominal_max, '');
        $s_date_min         = $this->input->$search_method('search_datecreated_min');
        $s_date_min         = ddm_isset($s_date_min, '');
        $s_date_max         = $this->input->$search_method('search_datecreated_max');
        $s_date_max         = ddm_isset($s_date_max, '');
        $s_datemodified_min = $this->input->$search_method('search_datemodified_min');
        $s_datemodified_min = ddm_isset($s_datemodified_min, '');
        $s_datemodified_max = $this->input->$search_method('search_datemodified_max');
        $s_datemodified_max = ddm_isset($s_datemodified_max, '');

        if(!empty($s_username))     { $condition .= str_replace('%s%', $s_username, ' AND %username% LIKE "%%s%%"'); }
        if(!empty($s_name))         { $condition .= str_replace('%s%', $s_name, ' AND %name% LIKE "%%s%%"'); }
        if(!empty($s_bank))         { $condition .= str_replace('%s%', $s_bank, ' AND %bank% = %s%'); }
        if(!empty($s_bill))         { $condition .= str_replace('%s%', $s_bill, ' AND %bill% LIKE "%%s%%"'); }
        if(!empty($s_bill_name))    { $condition .= str_replace('%s%', $s_bill_name, ' AND %bill_name% LIKE "%%s%%"'); }
        if(!empty($s_status))       { $condition .= str_replace('%s%', ($s_status == 'pending' ? 0 : 1), ' AND %status% = %s%'); }
        if(!empty($s_nominal_min))  { $condition .= ' AND %nominal_receipt% >= ' . $s_nominal_min . ''; }
        if(!empty($s_nominal_max))  { $condition .= ' AND %nominal_receipt% <= ' . $s_nominal_max . ''; }
        if(!empty($s_date_min))     { $condition .= ' AND DATE(%datecreated%) >= "' . $s_date_min . '"'; }
        if(!empty($s_date_max))     { $condition .= ' AND DATE(%datecreated%) <= "' . $s_date_max . '"'; }
        if(!empty($s_datemodified_min)) { $condition .= ' AND DATE(%dateconfirm%) >= "' . $s_datemodified_min . '"'; }
        if(!empty($s_datemodified_max)) { $condition .= ' AND DATE(%dateconfirm%) <= "' . $s_datemodified_max . '"'; }

        if(!empty($condition)) {
            $condition = substr($condition, 4);
            $condition = ' WHERE' . $condition;
        }

        if ( $member_id ) {
            if($column == 1)        { $order_by .= '%datecreated% ' . $sort; }
            elseif($column == 2)    { $order_by .= '%bank% ' . $sort; } 
            elseif($column == 3)    { $order_by .= '%bill% ' . $sort . ', %bill_name% ' . $sort; } 
            elseif($column == 4)    { $order_by .= '%nominal_receipt% ' . $sort; } 
            elseif($column == 5)    { $order_by .= '%status% ' . $sort; } 
            elseif($column == 6)    { $order_by .= '%nominal% ' . $sort; } 
            elseif($column == 7)    { $order_by .= '%dateconfirm% ' . $sort; }
        } else {
            if($column == 1)        { $order_by .= '%username% ' . $sort; }
            elseif($column == 2)    { $order_by .= '%name% ' . $sort; } 
            elseif($column == 3)    { $order_by .= '%bank% ' . $sort; } 
            elseif($column == 4)    { $order_by .= '%bill% ' . $sort . ', %bill_name% ' . $sort; } 
            elseif($column == 5)    { $order_by .= '%nominal_receipt% ' . $sort; } 
            elseif($column == 6)    { $order_by .= '%status% ' . $sort; } 
            elseif($column == 7)    { $order_by .= '%nominal% ' . $sort; } 
            elseif($column == 8)    { $order_by .= '%datecreated% ' . $sort; } 
            elseif($column == 9)    { $order_by .= '%dateconfirm% ' . $sort; }
        }

        $withdraw_list      = $this->Model_Bonus->get_all_member_withdraw($limit, $offset, $condition, $order_by);
        $records            = array();
        $records["aaData"]  = array();

        if(!empty($withdraw_list)) {
            $iTotalRecords  = ddm_get_last_found_rows();
            $currency       = config_item('currency');
            $i = $offset + 1;
            foreach($withdraw_list as $row) {
                $id                 = ddm_encrypt($row->id); 
                $id_member          = ddm_encrypt($row->id_member);
                $username           = ddm_strong(strtolower($row->username)); 
                $username           = $is_admin ? '<a href="'. base_url('profile/' . $id_member) .'">'. $username .'</a>' : $username; 
                $name               = strtoupper($row->name); 
                $name               = $is_admin ? '<a href="'. base_url('profile/' . $id_member) .'">'. $name .'</a>' : $name; 

                $bank               = ddm_banks($row->bank);
                $bank_name          = '-';
                if ( ! empty( $bank->kode ) && ! empty( $bank->nama ) ){
                    $bank_name      = strtoupper($bank->kode .' - '. $bank->nama);
                }
                $rekening           = '-';
                if ( $row->bill ) {
                    $rekening       = 'No.Rek : '. ddm_strong($row->bill) . br();
                    $rekening      .= 'An. Rek : '. ddm_strong(strtoupper($row->bill_name));
                }

                $checkbox           = '<input name="withdraw[]" class="cbwithdraw" value="'. $id .'" type="checkbox" />';
                $status             = '<span class="badge badge-sm badge-default">PENDING</span>';
                $btn_confirm        = '<a href="'.base_url('commission/withdrawaltransfer/'.$id).'" 
                                        username="'.$row->username.'" 
                                        name="'.$row->name.'" 
                                        bank="'.$bank_name.'" 
                                        bill="'.$row->bill.'" 
                                        billnama="'.$row->bill_name.'" 
                                        nominal="'.ddm_accounting($row->nominal_receipt, $currency).'" 
                                        class="btn btn-sm btn-primary withdrawaltransfer">Transfer</a>';
                if ( $row->status > 0 ) {
                    $checkbox       = '<input type="checkbox" disabled="disabled" />';
                    $status         = '<span class="badge badge-sm badge-success">TRANSFERED</span>';
                    $btn_confirm    = '<a href="javascript:;" class="btn btn-sm btn-outline-success" disabled=""><i class="fa fa-check"></i></a>';
                }

                $detail             = 'Withdrawal: '. ddm_strong(ddm_accounting($row->nominal, $currency)) . br();
                // $detail            .= 'Pajak: '.ddm_strong(ddm_accounting($row->tax, $currency)) . br();
                $detail            .= 'Admin: '. ddm_strong(ddm_accounting($row->admin_fund, $currency));

                $datatables         = array(ddm_center($i));

                if ( $member_id ) {
                    $datatables[]   = ddm_center(date('Y-m-d @H:i', strtotime($row->datecreated)));
                } else {
                    $datatables[]   = ddm_center($username);
                    $datatables[]   = $name;
                }

                $datatables[]       = '<div style="min-width: 80px">'. ddm_center($bank_name) .'</div>';
                $datatables[]       = '<div style="min-width: 80px">'. $rekening .'</div>';
                $datatables[]       = '<div style="min-width: 80px">'. ddm_right(ddm_accounting($row->nominal_receipt)) .'</div>';
                $datatables[]       = '<div style="min-width: 80px">'. ddm_center($status) .'</div>';
                $datatables[]       = $detail;

                if ( ! $member_id ) {
                    $datatables[]   = ddm_center(date('Y-m-d @H:i', strtotime($row->datecreated)));
                }

                $datatables[]       = ddm_center( $row->status == 0 ? '' : date('Y-m-d @H:i', strtotime($row->dateconfirm)) );
                $datatables[]       = ( $is_admin ) ? ddm_center($btn_confirm) : '';
                
                $records["aaData"][] = $datatables;
                $i++;
            }
        }

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        if( $sAction == 'export_excel' ){
            $data_export                    = $this->Model_Bonus->get_all_member_withdraw(0, 0, $condition, $order_by);
            $export                         = $this->ddm_xls->withdraw( $data_export );
            
            //$records["sStatus"]             = "EXPORTED"; // pass custom message(useful for getting status of group actions)
            //$records["sMessage"]            = $export; // pass custom message(useful for getting status of group actions)
        }

        $records["sEcho"] = $sEcho;
        $records["iTotalRecords"] = $iTotalRecords;
        $records["iTotalDisplayRecords"] = $iTotalRecords;

        echo json_encode($records);
    }

    /**
     * Withdraw Monthly List function.
     */
    function withdrawmonthlylistdata()
    {
        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);

        $condition          = '';
        $total_condition    = '';
        $order_by           = '';
        $iTotalRecords      = 0;

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);
        $sEcho              = intval($_REQUEST['sEcho']);
        $sAction            = ddm_isset($_REQUEST['sAction'], '');

        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_month_min        = $this->input->post('search_month_min');
        $s_month_min        = ddm_isset($s_month_min, '');
        $s_month_max        = $this->input->post('search_month_max');
        $s_month_max        = ddm_isset($s_month_max, '');
        $s_bonus_min        = $this->input->post('search_bonus_min');
        $s_bonus_min        = ddm_isset($s_bonus_min, '');
        $s_bonus_max        = $this->input->post('search_bonus_max');
        $s_bonus_max        = ddm_isset($s_bonus_max, '');
        $s_admin_min        = $this->input->post('search_admin_min');
        $s_admin_min        = ddm_isset($s_admin_min, '');
        $s_admin_max        = $this->input->post('search_admin_max');
        $s_admin_max        = ddm_isset($s_admin_max, '');
        $s_tax_min          = $this->input->post('search_tax_min');
        $s_tax_min          = ddm_isset($s_tax_min, '');
        $s_tax_max          = $this->input->post('search_tax_max');
        $s_tax_max          = ddm_isset($s_tax_max, '');
        $s_transfer_min     = $this->input->post('search_transfer_min');
        $s_transfer_min     = ddm_isset($s_transfer_min, '');
        $s_transfer_max     = $this->input->post('search_transfer_max');
        $s_transfer_max     = ddm_isset($s_transfer_max, '');

        if(!empty($s_month_min))    { $condition .= ' AND %month% >= "' . $s_month_min . '"'; }
        if(!empty($s_month_max))    { $condition .= ' AND %month% <= "' . $s_month_max . '"'; }
        if(!empty($s_bonus_min))    { $total_condition .= ' AND %bonus% >= ' . $s_bonus_min . ''; }
        if(!empty($s_bonus_max))    { $total_condition .= ' AND %bonus% <= ' . $s_bonus_max . ''; }
        if(!empty($s_tax_min))      { $total_condition .= ' AND %tax% >= ' . $s_tax_min . ''; }
        if(!empty($s_tax_max))      { $total_condition .= ' AND %tax% <= ' . $s_tax_max . ''; }
        if(!empty($s_admin_min))    { $total_condition .= ' AND %admin% >= ' . $s_admin_min . ''; }
        if(!empty($s_admin_max))    { $total_condition .= ' AND %admin% <= ' . $s_admin_max . ''; }
        if(!empty($s_transfer_min)) { $total_condition .= ' AND %transfer% >= ' . $s_transfer_min . ''; }
        if(!empty($s_transfer_max)) { $total_condition .= ' AND %transfer% <= ' . $s_transfer_max . ''; }

        if(!empty($condition)) {
            $condition = substr($condition, 4);
            $condition = ' WHERE' . $condition;
        }

        if($column == 1)        { $order_by .= '%month% ' . $sort; } 
        elseif($column == 2)    { $order_by .= '%bonus% ' . $sort; } 
        elseif($column == 3)    { $order_by .= '%tax% ' . $sort; } 
        elseif($column == 4)    { $order_by .= '%admin% ' . $sort; } 
        elseif($column == 5)    { $order_by .= '%transfer% ' . $sort; }

        $withdraw_list = $this->Model_Bonus->get_all_withdraw_monthly($limit, $offset, $condition, $order_by, $total_condition);

        $records = array();
        $records["aaData"] = array();

        if(!empty($withdraw_list)) {
            $iTotalRecords = ddm_get_last_found_rows();
            $i = $offset + 1;
            foreach($withdraw_list as $row) {
                $records["aaData"][] = array(
                    ddm_center($i),
                    ddm_center(date('F, Y', strtotime($row->month))),
                    '<div style="min-width: 100px">' . ddm_right(ddm_accounting($row->total_bonus)) . '</div>',
                    '<div style="min-width: 100px">' . ddm_right(ddm_accounting($row->total_tax)) . '</div>',
                    '<div style="min-width: 100px">' . ddm_right(ddm_accounting($row->total_admin)) . '</div>',
                    '<div style="min-width: 100px">' . ddm_right(ddm_accounting($row->total_transfer)) . '</div>',
                    ''
                );
                $i++;
            }
        }

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"] = $sEcho;
        $records["iTotalRecords"] = $iTotalRecords;
        $records["iTotalDisplayRecords"] = $iTotalRecords;

        echo json_encode($records);
    }

    // =============================================================================================
    // ACTION FUNCTION
    // =============================================================================================

    /**
     * Withdrawal Transfer function.
     */
    function withdrawaltransfer($id = 0)
    {
        if ( ! $this->input->is_ajax_request() ) redirect(base_url('commission/withdraw'), 'refresh');

        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'login', 'url' => base_url('login') );
            die(json_encode($data));
        } 

        $data = array('status' => 'error', 'message' => 'ID Withdrawal tidak dikenali. Silahkan pilih ID Witdrawal lainnya!');

        if( !$id ) {
            die(json_encode($data));
        }

        $id                 = ddm_decrypt($id);
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

        if( !$is_admin ){
            $data['message'] = 'Maaf, hanya Administrator yang dapat Konfirmasi Witdrawal !';
            die(json_encode($data));
        }

        if ( ! $withdraw = $this->Model_Bonus->get_withdraw_by_id($id) ) {
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

        // // Password Global
        // if ( $password_global = config_item('password_global') ) {
        //     if ( ddm_hash_verify($password, $password_global) ) {
        //         $pwd_valid  = true;
        //     }
        // }

        // Set Log Data
        $status_msg             = '';
        $log_data               = array('cookie' => $_COOKIE);
        $log_data['id_wd']      = $withdraw->id;
        $log_data['status']     = 'Konfirmasi Withdraw';

        if (!$pwd_valid) {
            $log_data['message']    = 'invalid password';
            $data['message']        = 'Maaf, Password anda tidak valid !';
            if ( $withdraw->status == 0 ) {
                ddm_log_action('WITHDRAW_CONFIRM', 'ERROR', $confirmed_by, json_encode($log_data));
            }
            die(json_encode($data));
        }

        if ( $withdraw->status > 0 ) {
            $data['message'] = 'Data Withdraw sudah ditransfer. Silahkan pilih ID Witdraw lainnya!';
            die(json_encode($data));
        }

        if( ! $member = $this->Model_Member->get_memberdata($withdraw->id_member) ) {
            $data['message'] = 'Data Member tidak ditemukan. Silahkan pilih ID Witdraw lainnya!';
            die(json_encode($data));
        }

        $datawithdraw   = array(
            'status'        => 1, 
            'datemodified'  => $datetime,
            'dateconfirm'   => $datetime,
            'confirm_by'    => $confirmed_by
        );

        if( ! $update_wd_data = $this->Model_Bonus->update_data_withdraw($id, $datawithdraw) ) {
            $data['message'] = 'Konfirmasi Withdraw tidak berhasil dikarenakan ada kesalahan sistem!';
            die(json_encode($data));
        }


        $log_data['id_member']  = $member->id;
        $log_data['username']   = $member->username;
        $log_data['nominal']    = $withdraw->nominal_receipt;
        ddm_log_action( 'WITHDRAW_CONFIRM', 'SUCCESS', $id, json_encode($log_data) );

        // Send Email and WhatsApp
        $this->ddm_email->send_email_withdraw( $member, $withdraw );
        // $this->ddm_wa->send_wa_withdraw( $member, $withdraw );
        
        // Set JSON data
        $data = array('status' => 'success', 'message' => 'Konfirmasi Withdraw berhasil!');
        die(json_encode($data));
    }

}

/* End of file Commission.php */
/* Location: ./app/controllers/Commission.php */
