<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard') ?>"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#"><?php echo lang('menu_setting') ?></a></li>
                            <li class="breadcrumb-item"><a href="<?php echo base_url('setting/reward') ?>"><?php echo lang('menu_setting_reward') ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <?php 
                                    $box_title = lang(strtolower($form)) . ' ' . lang('menu_setting_reward');
                                    echo $box_title;
                                ?>
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a href="<?php echo base_url('setting/reward'); ?>" class="btn btn-sm btn-neutral">
                        <i class="fa fa-step-backward mr-1"></i> <?php echo lang('back'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--6">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0"><?php echo $box_title; ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body wrapper-form-setting-reward pt-0">
                    <?php 
                        $action = base_url('setting/savereward');
                        if ( $dataform ) {
                            if ( isset($dataform->id) && $dataform->id ) {
                                $action .= '/'. ddm_encrypt($dataform->id);
                            }
                        }
                    ?>
                    <form action="<?php echo $action; ?>" method="post" class="form-horizontal" id="form-setting-reward" >
                        <div class="form-group row mb-2">
                            <label class="col-md-3 col-form-label form-control-label">Reward <span class="required">*</span></label>
                            <div class="col-md-9">
                                <input type="hidden" name="id" id="id" class="form-control" value="<?php echo ($dataform ? $dataform->id : '' ) ?>"/>
                                <input type="text" name="reward" id="reward" class="form-control" placeholder="Nama Reward"
                                value="<?php echo ($dataform ? $dataform->reward : '' ) ?>" <?php // echo ($dataform ? 'readonly=""' : '' ) ?> autocomplete="on" />
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-md-3 col-form-label form-control-label">Nominal Reward <span class="required">*</span></label>
                            <div class="col-md-9">
                                <input type="text" name="nominal" id="nominal" class="form-control numbercurrency" placeholder="Nominal Reward" value="<?php echo ($dataform ? $dataform->nominal : '' ) ?>" />
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-md-3 col-form-label form-control-label">Poin <span class="required">*</span></label>
                            <div class="col-md-9">
                                <input type="text" name="point" id="point" class="form-control numbercurrency" placeholder="Poin Reward" value="<?php echo ($dataform ? $dataform->point : '' ) ?>"/>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-md-3 col-form-label form-control-label">Deskripsi </label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="message" name="message" rows="2" style="resize: vertical;"><?php echo ($dataform ? $dataform->message : '' ) ?></textarea>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-md-3 col-form-label form-control-label">Status <span class="required">*</span></label>
                            <div class="col-md-9">
                                <?php 
                                    $selected_ya = $selected_no = '';
                                    if ( $dataform ) {
                                        if ( $dataform->is_active > 0 ) {
                                            $selected_ya = 'selected=""'; 
                                            $selected_no = '';
                                        } else {
                                            $selected_no = 'selected=""'; 
                                            $selected_ya = '';
                                        }
                                    }
                                ?>
                                <select name="is_active" id="is_active" class="form-control" >
                                    <option value="1" <?php echo $selected_ya; ?> >Aktif</option>
                                    <option value="0" <?php echo $selected_no; ?> >Tidak</option>
                                </select>
                            </div>
                        </div>
                        <hr class="my-3">
                        <div class="form-group row mb-2">
                            <label class="col-md-3 col-form-label form-control-label"></label>
                            <div class="col-md-9">
                                <?php 
                                    $lifetime_checked = 'checked="checked"'; 
                                    $hide_period      = 'style="display: none;"'; 
                                    if ( $dataform ) {
                                        $lifetime_checked = ($dataform->is_lifetime) ? 'checked="checked"' : ''; 
                                        if ( ! $dataform->is_lifetime ) {
                                            $hide_period  = ''; 
                                        }
                                    }
                                ?>

                                <div class="custom-control custom-checkbox mb-3">
                                    <input type="checkbox" class="custom-control-input is_lifetime" name="is_lifetime" id="is_lifetime" value="1" <?php echo $lifetime_checked ?> />
                                    <label class="custom-control-label is_lifetime" for="is_lifetime">LifeTime Reward</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-3" id="period_reward" <?php echo $hide_period; ?>>
                            <label class="col-md-3 col-form-label form-control-label"><?php echo lang('period'); ?> Reward <span class="required">*</span></label>
                            <div class="col-md-7">
                                <?php 
                                    $start_date     = date('Y-m-d');
                                    $end_date       = date('Y-m-t');
                                    if ( $dataform ) {
                                        $start_date = ($dataform->start_date != '0000-00-00') ? $dataform->start_date : $start_date;
                                        $end_date   = ($dataform->end_date != '0000-00-00') ? $dataform->end_date : $end_date;
                                    }
                                ?>
                                <div class="row input-daterange datepicker align-items-center">
                                    <div class="col">
                                        <div class="input-group">
                                            <input type="text" class="form-control text-center" readonly name="period_start" data-date-format="yyyy-mm-dd" placeholder="From" value="<?php echo $start_date; ?>" />
                                            <span class="input-group-btn">
                                                <button class="btn btn-neutral" type="button"><i class="ni ni-calendar-grid-58 text-primary"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-1 text-center">
                                        <label>s/d</label>
                                    </div>
                                    <div class="col">
                                        <div class="input-group">
                                            <input type="text" class="form-control text-center" readonly name="period_end" data-date-format="yyyy-mm-dd" placeholder="To" value="<?php echo $end_date; ?>" />
                                            <span class="input-group-btn">
                                                <button class="btn btn-neutral" type="button"><i class="ni ni-calendar-grid-58 text-primary"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="my-3">
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary bg-gradient-primary"><?php echo lang('save') .' '. lang('menu_setting'); ?></button> 
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>