<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Frontend Controller.
 * 
 * @class     Frontend
 * @author    Yuda
 * @version   1.0.0
 */
class Shop extends Public_Controller {
    /**
	 * Constructor.
	 */
    function __construct()
    {       
        parent::__construct();
        $this->load->model('Model_backend');
        $this->load->helper('shop_helper');
    }

    /*
    |--------------------------------------------------------------------------
    | Shop
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Our Products Page
    |--------------------------------------------------------------------------
    */
    public function pageProducts()
    {
        $content            = 'pages/product/list_agent';
        if ($member = auth_redirect(true)) {
            $member         = ddm_get_current_member();
            $content        = 'pages/product/list_agent';
        }

        $data['page']       = 'Our Products';
        $data['title']      = COMPANY_NAME . ' | ' . $data['page'];
        $data['content']    = $content;
        $data['member']     = $member;
        
        if ( $carabiner = config_item('cfg_carabiner') ) {
            $js = array(
                array(BE_JS_PATH . 'pages/shop/cart.js?ver=' . JS_VER_PAGE),
                array(BE_JS_PATH . 'pages/shop/list.js?ver=' . JS_VER_PAGE),
            );
            $this->carabiner->group('custom_js', array('js' => $js));
        }else{
            $data['scripts']    = array(
                BE_JS_PATH . 'pages/shop/cart.js?ver=' . JS_VER_PAGE,
                BE_JS_PATH . 'pages/shop/list.js?ver=' . JS_VER_PAGE,
            );
        }
        $this->load->view(VIEW_SHOP . 'template', $data);
    }

    /*
    |--------------------------------------------------------------------------
    | About us Page
    |--------------------------------------------------------------------------
    */
    public function pageAboutUs()
    {
        if ($member = auth_redirect(true)) {
            $member         = ddm_get_current_member();
        }

        $data['page']       = 'About Us';
        $data['title']      = COMPANY_NAME . ' | ' . $data['page'];
        $data['content']    = 'pages/about_us';
        $data['member']     = $member;

        $this->load->view(VIEW_SHOP . 'template', $data);
    }
    
    /*
    |--------------------------------------------------------------------------
    | Check Order Page
    |--------------------------------------------------------------------------
    */
    public function pageCheckOrder()
    {
        if ($member = auth_redirect(true)) {
            $member = ddm_get_current_member();
        }

        $getInvoice = $this->input->get('invoice');
        $getEmail   = $this->input->get('email');
        $condition  = array('email' => $getEmail);
        $checkOrder = explode('/', $getInvoice);

        if (count($checkOrder) > 2) {
            $getOrder = $this->Model_Shop->get_shop_order_customer_by('invoice', $getInvoice, $condition);
            $orderBy  = 'customer';
        } else {
            $getOrder = $this->Model_Shop->get_shop_order_by('invoice', $getInvoice, $condition);
            $orderBy  = 'agent';
        }

        $data['title']       = COMPANY_NAME . ' | Check My Order';
        $data['content']     = 'pages/shop/check_order';
        $data['member']      = $member;
        $data['id_order']    = ($getOrder) ? $getOrder->id : '';
        $data['get_invoice'] = $getInvoice;
        $data['get_email']   = $getEmail;
        $data['scripts']     = '';
        $data['order_by']    = $orderBy;

        $this->load->view(VIEW_SHOP . 'template', $data);
    }

    /*
    |--------------------------------------------------------------------------
    | Product Detail
    |--------------------------------------------------------------------------
    */
    public function pageProductDetail($slug)
    {
        if ($member = auth_redirect(true)) {
            $member         = ddm_get_current_member();
        }

        // get product by slug
        if (!$member) {
            // apply code seller
            $code = $this->input->get('ref');
            apply_code_seller($code);
        }

        $content        = 'pages/product/detail';
        $condition      = array('status' => 1);
        $productDetail  = ddm_product_by('slug', $slug, $condition, 1);

        if ($productDetail) {
            $pageID     = 'product';
            $imgpath    = PRODUCT_IMG;
            $img_src    = product_image($productDetail->image);

            $data['title']          = ucwords($productDetail->name);
            $data['product_detail'] = $productDetail;
            $data['content']        = $content;
            $data['member']         = $member;
            $data['breadcrumb']     = ucwords($productDetail->name);
            $data['imgpath']        = $imgpath;
            $data['pageID']         = $pageID;
            $data['metaDesc']       = sanitize(substr($data['product_detail']->description, 0, 100));

            $data['meta'] = '
                <meta property="og:image" content="' . $img_src . '"/>  
                <meta property="og:title" content="' . ucwords($data['product_detail']->name) . '"/>  
                <meta property="og:description" content="' . sanitize(substr($data['product_detail']->description, 0, 100)) . '"/>  
                <meta name="twitter:card" content="summary" />
                <meta name="twitter:site" content="' . COMPANY_NAME . '" />
                <meta name="twitter:title" content="' . ucwords($data['product_detail']->name) . '" />
                <meta name="twitter:description" content="' . sanitize(substr($data['product_detail']->description, 0, 100)) . '" />
                <meta name="twitter:image" content="' . $img_src . '" />
            ';

            if ( $carabiner = config_item('cfg_carabiner') ) {
                $js = array(
                    array(BE_JS_PATH . 'pages/shop/cart.js?ver=' . JS_VER_PAGE),
                );
                $this->carabiner->group('custom_js', array('js' => $js));
            }else{
                $data['scripts']    = array(
                    BE_JS_PATH . 'pages/shop/cart.js?ver=' . JS_VER_PAGE,
                );
            }
            $this->load->view(VIEW_SHOP . 'template', $data);
        } else {
            $this->load->view('errors/not_found'); // Error page
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Package Product Detail
    |--------------------------------------------------------------------------
    */
    public function pagePackageProductDetail($slug)
    {
        if (!$auth = auth_redirect(true)) {
            redirect(base_url('shop'), 'refresh');
        }

        $member         = ddm_get_current_member();
        // get product by slug
        $content        = 'pages/product/detail_agent';
        $condition      = array('status' => 1);
        $productDetail  = ddm_product_package('slug', $slug, $condition, 1);

        if ($productDetail) {
            $pageID     = 'product';
            $imgpath    = PRODUCT_IMG;
            $img_src    = product_image($productDetail->image);

            $data['title']          = ucwords($productDetail->name);
            $data['product_detail'] = $productDetail;
            $data['content']        = $content;
            $data['member']         = $member;
            $data['breadcrumb']     = ucwords($productDetail->name);
            $data['imgpath']        = $imgpath;
            $data['pageID']         = $pageID;
            $data['metaDesc']       = sanitize(substr($data['product_detail']->description, 0, 100));

            $data['meta'] = '
                <meta property="og:image" content="' . $img_src . '"/>  
                <meta property="og:title" content="' . ucwords($data['product_detail']->name) . '"/>  
                <meta property="og:description" content="' . sanitize(substr($data['product_detail']->description, 0, 100)) . '"/>  
                <meta name="twitter:card" content="summary" />
                <meta name="twitter:site" content="' . COMPANY_NAME . '" />
                <meta name="twitter:title" content="' . ucwords($data['product_detail']->name) . '" />
                <meta name="twitter:description" content="' . sanitize(substr($data['product_detail']->description, 0, 100)) . '" />
                <meta name="twitter:image" content="' . $img_src . '" />
            ';

            $js = array(
                array(BE_JS_PATH . 'pages/shop/cart.js?ver=' . JS_VER_PAGE),
            );
            $this->carabiner->group('custom_js', array('js' => $js));

            $this->load->view(VIEW_SHOP . 'template', $data);
        } else {
            $this->load->view('errors/not_found'); // Error page
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Search Products Page
    |--------------------------------------------------------------------------
    */
    public function pageSearchProduct()
    {
        $content            = 'pages/product/list';
        if ($member = auth_redirect(true)) {
            $member         = ddm_get_current_member();
            $content        = 'pages/product/list_agent';
        }
        $data['page']       = 'Search Products';
        $data['title']      = COMPANY_NAME . ' | ' . $data['page'];
        $data['content']    = $content;
        $data['member']     = $member;

        $js = array(
            array(BE_JS_PATH . 'pages/shop/cart.js?ver=' . JS_VER_PAGE),
            array(BE_JS_PATH . 'pages/shop/list.js?ver=' . JS_VER_PAGE),
        );
        $this->carabiner->group('custom_js', array('js' => $js));

        $this->load->view(VIEW_SHOP . 'template', $data);
    }
    
    /*
    |--------------------------------------------------------------------------
    | List Products Page
    |--------------------------------------------------------------------------
    */
    function getProducts($result = '', $totalRow = '')
    {
        $auth       = auth_redirect(true);
        $limit      = $this->input->post('limit');
        $start      = $this->input->post('start');

        /*
        if (empty($auth)) {
            $response = array(
                'status'  => 'failed',
                'message' => 'Please login your account',
            );
            die(json_encode($response));
        }
        */

        $output1    = '';
        $output2    = '';

        $pageID     = 'product';
        $imgpath    = PRODUCT_IMG . 'thumbnail/';

        if ($result) {
            $data = isset($result['data']) ? $result['data'] : '';
        } 
        else {
            $condition  = ' AND %status% = 1';
            $totalRow   = 0;
            $data       = false;

            //if ($auth) {
                //$get_products = shop_product_package($limit, $start, $condition, '%datemodified% DESC');
                //$get_products = shop_search_product($limit, $start, $condition, '%datemodified% DESC');
            //} else {
                $get_products = shop_search_product($limit, $start, $condition, '%datemodified% DESC');
            //}

            if ($get_products) {
                $totalRow   = isset($get_products['total_row']) ? $get_products['total_row'] : 0;
                $data       = isset($get_products['data']) ? $get_products['data'] : false;
            }
        }

        if ($data) {
            foreach ($data as $row) {
                //if ($auth) {
                    //$setHTML = $this->sethtmlagentlistproduct($row);
                //} else {
                    //$setHTML = $this->sethtmlcustomerlistproduct($row);
                    $setHTML = $this->sethtmlagentlistproduct($row);
                //}

                $output1 .= isset($setHTML['output1']) ? $setHTML['output1'] : '';
                $output2 .= isset($setHTML['output2']) ? $setHTML['output2'] : '';
            }
        } else {
            $response = array(
                'status'  => 'failed',
                'message' => 'Data not found',
            );
            die(json_encode($response));
        }

        $response = array(
            'status'    => 'success',
            'data1'     => $output1,
            'data2'     => $output2,
            'total'     => $totalRow,
        );

        die(json_encode($response));
    }
    
    /**
     * Set HTML List Product Package Agent Data function.
     */
    private function sethtmlagentlistproduct($data)
    {
        $member         = ddm_get_current_member();
        $provincearea   = 1;
        if(!empty($member)){
            $provincedata   = ddm_provinces($member->province);
            $provincearea   = $provincedata->province_area;
        }

        // Product already in cart
        $in_cart = FALSE;
        foreach ($this->cart->contents() as $item) {
            if ($item['id'] == $data->id) {
                $in_cart = TRUE;
            }
        }

        if (!$in_cart) {
            if ($data->stock == 0) {
                $btnCart = '<a class="ps-btn" href="javascript:;">Stok Kosong</a>';
            } else {
                $btnCart = '<div class="ps-btn addCart" data-id="' . ddm_encrypt($data->id) . '" data-qty="1" data-type="addcart" style="padding: 7px;width: 100%;border-radius: 25px;">Add Cart</div>';
            }
        } else {
            $btnCart = '<a class="ps-btn btn-gocart" href="' . base_url('cart') . '" >Go to cart</a>';
        }

        $img_src    = product_image($data->image);
        $imgPath    = '<img class="img-fluid" src="' . $img_src . '">';
        $price      = ddm_accounting($data->{"price_agent" . $provincearea}, "Rp");

        $output1    = '
        <div class="col-padding col-md-3 col-6 wow fadeIn">
            <div class="ps-product">
                <div class="ps-product__thumbnail">
                    <a href="' . base_url('product/detail/' . $data->slug) . '">
                    ' . $imgPath . '
                    </a>
                </div>
                <div class="ps-product__container desktop">
                    <div class="ps-product__content">
                        <a class="ps-product__title" href="' . base_url('product/detail/' . $data->slug) . '">
                            <span class="text-capitalize">' . $data->name . '</span>
                        </a>
                        <p class="ps-product__price sale">' . $price . '</p>
                        <div class="text-center pt-2 pb-3">
                        ' . $btnCart . '
                        </div>
                    </div>
                </div>
            </div>
        </div>';

        $output2    = '
        <div class="ps-product ps-product--wide wow fadeIn">
            <div class="ps-product__thumbnail">
                <a href="' . base_url('product/detail/' . $data->slug) . '">
                ' . $imgPath . '
                </a>
            </div>
            <div class="ps-product__container desktop">
                <div class="ps-product__content">
                    <a class="ps-product__title text-capitalize" href="' . base_url('product/detail/' . $data->slug) . '">' . $data->name . '</a>
                    <p class="ps-product__price sale">' . $price . '</p>

                    <div class="ps-product__desc">
                    ' . $data->description . '
                    </div>
                </div>
                <div class="ps-product__shopping">
                ' . $btnCart . '
                </div>
            </div>
        </div>';

        return array('output1' => $output1, 'output2' => $output2);
    }
    
    /*
    |--------------------------------------------------------------------------
    | Search Product
    |--------------------------------------------------------------------------
    */
    function searchProduct()
    {
        $member         = ddm_get_current_member();
        $provincedata   = ddm_provinces($member->province);
        $provincearea   = $provincedata->province_area;

        $auth       = auth_redirect(true);
        $product    = sanitize($this->input->get('product'));
        $category   = sanitize($this->input->get('category'));
        $sortBy     = sanitize($this->input->get('sortby'));
        $orderBy    = sanitize($this->input->get('orderby'));
        $limit      = $this->input->post('limit');
        $start      = $this->input->post('start');

        $condition  = ' AND %status% = 1';
        $order_by   = '%datemodified% DESC';

        //if ( $category !== 'all' ) {
        //$condition .= str_replace('%s%', $category, ' AND %category% = "%s%"');
        //}

        if ($sortBy && $orderBy) {
            if (strtolower($sortBy) ==  'datecreated') {
                $order_by = '%datecreated% ' . $orderBy;
            }
            if (strtolower($sortBy) ==  'price') {
                if ($auth) {
                    $order_by = 'price_agent' . $provincearea . ' ' . $orderBy;
                } else {
                    $order_by = 'price_customer' . $provincearea . ' ' . $orderBy;
                }
            }
        }

        $data       = false;
        $totalRow   = 0;
        if ($auth) {
            //$get_products = shop_product_package($limit, $start, $condition, $order_by);
            $get_products = shop_search_product($limit, $start, $condition, $order_by);
        } else {
            $get_products = shop_search_product($limit, $start, $condition, $order_by);
        }

        if ($get_products) {
            $data       = isset($get_products['data']) ? $get_products['data'] : false;
            $totalRow   = isset($get_products['total_row']) ? $get_products['total_row'] : 0;
        }

        $result     = array('data' => $data, 'count' => $totalRow);
        $this->getProducts($result, $totalRow);
    }

    /*
    |--------------------------------------------------------------------------
    | Cart
    |--------------------------------------------------------------------------
    */

    /*
    | Get cart contents and check product if any changes
    */
    function cartContents()
    {
        $auth       = auth_redirect( true );
        
        if(empty($auth)){
            return FALSE;
        }

        if ($this->cart->contents()) {
            $member         = ddm_get_current_member();
            $provincedata   = ddm_provinces($member->province);
            $provincearea   = $provincedata->province_area;

            foreach ($this->cart->contents() as $item) {
                $product_id = isset($item['id']) ? $item['id'] : 'none';
                if ( $auth ) {
                    //$product    = ddm_product_package('id', $product_id);
                    $product    = shop_product($product_id, $provincearea);
                } else {
                    $product    = shop_product($product_id, $provincearea);
                }
                $qty        = ($product) ? $item['qty'] : 0;

                if(!empty($provincearea)){
                    $price          = $product->{"price_agent".$provincearea};
                    $price_agent    = $product->{"price_agent".$provincearea};
                    $price_customer = $product->{"price_customer".$provincearea};
                }else{
                    $price          = $product->price_agent1;
                    $price_agent    = $product->price_agent1;
                    $price_customer = $product->price_customer1;
                }

                $minorder = $product->min_order;
                if(empty($minorder)){
                    $minorder = 15;
                }

                $shop_order[] = array(
                    'id'                => $item['id'],
                    'rowid'             => $item['rowid'],
                    'cart_price'        => $item['price'],
                    'cart_subtotal'     => $item['subtotal'],
                    'qty'               => $qty,
                    'weight'            => ($product) ? $item['options']['weight'] : 0,
                    'product_status'    => ($product && $product->status == 1) ? 'exist' : 'noexist',
                    'product_id'        => ($product) ? $product->id : 0,
                    'product_name'      => ($product) ? $product->name : 'Produk Tidak Ditemukan',
                    'product_slug'      => ($product) ? $product->slug : 'notfound',
                    'product_price'     => ($product) ? $price : 0,
                    'product_weight'    => ($product) ? $product->weight : 0,
                    'price_agent'       => isset($price_agent) ? $price_agent : 0,
                    'price_customer'    => isset($price_customer) ? $price_customer : 0,
                    'product_stock'     => isset($product->stock) ? $product->stock : 0,
                    'disc_min_qty'      => isset($product->discount_agent_min) ? $product->discount_agent_min : 0,
                    'disc_type'         => isset($product->discount_agent_type) ? $product->discount_agent_type : '',
                    'disc_amount'       => isset($product->discount_agent) ? $product->discount_agent : 0,
                    'min_order'         => isset($minorder) ? $minorder : 15,
                    'min_order_agent'   => isset($minorder) ? $minorder : 15,
                    'product_owner'     => 0,
                );

                foreach ($shop_order as $row) {
                    $product_status[]   = $row['product_status'];
                    $price_check[]      = ($row['product_price'] <> $row['cart_price']) ? 'error' : FALSE;
                    // $stock_check[]      = ($row['product_stock'] < $row['qty']) ? 'error' : FALSE;
                }
                $product_status_error   = (array_search('noexist', $product_status) !== FALSE) ? TRUE : FALSE;
                $price_error    = (array_search('error', $price_check) !== FALSE) ? TRUE : FALSE;
                // $stock_error    = (array_search('error', $stock_check) !== FALSE) ? TRUE : FALSE;
                $stock_error    = FALSE;
            }

            if ($stock_error || $product_status_error) {
                $has_error = TRUE;
            } else {
                $has_error = FALSE;
            }

            $response = array(
                'data'      => $shop_order,
                'has_error' => $has_error,
            );
            return $response;
        } else {
            return FALSE;
        }
    }

    /*
    | Cart List | Page
    */
    public function pageCart()
    {
        $content            = 'pages/shop/cart_agent';
        if ( $member = auth_redirect( true ) ) {
            $member         = ddm_get_current_member();
            $content        = 'pages/shop/cart_agent';
        }
        
        $data['title']      = COMPANY_NAME . ' | My Cart';
        $data['content']    = $content;
        $data['breadcrumb'] = "Keranjang Belanja";
        $data['carts']      = $this->cartContents();
        $data['member']     = $member;

        if ( $carabiner = config_item('cfg_carabiner') ) {
            $js = array(
                array(BE_JS_PATH . 'pages/shop/cart.js?ver=' . JS_VER_PAGE),
            );
            $this->carabiner->group('custom_js', array('js' => $js));
        }
        else{
            $data['scripts']    = array(
                BE_JS_PATH . 'pages/shop/cart.js?ver=' . JS_VER_PAGE,
            );
        }
        
        // Get Data DB
        // $data['get_products'] = $this->db->order_by("id", "desc")->get_where(TBL_PRODUCT, array('status' => 1))->result();
        $data['get_products'] = ddm_products(0, true);

        $this->load->view(VIEW_SHOP . 'template', $data);
    }

    /*
    | Add To Cart | ACTION
    */
    public function addToCart()
    {
        $products   = false;
        $auth       = auth_redirect( true );
        $id         = ddm_decrypt($this->input->post('id'));
        $qty        = $this->input->post('qty');

        $member         = ddm_get_current_member();
        $provincearea   = 1;
        if(!empty($member)){
            $provincedata   = ddm_provinces($member->province);
            $provincearea   = $provincedata->province_area;
        }

        if ( $auth ) {
            $condition          = array('status' => 1);
            //$product            = ddm_product_package('id', $id, $condition, 1);
            $product            = ddm_products($id, true);

            $qty_free_shipping  = get_option('qty_package_free_shipping');
            $qty_free_shipping  = $qty_free_shipping ? $qty_free_shipping : 0;
            $qty                = config_item('min_order_agent');
            $qty                = $qty ? $qty : 15;

            if(!empty($provincearea)){
                $price          = $product->{"price_agent".$provincearea};
            }else{
                $price          = $product->price_agent1;
            }
        } 
        else {
            $product            = shop_product($id);
            $qty_free_shipping  = $product->qty_free_shipping;
            if(!empty($provincearea)){
                $price          = $product->{"price_agent".$provincearea};
            }else{
                $price          = $product->price_agent1;
            }

            $productHasDiscountByQty = ( $product->discount_agent > 0 && $qty >= $product->min_order ) ? true : false;
            if ( $productHasDiscountByQty ) {
                if ( $product->discount_agent_type == 'percent' ) {
                    $price = $price * ((100 - $product->discount_agent) / 100); // price after - discount
                } else {
                    $price = $price - $product->discount_agent; // price after - discount
                }
            }
        }

        if ($qty == 0) {
            $response = array(
                'status'    => 'failed',
                'message'   => 'Added to cart Failed! (Qty cannot zero)',
            ); die(json_encode($response));
        }

        if (!$product) {
            $response = array(
                'status'    => 'failed',
                'message'   => 'Added to cart Failed! (product not found)',
            ); die(json_encode($response));
        }

        $total_weight       = $product->weight * $qty;
        $product_weight     = $total_weight;
        if ( $qty_free_shipping > 0 && $qty >= $qty_free_shipping ) {
            $total_weight   = 0;
        }

        $data = array(
            'id'        => $product->id,
            'name'      => $product->name,
            'qty'       => $qty,
            'price'     => $price,
            'subtotal'  => $price * $qty,
            'options'   => array(
                'weight' => $total_weight,
                'product_weight' => $product_weight,
            )

        );

        if ($this->cart->insert($data)) {
            $response = array(
                'status'    => 'success',
                'message'   => 'Success Added to Cart!',
            );
            die(json_encode($response));
        } else {
            $response = array(
                'status'    => 'success',
                'message'   => 'Failed Added to Cart!',
            );
            die(json_encode($response));
        }
    }

    /*
    | Destroy Cart Function
    */
    public function emptyCart()
    {
        $this->cart->destroy();
        remove_code_discount(); // remove discount on delete
        $response = array(
            'status'    => 'success',
            'message'   => 'Cart Empty',
        );
        die(json_encode($response));
    }

    /*
    | Action | Delete product from cart.
    */
    function deleteCart($rowid = '', $packageid = '')
    {
        $rowid      = ($rowid) ? $rowid : $this->input->post('rowid');
        $packageid  = ($packageid) ? $packageid : $this->input->post('packageid');
        
        $data = array(
            'rowid' => $rowid,
            'qty'   => 0,
        );
        $delete_cart = $this->cart->update($data);

        if (!$delete_cart) {
            $response = array(
                'status'    => 'failed',
                'message'   => 'Product Delete Failed!',
            );
            die(json_encode($response, true));
        }

        /*
        if ( $packageid && $this->cart->contents() ) {
            foreach ($this->cart->contents() as $item) {
                $rowid = isset($item['rowid']) ? $item['rowid'] : false;
                $id_package = isset($item['id_package']) ? $item['id_package'] : false;

                if ( $id_package == $packageid ) {
                    $data = array(
                        'rowid' => $rowid,
                        'qty'   => 0,
                    );
                    $delete_cart = $this->cart->update($data);
                }
            }
        }
        */

        remove_code_discount(); // remove discount on delete
        $response = array(
            'status'    => 'success',
            'message'   => 'Product Deleted!',
        );
        die(json_encode($response, true));
    }

    /*
    | Count Total Cart | Result Data as JSON
    */
    function countTotalCart()
    {

        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $response = $this->cart->total();

        $data = array('data'  => $response);
        die(json_encode($data));
    }

    /*
    |--------------------------------------------------------------------------
    | Check stock | Result Data as JSON | Ajax
    |--------------------------------------------------------------------------
    */
    function checkStock()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $productid  = $this->input->post('productid');
        $qty        = $this->input->post('qty');

        $stock_report = stock_availability($productid, $qty);
        echo json_encode($stock_report);
    }


    /*
    |--------------------------------------------------------------------------
    | Update Qty and checking stock availability
    |--------------------------------------------------------------------------
    */
    function updateQty()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $auth       = auth_redirect( true );
        $rowid      = $this->input->post('rowid');
        $qty        = $this->input->post('qty');
        $productid  = ddm_decrypt($this->input->post('productid'));
        $product    = shop_product($productid);

        if ($product) {
            $product_weight     = $product->weight;
            $qty_free_shipping  = $product->qty_free_shipping;

            // $stock = stock_availability($productid, $qty);
            $stock = stock_product_order($productid, $qty);
            // $stock = array('status'=>'success', 'stock'=>'0', 'message' => 'On' );

            if ($stock['status'] == 'failed') {

                // Check Free Shipping
                // if ( $auth ) {
                // }
                    if ( $qty_free_shipping > 0 && $qty >= $qty_free_shipping ) {
                        $product_weight = 0;
                    }

                $data = array(
                    'rowid' => $rowid,
                    'qty'   => $stock['stock'], // qty = current stock
                    'options' => array(
                        'weight' =>  ( $product_weight * $stock['stock'] ),
                    )
                );
                $this->cart->update($data); // update cart

                $response = array(
                    'status'    => 'failed',
                    'message'   => $stock['message'],
                ); die(json_encode($response));
            } else {

                $priceFinal = $product->price;
                
                // check if product has discount for buying exceed qty
                // $productHasDiscountByQty = ( $product->min_qty > 0 && $product->discount > 0 && $qty >= $product->min_qty ) ? true : false;
                $productHasDiscountByQty = ( $product->discount > 0 && $qty >= $product->min_qty ) ? true : false;
                if ( $productHasDiscountByQty ) {
                    if ( $product->discount_type == 'percent' ) {
                        $priceFinal = $product->price * ((100 - $product->discount) / 100); // price after - discount
                    } else {
                        $priceFinal = $product->price - $product->discount; // price after - discount
                        $product->discount = ddm_accounting($product->discount, config_item('currency'));
                    }
                }

                // Check Free Shipping
                // if ( $auth ) {
                // }
                    if ( $qty_free_shipping > 0 && $qty >= $qty_free_shipping ) {
                        $product_weight = 0;
                    }

                $data = array(
                    'rowid' => $rowid,
                    'qty'   => $qty,
                    'price'   => $priceFinal,
                    'options' => array(
                        'weight'         => $product_weight * $qty,
                        'product_weight' => $product->weight * $qty,
                    )
                );
                $this->cart->update($data); // update cart
            }

            $response  = array(
                'status' => 'success',
                'stock'  => $product->stock,
                'weight' => $product->weight * $data['qty'],
                'price'  => ddm_accounting($product->price),
                'discount_type'   => ($productHasDiscountByQty) ? $product->discount_type : FALSE,
                'discount_by_qty'   => ($productHasDiscountByQty) ? $product->discount : FALSE,
                'total_cart'        => ddm_accounting($priceFinal * $data['qty']),
                'subtotal_cart'     => ddm_accounting($this->cart->total()),
                'total_promo_amount'    => ddm_accounting(total_promo('amount')),
                'total_promo_discount'  => ddm_accounting(total_promo('discount'))
            );
            die(json_encode($response));
        } else {
            echo 'product not found';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Update Qty Agent Order
    |--------------------------------------------------------------------------
    */
    function updateQtyAgent()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            // Set JSON data
            $data = array('status' => 'access_denied', 'url' => base_url('login')); 
            die(json_encode($data));
        }

        $products   = $this->input->post('products');

        if ( $products ) {
            foreach ($products as $key => $row) {
                $rowid              = $row['rowid'];
                $qty                = $row['qty'];
                $productid          = ddm_decrypt($row['productid']);

                $condition          = array('status' => 1);
                //$product            = ddm_product_package('id', $productid, $condition, 1);
                $product            = ddm_products($productid, true);
                if ( ! $product ) { continue; }

                $qty_free_shipping  = get_option('qty_package_free_shipping');
                $qty_free_shipping  = $qty_free_shipping ? $qty_free_shipping : 0;
                $total_weight       = $product->weight * $qty;
                $product_weight     = $total_weight;
                if ( $qty_free_shipping > 0 && $qty >= $qty_free_shipping ) {
                    $total_weight = 0;
                }

                $data = array(
                    'rowid' => $rowid,
                    'qty'   => $qty,
                    'options' => array(
                        'weight'         => $total_weight,
                        'product_weight' => $product_weight,
                    )
                );
                $this->cart->update($data);
            }

            $response  = array(
                'status'                => 'success',
                'subtotal_cart'         => ddm_accounting($this->cart->total()),
                'total_promo_amount'    => ddm_accounting(total_promo('amount')),
                'total_promo_discount'  => ddm_accounting(total_promo('discount'))
            );
            die(json_encode($response));
        } else {
            echo 'product not found';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Update Cart
    |--------------------------------------------------------------------------
    */
    function updateCart()
    {
        $options_reg    = $this->input->post('options_reg');
        $options_reg    = ddm_isset($options_reg, '');

        $currency       = config_item('currency');
        $data_cart      = array();
        foreach ($this->cart->contents() as $item) {
            $product    = shop_product($item['id']);
            $qty        = $item['qty'] ;

            if ( $product ) {
                $product_weight     = $product->weight;
                $qty_free_shipping  = $product->qty_free_shipping;
                $priceFinal         = $product->price;

                $data_cart[$item['id']] = array(
                    'id'        => $product->id,
                    'product'   => $product->name,
                    'price'     => $priceFinal,
                );

                if ( $options_reg == 'agent' ) {
                    $qty                    = $product->min_order_agent;
                    $price                  = $product->price_agent;
                    $priceFinal             = $price;
                    $product->price         = $price;
                    $product->min_qty       = $product->discount_agent_min;
                    $product->discount      = $product->discount_agent;
                    $product->discount_type = $product->discount_agent_type;

                    $data_cart[$item['id']]['price_agent']      = $product->price_agent;
                    $data_cart[$item['id']]['price_customer']   = $product->price_customer;
                    $data_cart[$item['id']]['qty']              = $qty;
                } else {
                    $price  = $product->price;
                    
                    if ( $product->status != 1 ) { // product not active / deleted
                        $qty = 0;
                    }
                }

                if ( $qty_free_shipping > 0 && $qty >= $qty_free_shipping ) {
                    $product_weight = 0;
                }

                // check if product has discount for buying exceed qty
                $productHasDiscountByQty = ( $product->discount > 0 && $qty >= $product->min_qty ) ? true : false;
                if ( $productHasDiscountByQty ) {
                    if ( $product->discount_type == 'percent' ) {
                        $priceFinal = $product->price * ((100 - $product->discount) / 100); // price after - discount
                    } else {
                        $priceFinal = $product->price - $product->discount; // price after - discount
                    }
                }

                $data_cart[$item['id']]['priceFinal']   = $priceFinal;
                $data_cart[$item['id']]['subtotal']     = ddm_accounting($priceFinal * $qty);

                $data_update = array(
                    'rowid' => $item['rowid'],
                    'price' => $priceFinal,
                    'qty'   => $qty,
                    'options' => array(
                        'weight'         => $product_weight * $qty,
                        'product_weight' => $product->weight * $qty,
                    )
                );
                $this->cart->update($data_update);
            }
        }

        if ( $this->input->is_ajax_request() ) { 
            $response = array(
                'status'    => ($data_cart) ? 'success' : 'failed',
                'message'   => ($data_cart) ? 'success' : 'failed',
                'data'      => $data_cart,
            ); die(json_encode($response));
        } else {
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
    //-------------------------------------------------------------------------


    /*
    |--------------------------------------------------------------------------
    | Set Discount
    |--------------------------------------------------------------------------
    */
    function applyDiscount()
    {
        if (!$this->input->is_ajax_request()) { exit('No direct script access allowed'); }

        $auth           = auth_redirect( true );
        $codeDiscount   = $this->input->post('code_discount');
        $codeDiscount   = ddm_isset($codeDiscount, 0);
        
        if ( !$codeDiscount ) {
            $response = array('status'  => 'failed', 'message' => 'Kode diskon belum di isi!');
            die(json_encode($response));
        }

        if ( !$getDiscount = discount_code($codeDiscount) ) {
            $response = array('status'  => 'failed', 'message' => 'Kode diskon tidak ditemukan!');
            die(json_encode($response));
        }

        if ( $getDiscount->status == 0 ) {
            $response = array('status'  => 'failed', 'message' => 'Kode diskon sudah tidak tersedia !');
            die(json_encode($response));
        }

        if ( $auth ) {
            if ( $getDiscount->discount_agent == 0 ) {
                $response = array('status'  => 'failed', 'message' => 'Kode diskon tidak ditemukan !');
                die(json_encode($response));
            }
            $discount_type  = $getDiscount->discount_agent_type;
            $discount       = $getDiscount->discount_agent;
        } else {
            if ( $getDiscount->discount_customer == 0 ) {
                $response = array('status'  => 'failed', 'message' => 'Kode diskon tidak ditemukan !');
                die(json_encode($response));
            }
            $discount_type  = $getDiscount->discount_customer_type;
            $discount       = $getDiscount->discount_customer;
        }

        if ( $discount_products = is_json($getDiscount->products) ) {
            $discount_products = json_decode($getDiscount->products);
        }

        $discPerProduct         = 0;
        $checkAllProductDisc    = 0;
        if ( $discount_products ) {
            $cartContent = $this->cartContents();
            foreach ($cartContent['data'] as $row) {
                $productId  = $row['product_id'];
                foreach ($discount_products  as $key => $product) {
                    if ( $product == $productId ) {
                        $discPerProduct = $discount;
                    }
                }
            }
            if ( ! $discPerProduct ) {
                $response = array('status'  => 'failed', 'message' => 'Kode diskon ini hanya untuk produk tertentu');
                die(json_encode($response));
            }

        } else {
            // Check if code disc is for global
            $checkAllProductDisc = $discount;
        }

        $discValid = FALSE;
        if ($discPerProduct) { // if any discount per product
            apply_code_discount($getDiscount->created_by, $getDiscount->promo_code, $discount_type, $discPerProduct, $discount_products); // Set SESSION
            $discValid = TRUE;
        } else if ($checkAllProductDisc) { // global discount
            apply_code_discount($getDiscount->created_by, $getDiscount->promo_code, $discount_type, $checkAllProductDisc); // Set SESSION
            $discValid = TRUE;
        }

        if ($discValid) {
            $message = 'Kode diskon berhasil digunakan.';
            if ( $discPerProduct ) {
                $discount_info = '';
                // if ( $discount_type == 'percent' ) {
                //     $discount_info = ($discPerProduct+0) .'%'; 
                // }
                $message .= ' Anda mendapatkan diskon '. $discount_info .' dari produk tertentu';
            }

            $total_discount     = ddm_accounting(total_promo('discount'));
            $delete_voucher     = 'Kode diskon berhasil digunakan. <a href="'. base_url('shop/removeDiscount') .'" style="color:red">[Hapus Diskon]</a>';

            $response = array(
                'status'            => 'success', 
                'message'           => $message,
                'delete_discount'   => $delete_voucher, 
                'total_discount'    => $total_discount, 
            ); die(json_encode($response));
        } else {
            $response = array('status'  => 'failed', 'message' => 'Kode diskon tidak dapat digunakan ');
            die(json_encode($response));
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Set Discount
    |--------------------------------------------------------------------------
    */
    function applyDiscountRegAgent()
    {
        if (!$this->input->is_ajax_request()) { exit('No direct script access allowed'); }

        $auth           = auth_redirect( true );
        $codeDiscount   = $this->input->post('code_discount');
        $codeDiscount   = ddm_isset($codeDiscount, '');
        $products       = $this->input->post('products');
        
        if ( !$codeDiscount ) {
            $response = array('status'  => 'failed', 'message' => 'Kode Voucher belum di isi!');
            die(json_encode($response));
        }

        if ( !$getDiscount = discount_code($codeDiscount) ) {
            $response = array('status'  => 'failed', 'message' => 'Kode Voucher tidak ditemukan!');
            die(json_encode($response));
        }

        if ( $getDiscount->status == 0 ) {
            $response = array('status'  => 'failed', 'message' => 'Kode Voucher sudah tidak tersedia !');
            die(json_encode($response));
        }

        if ( $getDiscount->discount_agent == 0 ) {
            $response = array('status'  => 'failed', 'message' => 'Kode Voucher tidak ditemukan !');
            die(json_encode($response));
        }

        $discount_type  = $getDiscount->discount_agent_type;
        $discount       = $getDiscount->discount_agent;

        if ( $discount_products = is_json($getDiscount->products) ) {
            $discount_products = json_decode($getDiscount->products);
        }

        // Set Data Product
        $total_qty          = 0;
        $total_price        = 0;
        $data_product       = array();
        $no=1;
        foreach ($products as $item) {
            $productId      = isset($item['id']) ? ddm_decrypt($item['id']) : 0;
            $qty            = isset($item['qty']) ? $item['qty'] : 0;
            $price_cart     = isset($item['price']) ? $item['price'] : 0;

            if ( !$productId || !$qty ) { continue; }

            $subtotal       = ($price_cart * $qty);
            $total_qty     += $qty;
            $total_price   += $subtotal;

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
        }

        if ( !$total_price || !$total_qty ) {
            $response = array('status'  => 'failed', 'message' => 'Kode Voucher tidak ditemukan !');
            die(json_encode($response));
        }

        $total_price_product = $total_price;

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
            $total_discount = $total_price ? $discount : 0;;
        }

        if ( !$total_discount ) {
            $message = ( $discount_products ) ? 'Kode Voucher hanya untuk produk tertentu !' : 'Kode Voucher tidak dapat digunakan.';
            $response = array('status'  => 'failed', 'message' => $message);
            die(json_encode($response));
        }

        $message = 'Kode Voucher berhasil digunakan.';
        if ( $discount_products ) {
            $message .= ' Anda mendapatkan diskon dari produk tertentu';
        }

        $delete_voucher = '
            Kode Voucher berhasil digunakan. <a href="javascript:;" class="removeDiscount" style="color:red">[Hapus Diskon]</a>
            <input type="hide" class="form-control" name="voucher_code" style="display: none" value="'.strtoupper($codeDiscount).'">
            <input type="hide" class="form-control" name="total_discount" style="display: none" value="'.$total_discount.'">
        ';


        $response       = array(
            'status'            => 'success', 
            'message'           => $message,
            'discount'          => ( $total_discount ) ? ( $discount_type == 'percent' ? ($discount+0) .'%' : ddm_accounting($total_discount) ) : '-',
            'subtotal'          => $total_price_product,
            'total_discount'    => ($total_discount+0), 
            'delete_discount'   => $delete_voucher, 
        ); die(json_encode($response));
    }

    /*
    | Remove Code Discount
    */
    function removeDiscount()
    {
        // Unset Discount SESSION
        remove_code_discount();
        redirect($_SERVER['HTTP_REFERER']);
    }
    # -------------------------------------------------------------------------

    /*
    |--------------------------------------------------------------------------
    | Find Seller | Page
    |--------------------------------------------------------------------------
    */
    public function pageFindSeller($type)
    {
        if ( $member = auth_redirect( true ) ) {
            $member  = ddm_get_current_member();
        }

        $page_type  = $type;
        if ($type == 'shop') {
            if (!$this->cart->contents()) {
                redirect(base_url('cart'));
                die();
            }
            if ($this->session->userdata('seller_ref_applied') || is_logged_in()) {
                redirect(base_url('checkout'));
                die();
            }
        } else if ($type == 'contact') {
            if ( !$member && $this->cart->total() ) {
                $type = 'shop';
            }
        } else {
            redirect();
        }

        $msg = '';
        if ( $this->session->userdata('message_find_seller') ) {
            $msg = $this->session->userdata('message_find_seller');
            $this->session->unset_userdata('message_find_seller');
        }

        $data['title']      = COMPANY_NAME . ' | Find Agen';
        $data['content']    = 'pages/shop/find_seller';
        $data['carts']      = $this->cartContents();
        $data['type']       = $type;
        $data['page_type']  = $page_type;
        $data['msg']        = $msg;
        $data['member']     = $member;

        if ($data['carts']['has_error']) redirect(base_url('cart')); // Cart error
        
        if ( $carabiner = config_item('cfg_carabiner') ) {
            $js = array(
                array(BE_JS_PATH . 'components/rajaongkir/address.js?ver=' . JS_VER_PAGE),
                array(BE_JS_PATH . 'pages/shop/find_seller.js?ver=' . JS_VER_PAGE),
            );
            $this->carabiner->group('custom_js', array('js' => $js));
        }else{
            $data['scripts']    = array(
                BE_JS_PATH . 'components/rajaongkir/address.js?ver=' . JS_VER_PAGE,
                BE_JS_PATH . 'pages/shop/find_seller.js?ver=' . JS_VER_PAGE,
            );
        }

        // Get Data DB
        // $data['get_products'] = $this->db->order_by("id", "desc")->get_where(TBL_PRODUCT, array('status' => 1))->result();
        $data['get_products'] = ddm_products(0, true);

        $this->load->view(VIEW_SHOP . 'template', $data);
    }

    /*
    |--------------------------------------------------------------------------
    | Find Seller | Action
    |--------------------------------------------------------------------------
    */
    public function actionFindSeller()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $data_agent         = array();
        $dataAgent          = '';
        $opt_tracking       = $this->input->post('options_tracking');
        $opt_tracking       = ddm_isset($opt_tracking, '');
        $agent_code         = $this->input->post('agent_code');
        $agent_code         = ddm_isset($agent_code, '');
        $province           = $this->input->post('province');
        $province_id        = ddm_isset($province, 0);
        $city               = $this->input->post('city');
        $city_id            = ddm_isset($city, 0);
        $subdistrict        = $this->input->post('subdistrict');
        $subdistrict_id     = ddm_isset($subdistrict, 0);

        $condition          = ' AND %status% = '. ACTIVE .' AND %type% = '. MEMBER;

        if ( strtolower($opt_tracking) == 'agent_location' ) {
            if ( $city_id ) {
                $seller_by      = 'seller by city';
                $condition     .= ' AND %district_id% = '. $city_id;
                if ( $subdistrict_id ) {
                    $condition .= ' AND %subdistrict_id% = '. $subdistrict_id;
                }
                $dataAgent      = $this->Model_Member->get_all_member_address(0, 0, $condition, '%name% ASC');
            } else if ( $province_id ) {
                $seller_by      = 'seller by province';
                $condition     .= ' AND %province_id% = '. $province_id;
                $dataAgent      = $this->Model_Member->get_all_member_address(0, 0, $condition, '%name% ASC');            
            }
        } else if ( strtolower($opt_tracking) == 'agent_code' ) {
            $seller_by      = 'seller by agent code';
            $condition     .= ' AND %username% = "'. trim($agent_code) . '"';
            $dataAgent      = $this->Model_Member->get_all_member_address(0, 0, $condition, '%name% ASC');   
        }

        if ( ! $dataAgent ) {
            $response = array(
                'status'  => 'failed',
                'condition'  => $condition,
                'dataAgent'  => $dataAgent,
                'message' => 'Agen tidak ditemukan, silahkan ganti pencarian anda'
            ); die(json_encode($response));
        }

        foreach ($dataAgent as $key => $row) {
            $row->id = ddm_encrypt($row->id);
            $data_agent[] = $row;
        }


        $response = array(
            'status'  => 'success',
            'message' => $seller_by,
            'data'    => $data_agent
        ); die(json_encode($response));
    }

    /*
    |--------------------------------------------------------------------------
    | Choose Seller | Action
    |--------------------------------------------------------------------------
    */
    public function actionChooseSeller()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $idSeller = ddm_decrypt($this->input->post('id'));
        if ( $seller = ddm_get_memberdata_by_id($idSeller) ) {
            if ( $seller->status != ACTIVE || $seller->type != MEMBER ) {
                $seller = false;
            }
        }

        if ($seller) {
            apply_code_seller($seller->username);
            $response = array('status' => 'success', 'url' => base_url('checkout'));
            die(json_encode($response));
        } else {
            $response = array('status' => 'failed', 'message' => 'Gagal pilih seller');
            die(json_encode($response));
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Random Seller | Action
    |--------------------------------------------------------------------------
    */
    public function actionRandomSeller()
    {
        $seller = $this->db->order_by('rand()')->limit(1)->get_where(TBL_USERS, array('type' => MEMBER, 'status' => ACTIVE))->row();
        if ($seller) {
            apply_code_seller($seller->username);
            redirect(base_url('checkout'));
        } else {
            $this->session->set_userdata('message_find_seller', '<b>Maaf</b> untuk saat ini Agen belum tersedia.');
            redirect(base_url('find-agent/shop'));
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Checkout | Page
    |--------------------------------------------------------------------------
    */
    public function pageCheckout()
    {
        $auth                   = auth_redirect( true );
        $member                 = false;
        
        if ( !$this->cart->contents() ) {
            redirect(base_url('cart'));
        }

        if ( $auth ) {
            $member             = ddm_get_current_member();
            $is_admin           = as_administrator($member);
            $checkSeller        = false;
            $content            = 'pages/shop/checkout_agent';
            if ( $is_admin ) {
                redirect(base_url()); die();
            }
        } else {
            $checkSeller        = check_agent(true);
            $content            = 'pages/shop/checkout_agent';
            if ( ! $checkSeller ) {
                $this->session->set_userdata('message_find_seller', 'Anda belum pilih <b>Agen</b>. Silahkan pilih <b>Agen</b> terlebih dahulu untuk memesan produk !');
                redirect(base_url('find-agent/shop')); die();
            }
        }

        $data['title']      = COMPANY_NAME . ' | Checkout';
        $data['content']    = $content;
        $data['member']     = $member;
        $data['user']       = ( $auth ) ? $member : false;
        $data['agent']      = $checkSeller;
        $data['carts']      = $this->cartContents();

        if ($data['carts']['has_error']) redirect(base_url('cart')); // Cart error
        
        if ( $carabiner = config_item('cfg_carabiner') ) {
            $js = array(
                array(BE_JS_PATH . 'components/rajaongkir/address.js?ver=' . JS_VER_PAGE),
                array(BE_JS_PATH . 'components/rajaongkir/courier.js?ver=' . JS_VER_PAGE),
                array(BE_JS_PATH . 'pages/shop/checkout.js?ver=' . JS_VER_PAGE),
            );
            $this->carabiner->group('custom_js', array('js' => $js));
        }else{
            $data['scripts']    = array(
                BE_JS_PATH . 'components/rajaongkir/address.js?ver=' . JS_VER_PAGE,
                BE_JS_PATH . 'components/rajaongkir/courier.js?ver=' . JS_VER_PAGE,
                BE_JS_PATH . 'pages/shop/checkout.js?ver=' . JS_VER_PAGE,
            );
        }

        // Get Data DB
        // $data['get_products'] = $this->db->order_by("id", "desc")->get_where(TBL_PRODUCT, array('status' => 1))->result();
        $data['get_products'] = ddm_products(0, true);

        $this->load->view(VIEW_SHOP . 'template', $data);
    }

    /*
    |--------------------------------------------------------------------------
    | SAVE Order
    |--------------------------------------------------------------------------
    */
    public function saveOrder()
    {
        if (!$this->input->is_ajax_request()) { exit('No direct script access allowed'); }

        $auth       = auth_redirect( $this->input->is_ajax_request() );
        $datetime   = date('Y-m-d H:i:s');

        // @Required
        if ( $auth ) {
            $current_member         = ddm_get_current_member();
            $memberdata             = $current_member;
            $is_admin               = as_administrator($current_member);
            if ( $is_admin ) {
                $response = array(
                    'status'  => 'failed',
                    'message' => 'Admin tidak dapat pesan produk. Silahkan Login sebagai Agen untuk dapat pesan produk !'
                ); die(json_encode($response));
            }
        } else {
            // Check code seller valid 
            $checkSeller = check_agent(true);
            if ( ! $checkSeller ) {
                $response = array(
                    'status'  => 'failed',
                    'message' => 'Agen tidak valid. Silahkan reload untuk kembali ke pencarian Agen !'
                ); die(json_encode($response));
            }

            $id_agent   = isset($checkSeller->id) ? $checkSeller->id : 0;
            $user_agent = isset($checkSeller->username) ? $checkSeller->username : '';

            if ( ! $id_agent || ! $user_agent ) {
                $response = array(
                    'status'  => 'failed',
                    'message' => 'Agen tidak valid. Silahkan reload untuk kembali ke pencarian Agen !'
                ); die(json_encode($response));
            }
        }

        ## Validation Global --------------------------------------------------------------
        $this->form_validation->set_rules('shipping_name', 'Name', 'required|min_length[3]');
        $this->form_validation->set_rules('shipping_email', 'Email', 'valid_email|required|min_length[3]');
        $this->form_validation->set_rules('shipping_phone', 'No Telp', 'numeric|required');
        $this->form_validation->set_rules('shipping_province', 'Provinsi', 'required');
        $this->form_validation->set_rules('shipping_city', 'Kota', 'required');
        $this->form_validation->set_rules('shipping_subdistrict', 'Kecamatan', 'required');
        $this->form_validation->set_rules('shipping_address', 'Alamat', 'required');
        $this->form_validation->set_rules('shipping_postcode', 'Kodepos', 'numeric');

        $this->form_validation->set_rules('weight', 'Weight', 'numeric|required');
        $this->form_validation->set_rules('courier', 'Kurir', 'required');
        $this->form_validation->set_rules('courier_service', 'Layanan Kurir', 'required');
        $this->form_validation->set_rules('payment_method', 'Layanan Kurir', 'required');

        $optionsReg = sanitize($this->input->post('options_reg'));
        $optionsReg = $optionsReg ? strtolower($optionsReg) : '';
        if ( ! $auth ) {
            if ( $optionsReg == 'agent' ) {
                ## Validation Seller ---------------------------------------------------------
                $this->form_validation->set_rules('username_agent', 'Username', 'required');
                $this->form_validation->set_rules('password_agent', 'Password', 'required');
                $this->form_validation->set_rules('bill_bank', 'Bank', 'required');
                $this->form_validation->set_rules('bill_name', 'Nama Rekening', 'required');
                $this->form_validation->set_rules('bill_no', 'No Rekening', 'required');
            }
        }

        $this->form_validation->set_error_delimiters('', '');
        if ($this->form_validation->run() == FALSE) {
            $response = array(
                'status'    => 'failed',
                'message'   => validation_errors(),
            ); die(json_encode($response));
        } else {

            ## Input ---------------------------------------------------------
            $phone  = sanitize($this->input->post('shipping_phone'));
            if ($phone[0] == 0) { // if phone have 0 in first letter
                $phone     = $phone;
            } else {
                $phone     = substr_replace($phone, 0, 0, 0); // then add 0 in first letter
            }

            $optionsCustomer    = sanitize($this->input->post('options_save_customer'));
            $id_customer        = sanitize(ddm_decrypt($this->input->post('id_customer')));
            $name               = sanitize($this->input->post('shipping_name'));
            $email              = sanitize($this->input->post('shipping_email'));
            $address            = sanitize($this->input->post('shipping_address'));
            $postcode           = sanitize($this->input->post('shipping_postcode'));
            $weight             = sanitize($this->input->post('weight'));
            $courier            = sanitize($this->input->post('courier'));

            if ($this->session->userdata('promo_applied')) {
                $code_discount  = $this->session->userdata('promo_code');
            } else {
                $code_discount  = NULL;
            }

            $province           = sanitize($this->input->post('shipping_province')); 
            $city               = sanitize($this->input->post('shipping_city')); 
            $subdistrict        = sanitize($this->input->post('shipping_subdistrict')); 
            $courier_info       = explode(',', $this->input->post('courier_service', TRUE)); // sperate value by comma | Array doesn't need sanitize, instead using TRUE
            
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

            $courier_serv       = $courier_info[0]; // service
            $courier_cost       = $courier_info[1]; // cost

            ## Data Shopping ---------------------------------------------------
            $cart_contents = $this->cartContents();
            if ( isset($cart_contents['has_error']) && $cart_contents['has_error'] ) {
                $response = array(
                    'status'    => 'failed',
                    'message'   => 'Cart Error!',
                    'redirect'  => 'reload',
                ); die(json_encode($response));
            }

            if (!$cart_contents) {
                $response = array(
                    'status'   => 'failed',
                    'message'  => 'Order produk tidak berhasil. Data produk tidak ditemukan. Silahkan Reload!',
                    'redirect' => 'reload',
                ); die(json_encode($response));
            }

            $data_product       = array();
            $total_qty          = 0;
            $total_point        = 0;
            foreach ($cart_contents['data'] as $item) {
                $product_id     = isset($item['id']) ? $item['id'] : 0;
                $qty            = isset($item['qty']) ? $item['qty'] : 0;
                if ( !$product_id && !$qty ) { continue; }

                $product_point  = 0;
                $subtotal_point = 0;
                $data_product[] = array(
                    'id'            => $product_id,
                    'qty'           => $qty,
                    'price'         => $item['cart_price'], // price after discount
                    'price_ori'     => $item['product_price'], // original price
                    'name'          => $item['product_name'],
                    'weight'        => $item['product_weight'],
                    'point'         => $product_point,
                    'total_point'   => $subtotal_point,
                    'discount'      => $item['product_price'] - $item['cart_price'],
                    'discount_qty'  => ($qty >= $item['disc_min_qty']) ? $item['disc_amount'] : 0,
                );
                $total_qty     += $qty;
                $total_point   += $subtotal_point;

                // // Check Stock Product
                // if ( $auth ) {
                //     if ( $getProduct = ddm_products($product_id, false) ) {
                //         if ( $qty > $getProduct->stock ) {
                //             $response = array(
                //                 'status'  => 'failed', 
                //                 'message' => 'Stok '.$getProduct->name.' tidak tersedia. Silahkan kembali ke cart'
                //             ); die(json_encode($response));
                //         }
                //     }
                // }
            }

            if ( ! $data_product  ) {
                $response = array(
                    'status'   => 'failed',
                    'message'  => 'Order produk tidak berhasil. Data produk tidak ditemukan. Silahkan Reload!',
                    'redirect' => 'reload',
                ); die(json_encode($response));
            }

            $this->db->trans_begin();

            $member_save_id     = NULL;
            $member_confirm_id  = NULL;
            $customer_order     = FALSE;
            $customer_save_id   = 0;
            $cfg_register_fee   = 0;
            $code_unique        = 0;
            $total_omzet        = total_promo('amount');

            if ( ! $auth ) {
                if ( $optionsReg == 'agent' ) {
                    $username_sponsor   = sanitize($this->input->post('username_sponsor'));
                    $userName           = sanitize($this->input->post('username_agent'));
                    $password           = sanitize($this->input->post('password_agent'));
                    $billBank           = sanitize(ddm_decrypt($this->input->post('bill_bank')));
                    $billName           = sanitize($this->input->post('bill_name'));
                    $billNo             = sanitize($this->input->post('bill_no'));
                    $billBranch         = sanitize($this->input->post('bill_branch'));

                    $username   = substr($name, 0, 3) . substr($phone, -4); // Generate Username : JON4939

                    // -------------------------------------------------
                    // Check Sponsor
                    // -------------------------------------------------
                    $sponsordata            = $checkSeller;
                    if ( $username_sponsor  ) {
                        if ( ! $getSponsor = $this->Model_Member->get_member_by('login', $username_sponsor) ) {
                            // Rollback Transaction
                            $this->db->trans_rollback();
                            $response = array(
                                'status'  => 'failed',
                                'message' => 'Kode Referral tidak ditemukan atau belum terdaftar! Silahkan masukkan Kode Referral lainnya!'
                            ); die(json_encode($response)); // Set JSON data
                        }
                        if ( $getSponsor->type != MEMBER || $getSponsor->status != ACTIVE ) {
                            $this->db->trans_rollback();
                            $response = array(
                                'status'  => 'failed',
                                'message' => 'Kode Referral sudah tidak aktif. Silahkan masukkan Kode Referral lainnya!'
                            ); die(json_encode($response)); // Set JSON data
                        }
                        $sponsordata        = $getSponsor;
                    }

                    // -------------------------------------------------
                    // Check Username availability
                    // -------------------------------------------------
                    if( $username_exist = ddm_check_username($userName) ){
                        // Rollback Transaction
                        $this->db->trans_rollback();
                        $response = array(
                            'status'  => 'failed',
                            'message' => 'Username tidak valid. Silahkan gunakan Username lainnya!'
                        ); die(json_encode($response)); // Set JSON data
                    }
                    
                    // -------------------------------------------------
                    // Check Email availability
                    // -------------------------------------------------
                    if( $email_exist = $this->Model_Member->get_member_by('email', $email) ){
                        // Rollback Transaction
                        $this->db->trans_rollback();
                        $response = array(
                            'status'  => 'failed',
                            'message' => 'Email sudah digunakan. Silahkan gunakan Email lainnya'
                        ); die(json_encode($response)); // Set JSON data
                    }

                    // -------------------------------------------------
                    // Set Data Member
                    // -------------------------------------------------
                    // $password               = strtolower($password);
                    $username               = strtolower($userName);
                    $name                   = strtoupper($name);
                    $bill_name              = strtoupper($billName);
                    $password_bcript        = ddm_password_hash($password);
                    $package                = MEMBER_AGENT;
                    $cfg_register_fee       = get_option('register_fee');
                    $cfg_register_fee       = $cfg_register_fee ? $cfg_register_fee : 0;
                    // $uniquecode             = ddm_generate_unique();
                    $code_unique            = generate_uniquecode();
                    $total_omzet           += $cfg_register_fee;
                    
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
                        'bank'              => $billBank,
                        'bill'              => $billNo,
                        'bill_name'         => $bill_name,
                        'status'            => 0,
                        'total_omzet'       => $total_omzet,
                        'uniquecode'        => $code_unique,
                        'datecreated'       => $datetime,
                    );

                    if( ! $member_save_id = $this->Model_Member->save_data( $data_member ) ){
                        // Rollback Transaction
                        $this->db->trans_rollback();
                        $response = array(
                            'status'  => 'failed',
                            'message' => 'Pendaftaran Agen tidak berhasil. Terjadi kesalahan data simpan data Agen.'
                        ); die(json_encode($response)); // Set JSON data
                    }

                    if ( ! $memberdata = ddm_get_memberdata_by_id( $member_save_id ) ) {
                        // Rollback Transaction
                        $this->db->trans_rollback();
                        $response = array(
                            'status'  => 'failed',
                            'message' => 'Pendaftaran Agen tidak berhasil. Terjadi kesalahan data simpan data Agen.'
                        ); die(json_encode($response)); // Set JSON data
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
                        // Rollback Transaction
                        $this->db->trans_rollback();
                        // Set JSON data
                        $response = array(
                            'status'  => 'failed',
                            'message' => 'Pendaftaran Agen tidak berhasil. Terjadi kesalahan data simpan data confirm Agen.'
                        ); die(json_encode($response));
                    }
                } else {
                    $customer_order = TRUE;
                    if ( $optionsCustomer ) {
                        // -------------------------------------------------
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

                        // -------------------------------------------------
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
                            // Rollback Transaction
                            $this->db->trans_rollback();
                            $response = array(
                                'status'  => 'failed',
                                'message' => 'Data Konsumen tidak berhasil disimpan. Terjadi kesalahan data transaksi ',
                            ); die(json_encode($response));
                        }
                    }
                }
            }

            $data_shop_order = array(
                'products'          => serialize($data_product),
                'product_point'     => $total_point,
                'total_qty'         => $total_qty,
                'weight'            => $weight,

                'subtotal'          => $this->cart->total(),
                'shipping'          => $courier_cost,
                'discount'          => total_promo('discount'),
                'voucher'           => $code_discount,

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
                'service'           => $courier_serv,
                'datecreated'       => $datetime,
                'datemodified'      => $datetime,
            );

            if ( $customer_order ) {
                $id_member      = $checkSeller->id;
                $invoice        = generate_customer_invoice($id_member);
                $code_unique    = 0;
                $created_by     = strtolower($name);
            } else {
                $invoice        = generate_invoice();
                $code_unique    = $code_unique ? $code_unique : generate_uniquecode();
                $created_by     = $memberdata->id;
                $id_member      = $memberdata->id;
            }


            $total_payment                      = $total_omzet + $courier_cost + $code_unique;
            $data_shop_order['invoice']         = $invoice;
            $data_shop_order['id_member']       = $id_member;
            $data_shop_order['unique']          = $code_unique;
            $data_shop_order['total_payment']   = $total_payment;
            $data_shop_order['created_by']      = $created_by;

            if ( $customer_save_id ) {
                $data_shop_order['id_customer']  = $customer_save_id;
            } else {
                if ( ! $customer_order ) {
                    // Check Omzet Member
                    $condition_omzet    = array('status' => 'perdana');
                    if ( $omzetperdana  = $this->Model_Member->get_member_omzet_by('id_member', $memberdata->id, $condition_omzet) ) {
                        $status_omzet   = 'ro';
                    } else {
                        $status_omzet   = 'perdana';
                    }
                    $data_shop_order['type']    = $status_omzet;
                }
            }

            if ( $member_save_id ) {
                $data_shop_order['registration'] = $cfg_register_fee;
                $data_shop_order['type']         = 'perdana';
            }
                    
            // -------------------------------------------------
            // Save Shop Order
            // -------------------------------------------------
            if ( $customer_order ) {
                $shop_order_id = $this->Model_Shop->save_data_shop_order_customer($data_shop_order);
            } else {
                $shop_order_id = $this->Model_Shop->save_data_shop_order($data_shop_order);
            }

            if( ! $shop_order_id ){
                // Rollback Transaction
                $this->db->trans_rollback();
                $response = array(
                    'status'  => 'failed',
                    'message' => 'Order Produk tidak berhasil. Terjadi kesalahan data transaksi ',
                ); die(json_encode($response)); // Set JSON data
            }

            $data_order_detail = array();
            $no = 1;
            foreach ($data_product as $prodkey => $shop_order) {
                if ( $auth ) {
                    if ( $product = ddm_products($shop_order['id'], false) ) {
                        $stock = $product->stock - $shop_order['qty'];  // Update stock [to decrement]
                        if ( !$update_stock = $this->Model_Product->update_data_product($shop_order['id'], array('stock' => $stock)) ) {
                            $this->db->trans_rollback();
                            $response = array('status'  => 'failed', 'message' => 'Gagal disimpan (updstock)');
                            die(json_encode($response));
                        }
                    }
                }

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

                if( ! $customer_order ) {
                    $data_order_detail[$no]['id_member']        = isset( $memberdata->id ) ? $memberdata->id : 0;
                    $data_order_detail[$no]['product_point']    = $shop_order['point'];
                }
                $no++;
            }

            if (!$data_order_detail) {
                // Rollback Transaction
                $this->db->trans_rollback();
                $response = array(
                    'status'  => 'failed',
                    'message' => 'Order Produk tidak berhasil. Terjadi kesalahan data transaksi detail'
                ); die(json_encode($response));
            }

            foreach ($data_order_detail as $row) {
                // -------------------------------------------------
                // Save Shop Order Detail
                // -------------------------------------------------
                if ( $customer_order ) {
                    $order_detail_saved = $this->Model_Shop->save_data_shop_detail_customer($row);
                } else {
                    $order_detail_saved = $this->Model_Shop->save_data_shop_order_detail($row);
                }

                if ( !$order_detail_saved ) {
                    // Rollback Transaction
                    $this->db->trans_rollback();
                    $response = array(
                        'status'  => 'failed',
                        'message' => 'Order Produk tidak berhasil. Terjadi kesalahan data transaksi detail'
                    ); die(json_encode($response));
                }
            }

            ## Order Success -------------------------------------------------------
            $this->db->trans_commit();
            $this->db->trans_complete(); //  complete database transactions  
            $this->cart->destroy();

            remove_code_discount();
            remove_code_seller();

            ## Save Log Checkout ----------------------------------------------------
            $log_title = ( $customer_order ) ? 'CHECKOUT_CUSTOMER' : 'CHECKOUT';
            $link_conf = ( $customer_order ) ? 'confirm/paymentcustomer/' : 'confirm/payment/';

            ddm_log_action( $log_title, $invoice, $created_by, json_encode(array('cookie'=>$_COOKIE, 'status'=>'SUCCESS', 'shop_order_id'=>$shop_order_id)) );

            if ( $customer_order ) {
                if ( $shop_order = $this->Model_Shop->get_shop_order_customer_by('id', $shop_order_id) ) {
                    // Send Email
                    $mail_customer  = $this->ddm_email->send_email_shop_order_customer( $shop_order );
                    $mail_agent     = $this->ddm_email->send_email_shop_order_to_agent( $checkSeller, $shop_order );
                }
            } else {
                if ( $memberdata && $shop_order = $this->Model_Shop->get_shop_orders($shop_order_id) ) {
                    // Send Email
                    $mail = $this->ddm_email->send_email_shop_order( $memberdata, $shop_order );
                }
            }

            $response = array(
                'status'  => 'success',
                'message' => 'Order berhasil! Silahkan cek email anda untuk informasi lebih lanjut',
                'url'     => $link_conf . ddm_encrypt($shop_order_id),
            ); die(json_encode($response));
        }
    }
    # -------------------------------------------------------------------------

    /*
    |--------------------------------------------------------------------------
    | SAVE Order And Register Agent
    |--------------------------------------------------------------------------
    */
    public function saveOrderRegister()
    {
        if (!$this->input->is_ajax_request()) { exit('No direct script access allowed'); }

        $auth       = auth_redirect( $this->input->is_ajax_request() );
        $datetime   = date('Y-m-d H:i:s');

        // @Required
        if ( $auth ) {
            $current_member         = ddm_get_current_member();
            $memberdata             = $current_member;
            $is_admin               = as_administrator($current_member);
            $response = array(
                'status'  => 'failed',
                'message' => 'Anda tidak dapat register Agen. Silahkan Logout terlebih dahulu !'
            ); die(json_encode($response));
        }
        
        print_r($_POST);
        die();

        ## Validation Global --------------------------------------------------------------
        $this->form_validation->set_rules('username_agent', 'Username', 'required|min_length[6]');
        $this->form_validation->set_rules('password_agent', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('shipping_name', 'Name', 'required|min_length[3]');
        $this->form_validation->set_rules('shipping_phone', 'No Telp', 'numeric|required');
        $this->form_validation->set_rules('shipping_email', 'Email', 'valid_email|required|min_length[3]');
        $this->form_validation->set_rules('shipping_province', 'Provinsi', 'required');
        $this->form_validation->set_rules('shipping_city', 'Kota', 'required');
        $this->form_validation->set_rules('shipping_subdistrict', 'Kecamatan', 'required');
        $this->form_validation->set_rules('shipping_address', 'Alamat', 'required');
        $this->form_validation->set_rules('shipping_postcode', 'Kodepos', 'numeric');

        $this->form_validation->set_rules('courier', 'Kurir', 'required');
        $this->form_validation->set_rules('courier_service', 'Layanan Kurir', 'required');

        $this->form_validation->set_rules('package_product', 'Paket Produk', 'required');

        $this->form_validation->set_error_delimiters('', '');
        if ($this->form_validation->run() == FALSE) {
            $response = array(
                'status'    => 'failed',
                'message'   => validation_errors(),
            ); die(json_encode($response));
        } else {

            ## Input ---------------------------------------------------------
            $phone  = sanitize($this->input->post('shipping_phone'));
            if ($phone[0] == 0) { // if phone have 0 in first letter
                $phone     = $phone;
            } else {
                $phone     = substr_replace($phone, 0, 0, 0); // then add 0 in first letter
            }

            $username_sponsor   = sanitize($this->input->post('username_sponsor'));
            $username           = sanitize($this->input->post('username_agent'));
            $password           = sanitize($this->input->post('password_agent'));
            $name               = sanitize($this->input->post('shipping_name'));
            $email              = sanitize($this->input->post('shipping_email'));
            $address            = sanitize($this->input->post('shipping_address'));
            $postcode           = sanitize($this->input->post('shipping_postcode'));
            $courier            = sanitize($this->input->post('courier'));

            if ($this->session->userdata('promo_applied')) {
                $code_discount  = $this->session->userdata('promo_code');
            } else {
                $code_discount  = NULL;
            }

            $province           = sanitize($this->input->post('shipping_province')); 
            $city               = sanitize($this->input->post('shipping_city')); 
            $subdistrict        = sanitize($this->input->post('shipping_subdistrict')); 
            $courier_info       = explode(',', $this->input->post('courier_service', TRUE)); // sperate value by comma | Array doesn't need sanitize, instead using TRUE

            // Get Voucher Discount
            $voucher_code       = sanitize($this->input->post('voucher_code')); 
            $total_discount     = sanitize($this->input->post('total_discount')); 
            $total_discount     = is_numeric($total_discount) ? $total_discount : 0; 
            
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

            $courier_serv       = $courier_info[0]; // service
            $courier_cost       = $courier_info[1]; // cost

            ## Data Shopping ---------------------------------------------------
            $productPackages    = $this->input->post('products');
            if ( ! $productPackages  ) {
                $response = array(
                    'status'   => 'failed',
                    'message'  => 'Pendaftaran dan order paket produk tidak berhasil. Data paket produk tidak ditemukan. Silahkan Reload !',
                    'redirect' => 'reload',
                ); die(json_encode($response));
            }

            $data_product       = array();
            $total_qty          = 0;
            $total_point        = 0;
            $total_price        = 0;
            $total_weight       = 0;
            foreach ($productPackages as $item) {
                $productId      = isset($item['id']) ? ddm_decrypt($item['id']) : 0;
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

            if ( ! $data_product  ) {
                $response = array(
                    'status'   => 'failed',
                    'message'  => 'Pendaftaran dan order paket produk tidak berhasil. Data paket produk tidak ditemukan. Silahkan Reload!',
                    'redirect' => 'reload',
                ); die(json_encode($response));
            }

            // -------------------------------------------------
            // Check Sponsor
            // -------------------------------------------------
            $sponsordata            = ddm_get_memberdata_by_id(1);
            if ( $username_sponsor  ) {
                if ( ! $getSponsor = $this->Model_Member->get_member_by('login', $username_sponsor) ) {
                    $response = array(
                        'status'  => 'failed',
                        'message' => 'Kode Referral tidak ditemukan atau belum terdaftar! Silahkan masukkan Kode Referral lainnya!'
                    ); die(json_encode($response)); // Set JSON data
                }
                if ( $getSponsor->type != MEMBER || $getSponsor->status != ACTIVE ) {
                    $response = array(
                        'status'  => 'failed',
                        'message' => 'Kode Referral sudah tidak aktif. Silahkan masukkan Kode Referral lainnya!'
                    ); die(json_encode($response)); // Set JSON data
                }
                $sponsordata        = $getSponsor;
            }

            // -------------------------------------------------
            // Check Username availability
            // -------------------------------------------------
            $username               = strtolower(trim($username));
            if( $username_exist = ddm_check_username($username) ){
                $response = array(
                    'status'  => 'failed',
                    'message' => 'Username tidak valid. Silahkan gunakan Username lainnya!'
                ); die(json_encode($response)); // Set JSON data
            }
            
            // -------------------------------------------------
            // Check Email availability
            // -------------------------------------------------
            if( $email_exist = $this->Model_Member->get_member_by('email', $email) ){
                // Rollback Transaction
                $this->db->trans_rollback();
                $response = array(
                    'status'  => 'failed',
                    'message' => 'Email sudah digunakan. Silahkan gunakan Email lainnya'
                ); die(json_encode($response)); // Set JSON data
            }

            $this->db->trans_begin();

            // -------------------------------------------------
            // Set Data Member
            // -------------------------------------------------
            // $password               = strtolower($password);
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
                'bank'              => 0,
                'bill'              => null,
                'bill_name'         => '',
                'status'            => 0,
                'total_omzet'       => $total_omzet,
                'uniquecode'        => $code_unique,
                'datecreated'       => $datetime,
            );

            if( ! $member_save_id = $this->Model_Member->save_data( $data_member ) ){
                // Rollback Transaction
                $this->db->trans_rollback();
                $response = array(
                    'status'  => 'failed',
                    'message' => 'Pendaftaran dan order paket produk tidak berhasil. Terjadi kesalahan data simpan data Agen.'
                ); die(json_encode($response)); // Set JSON data
            }

            if ( ! $memberdata = ddm_get_memberdata_by_id( $member_save_id ) ) {
                // Rollback Transaction
                $this->db->trans_rollback();
                $response = array(
                    'status'  => 'failed',
                    'message' => 'Pendaftaran dan order paket produk tidak berhasil. Terjadi kesalahan data simpan data Agen.'
                ); die(json_encode($response)); // Set JSON data
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
                // Rollback Transaction
                $this->db->trans_rollback();
                $response = array(
                    'status'  => 'failed',
                    'message' => 'Pendaftaran dan order paket produk tidak berhasil. Terjadi kesalahan data simpan data Agen.'
                ); die(json_encode($response));
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
            $total_payment      = $total_omzet + $courier_cost + $code_unique - $total_discount;

            $data_shop_order = array(
                'invoice'           => $invoice,
                'id_member'         => $memberdata->id,
                'type'              => 'perdana',

                'products'          => maybe_serialize($data_product),
                'product_point'     => $total_point,
                'total_qty'         => $total_qty,
                'weight'            => $total_weight,

                'subtotal'          => $total_price,
                'registration'      => $cfg_register_fee,
                'shipping'          => $courier_cost,
                'unique'            => $code_unique,
                'discount'          => $total_discount,
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
                'service'           => $courier_serv,
                'created_by'        => $memberdata->username,
                'datecreated'       => $datetime,
                'datemodified'      => $datetime,
            );
                    
            // -------------------------------------------------
            // Save Shop Order
            // -------------------------------------------------
            $shop_order_id = $this->Model_Shop->save_data_shop_order($data_shop_order);
            if( ! $shop_order_id ){
                // Rollback Transaction
                $this->db->trans_rollback();
                $response = array(
                    'status'  => 'failed',
                    'message' => 'Pendaftaran dan order paket produk tidak berhasil. Terjadi kesalahan data transaksi ',
                ); die(json_encode($response)); // Set JSON data
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
                                $response = array('status'  => 'failed', 'message' => 'Gagal disimpan (updstock)');
                                die(json_encode($response));
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
                // Rollback Transaction
                $this->db->trans_rollback();
                $response = array(
                    'status'  => 'failed',
                    'message' => 'Pendaftaran dan order paket produk tidak berhasil. Terjadi kesalahan data transaksi detail'
                ); die(json_encode($response));
            }

            foreach ($data_order_detail as $row) {
                // -------------------------------------------------
                // Save Shop Order Detail
                // -------------------------------------------------
                $order_detail_saved = $this->Model_Shop->save_data_shop_order_detail($row);
                if ( !$order_detail_saved ) {
                    // Rollback Transaction
                    $this->db->trans_rollback();
                    $response = array(
                        'status'  => 'failed',
                        'message' => 'Pendaftaran dan order paket produk tidak berhasil. Terjadi kesalahan data transaksi detail'
                    ); die(json_encode($response));
                }
            }

            ## Order Success -------------------------------------------------------
            $this->db->trans_commit();
            $this->db->trans_complete(); //  complete database transactions  
            $this->cart->destroy();

            remove_code_discount();
            remove_code_seller();

            ## Save Log Checkout ----------------------------------------------------
            $log_title = 'CHECKOUT_REGISTER';
            ddm_log_action( $log_title, $invoice, $memberdata->username, json_encode(array('cookie'=>$_COOKIE, 'status'=>'SUCCESS', 'shop_order_id'=>$shop_order_id)) );

            if ( $memberdata && $shop_order = $this->Model_Shop->get_shop_orders($shop_order_id) ) {
                // Send Email
                $mail = $this->ddm_email->send_email_shop_order( $memberdata, $shop_order );
            }
                
            $response = array(
                'status'  => 'success',
                'message' => 'Pendaftaran dan order paket produk berhasil! Silahkan cek email anda untuk informasi lebih lanjut',
                'url'     => 'confirm/payment/' . ddm_encrypt($shop_order_id),
            ); die(json_encode($response));
        }
    }
    # -------------------------------------------------------------------------


    /*
    |--------------------------------------------------------------------------
    | SAVE Order Agent
    |--------------------------------------------------------------------------
    */
    public function saveOrderAgent()
    {
        if (!$this->input->is_ajax_request()) { exit('No direct script access allowed'); }

        if ( ! $auth = auth_redirect( $this->input->is_ajax_request() ) ) {
            $this->cart->destroy();
            remove_code_discount();
            remove_code_seller();

            $response = array(
                'status'    => 'access_denied',
                'message'   => 'Silahkan Login sebagai Agen untuk dapat pesan produk !',
                'url'       => base_url('login')
            ); die(json_encode($response));
        }

        $payment_type       = 'order'; //diset 
        $id_agent_data      = sanitize($this->input->post('agent_data'));
        $agent              = "";
        if( !empty($id_agent_data) ){
            $agent          = ddm_get_memberdata_by_id($id_agent_data);
        }

        $current_member     = ddm_get_current_member();
        $memberdata         = $current_member;
        $is_admin           = as_administrator($current_member);
        $datetime           = date('Y-m-d H:i:s');
        $stock              = $this->Model_Omzet_History->get_product_active($current_member->id);
        $provincedata       = ddm_provinces($memberdata->province);
        $provincearea       = $provincedata->province_area;

        if( !empty($agent) ){
            $provincedata   = ddm_provinces($agent->province);
            $provincearea   = $provincedata->province_area;
        }

        if ( $is_admin ) {
            $response = array(
                'status'  => 'failed',
                'message' => 'Admin tidak dapat pesan produk. Silahkan Login sebagai Agen untuk dapat pesan produk !'
            ); die(json_encode($response));
        }
        
        ## Data Shopping ---------------------------------------------------
        $cart_contents = $this->cartContents();
        if ( isset($cart_contents['has_error']) && $cart_contents['has_error'] ) {
            $response = array(
                'status'    => 'failed',
                'message'   => 'Cart Error!',
                'redirect'  => 'reload',
            ); die(json_encode($response));
        }

        if (!$cart_contents) {
            $response = array(
                'status'   => 'failed',
                'message'  => 'Order produk tidak berhasil. Data produk tidak ditemukan. Silahkan Reload!',
                'redirect' => 'reload',
            ); die(json_encode($response));
        }

        $data_product       = array();
        $data_package       = array();
        $total_qty          = 0;
        $total_point        = 0;
        $total_bv           = 0;
        $product_price      = 0;
        $product_bv         = 0;
        
        foreach ($cart_contents['data'] as $item) {
            $productId      = isset($item['product_id']) ? $item['product_id'] : 0;
            $qty            = isset($item['qty']) ? $item['qty'] : 0;
            if ( !$productId && !$qty ) { continue; }

            $product_point      = 0;
            $subtotal_point     = 0;
            $total_qty         += $qty;
            $total_point       += $subtotal_point;
            $package_name       = isset($item['product_name']) ? $item['product_name'] : '';
            $product_details    = array();
            
            if ( $getPackage = ddm_product_package('id', $productId) ) {
                $package_name   = isset($getPackage->name) ? $getPackage->name : $package_name;
                $productDetail  = isset($getPackage->product_details) ? $getPackage->product_details : false;
                $productDetail  = ($productDetail) ? maybe_unserialize($productDetail) : false; 

                if ( $productDetail ) {
                    foreach ($productDetail as $row) {
                        $product_id     = isset($row['id']) ? $row['id'] : 0;
                        $product_qty    = isset($row['qty']) ? $row['qty'] : 0;
                        $product_price  = isset($row['price'.$provincearea]) ? $row['price'.$provincearea] : 0;
                        $subtotal       = ($product_qty * $product_price);
                        $bv             = isset($row['bv'.$provincearea]) ? $row['bv'.$provincearea] : 0;
                        $product_bv     = $bv;
                        
                        $getProduct = ddm_products($product_id, false);
                        $product_details[$product_id] = array(
                            'id'            => $product_id,
                            'name'          => isset($getProduct->name) ? $getProduct->name : '',
                            'qty'           => $product_qty,
                            'price'         => $product_price,
                            'subtotal'      => $subtotal,
                            'total_qty'     => $total_qty,
                            'total_price'   => ($product_price * $total_qty),
                            'bv'            => $bv,
                            'total_bv'      => ($bv * $total_qty),
                        );

                        $total_bv += ($bv * $total_qty);
                    }
                }
            }

            $data_product[] = array(
                'id'            => $productId,
                'qty'           => $qty,
                'price'         => $item['cart_price'], // price after discount
                'price_ori'     => $item['product_price'], // original price
                'name'          => $package_name,
                'weight'        => $item['product_weight'],
                'point'         => $product_point,
                'total_point'   => $subtotal_point,
                'package_point' => $subtotal_point,
                'bv'            => $total_bv,
                'product_detail'=> $product_details,
                'discount'      => $item['product_price'] - $item['cart_price'],
                'discount_qty'  => ($qty >= $item['disc_min_qty']) ? $item['disc_amount'] : 0,
            );

            // Check Stock Product
            if ( $auth ) {
                if ( $getProduct = ddm_products($product_id, false) ) {
                    if ( $qty > $getProduct->stock ) {
                        $response = array(
                            'status'  => 'failed', 
                            'message' => 'Stok '.$getProduct->name.' tidak tersedia. Silahkan kembali ke cart'
                        ); die(json_encode($response));
                    }
                }
            }
        }

        if ( ! $data_product  ) {
            $response = array(
                'status'   => 'failed',
                'message'  => 'Order produk tidak berhasil. Data produk tidak ditemukan. Silahkan Reload!',
                'redirect' => 'reload',
            ); die(json_encode($response));
        }
        
        // -------------------------------------------------
        // Check Minimal Order
        // -------------------------------------------------
        $cfg_min_order      = config_item('min_order_agent');
        $cfg_min_order      = $cfg_min_order ? $cfg_min_order : 0;
        if( $total_qty < $cfg_min_order ){
            $response = array(
                'status'   => 'failed',
                'message'  => 'Jumlah Produk kurang dari minimal pemesanan. Minimal pemesanan adalah '.$cfg_min_order.' Liter !',
                'redirect' => 'reload',
            ); die(json_encode($response));
        }

        $qty_free_shipping  = get_option('qty_package_free_shipping');
        $qty_free_shipping  = $qty_free_shipping ? $qty_free_shipping : 0;
        
        ## Validation Global --------------------------------------------------------------
        $this->form_validation->set_rules('shipping_name', 'Name', 'required|min_length[3]');
        $this->form_validation->set_rules('shipping_email', 'Email', 'valid_email|required|min_length[3]');
        $this->form_validation->set_rules('shipping_phone', 'No Telp', 'numeric|required');
        $this->form_validation->set_rules('shipping_province', 'Provinsi', 'required');
        $this->form_validation->set_rules('shipping_city', 'Kota', 'required');
        $this->form_validation->set_rules('shipping_subdistrict', 'Kecamatan', 'required');
        $this->form_validation->set_rules('shipping_address', 'Alamat', 'required');
        $this->form_validation->set_rules('shipping_postcode', 'Kodepos', 'numeric');

        $free_shipping  = true;
        if ( $qty_free_shipping > $total_qty ) {
            $free_shipping  = false;
            $this->form_validation->set_rules('weight', 'Weight', 'numeric|required');
            $this->form_validation->set_rules('courier', 'Kurir', 'required');
            $this->form_validation->set_rules('courier_service', 'Layanan Kurir', 'required');
            $this->form_validation->set_rules('payment_method', 'Metode Pembayaran', 'required');
        }

        $this->form_validation->set_error_delimiters('', '');
        if ($this->form_validation->run() == FALSE) {
            $response = array(
                'status'    => 'failed',
                'message'   => validation_errors(),
            ); die(json_encode($response));
        } 

        ## Input ---------------------------------------------------------
        $name               = sanitize($this->input->post('shipping_name'));
        $phone              = sanitize($this->input->post('shipping_phone'));
        $email              = sanitize($this->input->post('shipping_email'));
        $address            = sanitize($this->input->post('shipping_address'));
        $postcode           = sanitize($this->input->post('shipping_postcode'));
        $weight             = sanitize($this->input->post('weight'));
        $weight             = $weight ? $weight : sum_cart_option('product_weight');
        $courier            = sanitize($this->input->post('courier'));
        $paymentMethod      = sanitize($this->input->post('payment_method'));
        $allProductActive   = sanitize($this->input->post('all_product_active'));

        // Cek Personal Sales
        $personalSales      = $this->Model_Omzet_History->get_personal_sales($memberdata->id, date('Y-m'));
        if(!empty($personalSales)){
            if($personalSales->total_qty > 0){
                $allProductActive   = true;
            }else{
                $allProductActive   = false;
            }
        }else{
            $allProductActive   = false;
        }

        if ($phone[0] == 0) { // if phone have 0 in first letter
            $phone          = $phone;
        } else {
            $phone          = substr_replace($phone, 0, 0, 0); // then add 0 in first letter
        }

        if ($this->session->userdata('promo_applied')) {
            $code_discount  = $this->session->userdata('promo_code');
        } else {
            $code_discount  = NULL;
        }

        $province           = sanitize($this->input->post('shipping_province')); 
        $city               = sanitize($this->input->post('shipping_city')); 
        $subdistrict        = sanitize($this->input->post('shipping_subdistrict')); 
        $courier_info       = $free_shipping ? array('', 0) : explode(',', $this->input->post('courier_service', TRUE)); // sperate value by comma | Array doesn't need sanitize, instead using TRUE
        
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

        $courier_serv       = $courier_info[0]; // service
        $courier_cost       = $courier_info[1]; // cost

        $cfg_pack_qty       = get_option('cfg_package_qty');
        $cfg_pack_qty       = $cfg_pack_qty ? $cfg_pack_qty : 0;
        $cfg_pack_point     = get_option('cfg_package_point');
        $cfg_pack_point     = $cfg_pack_point ? $cfg_pack_point : 0;
        if ( $cfg_pack_qty > 0 && $total_qty >= $cfg_pack_qty ) {
            $package_point  = floor($total_qty / $cfg_pack_qty);  
            $total_point    = $package_point * $cfg_pack_point;      
        }
        
        // Check Agent Produk Stock if Payment Method is Agent
        $status             = 0;
        if( $paymentMethod == 'agent' ){
            $stock = $this->Model_Omzet_History->get_product_active($agent->id);
            
            if( !$stock || $stock == 0 ){
                $response = array(
                    'status'  => 'failed',
                    'message' => 'Order Produk tidak berhasil. Agen tidak memiliki produk aktif!'
                ); die(json_encode($response));
            }
            
            if( $total_qty > $stock ){
                $response = array(
                    'status'  => 'failed',
                    'message' => 'Order Produk tidak berhasil. Produk Aktif Agen tidak mencukupi jumlah produk order!'
                ); die(json_encode($response));
            }
        }

        // -------------------------------------------------
        // Begin Transaction
        // -------------------------------------------------
        $this->db->trans_begin();

        $invoice            = $paymentMethod == 'agent' ? generate_receipt() : generate_invoice();
        $code_unique        = generate_uniquecode();
        $total_omzet        = total_promo('amount');
        $total_price        = $this->cart->total();
        $total_payment      = $total_omzet + $courier_cost + $code_unique;

        $data_shop_order    = array(
            'invoice'           => $invoice,
            'id_member'         => $memberdata->id,
            'id_agent'          => isset($agent->id) ? $agent->id : '',
            'products'          => maybe_serialize($data_product),
            'total_qty'         => $total_qty,
            'weight'            => $weight,
            'subtotal'          => $total_price,
            'shipping'          => $courier_cost,
            'unique'            => $code_unique,
            'discount'          => total_promo('discount'),
            'voucher'           => $code_discount,
            'total_payment'     => $total_payment,
            'total_bv'          => $total_bv,
            'payment_method'    => $paymentMethod == 'agent' ? 'product' : 'transfer',
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
            'service'           => $courier_serv,
            'status'            => $status,
            'all_product_active'=> $allProductActive ? $allProductActive : 0,
            'created_by'        => $memberdata->username,
            'datecreated'       => $datetime,
            'datemodified'      => $datetime,
        );

        // Check Omzet Member
        $condition_omzet        = array('status' => 'perdana');
        if ( $omzetperdana      = $this->Model_Member->get_member_omzet_by('id_member', $memberdata->id, $condition_omzet) ) {
            $status_omzet       = 'order';
        } else {
            $status_omzet       = 'perdana';
        }
        $status_omzet           = 'order';
        $data_shop_order['type']= $status_omzet;

        // -------------------------------------------------
        // Save Shop Order
        // -------------------------------------------------
        $shop_order_id = $this->Model_Shop->save_data_shop_order($data_shop_order);
        if( ! $shop_order_id ){
            // Rollback Transaction
            $this->db->trans_rollback();
            $response = array(
                'status'  => 'failed',
                'message' => 'Order Produk tidak berhasil. Terjadi kesalahan data transaksi ',
            ); die(json_encode($response)); // Set JSON data
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
                            $response = array('status'  => 'failed', 'message' => 'Gagal disimpan (updstock)');
                            die(json_encode($response));
                        }
                    }

                    $price_ori      = isset($get_product->{"price_agent".$provincearea}) ? $get_product->{"price_agent".$provincearea} : 0;
                    $discount       = ( $price_ori > $row['price'] ) ? ($price_ori - $row['price']) : 0;
                    $data_order_detail[$no] = array(
                        'id_shop_order' => $shop_order_id,
                        'id_member'     => $memberdata->id,
                        'package'       => $package_id,
                        'package_bv'    => $row['total_bv'],
                        'product'       => $product_id,
                        'product_bv'    => $row['bv'],
                        'qty'           => $row['total_qty'],
                        'amount'        => $price_ori,
                        'amount_order'  => $row['price'],
                        'total'         => $row['total_price'],
                        'total_bv'      => $row['total_bv'],
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
            // Rollback Transaction
            $this->db->trans_rollback();
            $response = array(
                'status'  => 'failed',
                'message' => 'Order Produk tidak berhasil. Terjadi kesalahan data transaksi detail'
            ); die(json_encode($response));
        }

        // -------------------------------------------------
        // Save Shop Order Detail
        // -------------------------------------------------
        $amount_pa          = 0;
        $bv_pa              = 0;
        foreach ($data_order_detail as $row) {
            $order_detail_saved = $this->Model_Shop->save_data_shop_order_detail($row);
            if ( !$order_detail_saved ) {
                // Rollback Transaction
                $this->db->trans_rollback();
                $response = array(
                    'status'  => 'failed',
                    'message' => 'Order Produk tidak berhasil. Terjadi kesalahan data transaksi detail'
                ); die(json_encode($response));
            }else{
                $amount_pa += $total_qty * $row['amount_order'];
                $bv_pa     += $row['total_bv'];
            }
        }

        ## Order Success -------------------------------------------------------
        $this->db->trans_commit();
        $this->db->trans_complete(); //  complete database transactions  
        $this->cart->destroy();

        remove_code_discount();
        remove_code_seller();

        ## Save Log Checkout ----------------------------------------------------
        ddm_log_action( 'CHECKOUT', $invoice, $memberdata->username, json_encode(array('cookie'=>$_COOKIE, 'status'=>'SUCCESS', 'shop_order_id'=>$shop_order_id)) );

        if ( $memberdata && $shop_order = $this->Model_Shop->get_shop_orders($shop_order_id) ) {
            // Send Email for Agent who has order
            if( $paymentMethod != 'transfer' ){ // transfer untuk ke perusahaan
                $mail = $this->ddm_email->send_email_shop_order( $memberdata, $shop_order );
            }
            // Send Email for Agent who get order
            if( $paymentMethod == 'agent' ){
                $mail = $this->ddm_email->send_email_shop_order_to_agent( $agent, $shop_order );
            }
        }

        $response = array(
            'status'  => 'success',
            'message' => 'Order berhasil! Silahkan cek email anda untuk informasi lebih lanjut',
            'url'     => 'confirm/payment/' . ddm_encrypt($shop_order_id),
        ); die(json_encode($response));
    }
    # -------------------------------------------------------------------------

    /*
    |--------------------------------------------------------------------------
    | Search Constumer function.
    |--------------------------------------------------------------------------
    */
    function actionSearchCustomer()
    {
        if (!$this->input->is_ajax_request()) { exit('No direct script access allowed'); }

        $phone = $this->input->post('phone');
        if ( $customerData = $this->Model_Shop->get_customer_by('phone', $phone) ) {
            $response = array(
                'status'  => 'success',
                'message' => 'Data konsumen anda ditemukan',
                'data'    => $customerData
            ); die(json_encode($response));
        } else {
            $response = array(
                'status'  => 'failed',
                'message' => 'Data konsumen anda tidak ditemukan!',
            ); die(json_encode($response));
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Confirm Payment Shop | Page
    |--------------------------------------------------------------------------
    */
    public function pageConfirmPayment($id)
    {
        if ( $member = auth_redirect( true ) ) {
            $member  = ddm_get_current_member();
        }

        $id     = ddm_decrypt($id);
        $order  = $this->Model_Shop->get_shop_order_by('id', $id);

        if ($order) {

            $data['title']      = COMPANY_NAME . ' | Confirm Payment';
            $data['content']    = 'pages/shop/confirm_payment';
            $data['member']     = $member;
            $data['order']      = $order;
            
            if ( $carabiner = config_item('cfg_carabiner') ) {
                $js = array(
                    array(BE_JS_PATH . 'pages/confirm_payment.js?ver=' . JS_VER_PAGE),
                );
                $this->carabiner->group('custom_js', array('js' => $js));
            }else{
                $data['scripts']    = array(
                    BE_JS_PATH . 'pages/confirm_payment.js?ver=' . JS_VER_PAGE,
                );
            }

            $this->load->view(VIEW_SHOP . 'template', $data);
        } else {
            redirect(base_url());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Confirm Payment Shop | Page
    |--------------------------------------------------------------------------
    */
    public function pageConfirmPaymentCustomer($id)
    {
        if ( $member = auth_redirect( true ) ) {
            $member  = ddm_get_current_member();
        }

        $id     = ddm_decrypt($id);
        $order  = $this->Model_Shop->get_shop_order_customer_by('id', $id);

        if ($order) {
            $agent              = ddm_get_memberdata_by_id($order->id_member);

            $data['title']      = COMPANY_NAME . ' | Confirm Payment';
            $data['content']    = 'pages/shop/confirm_payment_customer';
            $data['member']     = $member;
            $data['agent']      = $agent;
            $data['order']      = $order;

            $js = array(
                array(BE_JS_PATH . 'pages/confirm_payment.js?ver=' . JS_VER_PAGE),
            );
            $this->carabiner->group('custom_js', array('js' => $js));

            $this->load->view(VIEW_FRONT . 'template', $data);
        } else {
            redirect(base_url());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Invoice information
    |--------------------------------------------------------------------------
    */
    function pageInvoice($invoice = '' )
    {
        if ( $member = auth_redirect( true ) ) {
            $member         = ddm_get_current_member();
        }

        $order      = false;
        $invoice    = $invoice ? ddm_decrypt($invoice) : false;
        if ( $invoice ) {
            $order  = $this->Model_Shop->get_shop_orders($invoice);
        }

        $data['member']     = $member;
        if ($order) {
            $data['id_order'] = $order->id;
            $this->load->view('template/shop/invoice', $data);
        } else {
            $data['message'] = 'Invoice Not Found';
            $this->load->view('errors/not_found', $data); // Error page
        }
    }

    function pageInvoiceCustomer($invoice = '')
    {
        if ( $member = auth_redirect( true ) ) {
            $member         = ddm_get_current_member();
        }

        $order      = false;
        $invoice    = $invoice ? ddm_decrypt($invoice) : false;
        if ( $invoice ) {
            $order = $this->Model_Shop->get_shop_order_customer_by('id', ddm_decrypt($invoice));
        }

        $data['member']     = $member;
        if ($order) {
            $data['id_order'] = $order->id;
            $this->load->view('template/shop/invoice_customer', $data);
        } else {
            $data['message'] = 'Invoice Not Found';
            $this->load->view('errors/not_found', $data); // Error page
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Fetch product to show in select2
    |--------------------------------------------------------------------------
    */
    public function fetchProduct()
    {
        $searchTerm = $this->input->post('search');
        $response   = $this->Model_Shop->fetchProduct($searchTerm);
        echo json_encode($response);
    }
}

/* End of file Shop.php */
/* Location: ./app/controllers/Shop.php */