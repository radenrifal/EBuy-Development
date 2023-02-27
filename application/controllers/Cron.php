<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Cron Controller.
 *
 * @class     Cron
 * @author    Yuda
 * @version   1.0.0
 */
class Cron extends DDM_Controller
{

    /**
     * DDMcron.
     */
    protected $codecron = '$2y$05$7d89ow0YbLpkik76v3I3ne1EVfvxOgWuQGTuWKjlIERrERP0jL0.K'; // tahun2021

    /**
     * Constructor.
     */
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
    }
    /**
     * Start Cron Process
     */
    private function _start_cron($cron_name = '', $debug = true)
    {
        $this->benchmark->mark('started');
        if (!$debug) {
            $cron_name = $cron_name ? $cron_name : 'cronjob';
            ddm_log_cron($cron_name, 'STARTED');
        }
        echo "<pre>";
    }

    /**
     * End Cron Process
     */
    private function _end_cron($cron_name = '', $debug = true, $log = '')
    {
        $this->benchmark->mark('ended');
        $elapsed_time   = $this->benchmark->elapsed_time('started', 'ended');
        $elapsed_time   = 'Elapsed Time : ' . $elapsed_time . ' seconds';
        if (!$debug) {
            $cron_name  = $cron_name ? $cron_name : 'cronjob';
            $log_desc   = $elapsed_time . ', Log : ' . $log;
            ddm_log_cron($cron_name, 'ENDED', $log_desc);
        }
        echo  $elapsed_time . br();
        echo '</pre>';
    }

    /**
     * CRON JOB : Grade
     *
     * @param    String     $code       default ''
     * @param    boolean    $debug      default true
     * @param    Month      $month      default current month
     * @author   Yuda
     */
    function grade($keycode = '', $debug = true, $yearmonth = '')
    {
        set_time_limit(0);
        if (!$keycode) die();

        $keycode    = trim($keycode);
        $validate   = password_verify($keycode, $this->codecron);

        if (!$validate) die();

        $yearmonth                  = $yearmonth ? date('Y-m', strtotime($yearmonth)) : date('Y-m', strtotime('-1 Day'));
        $datetime                   = $yearmonth . '-' . date('t', strtotime($yearmonth)) . ' ' . date('23:59:5') . rand(0, 9);
        $curdate                    = date('Y-m-d H:i:s');
        $year                       = date('Y', strtotime($yearmonth));
        $month                      = date('n', strtotime($yearmonth));
        $package_maintain_cfg       = config_item('package_maintain');


        $before_yearmonth           = date('Y-m', strtotime($yearmonth . ' -1 month'));
        $before_year                = date('Y', strtotime($before_yearmonth));
        $before_month               = date('n', strtotime($before_yearmonth));

        $cfg_packnext               = config_item('package_next');
        $cfg_max_level              = config_item('max_gen_level');

        $this->_start_cron('Grade', $debug);
        echo '====================================================' . br();
        echo ' Calculate Grade' . br();
        echo '----------------------------------------------------' . br();
        echo ' Function    : ' . ($debug ? 'Debug ' : 'Save ') . br();
        echo ' Year        : ' . $year . br();
        echo ' Month       : ' . $month . br();
        echo ' Datetime    : ' . $datetime . br();
        echo '----------------------------------------------------' . br();

        if (!$debug) {
            // Update Package Member
            $sql    = 'UPDATE ddm_member SET rank = ? WHERE id > 1 AND package != ?';
            $query  = $this->db->query($sql, array(RANK_AGENT, RANK_AGENT));

            // Update Package Grade
            $sql    = 'UPDATE ddm_grade SET rank = ? WHERE year = ? AND month = ?';
            $query  = $this->db->query($sql, array(null, $year, $month));
        }

        $data_ancestry  = array();
        $condition      = ' AND %month_omzet% = ? AND %status% != ? ';
        if ($data_omzet = $this->Model_Member->get_all_omzet_monthly_member(0, 0, $condition, '', '', array($yearmonth, 'product'))) {
            $iResult = count($data_omzet);
            $no = 1;
            echo ' Total Data  : ' . $iResult . br();
            echo '----------------------------------------------------' . br(2);
            foreach ($data_omzet as $k => $row) {
                $total_omzet        = $row->total_omzet;
                $total_bv           = $row->total_bv;
                $total_amount       = $row->total_amount;
                $total_qty          = $row->total_qty;

                // Anchestry Data
                $data_ancestry[$row->id_member] = $row->id_member;
                $tree_ancestry  = ddm_ancestry_sponsor($row->id_member);
                $ancestry       = explode(',', $tree_ancestry);

                if ($ancestry) {
                    $gen = 1;
                    foreach ($ancestry as $key => $_id) {
                        if ($_id == 1) {
                            continue;
                        }
                        if ($gen == 5) {
                            break;
                        }

                        if (!isset($data_ancestry[$_id])) {
                            $data_ancestry[$_id] = $_id;
                        }
                        $gen++;
                    }
                }

                echo "No. " . ($no) . br();
                echo '-------------------------------------------------------' . br();
                echo 'Year                  : ' . $year . br();
                echo 'Month                 : ' . $month . br();
                echo '-------------------------------------------------------' . br();
                echo 'ID Member             : ' . $row->id_member . br();
                echo 'Username              : ' . $row->username . br();
                echo 'Name                  : ' . $row->name . br();
                echo '-------------------------------------------------------' . br();
                echo 'Omzet Qty             = ' . ddm_accounting($total_qty) . ' Liter' . br();
                echo 'Omzet                 = ' . ddm_accounting($total_omzet) . br();
                echo 'BV                    = ' . ddm_accounting($total_bv) . br();
                echo 'Amount                = ' . ddm_accounting($total_amount) . br();
                echo '-------------------------------------------------------' . br();
                echo 'Before Year           : ' . $before_year . br();
                echo 'Before Mnth           : ' . $before_month . br();
                echo '-------------------------------------------------------' . br();
                echo 'Data Anchestry        : ' . $tree_ancestry . br();
                echo 'Total Anchestry       = ' . count($data_ancestry) . br();
                echo '=======================================================' . br(3);

                if (!$debug) {

                    $data_grade     = array(
                        'id_member'     => $row->id_member,
                        'username'      => $row->username,
                        'rank'          => RANK_AGENT,
                        'year'          => $year,
                        'month'         => $month,
                        'total_qty'     => $total_qty,
                        'total_omzet'   => $total_omzet,
                        'total_pv'      => $total_bv,
                        'total_amount'  => $total_amount,
                        'datecreated'   => $curdate,
                        'datemodified'  => $curdate,
                    );

                    $cond_grade     = array('year' => $year, 'month' => $month);
                    if ($getGrade  = $this->Model_Member->get_grade_by('id_member', $row->id_member, $cond_grade, 1)) {
                        unset($data_grade['datecreated']);
                        // Update
                        $save_grade = $this->Model_Member->update_data_member_grade($getGrade->id, $data_grade);
                    } else {
                        // Insert
                        $save_grade = $this->Model_Member->save_data_member_grade($data_grade);
                    }
                }

                $no++;
            }
        } else {
            echo br(2) . " Data Omzet tidak ditemukan." . br();
        }


        if ($data_ancestry) {
            asort($data_ancestry);
            foreach ($data_ancestry as $key => $_id) {
                if ($_id == 1) {
                    continue;
                }
                $memberdata     = ddm_get_memberdata_by_id($_id);
                if (!$memberdata) {
                    continue;
                }

                $data_grade     = array(
                    'id_member'     => $memberdata->id,
                    'username'      => $memberdata->username,
                    'year'          => $year,
                    'month'         => $month,
                    'datecreated'   => $curdate,
                    'datemodified'  => $curdate,
                );

                $cond_grade     = array('year' => $year, 'month' => $month);
                $getGrade       = $this->Model_Member->get_grade_by('id_member', $memberdata->id, $cond_grade, 1);
                if (!$getGrade) {
                    $save_grade = $this->Model_Member->save_data_member_grade($data_grade);
                }
            }
        }

        echo br(2) . '----------------------------------------------------' . br();
        $this->_end_cron('Grade', $debug);
    }

    /**
     * CRON JOB : Grade Omzet Group
     *
     * @param    String     $code       default ''
     * @param    boolean    $debug      default true
     * @param    Month      $month      default current month
     * @author   Yuda
     */
    function grade_omzet_group($keycode = '', $debug = true, $yearmonth = '')
    {
        set_time_limit(0);
        if (!$keycode) die();

        $keycode    = trim($keycode);
        $validate   = password_verify($keycode, $this->codecron);

        if (!$validate) die();

        $yearmonth              = $yearmonth ? date('Y-m', strtotime($yearmonth)) : date('Y-m', strtotime('-1 Day'));
        $datetime               = $yearmonth . '-' . date('t', strtotime($yearmonth)) . ' ' . date('23:59:5') . rand(0, 9);
        $year                   = date('Y', strtotime($yearmonth));
        $month                  = date('n', strtotime($yearmonth));
        $curdate                = date('Y-m-d H:i:s');
        $cfg_max_level          = config_item('max_gen_level');
        $cfg_min_order_agent    = config_item('min_order_agent');

        $this->_start_cron('Grade_Omzet_Group', $debug);
        echo '====================================================' . br();
        echo ' Calculate Grade Omzet Group' . br();
        echo '----------------------------------------------------' . br();
        echo ' Function    : ' . ($debug ? 'Debug ' : 'Save ') . br();
        echo ' Year        : ' . $year . br();
        echo ' Month       : ' . $month . br();
        echo ' Datetime    : ' . $datetime . br();
        echo '----------------------------------------------------' . br();

        $data_ancestry  = array();
        $condition      = ' AND %year% = ? AND %month% = ? ';
        if ($data_omzet = $this->Model_Member->get_all_member_grade(0, 0, $condition, '', array($year, $month))) {
            $iResult    = count($data_omzet);
            $no = 1;
            echo ' Total Data  : ' . $iResult . br();
            echo '----------------------------------------------------' . br(2);
            foreach ($data_omzet as $k => $row) {
                if (!$row->tree);
                $my_level       = $row->level;
                $max_level      = $my_level + $cfg_max_level;
                $min_qty        = $cfg_min_order_agent;
                // New
                // ----------------------------------------
                $omzet_group    = 0;
                $pv_group       = 0;
                $qty_group      = 0;
                $amount_group   = 0;
                $active_rank    = 0;

                $pv_group       = 0;
                $pv_bonus_group = 0;
                $unit_group     = 0;
                $filter_group   = 0;
                $group_active   = 0;

                // Count Omzet Group
                $cond_total_group   = ' AND M.tree LIKE CONCAT(?, "%") AND DATE_FORMAT(G.date,"%Y-%m") = ? AND G.status != ?';
                $param_group        = array($row->tree, $yearmonth, 'product');

                if ($get_total_group = $this->Model_Member->get_total_member_omzet_group($cond_total_group, '', $param_group)) {
                    $omzet_group        = $get_total_group->total_omzet;
                    $pv_group           = $get_total_group->total_bv;
                    $qty_group          = $get_total_group->total_qty;
                    $amount_group       = $get_total_group->total_amount;
                    $active_rank        = $get_total_group->active_rank;
                    // $pv_group       = isset($get_total_group->total_pv_grade) ? $get_total_group->total_pv_grade : 0;
                    // $pv_bonus_group = isset($get_total_group->total_pv) ? $get_total_group->total_pv : 0;
                    // $unit_group     = isset($get_total_group->total_unit) ? $get_total_group->total_unit : 0;
                    // $filter_group   = isset($get_total_group->total_filter) ? $get_total_group->total_filter : 0;
                }

                // Get Group Active
                // $cond_group_act     = ' AND M.tree LIKE CONCAT(?, "-%") AND DATE_FORMAT(G.date,"%Y-%m") = ?';
                // $cond_group         = ' HAVING (SUM( G.`qty` ) >= ?)';
                // $param_group        = array($row->tree, $yearmonth, $min_qty);
                // $getGradeGroup      = $this->Model_Member->get_group_line_active($cond_group_act, $cond_group,  $param_group, TRUE);
                // if ($getGradeGroup) {
                //     $group_active = count($getGradeGroup) ? count($getGradeGroup) : 0;
                // }

                echo "No. " . ($no) . br();
                echo '-------------------------------------------------------' . br();
                echo 'ID Grade      : ' . $row->id . br();
                echo '-------------------------------------------------------' . br();
                echo 'ID Member     : ' . $row->id_member . br();
                echo 'Username      : ' . $row->username . br();
                echo 'Name          : ' . $row->name . br();
                echo '-------------------------------------------------------' . br();
                echo 'Level         : ' . $my_level . br();
                echo '-------------------------------------------------------' . br();
                echo 'Omzet         = ' . ddm_accounting($row->total_omzet) . br();
                echo 'BV            = ' . ddm_accounting($row->total_pv) . br();
                echo 'Qty           = ' . ddm_accounting($row->total_qty) . ' Liter' . br();
                echo 'Amount        = ' . ddm_accounting($row->total_amount) . br();
                echo '-------------------------------------------------------' . br();
                echo 'Group         :' . br();
                echo 'Omzet         = ' . ddm_accounting($omzet_group) . br();
                echo 'BV            = ' . ddm_accounting($pv_group) . br();
                echo 'Qty           = ' . ddm_accounting($qty_group) . ' Liter' . br();
                echo 'Amount        = ' . ddm_accounting($amount_group) . br();
                echo '-------------------------------------------------------' . br();
                echo 'Group Active  = ' . ddm_accounting($active_rank) . br();
                echo '====================================================' . br(3);

                if (!$debug) {
                    $data_grade     = array(
                        'id_member'             => $row->id_member,
                        'year'                  => $row->year,
                        'month'                 => $row->month,
                        'total_qty'             => $row->total_qty,
                        'total_qty_group'       => $qty_group,
                        'total_omzet'           => $row->total_omzet,
                        'total_omzet_group'     => $omzet_group,
                        'total_pv'              => $row->total_pv,
                        'total_pv_group'        => $pv_group,
                        'total_amount'          => $row->total_amount,
                        'total_amount_group'    => $amount_group,
                        'line_active'           => $active_rank,
                        'datemodified'          => $curdate,
                    );

                    $save_grade = $this->Model_Member->update_data_member_grade($row->id, $data_grade);
                }

                $no++;
            }
        } else {
            echo br(2) . " Data Omzet tidak ditemukan." . br();
        }

        echo br(2) . '----------------------------------------------------' . br();
        $this->_end_cron('Grade_Omzet_Group', $debug);
    }

    /**
     * CRON JOB : Grade Qualifed Star Agent
     *
     * @param    String     $code       default ''
     * @param    boolean    $debug      default true
     * @param    Month      $month      default current month
     * @author   Saddam
     */
    function grade_qualified_startagent($keycode = '', $debug = true, $yearmonth = '', $next_execute = false, $cli = true)
    {
        set_time_limit(0);
        if (!$keycode) die();

        $keycode    = trim($keycode);
        $validate   = password_verify($keycode, $this->codecron);

        if (!$validate) die();

        $yearmonth                  = $yearmonth ? date('Y-m', strtotime($yearmonth)) : date('Y-m', strtotime('-1 Day'));
        $datetime                   = $yearmonth . '-' . date('t', strtotime($yearmonth)) . ' ' . date('23:59:5') . rand(0, 9);
        $year                       = date('Y', strtotime($yearmonth));
        $month                      = date('n', strtotime($yearmonth));
        $curdate                    = date('Y-m-d H:i:s');
        $cfg_rank_qualified         = config_item('rank_qualified');
        $star_agent                 = isset($cfg_rank_qualified[RANK_STAR_AGENT]) ? $cfg_rank_qualified[RANK_STAR_AGENT] : false;

        // ===================================================
        // Begin check if is set config in database
        // ===================================================
        // $rank_qualified_db       = get_option('cfg_rank_qualified');

        // if ($rank_qualified_db && is_array($rank_qualified_db)) {
        //     $cfg_rank_qualified          = $rank_qualified_db;
        // }

        // ===================================================

        $this->_start_cron('Grade_Qualifed_StarAgent', $debug, $cli);
        echo '====================================================' . br();
        echo ' Calculate Grade Qualifed Star Agent' . br();
        echo '----------------------------------------------------' . br();
        echo ' Function    : ' . ($debug ? 'Debug ' : 'Save ') . br();
        echo ' Year        : ' . $year . br();
        echo ' Month       : ' . $month . br();
        echo ' Datetime    : ' . $datetime . br();
        echo '----------------------------------------------------' . br();

        $data_ancestry  = array();
        $condition      = ' AND %year% = ? AND %month% = ? ';
        if ($data_omzet = $this->Model_Member->get_all_member_grade(0, 0, $condition, '', array($year, $month))) {
            $iResult    = count($data_omzet);
            $no = 1;
            ddm_reset_count_package_downline($year, $month, RANK_STAR_AGENT, $debug);
            echo ' Total Data  : ' . $iResult . br();
            echo '----------------------------------------------------' . br(2);
            foreach ($data_omzet as $k => $row) {
                // Config Qualifed Executive
                $package_qualified_stat = false;
                $package_qualified      = '';
                $ancestry               = '';
                $count_executive        = 0;
                $min_omzet              = isset($star_agent['min_line']) ? $star_agent['min_line'] : 0;
                $ancestry               = ddm_ancestry_sponsor($row->id_member);
                $ancestry               = explode(',', $ancestry);

                // Check Qualified Package
                if (!$package_qualified_stat) {
                    // Chech Package Qualification for Executive
                    if ($row->total_omzet > 0) {

                        if ($star_agent) {
                            if ($row->line_active >= $min_omzet) {
                                $package_qualified = RANK_STAR_AGENT;
                                $package_qualified_stat = true;
                            }
                        }
                    }
                }


                echo "No. " . ($no) . br();
                echo '-------------------------------------------------------' . br();
                echo 'ID Grade    : ' . $row->id . br();
                echo '-------------------------------------------------------' . br();
                echo 'ID Member   : ' . $row->id_member . br();
                echo 'Username    : ' . $row->username . br();
                echo 'Name        : ' . $row->name . br();
                echo '-------------------------------------------------------' . br();
                echo 'Omzet         = ' . ddm_accounting($row->total_omzet) . br();
                echo 'Omzet Group   = ' . ddm_accounting($row->total_omzet_group) . br();
                echo 'BV            = ' . ddm_accounting($row->total_pv) . br();
                echo 'BV Group      = ' . ddm_accounting($row->total_pv_group) . br();
                echo 'Amount        = ' . ddm_accounting($row->total_amount) . br();
                echo 'Amount Group  = ' . ddm_accounting($row->total_amount_group) . br();
                echo 'Qty           = ' . ddm_accounting($row->total_qty) . ' Liter' . br();
                echo 'Qty Group     = ' . ddm_accounting($row->total_qty_group) . ' Liter' . br();
                echo 'Group Act     = ' . ddm_accounting($row->line_active) . br();
                echo '-------------------------------------------------------' . br();
                echo 'Min Omzet   = ' . ddm_accounting($min_omzet) . ' Line @150 Liter' . br();
                echo 'Package Q   = ' . ($package_qualified_stat ? '1' : '0') . br();
                echo '-------------------------------------------------------' . br();
                if ($package_qualified_stat) {
                    echo 'Rank     = ' . $package_qualified . br();
                    echo 'Ancestry Up = ' . json_encode($ancestry) . br();
                }
                echo '====================================================' . br(3);

                if (!$debug) {
                    $data_grade = array();

                    if ($package_qualified_stat) {
                        $data_grade['rank']                 = $package_qualified;
                        $data_grade['rank_qualified']       = $package_qualified;
                        $data_package['package']            = $package_qualified;
                        $update_package = $this->Model_Member->update_data_member($row->id_member, $data_package);
                    }

                    if ($data_grade) {
                        $data_grade['datemodified'] = $curdate;
                        $save_grade = $this->Model_Member->update_data_member_grade($row->id, $data_grade);

                        if ($save_grade && $package_qualified_stat) {

                            if ($ancestry) {
                                $gen = 1;
                                // Update Anchestry for 5 Gen
                                foreach ($ancestry as $key => $_id) {
                                    $data_grade = array();
                                    if ($_id == 1) {
                                        continue;
                                    }

                                    ddm_update_count_package_downline($_id, $month, $year, $package_qualified);

                                    $gen++;
                                }
                            }
                        }
                    }
                }

                $no++;
            }
        } else {
            echo br(2) . " Data Omzet tidak ditemukan." . br();
        }

        echo br(2) . '----------------------------------------------------' . br();
        $this->_end_cron('Grade_Qualifed_StarAgent', $debug);
    }

    /**
     * CRON JOB : Grade Qualifed Super Agent
     *
     * @param    String     $code       default ''
     * @param    boolean    $debug      default true
     * @param    Month      $month      default current month
     * @author   Saddam
     */
    function grade_qualified_superagent($keycode = '', $debug = true, $yearmonth = '', $next_execute = false, $cli = true)
    {
        set_time_limit(0);
        if (!$keycode) die();

        $keycode    = trim($keycode);
        $validate   = password_verify($keycode, $this->codecron);

        if (!$validate) die();

        $yearmonth                  = $yearmonth ? date('Y-m', strtotime($yearmonth)) : date('Y-m', strtotime('-1 Day'));
        $datetime                   = $yearmonth . '-' . date('t', strtotime($yearmonth)) . ' ' . date('23:59:5') . rand(0, 9);
        $year                       = date('Y', strtotime($yearmonth));
        $month                      = date('n', strtotime($yearmonth));
        $curdate                    = date('Y-m-d H:i:s');
        $cfg_rank_qualified         = config_item('rank_qualified');
        $superagent                 = isset($cfg_rank_qualified[RANK_SUPER_AGENT]) ? $cfg_rank_qualified[RANK_SUPER_AGENT] : false;

        // ===================================================
        // Begin check if is set config in database
        // ===================================================
        // $rank_qualified_db       = get_option('cfg_rank_qualified');

        // if ($rank_qualified_db && is_array($rank_qualified_db)) {
        //     $cfg_rank_qualified          = $rank_qualified_db;
        // }

        // ===================================================

        $this->_start_cron('Grade_Qualifed_SuperAgent', $debug, $cli);
        echo '====================================================' . br();
        echo ' Calculate Grade Qualifed Super Agent' . br();
        echo '----------------------------------------------------' . br();
        echo ' Function    : ' . ($debug ? 'Debug ' : 'Save ') . br();
        echo ' Year        : ' . $year . br();
        echo ' Month       : ' . $month . br();
        echo ' Datetime    : ' . $datetime . br();
        echo '----------------------------------------------------' . br();

        $data_ancestry  = array();
        $condition      = ' AND %year% = ? AND %month% = ? ';
        if ($data_omzet = $this->Model_Member->get_all_member_grade(0, 0, $condition, '', array($year, $month))) {
            $iResult    = count($data_omzet);
            $no = 1;
            ddm_reset_count_package_downline($year, $month, RANK_SUPER_AGENT, $debug);
            echo ' Total Data  : ' . $iResult . br();
            echo '----------------------------------------------------' . br(2);
            foreach ($data_omzet as $k => $row) {
                // Config Qualifed Executive
                $package_qualified_stat = false;
                $package_qualified      = '';
                $ancestry               = '';
                $count_executive        = 0;
                $min_omzet              = isset($superagent['min_line']) ? $superagent['min_line'] : 0;
                $ancestry               = ddm_ancestry_sponsor($row->id_member);
                $ancestry               = explode(',', $ancestry);
                $line_active            = 0;

                // Check Qualified Package
                if (!$package_qualified_stat) {
                    // Chech Package Qualification for Executive
                    if ($row->total_omzet > 0) {

                        if ($superagent) {
                            $line_active = ddm_count_line_rank($row->id_member, $yearmonth, RANK_STAR_AGENT);
                            if ($line_active >= $min_omzet) {
                                $package_qualified = RANK_SUPER_AGENT;
                                $package_qualified_stat = true;
                            }
                        }
                    }
                }


                echo "No. " . ($no) . br();
                echo '-------------------------------------------------------' . br();
                echo 'ID Grade    : ' . $row->id . br();
                echo '-------------------------------------------------------' . br();
                echo 'ID Member   : ' . $row->id_member . br();
                echo 'Username    : ' . $row->username . br();
                echo 'Name        : ' . $row->name . br();
                echo '-------------------------------------------------------' . br();
                echo 'Omzet         = ' . ddm_accounting($row->total_omzet) . br();
                echo 'Omzet Group   = ' . ddm_accounting($row->total_omzet_group) . br();
                echo 'BV            = ' . ddm_accounting($row->total_pv) . br();
                echo 'BV Group      = ' . ddm_accounting($row->total_pv_group) . br();
                echo 'Amount        = ' . ddm_accounting($row->total_amount) . br();
                echo 'Amount Group  = ' . ddm_accounting($row->total_amount_group) . br();
                echo 'Qty           = ' . ddm_accounting($row->total_qty) . ' Liter' . br();
                echo 'Qty Group     = ' . ddm_accounting($row->total_qty_group) . ' Liter' . br();
                echo 'Group Act     = ' . ddm_accounting($line_active) . br();
                echo '-------------------------------------------------------' . br();
                echo 'Min Omzet   = ' . ddm_accounting($min_omzet) . ' Line Star Agent' . br();
                echo 'Package Q   = ' . ($package_qualified_stat ? '1' : '0') . br();
                echo '-------------------------------------------------------' . br();
                if ($package_qualified_stat) {
                    echo 'Rank     = ' . $package_qualified . br();
                    echo 'Ancestry Up = ' . json_encode($ancestry) . br();
                }
                echo '====================================================' . br(3);

                if (!$debug) {
                    $data_grade = array();

                    if ($package_qualified_stat) {
                        $data_grade['rank']                 = $package_qualified;
                        $data_grade['rank_qualified']       = $package_qualified;
                        $data_package['package']            = $package_qualified;
                        $update_package = $this->Model_Member->update_data_member($row->id_member, $data_package);
                    }

                    if ($data_grade) {
                        $data_grade['datemodified'] = $curdate;
                        $save_grade = $this->Model_Member->update_data_member_grade($row->id, $data_grade);

                        if ($save_grade && $package_qualified_stat) {

                            if ($ancestry) {
                                $gen = 1;
                                // Update Anchestry for 5 Gen
                                foreach ($ancestry as $key => $_id) {
                                    $data_grade = array();
                                    if ($_id == 1) {
                                        continue;
                                    }

                                    ddm_update_count_package_downline($_id, $month, $year, $package_qualified);

                                    $gen++;
                                }
                            }
                        }
                    }
                }

                $no++;
            }
        } else {
            echo br(2) . " Data Omzet tidak ditemukan." . br();
        }

        echo br(2) . '----------------------------------------------------' . br();
        $this->_end_cron('Grade_Qualifed_SuperAgent', $debug);
    }

    function bonus_kga($keycode = '', $debug = true,  $yearmonth = '', $next_execute = false, $cli = true)
    {
        set_time_limit(0);
        if (!$keycode) die();

        $keycode    = trim($keycode);
        $validate   = password_verify($keycode, $this->codecron);

        if (!$validate) die();

        $yearmonth                  = $yearmonth ? date('Y-m', strtotime($yearmonth)) : date('Y-m', strtotime('-1 Day'));
        $datetime                   = $yearmonth . '-' . date('t', strtotime($yearmonth)) . ' ' . date('23:59:5') . rand(0, 9);
        $year                       = date('Y', strtotime($yearmonth));
        $month                      = date('n', strtotime($yearmonth));
        $curdate                    = date('Y-m-d H:i:s');
        $cfg_rank_qualified         = config_item('rank_qualified');
        $cfg_rank                   = config_item('ranks');
        $star_agent                 = isset($cfg_rank_qualified[RANK_STAR_AGENT]) ? $cfg_rank_qualified[RANK_STAR_AGENT] : false;

        // ===================================================
        // Begin check if is set config in database
        // ===================================================
        // $rank_qualified_db       = get_option('cfg_rank_qualified');

        // if ($rank_qualified_db && is_array($rank_qualified_db)) {
        //     $cfg_rank_qualified          = $rank_qualified_db;
        // }

        // ===================================================

        $this->_start_cron('Calculate_Bonus_KGA', $debug, $cli);
        echo '====================================================' . br();
        echo ' Calculate Grade Qualifed Star Agent' . br();
        echo '----------------------------------------------------' . br();
        echo ' Function    : ' . ($debug ? 'Debug ' : 'Save ') . br();
        echo ' Year        : ' . $year . br();
        echo ' Month       : ' . $month . br();
        echo ' Datetime    : ' . $datetime . br();
        echo '----------------------------------------------------' . br();

        $data_ancestry  = array();
        $condition      = ' AND %year% = ? AND %month% = ? AND (%rank% = ? OR %rank% = ?)';
        if ($data_omzet = $this->Model_Member->get_all_member_grade(0, 0, $condition, '', array($year, $month, RANK_STAR_AGENT, RANK_SUPER_AGENT))) {
            $iResult    = count($data_omzet);
            $no = 1;
            echo ' Total Data  : ' . $iResult . br();
            echo '----------------------------------------------------' . br(2);
            foreach ($data_omzet as $k => $row) {
                // // Config Qualifed Executive
                $bonus_qualified_stat       = false;
                $amount_bonus               = 0;
                $total_bonus                = 0;
                $rank_qualified             = '';

                // Check Qualified Bonus
                // --------------------------------------------------
                if (!$bonus_qualified_stat) {
                    if ($row->rank == RANK_STAR_AGENT) {
                        if (!$row->{"count_" . RANK_STAR_AGENT} && !$row->{"count_" . RANK_SUPER_AGENT}) {
                            $bonus_qualified_stat       = TRUE;
                            $rank_qualified             = $cfg_rank[$row->rank];
                            $amount_bonus               = isset($cfg_rank_qualified[RANK_STAR_AGENT]['amount']) ? $cfg_rank_qualified[RANK_STAR_AGENT]['amount'] : 0;
                        }
                    }

                    if ($row->rank == RANK_SUPER_AGENT) {
                        if ($row->{"count_" . RANK_SUPER_AGENT} == 0) {
                            $bonus_qualified_stat       = TRUE;
                            $rank_qualified             = $cfg_rank[$row->rank];
                            $amount_bonus               = isset($cfg_rank_qualified[RANK_SUPER_AGENT]['amount']) ? $cfg_rank_qualified[RANK_SUPER_AGENT]['amount'] : 0;
                        }
                    }
                }

                if ($bonus_qualified_stat) {
                    $total_bonus        = $amount_bonus * $row->total_omzet_group;
                }


                echo "No. " . ($no) . br();
                echo '-------------------------------------------------------' . br();
                echo 'ID Grade    : ' . $row->id . br();
                echo '-------------------------------------------------------' . br();
                echo 'ID Member   : ' . $row->id_member . br();
                echo 'Username    : ' . $row->username . br();
                echo 'Name        : ' . $row->name . br();
                echo 'Rank        : ' . strtoupper($cfg_rank[$row->rank]) . br();
                echo '-------------------------------------------------------' . br();
                echo 'Omzet         = ' . ddm_accounting($row->total_omzet) . br();
                echo 'Omzet Group   = ' . ddm_accounting($row->total_omzet_group) . br();
                echo 'BV            = ' . ddm_accounting($row->total_pv) . br();
                echo 'BV Group      = ' . ddm_accounting($row->total_pv_group) . br();
                echo 'Amount        = ' . ddm_accounting($row->total_amount) . br();
                echo 'Amount Group  = ' . ddm_accounting($row->total_amount_group) . br();
                echo 'Qty           = ' . ddm_accounting($row->total_qty) . ' Liter' . br();
                echo 'Qty Group     = ' . ddm_accounting($row->total_qty_group) . ' Liter' . br();
                echo '-------------------------------------------------------' . br();
                echo 'Star Agent    = ' . ddm_accounting($row->count_star) . ' Agen' . br();
                echo 'Super Agent   = ' . ddm_accounting($row->count_super) . ' Agen' . br();
                echo '-------------------------------------------------------' . br();
                echo 'Bonus Q       = ' . ($bonus_qualified_stat ? 'Ya' : 'Tidak')  . br();
                if ($bonus_qualified_stat) {

                    echo 'Bonus Percent = ' . ddm_accounting($amount_bonus * 100) . '%' . br();
                    echo 'Total Amount  = ' . ddm_accounting($total_bonus) .  br();
                    echo '-------------------------------------------------------' . br();
                }
                // if ($package_qualified_stat) {
                //     echo 'Rank     = ' . $package_qualified . br();
                //     echo 'Ancestry Up = ' . json_encode($ancestry) . br();
                // }
                echo '====================================================' . br(3);

                if (!$debug) {
                    if ($bonus_qualified_stat) {
                        $data_bonus        = array(
                            'id_bonus'      => 'B' . date('YmdHis') . ddm_generate_rand_string(4, 'num'),
                            'id_member'     => $row->id_member,
                            'type'          => BONUS_GA,
                            'desc'          => 'Komisi Group Agen (KGA) Rank ' . $rank_qualified . ' dari Omzet Group sebesar ' . ddm_accounting($row->total_omzet_group, 'Rp'),
                            'amount'        => $total_bonus,
                            'status'        => 1,
                            'datecreated'   => $datetime,
                            'datemodified'  => $datetime
                        );
                        ddm_save_bonus($row->id_member, $data_bonus, $debug);
                    }
                }

                // if (!$debug) {
                //     $data_grade = array();

                //     if ($package_qualified_stat) {
                //         $data_grade['rank']                 = $package_qualified;
                //         $data_grade['rank_qualified']       = $package_qualified;
                //         $data_package['package']            = $package_qualified;
                //         $update_package = $this->Model_Member->update_data_member($row->id_member, $data_package);
                //     }

                //     if ($data_grade) {
                //         $data_grade['datemodified'] = $curdate;
                //         $save_grade = $this->Model_Member->update_data_member_grade($row->id, $data_grade);

                //         if ($save_grade && $package_qualified_stat) {

                //             if ($ancestry) {
                //                 $gen = 1;
                //                 // Update Anchestry for 5 Gen
                //                 foreach ($ancestry as $key => $_id) {
                //                     $data_grade = array();
                //                     if ($_id == 1) {
                //                         continue;
                //                     }

                //                     ddm_update_count_package_downline($_id, $month, $year, $package_qualified);

                //                     $gen++;
                //                 }
                //             }
                //         }
                //     }
                // }

                $no++;
            }
        } else {
            echo br(2) . " Data Omzet tidak ditemukan." . br();
        }

        echo br(2) . '----------------------------------------------------' . br();
        $this->_end_cron('Calculate_Bonus_KGA', $debug);
    }

    /**
     * CRON JOB : Bonus Personal
     *
     * @param    String     $code       default ''
     * @param    boolean    $debug      default true
     * @param    Date       $date       default current date
     * @param    boolean    $calc_wd    default false
     * @author   Yuda
     */
    function bonus_weekly($keycode = '', $debug = true,  $date = '', $period = 'weekly', $calc_withdraw = false)
    {
        set_time_limit(0);
        if (!$keycode) die();

        $keycode    = trim($keycode);
        $validate   = password_verify($keycode, $this->codecron);

        if (!$validate) die();

        $datenow        = date('Y-m-d', strtotime('-1 day'));
        if (date('Hi') >= 2330) {
            $datenow    = date('Y-m-d');
        }

        $date           = $date ? date('Y-m-d', strtotime($date)) : $datenow;
        $start_date     = date('Y-m-d', strtotime($date));
        $end_date       = date('Y-m-d', strtotime($date));

        if (strtolower($period) == 'month') {
            $start_date = date('Y-m-01', strtotime($date));
            $end_date   = date('Y-m-t', strtotime($start_date));
        }

        if (strtolower($period) == 'weekly') {
            $start_date = date('Y-m-d', strtotime($date . '-6 day'));
            $end_date   = date('Y-m-d', strtotime($date));
        }

        $datetime       = $end_date . ' ' . date('23:59:s');
        $daterange      = array('start_date' => $start_date, 'end_date' => $end_date);
        $curdate        = date('Y-m-d H:i:s');

        $this->_start_cron('Bonus', $debug);
        echo '====================================================' . br();
        echo ' Calculate Bonus' . br();
        echo '----------------------------------------------------' . br();
        echo ' Function    : ' . ($debug ? 'Debug ' : 'Save ') . br();
        echo ' Period      : ' . ucwords(strtolower($period)) . br();
        echo '----------------------------------------------------' . br();
        echo ' Datetime    : ' . $datetime . br();
        echo ' Start Date  : ' . $start_date . br();
        echo ' End Date    : ' . $end_date . br();
        echo '----------------------------------------------------' . br();

        $condition      = ' AND %status% != "register" AND A.calc_bonus_personal = 0 AND %date% >= "' . $start_date . '" AND %date% <= "' . $end_date . '"';
        if ($data_omzet = $this->Model_Member->get_all_member_omzet(0, 0, $condition, '%datecreated% ASC')) {
            $iResult    = count($data_omzet);
            $no = 1;
            echo ' Total Data  : ' . $iResult . br();
            echo '----------------------------------------------------' . br(2);
            foreach ($data_omzet as $k => $row) {
                echo "No. " . ($no) . br();
                echo '-------------------------------------------------------' . br();
                echo 'ID Member   : ' . $row->id_member . br();
                echo 'Username    : ' . $row->username . br();
                echo 'Name        : ' . $row->name . br();
                echo 'ID Sponsor  : ' . $row->sponsor . br();
                echo '-------------------------------------------------------' . br();
                echo 'ID Order    : ' . $row->id_order . br();
                echo 'Omzet       = ' . ddm_accounting($row->omzet, 'Rp') . br();
                echo 'Qty         = ' . ddm_accounting($row->qty) . br();
                echo 'Type        = ' . ucwords($row->status) . br();
                echo '-------------------------------------------------------' . br(2);

                // Calculate Bonus Member
                echo ' Calculate Bonus Personal ' . br();
                echo '-------------------------------------------------------' . br();
                $bonus_personal     = ddm_calculate_personal_bonus($row->id_member, $row->id_order, $datetime, $daterange, $debug);

                if (!$debug) {
                    // Update Status Bonus Member Omzet 
                    $data_update = array('calc_bonus_personal' => 1, 'datemodified' => $curdate);
                    $this->Model_Member->update_data_member_omzet($row->id, $data_update);
                }

                echo br(2);
                echo '====================================================' . br();
                echo '====================================================' . br(3);

                $no++;
            }
        } else {
            echo br(2) . " Data Omzet tidak ditemukan." . br();
        }

        if ($debug) {
            echo br(2) . '----------------------------------------------------' . br();
            if ($data_omzet) {
                echo '  <a href="' . base_url('cron/bonus_weekly/DDMcron/0/' . $date . '/' . $period) . '">Eksekusi Bonus ini </a>' . br();
            } else {
                echo '  <a href="' . base_url('commission/bonus') . '" >Kembali </a>' . br();
            }
            echo '----------------------------------------------------' . br();
        } else {
            echo br(2) . '----------------------------------------------------' . br();
            echo '  <a href="' . base_url('commission/bonus') . '" >Kembali </a>' . br();
            echo '----------------------------------------------------' . br();
        }

        echo br(2) . '----------------------------------------------------' . br();
        $this->_end_cron('Bonus', $debug);
        if ($calc_withdraw) {
            $this->withdraw($keycode, $debug, date('Y-m-d', strtotime($datetime)));
        }
    }

    /**
     * CRON JOB : Bonus Referral & Bonus Development
     *
     * @param    String     $code       default ''
     * @param    boolean    $debug      default true
     * @param    Date       $date       default current date
     * @param    boolean    $calc_wd    default false
     * @author   Yuda
     */
    function bonus_monthly($keycode = '', $debug = true, $month = '', $calc_withdraw = false)
    {
        set_time_limit(0);
        if (!$keycode) die();

        $keycode    = trim($keycode);
        $validate   = password_verify($keycode, $this->codecron);

        if (!$validate) die();

        $monthnow    = date('Y-m');
        if (date('j') <= 5) {
            $day        = date('j');
            $monthnow   = date('Y-m', strtotime('-' . $day . ' day'));
        }

        $month      = $month ? date('Y-m', strtotime($month)) : $monthnow;
        $datetime   = date('Y-m-t', strtotime($month)) . ' ' . date('23:59:1') . rand(0, 9);
        $curdate    = date('Y-m-d H:i:s');

        $this->_start_cron('Bonus', $debug);
        echo '====================================================' . br();
        echo ' Calculate Bonus' . br();
        echo '----------------------------------------------------' . br();
        echo ' Function    : ' . ($debug ? 'Debug ' : 'Save ') . br();
        echo ' Month       : ' . $month . br();
        echo ' Datetime    : ' . $datetime . br();
        echo '----------------------------------------------------' . br();

        $condition      = ' AND %status% != "register" AND A.calc_bonus = 0 AND DATE_FORMAT(%date%, "%Y-%m") = "' . $month . '" AND %datecreated% <= "' . $datetime . '"';
        if ($data_omzet = $this->Model_Member->get_all_member_omzet(0, 0, $condition, '%datecreated% ASC')) {
            $iResult    = count($data_omzet);
            $no = 1;
            echo ' Total Data  : ' . $iResult . br();
            echo '----------------------------------------------------' . br(2);
            foreach ($data_omzet as $k => $row) {
                echo "No. " . ($no) . br();
                echo '-------------------------------------------------------' . br();
                echo 'ID Member   : ' . $row->id_member . br();
                echo 'Username    : ' . $row->username . br();
                echo 'Name        : ' . $row->name . br();
                echo 'ID Sponsor  : ' . $row->sponsor . br();
                echo '-------------------------------------------------------' . br();
                echo 'ID Order    : ' . $row->id_order . br();
                echo 'Omzet       = ' . ddm_accounting($row->omzet, 'Rp') . br();
                echo 'Qty         = ' . ddm_accounting($row->qty) . br();
                echo 'Type        = ' . ucwords($row->status) . br();
                echo '-------------------------------------------------------' . br(2);

                // Calculate Bonus Member
                if ($row->status == 'perdana') {
                    echo ' Calculate Bonus Referral ' . br();
                    echo '-------------------------------------------------------' . br();
                    $bonus_sponsor  = ddm_calculate_sponsor_bonus($row->id_member, $row->sponsor, $row->omzet, $datetime, $debug);
                } else {
                    echo ' Calculate Bonus Development ' . br();
                    echo '-------------------------------------------------------' . br();
                    $bonus_sponsor  = ddm_calculate_development_bonus($row->id_member, $row->sponsor, $row->omzet, $datetime, $debug);
                }

                if (!$debug) {
                    // Update Status Bonus Member Omzet 
                    $data_update = array('calc_bonus' => 1, 'datemodified' => $curdate);
                    $this->Model_Member->update_data_member_omzet($row->id, $data_update);
                }

                echo br(2);
                echo '====================================================' . br();
                echo '====================================================' . br(3);

                $no++;
            }
        } else {
            echo br(2) . " Data Omzet tidak ditemukan." . br();
        }

        if ($debug) {
            echo br(2) . '----------------------------------------------------' . br();
            if ($data_omzet) {
                echo '  <a href="' . base_url('cron/bonus_monthly/DDMcron/0/' . $month . '/0') . '">Eksekusi Bonus ini </a>' . br();
            } else {
                echo '  <a href="' . base_url('commission/bonus') . '" >Kembali </a>' . br();
            }
            echo '----------------------------------------------------' . br();
        } else {
            echo br(2) . '----------------------------------------------------' . br();
            echo '  <a href="' . base_url('commission/bonus') . '" >Kembali </a>' . br();
            echo '----------------------------------------------------' . br();
        }

        echo br(2) . '----------------------------------------------------' . br();
        $this->_end_cron('Bonus', $debug);
        if ($calc_withdraw) {
            $this->withdraw($keycode, $debug, date('Y-m-d', strtotime($datetime)));
        }
    }

    /**
     * CRON JOB : Withdraw
     *
     * @param    String     $code       default ''
     * @param    boolean    $debug      default true
     * @param    Date       $date       default current date
     * @author   Yuda
     */
    function withdraw($keycode = '', $debug = true, $date = '')
    {
        set_time_limit(0);

        if (!$keycode) die();

        $keycode    = trim($keycode);
        $validate   = ddm_hash_verify($keycode, $this->codecron);

        if (!$validate) die();

        $date                   = $date ? date('Y-m-d', strtotime($date)) : date('Y-m-d', strtotime('-1 day'));
        $datetime               = $date . ' ' . date('23:59:5') . rand(0, 9);

        // Config Withdraw
        $withdraw_minimal       = get_option('setting_withdraw_minimal');
        $withdraw_minimal       = $withdraw_minimal ? $withdraw_minimal : 0;
        $admin_fee              = get_option('setting_withdraw_fee');
        $admin_fee              = isset($admin_fee) ? $admin_fee : 0;
        $currency               = config_item('currency');
        $cron_log               = '';

        $this->_start_cron('Withdraw', $debug);
        echo '-------------------------------------------------------' . br();
        echo "                    Withdrawal" . br();
        echo '-------------------------------------------------------' . br();
        echo ' Function     : ' . ($debug ? 'View' : 'Save') . br();
        echo ' Datetime     : ' . $datetime . br();
        echo '-------------------------------------------------------' . br();
        echo ' WD Minimal   : ' . ddm_accounting($withdraw_minimal) . br();
        echo ' Admin Fee    : ' . ddm_accounting($admin_fee) . br();
        echo '-------------------------------------------------------' . br();

        $condition  = ' AND %wd_status% = 0 AND %status% = ' . ACTIVE . ' ';
        $data       = $this->Model_Bonus->get_all_total_ewallet_member(0, 0, $condition, '', ' %total% >= ' . $withdraw_minimal, $date);

        if ($data && $withdraw_minimal) {
            echo ' Total Data   : ' . count($data) . br();
            echo '-------------------------------------------------------' . br(3);
            $no = 0;
            foreach ($data as $row) {
                $wd_nominal             = $row->total_deposite;
                if ($withdraw_minimal > $wd_nominal) {
                    continue;
                }
                if (!$row->bank || !$row->bill) {
                    continue;
                }

                // $tax                    = ddm_calc_tax($wd_nominal, $row->npwp);
                $bill_name              = $row->bill_name ? $row->bill_name : $row->name;
                $tax                    = 0;
                $amount_receipt         = $wd_nominal - $tax - $admin_fee;

                echo "No. " . ($no += 1) . br();
                echo '-------------------------------------------------------' . br();
                echo 'ID Member             : ' . $row->id . br();
                echo 'Username              : ' . $row->username . ' - ' . $row->name . br();
                echo 'Bank                  : ' . $row->bank . br();
                echo 'Bill                  : ' . $row->bill . ' (' . $bill_name . ')' . br();
                echo '-------------------------------------------------------' . br();
                echo 'Nominal WD            = ' . ddm_accounting($wd_nominal, 'Rp') . br();
                echo 'Biaya Transfer        = ' . ddm_accounting($admin_fee, 'Rp') . br();
                echo '-------------------------------------------------------' . br();
                echo 'WD Diterima           = ' . ddm_accounting($amount_receipt, 'Rp') . br();
                echo '-------------------------------------------------------' . br(3);

                if (!$debug) {
                    // -------------------------------------------------
                    // Begin Transaction
                    // -------------------------------------------------
                    $this->db->trans_begin();

                    $data_withdraw          = array(
                        'id_member'         => $row->id,
                        'bank'              => $row->bank,
                        'bill'              => $row->bill,
                        'bill_name'         => $bill_name,
                        'nominal'           => $wd_nominal,
                        'nominal_receipt'   => $amount_receipt,
                        'tax'               => $tax,
                        'admin_fund'        => $admin_fee,
                        'datecreated'       => $datetime,
                        'datemodified'      => $datetime
                    );
                    if (!$withdraw_id  = $this->Model_Bonus->save_data_withdraw($data_withdraw)) {
                        $this->db->trans_rollback();
                        $separated          = $cron_log ? ', ' : '';
                        $cron_log          .= $separated . 'ID member : ' . $row->id . ' (Withdraw Failed Save)';
                        continue;
                    }

                    $data_ewallet = array(
                        'id_member'     => $row->id,
                        'id_source'     => $withdraw_id,
                        'amount'        => $wd_nominal,
                        'source'        => 'withdraw',
                        'type'          => 'OUT',
                        'status'        => 1,
                        'description'   => 'Withdraw tgl ' . date('Y-m-d', strtotime($datetime)) . ' ' . ddm_accounting($wd_nominal, $currency),
                        'datecreated'   => $datetime
                    );
                    if (!$wallet_id  = $this->Model_Bonus->save_data_ewallet($data_ewallet)) {
                        $this->db->trans_rollback();
                        $separated          = $cron_log ? ', ' : '';
                        $cron_log          .= $separated . 'ID Withdraw : ' . $withdraw_id . ' (Wallet Failed Save)';
                        continue;
                    }

                    // -------------------------------------------------
                    // Commit or Rollback Transaction
                    // -------------------------------------------------
                    if ($this->db->trans_status() === FALSE) {
                        // Rollback Transaction
                        $this->db->trans_rollback();
                    } else {
                        // Commit Transaction
                        $this->db->trans_commit();
                        // Complete Transaction
                        $this->db->trans_complete();
                    }
                }
            }
        } else {
            $cron_log = 'Data Deposite tidak ditemukan';
            echo br(1) . " " . $cron_log . br();
        }

        if (!$debug) {
            echo br(2) . '----------------------------------------------------' . br();
            echo '  <a href="' . base_url('commission/bonus') . '" >Kembali </a>' . br();
            echo '----------------------------------------------------' . br();
        }

        echo br(2) . '----------------------------------------------------' . br();
        $this->_end_cron('Withdraw', $debug, $cron_log);
    }
}
