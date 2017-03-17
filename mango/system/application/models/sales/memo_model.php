<?php
class Memo_model extends Model {
	
	var $ci;
	
	function __construct() {
		parent::Model();
		$this->ci =& get_instance();
		$this->load->database();
		
	}
	
	/**
	 * Closes and maybe converts a Memo
	 * 
	 * @param [int] $memo_id = memo id
	 * @param [bool] $convert = whether to convert or not
	 * 
	 * @return null;
	 */
	public function closeMemo($memo_id, $convert = false) {
		$fields = array();
			$fields['memo_close_date'] = date('Y-m-d');
			if($convert) {
				$fields['invoice_status'] = 4; //memo converted status	
			}
			else {
				$fields['invoice_status'] = 3; //memo closed status
			}
		$this->db->where('invoice_id', $memo_id);
		$this->db->update('invoice', $fields);
		
		return null;
	} //end closeMemo();
	
	/**
	 * Closes and maybe converts a memo invoice item into a
	 * 
	 * @param [int] $invoice_item_id = invoice item id
	 * @param [bool] $convert = whether to convert or not
	 * 
	 * @return null;
	 */
	public function closeMemoInvoiceItem($invoice_item_id, $convert = false) {
		$fields = array();
			if($convert) { //set item status to 4: //converted
				$fields['item_status'] = 4; //converted	
			}
			else {
				$fields['item_status'] = 3; //closed
			}
		$this->db->where('invoice_item_id', $invoice_item_id);
		$this->db->update('invoice_items', $fields);
		
		return null;
	} //end closeMemoInvoiceItem()
	
	/**
	 * Closes a special item 
	 * updates the item_status to 3, memo_closed 
	 * 
	 * @TODO This may be repeat code, see invocie_model->updateSpecicalItem()
	 * This could be done in the controller
	 * 
	 * @param [int] $special_item_id = special_item_id
	 * @param [int] $memo_id = invoice id of memo
	 * @param [bool] $convert = was special item converted into invoice?
	 * 
	 * @return null
	 */
	public function closeMemoInvoiceSpecialItem($special_item_id, $memo_id = null, $convert = false) {
		$fields = array();
			$fields['memo_id'] = $memo_id;
			if($convert) { //has been converted into an invoice
				$fields['item_status'] = 4; //converted to invoice
			}
			else {//just close it
				$fields['item_status'] = 3; //memo_closed
			}
		$this->db->where('special_item_id', $special_item_id);
		$this->db->update('invoice_special_items', $fields);
		
		return null;
	} //end closeMemoInvoiceSpecialItem()
	
	/**
	 * Gets all of the invoice item data and inserts a new
	 * invoice item record with the new invoice id
	 * 
	 * @param [int] $invoice_item_id = invoice item id
	 * @param [int] $new_invoice_id = new invoice id
	 * 
	 * @return [int] new invoice item id;
	 */
	public function copyMemoInvoiceItem($invoice_item_id, $new_invoice_id) {
		$this->db->from('invoice_items');
		$this->db->where('invoice_item_id', $invoice_item_id);
		$item = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$item = $row; //get all data
			}
			unset($item['invoice_item_id']); //remove invoice_item_id, causes index expection
			$item['item_status'] = 0; //make status 0=normal
			$item['invoice_id'] = $new_invoice_id; //update invoice_id to reflect the new invoice id
			$new_invoice_item_id = $this->db->insert('invoice_items', $item);
		}
		
		return $new_invoice_item_id; //int
	} //end copyMemoInvoiceItem()
	
	/**
	 * Copies a Special Item from a memo onto an Invoice
	 * Requires the invoice_id and special_item_id
	 * 
	 * This could be moved to the controller, atleast the forloop part
	 * 
	 * @param [int] $special_item_id = special item id
	 * @param [int] $invoice_id = invoice id
	 * 
	 * @return null
	 */
	public function copyMemoInvoiceSpecialItem($special_item_id, $invoice_id) {
		$this->db->from('invoice_special_items');
		$this->db->where('special_item_id', $special_item_id);
		$item = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$item = $row; //get all data
			}
			unset($item['special_item_id']); //remove invoice_item_id, causes index expection
			$item['item_status'] = 0; //make status 0=normal
			$item['invoice_id'] = $invoice_id; //update invoice_id to reflect the new invoice id
			$this->db->insert('invoice_special_items', $item);
		}
		
		return null;
	} //end copyMemoInvoiceSpecialItem();
	
	/**
	 * Converts a Memo into an Invoice, 
	 * 
	 * @param [int] $memo_id
	 * 
	 * @return [int] invoice id
	 */
	public function convertMemo($memo_id) {
		$memo = $this->getMemoData($memo_id);
		unset($memo['invoice_id']); //remove invoice_id, causes expections;
		$memo['invoice_status'] = 1; //make invoice editable;
		$memo['invoice_type'] = 0; //make normal invocie
		$memo['memo_close_date'] = null;
		$memo['memo_id'] = $memo_id; //old memo id
		
		$this->db->insert('invoice', $memo);
		$invoice_id = $this->db->insert_id();
		
		return $invoice_id; //int
	} //end convertMemo();
	
	/**
	 * Inserts a new Memo into the database
	 * 
	 * @param [array] $fields = array of column value pairs
	 * 
	 * @return [int] invoice_id = invoice id
	 */
	public function createMemo($fields) {
		$this->db->insert('invoice', $fields);
		
		return $this->db->insert_id(); //int
	} //end createMemo()
	
	/**
	 * Returns all the data for a specific invoice/memo
	 * @param [int] $memo_id = invoice_id
	 * 
	 * @return [array] = array of column value pairs
	 */
	public function getMemoData($memo_id) {
		$this->db->from('invoice');
		$this->db->where('invoice_id', $memo_id);
		$this->db->where('invoice_type', 3);
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row;
			}
		}
		return $data; //array
	} //end getMemoData();

	/**
	 * Attemps to return all of the pending conversion
	 * invoice items.
	 * 
	 * @param [int] $memo_id = memo id (invoice id)
	 * 
	 * @return [array] = multi-dim array of items
	 */
	public function getPendingMemoConverionItems($memo_id) {
		$this->db->from('invoice_items');
		$this->db->where('invoice_id', $memo_id);
		$this->db->where('item_status', 5); //pending conversion status
		$query = $this->db->get();
		
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data; //array
	} //end getPendingMemoConversionItems();
	
	/**
	 * Attemps to return all of the pending conversion
	 * invoice special items.
	 * 
	 * @param [int] $memo_id = memo id (invoice id)
	 * 
	 * @return [array] = multi-dim array of special items
	 */
	public function getPendingMemoConverionSpecialItems($memo_id) {
		$this->db->from('invoice_special_items');
		$this->db->where('invoice_id', $memo_id);
		$this->db->where('item_status', 5); //pending conversion status
		$query = $this->db->get();
		
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data; //array
	} //end getPendingMemoConversionSpecialItems();
	
	/**
	 * Returns all of the pending returned invvoice items for
	 * a specific memo
	 * 
	 * @param [int] $memo_id = memo id
	 * 
	 * @return [array] = multi-dim array of invoice items  
	 */
	public function getPendingMemoReturnedItems($memo_id) {
		$this->db->from('invoice_items');
		$this->db->where('invoice_id', $memo_id);
		$this->db->where('item_status', 2); //pending return status
		$query = $this->db->get();
		
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data; //array
	} //end getPendingMemoReturnItems();
	
	/**
	 * Returns all of the pending returend memo special items
	 * for a specific memo
	 * 
	 * @param [int] $memo_id = memo id
	 * 
	 * @return [array] = multi-dim array of special items
	 */
	public function getPendingMemoReturnedSpecialItems($memo_id) {
		$this->db->from('invoice_special_items');
		$this->db->where('invoice_id', $memo_id);
		$this->db->where('item_status', 2); //pending return status
		$query = $this->db->get();
		
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		
		return $data; //array
	} //end getPendingMemoReturnedSpecialItems();
	
	/**
	 * Returns all of the invoice items which might be returnable
	 * 
	 * @param [int] $memo_id = memo id
	 * 
	 * @return [array] = multi-dim array of invoice items
	 */
	public function getReturnableMemoInvoiceItems($memo_id) {
		$status = array(0,2,5); //0=normal, 2=pending return, 5=pending conversion
		$data = array();
			
		$this->db->from('invoice_items');
		$this->db->where('invoice_id', $memo_id);
		$this->db->where_in('item_status', $status);
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		
		return $data; //array
	} //end getReturnableMemoInvoiceItems();
	
	/**
	 * Returns all of the special items which might be returnable.
	 * 
	 * @param [int] $memo_id = memo id
	 * 
	 * @return [array] = multi-dim array of special items data
	 */
	public function getReturnableMemoInvoiceSpecialItems($memo_id) {
		$status = array(0,2,5); //0=normal, 2=pending return, 5=pending conversion
		$data = array();
			
		$this->db->from('invoice_special_items');
		$this->db->where('invoice_id', $memo_id);
		$this->db->where_in('item_status', $status);
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		
		return $data; //array
	} //end getReturnableMemoInvoiceSpecicalItems()
	
	/**
	 * Updates a memo item status to closed [3]
	 *  
	 * @param [int] $invoice_item_id = invoice item id
	 * 
	 * @return null;
	 */
	public function returnMemoInvoiceItem($invoice_item_id) {
		$fields = array();
			$fields['item_status'] = 3; //memo closed status
		$this->db->where('invoice_item_id', $invoice_item_id);
		$this->db->limit(1);
		$this->db->update('invoice_items', $fields);
		
		return null;
	} //end returnMemoInvoiceItem();

	/**
	 * Updates a memo special item status to closed [3].
	 * 
	 * @param [int] $invoice_special_id = invoice special item id
	 * 
	 * @return null;
	 */
	public function returnMemoInvoiceSpecialItem($invoice_special_id) {
		$fields = array();
			$fields['item_status'] = 3; //memo closed status
		$this->db->where('special_item_id', $invoice_special_id);
		$this->db->limit(1);
		$this->db->update('invoice_special_items', $fields);
		
		return null; //null
	} //end returnMemoInvoiceSpecialItem();
	
	/**
	 * @TODO Not sure what this does, (updateInventoryItemsOnMemo) 
	 * will need to research this a bit more
	 * 
	 * @param [int] $invoice_id = invoice id
	 * 
	 * @return null
	 */
	public function updateInventoryItemsOnMemo($invoice_id) {
		$this->db->from('invoice_items');
		$this->db->where('invoice_id', $invoice_id);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$fields = array();
					$fields['item_status'] = 4; //converted? 
				$this->db->where('item_id', $row['item_id']);
				$this->db->limit(1);
				$this->db->update('inventory', $fields);		
			}
		}
		
		return null; //null
	} //end updateInventoryItemsOnMemo()
	
	/**
	 * Verifies that Items are pending a memo conversion. 
	 * If false, then no items are ready to conversion, 
	 * else true, then items are ready 
	 * 
	 * @param [int] $memo_id = memo id
	 * 
	 * @return [bool]
	 */
	public function verifyPendingConversionItems($memo_id) {
		$data = array();
		$b = false;
		$data['items'] = $this->getPendingMemoConverionItems($memo_id);
		$data['specials'] = $this->getPendingMemoConverionSpecialItems($memo_id);
		
		if(sizeof($data['items']) > 0 || sizeof($data['specials']) > 0) {
			$b = true;
		}
		
		return $b; //bool
	} //end verifyPendingConversionItems();
	
} //end Memo_model()
?>