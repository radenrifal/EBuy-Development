<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('DDM_Model.php');

class Model_Omzet_History extends DDM_Model
{
    /**
     * For AN_Model
     */
    public $_table              = 'member_omzet_history';

    /**
     * Initialize table
     */
    var $omzet                  = TBL_PREFIX."member_omzet";
    var $omzet_history          = TBL_PREFIX."member_omzet_history";
    var $member                 = TBL_PREFIX."member";
    var $province               = TBL_PREFIX."province";

    /**
     * Initialize primary field
     */
    var $primary                = "id";

    /**
     * Constructor - Sets up the object properties.
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Retrieve Product Active Member
     *
     * @author  Iqbal
     * @param   Integer $member_id          Member ID
     * @return  Decimal  Result of member Product Active Member
     */
    function get_product_active($id_member){
        if ( !is_numeric($id_member) ) return 0;

        $id_member = absint($id_member);
        if ( !$id_member ) return 0;

        $sql    = '
        SELECT 
            id_member,
            IFNULL(SUM(qty_in),0) AS qty_in,
            IFNULL(SUM(qty_out),0) AS qty_out,
            IFNULL(SUM(qty_in),0) - IFNULL(SUM(qty_out),0) AS total_qty 
        FROM ( 
            SELECT
                A.id_member,
                A.qty AS qty_in,
                0 AS qty_out
            FROM '.$this->omzet_history.' A
            WHERE A.type = "IN"
            UNION ALL
            SELECT
                B.id_member,
                0 AS qty_in,
                B.qty AS qty_out
            FROM '.$this->omzet_history.' B
            WHERE B.type = "OUT"
        ) PA
        WHERE PA.id_member = ? 
        GROUP BY 1 ';

        $query  = $this->db->query($sql, array($id_member));
        if ( !$query || !$query->num_rows() ) return 0;
        
        //echo "<pre>";
        //print_r($query->row());
        //echo "</pre>";
        
        return $query->row()->total_qty;
    }
    
    /**
     * Retrieve Product Active Total
     *
     * @author  Iqbal
     * @return  Decimal  Result of Product Active Total
     */
    function get_product_active_total(){
        $sql    = '
        SELECT 
            IFNULL(SUM(qty_in),0) AS qty_in,
            IFNULL(SUM(qty_out),0) AS qty_out,
            IFNULL(SUM(qty_in),0) - IFNULL(SUM(qty_out),0) AS total_qty 
        FROM ( 
            SELECT
                A.qty AS qty_in,
                0 AS qty_out
            FROM '.$this->omzet_history.' A
            WHERE A.type = "IN"
            UNION ALL
            SELECT
                0 AS qty_in,
                B.qty AS qty_out
            FROM '.$this->omzet_history.' B
            WHERE B.type = "OUT"
        ) PA ';

        $query  = $this->db->query($sql);
        if ( !$query || !$query->num_rows() ) return 0;
        return $query->row()->total_qty;
    }

    /**
     * Retrieve Product Active All Member
     *
     * @author  Iqbal
     * @param   Integer $member_id          Member ID
     * @return  Decimal  Result of member Product Active Member
     */
    function get_product_active_all( $conditions = '' ){
        if( !empty($conditions) ){
            $conditions = str_replace("%username%",         "M.username", $conditions);
            $conditions = str_replace("%name%",             "M.name", $conditions);
            $conditions = str_replace("%id_member%",        "PA.id_member", $conditions);
            $conditions = str_replace("%province_area%",    "P.province_area", $conditions);
            $conditions = str_replace("%in%",               "IFNULL(SUM(PA.qty_in),0)", $conditions);
            $conditions = str_replace("%out%",              "IFNULL(SUM(PA.qty_out),0)", $conditions);
        }
        
        $sql    = '
        SELECT 
            PA.id_member,
            M.username,
            M.name,
            P.province_area,
            IFNULL(SUM(PA.qty_in),0) AS qty_in,
            IFNULL(SUM(PA.qty_out),0) AS qty_out,
            IFNULL(SUM(PA.qty_in),0) - IFNULL(SUM(PA.qty_out),0) AS total_qty 
        FROM ( 
            SELECT
                A.id_member,
                A.qty AS qty_in,
                0 AS qty_out
            FROM '.$this->omzet_history.' A
            WHERE A.type = "IN"
            UNION ALL
            SELECT
                B.id_member,
                0 AS qty_in,
                B.qty AS qty_out
            FROM '.$this->omzet_history.' B
            WHERE B.type = "OUT"
        ) AS PA 
        LEFT JOIN '.$this->member.' AS M ON M.id = PA.id_member
        LEFT JOIN '.$this->province.' AS P ON P.id = M.province
        WHERE PA.id_member != 1 '.$conditions.'
        GROUP BY 1 
        HAVING IFNULL(SUM(PA.qty_in),0) - IFNULL(SUM(PA.qty_out),0) > 0
        ORDER BY 6 DESC';

        $query  = $this->db->query($sql);
        
        if( !$query || $query->num_rows() == 0 ) return false;
        
        return $query->result();
    }
    
    /**
     * Retrieve All Product History
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @param   String  $balance_conditions Balance Condition of query  default ''
     * @return  Object  Result of member product list
     */
    function get_all_history_product($limit=0, $offset=0, $conditions='', $order_by='', $balance_conditions = ''){
        $balance_query  = "IFNULL(SUM(PH.qty_in),0) - IFNULL(SUM(PH.qty_out),0)";
        
        if( !empty($conditions) ){
            $conditions = str_replace("%username%",         "M.username", $conditions);
            $conditions = str_replace("%id_member%",        "PH.id_member", $conditions);
            $conditions = str_replace("%in%",               "IFNULL(SUM(PH.qty_in),0)", $conditions);
            $conditions = str_replace("%out%",              "IFNULL(SUM(PH.qty_out),0)", $conditions);
        }

        if( !empty($order_by) ){
            $order_by   = str_replace("%username%",         "M.username", $order_by);
            $order_by   = str_replace("%id_member%",        "PH.id_member", $order_by);
            $order_by   = str_replace("%in%",               3, $order_by);
            $order_by   = str_replace("%out%",              4, $order_by);
            $order_by   = str_replace("%balance%",          5, $order_by);
        }
        
        $balance_sql    = 'IFNULL(SUM(PH.qty_in),0) - IFNULL(SUM(PH.qty_out),0)';
        if ( $balance_conditions ) {
            $balance_conditions = str_replace("%balance%",  $balance_sql, $balance_conditions);
        }

        $sql    = '
        SELECT 
            PH.id_member,
            M.username,
            IFNULL(SUM(PH.qty_in),0) AS qty_in,
            IFNULL(SUM(PH.qty_out),0) AS qty_out,
            IFNULL(SUM(PH.qty_in),0) - IFNULL(SUM(PH.qty_out),0) AS qty_balance
        FROM ( 
            SELECT
                A.id_member,
                A.qty AS qty_in,
                0 AS qty_out
            FROM '.$this->omzet_history.' A
            WHERE A.type = "IN"
                UNION ALL
            SELECT
                B.id_member,
                0 AS qty_in,
                B.qty AS qty_out
            FROM '.$this->omzet_history.' B
            WHERE B.type = "OUT"
        ) AS PH
        LEFT JOIN '.$this->member.' AS M ON M.id = PH.id_member
        WHERE PH.id_member != 1 '.$conditions.'
        GROUP BY 1';
        
        if ( $balance_conditions ) {
            $sql .= ' HAVING ' . ltrim( $balance_conditions, ' AND' );
        }else{
            $sql .= ' HAVING ' . $balance_query . ' > 0';
        }

        $sql   .= ' ORDER BY '. ( !empty($order_by) ? $order_by : '5 DESC');

        if( $limit ) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql);
        if(!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Retrieve All Product History Detail
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of member product list
     */
    function get_all_history_product_detail($limit=0, $offset=0, $conditions='', $order_by=''){
        if( !empty($conditions) ){
            $conditions = str_replace("%username%",         "M.username", $conditions);
            $conditions = str_replace("%name%",             "M.name", $conditions);
            $conditions = str_replace("%id_member%",        "P.id_member", $conditions);
            $conditions = str_replace("%nominal%",          "P.amount", $conditions);
            $conditions = str_replace("%qty%",              "P.qty", $conditions);
            $conditions = str_replace("%desc%",             "P.`description`", $conditions);
            $conditions = str_replace("%status%",           "O.status", $conditions);
            $conditions = str_replace("%type%",             "P.type", $conditions);
            $conditions = str_replace("%source%",           "P.source", $conditions);
            $conditions = str_replace("%source_type%",      "P.source_type", $conditions);
            $conditions = str_replace("%type_status%",      "O.type", $conditions);
            $conditions = str_replace("%datecreated%",      "DATE(P.datecreated)", $conditions);
        }

        if( !empty($order_by) ){
            $order_by   = str_replace("%username%",         "M.username", $order_by);
            $order_by   = str_replace("%name%",             "M.name", $order_by);
            $order_by   = str_replace("%id_member%",        "P.id_member", $order_by);
            $order_by   = str_replace("%qty%",              "P.qty", $order_by);
            $order_by   = str_replace("%status%",           "O.status", $order_by);
            $order_by   = str_replace("%type%",             "P.type", $order_by);
            $order_by   = str_replace("%source%",           "P.source", $order_by);
            $order_by   = str_replace("%source_type%",      "P.source_type", $order_by);
            $order_by   = str_replace("%type_status%",      "O.type", $order_by);
            $order_by   = str_replace("%desc%",             "P.`description`", $order_by);
            $order_by   = str_replace("%datecreated%",      "P.datecreated", $order_by);
        }

        $sql = '
        SELECT SQL_CALC_FOUND_ROWS P.*, M.username, M.name, O.status, O.type AS type_status
        FROM ' . $this->omzet_history . ' AS  P
        JOIN ' . $this->omzet . ' AS O ON (O.id = P.id_source)
        JOIN ' . $this->member . ' AS M ON (M.id = P.id_member)
        WHERE P.id > 0 ';

        if( !empty($conditions) )   { $sql .= $conditions; }

        $sql   .= ' ORDER BY '. ( !empty($order_by) ? $order_by : 'P.datecreated DESC');

        if( $limit ) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql, array(MEMBER));
        if(!$query || !$query->num_rows()) return false;

        return $query->result();
    }
    
    /**
     * Retrieve Personal Sales Member
     *
     * @author  Iqbal
     * @param   Integer $member_id          Member ID
     * @param   String  $this_month         String This Month (Optional)
     * @return  Decimal  Result of member Personal Sales Member
     */
    function get_personal_sales($id_member, $this_month=''){
        if ( !is_numeric($id_member) ) return false;

        $id_member = absint($id_member);
        if ( !$id_member ) return false;

        $sql    = '
        SELECT 
            IFNULL(SUM(omzet),0) AS total_omzet,
            IFNULL(SUM(qty),0) AS total_qty
        FROM '.$this->omzet.'
        WHERE id_member = ? AND status LIKE "personal"';
        
        if( !empty($this_month) ) $sql .= ' AND datecreated LIKE "'.$this_month.'%"';

        $query  = $this->db->query($sql, array($id_member));
        if ( !$query || !$query->num_rows() ) return false;
        return $query->row();
    }

    /**
     * Save data of Omzet History
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of Omzet History
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_omzet_history($data)
    {
        if (empty($data)) return false;

        if ( $id = $this->insert($data)) {
            return $id;
        };
        return false;
    }

    /**
     * Update data of Omzet History
     *
     * @author  Iqbal
     * @param   Int     $omzet_id   (Required)  Omzet History ID
     * @param   Array   $data       (Required)  Array data of Omzet History
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_omzet_history($omzet_id, $data)
    {
        if (empty($omzet_id) || empty($data))
            return false;

        if ($this->update($omzet_id, $data))
            return true;

        return false;
    }

    /**
     * Delete Omzet History data
     * 
     * @author  Iqbal
     * @param   Int     $id     (Required)  Omzet History ID
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function delete_omzet_history($id)
    {
        if (empty($id))
            return false;

        if ($this->delete($id))
            return true;

        return false;
    }

    // ---------------------------------------------------------------------------------
}
/* End of file Model_Omzet_History.php */
/* Location: ./ddmapp/models/Model_Omzet_History.php */
