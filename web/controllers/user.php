<?php
/**
 * Controller for the User Section of the WebSite
 * Users can Add Favorites,
 * Add Notes to their favorites,
 * Update their Contact Info,
 *
 * ... and that's about it.
 *
 * @author zeph
 *
 */
class User extends Controller {

	function __construct() {
		parent::Controller();
		$this->session->unset_userdata('parent_id');
		$this->load->helper('ssl');
	}

	public function index() {
		redirect('user/signin', 'refresh');
	}

	public function contact_edit() {
		$this->load->model('user/user_model');
		$this->load->library('form_validation');
		$data = array();

		if($this->session->userdata('customer_id') != null) {
			$data['customer_data'] = $this->user_model->getCustomerData($this->session->userdata('customer_id'));

			$this->form_validation->set_rules('first_name', 'Frist Name', 'requried|trim|min_length[1]|max_length[64]');
			$this->form_validation->set_rules('last_name', 'Last Name', 'requried|trim|min_length[1]|max_length[64]');
			$this->form_validation->set_rules('middle_name', 'Middle Name', 'trim|min_length[1]|max_length[64]');
			$this->form_validation->set_rules('spouse_first', 'Spouse First Name', 'trim|min_length[1]|max_length[64]');
			$this->form_validation->set_rules('spouse_last', 'Spouse Last Name', 'trim|min_length[1]|max_length[64]');
			$this->form_validation->set_rules('spouse_middle', 'Spouse Middle Name', 'trim|min_length[1]|max_length[64]');
			$this->form_validation->set_rules('email', 'Email Address', 'required|trim|min_length[1]|max_length[256]|valid_email');
			$this->form_validation->set_rules('home_phone', 'Home Phone Number', 'trim|min_length[1]|max_length[20]');
			$this->form_validation->set_rules('work_phone', 'Work Phone Number', 'trim|min_length[1]|max_length[20]');
			$this->form_validation->set_rules('cell_phone', 'Cell Phone Number', 'trim|min_length[1]|max_length[20]');

			if($this->form_validation->run() == true) {
				$fields = array();
					$fields['first_name'] = $this->input->post('first_name');
					$fields['last_name'] = $this->input->post('last_name');
					$fields['middle_name'] = $this->input->post('middle_name');
					$fields['spouse_first'] = $this->input->post('spouse_first');
					$fields['spouse_middle'] = $this->input->post('spouse_middle');
					$fields['spouse_last'] = $this->input->post('spouse_last');
					$fields['email'] = $this->input->post('email');
					$fields['home_phone'] = $this->input->post('home_phone');
					$fields['work_phone'] = $this->input->post('work_phone');
					$fields['cell_phone'] = $this->input->post('cell_phone');

					$this->user_model->updateCustomerData($this->session->userdata('customer_id'), $fields);

					redirect('user/user-account', 'refresh');
			}
			else {
				$this->load->view('user/user_edit_contact_view', $data);
			}
		}
		else {
			redirect('user/signin','refresh');
		}
	}
	public function create_account() {
		$this->load->model('user/user_model');
		$this->load->library('form_validation');
		$data = array();

		$this->form_validation->set_rules('first_name','First Name','required|trim|min_lenth[1]|max_length[64]');
		$this->form_validation->set_rules('last_name','Last Name','required|trim|min_lenth[1]|max_length[64]');
		$this->form_validation->set_rules('email','Email Address','required|trim|min_lenth[1]|max_length[256]|valid_email|callback_CB_check_email_address');
		$this->form_validation->set_rules('password1','Password','required|trim|min_lenth[1]|max_length[256]');
		if($this->form_validation->run() == true) {
			$fields = array();
				$fields['first_name'] = $this->input->post('first_name');
				$fields['last_name'] = $this->input->post('last_name');
				$fields['email'] = $this->input->post('email');
				$fields['password'] = sha1($this->input->post('password1'));
			$customer_id = $this->user_model->insertCustomer($fields);
			$session_data = array();
				$session_data['customer_id'] = $customer_id;

			$this->session->set_userdata($session_data);
			//test to see if user is attempting to buying something...
			if($this->session->userdata('checkout')) {
				$this->session->set_userdata(array('checkout' => false));
				redirect('shopping/check-out','refresh'); //redirect user to checkout page
			}
			else {
				redirect('user/user-account', 'refresh');
			}
		}
		else {
			$this->load->view('user/user_create_account_view', $data);
		}
	}

	public function favorites() {
		$this->load->model('user/user_model');
		$customer_id = $this->session->userdata('customer_id');
		if($customer_id != null) {
			$data = array();
				$data['customer_data'] = $this->user_model->getCustomerData($customer_id);

			$data['favories'] = $this->user_model->getCustomerFavories($customer_id);

			$this->load->view('user/user_favorites_view', $data);
		}
		else {
			redirect('user/signin', 'refresh');
		}
	}

	public function favorite_add() {
		$this->load->model('user/user_model');

		if($this->input->post('customer_id') != null) {
			$fields = array();
				$fields['customer_id'] = $this->input->post('customer_id');
				$fields['item_id'] = $this->input->post('item_id');
				$fields['ip_address'] = $_SERVER['REMOTE_ADDR'];

			$this->user_model->addCustomerFavorite($fields);

			header("Location: " . $_SERVER['HTTP_REFERER']);
		}
		else {
			redirect('user/signin','refresh');
		}
	}

	public function favorite_remove() {
		$this->load->model('user/user_model');
		$customer_id = $this->input->post('customer_id');
		$item_id = $this->input->post('item_id');

		$this->user_model->removeCustomerFavorite($customer_id, $item_id);

		header("Location: " . $_SERVER['HTTP_REFERER']);
	}

	public function favorites_share() {
		$this->load->model('user/user_model');
		$this->load->library('form_validation');

		$customer_id = $this->session->userdata('customer_id');
		if($customer_id != null) {
			$data = array();
			$data['customer_data'] = $this->user_model->getCustomerData($customer_id);
			$data['favorites'] = $this->user_model->getCustomerFavories($customer_id);
			$data['captcha'] = $this->_main_captcha();
			$this->form_validation->set_rules('friend_name','Frends Name', 'required|max_length[256]');
			$this->form_validation->set_rules('friend_email','Frends Email', 'required|valid_email|max_length[256]');

			if($this->form_validation->run() == true) {
				$this->load->model('mailer/mailer_model');
				$content = array();
					$content['your_name'] = $data['customer_data']['first_name'] . ' ' . $data['customer_data']['last_name'];
					$content['friend_name'] = $this->input->post('friend_name');
					$content['friend_email'] = $this->input->post('friend_email');
					$content['personal_message'] = $this->input->post('personal_message');
					$content['favorites'] = $data['favorites'];

				$message = $this->mailer_model->composeShareFavoritesMessage($content);
				$message['from'] = 'info@langantiques.com';
				$message['to'] = $content['friend_email'];

				$this->mailer_model->sendEmail($message); //sends email

				redirect('user/favorites', 'refresh');

			}
			else {
				$this->load->view('user/user_favorites_share_view', $data);
			}
		}
		else {
			redirect('user/signin', 'refresh');
		}
	}

	public function forgot_password() {
		$this->load->model('user/user_model');
		$this->load->model('mailer/mailer_model');
		$this->load->library('form_validation');
		force_ssl();

		$data = array();
			$data['page'] = array();
			$data['page']['title'] = 'So, You Forgot Your Password - Lang Antiques';
			$data['page']['breadcrumb'] = 'Forgot Password';
			$data['page']['heading'] = 'Forgot Password';
			$data['page']['form'] = true;

		$this->form_validation->set_rules('email_address', 'Email Address', 'required|min_length[1]|valid_email|callback_CB_find_customer_email');

		if($this->form_validation->run() == true) {
			$data['page'] = array();
			$data['page']['title'] = 'An Email Has Been Sent - Lang Antiques';
			$data['page']['breadcrumb'] = 'Email Has Been Sent';
			$data['page']['heading'] = 'An Email Has Been Sent';
			$data['page']['form'] = false;
			$d = array();
				$d['temp_id'] = $this->user_model->getCustomerIdFromEmail($this->input->post('email_address'));
			$this->session->set_userdata($d);
			$message = $this->mailer_model->composeResetPasswordMessage($this->session->userdata('session_id'));
				$message['to'] = $this->input->post('email_address');
				$message['from'] = 'info@langantiques.com';
			$this->mailer_model->sendEmail($message);
			$this->load->view('user/user_forgot_password_view', $data);
		}
		else {
			$this->load->view('user/user_forgot_password_view', $data);
		}
	}

	public function mailing_edit() {
		$this->load->model('user/user_model');
		$this->load->library('form_validation');
		$data = array();

		if($this->session->userdata('customer_id') != null) {
			$data['customer_data'] = $this->user_model->getCustomerData($this->session->userdata('customer_id'));

			$this->form_validation->set_rules('address', 'Address Line 1', 'requried|trim|min_length[1]|max_length[256]');
			$this->form_validation->set_rules('address2', 'Address Line 2', 'trim|min_length[1]|max_length[256]');
			$this->form_validation->set_rules('city', 'City', 'trim|min_length[1]|max_length[256]');
			$this->form_validation->set_rules('state', 'State', 'trim|min_length[1]|max_length[2]');
			$this->form_validation->set_rules('zip', 'Zip Code', 'trim|min_length[1]|max_length[10]');
			$this->form_validation->set_rules('country', 'Country', 'trim|min_length[1]|max_length[256]');

			if($this->form_validation->run() == true) {
				$fields = array();
					$fields['address'] = $this->input->post('address');
					$fields['address2'] = $this->input->post('address2');
					$fields['city'] = $this->input->post('city');
					$fields['state'] = $this->input->post('state');
					$fields['zip'] = $this->input->post('zip');
					$fields['country'] = $this->input->post('country');
					$this->user_model->updateCustomerData($this->session->userdata('customer_id'), $fields);
					redirect('user/user-account', 'refresh');
			}
			else {
				$this->load->view('user/user_edit_mailing_view', $data);
			}
		}
		else {
			redirect('user/signin','refresh');
		}
	}

	public function note_add($item_number) {
		$this->load->model('user/user_model');
		$this->load->model('products/inventory_model');
		$this->load->library('form_validation');
		$data = array();
			$data['item'] = $this->inventory_model->getItemDataByNumber($item_number);

		$this->form_validation->set_rules('customer_id','Customer ID','required|trim|min_length[1]');
		$this->form_validation->set_rules('item_id','Item ID','required|trim|min_length[1]');
		$this->form_validation->set_rules('comment','Comment','required|trim|min_length[1]');

		if($this->form_validation->run() == 'true') {
			$fields = array();
				$fields['customer_id'] = $this->input->post('customer_id');
				$fields['item_id'] = $this->input->post('item_id');
				$fields['ip'] = $_SERVER['REMOTE_ADDR'];
				$fields['comment'] = $this->input->post('comment');
			$this->user_model->addNote($fields);
			redirect('user/favorites','refresh');
		}
		else {
			$this->load->view('user/user_add_note_view', $data);
		}
	}

	public function note_remove() {
		$this->load->model('user/user_model');
		$data = array();
			$data['customer_id'] = $this->input->post('customer_id');
			$data['item_id'] = $this->input->post('item_id');
			$data['comment_id'] = $this->input->post('comment_id');

		$this->user_model->removeCustomerFavoriteNote($data);

		redirect('user/favorites', 'refresh');
	}

	public function reset_password($id) {
		$this->load->model('user/user_model');
		$this->load->library('form_validation');
		$data = array();
		//test to make sure the session ids match
		if($this->session->userdata('session_id') == $id) {
			$data['page'] = array();
				$data['page']['title'] = 'Please Enter A New Password';
				$data['page']['breadcrumb'] = 'Enter New Password';
				$data['page']['heading'] = 'Enter New Password';
				$data['page']['form'] = true;

			$this->form_validation->set_rules('password', 'Password', 'required|min_length[1]|max_length[64]|matches[password2]');
			$this->form_validation->set_rules('password2', 'Verify Password', 'required|min_length[1]|max_length[64]|matches[password]');

			if($this->form_validation->run() == true) {
				//update customer table;
				$customer_id = $this->session->userdata('temp_id'); //temp location of customer_id;
				$fields = array();
					$fields['password'] = sha1($this->input->post('password'));
				//update user password;
				$this->user_model->updateCustomerData($customer_id, $fields);
				//redirect to login page;
				redirect('user/success', 'refresh');
			}
			else {
				$this->load->view('user/user_reset_password_view', $data);
			}
		}
	}

	public function signin() {
		$this->load->model('user/user_model');
		$this->load->library('form_validation');
        $this->load->helper('ssl');
        force_ssl(); //Turn On SSL


		$data = array();

		$this->form_validation->set_rules('email_address', 'Email Address', 'required|trim|min_length[1]|max_length[256]|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[1]|max_length[256]');

		if($this->form_validation->run() == true) {
			//do login stuff
			$fields = array();
				$fields['email'] = $this->input->post('email_address');
				$fields['password'] = sha1($this->input->post('password'));
			$customer_data = $this->user_model->testUserCreditals($fields);
			if ($customer_data['valid']) {
				$session_data = array();
					$session_data['customer_id'] = $customer_data['customer']['customer_id'];
				$this->session->set_userdata($session_data);
				//if the userdata['checkout'] is set to [true] then they want to checkout
				if($this->session->userdata('checkout')) {
					$this->session->set_userdata(array('checkout'=>false)); //set to false, don't need to cause an infinite loop
					//redirect user to checkout page
					redirect('shopping/check-out', 'refresh');
				}
				else {
					//go to account page
					redirect('user/user-account', 'refresh');
				}
			}
			else {
				$this->form_validation->set_rules('login','required', 'required');
				$this->form_validation->set_message('required','That Email/Password combonation does not match our records.');
				if($this->form_validation->run() == true) {

				}
				else {
					$this->load->view('user/user_signin_view', $data);
				}
			}

		}
		else {
			$this->load->view('user/user_signin_view', $data);
		}
	}

	public function signout() {
		$session_data = array('customer_id' => '');
		echo $this->session->unset_userdata($session_data);

		redirect('user/signin', 'refresh');
	}


	public function shipping_edit() {
		$this->load->model('user/user_model');
		$this->load->library('form_validation');
		$data = array();

		if($this->session->userdata('customer_id') != null) {
			$data['customer_data'] = $this->user_model->getCustomerData($this->session->userdata('customer_id'));

			$this->form_validation->set_rules('ship_contact', 'Ship Contact', 'requried|trim|min_length[1]|max_length[256]');
			$this->form_validation->set_rules('ship_phone', 'Ship Phone', 'trim|min_length[1]|max_length[20]');
			$this->form_validation->set_rules('ship_address', 'Address Line 1', 'requried|trim|min_length[1]|max_length[256]');
			$this->form_validation->set_rules('ship_address2', 'Address Line 2', 'trim|min_length[1]|max_length[256]');
			$this->form_validation->set_rules('ship_city', 'City', 'trim|min_length[1]|max_length[256]');
			$this->form_validation->set_rules('ship_state', 'State', 'trim|min_length[1]|max_length[2]');
			$this->form_validation->set_rules('ship_zip', 'Zip Code', 'trim|min_length[1]|max_length[10]');
			$this->form_validation->set_rules('ship_country', 'Country', 'trim|min_length[1]|max_length[256]');

			if($this->form_validation->run() == true) {
				$fields = array();
					$fields['ship_contact'] = $this->input->post('ship_contact');
					$fields['ship_phone'] = $this->input->post('ship_phone');
					$fields['ship_address'] = $this->input->post('ship_address');
					$fields['ship_address2'] = $this->input->post('ship_address2');
					$fields['ship_city'] = $this->input->post('ship_city');
					$fields['ship_state'] = $this->input->post('ship_state');
					$fields['ship_zip'] = $this->input->post('ship_zip');
					$fields['ship_country'] = $this->input->post('ship_country');
					$this->user_model->updateCustomerData($this->session->userdata('customer_id'), $fields);
					redirect('user/user-account', 'refresh');
			}
			else {
				$this->load->view('user/user_edit_shipping_view', $data);
			}
		}
		else {
			redirect('user/signin','refresh');
		}
	}

	public function success() {
		$data = array();

		$this->load->view('user/user_success_password_view', $data);
	}

	public function user_account() {
		remove_ssl();
		$this->load->model('user/user_model');
		$customer_id = $this->session->userdata('customer_id');
		if($customer_id != null) {
			$data = array();
				$data['customer_data'] = $this->user_model->getCustomerData($customer_id);

			$this->load->view('user/user_account_view', $data);
		}
		else {
			redirect('user/signin', 'refresh');
		}
	}

	public function CB_find_customer_email($string) {
		$this->load->model('user/user_model');
		$this->form_validation->set_message('CB_find_customer_email', 'We couldn\'t find that email address, are you sure you\'ve signed up?');
		$bool = $this->user_model->checkEmailAddress($string); //true = email found, false = no email found
		return $bool;

	}
	public function CB_check_email_address($string) {
		$this->load->model('user/user_model');
		$this->form_validation->set_message('CB_check_email_address', 'Hmmm, that email address is already being used. Try a different one.');
		$bool = $this->user_model->checkEmailAddress($string); //true = email found, false = no email found
		return !$bool; //flip bit
	}

	/**
	 * Private
	 *
	 * A captcha used to prevent bots from using our forms
	 *
	 * @return [string] = formatted string
	 *
	 */
	private function _main_captcha() {
		// First of all we are going to set up an array with the text equivalents of all the numbers we will be using.
		$captcha_number_convert = array(0=>'zero', 1=>'one', 2=>'two', 3=>'three', 4=>'four', 5=>'five', 6=>'six', 7=>'seven', 8=>'eight', 9=>'nine', 10=>'ten');
		// Choose the first number randomly between 6 and 10. This is to stop the answer being negative.
		$captcha_number_first = mt_rand(6, 10);
		// Choose the second number randomly between 0 and 5.
		$captcha_number_second = mt_rand(0, 5);
		// Set up an array with the operators that we want to use. At this stage it is only subtraction and addition.
		$captcha_operator_convert = array(0=> array('op' => '+', 'name' => 'plus'), 1 => array('op' => '-', 'name' => 'minus'));
		// Choose the operator randomly from the array.
		$captcha_operator = $captcha_operator_convert[mt_rand(0, 1)];
		// Get the equation in textual form to show to the user.
		$captcha_return = (mt_rand(0, 1) == 1 ? $captcha_number_convert[$captcha_number_first] : $captcha_number_first) . ' ' . $captcha_operator['name'] . ' ' . (mt_rand(0, 1) == 1 ? $captcha_number_convert[$captcha_number_second] : $captcha_number_second);
		// Evaluate the equation and get the result.
		eval('$captcha_result = ' . $captcha_number_first . ' ' . $captcha_operator['op'] . ' ' . $captcha_number_second . ';');
		// Store the result in a session key.
		$this->session->set_userdata('captcha', $captcha_result);
		// Return the question along with a bit of text in front as it will be used in the form itself.
		return 'What is ' . $captcha_return . '?';
	}
}