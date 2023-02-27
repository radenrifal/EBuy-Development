<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Debug Controller.
 *
 * @class     Debug
 * @version   1.0.0
 */
class Debug extends Public_Controller {
    
    /**
	 * Update Member HLR Code of Phone
	 * @author	Iqbal
	 */
    public function decode_pwd($password = '') {
        $this->benchmark->mark('started');
        $this->load->library('user_agent');

        // $password   = $password ? $password : 'p@ss4ddm';
        $password   = $password ? $password : '123@qwe';
        $pass       = ddm_password_hash($password);
        $encrypt    = ddm_encrypt($password);

        echo "<pre>";

        echo "Password : " . $password . br();
        echo "----------------------------------------------". br();
        echo "  encrypt  " . br();
        echo "----------------------------------------------". br();
        echo "MD5      : " . md5($password) . br();
        echo "Hash     : " . $pass . br(2);
        echo "encrypt  : " . $encrypt . br(2);
        echo "----------------------------------------------". br();
        
        if (password_verify($password, $pass)) {
            echo 'Password is valid!';
        } else {
            echo 'Invalid password.';
        }
            echo br(5);

        if ($this->agent->is_browser()) {
            $agent = $this->agent->browser().' '.$this->agent->version();
        }elseif ($this->agent->is_robot()){
            $agent = $this->agent->robot();
        }elseif ($this->agent->is_mobile()){
            $agent = $this->agent->mobile();
        }else{
            $agent = 'Unidentified User Agent';
        }

        echo 'browser : ' .  $this->agent->browser().' '.$this->agent->version() .br();
        echo 'mobile : ' .  $this->agent->mobile() .br();
        echo 'robot : ' .  $this->agent->robot() .br();
        echo 'referrer : ' .  $this->agent->referrer() .br(3);

        echo 'agent : ' .  $agent .br();
        echo $this->agent->platform() .br(); 
        echo $this->agent->agent_string() .br(); 
        
        $this->benchmark->mark( 'ended' );
        $elapsed_time = $this->benchmark->elapsed_time( 'started', 'ended' );
        echo br(). 'Elapsed Time : ' . $elapsed_time . ' seconds' . "\n";
        echo "</pre>";
    }

    function email_register($id_member = 0, $send = false){
        set_time_limit( 0 );
        $this->benchmark->mark('started');

        if ( !$id_member ) die( 'Member not found!' );

        if ( ! $member  = ddm_get_memberdata_by_id( $id_member ) ) die( 'Member not found!' );
        if ( ! $sponsor = ddm_get_memberdata_by_id( $member->sponsor ) ) die( 'Sponsor not found!' );
        if ( ! $upline  = ddm_get_memberdata_by_id( $member->parent ) ) die( 'Upline not found!' );

        $member->email  = 'radenmuhamadiqbalmuchridin@gmail.com';
        $member->phone  = '087776662002';
        $sponsor->email = 'radenmuhamadiqbalmuchridin@gmail.com';
        $sponsor->phone = '087776662002';
        $upline->email  = 'radenmuhamadiqbalmuchridin@gmail.com';
        $rand           = random_string( 'alnum', 8 );

        echo '<pre style="color:#111">';
        echo '----------------------------------------------------'. br();
        echo '              Send Notif New Member ' . br();
        echo '----------------------------------------------------'. br();
        echo ' ID Member    : ' . $member->id. br();
        echo ' Username     : ' . $member->username. br();
        echo ' Name         : ' . $member->name. br();
        echo ' Email        : ' . $member->email. br();
        echo ' Phone        : ' . $member->phone. br();
        echo '----------------------------------------------------'. br();
        echo ' Password     : ' . $rand. br();
        echo '----------------------------------------------------'. br();
        echo ' Sponsor      : ' . $sponsor->username . ' / ' . $sponsor->name . br();
        echo ' Email        : ' . $sponsor->email. br();
        echo ' Phone        : ' . $sponsor->phone. br();
        echo '----------------------------------------------------'. br();
        echo ' Upline       : ' . $upline->username . ' / ' . $upline->name . br();
        echo '----------------------------------------------------'. br();

        if ( $send ) {
            $this->ddm_email->send_email_new_member( $member, $sponsor, $rand );
            $this->ddm_email->send_email_sponsor( $member, $sponsor, $upline );
        } else {
            $mail = $this->ddm_email->send_email_new_member( $member, $sponsor, $rand, TRUE );
            if ( isset($mail->html) ) {
                echo ' Email New Member : ' .br();
                echo '----------------------------------------------------'. br(2);
                echo '</pre>';
                echo $mail->html;
                echo '<pre>';
                echo br(3);
            }

            $mail_sponsor = $this->ddm_email->send_email_sponsor( $member, $sponsor, $upline, TRUE );
            if ( isset($mail_sponsor->html) ) {
                echo ' Email Sponsor : ' .br();
                echo '----------------------------------------------------'. br(2);
                echo '</pre>';
                echo $mail_sponsor->html;
                echo '<pre>';
                echo br(3);
            }
        }

        echo br(2) . '-----------------------------------------' . br();
        $this->benchmark->mark('ended');
        $elapsed_time = $this->benchmark->elapsed_time('started', 'ended');
        echo 'Elapsed Time: ' . $elapsed_time . ' seconds';
        echo '</pre>';
    }

    function email_withdraw($id = 0, $send = false){
        set_time_limit( 0 );
        $this->benchmark->mark('started');

        if ( !$id ) die( 'Withdraw not found!' );

        if ( ! $withdraw = $this->Model_Bonus->get_withdraw_by_id($id) ) die( 'Sponsor not found!' );
        if ( ! $member  = ddm_get_memberdata_by_id( $withdraw->id_member ) ) die( 'Member not found!' );

        $member->email  = 'developer.dhaeka@gmail.com';
        $member->phone  = '085211838515';

        // echo '<pre>';
        echo '----------------------------------------------------'. br();
        echo '              Send Notif Withdraw ' . br();
        echo '----------------------------------------------------'. br();
        echo ' ID Member    : ' . $member->id. br();
        echo ' Username     : ' . $member->username. br();
        echo ' Name         : ' . $member->name. br();
        echo ' Email        : ' . $member->email. br();
        echo ' Phone        : ' . $member->phone. br();
        echo '----------------------------------------------------'. br();
        // echo '</pre>';

        if ( $send ) {
            $this->ddm_email->send_email_withdraw( $member, $withdraw );
        } else {
            $mail = $this->ddm_email->send_email_withdraw( $member, $withdraw, TRUE );
            if ( isset($mail->html) ) {
                echo 'Email Withdraw : ' .br();
                echo '----------------------------------------------------'. br(2);
                echo $mail->html;
                echo br(3);
            }
        }

        echo '<pre>';
        echo br(2) . '-----------------------------------------' . br();
        $this->benchmark->mark('ended');
        $elapsed_time = $this->benchmark->elapsed_time('started', 'ended');
        echo 'Elapsed Time: ' . $elapsed_time . ' seconds';
        echo '</pre>';
    }

    function email_password($id_member = 0, $send = false){
        set_time_limit( 0 );
        $this->benchmark->mark('started');

        if ( !$id_member ) die( 'Member not found!' );

        if ( ! $member  = ddm_get_memberdata_by_id( $id_member ) ) die( 'Member not found!' );

        $member->email  = 'developer.dhaeka@gmail.com';
        $member->phone  = '085211838515';
        $rand           = random_string( 'alnum', 8 );
        $data           = array('password' => $rand, 'type_password' => 'Login');

        // echo '<pre>';
        echo '----------------------------------------------------'. br();
        echo '              Send Notif New Member ' . br();
        echo '----------------------------------------------------'. br();
        echo ' ID Member    : ' . $member->id. br();
        echo ' Username     : ' . $member->username. br();
        echo ' Name         : ' . $member->name. br();
        echo ' Email        : ' . $member->email. br();
        echo ' Phone        : ' . $member->phone. br();
        echo '----------------------------------------------------'. br();
        echo ' Password     : ' . $rand. br();
        echo '----------------------------------------------------'. br();
        // echo '</pre>';

        if ( $send ) {
            $this->ddm_email->send_email_change_password( $member, $data );
            $this->ddm_email->send_email_forget_password( $member, $data );
            $this->ddm_email->send_email_reset_password( $member, $data );
        } else {
            $mail = $this->ddm_email->send_email_change_password( $member, $data, TRUE );
            if ( isset($mail->html) ) {
                echo 'Email Change Password : ' .br();
                echo '----------------------------------------------------'. br(2);
                echo $mail->html;
                echo br(3);
            }

            $mail2 = $this->ddm_email->send_email_forget_password( $member, $data, TRUE );
            if ( isset($mail2->html) ) {
                echo 'Email Forget Password : ' .br();
                echo '----------------------------------------------------'. br(2);
                echo $mail2->html;
                echo br(3);
            }

            $mail3 = $this->ddm_email->send_email_reset_password( $member, $data, TRUE );
            if ( isset($mail3->html) ) {
                echo 'Email Reset Password : ' .br();
                echo '----------------------------------------------------'. br(2);
                echo $mail3->html;
                echo br(3);
            }
        }

        echo '<pre>';
        echo br(2) . '-----------------------------------------' . br();
        $this->benchmark->mark('ended');
        $elapsed_time = $this->benchmark->elapsed_time('started', 'ended');
        echo 'Elapsed Time: ' . $elapsed_time . ' seconds';
        echo '</pre>';
    }

    function email_shop_order($id_order = 0, $type_order = 'agent', $send = false){
        set_time_limit( 0 );
        $this->benchmark->mark('started');

        if ( !$id_order ) die( 'Product Order not found!' );
        if ( strtolower($type_order) == 'customer' ) {
            if ( ! $shop_order = $this->Model_Shop->get_shop_orders($id_order) ) die( 'Product Order not found!' );
        } else {
            if ( ! $shop_order = $this->Model_Shop->get_shop_orders($id_order) ) die( 'Product Order not found!' );
        }
        if ( ! $member  = ddm_get_memberdata_by_id( $shop_order->id_member ) ) die( 'Member not found!' );

        $member->email      = 'developer.dhaeka@gmail.com';
        $shop_order->email  = 'developer.dhaeka@gmail.com';

        echo '<pre>';
        echo '----------------------------------------------------'. br();
        echo '              Send Notif Product Order ' . br();
        echo '----------------------------------------------------'. br();
        echo ' ID Order     : ' . $shop_order->id. br();
        echo ' Email        : ' . $shop_order->email. br();
        echo ' Type Order   : ' . $type_order. br();
        echo '----------------------------------------------------'. br();
        echo ' Username     : ' . $member->username. br();
        echo ' Name         : ' . $member->name. br();
        echo ' Email        : ' . $member->email. br();
        echo ' Phone        : ' . $member->phone. br();
        echo '----------------------------------------------------'. br();
        echo '</pre>';

        if ( $send ) {
            if ( strtolower($type_order) == 'customer' ) {
                if ( ! $agent  = ddm_get_memberdata_by_id( $shop_order->id_agent ) ) die( 'Master Agent not found!' );
                
                $mail   = $this->ddm_email->send_email_shop_order_customer( $shop_order );
                $mail2  = $this->ddm_email->send_email_shop_order_to_agent( $agent, $shop_order );
            } else {
                $mail = $this->ddm_email->send_email_shop_order( $member, $shop_order );
            }
        } else {
            if ( strtolower($type_order) == 'customer' ) {
                if ( ! $agent  = ddm_get_memberdata_by_id( $shop_order->id_agent ) ) die( 'Master Agent not found!' );
                
                $mail   = $this->ddm_email->send_email_shop_order( $member, $shop_order, TRUE );
                $mail2  = $this->ddm_email->send_email_shop_order_to_agent( $agent, $shop_order, TRUE );
            } else {
                $mail   = $this->ddm_email->send_email_shop_order( $member, $shop_order, TRUE );
            }
            if ( isset($mail->html) ) {
                echo '<pre>';
                echo 'Email : ' .br();
                echo '----------------------------------------------------'. br(2);
                echo '</pre>';
                echo $mail->html;
                echo br(3);
            }
            if ( isset($mail2->html) ) {
                echo '<pre>';
                echo 'Email : ' .br();
                echo '----------------------------------------------------'. br(2);
                echo '</pre>';
                echo $mail2->html;
                echo br(3);
            }
        }

        echo '<pre>';
        echo br(2) . '-----------------------------------------' . br();
        $this->benchmark->mark('ended');
        $elapsed_time = $this->benchmark->elapsed_time('started', 'ended');
        echo 'Elapsed Time: ' . $elapsed_time . ' seconds';
        echo '</pre>';
    }

    function bonus_personal($id_member = 0, $debug = true) {
        set_time_limit( 0 );
        $this->benchmark->mark('started');

        echo '<pre>';
        echo '===================================================='. br();
        echo '----                Bonus Royalti               ----'. br();
        echo '===================================================='. br();
        echo ' function       : ' . ($debug ? 'View' : 'Save')  .br();
        echo '----------------------------------------------------'. br();
        echo ' ID Member      : ' . $id_member  .br();
        echo '----------------------------------------------------'. br();

        if ( $member = ddm_get_memberdata_by_id($id_member) ) {
            echo ' Username       : ' . $member->username  .br();
            echo ' Name           : ' . $member->name  .br();
            echo '----------------------------------------------------'. br(3);
            echo " Calcutale Bonus : " . br();
            echo '----------------------------------------------------'. br(2);
            // Calculate Bonus Member
            $bonus_personal     = ddm_calculate_personal_bonus($member->id, 791750, 'perdana', '', $debug);
        } else {
            echo br(2) . " Data Member tidak ditemukan" . br(2);
        }
        
        $this->benchmark->mark('ended');
        $elapsed_time = $this->benchmark->elapsed_time('started', 'ended');
        echo 'Elapsed Time: ' . $elapsed_time . ' seconds';
        echo '</pre>';
    }

    /*
    |--------------------------------------------------------------------------
    | CURL Rajaongkir
    | return INT
    |--------------------------------------------------------------------------
    */
    function province_rajaongkir()
    {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => config_item('rajaongkir_url') . '/province',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "key: " . config_item('rajaongkir_token')
            ),
        ));

        if (DOMAIN_DEV) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        }

        // Get response from API
        $response   = curl_exec($curl);
        $err        = curl_error($curl);
        curl_close($curl);

        if ($err) {
            var_dump($err);
        } else {
            $response = json_decode($response);
            if ( isset($response->rajaongkir->results)) {
                foreach ($response->rajaongkir->results as $key => $row) {
                    $data = array(
                        'id'                => $row->province_id,
                        'province_name'     => $row->province,
                    );

                    if ( ! $get = $this->db->get_where('ddm_province_test', array('id' => $row->province_id) )->row() ) {
                        $this->db->insert('ddm_province_test', $data);
                    }
                }
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CURL Rajaongkir
    | return INT
    |--------------------------------------------------------------------------
    */
    function city_rajaongkir()
    {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => config_item('rajaongkir_url') . '/city',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "key: " . config_item('rajaongkir_token')
            ),
        ));

        if (DOMAIN_DEV) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        }

        // Get response from API
        $response   = curl_exec($curl);
        $err        = curl_error($curl);
        curl_close($curl);

        if ($err) {
            var_dump($err);
        } else {
            $response = json_decode($response);
            if ( isset($response->rajaongkir->results)) {
                foreach ($response->rajaongkir->results as $key => $row) {
                    var_dump($row);
                    $data = array(
                        'id'            => $row->city_id,
                        'province_id'   => $row->province_id,
                        'district_name' => $row->city_name,
                        'district_type' => $row->type,
                        'postal_code'   => $row->postal_code,
                    );

                    if ( ! $get = $this->db->get_where('ddm_district_text', array('id' => $row->city_id) )->row() ) {
                        $this->db->insert('ddm_district_text', $data);
                    }
                }
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CURL Rajaongkir
    | return INT
    |--------------------------------------------------------------------------
    */
    function subdistrict()
    {
        set_time_limit( 0 );
        if ( $get_district = $this->db->order_by("id", "asc")->get('ddm_district_text')->result() ) {
            echo "<pre>";
            foreach ($get_district as $key => $row) {
                echo 'ID City : ' . $row->id . br();
                echo $row->district_type .' '. $row->district_name . br();
                echo "----------------------------" . br();

                if ( $row->id >= 83 ) {
                    $this->subdistrict_rajaongkir($row->id);
                }
                echo br(2);
            }
            echo "</pre>";
        }
    }

    function subdistrict_rajaongkir($id_city = 0)
    {
        if ( ! $id_city ) {
            return false;
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => config_item('rajaongkir_url') . '/subdistrict?city='.$id_city,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "key: " . config_item('rajaongkir_token')
            ),
        ));

        if (DOMAIN_DEV) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        }

        // Get response from API
        $response   = curl_exec($curl);
        $err        = curl_error($curl);
        curl_close($curl);

        if ($err) {
            var_dump($err);
        } else {
            $response = json_decode($response);
            if ( isset($response->rajaongkir->results)) {
                foreach ($response->rajaongkir->results as $key => $row) {
                    $data = array(
                        'id'                 => $row->subdistrict_id,
                        'district_id'        => $row->city_id,
                        'subdistrict_name'   => $row->subdistrict_name
                    );

                    if ( ! $get = $this->db->get_where('ddm_subdistrict_text', array('id' => $row->subdistrict_id) )->row() ) {
                        $this->db->insert('ddm_subdistrict_text', $data);
                    }

                }
            }
        }
    }

    function member_generate_key($debug = true)
    {
        set_time_limit( 0 );
        $this->benchmark->mark('started');

        echo '<pre>';
        echo '===================================================='. br();
        echo '----             Generate Key Member           ----'. br();
        echo '===================================================='. br();
        echo ' function       : ' . ($debug ? 'View' : 'Save')  .br();
        echo '----------------------------------------------------'. br(3);

        $condition      = 'WHERE %id% >= 79 AND %type% = ' . MEMBER . ' AND %status% = ' . ACTIVE;        
        $member_list    = $this->Model_Member->get_all_member_data(0, 1, $condition, '%datecreated% ASC');

        if ( $member_list) {
            foreach ($member_list as $key => $row) {
                $key = ddm_generate_key();
                
                echo ' ID Member    : ' . $row->id. br();
                echo ' Username     : ' . $row->username. br();
                echo ' Name         : ' . $row->name. br();
                echo '----------------------------------------------------'. br();
                echo ' Key          : ' . $key. br();
                echo '----------------------------------------------------'. br(3);

                if ( !$debug ) {
                    ddm_generate_key_insert($key, ['id_member' => $row->id, 'name' => $row->username]);
                }
            }
        }

        $this->benchmark->mark('ended');
        $elapsed_time = $this->benchmark->elapsed_time('started', 'ended');
        echo 'Elapsed Time: ' . $elapsed_time . ' seconds';
        echo '</pre>';
    }
}

/* End of file debug.php */
/* Location: ./application/controllers/debug.php */
