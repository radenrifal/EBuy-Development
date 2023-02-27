<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('DDM_Model.php');

class Model_Member extends DDM_Model
{
    /**
     * For DDM_Model
     */
    public $_table          = 'member';

    /**
     * Initialize table
     */
    var $member             = TBL_PREFIX . "member";
    var $member_confirm     = TBL_PREFIX . "member_confirm";
    var $member_omzet       = TBL_PREFIX . "member_omzet";
    var $bonus_qualified    = TBL_PREFIX . "bonus_qualified";
    var $bank               = TBL_PREFIX . "banks";
    var $bonus              = TBL_PREFIX . "bonus";
    var $package            = TBL_PREFIX . "package";
    var $reward             = TBL_PREFIX . "reward";
    var $upgrade            = TBL_PREFIX . "upgrade";
    var $province           = TBL_PREFIX . "province";
    var $district           = TBL_PREFIX . "district";
    var $subdistrict        = TBL_PREFIX . "subdistrict";
    var $point_share        = TBL_PREFIX . "point_share";
    var $grade              = TBL_PREFIX . "grade";

    /**
     * Initialize primary field
     */
    var $primary            = "id";
    var $parent             = "parent";

    /**
     * Constructor - Sets up the object properties.
     */
    public function __construct()
    {
        parent::__construct();
    }

    // ---------------------------------------------------------------------------------
    // CRUD (Manipulation) data member
    // ---------------------------------------------------------------------------------

    /**
     * Retrieve all member data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default array()
     * @param   String  $order_by           Column that make to order   default array()
     * @return  Object  Result of member list
     */
    function get_data($limit = 0, $offset = 0, $conditions = array(), $order_by = array())
    {
        $this->limit($limit, $offset);

        if ($order_by) {
            foreach ($order_by as $criteria => $order)
                $this->order_by($criteria, $order);
        }

        if ($conditions) {
            return $this->get_many_by($conditions);
        }

        return $this->get_all();
    }

    /**
     * Get member data by conditions
     *
     * @author  Yuda
     * @param   String  $field  (Required)  Database field name or special field name defined inside this function
     * @param   String  $value  (Optional)  Value of the field being searched
     * @return  Mixed   Boolean false on failed process, invalid data, or data is not found, otherwise StdClass of member
     */
    function get_member_by($field, $value = '', $conditions = '')
    {
        $id = '';

        switch ($field) {
            case 'id':
                $id     = $value;
                break;
            case 'email':
                $value  = sanitize_email($value);
                $id     = '';
                $field  = 'email';
                break;
            case 'phone':
                $value  = $value;
                $id     = '';
                $field  = 'phone';
                break;
            case 'idcard':
                $value  = $value;
                $id     = '';
                $field  = 'idcard';
                break;
            case 'bill':
                $value  = $value;
                $id     = '';
                $field  = 'bill';
                break;
            case 'login':
                $value  = $value;
                $id     = '';
                $field  = 'login';
                break;
            default:
                return false;
        }

        if ($id != '' && $id > 0)
            return $this->get_memberdata($id);

        if (empty($field)) return false;

        $db     = $this->db;

        if ($field == 'login') {
            $db->where('username', $value);
        } else {
            $db->where($field, $value);
        }

        if ($conditions) {
            $db->where($conditions);
        }

        $query  = $db->get($this->member);

        if (!$query->num_rows())
            return false;

        foreach ($query->result() as $row) {
            $member = $row;
        }

        return $member;
    }

    /**
     * Get Member by Field
     *
     * @author  Yuda
     * @param   String  $field  (Required)  Database field name or special field name defined inside this function
     * @param   String  $value  (Optional)  Value of the field being searched
     * @return  Mixed   Boolean false on failed process, invalid data, or data is not found, otherwise StdClass of member reward
     */
    function get_memberdata_by($field = '', $value = '', $conditions = array(), $limit = 0)
    {
        if (!$field) return false;
        if ($value === '') return false;

        $this->db->where($field, $value);
        if ($conditions) {
            $this->db->where($conditions);
        }

        $this->db->order_by("id", "ASC");
        $query  = $this->db->get($this->member);

        if (!$query->num_rows()) {
            return false;
        }

        $data   = $query->result();
        if ($field == 'id' || $limit == 1) {
            foreach ($data as $row) {
                $datarow = $row;
            }
            $data = $datarow;
        }

        return $data;
    }

    /**
     * Get member omzet by Field
     *
     * @author  Yuda
     * @param   String  $field  (Required)  Database field name or special field name defined inside this function
     * @param   String  $value  (Optional)  Value of the field being searched
     * @return  Mixed   Boolean false on failed process, invalid data, or data is not found, otherwise StdClass of data
     */
    function get_member_omzet_by($field = '', $value = '', $conditions = '', $limit = 0)
    {
        if (!$field || !$value) return false;

        $this->db->where($field, $value);
        if ($conditions) {
            $this->db->where($conditions);
        }

        $this->db->order_by("datecreated", "DESC");
        $query  = $this->db->get($this->member_omzet);
        if (!$query->num_rows()) {
            return false;
        }

        $data   = $query->result();
        if ($field == 'id' || $limit == 1) {
            foreach ($data as $row) {
                $datarow = $row;
            }
            $data = $datarow;
        }

        return $data;
    }

    /**
     * Get member data by member ID
     *
     * @author  Yuda
     * @param   Integer $member_id  (Required)  Member ID
     * @return  Mixed   False on failed process, otherwise object of member.
     */
    function get_memberdata($member_id)
    {
        if (!is_numeric($member_id)) return false;

        $member_id = absint($member_id);
        if (!$member_id) return false;

        $query = $this->db->get_where($this->member, array($this->primary => $member_id));
        if (!$query->num_rows())
            return false;

        foreach ($query->result() as $row) {
            $member = $row;
        }

        return $member;
    }

    /**
     * Get member confirm
     *
     * @author  Yuda
     * @param   Int     $id  (Required)  Member Confirm ID
     * @return  Mixed   False on invalid onfirm id, otherwise array of member confirm.
     */
    function get_member_confirm($id)
    {
        if (!is_numeric($id)) return false;

        $id  = absint($id);
        if (!$id) return false;

        $data       = array($this->primary => $id);
        $this->db->where($data);

        $query      = $this->db->get($this->member_confirm);

        if (!$query->num_rows())
            return false;

        return $query->row();
    }

    /**
     * Get member confirm by id downline
     *
     * @author  Yuda
     * @param   Int     $id  (Required)  Member Confirm ID
     * @return  Mixed   False on invalid onfirm id, otherwise array of member confirm.
     */
    function get_member_confirm_by_downline($id_downline)
    {
        if (!is_numeric($id_downline)) return false;

        $id_downline  = absint($id_downline);
        if (!$id_downline) return false;

        $data       = array('id_downline' => $id_downline);
        $this->db->where($data);

        $query      = $this->db->get($this->member_confirm);

        if (!$query->num_rows())
            return false;

        return $query->row();
    }

    /**
     * Get is dowline
     *
     * @author  Yuda
     * @param   Int     $id_member  (Required)  ID Member
     * @param   String  $up_tree    (Required)  Tree of upline
     * @return  Boolean false if invalid data, otherwise true if is downline.
     */
    function get_is_downline($id_member, $up_tree)
    {
        if (!is_numeric($id_member)) return false;

        $id_member  = absint($id_member);
        if (!$id_member) return false;

        if (empty($up_tree) || !$up_tree) return false;

        $this->db->where('id', $id_member);
        $this->db->like('tree', $up_tree, 'after');
        $query  = $this->db->get($this->member);

        if ($query->num_rows() > 0)
            return true;

        return false;
    }

    /**
     * Get ancestry of member
     * @author  Yuda
     */
    function get_ancestry($id_member)
    {
        $id_member = absint($id_member);
        if (!$id_member) return false;

        $sql = 'SELECT GetAncestry(id) AS ancestry FROM ' . $this->member . ' WHERE id=?';
        $qry = $this->db->query($sql, array($id_member));

        if (!$qry || !$qry->num_rows()) return false;
        return $qry->row()->ancestry;
    }

    /**
     * Get ancestry sponsor of member
     * @author  Yuda
     */
    function get_ancestry_sponsor($id_member)
    {
        $id_member = absint($id_member);
        if (!$id_member) return false;

        $sql = 'SELECT GetAncestrySponsor(id) AS ancestry FROM ' . $this->member . ' WHERE id=?';
        $qry = $this->db->query($sql, array($id_member));

        if (!$qry || !$qry->num_rows()) return false;
        return $qry->row()->ancestry;
    }

    /**
     * Get Position member of sponsor
     * @author  Yuda
     */
    function get_position_sponsor($id_sponsor)
    {
        $id_sponsor = absint($id_sponsor);
        if (!$id_sponsor) return 0;

        $position = 1;

        $sql = 'SELECT position FROM ' . $this->member . ' WHERE sponsor = ? ORDER BY position DESC';
        $qry = $this->db->query($sql, array($id_sponsor));

        if ($qry && $qry->num_rows()) {
            $position = $qry->row()->position + 1;
        }
        return $position;
    }

    /**
     * Get user sponsored by another user
     * @param   Int     $user_ids     Array of user ID
     * @author	Iqbal
     */
    function get_sponsored_by($user_ids)
    {
        if (!is_array($user_ids)) return false;

        $sql = '
            SELECT 
                A.*, 
                B.name AS sponsor_name, 
                B.username As sponsor_username
			FROM ' . $this->member . ' A
			INNER JOIN ' . $this->member . ' B ON B.id = A.sponsor 
			WHERE A.status = 1 AND A.type = ' . MEMBER . ' AND A.sponsor IN (' . implode(',', $user_ids) . ')
			ORDER BY B.id, A.id';
        $qry = $this->db->query($sql);

        if (!$qry || !$qry->num_rows()) return false;
        return $qry->result();
    }

    /**
     * Get downline data or count downline (child level 1)
     *
     * @author  Yuda
     * @param   Int     $id (Required)      Member ID
     * @param   String  $group (Optional)   Group of downline, default ''
     * @param   String  $status (Optional)  Status of Downline, value ('active' or 'pending')
     * @param   Boolean $count (Optional)   Get Count of downline
     * @return  Mixed   False on invalid member id, otherwise array of downline.
     */
    function get_downline($id_member, $position = '', $status = '', $count = false)
    {
        if (!is_numeric($id_member)) return false;

        $id_member = absint($id_member);
        if (!$id_member) return false;

        $this->db->where("parent", $id_member);
        if (!empty($status)) $this->db->where("status", ($status == 'active' ? 1 : 0));

        if (!empty($position)) $this->db->where("position", $position);
        $this->db->order_by("position", "ASC");

        $query = $this->db->get($this->member);

        if ($count) return $query->num_rows();
        if (!empty($position)) return $query->row();

        return $query->result();
    }

    /**
     * Get group ID's
     *
     * @author  Iqbal
     * @param   Int     $id_member_tree (Required)      Member ID Tree
     * @param   Boolean $count          (Optional)      Get Count of downline
     * @return  Mixed   False on invalid member id tree, otherwise array group ids data.
     */
    function get_group_ids($id_member_tree, $count = false)
    {
        if (!$id_member_tree) return false;

        $sql = 'SELECT id FROM ' . $this->member . ' WHERE tree LIKE "' . $id_member_tree . '%"';
        $query = $this->db->query($sql);

        if ($query->num_rows() == 0) return false;

        if ($count) return $query->num_rows();
        $ids = array();
        foreach ($query->result() as $row) {
            $ids[] = $row->id;
        }

        return $ids;
    }

    /**
     * Get Point Share by Field
     *
     * @author  Yuda
     * @param   String  $field  (Required)  Database field name or special field name defined inside this function
     * @param   String  $value  (Optional)  Value of the field being searched
     * @return  Mixed   Boolean false on failed process, invalid data, or data is not found, otherwise StdClass of member reward
     */
    function get_point_share_by($field = '', $value = '', $conditions = array(), $limit = 0)
    {
        if (!$field) return false;
        if ($value === '') return false;

        $this->db->where($field, $value);
        if ($conditions) {
            $this->db->where($conditions);
        }

        $this->db->order_by("id_member", "ASC");
        $query  = $this->db->get($this->point_share);

        if (!$query->num_rows()) {
            return false;
        }

        $data   = $query->result();
        if ($field == 'id' || $limit == 1) {
            foreach ($data as $row) {
                $datarow = $row;
            }
            $data = $datarow;
        }

        return $data;
    }

    /**
     * Get Reward by Field
     *
     * @author  Yuda
     * @param   String  $field  (Required)  Database field name or special field name defined inside this function
     * @param   String  $value  (Optional)  Value of the field being searched
     * @return  Mixed   Boolean false on failed process, invalid data, or data is not found, otherwise StdClass of member reward
     */
    function get_member_reward_by($field = '', $value = '', $condition = array())
    {
        if (!$field || !$value) return false;
        switch ($field) {
            case 'id':
                $field  = 'id';
                $id     = $value;
                break;
            case 'id_member':
                $field  = 'id_member';
                $value  = $value;
                break;
            case 'id_reward':
                $field  = 'id_reward';
                $value  = $value;
                break;
                return false;
        }

        if (empty($field)) return false;

        $data = array($field => $value);
        $this->db->where($data);

        if (!empty($condition)) {
            $this->db->where($condition);
        }

        $query = $this->db->get($this->reward);
        if (!$query->num_rows()) {
            return false;
        }
        return $query->row();
    }

    /**
     * Retrieve all member data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of member list
     */
    function get_all_member_data($limit = 0, $offset = 0, $conditions = '', $order_by = '', $num_rows = false)
    {
        if (!empty($conditions)) {
            $conditions = str_replace("%id%",                   "A.id", $conditions);
            $conditions = str_replace("%type%",                 "A.type", $conditions);
            $conditions = str_replace("%status%",               "A.status", $conditions);
            $conditions = str_replace("%username%",             "A.username", $conditions);
            $conditions = str_replace("%name%",                 "A.name", $conditions);
            $conditions = str_replace("%package%",              "A.package", $conditions);
            $conditions = str_replace("%position%",             "A.position", $conditions);
            $conditions = str_replace("%email%",                "A.email", $conditions);
            $conditions = str_replace("%phone%",                "A.phone", $conditions);
            $conditions = str_replace("%parent%",               "A.parent", $conditions);
            $conditions = str_replace("%sponsor%",              "A.sponsor", $conditions);
            $conditions = str_replace("%as_stockist%",          "A.as_stockist", $conditions);
            $conditions = str_replace("%province%",             "A.province", $conditions);
            $conditions = str_replace("%city%",                 "A.city", $conditions);
            $conditions = str_replace("%level%",                "A.level", $conditions);
            $conditions = str_replace("%tree%",                 "A.tree", $conditions);
            $conditions = str_replace("%sponsor_username%",     "B.username", $conditions);
            $conditions = str_replace("%upline_username%",      "B.username", $conditions);
            $conditions = str_replace("%lastlogin%",            "A.last_login", $conditions);
            $conditions = str_replace("%datecreated%",          "A.datecreated", $conditions);
            $conditions = str_replace("%datemodified%",         "A.datemodified", $conditions);
        }

        if (!empty($order_by)) {
            $order_by   = str_replace("%id%",                   "A.id",  $order_by);
            $order_by   = str_replace("%type%",                 "A.type", $order_by);
            $order_by   = str_replace("%status%",               "A.status", $order_by);
            $order_by   = str_replace("%username%",             "A.username", $order_by);
            $order_by   = str_replace("%name%",                 "A.name", $order_by);
            $order_by   = str_replace("%package%",              "A.package", $order_by);
            $order_by   = str_replace("%position%",             "A.position", $order_by);
            $order_by   = str_replace("%email%",                "A.email", $order_by);
            $order_by   = str_replace("%phone%",                "A.phone", $order_by);
            $order_by   = str_replace("%parent%",               "A.parent", $order_by);
            $order_by   = str_replace("%sponsor%",              "A.sponsor", $order_by);
            $order_by   = str_replace("%as_stockist%",          "A.as_stockist", $order_by);
            $order_by   = str_replace("%province%",             "A.province", $order_by);
            $order_by   = str_replace("%city%",                 "A.city", $order_by);
            $order_by   = str_replace("%level%",                "A.level", $order_by);
            $order_by   = str_replace("%tree%",                 "A.tree", $order_by);
            $order_by   = str_replace("%sponsor_username%",     "B.username", $order_by);
            $order_by   = str_replace("%upline_username%",      "B.username", $order_by);
            $order_by   = str_replace("%lastlogin%",            "A.last_login", $order_by);
            $order_by   = str_replace("%datecreated%",          "A.datecreated", $order_by);
            $order_by   = str_replace("%datemodified%",         "A.datemodified", $order_by);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS 
                    A.* ,
                    B.name AS sponsor_name,
                    C.name AS upline_name,
                    B.username AS sponsor_username,
                    C.username AS upline_username
                FROM ' . $this->member . ' AS A 
                LEFT JOIN ' . $this->member . ' AS B ON B.id = A.sponsor
                LEFT JOIN ' . $this->member . ' AS C ON C.id = A.parent ';

        if (!empty($conditions)) {
            $sql .= $conditions;
        }
        $sql   .= ' ORDER BY ' . (!empty($order_by) ? $order_by : 'A.datecreated DESC');

        if ($limit) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql);
        if (!$query || !$query->num_rows()) return false;

        if ($num_rows)
            return $query->num_rows();

        return $query->result();
    }

    /**
     * Retrieve all member confirm data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of member confirm list
     */
    function get_all_member_confirm($limit = 0, $offset = 0, $conditions = '', $order_by = '')
    {
        if (!empty($conditions)) {
            $conditions = str_replace("%id%",               "A.id", $conditions);
            $conditions = str_replace("%id_member%",        "A.id_member", $conditions);
            $conditions = str_replace("%id_sponsor%",       "A.id_sponsor", $conditions);
            $conditions = str_replace("%id_downline%",      "A.id_downline", $conditions);
            $conditions = str_replace("%member%",           "A.member", $conditions);
            $conditions = str_replace("%sponsor%",          "A.sponsor", $conditions);
            $conditions = str_replace("%downline%",         "A.downline", $conditions);
            $conditions = str_replace("%name%",             "B.name", $conditions);
            $conditions = str_replace("%package%",          "A.package", $conditions);
            $conditions = str_replace("%status%",           "A.status", $conditions);
            $conditions = str_replace("%status_member%",    "B.status", $conditions);
            $conditions = str_replace("%type%",             "B.type", $conditions);
            $conditions = str_replace("%access%",           "A.access", $conditions);
            $conditions = str_replace("%province%",         "B.province", $conditions);
            $conditions = str_replace("%city%",             "B.city", $conditions);
            $conditions = str_replace("%omzet%",            "A.omzet", $conditions);
            $conditions = str_replace("%nominal%",          "A.nominal", $conditions);
            $conditions = str_replace("%datecreated%",      "DATE(A.datecreated)", $conditions);
            $conditions = str_replace("%dateconfirm%",      "DATE(A.datemodified)", $conditions);
        }

        if (!empty($order_by)) {
            $order_by   = str_replace("%id%",               "A.id",  $order_by);
            $order_by   = str_replace("%member%",           "A.member",  $order_by);
            $order_by   = str_replace("%sponsor%",          "A.sponsor",  $order_by);
            $order_by   = str_replace("%downline%",         "A.downline",  $order_by);
            $order_by   = str_replace("%name%",             "B.name",  $order_by);
            $order_by   = str_replace("%package%",          "A.package",  $order_by);
            $order_by   = str_replace("%status%",           "A.status",  $order_by);
            $order_by   = str_replace("%access%",           "A.access", $order_by);
            $order_by   = str_replace("%province%",         "B.province", $order_by);
            $order_by   = str_replace("%city%",             "B.city", $order_by);
            $order_by   = str_replace("%omzet%",            "A.omzet", $order_by);
            $order_by   = str_replace("%nominal%",          "A.nominal", $order_by);
            $order_by   = str_replace("%datecreated%",      "A.datecreated",  $order_by);
            $order_by   = str_replace("%dateconfirm%",      "A.datemodified",  $order_by);
        }

        $sql    = 'SELECT SQL_CALC_FOUND_ROWS
                        A.*,
                        B.name,
                        B.phone,
                        B.email,
                        B.address,
                        B.province,
                        B.district
                    FROM ' . $this->member_confirm . ' AS A
                    LEFT JOIN ' . $this->member . ' AS B ON B.id = A.id_downline ';

        if (!empty($conditions)) {
            $sql .= $conditions;
        }

        $sql   .= ' ORDER BY ' . (!empty($order_by) ? $order_by : 'A.datecreated DESC');

        if ($limit) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql);
        if (!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Retrieve all member omzet data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of member omzet list
     */
    function get_all_member_omzet($limit = 0, $offset = 0, $conditions = '', $order_by = '')
    {
        if (!empty($conditions)) {
            $conditions = str_replace("%id%",               "B.id", $conditions);
            $conditions = str_replace("%id_member%",        "A.id_member", $conditions);
            $conditions = str_replace("%status%",           "A.status", $conditions);
            $conditions = str_replace("%username%",         "B.username", $conditions);
            $conditions = str_replace("%package%",          "B.package", $conditions);
            $conditions = str_replace("%qty%",              "A.qty", $conditions);
            $conditions = str_replace("%omzet%",            "A.omzet", $conditions);
            $conditions = str_replace("%desc%",             "A.desc", $conditions);
            $conditions = str_replace("%date%",             "A.date", $conditions);
            $conditions = str_replace("%datecreated%",      "A.datecreated", $conditions);
        }

        if (!empty($order_by)) {
            $order_by   = str_replace("%id%",               "B.id", $order_by);
            $order_by   = str_replace("%id_member%",        "A.id_member", $order_by);
            $order_by   = str_replace("%status%",           "A.status", $order_by);
            $order_by   = str_replace("%username%",         "B.username", $order_by);
            $order_by   = str_replace("%package%",          "B.package", $order_by);
            $order_by   = str_replace("%qty%",              "A.qty", $order_by);
            $order_by   = str_replace("%omzet%",            "A.omzet", $order_by);
            $order_by   = str_replace("%desc%",             "A.desc", $order_by);
            $order_by   = str_replace("%date%",             "A.date", $order_by);
            $order_by   = str_replace("%datecreated%",      "A.datecreated", $order_by);
        }

        $sql    = '
        SELECT SQL_CALC_FOUND_ROWS A.*, B.username, B.name, B.package, B.sponsor, B.tree
        FROM ' . $this->member_omzet . ' AS A
        JOIN ' . $this->member . ' AS B ON B.id = A.id_member
        WHERE B.status = ' . ACTIVE . ' AND B.type = ' . MEMBER;

        if (!empty($conditions)) {
            $sql .= $conditions;
        }

        $sql   .= ' ORDER BY ' . (!empty($order_by) ? $order_by : 'A.date DESC, A.datecreated ASC');

        if ($limit) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql);
        if (!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Retrieve all member Total omzet data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of member omzet list
     */
    function get_all_member_omzet_total($limit = 0, $offset = 0, $conditions = '', $order_by = '')
    {
        if (!empty($conditions)) {
            $conditions = str_replace("%id%",               "B.id", $conditions);
            $conditions = str_replace("%id_member%",        "A.id_member", $conditions);
            $conditions = str_replace("%status%",           "A.status", $conditions);
            $conditions = str_replace("%username%",         "B.username", $conditions);
            $conditions = str_replace("%package%",          "B.package", $conditions);
            $conditions = str_replace("%qty%",              "A.qty", $conditions);
            $conditions = str_replace("%omzet%",            "A.omzet", $conditions);
            $conditions = str_replace("%datecreated%",      "A.datecreated", $conditions);
        }

        if (!empty($order_by)) {
            $order_by   = str_replace("%id%",               "B.id", $order_by);
            $order_by   = str_replace("%id_member%",        "A.id_member", $order_by);
            $order_by   = str_replace("%status%",           "A.status", $order_by);
            $order_by   = str_replace("%username%",         "B.username", $order_by);
            $order_by   = str_replace("%package%",          "B.package", $order_by);
            $order_by   = str_replace("%qty%",              "A.qty", $order_by);
            $order_by   = str_replace("%omzet%",            "A.omzet", $order_by);
            $order_by   = str_replace("%datecreated%",      "A.datecreated", $order_by);
        }

        $sql    = '
        SELECT SQL_CALC_FOUND_ROWS A.*, SUM(A.omzet) AS total_omzet, SUM(A.qty) AS total_qty, B.username, B.name, B.package, B.sponsor, B.tree
        FROM ' . $this->member_omzet . ' AS A
        JOIN ' . $this->member . ' AS B ON B.id = A.id_member
        WHERE B.status = ' . ACTIVE . ' AND B.type = ' . MEMBER;

        if (!empty($conditions)) {
            $sql .= $conditions;
        }

        $sql .= ' GROUP BY A.id_member ';

        $sql   .= ' ORDER BY ' . (!empty($order_by) ? $order_by : 'total_omzet DESC');

        if ($limit) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql);
        if (!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Retrieve all member Gen omzet data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of member omzet list
     */
    function get_all_member_generation_omzet($limit = 0, $offset = 0, $conditions = '', $order_by = '')
    {
        if (!empty($conditions)) {
            $conditions = str_replace("%id%",               "A.id", $conditions);
            $conditions = str_replace("%id_member%",        "A.id", $conditions);
            $conditions = str_replace("%username%",         "A.username", $conditions);
            $conditions = str_replace("%name%",             "A.name", $conditions);
            $conditions = str_replace("%package%",          "A.package", $conditions);
            $conditions = str_replace("%level%",            "A.level", $conditions);
            $conditions = str_replace("%tree%",             "A.tree", $conditions);
            $conditions = str_replace("%sponsor_username%", "B.username", $conditions);
            $conditions = str_replace("%sponsor_name%",     "B.name", $conditions);
            $conditions = str_replace("%omzet_perdana%",    "C.omzet_perdana", $conditions);
            $conditions = str_replace("%qty_perdana%",      "C.qty_perdana", $conditions);
            $conditions = str_replace("%omzet_ro%",         "D.omzet_ro", $conditions);
            $conditions = str_replace("%qty_ro%",           "D.qty_ro", $conditions);
            $conditions = str_replace("%date_join%",        "MC.datemodified", $conditions);
            $conditions = str_replace("%datecreated%",      "A.datecreated", $conditions);
        }

        if (!empty($order_by)) {
            $order_by   = str_replace("%id%",               "A.id", $order_by);
            $order_by   = str_replace("%id_member%",        "A.id", $order_by);
            $order_by   = str_replace("%username%",         "A.username", $order_by);
            $order_by   = str_replace("%name%",             "A.name", $order_by);
            $order_by   = str_replace("%package%",          "A.package", $order_by);
            $order_by   = str_replace("%level%",            "A.level", $order_by);
            $order_by   = str_replace("%sponsor_username%", "B.username", $order_by);
            $order_by   = str_replace("%sponsor_name%",     "B.name", $order_by);
            $order_by   = str_replace("%omzet_perdana%",    "C.omzet_perdana", $order_by);
            $order_by   = str_replace("%qty_perdana%",      "C.qty_perdana", $order_by);
            $order_by   = str_replace("%omzet_ro%",         "D.omzet_ro", $order_by);
            $order_by   = str_replace("%qty_ro%",           "D.qty_ro", $order_by);
            $order_by   = str_replace("%date_join%",        "MC.datemodified", $order_by);
            $order_by   = str_replace("%datecreated%",      "A.datecreated", $order_by);
        }

        $sql    = 'SELECT SQL_CALC_FOUND_ROWS A.*, 
                        B.name AS sponsor_name, B.username AS sponsor_username,
                        IFNULL(C.omzet_perdana, 0) AS omzet_perdana,
                        IFNULL(C.qty_perdana, 0) AS qty_perdana,
                        IFNULL(D.omzet_ro, 0) AS omzet_ro,
                        IFNULL(D.qty_ro, 0) AS qty_ro,
                        MC.datemodified AS date_join
                    FROM ' . $this->member . ' AS A
                    INNER JOIN ' . $this->member_confirm . ' AS MC ON (MC.id_downline = A.id)
                    LEFT JOIN ' . $this->member . ' AS B ON (B.id = A.sponsor)
                    LEFT JOIN (
                        SELECT OP.id_member, IFNULL(SUM(OP.omzet), 0) AS omzet_perdana, IFNULL(SUM(OP.qty), 0) qty_perdana
                        FROM ' . $this->member_omzet . ' AS OP
                        WHERE OP.`status` = "perdana"
                        GROUP BY OP.id_member
                    ) AS C ON (C.id_member = A.id) 
                    LEFT JOIN (
                        SELECT ORO.id_member, IFNULL(SUM(ORO.omzet), 0) AS omzet_ro, IFNULL(SUM(ORO.qty), 0) qty_ro
                        FROM ' . $this->member_omzet . ' AS ORO
                        WHERE ORO.`status` = "ro"
                        GROUP BY ORO.id_member
                    ) AS D ON (D.id_member = A.id)
                    WHERE A.`status` = ' . ACTIVE . ' AND A.type = ' . MEMBER;

        if (!empty($conditions)) {
            $sql .= $conditions;
        }

        $sql   .= ' ORDER BY ' . (!empty($order_by) ? $order_by : 'A.id ASC');

        if ($limit) $sql .= ' LIMIT ' . $offset . ', ' . $limit;;

        $query = $this->db->query($sql);
        if (!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Retrieve all member address data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of member list
     */
    function get_all_member_address($limit = 0, $offset = 0, $conditions = '', $order_by = '', $num_rows = false)
    {
        if (!empty($conditions)) {
            $conditions = str_replace("%id%",                   "M.id", $conditions);
            $conditions = str_replace("%username%",             "M.username", $conditions);
            $conditions = str_replace("%name%",                 "M.name", $conditions);
            $conditions = str_replace("%email%",                "M.email", $conditions);
            $conditions = str_replace("%phone%",                "M.phone", $conditions);
            $conditions = str_replace("%type%",                 "M.type", $conditions);
            $conditions = str_replace("%status%",               "M.status", $conditions);
            $conditions = str_replace("%province_id%",          "P.id", $conditions);
            $conditions = str_replace("%district_id%",          "D.id", $conditions);
            $conditions = str_replace("%subdistrict_id%",       "S.id", $conditions);
            $conditions = str_replace("%province%",             "P.province_name", $conditions);
            $conditions = str_replace("%district%",             "D.district_name", $conditions);
            $conditions = str_replace("%subdistrict%",          "S.subdistrict_name", $conditions);
            $conditions = str_replace("%datecreated%",          "M.datecreated", $conditions);
            $conditions = str_replace("%datemodified%",         "M.datemodified", $conditions);
        }

        if (!empty($order_by)) {
            $order_by   = str_replace("%id%",                   "M.id",  $order_by);
            $order_by   = str_replace("%username%",             "M.username", $order_by);
            $order_by   = str_replace("%name%",                 "M.name", $order_by);
            $order_by   = str_replace("%email%",                "M.email", $order_by);
            $order_by   = str_replace("%phone%",                "M.phone", $order_by);
            $order_by   = str_replace("%type%",                 "M.type", $order_by);
            $order_by   = str_replace("%status%",               "M.status", $order_by);
            $order_by   = str_replace("%province%",             "P.province_name", $order_by);
            $order_by   = str_replace("%district%",             "D.district_name", $order_by);
            $order_by   = str_replace("%subdistrict%",          "S.subdistrict_name", $order_by);
            $order_by   = str_replace("%datecreated%",          "M.datecreated", $order_by);
            $order_by   = str_replace("%datemodified%",         "M.datemodified", $order_by);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS 
                    M.id, M.username, M.name, M.phone, M.email, M.address,
                    P.id AS province_id, P.province_name, 
                    D.id AS district_id, D.district_name, D.district_type, 
                    S.id AS subdistrict_id, S.subdistrict_name
                FROM ' . $this->member . ' AS M 
                INNER JOIN ' . $this->province . ' AS P ON (P.id = M.province) 
                INNER JOIN ' . $this->district . ' AS D ON (D.id = M.district AND D.province_id = P.id) 
                INNER JOIN ' . $this->subdistrict . ' AS S ON (S.id = M.subdistrict AND S.district_id = D.id) 
                WHERE M.id > 1 ';

        if (!empty($conditions)) {
            $sql .= $conditions;
        }
        $sql   .= ' ORDER BY ' . (!empty($order_by) ? $order_by : 'M.name ASC');

        if ($limit) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql);
        if (!$query || !$query->num_rows()) return false;

        if ($num_rows)
            return $query->num_rows();

        return $query->result();
    }

    /**
     * Retrieve all omzet daily data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of data               default 0
     * @param   Int     $offset             Offset ot data              default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @param   String  $total_conditions   Total Condition of query    default ''
     * @return  Object  Result of Data List
     */
    function get_all_omzet_daily($limit = 0, $offset = 0, $conditions = '', $order_by = '', $total_conditions = '')
    {
        if (!empty($conditions)) {
            $conditions = str_replace("%date_omzet%",           "date_omzet", $conditions);
        }

        $total_omzet    = 'IFNULL( SUM(A.omzet_register), 0 ) + IFNULL( SUM(A.omzet_perdana), 0 ) + IFNULL( SUM(A.omzet_ro), 0 )';
        $omzet_bonus    = '( ' . $total_omzet . ' - IFNULL(SUM(A.bonus), 0) )';
        $percent        = 'ROUND( ( 100 / (' . $total_omzet . ') ) * ' . $omzet_bonus . ', 2 )  ';

        if ($total_conditions) {
            $total_conditions = str_replace("%trx_register%",       "COUNT(*)", $total_conditions);
            $total_conditions = str_replace("%omzet_register%",     "SUM(A.omzet_register)", $total_conditions);
            $total_conditions = str_replace("%omzet_perdana%",      "SUM(A.omzet_perdana)", $total_conditions);
            $total_conditions = str_replace("%omzet_ro%",           "SUM(A.omzet_ro)", $total_conditions);
            $total_conditions = str_replace("%total_bonus%",        "SUM(A.bonus)", $total_conditions);
            $total_conditions = str_replace("%total_omzet%",        $total_omzet, $total_conditions);
            $total_conditions = str_replace("%percent%",            $percent, $total_conditions);
        }

        if (!empty($order_by)) {
            $order_by   = str_replace("%date_omzet%",           "date_omzet", $order_by);
            $order_by   = str_replace("%omzet_register%",       "total_omzet_register", $order_by);
            $order_by   = str_replace("%omzet_perdana%",        "total_omzet_perdana", $order_by);
            $order_by   = str_replace("%omzet_ro%",             "total_omzet_ro", $order_by);
            $order_by   = str_replace("%total_omzet%",          "total_omzet", $order_by);
            $order_by   = str_replace("%total_bonus%",          "total_bonus", $order_by);
            $order_by   = str_replace("%percent%",              "percent", $order_by);
            $order_by   = str_replace("%total_trx%",            "total_trx", $order_by);
        }

        $sql    = 'SELECT SQL_CALC_FOUND_ROWS 
                        A.date_omzet,
                        IFNULL(COUNT(*), 0) AS total_trx,
                        IFNULL(SUM(A.omzet_register), 0) AS total_omzet_register,
                        IFNULL(SUM(A.omzet_perdana), 0) AS total_omzet_perdana,
                        IFNULL(SUM(A.omzet_ro), 0) AS total_omzet_ro,
                        IFNULL(SUM(A.bonus), 0) AS total_bonus,
                        ' . $total_omzet . ' AS total_omzet,
                        ' . $percent . ' AS percent
                    FROM (
                        SELECT 
                            DATE_FORMAT(MREG.datecreated, "%Y-%m-%d") AS date_omzet,
                            MREG.omzet AS omzet_register,
                            0 AS omzet_perdana,
                            0 AS omzet_ro,
                            0 AS bonus
                        FROM `' . $this->member_omzet . '` MREG
                        WHERE MREG.status = "register" AND MREG.omzet > 0
                        UNION ALL
                        SELECT 
                            DATE_FORMAT(MP.datecreated, "%Y-%m-%d") AS date_omzet,
                            0 AS omzet_register,
                            MP.omzet AS omzet_perdana,
                            0 AS omzet_ro,
                            0 AS bonus
                        FROM `' . $this->member_omzet . '` MP
                        WHERE MP.status = "perdana" AND MP.omzet > 0
                        UNION ALL
                        SELECT 
                            DATE_FORMAT(MR.datecreated, "%Y-%m-%d") AS date_omzet,
                            0 AS omzet_register,
                            0 AS omzet_perdana,
                            MR.omzet AS omzet_ro,
                            0 AS bonus
                        FROM `' . $this->member_omzet . '` MR
                        WHERE MR.status = "ro" AND MR.omzet > 0
                        UNION ALL
                        SELECT 
                            DATE_FORMAT(B.datecreated, "%Y-%m-%d") AS date_omzet,
                            0 AS omzet_register,
                            0 AS omzet_perdana,
                            0 AS omzet_ro,
                            B.amount AS bonus
                        FROM `' . $this->bonus . '` B
                    ) AS A ';

        if (!empty($conditions)) {
            $sql .= $conditions;
        }

        $sql   .= ' GROUP BY 1 ';

        if ($total_conditions) {
            $sql .= ' HAVING ' . ltrim($total_conditions, ' AND');
        }

        $sql   .= ' ORDER BY ' . (!empty($order_by) ? $order_by : 'date_omzet DESC');

        if ($limit) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql);
        if (!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Retrieve all omzet monthly data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of data               default 0
     * @param   Int     $offset             Offset ot data              default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @param   String  $total_conditions   Total Condition of query    default ''
     * @return  Object  Result of Data List
     */
    function get_all_omzet_monthly($limit = 0, $offset = 0, $conditions = '', $order_by = '', $total_conditions = '')
    {
        if (!empty($conditions)) {
            $conditions = str_replace("%month_omzet%",          "month_omzet", $conditions);
        }

        $total_omzet    = 'IFNULL( SUM(A.omzet_register), 0 ) + IFNULL( SUM(A.omzet_perdana), 0 ) + IFNULL( SUM(A.omzet_ro), 0 )';
        $omzet_bonus    = '( ' . $total_omzet . ' - IFNULL(SUM(A.bonus), 0) )';
        $percent        = 'ROUND( ( 100 / (' . $total_omzet . ') ) * ' . $omzet_bonus . ', 2 )  ';
        $percent        = 'ROUND( ( IFNULL(SUM(A.bonus), 0) / ( ' . $total_omzet . ' ) * 100 ), 2 )  ';

        if ($total_conditions) {
            $total_conditions = str_replace("%trx_register%",   "COUNT(*)", $total_conditions);
            $total_conditions = str_replace("%omzet_register%", "SUM(A.omzet_register)", $total_conditions);
            $total_conditions = str_replace("%omzet_perdana%",  "SUM(A.omzet_perdana)", $total_conditions);
            $total_conditions = str_replace("%omzet_ro%",       "SUM(A.omzet_ro)", $total_conditions);
            $total_conditions = str_replace("%total_bonus%",    "SUM(A.bonus)", $total_conditions);
            $total_conditions = str_replace("%total_omzet%",    $total_omzet, $total_conditions);
            $total_conditions = str_replace("%percent%",        $percent, $total_conditions);
        }

        if (!empty($order_by)) {
            $order_by   = str_replace("%month_omzet%",          "month_omzet", $order_by);
            $order_by   = str_replace("%omzet_register%",       "total_omzet_register", $order_by);
            $order_by   = str_replace("%omzet_perdana%",        "total_omzet_perdana", $order_by);
            $order_by   = str_replace("%omzet_ro%",             "total_omzet_ro", $order_by);
            $order_by   = str_replace("%total_omzet%",          "total_omzet", $order_by);
            $order_by   = str_replace("%total_bonus%",          "total_bonus", $order_by);
            $order_by   = str_replace("%percent%",              "percent", $order_by);
            $order_by   = str_replace("%total_trx%",            "total_trx", $order_by);
        }

        $sql    = 'SELECT SQL_CALC_FOUND_ROWS 
                        A.month_omzet,
                        IFNULL(COUNT(*), 0) AS total_trx,
                        IFNULL(SUM(A.omzet_register), 0) AS total_omzet_register,
                        IFNULL(SUM(A.omzet_perdana), 0) AS total_omzet_perdana,
                        IFNULL(SUM(A.omzet_ro), 0) AS total_omzet_ro,
                        IFNULL(SUM(A.bonus), 0) AS total_bonus,
                        ' . $total_omzet . ' AS total_omzet,
                        ' . $percent . ' AS percent
                    FROM (
                        SELECT 
                            DATE_FORMAT(MREG.datecreated, "%Y-%m") AS month_omzet,
                            MREG.omzet AS omzet_register,
                            0 AS omzet_perdana,
                            0 AS omzet_ro,
                            0 AS bonus
                        FROM `' . $this->member_omzet . '` MREG
                        WHERE MREG.status = "register" AND MREG.omzet > 0
                        UNION ALL
                        SELECT 
                            DATE_FORMAT(MP.datecreated, "%Y-%m") AS month_omzet,
                            0 AS omzet_register,
                            MP.omzet AS omzet_perdana,
                            0 AS omzet_ro,
                            0 AS bonus
                        FROM `' . $this->member_omzet . '` MP
                        WHERE MP.status = "perdana" AND MP.omzet > 0
                        UNION ALL
                        SELECT 
                            DATE_FORMAT(MR.datecreated, "%Y-%m") AS month_omzet,
                            0 AS omzet_register,
                            0 AS omzet_perdana,
                            MR.omzet AS omzet_ro,
                            0 AS bonus
                        FROM `' . $this->member_omzet . '` MR
                        WHERE MR.status = "ro" AND MR.omzet > 0
                        UNION ALL
                        SELECT 
                            DATE_FORMAT(B.datecreated, "%Y-%m") AS month_omzet,
                            0 AS omzet_register,
                            0 AS omzet_perdana,
                            0 AS omzet_ro,
                            B.amount AS bonus
                        FROM `' . $this->bonus . '` B
                    ) AS A ';

        if (!empty($conditions)) {
            $sql .= $conditions;
        }

        $sql   .= ' GROUP BY 1 ';

        if ($total_conditions) {
            $sql .= ' HAVING ' . ltrim($total_conditions, ' AND');
        }

        $sql   .= ' ORDER BY ' . (!empty($order_by) ? $order_by : 'month_omzet DESC');

        if ($limit) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql);
        if (!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Retrieve all omzet member monthly data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of data               default 0
     * @param   Int     $offset             Offset ot data              default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @param   String  $total_conditions   Total Condition of query    default ''
     * @return  Object  Result of Data List
     */
    function get_all_omzet_member_monthly($limit = 0, $offset = 0, $conditions = '', $order_by = '', $total_conditions = '')
    {
        if (!empty($conditions)) {
            $conditions = str_replace("%month_omzet%",          "month_omzet", $conditions);
            $conditions = str_replace("%id_member%",            "M.id", $conditions);
            $conditions = str_replace("%username%",             "M.username", $conditions);
            $conditions = str_replace("%name%",                 "M.name", $conditions);
        }

        $total_omzet    = '( IFNULL( SUM(A.omzet_perdana), 0 ) + IFNULL( SUM(A.omzet_ro), 0 ) )';
        $total_point    = '( IFNULL( SUM(A.point_perdana), 0 ) + IFNULL( SUM(A.point_ro), 0 ) )';
        $total_qty      = '( IFNULL( SUM(A.qty_perdana), 0 ) + IFNULL( SUM(A.qty_ro), 0 ) )';

        if ($total_conditions) {
            $total_conditions = str_replace("%omzet_perdana%",  "SUM(A.omzet_perdana)", $total_conditions);
            $total_conditions = str_replace("%point_perdana%",  "SUM(A.point_perdana)", $total_conditions);
            $total_conditions = str_replace("%qty_perdana%",    "SUM(A.qty_perdana)", $total_conditions);
            $total_conditions = str_replace("%omzet_ro%",       "SUM(A.omzet_ro)", $total_conditions);
            $total_conditions = str_replace("%point_ro%",       "SUM(A.point_ro)", $total_conditions);
            $total_conditions = str_replace("%point_ro%",       "SUM(A.point_ro)", $total_conditions);
            $total_conditions = str_replace("%qty_ro%",         "SUM(A.qty_ro)", $total_conditions);
            $total_conditions = str_replace("%total_omzet%",    $total_omzet, $total_conditions);
            $total_conditions = str_replace("%total_point%",    $total_omzet, $total_conditions);
            $total_conditions = str_replace("%total_qty%",      $total_omzet, $total_conditions);
        }

        if (!empty($order_by)) {
            $order_by   = str_replace("%month_omzet%",          "month_omzet", $order_by);
            $order_by   = str_replace("%id_member%",            "M.id", $order_by);
            $order_by   = str_replace("%username%",             "username", $order_by);
            $order_by   = str_replace("%name%",                 "name", $order_by);
            $order_by   = str_replace("%omzet_perdana%",        "total_omzet_perdana", $order_by);
            $order_by   = str_replace("%point_perdana%",        "total_point_perdana", $order_by);
            $order_by   = str_replace("%qty_perdana%",          "total_qty_perdana", $order_by);
            $order_by   = str_replace("%omzet_ro%",             "total_omzet_ro", $order_by);
            $order_by   = str_replace("%point_ro%",             "total_point_ro", $order_by);
            $order_by   = str_replace("%qty_ro%",               "total_qty_ro", $order_by);
            $order_by   = str_replace("%total_omzet%",          "total_omzet", $order_by);
            $order_by   = str_replace("%total_point%",          "total_point", $order_by);
            $order_by   = str_replace("%total_qty%",            "total_qty", $order_by);
        }

        $sql    = 'SELECT SQL_CALC_FOUND_ROWS 
                        A.month_omzet,
                        A.id_member,
                        M.id,
                        M.username,
                        M.name,
                        IFNULL(SUM(A.omzet_perdana), 0) AS total_omzet_perdana,
                        IFNULL(SUM(A.point_perdana), 0) AS total_point_perdana,
                        IFNULL(SUM(A.qty_perdana), 0) AS total_qty_perdana,
                        IFNULL(SUM(A.omzet_ro), 0) AS total_omzet_ro,
                        IFNULL(SUM(A.point_ro), 0) AS total_point_ro,
                        IFNULL(SUM(A.qty_ro), 0) AS total_qty_ro,
                        ' . $total_omzet . ' AS total_omzet,
                        ' . $total_point . ' AS total_point,
                        ' . $total_qty . ' AS total_qty
                    FROM (
                        SELECT 
                            MP.id_member,
                            DATE_FORMAT(MP.datecreated, "%Y-%m") AS month_omzet,
                            MP.omzet AS omzet_perdana,
                            MP.point AS point_perdana,
                            MP.qty AS qty_perdana,
                            0 AS omzet_ro,
                            0 AS point_ro,
                            0 AS qty_ro
                        FROM `' . $this->member_omzet . '` MP
                        WHERE MP.status = "perdana"
                        UNION ALL
                        SELECT 
                            MR.id_member,
                            DATE_FORMAT(MR.datecreated, "%Y-%m") AS month_omzet,
                            0 AS omzet_perdana,
                            0 AS point_perdana,
                            0 AS qty_perdana,
                            MR.omzet AS omzet_ro,
                            MR.point AS point_ro,
                            MR.qty AS qty_ro
                        FROM `' . $this->member_omzet . '` MR
                        WHERE MR.status = "ro"
                    ) AS A 
                    INNER JOIN ' . $this->member . ' AS M ON (M.id = A.id_member) 
                    WHERE M.type = ' . MEMBER . ' AND M.status = ' . ACTIVE . ' ' . $conditions . ' 
                    GROUP BY 1, 2';

        if ($total_conditions) {
            $sql .= ' HAVING ' . ltrim($total_conditions, ' AND');
        }

        $sql   .= ' ORDER BY ' . (!empty($order_by) ? $order_by : 'month_omzet DESC');

        if ($limit) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql);
        if (!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Retrieve member Reward data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of data               default 0
     * @param   Int     $offset             Offset ot data              default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of Data List
     */
    function get_all_member_reward($limit = 0, $offset = 0, $conditions = '', $order_by = '')
    {
        if (!empty($conditions)) {
            $conditions = str_replace("%id_member%",        "A.id_member", $conditions);
            $conditions = str_replace("%id_reward%",        "A.id_reward", $conditions);
            $conditions = str_replace("%username%",         "M.username", $conditions);
            $conditions = str_replace("%name%",             "M.name", $conditions);
            $conditions = str_replace("%type%",             "A.type", $conditions);
            $conditions = str_replace("%nominal%",          "A.nominal", $conditions);
            $conditions = str_replace("%status%",           "A.status", $conditions);
            $conditions = str_replace("%message%",          "A.message", $conditions);
            $conditions = str_replace("%datecreated%",      "DATE(A.datecreated)", $conditions);
            $conditions = str_replace("%datemodified%",     "DATE(A.datemodified)", $conditions);
        }

        if (!empty($order_by)) {
            $order_by = str_replace("%username%",           "M.username", $order_by);
            $order_by = str_replace("%username%",           "M.username", $order_by);
            $order_by = str_replace("%name%",               "M.name", $order_by);
            $order_by = str_replace("%type%",               "A.type", $order_by);
            $order_by = str_replace("%nominal%",            "A.nominal", $order_by);
            $order_by = str_replace("%status%",             "A.status", $order_by);
            $order_by = str_replace("%message%",            "A.message", $order_by);
            $order_by = str_replace("%datecreated%",        "A.datecreated", $order_by);
            $order_by = str_replace("%datemodified%",       "A.datemodified", $order_by);
        }

        $sql = '
            SELECT SQL_CALC_FOUND_ROWS
                A.*,
                M.name,
                M.username,
                B.nama AS bank,
                B.kode AS code_bank
            FROM ' . $this->reward . ' AS A
            INNER JOIN ' . $this->member . ' AS M ON M.id = A.id_member 
            INNER JOIN ' . $this->bank . '  AS B ON B.id = M.bank 
            WHERE M.type = ? ';

        if (!empty($conditions)) {
            $sql .= $conditions;
        }

        $sql   .= ' ORDER BY ' . (!empty($order_by) ? $order_by : ' A.datecreated DESC');

        if ($limit) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql, array(MEMBER));
        if (!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Count data of member
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of member
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function count_data($status = '')
    {
        $sql = 'SELECT COUNT(id) AS member_count FROM ' . $this->member . ' WHERE type = ?';
        if ($status == 'active') {
            $sql .= ' AND status = ' . ACTIVE;
        }
        $qry = $this->db->query($sql, array(MEMBER));
        $row = $qry->row();

        return $row->member_count;
    }

    /**
     * Count All Member By Sponsor
     *
     * @author  Yuda
     * @param   Int     $sponsor    Sponsor Member
     * @return  Int of total count member
     */
    function count_by_sponsor($sponsor, $condition = '')
    {
        if (!$sponsor) return 0;
        $sql = 'SELECT IFNULL(COUNT(id),0) AS member_count FROM ' . $this->member . ' WHERE status = ? AND type = ? AND sponsor = ? ';
        if ($condition) {
            $sql .= $condition;
        }
        $qry = $this->db->query($sql, array(ACTIVE, MEMBER, $sponsor));
        if (!$qry || !$qry->num_rows()) return 0;

        return $qry->row()->member_count;
    }

    /**
     * Get Count Child
     *
     * @author  Yuda
     * @param   Int     $id (Required)  Member ID
     * @param   String  $position (Required)  Position Of Node, value ('kiri' or 'kanan')
     * @param   Boolean $tree (Optional)  Get Only Tree
     * @param   String  $cfg (Required)  Point Of Node, value ('all' or 'childs' or 'pairing')
     * @param   Date    $datecreated (Optional)  Date Join of member
     * @param   Boolean $equaldate (Optional)  Get Only Date Join of member
     * @return  Mixed   False on invalid member id, otherwise count or array of invest.
     */
    function count_childs($id_member, $position = POS_LEFT, $tree = true, $cfg = 'all', $datecreated = '', $equaldate = false)
    {
        if (!is_numeric($id_member)) return false;

        $id_member = absint($id_member);
        if (!$id_member) return false;

        $pos = $position;
        if ($position == POS_LEFT) {
            $pos = POS_LEFT;
        }
        if ($position == POS_RIGHT) {
            $pos = POS_RIGHT;
        }

        $point  = 0;
        $result = array('total_downline' => $point, 'total_pairing' => $point, 'total_reward' => $point);

        $sql = 'SELECT A.id, A.username, B.tree AS tree_downline, B.position AS pos_downline, B.id AS id_downline FROM ' . $this->member . ' A
                JOIN ' . $this->member . ' B ON (A.id = B.parent)
                WHERE A.id=? AND B.position=?';
        $qry = $this->db->query($sql, array($id_member, $pos));

        if (!$qry || !$qry->num_rows()) {
            if ($tree) return $result;
            return $point;
        }

        $row = $qry->row();

        // Calculate Total Omzet Member
        $total_downline = 0;
        $condition      = '';

        $_sql   = 'SELECT COUNT(id) AS childs FROM ' . $this->member . ' WHERE tree LIKE "' . $row->tree_downline . '%" ' . $condition;
        if ($datecreated) {
            $_sql .= ' AND DATE(datecreated) <= "' . $datecreated . '" ';
        }

        $_qry = $this->db->query($_sql);
        if ($_qry && $_qry->num_rows()) {
            $total_downline = $point = $_qry->row()->childs;;
        }

        if ($cfg == 'childs') {
            $point = $total_downline;
        }
        $result['total_downline'] = $total_downline;

        // Calculate Total Pairing Point Member
        if ($cfg == 'all' || $cfg == 'pairing') {
            $date_omzet = $datecreated ? date('Y-m-d', strtotime($datecreated)) : date('Y-m-d');

            $_sql_to = 'SELECT IFNULL(SUM(O.pairing_point),0) AS total_pairing
                    FROM ' . $this->member . ' M
                    INNER JOIN ' . $this->member_omzet . ' O ON (O.id_member = M.id)
                    WHERE M.tree LIKE "' . $row->tree_downline . '%" ';
            if ($equaldate) {
                $_sql_to .= ' AND O.date = "' . $date_omzet . '"';
            } else {
                $_sql_to .= ' AND O.date <= "' . $date_omzet . '"';
            }

            $_qry_to = $this->db->query($_sql_to);

            if ($_qry_to && $_qry_to->num_rows()) {
                if ($cfg == 'pairing') {
                    $point = $_qry_to->row()->total_pairing;
                }
                $result['total_pairing'] = $_qry_to->row()->total_pairing;
            }
        }

        // Calculate Total Reward Point Member
        if ($cfg == 'all' || $cfg == 'reward') {
            $date_omzet = $datecreated ? date('Y-m-d', strtotime($datecreated)) : date('Y-m-d');

            $_sql_to = 'SELECT IFNULL(SUM(P.reward_point),0) AS total_reward
                    FROM ' . $this->member . ' M
                    INNER JOIN ' . $this->package . ' P ON (P.package = M.package)
                    WHERE M.tree LIKE "' . $row->tree_downline . '%" ';
            if ($equaldate) {
                $_sql_to .= ' AND (DATE(M.datecreated) = "' . $date_omzet . '" OR DATE(M.dateupgrade) = "' . $date_omzet . '")';
            } else {
                $_sql_to .= ' AND (DATE(M.datecreated) <= "' . $date_omzet . '" OR DATE(M.dateupgrade) <= "' . $date_omzet . '")';
            }

            $_qry_to = $this->db->query($_sql_to);

            if ($_qry_to && $_qry_to->num_rows()) {
                if ($cfg == 'reward') {
                    $point = $_qry_to->row()->total_reward;
                }
                $result['total_reward'] = $_qry_to->row()->total_reward;
            }
        }

        if ($tree) return $result;
        return $point;
    }

    /**
     * Get Count Pairing Qualified
     *
     * @author  Yuda
     * @param   Int $id (Required)  Member ID
     * @param   Int $status (Optional)  Status of investment
     * @return  Mixed  False on invalid member id, otherwise count or array of invest.
     */
    function count_pairing_qualified($id_member, $count_total = true, $datecreated = '', $equal = false)
    {
        if (!is_numeric($id_member)) return 0;

        $id_member = absint($id_member);
        if (!$id_member) return 0;

        $sql = 'SELECT 
                    IFNULL(SUM(`point_left`), 0) AS total_left, 
                    IFNULL(SUM(`point_right`), 0) AS total_right,
                    IFNULL(SUM(`qualified`), 0) AS total_qualified
                FROM ' . $this->bonus_qualified . ' WHERE id_member=?';

        if (!empty($datecreated)) {
            if ($equal) {
                $sql .= ' AND DATE(datecreated) = "' . date('Y-m-d', strtotime($datecreated)) . '"';
            } else {
                $sql .= ' AND DATE(datecreated) <= "' . date('Y-m-d', strtotime($datecreated)) . '"';
            }
        }

        $qry = $this->db->query($sql, array($id_member));
        if (!$qry || !$qry->num_rows()) return 0;

        if ($count_total) {
            $qualified = $qry->row()->total_qualified;
            return $qualified;
        }

        $row = $qry->row();
        return $row;
    }

    /**
     * Retrieve Total Member Omzet
     *
     * @author  Yuda
     * @param   String  $conditions         Condition of query          default ''
     * @return  Object  Result of data total
     */
    function get_total_member_omzet($conditions = '')
    {
        $sql    = '
        SELECT SQL_CALC_FOUND_ROWS 
            IFNULL( COUNT(id), 0) AS total_trx,
            IFNULL( SUM(`omzet`), 0 ) AS total_omzet,
            IFNULL( SUM(`amount`), 0 ) AS total_amount,
            IFNULL( SUM(`qty`), 0 ) AS total_qty,
            IFNULL( SUM(`point`), 0 ) AS total_point
        FROM `' . $this->member_omzet . '` WHERE id > 0 ';

        if (!empty($conditions)) {
            $sql .= $conditions;
        }

        $query  = $this->db->query($sql);

        if (!$query || !$query->num_rows())
            return false;

        return $query->row();
    }

    /**
     * Retrieve Total Member Omzet Group
     *
     * @author  Yuda
     * @param   String  $conditions         Condition of query          default ''
     * @return  Object  Result of data total
     */
    function get_total_member_omzet_group($conditions = '', $cond_group = '', $param_group = '', $person = FALSE)
    {
        $cfg_min_order = config_item('min_order_sa');
        $sql    = 'SELECT SQL_CALC_FOUND_ROWS 
                        IFNULL(COUNT(*), 0) AS total_trx,
                        IFNULL( SUM(G.`omzet`), 0 ) AS total_omzet,
                        IFNULL( SUM(G.`bv`), 0 ) AS total_bv,
                        IFNULL( SUM(G.`amount`), 0 ) AS total_amount,
                        IFNULL( SUM(G.`qty`), 0 ) AS total_qty,
                        IFNULL( SUM(G.`point`), 0 ) AS total_point,
                        GetLineActive(G.id_member, ' . $cfg_min_order . ', DATE_FORMAT(G.date, "%Y-%m")) AS active_rank
                    FROM ' . $this->member_omzet . ' AS G
                    INNER JOIN ' . $this->member . ' AS M ON (M.id = G.id_member)
                    WHERE G.id > 0 ';

        if (!empty($conditions)) {
            $sql .= $conditions;
        }

        if ($person) {
            $sql .= ' GROUP BY G.id_member';
        }

        if ($cond_group) {
            $sql .= $cond_group;
        }

        if ($param_group && is_array($param_group) && (count($param_group) > 0)) {
            $query  = $this->db->query($sql, $param_group);
        } else {
            $query  = $this->db->query($sql);
        }


        if (!$query || !$query->num_rows())
            return false;

        if ($person) {
            return $query->result();
        } else {
            return $query->row();
        }
    }


    /**
     * Get Grade by Field
     *
     * @author  Yuda
     * @param   String  $field  (Required)  Database field name or special field name defined inside this function
     * @param   String  $value  (Optional)  Value of the field being searched
     * @return  Mixed   Boolean false on failed process, invalid data, or data is not found, otherwise StdClass of member grade
     */
    function get_grade_by($field = '', $value = '', $condition = array(), $limit = 0)
    {
        if (!$field || !$value) return false;

        $this->db->where($field, $value);

        if ($condition && is_array($condition)) {
            $this->db->where($condition);
        }

        $query = $this->db->get($this->grade);
        if (!$query->num_rows()) {
            return false;
        }

        $data   = $query->result();
        if ($field == 'id' || $limit == 1) {
            foreach ($data as $row) {
                $datarow = $row;
            }
            $data = $datarow;
        }

        return $data;
    }

    /**
     * Retrieve all omzet monthly member data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @param   String  $total_conditions   Total Condition of query    default ''
     * @return  Decimal Result of Data List
     */
    function get_all_omzet_monthly_member($limit = 0, $offset = 0, $conditions = '', $order_by = '', $total_conditions = '', $params = '')
    {
        if (!empty($conditions)) {
            $conditions = str_replace("%id%",               "M.id", $conditions);
            $conditions = str_replace("%username%",         "M.username", $conditions);
            $conditions = str_replace("%name%",             "M.name", $conditions);
            $conditions = str_replace("%package%",          "M.package", $conditions);
            $conditions = str_replace("%rank%",             "M.rank", $conditions);
            $conditions = str_replace("%status%",           "A.status", $conditions);
            $conditions = str_replace("%month_omzet%",      'DATE_FORMAT(A.date, "%Y-%m")', $conditions);
            $conditions = str_replace("%date_register%",    "M.datecreated", $conditions);
        }

        if (!empty($order_by)) {
            $order_by   = str_replace("%id%",               "M.id", $order_by);
            $order_by   = str_replace("%username%",         "M.username", $order_by);
            $order_by   = str_replace("%name%",             "M.name", $order_by);
            $order_by   = str_replace("%package%",          "M.package", $order_by);
            $order_by   = str_replace("%total_pv%",         "total_pv", $order_by);
        }

        if ($total_conditions) {
            $total_conditions = str_replace("%total_omzet%",    "SUM(A.omzet)", $total_conditions);
            $total_conditions = str_replace("%total_amount%",   "SUM(A.amount)", $total_conditions);
            $total_conditions = str_replace("%total_pv%",       "SUM(A.pv)", $total_conditions);
            $total_conditions = str_replace("%total_unit%",     "SUM(A.unit)", $total_conditions);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS
                    DATE_FORMAT(A.date, "%Y-%m") AS month_omzet,
                    M.id,
                    A.id_member,
                    M.username,
                    M.name,
                    M.package,
                    M.rank,
                    M.tree,
                    M.datecreated as date_register,
                    IFNULL(SUM(A.omzet),0) AS total_omzet,
                    IFNULL(SUM(A.amount),0) AS total_amount,
                    IFNULL(SUM(A.qty),0) AS total_qty,
                    IFNULL(SUM(A.bv),0) AS total_bv
                FROM ' . $this->member_omzet . ' AS A
                INNER JOIN ' . $this->member . ' AS M ON (M.id = A.id_member)
                WHERE M.type = ' . MEMBER . ' AND M.status = ' . ACTIVE . ' ' . $conditions . '
                GROUP BY 1, 2';

        if ($total_conditions) {
            $sql .= ' HAVING ' . ltrim($total_conditions, ' AND');
        } else {
            if (empty(trim($conditions))) {
                $sql .= ' HAVING SUM(A.omzet) >= 0 ';
            }
        }

        $sql   .= ' ORDER BY ' . (!empty($order_by) ? $order_by : ' total_bv DESC, M.username ASC');
        if ($limit) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        if ($params && is_array($params)) {
            $query = $this->db->query($sql, $params);
        } else {
            $query = $this->db->query($sql);
        }


        if (!$query || !$query->num_rows()) return false;
        return $query->result();
    }

    /**
     * Retrieve member Reward data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of data               default 0
     * @param   Int     $offset             Offset ot data              default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of Data List
     */
    function get_all_member_grade($limit = 0, $offset = 0, $conditions = '', $order_by = '', $params_input = '')
    {
        if (!empty($conditions)) {
            $conditions = str_replace("%id%",               "A.id", $conditions);
            $conditions = str_replace("%id_member%",        "A.id_member", $conditions);
            $conditions = str_replace("%username%",         "M.username", $conditions);
            $conditions = str_replace("%name%",             "M.name", $conditions);
            $conditions = str_replace("%tree%",             "M.tree", $conditions);
            $conditions = str_replace("%rank%",             "A.rank", $conditions);
            $conditions = str_replace("%status%",           "M.status", $conditions);
            $conditions = str_replace("%year%",             "A.year", $conditions);
            $conditions = str_replace("%month%",            "A.month", $conditions);
            $conditions = str_replace("%package%",          "A.package", $conditions);
            $conditions = str_replace("%package_qualified%", "A.package_qualified", $conditions);
            $conditions = str_replace("%total_omzet%",      "A.total_omzet", $conditions);
            $conditions = str_replace("%pv_bonus%",         "A.pv_bonus", $conditions);
            $conditions = str_replace("%pv_bonus_group%",   "A.pv_bonus_group", $conditions);
            $conditions = str_replace("%total_pv%",         "A.total_pv", $conditions);
            $conditions = str_replace("%total_pv_group%",   "A.total_pv_group", $conditions);
            $conditions = str_replace("%total_unit%",       "A.total_unit", $conditions);
            $conditions = str_replace("%group_active%",     "A.group_active", $conditions);
            $conditions = str_replace("%datecreated%",      "DATE(A.datecreated)", $conditions);
            $conditions = str_replace("%datemodified%",     "DATE(A.datemodified)", $conditions);
        }

        if (!empty($order_by)) {
            $order_by = str_replace("%username%",           "M.username", $order_by);
            $order_by = str_replace("%name%",               "M.name", $order_by);
            $order_by = str_replace("%status%",             "M.status", $order_by);
            $order_by = str_replace("%year%",               "A.year", $order_by);
            $order_by = str_replace("%month%",              "A.month", $order_by);
            $order_by = str_replace("%package%",            "A.package", $order_by);
            $order_by = str_replace("%package_qualified%",  "A.package_qualified", $order_by);
            $order_by = str_replace("%total_omzet%",        "A.total_omzet", $order_by);
            $order_by = str_replace("%pv_bonus%",           "A.total_pv", $order_by);
            $order_by = str_replace("%pv_bonus_group%",     "A.pv_bonus_group", $order_by);
            $order_by = str_replace("%total_pv%",           "A.total_pv", $order_by);
            $order_by = str_replace("%total_pv_group%",     "A.total_pv_group", $order_by);
            $order_by = str_replace("%total_unit%",         "A.total_unit", $order_by);
            $order_by = str_replace("%group_active%",       "A.group_active", $order_by);
            $order_by = str_replace("%datecreated%",        "A.datecreated", $order_by);
            $order_by = str_replace("%datemodified%",       "A.datemodified", $order_by);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS
                    A.*,
                    M.name,
                    M.username,
                    M.level,
                    M.tree,
                    M.dateupgrade,
                    M.datecreated as member_join
                FROM ' . $this->grade . ' AS A
                INNER JOIN ' . $this->member . ' AS M ON (M.id = A.id_member) 
                WHERE M.type = ? ';

        if (!empty($conditions)) {
            $sql .= $conditions;
        }

        $sql   .= ' ORDER BY ' . (!empty($order_by) ? $order_by : ' A.year DESC, A.month DESC, A.id_member ASC');

        if ($limit) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = null;
        $params = array(MEMBER);

        if (is_array($params_input) && count($params_input)) {
            $params = array_merge($params, $params_input);
        }


        $query = $this->db->query($sql, $params);

        if (!$query || !$query->num_rows()) return false;

        return $query->result();
    }


    /**
     * Save data of member
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of member
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data($data)
    {
        if (empty($data)) return false;
        if ($id = $this->insert($data)) {
            return $id;
        };
        return false;
    }

    /**
     * Save data of member omzet
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of member
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_member_grade($data)
    {
        if (empty($data)) return false;

        if ($this->db->insert($this->grade, $data)) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }

    /**
     * Save data of member
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of member
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_confirm($data)
    {
        if (empty($data)) return false;

        if ($this->db->insert($this->member_confirm, $data)) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }

    /**
     * Save data of member omzet
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of member
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_member_omzet($data)
    {
        if (empty($data)) return false;

        if ($this->db->insert($this->member_omzet, $data)) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }

    /**
     * Save data of point share
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of point share
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_point_share($data)
    {
        if (empty($data)) return false;

        if ($this->db->insert($this->point_share, $data)) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }

    /**
     * Save data upgrade of member
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of upgrade
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_upgrade($data)
    {
        if (empty($data)) return false;

        if ($this->db->insert($this->upgrade, $data)) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }

    /**
     * Update data of member
     *
     * @author  Yuda
     * @param   Int     $id     (Required)  Member ID
     * @param   Array   $data   (Required)  Array data of user
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_data($id, $data)
    {
        if (empty($id) || empty($data)) return false;
        if ($this->update($id, $data))
            return true;

        return false;
    }

    function update_data_member_grade($id, $data)
    {
        if (empty($id) || empty($data)) return false;

        $this->db->where($this->primary, $id);
        if ($this->db->update($this->grade, $data))
            return $id;

        return false;
    }

    function update_data_member($id, $data)
    {
        if (empty($id) || empty($data)) return false;

        $this->db->where($this->primary, $id);
        if ($this->db->update($this->member, $data))
            return true;

        return false;
    }

    function update_data_member_confirm($id, $data)
    {
        if (empty($id) || empty($data)) return false;

        $this->db->where($this->primary, $id);
        if ($this->db->update($this->member_confirm, $data))
            return true;

        return false;
    }

    function update_data_member_omzet($id, $data)
    {
        if (empty($id) || empty($data)) return false;

        $this->db->where($this->primary, $id);
        if ($this->db->update($this->member_omzet, $data))
            return $id;

        return false;
    }

    function update_data_reward($id, $data)
    {
        if (empty($id) || empty($data)) return false;

        $this->db->where($this->primary, $id);
        if ($this->db->update($this->reward, $data))
            return $id;

        return false;
    }

    function update_data_point_share($id, $data)
    {
        if (empty($id) || empty($data)) return false;

        $this->db->where($this->primary, $id);
        if ($this->db->update($this->point_share, $data))
            return $id;

        return false;
    }

    function update_data_member_grade_by_condition($condition = '',  $data)
    {
        if (empty($condition) || !is_array($condition)) return false;
        if (empty($data)) return false;

        $this->db->where($condition);
        if ($this->db->update($this->grade, $data))
            return true;

        return false;
    }

    /**
     * Delete data of member
     *
     * @author  Yuda
     * @param   Int     $id   (Required)  ID of member
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function delete_data($id)
    {
        if (empty($id)) return false;
        if ($this->delete($id)) {
            return true;
        };
        return false;
    }



    // ---------------------------------------------------------------------------------
}
/* End of file Model_Member.php */
/* Location: ./application/models/Model_Member.php */