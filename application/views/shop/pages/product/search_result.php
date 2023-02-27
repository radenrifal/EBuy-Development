<?php include APPPATH . 'views/shop/components/header_search.php'; ?>

<?php include APPPATH . 'views/shop/components/mobile/nav_search.php'; ?>

<div class="ps-breadcrumb">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="<?= base_url() ?>">Home</a></li>
            <li>Search Result</li>
        </ul>
    </div>
</div>

<div class="ps-page--single no-bg">
    <img src="<?= FE_IMG ?>bg/vendor.jpg" alt="" width="100%">
    <div class="ps-about-intro">
        <div class="container">
            <h3 class="mb-5">Search Result : <?= (isset($_GET['product'])) ? sanitize($_GET['product']) : '' ?></h3>

            <div class="row cols">
                <?php include APPPATH . 'views/shop/components/products.php'; ?>
            </div>

        </div>
    </div>
</div>

<?php include APPPATH . 'views/shop/components/mobile/floating_cart.php'; ?>