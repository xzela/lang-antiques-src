<?php

class Products extends Controller {

	var $categories = array();
	var $types = array();
	var $controller;
	var $sorts = array();

	function __construct() {
		parent::Controller();
		$this->load->helper('ssl');
		//load Models
		$this->load->model('major_class/major_class_model');
		$this->load->model('modifier/modifier_model');
		$this->load->model('menu/menu_model');
		$this->sorts = $this->menu_model->getSorts();
		//Get All Active Major Classes
		$this->categories = $this->major_class_model->getMajorClasses();
		//Get All Active Modifiers
		$this->types = $this->modifier_model->getModifiers();
		//define the parent controller
		//in this case it's 'products', this is required for submenu feature
		$this->controller = 'products';
		remove_ssl();
	}

	public function index() {
		redirect('/', 'refresh');
	}

	/**
	 * Adds an Item to the Users Cart.
	 * So it can be purchased later. The cart is
	 * stored in a php session.
	 *
	 * @param [string] $item_number = item number, 000-000-0000;
	 */
	public function add_to_cart($item_number) {
		$this->load->model('products/inventory_model');
		$data = array();
		//get the item data
		$data['item_data'] = $this->inventory_model->getItemDataByNumber($item_number);
		if(!empty($data['item_data'])) {
			//if the session data 'cart' is null, create an empty array called $cart;
			if($this->session->userdata('cart') == null) {
				$cart = array();
			}
			else { //cart exits, create $cart array with with existing cart data (from session)
				$cart = $this->session->userdata('cart');
			}
			//populate $cart array with data, $key = item_id, value = item_number;
			$cart[$data['item_data']['item_id']] = $data['item_data']['item_number'];
			//write $cart array into session.
			$this->session->set_userdata('cart', $cart);
			redirect('shopping/view-cart', 'refresh');
		}
		else {// no item found, return 404 errror;
			redirect('error/page-not-found', 'refresh');
		}
	} //end add_to_cart();

	/**
	 * Does a major class lookup.
	 * Categories are an alias for Major Classes
	 *
	 * @param [string] $string = catgeory URL string ('diamond-rings')
	 * @param [string] $direction = direction inwhich to display the results
	 * @param [int] $offset = offset of the results
	 */
	public function category($string, $field = 'publish_date', $direction = 'desc', $offset = null) {
		$this->load->model('products/inventory_model'); //load inventory model
		$this->load->library('my_pagination'); //modified pagination lib
		$data = array();
		$data['sorts'] = $this->sorts;
		//take the string to find it's major_class id;
		$major_class_id = $this->_find_major_class($string);

		if($major_class_id != null) { //if not null,
			//test to see if a direction change has been made,
			foreach($this->sorts as $sort) {
				if($this->input->post($sort['field'] . '-' . $sort['direction'])) {
					$field = $sort['field'];
					if(isset($sort['direction'])) {
						$direction = $sort['direction'];
					}
					else {
						$direction = 'desc';
					}
				}
			}


			//$p_config = paination config array;
				//$string = url-string,
				//$offset = query offset
				//method_name = current method (for URL)
				//$field = field to sort by (URL)
				//$direction = sort direction
				//controller_name = name of controller (for URL)
			$p_config = $this->my_pagination->create_pagination_config($string, $offset, 'category', $field, $direction, $this->controller);

			//Get majorclass data
			$data['category_data'] = $this->major_class_model->getMajorClassData($major_class_id);
			//get items
			$pag_data = $this->inventory_model->getAvailableInventoryByMajorClass($major_class_id, $p_config['per_page'], $offset, $field, $direction);
			//get total number of rows
			$p_config['total_rows'] = $pag_data['pagination']['total_rows'];
			//selected inventory data
			$data['inventory_data'] = $pag_data['inventory'];

			//load page data (title, name, etc...)
			$page = array();
				//Check to see if a title has been set
				if($data['category_data']['major_class_title'] == '') {
					$h2_title = $data['category_data']['mjr_class_name'];
				}
				else {
					$h2_title = $data['category_data']['major_class_title'];
				}
				$page['field'] = $field;
				$page['h2'] = 'Our Selection of ' . $h2_title;
				$page['meta']['meta_description'] = $data['category_data']['meta_description'];
				$page['page_title'] = $data['category_data']['mjr_class_name'];
				$page['page_paragraph'] = $data['category_data']['page_paragraph'];
				if($offset != null) {
					$page['meta']['meta_description'] = $data['category_data']['meta_description'] . ' Items ' . $offset . ' of ' . $p_config['total_rows'];
					$page['page_title'] = $data['category_data']['mjr_class_name'] . ' - Items ' . $offset . ' of ' . $p_config['total_rows'];
				}

				$page['breadcrumb'] =  $data['category_data']['mjr_class_name'];
				$page['total_rows'] = $p_config['total_rows'];
				//$page['all_url'] = base_url() . $this->config->item('index_page') . '/products/category/' . $string . '/' . $direction . '/all';
				$page['all_url'] = base_url() . 'products/category/' . $string . '/' . $field . '/' . $direction . '/all';
				$page['direction'] = $direction;
				$page['offset'] = $offset;

			$data['page_data'] = $page;
			//initialize pagination
			$this->my_pagination->initialize($p_config);
			$data['page_data']['pagination'] = $this->my_pagination->create_links();
			if($offset == 'all') {
				$data['page_data']['pagination'] = null;
			}
			$this->load->view('products/products_list_view', $data);
		}
		else {
			redirect('error/page-not-found', 'refresh');
		}
	} //end category();

	/**
	 * Loads the Send to Friend View
	 *
	 * @param [string] $item_number = item_number
	 */
	public function email_to_friend($item_number) {
		$this->load->model('products/inventory_model');
		$this->load->library('form_validation');
		$data = array();

		$data['item_data'] = $this->inventory_model->getItemDataByNumber($item_number);
		if(sizeof($data['item_data']) > 0) {

			$data['category_data'] = $this->major_class_model->getMajorClassData($data['item_data']['mjr_class_id']);
			$page = array();

			//Validation rules
			$this->form_validation->set_rules('your_name','Your Name', 'required|trim|min_length[1]');
			$this->form_validation->set_rules('your_email','Your Email', 'required|trim|valid_email');
			$this->form_validation->set_rules('friend_name','Your Friends Name', 'required|trim|min_length[1]');
			$this->form_validation->set_rules('friend_email','Your Friends Email', 'required|trim|valid_email');
			$this->form_validation->set_rules('personal_message','Personal Message', 'trim|max_length[256]');
			$this->form_validation->set_rules('math_captcha','Math Problem', 'required|trim|min_length[1]|callback_CB_validate_math');

			if($this->form_validation->run() == true) {
				$this->load->model('mailer/mailer_model');
				$fields = array();
					$fields['your_name'] = $this->input->post('your_name');
					$fields['your_email'] = $this->input->post('your_email');
					$fields['friend_name'] = $this->input->post('friend_name');
					$fields['friend_email'] = $this->input->post('friend_email');
					$fields['personal_message'] = $this->input->post('personal_message');
					$fields['item_number'] = $data['item_data']['item_number'];
					$fields['item_name'] = $data['item_data']['item_name'];
					$fields['item_description'] = $data['item_data']['item_description'];
					$fields['image_location'] = $data['item_data']['images'][0]['image_location'];
				$message = $this->mailer_model->composeFriendMessage($fields);
					$message['to'] = $fields['friend_email'];
					$message['from'] = $fields['your_email'];
				$this->mailer_model->sendEmail($message);
				$page['h2'] = 'Thank You, Your Message Has Been Sent';
				$page['title'] = 'Your Message Has Been Sent';
				$page['breadcrumb'] = anchor('products/category/' . str_replace(' ', '-', strtolower($data['category_data']['mjr_class_name'])), $data['category_data']['mjr_class_name'])
					 . " &gt; "
					 . anchor('products/item/' . $data['item_data']['item_number'], $data['item_data']['item_name']);
				$page['message_title'] = 'Thank You! Your Friend Will Receive an Email Soon.';
				$page['message_content'] = "We have just sent an email to your friend about item #" . anchor('products/item/' . $data['item_data']['item_number'], $data['item_data']['item_number'])  . ". They should receive an email shortly. If you have any questions or would like to talk to our staff, please call us at 1-800-924-2213 during our normal business hours or email us at <a href='mailto:info@langantiques.com'>info@langantiques.com</a>.";
				$data['page_data'] = $page;
				$this->load->view('pages/message_sent_view', $data);
			}
			else {
				$page['h2'] = $data['item_data']['item_name'];
				$page['title'] = $data['item_data']['item_name'] . ' - ' . $data['item_data']['item_number'];
				$page['breadcrumb'] = anchor('products/category/' . str_replace(' ', '-', strtolower($data['category_data']['mjr_class_name'])), $data['category_data']['mjr_class_name'])
					 . " &gt; "
					 . anchor('products/item/' . $data['item_data']['item_number'], $data['item_data']['item_name']);
				$page['captcha'] = $this->_main_captcha();
				$data['page_data'] = $page;
				$this->load->view('products/products_friend_view', $data);
			}
		}
		else {
			redirect('error/page-not-found', 'refresh');
		}
	} //end email_to_friend();

	/**
	 * Loads the Send an Inquery View
	 *
	 * @param [string] $item_number
	 */
	public function inquire($item_number) {
		$this->load->model('products/inventory_model');
		$this->load->library('form_validation');
		$data = array();

		$data['item_data'] = $this->inventory_model->getItemDataByNumber($item_number);
		if(sizeof($data['item_data']) > 0) {

			$data['category_data'] = $this->major_class_model->getMajorClassData($data['item_data']['mjr_class_id']);
			$page = array();

			//validation rules
			$this->form_validation->set_rules('name','Name', 'required|trim|min_length[1]');
			$this->form_validation->set_rules('email','Email', 'required|trim|valid_email');
			$this->form_validation->set_rules('phone_number','Phone Number', 'trim');
			$this->form_validation->set_rules('question','Question', 'required|trim|min_length[1]');
			$this->form_validation->set_rules('math_captcha','Math Problem', 'required|trim|min_length[1]|callback_CB_validate_math');

			//Run validations
			if($this->form_validation->run() == true) {
				$this->load->model('mailer/mailer_model');
				$fields = array();
					$fields['name'] = $this->input->post('name');
					$fields['email'] = $this->input->post('email');
					$fields['phone_number'] = $this->input->post('phone_number');
					$fields['question'] = $this->input->post('question');
					$fields['item_number'] = $data['item_data']['item_number'];
					$fields['item_name'] = $data['item_data']['item_name'];
					$fields['image_location'] = $data['item_data']['images'][0]['image_location'];
				$message = $this->mailer_model->composeInquryMessage($fields); //returns formatted email body
					$message['to'] = 'info@langantiques.com';
					$message['from'] = $fields['email'];
				$this->mailer_model->sendEmail($message); //sends email
				$page['h2'] = 'Thank You, Your Message Has Been Sent';
				$page['title'] = 'Your Message Has Been Sent';
				$page['breadcrumb'] = anchor('products/category/' . str_replace(' ', '-', strtolower($data['category_data']['mjr_class_name'])), $data['category_data']['mjr_class_name'])
					 . " &gt; "
					 . anchor('products/item/' . $data['item_data']['item_number'], $data['item_data']['item_name']);
				$page['message_title'] = 'Thank You! You\'ll Be Hearing From Us Soon.';
				$page['message_content'] = "Thank you for your interest in item #" . anchor('products/item/' . $data['item_data']['item_number'], $data['item_data']['item_number'])  . " One of our staff members will be in contact with you soon. If you have any questions or would like to talk to our staff, please call us at 1-800-924-2213 during our normal business hours or email us at <a href='mailto:info@langantiques.com'>info@langantiques.com</a>.";
				$data['page_data'] = $page;
				$this->load->view('pages/message_sent_view', $data);
			}
			else {
				$page['h2'] = $data['item_data']['item_name'];
				$page['title'] = $data['item_data']['item_name'] . ' - ' . $data['item_data']['item_number'];
				$page['breadcrumb'] = anchor('products/category/' . str_replace(' ', '-', strtolower($data['category_data']['mjr_class_name'])), $data['category_data']['mjr_class_name'])
					 . " &gt; "
					 . anchor('products/item/' . $data['item_data']['item_number'], $data['item_data']['item_name']);
				$page['captcha'] = $this->_main_captcha();
				$data['page_data'] = $page;
				$this->load->view('products/products_inquire_view', $data);
			}
		}
		else {
			redirect('error/page-not-found', 'refresh');
		}
	} //end inquire();

	/**
	 * Loads the Item Information Page
	 *
	 * @param [string] $item_number = item_number
	 */
	public function item($item_number) {
		$this->load->model('products/inventory_model');

		$data = array();
		$data['item_data'] = $this->inventory_model->getItemDataByNumber($item_number);
		if(sizeof($data['item_data']) > 0) {
			$mods = $this->modifier_model->getItemModifiers($data['item_data']['item_id']);
			$keyword_list = array();
			foreach($mods as $mod) {
				$keyword_list[] = $mod['modifier_name'];
			}
			$data['keyword_modifiers'] = implode(', ', $keyword_list);
			$data['modifiers'] = $this->modifier_model->getWebActiveItemModifiers($data['item_data']['item_id']);
			$data['details'] = $this->_product_details($data['item_data']);
			$data['details']['staff_comments'] = $this->inventory_model->getStaffComments($data['item_data']['item_id']);
			$data['gemstone_info'] = $this->_gather_gemstones($data['item_data']['item_id']);
			$data['category_data'] = $this->major_class_model->getMajorClassData($data['item_data']['mjr_class_id']);
			$data['similar_items'] =  $this->inventory_model->getSimilarItems($data['item_data']['item_id']);
			$page = array();
				$page['h2'] = $data['item_data']['item_name'];
				$page['title'] = $data['item_data']['item_name'] . ' - ' . $data['item_data']['item_number'];
				$page['breadcrumb'] = anchor('products/category/' . str_replace(' ', '-', strtolower($data['category_data']['mjr_class_name'])), $data['category_data']['mjr_class_name'])
					 . " &gt; "
					 . anchor('products/item/' . $data['item_data']['item_number'], $data['item_data']['item_name']);
			$data['page_data'] = $page;
			$this->load->view('products/products_item_view', $data);
		}
		else {
			redirect('error/page-not-found', 'refresh');
		}
	} //end item();


	/**
	 * Loads the Printer Page,
	 * This allows the user to print the item from the web.
	 *
	 * @param [string] $item_number = item number
	 */
	public function printer($item_number) {
		$this->load->model('products/inventory_model');
		$data = array();
		$page = array();

		$data['item_data'] = $this->inventory_model->getItemDataByNumber($item_number);
		$page['title'] = $data['item_data']['item_name'];
		$page['h2'] = $data['item_data']['item_name'];

		$data['page_data'] = $page;
		$this->load->view('products/products_print_view', $data);
	} //end printer();

	/**
	 * Loads the type modifier listing
	 *
	 * @param [string] $string = name of modifier
	 * @param [string] $direction = direction ot sort
	 * @param [int] $offset = offset of search query
	 */
	public function type($string, $field = 'publish_date', $direction = 'desc', $offset = null) {
		$this->load->model('products/inventory_model');
		$this->load->library('my_pagination'); //modified pagination lib
		$data = array();
		$data['sorts'] = $this->sorts;
		//take the string to find it's major_class id;
		$modifier_id = $this->_find_modifier($string);
		if($modifier_id != null) {
			foreach($this->sorts as $sort) {
				if($this->input->post($sort['field'] . '-' . $sort['direction'])) {
					$field = $sort['field'];
					if(isset($sort['direction'])) {
						$direction = $sort['direction'];
					}
					else {
						$direction = 'desc';
					}
				}
			}

			$p_config = $this->my_pagination->create_pagination_config($string, $offset, 'type', $field, $direction, $this->controller);

			$data['category_data'] = $this->modifier_model->getModifierData($modifier_id);
			$pag_data = $this->inventory_model->getAvailableInventoryByModifier($modifier_id, $p_config['per_page'], $offset, $field, $direction);

			$p_config['total_rows'] = $pag_data['pagination']['total_rows'];

			$data['inventory_data'] = $pag_data['inventory'];

			//load page data (title, name, etc...)
			$page = array();
				if($data['category_data']['modifier_title'] == '') {
					$title = $data['category_data']['modifier_name'];
				}
				else {
					$title = $data['category_data']['modifier_title'];
				}
				if($data['category_data']['staff']) {
					$page['h2'] = 'Here are ' . $title;
				}
				else {
					$page['h2'] = 'Our Selection of ' . $title;
				}
				$page['field'] = $field;
				$page['meta']['meta_description'] = $data['category_data']['meta_description'];
				$page['page_title'] = $title;
				$page['page_paragraph'] = $data['category_data']['page_paragraph'];
				if($offset != null) {
					$page['meta']['meta_description'] = $data['category_data']['meta_description'] . ' Items ' . $offset . ' of ' . $p_config['total_rows'];
					$page['page_title'] = $title . ' - Items ' . $offset . ' of ' . $p_config['total_rows'];
				}
				$page['breadcrumb'] =  $title;
				$page['total_rows'] = $p_config['total_rows'];
				$page['direction'] = $direction;
				$page['offset'] = $offset;
				$page['all_url'] = base_url() . 'products/type/' . $string . '/' . $field . '/' . $direction . '/all';

			$data['page_data'] = $page;
			$this->my_pagination->initialize($p_config);
			$data['page_data']['pagination'] = $this->my_pagination->create_links();
			if($offset == 'all') {
				$data['page_data']['pagination'] = null;
			}
			//$this->output->enable_profiler(TRUE);

			$this->load->view('products/products_list_view', $data);
		}
		else {
			redirect('error/page-not-found', 'refresh');
		}
	} //end type();

	/**
	 * Call back functuon
	 * This is used to verify that the user is a human
	 * It appears have a weakness or something,
	 * either that or the bots are getting smarter.
	 *
	 * SHould change this to shapes or something.
	 *
	 * @param [string] $answer = answer to the question
	 *
	 * @return [boolean];
	 */
	public function CB_validate_math($answer) {
		$b = false;
		//sets the message of the validation
		$this->form_validation->set_message('CB_validate_math', 'Incorrect. Please use a calculator and try again.');
		//tests the answer via the session
		if($answer == $this->session->userdata('captcha')) {
			$this->session->unset_userdata('captcha');
			$b = true;
		}
		return $b; //boolean;
	} //end CB_validate_math();

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

	/**
	 * Attempts to find the id of a major class via
	 * a string
	 *
	 * @param [string] $string = major class name;
	 *
	 * @return [int|null] id = id of the major class
	 */
	private function _find_major_class($string = null) {
		$id = null;
		foreach($this->categories as $category) {
			if($string == $category['element_url_name']) {
				$id = $category['mjr_class_id'];
				break;
			}
		}
		return $id;
	}

	/**
	 * Attempts to find the id of a modifier
	 * via a string.
	 *
	 * @param [string] $string modifier name
	 *
	 * @return [int|null] id = id of modifier
	 */
	private function _find_modifier($string = null) {
		$id = null;
		foreach($this->types as $type) {
			if($string == $type['element_url_name']) {
				$id = $type['modifier_id'];
				break;
			}
		}
		return $id;
	}

	/**
	 * Private
	 *
	 * Gathers all of the gemstones applied
	 * to a specific item.
	 *
	 * Used to present the user with gemstone information
	 * on the Item page.
	 *
	 * @param [int] $item_id = item id
	 */
	private function _gather_gemstones($item_id) {
		$this->load->model('gemstones/diamond_model');
		$this->load->model('gemstones/gemstone_model');
		$this->load->model('gemstones/jadeite_model');
		$this->load->model('gemstones/opal_model');
		$this->load->model('gemstones/pearl_model');

		$temp = array();
		$data = array();
		$junk = array();

		$junk['stone_types'] = $this->gemstone_model->getGemstoneTypes();
		$temp['diamonds'] = $this->diamond_model->getItemDiamonds($item_id);
		$temp['gemstones'] = $this->gemstone_model->getItemGemstones($item_id);
		$temp['pearls'] = $this->pearl_model->getItemPearls($item_id);
		$temp['opals'] = $this->opal_model->getItemOpals($item_id);
		$temp['jade'] = $this->jadeite_model->getItemJadeite($item_id);

		$data['total_diamond_weight'] = 0;

		foreach($temp as $type) {
			foreach($type as $stone) {
				if($stone['is_center'] == 1) {
					$stone['stone_data'] = $junk['stone_types'][$stone['gemstone_type_id']];
					$type_id = $stone['stone_data']['template_type'];
					$stone = $this->gemstone_model->parseGemstone($stone);
					$stone['template_type'] = $type_id;
					$data['center'][] = $stone;
				}
				else {
					$stone['stone_data'] = $junk['stone_types'][$stone['gemstone_type_id']];
					$type_id = $stone['stone_data']['template_type'];
					$stone = $this->gemstone_model->parseGemstone($stone);
					$stone['template_type'] = $type_id;
					$data['additional'][] = $stone;
				}
				if($type_id == 3) {
					$data['total_diamond_weight'] += $stone['carats'];
				}
			}
		}

		//print_r($data);
		return $data; //array;
	} //end _gather_gemstones();

	/**
	 * Private
	 *
	 * Gets product details, length, size, etc
	 *
	 * @param unknown_type $item
	 *
	 * @return [array] = details of item
	 */
	private function _product_details($item) {
		$this->load->model('material/material_model');

		$data = array();

		$data['materials'] = $this->material_model->getItemMaterials($item['item_id']);
		$data['dimensions'] = array();
			if($item['item_size'] != '') {
				$data['dimensions']['Ring Size'] = $item['item_size']; //@TODO rename 'item_size' to Ring Size
			}
			if($item['item_height'] != '') {
				$data['dimensions']['Height'] = $item['item_height'];
			}
			if($item['item_width'] != '') {
				$data['dimensions']['Width'] = $item['item_width'];
			}
			if($item['item_length'] != '') {
				$data['dimensions']['Length'] = $item['item_length'];
			}
		return $data; //array;
	} //end _product_details();

}
?>