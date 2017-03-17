<?php
class Invoice_model extends Model {

	var $ci;

	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();

	}

	/**
	 * Creates a new Invoice
	 *
	 * @param [array] $data = array of data
	 */
	public function createInvoice($data) {
		$this->db->insert('invoice', $data);

		return $this->db->insert_id(); //int
	} //end createInvoice();

	/**
	 * Deletes an invoice from the database
	 * should not be called until the invoice has
	 * been prunned from all depenteds
	 *
	 * @param [int] $invoice_id = invoice id
	 *
	 * @return null
	 */
	public function deleteInvoice($invoice_id) {
		$this->db->where('invoice_id', $invoice_id);
		$this->db->limit(1); //always limit one
		$this->db->delete('invoice');

		return null;
	} //end deleteInvoice();

	/**
	 * Retunrs the buyers data based on ID and
	 * type
	 *
	 * @param [int] $buyer_id = buyer id
	 * @param [int] $type = type
	 *
	 * @return [array] = buyer data
	 */
	public function getBuyerData($buyer_id, $type) {
		$data = array();
		if($type == 1 || $type == 3) { //customers and internet people
			$this->ci->load->model('customer/customer_model');
			$data = $this->ci->customer_model->getCustomerData($buyer_id);
		}
		else if ($type == 2) { //vendor
			$this->ci->load->model('vendor/vendor_model');
			$data = $this->ci->vendor_model->getVendorData($buyer_id);
		}

		return $data; //array
	} //end getBuyerData();

	/**
	 * Returns the invoice id from the memo id
	 * For example, if a memo has been converted, it will
	 * return the 'new invoice id'.
	 *
	 * @param [int] $memo_id = memo id
	 *
	 * @return [int] = invoice id
	 */
	public function getConvertedInvoiceFromMemoId($memo_id) {
		$this->db->from('invoice');
		$this->db->where('memo_id', $memo_id);
		$this->db->limit(1);
		$invoice_id = null;
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			$row = $query->row_array();
			$invoice_id = $row['invoice_id'];
		}

		return $invoice_id; //int
	} //end getConvertedInvoiceFromMemoId();

	/**
	 * Attempst to find and return an item via it's
	 * invoice item id number.
	 *
	 * @param [int] $invoice_item_id = invoice item id
	 *
	 * @return [array] = array of item data
	 */
	public function getInvoiceItemDataByInvoiceItemId($invoice_item_id) {
		$this->db->from('invoice_items');
		$this->db->where('invoice_item_id', $invoice_item_id);
		$this->db->limit(1);
		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			$data = $query->row_array();
		}
		return $data; //array
	} //end getInvoiceDataByInvoiceItemId();

	/**
	 * Returns all of the data for a specific invoice
	 *
	 * @param [int] $invoice_id = invoice id
	 *
	 * @return [array] = array of invoice data
	 */
	public function getInvoiceData($invoice_id) {
		$this->db->from('invoice');
		$this->db->where('invoice_id', $invoice_id);
		$this->db->limit(1);

		$query = $this->db->get();
		$data = array();
		if($query->num_rows() == 1) {
			$data = $query->row_array();
			//get the invoice type text
			$data['invoice_type_text'] = $this->PV_getInvoiceTypeText($data['invoice_type'], $data['layaway_end_date']);
		}
		return $data; //array
	} //end getInvoiceData

	/**
	 * Returns item data for a specific invoice
	 * item combo
	 *
	 * @param [int] $invoice_id = invoice id
	 * @param [intg] $item_id = item id
	 *
	 * @return [array] = array of item data;
	 */
	public function getInvoiceItemData($invoice_id, $item_id) {
		$this->db->from('invoice_items');
		$this->db->where('invoice_id', $invoice_id);
		$this->db->where('item_id', $item_id);
		$this->db->limit(1); //limit one

		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			$data = $query->row_array();
		}

		return $data; //array
	} //end getInvoiceItemData();

	/**
	 * Attempts to get all the items that are
	 * applied to a specific invoice
	 *
	 * @param [int] $invoice_id = invoice id
	 * @param [boolean] $stone = show gemstone data if true
	 *
	 * @return [array] = multi-dim array
	 */
	public function getInvoiceItemsData($invoice_id, $stone = false) {
		$this->ci->load->model('image/image_model');
		//if stone is true, load external models
		if($stone) {
			$this->ci->load->model('inventory/gemstone_model');
			$this->ci->load->model('inventory/pearl_model');
			$this->ci->load->model('inventory/diamond_model');
			$this->ci->load->model('inventory/jadeite_model');
			$this->ci->load->model('inventory/opal_model');
		}

		$this->db->select('*, invoice_items.item_status as invoice_item_status', false);
		$this->db->from('invoice_items');
		$this->db->join('inventory', 'inventory.item_id = invoice_items.item_id');
		$this->db->where('invoice_items.invoice_id', $invoice_id);

		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				//get images
				$row['image_array'] = $this->ci->image_model->getItemImages($row['item_id']);
				//if stone is true, get gemstone information for specific item
				if($stone) {
					$row['stone']['gemstones'] = $this->ci->gemstone_model->getItemGemstones($row['item_id']);
					$row['stone']['diamonds'] = $this->ci->diamond_model->getItemDiamonds($row['item_id']);
					$row['stone']['pearls'] = $this->ci->pearl_model->getItemPearls($row['item_id']);
					$row['stone']['jadeite'] = $this->ci->jadeite_model->getItemJade($row['item_id']);
					$row['stone']['opals'] = $this->ci->opal_model->getItemOpals($row['item_id']);
				}
				$data[$row['item_id']] = $row;
			}
		}
		return $data; //array
	} //end getInvoiceItemsData();

	/**
	 * Gets all of the Invoice Payments for
	 * a specific invoice
	 *
	 * @param [int] $invoice_id = invoice id
	 *
	 * @return [array] = multi-dim array of payment information
	 */
	public function getInvoicePayments($invoice_id) {
		$this->db->from('invoice_payments');
		$this->db->where('invoice_id', $invoice_id);
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[$row['invoice_payment_id']] = $row;
			}
		}

		return $data; //array
	} //end getInvoicePayments();


	public function getActualPayoutCollected($invoice_id) {
		$this->ci->load->model('sales/layaway_model');
		$data = array();
		$data['total_collected'] = 0;
		$data['total_paid'] = 0;
		$data['total_store_credit'] = 0;
		$data['invoice_payments'] = $this->getInvoicePayments($invoice_id);
		$data['layaway_payments'] = $this->ci->layaway_model->getLayawayPayments($invoice_id);
		foreach($data['invoice_payments'] as $payment) {
			$data['total_paid'] += $payment['amount'];
			if($payment['method'] != 4) { //store credit
				$data['total_collected'] += $payment['amount'];
			}
			else {
				$data['total_store_credit'] += $payment['amount'];
			}
		}
		return $data;
	}
	/**
	 * Returns all of the Special Items applied
	 * to an invoice
	 *
	 * @param [int] $invoice_id = invoice id
	 *
	 * @return [array] = multi-dim array of special data
	 */
	public function getInvoiceSpecialItemsData($invoice_id) {
		$this->db->from('invoice_special_items');
		$this->db->where('invoice_id', $invoice_id);
		//$this->db->where('item_status', 0);

		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[$row['special_item_id']] = $row;
			}
		}

		return $data; //array
	} //end getInvoiceSpecialItemsData();

	/**
	 * Returns the data for a specific special
	 * invoice item
	 *
	 * @param [int] $special_item_id = specila item id
	 *
	 * @return [array] = array of special item data
	 */
	public function getInvoiceSpecialItemData($special_item_id) {
		$this->db->from('invoice_special_items');
		$this->db->where('special_item_id', $special_item_id);
		$this->db->limit(1); //always limit one

		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			$data = $query->row_array();
		}

		return $data; //array
	} //end getInvoiceSpecialItemData();

	/**
	 * Attempts to count the number items are
	 * applied to an invoice.
	 *
	 * @param [int] $invoice_id = invoice id
	 *
	 * @return [int] = number of items
	 */
	public function getNumberInvoiceItems($invoice_id) {
		$this->db->select('COUNT(item_id)');
		$this->db->from('invoice_items');
		$this->db->where('invoice_id', $invoice_id);
		$this->db->group_by('invoice_id');
		$data = '';
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row['COUNT(item_id)'];
			}
		}

		return $data; //int
	} //end getNumberInvoiceItems();

	/**
	 * for some retared reason I reversed
	 * the two numbers which specify whether the seller
	 * is a customer or vendor.
	 *
	 * @TODO fix reversed types
	 *
	 * @param [int] $seller_id = id of seller
	 * @param [int] $type = type: 1=vendor, 2=customer
	 *
	 * @return [array] = array of seller data
	 */
	public function getSellerData($seller_id, $type) {
		if($type == 2) { //customers and internet people
			$this->ci->load->model('customer/customer_model');
			return $this->ci->customer_model->getCustomerData($seller_id);
		}
		if ($type == 1) { //vendor
			$this->ci->load->model('vendor/vendor_model');
			return $this->ci->vendor_model->getVendorData($seller_id);
		}
	} //end getSellerData();


	/**
	 * Returns the slip id of an invoice
	 *
	 * @param unknown_type $invoice_id
	 */
	public function getSlipNumber($invoice_id) {
		$this->db->select('slip_id');
		$this->db->from('invoice');
		$this->db->where('invoice_id', $invoice_id);
		$this->db->limit(1);
		$query = $this->db->get();
		$number = null;
		if($query->num_rows() > 0) {
			$row = $query->row();
			$number = $row->slip_id;
		}

		return $number; //string
	} //end getSlipNumber()


	/**
	 * Inserts an invoice item
	 *
	 * @param [array] $fields = array of key and values
	 *
	 * @return [int] = invoice item id
	 */
	public function insertInvoiceItem($fields) {
		$this->db->insert('invoice_items', $fields);

		return $this->db->insert_id();//int
	} //end insertInvoiceItem();

	/**
	 * Inserts a special item,
	 *
	 * @param [array] $fields = array of key and values
	 *
	 * @return [int] = invoice special id
	 */
	public function insertSpecialItem($fields) {
		$this->db->insert('invoice_special_items', $fields);

		return $this->db->insert_id(); //int
	} //end insertSpecialItem();

	/**
	 * Inserts a Payment into the Invoiec Payments table
	 *
	 * @param [array] $fields = array of ekey value pairs
	 *
	 * @return [int] invoice payment id
	 */
	public function insertInvoicePayment($fields) {
		$this->db->insert('invoice_payments', $fields);

		return $this->db->insert_id(); //int
	} //end insertInvoicePayment();

	/**
	 * Updates inventory items as sold, changes
	 * their status to 0 (0=sold)
	 *
	 * @param [int] $invoice_id = invoice id
	 *
	 * @return null
	 */
	public function markInvoiceItemsSold($invoice_id) {
		$this->db->from('invoice_items');
		$this->db->where('invoice_id', $invoice_id);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$fields = array();
					$fields['item_status'] = 0;
				$this->db->where('item_id', $row['item_id']);
				$this->db->limit(1);
				$this->db->update('inventory', $fields);
			}
		}

		return null;
	} //end markInvoiceItemsSold();

	/**
	 * Dummy functyion, does nothing
	 */
	public function markSpecialItemsSold($invoice_id) {
		//do nothing.
		//I guess special items are always marked as sold

	} //end markSpecialItemsSold();

	/**
	 * Removes an invoice item from an invoice.
	 * Also, updates the item as Avaliable, but does
	 * NOT put the item back online!
	 *
	 * @param [int] $invoice_id = invoice id
	 *
	 * @return null;
	 */
	public function removeInventoryItems($invoice_id) {
		$this->ci->load->model('inventory/inventory_model');
		$this->db->from('invoice_items');
		$this->db->where('invoice_id', $invoice_id);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$this->removeItemFromInvoice($invoice_id, $row['item_id']); //remove the record
				$this->ci->inventory_model->AJAX_updateField($row['item_id'], 'item_status', 1); //update item status
			}
		}

		return null; //null
	} //end removeInventoryItems();

	/**
	 * Does a mass removal for invoice payments
	 *
	 * @param [int] $invoice_id = invoice id
	 *
	 * @return null;
	 */
	public function removeInvoicePayments($invoice_id) {
		$this->db->from('invoice_payments');
		$this->db->where('invoice_id', $invoice_id);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$this->removeInvoicePayment($invoice_id, $row['invoice_payment_id']);
			}
		}

		return null; //null
	} //end removeInvoicePayments();

	/**
	 * Removes a specific invoice payment from
	 * a specific invoice
	 *
	 * @param [int] $invoice_id = invoice id
	 * @param [int] $payment_id = payment id
	 *
	 * @return null;
	 */
	public function removeInvoicePayment($invoice_id, $payment_id) {
		$this->db->where('invoice_id', $invoice_id);
		$this->db->where('invoice_payment_id', $payment_id);
		$this->db->limit(1); //always limit one
		$this->db->delete('invoice_payments');

		return null;
	} //end removeInvoicePayment();

	/**
	 * Removes a specific Item from a specific Invoice
	 *
	 * @param [int] $invoice_id = invoice id
	 * @param [int] $item_id = item id
	 *
	 * @return null;
	 */
	public function removeItemFromInvoice($invoice_id, $item_id) {
		$this->db->where('invoice_id', $invoice_id);
		$this->db->where('item_id', $item_id);
		$this->db->limit(1); //always limit one
		$this->db->delete('invoice_items');

		return null; //null
	} //end removeItemFromInvoice();

	/**
	 * Does a mass removal of all special items
	 *
	 * @param [int] $invoice_id = invoice id
	 *
	 * @return null;
	 */
	public function removeAllSpecialItems($invoice_id) {
		$this->db->from('invoice_special_items');
		$this->db->where('invoice_id', $invoice_id);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$this->removeSpecialItemFromInvoice($invoice_id, $row['special_item_id']);
			}
		}

		return null; //null
	} //end removeAllSpecialItems();

	/**
	 * Removes a special item from an invoice
	 *
	 * @param [int] $invoice_id = invoice id
	 * @param [int] $special_item_id = special item id
	 *
	 * @return null;
	 */
	public function removeSpecialItemFromInvoice($invoice_id, $special_item_id) {
		$this->db->where('invoice_id', $invoice_id);
		$this->db->where('special_item_id', $special_item_id);
		$this->db->limit(1); //always limit one
		$this->db->delete('invoice_special_items');

		return null; //null
	} //end removeSpecialItemFromInvoice();

	/**
	 * Attempts to search for a specific string
	 *
	 * @param [string] $string = string to search by
	 *
	 * @return [array] = multi-dim array of invoice data
	 */
	public function searchInvoice($string) {
		$this->ci->load->model('customer/customer_model');
		$this->ci->load->model('vendor/vendor_model');

		$sql = 'SELECT * FROM invoice '
			. ' LEFT JOIN customer_info ON customer_info.customer_id = invoice.buyer_id'
			. ' LEFT JOIN vendor_info ON vendor_info.vendor_id = invoice.buyer_id'
			. ' WHERE invoice_id = "' . $string . '"'
			. ' OR sales_slip_number = "' . $string . '"'
			. ' OR customer_info.first_name LIKE "%' . $string . '%"'
			. ' OR customer_info.last_name LIKE "%' . $string . '%"'
			. ' OR vendor_info.name LIKE "%' . $string . '%"'
			. ' ORDER BY invoice.sale_date';

		$data = array();
		$query = $this->db->query($sql);
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				if($row['buyer_type'] == 1 || $row['buyer_type'] == 3) { //customer
					$customer_data = $this->ci->customer_model->getCustomerData($row['buyer_id']);
					if(sizeof($customer_data) > 0 ) {
						$row['buyer_name'] = $customer_data['first_name'] . ' ' . $customer_data['last_name'];
					}
					else {
						$row['buyer_name'] = 'Unknown Customer';
					}
				}
				if($row['buyer_type'] == 2) { //vendor
					$vendor_data = $this->ci->vendor_model->getVendorData($row['buyer_id']);
					if(sizeof($vendor_data) > 0) {
						$row['buyer_name'] = $vendor_data['name'];
					}
					else {
						$row['buyer_name'] = 'Unknown Vendor';
					}
				}
				$data[] = $row;
			}
		}

		return $data; //array
	} //end saerchInvoice()

	/**
	 * Attempts to figure out if all of the items are returned
	 * for a specific inovice
	 *
	 * @param [int] $invoice_id = invoice id
	 *
	 * @return [boolean]
	 */
	public function testAllItemsReturned($invoice_id) {
		$b = true;
		$this->db->from('invoice_items');
		$this->db->where('invoice_id', $invoice_id);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				if($row['item_status'] != 2) {
					$b = false;
					break;
				}
			}
		}
		$this->db->from('invoice_special_items');
		$this->db->where('invoice_id', $invoice_id);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				if($row['item_status'] != 2) {
					$b = false;
					break;
				}
			}
		}

		return $b; //bool
	} //end testAllItemsReturned()

	/**
	 * Tests for Store Credit
	 *
	 * @param [int] $invoice_payment_id = payment id
	 *
	 * @return bool;
	 */
	public function testForStoreCredit($invoice_payment_id) {
		$b = false;
		$this->db->from('invoice_payments');
		$this->db->where('invoice_payment_id', $invoice_payment_id);
		$this->db->where('method', 4); //4=storecredit
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			$b = array();
			foreach($query->result_array() as $row) {
				$b = $row;
			}
		}

		return $b; //bool|[mixed]
	} //end testForStoreCredit()

	/**
	 * Updates an invoice
	 *
	 * @param [int] $invoice_id = invoice id
	 * @param [array] $fields = array of key value paris
	 *
	 * @return null
	 */
	public function updateInvoice($invoice_id, $fields) {
		$this->db->where('invoice_id', $invoice_id);
		$this->db->limit(1);
		$this->db->update('invoice', $fields);

		return null; //null
	} //end updateInvoice();

	/**
	 * Updates an invoice item
	 *
	 * @param [int] $invoice_item_id = invoice item id
	 * @param [array] $fields = array of key value pairs
	 *
	 * @return null;
	 */
	public function updateInvoiceItem($invoice_item_id, $fields) {
		$this->db->where('invoice_item_id', $invoice_item_id);
		$this->db->limit(1);
		$this->db->update('invoice_items', $fields);

		return null; //null
	} //end updateInvoiceItem();

	/**
	 * Updates a special item with an array of value keys
	 *
	 * @param [int] $special_item_id = special item id
	 * @param [array] $fields = array of key value pairs
	 *
	 * @return null;
	 */
	public function updateSpecialItem($special_item_id, $fields) {
		$this->db->where('special_item_id', $special_item_id);
		$this->db->limit(1);
		$this->db->update('invoice_special_items', $fields);

		return null; //null
	} //end updateSpecialItem();

	/**
	 * Returns the number of invoice items which are
	 * pending a return (item_status = 2);
	 *
	 * @param [int] $invoice_id = invoice id
	 *
	 * @return [int] = count of items;
	 */
	public function verifyPendingReturnInvoiceItems($invoice_id) {
		$count = 0;
		$this->db->from('invoice_items');
		$this->db->where('invoice_id', $invoice_id);
		$this->db->where('item_status', 2); //pending return
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			$count = $query->num_rows();
		}
		return $count;
	} //end verifyPendingReturnInvoiceItems();

	/**
	 * Returns the total number of items (special and otherwise)
	 * which are pending return
	 *
	 * @param [int] $invoice_id = invoice id
	 *
	 * @return [int] = sum of items
	 */
	public function verifyPendingReturnItems($invoice_id) {
		$count = 0;
		$items = $this->verifyPendingReturnInvoiceItems($invoice_id);
		$specials = $this->verifyPendingReturnSpecialItems($invoice_id);
		$count = $items + $specials; //add up normal items and special items
		return $count; //return sum
	} //end verifyPendingReturnItems();

	/**
	 * Returns the number of special item which are pending
	 * a return
	 *
	 * @param [int] $invoice_id = invoice id
	 *
	 * @return [int] = count of pending special items
	 */
	public function verifyPendingReturnSpecialItems($invoice_id) {
		$count = 0;
		$this->db->from('invoice_special_items');
		$this->db->where('invoice_id', $invoice_id);
		$this->db->where('item_status', 2); //pending return
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			$count = $query->num_rows();
		}

		return $count; //int
	} //end verifyPendingReturnSpecialItems();

	/**
	 * @TODO move PV_getInvoiceTypeText to the lookup_list_model;
	 *
	 * @param [int] $type = invoice type
	 *
	 * @return [string] = string of text
	 */
	private function PV_getInvoiceTypeText($type, $layaway_end_date) {
		$text = null;
		switch($type) {
			case 0:
				$text = 'Invoice';
				break;
			case 1:
				$text = 'Layaway';
				if($layaway_end_date != null) {
					$text = 'Final Invoice';
				}
				break;
			case 3:
				$text = 'Memo';
				break;
		}
		return $text; //string
	} //end PV_getInvoiceTypeText();

	/**
	 * An AJAX call to to update a specific column
	 * for a specific invoice item
	 *
	 * @param [int] $invoice_item_id = invoice item id
	 * @param [string] $column = column to update
	 * @param [string] $value = new value
	 *
	 * @return [string] = new value;
	 */
	public function AJAX_updateInvoiceItemField($invoice_item_id, $column, $value) {
		$data = array($column => $value);
		$this->db->where('invoice_item_id', $invoice_item_id);
		$this->db->limit(1);
		$this->db->update('invoice_items', $data);

		return $value; //string
	} //end AJAX_updateInvoiceItemField();

	/**
	 * An AJAX call to update a specific column for a
	 * specific special item
	 *
	 * @param [int] $special_item_id = id of special item
	 * @param [string] $column = column to update
	 * @param [string] $value = new value
	 *
	 * @return [string] = return new value;
	 */
	public function AJAX_updateSpecialItemField($special_item_id, $column, $value) {
		$data = array($column => $value);
		$this->db->where('special_item_id', $special_item_id);
		$this->db->limit(1);
		$this->db->update('invoice_special_items', $data);

		return $value; //string
	} //end AJAX_updateSPecialItemField();

	/**
	 * An AJAX call to update a specific field for a
	 * specific invoice
	 *
	 * @param [int] $invoice_id = invoice id
	 * @param [string] $column = column to update
	 * @param [string] $value = new value
	 *
	 * @return [string] = returns new value
	 */
	public function AJAX_updateInvoiceField($invoice_id, $column, $value) {
		$data = array($column => $value);
		$this->db->where('invoice_id', $invoice_id);
		$this->db->limit(1); //always limit one
		$this->db->update('invoice', $data);

		return $value; //string
	} //end AJAX_updateInvoiceField();

} //end Invoice_model();
?>