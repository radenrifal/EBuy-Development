<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') or define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  or define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') or define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   or define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  or define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           or define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     or define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       or define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  or define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   or define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              or define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            or define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       or define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        or define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          or define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         or define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   or define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  or define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') or define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     or define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       or define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      or define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      or define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*
|--------------------------------------------------------------------------
| Website Title
|--------------------------------------------------------------------------
*/
define('TITLE',             "eBuy Shop | ");
define('COMPANY_NAME',      "eBuy Shop");
define('DOMAIN_NAME',       "ebuyshopdev.com");
define('COMPANY_ADDRESS',   "Jl. Jakarta - DKI Jakarta 11111");
define('COMPANY_PHONE',     "+62 1234 567");
define('COMPANY_EMAIL',     "info@" . DOMAIN_NAME);

/*
|--------------------------------------------------------------------------
| Server/Base URL
|--------------------------------------------------------------------------
*/
define('SCHEMA', (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://");
define('BASE_URL', SCHEMA . (isset($_SERVER["SERVER_NAME"]) ? $_SERVER["SERVER_NAME"] : '') . '/');
define('BASE_URL_LIVE', "https://ebuyshopdev.com" . '/');

/*
|--------------------------------------------------------------------------
| Document Root Path
|--------------------------------------------------------------------------
*/
define('ROOTPATH', rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/');

/*
|--------------------------------------------------------------------------
| APP and Assets  Folder Name
|--------------------------------------------------------------------------
*/
define('APP_FOLDER',                'application');
define('ASSET_FOLDER',              'assets');

if (defined('STDIN')) {
    // You should hardcode this for cli, otherwise it will fails.
    define('BASE_URL',              DOMAIN_NAME);
    define('DOMAIN_LIVE',           TRUE);
    define('DOMAIN_DEV',            FALSE);
} else {

    if ($_SERVER['SERVER_NAME'] == DOMAIN_NAME) {
        define('DOMAIN_LIVE',       TRUE);
        define('DOMAIN_DEV',        FALSE);
    } else {
        define('DOMAIN_DEV',        TRUE);
        define('DOMAIN_LIVE',       FALSE);
    }
}

/*
|--------------------------------------------------------------------------
| Page Settings
|--------------------------------------------------------------------------
*/
define('VIEW_AUTH',                 'auth/');
define('VIEW_BACK',                 'backend/');
define('VIEW_FRONT',                'frontend/');
define('VIEW_SHOP',                 'shop/');
define('VIEW_COMING_SOON',          'comingsoon/');
define('VIEW_MAINTENANCE',          'maintenance/');
define('VIEW_PATH',                 str_replace("\\", "/", VIEWPATH));

/*
|--------------------------------------------------------------------------
| Assets Path Settings
|--------------------------------------------------------------------------
*/
define('ASSET_PATH',                    BASE_URL . ASSET_FOLDER . '/');
define('ASSET_PATH_LIVE',               BASE_URL_LIVE . ASSET_FOLDER . '/');
define('PROFILE_IMG',                   BASE_URL . ASSET_FOLDER . '/upload/profile_picture/');
define('PRODUCT_IMG',                   BASE_URL . ASSET_FOLDER . '/upload/product/');
define('PRODUCT_IMG_PATH',              './'     . ASSET_FOLDER . '/upload/product/');
define('PAYMENT_IMG',                   BASE_URL . ASSET_FOLDER . '/upload/payment/');
define('PAYMENT_IMG_PATH',              './'     . ASSET_FOLDER . '/upload/payment/');
define('SLIDE_IMG',                     BASE_URL . ASSET_FOLDER . '/upload/slideshow/');
define('SLIDE_IMG_PATH',                './'     . ASSET_FOLDER . '/upload/slideshow/');

/*
|--------------------------------------------------------------------------
| Backend Assets Path Settings
|--------------------------------------------------------------------------
*/
define('BE_CSS_PATH',                   BASE_URL . ASSET_FOLDER . '/backend/css/');
define('BE_JS_PATH',                    BASE_URL . ASSET_FOLDER . '/backend/js/');
define('BE_IMG_PATH',                   BASE_URL . ASSET_FOLDER . '/backend/img/');
define('BE_IMG_LIVE_PATH',              BASE_URL_LIVE . ASSET_FOLDER . '/backend/img/');
define('BE_TREE_PATH',                  BASE_URL . ASSET_FOLDER . '/backend/img/tree/');
define('BE_PLUGIN_PATH',                BASE_URL . ASSET_FOLDER . '/backend/plugins/');
define('LOGO_IMG',                      BE_IMG_PATH . 'logo.png');

/*
|--------------------------------------------------------------------------
| Frontend Assets Path Settings
|--------------------------------------------------------------------------
*/
define('FE_CSS_PATH',                   BASE_URL . ASSET_FOLDER . '/frontend/css/');
define('FE_JS_PATH',                    BASE_URL . ASSET_FOLDER . '/frontend/js/');
define('FE_IMG_PATH',                   BASE_URL . ASSET_FOLDER . '/frontend/img/');
define('FE_FONTS_PATH',                 BASE_URL . ASSET_FOLDER . '/frontend/fonts/');
define('FE_PLUGIN_PATH',                BASE_URL . ASSET_FOLDER . '/frontend/plugins/');

/*
|--------------------------------------------------------------------------
| Shop Assets Path Settings
|--------------------------------------------------------------------------
*/
define('SH_CSS_PATH',                   BASE_URL . ASSET_FOLDER . '/shop/css/');
define('SH_JS_PATH',                    BASE_URL . ASSET_FOLDER . '/shop/js/');
define('SH_IMG_PATH',                   BASE_URL . ASSET_FOLDER . '/shop/img/');
define('SH_FONTS_PATH',                 BASE_URL . ASSET_FOLDER . '/shop/fonts/');
define('SH_PLUGIN_PATH',                BASE_URL . ASSET_FOLDER . '/shop/plugins/');

/*
|--------------------------------------------------------------------------
| Global Assets Path Settings
|--------------------------------------------------------------------------
*/
define('GLOBAL_PATH',                 BASE_URL . ASSET_FOLDER . '/global/');
define('GLOBAL_CSS_PATH',             BASE_URL . ASSET_FOLDER . '/global/css/');
define('GLOBAL_PLUGINS_PATH',         BASE_URL . ASSET_FOLDER . '/global/plugins/');

/*
|--------------------------------------------------------------------------
| Coming Soon and Maintenance Assets Path Settings
|--------------------------------------------------------------------------
*/
define('COMINGSOON_CSS_PATH',         BASE_URL . ASSET_FOLDER . '/comingsoon/css/');
define('COMINGSOON_JS_PATH',         BASE_URL . ASSET_FOLDER . '/comingsoon/js/');
define('MAINTENANCE_CSS_PATH',         BASE_URL . ASSET_FOLDER . '/maintenance/css/');
define('MAINTENANCE_JS_PATH',         BASE_URL . ASSET_FOLDER . '/maintenance/js/');

/*
|--------------------------------------------------------------------------
| Encryption / Key Config
|--------------------------------------------------------------------------
*/
define('DEBUG_KEY',         "debug123");
define('ENCRYPTION_KEY',    "q8tNvy4JZ9BS5v8MBG9EvHsjmQ2Y2S"); // is Unique
define('SECRET_IV',         "2456378494765431"); // 16bit
define('ENCRYPT_METHOD',    "aes-256-cbc");

/*
|--------------------------------------------------------------------------
| Auth Constant
|--------------------------------------------------------------------------
|
| These modes for set cookie
|
*/
define('AUTH_KEY',          '%4 N}|@na%Q;Tq$!3m?1^=u|PO_OO?!6Cr_l4h%MLbB<qu?%oj}l)+C~7;8p!vqI');
define('SECURE_AUTH_KEY',   '9`)6N;cRNBBEQG<}6P5zNS*F~#NU| uBsFb$K33-ynxgX1FE=SUP;BF-^@)Bj`CO');
define('LOGGED_IN_KEY',     '~16PA%~YtB1eWEvbozyjv01vo*4`[q3bI,O]I_].#9~S>qZHWgv/F??$=+?>uQ2l');
define('NONCE_KEY',         '))Z3:G![C@Oyb2bi=,OedV,n97J5b2M/Z&IJ*SmK*j/ApHxsRVt.cq|RDsY1mQ,)');
define('AUTH_SALT',         'w?e[S&y@,Pv7qJ&i.3*_I}{&uVm=2%B3AHt3{?PjFwvOQ|vYA^IPTf.^@,vx=d8&');
define('SECURE_AUTH_SALT',  '/wKdAgx=D?{wbw8{Mi-57JG6(+rfS:]MD{Gxp`dWyr^WyCtW]+ihseR]Rmh5p=N*');
define('LOGGED_IN_SALT',    'E(:=@55g ^ODRh9i6>PVRpW4J/u-}70N}7ALGnBey1hg7_#|-@1G<c8g]*|Fp]Q1');
define('NONCE_SALT',        'l`)q2S5Y6rY&%/Q`U,17@KfP)Okc?[Dwxqq,P*X!vh!Lp0/E|cw^d?z6D:F|4FuP');

/*
|--------------------------------------------------------------------------
| Unique Hash Cookie
|--------------------------------------------------------------------------
|
| Used to guarantee unique hash cookies
|
*/
define('COOKIEHASH', md5('[:ddmmember:]'));
define('MEMBER_COOKIE', 'ddmmember_' . COOKIEHASH);
define('PASS_COOKIE', 'ddmpass_' . COOKIEHASH);
define('AUTH_COOKIE', 'ddm_' . COOKIEHASH);
define('SECURE_AUTH_COOKIE', 'ddm_sec_' . COOKIEHASH);
define('LOGGED_IN_COOKIE', 'ddm_logged_in_' . COOKIEHASH);

/*
|--------------------------------------------------------------------------
| CSS and JS versioning
|--------------------------------------------------------------------------
|
| Used to version custom CSS and JS so that user do not have clear their browser cache manually
|
*/
define('CSS_VER_AUTH',    '0.0.0.0.00.09');
define('CSS_VER_MAIN',    '0.0.0.0.00.09');
define('CSS_VER_FRONT',   '0.0.0.0.00.09');
define('CSS_VER_BACK',    '0.0.0.0.00.09');

define('JS_VER_AUTH',    '0.0.0.0.00.16');
define('JS_VER_MAIN',    '0.0.0.0.00.16');
define('JS_VER_FRONT',   '0.0.0.0.00.16');
define('JS_VER_BACK',    '0.0.0.0.00.35');
define('JS_VER_PAGE',    '0.0.0.0.00.35');

define('VER_MANIFEST',  '2');

/*
|--------------------------------------------------------------------------
| Member Type
|--------------------------------------------------------------------------
*/
define('ADMINISTRATOR', 1);
define('MEMBER',        2);
define('CUSTOMER',      0);

/*
|--------------------------------------------------------------------------
| Member Status
|--------------------------------------------------------------------------
*/
define('NONACTIVE',     0);
define('ACTIVE',        1);
define('BANNED',        2);
define('DELETED',       3);

/*
|--------------------------------------------------------------------------
| Position
|--------------------------------------------------------------------------
*/
define('POS_LEFT',      'left');
define('POS_RIGHT',     'right');

/*
|--------------------------------------------------------------------------
| Package Member
|--------------------------------------------------------------------------
*/
define('MEMBER_AGENT',          'agent');
define('MEMBER_MASTER_AGENT',   'masteragent');
define('MEMBER_CUSTOMER',       'konsumen');

/*
|--------------------------------------------------------------------------
| Rank Member
|--------------------------------------------------------------------------
*/
define('RANK_AGENT',            'agent');
define('RANK_STAR_AGENT',       'star');
define('RANK_SUPER_AGENT',      'super');

/*
|--------------------------------------------------------------------------
| Bonus
|--------------------------------------------------------------------------
*/
define('BONUS_AGA',         1);     // AGA (Agent Get Agent)
define('BONUS_GA',          2);     // GA (Group Agent)

/*
|--------------------------------------------------------------------------
| SKPD Type
|--------------------------------------------------------------------------
*/
define('KABUPATEN',     1);
define('SEKRETARIAT',   2);
define('DINAS',            3);
define('BADAN',            4);
define('INSPEKTORAT',     5);
define('KECAMATAN',     6);
define('RSU',             7);

/*
|--------------------------------------------------------------------------
| Staff access
|--------------------------------------------------------------------------
*/
define('STAFF_ACCESS1', 1);
define('STAFF_ACCESS2', 2);
define('STAFF_ACCESS3', 3);
define('STAFF_ACCESS4', 4);
define('STAFF_ACCESS5', 5);
define('STAFF_ACCESS6', 6);
define('STAFF_ACCESS7', 7);
define('STAFF_ACCESS8', 8);
define('STAFF_ACCESS9', 9);
define('STAFF_ACCESS10', 10);
define('STAFF_ACCESS11', 11);
define('STAFF_ACCESS12', 12);
define('STAFF_ACCESS13', 13);
define('STAFF_ACCESS14', 14);
define('STAFF_ACCESS15', 15);


/*
|--------------------------------------------------------------------------
| TABLE
|--------------------------------------------------------------------------
*/
define('TBL_PREFIX',           'ddm_');
define('TBL_LOG',           'ddm_log');
define('TBL_LOG_CRON',        'ddm_log_cron');
define('TBL_LOG_NOTIF',     'ddm_log_notif');
define('TBL_LOG_ACTIONS',    'ddm_log_action');
define('TBL_OPTIONS',       'ddm_options');
define('TBL_SESSIONS',       'ddm_sessions');
define('TBL_PRODUCT',       'ddm_product');
define('TBL_DISCOUNT',       'ddm_promo_code');
define('TBL_SHOP_ORDER',       'ddm_shop_order');
define('TBL_USER_SHIPPING', 'ddm_member_shipping');
define('TBL_USERS',           'ddm_member');


/*
|--------------------------------------------------------------------------
| Mailer Engine
|--------------------------------------------------------------------------
|
| Swift Mailer Location
|
*/
define('SWIFT_MAILSERVER', realpath(dirname(__FILE__) . '/..') . DIRECTORY_SEPARATOR . '/libraries/swiftmailer/swift_required.php');
