<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('DDM_Model.php');

class Model_PIN extends DDM_Model{
	/**
	 * For DDM_Model
	 */
    public $_table              = 'pin';

    /**
     * Initialize table
     */
    var $member                 = TBL_PREFIX . "member";
    var $member_confirm         = TBL_PREFIX . "member_confirm";
    var $product                = TBL_PREFIX . "product";
    var $pin                    = TBL_PREFIX . "pin";
    var $pin_transfer           = TBL_PREFIX . "pin_transfer";

    /**
     * Initialize primary field
     */
    var $primary                = "id";
    var $parent                 = "parent";

    /**
	* Constructor - Sets up the object properties.
	*/
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get PINs with row lock
     *
     * Please only use this if you intend to update the rows inside a transaction
     *
     * @since 1.0.0
     * @access public
     *
     * @author ahmad
     */
    function get_pins_with_lock( $id_member, $product, $status = 1, $limit = 0 ) {
        if ( empty( $id_member ) )
            return false;

        $sql = 'SELECT * FROM ' . $this->pin . ' WHERE id_member = ? AND product = ? AND status = ?';
        if ( $limit )
            $sql .= ' LIMIT ' . $limit;

        $sql .= ' FOR UPDATE';
        $qry = $this->db->query( $sql, array( $id_member, $product, $status ) );
        if ( ! $qry || ! $qry->num_rows() )
            return false;

        return $qry->result();
    }

    /**
     * Retrieve all member pin data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of member pin list
     */
    function get_all_member_pin($limit=0, $offset=0, $conditions='', $order_by=''){
        if( !empty($conditions) ){
            $conditions = str_replace("%type%",                 "A.type", $conditions);
            $conditions = str_replace("%username%",             "A.username", $conditions);
            $conditions = str_replace("%id_member%",            "A.id", $conditions);
            $conditions = str_replace("%name%",                 "A.name", $conditions);
            $conditions = str_replace("%total%",                "B.total", $conditions);
            $conditions = str_replace("%total_active%",         "C.total_active", $conditions);
        }

        if( !empty($order_by) ){
            $order_by   = str_replace("%username%",             "A.username",  $order_by);
            $order_by   = str_replace("%id_member%",            "A.id",  $order_by);
            $order_by   = str_replace("%name%",                 "A.name",  $order_by);
            $order_by   = str_replace("%total%",                "B.total",  $order_by);
            $order_by   = str_replace("%total_active%",         "C.total_active",  $order_by);
        }

        $sql = '
            SELECT SQL_CALC_FOUND_ROWS A.id, A.username, A.name, A.type, B.total, C.total_active
            FROM ' . $this->member . ' AS A
            LEFT JOIN (
                SELECT id_member, IFNULL(COUNT(id),0) AS total
                FROM ' . $this->pin . '
                GROUP BY id_member
            ) AS B ON B.id_member = A.id 
            LEFT JOIN (
                SELECT id_member, IFNULL(COUNT(id),0) AS total_active
                FROM ' . $this->pin . '
                WHERE status = 1
                GROUP BY id_member
            ) AS C ON C.id_member = A.id ';

        if( !empty($conditions) )   { $sql .= $conditions; }

        $sql   .= ' ORDER BY '. ( !empty($order_by) ? $order_by : 'A.id ASC');

        if( $limit ) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql);
        if(!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Retrieve all member pin data
     *
     * @author  Yuda
     * @param   Integer $member_id          Member ID
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of member bonus list
     */
    function get_all_my_pin($id_member, $limit=0, $offset=0, $conditions='', $order_by=''){
        if ( !is_numeric($id_member) ) return false;

        $id_member = absint($id_member);
        if ( !$id_member ) return false;

        if( !empty($conditions) ){
            $conditions = str_replace("%id_pin%",           "A.id_pin", $conditions);
            $conditions = str_replace("%status%",           "A.status", $conditions);
            $conditions = str_replace("%username%",         "C.username", $conditions);
            $conditions = str_replace("%product%",          "A.product", $conditions);
            $conditions = str_replace("%amount_member%",    "P.amount_member", $conditions);
            $conditions = str_replace("%username_sender%",  "B.username_sender", $conditions);
            $conditions = str_replace("%datecreated%",      "DATE(A.datecreated)", $conditions);
            $conditions = str_replace("%datetransfer%",     "DATE(B.datecreated)", $conditions);
        }

        if( !empty($order_by) ){
            $order_by   = str_replace("%id_pin%",           "A.id_pin",  $order_by);
            $order_by   = str_replace("%status%",           "A.status",  $order_by);
            $order_by   = str_replace("%username%",         "C.username",  $order_by);
            $order_by   = str_replace("%product%",          "P.product_name", $order_by);
            $order_by   = str_replace("%amount_member%",    "P.amount_member",  $order_by);
            $order_by   = str_replace("%username_sender%",  "B.username_sender",  $order_by);
            $order_by   = str_replace("%datecreated%",      "A.datecreated",  $order_by);
            $order_by   = str_replace("%datetransfer%",     "B.datecreated",  $order_by);
        }

        $sql = '
            SELECT SQL_CALC_FOUND_ROWS A.*,
            	IFNULL(B.id_member_sender, 0) AS id_member_sender,
            	IFNULL(B.username_sender, "admin") AS username_sender,
            	IFNULL(B.id_member, 0) AS id_member_receiver,
            	IFNULL(B.username, 0) AS username_receiver,
            	IFNULL(B.id_pin, 0) AS pin_transfered,
            	IFNULL(B.datecreated, "0000-00-00 00:00:00") AS datetransfer,
            	C.username,
                P.product_name,
                P.amount_member
            FROM `'. $this->pin .'` AS A
            LEFT JOIN (
            	SELECT
                    max(id),
                	id_member_sender,
                	username_sender,
                	id_member,
                	username,
                	id_pin,
                	max(datecreated) AS datecreated
               	FROM `'. $this->pin_transfer .'`
                WHERE id_member = '. $id_member .'
                GROUP BY id_pin
            ) AS B ON B.id_pin = A.id
            LEFT JOIN `'. $this->member .'` AS C ON C.id = A.id_member
            LEFT JOIN `'. $this->product .'` AS P ON P.id = A.product
            WHERE A.id_member = '. $id_member .' ';

        if( !empty($conditions) )   { $sql .= $conditions; }

        $sql   .= ' ORDER BY '. ( !empty($order_by) ?
            ( substr($order_by,0,13) == "C.datecreated" ?
                $order_by . ', A.datecreated '. ( substr($order_by,-3)=='asc' ? 'DESC' : 'ASC' ) : $order_by ) : 'B.datecreated DESC, A.datecreated ASC');

        if( $limit ) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql);
        if(!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Retrieve all pin data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of pin                default 0
     * @param   Int     $offset             Offset ot pin               default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of pin list
     */
    function get_all_pin($limit=0, $offset=0, $conditions='', $order_by=''){
        if( !empty($conditions) ){
            $conditions = str_replace("%id_member%",            "A.id_member", $conditions);
            $conditions = str_replace("%id_memberreg%",         "A.id_member_registered", $conditions);
            $conditions = str_replace("%id_memberres%",         "A.id_member_register", $conditions);
            $conditions = str_replace("%id_pin%",               "A.id_pin", $conditions);
            $conditions = str_replace("%product%",              "A.product", $conditions);
            $conditions = str_replace("%status%",               "A.status", $conditions);
            $conditions = str_replace("%used%",                 "A.used", $conditions);
            $conditions = str_replace("%owner%",                "C.username", $conditions);
            $conditions = str_replace("%owner_name%",           "C.name", $conditions);
            $conditions = str_replace("%register%",             "D.username", $conditions);
            $conditions = str_replace("%register_name%",        "D.name", $conditions);
            $conditions = str_replace("%username%",             "B.username", $conditions);
            $conditions = str_replace("%name%",                 "B.name", $conditions);
            $conditions = str_replace("%datecreated%",          "DATE(A.datecreated)", $conditions);
            $conditions = str_replace("%dateused%",             "DATE(A.dateused)", $conditions);
        }

        if( !empty($order_by) ){
            $order_by   = str_replace("%id_member%",            "A.id_member", $order_by);
            $order_by   = str_replace("%id_memberreg%",         "A.id_member_registered", $order_by);
            $order_by   = str_replace("%id_memberres%",         "A.id_member_register", $order_by);
            $order_by   = str_replace("%id_pin%",               "A.id_pin",  $order_by);
            $order_by   = str_replace("%product%",              "A.product",  $order_by);
            $order_by   = str_replace("%status%",               "A.status",  $order_by);
            $order_by   = str_replace("%used%",                 "A.used",  $order_by);
            $order_by   = str_replace("%owner%",                "C.username",  $order_by);
            $order_by   = str_replace("%owner_name%",           "C.name", $order_by);
            $order_by   = str_replace("%register%",             "D.username",  $order_by);
            $order_by   = str_replace("%register_name%",        "D.name", $order_by);
            $order_by   = str_replace("%username%",             "B.username",  $order_by);
            $order_by   = str_replace("%name%",                 "B.name", $order_by);
            $order_by   = str_replace("%datecreated%",          "A.datecreated",  $order_by);
            $order_by   = str_replace("%dateused%",             "A.dateused",  $order_by);
        }

        $sql = '
            SELECT SQL_CALC_FOUND_ROWS 
                A.*,
                B.id AS id_registered,
                B.username AS username_registered,
                B.name AS name_registered,
                C.username AS username,
                C.name AS name,
                C.type AS type,
                D.id AS id_register,
                D.username AS username_register,
                D.name AS name_register,
                P.product_name
            FROM ' . $this->pin . ' AS A
            LEFT JOIN (
                SELECT id, username, name FROM ' . $this->member . '
            ) AS B ON B.id = A.id_member_registered
            LEFT JOIN ' . $this->member . ' AS C ON C.id = A.id_member
            LEFT JOIN (
                SELECT id, username, name FROM ' . $this->member . '
            ) AS D ON D.id = A.id_member_register
            LEFT JOIN `'. $this->product .'` AS P ON (P.id = A.product) ';

        if( !empty($conditions) )   { $sql .= $conditions; }

        $sql   .= ' ORDER BY '. ( !empty($order_by) ? $order_by : 'A.datemodified DESC');

        if( $limit ) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql);
        if(!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Retrieve all pin transfer data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of member pin transfer list
     */
    function get_all_member_pin_transfer($limit=0, $offset=0, $conditions='', $order_by='', $total_condition = '' ){
        if( !empty($conditions) ){
            $conditions = str_replace("%username_sender%",      "M1.username", $conditions);
            $conditions = str_replace("%name_sender%",          "M1.name", $conditions);
            $conditions = str_replace("%status_sender%",        "M1.as_stockist", $conditions);
            $conditions = str_replace("%username%",             "M2.username", $conditions);
            $conditions = str_replace("%name%",                 "M2.name", $conditions);
            $conditions = str_replace("%status%",               "M2.as_stockist", $conditions);
            $conditions = str_replace("%id_member_sender%",     "T.id_member_sender", $conditions);
            $conditions = str_replace("%id_member%",            "T.id_member", $conditions);
            $conditions = str_replace("%product%",              "T.product", $conditions);
            $conditions = str_replace("%datecreated%",          "T.datecreated", $conditions);
        }

        if( !empty($order_by) ){
            $order_by   = str_replace("%username_sender%",      "M1.username",  $order_by);
            $order_by   = str_replace("%name_sender%",          "M1.name",  $order_by);
            $order_by   = str_replace("%status_sender%",        "M1.as_stockist", $order_by);
            $order_by   = str_replace("%username%",             "M2.username",  $order_by);
            $order_by   = str_replace("%name%",                 "M2.name",  $order_by);
            $order_by   = str_replace("%status%",               "M2.as_stockist", $order_by);
            $order_by   = str_replace("%id_member_sender%",     "T.id_member_sender",  $order_by);
            $order_by   = str_replace("%id_member%",            "T.id_member",  $order_by);
            $order_by   = str_replace("%product%",              "T.product",  $order_by);
            $order_by   = str_replace("%total%",                "qty",  $order_by);
            $order_by   = str_replace("%datecreated%",          "T.datecreated",  $order_by);
        }

        $total_qty      = ' IFNULL( COUNT(T.id), 0 ) ';

        if ( $total_condition ) {
            $total_condition = str_replace("%total%",           $total_qty, $total_condition);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS 
                    T.id_member_sender, T.id_member, T.datecreated, 
                    M1.username AS username_sender, M1.name AS name_sender, M1.as_stockist AS status_sender, 
                    M2.username, M2.name, M2.as_stockist, 
                    P.product_name, 
                    '.$total_qty.' AS qty 
                FROM ' . $this->pin_transfer . ' T 
                JOIN ' . $this->member . ' M1 ON M1.id = T.id_member_sender
                JOIN ' . $this->member . ' M2 ON M2.id = T.id_member
                JOIN ' . $this->product . ' P ON P.id = T.product ';

        if( !empty($conditions) ){ $sql .= $conditions; }

        $sql   .= ' GROUP BY T.id_member, T.datecreated, T.product';

        if ( $total_condition ) {
            $sql .= ' HAVING ' . ltrim( $total_condition, ' AND' );
        }

        $sql   .= ' ORDER BY ' . ( !empty($order_by) ? $order_by : 'T.datecreated DESC');

        if( $limit ) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql);
        if(!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Retrieve all pin order data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of member pin list
     */
    function get_all_pin_product($limit=0, $offset=0, $conditions='', $order_by=''){
        if( !empty($conditions) ){
            $conditions = str_replace("%product%",          "A.id", $conditions);
            $conditions = str_replace("%name%",             "A.product_name", $conditions);
            $conditions = str_replace("%amount%",           "A.amount", $conditions);
            $conditions = str_replace("%bv%",               "A.bv", $conditions);
            $conditions = str_replace("%total%",            "B.total", $conditions);
            $conditions = str_replace("%total_active%",     "C.total_active", $conditions);
            $conditions = str_replace("%total_used%",       "D.total_used", $conditions);
        }

        if( !empty($order_by) ){
            $order_by   = str_replace("%product%",          "A.id", $order_by);
            $order_by   = str_replace("%name%",             "A.product_name", $order_by);
            $order_by   = str_replace("%amount%",           "A.amount", $order_by);
            $order_by   = str_replace("%bv%",               "A.bv", $order_by);
            $order_by   = str_replace("%total%",            "B.total", $order_by);
            $order_by   = str_replace("%total_active%",     "C.total_active", $order_by);
            $order_by   = str_replace("%total_used%",       "D.total_used", $order_by);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS 
                    A.*, 
                    B.total, 
                    C.total_active, 
                    D.total_used
                FROM ' . $this->product . ' AS A
                LEFT JOIN (
                    SELECT product, IFNULL(COUNT(id),0) AS total
                    FROM ' . $this->pin . '
                    GROUP BY product
                ) AS B ON B.product = A.id 
                LEFT JOIN (
                    SELECT product, IFNULL(COUNT(id),0) AS total_active
                    FROM ' . $this->pin . '
                    WHERE status = 1
                    GROUP BY product
                ) AS C ON C.product = A.id 
                LEFT JOIN (
                    SELECT product, IFNULL(COUNT(id),0) AS total_used
                    FROM ' . $this->pin . '
                    WHERE status = 2
                    GROUP BY product
                ) AS D ON D.product = A.id ';

        if( !empty($conditions) ){ $sql .= $conditions; }

        $sql   .= ' ORDER BY '. ( !empty($order_by) ? $order_by : 'A.is_sort ASC');

        if( $limit ) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql);
        if(!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Get all member pin
     *
     * @author  Yuda
     * @param   Int     $id_member  (Required)  Member ID
     * @param   String  $status     (Optional)  Status of Pin, default 'all'
     * @param   Boolean $count      (Optional)  Count PIN, default 'false'
     * @return  Mixed   False on invalid member id, otherwise array of all pin.
     */
    function get_pins($id_member, $status='all', $count=false, $product=''){
        if ( !is_numeric($id_member) ) return false;

        $id_member  = absint($id_member);
        if ( !$id_member ) return false;

        $data       = array('id_member' => $id_member);

        if ( $status == 'active' ){
            $data['status'] = 1;
        } elseif ( $status == 'pending' ){
            $data['status'] = 0;
        } elseif ( $status == 'used' ){
            $data['status'] = 2;
        }

        if ( !empty($product) ) { $data['product'] = $product; }

        $this->db->where($data);
        $query = $this->db->get($this->pin);

        if( $count ){
            if ( $query->num_rows() > 0 )
                return $query->num_rows();

            return 0;
        }else{
            if ( !$query->num_rows() )
                return false;

            return $query->result();
        }
    }

    /**
     * Get member pin by id pin
     *
     * @author  Yuda
     * @param   Int     $pin_id  (Required)  Pin ID
     * @return  Mixed   False on invalid pin id, otherwise array of pin.
     */
    function get_pin_by_id($pin_id){
        if ( !is_numeric($pin_id) ) return false;

        $pin_id  = absint($pin_id);
        if ( !$pin_id ) return false;

        $query = $this->db->get_where($this->pin, array($this->primary => $pin_id));
        if ( !$query->num_rows() )
            return false;

        foreach ( $query->result() as $row ) {
            $pin = $row;
        }

        return $pin;
    }

    /**
     * Count All Stock PIN Rows
     *
     * @author  Yuda
     * @return  Int of total rows stock PIN
     */
    function count_all_pin_member( $id_member ) {
        $sql = 'SELECT COUNT( id ) AS total_pin FROM '.$this->pin.' WHERE id_member = '.$id_member.' AND status = 1';
        $qry = $this->db->query( $sql );

        if ( ! $qry->num_rows() )
            return false;

        return $qry->row()->total_pin;
    }

    /**
     * Count All Pin Rows
     *
     * @author  Yuda
     * @param   Int     $id_member  (Optional) ID of member
     * @param   String  $status     (Optional) Status of PIN ('pending','active' and 'used')
     * @return  Int of total rows pin member
     */
    function count_all_pin($id_member='', $status=''){
        if ( !empty($id_member) ) { $this->db->where('id_member', $id_member); }

        if ( !empty($status) && $status == 'pending' ){
            $this->db->where('status', 0);
        } elseif( !empty($status) && $status == 'active' ) {
            $this->db->where('status', 1);
        } elseif( !empty($status) && $status == 'used' ) {
            $this->db->where('status', 2);
        }

        $query = $this->db->get($this->pin);

        return $query->num_rows();
    }

    /**
     * Save data pin of member
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of pin
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_pin($data){
        if( empty($data) ) return false;

        if( $this->db->insert($this->pin, $data) ) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }

    /**
     * Save data pin transfer of member
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of pin transer
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_pin_tansfer($data){
        if( empty($data) ) return false;

        if( $this->db->insert($this->pin_transfer, $data) ) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }

    /**
     * Update pin
     *
     * @author  Yuda
     * @param   Int     $id     (Required)  Pin ID
     * @param   Array   $data   (Required)  Data Pin ID
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_pin($id, $data){
        if( !$id || empty($id) ) return false;
        if( !$data || empty($data) ) return false;

        if ( is_array($id) ) $this->db->where_in($this->primary, $id);
		else $this->db->where($this->primary, $id);

        if( $this->db->update($this->pin, $data) )
            return true;

        return false;
    }

    /**
     * Update pin used
     *
     * @author  Yuda
     * @param   Int     $id         (Required)  Pin ID
     * @param   Int     $id_member  (Required)  Member ID
     * @param   Int     $register   (Required)  Register Member ID
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_pin_used($id, $id_member=0, $id_registered=0, $datetime = '', $used_for = 'register'){
        if( empty($id) ) return false;

        $datetime   = $datetime ? $datetime : date('Y-m-d H:i:s');
        $data       = array(
            'status'                => 2,
            'id_member_register'    => $id_member,
            'id_member_registered'  => $id_registered,
            'used'                  => $used_for,
            'dateused'              => $datetime,
        );

        $this->db->where($this->primary, $id);
        if( $this->db->update($this->pin, $data) )
            return true;

        return false;
    }
}
/* End of file Model_PIN.php */
/* Location: ./application/models/Model_PIN.php */
