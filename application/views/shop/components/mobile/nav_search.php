<?php include APPPATH . 'views/shop/components/menu.php'; ?>


<!-- Mobile Section -->
<header class="header header--mobile electronic" data-sticky="true">
    <div class="navigation--mobile">
        <div class="navigation__left">
            <a class="ps-logo-h" href="<?= base_url() ?>">
                <img src="<?= LOGO_IMG ?>" alt="">
            </a>
        </div>
        <div class="navigation__right">
            <div class="header__actions">
                <div class="ps-block--user-header">
                    <div class="ps-block__left">
                        <a href="<?= (is_logged_in()) ? base_url('dashboard') : base_url('login') ?>" class="d-flex align-items-center">
                            <i class="fa fa-user-circle-o"></i>
                            <span class="ml-3 text-username"><?= (is_logged_in()) ? user_info() : 'Login' ?></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<?php include APPPATH . 'views/shop/components/mobile/nav_bottom.php'; ?>
<!-- End Mobile -->