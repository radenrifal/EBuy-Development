<?php include APPPATH . 'views/shop/components/header.php'; ?>
<?php include APPPATH . 'views/shop/components/mobile/nav_search.php'; ?>

<?php
$pageID = 'product';
$imgpath = UPLOAD_IMG . $pageID . '/thumbnail/';

// Show single product
$condition = $this->db->where('product.status', 1)->where('product.name', 'single');
$product = $this->Model_Shop->get_products($condition)->row();

if ($product) {
?>
    <div class="ps-promotions py-5" style="background:url(<?= FE_IMG ?>bg_product.jpg) center no-repeat;background-size: cover;">
        <div class="container">
            <div class="ps-section__content" style="padding: 115px 0;">
                <div class="row">
                    <div class="col-md-6 col-sm-12 col-12 ">
                        <div class="text-black text-uppercase" style="font-size:15px;text-shadow: 2px 2px #ffffff;">
                            <button type="button" class="text-black btn btn-outline-dark px-5" style="font-size:15px;">OUR PRODUCT</button>

                            <div style="padding:50px 0;word-break: break-all;">
                                <?= $product->description  ?>
                            </div>

                            <?php if ($product->stock == 0) { ?>
                                <a class="btn-secondary px-5 mb-5" href="javascript:;">Stok Kosong</a>
                            <?php } else { ?>
                                <button type="button" class="btn addCart btn-danger px-5 mb-5" data-id="<?= encrypt_param($product->id) ?>" data-qty="1" data-type="buynow" style="font-size:15px;">BUY NOW</button>
                            <?php } ?>

                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 col-12 px-5 d-flex justify-content-center align-items-center">
                        <img src="<?= get_image_single($product->id, $pageID, $imgpath) ?>" alt="" width="400px">
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php } ?>