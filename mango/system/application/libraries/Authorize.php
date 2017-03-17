<?php
Class Authorize {

    var $ci;

    function Authorize () {
        date_default_timezone_set('America/Los_Angeles');
        $this->ci =& get_instance();
    }
    /**
     * Checks to make sure that the user is logged in correctly.
     * if $userdata['login'] is not true, then it should destroy
     * the session and kick the user out. Uses logOutUser() to do so.
     *
     */
    function isLoggedIn() {
        $this->ci->load->helper('url');
        $this->ci->load->library('session');
        if (!$this->ci->session->userdata('login')) {
            $this->logOutUser();
        }
        return false; //bool
    } //end isLoggedIn();


    public function isAdmin() {
        $session_data = $this->getSessionData();
        $bool = false;
        if($session_data['user_type'] == 9) {
            $bool = true;
        }
        return $bool;
    }

    /**
     * Saves the last URL that the user was viewing.
     *
     */
    function saveLastURL() {
        $url = uri_string();
        $this->setLastURL($this->ci->session->userdata('user_id'), $url);
    } //end saveLastURL();

    /**
     * Returns an array with the user session data
     *
     * @return [array] = user data;
     */
    function getSessionData($id = null) {
        $data = array();
        if($id != null) {
            //pull from database
            $this->load->database();
            $this->db->from('user_session');
            $this->db->where('user_id', $id);

            $query = $this->db->get();
            if($query->num_rows() > 0) {
                foreach($query->result_array() as $row) {
                    $data[] = $row;
                }
            }
        }
        else {
            //pull from session
            $this->ci->load->library('session');

            $data['login'] = $this->ci->session->userdata('login');
            $data['user_id'] = $this->ci->session->userdata('user_id');
            $data['user_name'] = $this->ci->session->userdata('user_name');
            $data['user_type'] = $this->ci->session->userdata('user_type');
            $data['ip_address'] = $this->ci->session->userdata('ip_address');
        }

        return $data;
    }

    function setSessionData($session) {
        $this->ci->load->database();
        $this->ci->load->library('session');
        $this->ci->session->set_userdata($session);
        if(sizeof($this->testDatabaseSession($session['user_id'])) > 0) {
            //update existing session records
            $fields = array();
            $keys = array_keys($session);
            foreach($keys as $key) {
                $fields['user_id'] = $session['user_id'];
                $fields['session_name'] = $key;
                $fields['session_value'] = $session[$key];
                $fields['session_time'] = date('Y-m-d H:i:s');
                $this->ci->db->where('session_name', $key);
                $this->ci->db->update('user_session', $fields);
                $fields = array();
            }
        }
        else {
            //insert new session records
            $fields = array();
            $keys = array_keys($session);
            foreach($keys as $key) {
                $fields['user_id'] = $session['user_id'];
                $fields['session_name'] = $key;
                $fields['session_value'] = $session[$key];
                $this->ci->db->insert('user_session', $fields);
                $fields = array();
            }
        }
        return false;
    }

    private function testDatabaseSession($id) {
        $this->ci->load->database();
        $this->ci->db->from('user_session');
        $this->ci->db->where('user_id', $id);
        $query = $this->ci->db->get();
        $data = array();
        if($query->num_rows() > 0) {
            foreach($query->result_array() as $row) {
                $data[] = $row;
            }
        }
        return $data;
    }


    private function setLastURL($id, $url) {
        $this->ci->load->database();
        $this->ci->db->from('user_session');
        $this->ci->db->where('session_name', 'last_url');
        $this->ci->db->where('user_id', $id);
        $query = $this->ci->db->get();
        if($query->num_rows() > 0) {
            //row found, update current row
            $row = $query->row();
            $fields = array();
            $fields['session_value'] = $url;
            $fields['session_time'] = date('Y-m-d H:i:s');
            $this->ci->db->where('session_id', $row->session_id);
            $this->ci->db->update('user_session', $fields);
        }
        else {
            //no value found, insert new value
            $fields = array();
            $fields['user_id'] = $id;
            $fields['session_name'] = 'last_url';
            $fields['session_value'] = $url;
            $fields['session_time'] = date('Y-m-d H:i:s');

            $this->ci->db->insert('user_session', $fields);
        }

    }

    function getLastURL($id) {
        $this->ci->load->database();
        $url = false;

        $this->ci->db->from('user_session');
        $this->ci->db->where('user_id', $id);
        $this->ci->db->where('session_name', 'last_url');
        $query = $this->ci->db->get();
        if($query->num_rows() > 0) {
            $row = $query->row_array();
            $url = $row['session_value'];
        }
        return $url;
    } //end getLastURL();

    /**
     * Logs out the user by destorying the session
     * and then redirects the user to the login page.
     *
     */
    function logOutUser() {
        $this->ci->load->library('session');

        $this->ci->session->sess_destroy(); //destory the session
        redirect('login');
    }
}
?>