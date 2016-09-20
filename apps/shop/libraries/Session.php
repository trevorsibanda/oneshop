<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * ==========================================================
 * LIBRARY: CI_Session
 * ==========================================================
 *
 * Custom Session library which saves data into database, by overwriting native functionality.
 * NDB Session Class for Codeigniter.
 * 
 * @package       Codeigniter
 * @subpackage    Libraries
 * @category      Libraries
 * @since         Version 1.0
 * @version       2.12
 * @author        Denis Molan
 * @copyright     Copyright (c) 2011 - 2012, Denis Molan
 * @license       GPL 2.0 and LGPL 2.1
 *
 */
class CI_Session {

    var $sess_table_name = 'sessions';
    var $sess_expiration = 3600; // 1 H
    var $expire_id;
    var $expire_cookie;
    var $sess_expire_on_close = TRUE;
    var $sess_time_to_update = 600; // 10 min
    var $sess_cookie_name = 'wsys';
    var $cookie_prefix = '';
    var $cookie_path = '/';
    var $cookie_domain = '';
    var $cookie_secure = FALSE;
    var $flashdata_key = 'flash';
    var $user_ip;
    var $user_useragent;
    var $user_time;
    var $CI;

    /**
     * ==========================================================
     * FUNCTION: __construct
     * ==========================================================
     *
     * Constructor.
     *
     * @access   private
     * @return   void
     *
     */
    function __construct() {
        $this->CI = & get_instance();

        //Database class must be loaded and table name for session must be set
        if ($this->sess_table_name != '') {
            $this->CI->load->database();
        } else {
            show_error('In order to use the Session class you need to setup database class.');
        }

        // User agent class has to be loaded if not allready
        if (!class_exists('user_agent')) {
            $this->CI->load->library('user_agent');
        }

        //Loading config data
        $params = array('sess_table_name', 'sess_expiration', 'sess_expire_on_close', 'sess_cookie_name', 'cookie_path', 'cookie_domain', 'cookie_secure', 'sess_time_to_update', 'cookie_prefix');
        foreach ($params as $key) {
            $this->$key = (isset($params[$key])) ? $params[$key] : $this->CI->config->item($key);
        }

        // Set the cookie name
        $this->sess_cookie_name = $this->cookie_prefix . $this->sess_cookie_name;
        // Detecting user preferences which will need later
        $this->user_ip = $this->CI->input->ip_address();

        if ($this->CI->agent->is_browser()) {
            $this->user_useragent = $this->CI->agent->browser() . ' ' . $this->CI->agent->version();
        } elseif ($this->CI->agent->is_robot()) {
            $this->user_useragent = $this->CI->agent->robot();
        } elseif ($this->CI->agent->is_mobile()) {
            $this->user_useragent = $this->CI->agent->mobile();
        } else {
            $this->user_useragent = 'Unidentified User Agent';
        }
        // Set different expiration times
        $this->expire_cookie = ($this->sess_expire_on_close) ? 0 : time() + $this->sess_expiration;

        // Client side session cookie name which contains only session id (no need to encrypt it because allready by default  )
        session_name($this->sess_cookie_name);
        session_set_cookie_params($this->expire_cookie, $this->cookie_path, $this->cookie_domain, $this->cookie_secure, false);
        // Overwriting normal session functions with our own -> user data goes into database and not on to server
        session_set_save_handler(
                array(&$this, '_sess_open'), array(&$this, '_sess_close'), array(&$this, '_sess_read'), array(&$this, '_sess_write'), array(&$this, 'sess_destroy'), array(&$this, '_sess_gc')
        );
        register_shutdown_function('session_write_close');

        // Session starts session and generate or use session id (Client side cookie)  
        session_start();
        // Checking session for security and maintaining purpuse
        $this->_sess_check();
        // Flashdata manage
        $this->_flashdata_sweep();
        $this->_flashdata_mark();
        log_message('debug', "Session Class Initialized");
    }

    /**
     * ==========================================================
     * FUNCTION: __destruct
     * ==========================================================
     *
     * Destructor.
     *
     * @access   private
     * @return   void
     *
     */
    function __destruct() {
        session_write_close();
    }

    /**
     * ==========================================================
     * FUNCTION: _sess_open
     * ==========================================================
     *
     * We dont need as saving data into database via CI Database
     * Open is first called after session_start
     *
     * @access   private
     * @return   bool
     *
     */
    function _sess_open() {
        return true;
    }

    /**
     * ==========================================================
     * FUNCTION: _sess_close
     * ==========================================================
     *
     * Session closed will chech garbage.
     *
     * @access   private
     * @return   bool
     *
     */
    function _sess_close() {
        return true;
    }

    /**
     * ==========================================================
     * FUNCTION: _sess_read
     * ==========================================================
     *
     * Read is second called after session_start, using it for reading data from database. 
     * If session does not exist we must return '' and if it does we return data.
     *
     * @access   private
     * @param    string    session id auto given into function
     * @return   string
     *
     */
    function _sess_read($sess_id) {
        $this->CI->db->select('sess_data')->where('sess_id', $sess_id);
        $query = $this->CI->db->get($this->sess_table_name);
        return ($query->num_rows() == 1 && $query->row()->sess_data != '') ? $query->row()->sess_data : '';
    }

    /**
     * ==========================================================
     * FUNCTION: _sess_write
     * ==========================================================
     *
     * Updating user data in database
     *
     * @access   private
     * @param    string    session id auto given into function
     * @param    mixed     session user data
     * @return   bool
     *
     */
    function _sess_write($sess_id, $sess_data) {
        $this->CI->db->set('sess_date_activity', 'NOW()', FALSE);
        $this->CI->db->set('sess_date_expire', 'DATE_ADD(NOW(),INTERVAL ' . $this->sess_expiration . ' SECOND)', FALSE);
        $this->CI->db->where('sess_id', $sess_id);
        return $this->CI->db->update($this->sess_table_name, array('sess_data' => $sess_data));
    }

    /**
     * ==========================================================
     * FUNCTION: sess_destroy
     * ==========================================================
     *
     * Removing session cookie and database data.
     *
     * @access   public
     * @param    string    session id given automaticly or in CI not
     * @return   void
     *
     */
    function sess_destroy() {
        session_unset();
        $old_id = session_id();
        $this->CI->db->where('sess_id', $old_id);
        $status = $this->CI->db->delete($this->sess_table_name);
        if ($status != FALSE) {
            $new_id = $this->_sess_regenerate();
            $status = $this->_sess_create($new_id);
        }
        return $status;
    }

    /**
     * ==========================================================
     * FUNCTION: _sess_gc
     * ==========================================================
     *
     * Called manualy because of some issues and just returning
     * true.
     *
     * @access   private
     * @return   bool
     *
     */
    function _sess_gc() {
        return true;
    }

    /**
     * ==========================================================
     * FUNCTION: _sess_create
     * ==========================================================
     *
     * Creating new session.
     *
     * @access   private
     * @param    string    session id
     * @return   void
     *
     */
    function _sess_create($sess_id) {
        $this->CI->db->set('sess_date_activity', 'NOW()', FALSE);
        $this->CI->db->set('sess_date_expire', 'DATE_ADD(NOW(),INTERVAL ' . $this->sess_expiration . ' SECOND)', FALSE);
        $this->CI->db->set('sess_date_expire_id', 'DATE_ADD(NOW(),INTERVAL ' . $this->sess_time_to_update . ' SECOND)', FALSE);
        $data = array(
            'sess_id' => $sess_id,
            'sess_ip' => $this->user_ip,
            'sess_user_agent' => $this->user_useragent,
            'sess_data' => ''
        );
        return $this->CI->db->insert($this->sess_table_name, $data);
    }

    /**
     * ==========================================================
     * FUNCTION: _sess_check
     * ==========================================================
     *
     * Garbage collection
     * Checking session if exists if not we create one.
     * Checking session IP-s and browser to prevent hijecking.
     * Updating session id after X min specified by user it self to prevent fixetion.
     *
     * @access   private
     * @return   void
     *
     */
    function _sess_check() {
        $sess_id = session_id();

        // Garbage collection
        $this->CI->db->where('sess_date_expire < NOW()', NULL, FALSE);
        $this->CI->db->delete($this->sess_table_name);

        // Checking if session exist for current user else we create one
        $this->CI->db->where('sess_user_agent', $this->user_useragent)->where('sess_ip', $this->user_ip)->where('sess_id', $sess_id);
        if ($this->CI->db->count_all_results($this->sess_table_name) == 0) {
            // Extra check if un-expected error accured and old session wasn't properly removed, IP and useragent same but ID different
            $this->CI->db->where('sess_user_agent', $this->user_useragent)->where('sess_ip', $this->user_ip)->where('sess_id !=', $sess_id)->delete($this->sess_table_name);
            $this->_sess_create($sess_id);
            return;
        }

        // Checking for session hiject by user IP and browser
        $this->CI->db->where('sess_user_agent !=', $this->user_useragent)->where('sess_ip !=', $this->user_ip)->where('sess_id', $sess_id);
        if ($this->CI->db->count_all_results($this->sess_table_name) > 0) {
            $this->sess_destroy();
            return;
        }

        // Checking if we need to update session id and update it
        $this->CI->db->start_cache();
        $this->CI->db->where('sess_user_agent', $this->user_useragent)->where('sess_ip', $this->user_ip)->where('sess_id', $sess_id);
        $this->CI->db->where('sess_date_expire_id < NOW()', NULL, FALSE);
        $this->CI->db->stop_cache();

        if ($this->CI->db->count_all_results($this->sess_table_name) == 1) {
            $new_id = $this->_sess_regenerate();
            $this->CI->db->set('sess_date_expire_id', 'DATE_ADD(NOW(),INTERVAL ' . $this->sess_time_to_update . ' SECOND)', FALSE);
            $this->CI->db->update($this->sess_table_name, array('sess_id' => $new_id));
        }
        $this->CI->db->flush_cache();
    }

    /**
     * ==========================================================
     * FUNCTION: _sess_regenerate
     * ==========================================================
     *
     * Change session id and return new one.
     *
     * @access   private
     * @param    bool
     * @return   string
     *
     */
    function _sess_regenerate() {
        session_regenerate_id(FALSE);
        return session_id();
    }

    /**
     * ==========================================================
     * FUNCTION: users_online
     * ==========================================================
     *
     * How many users are online checking last activity diff. and
     * unique IP.
     *
     * @access   public
     * @param    int
     * @return   int
     *
     */
    function users_online($time = 300) {
        $this->CI->db->where('sess_date_activity >= DATE_ADD(NOW(),INTERVAL -' . $time . ' SECOND)', NULL, FALSE);
        $this->CI->db->where('sess_date_activity <= NOW()', NULL, FALSE);
        $this->CI->db->group_by('sess_ip');
        return $this->CI->db->get($this->sess_table_name)->num_rows();
    }

    /**
     * ==========================================================
     * FUNCTION: parse
     * ==========================================================
     * 
     * Parsing session data from database and assign to array keys and values
     * 
     * @access    private
     * @param     string
     * @return    array
     * 
     */
    function parse($data) {
        $exploded = explode(";", $data);
        $sess_data = array();
        for ($i = 0; $i < count($exploded) - 1; $i++) {
            $sess_get = explode("|", $exploded[$i]);
            $sess_key = $sess_get[0];
            if (substr($sess_get[1], 0, 2) == "s:") {
                $sess_data[$sess_key] = str_replace("\"", "", strstr($sess_get[1], "\""));
            } else {
                $sess_data[$sess_key] = substr($sess_get[1], 2);
            }
        }
        return $sess_data;
    }

    /**
     * ==========================================================
     * FUNCTIONS: CI_Session modified functions
     * ==========================================================
     *
     */
    function all_userdata() {
        return $_SESSION;
    }

    function userdata($item) {
        return (!isset($_SESSION[$item])) ? FALSE : $_SESSION[$item];
    }

    function set_userdata($newdata = array(), $newval = '') {
        if (is_string($newdata)) {
            $newdata = array($newdata => $newval);
        }

        if (count($newdata) > 0) {
            foreach ($newdata as $key => $val) {
                $_SESSION[$key] = $val;
            }
        }
    }

    function unset_userdata($newdata = array()) {
        if (is_string($newdata)) {
            $newdata = array($newdata => '');
        }

        if (count($newdata) > 0) {
            foreach ($newdata as $key => $val) {
                unset($_SESSION[$key]);
            }
        }
    }

    function _flashdata_mark() {
        foreach ($_SESSION as $name => $value) {
            $parts = explode(':new:', $name);
            if (is_array($parts) && count($parts) === 2) {
                $new_name = $this->flashdata_key . ':old:' . $parts[1];
                $this->set_userdata($new_name, $value);
                $this->unset_userdata($name);
            }
        }
    }

    function _flashdata_sweep() {
        foreach ($_SESSION as $key => $value) {
            if (strpos($key, ':old:')) {
                $this->unset_userdata($key);
            }
        }
    }

    /**
     * ==========================================================
     * FUNCTIONS: CI_Session functions not modified
     * ==========================================================
     *
     */
    function set_flashdata($newdata = array(), $newval = '') {
        if (is_string($newdata)) {
            $newdata = array($newdata => $newval);
        }

        if (count($newdata) > 0) {
            foreach ($newdata as $key => $val) {
                $flashdata_key = $this->flashdata_key . ':new:' . $key;
                $this->set_userdata($flashdata_key, $val);
            }
        }
    }

    function keep_flashdata($key) {
        $old_flashdata_key = $this->flashdata_key . ':old:' . $key;
        $value = $this->userdata($old_flashdata_key);

        $new_flashdata_key = $this->flashdata_key . ':new:' . $key;
        $this->set_userdata($new_flashdata_key, $value);
    }

    function flashdata($key) {
        $flashdata_key = $this->flashdata_key . ':old:' . $key;
        return $this->userdata($flashdata_key);
    }

    /**
     * ==========================================================
     * END
     * ==========================================================
     *
     */
}