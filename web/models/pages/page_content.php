<?php

class Page_content extends Model {
	
	var $ci;
	
	public function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance(); 
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