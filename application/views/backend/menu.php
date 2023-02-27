<?php
    $sidebar        = '';
    if( as_administrator($member) ){
        $sidebar    = array(
            array(
                'title' => lang('menu_dashboard'),
                'nav'   => 'dashboard',
                'link'  => base_url('dashboard'),
                'icon'  => 'ni-tv-2',
                'roles' => array(),
                'sub'   => false,
            ),
            array(
                'title' => lang('menu_member'),
                'nav'   => 'member',
                'link'  => 'javascript:;',
                'icon'  => 'ni-single-02',
                'roles' => array(STAFF_ACCESS1, STAFF_ACCESS2),
                'sub'   => array(
                    array(
                        'title' => lang('menu_member_new'),
                        'nav'   => 'new',
                        'link'  => base_url('member/new'),
                        'icon'  => 'fa-user-plus',
                        'roles' => array(STAFF_ACCESS2),
                        'sub'   => false,
                    ),
                    array(
                        'title' => lang('menu_member_list'),
                        'nav'   => 'lists',
                        'link'  => base_url('member/lists'),
                        'icon'  => 'fa-list',
                        'roles' => array(STAFF_ACCESS1, STAFF_ACCESS2),
                        'sub'   => false,
                    ),
                    array(
                        'title' => lang('menu_member_generation'),
                        'nav'   => 'generation',
                        'link'  => base_url('member/generation'),
                        'icon'  => 'fa-list',
                        'roles' => array(STAFF_ACCESS1, STAFF_ACCESS2),
                        'sub'   => false,
                    ),
                    array(
                        'title' => lang('menu_member_generation_tree'),
                        'nav'   => 'generationtree',
                        'link'  => base_url('member/generationtree'),
                        'icon'  => 'fa-sitemap',
                        'roles' => array(STAFF_ACCESS1, STAFF_ACCESS2),
                        'sub'   => false,
                    ),
                ),
            ),
            array(
                'title' => lang('menu_product'),
                'nav'   => 'productmanage',
                'link'  => 'javascript:;',
                'icon'  => 'ni-bag-17',
                'roles' => array(STAFF_ACCESS3,STAFF_ACCESS4),
                'sub'   => array(
                    array(
                        'title' => lang('menu_product_list'),
                        'nav'   => 'productlist',
                        'link'  => base_url('productmanage/productlist'),
                        'icon'  => 'fa-list',
                        'roles' => array(STAFF_ACCESS3,STAFF_ACCESS4),
                        'sub'   => false,
                    ),
                    array(
                        'title' => lang('menu_package_list'),
                        'nav'   => 'packagelist',
                        'link'  => base_url('productmanage/packagelist'),
                        'icon'  => 'fa-list',
                        'roles' => array(STAFF_ACCESS3,STAFF_ACCESS4),
                        'sub'   => false,
                    ),
                    array(
                        'title' => lang('menu_product_category'),
                        'nav'   => 'categorylist',
                        'link'  => base_url('productmanage/categorylist'),
                        'icon'  => 'fa-list',
                        'roles' => array(STAFF_ACCESS4),
                        'sub'   => false,
                    ),
                )
            ),
            array(
                'title' => lang('menu_financial'),
                'nav'   => 'commission',
                'link'  => 'javascript:;',
                'icon'  => 'ni-credit-card',
                'roles' => array(STAFF_ACCESS5,STAFF_ACCESS6),
                'sub'   => array(
                    array(
                        'title' => lang('menu_financial_bonus'),
                        'nav'   => 'bonus',
                        'link'  => base_url('commission/bonus'),
                        'icon'  => 'fa-money-bill',
                        'roles' => array(STAFF_ACCESS5),
                        'sub'   => false,
                    ),
                    array(
                        'title' => lang('menu_financial_deposite'),
                        'nav'   => 'deposite',
                        'link'  => base_url('commission/deposite'),
                        'icon'  => 'fa-money-bill',
                        'roles' => array(STAFF_ACCESS5),
                        'sub'   => false,
                    ),
                    array(
                        'title' => lang('menu_financial_withdraw'),
                        'nav'   => 'withdraw',
                        'link'  => base_url('commission/withdraw'),
                        'icon'  => 'fa-money-bill',
                        'roles' => array(STAFF_ACCESS6),
                        'sub'   => false,
                    ),
                )
            ),
            array(
                'title' => lang('menu_report'),
                'nav'   => 'report',
                'link'  => 'javascript:;',
                'icon'  => 'ni-single-copy-04',
                'roles' => array(STAFF_ACCESS7),
                'sub'   => array(
                    array(
                        'title' => lang('menu_report_register'),
                        'nav'   => 'registration',
                        'link'  => base_url('report/registration'),
                        'icon'  => 'fa-file-alt',
                        'roles' => array(STAFF_ACCESS7),
                        'sub'   => false,
                    ),
                    array(
                        'title' => 'Orderan ke Perusahaan',
                        'nav'   => 'sales',
                        'link'  => base_url('report/sales'),
                        'icon'  => 'fa-file-alt',
                        'roles' => array(STAFF_ACCESS7),
                        'sub'   => false,
                    ),
                    array(
                        'title' => 'Orderan ke Master Agen',
                        'nav'   => 'ordercustomer',
                        'link'  => base_url('report/ordercustomer'),
                        'icon'  => 'fa-file-alt',
                        'roles' => array(STAFF_ACCESS7),
                        'sub'   => false,
                    ),
                    array(
                        'title' => lang('menu_report_omzet'),
                        'nav'   => 'omzet',
                        'link'  => base_url('report/omzet'),
                        'icon'  => 'fa-file-alt',
                        'roles' => array(STAFF_ACCESS7),
                        'sub'   => false,
                    ),
                    array(
                        'title' => 'Produk Agent',
                        'nav'   => 'product',
                        'link'  => base_url('report/product'),
                        'icon'  => 'fa-file-alt',
                        'roles' => array(STAFF_ACCESS8),
                        'sub'   => false,
                    ),
                    array(
                        'title' => 'Personal Sales Agen',
                        'nav'   => 'personalomzet',
                        'link'  => base_url('report/personalomzet'),
                        'icon'  => 'fa-file-alt',
                        'roles' => array(STAFF_ACCESS8),
                        'sub'   => false,
                    ),
                )
            ),
            array(
                'title' => lang('menu_setting_staff'),
                'nav'   => 'staff',
                'link'  => base_url('staff'),
                'icon'  => 'ni-circle-08',
                'roles' => array(STAFF_ACCESS8),
                'sub'   => false,
            ),
            array(
                'title' => lang('menu_setting'),
                'nav'   => 'setting',
                'link'  => 'javascript:;',
                'icon'  => 'ni-palette',
                'roles' => array(STAFF_ACCESS9),
                'sub'   => array(
                    array(
                        'title' => lang('menu_setting_general'),
                        'nav'   => 'general',
                        'link'  => base_url('setting/general'),
                        'icon'  => 'fa-cog',
                        'roles' => array(STAFF_ACCESS9),
                        'sub'   => false,
                    ),
                    array(
                        'title' => lang('menu_setting_notification'),
                        'nav'   => 'notification',
                        'link'  => base_url('setting/notification'),
                        'icon'  => 'fa-cog',
                        'roles' => array(STAFF_ACCESS9),
                        'sub'   => false,
                    ),
                    array(
                        'title' => lang('menu_setting_withdraw'),
                        'nav'   => 'withdraw',
                        'link'  => base_url('setting/withdraw'),
                        'icon'  => 'fa-cog',
                        'roles' => array(STAFF_ACCESS9),
                        'sub'   => false,
                    ),
                ),
            ),
        );
    } else {
        $sidebar    = array(
            array(
                'title' => lang('menu_dashboard'),
                'nav'   => 'dashboard',
                'link'  => base_url('dashboard'),
                'icon'  => 'ni-tv-2',
                'roles' => array(),
                'sub'   => false,
            ),
            array(
                'title' => lang('menu_member'),
                'nav'   => 'member',
                'link'  => 'javascript:;',
                'icon'  => 'ni-single-02',
                'roles' => array(STAFF_ACCESS1, STAFF_ACCESS2),
                'sub'   => array(
                    array(
                        'title' => lang('menu_member_new'),
                        'nav'   => 'new',
                        'link'  => base_url('member/new'),
                        'icon'  => 'fa-user-plus',
                        'roles' => array(STAFF_ACCESS2),
                        'sub'   => false,
                    ),
                    array(
                        'title' => lang('menu_member_generation'),
                        'nav'   => 'generation',
                        'link'  => base_url('member/generation'),
                        'icon'  => 'fa-list',
                        'roles' => array(STAFF_ACCESS1, STAFF_ACCESS2),
                        'sub'   => false,
                    ),
                    array(
                        'title' => lang('menu_member_generation_tree'),
                        'nav'   => 'generationtree',
                        'link'  => base_url('member/generationtree'),
                        'icon'  => 'fa-sitemap',
                        'roles' => array(STAFF_ACCESS1, STAFF_ACCESS2),
                        'sub'   => false,
                    ),
                ),
            ),
            array(
                'title' => lang('menu_financial'),
                'nav'   => 'commission',
                'link'  => 'javascript:;',
                'icon'  => 'ni-credit-card',
                'roles' => array(STAFF_ACCESS5,STAFF_ACCESS6),
                'sub'   => array(
                    array(
                        'title' => lang('menu_financial_bonus'),
                        'nav'   => 'bonus',
                        'link'  => base_url('commission/bonus'),
                        'icon'  => 'fa-money-bill',
                        'roles' => array(STAFF_ACCESS5),
                        'sub'   => false,
                    ),
                    array(
                        'title' => lang('menu_financial_deposite'),
                        'nav'   => 'deposite',
                        'link'  => base_url('commission/deposite'),
                        'icon'  => 'fa-money-bill',
                        'roles' => array(STAFF_ACCESS5),
                        'sub'   => false,
                    ),
                    array(
                        'title' => lang('menu_financial_withdraw'),
                        'nav'   => 'withdraw',
                        'link'  => base_url('commission/withdraw'),
                        'icon'  => 'fa-money-bill',
                        'roles' => array(STAFF_ACCESS6),
                        'sub'   => false,
                    ),
                )
            ),
            array(
                'title' => lang('menu_report'),
                'nav'   => 'report',
                'link'  => 'javascript:;',
                'icon'  => 'ni-single-copy-04',
                'roles' => array(STAFF_ACCESS9),
                'sub'   => array(
                    array(
                        'title' => lang('menu_report_register'),
                        'nav'   => 'registration',
                        'link'  => base_url('report/registration'),
                        'icon'  => 'fa-file-alt',
                        'roles' => array(STAFF_ACCESS8),
                        'sub'   => false,
                    ),
                    array(
                        'title' => lang('menu_report_buy'),
                        'nav'   => 'order',
                        'link'  => base_url('report/order'),
                        'icon'  => 'fa-file-alt',
                        'roles' => array(STAFF_ACCESS8),
                        'sub'   => false,
                    ),
                    array(
                        'title' => lang('menu_report_product'),
                        'nav'   => 'product',
                        'link'  => base_url('report/product'),
                        'icon'  => 'fa-file-alt',
                        'roles' => array(STAFF_ACCESS8),
                        'sub'   => false,
                    ),
                    array(
                        'title' => 'Personal Sales Agen',
                        'nav'   => 'personalomzet',
                        'link'  => base_url('report/personalomzet'),
                        'icon'  => 'fa-file-alt',
                        'roles' => array(STAFF_ACCESS8),
                        'sub'   => false,
                    ),
                    array(
                        'title' => 'Pesanan Produk Masuk',
                        'nav'   => 'ordercustomer',
                        'link'  => base_url('report/ordercustomer'),
                        'icon'  => 'fa-file-alt',
                        'roles' => array(STAFF_ACCESS8),
                        'sub'   => false,
                    ),
                )
            )
        );
        
        if( $member->package == MEMBER_AGENT ){
            unset($sidebar[3]['sub'][4]);
        }
    }

    $active_page    = ( $this->uri->segment(1, 0) ? $this->uri->segment(1, 0) : '');
    $active_sub     = ( $this->uri->segment(2, 0) ? $this->uri->segment(2, 0) : '');
?>