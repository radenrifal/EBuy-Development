<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Member Controller.
 *
 * @class     Member
 * @author    Yuda
 * @version   1.0.0
 */
class Member extends DDM_Controller
{
    /**
     * Constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    // =============================================================================================
    // LIST DATA MEMBER
    // =============================================================================================

    /**
     * Member List Data function.
     */
    function memberlistsdata()
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array('status' => 'access_denied', 'data' => '');
            die(json_encode($data));
        }

        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);

        $condition          = 'WHERE %type% = ' . MEMBER . ' AND %status% = ' . ACTIVE;
        $order_by           = '';
        $iTotalRecords      = 0;

        $sExport            = $this->input->get('export');
        $sAction            = ddm_isset($_REQUEST['sAction'], '');
        $sAction            = ddm_isset($sExport, $sAction);

        $search_method      = 'post';
        if ($sAction == 'download_excel') {
            $search_method  = 'get';
        }

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);
        $sEcho              = intval($_REQUEST['sEcho']);
        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_username         = $this->input->$search_method('search_username');
        $s_username         = ddm_isset($s_username, '');
        $s_name             = $this->input->$search_method('search_name');
        $s_name             = ddm_isset($s_name, '');
        $s_sponsor          = $this->input->$search_method('search_sponsor');
        $s_sponsor          = ddm_isset($s_sponsor, '');
        $s_package          = $this->input->$search_method('search_package');
        $s_package          = ddm_isset($s_package, '');
        $s_status           = $this->input->$search_method('search_status');
        $s_status           = ddm_isset($s_status, '');
        $s_date_min         = $this->input->$search_method('search_datecreated_min');
        $s_date_min         = ddm_isset($s_date_min, '');
        $s_date_max         = $this->input->$search_method('search_datecreated_max');
        $s_date_max         = ddm_isset($s_date_max, '');

        if (!empty($s_username)) {
            $condition .= str_replace('%s%', $s_username, ' AND %username% LIKE "%%s%%"');
        }
        if (!empty($s_name)) {
            $condition .= str_replace('%s%', $s_name, ' AND %name% LIKE "%%s%%"');
        }
        if (!empty($s_sponsor)) {
            $condition .= str_replace('%s%', $s_sponsor, ' AND %sponsor_username% LIKE "%%s%%"');
        }
        if (!empty($s_package)) {
            $condition .= str_replace('%s%', $s_package, ' AND %package% LIKE "%%s%%"');
        }
        if (!empty($s_status)) {
            if ($s_status == 'member') {
                $condition .= str_replace('%s%', 0, ' AND %as_stockist% = %s%');
            } else {
                $condition .= str_replace('%s%', 0, ' AND %as_stockist% > %s%');
            }
        }
        if (!empty($s_date_min)) {
            $condition .= ' AND %datecreated% >= "' . $s_date_min . '"';
        }
        if (!empty($s_date_max)) {
            $condition .= ' AND %datecreated% <= "' . $s_date_max . '"';
        }

        if ($column == 1) {
            $order_by .= '%username% ' . $sort;
        } elseif ($column == 2) {
            $order_by .= '%name% ' . $sort;
        } elseif ($column == 3) {
            $order_by .= '%sponsor_username% ' . $sort;
        } elseif ($column == 4) {
            $order_by .= '%package% ' . $sort;
        } elseif ($column == 5) {
            $order_by .= '%as_stockist% ' . $sort;
        } elseif ($column == 6) {
            $order_by .= '%datecreated% ' . $sort;
        } elseif ($column == 7) {
            $order_by .= '%lastlogin% ' . $sort;
        }

        if ($is_admin) {
            $member_list    = $this->Model_Member->get_all_member_data($limit, $offset, $condition, $order_by);
        } else {
            $member_list    = array();
        }

        $records            = array();
        $records["aaData"]  = array();

        if (!empty($member_list)) {
            $iTotalRecords  = ddm_get_last_found_rows();
            $cfg_type       = config_item('member_type');
            $access         = TRUE;
            if ($staff = ddm_get_current_staff()) {
                if ($staff->access == 'partial') {
                    $role   = array();
                    if ($staff->role) {
                        $role = $staff->role;
                    }

                    foreach (array(STAFF_ACCESS4) as $val) {
                        if (empty($role) || !in_array($val, $role))
                            $access = FALSE;
                    }
                }
            }
            $i = $offset + 1;
            foreach ($member_list as $row) {
                $id             = ddm_encrypt($row->id);
                $id_sponsor     = ddm_encrypt($row->sponsor);
                $username       = ddm_strong(strtolower($row->username));
                $username       = ($row->as_stockist >= 1 ? '<span class="text-success">' . $username . '</span>' : $username);
                $username       = ($is_admin ? '<a href="' . base_url('profile/' . $id) . '">' . $username . '</a>' : $username);
                $name           = ddm_strong(strtoupper($row->name));

                $sponsor        = strtolower($row->sponsor_username);
                $sponsor        = ($is_admin) ? '<a href="' . base_url('profile/' . $id_sponsor) . '">' . $sponsor . '</a>' : $sponsor;

                if ($row->as_stockist == 1) {
                    $status     = '<span class="badge badge-sm badge-primary">STOCKIST</span>';
                } else {
                    $status     = '<span class="badge badge-sm badge-success">MEMBER</span>';
                }

                if ($row->package == MEMBER_AGENT) {
                    $package    = '<span class="badge badge-sm badge-primary">AGENT</span>';
                } elseif ($row->package == MEMBER_MASTER_AGENT) {
                    $package    = '<span class="badge badge-sm badge-success">MASTER AGENT</span>';
                }

                $last_login     = '-';
                if ($row->last_login != '0000-00-00 00:00:00') {
                    $last_login     = date('d M y H:i', strtotime($row->last_login));
                }

                $id             = ddm_encrypt($row->id);
                $banned         = '<a href="' . base_url('member/asbanned/' . $id) . '" data-container="registration_list" class="btn btn-sm  btn-danger btn-tooltip asbanned" title="Banned" style="margin:2px 0px"><i class="fa fa-trash"></i></a>';
                $assume         = '<a href="' . base_url('backend/assume/' . $id) . '" class="btn btn-sm btn-outline-warning btn-tooltip" title="Assume"><i class="fa fa-user"></i></a>';
                $btn_gen        = '<a href="' . base_url('member/generation/' . strtolower($row->username)) . '" class="btn btn-sm btn-outline-primary btn-tooltip" title="Generation"><i class="fa fa-sitemap"></i></a>';

                $records["aaData"][] = array(
                    ddm_center($i),
                    ddm_center($username),
                    $name,
                    ddm_center($sponsor),
                    ddm_center($package),
                    $row->phone,
                    $row->email,
                    ddm_center(date('j M y @H:i', strtotime($row->datecreated))),
                    ddm_center($last_login),
                    ddm_center((($is_admin && $access) ? $btn_gen . $assume . $banned : '')),
                );
                $i++;
            }
        }

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }

    /**
     * Register List Data function.
     */
    function registerlistdata()
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array('status' => 'access_denied', 'data' => '');
            die(json_encode($data));
        }

        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);

        $condition          = 'WHERE %type% = ' . MEMBER;
        if (!$is_admin) {
            $condition     .= ' AND %id_member% = ' . $current_member->id;
        }
        $order_by           = '';
        $iTotalRecords      = 0;

        $sExport            = $this->input->get('export');
        $sAction            = ddm_isset($_REQUEST['sAction'], '');
        $sAction            = ddm_isset($sExport, $sAction);

        $search_method      = 'post';
        if ($sAction == 'download_excel') {
            $search_method  = 'get';
        }

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);
        $sEcho              = intval($_REQUEST['sEcho']);

        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_member           = $this->input->$search_method('search_member');
        $s_member           = ddm_isset($s_member, '');
        $s_sponsor          = $this->input->$search_method('search_sponsor');
        $s_sponsor          = ddm_isset($s_sponsor, '');
        $s_username         = $this->input->$search_method('search_username');
        $s_username         = ddm_isset($s_username, '');
        $s_name             = $this->input->$search_method('search_name');
        $s_name             = ddm_isset($s_name, '');
        $s_omzet_min        = $this->input->$search_method('search_omzet_min');
        $s_omzet_min        = ddm_isset($s_omzet_min, '');
        $s_omzet_max        = $this->input->$search_method('search_omzet_max');
        $s_omzet_max        = ddm_isset($s_omzet_max, '');
        $s_access           = $this->input->$search_method('search_access');
        $s_access           = ddm_isset($s_access, '');
        $s_status           = $this->input->$search_method('search_status');
        $s_status           = ddm_isset($s_status, '');
        $s_date_min         = $this->input->$search_method('search_datecreated_min');
        $s_date_min         = ddm_isset($s_date_min, '');
        $s_date_max         = $this->input->$search_method('search_datecreated_max');
        $s_date_max         = ddm_isset($s_date_max, '');
        $s_dateconfirm_min  = $this->input->$search_method('search_dateconfirmconfirm_min');
        $s_dateconfirm_min  = ddm_isset($s_dateconfirm_min, '');
        $s_dateconfirm_max  = $this->input->$search_method('search_dateconfirmconfirm_max');
        $s_dateconfirm_max  = ddm_isset($s_dateconfirm_max, '');

        if (!empty($s_member)) {
            $condition .= str_replace('%s%', $s_member, ' AND %member% LIKE "%%s%%"');
        }
        if (!empty($s_sponsor)) {
            $condition .= str_replace('%s%', $s_sponsor, ' AND %sponsor% LIKE "%%s%%"');
        }
        if (!empty($s_username)) {
            $condition .= str_replace('%s%', $s_username, ' AND %downline% LIKE "%%s%%"');
        }
        if (!empty($s_name)) {
            $condition .= str_replace('%s%', $s_name, ' AND %name% LIKE "%%s%%"');
        }
        if (!empty($s_access)) {
            $condition .= str_replace('%s%', $s_access, ' AND %access% = "%s%"');
        }
        if (!empty($s_omzet_min)) {
            $condition .= ' AND %nominal% >= ' . $s_omzet_min . '';
        }
        if (!empty($s_omzet_max)) {
            $condition .= ' AND %nominal% <= ' . $s_omzet_max . '';
        }
        if (!empty($s_date_min)) {
            $condition .= ' AND %datecreated% >= "' . $s_date_min . '"';
        }
        if (!empty($s_date_max)) {
            $condition .= ' AND %datecreated% <= "' . $s_date_max . '"';
        }
        if (!empty($s_status)) {
            if ($s_status == 'cancelled') {
                $condition .= str_replace('%s%', 2, ' AND %status% = %s%');
            }
            if ($s_status == 'confirmed') {
                $condition .= str_replace('%s%', 1, ' AND %status% = %s%');
            }
            if ($s_status == 'pending') {
                $condition .= str_replace('%s%', 0, ' AND %status% = %s%');
            }
        }
        if (!empty($s_dateconfirm_min)) {
            $condition .= ' AND %dateconfirm% >= "' . $s_dateconfirm_min . '"';
            $condition .= str_replace('%s%', 1, ' AND %status% = %s%');
        }
        if (!empty($s_dateconfirm_max)) {
            $condition .= ' AND %dateconfirm% <= "' . $s_dateconfirm_max . '"';
            $condition .= str_replace('%s%', 1, ' AND %status% = %s%');
        }

        if ($is_admin) {
            if ($column == 1) {
                $order_by .= '%member% ' . $sort;
            } elseif ($column == 2) {
                $order_by .= '%sponsor% ' . $sort;
            } elseif ($column == 3) {
                $order_by .= '%downline% ' . $sort;
            } elseif ($column == 4) {
                $order_by .= '%name% ' . $sort;
            } elseif ($column == 5) {
                $order_by .= '%nominal% ' . $sort;
            } elseif ($column == 6) {
                $order_by .= '%status% ' . $sort;
            } elseif ($column == 7) {
                $order_by .= '%access% ' . $sort;
            } elseif ($column == 8) {
                $order_by .= '%datecreated% ' . $sort;
            } elseif ($column == 9) {
                $order_by .= '%dateconfirm% ' . $sort;
                $condition .= str_replace('%s%', 1, ' AND %status% = %s%');
            }
        } else {
            if ($column == 1) {
                $order_by .= '%member% ' . $sort;
            } elseif ($column == 2) {
                $order_by .= '%sponsor% ' . $sort;
            } elseif ($column == 3) {
                $order_by .= '%downline% ' . $sort;
            } elseif ($column == 4) {
                $order_by .= '%name% ' . $sort;
            } elseif ($column == 5) {
                $order_by .= '%nominal% ' . $sort;
            } elseif ($column == 6) {
                $order_by .= '%status% ' . $sort;
            } elseif ($column == 7) {
                $order_by .= '%datecreated% ' . $sort;
            } elseif ($column == 8) {
                $order_by .= '%dateconfirm% ' . $sort;
                $condition .= str_replace('%s%', 1, ' AND %status% = %s%');
            }
        }

        $data_list          = $this->Model_Member->get_all_member_confirm($limit, $offset, $condition, $order_by);
        $records            = array();
        $records["aaData"]  = array();

        if (!empty($data_list)) {
            $iTotalRecords  = ddm_get_last_found_rows();
            $i = $offset + 1;
            foreach ($data_list as $row) {
                $id             = ddm_encrypt($row->id);
                $id_member      = ddm_encrypt($row->id_member);
                $id_sponsor     = ddm_encrypt($row->id_sponsor);
                $id_downline    = ddm_encrypt($row->id_downline);
                $member         = strtolower($row->member);
                $member         = ($is_admin) ? '<a href="' . base_url('profile/' . $id_member) . '">' . $member . '</a>' : $member;
                $sponsor        = strtolower($row->sponsor);
                $sponsor        = ($is_admin) ? '<a href="' . base_url('profile/' . $id_sponsor) . '">' . $sponsor . '</a>' : $sponsor;
                $downline       = ddm_strong(strtolower($row->downline));
                $downline       = ($is_admin ? '<a href="' . base_url('profile/' . $row->id) . '">' . $downline . '</a>' : $downline);
                $name           = ddm_strong(strtoupper($row->name));

                $status = '';
                if ($row->status == 0) {
                    $status = '<span class="badge badge-default">PENDING</span>';
                } elseif ($row->status == 1) {
                    $status = '<span class="badge badge-success">CONFIRMED</span>';
                } elseif ($row->status == 2) {
                    $status = '<span class="badge badge-danger">CANCELLED</span>';
                }

                $datatable = array(
                    ddm_center($i),
                    ddm_center($member),
                    ddm_center($sponsor),
                    ddm_center($downline),
                    $name,
                    ddm_accounting($row->nominal, '', TRUE),
                    ddm_center($status),
                );

                $btn_confirm        = '';
                if ($is_admin) {
                    $access         = '';
                    if ($row->access == 'admin') {
                        $access = '<span class="badge badge-success">ADMIN</span>';
                    }
                    if ($row->access == 'member') {
                        $access = '<span class="badge badge-primary">AGEN</span>';
                    }
                    if ($row->access == 'ewallet') {
                        $access = '<span class="badge badge-primary">DEPOSITE BONUS</span>';
                    }
                    if ($row->access == 'referral') {
                        $access = '<span class="badge badge-warning">REFERRAL</span>';
                    }
                    if ($row->access == 'shop') {
                        $access = '<span class="badge badge-info">SHOP</span>';
                    }
                    $datatable[]    = ddm_center($access);

                    if ($row->status == 0 && $row->access != 'shop') {

                        // $btn_confirm = '<a href="javascript:;" 
                        //                     data-url="'.base_url('member/memberconfirm/'.$id).'" 
                        //                     data-username="'.$row->downline.'"
                        //                     data-name="'.$row->name.'"
                        //                     data-nominal="'.ddm_accounting($row->nominal).'"
                        //                     class="btn btn-sm btn-block btn-primary btn-tooltip btn-member-confirm" 
                        //                     title="Konfirmasi Pendaftaran"><i class="fa fa-check"></i> Confirm</a>';
                    } else if ($row->status == 1) {
                        $btn_confirm = '<a href="javascript:;" class="btn btn-sm btn-outline-success btn-tooltip" title="Confirmed" disabled=""><i class="fa fa-check"></i></a>';
                    } else if ($row->status == 2) {
                        $btn_confirm = '<a href="javascript:;" class="btn btn-sm btn-outline-danger btn-tooltip" title="Cancelled" disabled=""><i class="fa fa-times"></i></a>';
                    }
                }

                $dateconfirm = ($row->status == 1) ? date('Y-m-d @H:i', strtotime($row->datemodified))  : '-';

                $datatable[] = ddm_center(date('Y-m-d @H:i', strtotime($row->datecreated)));
                $datatable[] = ddm_center($dateconfirm);
                $datatable[] = ddm_center($btn_confirm);

                $records["aaData"][] = $datatable;
                $i++;
            }
        }

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }

    /**
     * Generation Member List Data function.
     */
    function generationdata($username = '')
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array('status' => 'access_denied', 'data' => '');
            die(json_encode($data));
        }

        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);
        $memberdata         = $current_member;

        if ($is_admin && $username) {
            $username       = trim(strtolower($username));
            if ($getmember = $this->Model_Member->get_member_by('login', $username)) {
                $memberdata = $getmember;
            }
        }

        $my_gen             = $memberdata->level;
        $max_gen            = $my_gen + 3;
        $condition          = ' AND %tree% LIKE "' . $memberdata->tree . '-%" AND %level% <= ' . $max_gen;
        $order_by           = '%level% ASC, %username% ASC';
        $iTotalRecords      = 0;

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);

        $sAction            = ddm_isset($_REQUEST['sAction'], '');
        $sEcho              = intval($_REQUEST['sEcho']);
        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_name             = $this->input->post('search_name');
        $s_name             = ddm_isset($s_name, '');
        $s_username         = $this->input->post('search_username');
        $s_username         = ddm_isset($s_username, '');
        $s_sponsor          = $this->input->post('search_sponsor');
        $s_sponsor          = ddm_isset($s_sponsor, '');
        $s_generation       = $this->input->post('search_generation');
        $s_generation       = ddm_isset($s_generation, '');
        $s_perdana_min      = $this->input->post('search_perdana_min');
        $s_perdana_min      = ddm_isset($s_perdana_min, '');
        $s_perdana_max      = $this->input->post('search_perdana_max');
        $s_perdana_max      = ddm_isset($s_perdana_max, '');
        $s_ro_min           = $this->input->post('search_ro_min');
        $s_ro_min           = ddm_isset($s_ro_min, '');
        $s_ro_max           = $this->input->post('search_ro_max');
        $s_ro_max           = ddm_isset($s_ro_max, '');
        $s_date_min         = $this->input->post('search_datecreated_min');
        $s_date_min         = ddm_isset($s_date_min, '');
        $s_date_max         = $this->input->post('search_datecreated_max');
        $s_date_max         = ddm_isset($s_date_max, '');

        if (!empty($s_name)) {
            $condition .= str_replace('%s%', $s_name, ' AND %name% LIKE "%%s%%"');
        }
        if (!empty($s_username)) {
            $condition .= str_replace('%s%', $s_username, ' AND %username% LIKE "%%s%%"');
        }
        if (!empty($s_sponsor)) {
            $condition .= str_replace('%s%', $s_sponsor, ' AND %sponsor_username% LIKE "%%s%%"');
        }
        if (!empty($s_generation)) {
            $condition .= str_replace('%s%', ($s_generation + $my_gen), ' AND %level% = %s%');
        }
        if (!empty($s_perdana_min)) {
            $condition .= ' AND %omzet_perdana% >= ' . $s_perdana_min . '';
        }
        if (!empty($s_perdana_max)) {
            $condition .= ' AND ( %omzet_perdana% <= ' . $s_perdana_max . ' OR %omzet_perdana% IS NULL )';
        }
        if (!empty($s_ro_min)) {
            $condition .= ' AND %omzet_ro% >= "' . $s_ro_min . '"';
        }
        if (!empty($s_ro_max)) {
            $condition .= ' AND ( %omzet_ro% <= ' . $s_ro_max . ' OR %omzet_ro% IS NULL ) ';
        }
        if (!empty($s_date_min)) {
            $condition .= ' AND %date_join% >= "' . $s_date_min . '"';
        }
        if (!empty($s_date_max)) {
            $condition .= ' AND %date_join% <= "' . $s_date_max . '"';
        }

        if ($column == 1) {
            $order_by = '%username% ' . $sort;
        } elseif ($column == 2) {
            $order_by = '%name% ' . $sort;
        } elseif ($column == 3) {
            $order_by = '%sponsor_username% ' . $sort;
        } elseif ($column == 4) {
            $order_by = '%level% ' . $sort;
        } elseif ($column == 5) {
            $order_by = '%omzet_perdana% ' . $sort;
        } elseif ($column == 6) {
            $order_by = '%omzet_ro% ' . $sort;
        } elseif ($column == 7) {
            $order_by = '%date_join% ' . $sort;
        }

        $data_list          = $this->Model_Member->get_all_member_generation_omzet($limit, $offset, $condition, $order_by);
        $records            = array();
        $records["aaData"]  = array();

        if (!empty($data_list)) {
            $iTotalRecords  = ddm_get_last_found_rows();
            $i = $offset + 1;
            foreach ($data_list as $row) {
                $id             = ddm_encrypt($row->id);
                $id_sponsor     = ddm_encrypt($row->sponsor);
                $username       = ddm_strong(strtolower($row->username));
                $username       = ($is_admin ? '<a href="' . base_url('profile/' . $id) . '">' . $username . '</a>' : $username);
                $name           = ddm_strong(strtoupper($row->name));
                $sponsor        = ddm_strong(strtolower($row->sponsor_username)) . ' <small>(' . strtoupper($row->sponsor_name) . ')</small>';
                $sponsor        = ($is_admin) ? '<a href="' . base_url('profile/' . $id_sponsor) . '">' . $sponsor . '</a>' : $sponsor;

                $gen            = $row->level - $my_gen;
                $member_gen     = '<button class="btn btn-sm btn-outline-primary">Gen-' . $gen . '</button>';

                // // Calculate Total Omzet Perdana 
                // $omzet_perdana  = 0;
                // $cond_perdana   = ' AND id_member = '. $row->id .' AND `status` = "perdana" '; 
                // if ( $getOmzetPerdana  = $this->Model_Member->get_total_member_omzet($cond_perdana) ) {
                //     $omzet_perdana = $getOmzetPerdana->total_omzet;
                // } 

                // // Calculate Total Omzet RP 
                // $omzet_ro       = 0;
                // $cond_ro        = ' AND id_member = '. $row->id .' AND `status` = "ro" '; 
                // if ( $getOmzetRo  = $this->Model_Member->get_total_member_omzet($cond_ro) ) {
                //     $omzet_ro   = $getOmzetRo->total_omzet;
                // }

                $records["aaData"][] = array(
                    ddm_center($i),
                    ddm_center($username),
                    $name,
                    $sponsor,
                    ddm_center($member_gen),
                    ddm_accounting($row->omzet_perdana, '', true),
                    ddm_accounting($row->omzet_ro, '', true),
                    '<div style="min-width:110px">' . ddm_center(date('Y-m-d', strtotime($row->date_join))) . '</div>',
                    ''
                );
                $i++;
            }
        }

        $end                = $iDisplayStart + $iDisplayLength;
        $end                = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }

    /**
     * Omzet Personal List Data function.
     */
    function omzetpersonallistdata()
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array('status' => 'access_denied', 'data' => '');
            die(json_encode($data));
        }

        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);

        $condition          = ' AND %status% LIKE "personal" ';
        if (!$is_admin) {
            $condition     .= ' AND %id_member% = ' . $current_member->id . ' ';
        }

        $order_by           = '';
        $iTotalRecords      = 0;

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);

        $sAction            = ddm_isset($_REQUEST['sAction'], '');
        $sEcho              = intval($_REQUEST['sEcho']);
        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_username         = $this->input->post('search_username');
        $s_username         = ddm_isset($s_username, '');
        $s_desc             = $this->input->post('search_desc');
        $s_desc             = ddm_isset($s_desc, '');
        $s_qty_min          = $this->input->post('search_qty_min');
        $s_qty_min          = ddm_isset($s_qty_min, '');
        $s_qty_max          = $this->input->post('search_qty_max');
        $s_qty_max          = ddm_isset($s_qty_max, '');
        $s_omzet_min        = $this->input->post('search_omzet_min');
        $s_omzet_min        = ddm_isset($s_omzet_min, '');
        $s_omzet_max        = $this->input->post('search_omzet_max');
        $s_omzet_max        = ddm_isset($s_omzet_max, '');
        $s_date_min         = $this->input->post('search_datecreated_min');
        $s_date_min         = ddm_isset($s_date_min, '');
        $s_date_max         = $this->input->post('search_datecreated_max');
        $s_date_max         = ddm_isset($s_date_max, '');

        if (!empty($s_username)) {
            $condition .= str_replace('%s%', $s_username, ' AND %username% LIKE "%%s%%"');
        }
        if (!empty($s_desc)) {
            $condition .= str_replace('%s%', $s_desc, ' AND %description% LIKE "%%s%%"');
        }
        if (!empty($s_date_min)) {
            $condition .= ' AND %date% >= "' . $s_date_min . '" ';
        }
        if (!empty($s_date_max)) {
            $condition .= ' AND %date% <= "' . $s_date_max . '" ';
        }
        if (!empty($s_qty_min)) {
            $condition .= str_replace('%s%', $s_qty_min, ' AND %qty% >= %s%');
        }
        if (!empty($s_qty_max)) {
            $condition .= str_replace('%s%', $s_qty_max, ' AND %qty% <= %s%');
        }
        if (!empty($s_omzet_min)) {
            $condition .= str_replace('%s%', $s_omzet_min, ' AND %omzet% >= %s%');
        }
        if (!empty($s_omzet_max)) {
            $condition .= str_replace('%s%', $s_omzet_max, ' AND %omzet% <= %s%');
        }

        if ($is_admin) {
            if ($column == 1) {
                $order_by .= '%username% ' . $sort;
            } elseif ($column == 2) {
                $order_by .= '%qty% ' . $sort;
            } elseif ($column == 3) {
                $order_by .= '%omzet% ' . $sort;
            } elseif ($column == 4) {
                $order_by .= '%desc% ' . $sort;
            } elseif ($column == 5) {
                $order_by .= '%date% ' . $sort;
            }
        } else {
            if ($column == 1) {
                $order_by .= '%qty% ' . $sort;
            } elseif ($column == 2) {
                $order_by .= '%omzet% ' . $sort;
            } elseif ($column == 3) {
                $order_by .= '%desc% ' . $sort;
            } elseif ($column == 4) {
                $order_by .= '%date% ' . $sort;
            }
        }

        $data_list          = $this->Model_Member->get_all_member_omzet($limit, $offset, $condition, $order_by);
        $records            = array();
        $records["aaData"]  = array();

        if (!empty($data_list)) {
            $iTotalRecords  = ddm_get_last_found_rows();
            $currency       = config_item('currency');
            $i = $offset + 1;
            foreach ($data_list as $row) {
                if ($is_admin) {
                    $id_member = ddm_encrypt($row->id_member);
                    $records["aaData"][] = array(
                        ddm_center($i),
                        ddm_center('<a href="' . base_url('profile/' . $id_member) . '"><strong>' . $row->username . '</strong></a>'),
                        ddm_center($row->qty . ' Liter'),
                        ddm_right(ddm_accounting($row->omzet)),
                        $row->desc,
                        ddm_center($row->date),
                        ''
                    );
                } else {
                    $records["aaData"][] = array(
                        ddm_center($i),
                        ddm_center($row->qty . ' Liter'),
                        ddm_right(ddm_accounting($row->omzet)),
                        $row->desc,
                        ddm_center($row->date),
                        ''
                    );
                }
                $i++;
            }
        }

        $end                = $iDisplayStart + $iDisplayLength;
        $end                = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }

    /**
     * Omzet Total Personal List Data function.
     */
    function omzettotalpersonallistdata()
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array('status' => 'access_denied', 'data' => '');
            die(json_encode($data));
        }

        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);

        $condition          = ' AND %status% LIKE "personal" ';
        if (!$is_admin) {
            $condition     .= ' AND %id_member% = ' . $current_member->id . ' ';
        }

        $order_by           = '';
        $iTotalRecords      = 0;

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);

        $sAction            = ddm_isset($_REQUEST['sAction'], '');
        $sEcho              = intval($_REQUEST['sEcho']);
        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_username         = $this->input->post('search_username');
        $s_username         = ddm_isset($s_username, '');
        $s_qty_min          = $this->input->post('search_qty_min');
        $s_qty_min          = ddm_isset($s_qty_min, '');
        $s_qty_max          = $this->input->post('search_qty_max');
        $s_qty_max          = ddm_isset($s_qty_max, '');
        $s_omzet_min        = $this->input->post('search_omzet_min');
        $s_omzet_min        = ddm_isset($s_omzet_min, '');
        $s_omzet_max        = $this->input->post('search_omzet_max');
        $s_omzet_max        = ddm_isset($s_omzet_max, '');

        if (!empty($s_username)) {
            $condition .= str_replace('%s%', $s_username, ' AND %username% LIKE "%%s%%"');
        }
        if (!empty($s_qty_min)) {
            $condition .= str_replace('%s%', $s_qty_min, ' AND %qty% >= %s%');
        }
        if (!empty($s_qty_max)) {
            $condition .= str_replace('%s%', $s_qty_max, ' AND %qty% <= %s%');
        }
        if (!empty($s_omzet_min)) {
            $condition .= str_replace('%s%', $s_omzet_min, ' AND %omzet% >= %s%');
        }
        if (!empty($s_omzet_max)) {
            $condition .= str_replace('%s%', $s_omzet_max, ' AND %omzet% <= %s%');
        }

        if ($is_admin) {
            if ($column == 1) {
                $order_by .= '%username% ' . $sort;
            } elseif ($column == 2) {
                $order_by .= '%qty% ' . $sort;
            } elseif ($column == 3) {
                $order_by .= '%omzet% ' . $sort;
            }
        } else {
            if ($column == 1) {
                $order_by .= '%qty% ' . $sort;
            } elseif ($column == 2) {
                $order_by .= '%omzet% ' . $sort;
            } elseif ($column == 3) {
                $order_by .= '%desc% ' . $sort;
            } elseif ($column == 4) {
                $order_by .= '%date% ' . $sort;
            }
        }

        $data_list          = $this->Model_Member->get_all_member_omzet_total($limit, $offset, $condition, $order_by);
        $records            = array();
        $records["aaData"]  = array();

        if (!empty($data_list)) {
            $iTotalRecords  = ddm_get_last_found_rows();
            $currency       = config_item('currency');
            $i = $offset + 1;
            foreach ($data_list as $row) {
                if ($is_admin) {
                    $id_member = ddm_encrypt($row->id_member);
                    $records["aaData"][] = array(
                        ddm_center($i),
                        ddm_left('<a href="' . base_url('profile/' . $id_member) . '"><strong>' . $row->username . '</strong></a>'),
                        ddm_center($row->total_qty . ' Liter'),
                        ddm_right(ddm_accounting($row->total_omzet)),
                        ''
                    );
                } else {
                    $records["aaData"][] = array(
                        ddm_center($i),
                        ddm_center($row->qty . ' Liter'),
                        ddm_right(ddm_accounting($row->omzet)),
                        $row->desc,
                        ddm_center($row->date),
                        ''
                    );
                }
                $i++;
            }
        }

        $end                = $iDisplayStart + $iDisplayLength;
        $end                = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }

    /**
     * Omzet Daily List Data function.
     */
    function omzetdailylistdata()
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array('status' => 'access_denied', 'data' => '');
            die(json_encode($data));
        }

        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);

        $condition          = '';
        $total_condition    = '';
        $order_by           = '';
        $iTotalRecords      = 0;

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);

        $sAction            = ddm_isset($_REQUEST['sAction'], '');
        $sEcho              = intval($_REQUEST['sEcho']);
        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_reg_min          = $this->input->post('search_register_min');
        $s_reg_min          = ddm_isset($s_reg_min, '');
        $s_reg_max          = $this->input->post('search_register_max');
        $s_reg_max          = ddm_isset($s_reg_max, '');
        $s_perdana_min      = $this->input->post('search_perdana_min');
        $s_perdana_min      = ddm_isset($s_perdana_min, '');
        $s_perdana_max      = $this->input->post('search_perdana_max');
        $s_perdana_max      = ddm_isset($s_perdana_max, '');
        $s_ro_min           = $this->input->post('search_ro_min');
        $s_ro_min           = ddm_isset($s_ro_min, '');
        $s_ro_max           = $this->input->post('search_ro_max');
        $s_ro_max           = ddm_isset($s_ro_max, '');
        $s_omzet_min        = $this->input->post('search_omzet_min');
        $s_omzet_min        = ddm_isset($s_omzet_min, '');
        $s_omzet_max        = $this->input->post('search_omzet_max');
        $s_omzet_max        = ddm_isset($s_omzet_max, '');
        $s_bonus_min        = $this->input->post('search_bonus_min');
        $s_bonus_min        = ddm_isset($s_bonus_min, '');
        $s_bonus_max        = $this->input->post('search_bonus_max');
        $s_bonus_max        = ddm_isset($s_bonus_max, '');
        $s_percent_min      = $this->input->post('search_percent_min');
        $s_percent_min      = ddm_isset($s_percent_min, '');
        $s_percent_max      = $this->input->post('search_percent_max');
        $s_percent_max      = ddm_isset($s_percent_max, '');
        $s_date_min         = $this->input->post('search_datecreated_min');
        $s_date_min         = ddm_isset($s_date_min, '');
        $s_date_max         = $this->input->post('search_datecreated_max');
        $s_date_max         = ddm_isset($s_date_max, '');

        if (!empty($s_date_min)) {
            $condition .= ' AND %date_omzet% >= "' . $s_date_min . '" ';
        }
        if (!empty($s_date_max)) {
            $condition .= ' AND %date_omzet% <= "' . $s_date_max . '" ';
        }
        if (!empty($s_reg_min)) {
            $total_condition .= str_replace('%s%', $s_reg_min, ' AND %omzet_register% >= %s%');
        }
        if (!empty($s_reg_max)) {
            $total_condition .= str_replace('%s%', $s_reg_max, ' AND %omzet_register% <= %s%');
        }
        if (!empty($s_perdana_min)) {
            $total_condition .= str_replace('%s%', $s_perdana_min, ' AND %omzet_perdana% >= %s%');
        }
        if (!empty($s_perdana_max)) {
            $total_condition .= str_replace('%s%', $s_perdana_max, ' AND %omzet_perdana% <= %s%');
        }
        if (!empty($s_ro_min)) {
            $total_condition .= str_replace('%s%', $s_ro_min, ' AND %omzet_ro% >= %s%');
        }
        if (!empty($s_ro_max)) {
            $total_condition .= str_replace('%s%', $s_ro_max, ' AND %omzet_ro% <= %s%');
        }
        if (!empty($s_omzet_min)) {
            $total_condition .= str_replace('%s%', $s_omzet_min, ' AND %total_omzet% >= %s%');
        }
        if (!empty($s_omzet_max)) {
            $total_condition .= str_replace('%s%', $s_omzet_max, ' AND %total_omzet% <= %s%');
        }
        if (!empty($s_bonus_min)) {
            $total_condition .= str_replace('%s%', $s_bonus_min, ' AND %total_bonus% >= %s%');
        }
        if (!empty($s_bonus_max)) {
            $total_condition .= str_replace('%s%', $s_bonus_max, ' AND %total_bonus% <= %s%');
        }
        if (!empty($s_percent_min)) {
            $total_condition .= str_replace('%s%', $s_percent_min, ' AND %percent% >= %s%');
        }
        if (!empty($s_percent_max)) {
            $total_condition .= str_replace('%s%', $s_percent_max, ' AND %percent% <= %s%');
        }

        if (!empty($condition)) {
            $condition = substr($condition, 4);
            $condition = ' WHERE' . $condition;
        }

        if ($column == 1) {
            $order_by .= '%date_omzet% ' . $sort;
        } elseif ($column == 2) {
            $order_by .= '%omzet_register% ' . $sort;
        } elseif ($column == 3) {
            $order_by .= '%omzet_perdana% ' . $sort;
        } elseif ($column == 4) {
            $order_by .= '%omzet_ro% ' . $sort;
        } elseif ($column == 5) {
            $order_by .= '%total_omzet% ' . $sort;
        } elseif ($column == 6) {
            $order_by .= '%total_bonus% ' . $sort;
        } elseif ($column == 7) {
            $order_by .= '%percent% ' . $sort;
        }

        $data_list          = $this->Model_Member->get_all_omzet_daily($limit, $offset, $condition, $order_by, $total_condition);
        $records            = array();
        $records["aaData"]  = array();

        if (!empty($data_list)) {
            $iTotalRecords  = ddm_get_last_found_rows();
            $currency       = config_item('currency');
            $i = $offset + 1;
            foreach ($data_list as $row) {
                $id         = ddm_encrypt($row->date_omzet);
                $btn_detail = '<a href="' . base_url('report/omzetdailydetail/' . $id) . '" data-id="' . $id . '" class="btn btn-sm btn-primary omzetdailydetail"><i class="fa fa-plus"></i> Detail</a>';

                $percent = $row->percent ? $row->percent : 0;
                if ($percent <= 0) {
                    $percent = '<span style="color:#dd4b39"><b>--</b></span>';
                } else {
                    $percent = $percent . ' %';
                }

                $records["aaData"][] = array(
                    ddm_center($i),
                    ddm_center(date("Y-m-d", strtotime($row->date_omzet))),
                    ddm_right(ddm_accounting($row->total_omzet_register)),
                    ddm_right(ddm_accounting($row->total_omzet_perdana)),
                    ddm_right(ddm_accounting($row->total_omzet_ro)),
                    ddm_right(ddm_accounting($row->total_omzet)),
                    ddm_right(ddm_accounting($row->total_bonus)),
                    // ddm_center($percent),
                    // ddm_center($btn_detail)
                    ''
                );
                $i++;
            }
        }

        $end                = $iDisplayStart + $iDisplayLength;
        $end                = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }

    /**
     * Omzet Monthly List Data function.
     */
    function omzetmonthlylistdata()
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array('status' => 'access_denied', 'data' => '');
            die(json_encode($data));
        }

        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);

        $condition          = '';
        $total_condition    = '';
        $order_by           = '';
        $iTotalRecords      = 0;

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);

        $sAction            = ddm_isset($_REQUEST['sAction'], '');
        $sEcho              = intval($_REQUEST['sEcho']);
        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_reg_min          = $this->input->post('search_register_min');
        $s_reg_min          = ddm_isset($s_reg_min, '');
        $s_reg_max          = $this->input->post('search_register_max');
        $s_reg_max          = ddm_isset($s_reg_max, '');
        $s_perdana_min      = $this->input->post('search_perdana_min');
        $s_perdana_min      = ddm_isset($s_perdana_min, '');
        $s_perdana_max      = $this->input->post('search_perdana_max');
        $s_perdana_max      = ddm_isset($s_perdana_max, '');
        $s_ro_min           = $this->input->post('search_ro_min');
        $s_ro_min           = ddm_isset($s_ro_min, '');
        $s_ro_max           = $this->input->post('search_ro_max');
        $s_ro_max           = ddm_isset($s_ro_max, '');
        $s_omzet_min        = $this->input->post('search_omzet_min');
        $s_omzet_min        = ddm_isset($s_omzet_min, '');
        $s_omzet_max        = $this->input->post('search_omzet_max');
        $s_omzet_max        = ddm_isset($s_omzet_max, '');
        $s_bonus_min        = $this->input->post('search_bonus_min');
        $s_bonus_min        = ddm_isset($s_bonus_min, '');
        $s_bonus_max        = $this->input->post('search_bonus_max');
        $s_bonus_max        = ddm_isset($s_bonus_max, '');
        $s_percent_min      = $this->input->post('search_percent_min');
        $s_percent_min      = ddm_isset($s_percent_min, '');
        $s_percent_max      = $this->input->post('search_percent_max');
        $s_percent_max      = ddm_isset($s_percent_max, '');
        $s_date_min         = $this->input->post('search_datecreated_min');
        $s_date_min         = ddm_isset($s_date_min, '');
        $s_date_max         = $this->input->post('search_datecreated_max');
        $s_date_max         = ddm_isset($s_date_max, '');

        if (!empty($s_date_min)) {
            $condition .= ' AND %month_omzet% >= "' . $s_date_min . '" ';
        }
        if (!empty($s_date_max)) {
            $condition .= ' AND %month_omzet% <= "' . $s_date_max . '" ';
        }
        if (!empty($s_reg_min)) {
            $total_condition .= str_replace('%s%', $s_reg_min, ' AND %omzet_register% >= %s%');
        }
        if (!empty($s_reg_max)) {
            $total_condition .= str_replace('%s%', $s_reg_max, ' AND %omzet_register% <= %s%');
        }
        if (!empty($s_perdana_min)) {
            $total_condition .= str_replace('%s%', $s_perdana_min, ' AND %omzet_perdana% >= %s%');
        }
        if (!empty($s_perdana_max)) {
            $total_condition .= str_replace('%s%', $s_perdana_max, ' AND %omzet_perdana% <= %s%');
        }
        if (!empty($s_ro_min)) {
            $total_condition .= str_replace('%s%', $s_ro_min, ' AND %omzet_ro% >= %s%');
        }
        if (!empty($s_ro_max)) {
            $total_condition .= str_replace('%s%', $s_ro_max, ' AND %omzet_ro% <= %s%');
        }
        if (!empty($s_omzet_min)) {
            $total_condition .= str_replace('%s%', $s_omzet_min, ' AND %total_omzet% >= %s%');
        }
        if (!empty($s_omzet_max)) {
            $total_condition .= str_replace('%s%', $s_omzet_max, ' AND %total_omzet% <= %s%');
        }
        if (!empty($s_bonus_min)) {
            $total_condition .= str_replace('%s%', $s_bonus_min, ' AND %total_bonus% >= %s%');
        }
        if (!empty($s_bonus_max)) {
            $total_condition .= str_replace('%s%', $s_bonus_max, ' AND %total_bonus% <= %s%');
        }
        if (!empty($s_percent_min)) {
            $total_condition .= str_replace('%s%', $s_percent_min, ' AND %percent% >= %s%');
        }
        if (!empty($s_percent_max)) {
            $total_condition .= str_replace('%s%', $s_percent_max, ' AND %percent% <= %s%');
        }

        if (!empty($condition)) {
            $condition = substr($condition, 4);
            $condition = ' WHERE' . $condition;
        }

        if ($column == 1) {
            $order_by .= '%month_omzet% ' . $sort;
        } elseif ($column == 2) {
            $order_by .= '%omzet_register% ' . $sort;
        } elseif ($column == 3) {
            $order_by .= '%omzet_perdana% ' . $sort;
        } elseif ($column == 4) {
            $order_by .= '%omzet_ro% ' . $sort;
        } elseif ($column == 5) {
            $order_by .= '%total_omzet% ' . $sort;
        } elseif ($column == 6) {
            $order_by .= '%total_bonus% ' . $sort;
        } elseif ($column == 7) {
            $order_by .= '%percent% ' . $sort;
        }

        $data_list          = $this->Model_Member->get_all_omzet_monthly($limit, $offset, $condition, $order_by, $total_condition);
        $records            = array();
        $records["aaData"]  = array();

        if (!empty($data_list)) {
            $iTotalRecords  = ddm_get_last_found_rows();
            $currency       = config_item('currency');
            $i = $offset + 1;
            foreach ($data_list as $row) {
                $id         = ddm_encrypt($row->month_omzet);
                $btn_detail = '<a href="' . base_url('report/omzetmonthlydetail/' . $id) . '" data-id="' . $id . '" class="btn btn-sm btn-primary omzetmonthlydetail"><i class="fa fa-plus"></i> Detail</a>';

                $percent = $row->percent ? $row->percent : 0;
                if ($percent <= 0) {
                    $percent = '<span style="color:#dd4b39"><b>--</b></span>';
                } else {
                    $percent = $percent . ' %';
                }

                $records["aaData"][] = array(
                    ddm_center($i),
                    ddm_center(date("M, Y", strtotime($row->month_omzet))),
                    ddm_right(ddm_accounting($row->total_omzet_register)),
                    ddm_right(ddm_accounting($row->total_omzet_perdana)),
                    ddm_right(ddm_accounting($row->total_omzet_ro)),
                    ddm_right(ddm_accounting($row->total_omzet)),
                    ddm_right(ddm_accounting($row->total_bonus)),
                    ddm_center($percent),
                    // ddm_center($btn_detail)
                    ''
                );
                $i++;
            }
        }

        $end                = $iDisplayStart + $iDisplayLength;
        $end                = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }

    /**
     * Reward List Data function.
     */
    function rewardlistdata()
    {
        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);

        $condition          = '';
        if (!$is_admin) {
            $condition     .= ' AND %id_member% = ' . $current_member->id;
        }
        $order_by           = '';
        $iTotalRecords      = 0;

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);

        $sAction            = ddm_isset($_REQUEST['sAction'], '');
        $sEcho              = intval($_REQUEST['sEcho']);
        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_username         = $this->input->post('search_username');
        $s_username         = ddm_isset($s_username, '');
        $s_name             = $this->input->post('search_name');
        $s_name             = ddm_isset($s_name, '');
        $s_reward           = $this->input->post('search_reward');
        $s_reward           = ddm_isset($s_reward, '');
        $s_status           = $this->input->post('search_status');
        $s_status           = ddm_isset($s_status, '');
        $s_nominal_min      = $this->input->post('search_nominal_min');
        $s_nominal_min      = ddm_isset($s_nominal_min, '');
        $s_nominal_max      = $this->input->post('search_nominal_max');
        $s_nominal_max      = ddm_isset($s_nominal_max, '');
        $s_date_min         = $this->input->post('search_datecreated_min');
        $s_date_min         = ddm_isset($s_date_min, '');
        $s_date_max         = $this->input->post('search_datecreated_max');
        $s_date_max         = ddm_isset($s_date_max, '');
        $s_dateconfirm_min  = $this->input->post('search_dateconfirm_min');
        $s_dateconfirm_min  = ddm_isset($s_dateconfirm_min, '');
        $s_dateconfirm_max  = $this->input->post('search_dateconfirm_max');
        $s_dateconfirm_max  = ddm_isset($s_dateconfirm_max, '');

        if (!empty($s_username)) {
            $condition .= ' AND %username% LIKE "%' . $s_username . '%"';
        }
        if (!empty($s_name)) {
            $condition .= ' AND %name% LIKE "%' . $s_name . '%"';
        }
        if (!empty($s_reward)) {
            $condition .= ' AND %id_reward% = ' . $s_reward . '';
        }
        if (!empty($s_nominal_min)) {
            $condition .= ' AND %nominal% >= ' . $s_nominal_min . '';
        }
        if (!empty($s_nominal_max)) {
            $condition .= ' AND %nominal% <= ' . $s_nominal_max . '';
        }
        if (!empty($s_date_min)) {
            $condition .= ' AND %datecreated% >= "' . $s_date_min . '"';
        }
        if (!empty($s_date_max)) {
            $condition .= ' AND %datecreated% <= "' . $s_date_max . '"';
        }
        if (!empty($s_dateconfirm_min)) {
            $condition .= ' AND %datemodified% >= "' . $s_dateconfirm_min . '"';
        }
        if (!empty($s_dateconfirm_max)) {
            $condition .= ' AND %datemodified% <= "' . $s_dateconfirm_max . '"';
        }
        if (!empty($s_status)) {
            $condition .= str_replace('%s%', ($s_status == 'pending' ? 0 : 1), ' AND %status% = %s%');
        }

        if ($is_admin) {
            if ($column == 1) {
                $order_by .= '%datecreated% ' . $sort;
            } elseif ($column == 2) {
                $order_by .= '%username% ' . $sort;
            } elseif ($column == 3) {
                $order_by .= '%name% ' . $sort;
            } elseif ($column == 4) {
                $order_by .= '%message% ' . $sort;
            } elseif ($column == 5) {
                $order_by .= '%nominal% ' . $sort;
            } elseif ($column == 6) {
                $order_by .= '%status% ' . $sort;
            } elseif ($column == 7) {
                $order_by .= '%datemodified% ' . $sort;
            }
        } else {
            if ($column == 1) {
                $order_by .= '%datecreated% ' . $sort;
            } elseif ($column == 2) {
                $order_by .= '%message% ' . $sort;
            } elseif ($column == 3) {
                $order_by .= '%nominal% ' . $sort;
            } elseif ($column == 4) {
                $order_by .= '%status% ' . $sort;
            } elseif ($column == 5) {
                $order_by .= '%datemodified% ' . $sort;
            }
        }

        $data_list          = $this->Model_Member->get_all_member_reward($limit, $offset, $condition, $order_by);
        $records            = array();
        $records["aaData"]  = array();

        if (!empty($data_list)) {
            $iTotalRecords  = ddm_get_last_found_rows();
            $i = $offset + 1;
            $currency = config_item('currency');
            foreach ($data_list as $row) {
                $id         = ddm_encrypt($row->id);
                $id_member  = ddm_encrypt($row->id_member);
                if ($row->status >= 1) {
                    $status     = '<span class="badge badge-sm badge-success">CONFIRMED</span>';
                    $btn_action = '<a href="javascript:;" class="btn btn-sm text-success" disabled=""><i class="fa fa-check"></i></a>';
                    $dateconfirm = ddm_center(date('d M Y', strtotime($row->datemodified)));
                } else {
                    $status     = '<span class="badge badge-sm badge-default">PENDING</span>';
                    $btn_action = '<a href="javascript:;" class="btn btn-sm btn-primary rewardconfirm"
                                    data-url="' . base_url('member/rewardconfirm/' . $id) . '"
                                    data-username="' . $row->username . '"
                                    data-name="' . $row->name . '"
                                    data-nominal="' . ddm_accounting($row->nominal, $currency) . '"
                                    data-reward="' . $row->message . '"
                                    ><i class="fa fa-check"></i> Confirm</a>';
                    $dateconfirm = '';
                }



                $datatable = array(
                    ddm_center($i),
                    '<div style="min-width:100px">' . ddm_center(date('d M Y', strtotime($row->datecreated))) . '</div>',
                );

                if ($is_admin) {
                    $datatable[] = '<div style="min-width:100px"><a href="' . base_url('profile/' . $id_member) . '"><b>' . strtolower($row->username) . '</b></a></div>';
                    $datatable[] = '<div style="min-width:100px"><b>' . strtoupper($row->name) . '</b></div>';
                }

                $datatable[] = '<div style="min-width:100px">' . $row->message . '</div>';
                $datatable[] = '<div style="min-width:100px">' . ddm_accounting($row->nominal, '', TRUE) . '</div>';
                $datatable[] = '<div style="min-width:80px">' . ddm_center($status) . '</div>';
                $datatable[] = $dateconfirm;
                $datatable[] = ($is_admin ? ddm_center($btn_action) : '');

                $records["aaData"][] = $datatable;
                $i++;
            }
        }

        $end                = $iDisplayStart + $iDisplayLength;
        $end                = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }

    /**
     * Stockist List Data function.
     */
    function stockistlistspin($is_stockist = true)
    {
        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);

        $condition          = ' WHERE %type% = ' . MEMBER . ' AND %status% = ' . ACTIVE . ' ';
        if ($is_stockist) {
            $condition     .= ' AND %as_stockist% > 0 ';
        }

        $sAction            = ddm_isset($_REQUEST['sAction'], '');
        $order_by           = '';
        $iTotalRecords      = 0;

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);
        $sEcho              = intval($_REQUEST['sEcho']);

        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_memberid         = $this->input->post('search_memberid');
        $s_memberid         = ddm_isset($s_memberid, '');
        $s_name             = $this->input->post('search_name');
        $s_name             = ddm_isset($s_name, '');
        $s_username         = $this->input->post('search_username');
        $s_username         = ddm_isset($s_username, '');
        $s_province         = $this->input->post('search_province');
        $s_province         = ddm_isset($s_province, '');
        $s_city             = $this->input->post('search_city');
        $s_city             = ddm_isset($s_city, '');
        $s_status           = $this->input->post('search_status');
        $s_status           = ddm_isset($s_status, 1);
        $s_package          = $this->input->post('search_package');
        $s_package          = ddm_isset($s_package, '');

        if (!empty($s_memberid)) {
            $condition .= str_replace('%s%', $s_memberid, ' AND %id% = %s%');
        }
        if (!empty($s_name)) {
            $condition .= str_replace('%s%', $s_name, ' AND %name% LIKE "%%s%%"');
        }
        if (!empty($s_username)) {
            $condition .= str_replace('%s%', $s_username, ' AND %username% LIKE "%%s%%"');
        }
        if (!empty($s_province)) {
            $condition .= str_replace('%s%', $s_province, ' AND %province% = %s%');
        }
        if (!empty($s_city)) {
            $condition .= str_replace('%s%', $s_city, ' AND %city% = %s%');
        }
        if (!empty($s_package)) {
            $condition .= str_replace('%s%', $s_package, ' AND %package% = "%s%"');
        }
        if (!empty($s_status)) {
            if ($s_status == 'member') {
                $condition .= str_replace('%s%', 0, ' AND %as_stockist% = %s%');
            } else {
                $condition .= str_replace('%s%', 0, ' AND %as_stockist% > %s%');
            }
        }

        if ($column == 1) {
            $order_by .= '%username% ' . $sort;
        } elseif ($column == 2) {
            $order_by .= '%name% ' . $sort;
        } elseif ($column == 3) {
            $order_by .= '%username% ' . $sort;
        } elseif ($column == 4) {
            $order_by .= '%province% ' . $sort;
        } elseif ($column == 5) {
            $order_by .= '%city% ' . $sort;
        } elseif ($column == 6) {
            $order_by .= '%as_stockist% ' . $sort;
        }

        $member_list        = $this->Model_Member->get_all_member_data($limit, $offset, $condition, $order_by);

        $records            = array();
        $records["aaData"]  = array();

        if (!empty($member_list)) {
            $iTotalRecords  = ddm_get_last_found_rows();
            $i = $offset + 1;
            foreach ($member_list as $row) {

                $username       = '<a href="javascript:;" class="btn-stockist-pin" data-id="' . $row->username . '">' . $row->username . '</a>';
                $name           = '<a href="javascript:;" class="btn-stockist-pin" data-id="' . $row->username . '">' . $row->name . '</a>';

                $province_name  = '';
                if ($row->province) {
                    $province       = ddm_provinces($row->province);
                    $province_name  = $province ? $province->province_name : '';
                }

                $city_name  = '';
                if ($row->city) {
                    $cities         = ddm_cities($row->city);
                    $city_name      = $cities ? $cities->regional_name : '';
                }

                $status  = '<span class="label label-sm label-success"><strong>MEMBER</strong></span>';
                if ($row->as_stockist == 1) {
                    $status  = '<span class="label label-sm label-primary"><strong>STOCKIST</strong></span>';
                }

                $btn_process    = '<a href="javascript:;" class="btn btn-xs btn-flat bg-blue btn-stockist-pin" title="Pilih Stockist" data-id="' . $row->username . '"><i class="fa fa-check"></i> Pilih</a>';

                $records["aaData"][] = array(
                    ddm_center($i),
                    ddm_center($username),
                    $name,
                    $province_name,
                    $city_name,
                    ddm_center($status),
                    ddm_center($btn_process)
                );
                $i++;
            }
        }

        $end                                = $iDisplayStart + $iDisplayLength;
        $end                                = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }

    /**
     * Report History Product List Data function.
     */
    function historyproductlistdata()
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data));
        }

        $member_data        = '';
        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);

        $condition          = '';
        if (!$is_admin) {
            $condition .= ' AND %id_member% = ' . $current_member->id;
        }
        $balance_condition  = '';

        $order_by           = '';
        $iTotalRecords      = 0;

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);

        $sEcho              = intval($_REQUEST['sEcho']);
        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_username         = $this->input->post('search_username');
        $s_username         = ddm_isset($s_username, '');
        $s_in_min           = $this->input->post('search_in_min');
        $s_in_min           = ddm_isset($s_in_min, '');
        $s_in_max           = $this->input->post('search_in_max');
        $s_in_max           = ddm_isset($s_in_max, '');
        $s_out_min          = $this->input->post('search_out_min');
        $s_out_min          = ddm_isset($s_out_min, '');
        $s_out_max          = $this->input->post('search_out_max');
        $s_out_max          = ddm_isset($s_out_max, '');
        $s_balance_min      = $this->input->post('search_balance_min');
        $s_balance_min      = ddm_isset($s_balance_min, '');
        $s_balance_max      = $this->input->post('search_balance_max');
        $s_balance_max      = ddm_isset($s_balance_max, '');

        if (!empty($s_username)) {
            $condition .= str_replace('%s%', $s_username, ' AND %username% LIKE "%%s%%"');
        }

        if (!empty($s_in_min)) {
            $condition .= ' AND %in% >= ' . $s_in_min . '';
        }
        if (!empty($s_in_max)) {
            $condition .= ' AND %in% <= ' . $s_in_max . '';
        }
        if (!empty($s_out_min)) {
            $condition .= ' AND %out% >= ' . $s_out_min . '';
        }
        if (!empty($s_out_max)) {
            $condition .= ' AND %out% <= ' . $s_out_max . '';
        }

        if (!empty($s_balance_min)) {
            $balance_condition .= ' AND %balance% >= ' . $s_balance_min . '';
        }
        if (!empty($s_balance_max)) {
            $balance_condition .= ' AND %balance% <= ' . $s_balance_max . '';
        }

        if ($column == 1) {
            $order_by .= '%username% ' . $sort;
        } elseif ($column == 2) {
            $order_by .= '%in% ' . $sort;
        } elseif ($column == 3) {
            $order_by .= '%out% ' . $sort;
        } elseif ($column == 4) {
            $order_by .= '%balance% ' . $sort;
        }

        $data_list          = $this->Model_Omzet_History->get_all_history_product($limit, $offset, $condition, $order_by, $balance_condition);

        $records            = array();
        $records["aaData"]  = array();

        if (!empty($data_list)) {
            $iTotalRecords  = ddm_get_last_found_rows();
            $i = $offset + 1;

            foreach ($data_list as $row) {
                $id_member  = ddm_encrypt($row->id_member);
                $in         = $row->qty_in;
                $out        = $row->qty_out;
                $balance    = $row->qty_balance;

                $btn_detail = '<a href="' . base_url('report/product/' . $id_member) . '" class="btn btn-sm btn-primary">Detail</a>';

                $records["aaData"][]    = array(
                    ddm_center($i),
                    ddm_center('<a href="' . base_url('profile/' . $id_member) . '">' . ddm_strong(strtolower($row->username)) . '</a>'),
                    ddm_center($in),
                    ddm_center($out),
                    ddm_center($balance),
                    ddm_center($btn_detail)
                );
                $i++;
            }
        }

        $end                = $iDisplayStart + $iDisplayLength;
        $end                = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }

    /**
     * Report History Product Detail List Data function.
     */
    function historyproductdetaillistdata($id = '')
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data));
        }

        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);

        $condition          = '';
        if ($id) {
            $id             = ddm_encrypt($id, 'decrypt');
            $condition     .= ' AND %id_member% = ' . $id . ' ';
        } elseif (!$is_admin) {
            $id             = $current_member->id;
            $condition     .= ' AND %id_member% = ' . $id . ' ';
        }

        $order_by           = '';
        $iTotalRecords      = 0;

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);

        $sEcho              = intval($_REQUEST['sEcho']);
        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_date_min         = $this->input->post('search_datecreated_min');
        $s_date_min         = ddm_isset($s_date_min, '');
        $s_date_max         = $this->input->post('search_datecreated_max');
        $s_date_max         = ddm_isset($s_date_max, '');
        $s_qty_min          = $this->input->post('search_qty_min');
        $s_qty_min          = ddm_isset($s_qty_min, '');
        $s_qty_max          = $this->input->post('search_qty_max');
        $s_qty_max          = ddm_isset($s_qty_max, '');
        $s_username         = $this->input->post('search_username');
        $s_username         = ddm_isset($s_username, '');
        $s_name             = $this->input->post('search_name');
        $s_name             = ddm_isset($s_name, '');
        $s_desc             = $this->input->post('search_desc');
        $s_desc             = ddm_isset($s_desc, '');
        $s_type             = $this->input->post('search_type');
        $s_type             = ddm_isset($s_type, '');
        $s_type             = (empty($s_type) ? $this->input->get('bonus_type') : $s_type);

        if (!empty($s_username)) {
            $condition .= str_replace('%s%', $s_username, ' AND %username% LIKE "%%s%%"');
        }
        if (!empty($s_name)) {
            $condition .= str_replace('%s%', $s_name, ' AND %name% LIKE "%%s%%"');
        }
        if (!empty($s_desc)) {
            $condition .= str_replace('%s%', $s_desc, ' AND %desc% LIKE "%%s%%"');
        }
        if (!empty($s_type)) {
            $condition .= str_replace('%s%', $s_type, ' AND %type% LIKE "%%s%%"');
        }
        if (!empty($s_qty_min)) {
            $condition .= ' AND %qty% >= ' . $s_qty_min . '';
        }
        if (!empty($s_qty_max)) {
            $condition .= ' AND %qty% <= ' . $s_qty_max . '';
        }
        if (!empty($s_date_min)) {
            $condition .= ' AND %datecreated% >= "' . $s_date_min . '"';
        }
        if (!empty($s_date_max)) {
            $condition .= ' AND %datecreated% <= "' . $s_date_max . '"';
        }

        if ($column == 1) {
            $order_by .= '%datecreated% ' . $sort;
        } elseif ($column == 2) {
            $order_by .= '%username% ' . $sort;
        } elseif ($column == 3) {
            $order_by .= '%name% ' . $sort;
        } elseif ($column == 4) {
            $order_by .= '%qty% ' . $sort;
        } elseif ($column == 5) {
            $order_by .= '%type% ' . $sort;
        } elseif ($column == 6) {
            $order_by .= '%desc% ' . $sort;
        }

        $data_list      = $this->Model_Omzet_History->get_all_history_product_detail($limit, $offset, $condition, $order_by);

        $records            = array();
        $records["aaData"]  = array();

        if (!empty($data_list)) {
            $iTotalRecords  = ddm_get_last_found_rows();
            $currency       = config_item('currency');
            $i = $offset + 1;
            foreach ($data_list as $row) {
                $type       = '';
                $lbl_class  = 'default';
                if ($row->type == "IN") {
                    $lbl_class = 'success';
                    $type = '<span class="badge badge-sm badge-' . $lbl_class . '">' . strtoupper($row->type) . '</span>';
                } else if ($row->type == "OUT") {
                    $lbl_class = 'danger';
                    $type = '<span class="badge badge-sm badge-' . $lbl_class . '">' . strtoupper($row->type) . '</span>';
                }

                if (!$id) {
                    if ($is_admin) {
                        $id_member  = ddm_encrypt($row->id_member);
                        $records["aaData"][]    = array(
                            ddm_center($i),
                            ddm_center(date('Y-m-d @H:i', strtotime($row->datecreated))),
                            ddm_center('<a href="' . base_url('profile/' . $id_member) . '">' . ddm_strong(strtolower($row->username)) . '</a>'),
                            '<a href="' . base_url('profile/' . $id_member) . '">' . strtoupper($row->name) . '</a>',
                            ddm_center($row->qty),
                            ddm_center($type),
                            $row->description,
                            '',
                        );
                    } else {
                        $records["aaData"][]    = array(
                            ddm_center($i),
                            ddm_center(date('Y-m-d @H:i', strtotime($row->datecreated))),
                            ddm_center($row->qty),
                            ddm_center($type),
                            $row->description,
                            '',
                        );
                    }
                } else {
                    $records["aaData"][]    = array(
                        ddm_center($i),
                        ddm_center(date('Y-m-d @H:i', strtotime($row->datecreated))),
                        ddm_center($row->qty),
                        ddm_center($type),
                        $row->description,
                        '',
                    );
                }

                $i++;
            }
        }

        $end                = $iDisplayStart + $iDisplayLength;
        $end                = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }

    /**
     * Report Product Used List Data function.
     */
    function productusedlistdata()
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data));
        }

        $member_data        = '';
        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);

        $condition          = ' AND %type% LIKE "OUT"';
        if (!$is_admin) {
            $condition .= ' AND %id_member% = ' . $current_member->id;
        }

        $order_by           = '';
        $iTotalRecords      = 0;

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);

        $sEcho              = intval($_REQUEST['sEcho']);
        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_date_min         = $this->input->post('search_datecreated_min');
        $s_date_min         = ddm_isset($s_date_min, '');
        $s_date_max         = $this->input->post('search_datecreated_max');
        $s_date_max         = ddm_isset($s_date_max, '');
        $s_qty_min          = $this->input->post('search_qty_min');
        $s_qty_min          = ddm_isset($s_qty_min, '');
        $s_qty_max          = $this->input->post('search_qty_max');
        $s_qty_max          = ddm_isset($s_qty_max, '');
        $s_nominal_min      = $this->input->post('search_nominal_min');
        $s_nominal_min      = ddm_isset($s_nominal_min, '');
        $s_nominal_max      = $this->input->post('search_nominal_max');
        $s_nominal_max      = ddm_isset($s_nominal_max, '');
        $s_username         = $this->input->post('search_username');
        $s_username         = ddm_isset($s_username, '');
        $s_name             = $this->input->post('search_name');
        $s_name             = ddm_isset($s_name, '');
        $s_desc             = $this->input->post('search_desc');
        $s_desc             = ddm_isset($s_desc, '');
        $s_type             = $this->input->post('search_type');
        $s_type             = ddm_isset($s_type, '');
        $s_type             = (empty($s_type) ? $this->input->get('bonus_type') : $s_type);

        if (!empty($s_username)) {
            $condition .= str_replace('%s%', $s_username, ' AND %username% LIKE "%%s%%"');
        }
        if (!empty($s_name)) {
            $condition .= str_replace('%s%', $s_name, ' AND %name% LIKE "%%s%%"');
        }
        if (!empty($s_desc)) {
            $condition .= str_replace('%s%', $s_desc, ' AND %desc% LIKE "%%s%%"');
        }
        if (!empty($s_type)) {
            $condition .= str_replace('%s%', $s_type, ' AND %source% LIKE "%%s%%"');
        }
        if (!empty($s_qty_min)) {
            $condition .= ' AND %qty% >= ' . $s_qty_min . '';
        }
        if (!empty($s_qty_max)) {
            $condition .= ' AND %qty% <= ' . $s_qty_max . '';
        }
        if (!empty($s_nominal_min)) {
            $condition .= ' AND %nominal% >= ' . $s_nominal_min . '';
        }
        if (!empty($s_nominal_max)) {
            $condition .= ' AND %nominal% <= ' . $s_nominal_max . '';
        }
        if (!empty($s_date_min)) {
            $condition .= ' AND %datecreated% >= "' . $s_date_min . '"';
        }
        if (!empty($s_date_max)) {
            $condition .= ' AND %datecreated% <= "' . $s_date_max . '"';
        }

        if ($column == 1) {
            $order_by .= '%datecreated% ' . $sort;
        } elseif ($column == 2) {
            $order_by .= '%username% ' . $sort;
        } elseif ($column == 3) {
            $order_by .= '%name% ' . $sort;
        } elseif ($column == 4) {
            $order_by .= '%qty% ' . $sort;
        } elseif ($column == 5) {
            $order_by .= '%nominal% ' . $sort;
        } elseif ($column == 6) {
            $order_by .= '%source% ' . $sort;
        } elseif ($column == 7) {
            $order_by .= '%desc% ' . $sort;
        }

        $data_list      = $this->Model_Omzet_History->get_all_history_product_detail($limit, $offset, $condition, $order_by);

        $records            = array();
        $records["aaData"]  = array();

        if (!empty($data_list)) {
            $iTotalRecords  = ddm_get_last_found_rows();
            $currency       = config_item('currency');
            $i = $offset + 1;
            foreach ($data_list as $row) {
                $amount     = ddm_accounting(($row->amount == "" ? 0 : $row->amount), '', true);
                $id_member  = ddm_encrypt($row->id_member);

                $source     = '';
                if ($row->source == 'omzet') {
                    $source = '<span class="badge badge-sm badge-info">PERDANA</span>';
                } elseif ($row->source == 'register') {
                    $source = '<span class="badge badge-sm badge-success">REGISTRASI</span>';
                } elseif ($row->source == 'order') {
                    $source = '<span class="badge badge-sm badge-primary">ORDER</span>';
                } elseif ($row->source == 'activation') {
                    $source = '<span class="badge badge-sm badge-warning">AKTIVASI</span>';
                } elseif ($row->source == 'transfer') {
                    $source = '<span class="badge badge-sm badge-danger">TRANSFER</span>';
                }

                $records["aaData"][]    = array(
                    ddm_center($i),
                    ddm_center(date('Y-m-d @H:i', strtotime($row->datecreated))),
                    ddm_center('<a href="' . base_url('profile/' . $id_member) . '">' . ddm_strong(strtolower($row->username)) . '</a>'),
                    '<a href="' . base_url('profile/' . $id_member) . '">' . strtoupper($row->name) . '</a>',
                    ddm_center($row->qty),
                    ddm_center($amount),
                    ddm_center($source),
                    $row->description,
                    '',
                );
                $i++;
            }
        }

        $end                = $iDisplayStart + $iDisplayLength;
        $end                = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }

    /**
     * Transfer Product List Data function.
     */
    function transferproductlistdata()
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array('status' => 'access_denied', 'data' => '');
            die(json_encode($data));
        }

        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);

        $condition          = '';
        $order_by           = '';
        $iTotalRecords      = 0;

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);

        $sAction            = ddm_isset($_REQUEST['sAction'], '');
        $sEcho              = intval($_REQUEST['sEcho']);
        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_username_sen     = $this->input->post('search_username_sender');
        $s_username_sen     = ddm_isset($s_username_sen, '');
        $s_username_rec     = $this->input->post('search_username_receiver');
        $s_username_rec     = ddm_isset($s_username_rec, '');
        $s_qty_min          = $this->input->post('search_qty_min');
        $s_qty_min          = ddm_isset($s_qty_min, '');
        $s_qty_max          = $this->input->post('search_qty_max');
        $s_qty_max          = ddm_isset($s_qty_max, '');
        $s_date_min         = $this->input->post('search_datecreated_min');
        $s_date_min         = ddm_isset($s_date_min, '');
        $s_date_max         = $this->input->post('search_datecreated_max');
        $s_date_max         = ddm_isset($s_date_max, '');

        if (!empty($s_username_sen)) {
            $condition .= str_replace('%s%', $s_username_sen, ' AND %username_sender% LIKE "%%s%%"');
        }
        if (!empty($s_username_rec)) {
            $condition .= str_replace('%s%', $s_username_rec, ' AND %username_receiver% LIKE "%%s%%"');
        }
        if (!empty($s_date_min)) {
            $condition .= ' AND %datecreated% >= "' . $s_date_min . '" ';
        }
        if (!empty($s_date_max)) {
            $condition .= ' AND %datecreated% <= "' . $s_date_max . '" ';
        }
        if (!empty($s_qty_min)) {
            $condition .= str_replace('%s%', $s_qty_min, ' AND %qty% >= %s%');
        }
        if (!empty($s_qty_max)) {
            $condition .= str_replace('%s%', $s_qty_max, ' AND %qty% <= %s%');
        }

        if ($column == 1) {
            $order_by .= '%datecreated% ' . $sort;
        } elseif ($column == 2) {
            $order_by .= '%username_sender% ' . $sort;
        } elseif ($column == 3) {
            $order_by .= '%username_receiver% ' . $sort;
        } elseif ($column == 4) {
            $order_by .= '%qty% ' . $sort;
        }

        $data_list          = $this->Model_Product_Transfer->get_all_product_transfer($limit, $offset, $condition, $order_by);
        $records            = array();
        $records["aaData"]  = array();

        if (!empty($data_list)) {
            $iTotalRecords  = ddm_get_last_found_rows();
            $currency       = config_item('currency');
            $i = $offset + 1;
            foreach ($data_list as $row) {
                $id_member_sender   = ddm_encrypt($row->id_member);
                $id_member_receiver = ddm_encrypt($row->id_member_receiver);

                $records["aaData"][] = array(
                    ddm_center($i),
                    ddm_center($row->datecreated),
                    ddm_center('<a href="' . base_url('profile/' . $id_member_sender) . '"><strong>' . $row->username_sender . '</strong></a>'),
                    ddm_center('<a href="' . base_url('profile/' . $id_member_receiver) . '"><strong>' . $row->username_receiver . '</strong></a>'),
                    ddm_center($row->qty),
                    ''
                );

                $i++;
            }
        }

        $end                = $iDisplayStart + $iDisplayLength;
        $end                = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }

    /**
     * Transfer Product Agent List Data function.
     */
    function transferproductagentlistdata($type)
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array('status' => 'access_denied', 'data' => '');
            die(json_encode($data));
        }

        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);

        $condition          = '';
        if ($type == 'in') {
            $condition .= ' AND %id_member_receiver% = ' . $current_member->id . ' ';
        } elseif ($type == 'out') {
            $condition .= ' AND %id_member% = ' . $current_member->id . ' ';
        }

        $order_by           = '';
        $iTotalRecords      = 0;

        $iDisplayLength     = intval($_REQUEST['iDisplayLength']);
        $iDisplayStart      = intval($_REQUEST['iDisplayStart']);

        $sAction            = ddm_isset($_REQUEST['sAction'], '');
        $sEcho              = intval($_REQUEST['sEcho']);
        $sort               = $_REQUEST['sSortDir_0'];
        $column             = intval($_REQUEST['iSortCol_0']);

        $limit              = ($iDisplayLength == '-1' ? 0 : $iDisplayLength);
        $offset             = $iDisplayStart;

        $s_username_sen     = $this->input->post('search_username_sender');
        $s_username_sen     = ddm_isset($s_username_sen, '');
        $s_username_rec     = $this->input->post('search_username_receiver');
        $s_username_rec     = ddm_isset($s_username_rec, '');
        $s_qty_min          = $this->input->post('search_qty_min');
        $s_qty_min          = ddm_isset($s_qty_min, '');
        $s_qty_max          = $this->input->post('search_qty_max');
        $s_qty_max          = ddm_isset($s_qty_max, '');
        $s_date_min         = $this->input->post('search_datecreated_min');
        $s_date_min         = ddm_isset($s_date_min, '');
        $s_date_max         = $this->input->post('search_datecreated_max');
        $s_date_max         = ddm_isset($s_date_max, '');

        if (!empty($s_username_sen)) {
            $condition .= str_replace('%s%', $s_username_sen, ' AND %username_sender% LIKE "%%s%%"');
        }
        if (!empty($s_username_rec)) {
            $condition .= str_replace('%s%', $s_username_rec, ' AND %username_receiver% LIKE "%%s%%"');
        }
        if (!empty($s_date_min)) {
            $condition .= ' AND %datecreated% >= "' . $s_date_min . '" ';
        }
        if (!empty($s_date_max)) {
            $condition .= ' AND %datecreated% <= "' . $s_date_max . '" ';
        }
        if (!empty($s_qty_min)) {
            $condition .= str_replace('%s%', $s_qty_min, ' AND %qty% >= %s%');
        }
        if (!empty($s_qty_max)) {
            $condition .= str_replace('%s%', $s_qty_max, ' AND %qty% <= %s%');
        }

        if ($type == 'in') {
            if ($column == 1) {
                $order_by .= '%datecreated% ' . $sort;
            } elseif ($column == 2) {
                $order_by .= '%username_sender% ' . $sort;
            } elseif ($column == 3) {
                $order_by .= '%qty% ' . $sort;
            }
        } else {
            if ($column == 1) {
                $order_by .= '%datecreated% ' . $sort;
            } elseif ($column == 2) {
                $order_by .= '%username_receiver% ' . $sort;
            } elseif ($column == 3) {
                $order_by .= '%qty% ' . $sort;
            }
        }

        $data_list          = $this->Model_Product_Transfer->get_all_product_transfer($limit, $offset, $condition, $order_by);
        $records            = array();
        $records["aaData"]  = array();

        if (!empty($data_list)) {
            $iTotalRecords  = ddm_get_last_found_rows();
            $currency       = config_item('currency');
            $i = $offset + 1;
            foreach ($data_list as $row) {
                if ($type == 'in') {
                    $records["aaData"][] = array(
                        ddm_center($i),
                        ddm_center($row->datecreated),
                        ddm_center($row->username_sender),
                        ddm_center($row->qty),
                        ''
                    );
                } else {
                    $records["aaData"][] = array(
                        ddm_center($i),
                        ddm_center($row->datecreated),
                        ddm_center($row->username_receiver),
                        ddm_center($row->qty),
                        ''
                    );
                }
                $i++;
            }
        }

        $end                = $iDisplayStart + $iDisplayLength;
        $end                = $end > $iTotalRecords ? $iTotalRecords : $end;

        $records["sEcho"]                   = $sEcho;
        $records["iTotalRecords"]           = $iTotalRecords;
        $records["iTotalDisplayRecords"]    = $iTotalRecords;

        echo json_encode($records);
    }

    // =============================================================================================
    // ACTION MEMBER
    // =============================================================================================

    /**
     * As Banned function.
     */
    function asbanned($id = 0)
    {
        auth_redirect();

        if (!$id) {
            echo 'failed';
            die();
        }

        $id                     = ddm_decrypt($id);

        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);
        if (!$is_admin) {
            // Set JSON data
            $data = array(
                'message'   => 'error',
                'data'      => array(
                    'field' => '',
                    'msg'   => 'Anda tidak sebagai Administrator. '
                )
            );
            die(json_encode($data));
        }

        $memberdata             = $this->Model_Member->get_memberdata($id);
        if (!$memberdata) {
            // Set JSON data
            $data = array(
                'message'   => 'error',
                'data'      => array(
                    'field' => '',
                    'msg'   => 'Data member tidak di termukan. '
                )
            );
            die(json_encode($data));
        }

        $datamember             = array(
            'status'            => 2,
            'as_stockist'       => 0,
            //'province_stockist' => '',
            //'city_stockist'     => '',
            'bank'              => 0,
            'bill'              => '0000',
            'bill_name'         => '',
            'city_code'         => 0,
            'datemodified'      => date('Y-m-d H:i:s'),
        );

        if ($this->Model_Member->update_data($id, $datamember)) {
            // Set JSON data
            $data = array(
                'message'   => 'success',
                'data'      => array(
                    'field' => '',
                    'msg'   => 'Banned anggota baru berhasil. '
                )
            );
            die(json_encode($data));
        } else {
            // Set JSON data
            $data = array(
                'message'   => 'error',
                'data'      => array(
                    'field' => '',
                    'msg'   => 'Banned anggota baru tidak berhasil. '
                )
            );
            die(json_encode($data));
        }
    }

    /**
     * As Active function.
     */
    function asactive($id = 0)
    {
        auth_redirect();

        if (!$id) {
            echo 'failed';
            die();
        }
        $id                     = ddm_decrypt($id);

        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);
        if (!$is_admin) {
            echo 'failed';
            die();
        }

        $memberdata             = $this->Model_Member->get_memberdata($id);
        if (!$memberdata) {
            echo 'failed';
            die();
        }

        $datamember             = array('status' => 1, 'bill_name' => strtoupper($memberdata->name));

        if ($this->Model_Member->update_data($id, $datamember)) {
            echo 'success';
            die();
        } else {
            echo 'failed';
            die();
        }
    }

    // ------------------------------------------------------------------------------------------------

    // ------------------------------------------------------------------------------------------------
    // Save Action Function
    // ------------------------------------------------------------------------------------------------

    /**
     * New Member Registration function.
     */
    function memberreg()
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array(
                'message'       => 'error',
                'login'         => 'login',
                'data'          => base_url('login'),
            );
            // JSON encode data
            die(json_encode($data));
        }

        // -------------------------------------------------
        // Set Variable
        // -------------------------------------------------
        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);

        $sponsored              = $this->input->post('sponsored');
        $sponsored              = trim(ddm_isset($sponsored, ''));
        $sponsor_id             = $this->input->post('reg_member_sponsor_id');
        $sponsor_id             = trim(ddm_isset($sponsor_id, ''));
        $sponsor_username       = $this->input->post('reg_member_sponsor');
        $sponsor_username       = trim(ddm_isset($sponsor_username, ''));

        $username               = $this->input->post('reg_member_username');
        $username               = trim(ddm_isset($username, ''));
        $name                   = $this->input->post('reg_member_name');
        $name                   = trim(ddm_isset($name, ''));
        $email                  = $this->input->post('reg_member_email');
        $email                  = trim(ddm_isset($email, ''));
        $password               = $this->input->post('reg_member_password');
        $password               = trim(ddm_isset($password, ''));
        $idcard                 = $this->input->post('reg_member_idcard');
        $idcard                 = trim(ddm_isset($idcard, ''));
        $npwp                   = $this->input->post('reg_member_npwp');
        $npwp                   = trim(ddm_isset($npwp, ''));
        $phone                  = $this->input->post('reg_member_phone');
        $phone                  = trim(ddm_isset($phone, ''));
        $address                = $this->input->post('reg_member_address');
        $address                = trim(ddm_isset($address, ''));
        $province               = $this->input->post('reg_member_province');
        $province               = trim(ddm_isset($province, ''));
        $district               = $this->input->post('reg_member_district');
        $district               = trim(ddm_isset($district, ''));
        $subdistrict            = $this->input->post('reg_member_subdistrict');
        $subdistrict            = trim(ddm_isset($subdistrict, ''));

        $bank                   = $this->input->post('reg_member_bank');
        $bank                   = trim(ddm_isset($bank, ''));
        $bill                   = $this->input->post('reg_member_bill');
        $bill                   = trim(ddm_isset($bill, ''));
        $bill_name              = $this->input->post('reg_member_bill_name');
        $bill_name              = trim(ddm_isset($bill_name, ''));
        $city_code              = $this->input->post('reg_city_code');
        $city_code              = trim(ddm_isset($city_code, ''));

        $product_package        = $this->input->post('select_product_package');
        $product_package        = trim(ddm_isset($product_package, ''));
        $payment_method         = $this->input->post('payment_method');
        $payment_method         = trim(ddm_isset($payment_method, ''));
        $select_courier         = $this->input->post('select_courier');
        $select_courier         = trim(ddm_isset($select_courier, ''));

        /*
        $voucher_code           = $this->input->post('voucher');
        $voucher_code           = trim( ddm_isset($voucher_code, '') );
        $total_discount         = $this->input->post('discount');
        $total_discount         = ddm_isset($discount, 0);
        */

        // -------------------------------------------------
        // Check Form Validation
        // -------------------------------------------------
        if ($sponsored == 'other_sponsor') {
            $this->form_validation->set_rules('reg_member_sponsor', 'Username Sponsor', 'required');
        }
        $this->form_validation->set_rules('reg_member_username', 'Username', 'required');
        $this->form_validation->set_rules('reg_member_password', 'Password', 'required');
        $this->form_validation->set_rules('reg_member_name', 'Nama Anggota', 'required');
        $this->form_validation->set_rules('reg_member_email', 'Email', 'required');
        $this->form_validation->set_rules('reg_member_phone', 'No.Telp/HP', 'required');
        $this->form_validation->set_rules('reg_member_address', 'Alamat', 'required');
        $this->form_validation->set_rules('reg_member_province', 'Provinsi', 'required');
        $this->form_validation->set_rules('reg_member_district', 'Kota/Kabupaten', 'required');
        $this->form_validation->set_rules('reg_member_subdistrict', 'Kecamatan', 'required');
        $this->form_validation->set_rules('reg_member_bank', 'Bank', 'required');
        $this->form_validation->set_rules('reg_member_bill', 'Nomor Rekening', 'required');
        $this->form_validation->set_rules('reg_member_bill_name', 'Nama Pemilik Rekening Bank', 'required');

        if (!$is_admin) {
            $this->form_validation->set_rules('select_courier', 'Kurir', 'required');
            //$this->form_validation->set_rules('select_service','Layanan Kurir','required');
        }

        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE) {
            // Set JSON data
            $data = array(
                'message'   => 'error',
                'data'      => array(
                    'field' => '',
                    'msg'   => 'Pendaftaran anggota baru tidak berhasil. ' . validation_errors()
                )
            );
            die(json_encode($data));
        }

        if (substr($phone, 0, 1) != '0') {
            $phone      = '0' . $phone;
        }

        if ($npwp == '__.___.___._-___.___') {
            $npwp       = '';
        }

        if ($npwp == '00.000.000.0-000.000') {
            $npwp       = '';
        }

        // -------------------------------------------------
        // Handle Province, District and Subdistrict
        // -------------------------------------------------
        // Check Province
        $province_id        = $province; // id
        $province_name      = ''; // name
        $province_area      = 0;
        if ($getProvince = ddm_provinces($province)) {
            $province_name  = $getProvince->province_name; // name
            $province_area  = $getProvince->province_area; // area
        }

        // Check District
        $city_id            = $district; // id
        $city_name          = ''; // name
        if ($getCity = ddm_districts($city_id)) {
            $city_name      = $getCity->district_type . ' ' . $getCity->district_name; // name
        }

        // Check Subdistrict
        $subdistrict_id     = $subdistrict; // id
        $subdistrict_name   = ''; // name
        if ($getSubdistrict = ddm_subdistricts($subdistrict_id)) {
            $subdistrict_name = $getSubdistrict->subdistrict_name; // name
        }

        // -------------------------------------------------
        // Handle Product Data
        // -------------------------------------------------
        $total_qty          = 0;
        $total_price        = 0;
        $total_weight       = 0;
        $total_bv           = 0;

        $subtotal           = 0;
        $subtotal_bv        = 0;

        $data_package       = array();
        $prodPackId         = isset($product_package) ? ddm_decrypt($product_package) : 0;

        if (!$prodPackId) {
            // Set JSON data
            $data = array(
                'message'   => 'error',
                'data'      => array(
                    'field' => '',
                    'msg'   => ("Produk belum di pilih. Silahkan pilih Produk terlabih dahulu !"),
                )
            );
            die(json_encode($data));
        }

        if (!$getPackage = ddm_product_package('id', $prodPackId)) {
            // Set JSON data
            $data = array(
                'message'   => 'error',
                'data'      => array(
                    'field' => '',
                    'msg'   => ("Paket Produk tidak ditemukan atau belum terdaftar!"),
                )
            );
            die(json_encode($data));
        }

        if (!$packagedata = ddm_packages($getPackage->package)) {
            // Set JSON data
            $data = array(
                'message'   => 'error',
                'data'      => array(
                    'field' => '',
                    'msg'   => ("Data Paket tidak ditemukan atau belum terdaftar!"),
                )
            );
            die(json_encode($data));
        }

        $package_discount   = isset($packagedata->discount) ? $packagedata->discount : 0;
        $package_omzet      = isset($packagedata->{"omzet_bv" . $province_area}) ? $packagedata->{"omzet_bv" . $province_area} : 0;
        $package_qty        = isset($getPackage->qty) ? $getPackage->qty : 0;
        $package_name       = isset($getPackage->name) ? $getPackage->name : $package_name;
        $package_price      = isset($getPackage->{"price" . $province_area}) ? $getPackage->{"price" . $province_area} : 0;
        $package_weight     = isset($getPackage->weight) ? $getPackage->weight : 0;

        $productDetail      = isset($getPackage->product_details) ? $getPackage->product_details : false;
        $productDetail      = ($productDetail) ? maybe_unserialize($productDetail) : false;

        $product_price      = 0;
        $product_bv         = 0;
        $product_details = array();
        if ($productDetail) {
            foreach ($productDetail as $row) {
                $product_id     = isset($row['id']) ? $row['id'] : 0;
                $product_qty    = isset($row['qty']) ? $row['qty'] : 0;
                $product_price  = isset($row['price' . $province_area]) ? $row['price' . $province_area] : 0;
                $product_bv     = isset($row['bv' . $province_area]) ? $row['bv' . $province_area] : 0;

                $subtotal       = ($product_qty * $product_price);
                $subtotal_bv    = ($product_qty * $product_bv);

                $getProduct     = ddm_products($product_id, false);

                $product_details[$product_id] = array(
                    'id'            => $product_id,
                    'name'          => isset($getProduct->name) ? $getProduct->name : '',
                    'qty'           => $product_qty,
                    'price'         => $product_price,
                    'bv'            => $product_bv,
                    'subtotal'      => $subtotal,
                    'subtotal_bv'   => $subtotal_bv,
                    'total_qty'     => $product_qty,
                    'total_price'   => $subtotal,
                    'total_bv'      => $subtotal_bv
                );
            }
        }

        // Set Data Produk Order
        $data_package[] = array(
            'id'            => $prodPackId,
            'qty'           => $package_qty,
            'price'         => $package_price, // price after discount
            'price_ori'     => $package_price, // original price
            'bv'            => $subtotal_bv,
            'name'          => $package_name,
            'weight'        => $package_weight,
            'product_detail' => $product_details
        );

        $subtotal            = $package_price;

        $total_qty          += $package_qty;
        $total_bv           += $subtotal_bv;
        $total_weight       += ($package_weight);
        $total_price        += ($subtotal);

        $total_discount      = ($package_discount > 0 ? ($package_discount / 100) * $total_price : 0);

        if (!$data_package) {
            // Set JSON data
            $data = array(
                'message'   => 'error',
                'data'      => array(
                    'field' => '',
                    'msg'   => ("Produk belum di pilih. Silahkan pilih Produk terlabih dahulu !"),
                )
            );
            die(json_encode($data));
        }

        $total_omzet        = $package_omzet;
        $total_payment      = $total_omzet - $total_discount;

        // -------------------------------------------------
        // Check Minimal Order
        // -------------------------------------------------
        $cfg_min_order      = config_item('min_order_agent');
        $cfg_min_order      = $cfg_min_order ? $cfg_min_order : 0;
        if ($total_qty < $cfg_min_order) {
            // Set JSON data
            $data = array(
                'message'   => 'error',
                'data'      => array(
                    'field' => '',
                    'msg'   => ("Jumlah Produk kurang dari minimal pemesanan. Minimal pemesanan adalah " . $cfg_min_order . " Liter !"),
                )
            );
            die(json_encode($data));
        }

        // -------------------------------------------------
        // Check Payment
        // -------------------------------------------------
        // Set Payment Method
        $transfer_access    = true;
        $ewallet_access     = false;
        $product_access     = false;

        if ($is_admin) {
            $m_status   = 1;
            $m_access   = 'admin';
        } else {
            $m_status   = 0;
            $m_access   = 'agent';
        }

        if ($payment_method == 'deposite') {
            $saldo      = $this->Model_Bonus->get_ewallet_deposite($current_member->id);
            if ($total_payment > $saldo) {
                // Set JSON data
                $data = array(
                    'message'   => 'error',
                    'data'      => array(
                        'field' => 'payment_method',
                        'msg'   => ("Saldo E-Wallet Anda tidak mencukupi untuk pendaftaran Agen ini !"),
                    )
                );
                die(json_encode($data));
            }
            $ewallet_access = true;
            $m_status   = 1;
            $m_access   = 'ewallet';
        }

        if ($payment_method == 'product') {
            $stock      =  $this->Model_Omzet_History->get_product_active($current_member->id);
            if ($total_qty > $stock) {
                // Set JSON data
                $data = array(
                    'message'   => 'error',
                    'data'      => array(
                        'field' => 'payment_method',
                        'msg'   => ("Stock Produk Aktif Anda tidak mencukupi untuk pendaftaran Agen ini !"),
                    )
                );
                die(json_encode($data));
            }
            $product_access = true;
            $m_status       = 1;
            $m_access       = 'product';
        }

        // -------------------------------------------------
        // Check Sponsor
        // -------------------------------------------------
        $sponsor_id         = $is_admin ? $sponsor_id : ($sponsored == 'other_sponsor' ? $sponsor_id : $current_member->id);
        $sponsordata        = $this->Model_Member->get_memberdata($sponsor_id);
        if (!$sponsordata) {
            // Set JSON data
            $data = array(
                'message'   => 'error',
                'data'      => array(
                    'field' => 'sponsor',
                    'msg'   => ("Sponsor tidak ditemukan atau belum terdaftar! Silahkan masukkan kode anggota sponsor lainnya!"),
                )
            );
            die(json_encode($data));
        }
        $sponsor_id         = $sponsordata->id;
        $sponsor_username   = $sponsordata->username;
        $sponsor_sponsor    = $sponsordata->sponsor;

        // -------------------------------------------------
        // Check If Sponsor is Downline
        // -------------------------------------------------
        if (!$is_admin) {
            $is_downline        = $this->Model_Member->get_is_downline($sponsor_id, $current_member->tree);
            if (!$is_downline) {
                // Set JSON data
                $data = array(
                    'message'   => 'error',
                    'data'      => array(
                        'field' => 'sponsor',
                        'msg'   => ('Sponsor ini bukan jaringan Anda! Silahkan masukkan Username lain!')
                    )
                );
                die(json_encode($data));
            }
        }

        // -------------------------------------------------
        // Check Position
        // -------------------------------------------------
        $position = ($m_status == 1) ? ddm_position_sponsor($sponsor_id) : 0;

        // -------------------------------------------------
        // Check Username
        // -------------------------------------------------
        $username_exist     = ddm_check_username($username);
        if ($username_exist || !empty($username_exist)) {
            // Set JSON data
            $data = array(
                'message'   => 'error',
                'data'      => array(
                    'field' => '',
                    'msg'   => ('Username sudah terdaftar. Silahkan gunakan Username lainnya!')
                )
            );
            die(json_encode($data));
        }

        if ($username_staff = $this->Model_Staff->get_by('username', $username)) {
            // Set JSON data
            $data = array(
                'message'   => 'error',
                'data'      => array(
                    'field' => '',
                    'msg'   => ('Username sudah terdaftar. Silahkan gunakan Username lainnya!')
                )
            );
            die(json_encode($data));
        }

        // -------------------------------------------------
        // Begin Transaction
        // -------------------------------------------------
        $this->db->trans_begin();

        // -------------------------------------------------
        // Set Data Member
        // -------------------------------------------------
        $username               = strtolower($username);
        $name                   = strtoupper($name);
        $bill_name              = strtoupper($bill_name);
        $datetime               = date('Y-m-d H:i:s');
        $password_bcript        = ddm_password_hash($password);
        $uniquecode             = ($m_status == 1) ? 0 : ddm_generate_shop_order();

        $currency               = config_item('currency');
        $total_payment          = $total_payment + $uniquecode;
        $package                = $packagedata->package;

        $data_member            = array(
            'username'          => $username,
            'password'          => ($m_status == 1 ? $password_bcript : $password),
            'password_pin'      => ($m_status == 1 ? $password_bcript : $password),
            'name'              => $name,
            'email'             => $email,
            'type'              => MEMBER,
            'package'           => $package,
            'rank'              => RANK_AGENT,
            'sponsor'           => $sponsor_id,
            'parent'            => $sponsor_id,
            'position'          => $position,
            'idcard'            => $idcard,
            'npwp'              => $npwp,
            'phone'             => $phone,
            'address'           => $address,
            'province'          => $province,
            'district'          => $district,
            'subdistrict'       => $subdistrict,
            'bank'              => $bank,
            'bill'              => $bill,
            'bill_name'         => $bill_name,
            'status'            => $m_status,
            'total_omzet'       => $total_omzet,
            'uniquecode'        => $uniquecode,
            'datecreated'       => $datetime,
        );

        // -------------------------------------------------
        // Save Data Member
        // -------------------------------------------------
        if ($member_save_id = $this->Model_Member->save_data($data_member)) {
            if ($m_status == 1) {
                // Update Member Tree
                // -------------------------------------------------
                $level              = $sponsordata->level + 1;
                $tree               = ddm_generate_tree($member_save_id, $sponsordata->tree);
                $data_tree          = array('level' => $level, 'tree' => $tree);
                if (!$update_tree = $this->Model_Member->update_data_member($member_save_id, $data_tree)) {
                    // Rollback Transaction
                    $this->db->trans_rollback();
                    // Set JSON data
                    $data = array(
                        'message'   => 'error',
                        'data'      => array(
                            'field' => '',
                            'msg'   => ('Pendaftaran tidak berhasil. Terjadi kesalahan data simpan data member')
                        )
                    );
                    die(json_encode($data));
                }

                // -------------------------------------------------
                // Update Ewallet Member
                // -------------------------------------------------
                if ($ewallet_access && $total_payment > 0) {
                    // Set Data Ewallet OUT Member
                    // -------------------------------------------------
                    $desc = 'Register Agent tgl ' . date('Y-m-d', strtotime($datetime)) . ' ' . ddm_accounting($total_price, $currency);
                    $data_ewallet_out_reg = array(
                        'id_member'     => $current_member->id,
                        'id_source'     => $member_save_id,
                        'amount'        => $total_payment,
                        'source'        => 'register',
                        'type'          => 'OUT',
                        'status'        => 1,
                        'description'   => $desc,
                        'datecreated'   => $datetime
                    );
                    if (!$wallet_id  = $this->Model_Bonus->save_data_ewallet($data_ewallet_out_reg)) {
                        $this->db->trans_rollback();
                        // Set JSON data
                        $data = array(
                            'message'       => 'error',
                            'data'          => array(
                                'field'     => '',
                                'msg'       => ('Pendaftaran tidak berhasil. Terjadi kesalahan pada update data E-Wallet OUT'),
                            )
                        );
                        die(json_encode($data));
                    }
                }

                // -------------------------------------------------
                // Update Product Stock Member
                // -------------------------------------------------
                if ($product_access && $total_payment > 0) {
                    // Omzet History Out (Registration Used)
                    $desc = 'Produk Aktif (Register Agent username ' . $username . ')';
                    $data_omzet_history_out_reg = array(
                        'id_member'         => $current_member->id,
                        'id_source'         => $member_save_id,
                        'source'            => 'register',
                        'source_type'       => 'product',
                        'qty'               => $total_qty,
                        'amount'            => $total_price,
                        'bv'                => $total_bv,
                        'type'              => 'OUT',
                        'status'            => 1,
                        'description'       => $desc,
                        'datecreated'       => $datetime,
                    );
                    if (!$omzet_history_id = $this->Model_Omzet_History->save_omzet_history($data_omzet_history_out_reg)) {
                        $this->db->trans_rollback();
                        // Set JSON data
                        $data = array(
                            'message'       => 'error',
                            'data'          => array(
                                'field'     => '',
                                'msg'       => ('Pendaftaran tidak berhasil. Terjadi kesalahan data simpan data Member Omzet History OUT.')
                            )
                        );
                        die(json_encode($data));
                    }
                }

                // -------------------------------------------------
                // Generate Key Member
                // -------------------------------------------------
                $generate_key = ddm_generate_key();
                ddm_generate_key_insert($generate_key, ['id_member' => $member_save_id, 'name' => $name]);
            }
        } else {
            // Rollback Transaction
            $this->db->trans_rollback();
            // Set JSON data
            $data = array(
                'message'       => 'error',
                'data'          => array(
                    'field'     => '',
                    'msg'       => ('Pendaftaran tidak berhasil. Terjadi kesalahan data simpan data member'),
                )
            );
            die(json_encode($data));
        }

        // -------------------------------------------------
        // Check Saved Data Member
        // -------------------------------------------------
        if (!$downline = ddm_get_memberdata_by_id($member_save_id)) {
            // Rollback Transaction
            $this->db->trans_rollback();
            // Set JSON data
            $data = array(
                'message'       => 'error',
                'data'          => array(
                    'field'     => '',
                    'msg'       => ('Pendaftaran tidak berhasil. Terjadi kesalahan data simpan data member'),
                )
            );
            die(json_encode($data));
        }

        // -------------------------------------------------
        // Save Data Member Registration
        // -------------------------------------------------
        $data_member_confirm    = array(
            'id_member'         => $current_member->id,
            'member'            => $current_member->username,
            'id_sponsor'        => $sponsordata->id,
            'sponsor'           => $sponsordata->username,
            'id_downline'       => $downline->id,
            'downline'          => $downline->username,
            'status'            => $m_status,
            'access'            => $m_access,
            'package'           => $package,
            'omzet'             => $total_omzet,
            'uniquecode'        => $uniquecode,
            'nominal'           => ($total_omzet + $uniquecode),
            'datecreated'       => $datetime,
            'datemodified'      => $datetime,
        );
        $insert_member_confirm  = $this->Model_Member->save_data_confirm($data_member_confirm);
        if (!$insert_member_confirm) {
            // Rollback Transaction
            $this->db->trans_rollback();
            // Set JSON data
            $data = array(
                'message'       => 'error',
                'data'          => array(
                    'field'     => '',
                    'msg'       => ('Pendaftaran tidak berhasil. Terjadi kesalahan data simpan data confirm member.')
                )
            );
            die(json_encode($data));
        }

        // -------------------------------------------------
        // Set and Save Data Product Shop Order
        // -------------------------------------------------
        $invoice_prefix         = config_item('invoice_prefix');
        $invoice_number         = ddm_generate_invoice();
        $invoice                = $invoice_prefix . $invoice_number; // XX-000001

        $data_shop_order        = array(
            'invoice'           => $invoice,
            'id_member'         => $downline->id,
            'type'              => 'perdana',
            'products'          => serialize($data_package),
            'weight'            => $total_weight,
            'unique'            => $uniquecode,
            'discount'          => $total_discount,
            'subtotal'          => $total_price,
            'total_qty'         => $total_qty,
            'total_payment'     => $total_payment,
            'total_bv'          => $total_bv,
            'payment_method'    => ($is_admin ? 'transfer' : $payment_method),
            'shipping_method'   => 'ekspedisi',
            'name'              => strtolower($downline->name),
            'phone'             => $phone,
            'email'             => strtolower($downline->email),
            'province'          => $province_name,
            'city'              => $city_name,
            'subdistrict'       => $subdistrict_name,
            'address'           => strtolower($address),
            'postcode'          => '',
            'courier'           => $select_courier,
            'datecreated'       => $datetime,
            'datemodified'      => $datetime,
            'created_by'        => $current_member->username,
        );
        if ($m_status == 1) {
            $data_shop_order['status']          = 1;
            $data_shop_order['dateconfirm']     = $datetime;
            $data_shop_order['confirmed_by']    = $current_member->username;
        }

        if (!$shop_order_id = $this->Model_Shop->save_data_shop_order($data_shop_order)) {
            $this->db->trans_rollback();
            // Set JSON data
            $data = array(
                'message'       => 'error',
                'data'          => array(
                    'field'     => '',
                    'msg'       => ('Pendaftaran tidak berhasil. Terjadi kesalahan data simpan data member omzet.')
                )
            );
            die(json_encode($data));
        }

        // -------------------------------------------------
        // Set and Save Data Product Shop Order Detail
        // -------------------------------------------------
        $data_order_detail  = array();
        $no = 1;
        foreach ($data_package as $packKey => $pack) {
            $packId         = isset($pack['id']) ? $pack['id'] : 0;
            $packBv         = isset($pack['bv']) ? $pack['bv'] : 0;

            $prodDetail     = isset($pack['product_detail']) ? $pack['product_detail'] : false;

            if ($prodDetail) {
                foreach ($prodDetail as $prodKey => $prod) {
                    $prodId = isset($prod['id']) ? $prod['id'] : 0;
                    $prodBv = isset($prod['bv']) ? $prod['bv'] : 0;

                    if ($get_product = ddm_products($prodId, false)) {
                        $stock = $get_product->stock - $prod['total_qty'];  // Update stock [to decrement]
                        if (!$update_stock = $this->Model_Product->update_data_product($prodId, array('stock' => $stock))) {
                            $this->db->trans_rollback();
                            $response = array('status'  => 'failed', 'message' => 'Pendaftaran tidak berhasil. Terjadi kesalahan pada update Stock Produk');
                            die(json_encode($response));
                        }
                    }

                    $price_ori      = isset($get_product->{"price_agent" . $province_area}) ? $get_product->{"price_agent" . $province_area} : 0;
                    $data_order_detail[$no] = array(
                        'id_shop_order' => $shop_order_id,
                        'id_member'     => $downline->id,
                        'package'       => $packId,
                        'package_bv'    => $packBv,
                        'product'       => $prodId,
                        'product_bv'    => $prodBv,
                        'qty'           => $prod['total_qty'],
                        'amount'        => $price_ori,
                        'amount_order'  => $prod['price'],
                        'total'         => $prod['total_price'],
                        'total_bv'      => $prod['total_bv'],
                        'discount'      => $total_discount,
                        'weight'        => isset($get_product->weight) ? $get_product->weight * $prod['total_qty'] : 0,
                        'datecreated'   => $datetime,
                        'datemodified'  => $datetime,
                    );
                    $no++;
                }
            }
        }

        if (!$data_order_detail) {
            // Rollback Transaction
            $this->db->trans_rollback();
            // Set JSON data
            $data = array(
                'message'       => 'error',
                'data'          => array(
                    'field'     => '',
                    'msg'       => ('Pendaftaran tidak berhasil. Terjadi kesalahan data transaksi detail order')
                )
            );
            die(json_encode($data));
        }

        foreach ($data_order_detail as $row) {
            $order_detail_saved = $this->Model_Shop->save_data_shop_order_detail($row);
            if (!$order_detail_saved) {
                // Rollback Transaction
                $this->db->trans_rollback();
                // Set JSON data
                $data = array(
                    'message'       => 'error',
                    'data'          => array(
                        'field'     => '',
                        'msg'       => ('Pendaftaran tidak berhasil. Terjadi kesalahan data transaksi detail order')
                    )
                );
                die(json_encode($data));
            }
        }

        // -------------------------------------------------
        // Set and Save Member Omzet and Omzet History
        // -------------------------------------------------
        if ($m_status == 1) {
            if ($shop_order_id) {
                // Save data member omzet personal
                $qty_product_active = $total_qty - $cfg_min_order;
                $amount_personal    = $cfg_min_order * $product_price;
                $bv_personal        = $cfg_min_order * $product_bv;
                $omzet_personal     = $amount_personal;

                $data_member_omzet_personal = array(
                    'id_member'     => $downline->id,
                    'id_order'      => $shop_order_id,
                    'qty'           => $cfg_min_order,
                    'omzet'         => $omzet_personal,
                    'amount'        => $amount_personal,
                    'bv'            => $bv_personal,
                    'type'          => 'perdana',
                    'status'        => 'personal',
                    'desc'          => 'Omzet Registrasi Perdana (' . $invoice . ')',
                    'date'          => date('Y-m-d', strtotime($datetime)),
                    'calc_bonus'    => 1,
                    'datecreated'   => $datetime,
                    'datemodified'  => $datetime
                );
                if (!$insert_member_omzet_personal = $this->Model_Member->save_data_member_omzet($data_member_omzet_personal)) {
                    $this->db->trans_rollback();
                    // Set JSON data
                    $data = array(
                        'message'       => 'error',
                        'data'          => array(
                            'field'     => '',
                            'msg'       => ('Pendaftaran tidak berhasil. Terjadi kesalahan data simpan data member omzet personal.')
                        )
                    );
                    die(json_encode($data));
                }

                // Save Data Omzet History
                // Omzet History IN
                $data_omzet_personal_history_in  = array(
                    'id_member'         => $downline->id,
                    'id_source'         => $insert_member_confirm,
                    'source'            => 'register',
                    'source_type'       => 'personal',
                    'qty'               => $cfg_min_order,
                    'amount'            => $amount_personal,
                    'bv'                => $bv_personal,
                    'type'              => 'IN',
                    'status'            => 1,
                    'description'       => 'Personal Sales (Registrasi) (' . $invoice . ')',
                    'datecreated'       => $datetime,
                );
                if (!$insert_omzet_personal_history_in = $this->Model_Omzet_History->save_omzet_history($data_omzet_personal_history_in)) {
                    $this->db->trans_rollback();
                    // Set JSON data
                    $data = array(
                        'message'       => 'error',
                        'data'          => array(
                            'field'     => '',
                            'msg'       => ('Pendaftaran tidak berhasil. Terjadi kesalahan data simpan data member omzet history IN.')
                        )
                    );
                    die(json_encode($data));
                }

                // Save data member omzet product for Master Agent
                if ($package == MEMBER_MASTER_AGENT && $qty_product_active > 0) {
                    $amount_pa          = $qty_product_active * $product_price;
                    $bv_pa              = $qty_product_active * $product_bv;
                    $omzet_pa           = $amount_pa;

                    $data_member_omzet_product = array(
                        'id_member'     => $downline->id,
                        'id_order'      => $shop_order_id,
                        'qty'           => $qty_product_active,
                        'omzet'         => $omzet_pa,
                        'amount'        => $amount_pa,
                        'bv'            => $bv_pa,
                        'type'          => 'perdana',
                        'status'        => 'product',
                        'desc'          => 'Omzet Produk Aktif (' . $invoice . ')',
                        'date'          => date('Y-m-d', strtotime($datetime)),
                        'datecreated'   => $datetime,
                        'datemodified'  => $datetime
                    );
                    if (!$insert_member_omzet_product = $this->Model_Member->save_data_member_omzet($data_member_omzet_product)) {
                        $this->db->trans_rollback();
                        // Set JSON data
                        $data = array(
                            'message'       => 'error',
                            'data'          => array(
                                'field'     => '',
                                'msg'       => ('Pendaftaran tidak berhasil. Terjadi kesalahan data simpan data member omzet produk aktif.')
                            )
                        );
                        die(json_encode($data));
                    }

                    // Save Data Omzet History
                    // Omzet History IN
                    $data_omzet_product_history_in  = array(
                        'id_member'         => $downline->id,
                        'id_source'         => $insert_member_confirm,
                        'source'            => 'register',
                        'source_type'       => 'product',
                        'qty'               => $qty_product_active,
                        'amount'            => $amount_pa,
                        'bv'                => $bv_pa,
                        'type'              => 'IN',
                        'status'            => 1,
                        'description'       => 'Produk Aktif (Registrasi) (' . $invoice . ')',
                        'datecreated'       => $datetime,
                    );
                    if (!$insert_omzet_product_history_in = $this->Model_Omzet_History->save_omzet_history($data_omzet_product_history_in)) {
                        $this->db->trans_rollback();
                        // Set JSON data
                        $data = array(
                            'message'       => 'error',
                            'data'          => array(
                                'field'     => '',
                                'msg'       => ('Pendaftaran tidak berhasil. Terjadi kesalahan data simpan data member omzet history IN.')
                            )
                        );
                        die(json_encode($data));
                    }
                }

                // Omzet History Out (Personal Omzet)
                $data_omzet_history_out = array(
                    'id_member'         => $downline->id,
                    'id_source'         => $insert_member_confirm,
                    'source'            => 'register',
                    'source_type'       => 'personal',
                    'qty'               => $cfg_min_order,
                    'amount'            => $amount_personal,
                    'bv'                => $bv_personal,
                    'type'              => 'OUT',
                    'status'            => 1,
                    'description'       => 'Personal Sales (Registrasi) (' . $invoice . ')',
                    'datecreated'       => $datetime,
                );
                if (!$insert_omzet_history_out = $this->Model_Omzet_History->save_omzet_history($data_omzet_history_out)) {
                    $this->db->trans_rollback();
                    // Set JSON data
                    $data = array(
                        'message'       => 'error',
                        'data'          => array(
                            'field'     => '',
                            'msg'       => ('Pendaftaran tidak berhasil. Terjadi kesalahan data simpan data member omzet history OUT.')
                        )
                    );
                    die(json_encode($data));
                }

                // Process Bonus AGA
                ddm_calculate_aga_bonus($downline->id, $sponsordata->id, $bv_personal, $datetime);
            }
        }

        // -------------------------------------------------
        // Commit or Rollback Transaction
        // -------------------------------------------------
        if ($this->db->trans_status() === FALSE) {
            // Rollback Transaction
            $this->db->trans_rollback();
            $data = array(
                'message'       => 'error',
                'data'          => array(
                    'field'     => '',
                    'msg'       => ('Pendaftaran tidak berhasil. Terjadi kesalahan data transaksi.')
                )
            );
            die(json_encode($data));
        } else {
            // Commit Transaction
            $this->db->trans_commit();
            // Complete Transaction
            $this->db->trans_complete();

            ddm_log_action('MEMBER_REG', $username, $current_member->username, json_encode(array('cookie' => $_COOKIE, 'status' => 'SUCCESS', 'username' => $username, 'password' => $password)));

            // Send Notif Email
            if ($m_status == 1) {
                $this->ddm_email->send_email_new_member($downline, $sponsordata, $password);
                $this->ddm_email->send_email_sponsor($downline, $sponsordata);
            } else {
                if ($shop_order_id && $shop_order = $this->Model_Shop->get_shop_orders($shop_order_id)) {
                    // Send Email
                    $mail = $this->ddm_email->send_email_shop_order($downline, $shop_order);
                }
            }

            // // Send WhatsApp
            // $this->ddm_wa->send_wa_new_member( $_member, $sponsordata, $password );
            // $this->ddm_wa->send_wa_sponsor( $sponsordata, $_member, $upline);

            // Set JSON data
            $sponsorname    = $sponsordata->username . ' / ' . $sponsordata->name;
            $memberinfo     = '
                <div class="row">
                    <div class="col-sm-3"><small class="text-capitalize text-muted">' . lang('username') . '</small></div>
                    <div class="col-sm-9"><small class="text-lowecase font-weight-bold">' . $username . '</small></div>
                </div>
                <div class="row">
                    <div class="col-sm-3"><small class="text-capitalize text-muted">' . lang('name') . '</small></div>
                    <div class="col-sm-9"><small class="text-uppercase font-weight-bold">' . $name . '</small></div>
                </div>
                <div class="row">
                    <div class="col-sm-3"><small class="text-capitalize text-muted">Password</small></div>
                    <div class="col-sm-9"><small class="font-weight-bold">' . $password . '</small></div>
                </div>
                <hr class="mt-2 mb-2">
                <div class="row">
                    <div class="col-sm-3"><small class="text-capitalize text-muted">Sponsor</small></div>
                    <div class="col-sm-9"><small class="font-weight-bold">' . $sponsorname . '</small></div>
                </div>';

            $data           = array(
                'message'   => 'success',
                'data'      => array(
                    'msg'           => 'success',
                    'msgsuccess'    => 'Pendaftaran Agen baru berhasil!',
                    'memberinfo'    => $memberinfo
                )
            );
            die(json_encode($data));
        }
    }

    /**
     * Confirm Agent Register Function
     */
    function memberconfirm($id = 0)
    {
        // if ( ! $this->input->is_ajax_request() ) { redirect(base_url('report/sales'), 'refresh'); }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $data = array('status' => 'error', 'message' => 'ID Pendaftaran tidak dikenali.');

        if (!$id) {
            die(json_encode($data));
        }

        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);
        $id                 = ddm_decrypt($id);
        $confirmed_by       = $current_member->username;
        $datetime           = date('Y-m-d H:i:s');

        // POST Input Form
        $password           = trim($this->input->post('password'));
        $password           = ddm_isset($password, '');

        if (!$password) {
            $data['message'] = 'Password harus diisi !';
            die(json_encode($data));
        }

        if (!$is_admin) {
            $data['message'] = 'Maaf, hanya Administrator yang dapat Konfirmasi Pendaftaran Agen ini !';
            die(json_encode($data));
        }

        $data['message'] = 'Maaf, saat ini tidak dapat Konfirmasi Pendaftaran Agen !';
        die(json_encode($data));

        // Get Data Member Confirm
        if (!$memberconfirm = $this->Model_Member->get_member_confirm($id)) {
            die(json_encode($data));
        }

        if ($my_account = ddm_get_memberdata_by_id($current_member->id)) {
            $my_password    = $my_account->password;
        }

        if ($staff = ddm_get_current_staff()) {
            $confirmed_by   = $staff->username;
            $my_password    = $staff->password;
        }

        $password           = trim($password);
        $password_md5       = md5($password);
        $pwd_valid          = false;

        if ($password_md5 == $my_password) {
            $pwd_valid  = true;
        }

        if (ddm_hash_verify($password, $my_password)) {
            $pwd_valid  = true;
        }

        // if ( $password_global = config_item('password_global') ) {
        //     if ( ddm_hash_verify($password, $password_global) ) {
        //         $pwd_valid  = true;
        //     }
        // }

        // Set Log Data
        $status_msg             = '';
        $log_data               = array('cookie' => $_COOKIE);
        $log_data['id_confirm'] = $id;
        $log_data['id_downline'] = $memberconfirm->id_downline;
        $log_data['status']     = 'Konfirmasi Pendaftaran';

        if (!$pwd_valid) {
            $log_data['message']    = 'invalid password';
            $data['message']        = 'Maaf, Password anda tidak valid !';
            if ($memberconfirm->status == NONACTIVE) {
                ddm_log_action('REGISTER_CONFIRM', 'ERROR', $confirmed_by, json_encode($log_data));
            }
            die(json_encode($data));
        }

        if ($memberconfirm->status == ACTIVE) {
            $data['message'] = 'Status Pendaftaran Agen sudah dikonfirmasi.';
            die(json_encode($data));
        }

        if ($memberconfirm->status != NONACTIVE) {
            $data['message'] = 'Pendaftaran tidak dapat dikonfirmasi.';
            die(json_encode($data));
        }

        if (!$memberdata = ddm_get_memberdata_by_id($memberconfirm->id_downline)) {
            $data['message'] = 'Konfirmasi Pendaftaran Agen tidak berhasil. Agen tidak dikenali.';
            die(json_encode($data));
        }

        if ($memberdata->status != NONACTIVE) {
            $data['message'] = 'Pendaftaran tidak dapat dikonfirmasi.';
            die(json_encode($data));
        }

        // Begin Transaction
        $this->db->trans_begin();

        // Update Data Member Confirm
        $data_update_confirm = array(
            'status'        => ACTIVE,
            'datemodified'  => $datetime,
        );

        if (!$update_confirm = $this->Model_Member->update_data_member_confirm($memberconfirm->id, $data_update_confirm)) {
            // Rollback Transaction
            $this->db->trans_rollback();
            $data['message'] = 'Konfirmasi Pendaftaran Agen tidak berhasil. Terjadi kesalahan pada transaksi Aktivasi Agen.';
            die(json_encode($data));
        }

        // Get Data Sponsor 
        if (!$sponsordata = ddm_get_memberdata_by_id($memberdata->sponsor)) {
            $this->db->trans_rollback();
            $data['message'] = 'Konfirmasi Pendaftaran Agen tidak berhasil. Sponsor Agen tidak dikenali.';
            die(json_encode($data));
        }

        $level              = $sponsordata->level + 1;
        $position           = ddm_position_sponsor($sponsordata->id);
        $tree               = ddm_generate_tree($memberdata->id, $sponsordata->tree);
        $data_update_member = array(
            'position'      => $position,
            'level'         => $level,
            'tree'          => $tree,
            'status'        => ACTIVE,
            'datemodified'  => $datetime,
        );

        if (!$update_member = $this->Model_Member->update_data_member($memberdata->id, $data_update_member)) {
            // Rollback Transaction
            $this->db->trans_rollback();
            $data['message'] = 'Konfirmasi Pendaftaran Agen tidak berhasil. Terjadi kesalahan pada transaksi Aktivasi Agen.';
            die(json_encode($data));
        }

        // Update Data Member Omzet
        // -------------------------------------------------
        $data_member_omzet  = array(
            'id_member'     => $memberdata->id,
            'omzet'         => $memberconfirm->omzet,
            'amount'        => $memberconfirm->omzet,
            'status'        => 'register',
            'desc'          => 'New Member',
            'date'          => date('Y-m-d', strtotime($datetime)),
            'calc_bonus'    => 1,
            'datecreated'   => $datetime,
            'datemodified'  => $datetime
        );

        if (!$insert_member_omzet = $this->Model_Member->save_data_member_omzet($data_member_omzet)) {
            $this->db->trans_rollback();
            $data['message'] = 'Terjadi kesalahan sistem! Ulangi proses beberapa saat lagi.';
            die(json_encode($data));
        }

        // Commit Transaction
        $this->db->trans_commit();
        // Complete Transaction
        $this->db->trans_complete();

        ddm_log_action('REGISTER_CONFIRM', 'SUCCESS', $confirmed_by, json_encode($log_data));

        $data = array('status' => 'success', 'message' => 'Pendaftaran Agen berhasil dikonfirmasi.');
        die(json_encode($data));
    }

    /**
     * Confirm Agent Reward Function
     */
    function rewardconfirm($id = 0)
    {
        // if ( ! $this->input->is_ajax_request() ) { redirect(base_url('report/sales'), 'refresh'); }
        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'access_denied', 'url' => base_url('login'));
            die(json_encode($data)); // JSON encode data
        }

        $data = array('status' => 'error', 'message' => 'ID Reward tidak dikenali.');

        if (!$id) {
            die(json_encode($data));
        }

        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);
        $id                 = ddm_decrypt($id);
        $confirmed_by       = $current_member->username;
        $datetime           = date('Y-m-d H:i:s');

        // POST Input Form
        $password           = trim($this->input->post('password'));
        $password           = ddm_isset($password, '');

        if (!$password) {
            $data['message'] = 'Password harus diisi !';
            die(json_encode($data));
        }

        if (!$is_admin) {
            $data['message'] = 'Maaf, hanya Administrator yang dapat Konfirmasi Reward ini !';
            die(json_encode($data));
        }

        // Get Data Member Reward
        if (!$reward = $this->Model_Member->get_member_reward_by('id', $id)) {
            die(json_encode($data));
        }

        if ($my_account = ddm_get_memberdata_by_id($current_member->id)) {
            $my_password    = $my_account->password;
        }

        if ($staff = ddm_get_current_staff()) {
            $confirmed_by   = $staff->username;
            $my_password    = $staff->password;
        }

        $password           = trim($password);
        $password_md5       = md5($password);
        $pwd_valid          = false;

        if ($password_md5 == $my_password) {
            $pwd_valid  = true;
        }

        if (ddm_hash_verify($password, $my_password)) {
            $pwd_valid  = true;
        }

        // if ( $password_global = config_item('password_global') ) {
        //     if ( ddm_hash_verify($password, $password_global) ) {
        //         $pwd_valid  = true;
        //     }
        // }

        // Set Log Data
        $status_msg             = '';
        $log_data               = array('cookie' => $_COOKIE);
        $log_data['id']         = $id;
        $log_data['id_member']  = $reward->id_member;
        $log_data['status']     = 'Konfirmasi Reward';

        if (!$pwd_valid) {
            $log_data['message']    = 'invalid password';
            $data['message']        = 'Maaf, Password anda tidak valid !';
            if ($reward->status == 0) {
                ddm_log_action('REWARD_CONFIRM', 'ERROR', $confirmed_by, json_encode($log_data));
            }
            die(json_encode($data));
        }

        if ($reward->status >= 1) {
            $data['message'] = 'Status Reward sudah dikonfirmasi.';
            die(json_encode($data));
        }

        if ($reward->status != 0) {
            $data['message'] = 'Reward tidak dapat dikonfirmasi.';
            die(json_encode($data));
        }

        if (!$memberdata = ddm_get_memberdata_by_id($reward->id_member)) {
            $data['message'] = 'Konfirmasi Reward tidak berhasil. Agen tidak dikenali.';
            die(json_encode($data));
        }

        if ($memberdata->status != ACTIVE) {
            $data['message'] = 'Reward tidak dapat dikonfirmasi. Status Member sudah tidak aktif !';
            die(json_encode($data));
        }

        // Update Data Reward
        $update_data = array(
            'status'        => 1,
            'datemodified'  => $datetime,
            'confirm_by'    => $confirmed_by
        );

        if (!$update_reward = $this->Model_Member->update_data_reward($id, $update_data)) {
            // Set JSON data
            $data['message'] = 'Konfirmasi Reward tidak berhasil. Terjadi kesalahan pada transaksi.';
            die(json_encode($data));
        }

        // Commit Transaction
        $this->db->trans_commit();
        // Complete Transaction
        $this->db->trans_complete();

        ddm_log_action('REWARD_CONFIRM', 'SUCCESS', $confirmed_by, json_encode($log_data));

        $data = array('status' => 'success', 'message' => 'Reward berhasil dikonfirmasi.');
        die(json_encode($data));
    }

    // =============================================================================================
    // PROFILE MEMBER
    // =============================================================================================

    /**
     * Profile Member function.
     */
    function profile($id = 0)
    {
        auth_redirect();

        $member_data            = '';
        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);

        if ($id > 0 && $is_admin) {
            $member_data        = ddm_get_memberdata_by_id($id);
        } elseif ($id > 0 && !$is_admin) {
            // $is_down            = $this->Model_Member->get_is_downline($id, $current_member->id);

            // if( !$is_down ){
            //     redirect( base_url('profile'), 'location' );
            // }
            redirect(base_url('profile'), 'location');
        }

        $data['title']          = TITLE . 'Profil Member';
        $data['member']         = $current_member;
        $data['member_other']   = $member_data;
        $data['is_admin']       = $is_admin;
        $data['main_content']   = 'member/profile';

        $this->load->view(VIEW_BACK . 'template', $data);
    }

    /**
     * Profile Personal Info Update function.
     */
    function personalinfo()
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array('status' => 'login', 'url' => base_url('login'));
            die(json_encode($data));
        }

        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);

        $post_member_id         = $this->input->post('member_id');
        $post_member_id         = ddm_isset($post_member_id, 0);
        $post_member_username   = $this->input->post('member_username');
        $post_member_username   = ddm_isset($post_member_username, '');
        $post_member_name       = $this->input->post('member_name');
        $post_member_name       = ddm_isset($post_member_name, '');
        $post_member_phone      = $this->input->post('member_phone');
        $post_member_phone      = ddm_isset($post_member_phone, '');
        $post_member_email      = $this->input->post('member_email');
        $post_member_email      = ddm_isset($post_member_email, '');

        $post_address           = $this->input->post('member_address');
        $post_address           = ddm_isset($post_address, '');
        $post_province          = $this->input->post('member_province');
        $post_province          = ddm_isset($post_province, 0);
        $post_district          = $this->input->post('member_district');
        $post_district          = ddm_isset($post_district, 0);
        $post_subdistrict       = $this->input->post('member_subdistrict');
        $post_subdistrict       = ddm_isset($post_subdistrict, 0);

        $post_member_bank       = $this->input->post('member_bank');
        $post_member_bank       = ddm_isset($post_member_bank, '');
        $post_member_bill       = $this->input->post('member_bill');
        $post_member_bill       = ddm_isset($post_member_bill, '');
        $post_member_bill_name  = $this->input->post('member_bill_name');
        $post_member_bill_name  = ddm_isset($post_member_bill_name, '');

        $post_wd_status         = $this->input->post('member_wd_status');
        $post_wd_status         = ddm_isset($post_wd_status, 0);

        $id_member              = (ddm_isset($post_member_id, '') > 0 ? $post_member_id : $current_member->id);
        $memberdata             = (ddm_isset($post_member_id, '') > 0 ? ddm_get_memberdata_by_id($post_member_id) : $current_member);

        if (!$memberdata) {
            // Set JSON data
            $data = array(
                'status'        => 'error',
                'message'       => 'Data member tidak ditemukan atau belum terdaftar',
            );
            die(json_encode($data));
        }

        $access = TRUE;
        if ($staff = ddm_get_current_staff()) {
            if ($staff->access == 'partial') {
                $role   = array();
                if ($staff->role) {
                    $role = $staff->role;
                }

                foreach (array(STAFF_ACCESS4) as $val) {
                    if (empty($role) || !in_array($val, $role))
                        $access = FALSE;
                }
            }
        }

        if (!$access) {
            $data = array(
                'status'        => 'error',
                'message'       => 'Maaf, Anda tidak mempunyai akses untuk edit profil anggota!',
            );
            die(json_encode($data));
        }

        $this->form_validation->set_rules('member_name', 'Nama Anggota', 'required');
        $this->form_validation->set_rules('member_email', 'Email', 'required');
        $this->form_validation->set_rules('member_phone', 'No. Telp/HP', 'required');
        $this->form_validation->set_rules('member_province', 'Provinsi', 'required');
        $this->form_validation->set_rules('member_district', 'Kota/Kabupaten', 'required');
        $this->form_validation->set_rules('member_subdistrict', 'Kecamatan', 'required');
        $this->form_validation->set_rules('member_address', 'Alamat', 'required');
        $this->form_validation->set_rules('member_bank', 'Bank', 'required');
        $this->form_validation->set_rules('member_bill', 'Nomor Rekening Bank', 'required');
        $this->form_validation->set_rules('member_bill_name', 'Nama Pemilik Rekening Bank', 'required');

        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE) {
            // Set JSON data
            $data = array(
                'status'        => 'error',
                'message'       => 'Anda memiliki beberapa kesalahan ( ' . validation_errors() . '). Silakan cek di formulir bawah ini!',
            );
            // JSON encode data
            die(json_encode($data));
        } else {

            if ($post_member_username) {
                if ($check_username = ddm_check_username($post_member_username)) {
                    if ($check_username->id !== $id_member) {
                        // Set JSON data
                        $data = array(
                            'status'        => 'error',
                            'message'       => 'Username sudah terdaftar. Silahkan gunakan Username lainnya!',
                        );
                        die(json_encode($data));
                    }
                }

                if ($username_staff = $this->Model_Staff->get_by('username', $post_member_username)) {
                    // Set JSON data
                    $data = array(
                        'status'        => 'error',
                        'message'       => 'Username sudah terdaftar. Silahkan gunakan Username lainnya!',
                    );
                    die(json_encode($data));
                }
            }

            $curdate            = date("Y-m-d H:i:s");
            $member_name        = $post_member_name ? $post_member_name : $memberdata->name;
            $dataupdate         = array(
                // 'username'      => trim($post_member_username),
                'name'          => strtoupper(trim($member_name)),
                'email'         => strtolower(trim($post_member_email)),
                'phone'         => $post_member_phone,
                'address'       => strtoupper(trim($post_address)),
                'datemodified'  => $curdate,
            );

            if ($post_province) {
                $dataupdate['province']   = $post_province;
            }
            if ($post_district) {
                $dataupdate['district']   = $post_district;
            }
            if ($post_subdistrict) {
                $dataupdate['subdistrict'] = $post_subdistrict;
            }

            if ($post_member_bank) {
                $dataupdate['bank']       = $post_member_bank;
            }
            if ($post_member_bill) {
                $dataupdate['bill']       = trim($post_member_bill);
            }
            if ($post_member_bill_name) {
                $dataupdate['bill_name']  = strtoupper(trim($post_member_bill_name));
            }

            if ($save_member    = $this->Model_Member->update_data($id_member, $dataupdate)) {
                ddm_log_action('CHANGE_PROFILE', 'SUCCESS', $memberdata->username, json_encode(array('cookie' => $_COOKIE, 'id_member' => $id_member, 'member' => $memberdata, 'member_update' => $dataupdate, 'update_by' => $current_member->username)));

                // Set Message
                $msg            = ($id_member != $current_member->id ? 'Data profil <strong>(' . $memberdata->username . ')</strong> sudah tersimpan.' : 'Data profil Anda sudah tersimpan.');

                $data = array(
                    'status'    => 'success',
                    'message'   => 'Validasi formulir Anda berhasil! ' . $msg . '',
                );
            } else {
                $data = array(
                    'status'    => 'error',
                    'message'   => 'Validasi formulir Anda tidak berhasil! Silahkan periksa kembali data formulir Anda!',
                );
            }

            // JSON encode data
            die(json_encode($data));
        }
    }

    /**
     * Profile Admin Info Update function.
     */
    function admininfo()
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array('status' => 'login', 'url' => base_url('login'));
            die(json_encode($data));
        }

        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);

        if (!$is_admin) {
            // Set JSON data
            $data = array(
                'status'        => 'error',
                'message'       => 'Data tidak ditemukan atau belum terdaftar',
            );
            die(json_encode($data));
        }

        $post_name       = $this->input->post('member_name');
        $post_name       = ddm_isset($post_name, '');
        $post_phone      = $this->input->post('member_phone');
        $post_phone      = ddm_isset($post_phone, '');
        $post_email      = $this->input->post('member_email');
        $post_email      = ddm_isset($post_email, '');

        $this->form_validation->set_rules('member_name', 'Nama Anggota', 'required');
        $this->form_validation->set_rules('member_email', 'Email', 'required');
        $this->form_validation->set_rules('member_phone', 'No. Telp/HP', 'required');

        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE) {
            // Set JSON data
            $data = array(
                'status'        => 'error',
                'message'       => 'Anda memiliki beberapa kesalahan ( ' . validation_errors() . '). Silakan cek di formulir bawah ini!',
            );
            // JSON encode data
            die(json_encode($data));
        } else {

            $curdate            = date("Y-m-d H:i:s");
            $name               = $post_name ? $post_name : $current_member->name;
            $dataupdate         = array(
                // 'username'      => trim($post_member_username),
                'name'          => strtoupper(trim($name)),
                'email'         => strtolower(trim($post_email)),
                'phone'         => $post_phone,
                'datemodified'  => $curdate,
            );

            if ($save_member    = $this->Model_Member->update_data($current_member->id, $dataupdate)) {
                ddm_log_action('CHANGE_PROFILE_STAFF', 'SUCCESS', $current_member->username, json_encode(array('cookie' => $_COOKIE, 'id_member' => $current_member->id, 'memberdata' => $current_member, 'data_update' => $dataupdate, 'update_by' => $current_member->username)));

                $data = array(
                    'status'    => 'success',
                    'message'   => 'Data profil Anda sudah tersimpan.',
                );
            } else {
                $data = array(
                    'status'    => 'error',
                    'message'   => 'Validasi formulir Anda tidak berhasil! Silahkan periksa kembali data formulir Anda!',
                );
            }

            // JSON encode data
            die(json_encode($data));
        }
    }

    /**
     * Profile Staff Info Update function.
     */
    function staffinfo()
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array('status' => 'login', 'url' => base_url('login'));
            die(json_encode($data));
        }

        $current_staff          = ddm_get_current_staff();
        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);

        if (!$current_staff) {
            // Set JSON data
            $data = array(
                'status'        => 'error',
                'message'       => 'Data Staff tidak ditemukan atau belum terdaftar',
            );
            die(json_encode($data));
        }

        if (!$is_admin) {
            // Set JSON data
            $data = array(
                'status'        => 'error',
                'message'       => 'Data tidak ditemukan atau belum terdaftar',
            );
            die(json_encode($data));
        }

        $post_name       = $this->input->post('member_name');
        $post_name       = ddm_isset($post_name, '');
        $post_phone      = $this->input->post('member_phone');
        $post_phone      = ddm_isset($post_phone, '');
        $post_email      = $this->input->post('member_email');
        $post_email      = ddm_isset($post_email, '');

        $this->form_validation->set_rules('member_name', 'Nama Anggota', 'required');
        $this->form_validation->set_rules('member_email', 'Email', 'required');
        $this->form_validation->set_rules('member_phone', 'No. Telp/HP', 'required');

        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE) {
            // Set JSON data
            $data = array(
                'status'        => 'error',
                'message'       => 'Anda memiliki beberapa kesalahan ( ' . validation_errors() . '). Silakan cek di formulir bawah ini!',
            );
            // JSON encode data
            die(json_encode($data));
        } else {

            $curdate            = date("Y-m-d H:i:s");
            $name               = $post_name ? $post_name : $current_staff->name;
            $dataupdate         = array(
                // 'username'      => trim($post_member_username),
                'name'          => strtoupper(trim($name)),
                'email'         => strtolower(trim($post_email)),
                'phone'         => $post_phone,
                'access'        => $current_staff->access,
                'role'          => $current_staff->role,
                'datemodified'  => $curdate,
            );

            if ($save_member    = $this->Model_Staff->update($current_staff->id, $dataupdate)) {
                ddm_log_action('CHANGE_PROFILE_STAFF', 'SUCCESS', $current_staff->username, json_encode(array('cookie' => $_COOKIE, 'id_staff' => $current_staff->id, 'staff' => $current_staff, 'staff_update' => $dataupdate, 'update_by' => $current_staff->username)));

                $data = array(
                    'status'    => 'success',
                    'message'   => 'Data profil Anda sudah tersimpan.',
                );
            } else {
                $data = array(
                    'status'    => 'error',
                    'message'   => 'Validasi formulir Anda tidak berhasil! Silahkan periksa kembali data formulir Anda!',
                );
            }

            // JSON encode data
            die(json_encode($data));
        }
    }

    /**
     * Change Password function.
     */
    function changepassword()
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array(
                'message'       => 'error',
                'login'         => 'login',
                'data'          => base_url('login'),
            );
            // JSON encode data
            die(json_encode($data));
        }

        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);

        $post_id_member_other   = $this->input->post('id_member_other');
        $post_username_other    = $this->input->post('username_other');
        $pass_type              = $this->input->post('pass_type');
        $pass_type              = ddm_isset($pass_type, 'login');

        if (ddm_isset($post_id_member_other, '') != '') {
            $id_member          = trim(ddm_isset($post_id_member_other, ''));
            $username           = trim(ddm_isset($post_username_other, ''));

            $memberdata         = ddm_get_memberdata_by_id($id_member);
            if (!$memberdata || empty($memberdata)) {
                // Set JSON data
                $data = array(
                    'message'   => 'error',
                    'data'      => ddm_alert('Data anggota <strong>' . $username . '</strong> tidak ditemukan!'),
                );
                // JSON encode data
                die(json_encode($data));
            }

            $access = TRUE;
            if ($staff = ddm_get_current_staff()) {
                if ($staff->access == 'partial') {
                    $role   = array();
                    if ($staff->role) {
                        $role = $staff->role;
                    }

                    foreach (array(STAFF_ACCESS4) as $val) {
                        if (empty($role) || !in_array($val, $role))
                            $access = FALSE;
                    }
                }
            }

            if (!$access) {
                // Set JSON data
                $data = array(
                    'message'   => 'error',
                    'data'      => ddm_alert('Maaf, Anda tidak mempunyai akses untuk edit password anggota!'),
                );
                // JSON encode data
                die(json_encode($data));
            }

            if ($pass_type == 'pin') {
                $post_new_pass      = $this->input->post('new_pass_pin');
                $post_cnew_pass     = $this->input->post('cnew_pass_pin');
                $this->form_validation->set_rules('new_pass_pin', 'Pasword Baru', 'required');
                $this->form_validation->set_rules('cnew_pass_pin', 'Konfirmasi Password Baru', 'required');
            } else {
                $post_new_pass      = $this->input->post('new_pass');
                $post_cnew_pass     = $this->input->post('cnew_pass');
                $this->form_validation->set_rules('new_pass', 'Pasword Baru', 'required');
                $this->form_validation->set_rules('cnew_pass', 'Konfirmasi Password Baru', 'required');
            }

            $new_pass           = ddm_isset($post_new_pass, '');
            $cnew_pass          = ddm_isset($post_cnew_pass, '');

            $this->form_validation->set_message('required', '%s harus di isi');
            $this->form_validation->set_error_delimiters('', '');

            if ($this->form_validation->run() == FALSE) {
                // Set JSON data
                $data = array(
                    'message'   => 'error',
                    'data'      => ddm_alert('Anda memiliki beberapa kesalahan. ' . validation_errors() . ''),
                );
                // JSON encode data
                die(json_encode($data));
            }

            if ($new_pass != $cnew_pass) {
                // Set JSON data
                $data = array(
                    'message'   => 'error',
                    'data'      => ddm_alert('Konfirmasi password tidak sesuai dengan password baru!'),
                );
                // JSON encode data
                die(json_encode($data));
            }

            $global_pass        = get_option('global_password');
            $new_pass           = trim($new_pass);
            // $new_pass           = strtolower($new_pass);
            $password           = ddm_password_hash($new_pass);
            $curdate            = date("Y-m-d H:i:s");

            $passdata['datemodified'] = $curdate;
            if ($pass_type == 'pin') {
                $passdata['password_pin']   = $password;
            } else {
                $passdata['password']       = $password;
            }

            if ($save_pass      = $this->Model_Member->update_data($id_member, $passdata)) {
                ddm_log_action('CHANGE_PASSWORD_BY_ADMIN', $username, $current_member->username, json_encode(array('cookie' => $_COOKIE, 'status' => 'SUCCESS', 'username' => $username, 'password' => $new_pass, 'password_type' => $pass_type, 'updated_by' => $current_member->username)));

                $type_password      = ($pass_type == 'pin') ? 'Transfer PIN' : 'Login';
                $data_notif         = array(
                    'password'      => $new_pass,
                    'type_password' => $type_password
                );

                // Send Notif Email
                $this->ddm_email->send_email_reset_password($memberdata, $data_notif);
                // Send Notif WA
                // $this->ddm_wa->send_wa_reset_password_by_admin( $memberdata, $data_wa );

                // Set JSON data
                $data = array(
                    'message'   => 'success',
                    'access'    => 'admin',
                    'data'      => ddm_alert('Reset/Atur ulang password anggota <strong>' . $username . '</strong> berhasil!'),
                );
            } else {
                // Set JSON data
                $data = array(
                    'message'   => 'error',
                    'data'      => ddm_alert('Reset/Atur ulang password anggota <strong>' . $username . '</strong> tidak berhasil!'),
                );
            }
            // JSON encode data
            die(json_encode($data));
        }

        if ($pass_type == 'pin') {
            $post_cur_pass      = $this->input->post('cur_pass_pin');
            $post_new_pass      = $this->input->post('new_pass_pin');
            $post_cnew_pass     = $this->input->post('cnew_pass_pin');
            if (!$is_admin) {
                $this->form_validation->set_rules('cur_pass_pin', 'Password Lama', 'required');
            }
            $this->form_validation->set_rules('new_pass_pin', 'Pasword Baru', 'required');
            $this->form_validation->set_rules('cnew_pass_pin', 'Konfirmasi Password Baru', 'required');
        } else {
            $post_cur_pass      = $this->input->post('cur_pass');
            $post_new_pass      = $this->input->post('new_pass');
            $post_cnew_pass     = $this->input->post('cnew_pass');
            if (!$is_admin) {
                $this->form_validation->set_rules('cur_pass', 'Password Lama', 'required');
            }
            $this->form_validation->set_rules('new_pass', 'Pasword Baru', 'required');
            $this->form_validation->set_rules('cnew_pass', 'Konfirmasi Password Baru', 'required');
        }

        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE) {
            // Set JSON data
            $data = array(
                'message'   => 'error',
                'data'      => ('Anda memiliki beberapa kesalahan. ' . validation_errors() . ''),
            );
            // JSON encode data
            die(json_encode($data));
        } else {

            $cur_pass       = ddm_isset($post_cur_pass, '');
            $new_pass       = ddm_isset($post_new_pass, '');
            $new_pass_sms   = ddm_isset($post_new_pass, '');
            $cnew_pass      = ddm_isset($post_cnew_pass, '');

            // Check Member Password
            if ($pass_type == 'pin') {
                $check_pass = FALSE;
                if (ddm_hash_verify($cur_pass, $current_member->password_pin)) {
                    $check_pass = TRUE;
                }
            } else {
                $check_pass     = $this->Model_Auth->authenticate($current_member->username, $cur_pass);
            }

            if (!$check_pass && !$is_admin) {
                // Set JSON data
                $data = array(
                    'message'   => 'error',
                    'data'      => ('Password lama yang anda masukkan salah!'),
                );
                // JSON encode data
                die(json_encode($data));
            } else {
                if ($new_pass != $cnew_pass) {
                    // Set JSON data
                    $data = array(
                        'message'   => 'error',
                        'data'      => ('Konfirmasi password tidak sesuai dengan password baru!'),
                    );
                    // JSON encode data
                    die(json_encode($data));
                } else {
                    // $new_pass           = strtolower($new_pass);
                    $new_pass           = trim($new_pass);
                    $password           = ddm_password_hash($new_pass);
                    $curdate            = date("Y-m-d H:i:s");

                    $passdata['datemodified'] = $curdate;
                    if ($pass_type == 'pin') {
                        $passdata['password_pin']   = $password;
                    } else {
                        $passdata['password']       = $password;
                    }

                    if ($save_pass      = $this->Model_Member->update_data($current_member->id, $passdata)) {
                        ddm_log_action('CHANGE_PASSWORD', 'SUCCESS', $current_member->username, json_encode(array('cookie' => $_COOKIE, 'status' => 'SUCCESS', 'password' => $new_pass, 'password_type' => $pass_type)));

                        $type_password      = ($pass_type == 'pin') ? 'Transfer PIN/Produk' : 'Login';
                        $data_notif         = array(
                            'password'      => $new_pass,
                            'type_password' => $type_password
                        );

                        // Send Notif Email
                        $this->ddm_email->send_email_change_password($current_member, $data_notif);
                        // Send Notif WA
                        // $this->ddm_wa->send_wa_reset_password_by_member( $current_member, $data_wa );

                        if ($pass_type == 'pin') {
                            // Set JSON data
                            $data = array(
                                'message'   => 'success',
                                'access'    => 'admin',
                                'data'      => ('Reset/Atur ulang password PIN berhasil!'),
                            );
                        } else {

                            $credentials['username']    = $current_member->username;
                            $credentials['password']    = $new_pass;
                            $credentials['remember']    = '';

                            // Logout
                            ddm_logout();

                            // Sign On member
                            $time           = time();
                            $membersignon   = $this->Model_Auth->signon($credentials, $time);
                            $member         = $this->ddm_member->member($membersignon->id);
                            $last_activity  = date('Y-m-d H:i:s', $time);

                            // Set session data
                            $session_data   = array(
                                'id'            => $member->id,
                                'username'      => $member->username,
                                'name'          => $member->name,
                                'email'         => $member->email,
                                'last_login'    => $last_activity
                            );

                            // Set session
                            $this->session->set_userdata('member_logged_in', $session_data);

                            // Set cookie domain
                            $cookie_domain  = str_replace(array('http://', 'https://', 'www.'), '', base_url());
                            $cookie_domain  = '.' . str_replace('/', '', $cookie_domain);
                            $expire         = 0;
                            // Set cookie data
                            $cookie         = array(
                                'name'      => 'logged_in_' . md5('nonssl'),
                                'value'     => $member->id,
                                'expire'    => $expire,
                                'domain'    => $cookie_domain,
                                'path'      => '/',
                                'secure'    => false,
                            );
                            // set cookie
                            setcookie($cookie['name'], $cookie['value'], $cookie['expire'], $cookie['path'], $cookie['domain'], $cookie['secure']);

                            // Save Auth Session
                            ddm_set_auth_session($current_member->username, $membersignon, '', '', $time);

                            // Set JSON data
                            $data = array(
                                'message'   => 'success',
                                'access'    => 'admin',
                                'data'      => ('Reset/Atur ulang password Login berhasil!'),
                                // 'access'    => 'member',
                                // 'data'      => base_url('login'),
                            );
                        }
                    } else {
                        // Set JSON data
                        $data = array(
                            'message'   => 'error',
                            'data'      => ('Validasi formulir Anda tidak berhasil! Silahkan periksa kembali data formulir Anda!'),
                        );
                    }
                    // JSON encode data
                    die(json_encode($data));
                }
            }
        }
    }

    /**
     * Change Password Staff function.
     */
    function changepasswordstaff()
    {
        // This is for AJAX request
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array(
                'message'       => 'error',
                'login'         => 'login',
                'data'          => base_url('login'),
            );
            // JSON encode data
            die(json_encode($data));
        }

        $current_staff          = ddm_get_current_staff();
        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);

        if (!$current_staff) {
            // Set JSON data
            $data = array('message' => 'error', 'data' => 'Data Staff tidak ditemukan atau belum terdaftar');
            die(json_encode($data));
        }

        if (!$is_admin) {
            // Set JSON data
            $data = array('message' => 'error', 'data' => 'Data tidak ditemukan atau belum terdaftar');
            die(json_encode($data));
        }

        $post_cur_pass      = $this->input->post('cur_pass');
        $post_new_pass      = $this->input->post('new_pass');
        $post_cnew_pass     = $this->input->post('cnew_pass');

        $this->form_validation->set_rules('cur_pass', 'Password Lama', 'required');
        $this->form_validation->set_rules('new_pass', 'Pasword Baru', 'required');
        $this->form_validation->set_rules('cnew_pass', 'Konfirmasi Password Baru', 'required');

        $this->form_validation->set_message('required', '%s harus di isi');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE) {
            // Set JSON data
            $data = array(
                'message'   => 'error',
                'data'      => ('Anda memiliki beberapa kesalahan. ' . validation_errors() . ''),
            );
            die(json_encode($data));
        } else {

            $cur_pass       = ddm_isset($post_cur_pass, '');
            $new_pass       = ddm_isset($post_new_pass, '');
            $cnew_pass      = ddm_isset($post_cnew_pass, '');

            // Check Member Password
            $check_pass     = FALSE;
            if (ddm_hash_verify($cur_pass, $current_staff->password)) {
                $check_pass = TRUE;
            }

            if (!$check_pass) {
                // Set JSON data
                $data = array(
                    'message'   => 'error',
                    'data'      => ('Password lama yang anda masukkan salah!'),
                );
                die(json_encode($data));
            } else {
                if ($new_pass != $cnew_pass) {
                    // Set JSON data
                    $data = array(
                        'message'   => 'error',
                        'data'      => ('Konfirmasi password tidak sesuai dengan password baru!'),
                    );
                    die(json_encode($data));
                } else {
                    $new_pass           = trim($new_pass);
                    $password           = ddm_password_hash($new_pass);
                    $curdate            = date("Y-m-d H:i:s");

                    $staff_data = array(
                        'username'      => $current_staff->username,
                        'password'      => $password,
                        'name'          => $current_staff->name,
                        'email'         => $current_staff->email,
                        'access'        => $current_staff->access,
                        'role'          => $current_staff->role,
                        'datecreated'   => $current_staff->datecreated,
                        'datemodified'  => $curdate,
                    );

                    if ($this->Model_Staff->update($current_staff->id, $staff_data)) {
                        // Set JSON data
                        $data = array(
                            'message'   => 'success',
                            'access'    => 'admin',
                            'data'      => ('Reset/Atur ulang password berhasil!'),
                        );

                        ddm_log_action('CHANGE_PASSWORD_STAFF', 'SUCCESS', $current_staff->username, json_encode(array('cookie' => $_COOKIE, 'status' => 'SUCCESS', 'password' => $new_pass)));

                        $data_notif         = array(
                            'password'      => $new_pass,
                            'type_password' => 'Login'
                        );

                        // Send Notif Email
                        $this->ddm_email->send_email_change_password($current_staff, $data_notif);
                        // Send Notif WA
                        // $this->ddm_wa->send_wa_reset_password_by_member( $current_member, $data_wa );

                    } else {
                        // Set JSON data
                        $data = array(
                            'message'   => 'error',
                            'data'      => ('Validasi formulir Anda tidak berhasil! Silahkan periksa kembali data formulir Anda!'),
                        );
                    }
                    // JSON encode data
                    die(json_encode($data));
                }
            }
        }
    }

    // ------------------------------------------------------------------------------------------------

    // ------------------------------------------------------------------------------------------------
    // Member Function
    // ------------------------------------------------------------------------------------------------

    function export()
    {
        $this->load->library('ddm_XLS');
        $export                         = $this->ddm_xls->simpleInit();
    }

    // =============================================================================================
    // GENERATION TREE MEMBER
    // =============================================================================================

    /**
     * Load sponsors more
     */
    function generation_loadmore($offset = 0, $limit = 10)
    {
        $cur_user = ddm_get_current_member();
        $is_admin = as_administrator($cur_user);
        $data     = array();

        if ($is_admin) {
            $conditions = array('type' => MEMBER);
            if ($username = $this->input->post('username')) {
                $conditions['username'] = $username;
            }

            if (!$parents = $this->Model_Member->select('id,username,name,tree')->order_by('id', 'ASC')->limit($limit, $offset)->get_many_by($conditions)) {
                $parents = array();
            }

            foreach ($parents as $parent) {
                $downlines  = ddm_get_user_gen_sponsor($parent->id, $limit);

                // Personal Sales Omzet
                $ps_cond    = ' AND id_member = ' . $parent->id . ' AND datecreated LIKE "' . date('Y-m') . '%" AND status LIKE "personal"';
                $ps_data    = $this->Model_Member->get_total_member_omzet($ps_cond);
                $ps         = ($ps_data ? ddm_accounting($ps_data->total_omzet, config_item('currency')) . ' (' . $ps_data->total_qty . ' Ltr)' : 0);

                // Group Sales Omzet
                $gs_idsdata = $this->Model_Member->get_group_ids($parent->tree);
                $gs_ids     = $gs_idsdata ? implode(',', $gs_idsdata) : array();
                $gs_cond    = ' AND id_member IN (' . $gs_ids . ') AND datecreated LIKE "' . date('Y-m') . '%" AND status LIKE "personal"';
                $gs_data    = $this->Model_Member->get_total_member_omzet($gs_cond);
                $gs         = ($gs_data ? ddm_accounting($gs_data->total_omzet, config_item('currency')) . ' (' . $gs_data->total_qty . ' Ltr)' : 0);

                $data[]     = array(
                    'text'  => $this->generation_text($parent, 0, $ps, $gs),
                    'nodes' => $this->generation_nodes($parent, $downlines),
                );
            }
        } else {
            $downlines  = ddm_get_user_gen_sponsor($cur_user->id, $limit);

            // Personal Sales Omzet
            $ps_cond    = ' AND id_member = ' . $cur_user->id . ' AND datecreated LIKE "' . date('Y-m') . '%" AND status LIKE "personal"';
            $ps_data    = $this->Model_Member->get_total_member_omzet($ps_cond);
            $ps         = ($ps_data ? ddm_accounting($ps_data->total_omzet, config_item('currency')) . ' (' . $ps_data->total_qty . ' Ltr)' : 0);

            // Group Sales Omzet
            $gs_idsdata = $this->Model_Member->get_group_ids($cur_user->tree);
            $gs_ids     = $gs_idsdata ? implode(',', $gs_idsdata) : array();
            $gs_cond    = ' AND id_member IN (' . $gs_ids . ') AND datecreated LIKE "' . date('Y-m') . '%" AND status LIKE "personal"';
            $gs_data    = $this->Model_Member->get_total_member_omzet($gs_cond);
            $gs         = ($gs_data ? ddm_accounting($gs_data->total_omzet, config_item('currency')) . ' (' . $gs_data->total_qty . ' Ltr)' : 0);

            $data[]     = array(
                'text'  => $this->generation_text($cur_user, 0, $ps, $gs) . ' <span class="label label-info">Anda</span>',
                'nodes' => $this->generation_nodes($cur_user, $downlines),
            );
        }

        $success  = count($data) ? TRUE : FALSE;
        $response = array('success' => $success, 'data' => $data);
        echo json_encode($response);
    }

    /**
     * Get generation nodes function
     */
    private function generation_nodes($user, $downlines)
    {
        $nodes = array();

        foreach ($downlines as $gen => $users) {
            foreach ($users as $downline) {
                if ($downline->sponsor != $user->id) continue;

                // Personal Sales Omzet
                $ps_cond    = ' AND id_member = ' . $downline->id . ' AND datecreated LIKE "' . date('Y-m') . '%" AND status LIKE "personal"';
                $ps_data    = $this->Model_Member->get_total_member_omzet($ps_cond);
                $ps         = ($ps_data ? ddm_accounting($ps_data->total_omzet, config_item('currency')) . ' (' . $ps_data->total_qty . ' Ltr)' : 0);

                // Group Sales Omzet
                $gs_idsdata = $this->Model_Member->get_group_ids($downline->tree);
                $gs_ids     = $gs_idsdata ? implode(',', $gs_idsdata) : array();
                $gs_cond    = ' AND id_member IN (' . $gs_ids . ') AND datecreated LIKE "' . date('Y-m') . '%" AND status LIKE "personal"';
                $gs_data    = $this->Model_Member->get_total_member_omzet($gs_cond);
                $gs         = ($gs_data ? ddm_accounting($gs_data->total_omzet, config_item('currency')) . ' (' . $gs_data->total_qty . ' Ltr)' : 0);

                $nodes[] = array(
                    'text'  => $this->generation_text($downline, ($gen + 1), $ps, $gs),
                    'nodes' => $this->generation_nodes($downline, $downlines),
                );
            }
        }

        return $nodes;
    }

    /**
     * Get generation text function
     */
    private function generation_text($user, $gen = 0, $ps = '', $gs = '')
    {
        return '
            <strong>' . strtoupper($user->name) . '</strong> 
            <small>(' . $user->username . ')</small> ' .
            ($gen ? ' <span class="badge badge-warning">Gen-' . $gen . '</span>' : '') .
            '<span class="badge badge-info">PS : ' . $ps . '</span> ' .
            '<span class="badge badge-success">GS : ' . $gs . '</span>';
    }

    /**
     * Member Generation function.
     */
    function generationtreebak($id = '', $offset = 0, $limit = 0)
    {
        auth_redirect();

        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);
        $data               = array();
        $my_gen             = ddm_my_gen_sponsor($current_member->id);

        if ($id > 0) {
            $conditions         = ' WHERE %type% = ' . MEMBER . ' AND %status% = ' . ACTIVE . ' AND %sponsor% = ' . $id;
            $member_list        = $this->Model_Member->get_all_member_data($limit, $offset, $conditions, '%id% ASC');
            foreach ($member_list as $member) {
                $child          = $this->Model_Member->count_by_sponsor($member->id, false);
                $member_gen     = ddm_my_gen_sponsor($member->id);
                $gen            = $member_gen - $my_gen;

                $data[]         = array(
                    'id'        => $member->id,
                    'text'      => $this->generationtree_text($member, $gen),
                    'children'  => $child ? TRUE : FALSE
                );
            }
        } else {
            $child          = $this->Model_Member->count_by_sponsor($current_member->id, false);
            $data[]         = array(
                'id'        => $current_member->id,
                'text'      => $this->generationtree_text($current_member, 0),
                'children'  => $child ? TRUE : FALSE
            );
        }

        echo json_encode($data);
    }

    /**
     * Get generation tree text function
     */
    private function generationtreebak_text($member, $gen = 0, $childs = 0)
    {

        $is_admin = as_administrator($member);
        $username = $is_admin ? 'ROOT' : $member->username;

        return '
            <strong style="font-size:13px">' . strtoupper($member->name) . '</strong>
            <small>(' . $username . ')</small> ' .
            ($gen ? ' <span class="label bg-yellow">Gen-' . $gen . '</span>' : '<span class="label bg-blue">Anda</span>') .
            ($childs ? ' <span class="badge bg-green">' . $childs . '</span>' : '');
    }

    /**
     * Clone Member Data function.
     */
    function cloning()
    {
        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);
        $username           = $this->input->post('username');
        $username           = ddm_isset($username, '');

        if (empty($username)) {
            // Set JSON data
            $data = array(
                'message'   => 'failed',
                'alert'     => 'Username tidak boleh kosong. Silahkan inputkan Username lainnya!',
                'data'      => '',
            );
            // JSON encode data
            die(json_encode($data));
        }

        $memberdata         = $this->Model_Member->get_member_by('login', $username);
        if (!$memberdata) {
            // Set JSON data
            $data = array(
                'message'   => 'failed',
                'alert'     => 'Username tidak ditemukan. Silahkan inputkan Username lainnya!',
                'data'      => '',
            );
            // JSON encode data
            die(json_encode($data));
        }

        $id_member          = $memberdata->id;
        $memberdata         = ddm_unset_clone_member_data($memberdata);

        $provinces          = ddm_provinces();
        $provincetext       = '';
        if (!empty($provinces)) {
            foreach ($provinces as $province) {
                $provincetext  .= '<option value="' . $province->province_id . '">' . $province->province_name . '</option>';
            }
        }
        $memberdata->provinces = $provincetext;

        $cities             = ddm_cities_by_provinces($memberdata->province);
        $citytext           = '';
        if (!empty($cities)) {
            foreach ($cities as $city) {
                $citytext  .= '<option value="' . $city->regional_id . '">' . $city->regional_name . '</option>';
            }
        }
        $memberdata->cities = $citytext;

        $districts          = ddm_districts_by_city($memberdata->city);
        $districttext       = '';
        if (!empty($districts)) {
            foreach ($districts as $district) {
                $districttext  .= '<option value="' . $district->district_id . '">' . $district->district_name . '</option>';
            }
        }
        $memberdata->districts  = $districttext;

        // Check If Member is Downline
        // -------------------------------------------------
        if (!$is_admin) {
            $is_downline        = $this->Model_Member->get_is_downline($id_member, $current_member->tree);
            if (!$is_downline) {
                // Set JSON data
                $data = array(
                    'message'   => 'failed',
                    'alert'     => 'Username ini bukan jaringan Anda. Clone data hanya bisa dilakukan dari jaringan Anda!',
                    'data'      => '',
                );
                // JSON encode data
                die(json_encode($data));
            }
        }

        // Set JSON data
        $data = array(
            'message'       => 'Success',
            'alert'         => 'Username ditemukan dan data sudah di clone',
            'data'          => $memberdata,
        );
        // JSON encode data
        die(json_encode($data));
    }

    // =============================================================================================
    // SEARCH MEMBER
    // =============================================================================================

    /**
     * Search Tree Member function.
     */
    function searchtree()
    {
        $current_member = ddm_get_current_member();
        $username       = $this->input->post('username');
        $username       = ddm_isset($username, '');
        $member         = $this->Model_Member->get_member_by('login', strtolower($username));

        if (!empty($member)) {
            $is_downline = $this->Model_Member->get_is_downline($member->id, $current_member->tree);

            if ($is_downline) {
                // Set JSON data
                $id = ddm_encrypt($member->id);
                $data = array(
                    'message' => 'success',
                    'data' => base_url('member/tree/' . $id),
                );
            } else {
                // Set JSON data
                $data = array(
                    'message' => 'failed',
                    'data' => ddm_alert('Username Anggota ini bukan jaringan Anda.'),
                );
            }
        } elseif (empty($username)) {
            // Set JSON data
            $data = array(
                'message' => 'failed',
                'data' => ddm_alert('Username Anggota harus di isi. Silahkan masukkan Username Anggota'),
            );
        } else {
            // Set JSON data
            $data = array(
                'message' => 'failed',
                'data' => ddm_alert('Username Anggota tidak ditemukan atau belum terdaftar.'),
            );
        }
        // JSON encode data
        die(json_encode($data));
    }

    /**
     * Search Upline Group function.
     */
    function searchuplinetree()
    {
        $current_member     = ddm_get_current_member();
        $is_admin           = as_administrator($current_member);
        $id_parent          = $this->input->post('id_parent');
        $id_parent          = ddm_isset($id_parent, 0);
        $position           = $this->input->post('position');
        $position           = ddm_isset($position, '');
        $info               = '';

        if (!empty($id_parent)) {
            $memberdata = ddm_get_memberdata_by_id($id_parent);

            if ($position == POS_LEFT) $node = 'Kiri';
            elseif ($position == POS_RIGHT) $node = 'Kanan';

            if (!$memberdata) {
                // Set JSON data
                $data = array('message' => 'failed', 'data' => ddm_alert('Username upline tidak ditemukan!'));
                // JSON encode data
                die(json_encode($data));
            } else {
                if (!$is_admin) {
                    $is_down = $this->Model_Member->get_is_downline($memberdata->id, $current_member->tree);

                    if (!$is_down) {
                        // Set JSON data
                        $data = array('message' => 'failed', 'data' => ddm_alert('Upline member ID ini bukan jaringan Anda!'));
                        // JSON encode data
                        die(json_encode($data));
                    }
                }

                $info .= '
                <input type="hidden" name="reg_member_upline_id" class="form-control" value="' . $memberdata->id . '" />
                <div class="form-group">
                    <label class="col-md-3 control-label">Username Upline &nbsp;</label>
                    <input type="hidden" name="reg_member_upline" id="reg_member_upline" class="form-control" value="' . $memberdata->username . '" />
                    <div class="col-md-7">
                        <div class="input-group">
                            <input type="text" name="reg_upline_username" class="form-control" placeholder="Username Upline" disabled="" value="' . $memberdata->username . '" />
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">' . lang("name") . ' Upline &nbsp;</label>
                    <div class="col-md-7">
                        <div class="input-group">
                            <input type="text" name="reg_member_nama_dsb" class="form-control" placeholder="Nama Anggota" disabled="" value="' . strtoupper($memberdata->name) . '" />
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">' . lang("position") . ' &nbsp;</label>
                    <input type="hidden" name="reg_member_position" class="form-control" value="' . $position . '" />
                    <div class="col-md-7">
                        <div class="input-group">
                            <input type="text" name="reg_member_position_dsb" class="form-control" placeholder="Posisi" disabled="" value="' . strtoupper($node) . '" />
                            <span class="input-group-addon"><i class="fa fa-dot-circle-o"></i></span>
                        </div>
                    </div>
                </div>';

                // Set JSON data
                $data = array(
                    'message' => 'success',
                    'data' => $info
                );
                // JSON encode data
                die(json_encode($data));
            }
        }

        // Set JSON data
        $data = array(
            'message' => 'failed',
            'data' => ddm_alert('Member ID upline tidak ditemukan!')
        );
        // JSON encode data
        die(json_encode($data));
    }

    /**
     * Search member data function.
     */
    function searchmemberdata($type)
    {
        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);
        $username               = $this->input->post('username');
        $username               = ddm_isset($username, '');
        $status                 = '';
        $message                = '';

        if (!empty($username)) {
            $memberdata         = $this->Model_Member->get_member_by('login', $username);

            if (!$memberdata) {
                $status         = 'error';
                $message        = 'Data Username ' . $type . ' tidak ditemukan atau belum terdaftar!';
            } else {

                if (as_administrator($memberdata)) {
                    // Set JSON data
                    $data = array(
                        'status'    => 'error',
                        'message'   => 'Admin tidak dapat dijadikan sebagai ' . ucfirst($type) . '. Silahkan masukkan username ' . ucfirst($type) . ' lainnya',
                    );
                    // JSON encode data
                    die(json_encode($data));
                }

                $status     = 'available';
                $message   .= '
                <div class="form-group">
    				<label class="control-label">' . ucfirst($type) . ' Name</label>
                    <input type="hidden" name="reg_member_' . $type . '_id" class="form-control" value="' . $memberdata->id . '" />
    				<input type="text" name="reg_member_' . $type . '_name_dsb" class="form-control" placeholder="NAMA ' . ucwords($type) . '" disabled="" value="' . strtoupper($memberdata->name) . '" />
    			</div>';
            }
        } else {
            $status     = 'error';
            $message    = 'Username ' . $type . ' tidak boleh kosong. Silahkan masukkan Username ' . $type . '!';
        }

        // Set JSON data
        $data = array(
            'status'    => $status,
            'message'   => $message
        );
        // JSON encode data
        die(json_encode($data));
    }

    /**
     * Search Upline function.
     */
    function searchupline()
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url(), 'location');
        }

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array(
                'status'        => 'login',
                'message'       => base_url('login'),
            );
            die(json_encode($data));
        }

        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);
        $username               = $this->input->post('username');
        $username               = ddm_isset($username, '');
        $status                 = '';
        $message                = '';
        $info                   = '';

        if (!empty($username)) {
            $memberdata         = $this->Model_Member->get_member_by('login', $username);

            if (!$memberdata) {
                $status         = 'error';
                $message        = 'Username tidak ditemukan atau belum terdaftar';
            } else {
                if (!$is_admin) {
                    $is_down    = $this->Model_Member->get_is_downline($memberdata->id, $current_member->tree);

                    if (!$is_down) {
                        // Set JSON data
                        $data = array(
                            'status'    => 'error',
                            'message'   => 'Username upline ini bukan jaringan Anda! Silahkan ketik Username lain!'
                        );
                        die(json_encode($data));
                    }
                }

                $node           = ddm_check_node($memberdata->id);
                if (!empty($node)) {
                    $status     = 'available';
                    $message    = 'Data dari upline ini ditemukan, Anda dapat mengisi formulir pendaftaran anggota baru.';
                    $info      .= '
                    <div class="form-group">
                        <label class="col-md-3 control-label">' . lang("name") . ' Upline &nbsp;</label>
                        <input type="hidden" name="reg_member_upline_id" class="form-control" value="' . $memberdata->id . '" />
                        <div class="col-md-7">
                            <div class="input-group">
                                <input type="text" name="reg_member_nama_dsb" class="form-control text-uppercase" placeholder="Nama Upline" disabled="" value="' . strtoupper($memberdata->name) . '" />
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            </div>
                        </div>
                    </div>';

                    if (count($node) > 1) {
                        $info .= '
                        <div class="form-group">
                            <label class="col-md-3 control-label">' . lang("position") . ' <span class="required">*</span></label>
                            <div class="col-md-7">
                                <select class="form-control" name="reg_member_position" >';
                        foreach ($node as $n) {
                            $nodedesc = "";
                            if ($n == POS_LEFT) $nodedesc = "Kiri";
                            elseif ($n == POS_RIGHT) $nodedesc = "Kanan";

                            $info .= '<option value="' . $n . '">' . strtoupper($nodedesc) . '</option>';
                        }
                        $info .= '</select>
                            </div>
                        </div>';
                    } elseif (count($node) == 1) {

                        if ($node[0] == POS_LEFT) $nodedesc = "Kiri";
                        elseif ($node[0] == POS_RIGHT) $nodedesc = "Kanan";

                        $info .= '
                        <div class="form-group">
                            <label class="col-md-3 control-label">' . lang("position") . ' &nbsp;</label>
                            <input type="hidden" name="reg_member_position" class="form-control" value="' . $node[0] . '" />
                            <div class="col-md-7">
                                <div class="input-group">
                                    <input type="text" name="reg_member_position_dsb" class="form-control" placeholder="Posisi Anggota" disabled="" value="' . strtoupper($nodedesc) . '" />
                                    <span class="input-group-addon"><i class="fa fa-sitemap"></i></span>
                                    <!--<span class="input-group-addon"><i class="fa fa-dot-circle-o"></i></span>-->
                                </div>
                            </div>
                        </div>';
                    }
                } else {
                    $status     = 'error';
                    $message    = 'Semua Group upline ini sudah terisi';
                }
            }
        } else {
            $status         = 'error';
            $message        = 'Username Upline harus di isi !';
        }

        // Set JSON data
        $data = array('status' => $status, 'info' => $info, 'message' => $message);
        die(json_encode($data));
    }

    /**
     * Search Sponsor function.
     */
    function searchsponsor()
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url(), 'location');
        }

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array(
                'status'        => 'login',
                'message'       => base_url('login'),
            );
            die(json_encode($data));
        }

        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);
        $username               = $this->input->post('username');
        $username               = ddm_isset($username, '');
        $info                   = '';

        if (empty($username)) {
            // Set JSON data
            $data = array('status' => 'error', 'message' => 'Username Sponsor harus di isi!');
            die(json_encode($data));
        }

        $memberdata         = $this->Model_Member->get_member_by('login', $username);
        if (!$memberdata || empty($memberdata)) {
            // Set JSON data
            $data = array('status' => 'error', 'message' => 'Data Sponsor tidak ditemukan atau belum terdaftar!');
            die(json_encode($data));
        }

        if ($memberdata->status == 0) {
            // Set JSON data
            $data = array('status' => 'error', 'message' => 'Data Sponsor tidak ditemukan atau belum terdaftar!');
            die(json_encode($data));
        }

        if ($memberdata->status > 1) {
            // Set JSON data
            $data = array('status' => 'error', 'message' => 'Data Sponsor tersebut di banned. Silahkan ketik Username Sponsor lain !');
            die(json_encode($data));
        }

        if (!$is_admin) {
            $is_down    = $this->Model_Member->get_is_downline($memberdata->id, $current_member->tree);
            if (!$is_down) {
                // Set JSON data
                $data = array(
                    'status'    => 'error',
                    'message'   => 'Username Sponsor ini tidak berada di jaringan Anda ! Silahkan ketik Username lain!'
                );
                die(json_encode($data));
            }
        }

        $info      .= '
        <div class="form-group row mb-2">
            <label class="col-md-3 col-form-label form-control-label">' . lang("name") . ' Sponsor &nbsp;</label>
            <div class="col-md-9">
                <div class="input-group input-group-merge">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                    </div>
                    <input type="hidden" name="reg_member_sponsor_id" class="form-control" value="' . $memberdata->id . '" />
                    <input type="hidden" name="reg_member_sponsor_username" class="form-control" value="' . strtolower($memberdata->username) . '" />
                    <input type="text" name="reg_member_sponsor_name_dsb" class="form-control" placeholder="Nama Sponsor" disabled="" value="' . strtoupper($memberdata->name) . '" />
                </div>
            </div>
        </div>';

        // Set JSON data
        $data = array(
            'status'    => 'success',
            'message'   => 'Data anggota berhasil ditemukan. Silahkan cek hasil data anggota pada formulir dibawah.',
            'info'      => $info
        );
        die(json_encode($data));
    }

    /**
     * Search Agent function.
     */
    function searchagent()
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url(), 'location');
        }

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            // Set JSON data
            $data = array(
                'status'        => 'login',
                'message'       => base_url('login'),
            );
            die(json_encode($data));
        }

        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);
        $username               = $this->input->post('username');
        $username               = ddm_isset($username, '');
        $info                   = '';

        if (empty($username)) {
            // Set JSON data
            $data = array('status' => 'error', 'message' => 'Username Agen harus di isi!');
            die(json_encode($data));
        }

        $memberdata         = $this->Model_Member->get_member_by('login', $username);
        if (!$memberdata || empty($memberdata)) {
            // Set JSON data
            $data = array('status' => 'error', 'message' => 'Data Agen tidak ditemukan atau belum terdaftar!');
            die(json_encode($data));
        }

        if ($memberdata->status == 0) {
            // Set JSON data
            $data = array('status' => 'error', 'message' => 'Data Agen tidak ditemukan atau belum terdaftar!');
            die(json_encode($data));
        }

        if ($memberdata->status > 1) {
            // Set JSON data
            $data = array('status' => 'error', 'message' => 'Data Agen tersebut di banned. Silahkan ketik Username Agen lainnya !');
            die(json_encode($data));
        }

        if (as_administrator($memberdata)) {
            // Set JSON data
            $data = array('status' => 'error', 'message' => 'Username ini adalah Admin. Silahkan inputkan username Agen lainnya !');
            die(json_encode($data));
        }

        $info      .= '
        <div class="form-group row mb-2">
            <label class="col-md-3 col-form-label form-control-label">' . lang("name") . ' Agen &nbsp;</label>
            <div class="col-md-9">
                <div class="input-group input-group-merge">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                    </div>
                    <input type="hidden" name="member_agent_id" class="form-control" value="' . $memberdata->id . '" />
                    <input type="hidden" name="member_agent_username" class="form-control" value="' . strtolower($memberdata->username) . '" />
                    <input type="text" name="member_agent_name_dsb" class="form-control" placeholder="Nama Agen" disabled="" value="' . strtoupper($memberdata->name) . '" />
                </div>
            </div>
        </div>';

        // Set JSON data
        $data = array(
            'status'    => 'success',
            'message'   => 'Data Agen berhasil ditemukan. Silahkan cek hasil data Agen pada formulir dibawah.',
            'info'      => $info
        );
        die(json_encode($data));
    }

    /**
     * Search Member function.
     */
    function searchmember()
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('/'), 'location');
        }

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'login', 'message' => base_url('login'));
            die(json_encode($data));
        }

        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);
        $username               = $this->input->post('username');
        $username               = ddm_isset($username, '');
        $form                   = $this->input->post('form');
        $form                   = ddm_isset($form, '');
        $status                 = 'error';
        $message                = 'Username belum di isi. Silahkan input username member !';
        $info                   = '';

        if (!empty($username)) {
            $memberdata         = $this->Model_Member->get_member_by('login', strtolower($username));

            if (!$memberdata) {
                $status         = 'error';
                $message        = 'Username tidak ditemukan atau belum terdaftar';
            } elseif ($memberdata->status != ACTIVE) {
                $status         = 'error';
                $message        = 'Member sudah tidak aktif';
            } else {
                $member_admin   = as_administrator($memberdata);
                if ($member_admin) {
                    $data = array(
                        'status'    => 'error',
                        'message'   => 'Username tidak ditemukan atau belum terdaftar'
                    );
                    die(json_encode($data));
                }
                $message        = 'Username tidak ditemukan atau belum terdaftar';

                if (strtolower($form) == 'transfer') {
                    if ($current_member->id == $memberdata->id) {
                        $data = array(
                            'status'    => 'error',
                            'message'   => 'Anda tidak dapat men-transfer PIN kepada akun Anda sendiri!'
                        );
                        die(json_encode($data));
                    }
                    $status     = 'success';
                    $message    = 'Data Member ini ditemukan. Anda dapat melanjutkan proses pengisian formulir';

                    $info      .= '
                        <div class="form-group">
                            <label class="col-md-3 control-label">Nama&nbsp;&nbsp;&nbsp;</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" name="pin_member_nama_dsb" class="form-control" placeholder="Nama Anggota" disabled="" value="' . strtoupper($memberdata->name) . '" />
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                </div>
                            </div>
                        </div>';
                }
            }
        }

        $data = array('status' => $status, 'message' => $message, 'info' => $info);
        die(json_encode($data));
    }

    /**
     * Search Stocist for Generate PIN function.
     */
    function searchstockistpin()
    {
        // Check for AJAX Request
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('dashboard'), 'location');
        }

        $auth = auth_redirect($this->input->is_ajax_request());
        if (!$auth) {
            $data = array('status' => 'login', 'message' => base_url('login'));
            die(json_encode($data));
        }

        $current_member         = ddm_get_current_member();
        $is_admin               = as_administrator($current_member);
        $username               = $this->input->post('username');
        $username               = ddm_isset($username, '');
        $info                   = '';
        $add_address            = '';
        $shipping               = '';

        if (empty($username)) {
            $data = array(
                'status'    => 'error',
                'message'   => 'Username tidak boleh kosong. Silakan ketikkan Username Member lainnya!'
            );
            die(json_encode($data));
        }

        $memberdata         = $this->Model_Member->get_member_by('login', $username);
        if (!$memberdata) {
            $data = array(
                'status'    => 'error',
                'message'   => 'Username tidak valid atau belum terdaftar.'
            );
            die(json_encode($data));
        }

        // if( $memberdata->as_stockist == 0  ){
        //     $data = array(
        //         'status'    => 'error',
        //         'message'   => 'Username bukan Stockist. Silahkan ketikkan Username Stockist lainnya!'
        //     ); die(json_encode($data));
        // }

        if ($current_member->id == $memberdata->id) {
            $data = array(
                'status'    => 'error',
                'message'   => 'Anda tidak dapat mentransfer PIN ke akun Anda sendiri!'
            );
            die(json_encode($data));
        }

        // If member is admin
        if (as_administrator($memberdata)) {
            $data = array(
                'status'        => 'error',
                'message'       => 'Admin tidak perlu PIN. Silakan masukkan username lainnya!',
            );
            die(json_encode($data));
        }

        $info      .= '
        <input type="hidden" name="pin_stockist_id" class="form-control" value="' . $memberdata->id . '" />
        <div class="form-group">
            <label class="col-md-3 control-label">' . lang("name") . ' &nbsp;</label>
            <div class="col-md-6">
                <input type="text" name="pin_stockist_name_dsb" id="pin_stockist_name_dsb" class="form-control" disabled="" value="' . strtoupper($memberdata->name) . '" />
            </div>
        </div>';

        if ($is_admin) {
            $member_province    = $memberdata->province;
            $member_city        = $memberdata->city;

            $province_name      = '';
            if ($member_province) {
                $provinces      = ddm_provinces($member_province);
                $province_name  = $provinces ? $provinces->province_name : '';
            }

            $city_name          = '';
            $cities             = ddm_cities_by_provinces($member_province);
            if (!empty($cities)) {
                foreach ($cities as $c) {
                    if ($member_city == $c->regional_id) {
                        $city_name = $c->regional_name;
                    }
                }
            }

            $status = ($memberdata->as_stockist > 0) ? 'STOCKIST' : 'MEMBER';

            $info   .= '
            <div class="form-group">
                <label class="col-md-3 control-label">' . lang("phone") . ' &nbsp;</label>
                <div class="col-md-6">
                    <input type="text"  name="pin_stockist_phone" id="pin_stockist_phone" class="form-control" disabled="" value="' . $memberdata->phone . '" />
                </div>
            </div>';

            $info   .= '
            <div class="form-group">
                <label class="control-label col-md-3">' . lang("status") . ' &nbsp;</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Status Member" disabled="" value="' . $status . '" />
                </div>
            </div>';

            $input_province = '
            <input type="hidden" name="stockist_province" class="form-control" value="' . $member_province . '" />
            <div class="form-group">
                <label class="col-md-3 control-label">' . lang("province") . ' &nbsp;</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" disabled="" value="' . $province_name . '" />
                </div>
            </div>';
            $info   .= $input_province;

            $input_city = '
            <input type="hidden" name="stockist_city" class="form-control" value="' . $member_city . '" />
            <div class="form-group">
                <label class="col-md-3 control-label">' . lang("city") . ' &nbsp;</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" disabled="" value="' . $city_name . '" />
                </div>
            </div>';
            $info   .= $input_city;

            // Get shipping_address
            $add_address = true;
            $shipping    = '<div class="callout callout-warning bottom10" style="border-radius: 0;">
                            ' . strtoupper($memberdata->name) . ' (' . strtolower($memberdata->username) . ') belum menambahkan alamat pengiriman produk !
                            </div>';

            if ($shipping_address = ddm_shipping_addr_is_main($memberdata->id)) {
                $shipping = '<div class="callout callout-info bottom5" style="border-radius: 0;">
                                <input type="hidden" name="member_id" id="member_id" class="hide" value="' . $memberdata->id . '" />
                                <input type="hidden" name="member_shipping_id" id="member_shipping_id" class="hide" value="' . $shipping_address->id . '" />
                                <p class="lead bottom5">Alamat Pengiriman</p>
                                <strong>' . $shipping_address->label . ' <i class="fa fa-map-marker" style="margin-left: 5px"></i></strong>
                                <p class="text-muted" style="margin: 0px">
                                    <i class="fa fa-user" style="margin-right: 5px"></i> ' . $shipping_address->name . '
                                </p>
                                <strong class="text-danger">
                                    <i class="fa fa-phone" style="margin-right: 5px"></i> ' . $shipping_address->phone . '
                                </strong>
                                <p style="margin: 0px">' . $shipping_address->address . ', ' . $shipping_address->district . '</p>
                                <p style="margin: 0px">' . $shipping_address->city . ', ' . $shipping_address->province . '</p>
                            </div>';
                $add_address = false;
            }
        }

        // Set JSON data
        $data = array(
            'status'            => 'success',
            'message'           => 'Data Member ditemukan. Proses generate PIN dapat dilanjutkan',
            'info'              => $info,
            'shipping'          => $shipping,
            'add_address'       => $add_address,
            'member'            => $memberdata,
        );

        // JSON encode data
        die(json_encode($data));
    }

    /**
     * Check Username function.
     */
    function checkusernamestaff()
    {
        $username = $this->input->post('username');
        $username = ddm_isset($username, '');

        if (!empty($username)) {
            $memberdata     = $this->Model_Member->get_member_by('login', strtolower($username));

            if ($memberdata) {
                die('false');
            }

            // if staff with the username exists
            if ($staff = $this->Model_Staff->get_by('username', $username))
                die('false');
        }

        die('true');
    }

    /**
     * Check Email function.
     */
    function checkemail()
    {
        $id     = $this->input->post('id');
        $id     = trim(ddm_isset($id, ''));
        $email  = $this->input->post('email');
        $email  = trim(ddm_isset($email, ''));

        if (!empty($email)) {
            $memberdata = $this->Model_Member->get_member_by('email', $email);

            if ($memberdata) {
                if ($id) {
                    $id = ddm_encrypt($id, 'decrypt');
                    if ($id != $memberdata->id) {
                        die('false');
                    }
                } else {
                    die('false');
                }
            }
        }

        die('true');
    }

    /**
     * Check Phone function.
     */
    function checkphone()
    {
        $id     = $this->input->post('id');
        $id     = trim(ddm_isset($id, ''));
        $phone  = $this->input->post('phone');
        $phone  = trim(ddm_isset($phone, ''));

        if (!empty($phone)) {
            $memberdata     = $this->Model_Member->get_member_by('phone', $phone);

            if ($memberdata) {
                if ($id) {
                    $id = ddm_encrypt($id, 'decrypt');
                    if ($id != $memberdata->id) {
                        die('false');
                    }
                } else {
                    die('false');
                }
            }
        }

        die('true');
    }

    // ------------------------------------------------------------------------------------------------
    // Get City Function
    // ------------------------------------------------------------------------------------------------
    function citylist()
    {
        $daerah = isset($_GET['term']) ? strtolower($_GET['term']) : null;
        if ($daerah) {
            $data = $this->Model_Bank->get_city_list($daerah);
            if ($data) {
                foreach ($data as $row) {
                    $c[] = array('id' => $row->kode, 'label' => $row->daerah, 'value' => $row->daerah);
                }
                echo json_encode($c);
                exit;
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Get Member Data
    |--------------------------------------------------------------------------
    */
    function getAgentData()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $id = ddm_decrypt($this->input->post('id'));

        if ($member = ddm_get_memberdata_by_id($id)) {
            $member = ddm_unset_clone_member_data($member);
            $member->province_name = '';
            if ($provinces = ddm_provinces($member->province)) {
                $member->province_name = $provinces->province_name;
            }
            $response = array(
                'status' => 'success',
                'data'   => $member
            );
            die(json_encode($response));
        } else {
            $response = array(
                'status'  => 'failed',
                'message' => 'Userdata not found'
            );
            die(json_encode($response));
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Search Username function.
    |--------------------------------------------------------------------------
    */
    function searchAgentUsername()
    {
        $username   = $this->input->post('username');
        $username   = trim(ddm_isset($username, ''));
        $usertype   = $this->input->post('usertype');
        $usertype   = trim(ddm_isset($usertype, ''));
        $usertype   = ddm_decrypt($usertype);

        if ($username == 'admin') {
            $response = array(
                'status'  => 'failed',
                'message' => 'Username yang anda masukkan tidak diperbolehkan!'
            );
            die(json_encode($response));
        }

        $conditions = array('status' => ACTIVE, 'type' => $usertype);
        $memberdata = $this->Model_Member->get_member_by('login', $username, $conditions);

        if ($memberdata) {
            $data = '
                <input type="hidden" name="id_' . $memberdata->type . '" class="form-control" value="' . ddm_encrypt($memberdata->id) . '" />
                <input type="text" class="form-control text-capitalize" value="' . $memberdata->name . '" readonly>';

            $response = array(
                'status'  => 'success',
                'message' => 'Username Agen ditemukan',
                'data'    => $data
            );
            die(json_encode($response));
        } else {
            $response = array(
                'status'  => 'failed',
                'message' => 'Username Agen tidak ditemukan!'
            );
            die(json_encode($response));
        }
    }

    // ------------------------------------------------------------------------------------------------
}

/* End of file Member.php */
/* Location: ./application/controllers/Member.php */
