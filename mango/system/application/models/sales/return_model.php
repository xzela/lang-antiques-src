<?php
/**
 * Return_model
 *
 * Returns items and creates return slips.
 *
 *
 * @author user
 *
 */
class Return_model extends Model {

	var $ci;

	function __construct() {
		parent::Model();
		$this->ci =& get_instance();
		$this->load->database();
	}

	/**
	 * Returns all of the return pending invoice items
	 * on a invoice
	 *
	 * @param [int] $invoice_id = invoice id
	 *
	 * @return [array] = multi-dim array of column value pairs
	 */
	public function getPendingReturnedInvoiceItems($invoice_id) {
		$this->db->from('invoice_items');
		$this->db->where('invoice_id', $invoice_id);
		$this->db->where('item_status', 2);
		$data = array();
		$query = $this->db->get();

		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[$row['invoice_item_id']] = $row;
			}
		}
		return $data; //array
	} //end getPendingReturnedInvoiceItems();

	/**
	 * Returns all of the Pending
	 *
	 * @param [int] $invoice_id = invoice id
	 *
	 * @return [array] = multi-dim array column value pair
	 */
	public function getPendingReturnedSpecialItems($invoice_id) {
		$this->db->from('invoice_special_items');
		$this->db->where('invoice_id', $invoice_id);
		$this->db->where('item_status', 2);
		$data = array();
		$query = $this->db->get();

		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[$row['special_item_id']] = $row;
			}
		}
		return $data; //array
	} //end getPendingReturnedSpecialItems();

	/**
	 * Get total amount the return might be.
	 * Can be edited later
	 *
	 * @param [int] $invoice_id = invoice id
	 *
	 * @return [float] = return amount
	 */
	public function getReturnTotalAmount($invoice_id) {
		$items = $this->getPendingReturnedInvoiceItems($invoice_id);
		$specials = $this->getPendingReturnedSpecialItems($invoice_id);
		$amount = 0;
		foreach($items as $item) {
			$amount += $item['sale_price'] + $item['sale_tax'];
		}
		foreach($specials as $item) {
			$amount += $item['item_price'] + $item['item_tax'];
		}

		return $amount; //float
	} //end getReturnTotalAmmount()

	/**
	 * Returns all of the data for a specific
	 * return
	 * @TODO Refactor this, something is wrong with this
	 *
	 * @param [int] $id = return id
	 * @param [string] $type = 'return_id'|'invoice)id'
	 *
	 * @return [array] column value pair
	 */
	public function getReturnData($id, $type = null) {
		$this->db->from('returns');
		if($type == 'invoice_id') {
			$this->db->where('invoice_id', $id);
		}
		else {
			$this->db->where('return_id', $id);
		}
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row;
			}
		}
		return $data; //array
	} //end getReturnData();

	/**
	 * Returns all of the invoice items applied to a return
	 *
	 * @param [int] $return_id = return id
	 * @param [bool] $extra = used to fetch extra information (not used)?
	 *
	 * @return [array] = multi-dim array of column value pairs
	 */
	public function getReturnedInvoiceItems($return_id, $extra = true) {
		$this->ci->load->model('image/image_model');
		$this->db->distinct();
		$this->db->select('return_items.*, invoice_items.*, inventory.*', false);
		$this->db->from('invoice_items');
			$this->db->join('return_items', 'invoice_items.item_id = return_items.item_id');
			$this->db->join('inventory', 'invoice_items.item_id = inventory.item_id');
		$this->db->where('return_items.return_id', $return_id);
		$this->db->where('return_items.return_id', $return_id);
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['image_array'] = $this->ci->image_model->getItemImages($row['item_id']);
				$data[$row['return_item_id']] = $row;
			}
		}
		return $data; //array
	} //end getReturnedInvoiceItems();

	public function getReturnedInvoiceItemsByReturnId($return_id) {
		$this->db->from('return_items');
		$this->db->where('return_id', $return_id);
		$data = array();
		$results = $this->db->get();
		if($results->num_rows() > 0) {
			foreach($results->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data;
	}

	/**
	 * Returns all of the special items applied
	 * to a return
	 *
	 * @param [int] $return_id = return id
	 *
	 * @return [array] = multi-dim array of  column value pairs
	 */
	public function getReturnedSpecialItems($return_id) {
		$this->db->from('return_special_items');
		$this->db->where('return_id', $return_id);
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data; //array

	} //end getReturnedSpecialItems();

	/**
	 * Inserts a return record
	 *
	 * @param [array] $fields = column value pairs
	 *
	 * @return [int] return_id
	 */
	public function insertReturn($fields) {
		$this->db->insert('returns', $fields);

		return $this->db->insert_id(); //int
	} //end insertReturn();

	/**
	 * Inserts a returned invoice item record
	 *
	 * @param [array] $fields = column value pair
	 *
	 * @return [int] invoice item id
	 */
	public function insertReturnedInvoiceItem($fields) {
		$this->db->insert('return_items', $fields);

		return $this->db->insert_id(); //int
	} //end insertReturnedInvoiceItem();

	/**
	 * Inserts a returned special item record
	 *
	 * @param [array] $fields =  column value pair
	 *
	 * @return [int] return special item id
	 */
	public function insertReturnedSpecialItems($fields) {
		$this->db->insert('return_special_items', $fields);

		return $this->db->insert_id(); //int
	} //end insertReturnedSpecialItems()


	public function removeReturnedSpecialItem($return_id, $return_special_id) {
		$this->db->where('return_id', $return_id);
		$this->db->where('return_special_id', $return_special_id);
		$this->db->limit('1');
		$this->db->delete('return_special_items');

		return null;
	}

	public function removeReturnedInvoiceItem($return_id, $item_id) {
		$this->db->where('return_id', $return_id);
		$this->db->where('item_id', $item_id);
		$this->db->limit('1');
		$this->db->delete('return_items');

		return null;
	}

	/**
	 * Updates a return by it's return id
	 * @param [int] $return_id = return id
	 * @param [array] $fields = array of fields and data
	 *
	 * @return null;
	 */
	public function updateReturn($return_id, $fields) {
		$this->db->where('return_id', $return_id);
		$this->db->limit(1); //always limit one
		$this->db->update('returns', $fields);

		return null;
	} //end updateReturn();

} //end Return_model()
?>