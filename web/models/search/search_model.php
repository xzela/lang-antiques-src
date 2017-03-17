<?php
/**
 * Search Model
 *
 * Used to search the database,
 *
 *
 * @author user
 *
 */
class Search_model extends Model {

	var $ci;
	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
	}

	/**
	 * Attempts to find a specific item via
	 * its item number
	 *
	 *
	 * @param [string] $string = search string,
	 * @param [int] $limit = limit of query
	 * @param [int] $offset = offset of query
	 * @param [int] $direction = direction of query
	 *
	 * @return [array] = multi-dem array of inventory data
	 */
	public function getItemByNumber($string, $limit = 12, $offset, $direction) {
		$this->ci->load->model('images/image_model');
		$data = array();

		$this->db->start_cache();
		$this->db->from('inventory');
		$this->db->join('image_base', 'inventory.item_id = image_base.item_id');
		$this->db->where('item_number', $string);
		$this->db->group_by('image_base.item_id');
		$this->db->having('COUNT(image_base.item_id) > 0');
		$this->db->order_by('item_price', $direction);

		$this->db->stop_cache();

		$temp = $this->db->get();
		$data['pagination']['total_rows'] = $temp->num_rows();
		if($offset == 'all') {
			//no limit
		}
		else {
			if($offset != null) {
				$this->db->limit($limit, $offset);
			}
			else {
				$this->db->limit($limit);
			}
		}

		$query = $this->db->get();

		$this->db->flush_cache();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['images'] = $this->ci->image_model->getItemWebImages($row['item_id']);
				$data['inventory'][] = $row;
			}
		}

		return $data; //array
	} //end getItemByNumber();

	/**
	 * Attempts to find items via a string search
	 *
	 * @param [string] $string = search string
	 * @param [int] $limit = limit of query
	 * @param [int] $offset = offset of query
	 * @param [string] $direction = direction of sort
	 *
	 * @return [array] = multi-dem array of inventory data;
	 *
	 */
	public function getQuickSearchResults($data, $limit = 12, $offset = null, $direction = 'DESC') {
		$this->load->helper('inflector');
		$this->ci->load->model('images/image_model');

		$string = str_replace(",", " ", $data['string']);
		$temp = explode(" ",$string);
		$words = array(singular($string), plural($string));
		//var_dump($words);

		$sql = "SELECT * FROM ("
			. "SELECT DISTINCT inventory.item_id, inventory.item_status, inventory.item_number, inventory.mjr_class_id, inventory.web_status, inventory.item_name, inventory.item_description, inventory.item_price, inventory.item_quantity, modifiers.modifier_name, stone_type.stone_name, materials.material_name "
			. "FROM inventory "
			. "LEFT JOIN item_modifier "
				. "ON item_modifier.item_id = inventory.item_id "
			. "LEFT OUTER JOIN modifiers "
				. "ON item_modifier.modifier_id = modifiers.modifier_id "
			. "LEFT OUTER JOIN stone_info "
				. "ON stone_info.item_id = inventory.item_id "
			. "LEFT OUTER JOIN stone_type "
				. "ON stone_type.stone_id = stone_info.gem_type_id "
			. "LEFT OUTER JOIN item_material "
				. "ON item_material.item_id = inventory.item_id "
			. "LEFT OUTER JOIN materials "
				. "ON materials.material_id = item_material.material_id "
			. "WHERE inventory.item_id IS NOT NULL ";
			$active_sql =  " AND inventory.web_status = 1 AND inventory.item_status <> 0 AND inventory.item_quantity <> 0 ";
		if (isset($data['sub']) && isset($data['category_id']) && $data['sub'] == 1) { //normal major class
			$sql .=  " AND inventory.mjr_class_id = " . $data['category_id'] . ' ';
		}
		else if (isset($data['sub']) && isset($data['category_id']) && $data['sub'] == 2) { //modifier id
			$sql .=  " AND modifiers.modifier_id = " . $data['category_id'] . ' ';
		}
		else if (isset($data['sub']) && isset($data['special_type']) && $data['sub'] == 3) { //special case
			// archive, staff picks, etc...
			//var_dump($data['special_type']);
			if($data['special_type'] == 'the archive') {
				$active_sql = " AND inventory.web_status = 0 AND inventory.item_status = 0 "; //sold
			}
			if($data['special_type'] == " all rings") { //that space in the start of this string is important
				$active_sql .= " AND inventory.mjr_class_id IN (10, 30, 110) "; //just rings
			}
			if($data['special_type'] == " whats new") {
				$lastmonth = date("Y-m-d", mktime(0, 0, 0, date("m")-1, date("d"),   date("Y")));
				$active_sql .= " AND inventory.publish_date > '" . $lastmonth . "'"; //$this->db->where('publish_date >', $lastmonth);
			}
			if($data['special_type'] == " staff picks") {
				$active_sql .= " AND modifiers.staff = 1 "; //is a staff pick
			}
		}
		//var_dump($offset);
		$sql .= $active_sql;

		for ($i = 0; $i < count($words); $i++) {
			if ($i == 0) {
				$sql .= "AND ((item_name REGEXP '[[:<:]]" . $words[$i] . "[[:>:]]' ";
				$sql .= "OR item_description REGEXP '[[:<:]]" . $words[$i] . "[[:>:]]' ";
				$sql .= "OR modifier_name = \"" . $words[$i] . "\" ";
				$sql .= "OR stone_name = \"" . $words[$i] . "\" ";
				$sql .= "OR material_name REGEXP '[[:<:]]" . $words[$i] . "[[:>:]]' ";
				$sql .= "OR style_number REGEXP '[[:<:]]" . $words[$i] . "[[:>:]]') ";
			}
			else {
				$sql .= "OR (item_name REGEXP '[[:<:]]" . $words[$i] . "[[:>:]]' ";
				$sql .= "OR item_description REGEXP '[[:<:]]" . $words[$i] . "[[:>:]]' ";
				$sql .= "OR modifier_name = \"" . $words[$i] . "\" ";
				$sql .= "OR stone_name = \"" . $words[$i] . "\" ";
				$sql .= "OR material_name REGEXP '[[:<:]]" . $words[$i] . "[[:>:]]' ";
				$sql .= "OR style_number REGEXP '[[:<:]]" . $words[$i] . "[[:>:]]') ";
			}
		}
		$sql .= ") GROUP BY inventory.item_id ) AS temp ";
		$sql .= " ORDER BY item_price $direction ";
		//var_dump($sql);
		$count = $sql;
		if($offset == 'all') {
			//no limit
		}
		else {
			if($offset == 'all') {
				//no limit
			}
			else {
				if($offset != null) {
					$sql .=" LIMIT $offset, $limit ";
				}
				else {
					$sql .=" LIMIT $limit ";
				}
			}
		}

		$data = array();

		$count_s = $this->db->query($count);
		// get count query
		$data['inventory'] = array();
		if($count_s->num_rows() > 0) {
			foreach($count_s->result_array() as $row) {
				$row['images'] = $row['images'] = $this->ci->image_model->getItemWebImages($row['item_id']);
				if(!empty($row['images'])) {
					$data['inventory'][] = $row;
				}
			}
		}
		$data['pagination']['total_rows'] = sizeof($data['inventory']);
		//$data['inventory']
		//$data['inventory'] = array_splice($data['inventory'], $offset);
		// 12 % 64 = 4
		//var_dump($offset);
		if($offset == null) {
			// because you can't subtract a null...
			$offset = $limit;
		}
		if($offset == 'all') {
			//$data['inventory'] = array_slice($data['inventory'], $offset - $limit, $offset);
		}
		else {
			$data['inventory'] = array_slice($data['inventory'], $offset - $limit, $offset);
		}

		//var_dump($data['inventory']);
		//die();
		//var_dump($sql);
		// $query = $this->db->query($sql);
		// if($query->num_rows() > 0) {
		// 	foreach($query->result_array() as $row) {
		// 		$row['images'] = $this->ci->image_model->getItemWebImages($row['item_id']);
		// 		if(!empty($row['images'])) {
		// 			$data['inventory'][] = $row;
		// 		}
		// 	}
		// }
		// $data['pagination']['total_rows'] = $count_s->num_rows();
		return $data; //array
	} //end getQuickSearchResults();

} //end Search_model();
?>