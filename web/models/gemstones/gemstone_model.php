<?php
/**
 * Gets gemstone and gemstone related
 * information.
 *
 * This model should not Update, Delete, or Insert
 *
 * @author user
 *
 */

class Gemstone_model extends Model {
	var $ci;

	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
	}

	/**
	 * Returns a cut/shape name based on
	 * the cut_id
	 *
	 * @param [int] $cut_id = cut id
	 *
	 * @return [string] = name of cut
	 */
	public function getGemstoneCutName($cut_id) {
		$name = ''; //start of return string
		$this->db->select('cut_name');
		$this->db->from('diamond_cut'); //@TODO rename 'diamond_cut' table
		$this->db->where('cut_id', $cut_id);

		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$name = $row['cut_name'];
			}
		}
		return $name; //string
	} //end getGemstoneCutName();

	/**
	 * This returns data of a 'gemstone type' based on string name
	 * This is used for the introduction-to-gemstones
	 * and gemstone-information pages.
	 *
	 * This information is only used on the public web site.
	 *
	 * see controller: /web/controllers/pages.php:gemstone_information
	 *
	 * @param [string] $string = name of gemstone
	 *
	 * @return [array] = an array of stone_type data;
	 */
	public function getGemstoneDataByName($string) {
		$this->db->from('stone_type');
		$this->db->where('has_info', 1); //make sure it has info (available to web)
		$this->db->where('stone_name', $string);
		$data = array();
		$query = $this->db->get();

		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row;
			}
		}
		return $data; //array
	} //end getGemstoneDataByName();

	/**
	 * Returns high level information on all stone types
	 *
	 * @return [array] = multi-dem array
	 */
	public function getGemstoneTypes() {
		$data = array();
		$this->db->select('stone_id, stone_name, plural_name, template_type');
		$this->db->from('stone_type');
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[$row['stone_id']] = $row;
			}
		}
		return $data; //array
	} //end getGemstoneTypes();

	/**
	 * Returns an array of gemstone applied to a specific item
	 *
	 * @param [int] $item_id = item id
	 *
	 * @return [array] = multi-dem array of gemstone data
	 */
	public function getItemGemstones($item_id) {
		$data = array();
		$this->db->from('stone_info');
		$this->db->where('item_id', $item_id);

		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				//sets generic keys (used in parseGemstone());
				$row['gemstone_id'] = $row['gem_id'];
				$row['gemstone_type_id'] = $row['gem_type_id'];
				$data[] = $row;
			}
		}
		return $data; //array
	} //end getItemGemstones();

	/**
	 * Returns the types of gemstone used of
	 * a specific item
	 *
	 * Used to find similar items.
	 *
	 * @param [int] $item_id = item id
	 *
	 * @return [array] = multi-dem array of similar items
	 */
	public function getItemGemstoneTypes($item_id) {
		$data = array();
		$this->db->distinct();
		$this->db->select('gem_type_id');
		$this->db->from('stone_info');
		$this->db->where('item_id', $item_id);

		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}
		return $data; //array
	} //end getItemGemstoneTypes();

	/**
	 * Returns web active gemstone types
	 *
	 * This information is only used on the public web site.
	 *
	 * see controller: /web/controllers/pages.php:gemstone_information
	 *
	 * @return [array] = multi-dem array of active gemstones
	 */
	public function getWebActiveGemstones() {
		$this->db->from('stone_type');
		$this->db->where('has_info', 1);
		$data = array();
		$query = $this->db->get();

		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['web_name'] = str_replace(" ", "-", strtolower($row['stone_name']));
				$row['image_url'] = substr($row['image_url'], 8); //@TODO fix this hack, should redo column information
				$data[] = $row;
			}
		}
		return $data; //array
	} //getWebActiveGemstones();


	/**
	 * This parses and returns an array of gemstone data,
	 * this is used to present the visitor with the correct
	 * data about the item.
	 *
	 *
	 * @param [array] $array = specific stone data
	 *
	 * @return [array] = array of correctly parsed gemstone data;
	 *
	 */
	public function parseGemstone($array) {
		$this->ci->load->model('gemstones/diamond_model');

		//create an array which holds the default structure
		$fields = array();
			$fields[1] = array();//normal gemstones
				$fields[1]['weight'] = 'gem_carat';
				$fields[1]['quantity'] = 'gem_quantity';
				$fields[1]['is_ranged'] = 'is_ranged';
				$fields[1]['x1'] = 'gem_x1';
				$fields[1]['x2'] = 'gem_x2';
				$fields[1]['x3'] = 'gem_x3';
				$fields[1]['cut_id'] = 'gem_cut_id';
				$fields[1]['cut_name'] = null;
				$fields[1]['grade_report'] = 'gem_cert_by';
				$fields[1]['color'] = null;
				$fields[1]['clarity'] = null;
			$fields[2] = array(); //pearls
				$fields[2]['weight'] = 'p_weight';
				$fields[2]['quantity'] = 'p_quantity';
				$fields[2]['is_ranged'] = 'is_ranged';
				$fields[2]['x1'] = 'p_x1';
				$fields[2]['x2'] = 'p_x2';
				$fields[2]['x3'] = null;
				$fields[2]['cut_id'] = null;
				$fields[2]['cut_name'] = 'p_shape';
				$fields[2]['grade_report'] = null;
				$fields[2]['color'] = null;
				$fields[2]['clarity'] = null;
			$fields[3] = array(); //diamonds
				$fields[3]['weight'] = 'd_carats';
				$fields[3]['quantity'] = 'd_quantity';
				$fields[3]['is_ranged'] = 'is_ranged';
				$fields[3]['x1'] = 'd_x1';
				$fields[3]['x2'] = 'd_x2';
				$fields[3]['x3'] = 'd_x3';
				$fields[3]['cut_id'] = 'd_cut_id';
				$fields[3]['cut_name'] = null;
				$fields[3]['grade_report'] = 'd_cert_by';
				$fields[3]['color'] = array();
				$fields[3]['clarity'] = array();
			$fields[4] = array(); //jadeite
				$fields[4]['weight'] = 'j_carat';
				$fields[4]['quantity'] = 'j_quantity';
				$fields[4]['is_ranged'] = 'is_ranged';
				$fields[4]['x1'] = 'j_x1';
				$fields[4]['x2'] = 'j_x2';
				$fields[4]['x3'] = 'j_x3';
				$fields[4]['cut_id'] = null;
				$fields[4]['cut_name'] = 'j_cut';
				$fields[4]['grade_report'] = null;
				$fields[4]['color'] = null;
				$fields[4]['clarity'] = null;
			$fields[5] = array(); //opals
				$fields[5]['weight'] = 'o_carat';
				$fields[5]['quantity'] = 'o_quantity';
				$fields[5]['is_ranged'] = 'is_ranged';
				$fields[5]['x1'] = 'o_x1';
				$fields[5]['x2'] = 'o_x2';
				$fields[5]['x3'] = 'o_x3';
				$fields[5]['cut_id'] = 'o_cut_id';
				$fields[5]['cut_name'] = null;
				$fields[5]['grade_report'] = null;
				$fields[5]['color'] = null;
				$fields[5]['clarity'] = null;

		$data = array(); //create the return array
			//print_r($fields);
			//pull out the stone name,
			$data['stone_name'] = $array['stone_data']['stone_name'];
			//pull out the plural name,
			$data['plural_name'] = $array['stone_data']['plural_name'];
			//pull out the carats, if available
			$data['carats'] = $array[$fields[$array['stone_data']['template_type']]['weight']];
			//pull out the quantity,
			$data['quantity'] = $array[$fields[$array['stone_data']['template_type']]['quantity']];
			//pull out the ranged value,
			$data['is_ranged'] = $array[$fields[$array['stone_data']['template_type']]['is_ranged']];
				$data['x1'] = $array[$fields[$array['stone_data']['template_type']]['x1']];
				$data['x2'] = $array[$fields[$array['stone_data']['template_type']]['x2']];
			//if the x3 range is not null,
			if($fields[$array['stone_data']['template_type']]['x3'] != null) {
				//set the range
				$data['x3'] = $array[$fields[$array['stone_data']['template_type']]['x3']];
			}
			//get the cut name,
			if($fields[$array['stone_data']['template_type']]['cut_id'] == null) {
				//if the cut_id is null, it's  pearl or jade or something.
				//there is a field with that name already set.
				$data['cut_name'] = $array[$fields[$array['stone_data']['template_type']]['cut_name']];
			}
			else {
				//else, find it via a lookup
				$data['cut_name'] = $this->getGemstoneCutName($array[$fields[$array['stone_data']['template_type']]['cut_id']]) . ' Cut';
			}
			//set the grade_report
			if($fields[$array['stone_data']['template_type']]['grade_report'] != null) {
				//if the grade_report is NOT null, it's a diamond and should be set.
				$data['grade_report'] = $array[$fields[$array['stone_data']['template_type']]['grade_report']];
			}
			else {
				$data['grade_report'] = '';
			}

			//if the template_type is 3 (diamond)
			if($array['stone_data']['template_type'] == 3) {
				//set the color
				$data['color'] = $this->ci->diamond_model->getItemDiamondColors($array['gemstone_id']);
				//set the clarity
				$data['clarity'] = $this->ci->diamond_model->getItemDiamondClarity($array['gemstone_id']);
			}

		return $data; //array
	} //end parseGemstone();

} //end Gemstone_model();
?>