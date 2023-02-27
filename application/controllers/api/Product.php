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
class Product extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Load Shop helper 
        $this->load->helper('shop_helper');

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
    }

    public function index_post()
    {
        $limit      = $this->post('limit');
        $offset     = $this->post('offset');
        $sortBy     = $this->post('sortby');
        $orderBy    = $this->post('orderby');
        $orderBy    = ddm_isset($orderBy, 'ASC');

        // Get Condition
        $id         = $this->post('id');
        $name       = $this->post('name');
        $category   = $this->post('category');

        $data       = false;
        $totalRow   = 0;
        $condition  = ' AND %status% = 1';
        $order_by   = '%datecreated% DESC';

        if ( !empty($id) )          { $condition .= str_replace('%s%', $id, ' AND %id% = %s%'); }
        if ( !empty($name) )        { $condition .= str_replace('%s%', $name, ' AND %name% LIKE "%%s%%"'); }
        if ( !empty($category) )    { $condition .= str_replace('%s%', $category, ' AND %category% LIKE "%%s%%"'); }

        if ( $sortBy && $orderBy ) {
            $order_by = $sortBy .' '. $orderBy;
            if ( strtolower($sortBy) ==  'datecreated') { $order_by = '%datecreated% '. $orderBy; }
            if ( strtolower($sortBy) ==  'name' || strtolower($sortBy) ==  'product' ) { $order_by = '%name% '. $orderBy; }
            if ( strtolower($sortBy) ==  'category' ) { $order_by = '%category% '. $orderBy; }
        }

        if ( $get_products = shop_search_product($limit, $offset, $condition, $order_by) ) {
            $totalRow   = isset($get_products['total_row']) ? $get_products['total_row'] : 0;
            $data       = isset($get_products['data']) ? $get_products['data'] : false;
        }

        if ( $data ) {
            $results    = array();
            foreach ($data as $key => $row) {
                unset($row->status);
                unset($row->created_by);
                unset($row->modified_by);
                unset($row->datecreated);
                unset($row->datemodified);

                $image              = product_image($row->image, false);
                $image_thumbnail    = product_image($row->image, true);

                $row->image             = $image;
                $row->image_thumbnail   = $image;
                $results[]              = $row;
            }

            if ( $id ) {
                // Set the response and exit
                $this->response([
                    'success'       => TRUE,
                    'status'        => REST_Controller::HTTP_OK,
                    'message'       => 'Data Produk ditemukan',
                    'data'          => $results[0],
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            } else {
                // Set the response and exit
                $this->response([
                    'success'       => TRUE,
                    'status'        => REST_Controller::HTTP_OK,
                    'message'       => 'Data Produk ditemukan',
                    'total_data'    => $totalRow,
                    'data'          => $results,
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
        } else {
            // Set the response and exit
            $this->response([
                'success'   => FALSE,
                'status'    => REST_Controller::HTTP_NOT_FOUND,
                'message'   => 'Data Produk tidak ditemukan'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

    }

    public function package_post()
    {
        $limit      = $this->post('limit');
        $offset     = $this->post('offset');
        $sortBy     = $this->post('sortby');
        $orderBy    = $this->post('orderby');
        $orderBy    = ddm_isset($orderBy, 'ASC');

        // Get Condition
        $id         = $this->post('id');
        $name       = $this->post('name');

        $data       = false;
        $totalRow   = 0;
        $condition  = ' AND %status% = 1';
        $order_by   = '%datecreated% DESC';

        if ( !empty($id) )          { $condition .= str_replace('%s%', $id, ' AND %id% = %s%'); }
        if ( !empty($name) )        { $condition .= str_replace('%s%', $name, ' AND %name% LIKE "%%s%%"'); }

        if ( $sortBy && $orderBy ) {
            $order_by = $sortBy .' '. $orderBy;
            if ( strtolower($sortBy) ==  'datecreated') { $order_by = '%datecreated% '. $orderBy; }
            if ( strtolower($sortBy) ==  'name' || strtolower($sortBy) ==  'product' ) { $order_by = '%name% '. $orderBy; }
        }

        if ( $get_products = shop_product_package($limit, $offset, $condition, $order_by) ) {
            $totalRow   = isset($get_products['total_row']) ? $get_products['total_row'] : 0;
            $data       = isset($get_products['data']) ? $get_products['data'] : false;
        }

        if ( $data ) {
            $results    = array();
            foreach ($data as $key => $row) {
                unset($row->point);
                unset($row->is_mix);
                unset($row->lock_qty);
                unset($row->product_ids);
                unset($row->qty_free_shipping);
                unset($row->status);
                unset($row->created_by);
                unset($row->modified_by);
                unset($row->datecreated);
                unset($row->datemodified);

                $row->image             = product_image($row->image, false);
                $row->product_details   = maybe_unserialize($row->product_details);
                $results[]              = $row;
            }

            if ( $id ) {
                // Set the response and exit
                $this->response([
                    'success'       => TRUE,
                    'status'        => REST_Controller::HTTP_OK,
                    'message'       => 'Data Paket Produk ditemukan',
                    'data'          => $results[0],
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            } else {
                // Set the response and exit
                $this->response([
                    'success'       => TRUE,
                    'status'        => REST_Controller::HTTP_OK,
                    'message'       => 'Data Paket Produk ditemukan',
                    'total_data'    => $totalRow,
                    'data'          => $results,
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
        } else {
            // Set the response and exit
            $this->response([
                'success'   => FALSE,
                'status'    => REST_Controller::HTTP_NOT_FOUND,
                'message'   => 'Data Paket Produk tidak ditemukan'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }

    public function category_post()
    {
        $limit      = $this->post('limit');
        $offset     = $this->post('offset');
        $sortBy     = $this->post('sortby');
        $orderBy    = $this->post('orderby');

        // Get Condition
        $id         = $this->post('id');
        $name       = $this->post('name');
        $category   = $this->post('category');

        $data       = false;
        $totalRow   = 0;
        $condition  = ' AND %status% = 1';
        $order_by   = '%datecreated% DESC';

        if ( !empty($id) )          { $condition .= str_replace('%s%', $id, ' AND %id% = %s%'); }
        if ( !empty($name) )        { $condition .= str_replace('%s%', $name, ' AND %name% LIKE "%%s%%"'); }
        if ( !empty($category) )    { $condition .= str_replace('%s%', $category, ' AND %category% LIKE "%%s%%"'); }

        if ( $sortBy && $orderBy ) {
            if ( strtolower($sortBy) ==  'datecreated') { $order_by = '%datecreated% '. $orderBy; }
            if ( strtolower($sortBy) ==  'name' || strtolower($sortBy) ==  'product' ) { $order_by = '%name% '. $orderBy; }
            if ( strtolower($sortBy) ==  'category' ) { $order_by = '%category% '. $orderBy; }
        }

        if ( ! $data = $this->Model_Product->get_all_category($limit, $offset, $condition, $order_by) ) {
            // Set the response and exit
            $this->response([
                'success'   => FALSE,
                'status'    => REST_Controller::HTTP_NOT_FOUND,
                'message'   => 'Data Kategori tidak ditemukan'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        $totalRow   = ddm_get_last_found_rows();
        $results    = array();
        foreach ($data as $key => $row) {
            unset($row->status);
            unset($row->created_by);
            unset($row->modified_by);
            unset($row->datecreated);
            unset($row->datemodified);
            $results[]              = $row;
        }

        if ( $id ) {
            // Set the response and exit
            $this->response([
                'success'       => TRUE,
                'status'        => REST_Controller::HTTP_OK,
                'message'       => 'Data Kategori ditemukan',
                'data'          => $results[0],
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            // Set the response and exit
            $this->response([
                'success'       => TRUE,
                'status'        => REST_Controller::HTTP_OK,
                'message'       => 'Data Kategori ditemukan',
                'total_data'    => $totalRow,
                'data'          => $results,
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
    }

    public function minorder_post()
    {
        $response       = array(
            'success'   => FALSE,
            'status'    => REST_Controller::HTTP_NOT_FOUND,
            'message'   => 'Agen tidak valid'
        );

        // Get Condition
        $id_agent   = $this->post('id_agent');

        if ( !$id_agent ) {
            $response['message'] = 'id_agent field is required';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        // Check Data Agent
        $member = ddm_get_memberdata_by_id($id_agent);
        if ( ! $member ) {
            // Set the response and exit
            $response['message'] = 'Agen tidak valid';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( $member->status != ACTIVE || $member->type != MEMBER ) {
            $response['message'] = 'Agen tidak valid';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        $condition      = array('status' => 'perdana');
        if ( $omzetperdana  = $this->Model_Member->get_member_omzet_by('id_member', $member->id, $condition) ) {
            $status_order   = 'ro';
        } else {
            $status_order   = 'perdana';
        }

        $multiple       = true;
        $min_order      = config_item('min_order_agent');;
        $cfg_minorder   = config_item('order_type');
        $cfg_minorder   = isset($cfg_minorder[$status_order]) ? $cfg_minorder[$status_order] : false;
        $count_minorder = ($cfg_minorder) ? count($cfg_minorder) : 0;

        if ( ! $cfg_minorder ) {
            // Set the response and exit
            $response['message'] = 'Config Agent Order not found';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }


        // Set the response and exit
        $this->response([
            'success'       => TRUE,
            'status'        => REST_Controller::HTTP_OK,
            'message'       => 'Data Config Agent Order',
            'type_order'    => $status_order,
            'data'          => $cfg_minorder,
        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function minorderconfig_post()
    {   
        $response       = array('success' => FALSE, 'status' => REST_Controller::HTTP_NOT_FOUND);

        // Get Input post
        $status_order   = $this->post('type');
        if ( !$status_order ) {
            $response['message'] = 'type field is required';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        $status_order   = strtolower($status_order);
        $min_order      = config_item('min_order_agent');;
        $cfg_minorder   = config_item('order_type');
        $cfg_minorder   = isset($cfg_minorder[$status_order]) ? $cfg_minorder[$status_order] : false;
        $count_minorder = ($cfg_minorder) ? count($cfg_minorder) : 0;

        if ( ! $cfg_minorder ) {
            // Set the response and exit
            $response['message'] = 'Config order not found';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }


        // Set the response and exit
        $this->response([
            'success'       => TRUE,
            'status'        => REST_Controller::HTTP_OK,
            'message'       => 'Data Config Order',
            'data'          => $cfg_minorder,
        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

}
