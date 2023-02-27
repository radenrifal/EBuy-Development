<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('DDM_Model.php');

class Model_Product_Transfer extends DDM_Model
{
    /**
     * For AN_Model
     */
    public $_table              = 'product_transfer';

    /**
     * Initialize table
     */
    var $transfer               = TBL_PREFIX."product_transfer";
    var $member                 = TBL_PREFIX."member";

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
     * Retrieve All Product Transfer
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of member product list
     */
    function get_all_product_transfer($limit=0, $offset=0, $conditions='', $order_by=''){
        if( !empty($conditions) ){
            $conditions = str_replace("%id_member%",            "T.id_member", $conditions);
            $conditions = str_replace("%id_member_receiver%",   "T.id_member_receiver", $conditions);
            $conditions = str_replace("%username_sender%",      "M.username", $conditions);
            $conditions = str_replace("%username_receiver%",    "R.name", $conditions);
            $conditions = str_replace("%datecreated%",          "DATE(T.datecreated)", $conditions);
            $conditions = str_replace("%qty%",                  "T.qty", $conditions);
        }

        if( !empty($order_by) ){
            $order_by   = str_replace("%id_member%",            "T.id_member", $order_by);
            $order_by   = str_replace("%id_member_receiver%",   "T.id_member_receiver", $order_by);
            $order_by   = str_replace("%username_sender%",      "M.username", $order_by);
            $order_by   = str_replace("%username_receiver%",    "R.name", $order_by);
            $order_by   = str_replace("%datecreated%",          "DATE(T.datecreated)", $order_by);
            $order_by   = str_replace("%qty%",                  "T.qty", $order_by);
        }

        $sql ='
        SELECT SQL_CALC_FOUND_ROWS 
            T.*, 
            S.username AS username_sender,
            R.username AS username_receiver
        FROM ' . $this->transfer . ' T
        JOIN ' . $this->member . ' S ON (S.id = T.id_member)
        JOIN ' . $this->member . ' R ON (R.id = T.id_member_receiver)
        WHERE T.qty > 0  ';

        if( !empty($conditions) )   { $sql .= $conditions; }

        $sql   .= ' ORDER BY '. ( !empty($order_by) ? $order_by : 'T.datecreated DESC');

        if( $limit ) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql);
        if(!$query || !$query->num_rows()) return false;

        return $query->result();
    }
    
    /**
     * Save data of Transfer Product
     *
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of Transfer Product
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_transfer_product($data)
    {
        if (empty($data)) return false;

        if ( $id = $this->insert($data)) {
            return $id;
        };
        return false;
    }

    /**
     * Update data of Transfer Product
     *
     * @author  Iqbal
     * @param   Int     $omzet_id   (Required)  Transfer Product ID
     * @param   Array   $data       (Required)  Array data of Transfer Product
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_transfer_product($omzet_id, $data)
    {
        if (empty($omzet_id) || empty($data))
            return false;

        if ($this->update($omzet_id, $data))
            return true;

        return false;
    }

    /**
     * Delete Transfer Product data
     * 
     * @author  Iqbal
     * @param   Int     $id     (Required)  Transfer Product ID
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function delete_transfer_product($id)
    {
        if (empty($id))
            return false;

        if ($this->delete($id))
            return true;

        return false;
    }

    // ---------------------------------------------------------------------------------
}
/* End of file Model_Product_Transfer.php */
/* Location: ./ddmapp/models/Model_Product_Transfer.php */
