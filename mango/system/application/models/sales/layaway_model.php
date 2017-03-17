<?php
class Layaway_model extends Model {
	
	var $ci;
	
	function __construct() {
		parent::Model();
		$this->load->database(); 
		$this->ci =& get_instance();
		
	}
	
	/**
	 * Converts all layaway payments into actual payments
	 * Does not remove any payments
	 * 
	 * @param [int] $invoice_id = invoice id
	 * 
	 * @return null
	 */
	public function convertLayawayPayments($invoice_id) {
		$this->ci->load->model('sales/invoice_model');
		$payments = $this->getLayawayPayments($invoice_id);
		foreach($payments as $payment) {
			$fields = array();
				$fields['invoice_id'] = $invoice_id;
				$fields['buyer_type'] = 1; //@TODO change layaway payments to accept vendors and stuff  
				$fields['buyer_id'] = $payment['customer_id'];
				$fields['method'] = $payment['method'];
				$fields['amount'] = $payment['amount'];
				$fields['date'] = $payment['payment_date'];
			$this->ci->invoice_model->insertInvoicePayment($fields);
		}
		
		return null;
	} //end convertLayawayPayments()
	
	/**
	 * Returns all the known layaways for 
	 * specific layaway (invoice)
	 * 
	 * @param [int] $invoice_id = invoice id
	 * 
	 * @return [array] = multi-dim array of layaway payments
	 */
	public function getLayawayPayments($invoice_id) {
		$this->db->from('invoice_layaway'); //@TODO renamed table to invoice_layaway_payments;
		$this->db->where('invoice_id', $invoice_id);
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data; //array
	} //end getLayawayPayments()
	
	/**
	 * Returns the sum total of layaway payements for a specific
	 * invoice and a date range. 
	 * 
	 * @param [int] $invoice_id = invoice id
	 * @param [date] $start = start date [YYYY/MM/DD]
	 * @param [date] $end = end date [YYYY/MM/DD]
	 * 
	 * @return [float] = sum of payments
	 */
	public function getSumTotalLayawayPaymentsWithinRange($invoice_id, $start, $end) {
		$value = 0;
		$this->db->select_sum('amount');
		$this->db->from('invoice_layaway');
		$this->db->where('invoice_id', $invoice_id);
		$this->db->where('payment_date BETWEEN "' . $start . '" AND "' . $end . '"', null, false);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$value = $row['amount'];
			}
		}
		return $value; //float
	} //end getSumTotalLayawayPaymentsWithinRange();
	
	/**
	 * Inserts a new layaway payment
	 * 
	 * @param $fields
	 * 
	 * @return [int] invoice_layaway_payment_id 
	 */
	public function insertLayawayPayment($fields) {
		$this->db->insert('invoice_layaway', $fields); //@TODO renamed table to invoice_layaway_payments;
		
		return $this->db->insert_id(); //layaway payment id
	} //end insertLayawayPayment();
	
	/**
	 * Removes all layaway payments from the database
	 * 
	 * @param [int] $invoice_id = invoice id
	 * 
	 * @return null
	 */
	public function removeLayawayPayments($invoice_id) {
		$this->db->from('invoice_layaway'); //@TODO renamed table to invoice_layaway_payments;
		$this->db->where('invoice_id', $invoice_id);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$this->removeLayawayPayment($invoice_id, $row['layaway_id']);
			}
		}
		return null;
	} //end removeLayawayPayments();
	
	/**
	 * Removes a specific layaway payment from the database
	 * 
	 * @param [int] $invoice_id = invoice id
	 * @param [int] $payment_id = payment id
	 * 
	 * @return null
	 */
	public function removeLayawayPayment($invoice_id, $payment_id) {
		$this->db->where('invoice_id', $invoice_id);
		$this->db->where('layaway_id', $payment_id);
		$this->db->limit(1); //always limit one!
		$this->db->delete('invoice_layaway'); //@TODO renamed table to invoice_layaway_payments;
		
		return null; //null
	} //end removeLayawayPayment();
	
	/**
	 * This test to see if the payment should be marked as
	 * a down payment or not. If it's the first payment,
	 * will return 1:down_payment flag, else, 
	 * returns 2:additional_payment
	 * 
	 * @param [int] $invoice_id = invoice id
	 * 
	 * @return [int]
	 */
	public function testForDownPayment($invoice_id) {
		$i = 1; //down payment flag
		$this->db->from('invoice_layaway'); //@TODO renamed table to invoice_layaway_payments;
		$this->db->where('invoice_id', $invoice_id);
		$query = $this->db->get();
		if($query->num_rows > 0) {
			$i = 2; //additional payment
		}
		return $i; //int
	} //end testForDownPayment()
	
	/**
	 * Tests to see if the layaway payment was
	 * a store credit.
	 * If not a store credit payment, return:false
	 * Else it will return an array of column value par
	 * 
	 * @param [int] $invoice_id = invoice id
	 * 
	 * @return [bool|array] 
	 */
	public function testForStoreCredit($invoice_id) {
		$b = false;
		$this->db->from('invoice_layaway'); //@TODO renamed table to invoice_layaway_payments;
		$this->db->where('layaway_id', $invoice_id);
		$this->db->where('method', 4);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			$b = array();
			foreach($query->result_array() as $row) {
				$b = $row;
			}  
		}
		return $b; //bool|array
	} //end testForStoreCredit()

} //end Layaway_model()
?>