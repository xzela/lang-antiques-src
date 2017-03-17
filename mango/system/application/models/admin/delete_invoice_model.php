<?php
/**
 * Used to Delete invoice items from the database
 * 
 * @author user
 *
 */
class Delete_invoice_model extends Model {
	
	var $ci;
	
	function __construct() {
		parent::Model();
		
		$this->load->database();
		$this->ci =& get_instance();		
	}
	/**
	 * Returns Delete History for each item
	 * 
	 * @return array[] = database records
	 */
	public function getInvoiceDeleteHistory() {
		$this->ci->load->model('user/user_model');
		$this->ci->load->model('sales/invoice_model');
		
		$this->db->from('invoice_delete_history');
		$this->db->order_by('delete_date', 'DESC');
		
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				//gets user data
				$row['user_data'] = $this->ci->user_model->getUserData($row['user_id']);
				//gets buyer data
				$row['buyer_data'] = $this->ci->invoice_model->getBuyerData($row['buyer_id'], $row['buyer_type']);
				
				$data[] = $row;
			}
		}
		
		return $data; //array
	} //end getInvoiceDeleteHistory();
	
	/**
	 * Inserts an invoice delete histroy record
	 * used to record who has deleted things
	 * 
	 * @param [array] $fields = array of fields=>values
	 * @return [int] insert_id
	 */
	public function insertHistoryRecord($fields) {
		$this->db->insert('invoice_delete_history', $fields);
		return $this->db->insert_id();
	} //end insertHistoryRecord();
	
} //end Delete_invioce_model();
?>