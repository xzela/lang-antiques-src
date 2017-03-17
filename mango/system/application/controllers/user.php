<?php
class User extends Controller {
	
	function __construct() {
		parent::Controller();
		$this->load->library('authorize');
		
		$this->load->model('user/user_model');
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$this->authorize->isLoggedIn();	
		
	}
	
	function index() {
		$this->authorize->saveLastURL(); //saves the url
		
		$data['user_data'] = $this->authorize->getSessionData();
		$this->load->view('user/user_view', $data);
	}
	
	function change_credentials() {
		$data['user_data'] = $this->authorize->getSessionData();
		$data['user_info'] = $this->user_model->getUserData($data['user_data']['user_id']);
		$data['message'] = '';

		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
				
		$this->form_validation->set_rules('short_creds', 'Short Credentials', 'trim|max_length[128]');
		$this->form_validation->set_rules('long_creds', 'Long Credentials', 'trim');
		if($this->form_validation->run() == TRUE) {
			$short_creds = $this->input->post('short_creds');
			$long_creds = $this->input->post('long_creds');
			
			$status = $this->user_model->saveCredentials($data['user_info']['user_id'], $short_creds, $long_creds);
			$this->load->view('user/user_credentials_success', $data);	
			
		}
		else {
			$this->load->view('user/user_credentials_view', $data);
		}	
	}
	
	function change_email() {
		$data['user_data'] = $this->authorize->getSessionData();
		$data['user_info'] = $this->user_model->getUserData($data['user_data']['user_id']);
		$data['message'] = '';
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[256]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		
		if($this->form_validation->run() == TRUE) {
			$password = $this->input->post('password');
			$email = $this->input->post('email');
			
			$status = $this->user_model->saveEmailChange($data['user_info']['user_id'], $password, $email);
			if($status) {
				$this->load->view('user/user_email_success', $data);	
			}
			else {
				$data['message'] = '<div class="error">Bad password</div>';
				$this->load->view('user/user_email_view', $data);
			}	
		}
		else {
			$this->load->view('user/user_email_view', $data);
		}		
	}
	function change_name() {		
		$data['user_data'] = $this->authorize->getSessionData();
		
		$data['user_info'] = $this->user_model->getUserData($data['user_data']['user_id']);
		$data['message'] = '';
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required|min_length[1]|max_length[64]');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|min_length[1]|max_length[64]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) {
			$password = $this->input->post('password');
			$first_name = $this->input->post('first_name');
			$last_name = $this->input->post('last_name');
			
			$status = $this->user_model->saveNameChange($data['user_info']['user_id'], $password, $first_name, $last_name);
			if($status) {
				$this->load->view('user/user_name_success', $data);	
			}
			else {
				$data['message'] = '<div class="error">Bad password</div>';
				$this->load->view('user/user_name_view', $data);
			}
		}
		else {
			$this->load->view('user/user_name_view', $data);	
		}
	}
	
	function change_password() {
		$data['user_data'] = $this->authorize->getSessionData();
		$data['user_info'] = $this->user_model->getUserData($data['user_data']['user_id']);
		$data['message'] = '';
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
				
		$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|matches[new_passconf]');
		$this->form_validation->set_rules('new_passconf', 'Confrim Password', 'trim|required');
		$this->form_validation->set_rules('old_password', 'Old Password', 'trim|required');
		
		if($this->form_validation->run() == TRUE) {
			$new_password = $this->input->post('new_password');
			$new_passconf = $this->input->post('new_passconf');
			$old_password = $this->input->post('old_password');
			
			$status = $this->user_model->savePasswordChange($data['user_info']['user_id'], $old_password, $new_password);
			if($status) {
				$this->load->view('user/user_password_success', $data);	
			}
			else {
				$data['message'] = '<div class="error">Old Password does not match our records</div>';
				$this->load->view('user/user_password_view', $data);
			}	
		}
		else {
			$this->load->view('user/user_password_view', $data);
		}		
	}
	

	
	function upload_signature($upload = false) {
		/**
		 * @TODO add form validation to this. 
		 */
		$data['user_data'] = $this->authorize->getSessionData();
		$data['user_info'] = $this->user_model->getUserData($data['user_data']['user_id']);
		$data['signature'] = $this->user_model->getCurrentSignature($data['user_data']['user_id']);
		
		$data['message'] = '';
		
		$config['upload_path'] = './uploads/signatures/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size'] = '100';
		$config['max_height'] = '100';
		$config['max_width'] = '600';
		$config['max_filename'] = '150';
		$config['remove_spaces'] = true;
		
		if($upload) {
			$this->load->library('upload', $config);
			if (!$this->upload->do_upload('signature')) {
				$data['message'] = $this->upload->display_errors(); 
				$this->load->view('user/user_signature_view', $data);
			}
			else {
				
				$temp_data = $this->upload->data();
				$image['image_name'] = $temp_data['file_name'];
				$image['image_size'] = $temp_data['file_size'];
				$image['image_location'] = '/mango/uploads/signatures/' . $temp_data['file_name']; //define the actuall path
				$image['image_type'] = $temp_data['file_type'];
				
				$data['image'] = $temp_data;
				
				$this->user_model->uploadSignature($data['user_data']['user_id'], $image);
				//$this->load->view('user/user_signature_success', $data);
				redirect('user/upload_signature', 'refresh');	
			}
		}
		else {
			$this->load->view('user/user_signature_view', $data);
		}	
		
	}
	
	/**
	 * Admin options here
	 */
	
	function user_change_privileges() {
		$this->load->model('user/user_model');
		$data['user_data'] = $this->authorize->getSessionData();
		$data['users'] = $this->user_model->getActiveUsers();
		$data['user_types'] = $this->user_model->getUserTypes();
		unset($data['users'][0]); //hack to get rid of another hack : see user_model:getActiveUsers();
		
		$this->load->view('admin/users_privileges_list', $data);
	}
	
	function user_deactivate($user_id = null) {
		$this->load->model('user/user_model');
		$data['user_data'] = $this->authorize->getSessionData();
		$data['users'] = $this->user_model->getActiveUsers();
		unset($data['users'][0]); //hack to get rid of another hack : see user_model:getActiveUsers();
		if ($user_id == null) {
			//no user selected, do nothing
			$this->load->view('admin/users_deactivate_list', $data);
		}
		else {
			//deactivating the selected user
			$this->user_model->deactivateUser($user_id); //returns nothing
		}
	}
	function user_new() {
		$this->load->helper('form');
		$this->load->model('user/user_model');
		$this->load->library('form_validation');
		
		$data['message'] = '';
		$data['user_data'] = $this->authorize->getSessionData();
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		$this->form_validation->set_rules('login_name', 'Login Name', 'trim|required|min_length[1]|max_length[64]|callback_CB_check_login_name');
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required|min_length[1]|max_length[64]');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|min_length[1]|max_length[64]');
		$this->form_validation->set_rules('email', 'Email Address', 'trim|required|min_length[1]|max_length[256]|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|matches[passconf]');
		$this->form_validation->set_rules('passconf', 'Verify Password', 'trim|required|');
		
		if ($this->form_validation->run() == TRUE) {
			$login_name = $this->input->post('login_name');
			$first_name = $this->input->post('first_name');
			$last_name = $this->input->post('last_name');
			$email = $this->input->post('email');
			$password = sha1($this->input->post('password'));
			

			//$login_name = $this->user_model->createLoginName($first_name);

			$user = array('login_name' => $login_name,
				'first_name' => $first_name,
				'last_name' => $last_name,
				'email' => $email,
				'password' => $password,
				'user_type' => 5,
				'active' => 1,
				'last_login' => date('Y/m/d'),
				'ip_address' => '127.0.0.1');
			$status = $this->user_model->add_user($user);
			
			$data['login_name'] = $login_name;
			
			$this->load->view('admin/new_user_success', $data);
		}
		else {
			$this->load->view('admin/new_user', $data);	
		}			
	}
	

	
	function user_reactivate($user_id = null) {
		$this->load->model('user/user_model');
		$data['user_data'] = $this->authorize->getSessionData();
		$data['users'] = $this->user_model->getDeactiveUsers();
		
		if ($user_id == null) {
			//no user selected, do nothing
			$this->load->view('admin/users_reactivate_list', $data);
		}
		else {
			//reactivating the selected user
			$this->user_model->reactivateUser($user_id); //returns nothing
		}
	}
	
	function user_reset_password($user_id = null, $admin_id = null, $password = null) {
		$this->load->model('user/user_model');
		$data['user_data'] = $this->authorize->getSessionData();
		$data['users'] = $this->user_model->getActiveUsers();
		unset($data['users'][0]); //hack to get rid of another hack : see user_model:getActiveUsers();
		if ($user_id == null) {
			//no user selected, do nothing
			$this->load->view('admin/users_reset_password_list', $data);
		}
		else {
			//changing the users password
			$status = $this->user_model->resetPassword($user_id, $admin_id, $password);
			echo $status;
		}
	}
	

	
	function AJAX_update_user_privileges($user_id) {
		$this->load->model('user/user_model');
				
		$priv = $this->input->post('value');
		$status = $this->user_model->updateUserPrivileges($user_id, $priv);
		echo $priv;
	}
	
	function CB_check_login_name($string) {
		$this->load->model('user/user_model');
		$b = false;
		$users = $this->user_model->checkLoginNames($string);
		$this->form_validation->set_message('CB_check_login_name', 'A User with that login name already exists. <br /> Change the name and try again.');		 
		if($users) { //$mod = false, no material found 
			$b =  true;
		}
		return $b;
	}
	
}
?>