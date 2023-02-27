<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * General Controller.
 * 
 * @class     General
 * @author    Yuda
 * @version   1.0.0
 */
class General extends Public_Controller {
	
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
    | Show price tag and numbering format | Result Data as JSON
    |--------------------------------------------------------------------------
    */
	public function accounting($amount)
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$result = ddm_accounting($amount);

		$data = array('data'  => $result);
		die(json_encode($data));
	}

	/*
	|--------------------------------------------------------------------------
	| Encrypt Parameter | Result Data as JSON
	|--------------------------------------------------------------------------
	*/
	public function encryptParam($val)
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$result = ddm_encrypt($val);
		$data 	= array('data'  => $result);
		die(json_encode($data));
	}

	/*
	|--------------------------------------------------------------------------
	| Decrypt Parameter | Result Data as JSON
	|--------------------------------------------------------------------------
	*/
	public function decryptParam($val)
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$result = decrypt_param($val);
		$data 	= array('data'  => $result);
		die(json_encode($data));
	}

	/*
	|--------------------------------------------------------------------------
	| Get Category Name by ID | Result Data as JSON
	|--------------------------------------------------------------------------
	*/
	public function getCategory($id)
	{

		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$category = shop_category($id, 'name');
		$data = array('data' => $category);
		die(json_encode($data));
	}


	/*
	|--------------------------------------------------------------------------
	| Get Product Name by ID | Result Data as JSON
	|--------------------------------------------------------------------------
	*/
	public function getProductName()
	{

		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$id = $this->input->post('id');
		$condition = $this->db->where('id', $id);
		$productName = $this->Model_Shop->get_products($condition)->row();

		$data = array('data' => $productName->name);
		die(json_encode($data));
	}

	/*
    |--------------------------------------------------------------------------
    | Unserialize Data | Result Data as JSON
    |--------------------------------------------------------------------------
    */
	public function unserialize()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$data 	= $this->input->post('val');
		$result = unserialize($data);

		$data = array('data'  => $result);
		die(json_encode($data));
	}

	/*
    |--------------------------------------------------------------------------
    | Info Cart | Result Data as JSON
    |--------------------------------------------------------------------------
    */
	public function infoCart()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		$total_amount = total_promo('amount');
		$total_item  = count($this->cart->contents());

		$result = array(
			'total_amount' => $total_amount,
			'total_item'   => $total_item,
		);

		$data = array('data'  => $result);
		die(json_encode($data));
	}

	/*
    |------------------------------------------------------------------------
    | FUNCTION | Confirm Payment Form
    |------------------------------------------------------------------------
    */
	public function savePaymentEvidence()
	{
		if ( !$this->input->is_ajax_request() ) exit('No direct script access allowed');

        $memberdata         = ddm_get_current_member();

		# Validation
		$this->form_validation->set_rules('bill_bank', 'Bank', 'required');
		$this->form_validation->set_rules('bill_no', 'No. Rekening', 'required');
		$this->form_validation->set_rules('bill_name', 'Nama Pemilik Rekening', 'required');
		$this->form_validation->set_rules('transfer', 'Nominal Anda Transfer', 'required');

		$this->form_validation->set_message('required', '%s harus di isi');
		$this->form_validation->set_error_delimiters('', '');

		if ($this->form_validation->run() == FALSE) {
			$response['message'] = validation_errors();
			die(json_encode($response));
		}

		$auth       = auth_redirect( $this->input->is_ajax_request() );
		$id_order   = sanitize(ddm_decrypt($this->input->post('id_order')));
		$bill_bank  = sanitize(ddm_decrypt($this->input->post('bill_bank')));
		$bill_no    = sanitize($this->input->post('bill_no'));
		$bill_name  = sanitize($this->input->post('bill_name'));
		$transfer  	= sanitize($this->input->post('transfer'));

		if ( !$order = $this->Model_Shop->get_shop_order_by('id', $id_order) ) {
			$response['message'] = 'Pesanan tidak ditemukan.';
			die(json_encode($response));
		}
        
		if ( $order->status == 2 ) {
			$response['message'] = 'Konfirmasi Pesanan tidak berhasil. Pesanan sudah dibatalkan.';
			die(json_encode($response));
		}

		if ( $order->status > 0 ) {
			$response['message'] = 'Konfirmasi Pesanan tidak berhasil. Pesanan sudah diproses.';
			die(json_encode($response));
		}

		$condition 	= array('type' => 'shop');
		if ( $getPayment = $this->Model_Shop->get_payment_evidence_by('id_source', $id_order, $condition, 1) ) {
			$response['message'] = 'Konfirmasi Pesanan tidak berhasil. Pesanan sudah diproses.';
			die(json_encode($response));
		}

        // Config Upload Image
		$invoice 					= str_replace('/', '-', $order->invoice);
        $img_upload                 = true;
        $img_name                   = $invoice.'-'.time();

        $config['upload_path']      = PAYMENT_IMG_PATH;
        $config['allowed_types']    = 'jpg|png|jpeg';
        $config['max_size']         = '2048';
        $config['overwrite']        = true;
        $config['file_name']        = $img_name;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if( ! $this->upload->do_upload("upload_img")) {
            $img_upload             = false;
            $img_msg                = $this->upload->display_errors();
        }
        
		$this->db->trans_begin();

		# Set Data
		$data  				= array(
			'type'       	=> 'shop',
			'id_source' 	=> $order->id,
			'id_member' 	=> $order->id_member,
			'bill_bank'     => strtoupper($bill_bank),
			'bill_no'       => $bill_no,
			'bill_name'     => strtolower($bill_name),
			'amount'        => $transfer,
			'image'         => '',
		);

        if ( $img_upload ) {
            $get_data_img       = $this->upload->data();
            $img_msg            = 'upload success';
            $data['image']      = $get_data_img['file_name'];
        }

		if ( ! $payment_saved_id = $this->Model_Shop->save_data_payment_evidence($data) ) {
			$this->db->trans_rollback();
			$response['message'] = 'Konfirmasi tidak berhasil. Terjadi kesalahan simpan data pada sistem.';
			die(json_encode($response));
		}

		// Complete database transactions
		$this->db->trans_commit();
		$this->db->trans_complete();

		## Sending Notif
		// $subject        = 'Pemberitahuan Konfirmasi Pembayaran';
		// $add_recipient  = array(ADMIN_EMAIL);
		// if ($id_order) {
		// 	$this->send_notif->shop($id_order, $subject, $add_recipient);
		// }

		// if ($id_user) {
		// 	$this->send_notif->confirmPayment($id_user, $subject, $add_recipient);
		// }

        if ( $memberdata && $shop_order = $this->Model_Shop->get_shop_orders($id_order) ) {
            $agentdata = '';
            if( $shop_order->id_agent > 0 ){
                $agentdata = ddm_get_memberdata_by_id($shop_order->id_agent);
            }
            
            $data['invoice'] = $shop_order->invoice;
            $data['payment_method'] = $shop_order->payment_method;
            $data['shipping_method'] = $shop_order->shipping_method;
            $data['name'] = $shop_order->name;
            $data['phone'] = $shop_order->phone;
            $data['email'] = $shop_order->email;
            $data['city'] = $shop_order->city;
            $data['subdistrict'] = $shop_order->subdistrict;
            $data['address'] = $shop_order->address;
            $emailCom = get_option('company_email');
            $data['email_com'] = $emailCom;
            $data['agentdata'] = $agentdata;
            $data['date'] = $shop_order->datecreated;
            // Send Email
            $mail = $this->ddm_email->send_email_confirm_shop_order( $memberdata, $data );
        }

		$response = array('status' => 'success', 'img_msg' => $img_msg, 'message' => 'Konfirmasi pembayaran berhasil. Silahkan tunggu konfirmasi dari admin');
		die(json_encode($response));
	}
    
    /**
     * Product Package Details Function
     */
    function packagedetails(){
        if ( ! $this->input->is_ajax_request() ) { redirect(base_url('dashboard'), 'refresh'); }
        $auth = auth_redirect( $this->input->is_ajax_request() );
        if( !$auth ){
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }
        
        // Set variables
        $current_member     = ddm_get_current_member();
        $datetime           = date('Y-m-d H:i:s');
        $currency           = config_item('currency'); 
        
        // Check ID Product Package
        $id         = $this->input->post('param');
        $id         = trim( ddm_isset($id, '') );
        $prov_id    = $this->input->post('provid');
        $prov_id    = ddm_isset($prov_id, 0);
        
        if( !$id ){
            $data = array('status' => 'error', 'message' => 'ID Paket Produk tidak ditemukan !');
            die(json_encode($data));
        }

        // Check Product Package
        $id = ddm_decrypt($id);
        if ( ! $data_package = ddm_product_package('id', $id) ) {
            $data = array('status' => 'error', 'message' => 'Data Paket Produk tidak ditemukan !');
            die(json_encode($data));
        }
        if ( $data_package->status != 1 ){
            $data = array('status' => 'error', 'message' => 'Data Paket Produk tidak aktif !');
            die(json_encode($data));
        }
        
        // Check Product Data
        $product_ids        = json_decode($data_package->product_ids);
        if( !$product_ids ){
            $data = array('status' => 'error', 'message' => 'Data Produk ID tidak ditemukan !');
            die(json_encode($data));
        }
        $product_data       = ddm_products($product_ids[0]);
        if( !$product_data ){
            $data = array('status' => 'error', 'message' => 'Data Produk tidak ditemukan !');
            die(json_encode($data));
        }
        if( $product_data->status != 1 ){
            $data = array('status' => 'error', 'message' => 'Data Produk tidak aktif !');
            die(json_encode($data));
        }
        $img_src = ASSET_PATH . 'backend/img/no_image.jpg';
        if ( $product_data->image ) {
            $img_src = product_image($row->image);
        }
        
        // Get Agent Package
        $package        = ddm_packages($data_package->package);
        if( !$package ){
            $data = array('status' => 'error', 'message' => 'Data Paket Agent tidak ditemukan !');
            die(json_encode($data));
        }
        
        // Get Province Data
        $province_data  = ddm_provinces($prov_id);
        if( !$province_data ){
            $data = array('status' => 'error', 'message' => 'Data Propinsi tidak ditemukan !');
            die(json_encode($data));
        }
        $province_area  = $province_data->province_area;

        // Set Details Product Data
        $discount       = $package->discount;
        $pack_price     = $data_package->{"price".$province_area};
        $pack_bv        = $data_package->{"bv".$province_area};
        $pack_discount  = ( $pack_price * $discount ) / 100;
        $total_payment  = $pack_price - $pack_discount;
        
        $details        = '
        <div class="form-group row mb-2">
            <label class="col-md-3 col-form-label form-control-label"></label>
            <div class="col-md-9">
                <div class="my-3" style="border: 1px solid #5e72e4">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td scope="col" class="pl-3 pr-2" style="max-width: 150px"> 
                                    <span class="heading text-capitalize d-block mb-2">'.$data_package->name.'</span>
                                    <span class="text-muted d-inline-block" style="min-height: 50px">
                                        Paket ini berisi<br>
                                        '.$data_package->qty.' Liter Product '.$product_data->name.'<br>
                                        Berat: '.$data_package->qty.' Liter
                                    </span><br>
                                    Harga Wilayah 1 : <span class="heading text-warning product_price1">'.ddm_accounting($data_package->price1, $currency).'</span><br />
                                    Harga Wilayah 2 : <span class="heading text-warning product_price2">'.ddm_accounting($data_package->price2, $currency).'</span><br />
                                    Harga Wilayah 3 : <span class="heading text-warning product_price3">'.ddm_accounting($data_package->price3, $currency).'</span>
                                </td>
                                <td scope="col" class="text-right product-quantity py-4 pl-1 pr-2" style="width:140px">
                                    <div class="product-thumbnail mb-2 px-4">
                                        <img class="img-fluid" src="'.$img_src.'" alt="product-img" style="max-width: 78px;"/>
                                    </div>
                                    <input type="hidden" name="product_package_id" class="d-none" value="'.ddm_encrypt($id).'">
                                    <input class="form-control form-control-sm text-center numbermask numberQtyPack" 
                                        name="product_package_qty" value="'.$data_package->qty.'"
                                        title="Qty" pattern="[0-9]*" inputmode="numeric" readonly="readonly" 
                                        style="background-color: transparent !important; border-color: #5e72e4" />
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="py-2" style="border-color: #5e72e4">Subtotal</th>
                                <th class="text-right py-2" style="border-color: #5e72e4"><span class="subtotal">'.ddm_accounting($pack_price, $currency).'</span></th>
                            </tr>
                            <tr>
                                <th class="py-2" style="border-color: #5e72e4">BV</th>
                                <th class="text-right py-2" style="border-color: #5e72e4">
                                    <input type="hidden" name="reg_member_package_omzet" id="reg_member_package_omzet" value="'.$pack_bv.'" />
                                    <span class="subtotal">'.ddm_accounting($pack_bv).'</span>
                                </th>
                            </tr>
                            <tr>
                                <th class="py-2">
                                    '.lang('discount').' <span class="discount_code text-uppercase">('.$discount.'%)</span>
                                </th>
                                <th class="text-right py-2"><span class="discount">'.ddm_accounting($pack_discount, $currency).'</span></th>
                            </tr>
                            <tr>
                                <th class="text-primary py-2" style="border-color: #5e72e4;">
                                    <span class="heading-small">'.lang('total_payment').'</span>
                                </th>
                                <th class="text-primary text-right py-2" style="border-color: #5e72e4;">
                                    <span class="heading total_payment" data-total="'.$total_payment.'" data-totalqty="'.$data_package->qty.'">'.ddm_accounting($total_payment, $currency).'</span>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>';

        // Save Success
        $data = array('status' => 'success', 'message' => $details);
        die(json_encode($data));
    }
} // END OF FILE
