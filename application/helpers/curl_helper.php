<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Load CURL library
 * 
 * @since 1.0.0
 * @link https://github.com/php-curl-class/php-curl-class
 */
require APPPATH . 'libraries/curl/vendor/autoload.php';
use \Curl\Curl;

/**
 * 
 */
function ddm_curl() {
	return new Curl();
}

/**
 * 
 */
function ddm_curl_get( $url ) {
	$curl = new Curl();
	// set options
	$curl->setOpt( CURLOPT_SSL_VERIFYPEER, false );
	return $curl->get( $url );
}

/**
 * 
 */
function ddm_curl_post( $url, $param = array() ) {
	$curl = new Curl();
	// set options
	$curl->setOpt( CURLOPT_SSL_VERIFYPEER, false );
	return $curl->post( $url, $param );
}
