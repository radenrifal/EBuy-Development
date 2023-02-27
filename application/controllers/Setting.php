<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Setting Controller.
 *
 * @class     Setting
 * @version   1.0.0
 */
class Setting extends Admin_Controller {
    /**
	 * Constructor.
	 */
    function __construct()
    {
        parent::__construct();
    }

    // =============================================================================================
    // SETTING PAGE
    // =============================================================================================

    /**
     * Setting General function.
     */
    function general()
    {
        auth_redirect();

        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);
        
        $headstyles             = ddm_headstyles(array(
            // Default CSS Plugin
        ));
        
        $loadscripts            = ddm_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-notify/bootstrap-notify.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'bootbox/bootbox.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'form-validation.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK,
        ));

        $scripts_init           = ddm_scripts_init(array(
            'InputMask.init();',
            'SelectChange.init();',
            'GeneralSetting.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_setting') .' '. lang('menu_setting_general');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'setting/general';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Setting Notification function.
     */
    public function notification()
    {
        auth_redirect();

        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);

        $headstyles             = ddm_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN
        ));
        $loadscripts            = ddm_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-notify/bootstrap-notify.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'bootbox/bootbox.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'ckeditor/ckeditor.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'form-validation.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = ddm_scripts_init(array(
            'GeneralSetting.init();',
            'FV_Notification.init();',
            'TableAjaxNotifList.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_setting') .' '. lang('menu_setting_notification');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'setting/notifications';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Setting Reward function.
     */
    public function reward( $form = '', $id = '' )
    {
        auth_redirect();

        $dataform               = '';
        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);

        if ( $form ) {
            if ( $form != 'create' && $form != 'edit' ) {
                redirect(base_url('setting/reward'), 'refresh');
            }
            if ( $form == 'create' && $id )   { redirect(base_url('setting/reward'), 'refresh'); }
            if ( $form == 'edit' && ! $id )   { redirect(base_url('setting/reward'), 'refresh'); }

            $id = ddm_decrypt($id);
            if ( $form == 'edit' && $id ) {
                if ( ! $dataform = $this->Model_Option->get_reward_by('id', $id) ) {
                    redirect(base_url('setting/reward'), 'refresh');
                }
            }

            $main_content           = 'setting/form/reward';
            $headstyles             = ddm_headstyles(array(
                // Default CSS Plugin
            ));
            $loadscripts            = ddm_scripts(array(
                // Default JS Plugin
                BE_PLUGIN_PATH . 'bootstrap-notify/bootstrap-notify.min.js?ver=' . JS_VER_MAIN,
                BE_PLUGIN_PATH . 'bootbox/bootbox.min.js?ver=' . JS_VER_MAIN,
                BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
                BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
                BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
                // Always placed at bottom
                BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
                BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
            ));
            $scripts_init           = ddm_scripts_init(array(
                'InputMask.init();',
                'HandleDatepicker.init();',
                'GeneralSetting.initReward();'
            ));
        } else {
            $main_content           = 'setting/reward';
            $headstyles             = ddm_headstyles(array(
                // Default CSS Plugin
                BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN
            ));
            $loadscripts            = ddm_scripts(array(
                // Default JS Plugin
                BE_PLUGIN_PATH . 'bootstrap-notify/bootstrap-notify.min.js?ver=' . JS_VER_MAIN,
                BE_PLUGIN_PATH . 'bootbox/bootbox.min.js?ver=' . JS_VER_MAIN,
                BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
                BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
                BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
                BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
                BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
                // Always placed at bottom
                BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
                BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
            ));
            $scripts_init           = ddm_scripts_init(array(
                'TableAjaxSettingRewardList.init();'
            ));
        }

        $alert_msg              = '';
        if ( $this->session->userdata('alert_msg') ) {
            $alert_msg          = $this->session->userdata('alert_msg');
            $this->session->unset_userdata('alert_msg');
        }

        $data['title']          = TITLE . lang('menu_setting') .' '. lang('menu_setting_reward');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['form']           = $form;
        $data['dataform']       = $dataform;
        $data['alert_msg']      = $alert_msg;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = '';
        $data['main_content']   = $main_content;

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Setting withdraw function.
     */
    function withdraw()
    {
        auth_redirect();

        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);
        
        $headstyles             = ddm_headstyles(array(
            // Default CSS Plugin
        ));
        
        $loadscripts            = ddm_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-notify/bootstrap-notify.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'bootbox/bootbox.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'form-validation.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));

        $scripts_init           = ddm_scripts_init(array(
            'InputMask.init();',
            'FV_SettingWithdraw.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_setting') .' '. lang('menu_setting_withdraw');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'setting/withdraw';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Setting Intro function.
     */
    function intro()
    {
        auth_redirect();

        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);
        
        $headstyles             = ddm_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN
        ));
        
        $loadscripts            = ddm_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-notify/bootstrap-notify.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'bootbox/bootbox.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));

        $scripts_init           = ddm_scripts_init(array(
            'GeneralSetting.initIntro();',
            'TableAjaxIntroList.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_setting') .' Intro';
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'setting/intro';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    // ---------------------------------------------------------------------------------------------

    // =============================================================================================
    // LIST DATA SETTING
    // =============================================================================================

    /**
     * Promo Code List Data function.
     */
    function promocodelistdata($type = '')
    {
        $member_data        = '';
        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);

        $condition          = '';
        if ( $type == 'global' ) {
            $condition      = ' AND (products IS NULL OR products = "") ';
        }
        if ( $type == 'products' ) {
            $condition      = ' AND products IS NOT NULL AND products != ""';
        }
        $order_by           = '';
        $iTotalRecords      = 0;

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);

        $sEcho              = intval($_REQUEST['sEcho']);
        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ( $iDisplayLength == '-1' ? 0 : $iDisplayLength );
        $offset             = $iDisplayStart;

        $s_code             = $this->input->post('search_code');
        $s_code             = ddm_isset($s_code, '');
        $s_agent_type       = $this->input->post('search_type_agent');
        $s_agent_type       = ddm_isset($s_agent_type, '');
        $s_agent_min        = $this->input->post('search_discount_agent_min');
        $s_agent_min        = ddm_isset($s_agent_min, '');
        $s_agent_max        = $this->input->post('search_discount_agent_max');
        $s_agent_max        = ddm_isset($s_agent_max, '');
        $s_customer_type    = $this->input->post('search_type_customer');
        $s_customer_type    = ddm_isset($s_customer_type, '');
        $s_customer_min     = $this->input->post('search_discount_customer_min');
        $s_customer_min     = ddm_isset($s_customer_min, '');
        $s_customer_max     = $this->input->post('search_discount_customer_max');
        $s_customer_max     = ddm_isset($s_customer_max, '');
        $s_datecreated_min  = $this->input->post('search_datecreated_min');
        $s_datecreated_min  = ddm_isset($s_datecreated_min, '');
        $s_datecreated_max  = $this->input->post('search_datecreated_max');
        $s_datecreated_max  = ddm_isset($s_datecreated_max, '');
        $s_status           = $this->input->post('search_status');
        $s_status           = ddm_isset($s_status, '');

        if ( !empty($s_code) )          { $condition .= str_replace('%s%', $s_code, ' AND %promo_code% LIKE "%%s%%"'); }
        if ( !empty($s_agent_type) )    { $condition .= str_replace('%s%', $s_agent_type, ' AND discount_agent_type = "%s%"'); }
        if ( !empty($s_customer_type) ) { $condition .= str_replace('%s%', $s_customer_type, ' AND discount_customer_type = "%s%"'); }
        if ( !empty($s_agent_min) )     { $condition .= str_replace('%s%', $s_agent_min, ' AND discount_agent >= %s%'); }
        if ( !empty($s_agent_max) )     { $condition .= str_replace('%s%', $s_agent_max, ' AND discount_agent <= %s%'); }
        if ( !empty($s_customer_min) )  { $condition .= str_replace('%s%', $s_customer_min, ' AND discount_customer >= %s%'); }
        if ( !empty($s_customer_max) )  { $condition .= str_replace('%s%', $s_customer_max, ' AND discount_customer <= %s%'); }
        if ( !empty($s_datecreated_min) )   { $condition .= str_replace('%s%', $s_datecreated_min, ' AND DATE(datecreated) >= "%s%"'); }
        if ( !empty($s_datecreated_max) )   { $condition .= str_replace('%s%', $s_datecreated_max, ' AND DATE(datecreated) <= "%s%"'); }
        if ( !empty($s_status) )        { 
            $s_status   = ( $s_status == 'active' ) ? 1 : 0;
            $condition .= str_replace('%s%', $s_status, ' AND status = %s%'); 
        }

        if( $column == 1 )      { $order_by .= '%promo_code% ' . $sort; }
        elseif( $column == 2 )  { $order_by .= 'discount_agent_type ' . $sort; }
        elseif( $column == 3 )  { $order_by .= 'discount_agent ' . $sort; }
        elseif( $column == 4 )  { $order_by .= 'discount_customer_type ' . $sort; }
        elseif( $column == 5 )  { $order_by .= 'discount_customer ' . $sort; }
        elseif( $column == 6 )  { $order_by .= 'status ' . $sort; }

        $data_list          = $this->Model_Option->get_all_promo_code($limit, $offset, $condition, $order_by);
        $records            = array();
        $records["aaData"]  = array();

        if( !empty($data_list) ){
            $iTotalRecords  = ddm_get_last_found_rows();
            $discount_type  = config_item('discount_type');
            $i = $offset + 1;
            foreach($data_list as $row){
                $id             = ddm_encrypt($row->id);
                $promo_code     = ddm_strong(ucwords($row->promo_code));
                
                if ( $row->status == 1 ) {
                    $status     = '<a href="'.base_url('promocode/promocodestatus/'.$id).'" class="btn btn-sm btn-outline-success btn-status-promo" data-promo="'.$row->promo_code.'" data-status="'.$row->status.'"><i class="fa fa-check"></i> Active</a>';
                } else {
                    $status     = '<a href="'.base_url('promocode/promocodestatus/'.$id).'" class="btn btn-sm btn-outline-danger btn-status-promo" data-promo="'.$row->promo_code.'" data-status="'.$row->status.'"><i class="fa fa-times"></i> Non-Active</a>';
                }

                $agent_type     = isset($discount_type[$row->discount_agent_type]) ? $discount_type[$row->discount_agent_type] : '';
                $customer_type  = isset($discount_type[$row->discount_customer_type]) ? $discount_type[$row->discount_customer_type] : '';
                if ( $row->discount_agent_type == 'nominal' ) {
                    $discount_agent = ddm_accounting($row->discount_agent, '', true);
                } else {
                    $discount_agent = ddm_right(ddm_accounting($row->discount_agent) . ' %');
                }
                if ( $row->discount_customer_type == 'nominal' ) {
                    $discount_customer = ddm_accounting($row->discount_customer, '', true);
                } else {
                    $discount_customer = ddm_right(ddm_accounting($row->discount_customer) . ' %');
                }

                $btn_edit   = '<a class="btn btn-sm btn-primary btn-tooltip btn-edit-promo" 
                                href="'.base_url('promocode/savepromocode/'.$id).'" 
                                data-code="'.$id.'"
                                data-promo="'.$row->promo_code.'"
                                data-agent_type="'.$row->discount_agent_type.'"
                                data-agent_discount="'.($row->discount_agent + 0).'"
                                data-customer_type="'.$row->discount_customer_type.'"
                                data-customer_discount="'.($row->discount_customer + 0).'"
                                data-products=\''.$row->products.'\'"
                                title="Edit Promo" ><i class="fa fa-edit"></i></a>';
                $btn_delete = '<a class="btn btn-sm btn-warning btn-tooltip" title="View" href="'.base_url('setting/notifdata/'.$row->id.'/view').'"><i class="fa fa-eye"></i></a>';
                
                $records["aaData"][]    = array(
                    ddm_center($i),
                    $promo_code,
                    ddm_center($agent_type),
                    $discount_agent,
                    ddm_center($customer_type),
                    $discount_customer,
                    ddm_center($status),
                    ddm_center(date('Y-m-d @H:i', strtotime($row->datecreated))),
                    ddm_center($btn_edit),
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
     * Setting Notification List Data function.
     */
    function notificationlistdata()
    {
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

        $s_name             = $this->input->post('search_name');
        $s_name             = ddm_isset($s_name, '');
        $s_type             = $this->input->post('search_type');
        $s_type             = ddm_isset($s_type, '');
        $s_status           = $this->input->post('search_status');
        $s_status           = ddm_isset($s_status, '');

        if ( !empty($s_name) )          { $condition .= str_replace('%s%', $s_name, ' AND %name% LIKE "%%s%%"'); }
        if ( !empty($s_type) )          { $condition .= str_replace('%s%', $s_type, ' AND %type% = "%s%"'); }
        if ( !empty($s_status) )        { 
            $s_status   = ( $s_status == 'active' ) ? 1 : 0;
            $condition .= str_replace('%s%', $s_status, ' AND %status% = %s%'); 
        }

        if( $column == 1 )      { $order_by .= '%name% ' . $sort; }
        elseif( $column == 2 )  { $order_by .= '%type% ' . $sort; }
        elseif( $column == 3 )  { $order_by .= '%status% ' . $sort; }

        $data_list          = $this->Model_Option->get_all_notification_data($limit, $offset, $condition, $order_by);
        $records            = array();
        $records["aaData"]  = array();

        if( !empty($data_list) ){
            $iTotalRecords  = ddm_get_last_found_rows();
            $i = $offset + 1;
            foreach($data_list as $row){
                $lbl_class  = 'default';
                if ( $row->type == 'email' )    { $lbl_class = 'primary'; }
                if ( $row->type == 'whatsapp' ) { $lbl_class = 'success'; }
                $type       = '<span class="badge badge-sm badge-'.$lbl_class.'">'.strtoupper($row->type).'</span>';

                $status     = '<span class="badge badge-sm badge-danger">TIDAK AKTIF</span>';
                if ( $row->status > 0 ) {
                    $status = '<span class="badge badge-sm badge-success">AKTIF</span>';
                }

                $btn_edit   = '<a class="btn btn-sm btn-tooltip btn-primary notifdata" title="Edit" href="'.base_url('setting/notifdata/'.$row->id.'/edit').'"><i class="fa fa-edit"></i></a>';
                $btn_view   = '<a class="btn btn-sm btn-tooltip btn-secondary notifdata" title="View" href="'.base_url('setting/notifdata/'.$row->id.'/view').'"><i class="fa fa-eye"></i></a>';
                
                $records["aaData"][]    = array(
                    ddm_center($i),
                    $row->name,
                    ddm_center($type),
                    ddm_center($status),
                    ddm_center($btn_edit.$btn_view),
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
     * Setting Reward List Data function.
     */
    function rewardlistdata()
    {
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

        $s_name             = $this->input->post('search_name');
        $s_name             = ddm_isset($s_name, '');
        $s_type             = $this->input->post('search_type');
        $s_type             = ddm_isset($s_type, '');
        $s_status           = $this->input->post('search_status');
        $s_status           = ddm_isset($s_status, '');

        if ( !empty($s_name) )          { $condition .= str_replace('%s%', $s_name, ' AND %name% LIKE "%%s%%"'); }
        if ( !empty($s_type) )          { $condition .= str_replace('%s%', $s_type, ' AND %type% = "%s%"'); }
        if ( !empty($s_status) )        { 
            $s_status   = ( $s_status == 'active' ) ? 1 : 0;
            $condition .= str_replace('%s%', $s_status, ' AND %status% = %s%'); 
        }

        if( $column == 1 )      { $order_by .= '%name% ' . $sort; }
        elseif( $column == 2 )  { $order_by .= '%type% ' . $sort; }
        elseif( $column == 3 )  { $order_by .= '%status% ' . $sort; }

        $data_list          = $this->Model_Option->get_all_reward_data($limit, $offset, $condition, $order_by);
        $records            = array();
        $records["aaData"]  = array();

        if( !empty($data_list) ){
            $iTotalRecords  = ddm_get_last_found_rows();
            $i = $offset + 1;
            foreach($data_list as $row){
                $id         = ddm_encrypt($row->id);  

                $period     = '<span class="badge badge-sm badge-danger d-block mb-2">Periode Reward</span>';
                $period    .= 'Start : '. date('d-M-Y', strtotime($row->start_date)) .br();
                $period    .= 'End : '. date('d-M-Y', strtotime($row->end_date));
                if ( $row->is_lifetime > 0 ) {
                    $period = '<span class="badge badge-sm badge-success">Lifetime Reward</span>';
                }

                if ( $row->is_active == 1 ) {
                    $status = '<a href="'.base_url('setting/rewardstatus/'.$id).'" 
                                class="btn btn-sm btn-outline-success btn-status-setting-reward" 
                                data-reward="'.$row->reward.'" 
                                data-status="'.$row->is_active.'"><i class="fa fa-check"></i> Active</a>';
                } else {
                    $status = '<a href="'.base_url('setting/rewardstatus/'.$id).'" 
                                class="btn btn-sm btn-outline-danger btn-status-setting-reward" 
                                data-reward="'.$row->reward.'" 
                                data-status="'.$row->is_active.'"><i class="fa fa-times"></i> Non-Active</a>';
                }

                $btn_edit   = '<a class="btn btn-sm btn-tooltip btn-primary" title="Edit" href="'.base_url('setting/reward/edit/'.$id).'"><i class="fa fa-edit"></i></a>';
                $btn_delete = '<a class="btn btn-sm btn-tooltip btn-warning" title="Hapus" href="'.base_url('setting/reward/delete/'.$id).'"><i class="fa fa-times"></i></a>';
                
                $records["aaData"][]    = array(
                    ddm_center($i),
                    $row->reward,
                    ddm_accounting($row->nominal, '', true),
                    ddm_accounting($row->point, '', true),
                    ddm_center($period),
                    ddm_center($status),
                    ddm_center($btn_edit.$btn_delete),
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
     * Setting Intro List Data function.
     */
    function introlistdata()
    {
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

        $s_name             = $this->input->post('search_name');
        $s_name             = ddm_isset($s_name, '');
        $s_type             = $this->input->post('search_type');
        $s_type             = ddm_isset($s_type, '');
        $s_status           = $this->input->post('search_status');
        $s_status           = ddm_isset($s_status, '');

        if ( !empty($s_name) )          { $condition .= str_replace('%s%', $s_name, ' AND %name% LIKE "%%s%%"'); }
        if ( !empty($s_type) )          { $condition .= str_replace('%s%', $s_type, ' AND %type% = "%s%"'); }
        if ( !empty($s_status) )        { 
            $s_status   = ( $s_status == 'active' ) ? 1 : 0;
            $condition .= str_replace('%s%', $s_status, ' AND %status% = %s%'); 
        }

        if( $column == 1 )      { $order_by .= 'name ' . $sort; }

        $data_list          = $this->Model_Option->get_all_intro_data($limit, $offset, $condition, $order_by);
        $records            = array();
        $records["aaData"]  = array();

        if( !empty($data_list) ){
            $iTotalRecords  = ddm_get_last_found_rows();
            $i = $offset + 1;
            foreach($data_list as $row){
                $id         = ddm_encrypt($row->id); 
                $image    = '
                            <a href="'. $row->file_url .'" class="" target="_blank">
                                <img class="img-thumbnail" width="100%" src="'. $row->file_url .'" style="cursor: pointer;">
                            </a>';

                $btn_delete = '<a class="btn btn-sm btn-warning btn-delete-intro" data-image="'.$row->file_url.'" href="'.base_url('setting/deleteintro/'.$id).'"><i class="fa fa-times"></i> Hapus</a>';
                
                $records["aaData"][]    = array(
                    ddm_center($i),
                    ddm_center($image),
                    ddm_center($btn_delete),
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

    // ---------------------------------------------------------------------------------------------

    // =============================================================================================
    // ACTIONS SETTING
    // =============================================================================================

    /**
     * Get Data Notification function.
     */
    function notifdata($id='', $action = '')
    {
        // Check for AJAX Request
        if( !$this->input->is_ajax_request() ){ redirect(base_url('setting/notification'), 'location'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        // ID Data 
        if ( ! $id ) {
            $data = array('status' => 'error', 'message' => 'ID Notification tidak dikenali !');
            die(json_encode($data));
        }

        // Get Data Notification 
        if ( ! $notification = $this->Model_Option->get_notification_by('id', $id)  ) {
            $data = array('status' => 'error', 'message' => 'Data Notification tidak ditemukan !');
            die(json_encode($data));
        }

        $action     = $action ? $action : 'view';

        if ( $action == 'view' ) {
            if ( $notification->type == 'email' ) {
                $notification->content = ddm_notification_email_template($notification->content, $notification->title);
            } else {
                $notification->content = '<div style="padding: 0px 15px"><pre>'. $notification->content .'</pre></div>';
            }
        } else {
            if ( $notification->type != 'email' ) {
                $notification->content = strip_tags($notification->content);
            }
        }

        $data = array('status'=>'success', 'process'=>$action, 'notification'=>$notification, 'message'=>'Data Notification ditemukan.');
        die(json_encode($data));
    }

    /**
     * Update Setting General function.
     */
    function updatesetting($field='')
    {
        // Check for AJAX Request
        if( !$this->input->is_ajax_request() ){ redirect(base_url('setting/general'), 'location'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        // Get Data Field 
        if ( ! $field  ) {
            $data = array('status' => 'error', 'message' => 'Update Setting tidak berhasil. Data Setting tidak ditemukan !');
            die(json_encode($data));
        }

        // Get Data Form
        $value              = $this->input->post('value');
        $value              = ddm_isset($value, '');

        if ( $field == 'register_fee' ) {
            $value          = str_replace('.', '', $value);
        }

        // Update Setting
        $newvalue           = maybe_serialize( $value );
        $data               = array('value' => $newvalue);
        $this->db->where('name', $field);

        // Get Data Field 
        if ( ! $result = $this->db->update(TBL_OPTIONS, $data) ) {
            $data = array('status' => 'error', 'message' => 'Update Setting tidak berhasil. Terjadi kesalahan pada proses transaksi !');
            die(json_encode($data));
        }

        // Update Setting Success
        $data = array('status' => 'success', 'message' => 'Update Setting berhasil.');
        die(json_encode($data));
    }

    /**
     * Update All Setting General function.
     */
    function updateallsetting($field='')
    {
        // Check for AJAX Request
        if( !$this->input->is_ajax_request() ){ redirect(base_url('setting/general'), 'location'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }
     
        // Get Data Field 
        if ( ! $field = $this->input->post('field') ) {
            $data = array('status' => 'error', 'message' => 'Update Setting tidak berhasil. Data Setting tidak ditemukan !');
            die(json_encode($data));
        }

        foreach ($field as $key => $value) {
            // Update Data Setting
            $newvalue   = maybe_serialize( $value );
            $data       = array('value' => $newvalue);
            if ( ! $update_data = $this->db->where('name', $key)->update(TBL_OPTIONS, $data)) {
                $data = array('status' => 'error', 'message' => 'Update Setting tidak berhasil. Terjadi kesalahan pada proses transaksi !');
                die(json_encode($data));
            }
        }

        // Update Setting Success
        $data = array('status' => 'success', 'message' => 'Update Setting berhasil.');
        die(json_encode($data));
    }

    /**
     * Update Data Company function.
     */
    function updatecompany()
    {
        // Check for AJAX Request
        if( !$this->input->is_ajax_request() ){ redirect(base_url('setting/general'), 'location'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        // POST Input Form
        $company_name       = $this->input->post( 'company_name' );
        $company_name       = ddm_isset($company_name, '');
        $company_phone      = $this->input->post( 'company_phone' );
        $company_phone      = ddm_isset($company_phone, '');
        $company_email      = $this->input->post( 'company_email' );
        $company_email      = ddm_isset($company_email, '');
        $company_province   = $this->input->post( 'company_province' );
        $company_province   = ddm_isset($company_province, '');
        $company_city       = $this->input->post( 'company_city' );
        $company_city       = ddm_isset($company_city, '');
        $company_address   = $this->input->post( 'company_address' );
        $company_address   = ddm_isset($company_address, '');

        $this->form_validation->set_rules('company_name','Nama Perusahaan','required');
        $this->form_validation->set_rules('company_phone','No. Telp Perusahaan','required');
        $this->form_validation->set_rules('company_email','Email Perusahaan','required');
        $this->form_validation->set_rules('company_province','Provinsi','required');
        $this->form_validation->set_rules('company_city','Kota/Kabupaten','required');
        $this->form_validation->set_rules('company_address','Alamat Perusahaan','required');
        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE){
            $data = array('status' => 'error', 'message' => validation_errors() );
            die(json_encode($data));
        }

        // Update Data Company Name
        if ( ! $update_data = $this->db->where('name', 'company_name')->update(TBL_OPTIONS, array('value' => $company_name ))) {
            $data = array('status' => 'error', 'message' => 'Nama Perusahaan tidak berhasil di ubah. ');
            die(json_encode($data));
        }

        if ( substr($company_phone, 0, 1) != '0' ) {
            $company_phone  = '0'. $company_phone;
        }

        // Update Data Company Phone
        if ( ! $update_data = $this->db->where('name', 'company_phone')->update(TBL_OPTIONS, array('value' => $company_phone ))) {
            $data = array('status' => 'error', 'message' => 'No. Telp Perusahaan tidak berhasil di ubah. ');
            die(json_encode($data));
        }

        // Update Data Company Phone
        if ( ! $update_data = $this->db->where('name', 'company_email')->update(TBL_OPTIONS, array('value' => $company_email ))) {
            $data = array('status' => 'error', 'message' => 'Email Perusahaan tidak berhasil di ubah. ');
            die(json_encode($data));
        }

        // Update Data Province
        if ( ! $update_data = $this->db->where('name', 'company_province')->update(TBL_OPTIONS, array('value' => $company_province ))) {
            $data = array('status' => 'error', 'message' => 'Provinsi tidak berhasil di ubah. ');
            die(json_encode($data));
        }

        // Update Data City
        if ( ! $update_data = $this->db->where('name', 'company_city')->update(TBL_OPTIONS, array('value' => $company_city ))) {
            $data = array('status' => 'error', 'message' => 'Kota/Kabupaten tidak berhasil di ubah. ');
            die(json_encode($data));
        }

        // Update Data Address
        if ( ! $update_data = $this->db->where('name', 'company_address')->update(TBL_OPTIONS, array('value' => $company_address ))) {
            $data = array('status' => 'error', 'message' => 'Alamat Perusahaan tidak berhasil di ubah. ');
            die(json_encode($data));
        }

        // Update Success
        $data = array('status'=>'success', 'message'=>'Informasi Perusahaan berhasil d ubah.');
        die(json_encode($data));
    }

    /**
     * Update Data Company Billing function.
     */
    function updatecompanybilling()
    {
        // Check for AJAX Request
        if( !$this->input->is_ajax_request() ){ redirect(base_url('setting/general'), 'location'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        // POST Input Form
        $company_bank       = $this->input->post( 'company_bank' );
        $company_bank       = ddm_isset($company_bank, '');
        $company_bill       = $this->input->post( 'company_bill' );
        $company_bill       = ddm_isset($company_bill, '');
        $company_bill_name  = $this->input->post( 'company_bill_name' );
        $company_bill_name  = ddm_isset($company_bill_name, '');

        $this->form_validation->set_rules('company_bank','Bank Perusahaan','required');
        $this->form_validation->set_rules('company_bill','Nomor Rekening Perusahaan','required');
        $this->form_validation->set_rules('company_bill_name','Nama Pemilik Rekening','required');
        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE){
            $data = array('status' => 'error', 'message' => validation_errors() );
            die(json_encode($data));
        }

        // Update Data Company Bank
        if ( ! $update_data = $this->db->where('name', 'company_bank')->update(TBL_OPTIONS, array('value' => $company_bank ))) {
            $data = array('status' => 'error', 'message' => 'Bank Perusahaan tidak berhasil di ubah. ');
            die(json_encode($data));
        }

        // Update Data Company Bill
        if ( ! $update_data = $this->db->where('name', 'company_bill')->update(TBL_OPTIONS, array('value' => $company_bill ))) {
            $data = array('status' => 'error', 'message' => 'No. Rekening Perusahaan tidak berhasil di ubah. ');
            die(json_encode($data));
        }

        // Update Data Company Bill Name
        if ( ! $update_data = $this->db->where('name', 'company_bill_name')->update(TBL_OPTIONS, array('value' => $company_bill_name ))) {
            $data = array('status' => 'error', 'message' => 'Nama Pemilik Rekening Perusahaan tidak berhasil di ubah. ');
            die(json_encode($data));
        }

        // Update Success
        $data = array('status'=>'success', 'message'=>'Informasi Bank Perusahaan berhasil d ubah.');
        die(json_encode($data));
    }

    /**
     * Update Data Notification function.
     */
    function updatenotification()
    {
        // Check for AJAX Request
        if( !$this->input->is_ajax_request() ){ redirect(base_url('setting/notification'), 'location'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        // POST Input Form
        $notif_id       = $this->input->post('notif_id');
        $notif_id       = ddm_isset($notif_id, '');
        $notif_type     = $this->input->post('notif_type');
        $notif_type     = ddm_isset($notif_type, '');
        $notif_title    = $this->input->post('notif_title');
        $notif_title    = ddm_isset($notif_title, '');
        $notif_status   = $this->input->post('notif_status');
        $notif_status   = ddm_isset($notif_status, '');
        $content_email  = $this->input->post('content_email');
        $content_email  = ddm_isset($content_email, '');
        $content_plain  = $this->input->post('content_plain');
        $content_plain  = ddm_isset($content_plain, '');

        // Get Data Notification 
        if ( ! $notification = $this->Model_Option->get_notification_by('id', $notif_id)  ) {
            $data = array('status' => 'error', 'message' => 'Update Notifikasi tidak berhasil. Data Notification tidak ditemukan !');
            die(json_encode($data));
        }

        $content        = ( strtolower($notif_type) == 'email' ) ? $content_email : $content_plain;     

        // Set and Update Data Notification
        $data_notif     = array('title' => $notif_title, 'content' => $content, 'status' => $notif_status);
        if ( ! $update_notif = $this->Model_Option->update_data_notification($notification->id, $data_notif) ) {
            $data = array('status' => 'error', 'message' => 'Update Notifikasi tidak berhasil. Terjasi kesalahan pada proses transaksi.');
            die(json_encode($data));
        }

        // Update Success
        $data = array('status'=>'success', 'message'=>'Update Notifikasi berhasil.');
        die(json_encode($data));
    }

    /**
     * Update Data Setting Withdraw function.
     */
    function updatewithdraw()
    {
        // Check for AJAX Request
        if( !$this->input->is_ajax_request() ){ redirect(base_url('setting/notification'), 'location'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        // POST Input Form
        $wd_min         = $this->input->post( 'wd_min' );
        $wd_min         = ddm_isset($wd_min, 0);
        $wd_fee         = $this->input->post( 'wd_fee' );
        $wd_fee         = ddm_isset($wd_fee, 0);
        $wd_tax         = $this->input->post( 'wd_tax' );
        $wd_tax         = ddm_isset($wd_tax, 0);
        $wd_tax_npwp    = $this->input->post( 'wd_tax_npwp' );
        $wd_tax_npwp    = ddm_isset($wd_tax_npwp, 0);

        $this->form_validation->set_rules('wd_min','Withdraw Minimal','required');
        $this->form_validation->set_rules('wd_fee','Biaya Transfer','required');
        // $this->form_validation->set_rules('wd_tax','Pajak Non NPWP','required');
        // $this->form_validation->set_rules('wd_tax_npwp','Pajak NPWP','required');
        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE){
            $data = array('status' => 'error', 'message' => 'Setting Withdraw tidak berhasil di ubah. '.validation_errors() );
            die(json_encode($data));
        }

        // Update Data Withdraw Minimal
        $data_wd_min        = array('value' => str_replace('.', '', $wd_min));
        $this->db->where('name', 'setting_withdraw_minimal');
        if ( ! $up_wd_min = $this->db->update(TBL_OPTIONS, $data_wd_min)) {
            $data = array('status' => 'error', 'message' => 'Withdraw Minimal tidak berhasil di ubah. ');
            die(json_encode($data));
        }

        // Update Data Biaya Transfer
        $data_wd_fee        = array('value' => str_replace('.', '', $wd_fee));
        $this->db->where('name', 'setting_withdraw_fee');
        if ( ! $up_wd_fee = $this->db->update(TBL_OPTIONS, $data_wd_fee)) {
            $data = array('status' => 'error', 'message' => 'Biaya Transfer tidak berhasil di ubah. ');
            die(json_encode($data));
        }

        // Update Data Pajak NON NPWP WD
        $wd_tax             = str_replace(',', '.', $wd_tax);
        $wd_tax             = str_replace('%', '', $wd_tax);
        $wd_tax             = trim($wd_tax);
        $data_wd_tax        = array('value' => $wd_tax);
        $this->db->where('name', 'setting_withdraw_tax');
        if ( ! $up_wd_tax = $this->db->update(TBL_OPTIONS, $data_wd_tax)) {
            $data = array('status' => 'error', 'message' => 'Pajak tidak berhasil di ubah. ');
            die(json_encode($data));
        }

        // Update Data Pajak NPWP WD
        $wd_tax_npwp        = str_replace(',', '.', $wd_tax_npwp);
        $wd_tax_npwp        = str_replace('%', '', $wd_tax_npwp);
        $wd_tax_npwp        = trim($wd_tax_npwp);
        $data_wd_tax_npwp   = array('value' => $wd_tax_npwp);
        $this->db->where('name', 'setting_withdraw_tax_npwp');
        if ( ! $up_wd_tax = $this->db->update(TBL_OPTIONS, $data_wd_tax_npwp)) {
            $data = array('status' => 'error', 'message' => 'Pajak tidak berhasil di ubah. ');
            die(json_encode($data));
        }

        // Update Success
        $data = array('status'=>'success', 'message'=>'Setting Withdraw berhasil d ubah.');
        die(json_encode($data));
    }

    /**
     * Save Promo Code function.
     */
    function savepromocode($id='')
    {
        // Check for AJAX Request
        if( !$this->input->is_ajax_request() ){ redirect(base_url('promocode/global'), 'location'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            // Set JSON data
            $data = array('status' => 'access_denied', 'url' => base_url('login')); 
            die(json_encode($data));
        }

        $current_member     = ddm_get_current_member();
        $datetime           = date('Y-m-d H:i:s');
        $created_by         = $current_member->username;
        if ( $staff = ddm_get_current_staff() ) {
            $created_by     = $staff->username;
        }

        $promo_id           = '';
        if ( $id ) {
            $id = ddm_decrypt($id);
            if ( ! $data_promo = $this->Model_Option->get_promo_codes($id) ) {
                $data = array('status' => 'error', 'message' => 'Data Kode Promo tidak berhasil disimpan. ID Kode Promo tidak ditemukan !');
                die(json_encode($data));
            }
            $promo_id       = $data_promo->id;
        }

        // POST Input Form
        $promo_code             = trim( $this->input->post('promo_code') );
        $promo_code             = ddm_isset($promo_code, '');
        $discount_agent_type    = trim( $this->input->post('discount_agent_type') );
        $discount_agent_type    = ddm_isset($discount_agent_type, '');
        $discount_agent         = trim( $this->input->post('discount_agent') );
        $discount_agent         = ddm_isset($discount_agent, '');
        $discount_customer_type = trim( $this->input->post('discount_customer_type') );
        $discount_customer_type = ddm_isset($discount_customer_type, '');
        $discount_customer      = trim( $this->input->post('discount_customer') );
        $discount_customer      = ddm_isset($discount_customer, '');
        $form_input             = trim( $this->input->post('form_input') );
        $form_input             = ddm_isset($form_input, 'global');
        $product_ids            = '';

        if ( ! $promo_code ) {
            $data = array('status' => 'error', 'message' => 'Kode Promo harus di isi !');
            die(json_encode($data));
        }

        $discount_agent         = str_replace('.', '', $discount_agent);
        $discount_customer      = str_replace('.', '', $discount_customer);

        if ( ! $discount_agent && ! $discount_customer ) {
            $data = array('status' => 'error', 'message' => 'Salah atu diskon (Diskon Agen atau Diskon Konsumen) harus di isi !');
            die(json_encode($data));
        }

        if ( $form_input == 'products' ) {
            if ( ! $products = $this->input->post('products') ) {
                $data = array('status' => 'error', 'message' => 'Produk belum di pilih !');
                die(json_encode($data));
            }
            foreach ($products as $key => $value) {
                $product_ids[] = $value;
            }
        }

        $user_type              = 'all';
        if ( $discount_agent && ! $discount_customer ) {
            $user_type          = 'agent';
        }
        if ( ! $discount_agent && $discount_customer ) {
            $user_type          = 'customer';
        }

        $data = array(
            'promo_code'            => strtoupper($promo_code),
            'discount_agent_type'   => $discount_agent_type,
            'discount_agent'        => $discount_agent,
            'discount_customer_type'=> $discount_customer_type,
            'discount_customer'     => $discount_customer,
            'usertype'              => $user_type,
            'datecreated'           => $datetime,
            'datemodified'          => $datetime,
        );

        if ( $form_input == 'products' && $product_ids ) {
            $data['products']       = json_encode($product_ids);
        }

        if ( $id ) {
            unset($data['datecreated']);
            $data['modified_by'] = $created_by;
            if ( ! $update_data = $this->Model_Option->update_data_promo_code($id, $data) ) {
                $data = array('status' => 'error', 'message' => 'Data Kode Promo tidak berhasil disimpan. Silahkan cek form Kode Promo !');
                die(json_encode($data));
            }
        } else {
            $data['status']     = 1;
            $data['created_by'] = $created_by;
            if ( ! $saved_data = $this->Model_Option->save_data_promo_code($data) ) {
                $data = array('status' => 'error', 'message' => 'Data Kode Promo tidak berhasil disimpan. Silahkan cek form Kode Promo !');
                die(json_encode($data));
            }
            $id = $saved_data;
        }

        $data = array('status'=>'success', 'message'=>'Data Kode Promo berhasil disimpan.');
        die(json_encode($data));
    }

    /**
     * Status Promo Code Function
     */
    function promocodestatus( $id = 0 ){
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('productmanage/categorylist'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        if( !$id ){
            $data = array('status' => 'error', 'message' => 'Kode Promo tidak ditemukan !');
            die(json_encode($data));
        }
        $id = ddm_decrypt($id);
        if ( ! $data_promo = $this->Model_Option->get_promo_codes($id) ) {
            $data = array('status' => 'error', 'message' => 'Data Kode Promo tidak ditemukan !');
            die(json_encode($data));
        }

        // set variables
        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);
        $datetime           = date('Y-m-d H:i:s');
        $status             = ( $data_promo->status == 1 ) ? 0 : 1;

        $modified_by        = $current_member->username;
        if ( $staff = ddm_get_current_staff() ) {
            $modified_by    = $staff->username;
        }

        $data = array(
            'status'        => $status,
            'modified_by'   => $modified_by,
            'datemodified'  => $datetime,
        );

        if ( ! $update_data = $this->Model_Option->update_data_promo_code($id, $data) ) {
            $data = array('status' => 'error', 'message' => 'Status Kode Promo tidak berhasil diedit !');
            die(json_encode($data));
        }

        // Save Success
        $data = array('status'=>'success', 'message'=>'Status Kode Promo berhasil diedit.');
        die(json_encode($data));
    }

    /**
     * Save Reward Function
     */
    function savereward($id = 0){
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('setting/reward'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        // set variables
        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);
        $datetime           = date('Y-m-d H:i:s');

        // POST Input Form
        $reward             = trim( $this->input->post('reward') );
        $reward             = ddm_isset($reward, '');
        $nominal            = $this->input->post('nominal');
        $nominal            = ddm_isset($nominal, 0);
        $point              = trim( $this->input->post('point') );
        $point              = ddm_isset($point, 0);
        $message            = $this->input->post('message');
        $message            = ddm_isset($message, '');
        $is_active          = trim( $this->input->post('is_active') );
        $is_active          = ddm_isset($is_active, 0);
        $is_lifetime        = trim( $this->input->post('is_lifetime') );
        $is_lifetime        = ddm_isset($is_lifetime, 0);
        $period_start       = $this->input->post('period_start');
        $period_start       = ddm_isset($period_start, '');
        $period_end         = $this->input->post('period_end');
        $period_end         = ddm_isset($period_end, '');

        $this->form_validation->set_rules('reward','Nama Reward','required');
        $this->form_validation->set_rules('nominal','Nominal Reward (Rp)','required');
        $this->form_validation->set_rules('point','Nilai Poin','required');
        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE){
            $data = array('status' => 'error', 'message' => 'Setting Reward tidak berhasil disimpan. '.validation_errors() );
            die(json_encode($data));
        }else{
            $data = array(
                'reward'            => ucwords(strtolower($reward)),
                'nominal'           => str_replace('.','',$nominal),
                'point'             => str_replace('.','',$point),
                'message'           => $message,
                'start_date'        => $is_lifetime ? null : $period_start,
                'end_date'          => $is_lifetime ? null : $period_end,
                'is_lifetime'       => $is_lifetime,
                'is_active'         => $is_active,
                'datecreated'       => $datetime,
                'datemodified'      => $datetime,
            );

            if ( $id ) {
                $id = ddm_decrypt($id);
                unset($data['datecreated']);
                if ( ! $datareward = $this->Model_Option->get_reward_by('id', $id) ) {
                    $data = array('status' => 'error', 'message' => 'Setting Reward tidak berhasil disimpan. Silahkan cek form reward !');
                    die(json_encode($data));
                }
                if ( ! $update_data = $this->Model_Option->update_data_reward_config($id, $data) ) {
                    $data = array('status' => 'error', 'message' => 'Setting Reward tidak berhasil disimpan. Silahkan cek form reward !');
                    die(json_encode($data));
                }
            } else {
                if ( ! $saved_data = $this->Model_Option->save_data_reward_config($data) ) {
                    $data = array('status' => 'error', 'message' => 'Setting Reward tidak berhasil disimpan. Silahkan cek form reward !');
                    die(json_encode($data));
                }
            }

            // Save Success
            $this->session->set_userdata( 'alert_msg', 'Reward berhasil disimpan.' );
            $data = array('status'=>'success', 'message'=>'Setting Reward berhasil disimpan.', 'url'=>base_url('setting/reward') );
            die(json_encode($data));
        }
    }

    /**
     * Save Intro Function
     */
    function saveintro(){
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('setting/intro'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        // set variables
        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);
        $datetime           = date('Y-m-d H:i:s');

        // Config Upload Image
        $img_msg                    = '';
        $img_ext                    = '';
        $get_data_img               = '';
        $img_upload                 = true;
        $img_name                   = ddm_generate_rand_string(6).'-'.time();

        $config['upload_path']      = SLIDE_IMG_PATH;
        $config['allowed_types']    = 'jpg|png|jpeg';
        $config['max_size']         = '2048';
        $config['overwrite']        = true;
        $config['file_name']        = $img_name;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if( ! $this->upload->do_upload("intro_img") ) {
            $img_upload             = false;
            $img_msg                = $this->upload->display_errors();
        }

        if ( ! $img_upload ) {
            $data = array('status' => 'error', 'message' => $img_msg);
            die(json_encode($data));
        }

        $get_data_img       = $this->upload->data();
        if ( $get_data_img ) {
            $data = array(
                'name'              => $get_data_img['file_name'],
                'file_type'         => $get_data_img['file_type'],
                'file_ext'          => $get_data_img['file_ext'],
                'file_size'         => $get_data_img['file_size'],
                'file_url'          => SLIDE_IMG . $get_data_img['file_name'],
                'is_image'          => $get_data_img['is_image'],
                'datecreated'       => $datetime,
                'datemodified'      => $datetime,
            );
        }

        if ( ! $saved_data = $this->Model_Option->save_data_intro($data) ) {
            $data = array('status' => 'error', 'message' => 'Simpan gambar intro tidak berhasil disimpan.', 'url' => base_url('setting/saveintro') );
            die(json_encode($data));
        }

        // Save Success
        $data = array('status'=>'success', 'message'=>'Simpan gambar intro berhasil disimpan.' );
        die(json_encode($data));
    }

    /**
     * Save Intro Function
     */
    function deleteintro($id = ''){
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('setting/intro'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $data = array('status' => 'error', 'message' => 'ID Intro tidak dikenali !');
        if( !$id ){
            die(json_encode($data));
        }

        $id = ddm_decrypt($id);
        if ( ! $dataintro = $this->Model_Option->get_intro_by('id', $id) ) {
            die(json_encode($data));
        }

        if ( ! $delete_data = $this->Model_Option->delete_data_intro($id) ) {
            $data = array('status' => 'error', 'message' => 'Hapus intro tidak berhasil.' );
            die(json_encode($data));
        }

        // Delete Image
        $file = ''; 
        if ( $dataintro->name ) {
            $file_path = SLIDE_IMG_PATH . $dataintro->name;
            if ( file_exists($file_path) ) {
                $file = $file_path;
            }
        }
        if ( $file ) { unlink($file); }

        // Save Success
        $data = array('status'=>'success', 'message'=>'Hapus intro berhasil.' );
        die(json_encode($data));
    }

    /**
     * Check Promo Code function.
     */
    function checkpromocode()
    {
        $code       = $this->input->post('code');
        $code       = trim(ddm_isset($code, ''));
        $promo_code = $this->input->post('promo_code');
        $promo_code = trim(ddm_isset($promo_code, ''));
        
        if ( !empty($promo_code) ) {
            $promodata = $this->Model_Option->get_promo_code_by('promo_code', $promo_code);
            if ( $promodata ) {
                if ( $code ) {
                    $code = ddm_encrypt($code, 'decrypt');
                    if ( $code != $promodata->id ) {
                        die( 'false' );
                    }
                } else {
                    die( 'false' );
                }
            }
        }
        die( 'true' );
    }
}

/* End of file Setting.php */
/* Location: ./application/controllers/Setting.php */
