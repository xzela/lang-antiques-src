<?php
class Partnership_model extends Model {
	
	public $ci;
	
	public function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
		
	}
	
	public function deletePartnership($item_id, $partner_id, $partnership_id) {
		$this->db->where('item_id', $item_id);
		$this->db->where('partner_id', $partner_id);
		$this->db->where('partnership_id', $partnership_id);
		$this->db->limit(1); //always limit one
		$this->db->delete('inventory_partnerships');
		
		return null;
	}
	
	public function getItemPartnerships($item_id) {
		$this->ci->load->model('vendor/vendor_model');
		
		$this->db->from('inventory_partnerships');
		$this->db->where('item_id', $item_id);
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['partner_data'] = $this->ci->vendor_model->getVendorData($row['partner_id']);
				$data[] = $row;
			}
		}
		return $data;
	}
	
	public function getPartnerPartnerships($partner_id) {
		$this->ci->load->model('inventory/inventory_model');
		
		$this->db->from('inventory_partnerships');
		$this->db->where('partner_id', $partner_id);
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['item_data'] = $this->ci->inventory_model->getItemData($row['item_id']);
				$row['our_ownership'] = $this->getPercentOfCompanyOwnership($row['item_id']);
				$data[] = $row;
			}
		}
		return $data;
	}
	public function getPartnershipData($partnership_id) {
		$this->ci->load->model('vendor/vendor_model');
		
		$this->db->from('inventory_partnerships');
		$this->db->where('partnership_id', $partnership_id);
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['partner_data'] = $this->ci->vendor_model->getVendorData($row['partner_id']);
				$data = $row;
			}
		}
		return $data;	
	}
	/**
	 * Returns the Amount of Ownership [Current Company{Lang|Fran|ect..}] 
	 * has over an item
	 * 
	 * @param [int] $item_id = item id
	 * 
	 * @return [float] percent of ownership
	 */
	public function getPercentOfCompanyOwnership($item_id) {
		$lang_ownership = 100.00;
		$partners = $this->getItemPartnerships($item_id);
		if(sizeof($partners) > 0) {
			foreach($partners as $partner) {
				$lang_ownership -= $partner['percentage'];
			}
		}
		return $lang_ownership;
	}
	
	public function insertPartnership($fields) {
		$this->db->insert('inventory_partnerships', $fields);
		return $this->db->insert_id();
	}
	
	public function updatePartnership($partnership_id, $fields) {
		$this->db->where('partnership_id', $partnership_id);
		$this->db->update('inventory_partnerships', $fields);
		
		return null;
	}
}
?>