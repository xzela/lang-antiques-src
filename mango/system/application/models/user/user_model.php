<?php
Class User_model extends Model {
	
	function __construct() {
		parent::Model();
		
		$this->load->database();
		$this->ci =& get_instance(); //add comment explaining this stuff;
	}
	
	/**
	 * inserts a new user into the database
	 * See ./system/application/controllers/admin.php:new_user() for more details
	 * 
	 * @param [array] $user = user information
	 * 
	 * @return null
	 */	
	function add_user($user) {
		$this->db->insert('users', $user);
	} //end add_user();
	
	/**
	 * Checks to see if a username already exits 
	 * @param $string
	 */
	function checkLoginNames($string) {
		$b = false;
		$this->db->from('users');
		$this->db->where('login_name', $string);
		$query = $this->db->get();
		if($query->num_rows() <= 0) {
			$b = true;
		}
		return $b;
	}
	
	/**
	 * Creates a login name for the user. Based on other users with the same name
	 * 
	 * @param [string] $name first name of the user
	 * 
	 * @return [string] new login name
	 */	
	function createLoginName($name) {
		$name = strtolower($name);
		
		$this->db->from('users');
		$this->db->where('first_name', $name);
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			$name = $name . $query->num_rows(); 
		}
		return $name;		
	}
	
	/**
	 * This deactivates the given user
	 * @todo Add return value to this
	 * 	- return FALSE if fail
	 * 	- return TRUE if success
	 * 
	 * @param [int] $id = user id, the one to deactivate
	 * 
	 * @return null
	 */
	function deactivateUser($id) {
		$data = array('active' => 0);
		$this->db->where('user_id', $id);
		$this->db->update('users', $data);		
	}
	
	/**
	 * returns all active users
	 * 
	 * @return [array] list of users
	 */	
	function getActiveUsers() {
		$this->db->from('users');
		$this->db->where('active', '1');
		$this->db->order_by('last_name', 'asc');
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$data[$row['user_id']] = $row;
			}
			$data[0] = array('user_id' => 0, 
					'login_name' => 'unknown',
					'first_name' => 'unknown',
					'last_name' => 'unknown',
					'active' => '0',
					'email' => 'unknown',
					'password' => 'unknown',
					'user_type' => 1); //this is a total hack, find better way to do this
					//If the user_id is known it uses this, total hack
		}
		return $data;
	}
	
	/**
	 * Returns all users
	 * 
	 * @return array
	 */
	function getAllUsers() {
		$this->db->from('users');
		$this->db->order_by('last_name', 'asc');
		$query = $this->db->get();
		$data = array();
		if($query->num_rows > 0) {
			foreach($query->result_array() as $row) {
				$data[$row['user_id']] = $row; 
			}
			$data[0] = array('user_id' => 0, 
					'login_name' => 'unknown',
					'first_name' => 'unknown',
					'last_name' => 'unknown',
					'email' => 'unknown',
					'password' => 'unknown',
					'user_type' => 1);
			
		}
		
		return $data;
	}
	
	/**
	 * This returns the signature of the user (if any)
	 * 
	 * @param [int] $id = user id 
	 * 
	 * @return [array] = signature data
	 */
	function getCurrentSignature($id) {
		$this->db->from('users_signature');
		$this->db->where('user_id', $id);
		
		$query = $this->db->get();
		
		$signature = array();
		
		if($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$row['has_signature'] = true;
				$signature = $row;
			}
		}
		else {
			$signature['has_signature'] = false;
		}
		
		return $signature;
	}

	/**
	 * Returns all deactive users 
	 * 
	 * @return [array] list of users
	 */	
	function getDeactiveUsers() {
		$this->db->from('users');
		$this->db->where('active', '0');
		
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$data[$row['user_id']] = $row;
			}
		}
		return $data;
	}
	
	/**
	 * This returns all of the known user information 
	 * 
	 * @param [int] $id = user id;
	 * 
	 * @return [array] = user data
	 */
	function getUserData($id) {
		$this->db->from('users');
		$this->db->where('user_id', $id);
		
		$query = $this->db->get();
		
		$user_info = array();
		
		if($query->num_rows() > 0) { 
			foreach ($query->result_array() as $row) {
				$user_info = $row;
			}
		}
		return $user_info;
	}





	

	
	function getUserTypes() {
		$data = array();
		
		$this->db->from('user_privs');
		$query = $this->db->get();
		if($query->num_rows > 0) {
			foreach($query->result_array() as $row) {
				$data[$row['priv_id']] = $row['priv_name'];
			}
		}
		return $data;
	}
	
	/**
	 * Returns the firts and last name of the user
	 * in a string form '<first> <last>';
	 * that is all...
	 *
	 * @param [int] $id = user id
	 * @return [string] = '<first> <last>'
	 */
	function getUserName($id) {
		$this->db->from('users');
		$this->db->where('user_id', $id);
		
		$name = '';
		$query = $this->db->get();
		if($query->num_rows > 0) {
			foreach($query->result_array() as $row) {
				$name = $row['first_name'] . ' ' . $row['last_name'];
			}
		}
		return $name;
	}
	

	/**
	 * This updates the short and log credentails of the user
	 * @todo Add return value to this
	 * 	- return FALSE if fail
	 * 	- return TRUE if success
	 * 
	 * @param [int] $id = user id
	 * @param [string] $short = stort string for credentails
	 * @param [string] $long = long stirng for credentails
	 * 
	 * @return null
	 */
	function saveCredentials($id, $short, $long) {
		$data = array('short_creds' => $short, 'long_creds' => $long);
		$this->db->where('user_id', $id);
		$this->db->update('users', $data);
	}
	
	/**
	 * This saves the email change of the user
	 * 
	 * @param [int] $id = user id
	 * @param [string] $password = user password
	 * @param [string] $email = email address of user
	 * 
	 * @return bool
	 */
	function saveEmailChange($id, $password, $email) {
		$password = sha1($password);
		$this->db->select('user_id');
		$this->db->from('users');
		$this->db->where('user_id', $id);
		$this->db->where('password', $password);
		$this->db->limit(1);
		
		$query = $this->db->get();
		
		if($query->num_rows() == 0) {
			return FALSE; //error, couldn't find id/password combonation
		}
		else {
			$data = array('email' => $email);
			$this->db->where('user_id', $id);
			$this->db->where('password', $password);
			$this->db->update('users', $data);
			
			return TRUE;
		}		
	}
	/**
	 * This saves the name change of the user
	 *
	 * @param [int] $id
	 * @param [string] $password
	 * @param [string] $first
	 * @param [string] $last
	 * 
	 * @return bool
	 */
	function saveNameChange($id, $password, $first, $last) {
		$password = sha1($password);
		$this->db->select('user_id');
		$this->db->from('users');
		$this->db->where('user_id', $id);
		$this->db->where('password', $password);
		$this->db->limit(1);
		
		$query = $this->db->get();
		
		if($query->num_rows() == 0) {
			return FALSE; //error, couldn't find id/password combonation
		}
		else {
			$data = array('first_name' => $first, 'last_name' => $last);
			$this->db->where('user_id', $id);
			$this->db->where('password', $password);
			$this->db->update('users', $data);
			
			return TRUE;
		}
	}

	/**
	 * This saves the password change of the user
	 * 
	 * @param [int] $id = user id
	 * @param [string] $old_password = old user password
	 * @param [string] $new_password = new user password
	 * 
	 * @return bool
	 */		
	function savePasswordChange($id, $old_password, $new_password) {
		$old_password = sha1($old_password);
		$new_password = sha1($new_password);
		
		$this->db->select('user_id');
		$this->db->from('users');
		$this->db->where('user_id', $id);
		$this->db->where('password', $old_password);
		$this->db->limit(1);

		$query = $this->db->get();
		if($query->num_rows() == 0) {
			return FALSE; //error, couldn't find id/password combonation
		}
		else {
			$data = array('password' => $new_password);
			$this->db->where('user_id', $id);
			$this->db->where('password', $old_password);
			$this->db->update('users', $data);
			return TRUE;	
		}		
	}
	
	/**
	 * This reactivates the given user
	 * @todo Add return value to this
	 * 	- return FALSE if fail
	 * 	- return TRUE if success
	 * 
	 * @param [int] id = user id, the one to deactivate
	 * 
	 * @return null
	 */
	function reactivateUser($id) {
		$data = array('active' => 1);
		$this->db->where('user_id', $id);
		$this->db->update('users', $data);		
	}
	
	/**
	 * Resets the user password to 'password'
	 *
	 * @param [int] $user_id = user id
	 * @param [int] $admin_id = admin id
	 * @param [string] $password = password of the admin
	 * 
	 * @return [int] = success/failure 
	 */
	function resetPassword($user_id, $admin_id, $password) {
		$status = 0;
		$password = sha1($password);
		$new_password = sha1('password');
		
		$this->db->from('users');
		$this->db->where('user_id', $admin_id);
		$this->db->where('password', $password);
		
		$query = $this->db->get();
		
		if($query->num_rows() == 1) {
			$data = array('password' => $new_password);
			$this->db->where('user_id', $user_id);
			$this->db->update('users', $data);
			
			$status = 1; //success
		}
		else {
			$status = 0; //bad admin password
		}
		return $status;
	}
	
	/**
	 * Updates the users privileges based on ID
	 *
	 * @param [int] $user_id = user id
	 * @param [int] $priv = privilege number (0-9)
	 * 
	 * @return null;
	 */
	function updateUserPrivileges($user_id, $priv) {
		$data = array('user_type' => $priv);
		$this->db->where('user_id', $user_id);
		$this->db->update('users', $data);
		
	}
	
	/**
	 * Uploads the image of the users signature
	 * @todo Add return value to this
	 * 	- return FALSE if fail
	 * 	- return TRUE if success
	 * 
	 * @param [int] $id = user id
	 * @param [array] $image = image array, holds all data on image, sans image content		 *
	 * 
	 * @return null 
	 */
	function uploadSignature($id, $image) {
		$data = array('image_name' => $image['image_name'], 
				'image_size' => $image['image_size'],
				'image_location' => $image['image_location'],
				'image_type' => $image['image_type']);
		
		$test = $this->getCurrentSignature($id);
		
		$this->db->select('user_id');
		$this->db->from('users_signature');
		$this->db->where('user_id', $id);
		
		$query = $this->db->get();
		if(!$test['has_signature']) {
			//signature has not been uploaded yet.
			$data['user_id'] = $id;
			$this->db->insert('users_signature', $data);
		}
		else {
			//signature already exsists, replace it.
			$this->db->where('user_id', $id);
			$this->db->update('users_signature', $data);
		}
	}
}
?>