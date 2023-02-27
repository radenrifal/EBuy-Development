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
class Checkout extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Load Shop helper 
        $this->load->helper('shop_helper');

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
    }

    public function customer_post()
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
        
        $id_agent           = sanitize($this->post('id_agent'));
        $name               = sanitize($this->post('name'));
        $phone              = sanitize($this->post('phone'));
        $email              = sanitize($this->post('email'));

        $province           = sanitize($this->post('province')); 
        $city               = sanitize($this->post('city')); 
        $subdistrict        = sanitize($this->post('subdistrict'));
        $address            = sanitize($this->post('address'));
        $postcode           = sanitize($this->post('postcode'));

        $weight             = sanitize($this->post('weight'));
        $courier            = sanitize($this->post('courier'));
        $courier_service    = sanitize($this->post('courier_service'));
        $courier_cost       = sanitize($this->post('courier_cost'));

        $discount           = sanitize($this->post('discount'));
        $voucher_code       = sanitize($this->post('voucher_code'));

        $save_customer      = sanitize($this->post('save_customer'));

        $product_cart       = $this->post('product_cart');

        if ( !$product_cart ) {
            // Set the response and exit
            $this->response([
                'success'   => FALSE,
                'status'    => REST_Controller::HTTP_NOT_FOUND,
                'message'   => 'Checkout tidak berhasil. Silahkan periksa kembali keranjang belanjaan anda!'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        ## Validation Global --------------------------------------------------------------
        $this->form_validation->set_rules('id_agent', 'id_agent', 'required');
        $this->form_validation->set_rules('name', 'name', 'required|min_length[3]');
        $this->form_validation->set_rules('phone', 'phone', 'numeric|required');
        $this->form_validation->set_rules('email', 'email', 'valid_email|required|min_length[3]');
        $this->form_validation->set_rules('province', 'province', 'required');
        $this->form_validation->set_rules('city', 'city', 'required');
        $this->form_validation->set_rules('subdistrict', 'subdistrict', 'required');
        $this->form_validation->set_rules('address', 'address', 'required');
        $this->form_validation->set_rules('postcode', 'postcode', 'numeric');

        $this->form_validation->set_rules('courier', 'courier', 'required');
        $this->form_validation->set_rules('courier_service', 'courier_service', 'required');
        $this->form_validation->set_rules('courier_cost', 'courier_cost', 'required');

        $this->form_validation->set_error_delimiters('', ' ');
        if ($this->form_validation->run() == FALSE) {
            // Set the response and exit
            $this->response([
                'success'   => FALSE,
                'status'    => REST_Controller::HTTP_NOT_FOUND,
                'message'   => validation_errors()
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        // Check Data Agent
        $agent = ddm_get_memberdata_by_id($id_agent);
        if ( ! $agent ) {
            // Set the response and exit
            $this->response([
                'success'   => FALSE,
                'status'    => REST_Controller::HTTP_NOT_FOUND,
                'message'   => 'Agen tidak valid. Silahkan kembali ke pencarian Agen !'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( $agent->status != ACTIVE || $agent->type != MEMBER ) {
            // Set the response and exit
            $this->response([
                'success'   => FALSE,
                'agent'     => $agent,
                'status'    => REST_Controller::HTTP_NOT_FOUND,
                'message'   => 'Agen tidak valid. . Silahkan kembali ke pencarian Agen !'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        // Set Data Product
        $total_qty          = 0;
        $total_price        = 0;
        $data_product       = array();
        foreach ($product_cart as $item) {
            $product_id     = isset($item['id']) ? $item['id'] : 0;
            $qty            = isset($item['qty']) ? $item['qty'] : 0;
            $price          = isset($item['price']) ? $item['price'] : 0;
            $price_cart     = isset($item['price_cart']) ? $item['price_cart'] : 0;
            $subtotal       = $qty * $price_cart;
            $discount_prod  = $price - $price_cart;

            if ( !$product_id || !$qty ) { continue; }
            if ( !$getProduct = ddm_products($product_id) ) { continue; }

            $data_product[] = array(
                'id'            => $product_id,
                'qty'           => $qty,
                'price'         => $price_cart, // price after discount
                'price_ori'     => $price, // original price
                'name'          => $getProduct->name,
                'weight'        => $getProduct->weight,
                'point'         => 0,
                'total_point'   => 0,
                'discount'      => $discount,
                'discount_qty'  => ($discount_prod) ? $getProduct->discount_customer_min : '',
                'discount_type' => ($discount_prod) ? $getProduct->discount_customer_type : '',
            );
            $total_qty      += $qty;
            $total_price    += $subtotal;
        }

        if ( !$data_product || ! $total_qty ) {
            // Set the response and exit
            $this->response([
                'success'   => FALSE,
                'status'    => REST_Controller::HTTP_NOT_FOUND,
                'message'   => 'Checkout tidak berhasil. Silahkan periksa kembali keranjang belanjaan anda!'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        $province_id        = $province; // id
        $province_name      = ''; // name
        if ( $getProvince = ddm_provinces($province) ) {
            $province_name  = $getProvince->province_name; // name
        }
        $city_id            = $city; // id
        $city_name          = ''; // name
        if ( $getCity = ddm_districts($city_id) ) {
            $city_name      = $getCity->district_type .' '. $getCity->district_name; // name
        }
        $subdistrict_id     = $subdistrict; // id
        $subdistrict_name   = ''; // name
        if ( $getSubdistrict = ddm_subdistricts($subdistrict_id) ) {
            $subdistrict_name = $getSubdistrict->subdistrict_name; // name
        }

        // -------------------------------------------------
        // Transaction Begin
        // -------------------------------------------------
        $this->db->trans_begin();

        $datetime           = date('Y-m-d H:i:s');
        $customer_save_id   = 0;

        if ( $save_customer ) {
            // Set Data Customer
            // -------------------------------------------------
            $data_customer          = array(
                'name'              => strtoupper($name),
                'email'             => strtolower($email),
                'phone'             => $phone,
                'address'           => $address,
                'id_province'       => $province_id,
                'id_city'           => $city_id,
                'id_subdistrict'    => $subdistrict_id,
                'province_name'     => $province_name,
                'city_name'         => $city_name,
                'subdistrict_name'  => $subdistrict_name,
                'address'           => strtolower($address),
                'postcode'          => $postcode,
                'datecreated'       => $datetime,
                'datemodified'      => $datetime
            );

            // Save Customer
            // -------------------------------------------------
            if ( $getCustomer = $this->Model_Shop->get_customer_by('phone', $phone) ) {
                if ( $update_data_customer = $this->Model_Shop->update_data_customer($getCustomer->id, $data_customer) ) {
                    $customer_save_id = $getCustomer->id;
                }
            } else {
                $customer_save_id = $this->Model_Shop->save_data_customer($data_customer);
            }

            if( ! $customer_save_id ){
                $this->db->trans_rollback(); // Rollback Transaction
                // Set the response and exit
                $this->response([
                    'success'   => FALSE,
                    'status'    => REST_Controller::HTTP_NOT_FOUND,
                    'message'   => 'Checkout tidak berhasil. Silahkan periksa kembali keranjang belanjaan anda!'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }
        
        // Set Data Shop Order
        // -------------------------------------------------
        $invoice        = generate_customer_invoice($agent->id);
        $code_unique    = 0;
        $created_by     = strtolower($name);
        $discount       = !empty($discount) ? $discount : 0;
        $total_payment  = $total_price + $courier_cost - $discount; 

        $data_shop_order = array(
            'invoice'           => $invoice,
            'id_member'         => $agent->id,
            'id_customer'       => $customer_save_id,

            'products'          => serialize($data_product),
            'product_point'     => 0,
            'total_qty'         => $total_qty,
            'weight'            => $weight,

            'subtotal'          => $total_price,
            'shipping'          => $courier_cost,
            'unique'            => 0,
            'discount'          => $discount,
            'voucher'           => $voucher_code,
            'total_payment'     => $total_payment,

            'payment_method'    => 'transfer',
            'shipping_method'   => 'ekspedisi',
            'name'              => strtolower($name),
            'email'             => strtolower($email),
            'phone'             => $phone,
            'province'          => $province_name,
            'city'              => $city_name,
            'subdistrict'       => $subdistrict_name,
            'address'           => strtolower($address),
            'postcode'          => $postcode,
            'courier'           => $courier,
            'service'           => $courier_service,
            'created_by'        => $created_by,
            'datecreated'       => $datetime,
            'datemodified'      => $datetime,
        );

        $shop_order_id = $this->Model_Shop->save_data_shop_order_customer($data_shop_order);
        if( ! $shop_order_id ){
            $this->db->trans_rollback(); // Rollback Transaction
            // Set the response and exit
            $this->response([
                'success'   => FALSE,
                'status'    => REST_Controller::HTTP_NOT_FOUND,
                'message'   => 'Checkout tidak berhasil. Terjadi kesalahan data transaksi.'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        $data_order_detail = array();
        $no = 1;
        foreach ($data_product as $prodkey => $shop_order) {
            $discount       = $shop_order['price_ori'] - $shop_order['price'];
            $total          = $shop_order['price'] * $shop_order['qty'];

            $data_order_detail[$no] = array(
                'id_shop_order' => $shop_order_id,
                'product'       => $shop_order['id'],
                'qty'           => $shop_order['qty'],
                'amount'        => $shop_order['price_ori'],
                'amount_order'  => $shop_order['price'],
                'discount'      => $discount,
                'total'         => $total,
                'total_point'   => $shop_order['total_point'],
                'weight'        => $shop_order['weight'],
                'datecreated'   => $datetime,
                'datemodified'  => $datetime,
            );
            $no++;
        }

        if (!$data_order_detail) {
            $this->db->trans_rollback(); // Rollback Transaction
            // Set the response and exit
            $this->response([
                'success'   => FALSE,
                'status'    => REST_Controller::HTTP_NOT_FOUND,
                'message'   => 'Checkout tidak berhasil. Terjadi kesalahan data transaksi produk detail'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        foreach ($data_order_detail as $row) {
            // -------------------------------------------------
            // Save Shop Order Detail
            // -------------------------------------------------
            $order_detail_saved = $this->Model_Shop->save_data_shop_detail_customer($row);

            if ( !$order_detail_saved ) {
                $this->db->trans_rollback(); // Rollback Transaction
                // Set the response and exit
                $this->response([
                    'success'   => FALSE,
                    'status'    => REST_Controller::HTTP_NOT_FOUND,
                    'message'   => 'Checkout tidak berhasil. Terjadi kesalahan data transaksi produk detail'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }

        ## Order Success -------------------------------------------------------
        $this->db->trans_commit();
        $this->db->trans_complete(); //  complete database transactions  

        ddm_log_action( 'CHECKOUT_CUSTOMER', $invoice, 'MOBILE_APP', json_encode(array('cookie'=>$_COOKIE, 'status'=>'SUCCESS', 'shop_order_id'=>$shop_order_id)) );

        if ( $shop_order = $this->Model_Shop->get_shop_order_customer_by('id', $shop_order_id) ) {
            // Send Email
            $mail_customer  = $this->ddm_email->send_email_shop_order_customer( $shop_order );
            $mail_agent     = $this->ddm_email->send_email_shop_order_to_agent( $agent, $shop_order );
        }

        // Set the response and exit
        $this->response([
            'success'       => TRUE,
            'status'        => REST_Controller::HTTP_OK,
            'shop_order_id'  => $shop_order_id,
            'message'       => 'Checkout berhasil',
        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function agent_post()
    {
        $id_agent           = sanitize($this->post('id_agent'));
        $name               = sanitize($this->post('name'));
        $phone              = sanitize($this->post('phone'));
        $email              = sanitize($this->post('email'));

        $province           = sanitize($this->post('province')); 
        $city               = sanitize($this->post('city')); 
        $subdistrict        = sanitize($this->post('subdistrict'));
        $address            = sanitize($this->post('address'));
        $postcode           = sanitize($this->post('postcode'));

        $weight             = sanitize($this->post('weight'));
        $courier            = sanitize($this->post('courier'));
        $courier_service    = sanitize($this->post('courier_service'));
        $courier_cost       = sanitize($this->post('courier_cost'));

        $discount           = sanitize($this->post('discount'));
        $voucher_code       = sanitize($this->post('voucher_code'));

        $total_qty_package  = sanitize($this->post('total_qty_package'));
        $product_cart       = $this->post('product_cart');

        $response           = array(
            'success'       => FALSE,
            'status'        => REST_Controller::HTTP_NOT_FOUND,
            'message'       => 'Checkout tidak berhasil. Silahkan periksa kembali keranjang belanjaan anda!'
        );

        if ( !$product_cart ) {
            // Set the response and exit
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        ## Validation Global --------------------------------------------------------------
        $this->form_validation->set_rules('id_agent', 'id_agent', 'required');
        $this->form_validation->set_rules('name', 'name', 'required|min_length[3]');
        $this->form_validation->set_rules('phone', 'phone', 'numeric|required');
        $this->form_validation->set_rules('email', 'email', 'valid_email|required|min_length[3]');
        $this->form_validation->set_rules('province', 'province', 'required');
        $this->form_validation->set_rules('city', 'city', 'required');
        $this->form_validation->set_rules('subdistrict', 'subdistrict', 'required');
        $this->form_validation->set_rules('address', 'address', 'required');
        $this->form_validation->set_rules('postcode', 'postcode', 'numeric');

        $this->form_validation->set_rules('courier', 'courier', 'required');
        $this->form_validation->set_rules('courier_service', 'courier_service', 'required');
        $this->form_validation->set_rules('courier_cost', 'courier_cost', 'required');

        $this->form_validation->set_rules('total_qty_package', 'total_qty_package', 'numeric|required');

        $this->form_validation->set_error_delimiters('', ' ');
        if ($this->form_validation->run() == FALSE) {
            // Set the response and exit
            $response['message'] = validation_errors();
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        // Check Data Agent
        $agent = ddm_get_memberdata_by_id($id_agent);
        if ( ! $agent ) {
            // Set the response and exit
            $response['message'] = 'Agen tidak valid';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( $agent->status != ACTIVE || $agent->type != MEMBER ) {
            $response['message'] = 'Agen tidak valid';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        // Set Data Product
        $total_qty          = 0;
        $total_price        = 0;
        $total_point        = 0;
        $total_weight       = 0;
        $data_product       = array();
        foreach ($product_cart as $item) {
            $productId      = isset($item['id']) ? $item['id'] : 0;
            $qty            = isset($item['qty']) ? $item['qty'] : 0;

            if ( !$productId || !$qty ) { continue; }
            if ( ! $getPackage = ddm_product_package('id', $productId) ) { continue; }

            $product_point  = 0;
            $subtotal_point = 0;
            $package_name   = isset($getPackage->name) ? $getPackage->name : $package_name;
            $package_price  = isset($getPackage->price) ? $getPackage->price : 0;
            $package_weight = isset($getPackage->weight) ? $getPackage->weight : 0;
            $productDetail  = isset($getPackage->product_details) ? $getPackage->product_details : false;
            $productDetail  = ($productDetail) ? maybe_unserialize($productDetail) : false; 

            $product_details = array();
            if ( $productDetail ) {
                foreach ($productDetail as $row) {
                    $product_id     = isset($row['id']) ? $row['id'] : 0;
                    $product_qty    = isset($row['qty']) ? $row['qty'] : 0;
                    $product_price  = isset($row['price']) ? $row['price'] : 0;
                    $subtotal       = ($product_qty * $product_price);

                    $getProduct = ddm_products($product_id, false);
                    $product_details[$product_id] = array(
                        'id'            => $product_id,
                        'name'          => isset($getProduct->name) ? $getProduct->name : '',
                        'qty'           => $product_qty,
                        'price'         => $product_price,
                        'subtotal'      => $subtotal,
                        'total_qty'     => ($product_qty * $qty),
                        'total_price'   => ($subtotal * $qty),
                    );
                }
            }

            $data_product[] = array(
                'id'            => $productId,
                'qty'           => $qty,
                'price'         => $package_price, // price after discount
                'price_ori'     => $package_price, // original price
                'name'          => $package_name,
                'weight'        => $package_weight,
                'point'         => $product_point,
                'total_point'   => $subtotal_point,
                'package_point' => $subtotal_point,
                'product_detail'=> $product_details,
                'discount'      => 0,
                'discount_qty'  => 0,
            );

            $total_qty     += $qty;
            $total_point   += $subtotal_point;
            $total_price   += ($package_price * $qty);
            $total_weight  += ($package_weight * $qty);
        }

        if ( !$data_product || !$total_qty ) {
            // Set the response and exit
            $response['message'] = 'Checkout tidak berhasil. Silahkan periksa kembali keranjang belanjaan anda!';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( $total_qty_package != $total_qty ) {
            // Set the response and exit
            $response['message'] = 'Checkout tidak berhasil. Total Qty Paket produk tidak sesuai dengan kriteria syarat agen order paket produk!';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        $province_id        = $province; // id
        $province_name      = ''; // name
        if ( $getProvince = ddm_provinces($province) ) {
            $province_name  = $getProvince->province_name; // name
        }
        $city_id            = $city; // id
        $city_name          = ''; // name
        if ( $getCity = ddm_districts($city_id) ) {
            $city_name      = $getCity->district_type .' '. $getCity->district_name; // name
        }
        $subdistrict_id     = $subdistrict; // id
        $subdistrict_name   = ''; // name
        if ( $getSubdistrict = ddm_subdistricts($subdistrict_id) ) {
            $subdistrict_name = $getSubdistrict->subdistrict_name; // name
        }

        // -------------------------------------------------
        // Transaction Begin
        // -------------------------------------------------
        $this->db->trans_begin();

        // -------------------------------------------------
        // Set Data Order
        // -------------------------------------------------
        // Config package point
        $cfg_pack_qty       = get_option('cfg_package_qty');
        $cfg_pack_qty       = $cfg_pack_qty ? $cfg_pack_qty : 0;
        $cfg_pack_point     = get_option('cfg_package_point');
        $cfg_pack_point     = $cfg_pack_point ? $cfg_pack_point : 0;
        if ( $cfg_pack_qty > 0 && $total_qty >= $cfg_pack_qty ) {
            $package_point  = floor($total_qty / $cfg_pack_qty);  
            $total_point    = $package_point * $cfg_pack_point;      
        }

        $invoice            = generate_invoice();
        $code_unique        = generate_uniquecode();
        $total_payment      = $total_price + $courier_cost + $code_unique - $discount;
        $datetime           = date('Y-m-d H:i:s');

        $data_shop_order = array(
            'invoice'           => $invoice,
            'id_member'         => $agent->id,

            'products'          => serialize($data_product),
            'product_point'     => 0,
            'total_qty'         => $total_qty,
            'weight'            => $weight,

            'subtotal'          => $total_price,
            'shipping'          => $courier_cost,
            'unique'            => $code_unique,
            'discount'          => $discount,
            'voucher'           => $voucher_code,
            'total_payment'     => $total_payment,

            'payment_method'    => 'transfer',
            'shipping_method'   => 'ekspedisi',
            'name'              => strtolower($name),
            'email'             => strtolower($email),
            'phone'             => $phone,
            'province'          => $province_name,
            'city'              => $city_name,
            'subdistrict'       => $subdistrict_name,
            'address'           => strtolower($address),
            'postcode'          => $postcode,
            'courier'           => $courier,
            'service'           => $courier_service,
            'created_by'        => $agent->username,
            'datecreated'       => $datetime,
            'datemodified'      => $datetime,
        );

        // Check Omzet Member
        $condition_omzet    = array('status' => 'perdana');
        if ( $omzetperdana  = $this->Model_Member->get_member_omzet_by('id_member', $agent->id, $condition_omzet) ) {
            $status_omzet   = 'ro';
        } else {
            $status_omzet   = 'perdana';
        }
        $data_shop_order['type']    = $status_omzet;

        $shop_order_id = $this->Model_Shop->save_data_shop_order($data_shop_order);
        if( ! $shop_order_id ){
            $this->db->trans_rollback(); // Rollback Transaction
            // Set the response and exit
                $response['message'] = 'Checkout tidak berhasil. Terjadi kesalahan data transaksi.';
                $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        $data_order_detail = array();
        $no = 1;
        foreach ($data_product as $prodkey => $shop_order) {
            $package_id     = isset($shop_order['id']) ? $shop_order['id'] : 0;
            $package_point  = isset($shop_order['package_point']) ? $shop_order['package_point'] : 0;
            $productDetail  = isset($shop_order['product_detail']) ? $shop_order['product_detail'] : false;
            if ( $productDetail ) {
                foreach ($productDetail as $key => $row) {
                    $product_id     = isset($row['id']) ? $row['id'] : 'none';
                    if ( $get_product = ddm_products($product_id, false) ) {
                        $stock = $get_product->stock - $row['total_qty'];  // Update stock [to decrement]
                        if ( !$update_stock = $this->Model_Product->update_data_product($product_id, array('stock' => $stock)) ) {
                            $this->db->trans_rollback();
                            // Set the response and exit
                            $response['message'] = 'Checkout tidak berhasil. Terjadi kesalahan transaksi data update stock.';
                            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                        }
                    }

                    $price_ori      = isset($get_product->price_agent) ? $get_product->price_agent : 0;
                    $discount       = ( $price_ori > $row['price'] ) ? ($price_ori - $row['price']) : 0;

                    $data_order_detail[$no] = array(
                        'id_shop_order' => $shop_order_id,
                        'id_member'     => $agent->id,
                        'package'       => $package_id,
                        'package_point' => $package_point,
                        'product'       => $product_id,
                        'product_point' => 0,
                        'qty'           => $row['total_qty'],
                        'amount'        => $price_ori,
                        'amount_order'  => $row['price'],
                        'total'         => $row['total_price'],
                        'total_point'   => 0,
                        'discount'      => $discount,
                        'weight'        => isset($get_product->weight) ? $get_product->weight : 0,
                        'datecreated'   => $datetime,
                        'datemodified'  => $datetime,
                    );
                    $no++;
                }
            }
        }

        if (!$data_order_detail) {
            $this->db->trans_rollback(); // Rollback Transaction
            // Set the response and exit
            $response['message'] = 'Checkout tidak berhasil. Terjadi kesalahan data transaksi produk detail.';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        foreach ($data_order_detail as $row) {
            // -------------------------------------------------
            // Save Shop Order Detail
            // -------------------------------------------------
            $order_detail_saved = $this->Model_Shop->save_data_shop_order_detail($row);

            if ( !$order_detail_saved ) {
                $this->db->trans_rollback(); // Rollback Transaction
                // Set the response and exit
                $response['message'] = 'Checkout tidak berhasil. Terjadi kesalahan data transaksi produk detail.';
                $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }

        ## Order Success -------------------------------------------------------
        $this->db->trans_commit();
        $this->db->trans_complete(); //  complete database transactions  

        ddm_log_action( 'CHECKOUT', $invoice, 'MOBILE_APP', json_encode(array('cookie'=>$_COOKIE, 'status'=>'SUCCESS', 'shop_order_id'=>$shop_order_id)) );

        if ( $shop_order = $this->Model_Shop->get_shop_orders($shop_order_id) ) {
            // Send Email
            $mail = $this->ddm_email->send_email_shop_order( $agent, $shop_order );
        }

        // Set the response and exit
        $this->response([
            'success'       => TRUE,
            'status'        => REST_Controller::HTTP_OK,
            'shop_order_id'  => $shop_order_id,
            'message'       => 'Checkout berhasil',
        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function registeragent_post()
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

        $sponsor            = sanitize($this->post('sponsor'));
        $username           = sanitize($this->post('username'));
        $password           = sanitize($this->post('password'));
        $name               = sanitize($this->post('name'));
        $phone              = sanitize($this->post('phone'));
        $email              = sanitize($this->post('email'));

        $bank               = sanitize($this->post('bank'));
        $billno             = sanitize($this->post('billno'));
        $billname           = sanitize($this->post('billname'));

        $province           = sanitize($this->post('province')); 
        $city               = sanitize($this->post('city')); 
        $subdistrict        = sanitize($this->post('subdistrict'));
        $address            = sanitize($this->post('address'));
        $postcode           = sanitize($this->post('postcode'));

        $weight             = sanitize($this->post('weight'));
        $courier            = sanitize($this->post('courier'));
        $courier_service    = sanitize($this->post('courier_service'));
        $courier_cost       = sanitize($this->post('courier_cost'));

        $discount           = sanitize($this->post('discount'));
        $voucher_code       = sanitize($this->post('voucher_code'));

        $total_qty_package  = sanitize($this->post('total_qty_package'));
        $product_cart       = $this->post('product_cart');

        $response           = array(
            'success'       => FALSE,
            'status'        => REST_Controller::HTTP_NOT_FOUND,
            'message'       => 'Pendaftaran dan order paket produk tidak berhasil. Silahkan periksa kembali formulir pendaftaran anda!'
        );

        if ( !$product_cart ) {
            // Set the response and exit
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        ## Validation Global --------------------------------------------------------------
        $this->form_validation->set_rules('username', 'username', 'required|min_length[6]');
        $this->form_validation->set_rules('password', 'password', 'required|min_length[6]');
        $this->form_validation->set_rules('name', 'name', 'required|min_length[3]');
        $this->form_validation->set_rules('phone', 'phone', 'numeric|required');
        $this->form_validation->set_rules('email', 'email', 'valid_email|required|min_length[3]');

        $this->form_validation->set_rules('bank', 'bank', 'required');
        $this->form_validation->set_rules('billno', 'billno', 'required|numeric');
        $this->form_validation->set_rules('billname', 'billname', 'required');

        $this->form_validation->set_rules('province', 'province', 'required');
        $this->form_validation->set_rules('city', 'city', 'required');
        $this->form_validation->set_rules('subdistrict', 'subdistrict', 'required');
        $this->form_validation->set_rules('address', 'address', 'required');
        $this->form_validation->set_rules('postcode', 'postcode', 'numeric');

        $this->form_validation->set_rules('courier', 'courier', 'required');
        $this->form_validation->set_rules('courier_service', 'courier_service', 'required');
        $this->form_validation->set_rules('courier_cost', 'courier_cost', 'required');

        $this->form_validation->set_rules('total_qty_package', 'total_qty_package', 'numeric|required');

        $this->form_validation->set_error_delimiters('', ' ');
        if ($this->form_validation->run() == FALSE) {
            // Set the response and exit
            $response['message'] = validation_errors();
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        // -------------------------------------------------
        // Check Sponsor
        // -------------------------------------------------
        $sponsordata            = ddm_get_memberdata_by_id(1);
        if ( $sponsor  ) {
            if ( ! $getSponsor = $this->Model_Member->get_member_by('login', $sponsor) ) {
                $response['message'] = 'Kode Referral tidak ditemukan atau belum terdaftar! Silahkan masukkan Kode Referral lainnya!';
                $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
            if ( $getSponsor->type != MEMBER || $getSponsor->status != ACTIVE ) {
                $response['message'] = 'Kode Referral sudah tidak aktif. Silahkan masukkan Kode Referral lainnya!';
                $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
            $sponsordata        = $getSponsor;
        }

        // -------------------------------------------------
        // Check Username availability
        // -------------------------------------------------
        $username               = strtolower(trim($username));
        if( $username_exist = ddm_check_username($username) ){
            $response['message'] = 'Username tidak valid. Silahkan gunakan Username lainnya!';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
        
        // -------------------------------------------------
        // Check Email availability
        // -------------------------------------------------
        if( $email_exist = $this->Model_Member->get_member_by('email', $email) ){
            $response['message'] = 'Email sudah terdaftar. Silahkan gunakan Email lainnya';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        // -------------------------------------------------
        // Check Bank
        // -------------------------------------------------    
        $get_bank      = ddm_banks($bank);
        if ( ! $get_bank ) {
            $response['message'] = 'Bank tidak valid';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        // -------------------------------------------------
        // Set Region
        // -------------------------------------------------      
        $province_id        = $province; // id
        $province_name      = ''; // name
        if ( $getProvince = ddm_provinces($province) ) {
            $province_name  = $getProvince->province_name; // name
        }
        $city_id            = $city; // id
        $city_name          = ''; // name
        if ( $getCity = ddm_districts($city_id) ) {
            $city_name      = $getCity->district_type .' '. $getCity->district_name; // name
        }
        $subdistrict_id     = $subdistrict; // id
        $subdistrict_name   = ''; // name
        if ( $getSubdistrict = ddm_subdistricts($subdistrict_id) ) {
            $subdistrict_name = $getSubdistrict->subdistrict_name; // name
        }

        // -------------------------------------------------    
        // Set Data Product
        // -------------------------------------------------    
        $total_qty          = 0;
        $total_price        = 0;
        $total_point        = 0;
        $total_weight       = 0;
        $data_product       = array();
        foreach ($product_cart as $item) {
            $productId      = isset($item['id']) ? $item['id'] : 0;
            $qty            = isset($item['qty']) ? $item['qty'] : 0;

            if ( !$productId || !$qty ) { continue; }
            if ( ! $getPackage = ddm_product_package('id', $productId) ) { continue; }

            $product_point  = 0;
            $subtotal_point = 0;
            $package_name   = isset($getPackage->name) ? $getPackage->name : $package_name;
            $package_price  = isset($getPackage->price) ? $getPackage->price : 0;
            $package_weight = isset($getPackage->weight) ? $getPackage->weight : 0;
            $productDetail  = isset($getPackage->product_details) ? $getPackage->product_details : false;
            $productDetail  = ($productDetail) ? maybe_unserialize($productDetail) : false; 

            $product_details = array();
            if ( $productDetail ) {
                foreach ($productDetail as $row) {
                    $product_id     = isset($row['id']) ? $row['id'] : 0;
                    $product_qty    = isset($row['qty']) ? $row['qty'] : 0;
                    $product_price  = isset($row['price']) ? $row['price'] : 0;
                    $subtotal       = ($product_qty * $product_price);

                    $getProduct = ddm_products($product_id, false);
                    $product_details[$product_id] = array(
                        'id'            => $product_id,
                        'name'          => isset($getProduct->name) ? $getProduct->name : '',
                        'qty'           => $product_qty,
                        'price'         => $product_price,
                        'subtotal'      => $subtotal,
                        'total_qty'     => ($product_qty * $qty),
                        'total_price'   => ($subtotal * $qty),
                    );
                }
            }

            $data_product[] = array(
                'id'            => $productId,
                'qty'           => $qty,
                'price'         => $package_price, // price after discount
                'price_ori'     => $package_price, // original price
                'name'          => $package_name,
                'weight'        => $package_weight,
                'point'         => $product_point,
                'total_point'   => $subtotal_point,
                'package_point' => $subtotal_point,
                'product_detail'=> $product_details,
                'discount'      => 0,
                'discount_qty'  => 0,
            );

            $total_qty     += $qty;
            $total_point   += $subtotal_point;
            $total_price   += ($package_price * $qty);
            $total_weight  += ($package_weight * $qty);
        }

        if ( !$data_product || !$total_qty ) {
            // Set the response and exit
            $response['message'] = 'Pendaftaran dan order paket produk tidak berhasil. Silahkan periksa kembali keranjang belanjaan anda!';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( $total_qty_package != $total_qty ) {
            // Set the response and exit
            $response['message'] = 'Pendaftaran dan order paket produk tidak berhasil. Total Qty Paket produk tidak sesuai dengan kriteria syarat agen order paket produk!';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        // -------------------------------------------------
        // Transaction Begin
        // -------------------------------------------------
        $this->db->trans_begin();

        // -------------------------------------------------
        // Set Data Member
        // -------------------------------------------------
        // $password               = strtolower($password);
        $datetime               = date('Y-m-d H:i:s');

        $member_save_id         = NULL;
        $member_confirm_id      = NULL;
        $name                   = strtoupper($name);
        $password_bcript        = ddm_password_hash($password);
        $package                = MEMBER_AGENT;
        $cfg_register_fee       = get_option('register_fee');
        $cfg_register_fee       = $cfg_register_fee ? $cfg_register_fee : 0;
        $code_unique            = generate_uniquecode();
        $total_omzet            = $total_price + $cfg_register_fee;
        
        $data_member            = array(
            'username'          => $username,
            'password'          => $password_bcript,
            'password_pin'      => $password_bcript,
            'name'              => $name,
            'email'             => $email,
            'type'              => MEMBER,
            'package'           => $package,
            'sponsor'           => $sponsordata->id,
            'parent'            => $sponsordata->id,
            'phone'             => $phone,
            'address'           => $address,
            'province'          => $province_id,
            'district'          => $city_id,
            'subdistrict'       => $subdistrict_id,
            'bank'              => $bank,
            'bill'              => $billno,
            'bill_name'         => strtoupper($billname),
            'status'            => 0,
            'total_omzet'       => $total_omzet,
            'uniquecode'        => $code_unique,
            'datecreated'       => $datetime,
        );

        if( ! $member_save_id = $this->Model_Member->save_data( $data_member ) ){
            $this->db->trans_rollback(); // Rollback Transaction
            // Set the response and exit
            $response['message'] = 'Pendaftaran dan order paket produk tidak berhasil. Terjadi kesalahan data simpan data Agen.';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( ! $memberdata = ddm_get_memberdata_by_id( $member_save_id ) ) {
            $this->db->trans_rollback(); // Rollback Transaction
            // Set the response and exit
            $response['message'] = 'Pendaftaran dan order paket produk tidak berhasil. Terjadi kesalahan data simpan data Agen.';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        $data_member_confirm    = array(
            'id_member'         => $memberdata->id,
            'member'            => $memberdata->username,
            'id_sponsor'        => $sponsordata->id,
            'sponsor'           => $sponsordata->username,
            'id_downline'       => $memberdata->id,
            'downline'          => $memberdata->username,
            'status'            => 0,
            'access'            => 'shop',
            'package'           => $package,
            'omzet'             => $total_omzet,
            'uniquecode'        => $code_unique,
            'nominal'           => ( $total_omzet + $code_unique ),
            'datecreated'       => $datetime,
            'datemodified'      => $datetime,
        );

        if( ! $member_confirm_id = $this->Model_Member->save_data_confirm($data_member_confirm) ){
            $this->db->trans_rollback(); // Rollback Transaction
            // Set the response and exit
            $response['message'] = 'Pendaftaran dan order paket produk tidak berhasil. Terjadi kesalahan data simpan data Agen.';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        // -------------------------------------------------
        // Set Data Order
        // -------------------------------------------------
        // Config package point
        $cfg_pack_qty       = get_option('cfg_package_qty');
        $cfg_pack_qty       = $cfg_pack_qty ? $cfg_pack_qty : 0;
        $cfg_pack_point     = get_option('cfg_package_point');
        $cfg_pack_point     = $cfg_pack_point ? $cfg_pack_point : 0;
        if ( $cfg_pack_qty > 0 && $total_qty >= $cfg_pack_qty ) {
            $package_point  = floor($total_qty / $cfg_pack_qty);  
            $total_point    = $package_point * $cfg_pack_point;      
        }

        $invoice            = generate_invoice();
        $total_payment      = $total_omzet + $courier_cost + $code_unique - $discount;
        
        $data_shop_order = array(
            'invoice'           => $invoice,
            'id_member'         => $memberdata->id,
            'type'              => 'perdana',

            'products'          => serialize($data_product),
            'product_point'     => 0,
            'total_qty'         => $total_qty,
            'weight'            => $weight,

            'subtotal'          => $total_price,
            'registration'      => $cfg_register_fee,
            'shipping'          => $courier_cost,
            'unique'            => $code_unique,
            'discount'          => $discount,
            'voucher'           => $voucher_code,
            'total_payment'     => $total_payment,

            'payment_method'    => 'transfer',
            'shipping_method'   => 'ekspedisi',
            'name'              => strtolower($name),
            'email'             => strtolower($email),
            'phone'             => $phone,
            'province'          => $province_name,
            'city'              => $city_name,
            'subdistrict'       => $subdistrict_name,
            'address'           => strtolower($address),
            'postcode'          => $postcode,
            'courier'           => $courier,
            'service'           => $courier_service,
            'created_by'        => $memberdata->username,
            'datecreated'       => $datetime,
            'datemodified'      => $datetime,
        );

        // Save Order
        $shop_order_id = $this->Model_Shop->save_data_shop_order($data_shop_order);
        if( ! $shop_order_id ){
            $this->db->trans_rollback(); // Rollback Transaction
            // Set the response and exit
            $response['message'] = 'Pendaftaran dan order paket produk tidak berhasil. Terjadi kesalahan data transaksi.';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        $data_order_detail = array();
        $no = 1;
        foreach ($data_product as $prodkey => $shop_order) {
            $package_id     = isset($shop_order['id']) ? $shop_order['id'] : 0;
            $package_point  = isset($shop_order['package_point']) ? $shop_order['package_point'] : 0;
            $productDetail  = isset($shop_order['product_detail']) ? $shop_order['product_detail'] : false;
            if ( $productDetail ) {
                foreach ($productDetail as $key => $row) {
                    $product_id     = isset($row['id']) ? $row['id'] : 'none';
                    if ( $get_product = ddm_products($product_id, false) ) {
                        $stock = $get_product->stock - $row['total_qty'];  // Update stock [to decrement]
                        if ( !$update_stock = $this->Model_Product->update_data_product($product_id, array('stock' => $stock)) ) {
                            $this->db->trans_rollback();
                            // Set the response and exit
                            $response['message'] = 'Pendaftaran dan order paket produk tidak berhasil. Terjadi kesalahan transaksi data update stock.';
                            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                        }
                    }

                    $price_ori      = isset($get_product->price_agent) ? $get_product->price_agent : 0;
                    $discount       = ( $price_ori > $row['price'] ) ? ($price_ori - $row['price']) : 0;

                    $data_order_detail[$no] = array(
                        'id_shop_order' => $shop_order_id,
                        'id_member'     => $memberdata->id,
                        'package'       => $package_id,
                        'package_point' => $package_point,
                        'product'       => $product_id,
                        'product_point' => 0,
                        'qty'           => $row['total_qty'],
                        'amount'        => $price_ori,
                        'amount_order'  => $row['price'],
                        'total'         => $row['total_price'],
                        'total_point'   => 0,
                        'discount'      => $discount,
                        'weight'        => isset($get_product->weight) ? $get_product->weight : 0,
                        'datecreated'   => $datetime,
                        'datemodified'  => $datetime,
                    );
                    $no++;
                }
            }
        }

        if (!$data_order_detail) {
            $this->db->trans_rollback(); // Rollback Transaction
            // Set the response and exit
            $response['message'] = 'Pendaftaran dan order paket produk tidak berhasil. Terjadi kesalahan data transaksi produk detail.';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        foreach ($data_order_detail as $row) {
            // -------------------------------------------------
            // Save Shop Order Detail
            // -------------------------------------------------
            $order_detail_saved = $this->Model_Shop->save_data_shop_order_detail($row);

            if ( !$order_detail_saved ) {
                $this->db->trans_rollback(); // Rollback Transaction
                // Set the response and exit
                $response['message'] = 'Pendaftaran dan order paket produk tidak berhasil. Terjadi kesalahan data transaksi produk detail.';
                $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }

        ## Order Success -------------------------------------------------------
        $this->db->trans_commit();
        $this->db->trans_complete(); //  complete database transactions  

        ddm_log_action( 'CHECKOUT_REGISTER', $invoice, 'MOBILE_APP', json_encode(array('cookie'=>$_COOKIE, 'status'=>'SUCCESS', 'shop_order_id'=>$shop_order_id)) );

        // Set the response and exit
        $this->response([
            'success'       => TRUE,
            'status'        => REST_Controller::HTTP_OK,
            'shop_order_id' => $shop_order_id,
            'id_agent'      => $memberdata->id,
            'message'       => 'Pendaftaran dan order paket produk berhasil',
        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function registerbyagent_post()
    {
        $id_agent           = sanitize($this->post('id_agent'));
        $sponsor            = sanitize($this->post('sponsor'));
        $username           = sanitize($this->post('username'));
        $password           = sanitize($this->post('password'));
        $name               = sanitize($this->post('name'));
        $phone              = sanitize($this->post('phone'));
        $email              = sanitize($this->post('email'));

        $bank               = sanitize($this->post('bank'));
        $billno             = sanitize($this->post('billno'));
        $billname           = sanitize($this->post('billname'));

        $province           = sanitize($this->post('province')); 
        $city               = sanitize($this->post('city')); 
        $subdistrict        = sanitize($this->post('subdistrict'));
        $address            = sanitize($this->post('address'));
        $postcode           = sanitize($this->post('postcode'));

        $weight             = sanitize($this->post('weight'));
        $courier            = sanitize($this->post('courier'));
        $courier_service    = sanitize($this->post('courier_service'));
        $courier_cost       = sanitize($this->post('courier_cost'));

        $total_discount     = sanitize($this->post('discount'));
        $voucher_code       = sanitize($this->post('voucher_code'));

        $payment_method     = sanitize($this->post('payment_method'));

        $total_qty_package  = sanitize($this->post('total_qty_package'));
        $product_cart       = $this->post('product_cart');

        $response           = array(
            'success'       => FALSE,
            'status'        => REST_Controller::HTTP_NOT_FOUND,
            'message'       => 'Pendaftaran dan order paket produk tidak berhasil. Silahkan periksa kembali formulir pendaftaran anda!'
        );

        if ( !$product_cart ) {
            // Set the response and exit
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        ## Validation Global --------------------------------------------------------------
        $this->form_validation->set_rules('id_agent', 'id_agent', 'numeric|required');
        $this->form_validation->set_rules('username', 'username', 'required|min_length[6]');
        $this->form_validation->set_rules('password', 'password', 'required|min_length[6]');
        $this->form_validation->set_rules('name', 'name', 'required|min_length[3]');
        $this->form_validation->set_rules('phone', 'phone', 'numeric|required');
        $this->form_validation->set_rules('email', 'email', 'valid_email|required|min_length[3]');

        $this->form_validation->set_rules('bank', 'bank', 'required');
        $this->form_validation->set_rules('billno', 'billno', 'required|numeric');
        $this->form_validation->set_rules('billname', 'billname', 'required');

        $this->form_validation->set_rules('province', 'province', 'required');
        $this->form_validation->set_rules('city', 'city', 'required');
        $this->form_validation->set_rules('subdistrict', 'subdistrict', 'required');
        $this->form_validation->set_rules('address', 'address', 'required');
        $this->form_validation->set_rules('postcode', 'postcode', 'numeric');

        $this->form_validation->set_rules('courier', 'courier', 'required');
        $this->form_validation->set_rules('courier_service', 'courier_service', 'required');
        $this->form_validation->set_rules('courier_cost', 'courier_cost', 'required');

        $this->form_validation->set_rules('payment_method', 'payment_method', 'required');

        $this->form_validation->set_rules('total_qty_package', 'total_qty_package', 'numeric|required');

        $this->form_validation->set_error_delimiters('', ' ');
        if ($this->form_validation->run() == FALSE) {
            // Set the response and exit
            $response['message'] = validation_errors();
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        // Check Data Agent
        $agent = ddm_get_memberdata_by_id($id_agent);
        if ( ! $agent ) {
            // Set the response and exit
            $response['message'] = 'Agen tidak valid';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( $agent->status != ACTIVE || $agent->type != MEMBER ) {
            $response['message'] = 'Agen tidak valid';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        // -------------------------------------------------
        // Check Username availability
        // -------------------------------------------------
        $username               = strtolower(trim($username));
        if( $username_exist = ddm_check_username($username) ){
            $response['message'] = 'Username tidak valid. Silahkan gunakan Username lainnya!';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
        
        // -------------------------------------------------
        // Check Email availability
        // -------------------------------------------------
        if( $email_exist = $this->Model_Member->get_member_by('email', $email) ){
            $response['message'] = 'Email sudah terdaftar. Silahkan gunakan Email lainnya';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        // -------------------------------------------------
        // Check Sponsor
        // -------------------------------------------------
        $sponsordata            = $agent;
        if ( $sponsor  ) {
            $sponsor            = strtolower($sponsor);
            if ( $sponsor != strtolower($agent->username) ) {
                if ( ! $getSponsor = $this->Model_Member->get_member_by('login', $sponsor) ) {
                    $response['message'] = 'Sponsor tidak ditemukan atau belum terdaftar! Silahkan masukkan Kode Referral lainnya!';
                    $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                }
                if ( $getSponsor->type != MEMBER || $getSponsor->status != ACTIVE ) {
                    $response['message'] = 'Sponsor sudah tidak aktif. Silahkan masukkan Kode Referral lainnya!';
                    $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                }
                $sponsordata        = $getSponsor;

                // -------------------------------------------------
                // Check If Sponsor is Downline
                // -------------------------------------------------
                $is_downline        = $this->Model_Member->get_is_downline($sponsordata->id, $agent->tree);
                if( !$is_downline ){
                    $response['message'] = 'Sponsor ini bukan jaringan Anda! Silahkan masukkan Username Sponsor lain!';
                    $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                }
            }
        }

        // -------------------------------------------------
        // Check Bank
        // -------------------------------------------------    
        $get_bank      = ddm_banks($bank);
        if ( ! $get_bank ) {
            $response['message'] = 'Bank tidak valid';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        // -------------------------------------------------
        // Set Region
        // -------------------------------------------------      
        $province_id        = $province; // id
        $province_name      = ''; // name
        if ( $getProvince = ddm_provinces($province) ) {
            $province_name  = $getProvince->province_name; // name
        }
        $city_id            = $city; // id
        $city_name          = ''; // name
        if ( $getCity = ddm_districts($city_id) ) {
            $city_name      = $getCity->district_type .' '. $getCity->district_name; // name
        }
        $subdistrict_id     = $subdistrict; // id
        $subdistrict_name   = ''; // name
        if ( $getSubdistrict = ddm_subdistricts($subdistrict_id) ) {
            $subdistrict_name = $getSubdistrict->subdistrict_name; // name
        }

        // -------------------------------------------------    
        // Set Data Product
        // -------------------------------------------------    
        $total_qty          = 0;
        $total_price        = 0;
        $total_point        = 0;
        $total_weight       = 0;
        $data_product       = array();
        foreach ($product_cart as $item) {
            $productId      = isset($item['id']) ? $item['id'] : 0;
            $qty            = isset($item['qty']) ? $item['qty'] : 0;

            if ( !$productId || !$qty ) { continue; }
            if ( ! $getPackage = ddm_product_package('id', $productId) ) { continue; }

            $product_point  = 0;
            $subtotal_point = 0;
            $package_name   = isset($getPackage->name) ? $getPackage->name : $package_name;
            $package_price  = isset($getPackage->price) ? $getPackage->price : 0;
            $package_weight = isset($getPackage->weight) ? $getPackage->weight : 0;
            $productDetail  = isset($getPackage->product_details) ? $getPackage->product_details : false;
            $productDetail  = ($productDetail) ? maybe_unserialize($productDetail) : false; 

            $product_details = array();
            if ( $productDetail ) {
                foreach ($productDetail as $row) {
                    $product_id     = isset($row['id']) ? $row['id'] : 0;
                    $product_qty    = isset($row['qty']) ? $row['qty'] : 0;
                    $product_price  = isset($row['price']) ? $row['price'] : 0;
                    $subtotal       = ($product_qty * $product_price);

                    $getProduct = ddm_products($product_id, false);
                    $product_details[$product_id] = array(
                        'id'            => $product_id,
                        'name'          => isset($getProduct->name) ? $getProduct->name : '',
                        'qty'           => $product_qty,
                        'price'         => $product_price,
                        'subtotal'      => $subtotal,
                        'total_qty'     => ($product_qty * $qty),
                        'total_price'   => ($subtotal * $qty),
                    );
                }
            }

            $data_product[] = array(
                'id'            => $productId,
                'qty'           => $qty,
                'price'         => $package_price, // price after discount
                'price_ori'     => $package_price, // original price
                'name'          => $package_name,
                'weight'        => $package_weight,
                'point'         => $product_point,
                'total_point'   => $subtotal_point,
                'package_point' => $subtotal_point,
                'product_detail'=> $product_details,
                'discount'      => 0,
                'discount_qty'  => 0,
            );

            $total_qty     += $qty;
            $total_point   += $subtotal_point;
            $total_price   += ($package_price * $qty);
            $total_weight  += ($package_weight * $qty);
        }

        if ( !$data_product || !$total_qty ) {
            // Set the response and exit
            $response['message'] = 'Pendaftaran dan order paket produk tidak berhasil. Silahkan periksa kembali keranjang belanjaan anda!';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( $total_qty_package != $total_qty ) {
            // Set the response and exit
            $response['message'] = 'Pendaftaran dan order paket produk tidak berhasil. Total Qty Paket produk tidak sesuai dengan kriteria syarat agen order paket produk!';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        // -------------------------------------------------
        // Set Data Payment
        // -------------------------------------------------
        $cfg_register_fee       = get_option('register_fee');
        $cfg_register_fee       = $cfg_register_fee ? $cfg_register_fee : 0;
        $total_omzet            = $total_price + $cfg_register_fee;
        $total_payment          = $total_omzet + $courier_cost - $total_discount;

        $m_status               = 0;
        $m_access               = 'member';
        $ewallet_access         = false;

        $payment_method         = strtolower($payment_method);
        if( $payment_method == 'deposite' ){
            $saldo              = $this->Model_Bonus->get_ewallet_deposite($agent->id); 
            if ( $total_payment > $saldo ) {
                $response['message'] = 'Saldo Deposite Anda tidak mencukupi untuk pendaftaran Agen ini!';
                $response['saldo'] = $saldo;
                $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
            $ewallet_access = true;
            $m_status       = 1;
            $m_access       = 'ewallet';
        }

        // -------------------------------------------------
        // Transaction Begin
        // -------------------------------------------------
        $this->db->trans_begin();

        // -------------------------------------------------
        // Set Data Member
        // -------------------------------------------------
        // $password               = strtolower($password);
        $currency               = config_item('currency');
        $datetime               = date('Y-m-d H:i:s');

        $member_save_id         = NULL;
        $member_confirm_id      = NULL;
        $name                   = strtoupper($name);
        $password_bcript        = ddm_password_hash($password);
        $package                = MEMBER_AGENT;
        $code_unique            = ( $m_status == 1 ) ? 0 : ddm_generate_shop_order();
        $total_payment          = $total_payment + $code_unique;
        
        $data_member            = array(
            'username'          => $username,
            'password'          => $password_bcript,
            'password_pin'      => $password_bcript,
            'name'              => $name,
            'type'              => MEMBER,
            'email'             => $email,
            'phone'             => $phone,
            'package'           => $package,
            'sponsor'           => $sponsordata->id,
            'parent'            => $sponsordata->id,
            'position'          => 0,
            'address'           => $address,
            'province'          => $province_id,
            'district'          => $city_id,
            'subdistrict'       => $subdistrict_id,
            'bank'              => $bank,
            'bill'              => $billno,
            'bill_name'         => strtoupper($billname),
            'status'            => $m_status,
            'total_omzet'       => $total_omzet,
            'uniquecode'        => $code_unique,
            'datecreated'       => $datetime,
        );

        if( ! $member_save_id = $this->Model_Member->save_data( $data_member ) ){
            $this->db->trans_rollback(); // Rollback Transaction
            // Set the response and exit
            $response['message'] = 'Pendaftaran dan order paket produk tidak berhasil. Terjadi kesalahan data simpan data Agen.';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( ! $memberdata = ddm_get_memberdata_by_id( $member_save_id ) ) {
            $this->db->trans_rollback(); // Rollback Transaction
            // Set the response and exit
            $response['message'] = 'Pendaftaran dan order paket produk tidak berhasil. Terjadi kesalahan data simpan data Agen.';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( $m_status == 1 ) {
            // -------------------------------------------------
            // Update Member Position And Tree
            // -------------------------------------------------
            $level              = $sponsordata->level + 1;
            $position           = ddm_position_sponsor($sponsordata->id);
            $tree               = ddm_generate_tree( $member_save_id, $sponsordata->tree );
            $data_tree          = array( 'level' => $level, 'position' => $position, 'tree' => $tree );
            if ( ! $update_tree = $this->Model_Member->update_data_member( $member_save_id, $data_tree ) ){
                $this->db->trans_rollback(); // Rollback Transaction
                // Set the response and exit
                $response['message'] = 'Pendaftaran dan order paket produk tidak berhasil. Terjadi kesalahan pada update data jaringan Agen.';
                $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }

            // -------------------------------------------------
            // Update Ewallet Member
            // -------------------------------------------------
            if ( $ewallet_access && $total_payment > 0 ) {
                // Set Data Ewallet Member
                // -------------------------------------------------
                $desc = 'Register Agent tgl ' . date('Y-m-d', strtotime($datetime)). ' ' .ddm_accounting($total_price, $currency);
                $data_ewallet = array(
                    'id_member'     => $agent->id,
                    'id_source'     => $member_save_id,
                    'amount'        => $total_payment,
                    'source'        => 'register',
                    'type'          => 'OUT',
                    'status'        => 1,
                    'description'   => $desc,
                    'datecreated'   => $datetime
                );
                if ( ! $wallet_id  = $this->Model_Bonus->save_data_ewallet( $data_ewallet ) ) {
                    $this->db->trans_rollback(); // Rollback Transaction
                    // Set the response and exit
                    $response['message'] = 'Pendaftaran dan order paket produk tidak berhasil.  Terjadi kesalahan pada update data deposite bonus';
                    $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                }
            }

            // -------------------------------------------------
            // Generate Key Member
            // -------------------------------------------------
            $generate_key = ddm_generate_key();
            ddm_generate_key_insert($generate_key, ['id_member' => $member_save_id, 'name' => $name]);
        }

        $data_member_confirm    = array(
            'id_member'         => $memberdata->id,
            'member'            => $memberdata->username,
            'id_sponsor'        => $sponsordata->id,
            'sponsor'           => $sponsordata->username,
            'id_downline'       => $memberdata->id,
            'downline'          => $memberdata->username,
            'status'            => $m_status,
            'access'            => $m_access,
            'package'           => $package,
            'omzet'             => $total_omzet,
            'uniquecode'        => $code_unique,
            'nominal'           => ( $total_omzet + $code_unique ),
            'datecreated'       => $datetime,
            'datemodified'      => $datetime,
        );

        if( ! $member_confirm_id = $this->Model_Member->save_data_confirm($data_member_confirm) ){
            $this->db->trans_rollback(); // Rollback Transaction
            // Set the response and exit
            $response['message'] = 'Pendaftaran dan order paket produk tidak berhasil. Terjadi kesalahan data simpan data Agen.';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        // -------------------------------------------------
        // Set Data Order
        // -------------------------------------------------
        // Config package point
        $cfg_pack_qty       = get_option('cfg_package_qty');
        $cfg_pack_qty       = $cfg_pack_qty ? $cfg_pack_qty : 0;
        $cfg_pack_point     = get_option('cfg_package_point');
        $cfg_pack_point     = $cfg_pack_point ? $cfg_pack_point : 0;
        if ( $cfg_pack_qty > 0 && $total_qty >= $cfg_pack_qty ) {
            $package_point  = floor($total_qty / $cfg_pack_qty);  
            $total_point    = $package_point * $cfg_pack_point;      
        }

        $invoice            = generate_invoice();
        $data_shop_order    = array(
            'invoice'           => $invoice,
            'id_member'         => $memberdata->id,
            'type'              => 'perdana',

            'products'          => serialize($data_product),
            'product_point'     => 0,
            'total_qty'         => $total_qty,
            'weight'            => $weight,

            'subtotal'          => $total_price,
            'registration'      => $cfg_register_fee,
            'shipping'          => $courier_cost,
            'unique'            => $code_unique,
            'discount'          => $total_discount,
            'voucher'           => $voucher_code,
            'total_payment'     => $total_payment,

            'payment_method'    => ( $ewallet_access ) ? 'deposite' : 'transfer',
            'shipping_method'   => 'ekspedisi',
            'name'              => strtolower($name),
            'email'             => strtolower($email),
            'phone'             => $phone,
            'province'          => $province_name,
            'city'              => $city_name,
            'subdistrict'       => $subdistrict_name,
            'address'           => strtolower($address),
            'postcode'          => $postcode,
            'courier'           => $courier,
            'service'           => $courier_service,
            'created_by'        => $agent->username,
            'datecreated'       => $datetime,
            'datemodified'      => $datetime,
        );

        if ( $m_status == 1 ) {
            $data_shop_order['status'] = 1;
            $data_shop_order['dateconfirm'] = $datetime;
            $data_shop_order['confirmed_by'] = ( $ewallet_access ) ? 'deposite' : $agent->username;
        }

        // Save Order
        $shop_order_id = $this->Model_Shop->save_data_shop_order($data_shop_order);
        if( ! $shop_order_id ){
            $this->db->trans_rollback(); // Rollback Transaction
            // Set the response and exit
            $response['message'] = 'Pendaftaran dan order paket produk tidak berhasil. Terjadi kesalahan data transaksi.';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        $data_order_detail = array();
        $no = 1;
        foreach ($data_product as $prodkey => $shop_order) {
            $package_id     = isset($shop_order['id']) ? $shop_order['id'] : 0;
            $package_point  = isset($shop_order['package_point']) ? $shop_order['package_point'] : 0;
            $productDetail  = isset($shop_order['product_detail']) ? $shop_order['product_detail'] : false;
            if ( $productDetail ) {
                foreach ($productDetail as $key => $row) {
                    $product_id     = isset($row['id']) ? $row['id'] : 'none';
                    if ( $get_product = ddm_products($product_id, false) ) {
                        $stock = $get_product->stock - $row['total_qty'];  // Update stock [to decrement]
                        if ( !$update_stock = $this->Model_Product->update_data_product($product_id, array('stock' => $stock)) ) {
                            $this->db->trans_rollback();
                            // Set the response and exit
                            $response['message'] = 'Pendaftaran dan order paket produk tidak berhasil. Terjadi kesalahan transaksi data update stock.';
                            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                        }
                    }

                    $price_ori      = isset($get_product->price_agent) ? $get_product->price_agent : 0;
                    $discount       = ( $price_ori > $row['price'] ) ? ($price_ori - $row['price']) : 0;

                    $data_order_detail[$no] = array(
                        'id_shop_order' => $shop_order_id,
                        'id_member'     => $memberdata->id,
                        'package'       => $package_id,
                        'package_point' => $package_point,
                        'product'       => $product_id,
                        'product_point' => 0,
                        'qty'           => $row['total_qty'],
                        'amount'        => $price_ori,
                        'amount_order'  => $row['price'],
                        'total'         => $row['total_price'],
                        'total_point'   => 0,
                        'discount'      => $discount,
                        'weight'        => isset($get_product->weight) ? $get_product->weight : 0,
                        'datecreated'   => $datetime,
                        'datemodified'  => $datetime,
                    );
                    $no++;
                }
            }
        }

        if (!$data_order_detail) {
            $this->db->trans_rollback(); // Rollback Transaction
            // Set the response and exit
            $response['message'] = 'Pendaftaran dan order paket produk tidak berhasil. Terjadi kesalahan data transaksi produk detail.';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        foreach ($data_order_detail as $row) {
            // -------------------------------------------------
            // Save Shop Order Detail
            // -------------------------------------------------
            $order_detail_saved = $this->Model_Shop->save_data_shop_order_detail($row);

            if ( !$order_detail_saved ) {
                $this->db->trans_rollback(); // Rollback Transaction
                // Set the response and exit
                $response['message'] = 'Pendaftaran dan order paket produk tidak berhasil. Terjadi kesalahan data transaksi produk detail.';
                $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }

        if ( $m_status == 1 ) {
            // save data member omzet register
            if ( $cfg_register_fee > 0 ) {
                $data_member_omzet    = array(
                    'id_member'     => $memberdata->id,
                    'id_order'      => $shop_order_id,
                    'omzet'         => $cfg_register_fee,
                    'amount'        => $cfg_register_fee,
                    'status'        => 'register',
                    'desc'          => 'New Member',
                    'date'          => date('Y-m-d', strtotime($datetime)),
                    'calc_bonus'    => 1,
                    'datecreated'   => $datetime,
                    'datemodified'  => $datetime
                );

                if( ! $insert_member_omzet = $this->Model_Member->save_data_member_omzet($data_member_omzet) ){
                    $this->db->trans_rollback(); // Rollback Transaction
                    // Set the response and exit
                    $response['message'] = 'Pendaftaran dan order paket produk tidak berhasil. Terjadi kesalahan pada simpan data member omzet.';
                    $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
                }
            }

            // save data member omzet perdana
            $omzet_perdana      = ($total_payment - $cfg_register_fee - $code_unique);
            $data_member_omzet  = array(
                'id_member'     => $memberdata->id,
                'id_order'      => $shop_order_id,
                'omzet'         => ($total_price - $total_discount),
                'amount'        => $omzet_perdana,
                'qty'           => $total_qty,
                'point'         => $total_point,
                'status'        => 'perdana',
                'desc'          => 'Omzet Perdana ('. $invoice .')',
                'date'          => date('Y-m-d', strtotime($datetime)),
                'datecreated'   => $datetime,
                'datemodified'  => $datetime
            );

            if( ! $insert_member_omzet = $this->Model_Member->save_data_member_omzet($data_member_omzet) ){
                $this->db->trans_rollback(); // Rollback Transaction
                // Set the response and exit
                $response['message'] = 'Pendaftaran dan order paket produk tidak berhasil. Terjadi kesalahan pada simpan data member omzet.';
                $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }

        ## Order Success -------------------------------------------------------
        $this->db->trans_commit();
        $this->db->trans_complete(); //  complete database transactions  

        ddm_log_action( 'MEMBER_REG', $invoice, 'MOBILE_APP', json_encode(array('cookie'=>$_COOKIE, 'status'=>'SUCCESS', 'shop_order_id'=>$shop_order_id)) );

        // Set the response and exit
        $this->response([
            'success'       => TRUE,
            'status'        => REST_Controller::HTTP_OK,
            'shop_order_id'  => $shop_order_id,
            'message'       => 'Pendaftaran Agen dan order paket produk berhasil',
        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function applydiscount_post()
    {
        $apiKey     = ddm_member_api_key_valid($this->rest->key);
        $id_member  = isset($apiKey->id_member) ? $apiKey->id_member : 0;
        $nameKey    = isset($apiKey->name) ? $apiKey->name : '';
        $nameKey    = str_replace(' ', '_', $nameKey);

        $response       = array(
            'success'   => FALSE,
            'status'    => REST_Controller::HTTP_NOT_FOUND,
            'message'   => 'Kode Voucher tidak valid'
        );

        // Get Condition
        $voucher        = $this->post('voucher_code');
        $type           = $this->post('type');
        $product_cart   = $this->post('product_cart');

        if ( strtolower($type) == 'customer' && strtolower($nameKey) !== 'mobile_app' ) {
            $this->response([
                'success'   => false,
                'status'    => REST_Controller::HTTP_FORBIDDEN,
                'error'     => str_replace("%s", "", lang('text_rest_invalid_api_key')),
            ], REST_Controller::HTTP_FORBIDDEN); // the HTTP response code
        }

        if ( !$voucher ) {
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( !$product_cart ) {
            // Set the response and exit
            $response['message'] = 'Kode Voucher tidak valid. Silahkan periksa kembali keranjang belanjaan anda!';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }


        if ( !$getDiscount = discount_code($voucher) ) {
            // Set the response and exit
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( $getDiscount->status == 0 ) {
            // Set the response and exit
            $response['message'] = 'Kode Voucher sudah tidak tersedia';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( strtolower($type) == 'agent' ) {
            if ( $getDiscount->discount_agent == 0 ) {
                $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
            $discount_type  = $getDiscount->discount_agent_type;
            $discount       = $getDiscount->discount_agent;
        } else {
            if ( $getDiscount->discount_customer == 0 ) {
                $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
            $discount_type  = $getDiscount->discount_customer_type;
            $discount       = $getDiscount->discount_customer;
        }

        if ( !$discount ) {
            // Set the response and exit
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        // Set Data Product
        $total_qty          = 0;
        $total_price        = 0;
        $data_product       = array();
        $no=1;
        foreach ($product_cart as $item) {
            $productId      = isset($item['id']) ? $item['id'] : 0;
            $qty            = isset($item['qty']) ? $item['qty'] : 0;
            $price_cart     = isset($item['price_cart']) ? $item['price_cart'] : 0;

            if ( !$productId || !$qty ) { continue; }

            $subtotal       = ($price_cart * $qty);
            $total_qty     += $qty;
            $total_price   += $subtotal;

            if ( strtolower($type) == 'agent' ) {
                if ( ! $getPackage = ddm_product_package('id', $productId) ) { continue; }
                $productDetail  = isset($getPackage->product_details) ? $getPackage->product_details : false;
                $productDetail  = ($productDetail) ? maybe_unserialize($productDetail) : false; 
                if ( $productDetail ) {
                    foreach ($productDetail as $row) {
                        $product_id     = isset($row['id']) ? $row['id'] : 0;
                        $product_qty    = isset($row['qty']) ? $row['qty'] : 0;
                        $product_price  = isset($row['price']) ? $row['price'] : 0;
                        $product_price  = $product_price * $product_qty;
                        $subtotal       = ($product_price * $qty);

                        $data_product[$no]  = array(
                            'id'            => $product_id,
                            'price'         => $product_price,
                            'qty'           => $qty,
                            'subtotal'      => $subtotal,
                        );
                        $no++;
                    }
                }
            } else {
                if ( ! $getProduct = ddm_products($productId) ) { continue; }
                $data_product[$no]  = array(
                    'id'            => $productId,
                    'price'         => $price_cart,
                    'qty'           => $qty,
                    'subtotal'      => $subtotal,
                );
                $no++;
            }
        }

        if ( !$total_price || !$total_qty ) {
            // Set the response and exit
            $response['message'] = 'Kode Voucher tidak valid. Silahkan periksa kembali keranjang belanjaan anda!';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        $total_price_product = $total_price;
        if ( $discount_products = is_json($getDiscount->products) ) {
            $discount_products = json_decode($getDiscount->products);
        }
        if ( $discount_products && $data_product ) {
            $total_price        = 0;
            foreach ($data_product as $row) {
                $productId  = $row['id'];
                foreach ($discount_products  as $key => $product) {
                    if ( $product == $productId ) {
                        $total_price += $row['subtotal'];
                    }
                }
            }
        }

        $total_discount      = 0;
        if ( $discount_type == 'percent' ) {
            $total_discount = $total_price * ($discount / 100);
        } else {
            $total_discount = $total_price ? $discount : 0;
        }

        if ( !$total_discount ) {
            // Set the response and exit
            $response['message'] = ( $discount_products ) ? 'Kode Voucher hanya berlaku untuk produk tertentu !' : 'Kode Voucher tidak dapat digunakan.';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        // Set the response and exit
        $this->response([
            'success'       => TRUE,
            'status'        => REST_Controller::HTTP_OK,
            'message'       => 'Kode Voucher berhasil digunakan. '. ( $discount_products ? 'Anda mendapatkan diskon dari produk tertentu' : ''),
            'subtotal'      => $total_price_product,
            'discount'      => ( $discount_type == 'percent' ? ($discount+0) .'%' : ddm_accounting($total_discount) ),
            'total_discount'=> ($total_discount+0)
        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

}
