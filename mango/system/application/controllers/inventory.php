<?php
Class Inventory extends Controller {

	var $ci;

	function __construct() {
		parent::Controller();
		//Check The user to see if they are logged in
		$this->load->library('authorize');
		$this->authorize->isLoggedIn();
		$this->ci =& get_instance();
	}

	function index() {
		redirect('inventory_list/list_all_items'); //redirect
	}

	function add() {
		if($this->input->post('clear_seller')) {
			$this->session->unset_userdata('seller');
		}
		$this->load->model('inventory/inventory_model');
		$this->load->model('utils/lookup_list_model');
		$this->load->library('form_validation');


		$data['user_data'] = $this->authorize->getSessionData();
		$data['major_classes'] = $this->lookup_list_model->getMajorClasses();
		$data['minor_classes'] = $this->lookup_list_model->getMinorClasses();
		if($this->session->userdata('seller')) {
			$this->load->model('sales/invoice_model');
			$seller = $this->session->userdata('seller');
			if ($seller['type'] == 1) { //vendor, turn to two
				$seller['type'] = 2;
			}
			else if ($seller['type'] == 2) { //customer, turn to one
				$seller['type'] = 1;
			}
			$data['seller_data'] =  $this->invoice_model->getBuyerData($seller['id'], $seller['type']);
			$data['seller_data']['seller_date'] = $seller['date'];
		}
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		$this->form_validation->set_rules('major_class', 'Major Class', 'required|min_length[1]|max_length[5]');
		$this->form_validation->set_rules('minor_class', 'Minor Class', 'required|min_length[1]|max_length[5]');
		$this->form_validation->set_rules('item_name', 'Item Name', 'trim|required|min_lenth[1]|max_length[128]');
		if ($this->form_validation->run() == TRUE) {
			$fields = array();
				$fields['mjr_class_id'] = $this->input->post('major_class');
				$fields['min_class_id'] = $this->input->post('minor_class');
				$fields['item_name'] = $this->input->post('item_name');
				$fields['user_id'] = $data['user_data']['user_id'];
				$fields['entry_date'] = date("Y/m/d");
				if($this->session->userdata('seller') && $this->input->post('add_seller')) {
					$fields['seller_type'] = ($seller['type'] == 1) ? 2 : 1; //flip that god damn bit
					$fields['seller_id'] = $seller['id'];
					$fields['purchase_date'] = $seller['date'];
				}
			$item_id = $this->inventory_model->insertItem($fields);

			redirect('inventory/edit/' . $item_id, 'refresh');
		}
		else {
			$this->load->view('inventory/item_add_view', $data);
		}
	}

	function add_assembled() {
		$this->load->model('inventory/inventory_model');
		$this->load->model('inventory/assemble_model');
		$this->load->model('utils/lookup_list_model');
		$this->load->library('form_validation');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['major_classes'] = $this->lookup_list_model->getMajorClasses();
		$data['minor_classes'] = $this->lookup_list_model->getMinorClasses();

		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		$this->form_validation->set_rules('major_class', 'Major Class', 'required|min_length[1]|max_length[5]');
		$this->form_validation->set_rules('minor_class', 'Minor Class', 'required|min_length[1]|max_length[5]');
		$this->form_validation->set_rules('item_name', 'Item Name', 'trim|required|min_lenth[1]|max_length[128]');

		if ($this->form_validation->run() == TRUE) {
			$fields = array();
				$fields['mjr_class_id'] = $this->input->post('major_class');
				$fields['min_class_id'] = $this->input->post('minor_class');
				$fields['item_name'] = $this->input->post('item_name');
				$fields['user_id'] = $data['user_data']['user_id'];
				$fields['entry_date'] = date("Y/m/d");

			$item_id = $this->inventory_model->insertItem($fields);

			$this->assemble_model->createAssembleItem($item_id); //creates the assemble dependencies

			$this->inventory_model->AJAX_updateField($item_id, 'is_assembled', 1); //yes
			$this->inventory_model->AJAX_updateField($item_id, 'assemble_type', 1); //0=child, 1=parent, 2=assembled?

			redirect('inventory/assemble/' . $item_id, 'refresh');


		}
		else {
			$this->load->view('inventory/item_add_view', $data);
		}

	}

	function add_customer_seller($item_id) {
		$this->load->helper('form');
		$this->load->model('customer/customer_model');

		$fields = array();
			$fields['first_name'] = $this->input->post('first_name');
			$fields['last_name'] = $this->input->post('last_name');
			$fields['middle_name'] = $this->input->post('middle_name');
			$fields['spouse_first'] = $this->input->post('spouse_first');
			$fields['spouse_last'] = $this->input->post('spouse_last');
			$fields['spouse_middle'] = $this->input->post('spouse_middle');
			$fields['home_phone'] = $this->input->post('home_phone');
			$fields['work_phone'] = $this->input->post('work_phone');
			$fields['email'] = $this->input->post('email');
			$fields['address'] = $this->input->post('address');
			$fields['address2'] = $this->input->post('address2');
			$fields['city'] = $this->input->post('city');
			$fields['state'] = $this->input->post('state');
			$fields['zip'] = $this->input->post('zip');
			$fields['country'] = $this->input->post('country');
			$fields['notes'] = $this->input->post('notes');
		$customer_id = $this->customer_model->insertCustomer($fields);
		//then apply customer as seller
		$this->AJAX_apply_seller($item_id, 2, $customer_id);
		//then redirect to seller_edit
		redirect('inventory/seller/' . $item_id, 'refresh');
	}

	function add_vendor_seller($item_id) {
		$this->load->helper('form');
		$this->load->model('vendor/vendor_model');
		/*
		 * This is a total hack
		 * @TODO fix this hack code
		 */
		$fields = array();
		unset($_POST['vendor_sbmt']);
		$keys = array_keys($_POST);
		foreach($keys as $key) {
			if($this->input->post($key) != "") {
				$fields[$key] = $this->input->post($key);
			}
		}
		$vendor_id = $this->vendor_model->insertVendor($fields);
		//then apply customer as seller
		$this->AJAX_apply_seller($item_id, 1, $vendor_id);
		//then redirect to seller_edit
		redirect('inventory/seller/' . $item_id, 'refresh');
	}

	function assemble($item_id) {
		$this->authorize->saveLastURL(); //saves the url

		$this->load->model('inventory/inventory_model');
		$this->load->model('inventory/assemble_model');

		$data['item_data'] = $this->inventory_model->getInventoryData($item_id);

		//test to see if child or parent
		switch($data['item_data']['assemble_type']){
			case '': //nothing
				//probably not an assembled item
				redirect('inventory/edit/' . $item_id, 'refresh');
				break;
			case 0: //child
				redirect('inventory/assemble_child/' . $item_id, 'refresh');
				break;

			case 1: //parent
				$assemble = $this->assemble_model->getAssembleIdFromParent($item_id);
				if(sizeof($assemble) > 0) {
					$data['item_data']['assemble_id'] = $assemble['assemble_id'];
					$children = $this->assemble_model->getChildrenAssemblyData($assemble['assemble_id']);
					$data['assembled_items'] = array();
					foreach($children as $child) {
						$data['assembled_items'][$child['child_item_id']] = $this->inventory_model->getInventoryData($child['child_item_id']);
					}
				}
				$data['user_data'] = $this->authorize->getSessionData();

				$this->load->view('inventory/assemble_list_view', $data);
				break;

			case 2: //assembled?? - not sure
				//probably not an assembled item
				redirect('inventory/edit/' . $item_id, 'refresh');
				break;
		}

	}

	function assemble_add_item($assemble_id, $parent_id) {
		$this->load->model('inventory/inventory_model');
		$this->load->model('inventory/assemble_model');
		$this->load->model('utils/clone_model');

		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['item_data'] = $this->inventory_model->getInventoryData($parent_id);
		$data['item_data']['assemble_id'] = $assemble_id;

		$assemble = $this->assemble_model->getAssembleIdFromParent($parent_id);
		$children = $this->assemble_model->getChildrenAssemblyData($assemble['assemble_id']);
		$data['assembled_items'] = array();
		foreach($children as $child) {
			$data['assembled_items'][$child['child_item_id']] = $this->inventory_model->getInventoryData($child['child_item_id']);
		}

		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->form_validation->set_rules('item_number', 'Item Number', 'required|min_length[1]|max_length[25]|callback_CB_realnumber');

		if($this->form_validation->run() == TRUE) {
			$child_id = $this->inventory_model->getInventoryId($this->input->post('item_number'));
			//I should probably test to see if it's already been assembled, but i'm too lazy right
			//@TODO test for already assembled items

			$assemble_child_id = $this->assemble_model->insertChildItem($assemble_id, $child_id);
			$this->inventory_model->AJAX_updateField($child_id, 'is_assembled', 1); //yes
			$this->inventory_model->AJAX_updateField($child_id, 'assemble_type', 0); //0=child, 1=parent, 2=assembled?
			$this->inventory_model->AJAX_updateField($child_id, 'item_quantity', 0);
			$this->inventory_model->AJAX_updateField($child_id, 'item_status', 6); //6=assembled
			$fields = array();
				$fields['item_id'] = $child_id;
				$fields['user_id'] = $data['user_data']['user_id'];
				$fields['field_name'] = 'Assemble Note';
				$fields['old_value'] = '';
				$fields['new_value'] = 'Item Assembled on ' . date('m/d/Y');
			$this->assemble_model->insertHistoryNote($fields);

			//get child data;
			$child_data = $this->inventory_model->getItemData($child_id);
				$p = $child_data['purchase_price'];
			//get parent data
			$parent_data = $this->inventory_model->getItemData($parent_id);
				$this->inventory_model->AJAX_updateField($parent_id, 'purchase_price', $p + $parent_data['purchase_price']);
			//copy over stone info
			$this->clone_model->cloneDiamonds($child_id, $parent_id);
			$this->clone_model->cloneRecord($child_id, $parent_id, 'stone_info', 'gem_id');
			$this->clone_model->cloneRecord($child_id, $parent_id, 'jadeite_info', 'j_id');
			$this->clone_model->cloneRecord($child_id, $parent_id, 'opal_info', 'o_id');
			$this->clone_model->cloneRecord($child_id, $parent_id, 'pearl_info', 'p_id');

			//copy over job info
			$this->clone_model->cloneRecord($child_id, $parent_id, 'inventory_jobs', 'job_id');



			redirect('inventory/assemble/' . $parent_id, 'refresh');
		}
		else {
			$this->load->view('inventory/assemble_add_view', $data);
		}
	}

	function assemble_child($item_id) {
		//child
		$this->load->model('inventory/inventory_model');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['item_data'] = $this->inventory_model->getInventoryData($item_id);
		$assemble = $this->inventory_model->getAssembleIdFromChild($item_id);
		$parent = $this->inventory_model->getParentAssemblyData($assemble['assemble_id']);
		$data['parent_data'] = $this->inventory_model->getInventoryData($parent['parent_item_id']);

		$this->load->view('inventort/assemble_child_view', $data);
	}

	function assemble_create($item_id) {
		$this->load->model('inventory/inventory_model');
		$this->load->model('inventory/assemble_model');
		$this->load->helper('form');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['item_data'] = $this->inventory_model->getInventoryData($item_id);

		if($this->input->post('create_assemble') == 'true') {
			$this->assemble_model->createAssembleItem($item_id);
			$this->inventory_model->AJAX_updateField($item_id, 'is_assembled', 1); //yes
			$this->inventory_model->AJAX_updateField($item_id, 'assemble_type', 1); //0=child, 1=parent, 2=assembled?

			redirect('inventory/assemble/' . $item_id);
		}
		else {
			$this->load->view('inventory/assemble_create_view', $data);
		}
	}

	function assemble_remove_item($assemble_id, $child_id) {
		$this->load->model('inventory/assemble_model');
		$this->load->model('inventory/inventory_model');
		$data['user_data'] = $this->authorize->getSessionData();

		$this->assemble_model->removeChildItem($assemble_id, $child_id);
		$this->inventory_model->updateItem($child_id, array('item_status' => '1', 'item_quantity' => '1', 'is_assembled' => '0'));
		$parent = $this->assemble_model->getAssembleData($assemble_id);
		$fields = array();
			$fields['item_id'] = $child_id;
			$fields['user_id'] = $data['user_data']['user_id'];
			$fields['field_name'] = 'Assemble Note';
			$fields['old_value'] = '';
			$fields['new_value'] = 'Item was remove from assembly ' . date('m/d/Y');
		$this->assemble_model->insertHistoryNote($fields);

		redirect('inventory/assemble/' . $parent['parent_item_id'], 'refresh');
	}

	function change_status($id, $status = null) {
		$this->load->model('inventory/inventory_model');
		$data['user_data'] = $this->authorize->getSessionData();
		$data['item_data'] = $this->inventory_model->getItemData($id);


		if($status != null) {
			$this->inventory_model->AJAX_updateField($id, 'item_status', $status);
			redirect('inventory/edit/' . $id, 'refresh');
		}
		else {
			$this->load->view('inventory/item_change_status_view', $data);
		}
	}

	function comment_add($item_id) {
		$this->load->model('inventory/inventory_model');
		$this->load->library('form_validation');
		$data['user_data'] = $this->authorize->getSessionData();
		$data['item_data'] = $this->inventory_model->getItemData($item_id);

		$this->form_validation->set_rules('item_id', 'Item ID', 'trim|required|numeric|min_length[1]');
		$this->form_validation->set_rules('staff_id', 'Staff ID', 'trim|required|numeric|min_length[1]');
		$this->form_validation->set_rules('comment_type', 'Comment Type', 'trim|required');
		$this->form_validation->set_rules('comment', 'Comment', 'trim|required|min_length[5]');
		if($this->form_validation->run() == true) {
			$fields = array();
				$fields['item_id'] = $this->input->post('item_id');
				$fields['staff_id'] = $this->input->post('staff_id');
				$fields['comment'] = $this->input->post('comment');
			$this->inventory_model->insertStaffComment($fields);
			redirect('inventory/comments/' . $item_id . '/staff');
		}
		else {
			$this->load->view('inventory/comments/item_staff_comments_add_view', $data);
		}
	}

	function comment_delete($type = 'staff') {
		$this->load->model('inventory/inventory_model');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('comment_id', 'Comment ID', 'trim|required|numeric|min_length[1]');
		$this->form_validation->set_rules('item_id', 'Item ID', 'trim|required|numeric|min_length[1]');
		if($this->form_validation->run() == true) {
			$fields = array();
				$fields['comment_id'] = $this->input->post('comment_id');
				$fields['item_id'] = $this->input->post('item_id');
			if($type == 'staff') {
				$this->inventory_model->deleteStaffComment($fields);
			}
		}
		redirect('inventory/comments/' . $fields['item_id'] . '/' . $type, 'fresh');

	}
	function comments($item_id, $type = 'staff') {
		$this->load->model('inventory/inventory_model');
		$data['user_data'] = $this->authorize->getSessionData();
		$data['item_data'] = $this->inventory_model->getItemData($item_id);
		if($type == 'user') {
			$data['comment_data'] = $this->inventory_model->getItemUserComments($item_id);
			$this->load->view('inventory/comments/item_user_comments_list_view', $data);

		}
		else {
			$data['comment_data'] = $this->inventory_model->getItemStaffComments($item_id);
			$this->load->view('inventory/comments/item_staff_comments_list_view', $data);
		}

	}

	function clone_item($item_id) {
		$this->load->model('inventory/inventory_model');
		$this->load->model('utils/clone_model');

		$this->load->helper('form');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['item_data'] = $this->inventory_model->getItemData($item_id);
		if($this->input->post('submit_clone')) {
			$item = $this->inventory_model->getItemData($item_id, false);

			$item['item_status'] = 1; //update status of item to 'Available';
			//clone basic information
			$clone_fields = array();
				$clone_fields['mjr_class_id'] = $item['mjr_class_id'];
				$clone_fields['min_class_id'] = $item['min_class_id'];
				$clone_fields['item_name'] = $item['item_name'];
				$clone_fields['user_id'] = $item['user_id'];
				$clone_fields['entry_date'] = date("Y/m/d");

			$clone_id = $this->inventory_model->insertItem($clone_fields);
			$this->clone_model->updateClone($item, $clone_id);

			//clone modifier and material information
			$this->clone_model->cloneRecord($item['item_id'], $clone_id, 'item_modifier', 'item_modifier_id');
			$this->clone_model->cloneRecord($item['item_id'], $clone_id, 'item_material', 'item_material_id');

			//clone gemstone information
			$this->clone_model->cloneRecord($item['item_id'], $clone_id, 'jadeite_info', 'j_id');
			$this->clone_model->cloneRecord($item['item_id'], $clone_id, 'opal_info', 'o_id');
			$this->clone_model->cloneRecord($item['item_id'], $clone_id, 'pearl_info', 'p_id');
			$this->clone_model->cloneRecord($item['item_id'], $clone_id, 'stone_info', 'gem_id');

			//clone images
			$this->clone_model->cloneRecord($item['item_id'], $clone_id, 'image_lang', 'image_id', true);
			$this->clone_model->cloneRecord($item['item_id'], $clone_id, 'image_base', 'image_id', true);

			//clone diamond information
			$this->clone_model->cloneDiamonds($item['item_id'], $clone_id);

			//update suffix
			$this->clone_model->updateSuffix($clone_id);

			redirect('inventory/edit/' . $clone_id, 'refresh');

		}
		else {
			$this->load->view('inventory/item_clone_view', $data);
		}

	}

	function create_database_link($item_id) {
		$this->authorize->saveLastURL(); //saves the url
		$this->load->model('inventory/inventory_model');
		$this->load->model('utils/push_model');
		$this->load->model('utils/sync_model');
		$this->load->helper('form');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['item_data'] = $this->inventory_model->getItemData($item_id);

		if($this->input->post('lang_item_number_submit')) {
			$item_number = $this->input->post('lang_item_number');
			$data['lang_data'] = $this->push_model->getLangDataFromItemNumber($item_number);
		}

		if($this->input->post('create_link')) {//test for submitting of linkage!!
			$lang_id = $this->input->post('lang_id');
			$fran_id = $this->input->post('fran_id');
			$fields = array();
				$fields['lang_id'] = $lang_id;
				$fields['fran_id'] = $fran_id;
				$fields['push_state'] = 1;
				$fields['sync_date'] = date("Y-m-d H:i:s");

			$this->push_model->linkLangItem($lang_id, $fields);
			$this->push_model->linkFranItem($fran_id, $fields);

			redirect('inventory/edit/' . $item_id, 'refresh');
		}
		else {
			$this->load->view('inventory/item_link_with_lang_view',$data);
		}
	}

	function edit($id) {
		$this->authorize->saveLastURL(); //saves the url

		$this->load->model('inventory/inventory_model');
		$this->load->model('inventory/modifier_model');
		$this->load->model('inventory/material_model');
		$this->load->model('inventory/gemstone_model');
		$this->load->model('inventory/diamond_model');
		$this->load->model('inventory/pearl_model');
		$this->load->model('inventory/jadeite_model');
		$this->load->model('inventory/opal_model');
		$this->load->model('inventory/partnership_model');
		$this->load->model('utils/lookup_list_model');

		$this->load->helper('form');

		$data['user_data'] = $this->authorize->getSessionData();

		$data['item_data'] = $this->inventory_model->getItemData($id);
		if(sizeof($data['item_data']) > 0) {
			$data['entered_user_data'] = $this->inventory_model->getEnteredBy($data['item_data']['user_id']);
			$data['modifiers'] = $this->modifier_model->getAppliedModifiers($id);
			$data['material'] = $this->material_model->getAppliedMaterials($id);
			$data['partnerships'] = $this->partnership_model->getItemPartnerships($id);
			$data['gemstone_names'] = $this->gemstone_model->getGemstoneNames();
			$data['gemstone_cuts'] = $this->gemstone_model->getGemstoneCuts();
			$data['gemstones'] = $this->gemstone_model->getItemGemstones($id);
			$data['diamonds'] = $this->diamond_model->getItemDiamonds($id);
			$data['pearls'] = $this->pearl_model->getItemPearls($id);
			$data['jadeite'] = $this->jadeite_model->getItemJade($id);
			$data['opals'] = $this->opal_model->getItemOpals($id);
			$this->load->view('inventory/item_edit_view', $data);
		}
		else {
			$this->load->view('inventory/item_not_found_view', $data);
		}

	}

	function edit_history($id) {
		$this->load->model('inventory/inventory_model');
		$this->load->model('user/user_model');
		$data['user_data'] = $this->authorize->getSessionData();

		$data['item_id'] = $id;
		$data['history_data'] = $this->inventory_model->getEditHistory($id);
		$data['entered_user_data'] = $this->user_model->getAllUsers();

		$this->load->view('inventory/item_edit_history_view', $data);
	}

	function format_editor($item_id) {
		$this->authorize->saveLastURL(); //saves the url
		$this->load->model('inventory/inventory_model');
		$this->load->helper('form');

		$data['user_data'] = $this->authorize->getSessionData();

		$data['item_data'] = $this->inventory_model->getItemData($item_id);
		if($this->input->post('submit_format')) {
			$fields = array();
				$fields['item_description'] = $this->input->post('item_description');
			$this->inventory_model->updateItem($data['item_data']['item_id'], $fields);
			redirect('inventory/format_editor/' . $data['item_data']['item_id'], 'refresh');
		}
		else {
			$this->load->view('inventory/item_format_edit_view', $data);
		}
	}

	function image_edit_phrase($item_id) {
		$this->authorize->saveLastURL(); //saves the url

		$this->load->model('inventory/inventory_model');
		$this->load->model('image/image_model');
		$this->load->model('inventory/modifier_model');

		$data['user_data'] = $this->authorize->getSessionData();

		$data['item_data'] = $this->inventory_model->getItemData($item_id);
		//$data['keywords'] = $this->modifier_model->getKeyWordModifiers();
		$data['external_images'] = $this->image_model->getExternalImages($item_id);


		$this->load->view('inventory/images/image_edit_phrase_view', $data);
	}

	function images($id) {
		$this->authorize->saveLastURL(); //saves the url

		$this->load->model('inventory/inventory_model');
		$this->load->model('image/image_model');

		$data['user_data'] = $this->authorize->getSessionData();

		$data['item_data'] = $this->inventory_model->getItemData($id);
		$data['external_images'] = $this->image_model->getExternalImages($id);
		$data['internal_images'] = $this->image_model->getInternalImages($id);

		$this->load->view('inventory/images/item_images_view', $data);
	}


	function invoice_history($id) {
		$this->load->model('inventory/inventory_model');
		$data['user_data'] = $this->authorize->getSessionData();

		$data['item_id'] = $id;
		$data['invoice_data'] = $this->inventory_model->getInvoiceHistory($id);
		$data['return_data'] = $this->inventory_model->getReturnHistory($id);

		$this->load->view('inventory/item_invoice_history_view', $data);
	}

	function materials($item_id) {
		$this->load->model('inventory/inventory_model');
		$this->load->model('inventory/material_model');
		$data['user_data'] = $this->authorize->getSessionData();

		$data['item_data'] = $this->inventory_model->getItemData($item_id);
		$data['material_data'] = $this->material_model->getAppliedMaterials($item_id);
		$data['materials'] =  $this->material_model->getMaterials();

		$this->load->view('inventory/item_materials_view', $data);
	}

	function modifiers($id) {
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->load->model('inventory/inventory_model');
		$this->load->model('inventory/modifier_model');
		$this->load->model('admin/website_model');
		$data['user_data'] = $this->authorize->getSessionData();

		$data['item_data'] = $this->inventory_model->getItemData($id);
		$data['modifier_data'] = $this->modifier_model->getAppliedModifiers($id);
		$data['modifiers'] =  $this->modifier_model->getModifiers();
		$data['top_categories'] = $this->website_model->getTopLevelModifiers();
		$data['sub_categories'] = $this->website_model->getSubLevelModifiers();
		$data['applied_ids'] = array();
		foreach($data['modifier_data'] as $modifier) {
			$data['applied_ids'][$modifier['modifier_id']] = $modifier['modifier_id'];
		}
		$this->form_validation->set_rules('modifier_name', 'Modifier Name', 'trim|required|min_length[1]|max_length[64]');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		if($this->form_validation->run() == TRUE) {
			$fields = array();
				$fields['modifier_name'] = $this->input->post('modifier_name');
			$mod_id = $this->modifier_model->insertModifier($fields);

			$this->modifier_model->applyModifier($id, $mod_id);

			redirect('inventory/modifiers/' . $id, 'refresh');
		}
		else {
			$this->load->view('inventory/item_modifiers_view', $data);
		}
	}

	function partnership($item_id) {
		$this->load->model('inventory/inventory_model');
		$this->load->model('inventory/partnership_model');
		$this->load->model('utils/company_model');
		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['item_data'] = $this->inventory_model->getInventoryData($item_id);
		$data['item_partnerships'] = $this->partnership_model->getItemPartnerships($item_id);
		$data['company_data'] = $this->company_model->getCompanyInformation();
		$data['company_ownership'] = $this->partnership_model->getPercentOfCompanyOwnership($item_id);

		$this->load->view('inventory/partnership/item_partnership_view', $data);
	}

	function partnership_add($item_id) {
		$this->load->model('inventory/inventory_model');
		$this->load->model('inventory/partnership_model');
		$this->load->model('utils/company_model');
		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['item_data'] = $this->inventory_model->getInventoryData($item_id);
		$data['company_data'] = $this->company_model->getCompanyInformation();
		$data['company_ownership'] = $this->partnership_model->getPercentOfCompanyOwnership($item_id);

		$this->form_validation->set_rules('item_id', 'Item Number', 'trim|required|numeric');
		$this->form_validation->set_rules('partner_id', 'Partner ID', 'trim|required|numeric');
		$this->form_validation->set_rules('their_percentage', 'Their Ownership', 'trim|required');

		if($this->form_validation->run() == true) {
			$fields = array();
				$fields['item_id'] = $item_id;
				$fields['partner_id'] = $this->input->post('partner_id');
				$fields['percentage'] = $this->input->post('their_percentage');
			$this->partnership_model->insertPartnership($fields);
			redirect('inventory/partnership/' . $item_id, 'refresh');
		}
		else {
			$this->load->view('inventory/partnership/item_partnership_add_view', $data);
		}
	}

	function partnership_delete() {
		$item_id = $this->input->post('item_id');
		$partner_id = $this->input->post('partner_id');
		$partnership_id = $this->input->post('partnership_id');

		$this->load->model('inventory/partnership_model');

		$this->partnership_model->deletePartnership($item_id, $partner_id, $partnership_id);

		redirect('inventory/partnership/' . $item_id);
	}

	function partnership_edit($partnership_id) {
		$this->load->model('inventory/inventory_model');
		$this->load->model('inventory/partnership_model');
		$this->load->model('utils/company_model');
		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['user_data'] = $this->authorize->getSessionData();

		$data['partnership_data'] = $this->partnership_model->getPartnershipData($partnership_id);
		$data['item_data'] = $this->inventory_model->getInventoryData($data['partnership_data']['item_id']);
		$data['company_data'] = $this->company_model->getCompanyInformation();
		$data['company_ownership'] = $this->partnership_model->getPercentOfCompanyOwnership($data['partnership_data']['item_id']);

		$this->form_validation->set_rules('partnership_id', 'Partnership ID', 'trim|required');
		$this->form_validation->set_rules('percentage', 'Their Ownership', 'trim|required');

		if($this->form_validation->run() == true) {
			$fields = array();
				$fields['percentage'] = $this->input->post('percentage');
			$this->partnership_model->updatePartnership($this->input->post('partnership_id'), $fields);
			redirect('inventory/partnership/' . $data['partnership_data']['item_id'], 'refresh');
		}
		else {
			$this->load->view('inventory/partnership/item_partnership_edit_view', $data);
		}
	}

	function push_to_fran($item_id) {
		$this->authorize->saveLastURL(); //saves the url
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('inventory/inventory_model');
		$this->load->model('inventory/material_model');
		$this->load->model('image/image_model');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['item_data'] = $this->inventory_model->getItemData($item_id);

		$this->form_validation->set_rules('item_id', 'Item Id', 'trim|require|numeric');
		//print_r($data['item_data']);
		if($this->form_validation->run() == true) {
			$this->load->model('utils/push_model');
			$this->load->model('inventory/material_model');
			$this->load->model('inventory/modifier_model');
			$this->load->model('inventory/gemstone_model');
			$this->load->model('inventory/jadeite_model');
			$this->load->model('inventory/pearl_model');
			$this->load->model('inventory/opal_model');
			$this->load->model('inventory/diamond_model');


			//test the item number to see if it colides.
			if($this->push_model->testItemNumber($data['item_data']['item_number'])) {
				//the item number is safe to use.
				$obj = array();
				$obj = $data['item_data'];

				unset($obj['item_id']); //unset the item_id, this would cause a colision
				unset($obj['icon_status']); //remove icon status
				unset($obj['push_data']); //
				unset($obj['icon_web_status']);
				unset($obj['image_array']);
				unset($obj['mjr_class_name']);
				unset($obj['min_class_name']);
				unset($obj['material_count']);
				unset($obj['modifier_count']);
				unset($obj['buyer_type']);
				unset($obj['buyer_id']);
				unset($obj['seller_data']); //would probably cause an error
				$obj['item_status'] = 1; //was 91, but no one likes it.
				$obj['push_state'] = 1; //pushed
				$obj['fran_id'] = $data['item_data']['item_id']; //make a link between the two
				//lang_id will be null for newly pushed items. This is because we don't have the item_id.

				$lang_id = $this->push_model->copyItemToLang($obj);
				//once you have the item_id, you can start pushing other data,

				//update this item with it's Lang_id;
				$this->inventory_model->AJAX_updateField($data['item_data']['item_id'], 'lang_id', $lang_id); //shouldn't use these functions,
				$this->inventory_model->AJAX_updateField($data['item_data']['item_id'], 'fran_id', $data['item_data']['item_id']); //shouldn't use these functions,
				$this->inventory_model->AJAX_updateField($data['item_data']['item_id'], 'push_state', 1); //shouldn't use these functions,

				//echo $lang_id;
				//Update the pushed item with it's own id.
				$this->push_model->updatePushedRecord($lang_id, 'lang_id', $lang_id);

				//material
				$materials = $this->material_model->getItemMaterials($item_id);
				foreach($materials as $mat) {
					unset($mat['item_material_id']);
					$mat['item_id'] = $lang_id;
					$this->push_model->pushRecord('item_material', $mat);
				}

				//modifiers
				$modifiers = $this->modifier_model->getItemModifiers($item_id);
				foreach($modifiers as $mod) {
					unset($mod['item_modifier_id']);
					$mod['item_id'] = $lang_id;
					$this->push_model->pushRecord('item_modifier', $mod);
				}

				//gemstone information
				$gems = $this->gemstone_model->getItemGemstones($item_id);
				foreach($gems as $gem) {
					unset($gem['gem_id']);
					unset($gem['gemstone_name']);
					unset($gem['gemstone_shape']);
					$gem['item_id'] = $lang_id;
					$this->push_model->pushRecord('stone_info', $gem);
				}
				//jade
				$jadeite = $this->jadeite_model->getItemJade($item_id);
				foreach($jadeite as $jade) {
					unset($jade['j_id']);
					unset($jade['jade_name']);
					$jade['item_id'] = $lang_id;
					$this->push_model->pushRecord('jadeite_info', $jade);
				}

				//pearl
				$pearls = $this->pearl_model->getItemPearls($item_id);
				foreach($pearls as $pearl) {
					unset($pearl['p_id']);
					unset($pearl['pearl_name']);
					$pearl['item_id'] = $lang_id;
					$this->push_model->pushRecord('pearl_info', $pearl);
				}

				//opals
				$opals = $this->opal_model->getItemOpals($item_id);
				foreach($opals as $opal) {
					unset($opal['o_id']);
					unset($opal['opal_name']);
					unset($opal['opal_shape']);
					$opal['item_id'] = $lang_id;
					$this->push_model->pushRecord('opal_info', $opal);
				}

				//diamond info
				$diamonds = $this->diamond_model->getItemDiamonds($item_id);
				foreach($diamonds as $diamond) {
					$old_d_id = $diamond['d_id'];
					unset($diamond['d_id']);
					unset($diamond['diamond_name']);
					unset($diamond['diamond_shape']);
					unset($diamond['color']);
					unset($diamond['clarity']);

					$diamond['item_id'] = $lang_id;
					$lang_d_id = $this->push_model->pushRecord('diamond_info', $diamond);
					$colors = $this->diamond_model->getDiamondColorsData($old_d_id, $item_id);
					foreach($colors as $color) {
						unset($color['item_color_id']);
						$color['item_id'] = $lang_id;
						$color['diamond_id'] = $lang_d_id;
						$this->push_model->pushRecord('item_diamond_color', $color);
					}

					$clarities = $this->diamond_model->getDiamondClaritiesData($old_d_id, $item_id);
					foreach($clarities as $clarity) {
						unset($clarity['item_clarity_id']);
						$clarity['item_id'] = $lang_id;
						$clarity['diamond_id'] = $lang_d_id;
						$this->push_model->pushRecord('item_diamond_clarity', $clarity);
					}
				}

				$scan_images = $this->image_model->getInternalImages($item_id);
				$web_images = $this->image_model->getExternalImages($item_id);
				//get the doc_root
				$doc_root = str_replace('francesklein.com', '', $_SERVER['DOCUMENT_ROOT']);
				$fran_root = $this->config->item('document_root');
				$lang_root = $doc_root . 'langantiques.com';
				foreach($scan_images as $scan) {
					if(!is_dir($lang_root . '/images/internal/' . $data['item_data']['mjr_class_id'])) {
						mkdir($lang_root . '/images/internal/' . $data['item_data']['mjr_class_id']);
					}
					//copy the file to the new location
					copy($fran_root . $scan['image_copy_location'], $lang_root . $scan['image_copy_location']);
					//insert the data;
					$fields = array();
						$fields['item_id'] = $lang_id;
						$fields['image_name'] = $scan['image_name'];
						$fields['image_size'] = $scan['image_size'];
						$fields['image_location'] = $scan['image_copy_location'];
						$fields['image_date'] = $scan['image_date'];
						$fields['image_type'] = $scan['image_type'];
					$this->push_model->pushRecord('image_lang', $fields);
				}
				//Push over Web Images
				foreach($web_images as $image) {
					if(!is_dir($lang_root . '/images/external/' . $data['item_data']['mjr_class_id'])) {
						mkdir($lang_root . '/images/external/' . $data['item_data']['mjr_class_id']);
					}
					//copy the file to the new location
					copy($fran_root . $image['image_copy_location'], $lang_root . $image['image_copy_location']);
					//insert the data;
					$fields = array();
						$fields['item_id'] = $lang_id;
						$fields['image_seq'] = $image['image_seq'];
						$fields['image_name'] = $image['image_name'];
						$fields['image_title'] = $image['image_title'];
						$fields['image_size'] = $image['image_size'];
						$fields['image_location'] = $image['image_copy_location'];
						$fields['image_date'] = $image['image_date'];
						$fields['image_type'] = $image['image_type'];
					$this->push_model->pushRecord('image_base', $fields);
				}

				//push was sucessful!
				$this->inventory_model->markAsPushed($item_id, array('push_state' => 1));
				$this->inventory_model->AJAX_updateField($item_id, 'style_number', $data['item_data']['item_number']);

				$this->load->view('inventory/item_push_success_view', $data);
			}
			else {
				//failed inventory test
				//it needs a new number to continue
				$this->load->view('inventory/item_push_failure_view', $data);
			}
		}
		else {
			$this->load->view('inventory/item_push_view', $data);
		}
	}

	function push_to_lang($item_id) {
		$this->authorize->saveLastURL(); //saves the url
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('inventory/inventory_model');
		$this->load->model('inventory/material_model');
		$this->load->model('image/image_model');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['item_data'] = $this->inventory_model->getItemData($item_id);

		$this->form_validation->set_rules('item_id', 'Item Id', 'trim|require|numeric');
		//print_r($data['item_data']);
		if($this->form_validation->run() == true) {
			$this->load->model('utils/push_model');
			$this->load->model('inventory/material_model');
			$this->load->model('inventory/modifier_model');
			$this->load->model('inventory/gemstone_model');
			$this->load->model('inventory/jadeite_model');
			$this->load->model('inventory/pearl_model');
			$this->load->model('inventory/opal_model');
			$this->load->model('inventory/diamond_model');


			//test the item number to see if it colides.
			if($this->push_model->testItemNumber($data['item_data']['item_number'])) {
				//the item number is safe to use.
				$obj = array();
				$obj = $data['item_data'];

				unset($obj['item_id']); //unset the item_id, this would cause a colision
				unset($obj['icon_status']);
				unset($obj['push_data']);
				unset($obj['icon_web_status']);
				unset($obj['image_array']);
				unset($obj['mjr_class_name']);
				unset($obj['min_class_name']);
				unset($obj['material_count']);
				unset($obj['modifier_count']);
				unset($obj['buyer_type']);
				unset($obj['buyer_id']);
				unset($obj['seller_data']); //will probably cause an error
				unset($obj['item_job_cost']);
				unset($obj['assembly_data']);
				$obj['item_status'] = 1; //was 91, but no one likes it.
				$obj['push_state'] = 1; //pushed
				$obj['fran_id'] = $data['item_data']['item_id']; //make a link between the two

				//lang_id will be null for newly pushed items. This is because we don't have the item_id.

				$lang_id = $this->push_model->copyItemToLang($obj);
				//once you have the item_id, you can start pushing other data,

				//update this item with it's Lang_id;
				$this->inventory_model->AJAX_updateField($data['item_data']['item_id'], 'lang_id', $lang_id); //shouldn't use these functions,
				$this->inventory_model->AJAX_updateField($data['item_data']['item_id'], 'fran_id', $data['item_data']['item_id']); //shouldn't use these functions,
				$this->inventory_model->AJAX_updateField($data['item_data']['item_id'], 'push_state', 1); //shouldn't use these functions,

				//echo $lang_id;
				//Update the pushed item with it's own id.
				$this->push_model->updatePushedRecord($lang_id, 'lang_id', $lang_id);

				//material
				$materials = $this->material_model->getItemMaterials($item_id);
				foreach($materials as $mat) {
					unset($mat['item_material_id']);
					$mat['item_id'] = $lang_id;
					$this->push_model->pushRecord('item_material', $mat);
				}

				//modifiers
				$modifiers = $this->modifier_model->getItemModifiers($item_id);
				foreach($modifiers as $mod) {
					unset($mod['item_modifier_id']);
					$mod['item_id'] = $lang_id;
					$this->push_model->pushRecord('item_modifier', $mod);
				}

				//gemstone information
				$gems = $this->gemstone_model->getItemGemstones($item_id);
				foreach($gems as $gem) {
					unset($gem['gem_id']);
					unset($gem['gemstone_name']);
					unset($gem['gemstone_shape']);
					$gem['item_id'] = $lang_id;
					$this->push_model->pushRecord('stone_info', $gem);
				}
				//jade
				$jadeite = $this->jadeite_model->getItemJade($item_id);
				foreach($jadeite as $jade) {
					unset($jade['j_id']);
					unset($jade['jade_name']);
					$jade['item_id'] = $lang_id;
					$this->push_model->pushRecord('jadeite_info', $jade);
				}

				//pearl
				$pearls = $this->pearl_model->getItemPearls($item_id);
				foreach($pearls as $pearl) {
					unset($pearl['p_id']);
					unset($pearl['pearl_name']);
					$pearl['item_id'] = $lang_id;
					$this->push_model->pushRecord('pearl_info', $pearl);
				}

				//opals
				$opals = $this->opal_model->getItemOpals($item_id);
				foreach($opals as $opal) {
					unset($opal['o_id']);
					unset($opal['opal_name']);
					unset($opal['opal_shape']);
					$opal['item_id'] = $lang_id;
					$this->push_model->pushRecord('opal_info', $opal);
				}

				//diamond info
				$diamonds = $this->diamond_model->getItemDiamonds($item_id);
				foreach($diamonds as $diamond) {
					$old_d_id = $diamond['d_id'];
					unset($diamond['d_id']);
					unset($diamond['diamond_name']);
					unset($diamond['diamond_shape']);
					unset($diamond['color']);
					unset($diamond['clarity']);

					$diamond['item_id'] = $lang_id;
					$lang_d_id = $this->push_model->pushRecord('diamond_info', $diamond);
					$colors = $this->diamond_model->getDiamondColorsData($old_d_id, $item_id);
					foreach($colors as $color) {
						unset($color['item_color_id']);
						$color['item_id'] = $lang_id;
						$color['diamond_id'] = $lang_d_id;
						$this->push_model->pushRecord('item_diamond_color', $color);
					}

					$clarities = $this->diamond_model->getDiamondClaritiesData($old_d_id, $item_id);
					foreach($clarities as $clarity) {
						unset($clarity['item_clarity_id']);
						$clarity['item_id'] = $lang_id;
						$clarity['diamond_id'] = $lang_d_id;
						$this->push_model->pushRecord('item_diamond_clarity', $clarity);
					}
				}

				$scan_images = $this->image_model->getInternalImages($item_id);
				$web_images = $this->image_model->getExternalImages($item_id);
				//get the doc_root
				$doc_root = str_replace('francesklein.com', '', $_SERVER['DOCUMENT_ROOT']);
				$fran_root = $this->config->item('document_root');
				$lang_root = $doc_root . 'langantiques.com-trunk'; //TODO make this non-hard-coded
				foreach($scan_images as $scan) {
					if(!is_dir($lang_root . '/images/internal/' . $data['item_data']['mjr_class_id'])) {
						mkdir($lang_root . '/images/internal/' . $data['item_data']['mjr_class_id']);
					}
					//copy the file to the new location
					copy($fran_root . $scan['image_copy_location'], $lang_root . $scan['image_copy_location']);
					//insert the data;
					$fields = array();
						$fields['item_id'] = $lang_id;
						$fields['image_name'] = $scan['image_name'];
						$fields['image_size'] = $scan['image_size'];
						$fields['image_location'] = $scan['image_copy_location'];
						$fields['image_date'] = $scan['image_date'];
						$fields['image_type'] = $scan['image_type'];
					$this->push_model->pushRecord('image_lang', $fields);
				}
				//Push over Web Images
				foreach($web_images as $image) {
					if(!is_dir($lang_root . '/images/external/' . $data['item_data']['mjr_class_id'])) {
						mkdir($lang_root . '/images/external/' . $data['item_data']['mjr_class_id']);
					}
					//copy the file to the new location
					copy($fran_root . $image['image_copy_location'], $lang_root . $image['image_copy_location']);
					//insert the data;
					$fields = array();
						$fields['item_id'] = $lang_id;
						$fields['image_seq'] = $image['image_seq'];
						$fields['image_name'] = $image['image_name'];
						$fields['image_title'] = $image['image_title'];
						$fields['image_size'] = $image['image_size'];
						$fields['image_location'] = $image['image_copy_location'];
						$fields['image_date'] = $image['image_date'];
						$fields['image_type'] = $image['image_type'];
					$this->push_model->pushRecord('image_base', $fields);
				}

				//push was sucessful!
				$this->inventory_model->markAsPushed($item_id, array('push_state' => 1));
				$this->inventory_model->AJAX_updateField($item_id, 'style_number', $data['item_data']['item_number']);

				$this->load->view('inventory/item_push_success_view', $data);
			}
			else {
				//failed inventory test
				//it needs a new number to continue
				$this->load->view('inventory/item_push_failure_view', $data);
			}
		}
		else {
			$this->load->view('inventory/item_push_view', $data);
		}
	}

	/**
	 * Reclasses an Item number.
	 * See comments for full details.
	 *
	 * @param [int] $id = item_id
	 *
	 * @return null
	 */
	function reclass($id) {
		$this->load->helper('form');
		$this->load->model('inventory/inventory_model');
		$this->load->model('utils/lookup_list_model');
		$this->load->library('form_validation');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['major_classes'] = $this->lookup_list_model->getMajorClasses();
		$data['minor_classes'] = $this->lookup_list_model->getMinorClasses();
		$data['item_data'] = $this->inventory_model->getItemData($id);

		$this->form_validation->set_rules('major_class', 'Major Class', 'required|min_length[1]|max_length[5]');
		$this->form_validation->set_rules('minor_class', 'Minor Class', 'required|min_length[1]|max_length[5]');
		$this->form_validation->set_rules('suffix', 'Suffix', 'required|min_length[1]|max_length[11]|numeric');
		$this->form_validation->set_rules('item_number', 'Item Number', 'required');

		if ($this->form_validation->run() == TRUE) {
			$major_class = $this->input->post('major_class');
			$minor_class = $this->input->post('minor_class');
			$suffix = $this->input->post('suffix');

			$fields = array();
				$fields['mjr_class_id'] = $major_class;
				$fields['min_class_id'] = $minor_class;
				$fields['suffix'] = $suffix;
				$fields['item_number'] = $major_class . '-' . $minor_class . '-' . $suffix;
			$this->inventory_model->updateItem($id, $fields);
			redirect('inventory/edit/' . $id, 'refresh');
		}
		else {
			$this->load->view('inventory/item_reclass_view', $data);
		}
	}

	function remove_image($id, $image_id, $type) {
		$this->load->model('image/image_model');
		$this->load->helper('file');
		$this->load->helper('form');

		$image = $this->image_model->getImageLocation($image_id, $type);
		//the @ ('at' symbol) suppress any warning that might show up
		if(unlink($this->config->item('document_root') . $image['image_delete_location'])) {
		//if(@unlink($_SERVER['DOCUMENT_ROOT'] . $image['image_delete_location'])) {
			if($type == 'external') {
				$this->image_model->removeExternalImage($id, $image_id);
				redirect('inventory/upload_external_image/' . $id, 'refresh');
			}
			else {
				$this->image_model->removeInternalImage($id, $image_id);
				redirect('inventory/upload_internal_image/' . $id, 'refresh');
			}

		}
		else {
			//Error deleting images
			$data['error_data'] = array();
				$data['error_data']['image_location'] = $_SERVER['DOCUMENT_ROOT'] . $image['image_delete_location'];

			$data['user_data'] = $this->authorize->getSessionData();
			$data['type'] = $type;
			$data['item_id'] = $id;
			$data['image_id'] = $image_id;
			$this->load->view('inventory/images/image_removing_error_view', $data);
		}

	}

	function remove_image_force() {
		$this->load->model('image/image_model');
		$item_id = $this->input->post('item_id');
		$image_id = $this->input->post('image_id');

		if($this->input->post('image_type') == 'external') {
			$this->image_model->removeExternalImage($item_id, $image_id);
			redirect('inventory/upload_external_image/' . $item_id, 'refresh');
		}
		else {
			$this->image_model->removeInternalImage($item_id, $image_id);
			redirect('inventory/upload_internal_image/' . $item_id, 'refresh');
		}
	}

	function return_consignee($item_id) {
		$this->load->model('inventory/inventory_model');

		//update item_status
		$this->inventory_model->AJAX_updateField($item_id, 'item_status', '7');
		//update web_status
		$this->inventory_model->AJAX_updateField($item_id, 'web_status', '0');

		redirect('inventory/edit/' . $item_id, 'refresh');
	}

	/**
	 *
	 * @param [int] $id = item id;
	 *
	 * @return unknown_type
	 */
	function seller($item_id, $action = 'add') {
		$this->load->model('inventory/inventory_model');
		$this->load->helper('form');
		$this->load->library('form_validation');

		$temp = array();
		$data['user_data'] = $this->authorize->getSessionData();
		$data['item_data'] = $this->inventory_model->getItemData($item_id);


		switch($action) {
			case 'add':
				$this->load->view('inventory/item_seller_add_view', $data);
				break;
			case 'edit':
				if($this->input->post('clear_seller')) {
					$this->session->unset_userdata('seller');
					redirect('inventory/seller/' . $item_id . '/edit');
				}

				if($this->input->post('set_seller')) {
					$seller_data = array();
						$seller_data['id'] = $data['item_data']['seller_id'];
						$seller_data['type'] = $data['item_data']['seller_type'];
						$seller_data['date'] = ($this->input->post('purchase_date') != '') ? date('Y/m/d', strtotime($this->input->post('purchase_date'))) : '' ;
						$this->session->set_userdata('seller', $seller_data);
					redirect('inventory/seller/' . $item_id . '/edit');
				}

				if($data['item_data']['seller_type'] == 1) { //vendor
					$this->load->model('vendor/vendor_model');
					$temp = $this->vendor_model->getVendorData($data['item_data']['seller_id']);
					$data['seller_data']['name'] = $temp['name'] . ' (' . $temp['first_name'] . ' ' . $temp['last_name'] . ')';
					$data['seller_data']['phone'] = $temp['phone'];
					$data['seller_data']['address'] = $temp['address'];
					$data['seller_data']['address2'] = $temp['address2'];
					$data['seller_data']['city'] = $temp['city'];
					$data['seller_data']['state'] = $temp['state'];
					$data['seller_data']['zip'] = $temp['zip'];
					$data['seller_data']['country'] = $temp['country'];
				}
				else if ($data['item_data']['seller_type'] == 2) { //private buy
					$this->load->model('customer/customer_model');
					$temp = $this->customer_model->getCustomerData($data['item_data']['seller_id']);
					$data['seller_data']['name'] = $temp['first_name'] . ' ' . $temp['last_name'];
					$data['seller_data']['phone'] = $temp['home_phone'];
					$data['seller_data']['address'] = $temp['address'];
					$data['seller_data']['address2'] = $temp['address2'];
					$data['seller_data']['city'] = $temp['city'];
					$data['seller_data']['state'] = $temp['state'];
					$data['seller_data']['zip'] = $temp['zip'];
					$data['seller_data']['country'] = $temp['country'];
				}

				$this->form_validation->set_rules('purchase_date', 'Purchase Date', 'trim');
				$this->form_validation->set_rules('purchase_price', 'Purchase Price', 'trim|numeric');
				if($this->form_validation->run() == true) {
					if($this->input->post('purchase_date') != '') {
						$p_date = date('Y/m/d', strtotime($this->input->post('purchase_date')));
					}
					else {
						$p_date = null;
					}

					$fields = array();
						$fields['purchase_date'] = $p_date;
						$fields['purchase_price'] = $this->input->post('purchase_price');
					$this->inventory_model->updateItem($item_id, $fields);
					redirect('inventory/edit/' . $item_id, 'refresh');
				}
				else {
					$this->load->view('inventory/item_seller_edit_view', $data);
				}
				break;
		}
	}

	function seller_remove() {
		$this->load->model('inventory/inventory_model');
		$item_id = $this->input->post('item_id');
		$this->inventory_model->removeSeller($item_id);

		redirect('inventory/edit/' . $item_id, 'refresh');
	}

	function sync_with_lang($item_id) {
		$this->authorize->saveLastURL(); //saves the url
		$this->load->model('inventory/inventory_model');
		$this->load->model('utils/push_model');
		$this->load->model('utils/sync_model');
		$this->load->helper('form');


		//$data['sync_data'] = $this->inventory_model->getPushData($item_id);

		$data['user_data'] = $this->authorize->getSessionData();
		$data['item_data'] = $this->inventory_model->getItemData($item_id);

		$data['lang']['item_data'] = $this->push_model->getSingleLangTableDataRecord($data['item_data']['lang_id'], 'inventory');
		$data['lang']['gemstones'] = $this->push_model->getAllLangTableData($data['item_data']['lang_id'], 'stone_info');
		$data['fran']['gemstones'] = $this->gemstone_model->getItemGemstones($item_id, false);

		$ignore_list = array();
		$ignore_list['inventory'] = $this->push_model->getIgnoredFields();
		$ignore_list['gemstone'] = array('item_id', 'gem_id', 'legacy_id');
		$data['sync_data']['inventory_sync_data'] = $this->push_model->compareDataSets($data['item_data'], $data['lang']['item_data'], $ignore_list['inventory']);

		//$data['sync_data']['gemstone_sync_data'] = $this->push_model->compareMultipleDataSets($data['fran']['gemstones'], $data['lang']['gemstones'], $ignore_list['gemstone']);

		/*
		for($i = 0; $i <= sizeof($data['lang']['gemstones'])-1; $i++) {
			foreach($data['lang']['gemstones'][$i] as $key => $field) {
				if(!in_array($key, $ignore_list['gemstone'])) {
					if($i <= sizeof($data['fran']['gemstones'])-1) {
						if($data['lang']['gemstones'][$i][$key] != $data['fran']['gemstones'][$i][$key]) {
							$data['sync_data']['gemstone_sync_data']['out_syn'] = true;
							$data['sync_data']['gemstone_sync_data']['diff'][$i][$key] = array('fran_sub_id' => $data['fran']['gemstones'][$i]['gem_id'], 'fran_sub_value' => $data['fran']['gemstones'][$i][$key], 'lang_sub_id' => $data['fran']['gemstones'][$i]['gem_id'], 'lang_sub_value' => $data['lang']['gemstones'][$i][$key]);
						}
					}
					else {
						$data['sync_data']['gemstone_sync_data']['out_syn'] = true;
						$data['sync_data']['gemstone_sync_data']['diff'][$i]['Missing in Frandango'] = array('fran_sub_id' => 0, 'fran_sub_value' => 'Missing', 'lang_sub_id' => $data['lang']['gemstones'][$i]['gem_id'], 'lang_sub_value' => '');
					}
				}
			}
		}
		*/

		if($this->input->post('sync_all_invenyory_fields')) {
			//echo 'You have selected Sync all Fields. is this true?';
			$lang_id = $this->input->post('lang_id');

			$lang_data = $this->push_model->getLangInventoryData($item_id, $lang_id);
			unset($lang_data['item_id']); //unset the item id, will cause issues
			unset($lang_data['buyer_id']); //to be safe, unset the buyer id
			unset($lang_data['buyer_type']); //to be safe, unset the buyer_type
			//more fields should probably be unset.

			$this->sync_model->syncAllInventoryFields($item_id, $lang_id, $lang_data);
			redirect('inventory/sync_with_lang/' . $item_id, 'refresh');

		}
		else if($this->input->post('sync_selected_inventory_field')) {
			$column = $this->input->post('field_name');
			$lang_id = $this->input->post('lang_id');
			$fran_id = $this->input->post('fran_id');

			$value = $this->push_model->getPushedInventoryColumnData($lang_id, $column);
			$this->sync_model->syncInventoryField($item_id, $lang_id, $column, $value); //duplicate of inventory_model:AJAX_updateField();

			redirect('inventory/sync_with_lang/' . $item_id, 'refresh');
		}
		else {
			$this->load->view('inventory/item_sync_view', $data);
		}
	}

	function show_image($id, $type) {
		$this->load->model('inventory/inventory_model');
		$this->load->model('image/image_model');
		$data['user_data'] = $this->authorize->getSessionData();

		$data['image_data'] = $this->image_model->getImageLocation($id, $type);

		$this->load->view('inventory/images/image_view', $data);

	}

	function tags($item_id) {
		$this->load->model('inventory/inventory_model');
		$this->load->model('inventory/modifier_model');
		$this->load->model('inventory/material_model');
		$this->load->model('inventory/tag_model');
		$this->load->model('inventory/gemstone_model');
		$this->load->model('inventory/diamond_model');
		$this->load->model('inventory/pearl_model');
		$this->load->model('inventory/jadeite_model');
		$this->load->model('inventory/opal_model');

		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['item_data'] = $this->inventory_model->getItemData($item_id);
		$data['item_modifiers'] = $this->modifier_model->getAppliedModifiers($item_id);
		$data['item_material'] = $this->material_model->getAppliedMaterials($item_id);
		$data['gemstone_names'] = $this->gemstone_model->getGemstoneNames();
		$data['gemstone_cuts'] = $this->gemstone_model->getGemstoneCuts();
		$data['gemstones'] = $this->gemstone_model->getItemGemstones($item_id);
		$data['diamonds'] = $this->diamond_model->getItemDiamonds($item_id);
		$data['pearls'] = $this->pearl_model->getItemPearls($item_id);
		$data['jadeite'] = $this->jadeite_model->getItemJade($item_id);
		$data['opals'] = $this->opal_model->getItemOpals($item_id);

		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->form_validation->set_rules('line_1', 'Line 1', 'required|max_length[16]');
		$this->form_validation->set_rules('line_2', 'Line 2', 'trim|max_length[16]');
		$this->form_validation->set_rules('line_3', 'Line 3', 'trim|max_length[16]');
		$this->form_validation->set_rules('line_4', 'Line 4', 'trim|max_length[16]');
		$this->form_validation->set_rules('line_5', 'Line 5', 'trim|max_length[16]');

		if($this->form_validation->run() == TRUE) {
			$fields = array();
				$fields['line_1'] = $this->input->post('line_1');
				$fields['line_2'] = $this->input->post('line_2');
				$fields['line_3'] = $this->input->post('line_3');
				$fields['line_4'] = $this->input->post('line_4');
				$fields['line_5'] = $this->input->post('line_5');
			if(isset($_POST['tag_id'])) {
				$this->tag_model->updateItemTag($fields, $_POST['tag_id']);
			}
			else {
				$fields['item_id'] = $item_id;
				$fields['item_number'] = $data['item_data']['item_number'];
				$fields['active'] = 1; //not sure if i should use this any more

				$this->tag_model->insertItemTag($fields);
			}

		}
		$data['tag_data'] = $this->tag_model->getTagData($item_id);
		$data['tags'] = $this->tag_model->getItemTags($item_id);

		$this->load->view('inventory/tag_edit_view', $data);

	}
	function tags_remove($item_id, $tag_id) {
		$this->load->model('inventory/tag_model');
		$this->tag_model->deleteItemTag($tag_id);

		redirect('inventory/tags/' . $item_id, 'refresh');
	}

	function tags_requeue($item_id, $tag_id) {
		$this->load->model('inventory/tag_model');
		$this->tag_model->reQuqueItemTag($tag_id);

		redirect('inventory/tags/' . $item_id, 'refresh');
	}


	/**
	 * Uploads a set of files, but does not use the
	 * CI Upload Library because that doesn't support
	 * multi file uploading.
	 *
	 * @param [int] $item_id = item id
	 */
	public function upload_external_image($item_id) {
		$this->load->model('inventory/inventory_model');
		$this->load->helper('directory');
		$this->load->library('form_validation');

		$data = array();

		$data['user_data'] = $this->authorize->getSessionData();
		$data['item_data'] = $this->inventory_model->getItemData($item_id);
		$data['external_images'] = $this->image_model->getExternalImages($item_id);

		$this->form_validation->set_rules('major_class','Major Class ID','trim|required');

		if($this->form_validation->run() == true) {
			//get upload path
			$path = $this->config->item('image_upload_path') . 'external/'; //set via config file
			if(!is_dir($path . $data['item_data']['mjr_class_id'])) { //if new major class id
				mkdir($path . $data['item_data']['mjr_class_id'], 0700); //create dir
			}
			//set path;
			$path = $path . $data['item_data']['mjr_class_id'];

			//loop thru each file that is being uploaded
			foreach($_FILES['images']['error'] as $key => $error) {
				if($error == UPLOAD_ERR_OK) { //upload is going well...
					$pattern = '/[^a-zA-Z0-9.]/i'; //set pattern
					$tmp_name = $_FILES['images']['tmp_name'][$key];
					$file_name = $_FILES['images']['name'][$key];
					$striped_name = substr($file_name, 0,strrpos($file_name,'.'));
					$composite_name = preg_replace($pattern, '_', time() . '_' . $striped_name);
					$new_file_name = str_replace(' ', '_', $composite_name) . '.jpg';

					move_uploaded_file($tmp_name, "$path/$new_file_name"); //uploads the file

					$image = array();
					$image['image_name'] = $new_file_name;
					$image['image_title'] = '';
					$image['image_size'] = $_FILES['images']['size'][$key];
					$image['image_location'] = '/images/external/' . $data['item_data']['mjr_class_id']. '/' . $new_file_name; //define the actuall path
					$image['image_type'] = $_FILES['images']['type'][$key];

					//insert into database
					$this->image_model->uploadExternalImage($item_id, $image);
				}
				else {
					break; //something failed
				}
			}
			if($error == UPLOAD_ERR_OK) {
				redirect('inventory/upload_external_image/' . $item_id, 'refresh');
			}
			else {
				echo 'There was an error. Check the file size or something. Remember, you can\'t upload a file larger than 2MB!';
			}
		}
		else {
			$this->load->view('inventory/images/upload_external_images_view', $data);
		}
	} //end upload_external_image();

	/*
	 * old file upload method.
	 *
	 * does not support multi file uploads
	 * ... also kind of sucks...
	 *
	function upload_external_image2($id, $upload = false) {
		$this->load->model('inventory/inventory_model');
		$this->load->helper('directory');
		$this->load->helper('form');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['item_data'] = $this->inventory_model->getItemData($id);
		$data['external_images'] = $this->image_model->getExternalImages($id);
		$data['upload_messages'] = '';

		if($upload) {
			//$path = $_SERVER['DOCUMENT_ROOT'] . '/images/external/'; //removed to merge code bases
			$path = $this->config->item('image_upload_path') . 'external/';
			if(!is_dir($path . $data['item_data']['mjr_class_id'])) {
				mkdir($path . $data['item_data']['mjr_class_id'], 0700);
			}
			$path = $path . $data['item_data']['mjr_class_id'];
			$config['upload_path'] = $path;
			$config['allowed_types'] = 'jpg';
			$config['max_size'] = '1000';
			$config['max_height'] = '1000';
			$config['max_width'] = '1000';
			$config['max_filename'] = '256';

			$pattern = '/[^a-zA-Z0-9.]/i';
			$file_name = $_FILES['imgfile']['name'];
			$striped_name = substr($file_name, 0,strrpos($file_name,'.'));
			$composite_name = preg_replace($pattern, '_', time() . '_' . $striped_name);
			$new_file_name = str_replace(' ', '_', $composite_name);

			//print_r($_FILES);

			$config['file_name'] = $new_file_name;
			$this->load->library('upload', $config);
			if ($this->upload->do_upload('imgfile') == TRUE) {
				$this->load->model('image/image_model');
				$temp_data = $this->upload->data();

				$image['image_name'] = $temp_data['file_name'];
				$image['image_title'] = $new_file_name;
				$image['image_size'] = $temp_data['file_size'];
				$image['image_location'] = '/images/external/' . $data['item_data']['mjr_class_id']. '/' . $new_file_name . $temp_data['file_ext'] ; //define the actuall path
				$image['image_type'] = $temp_data['file_type'];
				//print_r($image);
				//echo '<br />';
				//print_r($temp_data);
				//$this->image_model->uploadExternalImage($id, $image);
				//redirect('inventory/upload_external_image/' . $id, 'refresh');
			}
			else {
				$data['upload_messages'] = $this->upload->display_errors('<div class="warning">','</div>');
				$this->load->view('inventory/images/upload_external_images_view', $data);
			}
		}
		else {
			$this->load->view('inventory/images/upload_external_images_view', $data);
		}
	}
	*/

	function upload_internal_image($id, $upload = false) {
		$this->load->model('inventory/inventory_model');
		$this->load->model('image/image_model');

		$this->load->helper('directory');
		$this->load->helper('form');

		$data['user_data'] = $this->authorize->getSessionData();
		$data['item_data'] = $this->inventory_model->getItemData($id);
		$data['internal_images'] = $this->image_model->getInternalImages($id);
		$data['upload_messages'] = '';

		if($upload) {

			//$path = $_SERVER['DOCUMENT_ROOT'] . '/images/internal/';
			$path = $this->config->item('image_upload_path') . 'internal/';
			//echo $path;
			if(!is_dir($path . $data['item_data']['mjr_class_id'])) {
				mkdir($path . $data['item_data']['mjr_class_id'], 0755);
			}

			$path = $path . $data['item_data']['mjr_class_id'];

			$config['upload_path'] = $path;
			$config['allowed_types'] = 'jpg';
			$config['max_size'] = '1000';
			$config['max_height'] = '2000';
			$config['max_width'] = '2000';
			$config['max_filename'] = '150';

			$pattern = '/[^a-zA-Z0-9.]/i';
			$file_name = $_FILES['imgfile']['name'];
			$striped_name = substr($file_name, 0,strrpos($file_name,'.'));
			$composite_name = preg_replace($pattern, '_', time() . '_' . $striped_name);
			//$composite_name = preg_replace($pattern, '_', time() . '_' . $file_name);
			$new_file_name = str_replace(' ', '_', $composite_name);
			//$config['encrypt_name'] = $new_file_name;
			$config['file_name'] = $new_file_name;

			$this->load->library('upload', $config);
			if ($this->upload->do_upload('imgfile') == TRUE) {
				$this->load->model('image/image_model');
				$temp_data = $this->upload->data();
				$image = array();
				$image['item_id'] = $id;
				$image['image_name'] = $temp_data['file_name'];
				$image['image_size'] = $temp_data['file_size'];
				$image['image_location'] = '/images/internal/' . $data['item_data']['mjr_class_id']. '/' . $new_file_name . $temp_data['file_ext'] ; //define the actuall path
				$image['image_type'] = $temp_data['file_type'];
				$image['image_date'] = date('Y/m/d');

				$this->image_model->uploadInternalImage($image);
				redirect('inventory/upload_internal_image/' . $id, 'refresh');
			}
			else {
				$data['upload_messages'] = $this->upload->display_errors('<div class="warning">','</div>');
				$this->load->view('inventory/images/upload_internal_images_view', $data);
			}
		}
		else {
			$this->load->view('inventory/images/upload_internal_images_view', $data);
		}
	} //end upload_internal_image();

	/**
	 * Updates the web status of the item.
	 * @param $id [int] = item id
	 * @param $status [bool] = status
	 *
	 * @TODO: should probably turn this into an AJAX call
	 */
	function web_options($id, $status) {
		$this->load->model('inventory/inventory_model');
		$this->inventory_model->AJAX_updateField($id, 'web_status', $status);
		if($status == 1) {
			$this->whats_new($id, 'add');
		}
		//redirect back to item
		redirect('inventory/edit/' . $id, 'refresh');
	}



	function whats_new($id, $action) {
		$this->load->model('inventory/inventory_model');
		$data = array();
		if($action == 'add') {
			$data['publish_date'] = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
		}
		if($action == 'remove') {
			$data['publish_date'] = null;
		}
		$this->inventory_model->updatePublishDate($id, $data);

		//redirect back to item
		redirect('inventory/edit/' . $id, 'refresh');
	}

	function AJAX_apply_seller($id, $seller_type, $seller_id) {
		$this->load->model('inventory/inventory_model');
		$this->inventory_model->applySeller($id, $seller_type, $seller_id);
		$this->session->set_userdata(array('seller' => array('type'=> $seller_type,'id'=> $seller_id)));
		redirect('inventory/seller/' . $id . '/edit', 'refresh');
	}

	function AJAX_testInventoryNumber() {
		$this->load->model('inventory/inventory_model');
		$string = $this->input->post('number');

		$item = $this->inventory_model->getInventoryId($string);
		if($item != null) { //number found
			echo $item;
		}
		else {
			echo true;
		}
	}

	function AJAX_getNextSuffixSequence() {
		$this->load->model('inventory/inventory_model');
		$major_class_id = $this->input->post('major_class_id');

		$suffix = null;

		$suffix = $this->inventory_model->getNextSuffixByMajorClass($major_class_id);

		echo $suffix;
	}

	function AJAX_updateCatalogueStatus() {
		$id = $this->input->post('item_id');
		$column = $this->input->post('name');
		$value = (boolean)$this->input->post('status');

		$this->load->model('inventory/inventory_model');
		$this->inventory_model->AJAX_updateField($id, $column, $value);
	}

	function AJAX_updateImage() {
		$this->load->model('image/image_model');
		$image_id = $this->input->post('image_id');

		$formatted_string = preg_replace("/[^a-zA-Z0-9\s]/", "$1", $this->input->post('image_title'));

		$fields = array();
			$fields['image_title'] = $formatted_string;
		$this->image_model->updateImageData($image_id, $fields);

		echo $formatted_string;
	}

	function AJAX_updateItemField() {
		$this->load->model('inventory/inventory_model');
		$id = $this->input->post('item_id');
		$column = $this->input->post('id');
		$value = $this->input->post('value');
		$type = $this->input->post('type');

		if($type == 'money') {
			$strip_chars = array(',', '$');
			$value = (float) str_replace($strip_chars, '', $value); //cast as a float for extra fun!
			$return_value = '$' . number_format($value,2);
		}
		else if($type == 'date') {
			$value = date('Y/m/d', strtotime($value));
			$return_value = date('m/d/Y', strtotime($value));;
		}
		else {
			$return_value = $value;
		}

		$this->inventory_model->AJAX_updateField($id, $column, $value);
		echo $return_value; //This returns the value back to the field (find a fix)
	}

	public function AJAX_updateJobAtWorkshopStatus() {
		$this->load->model('workshop/workshop_model');
		$job_id = $this->input->post('job_id');
		$fields = array();
			$fields['at_workshop'] = $this->input->post('at_workshop_status');

		if($fields['at_workshop'] == 'yes') {
			$fields['sent_date'] = date('Y/m/d', time());
		}
		$this->workshop_model->updateInventoryJob($job_id, $fields);
		$fields['job_id'] = $job_id;
		$fields['sent_date'] = date('m/d/Y', time());
//		header("Content-Type: application/json");
		echo json_encode($fields, JSON_FORCE_OBJECT);
	}

	function CB_check_modifier($string) {
		$this->load->model('inventory/modifier_model');
		$b = false;
		$mod = $this->modifier_model->checkModifierNames($string);
		if(!$mod) { //$mod = false, no material found
			$b =  true;
		}
		echo $b;

	}

	function CB_realnumber($string) {
		$this->load->model('inventory/inventory_model');
		$bool = false;
		$this->form_validation->set_message('CB_realnumber', 'That\'s not a real item number!!!1!!!');
		if($this->inventory_model->getInventoryId($string) != null) {
			$bool = true;
		}
		return $bool;
	}

	private function PV_push_data($item_id, $direction) {
		$this->load->model('utils/push_model');
		$this->load->model('inventory/material_model');
		$this->load->model('inventory/modifier_model');
		$this->load->model('inventory/gemstone_model');
		$this->load->model('inventory/jadeite_model');
		$this->load->model('inventory/pearl_model');
		$this->load->model('inventory/opal_model');
		$this->load->model('inventory/diamond_model');


		return false;
	}

}
?>