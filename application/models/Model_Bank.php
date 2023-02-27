<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('DDM_Model.php');

class Model_Bank extends DDM_Model{
	
    public $_table          = 'banks';
	
    /**
     * Initialize table
     */
    var $table              = TBL_PREFIX . "banks";
    var $city_code          = TBL_PREFIX . "city_code";
    
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
     * Retrieve all bank data
     * 
     * @author  Yuda
     * @param   Int     $limit              Limit of bank               default 0
     * @param   Int     $offset             Offset ot bank              default 0
     * @param   String  $conditions         Condition of query          default array()
     * @param   String  $order_by           Column that make to order   default array()
     * @return  Object  Result of bank list
     */
    function get_data( $limit=0, $offset=0, $conditions = array(), $order_by = array() ) {
		$this->limit( $limit, $offset );
		
		if ( $order_by ) {
			foreach( $order_by as $criteria => $order )
				$this->order_by( $criteria, $order );
		}
		
		if ( $conditions ) {
			return $this->get_many_by();
		}
		
		return $this->get_all();
	}
    
    /**
     * Get banks
     * 
     * @author  Yuda
     * @param   Int     $id     (Required)  ID of bank
     * @return  Mixed   False on invalid date parameter, otherwise data of bank(s).
     */
    function get_bank($id=''){
        if ( !empty($id) ) { 
            $id = absint($id); 
            $this->db->where($this->primary, $id);
        };
        
        $this->db->order_by("kode", "ASC"); 
        $query = $this->db->get($this->table);
		if ( ! $query || ! $query->num_rows() )
			return false;
		
        return ( !empty($id) ? $query->row() : $query->result() );
    }
    
    /**
     * Get banks
     * 
     * @author  Yuda
     * @param   String      $code   (Required)  Code of bank
     * @return  Mixed   False on invalid date parameter, otherwise data of bank(s).
     */
    function get_bank_by_code($code){
        if ( !$code ) return false;
        $this->db->where('kode', $code);
        $query = $this->db->get($this->table);        
        return $query->row();
    }
    
    /**
     * Get banks
     * 
     * @author  Yuda
     * @param   String      $code   (Required)  Code of bank
     * @return  Mixed   False on invalid date parameter, otherwise data of bank(s).
     */
    function get_bank_by_name($name){
        if ( !$name ) return false;
        $this->db->like('nama', $name);
        $query = $this->db->get($this->table);        
        return $query->row();
    }
    
    /**
     * Get Cities Code
     * 
     * @author  Yuda
     * @param   Int     $id     (Required)  ID of Cities Code
     * @return  Mixed   False on invalid date parameter, otherwise data of city(ies).
     */
    function get_cities_code($code=''){
        if ( !empty($code) ) { 
            $code = absint($code); 
            $this->db->where('kode', $code);
        };
        
        $this->db->order_by("daerah", "ASC"); 
        $query      = $this->db->get($this->city_code);        
        return ( !empty($id) ? $query->row() : $query->result() );
    }

    function get_city_list($city = '', $onerow = false) {
        if ( !empty($city) ) { 
            $this->db->like('daerah', $city);
        };
        
        $this->db->order_by("daerah", "ASC"); 
        $query = $this->db->get($this->city_code);
        if ( ! $query || ! $query->num_rows() )
            return false;

        if ( $onerow && !empty($city) ) {
            return $query->row();
        }
        
        return $query->result();
    }

    function get_city_by_code($code = '') {
        if ( !$code || empty( $code ) ) return false;
        $this->db->where('kode', $code);
        $query = $this->db->get($this->city_code);
        if ( ! $query || ! $query->num_rows() )
            return false;

        return $query->row();
    }
    
    /**
     * Save data of reseller
     * 
     * @author  Yuda
     * @param   Array   $data   (Required)  Array data of reseller
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
    
    // ---------------------------------------------------------------------------------
}
/* End of file Model_Bank.php */
/* Location: ./app/models/Model_Bank.php */
