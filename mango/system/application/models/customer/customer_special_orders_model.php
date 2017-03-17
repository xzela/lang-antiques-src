<?php
class Customer_special_orders_model extends Model {
	var $ci;
	
	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
	}
	
	public function getCustomerSpecialOrders($customer_id) {
		$this->db->from('customer_special_orders');
		$this->db->where('customer_id', $customer_id);
		
		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['order_status'] = $this->setSpecialOrderStatus($row['order_status']);
				$data[] = $row;
			}
		}
		
		return $data;
	}
	public function getCustomerSpecialOrder($customer_id, $order_id) {
		$this->db->from('customer_special_orders');
		$this->db->where('order_id', $order_id);
		$this->db->where('customer_id', $customer_id);
		
		
		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['order_status'] = $this->setSpecialOrderStatus($row['order_status']);
				$data = $row;
			}
		}
		
		return $data;
	}	
	public function addCustomerSpecialOrder($fields) {
		$this->db->insert('customer_special_orders', $fields);
		return $this->db->insert_id();
	}
	
	public function updateCustomerSpecialOrder($order_id, $fields) {
		$this->db->where('order_id', $order_id);
		$this->db->limit(1);
		$this->db->update('customer_special_orders', $fields);
		
		return null;
	}
	
	public function setSpecialOrderStatus($status_id) {
		$data = array();
			$data[0] = 'Order Complete';
			$data[1] = 'Order Inprogess';
			$data[2] = 'Order Delivered';
			$data[3] = 'Order Canceled';
			
		return $data[$status_id];
	}
	public function getSpecialOrderStatus() {
		$data = array();
			$data[0] = array('status_id'=>0, 'name' => 'Order Complete');
			$data[1] = array('status_id'=>1, 'name' => 'Order Inprogess');
			$data[2] = array('status_id'=>2, 'name' => 'Order Delivered');
			$data[3] = array('status_id'=>3, 'name' => 'Order Canceled');
			
		return $data;
	}	
	public function getNonClosedSpecialOrders() {
		$this->ci->load->model('customer/customer_model');
		$this->db->from('customer_special_orders');
		$this->db->where_in('order_status', array('1','2'));
		
		$data= array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['order_status'] = $this->setSpecialOrderStatus($row['order_status']);
				$row['customer_data'] = $this->ci->customer_model->getCustomerData($row['customer_id']);
				$data[] = $row;
			}
		}
		return $data;
	}
}
?>