<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('DDM_Model.php');

class Model_Auth extends DDM_Model{
	/**
	 * For DDM_Model
	 */
    public $_table          = 'member';

    /**
     * Initialize table
     */
    var $users              = TBL_PREFIX . "member";
    var $auth_session       = TBL_PREFIX . "sessions";

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
    // Login Process
    // ---------------------------------------------------------------------------------

    /**
     * Sign In
     *
     * Authenticate member and drop login cookie if member is valid.
     *
     * @author  Yuda
     * @param   Array   $credential     (Optional)  Associative array of member credential. It contains member_email, member_password, and remember
     * @return  Mixed   False on invalid member, otherwise object of member.
     */
    function signon($credentials, $time = '')
    {
        if ( empty($credentials) || !is_array($credentials) ) return false;

        if ( !empty($credentials['remember']) ){
            $credentials['remember'] = true;
        }else{
            $credentials['remember'] = false;
        }

        $member = $this->authenticate( $credentials['username'], $credentials['password'] );

        if ( empty($member) ) return false;

        /*
        if ( $auth_session = $this->get_session_by('username', $credentials['username']) ) {
            if ( $validate_session = ddm_check_auth_session( $auth_session, $time ) ) {
                ddm_clear_auth_session($validate_session);
            }
        }
        */

        if ( ! empty( $member->id ) ) {
            $id_staff = false;
            if ( ! empty( $member->staff ) )
                $id_staff = $member->staff->id;

            ddm_set_auth_cookie( $member->id, $credentials['remember'], '', $id_staff, $time );
        }

        return $member;
    }

    /**
     * Authenticate member
     *
     * @author  Yuda
     * @param   String  $username       (Required)  Username
     * @param   String  $password       (Required)  Password
     * @return  Mixed   False on invalid member, otherwise object of member.
     */
    function authenticate( $username, $password, $api = false )
    {
        $username     = trim($username);
        $password     = trim($password);

        if ( empty($username) || empty($password) ) return false;

        $memberdata = $this->get_user_by('login', $username);
        if ( ! $memberdata ) {
            if ( $api )
                return false;

            // check if this is staff login
            if ( $staff = $this->Model_Staff->get_by( 'username', $username ) ) {
                if (ddm_hash_verify($password, $staff->password)) {
                    // return admin
                    $memberdata = $this->get_userdata( 1 );
                    $memberdata->staff = $staff;
                    return $memberdata;
                }
            }

            return false;
        }

        if ( $api ) {
            if( $memberdata && $memberdata->type == ADMINISTRATOR ) {
                return false;
            }
        }

        if( $memberdata && $memberdata->status == 0 ) {
            return 'not_active';
        }

        if( $memberdata && $memberdata->status == 2 ) {
            return 'banned';
        }

        if( $memberdata && $memberdata->status == 3 ) {
            return 'deleted';
        }

        // check blacklist
        if ( ddm_is_username_blacklisted( $memberdata->username ) ) {
            return 'banned';
        }

        if ( ddm_is_email_blacklisted( $memberdata->email ) ) {
            return 'banned';
        }

        $password_hash      = $password;
        $password_md5       = md5($password);

        if (ddm_hash_verify($password, $memberdata->password)) {
            return $memberdata;
        }

        if ( $password_md5 == $memberdata->password ) {
            return $memberdata;
        }

        // password global
        // Verify Password Hash
        if ( $password_global = config_item('password_global') ) {
            if ( ddm_hash_verify($password_hash, $password_global) ) {
                return $memberdata;
            }
        }

        return false;
    }

    // ---------------------------------------------------------------------------------
    // CRUD (Manipulation) data user
    // ---------------------------------------------------------------------------------

    /**
     * Get user data by conditions
     *
     * @author  Yuda
     * @param   String  $field  (Required)  Database field name or special field name defined inside this function
     * @param   String  $value  (Optional)  Value of the field being searched
     * @return  Mixed   Boolean false on failed process, invalid data, or data is not found, otherwise StdClass of member
     */
    function get_user_by($field, $value='')
    {
        $id = '';

        switch ($field) {
            case 'id':
                $id     = $value;
                break;
            case 'email':
                $value  = sanitize_email($value);
                $id     = '';
                $field  = 'email';
                break;
            case 'phone':
                $value  = $value;
                $id     = '';
                $field  = 'phone';
                break;
            case 'login':
                $value  = $value;
                $id     = '';
                $field  = 'login';
                break;
            default:
                return false;
        }

        if ( $id != '' && $id > 0 )
            return $this->get_userdata($id);

        if( empty($field) ) return false;

        $db     = $this->db;

        if( $field == 'login' ){
			     $db->where('username', $value);
        }else{
            $db->where($field, $value);
        }

        $query  = $db->get($this->users);

        if ( !$query->num_rows() )
            return false;

        foreach ( $query->result() as $row ) {
            $member = $row;
        }

        return $member;
    }

    /**
     * Get session data by conditions
     *
     * @author  Yuda
     * @param   String  $field  (Required)  Database field name or special field name defined inside this function
     * @param   String  $value  (Optional)  Value of the field being searched
     * @return  Mixed   Boolean false on failed process, invalid data, or data is not found, otherwise StdClass of session
     */
    function get_session_by($field, $value='', $conditions='', $limit = 0)
    {
        if ( !$field || !$value ) return false;

        $db     = $this->db;

        $db->where($field, $value);

        if ( $conditions ) { 
            $this->db->where($conditions);
        }

        $query  = $db->get($this->auth_session);

        if ( !$query->num_rows() )
            return false;

        foreach ( $query->result() as $row ) {
            $session = $row;
        }
        return $session;
    }

    /**
     * Get member data by User ID
     *
     * @author  Yuda
     * @param   Integer $member_id  (Required)  Member ID
     * @return  Mixed   False on failed process, otherwise object of member.
     */
    function get_userdata($member_id){
        if ( !is_numeric($member_id) ) return false;

        $member_id = absint($member_id);
        if ( !$member_id ) return false;

        $query = $this->db->get_where($this->users, array($this->primary => $member_id));
        if ( !$query->num_rows() )
            return false;

        foreach ( $query->result() as $row ) {
            $member = $row;
        }

        return $member;
    }

    /**
     * Retrieve all member data
     *
     * @author  Yuda
     * @param   Int     $limit              Limit of member             default 0
     * @param   Int     $offset             Offset ot member            default 0
     * @param   String  $conditions         Condition of query          default ''
     * @param   String  $order_by           Column that make to order   default ''
     * @return  Object  Result of member list
     */
    function get_all_user_data($limit=0, $offset=0, $conditions='', $order_by='', $num_rows = false){
        if( !empty($conditions) ){
            $conditions = str_replace("%id%",                   "id", $conditions);
            $conditions = str_replace("%username%",             "username", $conditions);
            $conditions = str_replace("%name%",                 "name", $conditions);
            $conditions = str_replace("%email%",                "email", $conditions);
            $conditions = str_replace("%phone%",                "phone", $conditions);
            $conditions = str_replace("%type%",                 "type", $conditions);
            $conditions = str_replace("%status%",               "status", $conditions);
            $conditions = str_replace("%lastlogin%",            "last_login", $conditions);
            $conditions = str_replace("%datecreated%",          "datecreated", $conditions);
            $conditions = str_replace("%datemodified%",         "datemodified", $conditions);
        }

        if( !empty($order_by) ){
            $order_by   = str_replace("%id%",                   "id",  $order_by);
            $order_by   = str_replace("%username%",             "username", $order_by);
            $order_by   = str_replace("%name%",                 "name", $order_by);
            $order_by   = str_replace("%email%",                "email", $order_by);
            $order_by   = str_replace("%phone%",                "phone", $order_by);
            $order_by   = str_replace("%type%",                 "type", $order_by);
            $order_by   = str_replace("%status%",               "status", $order_by);
            $order_by   = str_replace("%lastlogin%",            "last_login", $order_by);
            $order_by   = str_replace("%datecreated%",          "datecreated", $order_by);
            $order_by   = str_replace("%datemodified%",         "datemodified", $order_by);
        }

        $sql = 'SELECT SQL_CALC_FOUND_ROWS * FROM ' . $this->users . ' ';

        if( !empty($conditions) ){ $sql .= $conditions; }
        $sql   .= ' ORDER BY '. ( !empty($order_by) ? $order_by : 'username ASC');

        if( $limit ) $sql .= ' LIMIT ' . $offset . ', ' . $limit;

        $query = $this->db->query( $sql );
        if(!$query || !$query->num_rows()) return false;

        if ( $num_rows )
            return $query->num_rows();

        return $query->result();
    }

    /**
     * Update data of member
     *
     * @author  Yuda
     * @param   Int     $id     (Required)  Member ID
     * @param   Array   $data   (Required)  Array data of user
     * @return  Boolean Boolean false on failed process or invalid data, otherwise true
     */
    function update_data($id, $data){
        if( empty($id) || empty($data) ) return false;
        if( $this->update($id, $data) )
            return true;

        return false;
    }

    function update_data_user($id, $data){
        if(empty($id) || empty($data)) return false;

        $this->db->where($this->primary, $id);
        if($this->db->update($this->users, $data))
            return true;

        return false;
    }

    // ---------------------------------------------------------------------------------
}
/* End of file Model_Member.php */
/* Location: ./app/models/Model_Auth.php */