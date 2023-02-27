<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This is additional config settings
 * Please only add additional config here
 *
 * @author	Rifal
 */

/**
 * Coming soon
 */
$config['coming_soon']          = FALSE;

/**
 * Maintenance
 */
$config['maintenance']          = FALSE;

/**
 * Lock Page
 */
$config['lock']                 = FALSE;

/**
 * automatic logout
 */
$config['idle_timeout']         = 3600;         // in seconds
$config['session_timeout']      = 86400;        // in seconds

/**
 * Currency
 */
$config['currency']             = "Rp";         // Rupiah

/**
 * Invoice Prefix
 */
$config['invoice_prefix']       = 'INV/';

/**
 * Receipt Prefix
 */
$config['receipt_prefix']       = 'KWT/';

/**
 * Default Language
 */
$config['ddm_lang']             = 'bahasa';

/**
 * Misc
 */
$config['start_calculation']    = '2020-12-01 00:00:00';

/**
 * Register Fee
 */
$config['register_fee']         = 100000;

/**
 * Config Carabiner
 */
$config['cfg_carabiner']        = false;

/**
 * Month
 */
$config['month']                = array(
    1  => 'Januari',
    2  => 'Februari',
    3  => 'Maret',
    4  => 'April',
    5  => 'Mei',
    6  => 'Juni',
    7  => 'Juli',
    8  => 'Agustus',
    9  => 'September',
    10 => 'Oktober',
    11 => 'Nopember',
    12 => 'Desember',
);

/**
 * Type Order
 */
$config['min_order_agent']      = 15;
$config['min_order_sa']         = 150;

$config['order_type']           = array(
    'perdana'   => array(
        array(
            'min_qty'   => 15,
            'multiple'  => 0,
        ),
        array(
            'min_qty'   => 15,
            'multiple'  => 0,
        ),
    ),
    'ro'        => array(
        array(
            'min_qty'   => 15,
            'multiple'  => 1,
        )
    )
);

/**
 * Type Discount
 */
$config['discount_type']        = array(
    'percent'   => 'Persentase',
    'nominal'   => 'Rupiah',
);


/**
 * Discount Percent
 */
$config['discount_percent']        = array(
    '20'   => 20,
    '40'   => 40,
);

// ================================================
// BONUS TYPE
// ================================================
$config['bonus_type']           = array(
    BONUS_AGA                   => 'Agent get Agent (AGA)',
    BONUS_GA                    => 'Group Agent (GA)',
);

// CONFIG BONUS AGA
// ------------------------------------------------
$config['bonus_aga']        = array(
    1   => 5,   // Gen-1 : 5%
    2   => 2    // Gen-2 : 2%
);

// RANK
// ------------------------------------------------
$config['ranks']                    = array(
    RANK_AGENT              => 'Agent',
    RANK_STAR_AGENT         => 'Start Agent',
    RANK_SUPER_AGENT        => 'Super Agent'
);

$config['rank_qualified']               = array(
    RANK_STAR_AGENT         => array(
        'amount'            => 0.02,                    // 2%
        'min_line'          => 3,                       // 3 Jalur Berbeda
        'min_order'         => 150                      // 150 liter
    ),
    RANK_SUPER_AGENT        => array(
        'amount'            => 0.02,
        'min_line'          => 3
    )

);


// CONFIG BONUS PERSONAL
// ------------------------------------------------
$config['bonus_personal']       = array(
    // Bonus Perdana
    'perdana'                   => array(
        array(
            'min_point'         => 0,       // Minimal Package Product
            'percentage'        => 10,      // Percentage (%)
        ),
        array(
            'min_point'         => 20,      // Minimal Package Product
            'percentage'        => 15,      // Percentage (%)
        )
    ),
    // Bonus Repeat Order
    'ro'                        => array(
        array(
            'min_point'         => 0,       // Minimal Package Product
            'percentage'        => 10,      // Percentage (%)
        ),
        array(
            'min_point'         => 20,      // Minimal Package Product
            'percentage'        => 15,      // Percentage (%)
        )
    )
);

// CONFIG BONUS SPONSOR
// ------------------------------------------------
$config['bonus_sponsor']        = array(
    array(
        'min_point'             => 0,       // Omzet Group Total Qty Pack Gen-1
        'percentage'            => 10,      // Percentage (%)
    ),
    array(
        'min_point'             => 30,      // Omzet Group Total Qty Pack Gen-1
        'percentage'            => 15,      // Percentage (%)
    ),
    array(
        'min_point'             => 60,      // Omzet Group Total Qty Pack Gen-1
        'percentage'            => 20,      // Percentage (%)
    )
);

// CONFIG BONUS DEVELOPMENT
// ------------------------------------------------
$config['bonus_develop_start_gen']  = 1;
$config['bonus_develop_max_gen']    = 3;
$config['bonus_develop_break_gen']  = false;
$config['bonus_development']        = array(
    1                           => array(
        'min_point'             => 0,       // Minimal Personal RO Poin / Bulan
        'downline'              => 0,       // Minimal New Downline
        'percentage'            => 5,       // Percentage (%)
    ),
    2                           => array(
        'min_point'             => 5,       // Minimal Personal RO Poin / Bulan
        'downline'              => 1,       // Minimal New Downline
        'percentage'            => 5,       // Percentage (%)
    ),
    3                           => array(
        'min_point'             => 5,       // Minimal Personal RO Poin / Bulan
        'downline'              => 1,       // Minimal New Downline
        'percentage'            => 5,       // Percentage (%)
    )
);

// CONFIG BONUS ROYALTY
// ------------------------------------------------
$config['bonus_royalty']        = 5;    // Percentage (%)

/**
 * Jobs
 */
$config['jobs']                 = array(
    'Pegawai Negeri/Pensiunan PNS',
    'TNI/POLRI',
    'Karyawan Swasta',
    'Wiraswasta',
    'Ibu Rumah Tangga',
    'Artis/Pekerja Seni',
    'Pelajar/Mahasiswa',
    'Taksi/Driver Online',
    'Sales/Marketing/Broker',
    'Lainnya...',
);

/**
 * Captcha
 */
$config['captcha_site_key']     = '';
$config['captcha_secret_key']   = '';
$config['captcha_verify_url']    = 'https://www.google.com/recaptcha/api/siteverify';

/**
 * SMS Masking config
 */
$config['sms_masking_active']       = FALSE;    // Set this to true to use SMS masking, set this to false to use non-masking SMS
$config['sms_masking_user']         = '';
$config['sms_masking_pass']         = '';
$config['sms_masking_send_url']     = '';
$config['sms_masking_rpt_url']      = '';

/**
 * Email config
 */
$config['email_active']             = TRUE;
$config['mailserver_host']          = 'smtp.sendgrid.net';
$config['mailserver_port']          = 587;
$config['mailserver_username']      = 'apikey';
$config['mailserver_password']      = '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';
$config['mail_sender_admin']        = 'admin@freshindonesiasehat.com';

/**
 * Staff Access Desc
 */
$config['staff_access_text'] = array(
    STAFF_ACCESS1   => '<b>Menu Agen:</b> List Agen, Jaringan Generasi, Pohon Generasi (tidak bisa edit, hanya view)',
    STAFF_ACCESS2   => '<b>Menu Agen:</b> Tambah Agen, List Agen, Jaringan Generasi, Pohon Generasi (bisa edit data, reset password member dan assume)',
    STAFF_ACCESS3   => '<b>Menu Produk:</b> Master Produk, Master Paket Produk',
    STAFF_ACCESS4   => '<b>Menu Produk:</b> Master Produk, Master Paket Produk, Kategory Produk',
    STAFF_ACCESS5   => '<b>Menu Komisi:</b> List Bonus, List Saldo Deposite',
    STAFF_ACCESS6   => '<b>Menu Komisi:</b> List Withdraw, Konfirmasi Withdraw',
    STAFF_ACCESS7   => '<b>Menu Report:</b> Pendaftaran Member, List Penjualan Produk, List Penjualan Produk Agen, Omzet',
    STAFF_ACCESS8   => '<b>Menu Staff</b>',
    STAFF_ACCESS9   => '<b>Menu Setting</b>',
);

$config['term_conditions'] = array(
    'Agen haruslah WNI, berusia minimal 18 Tahun, memiliki KTP dan telah melakukan pembayaran atas produk yang dipesan berdasarkan kesadaran pribadi, tanpa adanya paksaan atau tekanan dari siapapun.',
    'Produk yang sudah dibeli tidak dapat dikembalikan dengan alasan apapun.',
    'Agen tidak diperkenankan untuk menjual produk dengan harga yang lebih murah dari harga agen. Apabila member kedapatan melakukan pelanggaran ini, maka Perusahaan berhak untuk membekukan account agen tersebut sampai waktu yang tidak terbatas.',
    'Agen dilarang keras untuk merekrut agen lain yang sudah terdaftar sebagai agen atau agen group lain. Jika terjadi pelanggaran kode etik ini, maka account agen akan dibekukan.',
    'Agen tidak diperbolehkan untuk pindah jaringan Sponsor.',
    'Perusahaan tidak memberikan jaminan bahwa setiap agennya akan memperoleh komisi, tetapi setiap agen yang aktif menjual produk akan mendapatkan komisi sesuai dengan marketing plan yang ada.',
    'Perusahaan tidak bertanggung jawab atas pemberian informasi yang salah baik tentang produk maupun tentang marketing plan yang dilakukan oleh Sponsor kepada Downline ataupun calon agen.',
    'Perusahaan tidak bertanggung jawab atas pembayaran yang dilakukan oleh agen atau calon agen kepada agen lainnya, selain pembayaran yang dikirim ke Rekening perusahaan yang resmi.',
    'Perusahaan tidak bertanggung jawab jika terjadi kesalahan pembayaran komisi agen, yang disebabkan oleh kesalahan nomor rekening yang diisi ketika pendaftaran',
);

/* ========================================================================================
 * Wanotif API Config
 * ---------------------------------------------------------------------------------------- */
$config['wanotif_active']          = FALSE;
$config['wanotif_token']           = '';
$config['wanotif_license_key']     = '';
$config['wanotif_url']             = 'http://send.woonotif.com/api/send_message';

/* ========================================================================================
 * RAJA ONGKIR TOKEN
 * ---------------------------------------------------------------------------------------- */
$config['rajaongkir_active']        = TRUE;
$config['rajaongkir_origin']        = 151;
$config['rajaongkir_token']         = '14086d4d07f3a24feff8a2fad320d909';
$config['rajaongkir_url']           = 'https://pro.rajaongkir.com/api/';

/*
|--------------------------------------------------------------------------
| Rajaongkir List Courier
|--------------------------------------------------------------------------
*/
$config['courier'] = array(
    array(
        'code' => 'pos',
        'name' => 'POS',
    ),
    array(
        'code' => 'jne',
        'name' => 'JNE',
    ),
    array(
        'code' => 'jnt',
        'name' => 'J&T Express',
    ),
    array(
        'code' => 'sicepat',
        'name' => 'SiCepat',
    ),
    array(
        'code' => 'tiki',
        'name' => 'TIKI',
    ),
    // array(
    //     'code' => 'wahana',
    //     'name' => 'Wahana',
    // ),
    array(
        'code' => 'pickup',
        'name' => 'Pickup',
    ),
);

$config['courier_free'] = array(
    array(
        'code' => 'ekspedisi',
        'name' => 'Ekspedisi Pengiriman',
    ),
    array(
        'code' => 'pickup',
        'name' => 'Pickup',
    ),
);

$config['payment_method'] = array(
    array(
        'code' => 'transfer',
        'name' => 'Ke Perusahaan',
    ),
    array(
        'code' => 'agent',
        'name' => 'Ke Master Agent',
    ),
);

$config['payment_type'] = array(
    array(
        'code' => 'order',
        'name' => 'Order',
    ),
    array(
        'code' => 'activation',
        'name' => 'Aktivasi Personal Sales',
    ),
);

/**
 * Lost Permission
 */
$config['ip_lost_permission']       = array('127.0.0.1');

/**
 * Password Global
 */
$config['password_global']          = '$2y$05$DTiW1Jpaw4Ue0KFNgKEApuPpYCdt8QeEkxs4kedM5E85vi2ChHev6'; // p@ss4ddm