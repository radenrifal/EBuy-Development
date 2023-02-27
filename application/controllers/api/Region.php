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
class Region extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Load Shop helper 
        $this->load->helper('shop_helper');

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
    }

    public function province_post()
    {
        $id = $this->post('id');
        if ( ! $provinces = $this->Model_Address->get_provinces($id) ) {
            // Set the response and exit
            $this->response([
                'success'   => TRUE,
                'status'    => REST_Controller::HTTP_NOT_FOUND,
                'message'   => 'Data Provinsi tidak ditemukan'
            ], REST_Controller::HTTP_NOT_FOUND);
        }

        if ( $id ) {
            // Set the response and exit
            $this->response([
                'success'       => TRUE,
                'status'        => REST_Controller::HTTP_OK,
                'message'       => 'Data Provinsi ditemukan',
                'data'          => $provinces,
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            // Set the response and exit
            $this->response([
                'success'       => TRUE,
                'status'        => REST_Controller::HTTP_OK,
                'message'       => 'Data Provinsi ditemukan',
                'total_data'    => count($provinces),
                'data'          => $provinces,
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
    }

    public function district_post()
    {
        $id             = $this->post('id');
        $id_province    = $this->post('id_province');

        if ( $id_province ) {
            $districts = ddm_districts_by_province($id_province);
        } else {
            $districts = ddm_districts($id);
        }

        if ( ! $districts ) {
            // Set the response and exit
            $this->response([
                'success'   => TRUE,
                'status'    => REST_Controller::HTTP_NOT_FOUND,
                'message'   => 'Data Kota/Kabupaten tidak ditemukan'
            ], REST_Controller::HTTP_NOT_FOUND);
        }


        if ( $id ) {
            // Set the response and exit
            $this->response([
                'success'       => TRUE,
                'status'        => REST_Controller::HTTP_OK,
                'message'       => 'Data Kota/Kabupaten ditemukan',
                'data'          => $districts,
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            // Set the response and exit
            $this->response([
                'success'       => TRUE,
                'status'        => REST_Controller::HTTP_OK,
                'message'       => 'Data Kota/Kabupaten ditemukan',
                'total_data'    => count($districts),
                'data'          => $districts,
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
    }

    public function subdistrict_post()
    {
        $id             = $this->post('id');
        $id_district    = $this->post('id_district');

        if ( $id_district ) {
            $subdistricts = ddm_subdistricts_by_district($id_district);
        } else {
            $subdistricts = ddm_subdistricts($id);
        }

        if ( ! $subdistricts ) {
            // Set the response and exit
            $this->response([
                'success'   => TRUE,
                'status'    => REST_Controller::HTTP_NOT_FOUND,
                'message'   => 'Data Kecamatan tidak ditemukan'
            ], REST_Controller::HTTP_NOT_FOUND);
        }


        if ( $id ) {
            // Set the response and exit
            $this->response([
                'success'       => TRUE,
                'status'        => REST_Controller::HTTP_OK,
                'message'       => 'Data Kecamatan ditemukan',
                'data'          => $subdistricts,
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            // Set the response and exit
            $this->response([
                'success'       => TRUE,
                'status'        => REST_Controller::HTTP_OK,
                'message'       => 'Data Kecamatan ditemukan',
                'total_data'    => count($subdistricts),
                'data'          => $subdistricts,
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
    }

    public function courier_post()
    {
        $type           = sanitize($this->post('type'));
        $total_qty      = sanitize($this->post('total_qty'));
        $total_qty      = $total_qty ? $total_qty : 0;
        $free_shipping  = false;

        if ( strtolower($type) == 'agent' ) {
            $qty_free_shipping  = get_option('qty_package_free_shipping');
            $qty_free_shipping  = $qty_free_shipping ? $qty_free_shipping : 0;

            if ( $total_qty >= $qty_free_shipping ) {
                $free_shipping  = true;
            }
        }

        $couriers   = config_item('courier');
        if ( $free_shipping ) {
            $couriers   = config_item('courier_free');
        }

        if ( $couriers ) {
            // Set the response and exit
            $this->response([
                'success'       => TRUE,
                'status'        => REST_Controller::HTTP_OK,
                'message'       => 'Data Kurir ditemukan',
                'data'          => $couriers,
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            // Set the response and exit
            $this->response([
                'success'       => FALSE,
                'status'        => REST_Controller::HTTP_NOT_FOUND,
                'message'       => 'Data Kurir tidak ditemukan',
            ], REST_Controller::HTTP_NOT_FOUND); // OK (200) being the HTTP response code
        }
    }

    public function cost_post()
    {
        $city               = sanitize($this->post('city')); 
        $subdistrict        = sanitize($this->post('subdistrict'));
        $courier            = sanitize($this->post('courier'));

        $type               = sanitize($this->post('type'));
        $id_agent           = sanitize($this->post('id_agent'));

        ## Validation Global --------------------------------------------------------------
        $this->form_validation->set_rules('city', 'city', 'required');
        $this->form_validation->set_rules('subdistrict', 'subdistrict', 'required');
        $this->form_validation->set_rules('courier', 'courier', 'required');
        $this->form_validation->set_rules('type', 'type', 'required');
        $type               = strtolower($type);
        if ( $type == 'customer' ) {
            $this->form_validation->set_rules('id_agent', 'id_agent', 'required');
        }

        $response           = array(
            'success'       => FALSE,
            'status'        => REST_Controller::HTTP_NOT_FOUND,
            'message'       => 'Cek Biaya Pengiriman tidak berhasil. Silahkan periksa kembali keranjang belanjaan anda!'
        );
        
        $this->form_validation->set_error_delimiters('', ' ');
        if ($this->form_validation->run() == FALSE) {
            // Set the response and exit
            $response['message'] = validation_errors();
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( $type == 'customer' ) {
            // Check Data Agent
            $agent = ddm_get_memberdata_by_id($id_agent);
            if ( ! $agent ) {
                // Set the response and exit
                $response['message'] = 'Agen tidak valid. . Silahkan kembali ke pencarian Agen !';
                $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }

            if ( $agent->status != ACTIVE || $agent->type != MEMBER ) {
                // Set the response and exit
                $response['message'] = 'Agen tidak valid. . Silahkan kembali ke pencarian Agen !';
                $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        } else {
            if ( strtolower($courier) == 'ekspedisi') {
                $response           = array(
                    'success'       => TRUE,
                    'status'        => REST_Controller::HTTP_OK,
                    'message'       => 'Cek Biaya Pengiriman berhasil',
                    'data'          => array(
                        array(
                            'service'       => 'free',
                            'service_name'  => 'Free Shipping',
                            'courier_cost'  => 0,
                            'courier_etd'   => '',
                        )
                    )
                ); $this->response($response, REST_Controller::HTTP_OK);
            }
        }

        if ( strtolower($courier) == 'pickup') {
            $response           = array(
                'success'       => TRUE,
                'status'        => REST_Controller::HTTP_OK,
                'message'       => 'Cek Biaya Pengiriman berhasil',
                'data'          => array(
                    array(
                        'service'       => 'pickup',
                        'service_name'  => 'Paket diambil',
                        'courier_cost'  => 0,
                        'courier_etd'   => '',
                    )
                )
            ); $this->response($response, REST_Controller::HTTP_OK);
        }

        $product_cart       = $this->post('product_cart');
        if ( !$product_cart ) {
            // Set the response and exit
            $response['message'] = 'Keranjang belanjaan anda kosong. Silahkan periksa kembali keranjang belanjaan anda!';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if( empty($city) ){
            // Set the response and exit
            $response['message'] = 'Kab/Kota belum di pilih. Silahkan pilih Kab/Kota terlabih dahulu!';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if( empty($subdistrict) ){
            // Set the response and exit
            $response['message'] = 'Kecamatan belum di pilih. Silahkan pilih Kecamatan terlabih dahulu!';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if( empty($courier) ){
            // Set the response and exit
            $response['message'] = 'Kurir belum di pilih. Silahkan pilih Kurir terlabih dahulu!';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        // Calculate Total Weight
        $free_shipping      = false;
        $total_weight       = 0;
        $total_qty          = 0;
        if ( $product_cart ) {
            foreach ($product_cart as $key => $item) {
                $product_id     = isset($item['id']) ? $item['id'] : 0;
                $qty            = isset($item['qty']) ? $item['qty'] : 0;

                if ( $type == 'customer' ) {
                    if ( ! $get_product = ddm_products($product_id) ) { continue; }
                    $qty_free_shipping  = $get_product->qty_free_shipping;
                    if ( $qty_free_shipping > 0 && $qty >= $qty_free_shipping) {
                        $free_shipping  = true;
                    }

                } else {
                    if ( ! $get_product = ddm_product_package('id', $product_id) ) { continue; }
                }

                $product_weight     = $get_product->weight;
                $weight             = $product_weight * $qty;
                $total_weight      += $weight;
                $total_qty         += $qty;

            }
        }

        $response['total_weight']   = $total_weight;
        $response['total_qty']      = $total_qty;

        if( empty($total_weight) ){
            $response['message'] = 'Terjadi kesalahan pada sistem pengiriman. Total Berat null';
            $this->response($response, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }

        if ( $type == 'customer' ) {
            if ( $free_shipping ) {
                $total_weight   = 1;
            }
            
            $origin             = $agent->district; // kota asal pengirim
            $origin_type        = 'city'; // type
        } else {
            $qty_free_shipping  = get_option('qty_package_free_shipping');
            $qty_free_shipping  = $qty_free_shipping ? $qty_free_shipping : 0;
            if ( $qty_free_shipping > 0 && $total_qty >= $qty_free_shipping ) {
                $total_weight   = 1;
            }

            $origin             = config_item('rajaongkir_origin'); // kota asal pengirim
            $origin_type        = 'city'; // type
        }

        $shipping_fee  = ddm_shipping_fee($origin, $city, $subdistrict, $total_weight, $courier, $origin_type);
        if ( !$shipping_fee  ) {
            $response['message'] = 'Data Layanan Kurir tidak ditemukan. Alamat tidak mendukung untuk pengiriman. Silahkan Pilih Kurir lainnya !';
            $this->response($response, REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }

        $status_shipping    = isset($shipping_fee['status']) ? $shipping_fee['status'] : false;
        if ( !$status_shipping ) {
            $response['message'] = 'Data Layanan Kurir tidak ditemukan. Alamat tidak mendukung untuk pengiriman. Silahkan Pilih Kurir lainnya !';
            $this->response($response, REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }

        $courier_services   = isset($shipping_fee['data']) ? $shipping_fee['data'] : array();
        if ( ! $courier_services ) {
            $response['message'] = 'Data Layanan Kurir tidak ditemukan. Alamat tidak mendukung untuk pengiriman. Silahkan Pilih Kurir lainnya !';
            $this->response($response, REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }

        $courier_service    = array();
        foreach ($courier_services as $key => $row) {
            $service_name   = $row->service.' - '. $row->description;
            $ongkir         = isset($row->cost[0]->value) ? $row->cost[0]->value : 'failed';
            $day            = isset($row->cost[0]->etd) ? $row->cost[0]->etd : '';
            if ( $ongkir == 'failed' ) { continue; }

            if ( strtolower($courier) != 'pos' ) {
                $day .= ' Hari';
            }

            if ( $free_shipping ) { 
                $ongkir = 0;
                $service_name .= ' (Free ongkir)';
            }

            $courier_service[]  = array(
                'service'       => $row->service,
                'service_name'  => $service_name,
                'courier_cost'  => $ongkir,
                'courier_etd'   => $day,
            );
        }

        // Set the response and exit
        $response['success']    = TRUE;
        $response['status']     = REST_Controller::HTTP_OK;
        $response['message']    = 'Cek Biaya Pengiriman berhasil';
        $response['data']       = $courier_service;
        $this->response($response, REST_Controller::HTTP_OK);
    }

}
