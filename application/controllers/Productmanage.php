<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Productmanage Controller.
 *
 * @class     Productmanage
 * @version   1.0.0
 */
class Productmanage extends Admin_Controller {
    /**
	 * Constructor.
	 */
    function __construct()
    {
        parent::__construct();
    }

    // =============================================================================================
    // LIST DATA MEMBER
    // =============================================================================================

    /**
     * Product List Data function.
     */
    function productlistsdata(){
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

        $s_category         = $this->input->$search_method('search_category');
        $s_category         = ddm_isset($s_category, '');
        $s_name             = $this->input->$search_method('search_name');
        $s_name             = ddm_isset($s_name, '');
        $s_price_agent1_min = $this->input->$search_method('search_price_agent1_min');
        $s_price_agent1_min = ddm_isset($s_price_agent1_min, '');
        $s_price_agent1_max = $this->input->$search_method('search_price_agent1_max');
        $s_price_agent1_max = ddm_isset($s_price_agent1_max, '');
        $s_price_agent2_min = $this->input->$search_method('search_price_agent2_min');
        $s_price_agent2_min = ddm_isset($s_price_agent2_min, '');
        $s_price_agent2_max = $this->input->$search_method('search_price_agent2_max');
        $s_price_agent2_max = ddm_isset($s_price_agent2_max, '');
        $s_price_agent3_min = $this->input->$search_method('search_price_agent3_min');
        $s_price_agent3_min = ddm_isset($s_price_agent3_min, '');
        $s_price_agent3_max = $this->input->$search_method('search_price_agent3_max');
        $s_price_agent3_max = ddm_isset($s_price_agent3_max, '');
        
        $s_price_custo_min  = $this->input->$search_method('search_price_customer_min');
        $s_price_custo_min  = ddm_isset($s_price_custo_min, '');
        $s_price_custo_max  = $this->input->$search_method('search_price_customer_max');
        $s_price_custo_max  = ddm_isset($s_price_custo_max, '');
        $s_status           = $this->input->$search_method('search_status');
        $s_status           = ddm_isset($s_status, '');

        if ( !empty($s_category) )          { $condition .= str_replace('%s%', $s_category, ' AND %category% LIKE "%%s%%"'); }
        if ( !empty($s_name) )              { $condition .= str_replace('%s%', $s_name, ' AND %name% LIKE "%%s%%"'); }
        if ( !empty($s_price_agent1_min) )  { $condition .= ' AND price_agent1 >= "'.$s_price_agent1_min.'"'; }
        if ( !empty($s_price_agent1_max) )  { $condition .= ' AND price_agent1 <= "'.$s_price_agent1_max.'"'; }
        if ( !empty($s_price_agent2_min) )  { $condition .= ' AND price_agent2 >= "'.$s_price_agent2_min.'"'; }
        if ( !empty($s_price_agent2_max) )  { $condition .= ' AND price_agent2 <= "'.$s_price_agent2_max.'"'; }
        if ( !empty($s_price_agent3_min) )  { $condition .= ' AND price_agent3 >= "'.$s_price_agent3_min.'"'; }
        if ( !empty($s_price_agent3_max) )  { $condition .= ' AND price_agent3 <= "'.$s_price_agent3_max.'"'; }
        
        if ( !empty($s_price_custo_min) )   { $condition .= ' AND price_customer >= "'.$s_price_custo_min.'"'; }
        if ( !empty($s_price_custo_max) )   { $condition .= ' AND price_customer <= "'.$s_price_custo_max.'"'; }
        if ( !empty($s_status) )        { 
            if ( $s_status == 'active' ) {
                $condition .= str_replace('%s%', 1, ' AND %status% = %s%'); 
            } else {
                $condition .= str_replace('%s%', 1, ' AND %status% <> %s%'); 
            }
        }

        if( $column == 1 )      { $order_by .= 'image ' . $sort; }
        elseif( $column == 2 )  { $order_by .= '%category% ' . $sort; }
        elseif( $column == 3 )  { $order_by .= '%name% ' . $sort; }
        elseif( $column == 4 )  { $order_by .= 'price_agent1% ' . $sort; }
        elseif( $column == 5 )  { $order_by .= 'price_agent2% ' . $sort; }
        elseif( $column == 6 )  { $order_by .= 'price_agent3% ' . $sort; }
        elseif( $column == 7 )  { $order_by .= 'price_customer% ' . $sort; }
        elseif( $column == 8 )  { $order_by .= '%status% ' . $sort; }

        $data_list          = ( $is_admin ) ? $this->Model_Product->get_all_product($limit, $offset, $condition, $order_by) : array();
        $records            = array();
        $records["aaData"]  = array();

        if( !empty($data_list) ){
            $iTotalRecords  = ddm_get_last_found_rows();
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
                $category       = ddm_strong(ucwords($row->category));
                if ( $row->image ) {
                    $img_src    = PRODUCT_IMG_PATH . 'thumbnail/'. $row->image;
                    if ( file_exists($img_src) ) {
                        $img_src = PRODUCT_IMG . 'thumbnail/'. $row->image;
                    } else {
                        $img_src = ASSET_PATH . 'backend/img/no_image.jpg'; 
                    }
                } else {
                    $img_src = ASSET_PATH . 'backend/img/no_image.jpg'; 
                }

                $product    = '
                    <div class="media align-items-center">
                        <a href="#" class="avatar mr-3">
                            <img alt="Image placeholder" src="'. $img_src .'">
                        </a>
                        <div class="media-body">
                            <span class="name mb-0 font-weight-bold text-primary">'. $row->name .'</span>
                        </div>
                    </div>';
                
                if ( $row->status == 1 ) {
                    $status     = '<a href="'.base_url('productmanage/productstatus/'.$id).'" class="btn btn-sm btn-outline-success btn-status-product" data-product="'.$row->name.'" data-status="'.$row->status.'"><i class="fa fa-check"></i> Active</a>';
                } else {
                    $status     = '<a href="'.base_url('productmanage/productstatus/'.$id).'" class="btn btn-sm btn-outline-danger btn-status-product" data-product="'.$row->name.'" data-status="'.$row->status.'"><i class="fa fa-times"></i> Non-Active</a>';
                }

                $btn_edit       = '<a href="'.base_url('productmanage/productedit/'.$id).'" class="btn btn-sm btn-primary btn-tooltip" title="Edit Produk"><i class="fa fa-edit"></i></a>';

                $btn_delete     = '<a href="javascript:;" 
                                    data-url="'.base_url('productmanage/productdelete/'.$id).'"
                                    data-product="'.ucwords($row->name).'"
                                    class="btn btn-sm btn-warning btn-tooltip btn-delete-product" 
                                    title="Delete Produk"><i class="fa fa-trash"></i></a>';

                $records["aaData"][] = array(
                    ddm_center($i),
                    $product,
                    '<div style="min-width:100px">'. ddm_center($category) .'</div>',
                    '<div style="min-width:100px">'. ddm_accounting($row->price_agent1, '', true) .'</div>',
                    '<div style="min-width:100px">'. ddm_accounting($row->price_agent2, '', true) .'</div>',
                    '<div style="min-width:100px">'. ddm_accounting($row->price_agent3, '', true) .'</div>',
                    ddm_center($status),
                    ddm_center( ( ($is_admin && $access) ? $btn_edit.$btn_delete : '' ) )
                );
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
     * Product Package List Data function.
     */
    function packagelistsdata(){
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

        $s_package          = $this->input->$search_method('search_package');
        $s_package          = ddm_isset($s_package, '');
        $s_qty_min          = $this->input->$search_method('search_qty_min');
        $s_qty_min          = ddm_isset($s_qty_min, '');
        $s_qty_max          = $this->input->$search_method('search_qty_max');
        $s_qty_max          = ddm_isset($s_qty_max, '');
        $s_point_min        = $this->input->$search_method('search_point_min');
        $s_point_min        = ddm_isset($s_point_min, '');
        $s_point_max        = $this->input->$search_method('search_point_max');
        $s_point_max        = ddm_isset($s_point_max, '');
        $s_price1_min       = $this->input->$search_method('search_price1_min');
        $s_price1_min       = ddm_isset($s_price1_min, '');
        $s_price1_max       = $this->input->$search_method('search_price1_max');
        $s_price1_max       = ddm_isset($s_price1_max, '');
        $s_price2_min       = $this->input->$search_method('search_price2_min');
        $s_price2_min       = ddm_isset($s_price2_min, '');
        $s_price2_max       = $this->input->$search_method('search_price2_max');
        $s_price2_max       = ddm_isset($s_price2_max, '');
        $s_price3_min       = $this->input->$search_method('search_price3_min');
        $s_price3_min       = ddm_isset($s_price3_min, '');
        $s_price3_max       = $this->input->$search_method('search_price3_max');
        $s_price3_max       = ddm_isset($s_price3_max, '');
        $s_weight_min       = $this->input->$search_method('search_weight_min');
        $s_weight_min       = ddm_isset($s_weight_min, '');
        $s_weight_max       = $this->input->$search_method('search_weight_max');
        $s_weight_max       = ddm_isset($s_weight_max, '');
        $s_mix              = $this->input->$search_method('search_mix');
        $s_mix              = ddm_isset($s_mix, '');
        $s_status           = $this->input->$search_method('search_status');
        $s_status           = ddm_isset($s_status, '');

        if ( !empty($s_package) )       { $condition .= str_replace('%s%', $s_package, ' AND %package% LIKE "%%s%%"'); }
        if ( !empty($s_qty_min) )       { $condition .= ' AND %qty% >= "'.$s_qty_min.'"'; }
        if ( !empty($s_qty_max) )       { $condition .= ' AND %qty% <= "'.$s_qty_max.'"'; }
        if ( !empty($s_point_min) )     { $condition .= ' AND %point% >= "'.$s_point_min.'"'; }
        if ( !empty($s_point_max) )     { $condition .= ' AND %point% <= "'.$s_point_max.'"'; }
        if ( !empty($s_price1_min) )    { $condition .= ' AND %price1% >= "'.$s_price1_min.'"'; }
        if ( !empty($s_price1_max) )    { $condition .= ' AND %price1% <= "'.$s_price1_max.'"'; }
        if ( !empty($s_price2_min) )    { $condition .= ' AND %price2% >= "'.$s_price2_min.'"'; }
        if ( !empty($s_price2_max) )    { $condition .= ' AND %price2% <= "'.$s_price2_max.'"'; }
        if ( !empty($s_price3_min) )    { $condition .= ' AND %price3% >= "'.$s_price3_min.'"'; }
        if ( !empty($s_price3_max) )    { $condition .= ' AND %price3% <= "'.$s_price3_max.'"'; }
        if ( !empty($s_weight_min) )    { $condition .= ' AND %weight% >= "'.$s_weight_min.'"'; }
        if ( !empty($s_weight_max) )    { $condition .= ' AND %weight% <= "'.$s_weight_max.'"'; }
        if ( !empty($s_mix) )           { 
            if ( $s_mix == 'mix' ) {
                $condition .= str_replace('%s%', 1, ' AND %mix% >= %s%'); 
            } else {
                $condition .= str_replace('%s%', 0, ' AND %mix% = %s%'); 
            }
        }
        if ( !empty($s_status) )        { 
            if ( $s_status == 'active' ) {
                $condition .= str_replace('%s%', 1, ' AND %status% = %s%'); 
            } else {
                $condition .= str_replace('%s%', 1, ' AND %status% <> %s%'); 
            }
        }

        if( $column == 1 )      { $order_by .= '%package% ' . $sort; }
        elseif( $column == 2 )  { $order_by .= '%qty% ' . $sort; }
        elseif( $column == 3 )  { $order_by .= '%price1% ' . $sort; }
        elseif( $column == 4 )  { $order_by .= '%price2% ' . $sort; }
        elseif( $column == 5 )  { $order_by .= '%price3% ' . $sort; }
        elseif( $column == 6 )  { $order_by .= '%weight% ' . $sort; }
        elseif( $column == 7 )  { $order_by .= '%status% ' . $sort; }

        $data_list          = ( $is_admin ) ? $this->Model_Product->get_all_product_package($limit, $offset, $condition, $order_by) : array();
        $records            = array();
        $records["aaData"]  = array();

        if( !empty($data_list) ){
            $iTotalRecords  = ddm_get_last_found_rows();
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
                if ( $row->image ) {
                    $img_src    = PRODUCT_IMG_PATH . 'thumbnail/'. $row->image;
                    if ( file_exists($img_src) ) {
                        $img_src = PRODUCT_IMG . 'thumbnail/'. $row->image;
                    } else {
                        $img_src = ASSET_PATH . 'backend/img/no_image.jpg'; 
                    }
                } else {
                    $img_src = ASSET_PATH . 'backend/img/no_image.jpg'; 
                }

                $product    = '
                    <div class="media align-items-center">
                        <a href="#" class="avatar mr-3">
                            <img alt="Image placeholder" src="'. $img_src .'">
                        </a>
                        <div class="media-body">
                            <span class="name mb-0 font-weight-bold text-primary">'. $row->name .'</span>
                        </div>
                    </div>';
                
                if ( $row->status == 1 ) {
                    $status     = '<a href="'.base_url('productmanage/packagestatus/'.$id).'" class="btn btn-sm btn-outline-success btn-status-package" data-package="'.$row->name.'" data-status="'.$row->status.'"><i class="fa fa-check"></i> Active</a>';
                } else {
                    $status     = '<a href="'.base_url('productmanage/packagestatus/'.$id).'" class="btn btn-sm btn-outline-danger btn-status-package" data-package="'.$row->name.'" data-status="'.$row->status.'"><i class="fa fa-times"></i> Non-Active</a>';
                }

                $btn_edit       = '<a href="'.base_url('productmanage/packageedit/'.$id).'" class="btn btn-sm btn-primary btn-tooltip" title="Edit Paket Produk"><i class="fa fa-edit"></i></a>';

                $btn_delete     = '<a href="javascript:;" 
                                    data-url="'.base_url('productmanage/packagedelete/'.$id).'"
                                    data-package="'.ucwords($row->name).'"
                                    class="btn btn-sm btn-warning btn-tooltip btn-delete-package" 
                                    title="Delete Produk"><i class="fa fa-trash"></i></a>';

                $records["aaData"][] = array(
                    ddm_center($i),
                    $product,
                    ddm_center(ddm_accounting($row->qty)),
                    '<div style="min-width:100px">'. ddm_accounting($row->price1, '', true) .'</div>',
                    '<div style="min-width:100px">'. ddm_accounting($row->price2, '', true) .'</div>',
                    '<div style="min-width:100px">'. ddm_accounting($row->price3, '', true) .'</div>',
                    ddm_right(ddm_accounting($row->weight) .' ltr'),
                    ddm_center($status),
                    ddm_center( ( ($is_admin && $access) ? $btn_edit.$btn_delete : '' ) )
                );
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
     * Product Category List Data function.
     */
    function categorylistsdata(){
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

        $s_category         = $this->input->$search_method('search_category');
        $s_category         = ddm_isset($s_category, '');
        $s_status           = $this->input->$search_method('search_status');
        $s_status           = ddm_isset($s_status, '');

        if ( !empty($s_category) )          { $condition .= str_replace('%s%', $s_category, ' AND %name% LIKE "%%s%%"'); }
        if ( !empty($s_status) )        { 
            if ( $s_status == 'active' ) {
                $condition .= str_replace('%s%', 1, ' AND %status% = %s%'); 
            } else {
                $condition .= str_replace('%s%', 1, ' AND %status% <> %s%'); 
            }
        }

        if( $column == 1 )      { $order_by .= 'name ' . $sort; }
        elseif( $column == 2 )  { $order_by .= '%status% ' . $sort; }

        $data_list          = ( $is_admin ) ? $this->Model_Product->get_all_category($limit, $offset, $condition, $order_by) : array();
        $records            = array();
        $records["aaData"]  = array();

        if( !empty($data_list) ){
            $iTotalRecords  = ddm_get_last_found_rows();
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
                $category       = ddm_strong(ucwords($row->name));
                
                if ( $row->status == 1 ) {
                    $status     = '<a href="'.base_url('productmanage/categorystatus/'.$id).'" class="btn btn-sm btn-outline-success btn-status-category" data-category="'.$row->name.'" data-status="'.$row->status.'"><i class="fa fa-check"></i> Active</a>';
                } else {
                    $status     = '<a href="'.base_url('productmanage/categorystatus/'.$id).'" class="btn btn-sm btn-outline-danger btn-status-category" data-category="'.$row->name.'" data-status="'.$row->status.'"><i class="fa fa-times"></i> Non-Active</a>';
                }

                $btn_edit       = '<a href="'.base_url('productmanage/savecategory/'.$id).'" class="btn btn-sm btn-primary btn-tooltip btn-edit-category" title="Edit Kategori" data-category="'.$row->name.'"><i class="fa fa-edit"></i></a>';
                $btn_delete     = '<a href="javascript:;" 
                                    data-url="'.base_url('productmanage/categorydelete/'.$id).'"
                                    data-category="'.ucwords($row->name).'"
                                    class="btn btn-sm btn-warning btn-tooltip btn-delete-category" 
                                    title="Delete Kategori"><i class="fa fa-trash"></i></a>';
                $btn_status     = '';

                $records["aaData"][] = array(
                    ddm_center($i),
                    $category,
                    ddm_center($status),
                    ddm_center( ( ($is_admin && $access) ? $btn_edit.$btn_delete : '' ) )
                );
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
     * Product Point List Data function.
     */
    function productpointlistsdata(){
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

        $s_product          = $this->input->$search_method('search_product');
        $s_product          = ddm_isset($s_product, '');
        $s_total_min        = $this->input->$search_method('search_total_min');
        $s_total_min        = ddm_isset($s_total_min, '');
        $s_total_max        = $this->input->$search_method('search_total_max');
        $s_total_max        = ddm_isset($s_total_max, '');
        $s_point_min        = $this->input->$search_method('search_point_min');
        $s_point_min        = ddm_isset($s_point_min, '');
        $s_point_max        = $this->input->$search_method('search_point_max');
        $s_point_max        = ddm_isset($s_point_max, '');

        if ( !empty($s_product) )   { $condition .= str_replace('%s%', $s_product, ' AND %name% LIKE "%%s%%"'); }
        if ( !empty($s_total_min) ) { $condition .= ' AND total >= "'.$s_total_min.'"'; }
        if ( !empty($s_total_max) ) { $condition .= ' AND total <= "'.$s_total_max.'"'; }
        if ( !empty($s_point_min) ) { $condition .= ' AND point >= "'.$s_point_min.'"'; }
        if ( !empty($s_point_max) ) { $condition .= ' AND point <= "'.$s_point_max.'"'; }

        if( $column == 1 )      { $order_by .= 'name ' . $sort; }
        elseif( $column == 2 )  { $order_by .= '%total% ' . $sort; }
        elseif( $column == 3 )  { $order_by .= '%point% ' . $sort; }

        $data_list          = ( $is_admin ) ? $this->Model_Product->get_all_product_point($limit, $offset, $condition, $order_by) : array();
        $records            = array();
        $records["aaData"]  = array();

        if( !empty($data_list) ){
            $iTotalRecords  = ddm_get_last_found_rows();
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
                $id             = ddm_encrypt($row->source_id);
                $source_name    = ddm_strong(ucwords($row->source_name));
                $source_total   = ( $row->source == 'product' ? $row->product_total : $row->package_total );
                $source_point   = ( $row->source == 'product' ? $row->product_point : $row->package_point );
                $source_type    = lang(strtolower($row->source));
                $source         = '<span class="badge badge-'. ( $row->source == 'product' ? 'danger' : 'default' ) .'">'. $source_type .'</span>';
                $btn_edit       = '<a href="'.base_url('productmanage/saveproductpoint/'.$row->source.'/'.$id).'" 
                                        class="btn btn-sm btn-primary btn-edit-product-point" 
                                        data-source="'.$source_type.'"
                                        data-name="'.$row->source_name.'"
                                        data-total="'.$source_total.'"
                                        data-point="'.$source_point.'">
                                        <i class="fa fa-edit"></i> Edit Point
                                    </a>';

                $records["aaData"][] = array(
                    ddm_center($i),
                    ddm_center($source),
                    $source_name,
                    ddm_center(ddm_accounting($source_total)),
                    ddm_center(ddm_accounting($source_point)),
                    ddm_center( ( ($is_admin && $access) ? $btn_edit : '' ) )
                );
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
    // ACTION FUNCTIO
    // =============================================================================================

    /**
     * Save Product Function
     */
    function saveproduct( $id = 0 ){
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('productmanage/productnew'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $product_id             = '';
        $product_name           = '';
        $data_product           = '';
        if ( $id ) {
            $id = ddm_decrypt($id);
            if ( ! $data_product = ddm_products($id) ) {
                $data = array('status' => 'error', 'message' => 'Data Produk tidak berhasil disimpan. ID Produk tidak ditemukan !');
                die(json_encode($data));
            }
            $product_id         = $data_product->id;
            $product_name       = $data_product->name;
        }

        // set variables
        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);
        $datetime               = date('Y-m-d H:i:s');

        // POST Input Form
        $product                = trim( $this->input->post('product_name') );
        $product                = ddm_isset($product, '');
        $category               = $this->input->post('product_category');
        $category               = ddm_isset($category, 0);
        $price_agent1           = trim( $this->input->post('price_agent1') );
        $price_agent1           = ddm_isset($price_agent1, 0);
        $price_agent2           = trim( $this->input->post('price_agent2') );
        $price_agent2           = ddm_isset($price_agent2, 0);
        $price_agent3           = trim( $this->input->post('price_agent3') );
        $price_agent3           = ddm_isset($price_agent3, 0);
        $price_customer1        = trim( $this->input->post('price_customer1') );
        $price_customer1        = ddm_isset($price_customer1, 0);
        $price_customer2        = trim( $this->input->post('price_customer2') );
        $price_customer2        = ddm_isset($price_customer2, 0);
        $price_customer3        = trim( $this->input->post('price_customer3') );
        $price_customer3        = ddm_isset($price_customer3, 0);
        $product_point          = trim( $this->input->post('product_point') );
        $product_point          = ddm_isset($product_point, 0);
        $min_order              = trim( $this->input->post('min_order') );
        $min_order              = ddm_isset($min_order, 0);
        $stock                  = trim( $this->input->post('stock') );
        $stock                  = ddm_isset($stock, 0);
        $weight                 = trim( $this->input->post('weight') );
        $weight                 = ddm_isset($weight, 0);
        $description            = trim( $this->input->post('description') );
        $description            = ddm_isset($description, '');

        $qty_free_shipping       = trim( $this->input->post('qty_free_shipping') );
        $qty_free_shipping       = ddm_isset($qty_free_shipping, 0);

        // Discount Agent
        $discount_agent_min     = trim( $this->input->post('discount_agent_min') );
        $discount_agent_min     = ddm_isset($discount_agent_min, 0);
        $discount_agent_type    = $this->input->post('discount_agent_type');
        $discount_agent_type    = ddm_isset($discount_agent_type, '');
        $discount_agent         = trim( $this->input->post('discount_agent') );
        $discount_agent         = ddm_isset($discount_agent, 0);

        // Discount Customer
        $discount_customer_min  = trim( $this->input->post('discount_customer_min') );
        $discount_customer_min  = ddm_isset($discount_customer_min, 0);
        $discount_customer_type = $this->input->post('discount_customer_type');
        $discount_customer_type = ddm_isset($discount_customer_type, '');
        $discount_customer      = trim( $this->input->post('discount_customer') );
        $discount_customer      = ddm_isset($discount_customer, 0);

        $this->form_validation->set_rules('product_name','Nama Product','required');
        $this->form_validation->set_rules('product_category','Kategori Produk','required');
        $this->form_validation->set_rules('price_agent1','Harga Agen Wilayah 1','required');
        $this->form_validation->set_rules('price_agent2','Harga Agen Wilayah 3','required');
        $this->form_validation->set_rules('price_agent3','Harga Agen Wilayah 3','required');
        $this->form_validation->set_rules('price_customer1','Harga Konsumen Wilayah 1','required');
        $this->form_validation->set_rules('price_customer2','Harga Konsumen Wilayah 2','required');
        $this->form_validation->set_rules('price_customer3','Harga Konsumen Wilayah 3','required');
        $this->form_validation->set_rules('min_order','Mininmal Agen Order','required');
        $this->form_validation->set_rules('weight','Berat Produk','required');
        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE){
            $data = array('status' => 'error', 'message' => 'Data Produk tidak berhasil disimpan. '.validation_errors() );
            die(json_encode($data));
        }else{
            $slug                       = url_title($product, 'dash', TRUE);
            $check_slug                 = true;
            if ( $product_id == $id && strtolower($product_name) == strtolower($product) ) {
                $check_slug             = false;
            }

            if ( $check_slug ) {
                $condition              = ' AND %slug% = "'.$slug.'" OR %slug% LIKE "'.$slug.'-%" ';
                if ( $check_slug = $this->Model_Product->get_all_product(0, 0, $condition) ) {
                    $count_product      = count($check_slug);
                    $slug               = $slug .'-'. $count_product;
                }
            }

            // Config Upload Image
            $img_msg                    = '';
            $img_ext                    = '';
            $get_data_img               = '';
            $img_upload                 = true;
            $img_name                   = $slug.'-'.time();

            $config['upload_path']      = PRODUCT_IMG_PATH;
            $config['allowed_types']    = 'jpg|png|jpeg';
            $config['max_size']         = '2048';
            $config['overwrite']        = true;
            $config['file_name']        = $img_name;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if( ! $this->upload->do_upload("product_img")) {
                $img_upload             = false;
                $img_msg                = $this->upload->display_errors();
            }

            $created_by         = $current_member->username;
            if ( $staff = ddm_get_current_staff() ) {
                $created_by     = $staff->username;
            }

            $data = array(
                'name'              => $product,
                'slug'              => $slug,
                'id_category'       => $category,
                'price_agent1'      => str_replace('.', '', $price_agent1),
                'price_agent2'      => str_replace('.', '', $price_agent2),
                'price_agent3'      => str_replace('.', '', $price_agent3),
                'price_customer1'   => str_replace('.', '', $price_customer1),
                'price_customer2'   => str_replace('.', '', $price_customer2),
                'price_customer3'   => str_replace('.', '', $price_customer3),
                'min_order'         => str_replace('.', '', $min_order),
                'weight'            => str_replace('.', '', $weight),
                'stock'             => str_replace('.', '', $stock),
                'qty_free_shipping' => str_replace('.', '', $qty_free_shipping),
                'description'       => $description,
                'datecreated'       => $datetime,
                'datemodified'      => $datetime,
            );

            if ( $discount_agent ) {
                $data['discount_agent_min']     = $discount_agent_min;
                $data['discount_agent_type']    = $discount_agent_type;
                $data['discount_agent']         = str_replace('.', '', $discount_agent);
            }

            if ( $discount_customer ) {
                $data['discount_customer_min']  = $discount_customer_min;
                $data['discount_customer_type'] = $discount_customer_type;
                $data['discount_customer']      = str_replace('.', '', $discount_customer);
            }

            if ( $img_upload ) {
                $get_data_img       = $this->upload->data();
                $img_msg            = 'upload success';
                $data['image']      = $get_data_img['file_name'];

                create_thumbnail($data['image'] , PRODUCT_IMG_PATH); // Create thumbnail
            }

            if ( $id ) {
                unset($data['datecreated']);
                $data['modified_by'] = $created_by;
                if ( ! $update_data = $this->Model_Product->update_data_product($id, $data) ) {
                    $data = array('status' => 'error', 'message' => 'Data Produk tidak berhasil disimpan. Silahkan cek form produk !');
                    die(json_encode($data));
                }

                // Delete Image
                if ( $product_id && $data_product && $img_msg == "upload success" ) {
                    $file_path = $file_thumb_path = $file = $file_thumb = ''; 
                    if ( $data_product->image ) {
                        $file_path = PRODUCT_IMG_PATH . $data_product->image;
                        if ( file_exists($file_path) ) {
                            $file = $file_path;
                        }
                        $file_thumb_path = PRODUCT_IMG_PATH . 'thumbnail/' . $data_product->image;
                        if ( file_exists($file_thumb_path) ) {
                            $file_thumb = $file_thumb_path;
                        }
                    }
                    if ( $file ) { unlink($file); }
                    if ( $file_thumb ) { unlink($file_thumb); }
                }

            } else {
                $data['status']     = 1;
                $data['created_by'] = $created_by;
                if ( ! $saved_data = $this->Model_Product->save_data_product($data) ) {
                    $data = array('status' => 'error', 'message' => 'Data Produk tidak berhasil disimpan. Silahkan cek form produk !');
                    die(json_encode($data));
                }
                $id = $saved_data;
            }

            $id_encrypt = ddm_encrypt($id);
            // Save Success
            $data = array('status'=>'success', 'message'=>'Data Produk berhasil disimpan.', 'url'=>base_url('productmanage/productedit/'.$id_encrypt) );
            die(json_encode($data));
        }
    }

    /**
     * Save Product Package Function
     */
    function savepackage( $id = 0 ){
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('productmanage/packagenew'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $package_id             = '';
        $package_name           = '';
        $data_package           = '';
        if ( $id ) {
            $id = ddm_decrypt($id);
            if ( ! $data_package = ddm_product_package('id', $id) ) {
                $data = array('status' => 'error', 'message' => 'Data Paket Produk tidak berhasil disimpan. ID Paket Produk tidak ditemukan !');
                die(json_encode($data));
            }
            $package_id         = $data_package->id;
            $package_name       = $data_package->name;
        }

        // set variables
        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);
        $datetime               = date('Y-m-d H:i:s');
        $total_price1           = 0;
        $total_price2           = 0;
        $total_price3           = 0;
        $total_bv1              = 0;
        $total_bv2              = 0;
        $total_bv3              = 0;
        $product_ids            = '';
        $product_details        = '';

        // POST Input Form
        $package                = trim( $this->input->post('package_name') );
        $package                = ddm_isset($package, '');
        $total_qty              = $this->input->post('package_qty');
        $total_qty              = ddm_isset($total_qty, 0);
        $weight                 = trim( $this->input->post('package_weight') );
        $weight                 = ddm_isset($weight, 0);
        $point                  = trim( $this->input->post('package_point') );
        $point                  = ddm_isset($point, 0);
        $package_mix            = $this->input->post('package_mix');
        $package_mix            = ddm_isset($package_mix, 0);
        $lock_qty               = $this->input->post('lock_qty');
        $lock_qty               = ddm_isset($lock_qty, 0);
        $description            = trim( $this->input->post('description') );
        $description            = ddm_isset($description, '');

        $products               = $this->input->post('products');

        $this->form_validation->set_rules('package_name','Nama Paket Product','required');
        $this->form_validation->set_rules('package_qty','Qty Paket Produk','required');
        $this->form_validation->set_rules('package_weight','Berat Paket Produk','required');
        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE){
            $data = array('status' => 'error', 'message' => 'Data Paket Produk tidak berhasil disimpan. '.validation_errors() );
            die(json_encode($data));
        }else{

            if ( !$products ) {
                $data = array('status' => 'error', 'message' => 'Produk belum ada yang di pilih.');
                die(json_encode($data));
            }

            foreach ($products as $key => $row) {
                $qty            = str_replace('.', '', $row['qty']);
                $price1         = str_replace('.', '', $row['price1']);
                $price2         = str_replace('.', '', $row['price2']);
                $price3         = str_replace('.', '', $row['price3']);
                $bv1            = str_replace('.', '', $row['bv1']);
                $bv2            = str_replace('.', '', $row['bv2']);
                $bv3            = str_replace('.', '', $row['bv3']);
                $subtotal1      = str_replace('.', '', $row['subtotal1']);
                $subtotal2      = str_replace('.', '', $row['subtotal2']);
                $subtotal3      = str_replace('.', '', $row['subtotal3']);
                $product_ids[]  = $row['id'];

                if ( ! $package_mix ) {
                    $qty        = str_replace('.', '', $total_qty);
                    $subtotal1  = $price1 * $qty;
                    $subtotal2  = $price2 * $qty;
                    $subtotal3  = $price3 * $qty;
                }

                $product_details[$row['id']]  = array(
                    'id'        => $row['id'],
                    'name'      => $row['name'],
                    'qty'       => $qty,
                    'price1'    => $price1,
                    'price2'    => $price2,
                    'price3'    => $price3,
                    'bv1'       => $bv1,
                    'bv2'       => $bv2,
                    'bv3'       => $bv3,
                    'subtotal1' => $subtotal1,
                    'subtotal2' => $subtotal2,
                    'subtotal3' => $subtotal3,
                );
                $total_price1   += $subtotal1;
                $total_price2   += $subtotal2;
                $total_price3   += $subtotal3;
                $total_bv1      += $bv1 * $qty;
                $total_bv2      += $bv2 * $qty;
                $total_bv3      += $bv3 * $qty;

                if ( ! $package_mix ) { $lock_qty = 1; break; }
            }

            if ( !$product_ids || !$product_details ) {
                $data = array('status' => 'error', 'message' => 'Produk belum ada yang di pilih.');
                die(json_encode($data));
            }

            if ( $lock_qty && !$total_price1 && !$total_price2 && !$total_price3 ) {
                $data = array('status' => 'error', 'message' => 'Produk belum ada yang di pilih.');
                die(json_encode($data));
            }

            $slug                       = url_title($package, 'dash', TRUE);
            $check_slug                 = true;
            if ( $package_id == $id && strtolower($package_name) == strtolower($package) ) {
                $check_slug             = false;
            }

            if ( $check_slug ) {
                $condition              = ' AND %slug% = "'.$slug.'" OR %slug% LIKE "'.$slug.'-%" ';
                if ( $check_slug = $this->Model_Product->get_all_product_package(0, 0, $condition) ) {
                    $count_product      = count($check_slug);
                    $slug               = $slug .'-'. $count_product;
                }
            }

            // Config Upload Image
            $img_msg                    = '';
            $img_ext                    = '';
            $get_data_img               = '';
            $img_upload                 = true;
            $img_name                   = 'package-'.$slug.'-'.time();

            $config['upload_path']      = PRODUCT_IMG_PATH;
            $config['allowed_types']    = 'jpg|png|jpeg';
            $config['max_size']         = '2048';
            $config['overwrite']        = true;
            $config['file_name']        = $img_name;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if( ! $this->upload->do_upload("package_img")) {
                $img_upload             = false;
                $img_msg                = $this->upload->display_errors();
            }

            $created_by         = $current_member->username;
            if ( $staff = ddm_get_current_staff() ) {
                $created_by     = $staff->username;
            }

            $data = array(
                'name'              => $package,
                'slug'              => $slug,
                'qty'               => str_replace('.', '', $total_qty),
                'point'             => str_replace(',', '.', $point),
                'weight'            => str_replace('.', '', $weight),
                'price1'            => $total_price1,
                'price2'            => $total_price2,
                'price3'            => $total_price3,
                'bv1'               => $total_bv1,
                'bv2'               => $total_bv2,
                'bv3'               => $total_bv3,
                'is_mix'            => $package_mix,
                'lock_qty'          => $lock_qty,
                'product_ids'       => is_array($product_ids) ? json_encode($product_ids) : null,
                'product_details'   => maybe_serialize($product_details),
                'description'       => $description,
                'datecreated'       => $datetime,
                'datemodified'      => $datetime,
            );

            if ( $img_upload ) {
                $get_data_img       = $this->upload->data();
                $img_msg            = 'upload success';
                $data['image']      = $get_data_img['file_name'];

                create_thumbnail($data['image'] , PRODUCT_IMG_PATH); // Create thumbnail
            }

            if ( $id ) {
                unset($data['datecreated']);
                $data['modified_by'] = $created_by;
                if ( ! $update_data = $this->Model_Product->update_data_product_package($id, $data) ) {
                    $data = array('status' => 'error', 'message' => 'Data Paket Produk tidak berhasil disimpan. Silahkan cek form produk !');
                    die(json_encode($data));
                }

                // Delete Image
                if ( $package_id && $data_package && $img_msg == "upload success" ) {
                    $file_path = $file_thumb_path = $file = $file_thumb = ''; 
                    if ( $data_package->image ) {
                        $file_path = PRODUCT_IMG_PATH . $data_package->image;
                        if ( file_exists($file_path) ) {
                            $file = $file_path;
                        }
                        $file_thumb_path = PRODUCT_IMG_PATH . 'thumbnail/' . $data_package->image;
                        if ( file_exists($file_thumb_path) ) {
                            $file_thumb = $file_thumb_path;
                        }
                    }
                    if ( $file ) { unlink($file); }
                    if ( $file_thumb ) { unlink($file_thumb); }
                }

            } else {
                $data['status']     = 1;
                $data['created_by'] = $created_by;
                if ( ! $saved_data = $this->Model_Product->save_data_product_package($data) ) {
                    $data = array('status' => 'error', 'message' => 'Data Paket Produk tidak berhasil disimpan. Silahkan cek form produk !');
                    die(json_encode($data));
                }
                $id = $saved_data;
            }

            $id_encrypt = ddm_encrypt($id);
            $direct     = base_url('productmanage/packageedit/'.$id_encrypt);
            $direct     = base_url('productmanage/packagelist');
            // Save Success
            $data = array('status'=>'success', 'message'=>'Data Paket Produk berhasil disimpan.', 'url'=>$direct );
            die(json_encode($data));
        }
    }

    /**
     * Save Category Product Function
     */
    function savecategory( $id = 0 ){
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('productmanage/categorylist'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $category_id            = '';
        $category_name          = '';
        if ( $id ) {
            $id = ddm_decrypt($id);
            if ( ! $data_category = ddm_product_category($id) ) {
                $data = array('status' => 'error', 'message' => 'Data Kategori Produk tidak berhasil disimpan. ID Kategori Produk tidak ditemukan !');
                die(json_encode($data));
            }
            $category_id        = $data_category->id;
            $category_name      = $data_category->name;
        }

        // set variables
        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);
        $datetime               = date('Y-m-d H:i:s');

        // POST Input Form
        $category               = trim( $this->input->post('category') );
        $category               = ddm_isset($category, 0);
        $form_input             = trim( $this->input->post('form') );
        $form_input             = ddm_isset($form_input, '');

        $this->form_validation->set_rules('category','Kategori Produk','required');
        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE){
            $data = array('status' => 'error', 'message' => 'Data Kategori Produk tidak berhasil disimpan. '.validation_errors() );
            die(json_encode($data));
        }else{
            $category           = strtolower($category);
            $slug               = url_title($category, 'dash', TRUE);
            $check_slug         = true;
            if ( $category_id == $id && strtolower($category_name) == strtolower($category) ) {
                $check_slug     = false;
            }

            if ( $check_slug ) {
                $condition      = ' AND %slug% = "'.$slug.'" OR %slug% LIKE "'.$slug.'-%" ';
                if ( $check_slug = $this->Model_Product->get_all_category(0, 0, $condition) ) {
                    $count_slug = count($check_slug);
                    $slug       = $slug .'-'. $count_slug;
                }
            }

            $created_by         = $current_member->username;
            if ( $staff = ddm_get_current_staff() ) {
                $created_by     = $staff->username;
            }

            $data = array(
                'name'          => ucwords($category),
                'slug'          => $slug,
                'datecreated'   => $datetime,
                'datemodified'  => $datetime,
            );

            if ( $id ) {
                unset($data['datecreated']);
                $data['modified_by'] = $created_by;
                if ( ! $datacategory = ddm_product_category($id) ) {
                    $data = array('status' => 'error', 'message' => 'Data Kategori Produk tidak berhasil disimpan. Silahkan cek form Kategori !');
                    die(json_encode($data));
                }
                if ( ! $update_data = $this->Model_Product->update_data_product_category($id, $data) ) {
                    $data = array('status' => 'error', 'message' => 'Data Kategori Produk tidak berhasil disimpan. Silahkan cek form Kategori !');
                    die(json_encode($data));
                }
            } else {
                $data['status']     = 1;
                $data['created_by'] = $created_by;
                if ( ! $saved_data = $this->Model_Product->save_data_product_category($data) ) {
                    $data = array('status' => 'error', 'message' => 'Data Kategori Produk tidak berhasil disimpan. Silahkan cek form Kategori !');
                    die(json_encode($data));
                }
                $id = $saved_data;
            }

            if ( strtolower($form_input) == 'product' ) {
                $option = '<option value="" disabled="" selected="">-- '. lang('select') .' '. lang('select') .'--</option>';
                if ( $get_categories = ddm_product_category(0, true) ) {
                    foreach($get_categories as $row){
                        if ( $id == $row->id ) {
                            $selected = 'selected=""';
                        } else {
                            $selected = '';
                        }
                        $option .= '<option value="'. $row->id .'" '. $selected .'>'. ucwords($row->name) .'</option>';
                    }
                }
            } else {
                $option = '';
            }

            // Save Success
            $data = array('status'=>'success', 'option'=>$option, 'form_input'=>$form_input, 'message'=>'Data Kategori Produk berhasil disimpan.');
            die(json_encode($data));
        }
    }

    /**
     * Save Product Point Function
     */
    function saveproductpoint( $source = 0,  $id = 0 ){
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('productmanage/productpoint'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        if( !$id || !$source ){
            $data = array('status' => 'error', 'message' => 'ID Source tidak ditemukan !');
            die(json_encode($data));
        }

        $id = ddm_decrypt($id);
        if ( $source == 'product' ) {
            $data_source = ddm_products($id);
        } else {
            $data_source = ddm_product_package('id', $id);
        }

        if ( ! $data_source ) {
            $data = array('status' => 'error', 'message' => 'Data Source tidak ditemukan !');
            die(json_encode($data));
        }  

        // set variables
        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);
        $datetime           = date('Y-m-d H:i:s');

        // POST Input Form
        $total      = trim( $this->input->post('total') );
        $total      = ddm_isset($total, 0);
        $point      = trim( $this->input->post('point') );
        $point      = ddm_isset($point, 0);

        $this->form_validation->set_rules('total','Jumlah','required');
        $this->form_validation->set_rules('point','Poin','required');
        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE){
            $data = array('status' => 'error', 'message' => 'Data Source tidak berhasil disimpan. '.validation_errors() );
            die(json_encode($data));
        }else{
            $modified_by        = $current_member->username;
            if ( $staff = ddm_get_current_staff() ) {
                $modified_by    = $staff->username;
            }

            $data = array(
                'source'        => $source,
                'id_source'     => $id,
                'total'         => str_replace('.', '', $total),
                'point'         => str_replace('.', '', $point),
                'datecreated'   => $datetime,
                'datemodified'  => $datetime,
            );
            $condition  = array('source' => $source);
            if ( $get_product_point = ddm_product_point_by('id_source', $id, $condition) ) {
                $id_point = $get_product_point->id;
                unset($data['datecreated']);
                $data['modified_by'] = $modified_by;
                if ( ! $update_data = $this->Model_Product->update_data_product_point($id_point, $data) ) {
                    $data = array('status' => 'error', 'message' => 'Poin Produk tidak berhasil disimpan !');
                    die(json_encode($data));
                }
            } else {
                $data['created_by'] = $modified_by;
                if ( ! $save_data = $this->Model_Product->save_data_product_point($data) ) {
                    $data = array('status' => 'error', 'message' => 'Poin Produk tidak berhasil disimpan !');
                    die(json_encode($data));
                }
            }

            // Save Success
            $data = array('status'=>'success', 'message'=>'Poin Produk berhasil disimpan.');
            die(json_encode($data));
        }

    }

    /**
     * Status Product Function
     */
    function productstatus( $id = 0 ){
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('productmanage/productlist'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        if( !$id ){
            $data = array('status' => 'error', 'message' => 'ID Produk tidak ditemukan !');
            die(json_encode($data));
        }

        $id = ddm_decrypt($id);
        if ( ! $data_product = ddm_products($id) ) {
            $data = array('status' => 'error', 'message' => 'Data Produk tidak ditemukan !');
            die(json_encode($data));
        }

        // set variables
        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);
        $datetime           = date('Y-m-d H:i:s');
        $status             = ( $data_product->status == 1 ) ? 0 : 1;

        $modified_by        = $current_member->username;
        if ( $staff = ddm_get_current_staff() ) {
            $modified_by    = $staff->username;
        }

        $data = array(
            'status'        => $status,
            'modified_by'   => $modified_by,
            'datemodified'  => $datetime,
        );

        if ( ! $update_data = $this->Model_Product->update_data_product($id, $data) ) {
            $data = array('status' => 'error', 'message' => 'Status Produk tidak berhasil diedit !');
            die(json_encode($data));
        }

        // Save Success
        $data = array('status'=>'success', 'message'=>'Status Produk berhasil diedit.');
        die(json_encode($data));
    }

    /**
     * Delete Product Function
     */
    function productdelete( $id = 0 ){
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('productmanage/productlist'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        if( !$id ){
            $data = array('status' => 'error', 'message' => 'ID Produk tidak ditemukan !');
            die(json_encode($data));
        }

        $id = ddm_decrypt($id);
        if ( ! $data_product = ddm_products($id) ) {
            $data = array('status' => 'error', 'message' => 'Data Produk tidak ditemukan !');
            die(json_encode($data));
        }

        if ( $data_shop = $this->Model_Shop->get_shop_detail_by('product', $id) ) {
            $data = array('status' => 'error', 'message' => 'Data Produk tidak dapat di hapus! Data Produk ini sudah ada di Pesanan Produk.');
            die(json_encode($data));
        }

        $condition = str_replace('%s%', $id, ' AND product_ids LIKE "%%s%%"');
        if ( $data_package = $this->Model_Product->get_all_product_package(0, 0, $condition) ) {
            $data = array('status' => 'error', 'message' => 'Data Produk tidak dapat di hapus! Data Produk ini sudah ada di data Paket Produk.');
            die(json_encode($data));
        }

        if ( ! $delete_data = $this->Model_Product->delete_data_product($id) ) {
            $data = array('status' => 'error', 'message' => 'Produk tidak berhasil dihapus !');
            die(json_encode($data));
        }

        // Save Success
        $data = array('status'=>'success', 'message'=>'Produk berhasil dihapus.');
        die(json_encode($data));
    }

    /**
     * Status Product Package Function
     */
    function packagestatus( $id = 0 ){
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('productmanage/packagelist'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        if( !$id ){
            $data = array('status' => 'error', 'message' => 'ID Paket Produk tidak ditemukan !');
            die(json_encode($data));
        }

        $id = ddm_decrypt($id);
        if ( ! $data_package = ddm_product_package('id', $id) ) {
            $data = array('status' => 'error', 'message' => 'Data Paket Produk tidak ditemukan !');
            die(json_encode($data));
        }

        // set variables
        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);
        $datetime           = date('Y-m-d H:i:s');
        $status             = ( $data_package->status == 1 ) ? 0 : 1;

        $modified_by        = $current_member->username;
        if ( $staff = ddm_get_current_staff() ) {
            $modified_by    = $staff->username;
        }

        $data = array(
            'status'        => $status,
            'modified_by'   => $modified_by,
            'datemodified'  => $datetime,
        );

        if ( ! $update_data = $this->Model_Product->update_data_product_package($id, $data) ) {
            $data = array('status' => 'error', 'message' => 'Status Paket Produk tidak berhasil diedit !');
            die(json_encode($data));
        }

        // Save Success
        $data = array('status'=>'success', 'message'=>'Status Paket Produk berhasil diedit.');
        die(json_encode($data));
    }

    /**
     * Delete Product Package Function
     */
    function packagedelete( $id = 0 ){
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('productmanage/pckagelist'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        if( !$id ){
            $data = array('status' => 'error', 'message' => 'ID Paket Produk tidak ditemukan !');
            die(json_encode($data));
        }

        $id = ddm_decrypt($id);
        if ( ! $data_package = ddm_product_package('id', $id) ) {
            $data = array('status' => 'error', 'message' => 'Data Paket Produk tidak ditemukan !');
            die(json_encode($data));
        }

        if ( $data_shop = $this->Model_Shop->get_shop_detail_by('package', $id) ) {
            $data = array('status' => 'error', 'message' => 'Data Paket Produk tidak dapat di hapus! Data Paket Produk ini sudah ada di Pesanan Produk.');
            die(json_encode($data));
        }

        if ( ! $delete_data = $this->Model_Product->delete_data_product_package($id) ) {
            $data = array('status' => 'error', 'message' => 'Paket Produk tidak berhasil dihapus !');
            die(json_encode($data));
        }

        // Save Success
        $data = array('status'=>'success', 'message'=>'Paket Produk berhasil dihapus.');
        die(json_encode($data));
    }

    /**
     * Status Category Product Function
     */
    function categorystatus( $id = 0 ){
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('productmanage/categorylist'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        if( !$id ){
            $data = array('status' => 'error', 'message' => 'ID Kategori Produk tidak ditemukan !');
            die(json_encode($data));
        }

        $id = ddm_decrypt($id);
        if ( ! $data_category = ddm_product_category($id) ) {
            $data = array('status' => 'error', 'message' => 'Data Kategori Produk tidak ditemukan !');
            die(json_encode($data));
        }

        // set variables
        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);
        $datetime           = date('Y-m-d H:i:s');
        $status             = ( $data_category->status == 1 ) ? 0 : 1;

        $modified_by        = $current_member->username;
        if ( $staff = ddm_get_current_staff() ) {
            $modified_by    = $staff->username;
        }

        $data = array(
            'status'        => $status,
            'modified_by'   => $modified_by,
            'datemodified'  => $datetime,
        );

        if ( ! $update_data = $this->Model_Product->update_data_product_category($id, $data) ) {
            $data = array('status' => 'error', 'message' => 'Status Kategori Produk tidak berhasil diedit !');
            die(json_encode($data));
        }

        // Save Success
        $data = array('status'=>'success', 'message'=>'Status Kategori Produk berhasil diedit.');
        die(json_encode($data));
    }

    /**
     * Delete Category Product Function
     */
    function categorydelete( $id = 0 ){
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('productmanage/categorylist'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        if( !$id ){
            $data = array('status' => 'error', 'message' => 'ID Kategori Produk tidak ditemukan !');
            die(json_encode($data));
        }

        $id = ddm_decrypt($id);
        if ( ! $data_category = ddm_product_category($id) ) {
            $data = array('status' => 'error', 'message' => 'Data Kategori Produk tidak ditemukan !');
            die(json_encode($data));
        }

        if ( $data_product = ddm_product_by('id_category', $id) ) {
            $data = array('status' => 'error', 'message' => 'Data Kategori tidak dapat di hapus! Data Kategori ini sudah digunakan di Data Produk.');
            die(json_encode($data));
        }

        if ( ! $delete_data = $this->Model_Product->delete_data_category($id) ) {
            $data = array('status' => 'error', 'message' => 'Kategori Produk tidak berhasil dihapus !');
            die(json_encode($data));
        }

        // Save Success
        $data = array('status'=>'success', 'message'=>'Kategori Produk berhasil dihapus.');
        die(json_encode($data));
    }
}

/* End of file Productmanage.php */
/* Location: ./app/controllers/Productmanage.php */
