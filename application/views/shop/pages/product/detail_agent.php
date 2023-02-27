<?php include APPPATH . 'views/shop/components/header.php'; ?>
<?php include APPPATH . 'views/shop/components/mobile/nav_back.php'; ?>

<?php
$in_cart = FALSE;
foreach ($this->cart->contents() as $item) {
    if ($item['id'] == $product_detail->id) {
        $in_cart = TRUE;
    }
}
?>

<nav class="navigation--mobile-product">
    <?php if (!$in_cart) {  ?>
        <?php if ($product_detail->stock == 0) { ?>
            <a class="ps-btn" href="javascript:;" style="min-width:100%">Stok Kosong</a>
        <?php } else { ?>
            <a style="min-width:100%" class="ps-btn addCart" href="#" data-id="<?= encrypt_param($product_detail->id) ?>" data-qty="1">Add to Cart</a>
        <?php } ?>
    <?php } else { ?>
        <a style="min-width:100%" class="ps-btn" href="<?= base_url('cart') ?>">Go to cart</a>
    <?php } ?>
</nav>

<div class="ps-breadcrumb">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="<?= base_url() ?>">Home</a></li>
            <li><a href="<?= base_url('shop') ?>">Shop</a></li>
            <li><?= $breadcrumb ?></li>
        </ul>
    </div>
</div>
<div class="ps-page--product section-shadow">
    <div class="container">
        <div class="ps-page__container">
            <div class="ps-page__center">
                <div class="ps-product--detail ps-product--fullwidth">
                    <div class="ps-product__header">
                        <?php
                            if ( $product_detail->image ) {
                                $img_src    = PRODUCT_IMG_PATH . 'thumbnail/'. $product_detail->image;
                                if ( file_exists($img_src) ) {
                                    $img_src = PRODUCT_IMG . 'thumbnail/'. $product_detail->image;
                                } else {
                                    $img_src = ASSET_PATH . 'backend/img/no_image.jpg'; 
                                }
                            } else {
                                $img_src = ASSET_PATH . 'backend/img/no_image.jpg'; 
                            }
                        ?>
                        <div class="ps-product__thumbnail-" data-vertical="true">
                            <img class="img-fluid" src="<?= $img_src; ?>">
                        </div>

                        <div class="ps-product__info">
                            <h1 class="text-capitalize"><?= $product_detail->name ?></h1>
                            <h4 class="ps-product__price"><?= ddm_accounting($product_detail->price) ?></h4>
                            <div class="ps-product__desc">
                                <?= $product_detail->description ?>
                            </div>
                            <div class="ps-product__shopping detail">
                                <?php if (!$in_cart) {  ?>
                                    <a class="ps-btn addCart" href="#" data-id="<?= ddm_encrypt($product_detail->id) ?>" data-qty="1" style="border-radius: 25px;">Add to cart</a>
                                <?php } else { ?>
                                    <a class="ps-btn btn-gocart" href="<?= base_url('cart') ?>">Go to cart</a>
                                <?php } ?>
                            </div>

                            <div class="ps-product__sharing">
                                <p>Share produk ini :</p>
                                <a class="facebook" title="Share to Facebook" href="javascript:;" onclick="javascript:open('//facebook.com/sharer/sharer.php?u=<?= current_url() . ( is_logged_in() ? '?ref=' . user_info('username') : '') ?>', 'popup', 'height=400,width=800')"><i class="fa fa-facebook"></i></a>
                                <a class="twitter" title="Share to Twitter" href="javascript:;" onclick="javascript:open('//twitter.com/share?url=<?= current_url() . (is_logged_in() ? '?ref=' . user_info('username') : '') ?>', 'popup', 'height=400,width=800')"><i class="fa fa-twitter"></i></a>
                                <a class="whatsapp" title="Share to Whatsapp" href="//api.whatsapp.com/send/?phone&text=<?= current_url() . (is_logged_in() ? '?ref=' . user_info('username') : '') ?>"><i class="fa fa-whatsapp"></i></a>
                                <a class="twitter" title="Share to Telegram" href="//telegram.me/share/url?url=<?= current_url() . (is_logged_in() ? '?ref=' . user_info('username') : '') ?>&text=<?= sanitize(substr($product_detail->description, 0, 60)) ?>"><i class="fa fa-telegram"></i></a>
                                <a class="instagram copyLink" title="Share to Instagram" href="javascript:;" data-link="<?= current_url() . (is_logged_in() ? '?ref=' . user_info('username') : '') ?>"><i class="fa fa-instagram"></i></a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="ps-section--default">
            <div class="ps-section__header">
                <h3>Other products</h3>
            </div>
            <div class="ps-section__content">
                <div class="ps-carousel--nav owl-slider" data-owl-auto="true" data-owl-loop="true" data-owl-speed="10000" data-owl-gap="5" data-owl-nav="true" data-owl-dots="true" data-owl-item="6" data-owl-item-xs="2" data-owl-item-sm="2" data-owl-item-md="3" data-owl-item-lg="4" data-owl-item-xl="5" data-owl-duration="1000" data-owl-mousedrag="on">
                    <?php
                        $products   = '';
                        $condition  = ' AND %status% = 1';
                        if ( $get_products   = shop_product_package(5, 0, $condition, '%datemodified% DESC') ) {
                            $products = $get_products['data'];
                        }
                    ?>
                    <?php include APPPATH . 'views/shop/components/products_agent.php'; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include APPPATH . 'views/shop/components/mobile/floating_cart.php'; ?>