<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Model_backend extends CI_Model {

    /*
	|--------------------------------------------------------------------------
	| Save Data | Insert / Update | Global Uses
	|--------------------------------------------------------------------------
	*/
    function save_data($table, $data, $where = '')
    {

        $this->db->where($where);
        $q = $this->db->get($table);

        if ($q->num_rows() > 0) {
            $result = $this->db->where($where)->update($table, $data);
            return $result;
        } else {
            $result = $this->db->insert($table, $data);
            return $result;
        }
    }

    /*
	|--------------------------------------------------------------------------
    | Create Event Scheduler | Run One time only 
    | @ DONT FORGET to add in my.cnf : event_scheduler = ON
    | @ Show event scheduler ON/OFF , in mysql : select @@global.event_scheduler
	|--------------------------------------------------------------------------
	*/
    function createEventOnce($eventName, $date, $action)
    {
        $this->db->query("DROP EVENT IF EXISTS " . $eventName); // Drop event if exist
        $this->db->query("
        CREATE EVENT " . $eventName . "
        ON SCHEDULE AT '" . $date . "'
        DO
        " . $action);

        return TRUE;
    }

    /*
	|--------------------------------------------------------------------------
    | Create Event Scheduler | Run Every X TIMES
    | @ DONT FORGET to add in my.cnf : event_scheduler = ON
    | @ Show event scheduler ON/OFF, in mysql : select @@global.event_scheduler
	|--------------------------------------------------------------------------
	*/
    function createEventRecurring($time, $eventName, $date, $action)
    {
        $this->db->query("DROP EVENT IF EXISTS " . $eventName); // Drop event if exist
        $this->db->query("
        CREATE EVENT " . $eventName . "
        ON SCHEDULE 
        EVERY " . $time . "
        STARTS str_to_date( date_format(now(), '" . $date . "'), '%Y%m%d %H%i%s' ) + INTERVAL " . $time . "
        DO 
        " . $action);

        return TRUE;
    }

    // END OF FILE #################################################################################
}
