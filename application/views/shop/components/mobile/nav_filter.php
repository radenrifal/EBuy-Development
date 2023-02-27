<?php include APPPATH . 'views/shop/components/menu.php'; ?>

<header class="header header--mobile header--mobile-categories" data-sticky="true">
    <nav class="navigation--mobile">
        <div class="navigation__left">
            <div class="header__back" onclick="history.back()">
                <i class="icon-chevron-left"></i>
            </div>
            <div class="ps-search--mobile">
                <?php include APPPATH . 'views/shop/components/form_search.php'; ?>
            </div>
        </div>
    </nav>
    <div class="header__filter">
        <?php if ( ! $auth = auth_redirect( true ) ) { ?>
            <button class="ps-shop__filter-mb" id="filter-sidebar"><i class="icon-equalizer"></i><span>Filter</span></button>
        <?php } ?>
        <div class="header__sort sort"><i class="icon-sort-amount-desc"></i>
            <!-- include sort by -->
            <?php include APPPATH . 'views/shop/components/sort_by.php'; ?>
        </div>
    </div>
</header>

<div class="ps-filter--sidebar">
    <div class="ps-filter__header">
        <h3>Filter Products</h3><a class="ps-btn--close ps-btn--no-boder" href="#"></a>
    </div>
    <div class="ps-filter__content">
        <aside class="widget widget_shop">
            <h4 class="widget-title">Categories</h4>
            <ul class="ps-list--categories">

                <li class="text-capitalize"> <a href="<?= base_url('search?category=all' . (isset($_GET['product']) ? '&product=' . sanitize($_GET['product']) : '')) ?>"> All</a> </li>
                <?php
                    if ( $categories = ddm_product_category(0, true) ) {
                        foreach ($categories as $row) {
                            echo '<li class="text-capitalize"> <a href="'.base_url('search?category=' . $row->name . (isset($_GET['product']) ? '&product=' . sanitize($_GET['product']) : '')).'">'. ucwords($row->name) .'</a></li>';
                        }
                    } else {
                        echo '<li> No Category</li>';
                    }
                ?>
            </ul>
        </aside>
    </div>
</div>

<?php include APPPATH . 'views/shop/components/mobile/nav_bottom.php'; ?>