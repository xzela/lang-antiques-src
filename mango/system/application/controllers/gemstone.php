<?php
class Gemstone extends Controller {
	
	var $ci;
	
	function __construct() {
		parent::Controller();
		$this->load->library('authorize');
		$this->authorize->isLoggedIn();
		$this->ci =& get_instance();	
		
	}

	/*****************************************
	 * Admin options START here
	 * Admin options all start with:
	 * stone_*
	 * cuts_*
	 * dimaond_*
	 *  
	 * check out the config/routes.php for more information
	 */
	function cuts_add() {
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('inventory/gemstone_model');
		$this->load->model('utils/lookup_list_model');
		
		$data['user_data'] = $this->authorize->getSessionData();
		$this->form_validation->set_error_delimiters("<div class='error'>", "</div>");
		
		$this->form_validation->set_rules('cut_name', 'Cut/Shape Name', 'required|trim|min_length[1]|max_length[64]|callback_CB_check_cut_name');
		$this->form_validation->set_rules('seq', 'Sequence', 'required|trim|min_length[1]|max_length[11]|numeric');
		
		if($this->form_validation->run() == TRUE) {
			$fields = array();
				$fields['cut_name'] = $this->input->post('cut_name');
				$fields['seq'] = $this->input->post('seq');
				$fields['active'] = 1;
				
			$cut_id = $this->gemstone_model->insertCut($fields);
			redirect('admin/cuts_edit/' . $cut_id, 'refresh');
		}
		else {
			$this->load->view('admin/stone/cuts_add_view', $data);	
		}		
	}
	
	function cuts_delete() {
		$this->load->library('form_validation');
		$this->load->model('inventory/gemstone_model');
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->form_validation->set_rules('cut_id', 'Cut ID', 'trim|required|min_lenth[1]|max_length[11]');
		if($this->form_validation->run() == true) {
			$this->gemstone_model->deleteCut($this->input->post('cut_id'));
			redirect('admin/cuts_list', 'refresh');
		}
		else {
			echo '<h1>EORRROR...SDKLSJKJSKJJS!!!!</h1>';
		}
	}
	
	function cuts_edit($cut_id) {
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('inventory/gemstone_model');
		$this->load->model('utils/lookup_list_model');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['cut_data'] = $this->gemstone_model->getCutData($cut_id);
		$this->form_validation->set_error_delimiters('<div class="error"', '</div>');
		
		$this->form_validation->set_rules('cut_name', 'Cut/Shape Name', 'required|trim|min_length[1]|max_length[64]');
		$this->form_validation->set_rules('seq', 'Sequence', 'required|trim|min_length[1]|max_length[11]|numeric');
		
		if($this->form_validation->run() == true) {
			$fields = array();
				$fields['cut_name'] = $this->input->post('cut_name');
				$fields['seq'] = $this->input->post('seq');
				$fields['active'] = $this->input->post('active');
				
				$this->gemstone_model->updateCutRecord($cut_id, $fields);
				redirect('admin/cuts_edit/' . $cut_id, 'refresh');
		}
		else {
			$this->load->view('admin/stone/cuts_edit_view', $data);	
		}
	}
	function cuts_list() {
		$this->load->model('inventory/gemstone_model');
		$data['yesno'] = array(0 => 'No', 1 => 'Yes');
		$data['user_data'] = $this->authorize->getSessionData();
		$data['cuts'] = $this->gemstone_model->getAllStoneCutsData();
		
		
		$this->load->view('admin/stone/cuts_list_view', $data);
	}
	
	function stone_add() {
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('inventory/gemstone_model');
		$this->load->model('utils/lookup_list_model');
		
		$data['user_data'] = $this->authorize->getSessionData();
		$data['templates'] = $this->lookup_list_model->getStoneTemplateTypes();
		$this->form_validation->set_error_delimiters("<div class='error'>", "</div>");
		
		$this->form_validation->set_rules('stone_name', 'Stone Name', 'required|trim|min_length[1]|max_length[64]|callback_CB_check_stone_name');
		$this->form_validation->set_rules('template_type', 'Template Type', 'required|trim|min_length[1]|max_length[11]');
		
		if($this->form_validation->run() == TRUE) {
			$fields = array();
				$fields['stone_name'] = $this->input->post('stone_name');
				$fields['template_type'] = $this->input->post('template_type');
				
			$stone_id = $this->gemstone_model->insertStone($fields);
			redirect('admin/stone_edit/' . $stone_id, 'refresh');
		}
		else {
			$this->load->view('admin/stone/stones_add_view', $data);	
		}
	}
	
	function stone_delete() {
		$this->load->library('form_validation');
		$this->load->model('inventory/gemstone_model');
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->form_validation->set_rules('stone_id', 'Stone ID', 'trim|required|min_lenth[1]|max_length[11]|callback_CB_check_stone_id');
		if($this->form_validation->run() == true) {
			$this->gemstone_model->deleteStone($this->input->post('stone_id'));
			redirect('admin/stone_list', 'refresh');
		}
		else {
			echo '<h1>EORRROR...SDKLSJKJSKJJS!!!!</h1>';
		}
	}
	
	function stone_edit($stone_id) {
		$this->load->helper('form');
		$this->load->model('inventory/gemstone_model');
		$this->load->model('utils/lookup_list_model');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['templates'] = $this->lookup_list_model->getStoneTemplateTypes();
		$data['stone_data'] = $this->gemstone_model->getStoneData($stone_id);
		
		
		$this->load->view('admin/stone/stones_edit_view', $data);
	}
	
	function stone_list() {
		$this->load->model('inventory/gemstone_model');
		$data['yesno'] = array(0 => 'No', 1 => 'Yes');
		$data['user_data'] = $this->authorize->getSessionData();
		$data['stones'] = $this->gemstone_model->getAllStonesData();
		
		
		$this->load->view('admin/stone/stones_list_view', $data);
	}
	function diamond($id, $type = 'add', $d_id = null) {
		$this->load->helper('form');
		$this->load->helper('uri'); //custom helper
		$this->load->library('form_validation');
		$this->load->model('inventory/inventory_model');
		$this->load->model('inventory/gemstone_model');
		$this->load->model('inventory/diamond_model');
		$this->load->model('utils/lookup_list_model');
		
		//print_r(referrer_segment_array());
		//echo referrer_uri_string();
		$data['user_data'] = $this->authorize->getSessionData();
		$data['item_data'] = $this->inventory_model->getItemData($id);
		$data['gemstone_names'] = $this->gemstone_model->getGemstoneNames('diamond');
		
		$data['gemstone_cuts'] = $this->gemstone_model->getGemstoneCuts(true);
		
		$data['diamond_colors'] = $this->lookup_list_model->getDiamondColors();
		$data['diamond_clarities'] = $this->lookup_list_model->getDiamondClarities();
		$data['gemstone_scale'] = $this->lookup_list_model->getGemstoneScale();
		
			//total hack here
			//@TODO FIX ME //unset hack for gemstone cuts
			unset($data['gemstone_cuts'][0]);
			unset($data['gemstone_cuts']['']);
		/**
		 * Because Diamonds are specail we need to insert the diamond
		 * from the start. Because diamonds have special fields like color
		 * and clarity we need to add the diamond first... blah...
		 */
		if($type == 'add') {
			$fields = array();
				$fields['item_id'] = $id;
			$d_id = $this->diamond_model->insertDiamond($fields);
			
			$data['diamond_data'] = $this->diamond_model->getDiamondData($d_id);
			$data['selected_colors'] = $this->diamond_model->getDiamondColor($d_id);
			$data['selected_clarities'] = $this->diamond_model->getDiamondClarity($d_id);
			
			$this->load->view('inventory/gemstone/diamond_edit_view', $data);
		}
		if($type == 'edit') {
			$this->authorize->saveLastURL(); //saves the url
			
			$data['diamond_data'] = $this->diamond_model->getDiamondData($d_id);
			$data['selected_colors'] = $this->diamond_model->getDiamondColor($d_id);
			$data['selected_clarities'] = $this->diamond_model->getDiamondClarity($d_id);
			
			$this->form_validation->set_rules('d_quantity', 'Diamond Quanity', 'required|trim|numeric');
			
			if($this->form_validation->run() == true) {
				$fields = array();
					$fields['d_type_id'] = $this->input->post('gemstone_id'); //diamond type id (also knowns as gemstone type)
					$fields['is_center'] = $this->input->post('is_center');
					$fields['is_ranged'] = $this->input->post('is_ranged');
					$fields['d_carats'] = (float) $this->input->post('d_carats'); //carat weight
					$fields['d_quantity'] = $this->input->post('d_quantity');
					$fields['d_cut_id'] = $this->input->post('diamond_cut_id');
					$fields['other_color'] = $this->input->post('other_color');
					$fields['d_cut_grade_id'] = $this->input->post('diamond_cut_grade');
					if($fields['is_ranged']) {
						$fields['d_x1'] = (float) $this->input->post('d_rx1');
						$fields['d_x2'] = (float) $this->input->post('d_rx2');
					}
					else {
						$fields['d_x1'] = (float) $this->input->post('d_x1'); //must cast a float
						$fields['d_x2'] = (float) $this->input->post('d_x2');
						$fields['d_x3'] = (float) $this->input->post('d_x3');
					}					
					$fields['d_cert_by'] = $this->input->post('diamond_cert_by');
					$fields['d_cert_num'] = $this->input->post('diamond_cert_number');
					$fields['d_cert_date'] = $this->input->post('diamond_cert_date');
					$fields['d_report_num'] = $this->input->post('diamond_report_number');
					$fields['d_table_percnt'] = $this->input->post('diamond_table_percent'); //@TODO rename 'd_table_percnt' to 'd_table_percent'
					$fields['d_depth_percnt'] = $this->input->post('diamond_depth_percent'); //@TODO rename 'd_depth_percnt' to 'd_depth_percent'
					$fields['d_pavilion_depth'] = $this->input->post('diamond_pavilion_depth');
					$fields['d_crown_height'] = $this->input->post('diamond_crown_height');
					$fields['d_crown_angle'] = $this->input->post('diamond_crown_angle');
					$fields['d_girdle_thick'] = $this->input->post('diamond_girdle_thickness');
					$fields['d_culet'] = $this->input->post('diamond_culet');
					$fields['d_fluor'] = $this->input->post('diamond_fluorescence');
					$fields['d_polish'] = $this->input->post('diamond_polish');
					$fields['d_sym'] = $this->input->post('diamond_symmetry');
					$fields['country_origin'] = $this->input->post('country_origin');
					$fields['d_notes'] = $this->input->post('diamond_notes');
					$fields['average_color'] = $this->input->post('average_color');
					$fields['average_clarity'] = $this->input->post('average_clarity');
				
				$this->diamond_model->updateItemDiamond($data['diamond_data']['d_id'], $fields);
				
				redirect('inventory/diamond/' . $id . '/edit/' . $data['diamond_data']['d_id'],'refresh');
			}
			else {
				$this->load->view('inventory/gemstone/diamond_edit_view', $data);	
			}
			
			
		}
		if($type == 'remove') {
			$this->diamond_model->deleteDiamond($id, $d_id);	
			redirect('inventory/edit/' . $id, 'refresh');
		}
	}
	
	function diamond_clarity_add() {
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('inventory/diamond_model');
		
		$data['user_data'] = $this->authorize->getSessionData();
		$this->form_validation->set_error_delimiters("<div class='error'>", "</div>");
		
		$this->form_validation->set_rules('clarity_name', 'Clarity Name', 'required|trim|min_length[1]|max_length[64]|callback_CB_check_clarity_name');
		$this->form_validation->set_rules('clarity_abrv', 'Clarity Abrv', 'required|trim|min_length[1]|max_length[5]|callback_CB_check_clarity_abrv');
		$this->form_validation->set_rules('clarity_description', 'Clarity Description', 'trim');
		$this->form_validation->set_rules('seq', 'Clarity Sequence', 'required|trim|min_length[1]|max_length[11]|numeric');
		
		if($this->form_validation->run() == TRUE) {
			$fields = array();
				$fields['clarity_name'] = $this->input->post('clarity_name');
				$fields['clarity_abrv'] = $this->input->post('clarity_abrv');
				$fields['clarity_description'] = $this->input->post('clarity_description');
				$fields['seq'] = $this->input->post('seq');
				
			$clarity_id = $this->diamond_model->insertClarityData($fields);
			redirect('admin/diamond_clarity_edit/' . $clarity_id, 'refresh');
		}
		else {
			$this->load->view('admin/diamond/clarity_add_view', $data);	
		}
	}
	
	function diamond_clarity_delete() {
		$this->load->library('form_validation');
		$this->load->model('inventory/diamond_model');
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->form_validation->set_rules('clarity_id', 'Clarity ID', 'trim|required|min_lenth[1]|max_length[11]');
		if($this->form_validation->run() == true) {
			$this->diamond_model->deleteClarity($this->input->post('clarity_id'));
			redirect('admin/diamond_clarity_list', 'refresh');
		}
		else {
			echo '<h1>EORRROR...SDKLSJKJSKJJS!!!!</h1>';
		}
		
	}
	
	function diamond_clarity_edit($clarity_id) {
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('inventory/diamond_model');
		$data['user_data'] = $this->authorize->getSessionData();
		$data['clarity_data'] = $this->diamond_model->getClarityData($clarity_id);
		$data['clarity_data']['clarity_count'] = $this->diamond_model->getClarityCount($clarity_id);
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->form_validation->set_rules('clarity_name', 'Clarity Name', 'required|trim|min_length[1]|max_length[64]');
		$this->form_validation->set_rules('clarity_abrv', 'Clarity Abrv', 'required|trim|min_length[1]|max_length[5]');
		$this->form_validation->set_rules('clarity_description', 'Clarity Description', 'trim');
		$this->form_validation->set_rules('seq', 'Clarity Sequence', 'required|trim|min_length[1]|max_length[11]|numeric');
		
		if($this->form_validation->run() == true) {
			$fields = array();
				$fields['clarity_name'] = $this->input->post('clarity_name');
				$fields['clarity_abrv'] = $this->input->post('clarity_abrv');
				$fields['clarity_description'] = $this->input->post('clarity_description');
				$fields['seq'] = $this->input->post('seq');
			$this->diamond_model->updateClarityRecord($clarity_id, $fields);
			redirect('admin/diamond_clarity_edit/' . $clarity_id, 'refresh');
		}
		else {
			$this->load->view('admin/diamond/clarity_edit_view', $data);	
		}
	}
	
	function diamond_clarity_list() {
		$this->load->model('utils/lookup_list_model');
		$data['yesno'] = array(0 => 'No', 1 => 'Yes');
		$data['user_data'] = $this->authorize->getSessionData();
		$data['clarities'] = $this->lookup_list_model->getDiamondClarities();
		$this->load->view('admin/diamond/clarity_list_view', $data);
	}
	
	function diamond_color_add() {
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('inventory/diamond_model');
		
		$data['user_data'] = $this->authorize->getSessionData();
		$this->form_validation->set_error_delimiters("<div class='error'>", "</div>");
		
		$this->form_validation->set_rules('color_abrv', 'Color Abrv', 'required|trim|min_length[1]|max_length[5]|callback_CB_check_color_abrv');
		$this->form_validation->set_rules('color_description', 'Color Description', 'trim');
		$this->form_validation->set_rules('seq', 'Color Sequence', 'required|trim|min_length[1]|max_length[11]|numeric');
		
		if($this->form_validation->run() == TRUE) {
			$fields = array();
				$fields['color_abrv'] = $this->input->post('color_abrv');
				$fields['color_description'] = $this->input->post('color_description');
				$fields['seq'] = $this->input->post('seq');
				
			$color_id = $this->diamond_model->insertColorData($fields);
			redirect('admin/diamond_color_edit/' . $color_id, 'refresh');
		}
		else {
			$this->load->view('admin/diamond/color_add_view', $data);	
		}
	}
	
	function diamond_color_delete() {
		$this->load->library('form_validation');
		$this->load->model('inventory/diamond_model');
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->form_validation->set_rules('color_id', 'Color ID', 'trim|required|min_lenth[1]|max_length[11]');
		if($this->form_validation->run() == true) {
			$this->diamond_model->deleteColor($this->input->post('color_id'));
			redirect('admin/diamond_color_list', 'refresh');
		}
		else {
			echo '<h1>EORRROR...SDKLSJKJSKJJS!!!!</h1>';
		}
	}
	
	function diamond_color_edit($color_id) {
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('inventory/diamond_model');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['color_data'] = $this->diamond_model->getColorData($color_id);
		$data['color_data']['color_count'] = $this->diamond_model->getColorCount($color_id);
		
		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->form_validation->set_rules('color_abrv', 'Color Abrv', 'required|trim|min_length[1]|max_length[5]');
		$this->form_validation->set_rules('color_description', 'Color Description', 'trim');
		$this->form_validation->set_rules('seq', 'Color Sequence', 'required|trim|min_length[1]|max_length[11]|numeric');
		
		
		if($this->form_validation->run() == true) {
			$fields = array();
			$fields['color_abrv'] = $this->input->post('color_abrv');
			$fields['color_description'] = $this->input->post('color_description');
			$fields['seq'] = $this->input->post('seq');
			
			$this->diamond_model->updateColorRecord($color_id, $fields);
			
			redirect('admin/diamond_color_edit/' . $color_id, 'refresh');
		}
		else {
			$this->load->view('admin/diamond/color_edit_view', $data);
		}
	}
	
	function diamond_color_list() {
		$this->load->model('utils/lookup_list_model');
		$data['yesno'] = array(0 => 'No', 1 => 'Yes');
		$data['user_data'] = $this->authorize->getSessionData();
		$data['colors'] = $this->lookup_list_model->getDiamondColors();
		$this->load->view('admin/diamond/color_list_view', $data);
	}
	
	function gemstones($id, $type = 'add', $g_id = null) {
		$this->load->helper('form');
		$this->load->model('inventory/inventory_model');
		$this->load->model('inventory/gemstone_model');
		
		$data['user_data'] = $this->authorize->getSessionData();
		$data['item_data'] = $this->inventory_model->getItemData($id, false);
		$data['gemstone_names'] = $this->gemstone_model->getGemstoneNames('gemstone');
		$data['gemstone_cuts'] = $this->gemstone_model->getGemstoneCuts(true);
			//total hack here
			//@TODO FIX ME //unset hack for gemstone cuts
			unset($data['gemstone_cuts'][0]);
			unset($data['gemstone_cuts']['']);
		if($type == 'add') {
			//first insert a new blank gemstone;
			$fields = array();
				$fields['item_id'] = $id;
				$fields['gem_type_id'] = 35;
			$gem_id = $this->gemstone_model->applyItemGemstone($fields);
			$data['gemstone_data'] = $this->gemstone_model->getGemstoneData($gem_id);

			redirect('inventory/gemstone/' . $id . '/edit/' . $gem_id);
			
		}
		else if($type == 'edit') {
			$data['gemstone_data'] = $this->gemstone_model->getGemstoneData($g_id);
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('gem_type_id', 'Gemstone Type', 'trim|required|min_length[1]|numeric');
			$this->form_validation->set_rules('gem_quantity', 'Gemstone Quantity', 'trim|required|min_length[1]|numeric');
			$this->form_validation->set_rules('gem_carat', 'Gemstone Weight', 'trim|numeric');
			$this->form_validation->set_rules('gem_cut_id', 'Gemstone Cut', 'trim|numeric');
			$this->form_validation->set_rules('is_ranged', 'Is Ranged', 'trim|numeric');
			$this->form_validation->set_rules('is_center', 'Is Center', 'trim|numeric');
			$this->form_validation->set_rules('gem_x1', 'Dimensions x1', 'trim|numeric');
			$this->form_validation->set_rules('gem_x2', 'Dimensions x2', 'trim|numeric');
			$this->form_validation->set_rules('gem_x3', 'Dimensions x3', 'trim|numeric');
			$this->form_validation->set_rules('gem_range_x1', 'Dimensions Range x1', 'trim|numeric');
			$this->form_validation->set_rules('gem_range_x2', 'Dimensions Range x2', 'trim|numeric');
			
			
			if ($this->form_validation->run() == TRUE) {
				$fields = array();

					$fields['gem_type_id'] = $this->input->post('gem_type_id');
					$fields['gem_quantity'] = $this->input->post('gem_quantity');
					$fields['gem_carat'] = (float) $this->input->post('gem_carat'); //must declare float type
					$fields['gem_cut_id'] = $this->input->post('gem_cut_id');
					$fields['gem_cut_grade'] = $this->input->post('gem_cut_grade');
					$fields['is_ranged'] = $this->input->post('is_ranged');
					$fields['is_center'] = $this->input->post('is_center');
					
					if($fields['is_ranged'] == 0) {
						$fields['gem_x1'] = (float) $this->input->post('gem_x1'); //must declare float type
						$fields['gem_x2'] = (float) $this->input->post('gem_x2'); //must declare float type
						$fields['gem_x3'] = (float) $this->input->post('gem_x3'); //must declare float type
					}
					else {
						$fields['gem_x1'] = (float) $this->input->post('gem_range_x1'); //must declare float type
						$fields['gem_x2'] = (float) $this->input->post('gem_range_x2'); //must declare float type
						$fields['gem_x3'] = null;
					}
					
					$fields['gem_style'] = $this->input->post('gem_style');
					$fields['gem_hue'] = $this->input->post('gem_hue');
					$fields['gem_tone'] = $this->input->post('gem_tone');
					$fields['gem_clarity'] = $this->input->post('gem_clarity');
					$fields['gem_brill'] = $this->input->post('gem_brill');
					$fields['gem_intent'] = $this->input->post('gem_intent');
					$fields['gem_prop'] = $this->input->post('gem_prop');
					$fields['gem_finish'] = $this->input->post('gem_finish');
					$fields['gem_dialogue_number'] = $this->input->post('gem_dialogue_number');
					$fields['country_origin'] = $this->input->post('country_origin');
					$fields['gem_notes'] = $this->input->post('gem_notes');
					
					$fields['gem_phenomenon'] = $this->input->post('gem_phenomenon');
					$fields['gem_cert_by'] = $this->input->post('gem_cert_by');
					$fields['gem_cert_date'] = $this->input->post('gem_cert_date');
					$fields['gem_cert_number'] = $this->input->post('gem_cert_number');
					$fields['gem_cert_notes'] = $this->input->post('gem_cert_notes');
					
					$this->gemstone_model->updateItemGemstone($id, $g_id, $fields);
					
					redirect('inventory/gemstone/' . $id . '/edit/' . $g_id, 'refresh');
			}
			else {
				$this->load->view('inventory/gemstone/gemstone_edit_view', $data);
			}
		}
		if($type == 'remove') {
			$this->gemstone_model->removeGemstone($id, $g_id);	
			redirect('inventory/edit/' . $id, 'refresh');
		}
	}
	
	function jadeite($id, $type = null, $j_id = null) {
		$this->load->helper('form');
		$this->load->model('inventory/inventory_model');
		$this->load->model('inventory/gemstone_model');
		$this->load->model('inventory/jadeite_model');
		
		
		$data['user_data'] = $this->authorize->getSessionData();
		$data['item_data'] = $this->inventory_model->getItemData($id);
		$data['gemstone_names'] = $this->gemstone_model->getGemstoneNames('jadeite');
		$data['gemstone_cuts'] = $this->gemstone_model->getGemstoneCuts();
			//total hack here
			//@TODO FIX ME //unset hack for gemstone cuts
			unset($data['gemstone_cuts'][0]);
			unset($data['gemstone_cuts']['']);
		if($type == 'add') {
			//first insert a blank Jade
			$fields = array();
				$fields['item_id'] = $id;
				$fields['j_type_id'] = 19; //default jade
			$jade_id = $this->jadeite_model->insertJadeite($fields);
			
			redirect('inventory/jadeite/' . $id . '/edit/' . $jade_id, 'refreah');
			
		}
		else if($type == 'edit') {
			$this->load->library('form_validation');
			$data['jadeite_data'] = $this->jadeite_model->getJadeiteData($j_id);
			
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			
			$this->form_validation->set_rules('j_type_id', 'Jadeite Type', 'trim|required|min_length[1]|numeric');
			$this->form_validation->set_rules('j_quantity', 'Jadeite Quantity', 'trim|required|min_length[1]|numeric');
			$this->form_validation->set_rules('j_carat', 'Jadeite Carats', 'trim|numeric');
			$this->form_validation->set_rules('is_ranged', 'Is Ranged', 'trim|numeric');
			$this->form_validation->set_rules('is_center', 'Is Center', 'trim|numeric');
			$this->form_validation->set_rules('j_x1', 'Dimensions x1', 'trim');
			$this->form_validation->set_rules('j_x2', 'Dimensions x2', 'trim');
			$this->form_validation->set_rules('j_x3', 'Dimensions x3', 'trim');
			$this->form_validation->set_rules('j_range_x1', 'Dimensions Range x1', 'trim');
			$this->form_validation->set_rules('j_range_x2', 'Dimensions Range x2', 'trim');
			/*Extra stuff*/
			$this->form_validation->set_rules('j_style_id', 'Jadeite Style', 'trim');
			$this->form_validation->set_rules('j_cut', 'Jadeite Cut', 'trim');
			$this->form_validation->set_rules('j_cut_grade', 'Jadeite Cut Grade', 'trim');
			$this->form_validation->set_rules('ils_hue', 'ILS Hue', 'trim');
			$this->form_validation->set_rules('ils_tone', 'ILS Tone', 'trim');
			$this->form_validation->set_rules('ils_inten', 'ILS Intensity', 'trim');
			$this->form_validation->set_rules('fls_hue', 'FLS Hue', 'trim');
			$this->form_validation->set_rules('fls_tone', 'FLS Tone', 'trim');
			$this->form_validation->set_rules('fls_inten', 'FLS Intensity', 'trim');
			$this->form_validation->set_rules('j_clarity', 'Jadeite Clarity', 'trim');
			$this->form_validation->set_rules('j_brill', 'Jadeite Brilliancy', 'trim');
			$this->form_validation->set_rules('j_prop', 'Jadeite Proportions', 'trim');
			$this->form_validation->set_rules('j_finish', 'Jadeite Finish', 'trim');
			$this->form_validation->set_rules('j_dialogue_number', 'Jadeite Dialogue Number', 'trim');			
			$this->form_validation->set_rules('j_notes', 'Jadeite Notes', 'trim');
			
			if ($this->form_validation->run() == TRUE) {
				$fields = array();
					
					$fields['j_type_id'] = $this->input->post('j_type_id');
					$fields['j_quantity'] = $this->input->post('j_quantity');
					$fields['j_carat'] = (float) $this->input->post('j_carat'); //must declare float type
					$fields['j_cut'] = $this->input->post('j_cut');
					$fields['is_ranged'] = $this->input->post('is_ranged');
					$fields['is_center'] = $this->input->post('is_center');
					
					if($fields['is_ranged'] == 0) {
						$fields['j_x1'] = (float) $this->input->post('j_x1'); //must declare float type
						$fields['j_x2'] = (float) $this->input->post('j_x2'); //must declare float type
						$fields['j_x3'] = (float) $this->input->post('j_x3'); //must declare float type
					}
					else {
						$fields['j_x1'] = (float) $this->input->post('j_range_x1'); //must declare float type
						$fields['j_x2'] = (float) $this->input->post('j_range_x2'); //must declare float type
						$fields['j_x3'] = null;
					}
					
					$fields['j_cut_grade'] = $this->input->post('j_cut_grade'); 
					$fields['j_style_id'] = $this->input->post('j_style_id'); //is a varchar @TODO fix j_style_id (not int)
					$fields['j_clarity'] = $this->input->post('j_clarity');
					$fields['j_brill'] = $this->input->post('j_brill');
					$fields['j_prop'] = $this->input->post('j_prop');
					$fields['j_finish'] = $this->input->post('j_finish');
					
					$fields['ils_hue'] = $this->input->post('ils_hue');
					$fields['ils_tone'] = $this->input->post('ils_tone');
					$fields['ils_inten'] = $this->input->post('ils_inten');
					$fields['fls_hue'] = $this->input->post('fls_hue');
					$fields['fls_tone'] = $this->input->post('fls_tone');
					$fields['fls_inten'] = $this->input->post('fls_inten');
					
					$fields['j_notes'] = $this->input->post('j_notes');
					$fields['j_dialogue_number'] = $this->input->post('j_dialogue_number');
					
					$this->jadeite_model->updateItemJadeite($id, $j_id, $fields);
					
					redirect('inventory/jadeite/' . $id . '/edit/' . $j_id, 'refresh');
			}
			else {
				$this->load->view('inventory/gemstone/jadeite_edit_view', $data);
			}
		}
		if($type == 'remove') {
			$this->jadeite_model->deleteJadeite($id, $j_id);	
			redirect('inventory/edit/' . $id, 'refresh');
		}		
	}
	
	function opal($id, $type = null, $o_id = null) {
		$this->load->helper('form');
		$this->load->model('inventory/inventory_model');
		$this->load->model('inventory/gemstone_model');
		$this->load->model('inventory/opal_model');
		
		
		$data['user_data'] = $this->authorize->getSessionData();
		$data['item_data'] = $this->inventory_model->getItemData($id);
		$data['gemstone_names'] = $this->gemstone_model->getGemstoneNames('opal');
		$data['gemstone_cuts'] = $this->gemstone_model->getGemstoneCuts();
			//total hack here
			//@TODO FIX ME //unset hack for gemstone cuts
			unset($data['gemstone_cuts'][0]);
			unset($data['gemstone_cuts']['']);
		if($type == 'add') {
			//insert blank opal
			$fields = array();
				$fields['item_id'] = $id;
				$fields['o_type_id'] = 27; //default opal id
			$opal_id = $this->opal_model->insertOpal($fields);
			
			redirect('inventory/opal/' . $id . '/edit/' . $opal_id, 'refresh');
		}
		else if($type == 'edit') {
			$data['opal_data'] = $this->opal_model->getOpalData($o_id);			
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			
			$this->form_validation->set_rules('o_type_id', 'Opal Type', 'trim|required|min_length[1]|numeric');
			$this->form_validation->set_rules('o_quantity', 'Opal Quantity', 'trim|required|min_length[1]|numeric');
			$this->form_validation->set_rules('o_carat', 'Opal Carats', 'trim|numeric');
			$this->form_validation->set_rules('o_cut_id', 'Opal Cut/Shape', 'trim|numeric');
			$this->form_validation->set_rules('is_ranged', 'Is Ranged', 'trim|numeric');
			$this->form_validation->set_rules('is_center', 'Is Center', 'trim|numeric');
			$this->form_validation->set_rules('o_x1', 'Dimensions x1', 'trim|numeric');
			$this->form_validation->set_rules('o_x2', 'Dimensions x2', 'trim|numeric');
			$this->form_validation->set_rules('o_x3', 'Dimensions x3', 'trim|numeric');
			$this->form_validation->set_rules('o_range_x1', 'Dimensions x1', 'trim|numeric');
			$this->form_validation->set_rules('o_range_x2', 'Dimensions x2', 'trim|numeric');
			
			/*Extras*/
			$this->form_validation->set_rules('o_color', 'Opal Color', 'trim');
			$this->form_validation->set_rules('o_trans', 'Opal Transparency', 'trim');
			$this->form_validation->set_rules('o_prim_hue', 'Opal Primary Hue', 'trim');
			$this->form_validation->set_rules('o_secon_hue', 'Opal Secondary Hue', 'trim');
			$this->form_validation->set_rules('o_hue_inten', 'Opal Hue Intensity', 'trim');
			$this->form_validation->set_rules('o_satur', 'Opal Saturation', 'trim');
			$this->form_validation->set_rules('o_pattern', 'Opal Pattern', 'trim');
			
			$this->form_validation->set_rules('o_notes', 'Opal Notes', 'trim');
			
			if ($this->form_validation->run() == TRUE) {
				
				$fields = array();
					$fields['item_id'] = $id;
					$fields['o_type_id'] = $this->input->post('o_type_id');
					$fields['o_quantity'] = $this->input->post('o_quantity');
					$fields['o_carat'] = (float) $this->input->post('o_carat'); //must declare float type
					$fields['o_cut_id'] = $this->input->post('o_cut_id');
					$fields['is_ranged'] = $this->input->post('is_ranged');
					$fields['is_center'] = $this->input->post('is_center');
					
					if($fields['is_ranged'] == 0) {
						$fields['o_x1'] = (float) $this->input->post('o_x1'); //must declare float type
						$fields['o_x2'] = (float) $this->input->post('o_x2'); //must declare float type
						$fields['o_x3'] = (float) $this->input->post('o_x3'); //must declare float type
					}
					else {
						$fields['o_x1'] = (float) $this->input->post('o_x1'); //must declare float type
						$fields['o_x2'] = (float) $this->input->post('o_x2'); //must declare float type
						$fields['o_x3'] = null;
					}
										
					$fields['o_color'] = $this->input->post('o_color');
					$fields['o_trans'] = $this->input->post('o_trans');
					$fields['o_prim_hue'] = $this->input->post('o_prim_hue');
					$fields['o_secon_hue'] = $this->input->post('o_secon_hue');
					$fields['o_hue_inten'] = $this->input->post('o_hue_inten');
					$fields['o_satur'] = $this->input->post('o_satur');
					$fields['o_pattern'] = $this->input->post('o_pattern');
					$fields['country_origin'] = $this->input->post('country_origin');
					$fields['o_notes'] = $this->input->post('o_notes');
					

					$this->opal_model->updateItemOpal($id, $o_id, $fields);
					
					redirect('inventory/opal/' . $id . '/edit/' . $o_id, 'refresh');
			}
			else {
				$this->load->view('inventory/gemstone/opal_edit_view', $data);
			}
		}
		if($type == 'remove') {
			$this->opal_model->deleteOpal($id, $o_id);	
			redirect('inventory/edit/' . $id, 'refresh');
			
		}
	}	
	
	function pearl($id, $type = null, $p_id = null) {
		$this->load->helper('form');
		$this->load->model('inventory/inventory_model');
		$this->load->model('inventory/gemstone_model');
		$this->load->model('inventory/pearl_model');
		
		
		$data['user_data'] = $this->authorize->getSessionData();
		$data['item_data'] = $this->inventory_model->getItemData($id);
		$data['gemstone_names'] = $this->gemstone_model->getGemstoneNames('pearl');
		$data['gemstone_cuts'] = $this->gemstone_model->getGemstoneCuts();
			//total hack here
			//@TODO FIX ME //unset hack for gemstone cuts
			unset($data['gemstone_cuts'][0]);
			unset($data['gemstone_cuts']['']);
		if($type == 'add') {
			//first insert a new blank gemstone;
			$fields = array();
				$fields['item_id'] = $id;
				$fields['p_type_id'] = 29; //default pearl
			$pearl_id = $this->pearl_model->insertPearl($fields);
			$data['pearl_data'] = $this->pearl_model->getPearlData($pearl_id);

			redirect('inventory/pearl/' . $id . '/edit/' . $pearl_id);			
		}
		else if($type == 'edit') {
			$data['pearl_data'] = $this->pearl_model->getPearlData($p_id);
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			
			$this->form_validation->set_rules('p_type_id', 'Pearl Type', 'trim|required|min_length[1]|numeric');
			$this->form_validation->set_rules('p_quantity', 'Pearl Quantity', 'trim|required|min_length[1]|numeric');
			$this->form_validation->set_rules('p_weight', 'Pearl Weight', 'trim|numeric');
			$this->form_validation->set_rules('is_ranged', 'Is Ranged', 'trim|numeric');
			$this->form_validation->set_rules('is_center', 'Is Center', 'trim|numeric');
			$this->form_validation->set_rules('p_x1', 'Dimensions x1', 'trim|numeric');
			$this->form_validation->set_rules('p_x2', 'Dimensions x2', 'trim|numeric');
			/*Extras*/
			$this->form_validation->set_rules('p_shape', 'Pearl Shape', 'trim');
			$this->form_validation->set_rules('p_color', 'Pearl Color', 'trim');
			$this->form_validation->set_rules('p_cont', 'Pearl Continuity', 'trim');
			$this->form_validation->set_rules('p_luster', 'Pearl Luster', 'trim');
			$this->form_validation->set_rules('p_sphere', 'Pearl Sphericity', 'trim');
			$this->form_validation->set_rules('p_thick', 'Pearl Nacre Thinkness', 'trim');
			$this->form_validation->set_rules('p_blemish', 'Pearl Blemishes', 'trim');
			$this->form_validation->set_rules('p_notes', 'Pearl Notes', 'trim');
									
			if ($this->form_validation->run() == TRUE) {
				$fields = array();

					$fields['p_type_id'] = $this->input->post('p_type_id');
					$fields['p_weight'] = (float) $this->input->post('p_weight'); //must cast as float
					$fields['p_quantity'] = $this->input->post('p_quantity');
					$fields['p_shape'] = $this->input->post('p_shape');
					$fields['is_ranged'] = $this->input->post('is_ranged');
					$fields['is_center'] = $this->input->post('is_center');
					
					$fields['p_x1'] = (float) $this->input->post('p_x1'); //must declare float type
					$fields['p_x2'] = (float) $this->input->post('p_x2'); //must declare float type
					
					$fields['p_color'] = $this->input->post('p_color');
					$fields['p_cont'] = $this->input->post('p_cont');
					$fields['p_luster'] = $this->input->post('p_luster');
					$fields['p_sphere'] = $this->input->post('p_sphere');
					$fields['p_thick'] = $this->input->post('p_thick');
					$fields['p_blemish'] = $this->input->post('p_blemish');
					$fields['country_origin'] = $this->input->post('country_origin');
					$fields['p_notes'] = $this->input->post('p_notes');
					
					$this->pearl_model->updateItemPearl($id, $p_id, $fields);
					
					redirect('inventory/pearl/' . $id . '/edit/' . $p_id, 'refresh');
			}
			else {
				$this->load->view('inventory/gemstone/pearl_edit_view', $data);
			}
		}
		if($type == 'remove') {
			$this->pearl_model->deletePearl($id, $p_id);	
			redirect('inventory/edit/' . $id, 'refresh');
			
		}
	}

	function AJAX_cut_edit($cut_id) {
		$this->load->model('inventory/gemstone_model');
		$column = $this->input->post('id');
		$value = $this->input->post('value');
		
		$this->gemstone_model->AJAX_updateCutField($cut_id, $column, $value);
		echo $value;
	}		
		
	function AJAX_stone_edit($stone_id) {
		$this->load->model('inventory/gemstone_model');
		$column = $this->input->post('id');
		$value = $this->input->post('value');
		
		$this->gemstone_model->AJAX_updateStoneField($stone_id, $column, $value);
		echo $value;
	}
		
	function AJAX_diamondColor() {
		$this->load->model('inventory/diamond_model');
		$action = $this->input->post('action');
		$fields = array();
			$fields['diamond_id'] = $this->input->post('diamond');
			$fields['color_id'] = $this->input->post('color');
		if($action) {
			$fields['item_id'] = $this->input->post('id');
			$this->diamond_model->appendColor($fields);
		}
		else {
			$this->diamond_model->removeColor($this->input->post('id'), $fields);
		}
		echo $action;
	}
	
	function AJAX_diamondClarity() {
		$this->load->model('inventory/diamond_model');
		$action = $this->input->post('action');
		$fields = array();
			
			$fields['diamond_id'] = $this->input->post('diamond');
			$fields['clarity_id'] = $this->input->post('clarity');
		if($action) {
			$fields['item_id'] = $this->input->post('id');
			$this->diamond_model->appendClarity($fields);
		}
		else {
			
			$this->diamond_model->removeClarity($this->input->post('id'), $fields);
		}
		echo $action;
	}
	
	function AJAX_updateGemstoneField($stone, $id, $field, $data_type = false) {
		$this->load->helper('form');
		$this->load->model('inventory/inventory_model');

		$value = $this->input->post('value');

		if($stone == 'gemstone') { //type = gemstone
			$this->load->model('inventory/gemstone_model');
			$this->gemstone_model->AJAX_updateGemstoneField($id, $field, $value);
		}
		if($stone == 'pearl') {
			$this->load->model('inventory/pearl_model');
			$this->pearl_model->AJAX_updatePearlField($id, $field, $value);
		}
		if($stone == 'diamond') {
			$this->load->model('inventory/diamond_model');
			$this->diamond_model->AJAX_updateDiamondField($id, $field, $value);
		}
		if($stone == 'jadeite') {
			$this->load->model('inventory/jadeite_model');
			$this->jadeite_model->AJAX_updateJadeiteField($id, $field, $value);
		}
		if($stone == 'opal') {
			$this->load->model('inventory/opal_model');
			$this->opal_model->AJAX_updateOpalField($id, $field, $value);
		}
		
		echo $value;
	}
	
	function CB_check_cut_name($string) {
		$this->load->model('inventory/gemstone_model');
		$b = false;
		$g = $this->gemstone_model->checkCutName($string);
		$this->form_validation->set_message('CB_check_cut_name', 'Cut/Shape name already found. <br /> Change the name and try again.'); 
		if(!$g) { //$g returns true, cut/shape name found
			$b = true;
		}
		return $b;
	}
	
	function CB_check_stone_id($string) {
		$this->load->model('inventory/gemstone_model');
		$b = false;
		$g = $this->gemstone_model->checkStoneId($string);
		$this->form_validation->set_message('CB_check_gemstone_id', 'Something wicked has happened <br /> No Stone by THIS id: ' . $string);
		if(!$g) { //$g returns true, gemstone found
			$b = true;
		}
		return $b;
	}
	
	function CB_check_stone_name($string) {
		$this->load->model('inventory/gemstone_model');
		$b = false;
		$g = $this->gemstone_model->checkStoneName($string);
		$this->form_validation->set_message('CB_check_stone_name', 'Stone name already found. <br /> Change the name and try again.'); 
		if(!$g) { //$g = false, no gemstone names found
			$b = true;			
		}
		return $b;
	}
		
	function CB_check_clarity_name($string) {
		$this->load->model('inventory/diamond_model');
		$b = false;
		$g = $this->diamond_model->CB_checkClarityName($string);
		$this->form_validation->set_message('CB_check_clarity_name', 'Clarity name already found. Change the name and try again.'); 
		if(!$g) { //$g = false, no names found
			$b = true;			
		}
		return $b;		
	}
	
	function CB_check_clarity_abrv($string) {
		$this->load->model('inventory/diamond_model');
		$b = false;
		$g = $this->diamond_model->CB_checkClarityAbrv($string);
		$this->form_validation->set_message('CB_check_clarity_abrv', 'Clarity Abbreviation already found. Change the abbreviation and try again.'); 
		if(!$g) { //$g = false, no abbreviation found
			$b = true;			
		}
		return $b;		
	}	
	function CB_check_color_name($string) {
		$this->load->model('inventory/diamond_model');
		$b = false;
		$g = $this->diamond_model->CB_checkColorName($string);
		$this->form_validation->set_message('CB_check_color_name', 'Color name already found. Change the name and try again.'); 
		if(!$g) { //$g = false, no names found
			$b = true;			
		}
		return $b;		
	}
		
	function CB_check_color_abrv($string) {
		$this->load->model('inventory/diamond_model');
		$b = false;
		$g = $this->diamond_model->CB_checkColorAbrv($string);
		$this->form_validation->set_message('CB_check_color_abrv', 'Color Abbreviation already found. Change the abbreviation and try again.'); 
		if(!$g) { //$g = false, no abbreviation found
			$b = true;			
		}
		return $b;		
	}
}
?>