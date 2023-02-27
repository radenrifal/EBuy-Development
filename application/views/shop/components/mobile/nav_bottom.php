<div class="navigation--list">
    <div class="navigation__content">
        <a class="navigation__item <?= ($this->uri->uri_string() == '' ? 'active' : '') ?>" href="<?= base_url() ?>"><i class="fa fa-home" style="font-size: 25px;margin-bottom: 5px;"></i>
            <span style="margin-top: -5px;"> Home</span>
        </a>
        <a class="navigation__item <?= ($this->uri->uri_string() == 'shop' ? 'active' : '') ?>" href="<?= base_url('shop') ?>">
            <i class="fa fa-shopping-cart" style="font-size: 25px;margin-bottom: 8px;"></i>
            <span style="margin-top: -10px;"> Shop</span>
        </a>
        <a class="navigation__item <?= ($this->uri->uri_string() == 'cart' ? 'active' : '') ?>" href="<?= base_url('cart') ?>"><i class="fa fa-shopping-bag"></i>
            <span> Cart</span> <span class="cart-item-count"><?= count($this->cart->contents()) ?></span>
        </a>
        <!-- <a class="navigation__item ps-toggle--sidebar" href="#navigation-mobile"><i class="fa fa-list-alt" style="margin-bottom: 4px;font-size: 21px;"></i>
            <span style="margin-top: -3px;"> Categories</span>
        </a> -->
        <a class="navigation__item ps-toggle--sidebar" href="#menu-mobile"><i class="fa fa-bars"></i>
            <span> Menu</span>
        </a>
    </div>
</div>

<div class="ps-panel--sidebar" id="navigation-mobile">
    <div class="ps-panel__header">
        <h3>Categories</h3>
        <span class="close">&times;</span>
    </div>
    <div class="ps-panel__content">
        <ul class="menu--mobile">
            <?php
                if ( $categories = ddm_product_category(0, true) ) {
                    foreach ($categories as $row) {
                        echo '<li class="text-capitalize"> <a href="'.base_url('search?category=' . $row->name).'">'. ucwords($row->name) .'</a></li>';
                    }
                } else {
                    echo '<li> No Category</li>';
                }
            ?>
        </ul>
    </div>
</div>

<div class="ps-panel--sidebar" id="menu-mobile">
    <div class="ps-panel__header">
        <h3>Menu</h3>
        <span class="close">&times;</span>
    </div>
    <div class="ps-panel__content">
        <ul class="menu--mobile">
            <?php foreach ($menu as $nav) { ?>
                <li class="current-menu-item">
                    <a href="<?= $nav['link'] ?>"><?= $nav['name'] ?></a>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>