<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Backend Controller.
 *
 * @class     Backend
 * @version   1.0.0
 */
class Backend extends Member_Controller {
    /**
	 * Constructor.
	 */
    function __construct()
    {
        parent::__construct();
    }

    // =============================================================================================
    // DASHBOARD
    // =============================================================================================

    /**
	 * Dashboard function.
	 */
    public function index()
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
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'bootbox/bootbox.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'chart.js/dist/Chart.min.js',
            BE_PLUGIN_PATH . 'chart.js/dist/Chart.extension.js',
            // Always placed at bottom
            BE_JS_PATH . 'pages/dashboard.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));

        $scripts_init           = ddm_scripts_init(array(
            'InputMask.init();',
            'ShopOrderManage.init();'
        ));
        $scripts_add            = '';

        $data_omzet             = $this->Model_Shop->get_all_omzet_shop_order_monthly(6, 0);

        $data['title']          = TITLE . 'Dashboard';
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['data_omzet']     = $data_omzet;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'dashboard';

        // log for dashboard
        if ( ! $this->session->userdata( 'log_dashboard' ) ) {
            $this->session->set_userdata( 'log_dashboard', true );
            ddm_log( 'DASHBOARD', ddm_get_current_ip(), maybe_serialize( array( 'current_member' => $current_member, 'cookie' => $_COOKIE ) ) );
        }

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    // =============================================================================================
    // MEMBER PAGE
    // =============================================================================================

    /**
     * Member New function.
     */
    public function membernew()
    {
        auth_redirect();
        $this->load->helper('shop_helper');
        
        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);

        $headstyles             = ddm_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'jquery-ui/jquery-ui-1.8.13.custom.css?ver=' . CSS_VER_MAIN,
            BE_PLUGIN_PATH . 'select2/dist/css/select2.min.css?ver=' . CSS_VER_MAIN,
        ));
        $loadscripts            = ddm_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-notify/bootstrap-notify.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'select2/dist/js/select2.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'bootbox/bootbox.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'pages/register.js?ver=' . JS_VER_PAGE,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = ddm_scripts_init(array(
            'App.select2();',
            'InputMask.init();',
            'SearchAction.init();',
            'SelectChange.init();',
            'RegisterMember.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_member_new');
        $data['title_page']     = '<i class="fa fa-user-plus mr-1"></i> '. lang('menu_member_new');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['packages']       = ddm_packages();
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'member/form/register';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Member List function.
     */
    public function memberlist()
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
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = ddm_scripts_init(array(
            'ButtonAction.init();',
            'TableAjaxMemberList.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_member_list');
        $data['title_page']     = '<i class="ni ni-align-left-2 mr-1"></i> '. lang('menu_member_list');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'member/memberlists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Member Generation function.
     */
    public function membergeneration($username = '')
    {
        auth_redirect();

        $memberdata             = '';
        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);

        if ( $username ) {
            $username           = trim(strtolower($username));
            $memberdata         = $this->Model_Member->get_member_by('login', $username);
        }

        $headstyles             = ddm_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN,
            BE_PLUGIN_PATH . 'jstree/dist/themes/default/style.css?ver=' . CSS_VER_MAIN
        ));
        $loadscripts            = ddm_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-notify/bootstrap-notify.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'bootbox/bootbox.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = ddm_scripts_init(array(
            'SearchAction.init();',
            'TableAjaxMemberList.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_member_generation');
        $data['title_page']     = '<i class="fa fa-sitemap mr-1"></i> '. lang('menu_member_generation');
        $data['member']         = $current_member;
        $data['member_other']   = $memberdata;
        $data['is_admin']       = $is_admin;
        $data['packages']       = ddm_packages();
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'member/generation';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }
    
    /**
     * Member Generation Tree function.
     */
    public function membergenerationtree()
    {
        auth_redirect();
        
        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);
        
        $headstyles             = ddm_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN,
            BE_PLUGIN_PATH . 'jstree/dist/themes/default/style.css?ver=' . CSS_VER_MAIN,
            BE_PLUGIN_PATH . 'bootstrap-treeview/dist/bootstrap-treeview.min.css?ver=' . CSS_VER_MAIN,
        ));
        $loadscripts            = ddm_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-notify/bootstrap-notify.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'bootbox/bootbox.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'bootstrap-treeview/dist/bootstrap-treeview.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = ddm_scripts_init(array(
            'Generation.init();',
        ));
        $scripts_add            = '';
        
        $levels   = $is_admin ? 0 : 2;

        $data['title']          = TITLE . lang('menu_member_generation_tree');
        $data['title_page']     = '<i class="fa fa-sitemap mr-1"></i> '. lang('menu_member_generation_tree');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['packages']       = ddm_packages();
        $data['levels']         = $levels;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'member/generationtree';
        
        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    // =============================================================================================
    // PRODUCT MANAGE PAGE
    // =============================================================================================

    /**
     * Product New function.
     */
    public function productnew()
    {
        $this->auth(true);

        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);

        $headstyles             = ddm_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'quill/dist/quill.core.css?ver=' . CSS_VER_MAIN,
        ));
        $loadscripts            = ddm_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-notify/bootstrap-notify.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'bootbox/bootbox.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'quill/dist/quill.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'form-validation.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = ddm_scripts_init(array(
            'InputMask.init();',
            'ProductManage.init();',
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_product_new');
        $data['title_page']     = '<i class="fa fa-plus mr-1 mr-1"></i> '. lang('menu_product');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['form_page']      = 'new';
        $data['form_title']     = '<i class="fa fa-plus mr-1 mr-1"></i> '. lang('menu_product_new');
        $data['main_content']   = 'product/productform';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Product Edit function.
     */
    public function productedit($id = 0)
    {
        $this->auth(true);
        if ( ! $id ) {
            redirect(base_url('productmanage/productlist'), 'refresh');
        }

        $id_product     = ddm_decrypt($id);
        if ( ! $data_product = ddm_products($id_product) ) {
            redirect(base_url('productmanage/productlist'), 'refresh');
        }

        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);

        $headstyles             = ddm_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'quill/dist/quill.core.css?ver=' . CSS_VER_MAIN,
        ));
        $loadscripts            = ddm_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-notify/bootstrap-notify.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'quill/dist/quill.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'bootbox/bootbox.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'form-validation.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = ddm_scripts_init(array(
            'InputMask.init();',
            'ProductManage.init();',
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_product_edit');
        $data['title_page']     = '<i class="fa fa-edit mr-1 mr-1"></i> '. lang('menu_product');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['data_product']   = $data_product;
        $data['form_page']      = 'edit';
        $data['form_title']     = '<i class="fa fa-edit mr-1 mr-1"></i> '. lang('menu_product_edit');
        $data['main_content']   = 'product/productform';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Product Package New function.
     */
    public function packagenew()
    {
        $this->auth(true);

        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);

        $headstyles             = ddm_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'quill/dist/quill.core.css?ver=' . CSS_VER_MAIN,
        ));
        $loadscripts            = ddm_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-notify/bootstrap-notify.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'bootbox/bootbox.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'quill/dist/quill.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'form-validation.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = ddm_scripts_init(array(
            'InputMask.init();',
            'ProductManage.initPackage();',
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_package_new');
        $data['title_page']     = '<i class="fa fa-plus mr-1 mr-1"></i> '. lang('package') .' - '. lang('menu_product');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['form_page']      = 'new';
        $data['form_title']     = '<i class="fa fa-plus mr-1 mr-1"></i> '. lang('menu_package_new');
        $data['main_content']   = 'product/packageform';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Product Package Edit function.
     */
    public function packageedit($id = 0)
    {
        $this->auth(true);
        if ( ! $id ) {
            redirect(base_url('productmanage/packagelist'), 'refresh');
        }

        $id_package             = ddm_decrypt($id);
        if ( ! $data_package = $this->Model_Product->get_product_package_by('id', $id_package) ) {
            redirect(base_url('productmanage/packagelist'), 'refresh');
        }

        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);

        $headstyles             = ddm_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'quill/dist/quill.core.css?ver=' . CSS_VER_MAIN,
        ));
        $loadscripts            = ddm_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-notify/bootstrap-notify.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'quill/dist/quill.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'bootbox/bootbox.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'form-validation.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = ddm_scripts_init(array(
            'InputMask.init();',
            'ProductManage.initPackage();',
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_package_edit');
        $data['title_page']     = '<i class="fa fa-edit mr-1 mr-1"></i> '. lang('package') .' - '. lang('menu_product');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['data_package']   = $data_package;
        $data['form_page']      = 'edit';
        $data['form_title']     = '<i class="fa fa-edit mr-1 mr-1"></i> '. lang('menu_package_edit');
        $data['main_content']   = 'product/packageform';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Product List function.
     */
    public function productlist()
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
            'ProductManage.initPackage();',
            'TableAjaxProductManageList.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_product_list');
        $data['title_page']     = '<i class="ni ni-align-left-2 mr-1"></i> '. lang('menu_product_list');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'product/productlists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Product Package List function.
     */
    public function packagelist()
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
            'ProductManage.initPackage();',
            'TableAjaxProductManageList.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_package_list');
        $data['title_page']     = '<i class="ni ni-align-left-2 mr-1"></i> '. lang('menu_product_list');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'product/packagelists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Product Category List function.
     */
    public function categorylist()
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
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
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
            'ProductManage.initCategory();',
            'TableAjaxProductManageList.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_product_category');
        $data['title_page']     = '<i class="ni ni-align-left-2 mr-1"></i> '. lang('menu_product_category');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'product/categorylists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Product Point function.
     */
    public function productpoint()
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
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
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
            'ProductManage.initProductPoint();',
            'TableAjaxProductManageList.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_product_point');
        $data['title_page']     = '<i class="ni ni-align-left-2 mr-1"></i> '. lang('menu_product_point');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'product/productpoint';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    // =============================================================================================
    // PROMO CODE PAGE
    // =============================================================================================

    /**
     * Promo Code Global function.
     */
    public function promocodeglobal()
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
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
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
            'PromoCodeManage.init();',
            'TableAjaxPromoCodeList.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_promo_global');
        $data['title_page']     = '<i class="ni ni-align-left-2 mr-1"></i> '. lang('menu_promo_code');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'promocode/global';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Promo Code Spesific function.
     */
    public function promocodespesific()
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
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
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
            'PromoCodeManage.init();',
            'TableAjaxPromoCodeList.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_promo_spesific');
        $data['title_page']     = '<i class="ni ni-align-left-2 mr-1"></i> '. lang('menu_promo_code');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'promocode/spesific';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    // =============================================================================================
    // COMMISSION PAGE
    // =============================================================================================

    /**
     * Bonus function.
     */
    public function bonus( $id = '' )
    {
        auth_redirect();

        $member_data            = '';
        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);

        if( $id ) {
            $id = ddm_encrypt($id, 'decrypt');
            if( $is_admin ) {
                if ( ! $member_data = ddm_get_memberdata_by_id($id) ) {
                    redirect(base_url('commission/bonus'), 'location');
                }
            }
        }

        if( $is_admin ) {
            $dataBonus          = $this->Model_Bonus->get_total_deposite_bonus();
            $bonus_total        = isset($dataBonus->total_bonus) ? $dataBonus->total_bonus : 0;
        } else {
            $member_data        = $current_member;
        }
        
        if ( $member_data ) {
            $bonus_total        = $this->Model_Bonus->get_total_bonus_member($member_data->id);
        }

        $headstyles             = ddm_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN
        ));
        $loadscripts            = ddm_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-notify/bootstrap-notify.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'bootbox/bootbox.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = ddm_scripts_init(array(
            'InputMask.init();',
            'TableAjaxCommissionList.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_financial_bonus');
        $data['title_page']     = '<i class="ni ni-align-left-2 mr-1"></i> '. lang('menu_financial');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['member_other']   = $member_data;
        $data['bonus_total']    = $bonus_total;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'commission/bonuslists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Bonus function.
     */
    public function deposite( $id = '' )
    {
        auth_redirect();

        $member_data            = '';
        $deposite_in            = $deposite_out = $deposite_saldo = 0;
        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);

        if( $id ) {
            $id = ddm_encrypt($id, 'decrypt');
            if( $is_admin ) {
                if ( ! $member_data = ddm_get_memberdata_by_id($id) ) {
                    redirect(base_url('commission/deposite'), 'location');
                }
            }
        }

        if( ! $is_admin ) {
            $member_data        = $current_member;
        }

        if ( $member_data ) {
            $deposite_in        = $this->Model_Bonus->get_ewallet_total($member_data->id, 'IN'); 
            $deposite_out       = $this->Model_Bonus->get_ewallet_total($member_data->id, 'OUT');
            $deposite_saldo     = $deposite_in - $deposite_out;
        }

        $headstyles             = ddm_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN
        ));
        $loadscripts            = ddm_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-notify/bootstrap-notify.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'bootbox/bootbox.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = ddm_scripts_init(array(
            'InputMask.init();',
            'TableAjaxDepositeList.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_financial_deposite');
        $data['title_page']     = '<i class="ni ni-align-left-2 mr-1"></i> '. lang('menu_financial');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['member_other']   = $member_data;
        $data['deposite_in']    = $deposite_in;
        $data['deposite_out']   = $deposite_out;
        $data['deposite_saldo'] = $deposite_saldo;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'commission/depositelists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Withdraw function.
     */
    public function withdraw()
    {
        auth_redirect();

        $id_member              = 0;
        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);
        if ( !$is_admin ) {
            $id_member          = $current_member->id;
        }

        $total_withdraw         = $total_transfer = $total_bonus = $total_deposite = 0;
        if ( $data_deposite = $this->Model_Bonus->get_total_deposite_bonus($id_member) ) {
            $total_bonus        = $data_deposite->total_bonus;
            $total_withdraw     = $data_deposite->total_wd;
            $total_transfer     = $data_deposite->total_wd_transfer;
            $total_deposite     = $data_deposite->total_deposite;
        }

        $headstyles             = ddm_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN
        ));
        $loadscripts            = ddm_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-notify/bootstrap-notify.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'bootbox/bootbox.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = ddm_scripts_init(array(
            'InputMask.init();',
            'ButtonAction.init();',
            'TableAjaxWithdrawList.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_financial_withdraw');
        $data['title_page']     = '<i class="ni ni-align-left-2 mr-1"></i> '. lang('menu_financial');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['total_bonus']    = $total_bonus;
        $data['total_withdraw'] = $total_withdraw;
        $data['total_transfer'] = $total_transfer;
        $data['total_deposite'] = $total_deposite;
        $data['currency']       = config_item('currency');
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'commission/withdrawlists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }
    
    /**
     * Personal Sales Activation function.
     */
    public function personalactivation()
    {
        auth_redirect();
        $this->load->helper('shop_helper');
        
        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);

        $headstyles             = ddm_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'select2/dist/css/select2.min.css?ver=' . CSS_VER_MAIN,
        ));
        $loadscripts            = ddm_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-notify/bootstrap-notify.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'select2/dist/js/select2.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'bootbox/bootbox.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'pages/activation.js?ver=' . JS_VER_PAGE,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = ddm_scripts_init(array(
            'App.select2();',
            'InputMask.init();',
            'ActivationPersonalSales.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . 'Aktivasi Personal Sales';
        $data['title_page']     = '<i class="fa fa-user-plus mr-1"></i> '. 'Aktivasi Personal Sales';
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['packages']       = ddm_packages();
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'member/form/activation';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }
    
    /**
     * Transter Product function.
     */
    public function transferproduct()
    {
        auth_redirect();
        $this->load->helper('shop_helper');
        
        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);

        $headstyles             = ddm_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'select2/dist/css/select2.min.css?ver=' . CSS_VER_MAIN,
        ));
        $loadscripts            = ddm_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-notify/bootstrap-notify.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'select2/dist/js/select2.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'bootbox/bootbox.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'pages/transfer.js?ver=' . JS_VER_PAGE,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = ddm_scripts_init(array(
            'App.select2();',
            'InputMask.init();',
            'TransferProduct.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . 'Transfer Product';
        $data['title_page']     = '<i class="fa fa-user-plus mr-1"></i> '. 'Transfer Product';
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['packages']       = ddm_packages();
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'member/form/transfer';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }
    
    // =============================================================================================
    // REPORT GROUP
    // =============================================================================================

    /**
     * Member Registrations function.
     */
    public function registration()
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
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = ddm_scripts_init(array(
            'InputMask.init();',
            'ButtonAction.init();',
            'TableAjaxMemberList.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . lang('menu_report_register');
        $data['title_page']     = '<i class="ni ni-align-left-2 mr-1"></i> '. lang('menu_report_register') .' '. lang('agent');
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'report/registrationlists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Sales function.
     */
    public function sales()
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
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = ddm_scripts_init(array(
            'InputMask.init();',
            'ShopOrderManage.init();',
            'TableAjaxShopOrderList.init();'
        ));
        $scripts_add            = '';
        $menu_title             = $is_admin ? lang('menu_report_sales') : lang('menu_report_buy');

        $data['title']          = TITLE . $menu_title;
        $data['title_page']     = '<i class="ni ni-chart-bar-32 mr-1"></i> '. $menu_title;
        $data['menu_title']     = $menu_title;
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'report/saleslists';
        $data['type_content']   = 'agent';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Sales Customer function.
     */
    public function salescustomer()
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
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = ddm_scripts_init(array(
            'InputMask.init();',
            'ShopOrderManage.init();',
            'TableAjaxShopOrderList.init();'
        ));
        $scripts_add            = '';
        $menu_title             = lang('menu_report_sales') .' '. ($is_admin ? lang('agent') : '');

        $data['title']          = TITLE . $menu_title;
        $data['title_page']     = '<i class="ni ni-chart-bar-32 mr-1"></i> '. $menu_title;
        $data['menu_title']     = $menu_title;
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'report/saleslists';
        $data['type_content']   = 'customer';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Omzet function.
     */
    public function omzet()
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
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = ddm_scripts_init(array(
            'InputMask.init();',
            'ButtonAction.init();',
            'TableAjaxOmzetList.init();'
        ));
        $scripts_add            = '';
        $menu_title             = lang('menu_report_omzet');

        $data['title']          = TITLE . $menu_title;
        $data['title_page']     = '<i class="ni ni-chart-bar-32 mr-1"></i> '. $menu_title;
        $data['menu_title']     = $menu_title;
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'report/omzetlists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }
    
    /**
     * Omzet function.
     */
    public function omzetpersonal()
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
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = ddm_scripts_init(array(
            'InputMask.init();',
            'ButtonAction.init();',
            'TableAjaxOmzetList.init();'
        ));
        $scripts_add            = '';
        $menu_title             = 'Personal Sales';

        $data['title']          = TITLE . $menu_title;
        $data['title_page']     = '<i class="ni ni-chart-bar-32 mr-1"></i> '. $menu_title;
        $data['menu_title']     = $menu_title;
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'report/omzetpersonallists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }
    
    /**
     * Product Active function.
     */
    public function productactive()
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
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = ddm_scripts_init(array(
            'InputMask.init();',
            'ButtonAction.init();',
            'TableAjaxProductActiveList.init();'
        ));
        $scripts_add            = '';
        $menu_title             = lang('menu_report_product_active');

        $data['title']          = TITLE . $menu_title;
        $data['title_page']     = '<i class="ni ni-chart-bar-32 mr-1"></i> '. $menu_title;
        $data['menu_title']     = $menu_title;
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'report/productactivelists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Product function.
     */
    public function product( $id = '' )
    {
        auth_redirect();
        
        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);
        $member_data            = '';

        if( $id ) {
            $id = ddm_encrypt($id, 'decrypt');
            if( $is_admin ) {
                if ( ! $member_data = ddm_get_memberdata_by_id($id) ) {
                    redirect(base_url('report/product'), 'location');
                }
            }
        }

        if( $is_admin ) {
            $product_active     = $this->Model_Omzet_History->get_product_active_total();
        } else {
            $member_data        = $current_member;
        }
        
        if ( $member_data ) {
            $product_active     = $this->Model_Omzet_History->get_product_active($member_data->id);
        }

        $headstyles             = ddm_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.css?ver=' . CSS_VER_MAIN
        ));
        $loadscripts            = ddm_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-notify/bootstrap-notify.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'bootbox/bootbox.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = ddm_scripts_init(array(
            'InputMask.init();',
            'ButtonAction.init();',
            'TableAjaxProductList.init();'
        ));
        $scripts_add            = '';
        $menu_title             = lang('menu_report_product');

        $data['title']          = TITLE . $menu_title;
        $data['title_page']     = '<i class="ni ni-chart-bar-32 mr-1"></i> '. $menu_title;
        $data['menu_title']     = $menu_title;
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['member_other']   = $member_data;
        $data['product_active'] = $product_active;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'report/productlist';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Reward function.
     */
    public function reward()
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
            BE_PLUGIN_PATH . 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/jquery.dataTables.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/dataTables.bootstrap.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'datatables/datatable.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'table-ajax.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = ddm_scripts_init(array(
            'InputMask.init();',
            'ButtonAction.init();',
            'TableAjaxRewardList.init();'
        ));
        $scripts_add            = '';
        $menu_title             = lang('menu_report_reward');

        $data['title']          = TITLE . $menu_title;
        $data['title_page']     = '<i class="fa fa-gift mr-1"></i> '. $menu_title;
        $data['menu_title']     = $menu_title;
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'report/rewardlists';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    // ---------------------------------------------------------------------------------------------

    // =============================================================================================
    // PROFILE, ERROR, COMINGSOON PAGE
    // =============================================================================================

    /**
     * Profile Page function.
     */
    public function profile($id=0)
    {
        auth_redirect();

        $member_data            = '';
        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);

        if ( $id ){
            if ( $is_admin ) {
                $id             = ddm_decrypt($id);
                if ( ! $member_data = ddm_get_memberdata_by_id($id) ) {
                    redirect( base_url('profile'), 'location' );
                }
            } else {
                redirect( base_url('profile'), 'location' );
            }
        }

        $headstyles             = ddm_headstyles(array(
            // Default CSS Plugin
            BE_PLUGIN_PATH . 'jquery-ui/jquery-ui-1.8.13.custom.css?ver=' . CSS_VER_MAIN,
            BE_PLUGIN_PATH . 'select2/dist/css/select2.min.css?ver=' . CSS_VER_MAIN,
        ));
        $loadscripts            = ddm_scripts(array(
            // Default JS Plugin
            BE_PLUGIN_PATH . 'bootstrap-notify/bootstrap-notify.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-inputmask/jquery.inputmask.bundle.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'jquery-validation/dist/jquery.validate.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'select2/dist/js/select2.min.js?ver=' . JS_VER_MAIN,
            BE_PLUGIN_PATH . 'bootbox/bootbox.min.js?ver=' . JS_VER_MAIN,
            // Always placed at bottom
            BE_JS_PATH . 'form-validation.js?ver=' . JS_VER_BACK,
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK
        ));
        $scripts_init           = ddm_scripts_init(array(
            'App.select2();',
            'InputMask.init();',
            'SelectChange.init();',
            'Profile.init();',
            'FV_Profile.init();'
        ));
        $scripts_add            = '';

        $data['title']          = TITLE . 'Profil Member';
        $data['member']         = $current_member;
        $data['member_other']   = $member_data;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'member/profile';

        $this->load->view(VIEW_BACK . 'template_index', $data);
    }

    /**
     * Error 404 Page function.
     */
	public function error_404()
	{
        auth_redirect();

        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);
        
        $headstyles             = ddm_headstyles(array(
            // Default CSS Plugin
        ));
        
        $loadscripts            = ddm_scripts(array(
            // Default JS Plugin
            BE_JS_PATH . 'custom.js?ver=' . JS_VER_BACK,
        ));

        $scripts_init           = '';
        $scripts_add            = '';

        $data['title']          = TITLE . '404 Page Not Found';
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['headstyles']     = $headstyles;
        $data['scripts']        = $loadscripts;
        $data['scripts_init']   = $scripts_init;
        $data['scripts_add']    = $scripts_add;
        $data['main_content']   = 'error_404';

        $this->load->view(VIEW_BACK . 'template_index', $data);
	}

    // ---------------------------------------------------------------------------------------------

    /**
	 * Coming Soon View function.
	 */
	function comingsoon()
	{
        auth_redirect();

        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);

        $data['title']          = TITLE . 'Coming Soon';
        $data['member']         = $current_member;
        $data['is_admin']       = $is_admin;
        $data['main_content']   = 'pages/comingsoon';

        $this->load->view(VIEW_BACK . 'template', $data);

    }

    // ---------------------------------------------------------------------------------------------

    // =============================================================================================
    // ASSUME AND REVERT ACCOUNT
    // =============================================================================================

    /**
	 * Assume to member account
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param int $member_id. Member ID.
	 * @author Iqbal
	 */
	function assume( $member_id ) {
		$this->auth( true );
        $current_member = ddm_get_current_member();
        $uid            = $current_member->username;
        $type           = 'admin';
        if ( $staff = ddm_get_current_staff() ) {
            $uid        = $staff->username;
            $type       = 'staff';
        }
        $id         = ddm_encrypt($member_id, 'decrypt');
        $log_desc   = array('cookie' => $_COOKIE, 'type' => $type);
        ddm_log_action( 'ASSUME', $id, $uid, json_encode($log_desc) );
		ddm_assume( $id );
	}

	/**
	 * Revert account
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @author ahmad
	 */
	function revert() {
		ddm_revert();
	}

     /**
     * Switch Language function.
     */
    function switchlang( $lang='' )
    {
        if ( $this->input->is_ajax_request() ) {
            die('true');
        } else {
            $url  = $this->uri->uri_string();
            if ( $url == 'switchlang' ) {
                redirect(base_url('dashboard'), 'refresh');
            } else {
                redirect($url);
            }
        }
    }

    // Ubah foto profile
    function ubah_foto_profile(){
        auth_redirect();
        $current_member = ddm_get_current_member();
        $file = upload_file('file', 'images', ASSET_FOLDER.'/upload/profile_picture/');
        if( $file!='error_upload' && $file!='error_extension' && $file!='error' && $file!='empty' ){
            $data_member = array(
                'id'=>$current_member->id,
                'photo'=>$file,
                'datemodified'=>date('Y-m-d H:i:s')
            );
            $this->Model_Member->update_member($data_member);
            //set flashdata
            $this->session->set_flashdata('message', '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Upload Foto Profile Berhasil!</div>');
            redirect(base_url('profile'));
        } else {
            //set flashdata
            $this->session->set_flashdata('message', '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Upload Foto Profile Gagal!</div>');
            redirect(base_url('profile'));
        }
    }
    // ---------------------------------------------------------------------------------------------
}

/* End of file Backend.php */
/* Location: ./application/controllers/Backend.php */
