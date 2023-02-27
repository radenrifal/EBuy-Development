<!-- BEGIN TREE GENERATION -->
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_member') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo lang('menu_member_generation_tree'); ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0"><?php echo lang('menu_member_generation_tree') ?> </h3>
                        </div>
                    </div>
                </div>
                <div class="card-body alert-wrapper-gen">
    				<?php if ( $is_admin ): ?>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" name="search_username_gen" id="search_username_gen" placeholder="<?php echo lang('search_member_username') ?>...">
                        <div class="input-group-append">
                            <button class="btn btn-info" id="btn_search_username_gen"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                	<?php endif ?>
                    
    				<div class="generations" data-url="<?php echo base_url('member/generation_loadmore'); ?>" data-levels="<?php echo $levels; ?>"></div><br />
    				<?php if ( $is_admin ): ?>
                    <hr style="margin-top: 0;" />
    				<a href="javascript:;" class="btn btn-info loadmore"><i class="fa fa-refresh"></i> <?php echo lang('show_more') ?>...</a>
    				<?php endif ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END TREE GENERATION -->