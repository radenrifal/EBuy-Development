<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_financial') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo lang('menu_financial_bonus'); ?></li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <?php if ( $is_admin ) { ?>
                        <a href="<?php echo base_url('cron/bonus/DDMcron'); ?>" class="btn btn-sm btn-neutral" target="_blank">Eksekusi Omzet ke Bonus Bulan ini </a>
                    <?php } ?>
                    <a href="javascript;:" class="btn btn-sm btn-neutral"><?php echo lang('bonus_total') .' : '. ddm_accounting($bonus_total); ?> </a>
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
                            <h3 class="mb-0"><?php echo lang('menu_financial_bonus'); ?> </h3>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <?php 
                        if ( $is_admin ) {
                            if( !empty($member_other) ) {
                                $this->load->view(VIEW_BACK . 'commission/bonus/listmemberother');
                            } else {
                                $this->load->view(VIEW_BACK . 'commission/bonus/listadmin');
                            }
                        } else {
                            $this->load->view(VIEW_BACK . 'commission/bonus/listmember');
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
