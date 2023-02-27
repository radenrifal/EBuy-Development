<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'DDM_Model.php' );

class Model_Staff extends DDM_Model{
	
	public $_table = 'staff';

	/**
     * Initialize table
     */
    var $staff              = TBL_PREFIX . "staff";

    /**
     * Initialize primary field
     */
    var $primary            = "id";
    
    public $before_create   = array( 'encode_password', 'serialize(role)' );
    public $before_update   = array( 'serialize(role)' );
    public $after_get       = array( 'unserialize(role)' );
    
    function get_staffs( $limit, $offset, $conditions = array(), $order_by = array() ) {
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
     * Get Staff data by staff ID
     *
     * @author  Yuda
     * @param   Integer $staff_id (Required)  Member ID
     * @return  Mixed   False on failed process, otherwise object of member.
     */
    function get_staffdata($staff_id)
    {
        if(!is_numeric($staff_id)) return false;

        $staff_id = absint($staff_id);
        if(!$staff_id) return false;

        $query = $this->db->get_where($this->staff, array($this->primary => $staff_id));
        if(!$query->num_rows())
            return false;

        foreach($query->result() as $row) {
            $staff = $row;
        }

        return $staff;
    }

    /**
     * Retrieve all Staff data
     *
     * @author  Yuda
     * @param   Int $limit Limit of staff             default 0
     * @param   Int $offset Offset ot staff            default 0
     * @param   String $conditions Condition of query          default ''
     * @param   String $order_by Column that make to order   default ''
     * @return  Object  Result of staff list
     */
    function get_all_staff($limit = 0, $offset = 0, $conditions = '', $order_by = '')
    {
        if(!empty($conditions)) {
            $conditions = str_replace("%id%", "id", $conditions);
            $conditions = str_replace("%username%", "username", $conditions);
            $conditions = str_replace("%name%", "name", $conditions);
            $conditions = str_replace("%access%", "access", $conditions);
            $conditions = str_replace("%status%", "status", $conditions);
            $conditions = str_replace("%datecreated%", "datecreated", $conditions);
        }

        if(!empty($order_by)) {
            $order_by = str_replace("%id%", "id", $order_by);
            $order_by = str_replace("%username%", "username", $order_by);
            $order_by = str_replace("%name%", "name", $order_by);
            $order_by = str_replace("%access%", "access", $order_by);
            $order_by = str_replace("%datecreated%", "datecreated", $order_by);
        }

        $sql = 'SELECT * FROM ' . $this->staff . ' ';

        if(!empty($conditions)) {
            $sql .= $conditions;
        }
        $sql .= ' ORDER BY ' . (!empty($order_by) ? $order_by : 'username ASC');

        if($limit) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query($sql);
        if(!$query || !$query->num_rows()) return false;

        return $query->result();
    }

    /**
     * Update data of Staff
     *
     * @author  Yuda
     * @param   Int $id (Required)  Member ID
     * @param   Array $data (Required)  Array data of user
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_data($id, $data)
    {
        if(empty($id) || empty($data)) return false;

        if( isset($data['password']) ) {
            $data = $this->encode_password($data);
        }

        $this->db->where($this->primary, $id);
        if($this->db->update($this->staff, $data))
            return true;

        return false;
    }
    
    public function decode_password( $row ) {
        if ( is_object( $row ) ) {
            if ( isset( $row->password ) )
                $row->password = $row->password;
        } else {
            if ( isset( $row['password'] ) )
                $row['password'] = $row['password'];
        }

        return $row;
    }
    
    public function encode_password( $row ) {
        if ( is_object( $row ) ) {
            $row->password = password_hash($row->password, PASSWORD_BCRYPT);
        } else {
            $row['password'] = password_hash($row['password'], PASSWORD_BCRYPT);
        }

        return $row;
    }
}

/* End of file model_staff.php */
/* Location: ./application/models/model_staff.php */
