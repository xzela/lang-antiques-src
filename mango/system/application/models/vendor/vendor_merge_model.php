<?php
/**
 * This class is used to Merge Vendors
 * 
 * @author zeph
 *
 */
class Vendor_merge_model extends Model {

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
	public function deleteVendor($id) {
		$this->db->where('vendor_id', $id);
		$this->db->limit(1); //always limit one
		$this->db->delete('vendor_info');
		
		return null;
	} //end deleteCustomer();
	
	/**
	 * Merges Vendor Invoices
	 * 
	 * Transfder any old vendor invoices into the new vendor
	 * 
	 * @param [int] $old_id = old vendor id
	 * @param [int] $new_id = new vendor id (primary)
	 * 
	 * @return null
	 */
	public function mergeVendorInvoices($old_id, $new_id) {
		$data = array('buyer_id' => $new_id);
		$this->db->where('buyer_id', $old_id);
		$this->db->where_in('buyer_type', array(2));
		$this->db->update('invoice', $data);
		
		return null;
	} //end mergeVendorInvoices();
	
	/**
	 * Merges Vendor Invoice Items
	 * 
	 * transfers any old Vendor invoice items into
	 * the new customer
	 * 
	 * @param [int] $old_id = old vendor id
	 * @param [int] $new_id = new vendor id (primary)
	 * 
	 * @return null
	 */
	public function mergevendorInvoiceItems($old_id, $new_id) {
		$data = array('buyer_id' => $new_id);
		$this->db->where('buyer_id', $old_id);
		$this->db->where_in('buyer_type', array(2));
		$this->db->update('invoice_items', $data);
		
		return null;
	} //end mergeVendorInvoiceItems();
	
	/**
	 * Merges Vendor Invoice Payments
	 * 
	 * Transfer any Vendor invoice payments from an old customer
	 * to the new customer
	 * 
	 * @param [int] $old_id = old vendor id
	 * @param [int] $new_id = new vendor id (primary)
	 * 
	 * @return null
	 */
	public function mergeVendorInvoicePayments($old_id, $new_id) {
		$data = array('buyer_id' => $new_id);
		$this->db->where('buyer_id', $old_id);
		$this->db->where_in('buyer_type', array(2));
		$this->db->update('invoice_payments', $data);
		
		return null;
	} //end mergeVendorInvoicePayments();
	
	/**
	 * Merge Vendor Returns
	 * 
	 * Transfers vendor returns to the new vendor
	 * 
	 * @param [int] $old_id = old vendor id
	 * @param [int] $new_id = new vendor id (primary)
	 * 
	 * @return null
	 */
	public function mergeVendorReturns($old_id, $new_id) {
		$data = array('buyer_id' => $new_id);
		$this->db->where('buyer_id', $old_id);
		$this->db->where_in('buyer_type', array(2));
		$this->db->update('returns', $data);
		
		return null;
	} //end mergeVendorReturns();
	
	/**
	 * Merges Vendor Store Credit
	 * 
	 * Transfers Vendor Store credit information from
	 * a merged Vendor
	 * 
	 * @param [int] $old_id = old customer id
	 * @param [int] $new_id = new customer id (primary)
	 * 
	 * @return null
	 */
	public function mergeVendorStoreCredit($old_id, $new_id) {
		$data = array('vendor_id' => $new_id);
		$this->db->where('vendor_id', $old_id);
		$this->db->update('vendor_store_credit', $data);
		
		return null;
		
	} //end mergeVendorStoreCredit();
	
	/**
	 * Merges inventory items based on which 
	 * vendor was the seller,
	 * 
	 * Used to trasfer ownership of inventory items from
	 * a merged vendor
	 * 
	 * @param [int] $old_id = old vendor id
	 * @param [int] $new_id = new vendor id (primary)
	 * 
	 * @return null
	 */
	public function mergeInventorySellerId($old_id, $new_id) {
		$data = array('seller_id' => $new_id);
		$this->db->where('seller_id', $old_id);
		$this->db->where('seller_type', 1); //1=vendor, 2=customer
		$this->db->update('inventory', $data);
		
		return null;
		
	} //end mergeInventorySellerId();
	
} //end Vendor_merge_model();
?>