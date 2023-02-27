<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('DDM_Model.php');

class Model_Address extends DDM_Model{
    /**
     * Initialize table
     */
    var $province           = TBL_PREFIX . "province";
    var $district           = TBL_PREFIX . "district";
    var $subdistrict        = TBL_PREFIX . "subdistrict";
    var $village            = TBL_PREFIX . "village";
    
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
    // CRUD (Manipulation) data province and regional
    // ---------------------------------------------------------------------------------
    
    /**
     * Get Province
     * 
     * @author  Yuda
     * @param   Int     $id     (Optional)  ID of province
     * @return  Mixed   False on invalid date parameter, otherwise data of province(s).
     */
    function get_provinces($id=''){
        if ( !empty($id) ) { 
            $this->db->where($this->primary, $id);
        };
        
        $this->db->order_by("province_name", "ASC"); 
        $query      = $this->db->get($this->province);        
        return ( !empty($id) ? $query->row() : $query->result() );
    }
    
    /**
     * Get District
     * 
     * @author  Yuda
     * @param   Int     $id     (Optional)  ID of districts
     * @return  Mixed   False on invalid date parameter, otherwise data of districts.
     */
    function get_districts($id=''){
        if ( !empty($id) ) { 
            $this->db->where($this->primary, $id);
        };
        
        $this->db->order_by("district_type", "ASC"); 
        $this->db->order_by("district_name", "ASC"); 
        $query      = $this->db->get($this->district);        
        return ( !empty($id) ? $query->row() : $query->result() );
    }
    
    /**
     * Get Subdistricts
     * 
     * @author  Yuda
     * @param   Int     $id     (Optional)  ID of subdistricts
     * @return  Mixed   False on invalid date parameter, otherwise data of subdistricts.
     */
    function get_subdistricts($id=''){
        if ( !empty($id) ) { 
            $this->db->where($this->primary, $id);
        };
        
        $this->db->order_by("subdistrict_name", "ASC"); 
        $query      = $this->db->get($this->subdistrict);        
        return ( !empty($id) ? $query->row() : $query->result() );
    }
    
    /**
     * Get Village
     * 
     * @author  Yuda
     * @param   Int     $id     (Optional)  ID of village
     * @return  Mixed   False on invalid date parameter, otherwise data of village.
     */
    function get_villages($id=''){
        if ( !empty($id) ) { 
            $this->db->where($this->primary, $id);
        };
        
        $this->db->order_by("village_name", "ASC"); 
        $query      = $this->db->get($this->village);        
        return ( !empty($id) ? $query->row() : $query->result() );
    }
    
    /**
     * Get Districts
     * 
     * @author  Yuda
     * @param   Int     $id     (Required)  ID of province
     * @param   String  $id     (Optional)  district name
     * @return  Mixed   False on invalid date parameter, otherwise data of district(s).
     */
    function get_districts_by_province($province_id, $district_name = ''){
        if ( !$province_id || empty($province_id) ) return false; 
        
        $province_id = absint($province_id);
        if ( !$province_id ) return false;

        $this->db->where('province_id', $province_id);

        if ( $district_name ) { $this->db->like('district_name', $district_name); }

        $this->db->order_by("district_type", "DESC"); 
        $this->db->order_by("district_name", "ASC"); 
        $query      = $this->db->get($this->district); 
        return $query->result();
    }
    
    /**
     * Get SubDistricts
     * 
     * @author  Yuda
     * @param   Int     $id     (Required)  ID of district
     * @return  Mixed   False on invalid date parameter, otherwise data of subdistrict(s).
     */
    function get_subdistricts_by_district($district_id){
        if ( !$district_id || empty($district_id) ) return false; 
        
        $district_id = absint($district_id);
        if ( !$district_id ) return false;

        $this->db->where('district_id', $district_id);
        $this->db->order_by("subdistrict_name", "ASC"); 
        $query      = $this->db->get($this->subdistrict); 
               
        return $query->result();
    }
    
    /**
     * Get Villages
     * 
     * @author  Yuda
     * @param   Int     $id     (Required)  ID of subdistrict
     * @return  Mixed   False on invalid date parameter, otherwise data of village(s).
     */
    function get_villages_by_subdistrict($subdistrict_id){
        if ( !$subdistrict_id || empty($subdistrict_id) ) return false; 
        $this->db->where('subdistrict_id', $subdistrict_id);
        $this->db->order_by("village_name", "ASC"); 
        $query      = $this->db->get($this->village); 
               
        return $query->result();
    }
    
    // ---------------------------------------------------------------------------------
}
/* End of file Model_Address.php */
/* Location: ./app/models/Model_Address.php */
