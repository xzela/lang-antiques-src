<?php
class User_model extends Model {
	
	var $ci;
	
	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
	}
	
	public function addCustomerFavorite($fields) {
		$this->db->insert('customer_favorites', $fields);
		return $this->db->insert_id();
	}
	
	public function addNote($fields) {
		$this->db->insert('customer_comments', $fields); //@TODO rename table to 'customer_notes'
		return $this->db->insert_id();
	}
	
	
	public function checkEmailAddress($email) {
		$bool = false;
		$this->db->from('customer_info');
		$this->db->where('email', $email);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			$bool = true;
		}
		return $bool;
	}
	
	public function getCustomerData($customer_id) {
		$data = array();
		$this->db->from('customer_info');
		$this->db->where('customer_id', $customer_id);
		
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row;
			}
		}
		return $data;
	}
	
	public function getCustomerFavories($customer_id) {
		$this->ci->load->model('images/image_model');
		$data = array();
		$this->db->from('customer_favorites');
		$this->db->join('inventory', 'customer_favorites.item_id = inventory.item_id');
		$this->db->where('customer_id', $customer_id);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['images'] = $this->ci->image_model->getItemWebImages($row['item_id']);
				$row['notes'] = $this->getCustomerFavoriteNotes($customer_id, $row['item_id']);
				$data[] = $row;
			}
		}
		return $data;
	}
	
	public function getCustomerFavoriteNotes($customer_id, $item_id) {
		$data = array();
		$this->db->from('customer_comments');
		$this->db->where('customer_id', $customer_id);
		$this->db->where('item_id', $item_id);
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data;
	}
	
	public function getCustomerIdFromEmail($email) {
		$id = null;
		$this->db->select('customer_id');
		$this->db->from('customer_info');
		$this->db->where('email', $email);
		
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$id = $row['customer_id'];
			}
		}
		
		return $id;
	}
	
	public function insertCustomer($fields) {
		$this->db->insert('customer_info',$fields);
		return $this->db->insert_id();
	}
	
	public function removeCustomerFavorite($customer_id, $item_id) {
		$this->db->where('customer_id', $customer_id);
		$this->db->where('item_id', $item_id);
		$this->db->limit(1);
		
		$this->db->delete('customer_favorites');
	}
	
	public function removeCustomerFavoriteNote($data) {
		$this->db->where('customer_id', $data['customer_id']);
		$this->db->where('item_id', $data['item_id']);
		$this->db->where('comment_id', $data['comment_id']);
		$this->db->limit(1);
		
		$this->db->delete('customer_comments');
	}
	
	public function updateCustomerData($customer_id, $fields) {
		$this->db->where('customer_id', $customer_id);
		$this->db->update('customer_info', $fields);
	}
	
	public function testIsFavorite($customer_id, $item_id) {
		$bool = false;
		$this->db->from('customer_favorites');
		$this->db->where('customer_id', $customer_id);
		$this->db->where('item_id', $item_id);
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			$bool = true;
		}
		return $bool;
	}
	
	public function testUserCreditals($fields) {
		$data = array();
			$data['valid'] = false;
			
		$this->db->from('customer_info');
		$this->db->where('email', $fields['email']);
		$this->db->where('password', $fields['password']);
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			$data['valid'] = true;
			foreach($query->result_array() as $row) {
				$data['customer'] = $row;
			}
		}
		return $data;
	}
	

}
?>