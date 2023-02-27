<select class="ps-select select2" data-placeholder="Sort By" data-select2-id="1" tabindex="-1" aria-hidden="true">
    <option value="sortby=datecreated&orderby=desc" data-select2-id="1">Sort by latest</option>
    <!-- <option value="sortby=discount&orderby=desc" <?= isset($_GET['sortby']) && isset($_GET['sortby']) == 'discount' ? 'selected' : '' ?>>Sort by discount</option> -->
    <option value="sortby=price&orderby=asc" <?= isset($_GET['sortby']) && isset($_GET['orderby']) && $_GET['sortby'] == 'price' && $_GET['orderby'] == 'asc' ? 'selected' : '' ?>>Sort by price: low to high</option>
    <option value="sortby=price&orderby=desc" <?= isset($_GET['sortby']) && isset($_GET['orderby']) && $_GET['sortby'] == 'price' && $_GET['orderby'] == 'desc' ? 'selected' : '' ?>>Sort by price: high to low</option>
</select>