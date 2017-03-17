<?php
/**
 * Menu Model
 *
 * Gets menu objects
 *
 * This helps construct the menu on the side
 *
 * See: /web/libraries/My_menu.php for more details
 *
 * @author user
 *
 */
class Menu_model extends Model {
	var $ci;

	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
	}
	/**
	 * Returns the data of a specific menu element
	 *
	 * @param [int] $element_id = element id
	 *
	 * @return [array] = array of element data
	 */
	public function getMenuElementData($element_id) {
		$data = array();

		$this->db->from('menu_elements');
		$this->db->where('element_id', $element_id);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[$row['element_id']] = $row;
			}
		}
		return $data; //array
	} //end getMenuElementData();

	/**
	 * Returns all the menu elements of a specific menu
	 *
	 * menu_id = 1 (main menu)
	 * menu_id = 2 (period menu)
	 * menu_id = 3 (N/A) //does not exist
	 *
	 * @param [int] $menu_id = menu id
	 *
	 * @return [array] = multi-dem array of memu elements
	 */
	public function getMenuElements($menu_id = 1) { //deafult to main menu
		$this->db->from('menu_elements');
		$this->db->where('element_menu', $menu_id);
		$this->db->order_by('element_seq');
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				//if element type is 3, it's a special search
				if($row['element_type'] == 3) {
					$row['element_name'] = $row['special_name'];
				}
				else {
					//gets element name based on emenmt_id
					$row['element_name'] = $this->getMenuElementName($row['element_type_id'], $row['element_type']);
				}
				if($row['element_url'] == 'the-archive') {
					$row['children'] = $this->_getNormalParentMenuElements();
				}
				else {
					$row['children'] = $this->_getChildMenuElements($row['element_id']);
				}
				$data[$row['element_id']] = $row;
			}
		}
		return $data; //array
	} //end getMenuElements()

	/**
	 * Attempts to find a menu element by its URL string
	 *
	 *
	 * @param [string] $string = url string
	 *
	 * @return [array] = array of element data
	 */
	public function getMenuElementByURLString($string) {
		$data = array();
		$this->db->from('menu_elements');
		$this->db->where('element_type', 3);
		$this->db->where('element_url', $string);

		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row;
			}
		}
		return $data; //array
	} //end getMenuElementByURLString

	/**
	 * Attempts to get the Name of the Menu Element
	 * based on Id and Type.
	 *
	 * @param [int] $element_type_id = element type id
	 * @param [int] $element_type = element type
	 * 		1 = major class
	 * 		2 = modifier
	 * 		3 = search (not used here)
	 *
	 * @return [string] =
	 */
	public function getMenuElementName($element_type_id, $element_type) {
		$data = ''; //start string
		//test the element type,
		if($element_type == 1) { //element type is major class
			$this->ci->load->model('major_class/major_class_model');
			$mjr = $this->ci->major_class_model->getMajorClassData($element_type_id);
			$data = $mjr['mjr_class_name'];

		}
		else if($element_type = 2) { //element type is modifier
			$this->ci->load->model('modifier/modifier_model');
			$mod = $this->ci->modifier_model->getModifierData($element_type_id);
			if($mod['modifier_title'] != '') {
				$data = $mod['modifier_title'];
			}
			else {
				$data = $mod['modifier_name'];
			}

		}

		return $data; //string
	} //end getMenuElementName();

	/**
	 * Attempts to get the Parent ID based on the childs
	 * URL.
	 *
	 *
	 * @param [string] $url = url string
	 *
	 * @return [int] = parent id
	 */
	public function getParentIdFromChildURL($url) {
		$id = null;

		$this->db->from('menu_sub_elements');
		$this->db->where('sub_element_url', $url);
		$query = $this->db->get();
		if($query->num_rows() > 0 ) {
			foreach($query->result_array() as $row) {
				$id = $row['parent_element_id'];
			}
		}
		return $id; //int
	} //end getParentIdFromChildURL();

	/**
	 * Attempts to get Parent Data from Parent URL
	 *
	 *
	 * @param [string] $url = url string
	 *
	 * @return [array] = menu element id
	 */
	public function getParentDataFromParentURL($url) {
		$data = array();
		$this->db->from('menu_elements');
		$this->db->where('element_url', $url);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row;
			}
		}

		return $data; //array
	} //end getParentDataFromParentURL();

	public function getPerPageNumber() {
		$per_page = 20;
		if($this->ci->input->post('show-90') || $this->ci->session->userdata('per_page') == '90') {
			$this->ci->session->set_userdata(array('per_page' => '90'));
			$per_page = 90;
		}
		else if($this->ci->input->post('show-60') || $this->ci->session->userdata('per_page') == '60') {
			$this->ci->session->set_userdata(array('per_page' => '60'));
			$per_page = 60;
		}
		else if($this->ci->input->post('show-30') || $this->ci->session->userdata('per_page') == '30') {
			$this->ci->session->set_userdata(array('per_page' => '30'));
			$per_page = 30;
		}
		return $per_page;
	}
	public function getSorts() {
		$data = array();
			$data['newest'] = array('name'=>'Newest','field'=>'publish_date','direction'=>'desc');
			//$data['oldest'] = array('name'=>'Oldest Items','field'=>'publish_date','direction'=>'asc');
			$data['high_price'] = array('name'=>'Highest Price','field'=>'item_price','direction'=>'desc');
			$data['low_price'] = array('name'=>'Lowest Price','field'=>'item_price','direction'=>'asc');
		return $data;
	}

	public function getWhatsNewRanges() {
		$data = array();
			//value is the number months
			$data['thirty'] = array('name'=>'30 Days', 'value'=>1);
			$data['ninety'] = array('name'=>'90 Days', 'value'=>3);
			$data['onetwenty'] = array('name'=>'120 Days', 'value'=>4);
		return $data;
	}

	/**
	 * PRIVATE
	 *
	 * Attempts to get Child Menu Elements
	 * for a specific parent
	 *
	 * @param [int] $parent_id = parent id
	 *
	 * @return [array] =
	 */
	private function _getChildMenuElements($parent_id) {
		$this->db->from('menu_sub_elements');
		$this->db->where('parent_element_id', $parent_id);
		$this->db->where('sub_element_type !=', 0);
		$this->db->where('sub_element_status', 1);
		$this->db->order_by('sub_element_seq','ASC');
		$query = $this->db->get();
		$data = array();

		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				//test sub element type
				if($row['sub_element_type'] == 3) { //3 = speical search
					$row['sub_element_name'] = $row['sub_special_name'];
				}
				else {
					//get the sub menu elements name
					$row['sub_element_name'] = $this->getMenuElementName($row['sub_element_type_id'], $row['sub_element_type']);
				}
				$data[] = $row;
			}
		}
		return $data; //array
	} //end _getChildMenuElements();

	private function _getNormalParentMenuElements() {
		$this->db->from('menu_elements');
		$this->db->where('element_type',1);
		$this->db->order_by('element_seq','ASC');

		$data = array();
		$query = $this->db->get();

		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$e = explode('/',$row['element_url']);
				$row['sub_element_url'] = 'the-archive/' . $e[2];
				$row['sub_element_name'] = $this->getMenuElementName($row['element_type_id'], 1);

				$data[] = $row;
			}
		}
		return $data;
	}
} //end Menu_model();


?>