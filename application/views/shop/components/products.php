<?php
if (!isset($products) && !$products) {
    echo "<div class='col-md-12'>No Product</div>";
} else {
    $member         = ddm_get_current_member();
    $provincedata   = ddm_provinces($member->province);
    $provincearea   = $provincedata->province_area;
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

        $price = ( is_logged_in() ) ? $row->{'price_agent'.$provincearea} : $row->{'price_customer'.$provincearea};

?>

        <div class="col-padding col-md-3 col-6">
            <div class="ps-product">
                <div class="ps-product__thumbnail">
                    <a href="<?= base_url('product/detail/' . $row->slug) ?>">
                        <img class="img-fluid" src="<?= $img_src; ?>">
                    </a>
                </div>
                <a style="margin-bottom:0" class="ps-product__vendor text-capitalize" href="<?= base_url('search?category=' . shop_category($row->id_category, 'name')) ?>"> <?= shop_category($row->id_category, 'name') ?></a>
                <div class="ps-product__container desktop">
                    <div class="ps-product__content">
                        <a class="ps-product__title" href="<?= base_url('product/detail/' . $row->slug) ?>">
                            <span class="text-capitalize"><?= $row->name ?></span>
                        </a>
                        <div class="ps-product__rating">
                            <select class="ps-rating" data-read-only="true">
                                <option value="1">1</option>
                                <option value="1">2</option>
                                <option value="1">3</option>
                                <option value="1">4</option>
                                <option value="1">5</option>
                            </select>
                        </div>
                        <p class="ps-product__price sale"><?= ddm_accounting($price) ?></p>
                        <div class="text-center py-3">

                            <?php if (!$in_cart) {  ?>
                                <?php if ($row->stock == 0) { ?>
                                    <a class="ps-btn" href="javascript:;">Stok Kosong</a>
                                <?php } else { ?>
                                    <div class="ps-btn addCart" data-id="<?= ddm_encrypt($row->id) ?>" data-qty="1" data-type="addcart" style="padding: 7px;width: 100%;border-radius: 25px;">Add Cart</div>
                                <?php } ?>
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