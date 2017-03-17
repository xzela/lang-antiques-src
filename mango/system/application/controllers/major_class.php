<?php
class Major_class extends Controller {
	
	var $ci;
	
	function __construct() {
		parent::Controller();
		//Check The user to see if they are logged in
		$this->load->library('authorize');
		$this->authorize->isLoggedIn();
		
		$this->ci =& get_instance();
		
	}
	
	function major_class_add() {
		$this->load->library('form_validation');
		$this->load->model('admin/major_class_model');
		
		$data['user_data'] = $this->authorize->getSessionData();
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->form_validation->set_rules('major_class_id', 'Major Class ID', 'trim|required|min_length[1]|max_length[11]|numeric|callback_CB_check_major_class_id');
		$this->form_validation->set_rules('major_class_name', 'Major Class Name', 'trim|required|min_length[1]|max_length[64]|callback_CB_check_major_class_name');
		if ($this->form_validation->run() == TRUE) {
			$fields = array();
				$fields['mjr_class_id'] = $this->input->post('major_class_id');
				$fields['mjr_class_name'] = $this->input->post('major_class_name');
				
			$major_class_id = $this->major_class_model->insertMajorClass($fields);
			
			redirect('admin/major/major_class_edit/' . $major_class_id, 'refresh');
		}
		else {
			$this->load->view('admin/major/major_class_add_view', $data);	
		}		
	}
	
	function major_class_delete() {
		$this->load->library('form_validation');		
		$this->form_validation->set_rules('major_class_id', 'Major Class ID', 'trim|required|numeric|min_length[1]');
		
		if($this->form_validation->run() == TRUE) {
			$this->load->model('admin/major_class_model');
			$this->major_class_model->deleteMajorClass($this->input->post('major_class_id'));
			redirect('admin/major_class_list', 'refresh');	
		}
		else {
			echo '<h1>ERRORRRSKJZNMZN.......';
		}
	}
	
	function major_class_edit($major_class_id) {
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('admin/major_class_model');
		$data['user_data'] = $this->authorize->getSessionData();
		$data['major_class_data'] = $this->major_class_model->getMajorClassData($major_class_id);
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->form_validation->set_rules('mjr_class_name', 'Major Class Name', 'trim|required|min_length[1]|max_length[64]');				
		$this->form_validation->set_rules('major_class_title', 'Major Class Title', 'trim|required|min_length[1]|max_length[256]');
		$this->form_validation->set_rules('element_url_name', 'Element URL Name', 'trim|required|min_length[1]|max_length[256]|callback_CB_check_major_class_element_name');
		
		if($this->form_validation->run() == true) {
			$fields = array();
				$fields['active'] = $this->input->post('active');
				$fields['show_web'] = $this->input->post('show_web');
				$fields['mjr_class_name'] = $this->input->post('mjr_class_name');
				$fields['element_url_name'] = strtolower($this->input->post('element_url_name'));
				$fields['major_class_title'] = $this->input->post('major_class_title');
				$fields['meta_description'] = $this->input->post('meta_description');
				$fields['page_paragraph'] = $this->input->post('page_paragraph');
				
			$this->major_class_model->updateMajorClass($major_class_id, $fields);
			redirect('admin/major_class_list/', 'refresh');
		}
		else {
			$this->load->view('admin/major/major_class_edit_view', $data);	
		}
	}
	
	function major_class_list() {
		$this->load->model('admin/major_class_model');
		$data['yesno'] = array(0 => 'No', 1 => 'Yes');
		
		$data['user_data'] = $this->authorize->getSessionData();
		$data['major_classes'] = $this->major_class_model->getMajorClasses();
		
		$this->load->view('admin/major/major_class_list_view', $data);
	}
	
	function AJAX_major_class_edit($major_class_id) {
		$this->load->model('admin/major_class_model');
		$value = $this->input->post('value');
		$field = $this->input->post('id');
		
		$this->major_class_model->AJAX_updateMajorClassField($major_class_id, $field, $value);
		echo $value;
	}
	
	function CB_check_major_class_id($string) {
		$this->load->model('admin/major_class_model');
		$b = false;
		$this->form_validation->set_message('CB_check_major_class_id', 'A Major Class with this ID already Exists! <br /> Change the ID and try again.');
		$id = $this->major_class_model->checkMajorClassId($string);
		if(!$id) { //$id = false, no other names found, we're good to go
			$b = true;
		}
		return $b;
	}
	
	function CB_check_major_class_name($string) {
		$this->load->model('admin/major_class_model');
		$b = false;
		$this->form_validation->set_message('CB_check_major_class_name', 'A Major Class with this Name already Exists! <br /> Change the name and try again.');
		$name = $this->major_class_model->checkMajorClassName($string);
		if(!$name) { //$name = false, no other names found, we're good to go
			$b = true;
		}
		return $b;
	}
	
	function CB_check_major_class_element_name($string) {
		$b = false;
		$this->form_validation->set_message('CB_check_major_class_element_name', 'An Element URL name can only contain letters, numbers, and dashes. No spaces please');
		$pattern = '/^[a-zA-Z0-9\-]*$/';
		if(preg_match($pattern, $string)) { 
			$b = true;
		}
		return $b;
		
	}

}
?>