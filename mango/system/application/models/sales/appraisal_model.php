<?php
/**
 * Good Luck! hahahahah
 * 
 * 
 * @author sucker
 *
 */
class Appraisal_model extends Model {
	
	var $ci;
	
	function __construct() {
		parent::Model();

		$this->load->database();
		$this->ci =& get_instance();
	}
	
	public function createAppraisal($fields) {
		$this->db->insert('inventory_appraisels', $fields); //@TODO fix appraisal database misspelling
		$id = $this->db->insert_id();
		$this->PV_sendAppraisalEmail($id);
		
		return $id;
	}
	
	public function getAppraisalData($id) {
		$this->db->from('inventory_appraisels'); //@TODO fix appraisal database misspelling
		$this->db->where('appraisel_id', $id); //@TODO fix appraisal database misspelling
		$data =  array();
		
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['appraisal_id'] = $row['appraisel_id']; //@TODO fix appraisal database misspelling 
				$data = $row; //@TODO fix appraisal database misspelling
			}
		}
		return $data;
	}
	
	public function getAppriasalDiamonds($item_id) {
		$this->ci->load->model('inventory/diamond_model');
		
		$this->db->from('diamond_info');
		$this->db->join('stone_type', 'stone_type.stone_id = diamond_info.d_type_id');
		$this->db->join('diamond_cut', 'diamond_cut.cut_id = diamond_info.d_cut_id');
		$this->db->where('diamond_info.item_id', $item_id);
		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['color'] = $this->ci->diamond_model->getDiamondColor($row['d_id'], true);
				$row['clarity'] = $this->ci->diamond_model->getDiamondClarity($row['d_id'], true);
				$row['measurements'] = $this->PV_formatMeasurements($row['d_x1'], $row['d_x2'], $row['d_x3']);
				
				$data[$row['d_id']] = $row;
			}
		}
		
		return $data;
	}
	
	public function getAppriasalGemstones($item_id) {
		$this->ci->load->model('inventory/gemstone_model');
		
		$this->db->from('stone_info');
		$this->db->join('stone_type', 'stone_type.stone_id = stone_info.gem_type_id');
		$this->db->join('diamond_cut', 'diamond_cut.cut_id = stone_info.gem_cut_id');
		$this->db->where('stone_info.item_id', $item_id);
		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['measurements'] = $this->PV_formatMeasurements($row['gem_x1'], $row['gem_x2'], $row['gem_x3']);
				
				$data[$row['gem_id']] = $row;
			}
		}
		
		return $data;
	}
	
	public function getAppraisedItems($invoice_id) {
		$this->ci->load->model('inventory/inventory_model');
		$this->ci->load->model('sales/invoice_model');
		
		$this->db->from('inventory_appraisels'); //@TODO fix appraisal database misspelling
		$this->db->where('invoice_id', $invoice_id);
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$item_data = $this->ci->inventory_model->getItemData($row['item_id']);
				$invoice_item = $this->ci->invoice_model->getInvoiceItemData($invoice_id, $row['item_id']);
				
				$row['item_number'] = $item_data['item_number'];
				$row['image_array'] = $item_data['image_array'];
				$row['sale_price'] = $invoice_item['sale_price'];
				$row['sale_tax'] = $invoice_item['sale_tax'];
				$data[$row['appraisel_id']] = $row; //@TODO fix appraisal database misspelling
			}
		}
		return $data;
	}
	
	public function getAppraisalItemPlot($plot_id) {
		$this->db->from('inventory_appraisel_plots'); //@TODO fix appraisal database misspelling
		$this->db->where('appraisel_image_id', $plot_id); //@TODO fix appraisal database misspelling
		
		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['appraisal_image_id'] = $row['appraisel_image_id']; //@TODO fix appraisal database miseppling
				$data = $row;
			}
		}
		return $data;
	}
	
	public function getAppraisalPlotsData($id) {
		$this->db->from('inventory_appraisel_plots'); //@TODO fix appraisal database misspelling
		$this->db->where('appraisel_id', $id); //@TODO fix appraisal database misspelling
		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[$row['appraisel_image_id']] = $row; //@TODO fix appraisal database misspelling
			}
		}
		return $data;
	}
	
	public function listAllAppraisals($per_page, $offset, $user_id = null) {
		$this->ci->load->model('customer/customer_model');
		$this->ci->load->model('inventory/inventory_model');
		$this->ci->load->model('user/user_model');
		$this->db->start_cache();
		$this->db->from('inventory_appraisels'); //@TODO fix database misspelling
		if($user_id != null) {
			$this->db->where('user_id', $user_id);
		}
		$this->db->order_by('appraisel_date', 'DESC'); //@TODO fix database misspelling
		
		$this->db->stop_cache(); //stop cache!
		$count = $this->db->get(); //get count
		
		//cache has been saved, we can call the same query again
		$this->db->limit($per_page, $offset);
		$query = $this->db->get();
		$this->db->flush_cache(); //flush that cache!
		$data = array();
		if($count->num_rows() > 0) {
			$data['num_rows'] = $count->num_rows();
			foreach ($query->result_array() as $row) {
				$customer_data = $this->ci->customer_model->getCustomerData($row['customer_id']);
				$item_data = $this->ci->inventory_model->getItemData($row['item_id']);
				$user_data = $this->ci->user_model->getUserData($row['user_id']);
				
				$row['item_number'] = $item_data['item_number'];
				$row['appraiser_name'] = $user_data['first_name'] . ' ' . $user_data['last_name'];
				$row['appraisal_id'] = $row['appraisel_id']; //@TODO fix database misspelling
				$row['appraisal_date'] = $row['appraisel_date']; //@TODO fix database misspelling
				
				if(sizeof($customer_data) > 0 ) {
					$row['buyer_name'] = $customer_data['first_name'] . ' ' . $customer_data['last_name'];	
				}
				else {
					$row['buyer_name'] = 'Unknown Customer';
				}
				$data[$row['invoice_id']] = $row;				
			}
		}
		return $data;		
	} //end listAllAppraisals();
	
	/**
	 * 
	 * @param unknown_type $appraisal_id
	 * @param unknown_type $gemstone_id
	 * @param unknown_type $gemstone_type
	 */
	public function testForPlot($appraisal_id, $gemstone_id, $gemstone_type) {
		$this->db->from('inventory_appraisel_plots'); //@TODO fix appraisal database misspelling
		$this->db->where('appraisel_id', $appraisal_id); //@TODO fix appraisal database misspelling
		$this->db->where('gemstone_id', $gemstone_id);
		$this->db->where('gemstone_type', $gemstone_type);
		
		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['appraisal_image_id'] = $row['appraisel_image_id']; //@TODO fix appraisal database miseppling 
				$data = $row;
			}
		}
		return $data;
		
	} //end testForPlot()
	
	/**
	 * 
	 * @param [int] $appraisal_id
	 * @param [int] $fields
	 */
	public function updateAppraisedItem($appraisal_id, $fields) {
		$this->db->where('appraisel_id', $appraisal_id); //@TODO fix appraisal database misspelling
		$this->db->update('inventory_appraisels', $fields); //@TODO fix appraisal database misspelling
		
		$this->PV_sendAppraisalEmail($appraisal_id); //null
	} //end updateAppraisedItem();
	
	/**
	 * 
	 * @param [int] $id = appraisal id
	 * @param [string] $field 
	 * @param [string] $value
	 * 
	 * @return [string]
	 */
	public function AJAX_updateAppraisalField($id, $field, $value) {
		$data = array($field => $value);
		$this->db->where('appraisel_id', $id); //@TODO fix appraisal database misspelling
		$this->db->update('inventory_appraisels', $data); //@TODO fix appraisal database misspelling
		
		return $value; //string
	} //end AJAX_updateAppraisalField();
	
	/**
	 * Takes a set of measurements and formats them 
	 * 
	 * @param [float] $x1
	 * @param [float] $x2
	 * @param [float] $x3
	 * 
	 * @return null;
	 */
	private function PV_formatMeasurements($x1 = null, $x2 = null, $x3 = null) {
		$measurements = null;
		if((string)$x1 != 0) {
			if((string)$x3 != 0) {
				$measurements = number_format($x1, 2) . ' x ' . number_format($x2, 2) . ' x ' . number_format($x3, 2); 
			}
			else {	
				$measurements = number_format($x1, 2) . ' - ' . number_format($x2, 2);
			}
		}
		
		return $measurements; //string
	} //end PV_formatMeasurements();
	
	/**
	 * Private method:
	 * Sends an email to the user who has been assigned 
	 * to this appraisal
	 * 
	 * @param [int] $appraisal_id = appraisal id
	 * 
	 * @return null
	 */
	private function PV_sendAppraisalEmail($appraisal_id) {
		$this->ci->load->model('inventory/inventory_model');
		$this->ci->load->model('user/user_model');
		$this->ci->load->model('customer/customer_model');
		
		$appraisal = $this->getAppraisalData($appraisal_id);
		$item = $this->ci->inventory_model->getItemData($appraisal['item_id']);
		$user = $this->ci->user_model->getUserData($appraisal['user_id']);
		$customer = $this->ci->customer_model->getCustomerData($appraisal['customer_id']);
		
		$subject = "You have a new Appraisal for " . $customer['first_name'] . ' ' . $customer['last_name'] . "!";
		$text_message = "Someone (probably Alison) has assigned an Appraisal for you to do.<br />"
			. "Invoice: " . $appraisal['invoice_id'] . " <br />"
			. "Customer: " . $customer['first_name'] . ' ' . $customer['last_name'] . "<br />"
			. "Item Number: " . $item['item_number'] . " <br />"
			. "Note: " . $appraisal['email_note'] . " <br />";
		$headers = "From: alert@clangity.system.com\nContent-Type: text/html; charset=iso-8859-1";
		$email = $user['email'];
		mail($email, $subject, $text_message, $headers);
		
		return null;
	} //end PV_sendAppraisalEmail();
	
} //end Appraisal_model();
?>