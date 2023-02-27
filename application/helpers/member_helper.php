<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// -------------------------------------------------------------------------
// Member functions helper
// -------------------------------------------------------------------------

if (!function_exists('ddm_get_memberdata_by_id')) {
    /**
     * Get member data by id
     *
     * @param integer $id Member ID
     * @return (object) member data
     */
    function ddm_get_memberdata_by_id($id)
    {
        $CI = &get_instance();
        return $CI->Model_Member->get_memberdata($id);
    }
}

if (!function_exists('ddm_get_memberdata_by')) {
    /**
     * Get member data by 
     *
     * @param integer $id Member ID
     * @return (object) member data
     */
    function ddm_get_memberdata_by($field = '', $value = '', $conditions = array(), $limit = 0)
    {
        $CI = &get_instance();
        return $CI->Model_Member->get_memberdata_by($field, $value, $conditions, $limit);
    }
}

if (!function_exists('ddm_get_memberconfirm_by_downline')) {
    /**
     * Get Member Confirm data by id downline
     *
     * @param integer $id ID Downline
     * @return (object) member confirm data
     */
    function ddm_get_memberconfirm_by_downline($id)
    {
        $CI = &get_instance();
        return $CI->Model_Member->get_member_confirm_by_downline($id);
    }
}

if (!function_exists('as_active_member')) {
    /**
     *
     * Is current member is active member
     * @param Object $member
     * @return bool
     */
    function as_active_member($member)
    {
        if (!empty($member)) {
            return ($member->status == 1);
        }
        return false;
    }
}

if (!function_exists('as_administrator')) {
    /**
     *
     * Is current member is SuperAdmin
     * @param Object $member
     * @return bool
     */
    function as_administrator($member)
    {
        if (!$member) return false;

        $CI = &get_instance();
        $member = $CI->ddm_member->member($member->id);

        return (($member->type == ADMINISTRATOR));
    }
}

if (!function_exists('as_member')) {
    /**
     *
     * Is current user is member
     * @param Object $member
     * @return bool
     */
    function as_member($member)
    {
        if (!$member) return false;

        $CI = &get_instance();
        $member = $CI->ddm_member->member($member->id);

        return (($member->type == MEMBER));
    }
}

if (!function_exists('ddm_generate_password')) {
    /**
     * Generate password for member
     * @author  Yuda
     * @param   int     $length     Random String Length
     * @param   boolean $cap        Capital (Default FALSE)
     * @return  String
     */
    function ddm_generate_password($length = 0, $cap = false)
    {
        $characters     = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        if ($cap) {
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }

        $randomString   = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}

if (!function_exists('ddm_is_username_blacklisted')) {
    /**
     * Is ID member blacklisted
     * @param  string $username ID Member
     * @return boolean           blacklisted
     */
    function ddm_is_username_blacklisted($username)
    {
        if (!$blacklist = get_option('blacklist'))
            return false;

        return in_array($username, ddm_isset($blacklist['usernames'], array()));
    }
}

if (!function_exists('ddm_is_email_blacklisted')) {
    /**
     * Is email blacklisted
     * @param  string $email Email
     * @return boolean        blacklisted
     */
    function ddm_is_email_blacklisted($email)
    {
        if (!$blacklist = get_option('blacklist'))
            return false;

        return in_array($email, ddm_isset($blacklist['emails'], array()));
    }
}

if (!function_exists('ddm_unset_current_member_human')) {
    /**
     * @since 1.0.0
     * @access public
     * @author Yuda
     */
    function ddm_unset_current_member_human()
    {
        $CI = &get_instance();
        return $CI->session->unset_userdata('is_human');
    }
}
if (!function_exists('ddm_unset_clone_member_data')) {
    /**
     * @since 1.0.0
     * @access public
     * @author Yuda
     * @param  Object $memberdata Object member data
     */
    function ddm_unset_clone_member_data($memberdata, $unset_id = false)
    {
        if (!$memberdata) return false;

        $CI = &get_instance();

        if (is_array($memberdata)) $memberdata = (object)$memberdata;

        if ($unset_id) {
            unset($memberdata->id);
        }

        unset($memberdata->password);
        unset($memberdata->password_pin);
        unset($memberdata->sponsor);
        unset($memberdata->parent);
        unset($memberdata->position);
        unset($memberdata->level);
        unset($memberdata->tree);
        unset($memberdata->uniquecode);
        unset($memberdata->last_login);
        unset($memberdata->datecreated);
        unset($memberdata->datemodified);
        unset($memberdata->dateupgrade);

        return $memberdata;
    }
}

if (!function_exists('ddm_generate_tree')) {
    /**
     * Generate tree for member
     * @author  Yuda
     * @param   Int     $id_member  (Required)  Member ID
     * @param   int     $up_tree    (Required)  Upline Tree
     * @return  String
     */
    function ddm_generate_tree($id_member, $up_tree)
    {
        if (!$up_tree) return false;

        if (!is_numeric($id_member)) return false;

        $id_member  = absint($id_member);
        if (!$id_member) return false;

        $tree = $up_tree . '-' . $id_member;

        return $tree;
    }
}

if (!function_exists('ddm_ancestry')) {
    /**
     * Get ancestry data of member
     * @author  Yuda
     * @param   Int     $id_member      (Required)  Member ID
     * @return  Object parent data
     */
    function ddm_ancestry($id_member)
    {
        $CI = &get_instance();
        return $CI->Model_Member->get_ancestry($id_member);
    }
}

if (!function_exists('ddm_ancestry_sponsor')) {
    /**
     * Get ancestry sponsor data of member
     * @author  Yuda
     * @param   Int     $id_member      (Required)  Member ID
     * @return  Object parent data
     */
    function ddm_ancestry_sponsor($id_member)
    {
        $CI = &get_instance();
        return $CI->Model_Member->get_ancestry_sponsor($id_member);
    }
}

if (!function_exists('ddm_my_gen_sponsor')) {
    /**
     * Get ancestry sponsor data of member
     * @author  Yuda
     * @param   Int     $id_member      (Required)  Member ID
     * @return  Object parent data
     */
    function ddm_my_gen_sponsor($id_member)
    {
        $CI = &get_instance();
        $ancestry_sponsor = $CI->Model_Member->get_ancestry_sponsor($id_member);
        if (!$ancestry_sponsor) return 0;
        $ids_sponsor = explode(',', $ancestry_sponsor);
        return count($ids_sponsor);
    }
}

if (!function_exists('ddm_get_user_gen_sponsor')) {
    /**
     * Get user generation by sponsor
     * 
     * Returns the downline of users based on sponsorship.
     * 
     * @since 1.0.0
     * @access public
     * 
     * @param int $user_id
     * @param int $total_gen (optional)
     * @return array of user object
     * 
     * @author Iqbal
     */
    function ddm_get_user_gen_sponsor($user_id, $total_gen = 0)
    {
        $user_id = absint($user_id);
        if (empty($user_id)) return false;

        $total_gen = absint($total_gen);
        if (empty($total_gen)) return false;

        $CI = &get_instance();

        $result       = array();
        $gen          = 0;
        $user_ids     = array($user_id);

        while ($gen < $total_gen) {
            if (!$users = ddm_sponsored_by($user_ids)) break;

            $result[$gen] = $users;
            $gen++;

            // renewing the user ids
            $user_ids = array();
            foreach ($users as $user) $user_ids[] = $user->id;

            unset($users);
        }

        // array of user object
        return $result;
    }
}

if (!function_exists('ddm_sponsored_by')) {
    /**
     * Get user sponsored by array of User ID
     * 
     * @since 1.0.0
     * @access public
     * @see model user
     * 
     * @param array $user_ids
     * @return array 
     * 
     * @author Iqbal
     */
    function ddm_sponsored_by($user_ids)
    {
        $CI = &get_instance();
        return $CI->Model_Member->get_sponsored_by($user_ids);
    }
}

if (!function_exists('ddm_position_sponsor')) {
    /**
     * Check your position of sponsor
     * @author  Yuda
     * @param   Int     $id_member      (Required)  Member ID
     * @return Mixed, Boolean false if invalid member id, otherwise of position sponsor 
     */
    function ddm_position_sponsor($id_member)
    {
        $CI = &get_instance();
        return $CI->Model_Member->get_position_sponsor($id_member);
    }
}

if (!function_exists('ddm_check_username')) {
    /**
     *
     * Check username available
     * @param   Int     $username      Username
     * @return Mixed, Boolean false if invalid username, otherwise array of phone available
     */
    function ddm_check_username($username)
    {
        if (!$username) return false;
        $CI = &get_instance();

        $username_exist = false;
        $condition      = ' WHERE %username% LIKE "' . $username . '" ';
        $data           = $CI->Model_Auth->get_all_user_data(1, 0, $condition, '');
        if ($data) {
            $username_exist = $data[0];
        }

        if (!$username_exist) {
            $staff = $CI->Model_Staff->get_by('username', $username);
            if ($staff) {
                $username_exist = $staff;
            }
        }

        return $username_exist;
    }
}

if (!function_exists('ddm_calc_tax')) {
    /**
     * Calculate Pajak
     */
    function ddm_calc_tax($nominal, $npwp = '')
    {
        if (!$nominal || !is_numeric($nominal)) return 0;

        $tax_npwp       = 0;
        $tax_non_npwp   = 0;

        if ($_tax_npwp = get_option('setting_withdraw_tax_npwp')) {
            $tax_npwp   = $_tax_npwp;
        }

        if ($_tax_non_npwp = get_option('setting_withdraw_tax')) {
            $tax_non_npwp   = $_tax_non_npwp;
        }

        if (!$tax_npwp && !$tax_non_npwp) {
            return 0;
        }

        if ($npwp == '__.___.___._-___.___') {
            $npwp = '';
        }

        $npwp   = trim($npwp);
        $tax    = $tax_non_npwp;

        if (!empty($npwp)) {
            $tax = $tax_npwp;
        }

        $calc_tax = ($nominal * $tax) / 100;
        return round($calc_tax);
    }
}

if (!function_exists('ddm_notification_email_template')) {
    /**
     * Get notification template
     * 
     * @since 1.0.0
     * @access public
     * 
     * @param string $message
     * @return array 
     * 
     * @author Yuda
     */
    function ddm_notification_email_template($message = "", $title = "")
    {
        $template_open      = '
        <style>
            pre{ background-color: transparent; color: #FFFFFF; border:none; padding: 0px 10px 10px; }
        </style>
        <body class="clean-body" style="margin: 0; padding: 20px 0px; -webkit-text-size-adjust: 100%; background-color: #F5F5F5; font-family:Roboto,Arial,Helvetica,sans-serif;">
            <div style="background-color:transparent; margin: 0 auto; min-width: 320px; max-width: 650px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word;">';

        $template_header = '
            <div style="background-color:#FFFFFF; display: block;">
                <div style="width:100% !important;">
                    <div style="border:0px solid transparent; padding: 25px 10px;">
                        <div style="padding: 0px; text-align: center;">
                            <img src="' . (DOMAIN_DEV == true ? BE_IMG_LIVE_PATH : BE_IMG_PATH) . 'logo.png" alt="' . COMPANY_NAME . '" width="50%">
                        </div>
                    </div>
                </div>
            </div>';

        $template_body = '
            <div style="background-color:#FFFFFF; display: block; padding: 0px; font-size: 14px;">
                <div style="background-color:#2e6694; padding: 20px; color: #FFFFFF;">
                    ' . (empty($title) ? '' : '<div style="text-align: center;"><h3 style="font-size:18px;">' . $title . '</h3><hr/></div>') . '
                    ' . (empty($message) ? '<div style="text-align: center;">Email Notifikasi ini tidak memiliki pesan</div>' : $message) . '
                </div>
            </div>';

        $template_footer = '
            <div style="background-color:#FFFFFF;">
                <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 650px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
                    <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                        <div class="col num12" style="min-width: 320px; max-width: 650px; display: table-cell; vertical-align: top; width: 650px;">
                            <div style="width:100% !important;">
                                <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:20px; padding-bottom:30px; padding-right: 0px; padding-left: 0px;">
                                    <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                        <tbody>
                                            <tr style="vertical-align: top;" valign="top">
                                                <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding: 10px;" valign="top">
                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 60%; border-top: 1px dotted #C4C4C4; height: 0px;" valign="top" width="60%">
                                                        <tbody>
                                                            <tr style="vertical-align: top;" valign="top">
                                                                <td height="0" style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div style="color:#5F5F5F; line-height:120%; padding: 10px;">
                                        <div style="font-size: 12px; line-height: 14px; color: #5F5F5F;">
                                            <p style="font-size: 12px; line-height: 16px; text-align: center; margin: 0;">
                                                <strong>' . COMPANY_NAME . ' &copy; 2020. All Right Reserved</strong>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';

        $template_close = '
            <div>
        </body>';

        $template           = $template_open . $template_header . $template_body . $template_footer . $template_close;
        return $template;
    }
}

if (!function_exists('ddm_notification_shop_template')) {
    /**
     * Get notification template
     * 
     * @since 1.0.0
     * @access public
     * 
     * @param string $message
     * @return array 
     * 
     * @author Yuda
     */
    function ddm_notification_shop_template($type_order = "agent", $shop_order = "", $subject = "", $member_name = "", $member = '')
    {
        $CI = &get_instance();
        $CI->load->helper('shop_helper');

        $currency           = config_item('currency');
        $server_name        = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : DOMAIN_NAME;
        $company_name       = get_option('company_name');
        $company_name       = !empty($company_name) ? $company_name : COMPANY_NAME;
        $dateorder          = !empty($shop_order->datecreated) ? date_indo($shop_order->datecreated, 'datetime') : '';

        $title              = '
            <div style="text-align: center;">
                <p style="font-size:18px;">' . (empty($subject) ? 'Informasi Pemesanan Produk' : $subject) . '</p>
                <hr>
                <p style="margin: 10px 0px 0px;">
                    <span style="font-size: 16px; font-weight:600; color: #fff;">No. ' . ($shop_order->id_agent > 0 ? 'Kwitansi' : 'Invoice') . ' </span>
                    <span style="font-size: 16px; font-weight:600; color: #ff8d2b;">' . $shop_order->invoice . '</span>
                </p>
                <p style="font-size: 12px; margin-top: 0px; color:#ddd">' . $dateorder . '</p>
                <hr/><br>
            </div>';

        $text_message       = '<p style="margin: 3px 0px;">Terima kasih sudah order di aplikasi <b>' . $company_name . '</b>. Sebagai konfirmasi, berikut informasi data pemesanan anda :</p>';

        if (strtolower($type_order) == 'agent' && strtolower($shop_order->type) == 'perdana') {
            $text_message   = '<p style="margin: 3px 0px;">Terima kasih sudah mendaftar sebagai agen dan order paket produk di aplikasi <b>' . $company_name . '</b>.</p>';
            if (is_object($member)) {
                $text_message   .= '
                    <p>Berikut adalah informasi akun keanggotaan anda :</p>
                    <p style="color:#666">
                    Nama       : <strong>' . $member->name . '</strong><br />
                    Username   : <strong>' . $member->username . '</strong>
                    </p>
                ';
            }
            $text_message   .= '<p>Sebagai konfirmasi, berikut informasi data pemesanan anda :</p>';
        }

        if (strtolower($type_order) == 'customer' && is_object($member)) {
            $text_message   = '<p style="margin: 3px 0px;">Anda menerima pesanan dari konsumen yang telah order di aplikasi <b>' . $company_name . '</b>. Sebagai konfirmasi, berikut informasi data pemesanannya  :</p>';
        }

        $message            = '<p style="line-height: 1.2;">Halo <b>' . $member_name . '</b></p>' . $text_message;

        $product_detail     = '
        <table class="table no-wrap table-responsive" style="margin-bottom: 30px;width: 100%;line-height: inherit;text-align: left;">
            <thead>
                <tr class="heading">
                    <th style="width:70%;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;padding: 10px;">Produk</th>
                    <th style="width:30%;text-align: right;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;padding: 10px;">Total</th>
                </tr>
            </thead>
            <tbody>';

        if (is_serialized($shop_order->products)) {
            $unserialize_data = maybe_unserialize($shop_order->products);
            foreach ($unserialize_data as $row) {
                $idMaster       = $row['id'];
                $image          = data_product($idMaster, 'image');
                $img_src        = product_image($image);

                $package_id     = isset($row['package']) ? $row['package'] : 0;
                $lock_qty       = isset($row['lock_qty']) ? $row['lock_qty'] : false;
                $product_name   = isset($row['name']) ? $row['name'] : 'Produk';

                $qty            = isset($row['qty']) ? $row['qty'] : 0;
                $price          = isset($row['price']) ? $row['price'] : 0;
                $price_ori      = isset($row['price_ori']) ? $row['price_ori'] : 0;
                $discount       = isset($row['discount']) ? $row['discount'] : 0;
                $subtotal       = $qty * $price;

                if ($price_ori > $price) {
                    $price_prod = '( <s style="font-size: 11px">' . ddm_accounting($price_ori) . '</s> <span style="color:#fb6340;font-size: 11px">' . ddm_accounting($price, $currency) . '</span> )';
                } else {
                    $price_prod = ddm_accounting($price, $currency);
                }

                $product_detail     .= '
                    <tr>
                        <td style="text-align: left;text-transform: capitalize;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;">
                            <img src="' . $img_src . '" style="width: 55px;float: left;margin-right: 10px;">
                            <span style="font-size: 12px;font-weight:600; margin-bottom: 3px;display: block;">' . $product_name . '</span>
                            <span style="font-size: 11px;display:block;margin-bottom:2px">
                                Harga: ' . $price_prod . '
                            </span>
                            <span style="font-size: 10px;">Qty: ' . $qty . '</span>
                        </td>
                        <td class="text-center" style="text-align: right;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                            ' . ddm_accounting($subtotal)  . '
                        </td>
                    </tr>
                ';
            }
        }

        $uniquecode         = str_pad($shop_order->unique, 3, '0', STR_PAD_LEFT);
        $product_detail     .= '
                <tr>
                    <td style="text-align:right;padding:5px;vertical-aligntop;white-space:nowrap;font-weight:500; color:#666; font-size:13px">Subtotal</td>
                    <td style="text-align:right;padding:5px;vertical-align:top;white-space:nowrap;font-weight:bold; color:#666; font-size:13px">
                        ' . ddm_accounting($shop_order->subtotal) . '
                    </td>
                </tr>
                ' . (($type_order == 'agent') ? (($shop_order->type == 'perdana') ? '
                    <tr>
                        <td style="text-align:right;padding:5px;vertical-aligntop;white-space:nowrap;font-weight:500; color:#666; font-size:13px">Biaya Pendaftaran</td>
                        <td style="text-align:right;padding:5px;vertical-align:top;white-space:nowrap;font-weight:bold; color:#666; font-size:13px">
                            ' . ddm_accounting($shop_order->registration) . '
                        </td>
                    </tr>' : '') : '') . '
                <tr>
                    <td style="text-align:right;padding:5px;vertical-aligntop;white-space:nowrap;font-weight:500; color:#666; font-size:13px">' . lang('shipping_fee') . '</td>
                    <td style="text-align:right;padding:5px;vertical-align:top;white-space:nowrap;font-weight:bold; color:#666; font-size:13px">
                        ' . ddm_accounting($shop_order->shipping) . '
                    </td>
                </tr>
                ' . (($shop_order->unique) ? '
                    <tr>
                        <td style="text-align:right;padding:5px;vertical-aligntop;white-space:nowrap;font-weight:500; color:#666; font-size:13px">Kode Unik</td>
                        <td style="text-align:right;padding:5px;vertical-align:top;white-space:nowrap;font-weight:bold; color:#666; font-size:13px">
                            ' . ($uniquecode) . '
                        </td>
                    </tr>' : '') . '
                ' . (($shop_order->discount) ? '
                    <tr>
                        <td style="text-align:right;padding:5px;vertical-aligntop;white-space:nowrap;font-weight:500; color:#666; font-size:13px">
                            ' . lang('discount') . ' ' . ($shop_order->voucher ? ' (<span style="font-size:10px; color:#5e72e4">' . $shop_order->voucher . '</span>)' : '') . '
                        </td>
                        <td style="text-align:right;padding:5px;vertical-align:top;white-space:nowrap;font-weight:bold; color:#666; font-size:13px">
                            ' . ddm_accounting($shop_order->discount) . '
                        </td>
                    </tr>' : '') . '
                <tr>
                    <td style="text-align:right;padding:5px;vertical-aligntop;white-space:nowrap;font-weight:bold; color:#666; font-size:15px">
                        ' . lang('total_payment') . '
                    </td>
                    <td style="text-align:right;padding:5px;vertical-align:top;white-space:nowrap;font-weight:bold; color:#fb6340; font-size:15px">
                        ' . ddm_accounting($shop_order->total_payment, $currency) . '
                    </td>
                </tr>
            </tbody>
        </table>';

        // Information Shipping Address
        $address            = ucwords(strtolower($shop_order->address)) . ', Kec. ' . $shop_order->subdistrict . br();
        $address           .= $shop_order->city . ' - ' . $shop_order->province;
        $address           .= ($shop_order->postcode) ? ' (' . $shop_order->postcode . ')' : '';

        $shipping_detail    = '
        <table class="table" style="margin-bottom: 20px;width: 100%;line-height: inherit;text-align: left;">
            <tr class="heading">
                <th colspan="3" style="width: 100%;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;padding: 10px;">Informasi Pengiriman</th>
            </tr>

            <tr class="item">
                <td style="width: 20%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">' . lang("name") . '</td>
                <td style="width: 2%x;">:</td>
                <td style="width: 78px;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                    ' . ucwords(strtolower($shop_order->name)) . '
                </td>
            </tr>
            <tr class="item">
                <td style="width: 20%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">' . lang("reg_no_telp") . '</td>
                <td style="width: 2%x;">:</td>
                <td style="width: 78px;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                    ' . $shop_order->phone . '
                </td>
            </tr>
            <tr class="item">
                <td style="width: 20%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">' . lang("reg_email") . '</td>
                <td style="width: 2%x;">:</td>
                <td style="width: 78px;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                    ' . $shop_order->email . '
                </td>
            </tr>
            <tr class="item">
                <td style="width: 20%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">' . lang("reg_alamat") . '</td>
                <td style="width: 2%x;">:</td>
                <td style="width: 78px;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                    ' . $address . '
                </td>
            </tr>
            <tr class="item">
                <td style="width: 20%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">' . lang("courier") . '</td>
                <td style="width: 2%x;">:</td>
                <td style="width: 78px;padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                    ' . strtoupper($shop_order->courier) . (!empty($shop_order->service) ? ' (' . strtoupper($shop_order->service) . ')' : '') . '
                </td>
            </tr>
        </table>';


        // Information Billing Account
        $billing_detail     = '';
        if (strtolower($type_order) == 'agent') {
            $bill_bank      = '';
            $bill_no        = get_option('company_bill');
            $bill_name      = get_option('company_bill_name');
            if ($company_bank = get_option('company_bank')) {
                if ($getBank = ddm_banks($company_bank)) {
                    $bill_bank = $getBank->nama;
                }
            }

            if ($bill_no) {
                $bill_format = '';
                $arr_bill    = str_split($bill_no, 4);
                foreach ($arr_bill as $no) {
                    $bill_format .= $no . ' ';
                }
                $bill_no = $bill_format ? $bill_format : $bill_no;;
            }

            $agentdata          = '';
            if ($shop_order->id_agent > 0) {
                $agentdata      = ddm_get_memberdata_by_id($shop_order->id_agent);
            }
            $agent              = ($agentdata || !empty($agentdata) ? true : false);

            if ($agent) {
                $province_name      = '';
                $district_name      = '';
                $subdistrict_name   = '';
                if ($getProvince    = ddm_provinces($agentdata->province)) {
                    $province_name  = $getProvince->province_name;
                }
                if ($getDistrict    = ddm_districts($agentdata->district)) {
                    $district_name  = $getDistrict->district_name;
                }
                if ($getSubdistrict = ddm_subdistricts($agentdata->subdistrict)) {
                    $subdistrict_name = $getSubdistrict->subdistrict_name;
                }
                $address            = ucwords(strtolower($agentdata->address)) . ', ' . $subdistrict_name . ', ' . $district_name . ', ' . $province_name;
                $agent_name         = $agentdata->name;
                $agent_phone        = $agentdata->phone;
            }

            $billing_detail     = '
            <table class="table" style="margin-bottom: 20px;width: 100%;line-height: inherit;text-align: left;">
                <tr class="heading">
                    <th colspan="3" style="width: 100%;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;padding: 10px;">Informasi Pengirim</th>
                </tr>

                <tr class="item">
                    <td style="width: 20%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">' . ($agent ? 'Nama' : 'Bank') . '</td>
                    <td style="width: 2%px;">:</td>
                    <td style="width: 78%; padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                        ' . ($agent ? $agent_name : ucwords(strtolower($bill_bank))) . '
                    </td>
                </tr>
                <tr class="item">
                    <td style="width: 20%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">' . ($agent ? 'HP' : 'No. Rekening') . '</td>
                    <td style="width: 2%px;">:</td>
                    <td style="padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                        ' . ($agent ? $agent_phone : $bill_no) . '
                    </td>
                </tr>
                <tr class="item">
                    <td style="width: 20%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">' . ($agent ? 'Alamat' : 'Nama Rekening') . '</td>
                    <td style="width: 2%px;">:</td>
                    <td style="padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                        ' . ($agent ? $address : ucwords(strtolower($bill_name))) . '
                    </td>
                </tr>
            </table>

            <div class="info-box" style="padding: 20px;margin: auto;background: #5c4b79;color: white; text-align: center;">
                Sebelum konfirmasi pembayaran ini pastikan anda sudah mentransfer sejumlah nominal di bawah ini ke rekening ' . ($agent ? 'Master Agen' : 'Perusahaan') . '.<br />
                <span style="font-size: 22px; font-weight:600; color: #fb6340;">' . ddm_accounting($shop_order->total_payment, $currency) . '</span><br />
                Hubungi ' . ($agent ? 'Master Agen tersebut' : 'Perusahaan') . ' untuk info lebih detail.
            </div>
            <br>
            <center>
                <div style="margin: 15px 0;">
                    <a href="' . base_url('confirm/payment/' . ddm_encrypt($shop_order->id)) . '" style="background: #2e6694;width: 200px;padding: 13px 26px;border-radius: 40px;color: white;text-decoration: unset;" target="_blank">Konfirmasi Pembayaran</a>
                </div>
            </center>';
        }

        if (strtolower($type_order) == 'customer' && !$member) {
            $agen_name  = '';
            $agen_phone = '';
            $agen_email = '';

            if ($get_agent = ddm_get_memberdata_by_id($shop_order->id_member)) {
                $agen_name  = $get_agent->name;
                $agen_phone = $get_agent->phone;
                $agen_email = $get_agent->email;
            }

            $billing_detail     = '
            <table class="table" style="margin-bottom: 20px;width: 100%;line-height: inherit;text-align: left;">
                <tr class="heading">
                    <th colspan="3" style="width: 100%;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;padding: 10px;">Informasi Agen</th>
                </tr>

                <tr class="item">
                    <td style="width: 20%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">' . lang("name") . '</td>
                    <td style="width: 2%px;">:</td>
                    <td style="width: 78%; padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                        ' . ucwords(strtolower($agen_name)) . '
                    </td>
                </tr>
                <tr class="item">
                    <td style="width: 20%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">' . lang("reg_no_telp") . '</td>
                    <td style="width: 2%px;">:</td>
                    <td style="padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                        ' . $agen_phone . '
                    </td>
                </tr>
                <tr class="item">
                    <td style="width: 20%;padding: 5px 10px;vertical-align: top;border-bottom: 1px solid #eee;">' . lang("reg_email") . '</td>
                    <td style="width: 2%px;">:</td>
                    <td style="padding: 5px;vertical-align: top;border-bottom: 1px solid #eee;white-space: nowrap;">
                        ' . $agen_email . '
                    </td>
                </tr>
            </table>
            <div class="info-box" style="padding: 20px;margin: auto;background: #79c5ea;color: white;">
                Sebelum melakukan pembayaran sebesar 
                <span style="font-size: 16px; font-weight:600; color: #fb6340;">' . ddm_accounting($shop_order->total_payment, $currency) . '</span>, 
                pastikan anda telah menghubungi Agen terlebih dahulu untuk proses lebih lanjut terhadap pesanan anda.
            </div>';
        }

        $template_style     = '
        <style>
            * { font-size: 14px; }

            @media only screen and (max-width:480px) {
                table td.mobile-center {
                    width: 100% !important;
                    display: block !important;
                    text-align: left !important;
                }

                table td.title.mobile-center {
                    text-align: center !important
                }

                .mobile-hide {
                    display: none;
                }

                .mobile-text-left {
                    text-align: left !important;
                }
            }

            .no-padding {
                padding: unset !important;
            }

            .rtl {
                direction: rtl;
                font-family: Tahoma, "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
            }

            .rtl table {
                text-align: right;
            }

            .rtl table tr td:nth-child(2) {
                text-align: left;
            }

            table.no-wrap td {
                white-space: unset;
            }

            table.table tr.item td {
                font-size: 13px;
            }
            pre{ background-color: transparent; color: #FFFFFF; border:none; padding: 0px 10px 10px; }
        </style>';

        $template_open      = '
        <body class="clean-body" style="margin: 0; padding: 20px 0px; -webkit-text-size-adjust: 100%; background-color: #F5F5F5; font-family:Roboto,Arial,Helvetica,sans-serif;">
            <div style="background-color:transparent; margin: 0 auto; min-width: 320px; max-width: 650px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word;">';

        $template_header = '
            <div style="background-color:#FFFFFF; display: block;">
                <div style="width:100% !important;">
                    <div style="border:0px solid transparent; padding: 25px 10px;">
                        <div style="padding: 0px; text-align: center;">
                            <img src="' . (DOMAIN_DEV == true ? BE_IMG_LIVE_PATH : BE_IMG_PATH) . 'logo.png" alt="' . $company_name . '" width="50%">
                        </div>
                    </div>
                </div>
            </div>';

        $template_body = '
            <div style="background-color:#FFFFFF; display: block; padding: 0px; font-size: 14px;">
                <div style="background-color:#2e6694; padding: 20px 20px 0px; color: #FFFFFF;">
                    ' . (empty($title) ? '' : $title) . '
                </div>
                <div style="padding: 10px 20px; margin-top: 30px; color: #333;">
                    ' . (empty($message) ? '<div style="text-align: center;">Email Notifikasi ini tidak memiliki pesan</div>' : $message) . '
                </div>
                <div style="padding: 5px 20px; color: #333;">
                    ' . $product_detail . '
                </div>
                <div style="padding: 5px 20px; color: #333;">
                    ' . $shipping_detail . '
                </div>
                <div style="padding: 5px 20px; color: #333;">
                    ' . $billing_detail . '
                </div>
            </div>';

        $template_footer = '
            <div style="background-color:#FFFFFF;">
                <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 650px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
                    <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                        <div class="col num12" style="min-width: 320px; max-width: 650px; display: table-cell; vertical-align: top; width: 650px;">
                            <div style="width:100% !important;">
                                <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:20px; padding-bottom:30px; padding-right: 0px; padding-left: 0px;">
                                    <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                        <tbody>
                                            <tr style="vertical-align: top;" valign="top">
                                                <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding: 10px;" valign="top">
                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" height="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 60%; border-top: 1px dotted #C4C4C4; height: 0px;" valign="top" width="60%">
                                                        <tbody>
                                                            <tr style="vertical-align: top;" valign="top">
                                                                <td height="0" style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div style="color:#5F5F5F; line-height:120%; padding: 10px;">
                                        <div style="font-size: 12px; line-height: 14px; color: #5F5F5F;">
                                            <p style="font-size: 12px; line-height: 16px; text-align: center; margin: 0;">
                                                <strong>' . COMPANY_NAME . ' &copy; 2020. All Right Reserved</strong>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';

        $template_close = '
            <div>
        </body>';

        $template           = $template_style . $template_open . $template_header . $template_body . $template_footer . $template_close;
        return $template;
    }
}

if (!function_exists('ddm_count_line_rank')) {
    function ddm_count_line_rank($id_sponsor = '', $yearmonth = '', $rank = RANK_AGENT)
    {
        if (!$id_sponsor) return false;
        $CI                         = &get_instance();
        $total_member               = 0;
        $datamember                 = ddm_get_memberdata_by_id($id_sponsor);
        $yearmonth                  = $yearmonth ? date('Y-m', strtotime($yearmonth)) : date('Y-m', strtotime('-1 Day'));
        $datetime                   = $yearmonth . '-' . date('t', strtotime($yearmonth)) . ' ' . date('23:59:5') . rand(0, 9);
        $year                       = date('Y', strtotime($yearmonth));
        $month                      = date('n', strtotime($yearmonth));

        if (!$datamember) {
            return false;
        }

        $datadownline               = ddm_get_memberdata_by('parent', $datamember->id, '');
        $line                       = 1;

        if (is_array($datadownline) && (count($datadownline) > 0)) {
            foreach ($datadownline as $downline) {
                if (!$downline) continue;

                $conditions = ' AND %tree% LIKE CONCAT(?, "%") AND %rank% = ? AND %year% = ? AND %month% = ?';
                $params     = array($downline->tree, $rank, $year, $month);
                $dd         = $CI->Model_Member->get_all_member_grade(0, 0, $conditions, '', $params);
                if (is_array($dd) && (count($dd) > 0)) {
                    $total_member++;
                }

                $line++;
            }
        }

        return $total_member;
    }
}

/*
CHANGELOG
---------
Insert new changelog at the top of the list.
-----------------------------------------------
Version YYYY/MM/DD  Person Name     Description
-----------------------------------------------
1.0.0   2016/06/01  Yuda           - Create this changelog.
*/
