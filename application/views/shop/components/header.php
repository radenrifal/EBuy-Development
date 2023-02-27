<?php include APPPATH . 'views/shop/components/menu.php'; ?>

<header class="header header--standard header--electronic" data-sticky="true">
    <div class="header__content">
        <nav class="navigation">
            <div class="container">
                <a class="ps-logo-h" href="<?= base_url('shop') ?>">
                    <img src="<?= LOGO_IMG ?>" alt="">
                </a>
                <ul class="menu menu--electronic text-right">
                    <?php $current_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
                    <?php foreach ($menu as $nav) { ?>
                        <li class="<?= ($current_link == $nav['link']) ? 'active' : '' ?>">
                            <a href="<?= $nav['link']; ?>"><?= $nav['name']; ?></a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </nav>
</header>