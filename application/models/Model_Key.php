<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('DDM_Model.php');

class Model_Key extends DDM_Model{
	
    public $_table          = 'keys';
	
    /**
     * Initialize table
     */
    var $table              = TBL_PREFIX . "keys";
    
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
    // CRUD (Manipulation) data bank
    // ---------------------------------------------------------------------------------
    
    /**
     * Retrieve all key data
     * 
     * @author  Yuda
     * @param   Int     $limit              Limit of data               default 0
     * @param   Int     $offset             Offset ot data              default 0
     * @param   String  $conditions         Condition of query          default array()
     * @param   String  $order_by           Column that make to order   default array()
     * @return  Object  Result of data list
     */
    function get_data( $limit=0, $offset=0, $conditions = array(), $order_by = array() ) {
		$this->limit( $limit, $offset );
		
		if ( $order_by ) {
			foreach( $order_by as $criteria => $order )
				$this->order_by( $criteria, $order );
		}
		
		if ( $conditions ) {
			return $this->get_many_by($conditions);
		}
		
		return $this->get_all();
	}
    
    /**
     * Get Keys
     * 
     * @author  Yuda
     * @param   Int     $id     (Required)  ID of key
     * @return  Mixed   False on invalid date parameter, otherwise data of key(s).
     */
    function get_keys($id=''){
        if ( !empty($id) ) { 
            $id = absint($id); 
            $this->db->where($this->primary, $id);
        };
        
        $this->db->order_by("kode", "ASC"); 
        $query = $this->db->get($this->table);
		if ( ! $query || ! $query->num_rows() ){
			return false;
		}
		
        return ( !empty($id) ? $query->row() : $query->result() );
    }

    /**
     * Get Key by Field
     *
     * @author  Yuda
     * @param   String  $field  (Required)  Database field name or special field name defined inside this function
     * @param   String  $value  (Optional)  Value of the field being searched
     * @return  Mixed   Boolean false on failed process, invalid data, or data is not found, otherwise StdClass of key
     */
    function get_key_by($field='', $value='', $conditions='', $limit = 0){
        if ( !$field || !$value ) return false;

        $this->db->where($field, $value);
        if ( $conditions ) { 
            $this->db->where($conditions);
        }

        $this->db->order_by("name", "ASC"); 
        $query  = $this->db->get($this->table);
        if ( !$query->num_rows() ){
            return false;
        }

        $data   = $query->result(); 
        if ($field == 'id' || $field == 'key' || $limit == 1 ) {
            foreach ( $data as $row ) {
                $datarow = $row;
            }
            $data = $datarow;
        }

        return $data;
    }
    
    /**
     * Save data of Key
     * 
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of key
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function save_data($data){
        if( empty($data) ) return false;
        if( $this->db->insert($this->table, $data) ) {
            $id = $this->db->insert_id();
            return $id;
        };
        return false;
    }
    
    /**
     * Updata data of Key
     * 
     * @author  Yuda
     * @param   Int     $id     (Required)  ID of key
     * @param   Array   $data   (Required)  Array data of key
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_data($id, $data){
        if( !$id || empty($id) ) return false;
        if( !$data || empty($data) ) return false;

        if ( is_array($id) ) {
        	$this->db->where_in($this->table, $id);
        } else {
        	$this->db->where($this->primary, $id);
        }

        if( $this->db->update($this->table, $data) ) {
            return $id;
        }

        return false;
    }
    
    // ---------------------------------------------------------------------------------
}
/* End of file Model_Key.php */
/* Location: ./App/models/Model_Key.php */
