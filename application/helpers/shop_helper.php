<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Get Product Data 
| @param = price, qty, etc..
|--------------------------------------------------------------------------
*/
function shop_product($id, $provincearea = 1) //default 1 aja
{
    if ( !$id ) { return false; }

    $CI = &get_instance();

    if ( $product = ddm_products($id, false) ) {
        $price      = ( is_logged_in() ) ? $product->{"price_agent".$provincearea} : $product->{"price_customer".$provincearea};
        $min_qty    = ( is_logged_in() ) ? $product->discount_agent_min : $product->discount_customer_min;
        $discount   = ( is_logged_in() ) ? $product->discount_agent : $product->discount_customer;
        $disctype   = ( is_logged_in() ) ? $product->discount_agent_type : $product->discount_customer_type;
        $min_order  = ( is_logged_in() ) ? $product->min_order : 1;

        $product->min_order_agent   = $product->min_order;
        $product->min_order         = $min_order;
        $product->price             = $price;
        $product->min_qty           = $min_qty;
        $product->discount          = $discount;
        $product->discount_type     = $disctype;

        return $product;
    } else {
        return false;
    }
}
/*
|--------------------------------------------------------------------------
| Get Product Package Data 
| @param = price, qty, etc..
|--------------------------------------------------------------------------
*/
function shop_product_package($limit=0, $offset=0, $conditions='', $order_by='')
{
    $CI = &get_instance();
    if ( $data = $CI->Model_Product->get_all_product_package($limit, $offset, $conditions, $order_by) ) {
        $total_data = ddm_get_last_found_rows();
        return array('data' => $data, 'total_row' => $total_data);
    } else {
        return false;
    }
}

/*
|--------------------------------------------------------------------------
| Get Product Data 
| @param = price, qty, etc..
|--------------------------------------------------------------------------
*/
function data_product($id, $param)
{
    if ( !$id && ! $param ) { return false; }

    $CI = &get_instance();

    if ( $product = ddm_products($id, false) ) {
        if ( $param == 'price' ) {
            if ( is_logged_in() ) {
                $param = 'price_agent';
            } else {
                $param = 'price_customer';
            }
        }
        return $product->$param;
    } else {
        return 'Produk tidak ditemukan';
    }
}

/*
|--------------------------------------------------------------------------
| Get Product Data 
| @param = price, qty, etc..
|--------------------------------------------------------------------------
*/
function shop_search_product($limit = 0, $start = 0, $condition = '', $order_by = '')
{
    $CI = &get_instance();

    if ( $data = $CI->Model_Product->get_all_product($limit, $start, $condition, $order_by) ) {
        $total_data = ddm_get_last_found_rows();
        return array('data' => $data, 'total_row' => $total_data);
    }
    return false;
}

/*
|--------------------------------------------------------------------------
| Get Category Name By ID
|--------------------------------------------------------------------------
*/
if (!function_exists('shop_category')) {
    function shop_category($id, $val)
    {
        $CI = &get_instance();
        $query = ddm_product_category($id, false);

        if ($query) {
            $category = isset($query->$val) ? $query->$val : false;
            return $category;
        } else {
            return 'No Category Found';
        }
    }
}

/*
|--------------------------------------------------------------------------
| Get Product Proce
|--------------------------------------------------------------------------
*/
if (!function_exists('product_price')) {
    function product_price($product)
    {
        if ( ! $product ) { return 0; }
        if ( ! isset($product->price_agent) && ! isset($product->price_customer)) { return 0; }
        $price = ( is_logged_in() ) ? $product->price_agent : $product->price_customer;
        return $price;
    }
}

/*
|--------------------------------------------------------------------------
| Get Product Proce
|--------------------------------------------------------------------------
*/
if (!function_exists('product_discount')) {
    function product_discount($product)
    {
        if ( ! $product ) { return 0; }
        if ( ! isset($product->price_agent) && ! isset($product->price_customer)) { return 0; }
        if ( ! isset($product->discount_agent) && ! isset($product->discount_agent)) { return 0; }
        if ( ! isset($product->discount_agent_min) && ! isset($product->discount_agent_min)) { return 0; }
        if ( ! isset($product->discount_agent_type) && ! isset($product->discount_agent_type)) { return 0; }

        $promo      = '';
        $currency   = config_item('currency');
        $price      = ( is_logged_in() ) ? $product->price_agent : $product->price_customer;
        $discount   = ( is_logged_in() ) ? $product->discount_agent : $product->discount_customer;
        $min_qty    = ( is_logged_in() ) ? $product->discount_agent_min : $product->discount_customer_min;
        $disctype   = ( is_logged_in() ) ? $product->discount_agent_type : $product->discount_customer_type;

        if ( $min_qty <= 1 && $discount > 0 ) {
            if ( $disctype == 'percent' ) {
                $promo = $discount .' %';
            } else {
                $promo = ddm_accounting($discount, $currency);
            }
        }

        return $promo;
    }
}

/*
|--------------------------------------------------------------------------
| Get Product Iamge
|--------------------------------------------------------------------------
*/
if (!function_exists('product_image')) {
    function product_image($image, $thumbnail = true )
    {
        $CI = &get_instance();

        $no_image = ( DOMAIN_DEV == true ? ASSET_PATH_LIVE : ASSET_PATH ) . 'backend/img/no_image.jpg';
        if ( $image ) {
            $thumb_path = $thumbnail ? 'thumbnail/' : '';
            $img_src    = PRODUCT_IMG_PATH . $thumb_path . $image;
            if ( file_exists($img_src) ) {
                $img_src = PRODUCT_IMG . $thumb_path . $image;
            } else {
                $img_src = $no_image; 
            }
            return $img_src;
        } else {
            return $no_image;
        }
    }
}

/*
|--------------------------------------------------------------------------
| Checking stock product availability
| With $qty = Check stock | Available / Not
| Without $qty = Display current stock
|--------------------------------------------------------------------------
*/
if (!function_exists('stock_availability')) {
    function stock_availability($id, $qty = '') {
        $CI = &get_instance();
        if ( $product = ddm_products($id, false) ) {
            if ($qty) {
                if ($qty > $product->stock) {
                    $result = array(
                        'status'    => 'failed',
                        'message'   => 'Maaf produk yang Anda order melebihi jumlah stok kami. Saat ini stok kami berjumlah ' . $product->stock,
                        'stock'     => $product->stock,
                    );
                    return $result;
                } else {
                    $result = array(
                        'status'    => 'success',
                        'message'   => 'On Stock',
                        'stock'     => $product->stock,
                    );
                    return $result;
                }
            } else {
                $result = array(
                    'stock' => $product->stock,
                );
                return $result;
            }
        }
    }
}

/*
|--------------------------------------------------------------------------
| Checking stock product availability
| With $qty = Check stock | Available / Not
| Without $qty = Display current stock
|--------------------------------------------------------------------------
*/
if (!function_exists('stock_product_order')) {
    function stock_product_order($id, $qty = '') {
        $CI = &get_instance();
        if ( $product = ddm_products($id, false) ) {
            if ($qty) {
                if ( $auth = auth_redirect( true ) ) {
                    $status         = 'success';
                    $message        = 'On Stock';
                    $stock_order    = $qty;
                    $stock_mod      = $qty % $product->min_order;
                    if ( $stock_mod ) {
                        $status         = 'failed';
                        $message        = 'Qty produk harus sesuai dengan syarat minimal order agen (kelipatan '.$product->min_order.' pcs)';
                        $stock_order    = $qty - $stock_mod;
                    }
                    $result = array(
                        'status'    => $status,
                        'stock'     => $stock_order,
                        'message'   => $message,
                    ); return $result;   
                } else {
                    $result = array(
                        'status'    => 'success',
                        'message'   => 'On Stock',
                        'stock'     => $qty,
                    ); return $result;                   
                }
            } else {
                $result = array(
                    'stock' => $product->stock,
                );
                return $result;
            }
        }
    }
}

/*
|--------------------------------------------------------------------------
| Get Discount Code
|--------------------------------------------------------------------------
*/
if (!function_exists('discount_code')) {
    function discount_code($code)
    {
        if ( ! $code ) { return false; }
        $CI = &get_instance();
        $discount = $CI->Model_Option->get_promo_code_by('promo_code', $code);
        return $discount;
    }
}

/*
|--------------------------------------------------------------------------
| Check Agent
|--------------------------------------------------------------------------
*/
if (!function_exists('check_agent')) {
    function check_agent($remove_seller = false)
    {
        $CI = &get_instance();
        $status     = true;
        $idSeller   = $CI->session->userdata('seller_ref_id');
        if ( ! $idSeller ) {
            return false;
        }

        if ( ! $seller = ddm_get_memberdata_by_id($idSeller) ) {
            if ( $remove_seller ) {
                remove_code_seller();
            }
            return false;
        }

        if ( $seller->status != ACTIVE || $seller->type != MEMBER ) {
            if ( $remove_seller ) {
                remove_code_seller();
            }
            return false;
        }

        $usernameSeller = $CI->session->userdata('seller_ref_username');
        if ( $usernameSeller ) {
            if ( strtolower($usernameSeller) !== strtolower($seller->username)) {
                if ( $remove_seller ) {
                    remove_code_seller();
                }
                return false;
            }
        }

        $seller = ddm_unset_clone_member_data( $seller );
        return $seller;
    }
}


/*
|--------------------------------------------------------------------------
| Status Order
|--------------------------------------------------------------------------
*/
function status_order($id_order, $status, $type_order = 'agent', $sent = false)
{
    $CI = &get_instance();
    $condition  = array('type' => 'shop');
    if ( $type_order == 'agent' ) {
        $paymentEvidence = $CI->Model_Shop->get_payment_evidence_by('id_source', $id_order, $condition, 1);
    } else {
        $paymentEvidence = false;
    }

    if ($status == 0 && !$paymentEvidence) {
        return 'Menunggu Pembayaran';
    } else if ($status == 0 && $paymentEvidence) {
        return 'Pembayaran berhasil. Pesanan dalam proses.';
    } else if ($status == 1) {
        if ( $sent ) {
            return 'Pesanan sudah dikirim';
        } else {
            return 'Pembayaran Berhasil dikonfirmasi';
        }
    } else if ($status == 2) {
        return 'Dibatalkan';
    } else if ($status == 3) {
        return 'Selesai';
    }
}

/*
|--------------------------------------------------------------------------
| SUM of cart options
| ex : sum_cart_option('weight') = GET Total Weight
|--------------------------------------------------------------------------
*/
function sum_cart_option($value)
{
    $CI = &get_instance();

    $total_result = 0;
    if ($CI->cart->contents()) {
        foreach ($CI->cart->contents() as $total) {
            $total_result += $total['options'][$value];
        }
        return $total_result;
    } else {
        echo 'No cart data';
    }
}

/*
|--------------------------------------------------------------------------
| Get Total With Promo Applied
| Param = discount / amount
|--------------------------------------------------------------------------
*/
function total_promo($param)
{
    $CI = &get_instance();

    $auth           = auth_redirect( true );
    $promo_amount   = $CI->session->userdata('promo_amount');
    $promo_type     = $CI->session->userdata('promo_type');
    $promo_product  = $CI->session->userdata('promo_product');
    $total          = $CI->cart->total();

    $current_member         = ddm_get_current_member();
    $memberdata             = $current_member;
    $is_admin               = as_administrator($current_member);
    $datetime               = date('Y-m-d H:i:s');
    $provincedata           = ddm_provinces($memberdata->province);
    $provincearea           = $provincedata->province_area;

    //print_r($promo_type);die;

    // return total discount
    if ( $param == 'discount' ) {
        if ($promo_type == 'nominal') {
            $new = $promo_amount;
        } else {
            $promo_product = true;
            if ( $promo_product ) {
                $total_price = 0;
                $total_qty = 0;
                if ($CI->cart->contents()) {
                    foreach ($CI->cart->contents() as $item) {
                        $productId  = $item['id'];
                        $qty        = $item['qty'];
                        $price      = $item['price'];
                        $subtotal   = $price * $qty;
                        $total_price += ($subtotal);
                        $total_qty  += $qty;
                    }
                }
                
                $new = $total_price * 0;
                
                $discount_percent  = config_item('discount_percent');
                if($total_qty >= 15 && $total_qty <= 1049){
                    $discount_percent = $discount_percent[20];
                    $promo_amount = $discount_percent;
                     $new = $total_price * ($promo_amount / 100);
                }elseif($total_qty >= 1050){
                    $promo_amount = $discount_percent;
                    $new = $total_price * ($promo_amount / 100);
                }
            } else {
                $new = $total * ($promo_amount / 100);
            }
        }
    }

    // return total cart - total discount
    if ( $param == 'amount' ) {
        if ( $promo_type == 'nominal' ) {
            $new = $total - $promo_amount;
        } else {
            if ($CI->cart->contents()) {
                $total_price    = 0;
                $total_qty      = 0;
                foreach ($CI->cart->contents() as $item) {
                    $productId  = $item['id'];
                    $qty        = $item['qty'];
                    $price      = $item['price'];
                    $subtotal   = $price * $qty;
                    $total_price += ($subtotal);
                    $total_qty += $qty;
                }
            }
            $discount_percent  = config_item('discount_percent');
            $discount_amount   = 0;
            if($total_qty >= 15 && $total_qty <= 1049){
                $discount_percent = $discount_percent[20];
                $promo_amount = $discount_percent;
                $discount_amount  = ($total * ($promo_amount / 100));
            }elseif($total_qty >= 1050){
                $promo_amount = $discount_percent;
                $discount_percent = $discount_percent[40];
                $discount_amount  = ($total * ($promo_amount / 100));
            }

            $new = $total - $discount_amount;
        }
    }

    return ceil($new);
}

/*
|--------------------------------------------------------------------------
| Apply Code Promo
|--------------------------------------------------------------------------
*/
function apply_code_discount($owner, $code, $type, $amount, $products = '')
{
    $CI = &get_instance();

    // Unset previous Session
    remove_code_discount();

    // Set SESSION
    $promo_session = array(
        'promo_applied' => TRUE,
        'promo_owner'   => $owner,
        'promo_code'    => $code,
        'promo_type'    => $type,
        'promo_product' => $products,
        'promo_amount'  => $amount,
    );
    $CI->session->set_userdata($promo_session);
}

/*
|--------------------------------------------------------------------------
| Remove Code Promo
|--------------------------------------------------------------------------
*/
function remove_code_discount()
{
    $CI = &get_instance();

    $promo_session = array(
        'promo_applied',
        'promo_owner',
        'promo_code',
        'promo_type',
        'promo_product',
        'promo_amount'
    );

    $CI->session->unset_userdata($promo_session);
}

/*
|--------------------------------------------------------------------------
| Apply Code Promo
|--------------------------------------------------------------------------
*/
function apply_code_seller($code)
{
    $CI = &get_instance();

    if ( !is_logged_in() ) {
        // Unset previous Session
        remove_code_seller();

        $conditions = array('type' => MEMBER, 'status' => 1);
        $sellerCode = $CI->Model_Member->get_member_by('login', $code, $conditions);
        if ($sellerCode) {
            // Set SESSION
            $session = array(
                'seller_ref_applied'    => TRUE,
                'seller_ref_username'   => $sellerCode->username,
                'seller_ref_id'         => $sellerCode->id,
            );
            return $CI->session->set_userdata($session);
        }

        return FALSE;
    }
}


/*
|--------------------------------------------------------------------------
| Remove Code Seller
|--------------------------------------------------------------------------
*/
function remove_code_seller()
{
    $CI = &get_instance();

    $session = array(
        'seller_ref_applied',
        'seller_ref_username',
        'seller_ref_id',
    );

    $CI->session->unset_userdata($session);
}

if ( !function_exists('ddm_shipping_fee') )
{
    /**
     * Get Shipping Fee data
     * @author  Yuda
     * @param   Int         $origin         (Required)  ID City Of Origin
     * @param   Int         $id_city        (Required)  ID City Of Destination
     * @param   Int         $id_district    (Required)  ID District/Subdistrict Of Destination
     * @param   Int         $weight         (Required)  Total Weight Of Product
     * @param   Varchar     $courier        (Required)  Courier
     * @return  Shipping Fee data
     */
    function ddm_shipping_fee($origin, $id_city, $id_district = 0, $weight, $courier = 'jne', $origin_type = '') {
        $CI =& get_instance();

        if ( !is_numeric($origin) ) return false;
        if ( !is_numeric($id_city) ) return false;
        if ( !is_numeric($weight) ) return false;
        if ( !$courier ) return false;

        $shipping_active    = config_item('rajaongkir_active');
        $token              = config_item('rajaongkir_token');
        $url                = config_item('rajaongkir_url');

        if ( !$shipping_active ) return false;

        $destination        = $id_district ? $id_district : $id_city;
        $destinationType    = $id_district ? 'subdistrict' : 'city';
        $originType         = $origin_type ? $origin_type : 'city';

        $post_params            = array(
            'origin'            => $origin,
            'originType'        => $originType,
            'destination'       => $destination,
            'destinationType'   => $destinationType,
            'weight'            => $weight,
            'courier'           => $courier,
        );

        $postfield  = http_build_query($post_params);
        $curl       = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL             => $url .'cost',
            CURLOPT_RETURNTRANSFER  => true,           
            CURLOPT_ENCODING        => "",
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 30,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST   => "POST",
            CURLOPT_POSTFIELDS      => $postfield,
            CURLOPT_HTTPHEADER      => array(
                "content-type: application/x-www-form-urlencoded",
                "key: " . $token
            )
        ));

        if (DOMAIN_DEV) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        }

        $response = curl_exec($curl);
        $err      = curl_error($curl);
        curl_close($curl);

        if ( $err ) return false;

        $response       = ( is_json($response) ? json_decode($response) : false );
        $rajaongkir     = isset($response->rajaongkir) ? $response->rajaongkir : false;
        $status         = isset($rajaongkir->status) ? $rajaongkir->status : false;
        $status_code    = isset($status->code) ? $status->code : false;
        $description    = isset($status->description) ? $status->description : '';
        
        if ( $status_code == 400 ) {
            $message    = str_replace('Weight', 'Berat', $description);
            return array( 'status' => false, 'data' => $message );
        }

        if ( $status_code == 200 ) {
            $results        = isset($rajaongkir->results) ? $rajaongkir->results : false;
            $origin         = isset($rajaongkir->origin_details) ? $rajaongkir->origin_details : false;
            $destination    = isset($rajaongkir->destination_details) ? $rajaongkir->destination_details : false;
            $costs          = isset($results[0]->costs) ? $results[0]->costs : false;
            
            if ( $costs ) {
                return array( 'status' => true, 'data' => $costs );
            }
        }

        return false;
    }
}


/*
|--------------------------------------------------------------------------
| Generate Customer Invoice Shop
|--------------------------------------------------------------------------
*/
function generate_customer_invoice($id_member)
{
    if ( !$id_member ) { return false; }
    $CI = &get_instance();

    $sql = 'SELECT username, unique_customer_invoice AS value FROM ' . TBL_USERS . ' WHERE id = ? FOR UPDATE';
    $qry = $CI->db->query($sql, array($id_member));
    if(!$qry || !$qry->num_rows()) return false;
    $row = $qry->row();

    $invoice_prefix = config_item('invoice_prefix');
    $number         = intval($row->value);
    $unique_number  = str_pad($number + 1, 8, '0', STR_PAD_LEFT);
    $invoice        = $invoice_prefix . strtoupper($row->username) .'/'. $unique_number; // XX/username/000001

    if($unique_number == 99999999) {
        $sql_update = 'UPDATE ' . TBL_USERS . ' SET unique_customer_invoice = ? WHERE id = ?';
        $no_update  = 0;
    } else {
        $no_update  = $number + 1;
        $sql_update = 'UPDATE ' . TBL_USERS . ' SET unique_customer_invoice = ? WHERE id = ?';
    }

    $CI->db->query($sql_update, array($no_update, $id_member));
    return $invoice;
}

/*
|--------------------------------------------------------------------------
| Generate Invoice Shop
|--------------------------------------------------------------------------
*/
function generate_invoice()
{
    $CI = &get_instance();
    $invoice_prefix = config_item('invoice_prefix');
    $invoice_number = ddm_generate_invoice();
    $invoice        = $invoice_prefix . $invoice_number; // XX-000001
    return $invoice;
}

/*
|--------------------------------------------------------------------------
| Generate Receipt Shop
|--------------------------------------------------------------------------
*/
function generate_receipt()
{
    $CI = &get_instance();
    $receipt_prefix = config_item('receipt_prefix');
    $receipt_number = ddm_generate_receipt();
    $receipt        = $receipt_prefix . $receipt_number; // XX-000001
    return $receipt;
}

/*
|--------------------------------------------------------------------------
| Generate Code Unique Shop Payment
|--------------------------------------------------------------------------
*/
function generate_uniquecode()
{
    $CI = &get_instance();
    $uniquecode = ddm_generate_shop_order();
    return $uniquecode;
}

/*
|--------------------------------------------------------------------------
| Formatting date to indonesia month
| return dateonly / datetime
|--------------------------------------------------------------------------
*/
function date_indo($date, $format)
{
    $date_format = date("Y-m-j-H-i", strtotime($date));
    $month = array(
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $split = explode('-', $date_format);
    // print_r($split);
    // die;
    if ($format == 'dateonly') {
        return $split[2] . ' ' . $month[(int) $split[1]] . ' ' . $split[0];
    } else if ($format == 'datetime') {
        return $split[2] . ' ' . $month[(int) $split[1]] . ' ' . $split[0] . ' ' . $split[3] . ':' . $split[4] . ' WIB';
    }
}
