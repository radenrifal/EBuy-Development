<header class="header header--mobile header--mobile-product" data-sticky="true">
    <div class="navigation--mobile">
        <div class="navigation__left">
            <div class="header__back" onclick="history.back()">
                <i class="icon-chevron-left"></i> <span style="font-size:15px;font-weight:400" class="text">Back</span>
            </div>
        </div>
        <div class="navigation__right">
            <div class="header__actions">
                <a class="header__extra" href="<?= base_url('cart') ?>">
                    <i class="icon-bag2"></i><span class="cart-item-count"><?= count($this->cart->contents()) ?></span>
                </a>
            </div>
        </div>
    </div>
</header>