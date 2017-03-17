<?php

class Content_model extends Model {
	
	var $ci;
	
	public function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
	}
	
	public function update_content($page, $content) {
		$fields = array();
			$fields['content'] = $content;
		$this->db->where('content_page', $page);
		$this->db->update('website_content', $fields);
		
		return null;
	}
	
	public function get_content($page) {
		$this->db->select('content');
		$this->db->from('website_content');
		$this->db->where('content_page', $page);
		$data = '';
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data = $row->content;
			}
		}
		
		return $data;
	}
}

?>