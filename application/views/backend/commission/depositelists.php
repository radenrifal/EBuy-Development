<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_financial') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo lang('menu_financial_deposite'); ?></li>
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
                            <h3 class="mb-0"><?php echo lang('menu_financial_deposite'); ?> </h3>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <?php 
                        if ( $is_admin ) {
                            if( !empty($member_other) ) {
                                $this->load->view(VIEW_BACK . 'commission/deposite/listmemberother');
                            } else {
                                $this->load->view(VIEW_BACK . 'commission/deposite/listadmin');
                            }
                        } else {
                            $this->load->view(VIEW_BACK . 'commission/deposite/listmember');
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
