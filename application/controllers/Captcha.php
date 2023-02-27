<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Captcha extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    function index(){
        echo $this->get_create_capt();
    }

    function get_create_capt(){
        ob_start();
        error_reporting(0);
        header("Content-type: image/png");
        $gbr = imagecreate(110, 30);

        $v = $this->input->get("v");

        if ( ! $v ) {
            $v = mt_rand(100000, 999999);
        }

        if ( $this->session->userdata('ddm_capt') ) {
            $this->session->unset_userdata('ddm_capt');
        }
        $this->session->set_userdata(array('ddm_capt' => $v));

        imagecolorallocate($gbr, 229, 229, 229);
        $white = imagecolorallocate($gbr, 50, 50, 50);
        $line_color = imagecolorallocate($gbr, 231, 240, 255);
        // $line_color = imagecolorallocate($gbr, 0, 0, 0);

        $font = './' . ASSET_FOLDER . '/backend/fonts/Monaco.ttf';
        $capt_kode = '';

        for($i = 0; $i < 6; $i++) {
            $nomor      = rand(0, 9);
            $capt_kode .= $nomor;
            $capt       = substr($v, $i, 1);
            $sudut      = rand(0, 20);
            imagestring($gbr, 25, 13+($i*15), 8, $capt, $white);
        }

        // imageline($gbr, 0, 13, 100, 18, $line_color);
        imagepng($gbr);
        imagedestroy($gbr);
    }
}