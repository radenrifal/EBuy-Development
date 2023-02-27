<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('DDM_Model.php');

class Model_Shop extends DDM_Model{
    /**
     * Initialize table
     */
    var $shop_order             = TBL_PREFIX . "shop_order";
    var $shop_order_detail      = TBL_PREFIX . "shop_order_detail ";
    var $shop_order_customer    = TBL_PREFIX . "shop_order_customer";
    var $shop_detail_customer   = TBL_PREFIX . "shop_order_detail_customer";
    var $member                 = TBL_PREFIX . "member";
    var $customer               = TBL_PREFIX . "customer";
    var $product                = TBL_PREFIX . "product";
    var $product_category       = TBL_PREFIX . "product_category";
    var $product_point          = TBL_PREFIX . "product_point";
    var $payment_evidence       = TBL_PREFIX . "payment_evidence";
    
    /**
     * Initialize primary field
     */
    var $primary            = "id";
    
    /**
    * Constructor - Sets up the object properties.
    */
    public function __construct()
    {
        parent::__construct();
    }

    /*
	|--------------------------------------------------------------------------
    | Get All Products
	|--------------------------------------------------------------------------
    */
    function get_products($condition = "")
    {
        $this->db->select('*');
        $this->db->from(TBL_PRODUCT);

        if ($condition) {
            $condition;
        }

        $result = $this->db->get();
        //print_r($result);die;
        return $result;
    }
    
    /**
     * Get shop order
     * 
     * @author  Yuda
     * @param   Int     $id     (Optional)  ID of data
     * @return  Mixed   False on invalid date parameter, otherwise data of data(s).
     */
    function get_shop_orders($id=''){
        if ( !empty($id) ) { 
            $this->db->where($this->primary, $id);
        };
        
        $this->db->order_by("datecreated", "DESC"); 
        $query      = $this->db->get($this->shop_order);        
        return ( !empty($id) ? $query->row() : $query->result() );
    }

    /**
     * Get shop order by Field
     *
     * @author  Yuda
     * @param   String  $field  (Required)  Database field name or special field name defined inside this function
     * @param   String  $value  (Optional)  Value of the field being searched
     * @return  Mixed   Boolean false on failed process, invalid data, or data is not found, otherwise StdClass of data
     */
    function get_shop_order_by($field='', $value='', $conditions='', $limit = 0){
        if ( !$field || !$value ) return false;

        $this->db->where($field, $value);
        if ( $conditions ) { 
            $this->db->where($conditions);
        }

        $this->db->order_by("datecreated", "ASC"); 
        $query  = $this->db->get($this->shop_order);
        if ( !$query->num_rows() ){
            return false;
        }

        $data   = $query->result(); 
        if ($field == 'id' || $field == 'invoice' || $limit == 1 ) {
            foreach ( $data as $row ) {
                $datarow = $row;
            }
            $data = $datarow;
        }

        return $data;
    }

    /**
     * Get shop order by Field
     *
     * @author  Yuda
     * @param   String  $field  (Required)  Database field name or special field name defined inside this function
     * @param   String  $value  (Optional)  Value of the field being searched
     * @return  Mixed   Boolean false on failed process, invalid data, or data is not found, otherwise StdClass of data
     */
    function get_shop_detail_by($field='', $value='', $conditions='', $limit = 0){
        if ( !$field || !$value ) return false;

        $this->db->where($field, $value);
        if ( $conditions ) { 
            $this->db->where($conditions);
        }

        $this->db->order_by("id", "ASC"); 
        $query  = $this->db->get($this->shop_order_detail);
        if ( !$query->num_rows() ){
            return false;
        }

        $data   = $query->result(); 
        if ($field == 'id' || $limit == 1 ) {
            foreach ( $data as $row ) {
                $datarow = $row;
            }
            $data = $datarow;
        }

        return $data;
    }

    /**
     * Get shop order customer by Field
     *
     * @author  Yuda
     * @param   String  $field  (Required)  Database field name or special field name defined inside this function
     * @param   String  $value  (Optional)  Value of the field being searched
     * @return  Mixed   Boolean false on failed process, invalid data, or data is not found, otherwise StdClass of data
     */
    function get_shop_order_customer_by($field='', $value='', $conditions='', $limit = 0){
        if ( !$field || !$value ) return false;

        $this->db->where($field, $value);
        if ( $conditions ) { 
            $this->db->where($conditions);
        }

        $this->db->order_by("datecreated", "ASC"); 
        $query  = $this->db->get($this->shop_order_customer);
        if ( !$query->num_rows() ){
            return false;
        }

        $data   = $query->result(); 
        if ($field == 'id' || $field == 'invoice' || $limit == 1 ) {
            foreach ( $data as $row ) {
                $datarow = $row;
            }
            $data = $datarow;
        }

        return $data;
    }
    
    /**
     * Get customer
     * 
     * @author  Yuda
     * @param   Int     $id     (Optional)  ID of data
     * @return  Mixed   False on invalid date parameter, otherwise data of data(s).
     */
    function get_customers($id=''){
        if ( !empty($id) ) { 
            $this->db->where($this->primary, $id);
        };
        
        $this->db->order_by("datecreated", "DESC"); 
        $query      = $this->db->get($this->customer);        
        return ( !empty($id) ? $query->row() : $query->result() );
    }

    /**
     * Get customer by Field
     *
     * @author  Yuda
     * @param   String  $field  (Required)  Database field name or special field name defined inside this function
     * @param   String  $value  (Optional)  Value of the field being searched
     * @return  Mixed   Boolean false on failed process, invalid data, or data is not found, otherwise StdClass of data
     */
    function get_customer_by($field='', $value='', $conditions='', $limit = 0){
        if ( !$field || !$value ) return false;

        $this->db->where($field, $value);
        if ( $conditions ) { 
            $this->db->where($conditions);
        }

        $this->db->order_by("datecreated", "ASC"); 
        $query  = $this->db->get($this->customer);
        if ( !$query->num_rows() ){
            return false;
        }

        $data   = $query->result(); 
        if ($field == 'id' || $field == 'phone' || $limit == 1 ) {
            foreach ( $data as $row ) {
                $datarow = $row;
            }
            $data = $datarow;
        }

        return $data;
    }

    /**
     * Get shop order by Field
     *
     * @author  Yuda
     * @param   String  $field  (Required)  Database field name or special field name defined inside this function
     * @param   String  $value  (Optional)  Value of the field being searched
     * @return  Mixed   Boolean false on failed process, invalid data, or data is not found, otherwise StdClass of data
     */
    function get_payment_evidence_by($field='', $value='', $conditions='', $limit = 0){
        if ( !$field || !$value ) return false;

        $this->db->where($field, $value);
        if ( $conditions ) { 
            $this->db->where($conditions);
        }

        $this->db->order_by("id", "ASC"); 
        $query  = $this->db->get($this->payment_evidence);
        if ( !$query->num_rows() ){
            return false;
        }

        $data   = $query->result(); 
        if ($field == 'id' || $field == 'id_source' || $limit == 1 ) {
            foreach ( $data as $row ) {
                $datarow = $row;
            }
            $data = $datarow;
        }

        return $data;
    }

    /**
     * Retrieve all shop order data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of data               default 0
     * @param   Int     $offset             Offset ot data              default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of data list
     */
    function get_all_shop_order_data($limit=0, $offset=0, $conditions='', $order_by='', $num_rows = false){
        if( !empty($conditions) ){
            $conditions = str_replace("%id%",                   "PO.id", $conditions);
            $conditions = str_replace("%id_member%",            "PO.id_member", $conditions);
            $conditions = str_replace("%id_agent%",             "PO.id_agent", $conditions);
            $conditions = str_replace("%type%",                 "PO.type", $conditions);
            $conditions = str_replace("%invoice%",              "PO.invoice", $conditions);
            $conditions = str_replace("%username%",             "M.username", $conditions);
            $conditions = str_replace("%name%",                 "M.name", $conditions);
            $conditions = str_replace("%type_member%",          "M.type", $conditions);
            $conditions = str_replace("%unique%",               "PO.unique", $conditions);
            $conditions = str_replace("%status%",               "PO.status", $conditions);
            $conditions = str_replace("%received%",             "PO.name", $conditions);
            $conditions = str_replace("%email%",                "PO.email", $conditions);
            $conditions = str_replace("%phone%",                "PO.phone", $conditions);
            $conditions = str_replace("%province%",             "PO.province", $conditions);
            $conditions = str_replace("%city%",                 "PO.city", $conditions);
            $conditions = str_replace("%subdistrict%",          "PO.subdistrict", $conditions);
            $conditions = str_replace("%address%",              "PO.address", $conditions);
            $conditions = str_replace("%datecreated%",          "PO.datecreated", $conditions);
            $conditions = str_replace("%dateconfirm%",          "PO.dateconfirm", $conditions);
            $conditions = str_replace("%datemodified%",         "PO.datemodified", $conditions);
            $conditions = str_replace("%agentname%",            "AG.name", $conditions);
        }

        if( !empty($order_by) ){
            $order_by   = str_replace("%id%",                   "PO.id",  $order_by);
            $order_by   = str_replace("%id_member%",            "PO.id_member", $order_by);
            $order_by   = str_replace("%id_agent%",             "PO.id_agent", $order_by);
            $order_by   = str_replace("%invoice%",              "PO.invoice", $order_by);
            $order_by   = str_replace("%username%",             "M.username", $order_by);
            $order_by   = str_replace("%name%",                 "M.name", $order_by);
            $order_by   = str_replace("%type%",                 "PO.type", $order_by);
            $order_by   = str_replace("%unique%",               "PO.unique", $order_by);
            $order_by   = str_replace("%status%",               "PO.status", $order_by);
            $order_by   = str_replace("%received%",             "PO.name", $order_by);
            $order_by   = str_replace("%email%",                "PO.email", $order_by);
            $order_by   = str_replace("%phone%",                "PO.phone", $order_by);
            $order_by   = str_replace("%province%",             "PO.province", $order_by);
            $order_by   = str_replace("%city%",                 "PO.city", $order_by);
            $order_by   = str_replace("%subdistrict%",          "PO.subdistrict", $order_by);
            $order_by   = str_replace("%address%",              "PO.address", $order_by);
            $order_by   = str_replace("%datecreated%",          "PO.datecreated", $order_by);
            $order_by   = str_replace("%dateconfirm%",          "PO.dateconfirm", $order_by);
            $order_by   = str_replace("%datemodified%",         "PO.datemodified", $order_by);
            $order_by   = str_replace("%agentname%",            "AG.name", $order_by);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS 
                    PO.*,
                    M.username,
                    M.name AS membername,
                    M.photo AS photoprofile, 
                    AG.name AS agentname,
                    IFNULL(PE.bill_bank,"") AS tf_bank,
                    IFNULL(PE.bill_no,"") AS tf_bill,
                    IFNULL(PE.bill_name,"") AS tf_bill_name,
                    IFNULL(PE.amount,"") AS tf_nominal,
                    IFNULL(PE.image,"") AS tf_img
                FROM ' . $this->shop_order . ' AS PO 
                INNER JOIN ' . $this->member . ' AS M ON (M.id = PO.id_member) 
                LEFT JOIN ' . $this->payment_evidence . ' AS PE ON (PE.id_source = PO.id AND PE.id_member = PO.id_member AND PE.type = "shop") 
                LEFT JOIN ' . $this->member . ' AS AG ON (AG.id = PO.id_agent)
                ';

        if( !empty($conditions) ){ $sql .= $conditions; }
        $sql   .= ' ORDER BY '. ( !empty($order_by) ? $order_by : 'PO.datecreated DESC');

        if( $limit ) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query( $sql );
        if(!$query || !$query->num_rows()) return false;

        if ( $num_rows ){
            return $query->num_rows();
        }

        return $query->result();
    }

    /**
     * Retrieve all shop order data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of data               default 0
     * @param   Int     $offset             Offset ot data              default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of data list
     */
    function get_all_shop_order_customer_data($limit=0, $offset=0, $conditions='', $order_by='', $num_rows = false){
        if( !empty($conditions) ){
            $conditions = str_replace("%id%",                   "PO.id", $conditions);
            $conditions = str_replace("%id_member%",            "PO.id_member", $conditions);
            $conditions = str_replace("%id_customer%",          "PO.id_customer", $conditions);
            $conditions = str_replace("%username%",             "M.username", $conditions);
            $conditions = str_replace("%name%",                 "M.name", $conditions);
            $conditions = str_replace("%type%",                 "M.type", $conditions);
            $conditions = str_replace("%unique%",               "PO.unique", $conditions);
            $conditions = str_replace("%status%",               "PO.status", $conditions);
            $conditions = str_replace("%received%",             "PO.name", $conditions);
            $conditions = str_replace("%email%",                "PO.email", $conditions);
            $conditions = str_replace("%phone%",                "PO.phone", $conditions);
            $conditions = str_replace("%province%",             "PO.province", $conditions);
            $conditions = str_replace("%city%",                 "PO.city", $conditions);
            $conditions = str_replace("%subdistrict%",          "PO.subdistrict", $conditions);
            $conditions = str_replace("%address%",              "PO.address", $conditions);
            $conditions = str_replace("%datecreated%",          "PO.datecreated", $conditions);
            $conditions = str_replace("%dateconfirm%",          "PO.dateconfirm", $conditions);
            $conditions = str_replace("%datemodified%",         "PO.datemodified", $conditions);
        }

        if( !empty($order_by) ){
            $order_by   = str_replace("%id%",                   "PO.id",  $order_by);
            $order_by   = str_replace("%id_member%",            "PO.id_member", $order_by);
            $order_by   = str_replace("%username%",             "M.username", $order_by);
            $order_by   = str_replace("%name%",                 "M.name", $order_by);
            $order_by   = str_replace("%unique%",               "PO.unique", $order_by);
            $order_by   = str_replace("%status%",               "PO.status", $order_by);
            $order_by   = str_replace("%received%",             "PO.name", $order_by);
            $order_by   = str_replace("%email%",                "PO.email", $order_by);
            $order_by   = str_replace("%phone%",                "PO.phone", $order_by);
            $order_by   = str_replace("%province%",             "PO.province", $order_by);
            $order_by   = str_replace("%city%",                 "PO.city", $order_by);
            $order_by   = str_replace("%subdistrict%",          "PO.subdistrict", $order_by);
            $order_by   = str_replace("%address%",              "PO.address", $order_by);
            $order_by   = str_replace("%datecreated%",          "PO.datecreated", $order_by);
            $order_by   = str_replace("%dateconfirm%",          "PO.dateconfirm", $order_by);
            $order_by   = str_replace("%datemodified%",         "PO.datemodified", $order_by);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS 
                    PO.*,
                    M.username,
                    M.name AS membername
                FROM ' . $this->shop_order_customer . ' AS PO 
                INNER JOIN ' . $this->member . ' AS M ON (M.id = PO.id_member) ';

        if( !empty($conditions) ){ $sql .= $conditions; }
        $sql   .= ' ORDER BY '. ( !empty($order_by) ? $order_by : 'PO.datecreated DESC');

        if( $limit ) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query( $sql );
        if(!$query || !$query->num_rows()) return false;

        if ( $num_rows ){
            return $query->num_rows();
        }

        return $query->result();
    }

    /**
     * Retrieve all omzet Order Monthly data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of data               default 0
     * @param   Int     $offset             Offset ot data              default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @param   String  $total_conditions   Total Condition of query    default ''
     * @return  Object  Result of Data List
     */
    function get_all_omzet_shop_order_monthly($limit=0, $offset=0, $conditions='', $order_by='', $total_conditions=''){
        if( !empty($conditions) ){
            $conditions = str_replace("%month_omzet%",          "A.month_omzet", $conditions);
        }

        if ( $total_conditions ) {
            $total_conditions = str_replace("%total_trx%",          "COUNT(*)", $total_conditions);
            $total_conditions = str_replace("%subtotal%",           "SUM(A.subtotal)", $total_conditions);
            $total_conditions = str_replace("%total_shipping%",     "SUM(A.shipping)", $total_conditions);
            $total_conditions = str_replace("%total_discount%",     "SUM(A.discount)", $total_conditions);
            $total_conditions = str_replace("%total_payment%",      "SUM(A.payment)", $total_conditions);
            $total_conditions = str_replace("%total_omzet%",        "SUM(A.omzet)", $total_conditions);
        }

        if( !empty($order_by) ){
            $order_by   = str_replace("%month_omzet%",          "A.month_omzet", $order_by);
            $order_by   = str_replace("%total_trx%",            "total_trx", $order_by);
            $order_by   = str_replace("%subtotal%",             "subtotal", $order_by);
            $order_by   = str_replace("%total_shipping%",       "total_shipping", $order_by);
            $order_by   = str_replace("%total_discount%",       "total_discount", $order_by);
            $order_by   = str_replace("%total_payment%",        "total_payment", $order_by);
            $order_by   = str_replace("%total_omzet%",          "total_omzet", $order_by);
        }        

        $sql    = 'SELECT SQL_CALC_FOUND_ROWS 
                        A.month_omzet,
                        IFNULL(COUNT(*), 0) AS total_trx,
                        IFNULL( SUM(A.subtotal), 0 ) AS subtotal,
                        IFNULL( SUM(A.shipping), 0 ) AS total_shipping,
                        IFNULL( SUM(A.discount), 0 ) AS total_discount,
                        IFNULL( SUM(A.payment), 0 ) AS total_payment,
                        IFNULL( SUM(A.omzet), 0 ) AS total_omzet
                    FROM (
                        SELECT 
                            DATE_FORMAT(S.datecreated, "%Y-%m") AS month_omzet,
                            S.subtotal AS subtotal,
                            S.shipping AS shipping,
                            S.discount AS discount,
                            S.total_payment AS payment,
                            (S.total_payment - S.shipping - S.unique - S.registration) AS omzet
                        FROM `'. $this->shop_order .'` AS S
                        WHERE S.status = 1
                    ) AS A ';

        if( !empty($conditions) )   { $sql .= $conditions; }

        $sql   .= ' GROUP BY 1 ';

        if ( $total_conditions ) { $sql .= ' HAVING ' . ltrim( $total_conditions, ' AND' ); }

        $sql   .= ' ORDER BY '. ( !empty($order_by) ? $order_by : 'month_omzet DESC');

        if( $limit ) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql);
        if(!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Retrieve Total Shop Order
     *
     * @author  Yuda
     * @param   String  $conditions         Condition of query          default ''
     * @return  Object  Result of data total
     */
    function get_total_shop_order($conditions='') {
        $sql    = 'SELECT SQL_CALC_FOUND_ROWS 
                        IFNULL(COUNT(*), 0) AS total_trx,
                        IFNULL( SUM(total_qty), 0 ) AS total_qty,
                        IFNULL( SUM(subtotal), 0 ) AS subtotal,
                        IFNULL( SUM(registration), 0 ) AS total_registration,
                        IFNULL( SUM(shipping), 0 ) AS total_shipping,
                        IFNULL( SUM(`unique`), 0 ) AS total_unique,
                        IFNULL( SUM(discount), 0 ) AS total_discount,
                        IFNULL( SUM(total_payment), 0 ) AS total_payment,
                        ( IFNULL( SUM(total_payment), 0 ) - IFNULL( SUM(shipping), 0 ) - IFNULL( SUM(`unique`), 0 ) - IFNULL( SUM(registration), 0 ) ) AS total_omzet
                    FROM `'. $this->shop_order .'` WHERE id > 0 ';

        if( !empty($conditions) )   { $sql .= $conditions; }
        
        $query  = $this->db->query($sql);

        if ( !$query || !$query->num_rows() )
            return false;

        return $query->row();
    }

    /*
	|--------------------------------------------------------------------------
    | Fetch Product To show in select2
	|--------------------------------------------------------------------------
    */
    function fetchProduct($searchTerm = "")
    {
        // Fetch users
        $this->db->select('*');
        $this->db->where("name like '%" . $searchTerm . "%' ");
        $this->db->where("status", 1);
        $fetched_records = $this->db->get(TBL_PRODUCT);
        $fetch = $fetched_records->result_array();
        // Initialize Array with fetched data
        $data = array();
        foreach ($fetch as $row) {
            $data[] = array("id" => $row['id'], "text" => $row['name']);
        }
        return $data;
    }
    
    /**
     * Save data of shop order
     * 
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of shop orders
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_shop_order($data){
        if( empty($data) ) return false;
        if( $this->db->insert($this->shop_order, $data) ) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }
    
    /**
     * Save data of shop order detail
     * 
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of shop order details
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_shop_order_detail($data){
        if( empty($data) ) return false;
        if( $this->db->insert($this->shop_order_detail, $data) ) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }
    
    /**
     * Save data of payment evidence
     * 
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_payment_evidence($data){
        if( empty($data) ) return false;
        if( $this->db->insert($this->payment_evidence, $data) ) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }
    
    /**
     * Save data of customer
     * 
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_customer($data){
        if( empty($data) ) return false;
        if( $this->db->insert($this->customer, $data) ) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }
    
    /**
     * Save data of shop order customer
     * 
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_shop_order_customer($data){
        if( empty($data) ) return false;
        if( $this->db->insert($this->shop_order_customer, $data) ) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }
    
    /**
     * Save data of shop order detail
     * 
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of shop order details
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_shop_detail_customer($data){
        if( empty($data) ) return false;
        if( $this->db->insert($this->shop_detail_customer, $data) ) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }

    /**
     * Update shop order
     *
     * @author  Yuda
     * @param   Int     $id     (Required)  data id
     * @param   Array   $data   (Required)  Data
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_data_shop_order($id, $data){
        if( !$id || empty($id) ) return false;
        if( !$data || empty($data) ) return false;

        if ( is_array($id) ) $this->db->where_in($this->shop_order, $id);
        else $this->db->where($this->primary, $id);

        if( $this->db->update($this->shop_order, $data) )
            return true;

        return false;
    }

    /**
     * Update customer
     *
     * @author  Yuda
     * @param   Int     $id     (Required)  data id
     * @param   Array   $data   (Required)  Data
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_data_customer($id, $data){
        if( !$id || empty($id) ) return false;
        if( !$data || empty($data) ) return false;

        if ( is_array($id) ) $this->db->where_in($this->customer, $id);
        else $this->db->where($this->primary, $id);

        if( $this->db->update($this->customer, $data) )
            return true;

        return false;
    }

    /**
     * Update shop order customer
     *
     * @author  Yuda
     * @param   Int     $id     (Required)  data id
     * @param   Array   $data   (Required)  Data
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_data_shop_order_customer($id, $data){
        if( !$id || empty($id) ) return false;
        if( !$data || empty($data) ) return false;

        if ( is_array($id) ) $this->db->where_in($this->shop_order_customer, $id);
        else $this->db->where($this->primary, $id);

        if( $this->db->update($this->shop_order_customer, $data) )
            return true;

        return false;
    }

    // END OF FILE #################################################################################
}
