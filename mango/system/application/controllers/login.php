<?php

class Login extends Controller {

	function __construct() {
		parent::Controller();
		
	}
	
	/**
	 * Helps the user login to Project Mango
	 *
	 * @return null
	 */
	function index() {
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('session');
		
		$this->form_validation->set_rules('username', 'User Name', 'trim|required');		
		$this->form_validation->set_rules('password', 'Password', 'required');
		
		if ($this->form_validation->run() == FALSE) {
			$data['message'] = ''; //empty string for message
			$this->load->view('login_view', $data);
		}
		else {
			
			$this->load->model('utils/login/login_model');
			
			$username = $this->input->post('username');
			$password = sha1($this->input->post('password')); //sha1 the password
			
			$user_id = $this->login_model->login($username, $password); //get the user id
			if($user_id != null) {
				$user_data = $this->login_model->getUserData($user_id);
				$this->load->library('authorize');
				$ip_address = $this->login_model->updateUserIPAddress($user_id);
				
				//$data['stuff'] = $user_data['login_name'];
				$data['login'] = TRUE;
				$data['user_id'] = $user_data['user_id'];
				$data['user_name'] = $user_data['first_name'] . ' ' . $user_data['last_name'];
				$data['user_type'] = $user_data['user_type'];
				$data['ip_address'] = $ip_address;
				$data['search_data'] = '';
				$this->authorize->setSessionData($data);
				$url = $this->authorize->getLastURL($data['user_id']); 
				if($url != false) {
					redirect($url, 'refresh');
				}
				else {
					redirect('main', 'refresh');	
				}
				
			}
			else {
				//send back bad username/password combo message
				$subject = "Mango Access attempt was made from: " . $_SERVER['REMOTE_ADDR'] . " on " . date('Y/m/d');
				$text_message = "Someone was denided access to Mango!<br />"
					. "IP: " . $_SERVER['REMOTE_ADDR'] . " <br />"
					. "Username: " . $this->input->post('username') . "<br />"
					. "Password: " . $this->input->post('password') . " <br />";
					
				$headers = "From: alert@mango.langantiques.com\nContent-Type: text/html; charset=iso-8859-1";
				@mail("zeph@langantiques.com", $subject, $text_message, $headers);
				$data['message'] = '<div class="error">Incorrect username and password combination</div>';
				$this->load->view('login_view', $data);
			}
		}
	}
	
	/**
	 * Makes the user logout of the system
	 *
	 * @return null
	 */
	function logout () {
		$this->load->helper('form');
		$this->load->library('authorize');
	
		$this->authorize->logOutUser();	
	}	
}

/* End of file login.php */
/* Location: ./system/application/controllers/login.php */
?>