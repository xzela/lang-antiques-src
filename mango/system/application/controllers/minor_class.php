<?php
class Minor_class extends Controller {
	
	var $ci;
	
	function __construct() {
		parent::Controller();
		//Check The user to see if they are logged in
		$this->load->library('authorize');
		$this->authorize->isLoggedIn();
		
		$this->ci =& get_instance();
		
	}
	

	
	function minor_class_add() {
		$this->load->library('form_validation');
		$this->load->model('admin/minor_class_model');
		
		$data['user_data'] = $this->authorize->getSessionData();
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->form_validation->set_rules('minor_class_id', 'Minor Class ID', 'trim|required|min_length[1]|max_length[11]|numeric|callback_CB_check_minor_class_id');
		$this->form_validation->set_rules('minor_class_name', 'Minor Class Name', 'trim|required|min_length[1]|max_length[64]|callback_CB_check_minor_class_name');
		if ($this->form_validation->run() == TRUE) {
			$fields = array();
				$fields['min_class_id'] = $this->input->post('minor_class_id');
				$fields['min_class_name'] = $this->input->post('minor_class_name');
				
			$minor_class_id = $this->minor_class_model->insertMinorClass($fields);
			
			redirect('admin/minor_class_edit/' . $minor_class_id, 'refresh');
		}
		else {
			$this->load->view('admin/minor/minor_class_add_view', $data);	
		}		
	}
	
	function minor_class_delete() {
		$this->load->library('form_validation');		
		$this->form_validation->set_rules('minor_class_id', 'Minor Class ID', 'trim|required|numeric|min_length[1]');
		
		if($this->form_validation->run() == TRUE) {
			$this->load->model('admin/minor_class_model');
			$this->minor_class_model->deleteMinorClass($this->input->post('minor_class_id'));
			redirect('admin/minor_class_list', 'refresh');
		}
		else {
			echo '<h1>ERRORRRSKJZNMZN.......';
		}
	}
	
	function minor_class_edit($minor_class_id) {
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$this->load->model('admin/minor_class_model');
		$data['user_data'] = $this->authorize->getSessionData();
		$data['minor_class_data'] = $this->minor_class_model->getMinorClassData($minor_class_id);
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->form_validation->set_rules('min_class_name', 'Minor Class Name', 'trim|required|min_length[1]|max_length[64]');
		
		if($this->form_validation->run() == true) {
			$fields = array();
				$fields['min_class_name'] = $this->input->post('min_class_name');
				$fields['active'] = $this->input->post('active');
				
				$this->minor_class_model->updateMinorClass($minor_class_id, $fields);
				redirect('admin/minor_class_edit/' . $minor_class_id, 'refresh');
		}
		else {
			$this->load->view('admin/minor/minor_class_edit_view', $data);	
		}
		
		
	}
	

	
	function minor_class_list() {
		$this->load->model('admin/minor_class_model');
		$data['yesno'] = array(0 => 'No', 1 => 'Yes');
		
		$data['user_data'] = $this->authorize->getSessionData();
		$data['minor_classes'] = $this->minor_class_model->getMinorClasses();
		
		$this->load->view('admin/minor/minor_class_list_view', $data);
	}
	function AJAX_minor_class_edit($minor_class_id) {
		$this->load->model('admin/minor_class_model');
		$value = $this->input->post('value');
		$column = $this->input->post('id');
		
		$this->minor_class_model->AJAX_updateMinorClassField($minor_class_id, $column, $value);
		
		echo $value;
	}
	function CB_check_minor_class_id($string) {
		$this->load->model('admin/minor_class_model');
		$b = false;
		$this->form_validation->set_message('CB_check_minor_class_id', 'A Minor Class with this ID already Exists! <br /> Change the ID and try again.');
		$id = $this->minor_class_model->checkMinorClassId($string);
		if(!$id) { //$id = false, no other names found, we're good to go
			$b = true;
		}
		return $b;
	}
	
	function CB_check_minor_class_name($string) {
		$this->load->model('admin/minor_class_model');
		$b = false;
		$this->form_validation->set_message('CB_check_minor_class_name', 'A Minor Class with this Name already Exists! <br /> Change the name and try again.');
		$name = $this->minor_class_model->checkMinorClassName($string);
		if(!$name) { //$name = false, no other names found, we're good to go
			$b = true;
		}
		return $b;
	}
	

	

}
?>