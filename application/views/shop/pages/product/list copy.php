<?php include APPPATH . 'views/shop/components/header_search.php'; ?>

<?php include APPPATH . 'views/shop/components/mobile/nav_search.php'; ?>

<div class="ps-breadcrumb">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="<?= base_url() ?>">Home</a></li>
            <li><?= $page ?></li>
        </ul>
    </div>
</div>

<div class="ps-page--single no-bg">
    <div class="ps-about-intro" style="padding-top: 25px;">
        <div class="container">
            <h3 class="mb-5">Product List</h3>

            <div class="row cols">
                <?php // Show all products
                $condition = $this->db->where('product.status', 1)->where('product.stock >', 0)->order_by('id', 'DESC');
                $products = $this->Model_Shop->get_products($condition)->result();
                ?>
                <?php include APPPATH . 'views/shop/components/products.php'; ?>
            </div>

        </div>
    </div>
</div>

<?php include APPPATH . 'views/shop/components/mobile/floating_cart.php'; ?>