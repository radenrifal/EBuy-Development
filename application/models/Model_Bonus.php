<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('DDM_Model.php');

class Model_Bonus extends DDM_Model{
	/**
	 * For DDM_Model
	 */
    public $_table          = 'bonus';

    /**
     * Initialize table
     */
    var $member             = TBL_PREFIX . "member";
    var $bank               = TBL_PREFIX . "banks";
    var $bonus              = TBL_PREFIX . "bonus";
    var $shop_order         = TBL_PREFIX . "shop_order";
    var $ewallet            = TBL_PREFIX . "ewallet";
    var $ewallet_topup      = TBL_PREFIX . "ewallet_topup";
    var $withdraw           = TBL_PREFIX . "withdraw";

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

    // -----------------------------------------------------------------------------------------------

    /**
     * Retrieve all member bonus data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of member bonus list
     */
    function get_all_member_bonus($limit=0, $offset=0, $conditions='', $order_by=''){
        if( !empty($conditions) ){
            $conditions = str_replace("%id%",               "A.id", $conditions);
            $conditions = str_replace("%type%",             "A.type", $conditions);
            $conditions = str_replace("%username%",         "A.username", $conditions);
            $conditions = str_replace("%name%",             "A.name", $conditions);
            $conditions = str_replace("%total%",            "B.total", $conditions);
            $conditions = str_replace("%desc%",             "B.`desc`", $conditions);
            $conditions = str_replace("%datecreated%",      "A.datecreated", $conditions);
            $conditions = str_replace("%datecreated_bonus%","A.datecreated_bonus", $conditions);
        }

        if( !empty($order_by) ){
            $order_by   = str_replace("%username%",         "A.username",  $order_by);
            $order_by   = str_replace("%name%",             "A.name",  $order_by);
            $order_by   = str_replace("%datecreated%",      "A.datecreated",  $order_by);
            $order_by   = str_replace("%total%",            "B.total",  $order_by);
            $order_by   = str_replace("%datecreated_bonus%","B.datecreated_bonus",  $order_by);
        }

        $sql = '
            SELECT SQL_CALC_FOUND_ROWS
                A.*,
                B.total,
                B.datecreated_bonus,
                B.desc
            FROM ' . $this->member . ' AS A
            LEFT JOIN (
                SELECT
                    id_member,
                    SUM(amount) AS total,
                    datecreated AS datecreated_bonus,
                    `desc`
                FROM ' . $this->bonus . '
                GROUP BY id_member
            ) AS B ON B.id_member = A.id ';

        if( !empty($conditions) )   { $sql .= $conditions; }

        $sql   .= ' ORDER BY '. ( !empty($order_by) ? $order_by : 'B.total DESC');

        if( $limit ) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql);
        if(!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Retrieve All bonus data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of member bonus list
     */
    function get_all_history_bonus($limit=0, $offset=0, $conditions='', $order_by=''){
        if( !empty($conditions) ){
            $conditions = str_replace("%username%",         "M.username", $conditions);
            $conditions = str_replace("%name%",             "M.name", $conditions);
            $conditions = str_replace("%nominal%",          "B.amount", $conditions);
            $conditions = str_replace("%desc%",             "B.`desc`", $conditions);
            $conditions = str_replace("%status%",           "B.status", $conditions);
            $conditions = str_replace("%type%",             "B.type", $conditions);
            $conditions = str_replace("%datecreated%",      "DATE(B.datecreated)", $conditions);
        }

        if( !empty($order_by) ){
            $order_by   = str_replace("%username%",         "M.username", $order_by);
            $order_by   = str_replace("%name%",             "M.name", $order_by);
            $order_by   = str_replace("%nominal%",          "B.amount", $order_by);
            $order_by   = str_replace("%status%",           "B.status", $order_by);
            $order_by   = str_replace("%type%",             "B.type", $order_by);
            $order_by   = str_replace("%desc%",             "B.`desc`", $order_by);
            $order_by   = str_replace("%datecreated%",      "B.datecreated", $order_by);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS B.*, M.username, M.name 
                FROM ' . $this->bonus . ' B
                JOIN ' . $this->member . ' M ON (M.id = B.id_member)
                WHERE M.type = ? ';

        if( !empty($conditions) )   { $sql .= $conditions; }

        $sql   .= ' ORDER BY '. ( !empty($order_by) ? $order_by : 'B.datecreated DESC');

        if( $limit ) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql, array(MEMBER));
        if(!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Retrieve my bonus data
     *
     * @author  Yuda
     * @param   Integer $id_member          Member ID
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of member bonus list
     */
    function get_all_my_bonus($id_member, $limit=0, $offset=0, $conditions='', $order_by=''){
        if ( !is_numeric($id_member) ) return false;

        $id_member = absint($id_member);
        if ( !$id_member ) return false;

        if( !empty($conditions) ){
            $conditions = str_replace("%nominal%",          "amount", $conditions);
            $conditions = str_replace("%amount%",           "amount", $conditions);
            $conditions = str_replace("%status%",           "status", $conditions);
            $conditions = str_replace("%type%",             "type", $conditions);
            $conditions = str_replace("%desc%",             "`desc`", $conditions);
            $conditions = str_replace("%datecreated%",      "datecreated", $conditions);
        }

        if( !empty($order_by) ){
            $order_by   = str_replace("%nominal%",          "amount",  $order_by);
            $order_by   = str_replace("%amount%",           "amount",  $order_by);
            $order_by   = str_replace("%status%",           "status",  $order_by);
            $order_by   = str_replace("%type%",             "type",  $order_by);
            $order_by   = str_replace("%desc%",             "`desc`", $order_by);
            $order_by   = str_replace("%datecreated%",      "datecreated",  $order_by);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS * FROM ' . $this->bonus . ' WHERE id_member = ' . $id_member . '';

        if( !empty($conditions) )   { $sql .= $conditions; }

        $sql   .= ' ORDER BY '. ( !empty($order_by) ? $order_by : 'datecreated DESC');

        if( $limit ) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql);
        if(!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Retrieve member wallet cash total
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @param   String  $total_conditions   Total Condition of query    default ''
     * @return  Decimal Result of member Deposite total
     */
    function get_all_member_deposite($limit=0, $offset=0, $conditions='', $order_by='', $total_conditions = '', $date = ''){
        if ( ! empty( $conditions ) ){
            $conditions = str_replace("%id%",               "M.id", $conditions);
            $conditions = str_replace("%username%",         "M.username", $conditions);
            $conditions = str_replace("%name%",             "M.name", $conditions);
            $conditions = str_replace("%type%",             "M.type", $conditions);
            $conditions = str_replace("%status%",           "M.status", $conditions);
        }

        if( !empty($order_by) ){
            $order_by   = str_replace("%id%",               "M.id",  $order_by);
            $order_by   = str_replace("%username%",         "M.username",  $order_by);
            $order_by   = str_replace("%name%",             "M.name",  $order_by);
            $order_by   = str_replace("%total%",            "total_deposite",  $order_by);
        }

        $total_sql = 'SUM( IFNULL( C.total_bonus, 0 ) ) - SUM( IFNULL( C.total_withdraw, 0 ) ) - SUM( IFNULL( C.total_order, 0 ) )';

        if ( $total_conditions ) {
            $total_conditions = str_replace("%total%",      $total_sql, $total_conditions);
        }

        $date_wd  = $date ? date('Y-m-d', strtotime('1 day', strtotime($date))) : '';

        $sql = 'SELECT SQL_CALC_FOUND_ROWS
                    M.id,
                    M.username,
                    M.name,
                    M.npwp,
                    M.bank,
                    M.bill,
                    M.bill_name,
                    IFNULL(SUM(C.total_bonus),0) AS total_bonus,
                    IFNULL(SUM(C.total_withdraw),0) AS total_withdraw,
                    IFNULL(SUM(C.total_order),0) AS total_order,
                    ' . $total_sql . ' AS total_deposite
                FROM (
                    SELECT 
                        A.id_member,
                        A.amount AS total_bonus,
                        0 AS total_withdraw,
                        0 AS total_order
                    FROM '.$this->bonus.' AS A
                    '. ($date ? ' WHERE DATE(A.datecreated) <= "'.$date.'" ' : '' ) .'
                    UNION ALL
                    SELECT 
                        B.id_member,
                        0 AS total_bonus,
                        B.nominal AS total_withdraw,
                        0 AS total_order
                    FROM '.$this->withdraw.' AS B
                    '. ($date ? ' WHERE DATE(B.datecreated) <= "'.$date_wd.'" ' : '' ) .'
                ) AS C
                INNER JOIN '.$this->member.' AS M ON (M.id = C.id_member)
                WHERE M.type = '.MEMBER.' AND M.wd_status = 0 ' . $conditions . '
                GROUP BY 1';

        if ( $total_conditions ) {
            $sql .= ' HAVING ' . ltrim( $total_conditions, ' AND' );
        }else{
            $sql .= ' HAVING ' . $total_sql . ' > 0';
        }

        $sql   .= ' ORDER BY '. ( !empty($order_by) ? $order_by : ' total_deposite DESC, M.id ASC');
        if( $limit ) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql);
        if ( !$query || !$query->num_rows() ) return false;
        return $query->result();
    }

    /**
     * Retrieve member wallet total
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @param   String  $total_conditions   Total Condition of query    default ''
     * @return  Decimal Result of member Deposite total
     */
    function get_all_total_ewallet_member($limit=0, $offset=0, $conditions='', $order_by='', $total_conditions = '', $date = ''){
        if ( ! empty( $conditions ) ){
            $conditions = str_replace("%id%",               "M.id", $conditions);
            $conditions = str_replace("%username%",         "M.username", $conditions);
            $conditions = str_replace("%name%",             "M.name", $conditions);
            $conditions = str_replace("%type%",             "M.type", $conditions);
            $conditions = str_replace("%status%",           "M.status", $conditions);
            $conditions = str_replace("%wd_status%",        "M.wd_status", $conditions);
        }

        if( !empty($order_by) ){
            $order_by   = str_replace("%id%",               "M.id",  $order_by);
            $order_by   = str_replace("%username%",         "M.username",  $order_by);
            $order_by   = str_replace("%name%",             "M.name",  $order_by);
            $order_by   = str_replace("%total%",            "total_deposite",  $order_by);
        }

        $total_sql = 'SUM( IFNULL( C.total_in, 0 ) ) - SUM( IFNULL( C.total_out, 0 ) )';

        if ( $total_conditions ) {
            $total_conditions = str_replace("%total%",      $total_sql, $total_conditions);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS
                    M.id,
                    M.username,
                    M.name,
                    M.npwp,
                    M.bank,
                    M.bill,
                    M.bill_name,
                    IFNULL(SUM(C.total_in),0) AS total_in,
                    IFNULL(SUM(C.total_out),0) AS total_out,
                    ' . $total_sql . ' AS total_deposite
                FROM (
                    SELECT 
                        A.id_member,
                        A.amount AS total_in,
                        0 AS total_out
                    FROM '.$this->ewallet.' AS A
                    WHERE A.type = "IN"
                    '. ($date ? ' AND DATE(A.datecreated) <= "'.$date.'" ' : '' ) .'
                    UNION ALL
                    SELECT 
                        B.id_member,
                        0 AS total_in,
                        B.amount AS total_out
                    FROM '.$this->ewallet.' AS B
                    WHERE B.type = "OUT"
                    '. ($date ? ' AND DATE(B.datecreated) <= "'.$date.'" ' : '' ) .'
                ) AS C
                INNER JOIN '.$this->member.' AS M ON (M.id = C.id_member)
                WHERE M.type = '.MEMBER.' AND M.status = '.ACTIVE.' ' . $conditions . '
                GROUP BY 1';

        if ( $total_conditions ) {
            $sql .= ' HAVING ' . ltrim( $total_conditions, ' AND' );
        }else{
            if ( empty( trim($conditions) ) ) {
                $sql .= ' HAVING ' . $total_sql . ' >= 0';
            }
        }

        $sql   .= ' ORDER BY '. ( !empty($order_by) ? $order_by : ' total_deposite DESC, M.id ASC');
        if( $limit ) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql);
        if ( !$query || !$query->num_rows() ) return false;
        return $query->result();
    }

    /**
     * Retrieve member wallet
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Decimal Result of member Deposite
     */
    function get_all_ewallet_member($limit=0, $offset=0, $conditions='', $order_by=''){
        if ( ! empty( $conditions ) ){
            $conditions = str_replace("%id%",               "A.id", $conditions);
            $conditions = str_replace("%id_member%",        "A.id_member", $conditions);
            $conditions = str_replace("%username%",         "B.username", $conditions);
            $conditions = str_replace("%name%",             "B.name", $conditions);
            $conditions = str_replace("%id_source%",        "A.id_source", $conditions);
            $conditions = str_replace("%source%",           "A.source", $conditions);
            $conditions = str_replace("%amount%",           "A.amount", $conditions);
            $conditions = str_replace("%type%",             "A.type", $conditions);
            $conditions = str_replace("%status%",           "A.status", $conditions);
            $conditions = str_replace("%description%",      "A.description", $conditions);
            $conditions = str_replace("%datecreated%",      "A.datecreated", $conditions);
        }

        if( !empty($order_by) ){
            $order_by   = str_replace("%id%",               "A.id",  $order_by);
            $order_by   = str_replace("%username%",         "B.username",  $order_by);
            $order_by   = str_replace("%name%",             "B.name",  $order_by);
            $order_by   = str_replace("%source%",           "A.source", $order_by);
            $order_by   = str_replace("%amount%",           "A.amount", $order_by);
            $order_by   = str_replace("%description%",      "A.description", $order_by);
            $order_by   = str_replace("%datecreated%",      "A.datecreated", $order_by);
        }       

        $sql = 'SELECT SQL_CALC_FOUND_ROWS A.*, B.username, B.name 
                FROM ' . $this->ewallet . ' A
                JOIN ' . $this->member . ' B ON (B.id = A.id_member)
                WHERE B.type = ? ';

        if( !empty($conditions) )   { $sql .= $conditions; }

        $sql   .= ' ORDER BY '. ( !empty($order_by) ? $order_by : ' A.datecreated DESC, B.username ASC');
        if( $limit ) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql, array(MEMBER));
        if ( !$query || !$query->num_rows() ) return false;
        return $query->result();
    }

    /**
     * Retrieve all member commission data
     *
     * @author  Yuda
     * @param   Int     $limit          Limit of member             default 0
     * @param   Int     $offset         Offset ot member            default 0
     * @param   String  $conditions     Condition of query          default ''
     * @param   String  $order_by       Column that make to order   default ''
     * @return  Object  Result of member commission list
     */
    function get_all_member_commission($limit = 0, $offset = 0, $conditions = '', $order_by = '', $total_condition = '')
    {
        if(!empty($conditions)) {
            $conditions = str_replace("%username%",     "B.username", $conditions);
            $conditions = str_replace("%name%",         "B.name", $conditions);
            $conditions = str_replace("%datecreated%",  "DATE(A.datecreated)", $conditions);
        }

        if(!empty($order_by)) {
            $order_by = str_replace("%username%",       "B.username", $order_by);
            $order_by = str_replace("%name%",           "B.name", $order_by);
            $order_by = str_replace("%total%",          "total", $order_by);
        }

        $total_sql = ' SUM( IFNULL( A.amount, 0 ) ) ';

        if ( $total_condition ) {
            $total_condition = str_replace("%total%",          $total_sql, $total_condition);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS B.id, B.username, B.name, '.$total_sql.' AS total
                FROM ' . $this->bonus . ' AS A
                INNER JOIN ' . $this->member . ' AS B ON (B.id = A.id_member)
                WHERE 1=1 ';

        if( !empty($conditions) )   { $sql .= $conditions; }

        $sql   .= ' GROUP BY 1 ';

        if ( $total_condition ) {
            $sql .= ' HAVING ' . ltrim( $total_condition, ' AND' );
        }

        $sql   .= ' ORDER BY '. ( !empty($order_by) ? $order_by : 'total DESC');

        if( $limit ) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql);
        if(!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Retrieve all member withdraw data
     *
     * @param   Int $limit Limit of member             default 0
     * @param   Int $offset Offset ot member            default 0
     * @param   String $conditions Condition of query          default ''
     * @param   String $order_by Column that make to order   default ''
     * @return  Object  Result of member withdraw list
     */
    function get_all_member_withdraw($limit = 0, $offset = 0, $conditions = '', $order_by = '')
    {
        if(!empty($conditions)) {
            $conditions = str_replace("%id%",               "A.id", $conditions);
            $conditions = str_replace("%id_member%",        "A.id_member", $conditions);
            $conditions = str_replace("%username%",         "B.username", $conditions);
            $conditions = str_replace("%name%",             "B.name", $conditions);
            $conditions = str_replace("%bank%",             "A.bank", $conditions);
            $conditions = str_replace("%bill%",             "A.bill", $conditions);
            $conditions = str_replace("%bill_name%",        "A.bill_name", $conditions);
            $conditions = str_replace("%nominal%",          "A.nominal", $conditions);
            $conditions = str_replace("%nominal_receipt%",  "A.nominal_receipt", $conditions);
            $conditions = str_replace("%admin_fund%",       "A.admin_fund", $conditions);
            $conditions = str_replace("%tax%",              "A.tax", $conditions);
            $conditions = str_replace("%status%",           "A.status", $conditions);
            $conditions = str_replace("%datecreated%",      "A.datecreated", $conditions);
            $conditions = str_replace("%datemodified%",     "A.datemodified", $conditions);
            $conditions = str_replace("%dateconfirm%",      "A.dateconfirm", $conditions);
        }

        if(!empty($order_by)) {
            $order_by = str_replace("%id%",                 "A.id", $order_by);
            $order_by = str_replace("%username%",           "B.username", $order_by);
            $order_by = str_replace("%name%",               "B.name", $order_by);
            $order_by = str_replace("%bank%",               "C.nama", $order_by);
            $order_by = str_replace("%bill%",               "A.bill", $order_by);
            $order_by = str_replace("%bill_name%",          "A.bill_name", $order_by);
            $order_by = str_replace("%nominal%",            "A.nominal", $order_by);
            $order_by = str_replace("%nominal_receipt%",    "A.nominal_receipt", $order_by);
            $order_by = str_replace("%admin_fund%",         "A.admin_fund", $order_by);
            $order_by = str_replace("%tax%",                "A.tax", $order_by);
            $order_by = str_replace("%status%",             "A.status", $order_by);
            $order_by = str_replace("%datecreated%",        "A.datecreated", $order_by);
            $order_by = str_replace("%datemodified%",       "A.datemodified", $order_by);
            $order_by = str_replace("%dateconfirm%",        "A.dateconfirm", $order_by);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS
                    A.*,
                    B.username, B.name, B.bank, B.bill, B.bill_name,
                    C.nama AS bank_name, C.kode AS bank_code
                FROM ' . $this->withdraw . ' AS A
                LEFT JOIN ' . $this->member . ' AS B ON (B.id = A.id_member)
                LEFT JOIN ' . $this->bank . ' C ON (C.id = A.bank) ';

        if(!empty($conditions)) {
            $sql .= $conditions;
        }

        $sql .= ' ORDER BY ' . (!empty($order_by) ? $order_by : 'A.datecreated DESC, B.username ASC');

        if($limit) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql);
        if(!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Retrieve all member withdraw monthly data
     *
     * @param   Int $limit Limit of member             default 0
     * @param   Int $offset Offset ot member            default 0
     * @param   String $conditions Condition of query          default ''
     * @param   String $order_by Column that make to order   default ''
     * @param   String $total_conditions Condition of query          default ''
     * @return  Object  Result of member withdraw list
     */
    function get_all_withdraw_monthly($limit = 0, $offset = 0, $conditions = '', $order_by = '', $total_cond = '')
    {
        if(!empty($conditions)) {
            $conditions = str_replace("%month%",        "A.month", $conditions);
        }

        if(!empty($total_cond)) {
            $total_cond = str_replace("%bonus%",        "SUM(A.total_bonus)", $total_cond);
            $total_cond = str_replace("%admin%",        "SUM(A.total_admin)", $total_cond);
            $total_cond = str_replace("%tax%",          "SUM(A.total_tax)", $total_cond);
            $total_cond = str_replace("%transfer%",     "SUM(A.total_transfer)", $total_cond);
            $total_cond = str_replace("%withdraw%",     "SUM(A.total_wd)", $total_cond);
        }

        if(!empty($order_by)) {
            $order_by   = str_replace("%month%",        "A.month", $order_by);
            $order_by   = str_replace("%bonus%",        "total_bonus", $order_by);
            $order_by   = str_replace("%admin%",        "total_admin", $order_by);
            $order_by   = str_replace("%tax%",          "total_tax", $order_by);
            $order_by   = str_replace("%transfer%",     "total_transfer", $order_by);
            $order_by   = str_replace("%withdraw%",     "total_wd", $order_by);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS
                    A.month,
                    IFNULL(SUM(A.total_bonus),0) AS total_bonus,
                    IFNULL(SUM(A.total_wd),0) AS total_wd,
                    IFNULL(SUM(A.total_transfer),0) AS total_transfer,
                    IFNULL(SUM(A.total_tax),0) AS total_tax,
                    IFNULL(SUM(A.total_admin),0) AS total_admin
                FROM (
                    SELECT 
                        DATE_FORMAT(B.datecreated,"%Y-%m") AS month,
                        B.amount AS total_bonus,
                        0 AS total_wd,
                        0 AS total_transfer,
                        0 AS total_tax,
                        0 AS total_admin
                    FROM ' . $this->bonus . ' B
                    UNION ALL
                    SELECT 
                        DATE_FORMAT(C.datecreated,"%Y-%m") AS month,
                        0 AS total_bonus,
                        C.nominal AS total_wd,
                        C.nominal_receipt AS total_transfer,
                        C.tax AS total_tax,
                        C.admin_fund AS total_admin
                    FROM ' . $this->withdraw . ' C
                ) AS A ';

        if(!empty($conditions)) { $sql .= $conditions; }

        $sql .= ' GROUP BY 1 ';

        if ( $total_cond ) { $sql .= ' HAVING ' . ltrim( $total_cond, ' AND' ); }

        $sql .= ' ORDER BY ' . (!empty($order_by) ? $order_by : 'A.month DESC');

        if($limit) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql);
        if(!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Get Total Bonus Member
     *
     * @author  Yuda
     * @param   Int     $id_member      ID of member
     * @return  Decimal Result of member Bonus total
     */
    function get_total_bonus_member($id_member, $type='all', $date=''){
        if ( !is_numeric($id_member) ) return 0;

        $id_member  = absint($id_member);
        if ( !$id_member ) return 0;

        $sql = "SELECT IFNULL(SUM(amount),0) total FROM ".$this->bonus." WHERE id_member = ? ";
        
        if ( !empty($type) && $type != 'all' ) {
            $sql .= ' AND type = ' . $type;
        }

        if ( ! empty( $date ) ) {
            if ( is_array( $date ) ) {
                $sql .= ' AND (DATE_FORMAT(datecreated, "%Y-%m-%d") BETWEEN "'. $date[0] .'" AND "'. $date[1] .'")';
            } else {
                $sql .= ' AND DATE_FORMAT(datecreated, "%Y-%m-%d") = "' . $date . '"';
            }
        }

        $query = $this->db->query( $sql, array($id_member) );
        if ( !$query || !$query->num_rows() ) return 0;
        return $query->row()->total;
    }

    /**
     * Get Total Bonus By Date
     *
     * @author  Yuda
     * @param   Int     $id_member      ID of member
     * @return  Decimal Result of member Bonus total
     */
    function get_total_bonus_type($type, $date='', $date_format='daily'){
        if ( !is_numeric($type) ) return 0;

        $type  = absint($type);
        if ( !$type ) return 0;

        $sql = "SELECT IFNULL(SUM(amount),0) total FROM ".$this->bonus." WHERE type = ? ";

        if ( ! empty( $date ) ) {
            if ( $date_format == 'daily' ) {
                $sql .= ' AND DATE(datecreated) = "' . $date . '"';
            }

            if ( $date_format == 'monthly' ) {
                $sql .= ' AND DATE_FORMAT(datecreated, "%Y-%m") = "' . $date . '"';
            }
        }

        $query = $this->db->query( $sql, array($type) );
        if ( !$query || !$query->num_rows() ) return 0;
        return $query->row()->total;
    }

    /**
     * Retrieve member withdraw total
     *
     * @author  Yuda
     * @param   Integer $member_id          Member ID
     * @return  Decimal  Result of member withdraw total
     */
    function get_ewallet_total($id_member, $type = '', $source = ''){
        if ( !is_numeric($id_member) ) return 0;

        $id_member = absint($id_member);
        if ( !$id_member ) return 0;

        $type   = $type ? strtoupper($type) : '';

        $sql    = 'SELECT IFNULL(SUM(amount),0) total FROM '.$this->ewallet.' WHERE id_member = ? ';
        if ( $type )    { $sql .= ' AND type = "' . $type . '" '; }
        if ( $source )  { $sql .= ' AND source = "' . $source . '" '; }

        $query  = $this->db->query($sql, array($id_member));
        if ( !$query || !$query->num_rows() ) return 0;
        return $query->row()->total;
    }

    /**
     * Retrieve Deposite Member
     *
     * @author  Yuda
     * @param   Integer $member_id          Member ID
     * @return  Decimal  Result of member Deposite Member
     */
    function get_ewallet_deposite($id_member){
        if ( !is_numeric($id_member) ) return 0;

        $id_member = absint($id_member);
        if ( !$id_member ) return 0;

        $sql    = 'SELECT 
                        id_member,
                        IFNULL(SUM(saldo_in),0) AS total_in,
                        IFNULL(SUM(saldo_out),0) AS total_out,
                        IFNULL(SUM(saldo_in),0) - IFNULL(SUM(saldo_out),0) AS total_deposite 
                    FROM ( 
                        SELECT
                            A.id_member,
                            A.amount AS saldo_in,
                            0 AS saldo_out
                        FROM '.$this->ewallet.' A
                        WHERE A.type = "IN"
                        UNION ALL
                        SELECT
                            B.id_member,
                            0 AS saldo_in,
                            B.amount AS saldo_out
                        FROM '.$this->ewallet.' B
                        WHERE B.type = "OUT"
                    ) DEP
                    WHERE DEP.id_member = ? 
                    GROUP BY 1 ';

        $query  = $this->db->query($sql, array($id_member));
        if ( !$query || !$query->num_rows() ) return 0;
        return $query->row()->total_deposite;
    }

    /**
     * Retrieve Total deposite bonus total
     *
     * @author  Yuda
     * @param   Integer $member_id          Member ID
     * @return  Object  Result of total desposite bonus
     */
    function get_total_deposite_bonus($id_member = 0) {
        $sql = 'SELECT SQL_CALC_FOUND_ROWS
                    IFNULL(SUM(DEP.total_bonus),0) AS total_bonus,
                    IFNULL(SUM(DEP.total_wd),0) AS total_wd,
                    IFNULL(SUM(DEP.total_wd_receipt),0) AS total_wd_receipt,
                    IFNULL(SUM(DEP.total_wd_transfer),0) AS total_wd_transfer,
                    IFNULL(SUM(DEP.total_wd_receipt_transfer),0) AS total_wd_receipt_transfer,
                    IFNULL(SUM(DEP.total_deposite),0) AS total_deposite
                FROM (
                    SELECT 
                        A.id_member, 
                        IFNULL(SUM(A.amount), 0) AS total_bonus,
                        IFNULL(B.nominal, 0) AS total_wd,
                        IFNULL(B.nominal_receipt, 0) AS total_wd_receipt,
                        IFNULL(C.nominal, 0) AS total_wd_transfer,
                        IFNULL(C.nominal_receipt, 0) AS total_wd_receipt_transfer,
                        ( IFNULL(SUM(A.amount), 0) - IFNULL(B.nominal, 0) ) AS total_deposite
                    FROM `'. $this->bonus .'` AS A
                    LEFT JOIN (
                        SELECT id_member, IFNULL(SUM(nominal), 0) AS nominal, IFNULL(SUM(nominal_receipt), 0) AS nominal_receipt 
                        FROM `'. $this->withdraw .'` 
                        GROUP BY id_member
                    ) AS B ON (B.id_member = A.id_member)
                    LEFT JOIN (
                        SELECT id_member, IFNULL(SUM(nominal), 0) AS nominal, IFNULL(SUM(nominal_receipt), 0) AS nominal_receipt 
                        FROM `'. $this->withdraw .'` 
                        WHERE status = 1 
                        GROUP BY id_member
                    ) AS C ON (C.id_member = A.id_member)
                    GROUP BY A.id_member
                ) AS DEP ';

        if ( $id_member) {
            $sql .= ' WHERE DEP.id_member = ' . $id_member . ' ';
        }
        
        $query  = $this->db->query($sql);

        if ( !$query || !$query->num_rows() )
            return false;

        return $query->row();
    }

    /**
     * Get withdraw by id
     */
    function get_withdraw_by_id($id){
        if ( !is_numeric($id) ) return false;
        $id  = absint($id);
        if ( !$id ) return false;

        $sql    = 'SELECT * FROM ' . $this->withdraw . ' WHERE id=?';
        $query  = $this->db->query($sql, array($id));
        if(!$query || !$query->num_rows()) return false;
        return $query->row();
    }

    /**
     * Save data of bonus
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of bonus
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_bonus($data){
        if( empty($data) ) return false;
        if( $this->db->insert($this->bonus, $data) ) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }

    /**
     * Save data of ewallet
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of ewallet
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_ewallet($data){
        if( empty($data) ) return false;
        if( $this->db->insert($this->ewallet, $data) ) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }

    /**
     * Save data withdraw of member
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of upgrade
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_withdraw($data){
        if( empty($data) ) return false;

        if( $this->db->insert($this->withdraw, $data) ) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }

    /**
     * Update data of withdraw
     *
     * @author  Yuda
     * @param   Int $id (Required)  Withdraw ID
     * @param   Array $data (Required)  Array data of withdraw
     * @param   Array $condition (Optional)  Array data of withdraw condition
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_data_withdraw($id, $data, $condition = array()){
        if(empty($id) || empty($data)){ return false; }

        $this->db->where($this->primary, $id);
        if(!empty($condition)) {
            $this->db->where($condition);
        }
        if($this->db->update($this->withdraw, $data)){
            return true;
        }
        return false;
    }

    // -----------------------------------------------------------------------------------------------
}
/* End of file Model_Bonus.php */
/* Location: ./application/models/Model_Bonus.php */
