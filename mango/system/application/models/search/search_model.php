<?php
class Search_model extends Model {

	var $ci;

	function __construct() {
		parent::Model();
		$this->ci =& get_instance();
		$this->load->database();
	}

	function advanceSearch($fields, $per_page, $offset, $sort = FALSE, $direction = NULL) {
		$this->ci->load->model('image/image_model');
		$this->ci->load->model('inventory/inventory_model');

		$sql = $this->createAdvanceSearchSQL($fields);

		//print_r($sql);

		if($sort != false) {
			$sql .= ' ORDER BY ' . $sort . ' ' . $direction;
		}
		$this->db->start_cache();

		$count = $this->db->query($sql);


		$this->db->stop_cache(); //Stops Cache!
		$limit_sql = $sql . ' LIMIT ' . $offset . ', ' . $per_page;
		$query = $this->db->query($limit_sql);
		$this->db->flush_cache(); //flush Cache!

		$data = array();

		$data['num_rows'] = $count->num_rows();
		if($count->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				if (isset($row['item_status'])) {
					$row['icon_status'] = $this->ci->inventory_model->itemStatus($row['item_status']);
				}
				if (isset($row['web_status'])) {
					$row['icon_web_status'] = $this->ci->inventory_model->itemWebStatus($row['web_status']);
				}
				$row['image_array'] = $this->ci->image_model->getItemImages($row['item_id']);
				$data['items'][$row['item_id']] = $row;
			}
		}
		return $data;
	}

	private function createAdvanceSearchSQL($fields) {

		//print_r($fields);
		$query = 'SELECT DISTINCT '
			. ' inventory.item_id, inventory.item_number, inventory.item_name, inventory.item_description, inventory.item_status, inventory.web_status, inventory.item_quantity, inventory.item_price, '
			. ' users.first_name, users.last_name ';
			$query .= ' FROM inventory ';
			$query .= ' LEFT JOIN users ON inventory.user_id = users.user_id ';
		$query_array = array();


		if(isset($fields['vendor_id'])) {
			$query_array['vendor'] = " inventory.seller_id = " . $fields['vendor_id'] . " AND inventory.seller_type = 1 ";
		}
		/*
		 * This section filters through the gemstone stuff
		 */
		if(isset($fields['gemstone'])) {
			$gem_array = explode(',', $fields['gemstone']);
			switch($gem_array[1]) { //0=id, 1=template_type
				case 1: //gemstones
					$query .= " LEFT JOIN stone_info ON inventory.item_id = stone_info.item_id ";
					$query_array['gem_id'] = " stone_info.gem_type_id = $gem_array[0] ";
						if(isset($fields['gemstone_cut'])) {
							$cut_id = $fields['gemstone_cut'];
							$query_array['gemstone'] = " stone_info.gem_cut_id = $cut_id ";
						}
						if(isset($fields['carat_1']) && $fields['carat_1'] != '') {
							$carat_1 = addslashes($fields['carat_1']);
							if(isset($fields['carat_2']) && $fields['carat_2'] != '' ) {
								$carat_2 = addslashes($fields['carat_2']);
								$query_array['carat'] = " stone_info.gem_carat BETWEEN $carat_1 AND $carat_2 ";
							}
							else {
								$query_array['carat'] = " stone_info.gem_carat = $carat_1 ";
							}
						}
					break;
				case 2: //pearls
					$query .= " LEFT JOIN pearl_info ON inventory.item_id = pearl_info.item_id ";
					$query_array['gemstone'] = "pearl_info.p_type_id = " . $gem_array[0];
					//does not use CUT
					if(isset($fields['carat_1']) && $fields['carat_1'] != '') {
						$carat_1 = addslashes($fields['carat_1']);
						if(isset($fields['carat_2']) && $fields['carat_2'] != '') {
							$carat_2 = addslashes($fields['carat_2']);
							$query_array['carat'] = " pearl_info.p_weight BETWEEN $carat_1 AND $carat_2 ";
						}
						else {
							$query_array['carat'] = " pearl_info.j_weight = $carat_1 ";
						}
					}
					break;
				case 3: //diamonds
					$query .= " LEFT JOIN diamond_info ON inventory.item_id = diamond_info.item_id ";
					$query_array['gemstone'] = "diamond_info.d_type_id = $gem_array[0] ";

					if(isset($fields['color_1'])) {
						//need to reorder the values;
						$color_1 = $fields['color_1'];
						$query .= "LEFT JOIN item_diamond_color ON diamond_info.d_id = item_diamond_color.diamond_id ";
						if(isset($fields['color_2'])) {
							$color_2 = $fields['color_2'];
							$color_array = array($color_1, $color_2);
							sort($color_array);
							$query_array['color'] = " item_diamond_color.color_id = $color_1 AND item_diamond_color.color_id = $color_2 ";
						}
						else {
							$query_array['color'] = " item_diamond_color.color_id = $color_1 ";
						}
					}
					if(isset($fields['clarity_1'])) {
						$clarity_1 = $fields['clarity_1'];
						$query .= "LEFT JOIN item_diamond_clarity ON diamond_info.d_id = item_diamond_clarity.diamond_id ";
						if(isset($fields['clarity_2'])) {
							$clarity_2 = $fields['clarity_2'];
							$query_array['clarity'] = " item_diamond_clarity.clarity_id = $clarity_1 OR item_diamond_clarity.clarity_id  = $clarity_2 ";
						}
						else {
							$query_array['clarity'] = " item_diamond_clarity.clarity_id = $clarity_1 ";
						}
					}

					if(isset($fields['gemstone_cut'])) {
						$cut_id = $fields['gemstone_cut'];
						$query_array['cut_id'] = " diamond_info.d_cut_id = $cut_id ";
					}
					if(isset($fields['carat_1']) && $fields['carat_1'] != '') {
						$carat_1 = addslashes($fields['carat_1']);
						if(isset($fields['carat_2'])) {
							$carat_2 = addslashes($fields['carat_2']);
							$query_array['carat'] = " diamond_info.d_carats BETWEEN $carat_1 AND $carat_2 ";
						}
						else {
							$query_array['carat'] = " diamond_info.d_carats = $carat_1 ";
						}
					}
					break;
				case 4: //jadeite
					$query .= " LEFT JOIN jadeite_info ON inventory.item_id = jadeite_info.item_id ";
					$query_array['gemstone'] = "jadeite_info.j_type_id = " . $gem_array[0];
					//does not use CUT
					if(isset($fields['carat_1']) && $fields['carat_1'] != '') {
						$carat_1 = addslashes($fields['carat_1']);
						if(isset($fields['carat_2'])) {
							$carat_2 = addslashes($fields['carat_2']);
							$query_array['carat'] = " jadeite_info.j_carat BETWEEN $carat_1 AND $carat_2 ";
						}
						else {
							$query_array['carat'] = " jadeite_info.j_carat = $carat_1 ";
						}
					}
					break;
				case 5: //opals
					$query .= " LEFT JOIN opal_info ON inventory.item_id = opal_info.item_id ";
					$query_array['gemstone'] = "opal_info.o_type_id = " . $gem_array[0];
					if(isset($fields['gemstone_cut'])) {
						$cut_id = $fields['gemstone_cut'];
						$query_array['gemstone'] = " opal_info.o_cut_id = $cut_id ";
					}
					if(isset($fields['carat_1']) && $fields['carat_1'] != '') {
						$carat_1 = addslashes($fields['carat_1']);
						if(isset($fields['carat_2'])) {
							$carat_2 = addslashes($fields['carat_2']);
							$query_array['carat'] = " opal_info.o_carat BETWEEN $carat_1 AND $carat_2 ";
						}
						else {
							$query_array['carat'] = " opal_info.o_carat = $carat_1 ";
						}
					}
					break;
			}
		}


		/*
		 * Website fields
		 */
		if(isset($fields['website'])) {
			if($fields['website'] == 'online') {
				$query_array['web_status'] = " web_status = 1 ";
			}
			else if ($fields['website'] == 'offline') {
				$query_array['web_status'] = " web_status <> 1 ";
			}
		}

		/**
		 * Import Stuff
		 */
		if(isset($fields['import'])) {
			if($fields['import'] == 'imported') {
				$query_array['import_state'] = " push_state = 1 ";
			}
			else if ($fields['import'] == 'notimported') {
				$query_array['import_state'] = " push_state = 0 ";
			}
		}
		/**
		 * Major and Minor Class ID
		 */
		if(isset($fields['major_class'])) {
			$query_array['major_class'] = " mjr_class_id = " . $fields['major_class'];
		}
		if(isset($fields['minor_class'])) {
			$query_array['minor_class'] = " min_class_id = " . $fields['minor_class'];
		}

		/**
		 * Name, Description, style number
		 */
		if(isset($fields['name'])) {
			$query_array['name'] = " item_name LIKE '%" . addslashes($fields['name']) . "%'";
		}
		if(isset($fields['description'])) {
			$query_array['description'] = " item_description LIKE '%" . addslashes($fields['description']) . "%'";
		}
		if(isset($fields['style_number'])) {
			$query_array['style_number'] = " style_number LIKE '%" . addslashes($fields['style_number']) . "%'";
		}

		if(isset($fields['item_quantity'])) {
			if(is_numeric($fields['item_quantity'])) {
				$query_array['item_quantity'] = " item_quantity = " . $fields['item_quantity'];
			}
		}
		/**
		 * Purchase date
		 */
		if(isset($fields['purchase_date_from'])) {
			$purchase_date1 = addslashes(date('Y/m/d', strtotime($fields['purchase_date_from'])));

			if(isset($fields['purchase_date_to'])) {
				$purchase_date2 = addslashes(date('Y/m/d', strtotime($fields['purchase_date_to'])));
				$query_array['purchase_date_sql'] = " purchase_date BETWEEN '$purchase_date1' AND '$purchase_date2'";
			}
			else {
				$query_array['purchase_date_sql'] = " purchase_date = '$purchase_date1'";
			}
		}
		/**
		 * Entry date
		 */
		if(isset($fields['entry_date_from'])) {
			$entry_date1 = addslashes(date('Y/m/d', strtotime($fields['entry_date_from'])));

			if(isset($fields['entry_date_to'])) {
				$entry_date2 = addslashes(date('Y/m/d', strtotime($fields['entry_date_to'])));
				$query_array['entry_date_sql'] = " entry_date BETWEEN '$entry_date1' AND '$entry_date2'";
			}
			else {
				$query_array['entry_date_sql'] = " entry_date = '$entry_date1'";
			}
		}
		/*
		 * Item Statuses
		 */
		if(isset($fields['statuses'])) {
			$statuses = $fields['statuses'];
			$list_status = explode(',', $statuses);
			$s = 0;
			$last_s = sizeof($list_status) - 1;
			foreach ($list_status as $stat) {
				if($s == 0) {
					$query_array['status_sql'] = "( inventory.item_status = $stat ";
				}
				else {
					$query_array['status_sql'] .= " OR inventory.item_status = $stat ";
				}
				if($s == $last_s) {
					$query_array['status_sql'] .= ")";
				}
				$s++;
			}
		}

		/**
		* Check pending repair queue status.
		*/
		if(isset($fields['pending_queue'])) {
			$query .= " RIGHT JOIN inventory_pending_jobs ON inventory.item_id = inventory_pending_jobs.item_id ";
			$query_array['pending_jobs'] = " inventory_pending_jobs.job_status = 'open' ";
		}
		/**
		 * Purchase and Retain prices
		 * Need to test for ints here
		 * fix that
		 */
		if(isset($fields['purchase_price_start'])) {
			$purchase_price1 = addslashes($fields['purchase_price_start']);
			if(isset($fields['purchase_price_end'])) {
				$purchase_price2 = addslashes($fields['purchase_price_end']);
				$query_array['purchase_price_sql'] = " purchase_price BETWEEN '$purchase_price1' AND '$purchase_price2'";
			}
			else {
				$query_array['purchase_price_sql'] = " purchase_price = '$purchase_price1'";
			}
		}
		if(isset($fields['retail_price_start'])) {
			$retail_price1 = addslashes($fields['retail_price_start']);
			if(isset($fields['retail_price_end'])) {
				$retail_price2 = addslashes($fields['retail_price_end']);
				$query_array['retail_price_sql'] = " item_price BETWEEN '$retail_price1' AND '$retail_price2'";
			}
			else {
				$query_array['retail_price_sql'] = " item_price = '$retail_price1'";
			}
		}
		/**
		 * Materials
		 */
		if(isset($fields['material_ids'])) {
			$query .= ' LEFT JOIN item_material ON inventory.item_id = item_material.item_id ';
			$material_ids = $fields['material_ids'];
			$list_ids = explode(',', $material_ids);
			$m = 0;
			$last_m = sizeof($list_ids) - 1;
			foreach ($list_ids as $mat) {
				if($m == 0) {
					$query_array['material_sql'] = "( item_material.material_id = $mat ";
				}
				else {
					$query_array['material_sql'] .= " OR item_material.material_id = $mat ";
				}
				if($m == $last_m) {
					$query_array['material_sql'] .= ")";
				}
				$m++;
			}
		}
		/*
		 * No Web Images
		 */
		if(isset($fields['no_web_images'])) {
			if($fields['no_web_images'] == 'on') {
				$query .= 'LEFT JOIN image_base ON inventory.item_id = image_base.item_id ';
			}
		}
		/*
		 * No Scan Images
		 */
		if(isset($fields['no_scan_images'])) {
			if($fields['no_scan_images'] == 'on') {
				$query .= 'LEFT JOIN image_lang ON inventory.item_id = image_lang.item_id ';
			}
		}
		/**
		 *
		 * Modifers
		 */
		if(isset($fields['no_modifiers'])) {
			if($fields['no_modifiers'] == 'on') {
				$query .= 'LEFT JOIN item_modifier ON inventory.item_id = item_modifier.item_id ';
			}
		}
		else {
			if(isset($fields['modifier_ids'])) {
				$query .= ' LEFT JOIN item_modifier ON inventory.item_id = item_modifier.item_id ';
				$modifier_ids = $fields['modifier_ids'];
				$list_ids = explode(',', $modifier_ids);
				$m = 0;
				$last_m = sizeof($list_ids) - 1;
				foreach ($list_ids as $mod) {
					if($m == 0) {
						$query_array['modifier_sql'] = "( item_modifier.modifier_id = $mod ";
					}
					else {
						$query_array['modifier_sql'] .= " OR item_modifier.modifier_id = $mod ";
					}
					if($m == $last_m) {
						$query_array['modifier_sql'] .= ")";
					}
					$m++;
				}
			}
		}


		//Start the SQL construction
		$i = 0;
		foreach($query_array as $field) {
			if ($i == 0) {
				$query .= ' WHERE ' . $field;
			}
			else {
				$query .= ' AND ' . $field;
			}
			$i++;
		}


		$group_query = array();
		if(isset($fields['no_web_images'])) {
			if($fields['no_web_images'] == 'on') {
				$group_query['image_base'] = ' count(image_base.item_id) = 0 ';
			}
		}

		if(isset($fields['no_scan_images'])) {
			if($fields['no_scan_images'] == 'on') {
				$group_query['image_lang'] = ' count(image_lang.item_id) = 0 ';
			}
		}

		//add last part of no modifier query;
		if(isset($fields['no_modifiers'])) {
			if($fields['no_modifiers'] == 'on') {
				$group_query['item_modifier'] = ' count(item_modifier.item_id) = 0 ';
			}
		}
		$last_query = '';
		//print_r($group_query);
		if(sizeof($group_query) > 0) {
			$last_query .= ' GROUP BY inventory.item_id ';
			$last_query .= ' HAVING ';
			foreach($group_query as $hav) {
				if($hav != end($group_query)) {
					$last_query .= ' AND ' . $hav;
				}
				else {
					$last_query .=  $hav;
				}
			}
		}
		//echo $query . $last_query; //print query string to page
		return $query . $last_query;

	}

	function searchForNumber($number) {
		$this->ci->load->model('inventory/inventory_model');

		$this->db->from('inventory');
		$this->db->where('item_number', $number);
		$this->db->or_where('style_number', $number);

		$query = $this->db->get();
		$data = array();
		$data['num_rows'] = $query->num_rows();

		if($data['num_rows'] > 0) {
			foreach($query->result_array() as $row) {
				$data['items'][] = $this->ci->inventory_model->getItemData($row['item_id']);
			}
		}
		return $data;
	}

	function searchForString($string, $per_page, $offset, $sort = FALSE, $direction = NULL) {
		$this->ci->load->model('image/image_model');
		$this->ci->load->model('inventory/inventory_model');
		/**
		 * @TODO rewrite this query
		 * Need to rewrite this query!
		 */
		$string = str_replace(",", " ", $string);
		$ar = explode(" ",$string);
		$array_string = implode(",", $ar);

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
		$array_size = sizeof($ar);
		for ($i = 0; $i< count($ar); $i++) {
				if ($i == 0) {
					$sql .= "AND ((item_name REGEXP '[[:<:]]" . $ar[$i] . "[[:>:]]' ";
					$sql .= "OR item_description REGEXP '[[:<:]]" . $ar[$i] . "[[:>:]]' ";
					$sql .= "OR modifier_name = \"" . $ar[$i] . "\" ";
					$sql .= "OR stone_name = \"" . $ar[$i] . "\" ";
					$sql .= "OR material_name REGEXP '[[:<:]]" . $ar[$i] . "[[:>:]]' ";
					$sql .= "OR style_number REGEXP '[[:<:]]" . $ar[$i] . "[[:>:]]') ";
				}
				else {
					$sql .= "AND (item_name REGEXP '[[:<:]]" . $ar[$i] . "[[:>:]]' ";
					$sql .= "OR item_description REGEXP '[[:<:]]" . $ar[$i] . "[[:>:]]' ";
					$sql .= "OR modifier_name = \"" . $ar[$i] . "\" ";
					$sql .= "OR stone_name = \"" . $ar[$i] . "\" ";
					$sql .= "OR material_name REGEXP '[[:<:]]" . $ar[$i] . "[[:>:]]' ";
					$sql .= "OR style_number REGEXP '[[:<:]]" . $ar[$i] . "[[:>:]]') ";
				}
		}
		$sql .= ") GROUP BY inventory.item_id ) AS temp ";
		if($sort != false) {
			$sql .=" ORDER BY $sort $direction ";
		}
		$count = $sql;

		$sql .=" LIMIT $offset, $per_page ";
		$data = array();

		$count_query = $this->db->query($count);
		$query = $this->db->query($sql);

		$data['num_rows'] = $count_query->num_rows();
		if($count_query->num_rows() > 0) {

			foreach($query->result_array() as $row) {
				$row['image_array'] = $this->ci->image_model->getItemImages($row['item_id']);
				$row['icon_status'] = $this->ci->inventory_model->itemStatus($row['item_status']);
				$row['icon_web_status'] = $this->ci->inventory_model->itemWebStatus($row['web_status']);
				$data['items'][$row['item_id']] = $row;
			}
		}
		return $data;
	}

	function getSessionSearch($id) {
		$this->db->from('user_session');
		$this->db->where('user_id', $id);
		$this->db->where('session_name', 'search_data');
		$value = '';
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			$row = $query->row_array();
			$value = $row['session_value'];
		}
		return $value;
	}

	function setSessionSearch($id, $value) {
		$fields = array();
		$fields['session_value'] = $value;
		$this->db->where('user_id', $id);
		$this->db->where('session_name', 'search_data');
		$this->db->update('user_session', $fields);
	}
}
?>