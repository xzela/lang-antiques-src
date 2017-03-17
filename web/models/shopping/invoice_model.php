<?php
/**
 * Invoice model
 *
 * Used to create invoices for on-line sales
 *
 * This can create invoices, invoice_items, and payments
 *
 * @author user
 *
 */
class Invoice_model extends Model {

	var $ci;

	function __construct() {
		parent::Model();
		$this->ci =& get_instance();
		$this->load->database();
	}

	/**
	 * Returns all invoice specific data for
	 * and invoice
	 *
	 * @param [int] $invoice_id = invoice id
	 *
	 * @return [array] = array of inovice data
	 *
	 */
	public function getInvoiceData($invoice_id) {
		$data = array();
		$this->db->from('invoice');
		$this->db->where('invoice_id', $invoice_id);

		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row;
			}
		}
		return $data; //array
	} //end getInvoiceData();

	/**
	 * Returns all of the items which may be
	 * applied to a specific invoice
	 *
	 * @param [int] $invoice_id = invoice id
	 *
	 * @return [array] = multi-dem array of invoice items
	 */
	public function getInvoiceItems($invoice_id) {
		$data = array();
		$this->db->from('invoice_items');
		$this->db->where('invoice_id', $invoice_id);

		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['item_name'] = $this->ci->inventory_model->getItemName($row['item_id']);
				$data[] = $row;
			}
		}
		return $data; //array
	} //end getInvoiceItems();

	/**
	 * Inserts a Customers Credit Card information
	 *
	 * @param [array] $fields = array of fields to be insertated
	 *
	 * @return [int] = internet_customer_creditcard_id;
	 */
	public function insertCustomerCreditCard($fields) {
		$this->db->set('encrypt_card_number', "AES_ENCRYPT('{$fields['card_number']}','". AES_KEY . "')", FALSE);
		$this->db->set('encrypt_card_cvv', "AES_ENCRYPT('{$fields['card_cvv']}','". AES_KEY . "')", FALSE);

		unset($fields['card_number']);
		unset($fields['card_cvv']);

		$this->db->insert('internet_customer_creditcard', $fields);
		return $this->db->insert_id(); //int
	} //end insertCustomerCreditCard();

	/**
	 * Creates a new Invoice.
	 *
	 * @param [array] $fields = array of fields specific to invoices
	 *
	 * @return [int] = invoice id
	 */
	public function insertInvoice($fields) {
		$this->db->insert('invoice', $fields);
		return $this->db->insert_id(); //int
	} //end insertInvoice();

	/**
	 * Inserts an Invoice Item
	 *
	 * @param [array] $fields = array of fields to be inserted
	 *
	 * @return [int] = invoice item id
	 */
	public function insertInvoiceItem($fields) {
		$this->db->insert('invoice_items', $fields);
		return $this->db->insert_id(); //int
	}//end insertInvoiceItem();

	/**
	 * Inserts an Invoice Payment
	 *
	 * @param [array] $fields = array of fields to be inserted
	 *
	 * @return [int] = invoice payment id;
	 */
	public function insertInvoicePayment($fields) {
		$this->db->insert('invoice_payments', $fields);
		return $this->db->insert_id(); //int
	} //end insertInvoicePayment();

	public function getInvoicePaymentById($payment_id) {
		$this->db->from('invoice_payments');
		$this->db->where('invoice_payment_id', $payment_id);
		$this->db->limit(1);
		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row;
			}
		}
		return $data;
	}
}