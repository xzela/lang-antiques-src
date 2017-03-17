<?php
class Vendor_reports_model extends Model {
	
	var $ci;
	
	function __construct() {
		parent::Model();
		$this->ci =& get_instance();
		$this->load->database();
		
	}
	
	/**
	 * This is used for listing through the vendors
	 * 
	 * @param [int] $per_page = number of results per page
	 * @param [int] $offset = offset used 
	 * @param [string] $sort = field to sort by
	 * @param [string] $direction = direction of sort
	 * 
	 * @return [array] multidimtonal array of vendors
	 */
	public function getAvailableVendors($per_page, $offset, $sort = FALSE, $direction = NULL) {
		$this->db->start_cache(); 
		$this->db->from('vendor_info');
		$this->db->where('active', 1);
		if($sort != FALSE) {
			$this->db->order_by($sort, $direction);
		}
		
		$this->db->stop_cache(); //Stops Cache!
		$count = $this->db->get();
		$this->db->limit($per_page, $offset);
		$query = $this->db->get();
		$this->db->flush_cache(); //flush Cache!
		
		$data = array();
		if($count->num_rows() > 0) {
			//$data['num_rows'] = $count->num_rows(); 
			foreach ($query->result_array() as $row) {
				if($row['name'] == '') {
					$row['name'] = $row['first_name'] . ' ' . $row['last_name'];
				}
				$data[] = $row;
			}
		}
		$data['num_rows'] = $count->num_rows();
		return $data; //array
	} //end getAvailableVendors()
	
	/**
	 * This returns all of the items that have been purchased by
	 * a specific vendor
	 * 
	 * @param [int] $id = vendor_id
	 * 
	 * @return [array] multidimential array of items;
	 */
	function getVendorPurchasedItems($id) {
		$this->ci->load->model('image/image_model');
		
		$this->db->select('invoice.invoice_id, invoice.total_price, invoice.tax, 
							invoice.invoice_status, invoice_items.invoice_item_id, 
							invoice_items.item_number, invoice_items.item_id, 
							invoice_items.sale_price, invoice_items.sale_tax, inventory.item_name');	
		$this->db->from('invoice');
		$this->db->join('invoice_items', 'invoice_items.invoice_id = invoice.invoice_id', 'left');
		$this->db->join('inventory', 'inventory.item_id = invoice_items.item_id', 'left');
		$this->db->where_in('invoice.buyer_type', 2); //1=customer, 2=vendor, 3=internet_customer
		$this->db->where('invoice.buyer_id', $id);

		$data = array();
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['image_array'] = $this->ci->image_model->getItemImages($row['item_id']);
				$data[] = $row;
			}
		}		
		return $data; //array
	} //end getVendorPurchasedItems()
	
	/**
	 * This returns all of the item that a specific vendor
	 * sold to us. The name kind of sucks. 
	 * @TODO rename this method
	 * 
	 * @param [int] $id = vendor_id;
	 * 
	 * @return [array] multidimential array of items;
	 */
	function getVendorSoldItems($id) {
		$this->ci->load->model('image/image_model');
		$this->ci->load->model('utils/lookup_list_model');
		$status = $this->ci->lookup_list_model->getItemStatus();
		
		$this->db->from('inventory');
		$this->db->where('seller_id', $id);
		$this->db->where('seller_type', 1); //1=vendor, 2=customer
		$this->db->order_by('purchase_date', 'DESC');
		
		$data = array();
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['item_status_text'] = $status[$row['item_status']]['name'];
				$row['image_array'] = $this->ci->image_model->getItemImages($row['item_id']);
				$data[] = $row;
			}
		}
		
		return $data; //array
	} //end getVendorSoldItems();
	
} //end Vendor_reports_model();
?>