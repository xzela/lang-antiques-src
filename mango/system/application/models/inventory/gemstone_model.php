<?php
class Gemstone_Model extends Model {
	
	var $ci;
	
	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance(); //used to call other models		
		
	}
	
	/**
	 * This applies the gemstone to the item. 
	 * It does not instert a new Gemstone Type
	 * 
	 * @param [array] $fields = array of fields to by inserted
	 * 
	 * @return [int] stone_info insert id  
	 */
	public function applyItemGemstone($fields) {
		$this->db->insert('stone_info', $fields);
		
		return $this->db->insert_id(); //int
	} //end applyItemGemstone();
	
	/**
	 * Checks a Cut name,
	 * 
	 * @param [string] $string = string to check
	 * 
	 * @return [boolean] = 0=nothing found, 1=string already exists
	 */
	public function checkCutName($string) {
		$this->db->from('diamond_cut');
		$this->db->where('cut_name', $string);
		$b = false;
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			$b = true;
		}
		return $b; //bool
	} //end checkCutName();
	
	/**
	 * Verifies that a particular stone id exists
	 *  
	 * @param [string] $string = string to check (hopefully an int)
	 * 
	 * @return [boolean] = 0=nothing found, 1=string already exists
	 */
	public function checkStoneId($string) {
		$b = false;
		$this->db->from('stone_type');
		$this->db->where('stone_id', $string);
		$query = $this->db->get();

		if($query->num_rows() == 1) { //only one? not sure why...
			$b = true;
		}
		return $b; //boolean
	} //end checkStoneId();
	
	/**
	 * Chceks the name of a Stone to prevent dups
	 * 
	 * @param [string] $string = string to check
	 * 
	 * @return [boolean] = 0=nothing found, 1=string exists;
	 */
	public function checkStoneName($string) {
		$this->db->from('stone_type');
		$this->db->where('stone_name', $string);
		$b = false;
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			$b = true;
		}
		
		return $b; //bool
	} //end checkStoneName();
	
	/**
	 * Deletes a Stone Type cut
	 * 
	 * @param [int] $id = cut id
	 * 
	 * @return null;
	 */
	public function deleteCut($cut_id) {
		$this->db->where('cut_id', $cut_id);
		$this->db->limit(1); //always limit one
		$this->db->delete('diamond_cut'); //@TODO rename 'diamond_cut' table
		
		return null; //null
	} //end deleteCut()
	
	/**
	 * Deletes a stone type from the database
	 * 
	 * @param [int] $stone_id = stone id
	 * 
	 * @return null;
	 */
	public function deleteStone($stone_id) {
		$this->db->where('stone_id', $stone_id);
		$this->db->limit(1); //always limit one
		$this->db->delete('stone_type');
		
		return null; //null
	} //end deleteStone();
	
	/**
	 * Returns all of the Stone Cut Types Data
	 * 
	 * @return [array] = multi-dim array of cut type data
	 */
	public function getAllStoneCutsData() {
		$this->db->from('diamond_cut'); //@TODO rename 'diamond_cut' table 
		$this->db->order_by('cut_name');
		$query = $this->db->get();
		
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['cut_count'] = $this->getCutCount($row['cut_id']);
				$data[$row['cut_id']] = $row;
			}
		}
		
		return $data; //array
	} //end getAllStoneCustData();
	
	/**
	 * Returns all of the Stone Type Data
	 * 
	 * @return [array] = multi-dim array of stone type data;
	 */
	public function getAllStonesData() {
		$this->ci->load->model('utils/lookup_list_model');
		$this->db->from('stone_type');
		$this->db->order_by('stone_name');
		$query = $this->db->get();
		$data = array();
		$template = $this->ci->lookup_list_model->getStoneTemplateTypes();
		
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['stone_count'] = $this->getStoneCount($row['stone_id'], $row['template_type']);
				$row['type'] = $template[$row['template_type']]['name'];
				$data[] = $row;
			}
		}
		
		return $data; //array
	} //end getAllStonesData();
	
	public function getCutCount($cut_id) {
		$value = 0;
		/*
		 * ISSUE: Count from multiple tables (diamonds, opals, gemstone)
		 * 
		 * I'm sure there is a better way, 
		 * but i'm going to use a union to solve this issue. Unions
		 * are ok, but can cause problems with misnumbered column counts
		 * 
		 * 
		 */
		
		$query = $this->db->query('(SELECT COUNT(d_id) AS nums FROM diamond_info WHERE d_cut_id = ' . $cut_id . ') '
		. ' UNION (SELECT COUNT(o_id) AS nums FROM opal_info WHERE o_cut_id = ' . $cut_id . ') '
		. ' UNION (SELECT COUNT(gem_id) AS nums FROM stone_info WHERE gem_cut_id = ' . $cut_id . ')');
		
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$value += $row['nums'];
			}
		}
		return $value;
	}
	
	public function getCutData($cut_id) {
		$this->db->from('diamond_cut');
		$this->db->where('cut_id', $cut_id);
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['cut_count'] = $this->getCutCount($row['cut_id']);
				$data = $row;
			}
		}
		return $data;
	}
	
	public function getGemstoneCut($cut_id) {
		$this->db->select('cut_name');
		$this->db->from('diamond_cut');
		$this->db->where('cut_id', $cut_id);
		
		$query = $this->db->get();
		$data = '';
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row['cut_name'];
			}
		}
		return $data;
	}
	
	public function getGemstoneCuts($order = false) {
		$this->db->from('diamond_cut');
		if($order) {
			$this->db->order_by('seq, cut_name');	
		}
		else {
			$this->db->order_by('cut_name');
		}
		$query = $this->db->get();
		
		$data = array();
		$data[0] = array('cut_name' => ''); //for the erros
		$data[''] = array('cut_name' => ''); //for the errors
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[$row['cut_id']] = $row;
			}
		}
		return $data; //array 
	} //end getGemstoneCuts();
	
	/**
	 * 
	 * @param unknown_type $id
	 */
	public function getGemstoneData($id) {
		$this->db->where('gem_id', $id);
		$this->db->from('stone_info');
		
		$query = $this->db->get(); 
		$data = array();
		if($query->num_rows() > 0) {
			$data = $query->row_array();
		}
		return $data; //array
	} //end getGemstoneData();
	
	/**
	 * 
	 * @param [int] $type_id = type id
	 * 
	 * @return [array] = array of stone type
	 */
	public function getGemstoneName($type_id) {
		$this->db->from('stone_type');
		$this->db->where('stone_id', $type_id);
		$this->db->limit(1);
		
		$query = $this->db->get(); 
		$data = 'Unknwon';
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row['stone_name'];
			}
		}
		return $data; //array
	} //end getGemstoneName();
	
	public function getGemstoneNames($type = 'all') {
		//@TODO Need to change all methods referring to this to use getAllStones;
		$this->db->from('stone_type');
		$this->db->order_by('stone_seq, stone_name');
		
		switch($type) {
			case 'gemstone':
				$this->db->where('template_type',1); 
				break;
			case 'pearl':
				$this->db->where('template_type',2); 
				break;
			case 'diamond':
				$this->db->where('template_type',3); 
				break;
			case 'jadeite':
				$this->db->where('template_type',4); 
				break;
			case 'opal':
				$this->db->where('template_type',5); 
				break;
			case 'all':
				break;
		}
		
		$query = $this->db->get();
		
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[$row['stone_id']] = $row;
			}
		}
		return $data;	
	}
	
	/**
	 * Returns all gemstone items by specific item id
	 * if you do not want the stone index assigned to the
	 * gemstone_id, send a 'false' 
	 * 
	 * @param [int] $item_id = item_id
	 * @param [bool] $use_id = use gemstone_id as index?
	 * 
	 * @return [array] = multi-dim array if gemstone data for a spcific item.
	 */
	public function getItemGemstones($item_id, $use_id = true) {
		$this->db->from('stone_info');
		$this->db->where('item_id', $item_id);
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['gemstone_name'] = $this->getGemstoneName($row['gem_type_id']);
				$row['gemstone_shape'] = $this->getGemstoneCut($row['gem_cut_id']);
				if($use_id) {
					$data[$row['gem_id']] = $row;	
				}
				else {
					$data[] = $row;
				}
				
			}
		}
		
		return $data;
	} //end getItemGemstones();
	

	/**
	 * Returns a 
	 * 
	 * @param [int] $stone_id = stone id
	 * 
	 * @return [array] = array of data
	 */
	public function getStoneData($stone_id) {
		$this->db->from('stone_type');
		$this->db->where('stone_id', $stone_id);
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['stone_count'] = $this->getStoneCount($row['stone_id'], $row['template_type']);
				$data = $row;
			}
		}
		
		return $data; //array
	} //end getStoneData();
	
	/**
	 * Returns the number of items that use a specific
	 * gemstone type
	 *
	 * This helps determine whether a gemstone could be deleted or not
	 * no null pointer expections here... (haha)
	 * 
	 * @param [int] $stone_id = id of gem type
	 * @param [int] $type = type of gemstone
	 * 
	 * @return [int] - i think...
	 */
	public function getStoneCount($stone_id, $type) {
		switch($type) {
			case 1: //gemstone
				$this->db->from('stone_info');
				$this->db->where('gem_type_id', $stone_id);
				break;
			case 2: //pearl
				$this->db->from('pearl_info');
				$this->db->where('p_type_id', $stone_id);
				break;
			case 3: //diamond
				$this->db->from('diamond_info');
				$this->db->where('d_type_id', $stone_id);
				break;
			case 4: //jadeite
				$this->db->from('jadeite_info');
				$this->db->where('j_type_id', $stone_id);
				break;
			case 5: //opal
				$this->db->from('opal_info');
				$this->db->where('o_type_id', $stone_id);
				break;
		}
		$data = 0;
		$data = $this->db->count_all_results(); //wtf?
		
		return $data; //int?
	} //end getStoneCount();

	/**
	 * Inserts a new Cut into the database
	 * 
	 * @param [array] $fields = array
	 * 
	 * @return [int];
	 */
	public function insertCut($fields) {
		$this->db->insert('diamond_cut', $fields); //@TODO rename 'diamond_cut' database table
		
		return $this->db->insert_id(); //int
	} //end insertCut();
	
	/**
	 * Inserts a new Stone Type into the database
	 * 
	 * @param [array] $fields = array of stone data
	 * 
	 * @return [int];
	 */
	public function insertStone($fields) {
		$this->db->insert('stone_type', $fields);
		
		return $this->db->insert_id(); //int
	} //end insertStone();
	
	/**
	 * Updates a Cut Record with new information
	 *  
	 * @param [int] $cut_id = cut id
	 * @param [array] $fields = array of fields
	 * 
	 * @return [null]
	 */
	public function updateCutRecord($cut_id, $fields) {
		$this->db->where('cut_id', $cut_id);
		$this->db->update('diamond_cut', $fields);
		
		return null; //null
	} //end updateCutRecord();
	
	/**
	 * Updates an Item gemstone with new information
	 * 
	 * @param [int] $item_id = item id
	 * @param [int] $gemstone_id = gemstone id
	 * @param [array] $fields = array of fields
	 * 
	 * @return [null]
	 */
	public function updateItemGemstone($item_id, $gemstone_id, $fields) {
		$this->db->where('item_id', $item_id);
		$this->db->where('gem_id', $gemstone_id);
		$this->db->limit(1); //always limit one
		$this->db->update('stone_info', $fields);
		
		return null;
	} //end updateItemGemstone();
	
	/**
	 * This removes the gemstone from the item.
	 * It does not delete the gemstone type
	 * 
	 * @param [int] $id = item_id
	 * @param [int] $gemstone_id = gemstone id
	 * 
	 * @return null 
	 */
	public function removeGemstone($item_id, $gemstone_id) {
		$this->db->where('item_id', $item_id);
		$this->db->where('gem_id', $gemstone_id);
		$this->db->limit(1); //always limit one
		$this->db->delete('stone_info');
		
		return null;
	} //end removeGemstone();
	
	/**
	 * Ajax call which updates a specific cut
	 * 
	 * @param [int] $cut_id = item_id
	 * @param [string] $column = column to update
	 * @param [string] $value = value to update
	 * 
	 * @return [null]
	 */
	public function AJAX_updateCutField($cut_id, $column, $value) {
		$fields = array();
			$fields[$column] = $value;
		$this->db->where('cut_id', $cut_id);
		$this->db->update('diamond_cut', $fields); //@TODO rename 'diamond_cut' database table;	
		
		return null;
	} //end AJAX_updateCutField();
	
	/**
	 * Ajax call which Updates a Gemstone record
	 * 
	 * @param [int] $gem_id = gemstone id
	 * @param [string] $field = ccolumn to update
	 * @param [string] $value = value to update 
	 * 
	 * @return [string]
	 */
	public function AJAX_updateGemstoneField($gem_id, $field, $value) {
		$data = array($field => $value);
		$this->db->where('gem_id', $gem_id);
		$this->db->limit(1); //always limit one
		$this->db->update('stone_info', $data);
		
		return $value; //string
	} //end AJAX_updateGemstoneField();
	
	/**
	 * AJAX call which updates a specific Stone 
	 * (diff from gemstone)
	 * 
	 * @param [int] $stone_id = stone id
	 * @param [string] $column = column to update
	 * @param [string] $value = value to update
	 * 
	 * @return null;
	 */
	public function AJAX_updateStoneField($stone_id, $column, $value) {
		$fields = array();
			$fields[$column] = $value;
		$this->db->where('stone_id', $stone_id);
		$this->db->update('stone_type', $fields);
		
		return null;
	} //end AJAX_updateStoneField();

} //end Gemstone_model();
?>