<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Email Class
 *
 * @subpackage	Libraries
 */
class DDM_Email
{
	var $CI;
	var $active;

	/**
	 * Constructor - Sets up the object properties.
	 */
	function __construct()
    {
        $this->CI       =& get_instance();
		$this->active	= config_item('email_active');

        require_once SWIFT_MAILSERVER;
	}

    /**
	 * Send email function.
	 *
     * @param string    $to         (Required)  To email destination
     * @param string    $subject    (Required)  Subject of email
     * @param string    $message    (Required)  Message of email
     * @param string    $from       (Optional)  From email
     * @param string    $from_name  (Optional)  From name email
	 * @return Mixed
	 */
    function send($to, $subject, $message, $from = '', $from_name = '', $debug = false){
        if (!$this->active) return false;

        $mailserver_host    = config_item('mailserver_host');
        $port               = config_item('mailserver_port');
        $username           = config_item('mailserver_username');
        $password           = config_item('mailserver_password');

        require_once(APPPATH . 'libraries/vendor/phpmailer/src/PHPMailer.php');
        require_once(APPPATH . 'libraries/vendor/phpmailer/src/SMTP.php');
        require_once(APPPATH . 'libraries/vendor/phpmailer/src/Exception.php');

        try {
            $this->CI->phpmailer = new PHPMailer\PHPMailer\PHPMailer();

            $mail               = $this->CI->phpmailer;
            $mail->IsSMTP();                // telling the class to use SMTP
            $mail->SMTPDebug    = false;    // debug email sending (inspect: "Network" in browser)
            $mail->SMTPOptions  = array(
                'ssl' => array(
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true
                )
            );

            $mail->SMTPAuth     = true;                 // Enable SMTP authentication (TRUE / FALSE)
            $mail->SMTPSecure   = "tls"; // tls/ssl
            $mail->Host         = $mailserver_host;     // sets GMAIL as the SMTP server
            $mail->Port         = $port;                // gmail smtp port
            $mail->Username     = $username;            // SMTP gmail address
            $mail->Password     = $password;            // SMTP account password

            $mail->From         = $from;                // sender's address
            $mail->FromName     = $from_name;           // sender's name

            $mail->AddAddress($to);                     // send to receiver's e-mail address
            $mail->Subject      = ($subject);           // e-mail subject
            $mail->Body         = html_entity_decode($message->html);
            $mail->Encoding     = "base64";
            $mail->CharSet      = "UTF-8";
            $mail->IsHTML(true);
            $mail->WordWrap     = 50;

            if ($mail->Send()) {
                ddm_log_notif('email', $subject, $to, $message->plain, 'SUCCESS');
                return true;
            } else {
                ddm_log_notif('email', $subject, $to, $message->plain, 'FAILED');
                return false;
            }
            $mail->SmtpClose();
        } catch (Exception $e) {
            ddm_log_notif('email', $subject, $to, $e->getMessage(), 'ERROR');
        }

        return false;
    }

    // GMAIL
	function send_gmail($to, $subject, $message, $from = '', $from_name = '', $debug = false){
		if (!$this->active) return false;

		$mailserver_host    = '';
        $username           = '';
        $password           = '';
        $port               = 587;

        try {
            $transport = (new Swift_SmtpTransport($mailserver_host, $port, 'tls'))->setUsername($username)->setPassword($password);
            // Create the Mailer using your created Transport
            $mailer = new Swift_Mailer($transport);

            // Create a message
            $mail_msg = (new Swift_Message($subject))
            ->setFrom(array($from => $from_name))
            ->setTo($to)
            ->setBody($message->plain)
            ->addPart($message->html, 'text/html');
            
            $result = $mailer->send($mail_msg);
            if ( $debug ) { var_dump($result); }
            if ( $result ) {
                ddm_log_notif('email', $subject, $to, $message->plain, 'SUCCESS');
            }
            return $result;
        } catch (Exception $e) {
            if ( $debug ) {
                var_dump($e->getMessage());
            }
            // Should be database log in here
            ddm_log_notif('email', $subject, $to, $e->getMessage(), 'FAILED');
        }
		return false;
	}

    /**
     * Send email to New Member function.
     *
     * @param   Object  $member     (Required)  Member Data of Downline
     * @param   Object  $sponsor    (Required)  Member Data of Sponsor
     * @param   Object  $password   (Required)  Password of Downline
     * @return  Mixed
     */
    function send_email_new_member($member, $sponsor, $password, $view = false){
        if ( !$member ) return false;
        if ( !$sponsor ) return false;
        if ( !$password ) return false;
        if ( empty($member->email) ) return false;

        if( $member->status == 0 ){
            if ( ! $notif = $this->CI->Model_Option->get_notification_by('slug', 'notification-new-member', 'email')  ) {
                return false;
            }
        }elseif( $member->status == 1 ){
            if ( ! $notif = $this->CI->Model_Option->get_notification_by('slug', 'notification-new-member-active', 'email')  ) {
                return false;
            }
        }

        if( $notif->status == 0 ) return false;
        if( empty($notif->content) ) return false;

        $from_name      = 'Admin ' . COMPANY_NAME;
        $from_mail      = get_option('mail_sender_admin');
        $from_mail      = $from_mail ? $from_mail : config_item('mail_sender');
        $sponsor_name   = strtolower($sponsor->username) . ' / ' . strtoupper($sponsor->name);
        $url_login      = '<a href="'.base_url('login').'" style="text-decoration: none; color: #FFFFFF;" target="_blank"><b>'.base_url('login').'</b></a>';

        // Set Variable Email
        $subject        = ( !empty($notif->title) ) ? $notif->title : 'Informasi Pendaftaran';
        $text           = $notif->content;

        $text           = str_replace("%name%",             $member->name, $text);
        $text           = str_replace("%username%",         $member->username, $text);
        $text           = str_replace("%password%",         $password, $text);
        $text           = str_replace("%sponsor%",          $sponsor_name, $text);
        $text           = str_replace("%url_login%",        $url_login, $text);

        $plain_mail     = ddm_html2text($text);
        $html_mail      = ddm_notification_email_template($text, $subject);

        $message        = new stdClass();
        $message->plain = $plain_mail;
        $message->html  = $html_mail;

        if ( $view ) {
            return $message;
        } else {
            $send       = $this->send($member->email, $subject, $message, $from_mail, $from_name);
            // $send       = $this->send_gmail($member->email, $subject, $message, $from_mail, $from_name);
            if( $send ){
                return true;
            }
            return false;
        }
    }

    /**
     * Send email to New Staff function.
     *
     * @param   Object  $member     (Required)  Staff Data of Downline
     * @param   Object  $sponsor    (Required)  Staff Data of Sponsor
     * @param   Object  $password   (Required)  Password of Downline
     * @return  Mixed
     */
    function send_email_new_staff($member, $password, $view = false){
        if ( !$member ) return false;
        if ( !$password ) return false;
        if ( empty($member->email) ) return false;

        if ( ! $notif = $this->CI->Model_Option->get_notification_by('slug', 'notification-new-staff', 'email')  ) {
            return false;
        }
        if( $notif->status == 0 ) return false;
        if( empty($notif->content) ) return false;

        $from_name      = 'Admin ' . COMPANY_NAME;
        $from_mail      = get_option('mail_sender_admin');
        $from_mail      = $from_mail ? $from_mail : config_item('mail_sender');
        $url_login      = '<a href="'.base_url('login').'" style="text-decoration: none; color: #FFFFFF;" target="_blank"><b>'.base_url('login').'</b></a>';

        // Set Variable Email
        $subject        = ( !empty($notif->title) ) ? $notif->title : 'Informasi Pendaftaran';
        $text           = $notif->content;

        $text           = str_replace("%name%",             $member->name, $text);
        $text           = str_replace("%username%",         $member->username, $text);
        $text           = str_replace("%password%",         $password, $text);
        $text           = str_replace("%url_login%",        $url_login, $text);

        $plain_mail     = ddm_html2text($text);
        $html_mail      = ddm_notification_email_template($text, $subject);

        $message        = new stdClass();
        $message->plain = $plain_mail;
        $message->html  = $html_mail;

        if ( $view ) {
            return $message;
        } else {
            $send       = $this->send($member->email, $subject, $message, $from_mail, $from_name);
            // $send       = $this->send_gmail($member->email, $subject, $message, $from_mail, $from_name);
            if( $send ){
                return true;
            }
            return false;
        }
    }

    /**
     * Send email to Sponsor (New Member) function.
     *
     * @param   Object  $member     (Required)  Member Data of Downline
     * @param   Object  $sponsor    (Required)  Member Data of Sponsor
     * @param   Object  $upline     (Required)  Member Data of Upline
     * @return  Mixed
     */
    function send_email_sponsor($member, $sponsor, $view = false){
        if ( !$member ) return false;
        if ( !$sponsor ) return false;
        if ( empty($sponsor->email) ) return false;

        if ( ! $notif = $this->CI->Model_Option->get_notification_by('slug', 'notification-sponsor', 'email')  ) {
            return false;
        }
        if( $notif->status == 0 ) return false;
        if( empty($notif->content) ) return false;

        $from_name      = 'Admin ' . COMPANY_NAME;
        $from_mail      = get_option('mail_sender_admin');
        $from_mail      = $from_mail ? $from_mail : config_item('mail_sender');
        
        // Set Variable Email
        $subject        = ( !empty($notif->title) ) ? $notif->title : 'Informasi Member Baru';
        $text           = $notif->content;

        $text           = str_replace("%name%",             $member->name, $text);
        $text           = str_replace("%username%",         $member->username, $text);

        $plain_mail     = ddm_html2text($text);
        $html_mail      = ddm_notification_email_template($text, $subject);

        $message        = new stdClass();
        $message->plain = $plain_mail;
        $message->html  = $html_mail;

        if ( $view ) {
            return $message;
        } else {
            $send       = $this->send($sponsor->email, $subject, $message, $from_mail, $from_name);
            // $send       = $this->send_gmail($member->email, $subject, $message, $from_mail, $from_name);
            if( $send ){
                return true;
            }
            return false;
        }
    }

    /**
     * Send email withdraw function.
     *
     * @param   Object  $member     (Required)  Data of Member
     * @param   Object  $withdraw   (Required)  Data of Withdraw
     * @return  Mixed
     */
    function send_email_withdraw($member, $withdraw, $view = false){
        if ( !$member ) return false;
        if ( !$withdraw ) return false;
        if ( empty($member->email) ) return false;

        if ( ! $notif = $this->CI->Model_Option->get_notification_by('slug', 'notification-withdraw', 'email')  ) {
            return false;
        }
        if( !$bank = ddm_banks($withdraw->bank) ) {
            return false;
        }
        if( $notif->status == 0 ) return false;
        if( empty($notif->content) ) return false;
        

        $from_name      = 'Admin ' . COMPANY_NAME;
        $from_mail      = get_option('mail_sender_admin');
        $from_mail      = $from_mail ? $from_mail : config_item('mail_sender');
        $currency       = config_item('currency');
        $rekening       = $withdraw->bill . ' - ' . strtoupper($withdraw->bill_name);

        // Set Variable Email
        $subject        = ( !empty($notif->title) ) ? $notif->title : 'Informasi Transfer Bonus';
        $text           = $notif->content;

        $text           = str_replace("%member_name%",      ucwords(strtolower($member->name)), $text);
        $text           = str_replace("%username%",         $member->username, $text);
        $text           = str_replace("%bank%",             $bank->nama, $text);
        $text           = str_replace("%bill%",             $rekening, $text);
        $text           = str_replace("%nominal%",          ddm_accounting($withdraw->nominal_receipt, $currency), $text);

        $plain_mail     = ddm_html2text($text);
        $html_mail      = ddm_notification_email_template($text, $subject);

        $message        = new stdClass();
        $message->plain = $plain_mail;
        $message->html  = $html_mail;

        if ( $view ) {
            return $message;
        } else {
            $send       = $this->send($member->email, $subject, $message, $from_mail, $from_name);
            // $send       = $this->send_gmail($member->email, $subject, $message, $from_mail, $from_name);
            if( $send ){
                return true;
            }
            return false;
        }
    }

    /**
     * Send email PIN transfer to Sender function.
     *
     * @param   Object  $member     (Required)  Data of Member
     * @param   Object  $data       (Required)  Data of Transfer PIN
     * @return  Mixed
     */
    function send_email_pin_transfer_sender($member, $data, $view = false){
        if ( !$member ) return false;
        if ( !$data ) return false;
        if ( empty($member->email) ) return false;

        if ( ! $notif = $this->CI->Model_Option->get_notification_by('slug', 'notification-transfer-pin-sender', 'email')  ) {
            return false;
        }
        if( $notif->status == 0 ) return false;
        if( empty($notif->content) ) return false;
        if( !isset($data['receiver_username']) || !isset($data['receiver_name']) || !isset($data['date']) || !isset($data['detail_pin']) ){
            return false;
        }
        if( empty($data['receiver_username']) || empty($data['receiver_name']) || empty($data['date']) || !$data['detail_pin'] ) return false;
        
        $from_name      = 'Admin ' . COMPANY_NAME;
        $from_mail      = get_option('mail_sender_admin');
        $from_mail      = $from_mail ? $from_mail : config_item('mail_sender');
        $member_name    = strtolower($member->username) . ' / ' . strtoupper($member->name);
        $date           = date('j M Y', strtotime($data['date']));
        $hour           = date('H:i', strtotime($data['date']));
        $datetime       = $date .' Pukul '. $hour .' WIB';

        // Set Data Detail PIN
        $no             = 1;
        $transfer       = '<pre>';
        foreach ($data['detail_pin'] as $pin => $qty) {
            $transfer  .= $no .'. '. strtoupper($pin) .' ('. ddm_accounting($qty) ." qty) \n";
            $no++;
        }
        $transfer      .= '</pre>';

        // Set Variable Email
        $subject        = ( !empty($notif->title) ) ? $notif->title : 'Informasi Transfer PIN';
        $text           = $notif->content;

        $text           = str_replace("%member_name%",          $member_name, $text);
        $text           = str_replace("%date%",                 $datetime, $text);
        $text           = str_replace("%receiver_username%",    $data['receiver_username'], $text);
        $text           = str_replace("%receiver_name%",        $data['receiver_name'], $text);
        $text           = str_replace("%detail_pin%",           $transfer, $text);

        $plain_mail     = ddm_html2text($text);
        $html_mail      = ddm_notification_email_template($text, $subject);

        $message        = new stdClass();
        $message->plain = $plain_mail;
        $message->html  = $html_mail;

        if ( $view ) {
            return $message;
        } else {
            $send       = $this->send($member->email, $subject, $message, $from_mail, $from_name);
            // $send       = $this->send_gmail($member->email, $subject, $message, $from_mail, $from_name);
            if( $send ){
                return true;
            }
            return false;
        }
    }

    /**
     * Send email PIN transfer to Receiver function.
     *
     * @param   Object  $member     (Required)  Data of Member
     * @param   Object  $data       (Required)  Data of Transfer PIN
     * @return  Mixed
     */
    function send_email_pin_transfer_receiver($member, $data, $view = false){
        if ( !$member ) return false;
        if ( !$data ) return false;
        if ( empty($member->email) ) return false;

        if ( ! $notif = $this->CI->Model_Option->get_notification_by('slug', 'notification-transfer-pin-receiver', 'email')  ) {
            return false;
        }
        if( $notif->status == 0 ) return false;
        if( empty($notif->content) ) return false;
        if( !isset($data['sender_username']) || !isset($data['sender_name']) || !isset($data['date']) || !isset($data['detail_pin']) ) {
            return false;
        }
        if( empty($data['sender_username']) || empty($data['sender_name']) || empty($data['date']) || !$data['detail_pin'] ) return false;
        
        $from_name      = 'Admin ' . COMPANY_NAME;
        $from_mail      = get_option('mail_sender_admin');
        $from_mail      = $from_mail ? $from_mail : config_item('mail_sender');
        $member_name    = strtolower($member->username) . ' / ' . strtoupper($member->name);
        $date           = date('j M Y', strtotime($data['date']));
        $hour           = date('H:i', strtotime($data['date']));
        $datetime       = $date .' Pukul '. $hour .' WIB';

        // Set Data Detail PIN
        $no             = 1;
        $transfer       = '<pre>';
        foreach ($data['detail_pin'] as $pin => $qty) {
            $transfer  .= $no .'. '. strtoupper($pin) .' ('. ddm_accounting($qty) ." qty) \n";
            $no++;
        }
        $transfer      .= '</pre>';

        // Set Variable Email
        $subject        = ( !empty($notif->title) ) ? $notif->title : 'Informasi Transfer PIN';
        $text           = $notif->content;

        $text           = str_replace("%member_name%",          $member_name, $text);
        $text           = str_replace("%date%",                 $datetime, $text);
        $text           = str_replace("%sender_username%",      $data['sender_username'], $text);
        $text           = str_replace("%sender_name%",          $data['sender_name'], $text);
        $text           = str_replace("%detail_pin%",           $transfer, $text);

        $plain_mail     = ddm_html2text($text);
        $html_mail      = ddm_notification_email_template($text, $subject);

        $message        = new stdClass();
        $message->plain = $plain_mail;
        $message->html  = $html_mail;

        if ( $view ) {
            return $message;
        } else {
            $send       = $this->send($member->email, $subject, $message, $from_mail, $from_name);
            // $send       = $this->send_gmail($member->email, $subject, $message, $from_mail, $from_name);
            if( $send ){
                return true;
            }
            return false;
        }
    }

    /**
     * Send email change password function.
     *
     * @param   Object  $member     (Required)  Data of Member
     * @param   Object  $data       (Required)  Data of New Password
     * @return  Mixed
     */
    function send_email_change_password($member, $data, $view = false){
        if ( !$member ) return false;
        if ( !$data ) return false;
        if ( empty($member->email) ) return false;

        if ( ! $notif = $this->CI->Model_Option->get_notification_by('slug', 'notification-change-password', 'email')  ) {
            return false;
        }
        if( $notif->status == 0 ) return false;
        if( empty($notif->content) ) return false;
        if( !isset($data['password']) || !isset($data['type_password']) ) return false;
        if( empty($data['password']) || empty($data['type_password']) ) return false;
        

        $from_name      = 'Admin ' . COMPANY_NAME;
        $from_mail      = get_option('mail_sender_admin');
        $from_mail      = $from_mail ? $from_mail : config_item('mail_sender');
        $member_name    = strtolower($member->username) . ' / ' . strtoupper($member->name);

        // Set Variable Email
        $subject        = ( !empty($notif->title) ) ? $notif->title : 'Informasi Ganti Password';
        $text           = $notif->content;

        $text           = str_replace("%member_name%",      $member_name, $text);
        $text           = str_replace("%username%",         $member->username, $text);
        $text           = str_replace("%password%",         $data['password'], $text);
        $text           = str_replace("%type_password%",    $data['type_password'], $text);

        $plain_mail     = ddm_html2text($text);
        $html_mail      = ddm_notification_email_template($text, $subject);

        $message        = new stdClass();
        $message->plain = $plain_mail;
        $message->html  = $html_mail;

        if ( $view ) {
            return $message;
        } else {
            $send       = $this->send($member->email, $subject, $message, $from_mail, $from_name);
            // $send       = $this->send_gmail($member->email, $subject, $message, $from_mail, $from_name);
            if( $send ){
                return true;
            }
            return false;
        }
    }

    /**
     * Send email reset password function.
     *
     * @param   Object  $member     (Required)  Data of Member
     * @param   Object  $data       (Required)  Data of New Password
     * @return  Mixed
     */
    function send_email_reset_password($member, $data, $view = false){
        if ( !$member ) return false;
        if ( !$data ) return false;
        if ( empty($member->email) ) return false;

        if ( ! $notif = $this->CI->Model_Option->get_notification_by('slug', 'notification-reset-password', 'email')  ) {
            return false;
        }
        if( $notif->status == 0 ) return false;
        if( empty($notif->content) ) return false;
        if( !isset($data['password']) || !isset($data['type_password']) ) return false;
        if( empty($data['password']) || empty($data['type_password']) ) return false;
        

        $from_name      = 'Admin ' . COMPANY_NAME;
        $from_mail      = get_option('mail_sender_admin');
        $from_mail      = $from_mail ? $from_mail : config_item('mail_sender');
        $member_name    = strtolower($member->username) . ' / ' . strtoupper($member->name);

        // Set Variable Email
        $subject        = ( !empty($notif->title) ) ? $notif->title : 'Informasi Ganti Password';
        $text           = $notif->content;

        $text           = str_replace("%member_name%",      $member_name, $text);
        $text           = str_replace("%username%",         $member->username, $text);
        $text           = str_replace("%password%",         $data['password'], $text);
        $text           = str_replace("%type_password%",    $data['type_password'], $text);

        $plain_mail     = ddm_html2text($text);
        $html_mail      = ddm_notification_email_template($text, $subject);

        $message        = new stdClass();
        $message->plain = $plain_mail;
        $message->html  = $html_mail;

        if ( $view ) {
            return $message;
        } else {
            $send       = $this->send($member->email, $subject, $message, $from_mail, $from_name);
            // $send       = $this->send_gmail($member->email, $subject, $message, $from_mail, $from_name);
            if( $send ){
                return true;
            }
            return false;
        }
    }

    /**
     * Send email forget password function.
     *
     * @param   Object  $member     (Required)  Data of Member
     * @param   Object  $data       (Required)  Data of New Password
     * @return  Mixed
     */
    function send_email_forget_password($member, $data, $view = false){
        if ( !$member ) return false;
        if ( !$data ) return false;
        if ( empty($member->email) ) return false;

        if ( ! $notif = $this->CI->Model_Option->get_notification_by('slug', 'notification-forget-password', 'email')  ) {
            return false;
        }
        if( $notif->status == 0 ) return false;
        if( empty($notif->content) ) return false;
        if( !isset($data['password']) ) return false;
        if( empty($data['password']) ) return false;
        

        $from_name      = 'Admin ' . COMPANY_NAME;
        $from_mail      = get_option('mail_sender_admin');
        $from_mail      = $from_mail ? $from_mail : config_item('mail_sender');
        $member_name    = strtolower($member->username) . ' / ' . strtoupper($member->name);

        // Set Variable Email
        $subject        = ( !empty($notif->title) ) ? $notif->title : 'Informasi Reset Password';
        $text           = $notif->content;

        $text           = str_replace("%member_name%",      $member_name, $text);
        $text           = str_replace("%username%",         $member->username, $text);
        $text           = str_replace("%password%",         $data['password'], $text);

        $plain_mail     = ddm_html2text($text);
        $html_mail      = ddm_notification_email_template($text, $subject);

        $message        = new stdClass();
        $message->plain = $plain_mail;
        $message->html  = $html_mail;

        if ( $view ) {
            return $message;
        } else {
            $send       = $this->send($member->email, $subject, $message, $from_mail, $from_name);
            // $send       = $this->send_gmail($member->email, $subject, $message, $from_mail, $from_name);
            if( $send ){
                return true;
            }
            return false;
        }
    }

    /**
     * Send email confirm shop order function.
     *
     * @param   Object  $member     (Required)  Data of Member
     * @param   Object  $shop_order (Required)  Data of Product Order
     * @return  Mixed
     */
    function send_email_confirm_shop_order($member, $shop_order, $view = false){
        if ( !$member ) return false;
        if ( !$shop_order ) return false;
        if ( empty($shop_order['email_com']) ) return false;

        if ( ! $notif = $this->CI->Model_Option->get_notification_by('slug', 'notification-confirm-payment', 'email')  ) {
            return false;
        }
        if( $notif->status == 0 ) return false;
        if( empty($notif->content) ) return false;
        
        
        $from_name      = 'Admin ' . COMPANY_NAME;
        $from_mail      = get_option('mail_sender_admin');
        $from_mail      = $from_mail ? $from_mail : config_item('mail_sender');
        
        $agent          = ( $shop_order['agentdata'] || !empty($shop_order['agentdata']) ? true : false );
        $to             = ( $agent ? $shop_order['agentdata']->email : $shop_order['email_com'] );

        $invoice        = isset($shop_order['invoice']) ? '('.$shop_order['invoice'].')' : '';
        if( $agent ){
            $invoice_txt = 'No. Kwitansi: '.$invoice;
        }else{
            $invoice_txt = 'No. Invoice: '.$invoice;
        }
        $subject        = ( !empty($notif->title) ) ? $notif->title : 'Informasi Konfirmasi Pembayaran Produk';
        $subject_email  = $subject .' '. $invoice;
        $member_name    = $from_name;
        if( $agent ){
            $member_name = strtolower($shop_order['agentdata']->username) . ' / ' . strtoupper($shop_order['agentdata']->name);
        }

        // Set Variable Email
        $text           = $notif->content;

        $text           = str_replace("%member_name%",      $member_name, $text);
        $text           = str_replace("%date%",             date('Y-m-d @H:i', strtotime($shop_order['date'])), $text);
        $text           = str_replace("%username%",         $member->username, $text);
        $text           = str_replace("%name%",             $member->name, $text);
        $text           = str_replace("%invoice%",          $invoice_txt, $text);
        $text           = str_replace("%bill_bank%",        $shop_order['bill_bank'], $text);
        $text           = str_replace("%bill_no%",          $shop_order['bill_no'], $text);
        $text           = str_replace("%bill_name%",        $shop_order['bill_name'], $text);
        $text           = str_replace("%amount%",           ddm_accounting($shop_order['amount']), $text);

        $plain_mail     = ddm_html2text($text);
        $html_mail      = ddm_notification_email_template($text, $subject);

        $message        = new stdClass();
        $message->plain = $plain_mail;
        $message->html  = $html_mail;

        if ( $view ) {
            return $message;
        } else {
            $send       = $this->send($to, $subject, $message, $from_mail, $from_name);
            // $send       = $this->send_gmail($member->email, $subject, $message, $from_mail, $from_name);
            if( $send ){
                return true;
            }
            return false;
        }
    }

    /**
     * Send email shop order function.
     *
     * @param   Object  $member     (Required)  Data of Member
     * @param   Object  $shop_order (Required)  Data of Product Order
     * @return  Mixed
     */
    function send_email_shop_order($member, $shop_order, $view = false){
        if ( !$member ) return false;
        if ( !$shop_order ) return false;
        if ( empty($member->email) ) return false;

        $from_name      = 'Admin ' . COMPANY_NAME;
        $from_mail      = get_option('mail_sender_admin');
        $from_mail      = $from_mail ? $from_mail : config_item('mail_sender');

        $invoice        = isset($shop_order->invoice) ? '('.$shop_order->invoice.')' : '';
        $subject        = 'Informasi Pemesanan Produk';
        if ( strtolower($shop_order->type) == 'perdana' ) {
            $subject    = 'Informasi Pendaftaran Agen dan Pemesanan Produk';
        }
        $subject_email  = $subject .' '. $invoice;
        $member_name    = strtolower($member->username) . ' / ' . strtoupper($member->name);

        $html_mail      = ddm_notification_shop_template('agent', $shop_order, $subject, $member_name, $member);
        $plain_mail     = ddm_html2text($html_mail);

        $message        = new stdClass();
        $message->plain = $plain_mail;
        $message->html  = $html_mail;

        if ( $view ) {
            return $message;
        } else {
            $send       = $this->send($member->email, $subject, $message, $from_mail, $from_name);
            if( $send ){
                return true;
            }
            return false;
        }
    }

    /**
     * Send email shop order customer function.
     *
     * @param   Object  $shop_order (Required)  Data of Product Order
     * @return  Mixed
     */
    function send_email_shop_order_customer($shop_order, $view = false){
        if ( !$shop_order ) return false;
        if ( empty($shop_order->email) ) return false;

        $from_name      = 'Admin ' . COMPANY_NAME;
        $from_mail      = get_option('mail_sender_admin');
        $from_mail      = $from_mail ? $from_mail : config_item('mail_sender');

        $invoice        = isset($shop_order->invoice) ? '('.$shop_order->invoice.')' : '';
        $subject        = 'Informasi Pemesanan Produk';
        $subject_email  = $subject .' '. $invoice;
        $member_name    = ucwords(strtolower($shop_order->name));

        $html_mail      = ddm_notification_shop_template('customer', $shop_order, $subject, $member_name);
        $plain_mail     = ddm_html2text($html_mail);

        $message        = new stdClass();
        $message->plain = $plain_mail;
        $message->html  = $html_mail;

        if ( $view ) {
            return $message;
        } else {
            $send       = $this->send($shop_order->email, $subject, $message, $from_mail, $from_name);
            if( $send ){
                return true;
            }
            return false;
        }
    }

    /**
     * Send email shop order function.
     *
     * @param   Object  $member     (Required)  Data of Member
     * @param   Object  $shop_order (Required)  Data of Product Order
     * @return  Mixed
     */
    function send_email_shop_order_to_agent($member, $shop_order, $view = false){
        if ( !$member ) return false;
        if ( !$shop_order ) return false;
        if ( empty($member->email) ) return false;

        $from_name      = 'Admin ' . COMPANY_NAME;
        $from_mail      = get_option('mail_sender_admin');
        $from_mail      = $from_mail ? $from_mail : config_item('mail_sender');

        $invoice        = isset($shop_order->invoice) ? '('.$shop_order->invoice.')' : '';
        $subject        = 'Informasi Pemesanan Konsumen';
        $subject_email  = $subject .' '. $invoice;
        $member_name    = strtolower($member->username) . ' / ' . strtoupper($member->name);

        $html_mail      = ddm_notification_shop_template('customer', $shop_order, $subject, $member_name, $member);
        $plain_mail     = ddm_html2text($html_mail);

        $message        = new stdClass();
        $message->plain = $plain_mail;
        $message->html  = $html_mail;

        if ( $view ) {
            return $message;
        } else {
            $send       = $this->send($member->email, $subject, $message, $from_mail, $from_name);
            if( $send ){
                return true;
            }
            return false;
        }
    }
}