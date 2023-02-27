<?php if (!defined("BASEPATH")) exit("No direct script access allowed");

/**
 * Generate Encode
 * @author  Yuda
 * @return  String
 */
if ( !function_exists('ddm_encrypt') )
{
    function ddm_encrypt($string = '', $type = 'encrypt', $url_code = true){
        if ( !$string || !$type ) { return false; }
        $string_code = encrypt_param($string, $url_code);
        if ($type == 'decrypt') {
            $string_code = decrypt_param($string, $url_code);
        }
        return $string_code;
    }
}

if (!function_exists('ddm_decrypt')) {
    function ddm_decrypt($string = '', $url_code = true, $ajax = false)
    {
        if ( !$string ) { return false; }
        $decrypt = decrypt_param($string, $url_code, $ajax);
        return $decrypt;
    }
}

if (!function_exists('ddm_password_hash')) {
    function ddm_password_hash($password = '')
    {
        if ( !$password ) { return false; }
        $password_hash = get_hash($password);
        return $password_hash;
    }
}

if (!function_exists('ddm_hash_verify')) {
    function ddm_hash_verify($password = '', $hash_password = '')
    {
        if ( !$password || !$hash_password ) { return false; }
        $password_verify = hash_verify($password, $hash_password);
        return $password_verify;
    }
}

/*
|--------------------------------------------------------------------------
| Password hash helper (encryption)
|--------------------------------------------------------------------------
*/
if (!function_exists('get_hash')) {

    function get_hash($plain_password = '')
    {
        $option = [
            'cost' => 5, // proses hash sebanyak: 2^5 = 32x
        ];
        return password_hash($plain_password, PASSWORD_DEFAULT, $option);
    }
}

/*
|--------------------------------------------------------------------------
| Password verify helper (encryption)
|--------------------------------------------------------------------------
*/
if (!function_exists('hash_verify')) {

    function hash_verify($plain_password, $hash_password)
    {
        return password_verify($plain_password, $hash_password) ? true : false;
    }
}


/*
|--------------------------------------------------------------------------
| Encrypt Parameter
|--------------------------------------------------------------------------
*/
function encrypt_param($string, $url_code = true)
{

    $output = false;

    $secret_key     = ENCRYPTION_KEY;
    $secret_iv      = SECRET_IV;
    $encrypt_method = ENCRYPT_METHOD;

    // hash
    $key    = hash("sha256", $secret_key);

    // iv – encrypt method AES-256-CBC expects 16 bytes – else you will get a warning
    $iv     = substr(hash("sha256", $secret_iv), 0, 16);

    //do the encryption given text/string/number
    $result = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
    $output = base64_encode($result);

    if (!$output) {
        return FALSE;
    } else {
        if ($url_code) {
            $output = strtr($output, array('+' => '.', '=' => '-', '/' => '~'));
        }
        return $output;
    }
}


/*
|--------------------------------------------------------------------------
| Decrypt Parameter
|--------------------------------------------------------------------------
*/
function decrypt_param($string, $url_code = true, $ajax = false)
{

    $output = false;

    $secret_key     = ENCRYPTION_KEY;
    $secret_iv      = SECRET_IV;
    $encrypt_method = ENCRYPT_METHOD;

    // hash
    $key = hash("sha256", $secret_key);

    // iv – encrypt method AES-256-CBC expects 16 bytes – else you will get a warning
    $iv = substr(hash("sha256", $secret_iv), 0, 16);

    //do the decryption given text/string/number
    if ($url_code) {
        $string = strtr($string, array('.' => '+', '-' => '=', '~' => '/'));
    }
    $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);

    if ( !$output ) {
        if ( $ajax ) {
            $response = array('status'  => 'failed', 'message' => 'Decrypt Failed!');
            die(json_encode($response));
        }
        return false;
    } else {
        return $output;
    }
}

/*
|--------------------------------------------------------------------------
| Sanitize Input
|--------------------------------------------------------------------------
*/
function sanitize($input)
{
    if (isset($input)) {
        $input = trim($input);
        $input = strtr($input, array_flip(get_html_translation_table(HTML_ENTITIES)));
        $input = strip_tags($input);
        $input = htmlspecialchars($input);
        $input = htmlentities($input);
        $input = html_escape($input);

        return $input;
    } else {
        return NULL;
    }
}
