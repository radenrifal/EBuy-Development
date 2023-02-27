<?php
if (!isset($products) && !$products) {
    echo "<div class='col-md-12'>No Product</div>";
} else {
    foreach ($products as $row) {

        // Product already in cart
        $in_cart = FALSE;
        foreach ($this->cart->contents() as $item) {
            if ($item['id'] == $row->id) {
                $in_cart = TRUE;
            }
        }

        if ( $row->image ) {
            $img_src    = PRODUCT_IMG_PATH . 'thumbnail/'. $row->image;
            if ( file_exists($img_src) ) {
                $img_src = PRODUCT_IMG . 'thumbnail/'. $row->image;
            } else {
                $img_src = ASSET_PATH . 'backend/img/no_image.jpg'; 
            }
        } else {
            $img_src = ASSET_PATH . 'backend/img/no_image.jpg'; 
        }

        $price = ddm_accounting($row->price);
        if ( $row->is_mix && ! $row->lock_qty ) {
            $price = 'Produk Mix';
        }

?>

        <div class="col-padding col-md-3 col-6">
            <div class="ps-product">
                <div class="ps-product__thumbnail">
                    <a href="<?= base_url('packageproduct/detail/' . $row->slug) ?>">
                        <img class="img-fluid" src="<?= $img_src; ?>">
                    </a>
                </div>
                <div class="ps-product__container desktop">
                    <div class="ps-product__content">
                        <a class="ps-product__title" href="<?= base_url('packageproduct/detail/' . $row->slug) ?>">
                            <span class="text-capitalize"><?= $row->name ?></span>
                        </a>
                        <p class="ps-product__price sale"><?= $price ?></p>
                        <div class="text-center py-3">
                            <?php if (!$in_cart) {  ?>
                                <div class="ps-btn addCart" data-id="<?= ddm_encrypt($row->id) ?>" data-qty="1" data-type="addcart" style="padding: 7px;width: 100%;border-radius: 25px;">Add Cart</div>
                            <?php } else { ?>
                                <a class="ps-btn btn-gocart" href="<?= base_url('cart') ?>" >Go to cart</a>
                            <?php } ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php } ?>
<?php } ?>