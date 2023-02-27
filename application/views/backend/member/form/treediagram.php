<!-- Begin Table Responsive Tree Diagram -->
<div class="table-responsive">
    <div id="message_error" class="alert alert-danger display-hide">
		<button class="close" data-close="alert" type="button"><i class="fa fa-times"></i></button>
        Silahkan cari Username anggota menggunakan form di atas!
	</div>

    <ul class="tree-table">
        <li>
            <!-------------------------------------------------------------------------------------------------------->
            <!-- =================== Parent Section =============================================================== -->
            <!-------------------------------------------------------------------------------------------------------->
            <?php if( !empty($member_other) ): ?>

                <?php if( $member_other->type == ADMINISTRATOR ): ?>

                    <!-- If View Tree of Member Login -->
                    <?php echo tfy_avatar($member->id, 'photo-me', 0, true); ?>

                <?php elseif($member_other->id == $member->id): ?>

                    <!-- If View Tree of Member Login -->
                    <?php echo tfy_avatar($member->id, '', 0, true); ?>

                <?php else: ?>

                    <?php if( $is_down ): ?>

                        <!-- If View Tree of Member Login Downline -->
                        <div>
                            <?php $member_id = tfy_encrypt($member->id); ?>
                            <a href="<?php echo base_url('member/tree/' . $member_id); ?>">
                                <?php echo tfy_avatar($member->id, '', 0, true); ?>
                            </a><hr style="margin-bottom: 20px; border: none; border-bottom: 2px dotted #CCC;" />
                        </div>

                        <?php if( $is_down ): ?>
                            <?php $member_other_parent = tfy_encrypt($member_other->parent); ?>
                            <p><a href="<?php echo base_url('member/tree/' . $member_other_parent); ?>" class="btn btn-sm btn-flat btn-primary"><i class="fa fa-arrow-circle-up"></i> Upline</a></p>
                        <?php endif ?>

                        <?php echo tfy_avatar($member_other->id, 'photo-me', 0, true); ?>

                    <?php else: ?>

                        <!-- If View Tree of Member Login -->
                        <?php echo tfy_avatar($member->id, 'photo-me', 0, true); ?>

                    <?php endif ?>

                <?php endif ?>

            <?php else: ?>

                <!-- If View Tree of Member Login -->
                <?php echo tfy_avatar($member->id, 'photo-me', 0, true); ?>

            <?php endif ?>
            <!-------------------------------------------------------------------------------------------------------->

            <ul class="child-1">
                <!---------------------------------------------------------------------------------------------------->
                <!-- =================== Child Level 1 ============================================================ -->
                <!---------------------------------------------------------------------------------------------------->
                <?php
                    $id_member_p    = ( !empty($member_other) && $is_down ? $member_other->id : $member->id );
                    $downleft       = tfy_downline($id_member_p, POS_LEFT);
                    $downright      = tfy_downline($id_member_p, POS_RIGHT);
                ?>

                <li>
                    <!------------------------------------>
                    <!-- Left Position ------------------->
                    <!------------------------------------>
                    <?php if( !empty($downleft) ): ?>
                        <?php // $downleftSpon = bo_get_memberdata_by_id($downleft->sponsor); ?>
                        <?php $downleft_id = tfy_encrypt($downleft->id); ?>
                        <a href="<?php echo base_url('member/tree/' . $downleft_id); ?>">
                            <?php echo tfy_avatar($downleft->id, '', $downleft->sponsor); ?>
                        </a>
                    <?php else: ?>
                        <!-- Available To Add New Member -->
                        <a href="#" class="add-user" data-id="<?php echo $id_member_p; ?>" data-position="<?php echo POS_LEFT?>">
                            <div class="photo-wrapper">
                                <div class="photo-content">
                                    <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-add.jpg'; ?>" /></div>
                                </div>
                                <div class="photo-name-available">Available</div>
                                <div class="photo-name-available2"><span>New Member</span></div>
                                <?php echo tfy_node(1,true); ?>
                            </div>
                        </a>
                    <?php endif?>
                    <!------------------------------------>

                    <ul class="child-2">
                        <!---------------------------------------------------------------------------------------------->
                        <!-- =================== Child Level 2 - Left Position ====================================== -->
                        <!---------------------------------------------------------------------------------------------->
                        <?php
                            $id_member_c1   = ( !empty($downleft) ? $downleft->id : '' );
                            $downleftL      = tfy_downline($id_member_c1, POS_LEFT);
                            $downrightL     = tfy_downline($id_member_c1, POS_RIGHT);
                        ?>

                        <li>
                            <!------------------------------------>
                            <!-- Left Position ------------------->
                            <!------------------------------------>
                            <?php if( !empty($downleftL) ): ?>
                                <?php // $downleftLSpon = bo_get_memberdata_by_id($downleftL->sponsor); ?>
                                <?php $downleftL_id = tfy_encrypt($downleftL->id); ?>
                                <a href="<?php echo base_url('member/tree/' . $downleftL_id); ?>">
                                    <?php echo tfy_avatar($downleftL->id, '', $downleftL->sponsor); ?>
                                </a>
                            <?php else: ?>
                                <?php if( !empty($downleft) ): ?>
                                    <!-- Available To Add New Member -->
                                    <a href="#" class="add-user" data-id="<?php echo $id_member_c1; ?>" data-position="<?php echo POS_LEFT?>">
                                        <div class="photo-wrapper">
                                            <div class="photo-content">
                                                <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-add.jpg'; ?>" /></div>
                                            </div>
                                            <div class="photo-name-available">Available</div>
                                            <div class="photo-name-available2"><span>New Member</span></div>
                                            <?php echo tfy_node(1,true); ?>
                                        </div>
                                    </a>
                                <?php else: ?>
                                    <!-- Not Available To Add New Member -->
                                    <div class="photo-wrapper">
                                        <div class="photo-content">
                                            <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-noadd.jpg'; ?>" /></div>
                                        </div>
                                        <div class="photo-name-notavailable">Not Available</div>
                                        <div class="photo-name-notavailable2"><span>Empty</span></div>
                                        <?php echo tfy_node(1,true); ?>
                                    </div>
                                <?php endif ?>
                            <?php endif?>
                            <!------------------------------------>

                            <ul class="child-3 hidden-xs">
                                <!-------------------------------------------------------------------------------------->
                                <!-- =================== Child Level 3 - Left Position ============================== -->
                                <!-------------------------------------------------------------------------------------->
                                <?php
                                    $id_member      = ( !empty($downleftL) ? $downleftL->id : '' );
                                    $downleftLL     = tfy_downline($id_member, POS_LEFT);
                                    $downleftLL_chL = ( !empty($downleftLL) ? tfy_downline( tfy_isset($downleftLL->id, 0), POS_LEFT) : '' );
                                    $downleftLR     = tfy_downline($id_member, POS_RIGHT);
                                ?>

                                <li>
                                    <!------------------------------------>
                                    <!-- Left Position ------------------->
                                    <!------------------------------------>
                                    <?php if( !empty($downleftLL) ): ?>
                                        <?php // $downleftLLSpon = bo_get_memberdata_by_id($downleftLL->sponsor); ?>
                                        <?php $downleftLL_id = tfy_encrypt($downleftLL->id); ?>
                                        <a href="<?php echo base_url('member/tree/' . $downleftLL_id); ?>">
                                            <?php echo tfy_avatar($downleftLL->id, '', $downleftLL->sponsor, FALSE); ?>
                                        </a>
                                    <?php else: ?>
                                        <?php if( !empty($downleftL) ): ?>
                                            <!-- Available To Add New Member -->
                                            <a href="#" class="add-user" data-id="<?php echo $id_member; ?>" data-position="<?php echo POS_LEFT?>">
                                                <div class="photo-wrapper">
                                                    <div class="photo-content">
                                                        <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-add.jpg'; ?>" /></div>
                                                    </div>
                                                    <div class="photo-name-available">Available</div>
                                                    <div class="photo-name-available2"><span>New Member</span></div>
                                                    <?php echo tfy_node(1,true); ?>
                                                </div>
                                            </a>
                                        <?php else: ?>
                                            <!-- Not Available To Add New Member -->
                                            <div class="photo-wrapper">
                                                <div class="photo-content">
                                                    <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-noadd.jpg'; ?>" /></div>
                                                </div>
                                                <div class="photo-name-notavailable">Not Available</div>
                                                <div class="photo-name-notavailable2"><span>Empty</span></div>
                                                <?php echo tfy_node(1,true); ?>
                                            </div>
                                        <?php endif ?>
                                    <?php endif?>
                                    <!------------------------------------>
                                </li>

                                <li>
                                    <!------------------------------------>
                                    <!-- Right Position ------------------>
                                    <!------------------------------------>
                                    <?php if( !empty($downleftLR) ): ?>
                                        <?php // $downleftLRSpon = bo_get_memberdata_by_id($downleftLR->sponsor); ?>
                                        <?php $downleftLR_id = tfy_encrypt($downleftLR->id); ?>
                                        <a href="<?php echo base_url('member/tree/' . $downleftLR_id); ?>">
                                            <?php echo tfy_avatar($downleftLR->id, '', $downleftLR->sponsor, FALSE); ?>
                                        </a>
                                    <?php else: ?>
                                        <?php if( !empty($downleftL) ): ?>
                                            <!-- Available To Add New Member -->
                                            <a href="#" class="add-user" data-id="<?php echo $id_member; ?>" data-position="<?php echo POS_RIGHT?>">
                                                <div class="photo-wrapper">
                                                    <div class="photo-content">
                                                        <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-add.jpg'; ?>" /></div>
                                                    </div>
                                                    <div class="photo-name-available">Available</div>
                                                    <div class="photo-name-available2"><span>New Member</span></div>
                                                    <?php echo tfy_node(1,true); ?>
                                                </div>
                                            </a>
                                        <?php else: ?>
                                            <!-- Not Available To Add New Member -->
                                            <div class="photo-wrapper">
                                                <div class="photo-content">
                                                    <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-noadd.jpg'; ?>" /></div>
                                                </div>
                                                <div class="photo-name-notavailable">Not Available</div>
                                                <div class="photo-name-notavailable2"><span>Empty</span></div>
                                                <?php echo tfy_node(1,true); ?>
                                            </div>
                                        <?php endif ?>
                                    <?php endif?>
                                    <!------------------------------------>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <!------------------------------------>
                            <!-- Right Position ------------------>
                            <!------------------------------------>
                            <?php if( !empty($downrightL) ): ?>
                                <?php // $downrightLSpon = bo_get_memberdata_by_id($downrightL->sponsor); ?>
                                <?php $downrightL_id = tfy_encrypt($downrightL->id); ?>
                                <a href="<?php echo base_url('member/tree/' . $downrightL_id); ?>">
                                    <?php echo tfy_avatar($downrightL->id, '', $downrightL->sponsor); ?>
                                </a>
                            <?php else: ?>
                                <?php if( !empty($downleft) ): ?>
                                    <!-- Available To Add New Member -->
                                    <a href="#" class="add-user" data-id="<?php echo $id_member_c1; ?>" data-position="<?php echo POS_RIGHT?>">
                                        <div class="photo-wrapper">
                                            <div class="photo-content">
                                                <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-add.jpg'; ?>" /></div>
                                            </div>
                                            <div class="photo-name-available">Available</div>
                                            <div class="photo-name-available2"><span>New Member</span></div>
                                            <?php echo tfy_node(1,true); ?>
                                        </div>
                                    </a>
                                <?php else: ?>
                                    <!-- Not Available To Add New Member -->
                                    <div class="photo-wrapper">
                                        <div class="photo-content">
                                            <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-noadd.jpg'; ?>" /></div>
                                        </div>
                                        <div class="photo-name-notavailable">Not Available</div>
                                        <div class="photo-name-notavailable2"><span>Empty</span></div>
                                        <?php echo tfy_node(1,true); ?>
                                    </div>
                                <?php endif ?>
                            <?php endif?>
                            <!------------------------------------>

                            <ul class="child-3 hidden-xs">
                                <!-------------------------------------------------------------------------------------->
                                <!-- =================== Child Level 3 - Right Position ============================= -->
                                <!-------------------------------------------------------------------------------------->
                                <?php
                                    $id_member          = ( !empty($downrightL) ? $downrightL->id : '' );
                                    $downrightLL        = tfy_downline($id_member, POS_LEFT);
                                    $downrightLR        = tfy_downline($id_member, POS_RIGHT);
                                ?>

                                <li>
                                    <!------------------------------------>
                                    <!-- Left Position ------------------->
                                    <!------------------------------------>
                                    <?php if( !empty($downrightLL) ): ?>
                                        <?php // $downrightLLSpon = bo_get_memberdata_by_id($downrightLL->sponsor); ?>
                                        <?php $downrightLL_id = tfy_encrypt($downrightLL->id); ?>
                                        <a href="<?php echo base_url('member/tree/' . $downrightLL_id); ?>">
                                            <?php echo tfy_avatar($downrightLL->id, '', $downrightLL->sponsor, FALSE); ?>
                                        </a>
                                    <?php else: ?>
                                        <?php if( !empty($downrightL) ): ?>
                                            <!-- Available To Add New Member -->
                                            <a href="#" class="add-user" data-id="<?php echo $id_member; ?>" data-position="<?php echo POS_LEFT?>">
                                                <div class="photo-wrapper">
                                                    <div class="photo-content">
                                                        <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-add.jpg'; ?>" /></div>
                                                    </div>
                                                    <div class="photo-name-available">Available</div>
                                                    <div class="photo-name-available2"><span>New Member</span></div>
                                                    <?php echo tfy_node(1,true); ?>
                                                </div>
                                            </a>
                                        <?php else: ?>
                                            <!-- Not Available To Add New Member -->
                                            <div class="photo-wrapper">
                                                <div class="photo-content">
                                                    <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-noadd.jpg'; ?>" /></div>
                                                </div>
                                                <div class="photo-name-notavailable">Not Available</div>
                                                <div class="photo-name-notavailable2"><span>Empty</span></div>
                                                <?php echo tfy_node(1,true); ?>
                                            </div>
                                        <?php endif ?>
                                    <?php endif?>
                                    <!------------------------------------>
                                </li>

                                <li>
                                    <!------------------------------------>
                                    <!-- Right Position ------------------>
                                    <!------------------------------------>
                                    <?php if( !empty($downrightLR) ): ?>
                                        <?php // $downrightLRSpon = bo_get_memberdata_by_id($downrightLR->sponsor); ?>
                                        <?php $downrightLR_id = tfy_encrypt($downrightLR->id); ?>
                                        <a href="<?php echo base_url('member/tree/' . $downrightLR_id); ?>">
                                            <?php echo tfy_avatar($downrightLR->id, '', $downrightLR->sponsor, FALSE); ?>
                                        </a>
                                    <?php else: ?>
                                        <?php if( !empty($downrightL) ): ?>
                                            <!-- Available To Add New Member -->
                                            <a href="#" class="add-user" data-id="<?php echo $id_member; ?>" data-position="<?php echo POS_RIGHT?>">
                                                <div class="photo-wrapper">
                                                    <div class="photo-content">
                                                        <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-add.jpg'; ?>" /></div>
                                                    </div>
                                                    <div class="photo-name-available">Available</div>
                                                    <div class="photo-name-available2"><span>New Member</span></div>
                                                    <?php echo tfy_node(1,true); ?>
                                                </div>
                                            </a>
                                        <?php else: ?>
                                            <!-- Not Available To Add New Member -->
                                            <div class="photo-wrapper">
                                                <div class="photo-content">
                                                    <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-noadd.jpg'; ?>" /></div>
                                                </div>
                                                <div class="photo-name-notavailable">Not Available</div>
                                                <div class="photo-name-notavailable2"><span>Empty</span></div>
                                                <?php echo tfy_node(1,true); ?>
                                            </div>
                                        <?php endif ?>
                                    <?php endif?>
                                    <!------------------------------------>
                                </li>
                            </ul>
                        </li>
                        <!-------------------------------------------------------------------------------------------------------->
                    </ul>
                </li>

                <li>
                    <!------------------------------------>
                    <!-- Right Position ------------------>
                    <!------------------------------------>
                    <?php if( !empty($downright) ): ?>
                        <?php // $downrightSpon = bo_get_memberdata_by_id($downright->sponsor); ?>
                        <?php $downright_id = tfy_encrypt($downright->id); ?>
                        <a href="<?php echo base_url('member/tree/' . $downright_id); ?>">
                            <?php echo tfy_avatar($downright->id, '', $downright->sponsor); ?>
                        </a>
                    <?php else: ?>
                        <!-- Available To Add New Member -->
                        <a href="#" class="add-user" data-id="<?php echo $id_member_p; ?>" data-position="<?php echo POS_RIGHT?>">
                            <div class="photo-wrapper">
                                <div class="photo-content">
                                    <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-add.jpg'; ?>" /></div>
                                </div>
                                <div class="photo-name-available">Available</div>
                                <div class="photo-name-available2"><span>New Member</span></div>
                                <?php echo tfy_node(1,true); ?>
                            </div>
                        </a>
                    <?php endif?>

                    <ul class="child-2">
                        <!---------------------------------------------------------------------------------------------->
                        <!-- =================== Child Level 2 - Right Position ===================================== -->
                        <!---------------------------------------------------------------------------------------------->
                        <?php
                            $id_member_c1   = ( !empty($downright) ? $downright->id : '' );
                            $downleftR      = tfy_downline($id_member_c1, POS_LEFT);
                            $downrightR     = tfy_downline($id_member_c1, POS_RIGHT);
                        ?>

                        <li>
                            <!------------------------------------>
                            <!-- Left Position ------------------->
                            <!------------------------------------>
                            <?php if( !empty($downleftR) ): ?>
                                <?php // $downleftRSpon = bo_get_memberdata_by_id($downleftR->sponsor); ?>
                                <?php $downleftR_id = tfy_encrypt($downleftR->id); ?>
                                <a href="<?php echo base_url('member/tree/' . $downleftR_id); ?>">
                                    <?php echo tfy_avatar($downleftR->id, '', $downleftR->sponsor); ?>
                                </a>
                            <?php else: ?>
                                <?php if( !empty($downright) ): ?>
                                    <!-- Available To Add New Member -->
                                    <a href="#" class="add-user" data-id="<?php echo $id_member_c1; ?>" data-position="<?php echo POS_LEFT?>">
                                        <div class="photo-wrapper">
                                            <div class="photo-content">
                                                <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-add.jpg'; ?>" /></div>
                                            </div>
                                            <div class="photo-name-available">Available</div>
                                            <div class="photo-name-available2"><span>New Member</span></div>
                                            <?php echo tfy_node(1,true); ?>
                                        </div>
                                    </a>
                                <?php else: ?>
                                    <!-- Not Available To Add New Member -->
                                    <div class="photo-wrapper">
                                        <div class="photo-content">
                                            <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-noadd.jpg'; ?>" /></div>
                                        </div>
                                        <div class="photo-name-notavailable">Not Available</div>
                                        <div class="photo-name-notavailable2"><span>Empty</span></div>
                                        <?php echo tfy_node(1,true); ?>
                                    </div>
                                <?php endif ?>
                            <?php endif?>
                            <!------------------------------------>

                            <ul class="child-3 hidden-xs">
                                <!-------------------------------------------------------------------------------------->
                                <!-- =================== Child Level 3 - Left Position ============================== -->
                                <!-------------------------------------------------------------------------------------->
                                <?php
                                    $id_member      = ( !empty($downleftR) ? $downleftR->id : '' );
                                    $downleftRL     = tfy_downline($id_member, POS_LEFT);
                                    $downleftRR     = tfy_downline($id_member, POS_RIGHT);
                                ?>
                                <li>
                                    <!------------------------------------>
                                    <!-- Left Position ------------------->
                                    <!------------------------------------>
                                    <?php if( !empty($downleftRL) ): ?>
                                        <?php // $downleftRLSpon = bo_get_memberdata_by_id($downleftRL->sponsor); ?>
                                        <?php $downleftRL_id = tfy_encrypt($downleftRL->id); ?>
                                        <a href="<?php echo base_url('member/tree/' . $downleftRL_id); ?>">
                                            <?php echo tfy_avatar($downleftRL->id, '', $downleftRL->sponsor, FALSE); ?>
                                        </a>
                                    <?php else: ?>
                                        <?php if( !empty($downleftR) ): ?>
                                            <!-- Available To Add New Member -->
                                            <a href="#" class="add-user" data-id="<?php echo $id_member; ?>" data-position="<?php echo POS_LEFT?>">
                                                <div class="photo-wrapper">
                                                    <div class="photo-content">
                                                        <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-add.jpg'; ?>" /></div>
                                                    </div>
                                                    <div class="photo-name-available">Available</div>
                                                    <div class="photo-name-available2"><span>New Member</span></div>
                                                    <?php echo tfy_node(1,true); ?>
                                                </div>
                                            </a>
                                        <?php else: ?>
                                            <!-- Not Available To Add New Member -->
                                            <div class="photo-wrapper">
                                                <div class="photo-content">
                                                    <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-noadd.jpg'; ?>" /></div>
                                                </div>
                                                <div class="photo-name-notavailable">Not Available</div>
                                                <div class="photo-name-notavailable2"><span>Empty</span></div>
                                                <?php echo tfy_node(1,true); ?>
                                            </div>
                                        <?php endif ?>
                                    <?php endif?>
                                    <!------------------------------------>
                                </li>

                                <li>
                                    <!------------------------------------>
                                    <!-- Right Position ------------------>
                                    <!------------------------------------>
                                    <?php if( !empty($downleftRR) ): ?>
                                        <?php // $downleftRRSpon = bo_get_memberdata_by_id($downleftRR->sponsor); ?>
                                        <?php $downleftRR_id = tfy_encrypt($downleftRR->id); ?>
                                        <a href="<?php echo base_url('member/tree/' . $downleftRR_id); ?>">
                                            <?php echo tfy_avatar($downleftRR->id, '', $downleftRR->sponsor, FALSE); ?>
                                        </a>
                                    <?php else: ?>
                                        <?php if( !empty($downleftR) ): ?>
                                            <!-- Available To Add New Member -->
                                            <a href="#" class="add-user" data-id="<?php echo $id_member; ?>" data-position="<?php echo POS_RIGHT?>">
                                                <div class="photo-wrapper">
                                                    <div class="photo-content">
                                                        <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-add.jpg'; ?>" /></div>
                                                    </div>
                                                    <div class="photo-name-available">Available</div>
                                                    <div class="photo-name-available2"><span>New Member</span></div>
                                                    <?php echo tfy_node(1,true); ?>
                                                </div>
                                            </a>
                                        <?php else: ?>
                                            <!-- Not Available To Add New Member -->
                                            <div class="photo-wrapper">
                                                <div class="photo-content">
                                                    <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-noadd.jpg'; ?>" /></div>
                                                </div>
                                                <div class="photo-name-notavailable">Not Available</div>
                                                <div class="photo-name-notavailable2"><span>Empty</span></div>
                                                <?php echo tfy_node(1,true); ?>
                                            </div>
                                        <?php endif ?>
                                    <?php endif?>
                                    <!------------------------------------>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <!------------------------------------>
                            <!-- Right Position ------------------>
                            <!------------------------------------>
                            <?php if( !empty($downrightR) ): ?>
                                <?php // $downrightRSpon = bo_get_memberdata_by_id($downrightR->sponsor); ?>
                                <?php $downrightR_id = tfy_encrypt($downrightR->id); ?>
                                <a href="<?php echo base_url('member/tree/' . $downrightR_id); ?>">
                                    <?php echo tfy_avatar($downrightR->id, '', $downrightR->sponsor); ?>
                                </a>
                            <?php else: ?>
                                <?php if( !empty($downright) ): ?>
                                    <!-- Available To Add New Member -->
                                    <a href="#" class="add-user" data-id="<?php echo $id_member_c1; ?>" data-position="<?php echo POS_RIGHT?>">
                                        <div class="photo-wrapper">
                                            <div class="photo-content">
                                                <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-add.jpg'; ?>" /></div>
                                            </div>
                                            <div class="photo-name-available">Available</div>
                                            <div class="photo-name-available2"><span>New Member</span></div>
                                            <?php echo tfy_node(1,true); ?>
                                        </div>
                                    </a>
                                <?php else: ?>
                                    <!-- Not Available To Add New Member -->
                                    <div class="photo-wrapper">
                                        <div class="photo-content">
                                            <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-noadd.jpg'; ?>" /></div>
                                        </div>
                                        <div class="photo-name-notavailable">Not Available</div>
                                        <div class="photo-name-notavailable2"><span>Empty</span></div>
                                        <?php echo tfy_node(1,true); ?>
                                    </div>
                                <?php endif ?>
                            <?php endif?>
                            <!------------------------------------>

                            <ul class="child-3 hidden-xs">
                                <!-------------------------------------------------------------------------------------->
                                <!-- =================== Child Level 3 - Right Position ============================= -->
                                <!-------------------------------------------------------------------------------------->
                                <?php
                                    $id_member          = ( !empty($downrightR) ? $downrightR->id : '' );
                                    $downrightRL        = tfy_downline($id_member, POS_LEFT);
                                    $downrightRR        = tfy_downline($id_member, POS_RIGHT);
                                ?>

                                <li>
                                    <!------------------------------------>
                                    <!-- Left Position ------------------->
                                    <!------------------------------------>
                                    <?php if( !empty($downrightRL) ): ?>
                                        <?php // $downrightRLSpon = bo_get_memberdata_by_id($downrightRL->sponsor); ?>
                                        <?php $downrightRL_id = tfy_encrypt($downrightRL->id); ?>
                                        <a href="<?php echo base_url('member/tree/' . $downrightRL_id); ?>">
                                            <?php echo tfy_avatar($downrightRL->id, '', $downrightRL->sponsor, FALSE); ?>
                                        </a>
                                    <?php else: ?>
                                        <?php if( !empty($downrightR) ): ?>
                                            <!-- Available To Add New Member -->
                                            <a href="#" class="add-user" data-id="<?php echo $id_member; ?>" data-position="<?php echo POS_LEFT?>">
                                                <div class="photo-wrapper">
                                                    <div class="photo-content">
                                                        <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-add.jpg'; ?>" /></div>
                                                    </div>
                                                    <div class="photo-name-available">Available</div>
                                                    <div class="photo-name-available2"><span>New Member</span></div>
                                                    <?php echo tfy_node(1,true); ?>
                                                </div>
                                            </a>
                                        <?php else: ?>
                                            <!-- Not Available To Add New Member -->
                                            <div class="photo-wrapper">
                                                <div class="photo-content">
                                                    <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-noadd.jpg'; ?>" /></div>
                                                </div>
                                                <div class="photo-name-notavailable">Not Available</div>
                                                <div class="photo-name-notavailable2"><span>Empty</span></div>
                                                <?php echo tfy_node(1,true); ?>
                                            </div>
                                        <?php endif ?>
                                    <?php endif?>
                                    <!------------------------------------>
                                </li>

                                <li>
                                    <!------------------------------------>
                                    <!-- Right Position ------------------>
                                    <!------------------------------------>
                                    <?php if( !empty($downrightRR) ): ?>
                                        <?php // $downrightRRSpon = bo_get_memberdata_by_id($downrightRR->sponsor); ?>
                                        <?php $downrightRR_id = tfy_encrypt($downrightRR->id); ?>
                                        <a href="<?php echo base_url('member/tree/' . $downrightRR_id); ?>">
                                            <?php echo tfy_avatar($downrightRR->id, '', $downrightRR->sponsor, FALSE); ?>
                                        </a>
                                    <?php else: ?>
                                        <?php if( !empty($downrightR) ): ?>
                                            <!-- Available To Add New Member -->
                                            <a href="#" class="add-user" data-id="<?php echo $id_member; ?>" data-position="<?php echo POS_RIGHT?>">
                                                <div class="photo-wrapper">
                                                    <div class="photo-content">
                                                        <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-add.jpg'; ?>" /></div>
                                                    </div>
                                                    <div class="photo-name-available">Available</div>
                                                    <div class="photo-name-available2"><span>New Member</span></div>
                                                    <?php echo tfy_node(1,true); ?>
                                                </div>
                                            </a>
                                        <?php else: ?>
                                            <!-- Not Available To Add New Member -->
                                            <div class="photo-wrapper">
                                                <div class="photo-content">
                                                    <div class="photo-image"><img src="<?php echo BE_TREE_PATH . 'user-noadd.jpg'; ?>" /></div>
                                                </div>
                                                <div class="photo-name-notavailable">Not Available</div>
                                                <div class="photo-name-notavailable2"><span>Empty</span></div>
                                                <?php echo tfy_node(1,true); ?>
                                            </div>
                                        <?php endif ?>
                                    <?php endif?>
                                    <!------------------------------------>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <!-------------------------------------------------------------------------------------------------------->
            </ul>
            <!-------------------------------------------------------------------------------------------------------->
        </li>
    </ul>
</div>
<!-- Begin Table Responsive Tree Diagram -->
