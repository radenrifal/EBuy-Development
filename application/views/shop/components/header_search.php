<?php include APPPATH . 'views/shop/components/menu.php'; ?>

<header class="header header--standard search header--electronic" data-sticky="true">
    <div class="header__content">
        <div class="container">
            <div class="header__content-left">
                <a class="ps-logo-h" href="<?= base_url() ?>">
                    <img src="<?= LOGO_IMG ?>" alt="">
                </a>
                <div class="menu--product-categories">
                    <div class="menu__toggle"><i class="icon-menu"></i></div>
                    <div class="menu__content">
                        <ul class="menu--dropdown">
                            <?php $current_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
                            <?php foreach ($menu as $nav) { ?>
                                <li class="text-capitalize"> <a href="<?= $nav['link']; ?>"> <?= $nav['name'] ?></a> </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="header__content-center">
                <?php include APPPATH . 'views/shop/components/form_search.php'; ?>
            </div>

            <div class="header__content-right">
                <div class="header__actions">
                    <a class="header__extra d-flex align-items-center" href="<?= base_url('check-order') ?>">
                        <i class="icon-magnifier"></i>Search Invoice
                    </a>
                    <a class="header__extra d-flex align-items-center" href="<?= (is_logged_in()) ? base_url('dashboard') : base_url('login') ?>">
                        <i class="icon-user"></i><?= (is_logged_in()) ? user_info() : 'Login' ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>