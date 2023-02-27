<div class="floating-cart w-100 <?= (!$this->cart->contents()) ? 'd-none' : '' ?>">
    <div class="d-flex flex-row justify-content-between align-items-center">
        <div class="d-flex flex-row justify-content-start align-items-center cart-details" style="color: #333333;">
            <div class="item-count">
                <span class="cart-item-count"><?= count($this->cart->contents()) ?></span> Produk
            </div>
            <div class="d-flex flex-column justify-content-between align-items-start">
                <div class="total label">Estimasi Harga</div>
                <div class="total"><span class="cart-total-amount"><?= ddm_accounting($this->cart->total()) ?></span></div>
            </div>
        </div>
        <a href="<?= base_url('cart') ?>">
            <button class="btn ps-btn place-order-btn">My Cart</button>
        </a>
    </div>
</div>