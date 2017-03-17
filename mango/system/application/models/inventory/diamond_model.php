<?php
class Diamond_model extends Model {
	
	var $ci;
	
	public function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance(); //used to call other models		
	}
	
	/**
	 * Inserts a clarity for a spcific diamond
	 * 
	 * @param [array] $fields = array of fields
	 * 
	 * @return [int]
	 */
	public function appendClarity($fields) {
		$this->db->insert('item_diamond_clarity', $fields);
		
		return $this->db->insert_id(); //int
	} //end appendClarity();
	
	/**
	 * Inserts a color for a specific diamond
	 * 
	 * @param [array] $fields = array
	 * 
	 * @return [int]
	 */
	public function appendColor($fields) {			
		$this->db->insert('item_diamond_color', $fields);
		
		return $this->db->insert_id(); //int
	} //end appendColor();
	
	/**
	 * Deletes a clarity record from the database
	 * 
	 * @param [int] $clarity_id = clarity id
	 * 
	 * @return null;
	 */
	public function deleteClarity($clarity_id) {
		$this->db->where('clarity_id', $clarity_id);
		$this->db->limit(1); //always limit one
		$this->db->delete('diamond_clarity');
		
		return null;
	} //end deleteClarity();
	
	/**
	 * Deletes a color record from the database 
	 * 
	 * @param [int] $color_id = color id
	 * 
	 * @return null;
	 */
	public function deleteColor($color_id) {
		$this->db->where('color_id', $color_id);
		$this->db->limit(1); //always limit one
		$this->db->delete('diamond_color');
		
		return null;
	} //end deleteColor();
	
	/**
	 * Deletes a diamond from the database
	 * 
	 * @param [int] $item_id = item id
	 * @param [int] $diamond_id = diamond id
	 * 
	 * @return [null]
	 */
	public function deleteDiamond($item_id, $diamond_id) {
		$this->db->where('item_id', $item_id);
		$this->db->where('d_id', $diamond_id);
		$this->db->limit(1); //always limit one
		$this->db->delete('diamond_info');
		
		return null;	
	} //end deleteDiamond()
	
	/**
	 * Returns the count of items which use a 
	 * specific clarity
	 * 
	 * @param [int] $clarity_id = clarity id
	 * 
	 * @return [int]
	 */
	public function getClarityCount($clarity_id) {
		$this->db->from('item_diamond_clarity');
		$this->db->where('clarity_id', $clarity_id);
		$query = $this->db->get();
		$data = 0;
		if($query->num_rows() > 0) {
				$data = $query->num_rows();
		}
		
		return $data; //int
	} //end getClarityCount();
	
	/**
	 * Returns Clarity record for a clarity element
	 * 
	 * @param [int] $clarity_id = clarity id
	 * 
	 * @return [array] = array of clarity data
	 */
	public function getClarityData($clarity_id) {
		$this->db->from('diamond_clarity');
		$this->db->where('clarity_id', $clarity_id);
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row;
			}
		}
		return $data; //array
	} // end getClarityData(); 
	
	/**
	 * Returns the count of items used by this color 
	 * 
	 * @param [int] $color_id = oclor id
	 * 
	 * @return [int]
	 */
	public function getColorCount($color_id) {
		$this->db->from('item_diamond_color');
		$this->db->where('color_id', $color_id);
		$query = $this->db->get();
		$data = 0;
		if($query->num_rows() > 0) {
				$data = $query->num_rows();
		}
		return $data; //int
	} //end getColorCount();
	
	/**
	 * Returns a record of color data
	 * 
	 * @param [int] $color_id = color id
	 * 
	 * @return [array] = array of color data;
	 */
	public function getColorData($color_id) {
		$this->db->from('diamond_color');
		$this->db->where('color_id', $color_id);
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row;
			}
		}
		
		return $data; //array
	} //end getColorData();
	
	/**
	 * Returns the clarity data
	 * 
	 * @param [int] $diamond_id = diamond id
	 * @param [int] $item_id = item id
	 * 
	 * @return [array] = multi-dim array of diamond clarity data
	 */
	public function getDiamondClaritiesData($diamond_id, $item_id) {
		$this->db->from('item_diamond_clarity');
		$this->db->where('item_id', $item_id);
		$this->db->where('diamond_id', $diamond_id);
		$data = array();
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		
		return $data; //array
	} //end getDiamondClaritiesData();
	
	/**
	 * Return the diamond clarity
	 * string example: VS1-VS2 
	 *
	 * @param [int] $diamond_id = diamond id
	 * @param [bool] = whether to format or not
	 * 
	 * @return [string]
	 */
	public function getDiamondClarity($diamond_id, $format = false) {
		$this->load->model('utils/lookup_list_model');
		
		$this->db->select('item_diamond_clarity.clarity_id');
		$this->db->from('item_diamond_clarity');
		$this->db->join('diamond_clarity', 'diamond_clarity.clarity_id = item_diamond_clarity.clarity_id');
		$this->db->where('diamond_id', $diamond_id);
		$this->db->order_by('diamond_clarity.seq', 'asc');
		
		$query = $this->db->get();
		if($format) {
			$data = '';
			$clarity = $this->ci->lookup_list_model->getDiamondClarities();
			if($query->num_rows() > 0) {
				
				$temp_row = array();
				foreach($query->result_array() as $row) {
					$temp_row[] = $clarity[$row['clarity_id']]['clarity_abrv'];
				}
				if(sizeof($temp_row) > 1) {
					$data = $temp_row[0] . '-' . end($temp_row);  					
				}
				else {
					$data = $temp_row[0];
				}
			}			
		}
		else {
			$data = array();
			foreach($query->result_array() as $row) {
				$data[$row['clarity_id']] = $row['clarity_id'];
			}
			
		}

		return $data; //string
	} //end getDiamondClarity();
	
	/**
	 * Returns the diamond color string example: D-E
	 *
	 * @param [int] $diamond_id = diamond id
	 * @param [bool] = whether to format the end result. 
	 * 
	 * @return [string]
	 */
	public function getDiamondColor($diamond_id, $format = false) {
		$this->load->model('utils/lookup_list_model');
		
		$this->db->select('diamond_color.color_id');
		$this->db->from('item_diamond_color');
		$this->db->join('diamond_color', 'diamond_color.color_id = item_diamond_color.color_id');
		$this->db->where('diamond_id', $diamond_id);
		$this->db->order_by('diamond_color.seq','asc');
		
		$query = $this->db->get();
		if($format) {
			$data = '';
			$colors = $this->ci->lookup_list_model->getDiamondColors();
			if($query->num_rows() > 0) {
				$temp_row = array();
				foreach($query->result_array() as $row) {
					if($row['color_id'] == 24) { //hard_coded for other
						$temp_row[] = $this->getOtherDiamondColor($diamond_id);
					}
					else {
						$temp_row[] = $colors[$row['color_id']]['color_abrv'];
					}
				}
				if(sizeof($temp_row) > 1) {
					$data = $temp_row[0] . '-' . end($temp_row);  					
				}
				else {
					$data = $temp_row[0];
				}
			}	
		}
		else {
			$data = array();
			foreach($query->result_array() as $row) {
				$data[$row['color_id']] = $row['color_id'];
			}
		}
		return $data; //string;
		
	} //end getDimaondColor();
	
	/**
	 * Returns the Diamond Color Data;
	 * 
	 * @param [int] $diamond_id = diamond id
	 * @param [int] $item_id = item id
	 * 
	 * @return [array] = multi-dim array
	 */
	public function getDiamondColorsData($diamond_id, $item_id) {
		$this->db->from('item_diamond_color');
		$this->db->where('diamond_id', $diamond_id);
		$this->db->where('item_id', $item_id);
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data; //array
	} //end getDiamondColorsData();
	
	/**
	 * REturns all of the data for a specific
	 * diamond 
	 * 
	 * @param [int] $diamond_id
	 * 
	 * @return [array] = array of diamond data
	 */
	public function getDiamondData($diamond_id) {
		$this->db->where('d_id', $diamond_id);
		$this->db->from('diamond_info');
		$query = $this->db->get(); 
		$data = array();
		if($query->num_rows() > 0) {
			$data = $query->row_array();
		}
		return $data; //array
	} //end getDiamondData();
	
	/**
	 *  Returns all of the diamonds applied to a
	 *  specific item
	 *  
	 * @param [int] $item_id = item id
	 * 
	 * @return [array] = multi-dim array of diamond data
	 */
	public function getItemDiamonds($item_id) {
		$this->ci->load->model('inventory/gemstone_model');
		$this->db->from('diamond_info');
		$this->db->where('item_id', $item_id);
		
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['diamond_name'] = $this->ci->gemstone_model->getGemstoneName($row['d_type_id']);
				$row['diamond_shape'] = $this->ci->gemstone_model->getGemstoneCut($row['d_cut_id']);
				
				$row['color'] = $this->getDiamondColor($row['d_id'], true);
				$row['clarity'] = $this->getDiamondClarity($row['d_id'], true);
				$data[] = $row;
			}
		}
		
		return $data; //array
	} //end getItemDiamonds();
	
	/**
	 * Returns the 'Other' color of a diamond.
	 * This color field is unique to each diamond. 
	 * Thus, no set list is available.
	 * 
	 * @param [int] $diamond_id = diamond id
	 * 
	 * @return [string] = name of other color
	 */
	public function getOtherDiamondColor($diamond_id) {
		$color = 'Other';
		$this->db->select('other_color');
		$this->db->from('diamond_info');
		$this->db->where('d_id', $diamond_id);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			//@TODO convert this into $query->row_array();
			foreach($query->result_array() as $row) {
				$color = $row['other_color'];
			}			
		}
		return $color; //string
	} //getOtherDiamondColor();
	
	/**
	 * Insert a dimaond clarity item 
	 * 
	 * @param [array] $fields = array of fields
	 * 
	 * @return [int] = diamond clarity id
	 */
	public function insertClarityData($fields) {
		$this->db->insert('diamond_clarity', $fields);
		
		return $this->db->insert_id(); //int
	} //end insertClarityData();
	
	/**
	 * Inserts Color data into the database
	 * 
	 * @param [array] $fields = array of color data
	 * 
	 * @return [int] = diamond color id; 
	 */
	public function insertColorData($fields) {
		$this->db->insert('diamond_color', $fields);
		
		return $this->db->insert_id(); //int
	} //end insertColorData();
	
	/**
	 * Inserts a diamond into the database,
	 *
	 * @param [int] $id = item id
	 * @param [array] = array of diamond fields and values
	 * 
	 * @return [int] = diamond id 
	 */
	public function insertDiamond($fields) {
		$this->db->insert('diamond_info', $fields);
		
		return $this->db->insert_id(); //int		
	} //end insertDiamond();
	
	/**
	 * Updates a Diamond Clarity Record
	 *  
	 * @param [int] $clarity_id = clarity id
	 * @param [array] $fields = array of fields
	 * 
	 * @return [null]
	 */
	public function updateClarityRecord($clarity_id, $fields) {
		$this->db->where('clarity_id', $clarity_id);
		$this->db->limit(1); //always limit one
		$this->db->update('diamond_clarity', $fields);
		
		return null;
	} //end updateClarityRecord();
	
	/**
	 * Updates a Diamond Color Record 
	 * 
	 * @param [int] $color_id = color id
	 * @param [array] $fields = array of fields
	 */
	public function updateColorRecord($color_id, $fields) {
		$this->db->where('color_id', $color_id);
		$this->db->limit(1); //always limit one
		$this->db->update('diamond_color', $fields);
		
		return null;
	} //end updateColorRecord();
	
	/**
	 * Updates an Item Diamond record
	 * 
	 * @param [int] $diamond_id = diamond id
	 * @param [array] $fields = array of key paried vbalued
	 * 
	 * @return [int]
	 */
	public function updateItemDiamond($diamond_id, $fields) {
		$this->db->where('d_id', $diamond_id);
		$this->db->limit(1); //always limit one
		$this->db->update('diamond_info', $fields);
		
		return $diamond_id; //int
	} //end updateItemDiamond();
	
	/**
	 * Removes a Clarity record
	 * 
	 * @param [int] $item_id = item id
	 * @param [array] $fields = array of key paired values
	 * 
	 * @return null;
	 */
	public function removeClarity($item_id, $fields) {
		$this->db->where('item_id', $item_id);
		$this->db->where('diamond_id', $fields['diamond_id']);
		$this->db->where('clarity_id', $fields['clarity_id']);
		$this->db->limit(1); //always limit one
		$this->db->delete('item_diamond_clarity');
		
		return null;
	} //end removeClarity();
	
	/**
	 * Removes a specific color
	 * 
	 * @param [int] $item_id = item id
	 * @param [array] $fields = array of key values
	 * 
	 * @return null;
	 */
	public function removeColor($item_id, $fields) {
		$this->db->where('item_id', $item_id);
		$this->db->where('diamond_id', $fields['diamond_id']);
		$this->db->where('color_id', $fields['color_id']);
		$this->db->limit(1); //always limit one
		$this->db->delete('item_diamond_color');
		
		return null; //null
	} //end removeColor();
	
	/**
	 * AJAX call to update a clairty column
	 * 
	 * @param [int] $clarity_id = clarity id
	 * @param [string] $column = column to update
	 * @param [string] $value = value
	 * 
	 * @return [string]
	 */
	public function AJAX_updateClarityField($clarity_id, $column, $value) {
		$data = array($column => $value);
		$this->db->where('clarity_id', $clarity_id);
		$this->db->limit(1);
		$this->db->update('diamond_clarity', $data);
		
		return $value; //string
	} //end AJAX_updateClarityField();
	
	/**
	 * Ajax call to update a color field
	 *  
	 * @param [int] $color_id = color id
	 * @param [string] $column = column to update
	 * @param [string] $value = value
	 * 
	 * @return [string]
	 */
	public function AJAX_updateColorField($color_id, $column, $value) {
		$data = array($column => $value);
		$this->db->where('color_id', $color_id);
		$this->db->limit(1);
		$this->db->update('diamond_color', $data);

		return $value; //null
	} //end AJAX_updateColorField();
	
	/**
	 * Ajax call which updates a specific diamond
	 * 
	 * @param [int] $diamond_id = diamond id
	 * @param [string] $field = name of column to update
	 * @param [string] $value = value of column
	 * 
	 * @return [string]; 
	 */
	public function AJAX_updateDiamondField($diamond_id, $field, $value) {
		$data = array($field => $value);
		$this->db->where('d_id', $diamond_id);
		$this->db->limit(1);
		$this->db->update('diamond_info', $data);
		
		return $value; //string
	} //end AJAX_updateDiamondField();
	
	/**
	 * A Callback function which checks the
	 * abrv of some Clarity value
	 * 
	 * @param [string] $string
	 * 
	 * @return [boolean]
	 */
	public function CB_checkClarityAbrv($string) {
		$this->db->from('diamond_clarity');
		$this->db->where('clarity_abrv', $string);
		$b = false;
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			$b = true;
		}
		return $b; //bool
	} //end CB_checkClarityAbrv();
	
	/**
	 * A Callback function that tests the
	 * Name of some Clarity value
	 * 
	 * @param [string] $string = name of clarity
	 * 
	 * @return [boolean];
	 */
	public function CB_checkClarityName($string) {
		$this->db->from('diamond_clarity');
		$this->db->where('clarity_name', $string);
		$b = false;
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			$b = true;
		}
		return $b; //bool
	} //end CB_checkClarityName():
	
	/**
	 * A Callback function which validates that
	 * 
	 * @param [string] $string = abrv
	 * 
	 * @return [boolean];
	 */
	public function CB_checkColorAbrv($string) {
		$this->db->from('diamond_color');
		$this->db->where('color_abrv', $string);
		$b = false;
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			$b = true;
		}
		return $b; //bool
	} //end CB_checkColorAbrv();
	
	/**
	 * A Callback function which validates that
	 * a color name exists or not.
	 * 
	 * @param [string] $string = color name
	 * 
	 * @return [boolean] 
	 */
	public function CB_checkColorName($string) {
		$this->db->from('diamond_color');
		$this->db->where('color_name', $string);
		$b = false;
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			$b = true;
		}
		return $b; //bool
	} //end CB_checkColorName();
	
} //end Diamond_model();
?>