<?php
/**
 * Tag related functions. 
 * 
 * @author user
 *
 */
class Tag_model extends Model {
	
	var $ci;
	
	function __construct() {
		parent::Model();
		$this->ci =& get_instance();
		$this->load->database();
	}
	
	/**
	 * Removes a tag from the database;
	 * 
	 * @param [int] $tag_id = tag id
	 * 
	 * @return null;
	 */
	public function deleteItemTag($tag_id) {
		$this->db->where('tag_id', $tag_id);
		$this->db->limit(1); //always limit one
		$this->db->delete('inventory_tag');
		
		return null;
		
	} //end deleteItemTag();
	 
	/**
	 * Returns all of the tags applied to a specific item
	 * 
	 * @param [int] $item_id = item id
	 * 
	 * @return [array] = multi-din  array of tag data
	 */
	public function getItemTags($item_id) {
		$this->db->from('inventory_tag');
		$this->db->where('item_id', $item_id);
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[$row['tag_id']] = $row;
			}
		}
		
		return $data; //array
		
	} //end getItemTags();
	
	/**
	 * Returns tag data for a specific item
	 * 
	 * @param [int] $item_id;
	 * 
	 * @return [array] = array of tag data
	 */
	public function getTagData($item_id) {
		$this->db->from('inventory_tag');
		$this->db->where('item_id', $item_id);
		$this->db->where('active', 1);
		$query = $this->db->get();
		$data = array();
			$data['line_1'] = '';
			$data['line_2'] = '';
			$data['line_3'] = '';
			$data['line_4'] = '';
			$data['line_5'] = '';
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row;
			}
		}
		return $data; //array
		
	} //end getTagData();
	
	/**
	 * Inserts a tag into the dabase
	 * 
	 * @param [array] $fields = array of fields to be inserted
	 * 
	 * @return [int] = tag_id
	 */
	public function insertItemTag($fields) {
		$this->db->insert('inventory_tag', $fields);
		
		return $this->db->insert_id(); //int
		
	} //end insertItemTag();
	
	/**
	 * Requeues an item tag to be printed
	 * 
	 * @param [int] $tag_id = tag id
	 * 
	 * @return null;
	 */
	public function reQuqueItemTag($tag_id) {
		$fields = array();
			$fields['active'] = 1;
		$this->db->where('tag_id', $tag_id);
		$this->db->limit(1); //always limit one
		$this->db->update('inventory_tag', $fields);

		return null; //null
		
	} //end reQueuItemTag();
	
	/**
	 * Updates a specific tag with info
	 * 
	 * @param [array] $fields = array of fields
	 * @param [int] $tag_id = tag id
	 * 
	 * @return null; 
	 */
	public function updateItemTag($fields, $tag_id) {
		$this->db->where('tag_id', $tag_id);
		$this->db->limit(1); //always limit one
		$this->db->update('inventory_tag', $fields);
		
		return null; //null
		
	} //end updateItemTag();

} //end Tag_model();
?>