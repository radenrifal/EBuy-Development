<?php
$menu = array(
    [
        'name' => 'Home',
        'icon' => 'fa fa-home',
        'link' => base_url('shop'),
    ],
    /*
    [
        'name' => 'Shop',
        'icon' => 'fa fa-gift',
        'link' => base_url('shop'),
    ],
    */
    [
        'name' => 'Find Agent',
        'icon' => 'fa fa-building',
        'link' => base_url('find-agent/contact'),
    ],
    [
        'name' => 'About Us',
        'icon' => 'fa fa-building',
        'link' => base_url('about-us-shop'),
    ],
    [
        'name' => 'Check Order',
        'icon' => 'fa fa-search',
        'link' => base_url('check-order'),
    ],
    [
        'name' => ( isset($member) && $member ) ? '<i class="fa fa-user"></i> '. user_info() : 'Login',
        'icon' => 'fa fa-user-circle-o',
        'link' => base_url('login'),
    ],
);
