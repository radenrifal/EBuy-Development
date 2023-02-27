<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// -------------------------------------------------------------------------
// Bonus General functions helper
// -------------------------------------------------------------------------

if (!function_exists('ddm_save_bonus')) {
    /**
     * Save bonus of member
     * @author  Yuda
     * @param   Int         $id_member      (Required)  Member ID
     * @param   Object      $data           (Required)  Data of Bonus
     * @param   Boolean     $debug          (Optional)  Debug mode option
     * @return  Boolean
     */
    function ddm_save_bonus($id_member, $data, $debug = false)
    {
        if (!is_numeric($id_member)) return false;
        if (!$data) return false;

        $id_member  = absint($id_member);
        if (!$id_member) return false;

        $CI = &get_instance();

        // Set Required Variables
        $bonus_qualified        = TRUE;
        $bonus_amount           = isset($data['amount']) ? $data['amount'] : 0;
        $bonus_code             = isset($data['id_bonus']) ? $data['id_bonus'] : 'B' . date('YmdHis') . ddm_generate_rand_string(4, 'num');
        $type                   = isset($data['type']) ? $data['type'] : '';
        $description            = isset($data['desc']) ? $data['desc'] : '';
        $datetime               = isset($data['datecreated']) ? $data['datecreated'] : date('Y-m-d H:i:s');

        // Get Data member
        $memberdata             = ddm_get_memberdata_by_id($id_member);
        if (!$memberdata) return false;

        if ($memberdata->id == 1) {
            $bonus_qualified = false;
        }
        if ($memberdata->status != 1) return false;

        // Save Data Bonus
        // ---------------------------------------------------------
        if (!$debug && $bonus_qualified) {
            $bonus_id           = $CI->Model_Bonus->save_data_bonus($data);
            if (!$bonus_id) return false;

            // Set Data Ewallet
            $data_ewallet = array(
                'id_member'     => $memberdata->id,
                'id_source'     => $bonus_id,
                'amount'        => $bonus_amount,
                'source'        => 'bonus',
                'type'          => 'IN',
                'status'        => 1,
                'description'   => $description,
                'datecreated'   => $datetime
            );

            if (!$ewallet_id = $CI->Model_Bonus->save_data_ewallet($data_ewallet)) return false;

            return $bonus_id;
        }

        return true;
    }
}

if (!function_exists('ddm_calculate_aga_bonus')) {
    /**
     * Count Agent get Agent bonus of member
     * @author  Yuda
     * @param   Int         $id_member      (Required)  Member ID
     * @param   Int         $sponsor        (Required)  Sponsor ID
     * @param   Int         $omzet          (Required)  Total Omzet
     * @param   Datetime    $datetime       (Optional)  Datetime
     * @param   Boolean     $debug          (Optional)  Debug mode option
     * @return  Boolean
     */
    function ddm_calculate_aga_bonus($id_member, $sponsor, $omzet, $datetime = '', $debug = false)
    {
        if (!$id_member && !is_numeric($id_member)) return false;
        if (!$sponsor && !is_numeric($sponsor)) return false;
        if (!$omzet && !is_numeric($omzet)) return false;

        $id_member  = absint($id_member);
        if (!$id_member) return false;

        $sponsor    = absint($sponsor);
        if (!$sponsor) return false;

        $CI = &get_instance();

        $datetime               = $datetime ? $datetime : date('Y-m-d H:i:s');

        $memberdata             = ddm_get_memberdata_by_id($id_member);
        if (!$memberdata) return false;

        $sponsordata1           = ddm_get_memberdata_by_id($sponsor);
        if (!$sponsordata1) return false;
        if ($sponsordata1->status != 1) return false;

        $sponsorconfirm1        = ddm_get_memberconfirm_by_downline($sponsordata1->id);
        if (!$sponsorconfirm1) return false;
        if ($sponsorconfirm1->status != 1) return false;

        $cfg_aga                = config_item('bonus_aga');
        if (!$cfg_aga) return false;

        $percentage_spon1       = $cfg_aga[1];
        if (!$percentage_spon1) return false;

        $bonus_nominal_spon1          = ($omzet * $percentage_spon1) / 100;

        if ($debug) {
            echo 'Username Member : ' . $sponsordata1->username . ' get Bonus Agent get Agent (AGA) ' . ddm_accounting($bonus_nominal_spon1, 'Rp') . br(2);
        } else {
            if ($bonus_nominal_spon1) {
                // Set data and save bonus
                $data_bonus1        = array(
                    'id_bonus'      => 'B' . date('YmdHis') . ddm_generate_rand_string(4, 'num'),
                    'id_member'     => $sponsordata1->id,
                    'type'          => BONUS_AGA,
                    'desc'          => 'Komisi Agen get Agent (AGA) Gen-1 dari Omzet Agen ' . $memberdata->username . ' sebesar ' . ddm_accounting($omzet, 'Rp'),
                    'amount'        => $bonus_nominal_spon1,
                    'status'        => 1,
                    'datecreated'   => $datetime,
                    'datemodified'  => $datetime
                );
                ddm_save_bonus($sponsordata1->id, $data_bonus1, $debug);
            }
        }

        $sponsordata2           = ddm_get_memberdata_by_id($sponsordata1->sponsor);
        if (!$sponsordata2) return false;
        if ($sponsordata2->id != 1) {
            if ($sponsordata2->status != 1) return false;

            $sponsorconfirm2          = ddm_get_memberconfirm_by_downline($sponsordata2->id);
            if (!$sponsorconfirm2) return false;
            if ($sponsorconfirm2->status != 1) return false;

            $percentage_spon2       = $cfg_aga[2];
            if (!$percentage_spon2) return false;

            $bonus_nominal_spon2          = ($omzet * $percentage_spon2) / 100;

            if ($debug) {
                echo 'Username Member : ' . $sponsordata2->username . ' get Bonus Agent get Agent (AGA) ' . ddm_accounting($bonus_nominal_spon2, 'Rp') . br(2);
            } else {
                if ($bonus_nominal_spon2) {
                    // Set data and save bonus
                    $data_bonus2        = array(
                        'id_bonus'      => 'B' . date('YmdHis') . ddm_generate_rand_string(4, 'num'),
                        'id_member'     => $sponsordata2->id,
                        'type'          => BONUS_AGA,
                        'desc'          => 'Komisi Agen get Agent (AGA) Gen-2 dari Omzet Agen ' . $memberdata->username . ' sebesar ' . ddm_accounting($omzet, 'Rp'),
                        'amount'        => $bonus_nominal_spon2,
                        'status'        => 1,
                        'datecreated'   => $datetime,
                        'datemodified'  => $datetime
                    );
                    ddm_save_bonus($sponsordata2->id, $data_bonus2, $debug);
                }
            }
        }
        return true;
    }
}

if (!function_exists('ddm_calculate_kga_bonus')) {
    /**
     * Count Agent get Agent bonus of member
     * @author  Yuda
     * @param   Int         $id_member      (Required)  Member ID
     * @param   Int         $sponsor        (Required)  Sponsor ID
     * @param   Int         $omzet          (Required)  Total Omzet
     * @param   Datetime    $datetime       (Optional)  Datetime
     * @param   Boolean     $debug          (Optional)  Debug mode option
     * @return  Boolean
     */
    function ddm_calculate_kga_bonus($id_member, $omzet, $datetime = '', $debug = false)
    {
        if (!$id_member && !is_numeric($id_member)) return false;
        if (!$omzet && !is_numeric($omzet)) return false;

        $id_member  = absint($id_member);
        if (!$id_member) return false;


        $CI = &get_instance();

        $datetime               = $datetime ? $datetime : date('Y-m-d H:i:s');

        $memberdata             = ddm_get_memberdata_by_id($id_member);
        if (!$memberdata) return false;

        $cfg_aga                = config_item('bonus_aga');
        if (!$cfg_aga) return false;

        $percentage_spon1       = $cfg_aga[1];
        if (!$percentage_spon1) return false;

        $bonus_nominal_spon1          = ($omzet * $percentage_spon1) / 100;

        if ($debug) {
            echo 'Username Member : ' . $sponsordata1->username . ' get Bonus Agent get Agent (AGA) ' . ddm_accounting($bonus_nominal_spon1, 'Rp') . br(2);
        } else {
            if ($bonus_nominal_spon1) {
                // Set data and save bonus
                $data_bonus1        = array(
                    'id_bonus'      => 'B' . date('YmdHis') . ddm_generate_rand_string(4, 'num'),
                    'id_member'     => $sponsordata1->id,
                    'type'          => BONUS_AGA,
                    'desc'          => 'Komisi Agen get Agent (AGA) Gen-1 dari Omzet Agen ' . $memberdata->username . ' sebesar ' . ddm_accounting($omzet, 'Rp'),
                    'amount'        => $bonus_nominal_spon1,
                    'status'        => 1,
                    'datecreated'   => $datetime,
                    'datemodified'  => $datetime
                );
                ddm_save_bonus($sponsordata1->id, $data_bonus1, $debug);
            }
        }

        $sponsordata2           = ddm_get_memberdata_by_id($sponsordata1->sponsor);
        if (!$sponsordata2) return false;
        if ($sponsordata2->id != 1) {
            if ($sponsordata2->status != 1) return false;

            $sponsorconfirm2          = ddm_get_memberconfirm_by_downline($sponsordata2->id);
            if (!$sponsorconfirm2) return false;
            if ($sponsorconfirm2->status != 1) return false;

            $percentage_spon2       = $cfg_aga[2];
            if (!$percentage_spon2) return false;

            $bonus_nominal_spon2          = ($omzet * $percentage_spon2) / 100;

            if ($debug) {
                echo 'Username Member : ' . $sponsordata2->username . ' get Bonus Agent get Agent (AGA) ' . ddm_accounting($bonus_nominal_spon2, 'Rp') . br(2);
            } else {
                if ($bonus_nominal_spon2) {
                    // Set data and save bonus
                    $data_bonus2        = array(
                        'id_bonus'      => 'B' . date('YmdHis') . ddm_generate_rand_string(4, 'num'),
                        'id_member'     => $sponsordata2->id,
                        'type'          => BONUS_AGA,
                        'desc'          => 'Komisi Agen get Agent (AGA) Gen-2 dari Omzet Agen ' . $memberdata->username . ' sebesar ' . ddm_accounting($omzet, 'Rp'),
                        'amount'        => $bonus_nominal_spon2,
                        'status'        => 1,
                        'datecreated'   => $datetime,
                        'datemodified'  => $datetime
                    );
                    ddm_save_bonus($sponsordata2->id, $data_bonus2, $debug);
                }
            }
        }
        return true;
    }
}
