<form class="ps-form--quick-search" action="<?= base_url('search') ?>" method="get">
    <?php if ( ! $auth = auth_redirect( true ) ) { ?>
        <div class="form-group--icon"><i class="icon-chevron-down"></i>
            <select class="form-control" name="category">
                <option value="all" selected>All</option>
                    <?php
                        if ( $categories = ddm_product_category(0, true) ) {
                            foreach ($categories as $row) {
                                if ( isset($_GET['category']) && $_GET['category'] == $row->name ) {
                                    $selected = 'selected';
                                } else {
                                    $selected = '';
                                }
                                echo '<option value="'.$row->name.'" '.$selected.'>'. ucwords($row->name) .'</option>';
                            }
                        }
                    ?>
            </select>
        </div>
    <?php } ?>
    <input name="product" class="form-control" type="text" placeholder="Search Product.." value="<?= (isset($_GET['product'])) ? sanitize($_GET['product']) : '' ?>">
    <button>Search</button>
</form>