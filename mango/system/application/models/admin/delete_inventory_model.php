<?php
/**
 * Delete inventory Related methods 
 * 
 * @author zeph
 *
 */
class Delete_inventory_model extends Model {
	
	var $ci;
	
	function __construct() {
		parent::Model();
		
		$this->load->database();
		$this->ci =& get_instance();		
	}
	
	/**
	 * Checks to see if any invoices are applied to the current item
	 * If an invoice is found, it will return true, else false;
	 *
	 * @param [int] $id = item_id
	 * @return bool;
	 */
	public function checkForInvoice($id) {
		$this->db->from('invoice');
		$this->db->join('invoice_items', 'invoice_items.invoice_id = invoice.invoice_id');
		$this->db->where('item_id', $id);
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			return true; //bool
		}
		return false; //bool
	} //end checkForInvoice();
	
	/**
	 * Calls a remote procedure to delete an item 
	 * from inventory
	 *
	 * @param [int] $id
	 * 
	 * @return null
	 */
	public function deleteInventoryItem($id) {
		$this->db->query("call delete_item($id);");
	} //end deleteInventoryItem();
	
	/**
	 * Gets all Delete History for each item
	 * 
	 * @return array[] = database records
	 */
	public function getDeleteHistory() {
		$this->db->from('inventory_delete_history');
		$this->db->join('users', 'users.user_id = inventory_delete_history.user_id');
		$this->db->order_by('delete_date', 'DESC');
		
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) { 
				$data[] = $row;
			}
		}
		
		return $data; //array
	} //end getDeleteHistory();
		
	/**
	 * Inserts a history record for each item that is deleted
	 * 
	 * @param [int] $id = item id
	 * @param [int] $user_id = user id
	 * @param [text] $reason = reason for delete
	 * 
	 * @return null
	 */
	public function insertDeleteHistory($id, $user_id, $reason) {
		$this->ci->load->model('inventory/inventory_model');
		$item = $this->ci->inventory_model->getItemData($id);
		$fields = array('item_id' => $id,
						'item_number' => $item['item_number'],
						'item_name' => $item['item_name'],
						'user_id' => $user_id,
						'delete_reason' => $reason);
		
		$this->db->insert('inventory_delete_history', $fields);
	} //end insterDeleteHistory();
	

}// end Delete_inventory_model();
?>