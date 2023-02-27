<?php 
    include APPPATH . 'views/shop/components/header_search.php'; 
    include APPPATH . 'views/shop/components/mobile/nav_filter.php';
    $auth       = auth_redirect( true );
?>

<div class="ps-page--shop" id="shop-sidebar" style="background: #fbfbfb;">
    <div class="container">
        <div class="ps-layout--shop">
            <div class="ps-shopping ps-tab-root">
                <div class="ps-shopping__header">
                    <p><strong> <span id="total_rows">0</span></strong> Products found</p>
                    <div class="ps-shopping__actions sort">
                        <!-- include sort by -->
                        <?php include APPPATH . 'views/shop/components/sort_by.php'; ?>
                        <div class="ps-shopping__view">
                            <ul class="ps-tab-list">
                                <li class="active"><a href="#tab-1"><i class="icon-grid"></i></a></li>
                                <li class=""><a href="#tab-2"><i class="icon-list4"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="ps-tabs">
                    <?php 
                        //if (empty($auth)) { 
                    ?>
                    <!--
                        <center>
                            <p>Please login your account</p>
                            <p class="return-to-shop">
                                <a class="ps-btn" href="<?= base_url('login') ?>">Login</a>
                            </p>
                        </center>
                    -->
                    <?php //} else { ?>
                        <div class="ps-tab active" id="tab-1">
                            <div class="ps-shopping-product">
                                <div class="row cols" id="load_data1"> </div>
                                <div class="px-2 py-4 load_data_message"></div>
                            </div>
                        </div>

                        <div class="ps-tab" id="tab-2">
                            <div class="ps-shopping-product">
                                <div id="load_data2"> </div>
                                <div class="px-2 py-4 load_data_message"></div>
                            </div>
                        </div>
                    <?php //} ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php //if (!empty($auth)) : ?>
<?php include APPPATH . 'views/shop/components/mobile/floating_cart.php'; ?>
<?php //endif; ?>