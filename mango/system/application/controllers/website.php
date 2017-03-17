<?php
class Website extends Controller {
	
	var $ci;
	
	function __construct() {
		parent::Controller();
		//Check The user to see if they are logged in
		$this->load->library('authorize');
		$this->authorize->isLoggedIn();
		
		$this->ci =& get_instance();
	}
	
	public function menu_list() {
		$data = array();
		
		$this->load->model('admin/website_model');
		$data['user_data'] = $this->authorize->getSessionData();
		$data['main_menu'] = $this->website_model->getMenuElements(1);
		$data['secondary_menu'] = $this->website_model->getMenuElements(2); //secondary menu;
		
		$this->load->view('admin/website/menu_list_view', $data);
	}
	
	
	public function menu_add() {
		$data = array();		
		$this->load->model('admin/website_model');
		$this->load->model('admin/major_class_model');
		$this->load->model('inventory/modifier_model');
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		$data['user_data'] = $this->authorize->getSessionData();
		$data['major_classes'] = $this->major_class_model->getWebActiveMajorClasses();
		$data['modifiers'] = $this->modifier_model->getTopLevelModifiers();
		
		$this->form_validation->set_rules('element_type', 'Element Type', 'required|trim|min_length[1]|numeric');
		$this->form_validation->set_rules('element_seq', 'Element Sequence', 'required|trim|min_length[1]|numeric');
		$this->form_validation->set_rules('element_menu', 'Which Menu', 'required|trim|min_length[1]|numeric');

		if($this->input->post('element_type') == 3) { //special
			$this->form_validation->set_rules('element_name', 'Element Name', 'required|trim|min_length[1]');
			$this->form_validation->set_rules('special_title', 'Element Title', 'required|trim|min_length[1]');
			$this->form_validation->set_rules('element_type_id', 'Element Type ID', 'required|trim|min_length[1]|numeric');
			$this->form_validation->set_rules('url_path', 'URL Path', 'required|trim|min_length[1]|alphanumeric');
			$this->form_validation->set_rules('url_argument', 'URL Argument', 'required|trim|min_length[1]|alphanumeric');
			$this->form_validation->set_rules('meta_description', 'Meta Description', 'required|trim|min_length[1]|max_length[256]alphanumeric');
		}
		else {
			$this->form_validation->set_rules('element_type', 'Element Type', 'required|trim|min_length[1]');
			$this->form_validation->set_rules('element_url_input1', 'Path', 'required|trim|min_length[1]|alphanumeric');
			$this->form_validation->set_rules('element_url_input2', 'Argument', 'required|trim|min_length[1]|alphanumeric');
		}
		
		if($this->form_validation->run() == true) {
			$fields = array();
				$fields['element_type'] = $this->input->post('element_type');
				$fields['element_seq'] = $this->input->post('element_seq');
				$fields['element_url'] = $this->input->post('element_url_input1') . $this->input->post('element_url_input2');
				$fields['element_menu'] = $this->input->post('element_menu');
				
				
				if($this->input->post('element_type') == 1) { //major class
					$fields['element_type_id'] = $this->input->post('top_major_class');
				}
				else if($this->input->post('element_type') == 2) { //modifier
					$fields['element_type_id'] = $this->input->post('top_modifier');
				}
				else {
					$fields['special_name'] = $this->input->post('element_name');
					$fields['special_title'] = $this->input->post('special_title');
					$fields['element_type_id'] = 0;
					$fields['element_url'] = $this->input->post('url_path') . $this->input->post('url_argument');
					$fields['page_paragraph'] = $this->input->post('page_paragraph');
					$fields['meta_description'] = $this->input->post('meta_description');
				}
				
				$this->website_model->addMenuElement($fields);
				redirect('admin/menu_list', 'refresh');
		}
		else {
			$this->load->view('admin/website/menu_add_view', $data);	
		}
		
		
	}
	
	public function menu_delete() {
		$this->load->model('admin/website_model');
		$this->load->helper('form');
		
		$element_id = $this->input->post('element_id');
		$menu_id = $this->input->post('menu_id');
		
		$this->website_model->deleteMenuElement($element_id, $menu_id);
		
		redirect('admin/menu_list', 'refresh');
	}
	
	public function menu_edit($element_id) {
		$data = array();
		$this->load->model('admin/website_model');
		$this->load->model('admin/major_class_model');
		$this->load->model('inventory/modifier_model');
		$this->load->model('utils/lookup_list_model');
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		$data['user_data'] = $this->authorize->getSessionData();
		$data['element_data'] = $this->website_model->getMenuElementData($element_id);
		$data['sub_elements'] = $this->website_model->getMenuSubElements($element_id);		
		$data['element_types'] = array('', 'Major Class', 'Modifier', 'Special Element');
		$data['sub_states'] = $this->lookup_list_model->getSubElementStates();
		
		if($data['element_data']['element_type'] == 3) {
			$this->form_validation->set_rules('element_name', 'Element Name', 'required|trim|min_length[1]|max_length[64]');
			$this->form_validation->set_rules('special_title', 'Element Title', 'trim|min_length[1]|max_length[256]');
			$this->form_validation->set_rules('meta_description', 'Meta Description', 'trim|min_length[1]|max_length[256]');
		}
		
		if($this->form_validation->run() == true) {
			$fields = array();
				$fields['special_name'] = $this->input->post('element_name');
				$fields['special_title'] = $this->input->post('special_title');
				$fields['meta_description'] = $this->input->post('meta_description');
				$fields['page_paragraph'] = $this->input->post('page_paragraph');
			$this->website_model->updateMenuElement($element_id, $fields);
			redirect('admin/menu_list', 'refresh');
		}
		else {
			$this->load->view('admin/website/menu_edit_view', $data);
		}
	}
	
	public function menu_reorder() {
		$data = array();
		
		$this->load->model('admin/website_model');
		$data['user_data'] = $this->authorize->getSessionData();
		$data['main_elements'] = $this->website_model->getMenuElements(1); //0=inactive, 1=main, 2=secondary
		$data['secondary_elements'] = $this->website_model->getMenuElements(2); //0=inactive, 1=main, 2=secondary
		
		$this->load->view('admin/website/menu_reorder_view', $data);		
	}
	
	public function menu_sub_status() {
		$this->load->model('admin/website_model');
		
		$parent_id = $this->input->post('element_id');
		$sub_element_id = $this->input->post('sub_element_id');
		if($this->input->post('status') == 1) { //disable it
			$this->website_model->updateSubMenuElementStatus($sub_element_id, 0);
		}
		else { //enabled it
			$this->website_model->updateSubMenuElementStatus($sub_element_id, 1);
		}
		redirect('admin/menu_edit/' . $parent_id, 'refresh');
	} 
	
	public function menu_sub_add($element_id) {
		$data = array();		
		$this->load->model('admin/website_model');
		$this->load->model('admin/major_class_model');
		$this->load->model('inventory/modifier_model');
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		$data['element_data'] = $this->website_model->getMenuElementData($element_id);
		$data['user_data'] = $this->authorize->getSessionData();
		$data['modifiers'] = $this->modifier_model->getNonTopLevelWebActiveModifiers($element_id);
		
		$this->form_validation->set_rules('sub_element_type', 'Sub Element Type', 'required|trim|min_length[1]|numeric');
		$this->form_validation->set_rules('sub_element_seq', 'Sub Element Sequence', 'required|trim|min_length[1]|numeric');

		if($this->input->post('element_type') == 3) { //special
			$this->form_validation->set_rules('sub_special_name', 'Sub Element Name', 'required|trim|min_length[1]');
			$this->form_validation->set_rules('sub_special_title', 'Sub Element Title', 'trim|min_length[1]');
			$this->form_validation->set_rules('sub_special_title', 'Sub Element Title', 'required|trim|min_length[1]');
			$this->form_validation->set_rules('element_type_id', 'Element Type ID', 'required|trim|min_length[1]|numeric');
			$this->form_validation->set_rules('url_path', 'URL Path', 'required|trim|min_length[1]|alphanumeric');
			$this->form_validation->set_rules('url_argument', 'URL Argument', 'required|trim|min_length[1]|alphanumeric');
			$this->form_validation->set_rules('sub_meta_description', 'Sub Meta Description', 'required|trim|min_length[1]|max_length[256]alphanumeric');
			$this->form_validation->set_rules('sub_page_paragraph', 'Sub Meta Description', 'trim|min_length[1]|alphanumeric');
		}
		else {
			$this->form_validation->set_rules('element_url_input1', 'Path', 'required|trim|min_length[1]|alphanumeric');
			$this->form_validation->set_rules('element_url_input2', 'Argument', 'required|trim|min_length[1]|alphanumeric');
		}
		
		if($this->form_validation->run() == true) {
			$fields = array();
				$fields['parent_element_id'] = $element_id;
				
				$fields['sub_element_type'] = $this->input->post('sub_element_type');
				$fields['sub_element_seq'] = $this->input->post('sub_element_seq');
				$fields['sub_element_url'] = $this->input->post('element_url_input1') . $this->input->post('element_url_input2');
				
				if($this->input->post('sub_element_type') == 2) { //modifier
					$fields['sub_element_type_id'] = $this->input->post('top_modifier');
				}
				else {
					$fields['sub_special_name'] = $this->input->post('sub_special_name');
					$fields['sub_special_title'] = $this->input->post('sub_special_title');
					$fields['sub_element_type_id'] = 0;
					$fields['sub_element_url'] = $this->input->post('url_path') . $this->input->post('url_argument');
					$fields['sub_meta_description'] = $this->input->post('meta_description');
					$fields['sub_page_paragraph'] = $this->input->post('sub_page_paragraph');
				}
				//print_r($fields);
				$this->website_model->addSubMenuElement($fields);
				redirect('admin/menu_edit/' . $element_id, 'refresh');
		}
		else {
			$this->load->view('admin/website/menu_sub_add_view', $data);	
		}		
	}
	
	public function menu_sub_delete() {
		$this->load->model('admin/website_model');			
		$this->website_model->deleteSubMenuElement($this->input->post('sub_element_id'), $this->input->post('parent_element_id'));
		
		redirect('admin/menu_edit/' . $this->input->post('parent_element_id'), 'refresh');
	}
	
	public function menu_sub_reorder($parent_id) {
		$data = array();		
		$this->load->model('admin/website_model');
		$data['element_data'] = $this->website_model->getMenuElementData($parent_id);
		$data['sub_elements'] = $this->website_model->getMenuSubElements($parent_id);		
		$data['user_data'] = $this->authorize->getSessionData();
		
		$this->load->view('admin/website/menu_sub_reorder_view', $data);
	}
	
	public function content_our_store() {
		$this->load->model('website/content/content_model');
		$this->load->library('form_validation');
		$data = array();
		$data['user_data'] = $this->authorize->getSessionData();
		$data['content'] = $this->content_model->get_content('our_store');
		
		$this->form_validation->set_rules('content','content','trim');
		
		if ($this->form_validation->run() == true) {
			$this->content_model->update_content('our_store', $this->input->post('content'));
			redirect('admin');
		}
		else {
			$this->load->view('admin/website/content/content_our_store_view', $data);
			
		}
	}
	
	public function content_our_staff() {
		$this->load->model('website/content/content_model');
		$this->load->library('form_validation');
		$data = array();
		$data['user_data'] = $this->authorize->getSessionData();
		$data['content'] = $this->content_model->get_content('our_staff');
		
		$this->form_validation->set_rules('content','content','trim');
		
		if ($this->form_validation->run() == true) {
			$this->content_model->update_content('our_staff', $this->input->post('content'));
			redirect('admin');
		}
		else {
			$this->load->view('admin/website/content/content_our_staff_view', $data);
			
		}
	}
	public function content_selling_jewelry() {
		$this->load->model('website/content/content_model');
		$this->load->library('form_validation');
		$data = array();
		$data['user_data'] = $this->authorize->getSessionData();
		$data['content'] = $this->content_model->get_content('selling_jewelry');
		
		$this->form_validation->set_rules('content','content','trim');
		
		if ($this->form_validation->run() == true) {
			$this->content_model->update_content('selling_jewelry', $this->input->post('content'));
			redirect('admin');
		}
		else {
			$this->load->view('admin/website/content/content_selling_jewelry_view', $data);
			
		}
	}	
	
	public function AJAX_updateMenuElementSeq() {
		$this->load->model('admin/website_model');
		$menu_id = $this->input->post('id');
		$order = explode(',', $this->input->post('order'));
		$this->website_model->updateMenuElementSeq($menu_id, $order);
	}
	
	public function AJAX_updateSubMenuElementSeq() {
		$this->load->model('admin/website_model');
		$parent_id = $this->input->post('parent_id');
		$menu_id = $this->input->post('id');
		$order = explode(',', $this->input->post('order'));
		$this->website_model->updateSubMenuElementSeq($parent_id, $order);
	}
	
	public function AJAX_getMajorClassURLName() {
		$this->load->model('admin/major_class_model');
		$major_class_id = $this->input->post('major_class_id'); 
		$data = $this->major_class_model->getMajorClassData($major_class_id);
		
		echo $data['element_url_name'];
	}
	
	public function AJAX_getModifierURLName() {
		$this->load->model('inventory/modifier_model');
		$modifier_id = $this->input->post('modifier_id'); 
		$data = $this->modifier_model->getModifierData($modifier_id);
		
		echo $data['element_url_name'];
	}
}
?>