<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('ddm_update_count_package_downline')) {
    /**
     * Save bonus of member
     * @author  Saddam
     * @param   Int         $id_member      (Required)  Member ID
     * @param   String      $month          (Required)  Year of Transaction
     * @param   String      $year           (Required)  Year of Transaction
     * @param   String      $package        (Required)  Package Qualification
     * @param   Boolean     $debug          (Optional)  Debug mode option
     * @return  Boolean
     */
    function ddm_update_count_package_downline($id_member, $month = '', $year = '', $package = '', $debug = false)
    {
        if (!$id_member) return false;
        if (empty($month) || empty($year) || empty($package)) return false;

        $CI                         = &get_instance();
        $curdate                    = date('Y-m-d H:i:s');
        $data_grade                 = array();
        $cond_grade                 = array('year' => $year, 'month' => $month);

        if ($grade = $CI->Model_Member->get_grade_by('id_member', $id_member, $cond_grade, 1)) {
            $count_package        = isset($grade->{"count_" . $package}) ? $grade->{"count_" . $package} : 0;
            $count_package        = absint($count_package);
            $count_package        = $count_package + 1;

            if ($count_package > 0) {
                $cond_grade['id_member'] = $id_member;
                $data_grade['count_' . $package] = $count_package;
                $data_grade['datemodified'] = $curdate;
                $update_member_grade = $CI->Model_Member->update_data_member_grade_by_condition($cond_grade, $data_grade);
            }
        }
    }
}

if (!function_exists('ddm_update_count_unit_package')) {
    /**
     * Save bonus of member
     * @author  Saddam
     * @param   Int         $id_member      (Required)  Member ID
     * @param   Int         $unit           (Required)  Point Unit
     * @param   String      $month          (Required)  Year of Transaction
     * @param   String      $year           (Required)  Year of Transaction
     * @param   String      $package        (Required)  Package Qualification
     * @param   Boolean     $debug          (Optional)  Debug mode option
     * @return  Boolean
     */
    function ddm_update_count_unit_package($id_member, $unit, $month = '', $year = '', $package = '', $debug = false)
    {
        if (!$id_member || !is_numeric($id_member)) return false;
        if (!$unit || !is_numeric($unit)) return false;
        if (empty($month) || empty($year) || empty($package)) return false;

        $CI                         = &get_instance();
        $data_grade                 = array();
        $cond_grade                 = array('year' => $year, 'month' => $month);

        if ($grade = $CI->Model_Member->get_grade_by('id_member', $id_member, $cond_grade, 1)) {
            $count_unit             = isset($grade->{"count_unit_" . $package}) ? $grade->{"count_unit_" . $package} : 0;
            $count_unit             = is_numeric($count_unit) ? $count_unit : 0;
            $count_unit             = $count_unit + $unit;

            if ($count_unit > 0) {
                $cond_grade['id_member'] = $id_member;
                $data_grade['count_unit_' . $package] = $count_unit;
                $update_member_grade = $CI->Model_Member->update_data_member_grade_by_condition($cond_grade, $data_grade);
            }
        }
    }
}

if (!function_exists('ddm_reset_count_package_downline')) {
    function ddm_reset_count_package_downline($year = '', $month = '', $package = '', $debug = false)
    {
        if (empty($month) || empty($year) || empty($package)) return false;

        $CI                                 = &get_instance();
        $curdate                            = date('Y-m-d H:i:s');
        $data_grade                         = array();
        $cond_grade                         = array('year' => $year, 'month' => $month);
        $data_grade['count_' . $package]    = 0;

        if (!$debug) {
            $CI->Model_Member->update_data_member_grade_by_condition($cond_grade, $data_grade);
        } else {
            echo ' Reset Package : ' . $package . br();
            echo ' Reset Count   : ' . json_encode($data_grade);
        }
    }
}

if (!function_exists('ddm_reset_count_unit_package')) {
    function ddm_reset_count_unit_package($year = '', $month = '', $package = '', $debug = false)
    {
        if (empty($month) || empty($year) || empty($package)) return false;

        $CI                                 = &get_instance();
        $cond_grade                         = array('year' => $year, 'month' => $month);
        $data_grade['count_unit_' . $package] = 0;

        if (!$debug) {
            $CI->Model_Member->update_data_member_grade_by_condition($cond_grade, $data_grade);
        } else {
            echo ' Reset Package : ' . $package . br();
            echo ' Reset Count   : ' . json_encode($data_grade);
        }
    }
}
