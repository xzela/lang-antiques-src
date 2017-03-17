<?php
/**
 * This class is used to Merge Customers
 * 
 * @author zeph
 *
 */
class Customer_merge_model extends Model {

	var $ci;
	
	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
	}
	
	/**
	 * Deletes the old customer
	 * 
	 * @param [int] $id = old customer id
	 * 
	 * @return null
	 */
	public function deleteCustomer($id) {
		$this->db->where('customer_id', $id);
		$this->db->limit(1); //always limit one
		$this->db->delete('customer_info');
		
		return null;
	} //end deleteCustomer();
	
	/**
	 * Merges Customer Invoices
	 * 
	 * Transfder any old customer invoices into the new customer
	 * 
	 * @param [int] $old_id = old customer id
	 * @param [int] $new_id = new customer id (primary)
	 * 
	 * @return null
	 */
	public function mergeCustomerInvoices($old_id, $new_id) {
		$data = array('buyer_id' => $new_id);
		$this->db->where('buyer_id', $old_id);
		$this->db->where_in('buyer_type', array(1,3));
		$this->db->update('invoice', $data);
		
		return null;
	} //end mergeCustomerInvoices();
	
	/**
	 * Merges Customer Invoice Items
	 * 
	 * transfers any old customer invoice items into
	 * the new customer
	 * 
	 * @param [int] $old_id = old customer id
	 * @param [int] $new_id = new customer id (primary)
	 * 
	 * @return null
	 */
	public function mergeCustomerInvoiceItems($old_id, $new_id) {
		$data = array('buyer_id' => $new_id);
		$this->db->where('buyer_id', $old_id);
		$this->db->where_in('buyer_type', array(1,3));
		$this->db->update('invoice_items', $data);
		
		return null;
	} //end mergeCustomerInvoiceItems();
	
	/**
	 * Merges Customer Invoice Payments
	 * 
	 * Transfer any customer invoice payments from an old customer
	 * to the new customer
	 * 
	 * @param [int] $old_id = old customer id
	 * @param [int] $new_id = new customer id (primary)
	 * 
	 * @return null
	 */
	public function mergeCustomerInvoicePayments($old_id, $new_id) {
		$data = array('buyer_id' => $new_id);
		$this->db->where('buyer_id', $old_id);
		$this->db->where_in('buyer_type', array(1,3));
		$this->db->update('invoice_payments', $data);
		
		return null;
	} //end mergeCustomerInvoicePayments();
	
	/**
	 * Merges Customer Jobs
	 * 
	 * Transfers old customer jobs into the new customer
	 * 
	 * @param [int] $old_id = old customer id
	 * @param [int] $new_id = new customer id (primary)
	 * 
	 * @return null
	 */
	public function mergeCustomerJobs($old_id, $new_id) {
		$data = array('customer_id' => $new_id);
		$this->db->where('customer_id', $old_id);
		$this->db->update('customer_jobs', $data);
		
		return null;
	} //end mergeCustomerJobs();
	
	/**
	 * Merges Customer Layaway Payments
	 * 
	 * Transfers any old customer layaway Payments into
	 * a new customer
	 *   
	 * @param [int] $old_id = old customer id
	 * @param [int] $new_id = new customer id (primary)
	 * 
	 * @return null
	 */
	public function mergeCustomerLayawayPayments($old_id, $new_id) {
		$data = array('customer_id' => $new_id);
		$this->db->where('customer_id', $old_id);
		$this->db->update('invoice_layaway', $data);
		
		return null;
	} //end mergeCustomerLayawayPayments();
	
	/**
	 * Merge Customer Returns
	 * 
	 * Transfers customer returns to the new customer
	 * 
	 * @param [int] $old_id = old customer id
	 * @param [int] $new_id = new customer id (primary)
	 * 
	 * @return null
	 */
	public function mergeCustomerReturns($old_id, $new_id) {
		$data = array('buyer_id' => $new_id);
		$this->db->where('buyer_id', $old_id);
		$this->db->where_in('buyer_type', array(1,3));
		$this->db->update('returns', $data);
		
		return null;
	} //end mergeCustomerReturns();
	
	/**
	 * Merges Customer Store Credit
	 * 
	 * Transfers Custoemr Store credit information from
	 * a merged customer
	 * 
	 * @param [int] $old_id = old customer id
	 * @param [int] $new_id = new customer id (primary)
	 * 
	 * @return null
	 */
	public function mergeCustomerStoreCredit($old_id, $new_id) {
		$data = array('customer_id' => $new_id);
		$this->db->where('customer_id', $old_id);
		$this->db->update('customer_store_credit', $data);
		
		return null;
		
	} //end mergeCustomerStoreCredit();
	
	/**
	 * Merges inventory items based on which 
	 * customer was the seller,
	 * 
	 * Used to trasfer ownership of inventory items from
	 * a merged customer
	 * 
	 * @param [int] $old_id = old customer id
	 * @param [int] $new_id = new customer id (primary)
	 * 
	 * @return null
	 */
	public function mergeInventorySellerId($old_id, $new_id) {
		$data = array('seller_id' => $new_id);
		$this->db->where('seller_id', $old_id);
		$this->db->where('seller_type', 2);
		$this->db->update('inventory', $data);
		
		return null;
		
	} //end mergeInventorySellerId();
	
} //end Customer_merge_model();
?>