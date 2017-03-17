<?php
class Material extends Controller {
	
	var $ci;
	
	function __constuct() {
		parent::Controller();
		$this->ci =& get_instance();	
	}
	
	function material_add() {
		$this->load->library('authorize');
		$this->load->library('form_validation');
		$this->load->model('inventory/material_model');
		
		$data['user_data'] = $this->authorize->getSessionData();
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->form_validation->set_rules('material_name', 'Material Name', 'trim|required|min_length[3]|max_length[256]|callback_CB_check_material');
		$this->form_validation->set_rules('has_karats', 'Has Karats?', 'trim|required');
		if ($this->form_validation->run() == TRUE) {
			$fields = array();
				$fields['material_name'] = $this->input->post('material_name');
				$fields['karats'] = $this->input->post('has_karats');
				
			$material_id = $this->material_model->insertMaterial($fields);
			
			redirect('admin/material_edit/' . $material_id, 'refresh');
		}
		else {
			$this->load->view('admin/material/materials_add_view', $data);	
		}
	}
	
	function material_delete($material_id) {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('material_id', 'Material ID', 'trim|required|numeric|min_length[1]');
		
		if($this->form_validation->run() == TRUE) {
			$this->load->model('inventory/material_model');
			$this->material_model->deleteMaterial($this->input->post('material_id'));
			redirect('admin/material_list', 'refresh');	
		}
		else {
			echo '<h1>ERRORRRSKJZNMZN.......';
		}	
	}	
	
	function material_edit($material_id) {
		$this->load->library('authorize');
		$this->load->model('inventory/material_model');
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$data['user_data'] = $this->authorize->getSessionData();
		$data['material_data'] = $this->material_model->getMaterialData($material_id);
		$data['material_data']['item_count'] = $this->material_model->getMaterialCount($material_id);
		
		$this->form_validation->set_rules('material_name', 'Material Name', 'trim|required|min_length[3]|max_length[256]');
		
		if($this->form_validation->run() == true) {
			$fields = array();
				$fields['material_name'] = $this->input->post('material_name');
				$fields['active'] = $this->input->post('active');
				$fields['karats'] = $this->input->post('karats');
				$this->material_model->updateMaterial($material_id, $fields);
				redirect('admin/material_edit/' . $material_id, 'refresh');
		}
		else {
			$this->load->view('admin/material/materials_edit_view', $data);
		}
		
	}
	
	/**
	 * Lists all of the Materials
	 * 
	 * This is part of the View
	 */
	function material_list() {
		$this->load->library('authorize');
		$this->load->model('inventory/material_model');
		$this->authorize->isLoggedIn();
		
		$data['yesno'] = array(0 => 'No', 1 => 'Yes');
		$data['user_data'] = $this->authorize->getSessionData();
		$data['materials'] = $this->material_model->getAllMaterials();
		
		$this->load->view('admin/material/materials_list_view', $data);	
	}

	

	/**
	 * Applies a Material to an item. If no $karat is supplied, 
	 * then it's assumed that the material does not require a karat value. 
	 *
	 * @param [int] $id = item id
	 * @param [int] $material_id = material id
	 * @param [null|string] $karat {null=no karat, [string]=karat}
	 * 
	 * @return null
	 */
	function AJAX_applyMaterial() {
		$this->load->model('inventory/material_model');
		$fields = array();
			$fields['item_id'] = $this->input->post('id');
			$fields['material_id'] = $this->input->post('material_id');
			$fields['karat'] = $this->input->post('karat');
		
		echo $this->material_model->applyMaterial($fields);
	}
	
	/**
	 * Returns the name of a given material
	 *
	 * @param [int] $material_id = material id
	 * 
	 * @return [string] = echoes back the name of the material
	 */
	function AJAX_getMaterialName($material_id) {
		$this->load->model('inventory/material_model');		
		$name = $this->material_model->getMaterialName($material_id);
		echo $name; //returns the name of the material
	}
	
	/**
	 * An AJAX call which updates the Material Fields
	 * 
	 * @param [int] $material_id = matrial id
	 * 
	 * @return [string] Change value
	 */
	function AJAX_material_edit($material_id) {
		$this->load->model('inventory/material_model');
		$value = $this->input->post('value');
		$column = $this->input->post('id');
		
		$this->material_model->AJAX_updateMaterialField($material_id, $column, $value);
		echo $value;
	}
	
	/**
	 * An AJAX call which Tests whether the 
	 * material requires a Karat value or not.
	 * 
	 * @param [int] $material_id = the material id
	 */
	function AJAX_material_test_karat($material_id) {
		$this->load->model('inventory/material_model');
		$b = $this->material_model->materialHasKarats($material_id);
		echo $b;
	}
	
	/**
	 * Removes a material from an item.
	 *
	 * @param [int] $id = item id
	 * @param [int] $material_id = material id
	 * 
	 * @return null
	 */
	function AJAX_removeMaterial() {
		$this->load->model('inventory/material_model');
		$item_material_id = $this->input->post('item_material_id');
			
		$this->material_model->removeMaterial($item_material_id);
	}
	
	/**
	 * Callback function to make sure a material is 
	 * not inserted with an already existing mateial 
	 * (ie same name)
	 * 
	 * @param $stirng
	 * @return bool
	 */
	function CB_check_material($string) {
		$this->load->model('inventory/material_model');
		$b = false;
		$mod = $this->material_model->checkMaterialNames($string);
		$this->form_validation->set_message('CB_check_material', 'Material with that Name Found. <br /> Change the name and try again.');
		if(!$mod) { //$mod = false, no material found 
			$b =  true;
		}
		return $b;
	}
}
?>