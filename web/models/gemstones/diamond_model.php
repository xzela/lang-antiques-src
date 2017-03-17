<?php
/**
 * Diamond Model. 
 * Gets Diamond information
 * 
 * This Model should not Update, Delete, or Insert.
 * Save that stuff for Mango
 * 
 * @author user
 *
 */
class Diamond_model extends Model {
	
	var $ci;
	
	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
	}
	
	/**
	 * Gets any diamons which might be applied
	 * to an item
	 * 
	 * @param [int] $item_id = Item ID
	 * 
	 * @return [array] = multi-dem array of diamond data
	 */
	public function getItemDiamonds($item_id) {
		
		$data = array();
		$this->db->from('diamond_info');
		$this->db->where('item_id', $item_id);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				//set generic fields
				$row['gemstone_id'] = $row['d_id'];
				$row['gemstone_type_id'] = $row['d_type_id'];
				$data[] = $row;
			}
		}
		return $data; //array of diamond data
	} //end getItemDiamonds();
	
	/**
	 * Returns the diamond color
	 *  
	 * @param [int] $diamond_id = diamond id
	 * 
	 * @return [string] = a formatted string (ei: H-J, D)
	 */
	public function getItemDiamondColors($diamond_id) {
		$data = '';
		
		$this->db->from('item_diamond_color');
		$this->db->join('diamond_color', 'diamond_color.color_id = item_diamond_color.color_id');
		$this->db->where('diamond_id', $diamond_id);
		$this->db->order_by('diamond_color.seq','asc');

		$query = $this->db->get();

		if($query->num_rows() > 0) {
			$temp_row = array();
			foreach($query->result_array() as $row) {
				if($row['color_id'] == 24) { //hard_coded for 'other color'
					$temp_row[] = $this->getOtherDiamondColor($diamond_id);
				}
				else { //use color abbreviation
					$temp_row[] = $row['color_abrv'];
				}
			}
			//test to see if range is required
			if(sizeof($temp_row) > 1) { //format for range
				$data = $temp_row[0] . '-' . end($temp_row);  					
			}
			else {
				$data = $temp_row[0];
			}
		}
		return $data; //string
	} //end getItemDiamondColors();
	
	/**
	 * Returns a diamond clarity
	 * 
	 * @param [int] $diamond_id = diamond id
	 * 
	 * @return [string] = formatted string of
	 */
	public function getItemDiamondClarity($diamond_id) {
		$data = ''; //start of string;
		
		$this->db->from('item_diamond_clarity');
		$this->db->join('diamond_clarity', 'diamond_clarity.clarity_id = item_diamond_clarity.clarity_id');
		$this->db->where('diamond_id', $diamond_id);
		$this->db->order_by('diamond_clarity.seq', 'asc');
		
		$query = $this->db->get();

		if($query->num_rows() > 0) {
			$temp_row = array();
			foreach($query->result_array() as $row) {
				$temp_row[] = $row['clarity_abrv'];
			}
			if(sizeof($temp_row) > 1) { //test for range
				$data = $temp_row[0] . '-' . end($temp_row);  					
			}
			else {
				$data = $temp_row[0];
			}
		}
		return $data; //string
	} //end getItemDiamondClarity();
	
	/**
	 * If in the future, a new diamond type is created,
	 * we can support it (synthetic diamonds?).
	 * 
	 * This is used to get similar items based on
	 * what type of gemstone the current item contains.
	 * 
	 * @param [int] $item_id = item id
	 * 
	 * @return [array] = multi-dem array of Similar items
	 * 
	 */
	public function getItemDiamondTypes($item_id) {
		$data = array();
		$this->db->distinct();
		$this->db->select('d_type_id');
		$this->db->from('diamond_info');
		$this->db->where('item_id', $item_id);
		
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data; //array
	} //end getItemDiamondTypes();
	
	/**
	 * Returns the 'Other' color of a diamond
	 * 
	 * @param [int] $diamond_id = diamond id
	 * 
	 * @return [string] = formatted other color string;
	 */
	function getOtherDiamondColor($diamond_id) {
		$color = 'Other';
		$this->db->select('other_color');
		$this->db->from('diamond_info');
		$this->db->where('d_id', $diamond_id);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$color = $row['other_color'];
			}			
		}
		return $color; //string
	} //end getOtherDiamondColor();
	
} //end Diamond_model();

?>