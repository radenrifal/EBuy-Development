<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Staff Class
 *
 * @subpackage	Libraries
 */
class DDM_Staff extends CI_Session {
	
	/**
	 * 
	 */
	public $CI;
	
	/**
	 * 
	 */
	private $data;
	
	/**
	 * 
	 */
	private $role;
	
	/**
	 * 
	 */
	private $allowed_access;
	
	/**
	 * 
	 */
	private $restricted_access;
	
	/**
	 * 
	 */
	private $access_text;
	
	/**
	 * 
	 */
	protected static $DASHBOARD 		= array( 'backend/index' );
	protected static $CHANGEPPROFILE 	= array( 'member/profile', 'member/personalinfo', 'member/changepassword' );
	protected static $NEWS 				= array( 'backend/get_all_news' );
	protected static $VIEW_NEWS			= array( 'backend/view_news' );
	protected static $INBOX				= array( 'backend/inbox', 'backend/ticket_view' );
	protected static $LOGOUT 			= array( 'member/logout' );

	// PIN
	protected static $PIN_CRUD 			= array( 'pin/pingenerate', 'pin/pingenerateact', 'pin/pinorderconfirm', 'pin/pinorderedit', 'pin/pinordereditact', 'pin/pinorderdelete');
	protected static $PIN_LIST 			= array( 'pin/pintransferhistorylist', 'pin/pintransferhistorylistdata', 'pin/pinlist', 'pin/pinlistdata', 'pin/pinorderlistdata', 'pin/listused', 'pin/pindata' );
	
	// MEMBER
	protected static $MEMBER_CRUD 		= array( 'member/membernew', 'member/searchuplinetree', 'member/cloning', 'member/searchupline', 'member/searchsponsor', 'member/selectprovince', 'member/selectcities', 'member/memberconfirm' );
	protected static $MEMBER_LIST 		= array( 'member/memberlists', 'member/memberlistsdata' );
	protected static $MEMBER_GEN 		= array( 'member/generationtree', 'member/generationtree_loadmore' );
	protected static $MEMBER_TREE 		= array( 'member/tree', 'member/searchtree' );
	protected static $MEMBER_UPDATE		= array( 'member/asstockist', 'member/asmember', 'member/asbanned', 'member/asactive', 'backend/assume', 'backend/revert');
	protected static $VIEW_PROFILE 		= array( 'member/profile' );
	protected static $EDIT_PROFILE 		= array( 'member/profile', 'member/personalinfo', 'member/changepassword' );
	
	// DEPOSITE
	protected static $DEPOSITE 			= array( 'backend/deposite', 'backend/depositelist', 'backend/bonuslistminedata', 'backend/bonuspblistminedata', 'backend/withdrawlistminedata' );

	// REPORT
	protected static $REPORT_LIST 		= array( 'stats/registrationreport', 'stats/registrationdata', 'member/memberconfirm', 'stats/incomestatement', 'backend/memberlistdata', 'backend/load_income', 'backend/bonus', 'backend/bonuslistdata', 'backend/bonuslistminedata', 'stats/recapcommission', 'stats/recaproyalty', 'backend/upgradehistory', 'backend/upgradehistorylist', 'backend/upgradehistorylist' );
	
	// WITHDRAW
	protected static $WITHDRAW 			= array( 'backend/withdraw', 'backend/withdrawallist', 'backend/withdrawaltransfer', 'backend/withdrawaltransferall' );

	// SETTING	
	protected static $SETTING			= array( 'backend/general', 'backend/updatesetting' );
	protected static $STAFF 			= array( 'staff/manage', 'staff/managelist', 'staff/add', 'staff/edit', 'staff/del' );
	protected static $RUNNING_TEXT 		= array( 'backend/setting_runningtext', 'backend/updatesetting' );
	protected static $SETTING_NEWS  	= array( 'backend/setting_news', 'backend/get_all_news', 'backend/get_news_by_id', 'backend/get_news_img_by_id', 'backend/edit_news' );
	protected static $IMG_SLIDESHOW 	= array( 'backend/setting_slideshow', 'backend/image_uploader', 'backend/updatesetting' );
	protected static $SOCIAL_MEDIA 		= array( 'backend/setting_social_media', 'backend/updatesetting' );

    
    /**
     * Session Constructor
     *
     * The constructor runs the session routines automatically
     * whenever the class is instantiated.
     */
    public function __construct( $params = array() ) {
        $this->CI =& get_instance();
		$this->allowed_access = array();
		$this->restricted_access = array();
    }

	// --------------------------------------------------------------------
	
	public function staff( $id_staff ) {
		if ( empty( $id_staff ) )
			return false;
		
		if ( ! $staff = $this->CI->Model_Staff->get( $id_staff ) )
			return false;
		
		$this->data = $staff;
		
		// set role
		$this->_set_role();
		
		// return staff object
		return $this->data;
	}

	// --------------------------------------------------------------------
	
	public function has_access() {
		$path = $this->_get_current_path();
		
		if ( $this->data->access == 'all' ) {
			if ( in_array( $path, $this->restricted_access ) )
				return false;
			
			return true;
		}
		
		// partial access
		if ( in_array( $path, $this->allowed_access ) )
			return true;
		
		return false;
	}

	// --------------------------------------------------------------------
	
	public function get_access_text() {
		return $this->access_text;
	} 

	// --------------------------------------------------------------------
	
	protected function _set_role() {
		$this->role = array();
		$this->access_text = array();
		
		if ( is_array( $this->data->role ) )
			$this->role = $this->data->role;
		
		$config_access_text = config_item( 'staff_access_text' );
		
		$this->_add_allowed_access( self::$DASHBOARD );
		$this->_add_allowed_access( self::$CHANGEPPROFILE );
		$this->_add_allowed_access( self::$NEWS );
		$this->_add_allowed_access( self::$VIEW_NEWS );
		$this->_add_allowed_access( self::$INBOX );
		$this->_add_allowed_access( self::$LOGOUT );
		
		foreach ( $this->role as $role ) {
			$this->access_text[] = $config_access_text[ $role ];
			switch ( $role ) {
				case STAFF_ACCESS1:
					$this->_add_allowed_access( self::$PIN_CRUD );
					break;
				case STAFF_ACCESS2:
					$this->_add_allowed_access( self::$PIN_LIST );
				case STAFF_ACCESS3:
					$this->_add_allowed_access( self::$MEMBER_GEN );
					$this->_add_allowed_access( self::$MEMBER_TREE );
					$this->_add_allowed_access( self::$VIEW_PROFILE );
					break;
				case STAFF_ACCESS4:
					$this->_add_allowed_access( self::$MEMBER_CRUD );
					$this->_add_allowed_access( self::$MEMBER_LIST );
					$this->_add_allowed_access( self::$VIEW_PROFILE );
					$this->_add_allowed_access( self::$EDIT_PROFILE );
					$this->_add_allowed_access( self::$MEMBER_UPDATE );
					break;
				case STAFF_ACCESS5:
					$this->_add_allowed_access( self::$DEPOSITE );
					break;
				case STAFF_ACCESS6:
					$this->_add_allowed_access( self::$REPORT_LIST );
					break;
				case STAFF_ACCESS7:
					$this->_add_allowed_access( self::$WITHDRAW );
					break;
				case STAFF_ACCESS8:
					$this->_add_allowed_access( self::$SETTING );
					$this->_add_allowed_access( self::$RUNNING_TEXT );
					break;
				case STAFF_ACCESS9:
					$this->_add_allowed_access( self::$STAFF );
					break;
			}
		}
		
		if ( $this->data->access == 'all' ) {
			$this->access_text = array( 'Semua Fitur' );
			
			foreach ( array( STAFF_ACCESS9 ) as $role ) {
				if ( empty( $this->role ) || ! in_array( $role, $this->role ) ) {
					$this->access_text[] = 'Tidak bisa akses ' . $config_access_text[ $role ];
					switch( $role ) {
						case STAFF_ACCESS9:
							$this->_add_restricted_access( self::$STAFF );
							break;
					}
				}
			}
			
		}
	}

	// --------------------------------------------------------------------
	
	protected function _get_current_path() {
		$controller = $this->CI->router->fetch_class();
		$method = $this->CI->router->fetch_method();
		return $controller . '/' . $method;
	}

	// --------------------------------------------------------------------
	
	protected function _add_allowed_access( $access ) {
		$this->_add_access( $access, $this->allowed_access );
	}

	// --------------------------------------------------------------------
	
	protected function _add_restricted_access( $access ) {
		$this->_add_access( $access, $this->restricted_access );
	}

	// --------------------------------------------------------------------
	
	protected function _add_access( $access, &$to ) {
		if ( is_string( $access ) ) {
			$to[] = $access;
			$to = array_unique( $to );
			return;
		}
		
		$to = array_merge( $to, $access );
		$to = array_unique( $to );
	}

	// --------------------------------------------------------------------
}
// END Session Class