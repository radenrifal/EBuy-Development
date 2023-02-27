<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Frontend Controller.
 * 
 * @class     Frontend
 * @author    Yuda
 * @version   1.0.0
 */
class Frontend extends Public_Controller
{
    /**
     * Constructor.
     */
    function __construct()
    {
        parent::__construct();
        $this->load->helper('shop_helper');
    }

    /*
    |--------------------------------------------------------------------------
    | Index Page
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $data['title']      = COMPANY_NAME . ' | Home';
        $data['content']    = 'pages/home';

        $this->load->view(VIEW_FRONT . 'template', $data);
    }

    /*
    |--------------------------------------------------------------------------
    | About Us Page
    |--------------------------------------------------------------------------
    */
    public function aboutus()
    {
        $data['title']      = COMPANY_NAME . ' | About Us';
        $data['content']    = 'pages/aboutus';

        $this->load->view(VIEW_FRONT . 'template', $data);
    }

    /*
    |--------------------------------------------------------------------------
    | Product Page
    |--------------------------------------------------------------------------
    */
    public function product()
    {
        $data['title']      = COMPANY_NAME . ' | Our Product';
        $data['content']    = 'pages/product';

        $this->load->view(VIEW_FRONT . 'template', $data);
    }

    /*
    |--------------------------------------------------------------------------
    | Applied Info Promo Page
    |--------------------------------------------------------------------------
    */
    public function pageInfoPromo()
    {
        if ($member = auth_redirect(true)) {
            $member         = ddm_get_current_member();
        }

        $promo      = $this->uri->segment(1);
        $checkPromo = $this->db->get_where(TBL_DISCOUNT, array('code' => $promo))->row();
        if ($checkPromo) {

            //($checkPromo->code, $checkPromo->amount);

            $data['title']      = COMPANY_NAME . ' | Home';
            $data['content']    = 'pages/home';
            $data['member']     = $member;
            $data['uri_promo']  = TRUE;
            $data['get_products'] = $this->db->order_by("id", "desc")->get_where(TBL_PRODUCT, array('status' => 1, 'stock !=' => 0))->result();

            $this->load->view(VIEW_FRONT . 'template', $data);
        } else {
            $this->load->view('errors/not_found'); // Error page
        }
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

        if (empty($auth)) {
            $response = array(
                'status'  => 'failed',
                'message' => 'Please login your account',
            );
            die(json_encode($response));
        }

        $output1    = '';
        $output2    = '';

        $pageID  = 'product';
        $imgpath = PRODUCT_IMG . 'thumbnail/';

        if ($result) {
            $data = isset($result['data']) ? $result['data'] : '';
        } else {
            $condition  = ' AND %status% = 1';
            $totalRow   = 0;
            $data       = false;

            if ($auth) {
                //$get_products = shop_product_package($limit, $start, $condition, '%datemodified% DESC');
                $get_products = shop_search_product($limit, $start, $condition, '%datemodified% DESC');
            } else {
                $get_products = shop_search_product($limit, $start, $condition, '%datemodified% DESC');
            }

            if ($get_products) {
                $totalRow   = isset($get_products['total_row']) ? $get_products['total_row'] : 0;
                $data       = isset($get_products['data']) ? $get_products['data'] : false;
            }
        }

        if ($data) {
            foreach ($data as $row) {
                if ($auth) {
                    $setHTML = $this->sethtmlagentlistproduct($row);
                } else {
                    $setHTML = $this->sethtmlcustomerlistproduct($row);
                }

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
        $provincedata   = ddm_provinces($member->province);
        $provincearea   = $provincedata->province_area;

        // Product already in cart
        $in_cart = FALSE;
        foreach ($this->cart->contents() as $item) {
            if ($item['id'] == $data->id) {
                $in_cart = TRUE;
            }
        }

        if (!$in_cart) {
            $btnCart = '<div class="ps-btn addCart" data-id="' . ddm_encrypt($data->id) . '" data-qty="1" data-type="addcart" style="padding: 7px;width: 100%;border-radius: 25px;">Add Cart</div>';
            // if ($data->stock == 0) {
            //     $btnCart = '<a class="ps-btn" href="javascript:;">Stok Kosong</a>';
            // } else {
            //     $btnCart = '<div class="ps-btn addCart" data-id="' . ddm_encrypt($data->id) . '" data-qty="1" data-type="addcart" style="padding: 7px;width: 100%;border-radius: 25px;">Add Cart</div>';
            // }
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

    /**
     * Set HTML List Product Customer Data function.
     */
    private function sethtmlcustomerlistproduct($data)
    {
        // Product already in cart
        $in_cart = FALSE;
        foreach ($this->cart->contents() as $item) {
            if ($item['id'] == $data->id) {
                $in_cart = TRUE;
            }
        }

        if (!$in_cart) {
            $btnCart = '<div class="ps-btn addCart" data-id="' . ddm_encrypt($data->id) . '" data-qty="1" data-type="addcart" style="padding: 7px;width: 100%;border-radius: 25px;">Add Cart</div>';
            // if ($data->stock == 0) {
            //     $btnCart = '<a class="ps-btn" href="javascript:;">Stok Kosong</a>';
            // } else {
            //     $btnCart = '<div class="ps-btn addCart" data-id="' . ddm_encrypt($data->id) . '" data-qty="1" data-type="addcart" style="padding: 7px;width: 100%;border-radius: 25px;">Add Cart</div>';
            // }
        } else {
            $btnCart = '<a class="ps-btn btn-gocart" href="' . base_url('cart') . '" >Go to cart</a>';
        }

        $price      = product_price($data);
        $discount   = product_discount($data);
        $img_src    = product_image($data->image);
        $imgPath    = '<img class="img-fluid" src="' . $img_src . '">';

        $productCategory = '<a style="margin-bottom:0" class="ps-product__vendor text-capitalize" href="' . base_url('search?category=' . shop_category($data->id_category, 'name')) . '"> ' . shop_category($data->id_category, 'name') . '</a>';

        $output1    = '
        <div class="col-padding col-md-3 col-6 wow fadeIn">
            <div class="ps-product">
                <div class="ps-product__thumbnail">
                    <a href="' . base_url('product/detail/' . $data->slug) . '">
                    ' . $imgPath . '
                    </a>
                </div>
                ' . $productCategory . '
                <div class="ps-product__container desktop">
                    <div class="ps-product__content">
                        <a class="ps-product__title" href="' . base_url('product/detail/' . $data->slug) . '">
                            <span class="text-capitalize">' . $data->name . '</span>
                        </a>
                        <p class="ps-product__price sale">' . ddm_accounting($price) . ' ' . ($discount ? '<small style="float:right">-' . $discount . '</small>' : '') . '</p>
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
                    ' . $productCategory . '
                    <p class="ps-product__price sale">' . ddm_accounting($price) . ' ' . ($discount ? '<small style="float:right">-' . $discount . '</small>' : '') . '</p>

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
    | Show Single Product Only | Page
    |--------------------------------------------------------------------------
    */
    public function pageSingleProduct()
    {
        if ($member = auth_redirect(true)) {
            $member         = ddm_get_current_member();
        }

        $data['page']       = 'Our Product';
        $data['title']      = COMPANY_NAME . ' | ' . $data['page'];
        $data['content']    = 'pages/product/single';
        $data['member']     = $member;

        $js = array(
            array(BE_JS_PATH . 'pages/shop/cart.js?ver=' . JS_VER_PAGE),
        );
        $this->carabiner->group('custom_js', array('js' => $js));

        $this->load->view(VIEW_FRONT . 'template', $data);
    }

    /*
    |--------------------------------------------------------------------------
    | Contact Us | Page
    |--------------------------------------------------------------------------
    */
    public function pageContactUs()
    {
        if ($member = auth_redirect(true)) {
            $member         = ddm_get_current_member();
        }

        $data['title']      = COMPANY_NAME . ' | Contact Us';
        $data['content']    = 'pages/contact_us';
        $data['member']     = $member;

        $this->load->view(VIEW_FRONT . 'template', $data);
    }

    

    /*
    |--------------------------------------------------------------------------
    | Company Profile Page
    |--------------------------------------------------------------------------
    */
    public function pageCompanyProfile()
    {
        if ($member = auth_redirect(true)) {
            $member         = ddm_get_current_member();
        }

        $data['page']       = 'Company Profile';
        $data['title']      = COMPANY_NAME . ' | ' . $data['page'];
        $data['content']    = 'pages/company_profile';
        $data['member']     = $member;
        $data['data']       = $this->db->get_where(TBL_CMS, array('page' => 'company_profile'))->row();

        $this->load->view(VIEW_FRONT . 'template', $data);
    }

    

    /*
    |--------------------------------------------------------------------------
    | Register page
    |--------------------------------------------------------------------------
    */
    public function register_page()
    {
        if ($member = auth_redirect(true)) {
            $member         = ddm_get_current_member();
        }

        $data['title']      = COMPANY_NAME . ' | Register';
        $data['content']    = 'pages/user/register';
        $data['member']     = $member;

        $this->load->view(VIEW_SHOP . 'template', $data);
    }

    /*
    |--------------------------------------------------------------------------
    | Register Form
    |--------------------------------------------------------------------------
    */
    public function register($reg = '')
    {
        /*
        if ($member = auth_redirect(true)) {
            redirect(base_url());
            die();
        }
        */

        if ($reg == 'agent') {
            $page = TRUE;
            $data['breadcrumb'] = 'Pendaftaran Agen';
        }

        if ($page) {
            $totalRow   = 0;
            $packages   = false;
            $get_products = shop_product_package(0, 0, ' AND %status% = 1', '%datecreated% DESC');
            if ($get_products) {
                $totalRow   = isset($get_products['total_row']) ? $get_products['total_row'] : 0;
                $packages   = isset($get_products['data']) ? $get_products['data'] : false;
            }

            $data['title']      = COMPANY_NAME . ' | Register';
            $data['content']    = 'pages/user/register';
            $data['agent']      = check_agent(true);
            $data['member']     = false;
            $data['user']       = false;
            $data['packages']   = $packages;

            $js = array(
                array(BE_JS_PATH . 'components/rajaongkir/address.js?ver=' . JS_VER_PAGE),
                array(BE_JS_PATH . 'pages/shop/register.js?ver=' . JS_VER_PAGE . '&t=' . time()),
            );
            $this->carabiner->group('custom_js', array('js' => $js));

            $this->load->view(VIEW_SHOP . 'template', $data);
        }
    }
}

/* End of file Frontend.php */
/* Location: ./app/controllers/Frontend.php */