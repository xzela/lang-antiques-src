<?php
/**
 * Login Model helps a user login!
 * 
 * 
 * @author user
 *
 */
class Login_model extends Model {
	
	function Login_model () {
		parent::Model();
		$this->load->database();
	}
	
	/**
	 * Checks the users creditionals and logs them in if they are lucky...
	 * 
	 * @param [string] $username = login name
	 * @param [string] $password = password
	 * 
	 * @return [int] = id of user
	 */
	public function login($username, $password) {
		$id = null;
		$this->db->select('user_id');
		$this->db->from('users');
		$this->db->where('login_name', $username);
		$this->db->where('password', $password);
		$this->db->where('active', 1); //check for active/deactive
		$this->db->limit(1);
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			$row = $query->row();
			return $row->user_id;
		}
		return $id; //int
	} //end login
	
	/**
	 * Returns all of the information about the user
	 * 
	 * @param [int] $user_id = user_id
	 * @return [array] = data of user
	 */
	public function getUserData($user_id) {
		$this->db->from('users');
		$this->db->where('user_id', $user_id);
		$query = $this->db->get();
		$data = array();
		
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row;
			}
		}
		return $data;		
	} //end getUserData();
	
	/**
	 * Updates and returns the users ip address
	 *
	 * @param [int] $id = user id
	 * 
	 * @return [string] "127.0.0.1";
	 */
	function updateUserIPAddress($id) {
		$ip = $_SERVER['REMOTE_ADDR'];
		$data = array('ip_address' => $ip);
		
		$this->db->where('user_id', $id);
		$this->db->update('users', $data);
		return $ip; //string
	} //end updateUserIPAddress()

} //end Login_model
?>