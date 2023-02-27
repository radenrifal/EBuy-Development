<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('DDM_Model.php');

class Model_Product extends DDM_Model{
    /**
     * Initialize table
     */
    var $product            = TBL_PREFIX . "product";
    var $product_category   = TBL_PREFIX . "product_category";
    var $product_point      = TBL_PREFIX . "product_point";
    var $product_package    = TBL_PREFIX . "product_package";
    var $package_detail     = TBL_PREFIX . "product_package_detail";
    var $member             = TBL_PREFIX . "member";
    
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

    // ---------------------------------------------------------------------------------
    // CRUD
    // ---------------------------------------------------------------------------------
    
    /**
     * Get product
     * 
     * @author  Yuda
     * @param   Int     $id     (Optional)  ID of product
     * @return  Mixed   False on invalid date parameter, otherwise data of product(s).
     */
    function get_products($id='', $is_active = false){
        if ( !empty($id) ) { 
            $this->db->where($this->primary, $id);
        }

        if ( $is_active ) {
            $this->db->where('status', 1);
        }
        
        $this->db->order_by("name", "ASC"); 
        $query      = $this->db->get($this->product);        
        return ( !empty($id) ? $query->row() : $query->result() );
    }
    
    /**
     * Get product category
     * 
     * @author  Yuda
     * @param   Int     $id     (Optional)  ID of product_category
     * @return  Mixed   False on invalid date parameter, otherwise data of product_category(s).
     */
    function get_product_category($id='', $is_active = false){
        if ( !empty($id) ) { 
            $this->db->where($this->primary, $id);
        };

        if ( $is_active ) {
            $this->db->where('status', 1);
        }
        
        $this->db->order_by("name", "ASC"); 
        $query      = $this->db->get($this->product_category);        
        return ( !empty($id) ? $query->row() : $query->result() );
    }

    /**
     * Get product by Field
     *
     * @author  Yuda
     * @param   String  $field  (Required)  Database field name or special field name defined inside this function
     * @param   String  $value  (Optional)  Value of the field being searched
     * @return  Mixed   Boolean false on failed process, invalid data, or data is not found, otherwise StdClass of product
     */
    function get_product_by($field='', $value='', $conditions='', $limit = 0){
        if ( !$field || !$value ) return false;

        $this->db->where($field, $value);
        if ( $conditions ) { 
            $this->db->where($conditions);
        }

        $this->db->order_by("name", "ASC"); 
        $query  = $this->db->get($this->product);
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
     * Get product point by Field
     *
     * @author  Yuda
     * @param   String  $field  (Required)  Database field name or special field name defined inside this function
     * @param   String  $value  (Optional)  Value of the field being searched
     * @return  Mixed   Boolean false on failed process, invalid data, or data is not found, otherwise StdClass of product point
     */
    function get_product_point_by($field='', $value='', $conditions='', $limit = 0){
        if ( !$field || !$value ) return false;

        $this->db->where($field, $value);
        if ( $conditions ) { 
            $this->db->where($conditions);
        }
 
        $query  = $this->db->get($this->product_point);
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
     * Get product package by Field
     *
     * @author  Iqbal
     * @return  Mixed   Boolean false on failed process, invalid data, or data is not found, otherwise StdClass of product package
     */
    function get_product_package(){
        $this->db->order_by("id", "ASC"); 
        $query  = $this->db->get($this->product_package);
        if ( !$query->num_rows() ){
            return false;
        }

        $data   = $query->result(); 
        return $data;
    }

    /**
     * Get product package by Field
     *
     * @author  Yuda
     * @param   String  $field  (Required)  Database field name or special field name defined inside this function
     * @param   String  $value  (Optional)  Value of the field being searched
     * @return  Mixed   Boolean false on failed process, invalid data, or data is not found, otherwise StdClass of product package
     */
    function get_product_package_by($field='', $value='', $conditions='', $limit = 0){
        if ( !$field || !$value ) return false;

        $this->db->where($field, $value);
        if ( $conditions ) { 
            $this->db->where($conditions);
        }

        $this->db->order_by("name", "ASC"); 
        $query  = $this->db->get($this->product_package);
        if ( !$query->num_rows() ){
            return false;
        }

        $data   = $query->result(); 
        if ($field == 'id' || $field == 'name' || $limit == 1 ) {
            foreach ( $data as $row ) {
                $datarow = $row;
            }
            $data = $datarow;
        }

        return $data;
    }

    /**
     * Get product package detail by Field
     *
     * @author  Yuda
     * @param   String  $field  (Required)  Database field name or special field name defined inside this function
     * @param   String  $value  (Optional)  Value of the field being searched
     * @return  Mixed   Boolean false on failed process, invalid data, or data is not found, otherwise StdClass of product package detail
     */
    function get_package_detail_by($field='', $value='', $conditions='', $limit = 0){
        if ( !$field || !$value ) return false;

        $this->db->where($field, $value);
        if ( $conditions ) { 
            $this->db->where($conditions);
        }

        $query  = $this->db->get($this->package_detail);
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
     * Retrieve All product Data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of data               default 0
     * @param   Int     $offset             Offset ot data              default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of data list
     */
    function get_all_product($limit=0, $offset=0, $conditions='', $order_by=''){
        if( !empty($conditions) ){
            $conditions = str_replace("%id%",               "P.id", $conditions);
            $conditions = str_replace("%id_category%",      "P.id_category", $conditions);
            $conditions = str_replace("%category%",         "PC.name", $conditions);
            $conditions = str_replace("%product%",          "P.name", $conditions);
            $conditions = str_replace("%name%",             "P.name", $conditions);
            $conditions = str_replace("%slug%",             "P.slug", $conditions);
            $conditions = str_replace("%slug_category%",    "PC.slug", $conditions);
            $conditions = str_replace("%status%",           "P.status", $conditions);
            $conditions = str_replace("%price_agent1%",     "P.price_agent1", $conditions);
            $conditions = str_replace("%price_agent2%",     "P.price_agent2", $conditions);
            $conditions = str_replace("%price_agent3%",     "P.price_agent3", $conditions);
            $conditions = str_replace("%datecreated%",      "DATE(P.datecreated)", $conditions);
            $conditions = str_replace("%datemodified%",     "DATE(P.datemodified)", $conditions);
        }

        if( !empty($order_by) ){
            $order_by   = str_replace("%id%",               "P.id", $order_by);
            $order_by   = str_replace("%id_category%",      "P.id_category", $order_by);
            $order_by   = str_replace("%category%",         "PC.name", $order_by);
            $order_by   = str_replace("%product%",          "P.name", $order_by);
            $order_by   = str_replace("%name%",             "P.name", $order_by);
            $order_by   = str_replace("%slug%",             "P.slug", $order_by);
            $order_by   = str_replace("%slug_category%",    "PC.slug", $order_by);
            $order_by   = str_replace("%status%",           "P.status", $order_by);
            $order_by   = str_replace("%price_agent1%",     "P.price_agent1", $order_by);
            $order_by   = str_replace("%price_agent2%",     "P.price_agent2", $order_by);
            $order_by   = str_replace("%price_agent3%",     "P.price_agent3", $order_by);
            $order_by   = str_replace("%datecreated%",      "P.datecreated", $order_by);
            $order_by   = str_replace("%datemodified%",     "P.datemodified", $order_by);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS
                    P.*, PC.name AS category, PC.slug AS slug_category
                FROM ' . $this->product . ' P
                INNER JOIN ' . $this->product_category . ' PC ON (P.id_category = PC.id)
                WHERE P.id >= 0 ';

        if( !empty($conditions) )   { $sql .= $conditions; }

        $sql   .= ' ORDER BY '. ( !empty($order_by) ? $order_by : 'P.datecreated DESC');

        if( $limit ) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql);
        if(!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Retrieve All category product Data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of data               default 0
     * @param   Int     $offset             Offset ot data              default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of data list
     */
    function get_all_category($limit=0, $offset=0, $conditions='', $order_by='', $group_by=''){
        if( !empty($conditions) ){
            $conditions = str_replace("%id%",               "id", $conditions);
            $conditions = str_replace("%name%",             "name", $conditions);
            $conditions = str_replace("%slug%",             "slug", $conditions);
            $conditions = str_replace("%status%",           "status", $conditions);
            $conditions = str_replace("%datecreated%",      "datecreated", $conditions);
        }

        if( !empty($order_by) ){
            $order_by   = str_replace("%id%",               "id", $order_by);
            $order_by   = str_replace("%name%",             "name", $order_by);
            $order_by   = str_replace("%slug%",             "slug", $order_by);
            $order_by   = str_replace("%status%",           "status", $order_by);
            $order_by   = str_replace("%datecreated%",      "datecreated", $order_by);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS * FROM ' . $this->product_category . ' WHERE id > 0 ';

        if( !empty($conditions) )   { $sql .= $conditions; }

        $sql   .= ' ORDER BY '. ( !empty($order_by) ? $order_by : 'name ASC');

        if( $limit ) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql);
        if(!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Retrieve All product point Data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of data               default 0
     * @param   Int     $offset             Offset ot data              default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of data list
     */
    function get_all_product_point($limit=0, $offset=0, $conditions='', $order_by='', $group_by=''){
        if( !empty($conditions) ){
            $conditions = str_replace("%id%",               "P.id", $conditions);
            $conditions = str_replace("%name%",             "P.name", $conditions);
            $conditions = str_replace("%slug%",             "P.slug", $conditions);
            $conditions = str_replace("%status%",           "P.status", $conditions);
            $conditions = str_replace("%total%",            "PP.total", $conditions);
            $conditions = str_replace("%point%",            "PP.point", $conditions);
            $conditions = str_replace("%datecreated%",      "P.datecreated", $conditions);
        }

        if( !empty($order_by) ){
            $order_by   = str_replace("%id%",               "P.id", $order_by);
            $order_by   = str_replace("%name%",             "P.name", $order_by);
            $order_by   = str_replace("%slug%",             "P.slug", $order_by);
            $order_by   = str_replace("%status%",           "P.status", $order_by);
            $order_by   = str_replace("%total%",            "PP.total", $order_by);
            $order_by   = str_replace("%point%",            "PP.point", $order_by);
            $order_by   = str_replace("%datecreated%",      "P.datecreated", $order_by);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS A.*
                FROM (
                    SELECT
                        P.id AS source_id,
                        "product" AS source,
                        P.name AS source_name,
                        P.id AS product_id,
                        P.name AS product_name,
                        IFNULL(PP.id, 0) AS product_point_id,
                        IFNULL(PP.total, 0) AS product_total, 
                        IFNULL(PP.point, 0) AS product_point,
                        0 AS package_id,
                        0 AS package_name,
                        0 AS package_point_id,
                        0 AS package_total, 
                        0 AS package_point
                    FROM ' . $this->product . ' P
                    LEFT JOIN ' . $this->product_point . ' PP ON (P.id = PP.id_source AND PP.source = "product")
                    UNION ALL
                    SELECT
                        PACK.id AS source_id,
                        "package" AS source,
                        PACK.name AS source_name,
                        0 AS product_id,
                        0 AS product_name,
                        0 AS product_point_id,
                        0 AS product_total, 
                        0 AS product_point,
                        PACK.id AS package_id,
                        PACK.name AS package_name,
                        IFNULL(PACKP.id, 0) AS package_point_id,
                        IFNULL(PACKP.total, 0) AS package_total, 
                        IFNULL(PACKP.point, 0) AS package_point
                    FROM ' . $this->product_package . ' PACK
                    LEFT JOIN ' . $this->product_point . ' PACKP ON (PACK.id = PACKP.id_source AND PACKP.source = "package")
                ) A
                WHERE A.source_id > 0 ';

        if( !empty($conditions) )   { $sql .= $conditions; }

        $sql   .= ' ORDER BY '. ( !empty($order_by) ? $order_by : 'A.source ASC');

        if( $limit ) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql);
        if(!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Retrieve All product package data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of data               default 0
     * @param   Int     $offset             Offset ot data              default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of data list
     */
    function get_all_product_package($limit=0, $offset=0, $conditions='', $order_by=''){
        if( !empty($conditions) ){
            $conditions = str_replace("%id%",               "id", $conditions);
            $conditions = str_replace("%package%",          "name", $conditions);
            $conditions = str_replace("%name%",             "name", $conditions);
            $conditions = str_replace("%slug%",             "slug", $conditions);
            $conditions = str_replace("%qty%",              "qty", $conditions);
            $conditions = str_replace("%point%",            "point", $conditions);
            $conditions = str_replace("%price1%",           "price1", $conditions);
            $conditions = str_replace("%price2%",           "price2", $conditions);
            $conditions = str_replace("%price3%",           "price3", $conditions);
            $conditions = str_replace("%weight%",           "weight", $conditions);
            $conditions = str_replace("%status%",           "status", $conditions);
            $conditions = str_replace("%mix%",              "is_mix", $conditions);
            $conditions = str_replace("%datecreated%",      "DATE(datecreated)", $conditions);
            $conditions = str_replace("%datemodified%",     "DATE(datemodified)", $conditions);
        }

        if( !empty($order_by) ){
            $order_by   = str_replace("%id%",               "id", $order_by);
            $order_by   = str_replace("%package%",          "name", $order_by);
            $order_by   = str_replace("%name%",             "name", $order_by);
            $order_by   = str_replace("%slug%",             "slug", $order_by);
            $order_by   = str_replace("%qty%",              "qty", $order_by);
            $order_by   = str_replace("%point%",            "point", $order_by);
            $order_by   = str_replace("%price1%",           "price1", $order_by);
            $order_by   = str_replace("%price2%",           "price2", $order_by);
            $order_by   = str_replace("%price3%",           "price3", $order_by);
            $order_by   = str_replace("%weight%",           "weight", $order_by);
            $order_by   = str_replace("%status%",           "status", $order_by);
            $order_by   = str_replace("%mix%",              "is_mix", $order_by);
            $order_by   = str_replace("%datecreated%",      "datecreated", $order_by);
            $order_by   = str_replace("%datemodified%",     "datemodified", $order_by);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS * FROM ' . $this->product_package . ' WHERE id > 0 ';

        if( !empty($conditions) )   { $sql .= $conditions; }

        $sql   .= ' ORDER BY '. ( !empty($order_by) ? $order_by : 'datecreated DESC');

        if( $limit ) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql);
        if(!$query || !$query->num_rows()) return false;

        return $query->result();
    }
    
    /**
     * Save data of product
     * 
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of products
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_product($data){
        if( empty($data) ) return false;
        if( $this->db->insert($this->product, $data) ) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }
    
    /**
     * Save data of product category
     * 
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of product_category
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_product_category($data){
        if( empty($data) ) return false;
        if( $this->db->insert($this->product_category, $data) ) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }
    
    /**
     * Save data of product point
     * 
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of product_point
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_product_point($data){
        if( empty($data) ) return false;
        if( $this->db->insert($this->product_point, $data) ) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }
    
    /**
     * Save data of product package
     * 
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of product_package
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_product_package($data){
        if( empty($data) ) return false;
        if( $this->db->insert($this->product_package, $data) ) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }
    
    /**
     * Save data of product package detail
     * 
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of product_package_detail
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data_package_detail($data){
        if( empty($data) ) return false;
        if( $this->db->insert($this->package_detail, $data) ) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }

    /**
     * Update product
     *
     * @author  Yuda
     * @param   Int     $id     (Required)  product id
     * @param   Array   $data   (Required)  Data products
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_data_product($id, $data){
        if( !$id || empty($id) ) return false;
        if( !$data || empty($data) ) return false;

        if ( is_array($id) ) $this->db->where_in($this->product, $id);
        else $this->db->where($this->primary, $id);

        if( $this->db->update($this->product, $data) )
            return true;

        return false;
    }

    /**
     * Update product category
     *
     * @author  Yuda
     * @param   Int     $id     (Required)  product_category id
     * @param   Array   $data   (Required)  Data product_categorys
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_data_product_category($id, $data){
        if( !$id || empty($id) ) return false;
        if( !$data || empty($data) ) return false;

        if ( is_array($id) ) $this->db->where_in($this->product_category, $id);
        else $this->db->where($this->primary, $id);

        if( $this->db->update($this->product_category, $data) )
            return true;

        return false;
    }

    /**
     * Update product point
     *
     * @author  Yuda
     * @param   Int     $id     (Required)  product_point id
     * @param   Array   $data   (Required)  Data product_points
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_data_product_point($id, $data){
        if( !$id || empty($id) ) return false;
        if( !$data || empty($data) ) return false;

        if ( is_array($id) ) $this->db->where_in($this->product_point, $id);
        else $this->db->where($this->primary, $id);

        if( $this->db->update($this->product_point, $data) )
            return true;

        return false;
    }

    /**
     * Update product package
     *
     * @author  Yuda
     * @param   Int     $id     (Required)  product_package id
     * @param   Array   $data   (Required)  Data product_package
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_data_product_package($id, $data){
        if( !$id || empty($id) ) return false;
        if( !$data || empty($data) ) return false;

        if ( is_array($id) ) $this->db->where_in($this->product_package, $id);
        else $this->db->where($this->primary, $id);

        if( $this->db->update($this->product_package, $data) )
            return true;

        return false;
    }

    /**
     * Update product package detail
     *
     * @author  Yuda
     * @param   Int     $id     (Required)  package_detail id
     * @param   Array   $data   (Required)  Data package_detail
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_data_package_detail($id, $data){
        if( !$id || empty($id) ) return false;
        if( !$data || empty($data) ) return false;

        if ( is_array($id) ) $this->db->where_in($this->package_detail, $id);
        else $this->db->where($this->primary, $id);

        if( $this->db->update($this->package_detail, $data) )
            return true;

        return false;
    }

    /**
     * Delete data of Product
     *
     * @author  Yuda
     * @param   Int     $id   (Required)  ID of data
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function delete_data_product($id){
        if( empty($id) ) return false;
        $this->db->where($this->primary, $id);
        if( $this->db->delete($this->product) ) {
            return true;
        };
        return false;
    }

    /**
     * Delete data of Category
     *
     * @author  Yuda
     * @param   Int     $id   (Required)  ID of data
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function delete_data_category($id){
        if( empty($id) ) return false;
        $this->db->where($this->primary, $id);
        if( $this->db->delete($this->product_category) ) {
            return true;
        };
        return false;
    }

    /**
     * Delete data of Product Package
     *
     * @author  Yuda
     * @param   Int     $id   (Required)  ID of data
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function delete_data_product_package($id){
        if( empty($id) ) return false;
        $this->db->where($this->primary, $id);
        if( $this->db->delete($this->product_package) ) {
            return true;
        };
        return false;
    }
    
    // ---------------------------------------------------------------------------------
}
/* End of file Model_Product.php */
/* Location: ./app/models/Model_Product.php */
