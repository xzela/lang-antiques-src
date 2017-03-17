<?php
/**
 * This stuff sucks.
 * The push to lang and whatnot causes serious pronblems
 * Like dup numbers between different databases.
 *
 *
 * @author user
 *
 */
class Push_model extends Model {

	public $ci;
	public $langdb;

	/**
	 * Constructorz!!!
	 *
	 */
	public function __construct() {
		parent::Model();
		//$this->lang =& $this->load->database('langdb01', true); //set langdb01 database
	}

	public function compareDataSets($fran, $lang, $ignore = array()) {
		$data = array();
		$data['out_sync'] = false; //{true|false}
		$data['diff'] = array();
		foreach($lang as $key => $field) {
			if(!in_array($key, $ignore)) { //ignore any items that are listed
				if($lang[$key] != $fran[$key]) {
					$data['out_sync'] = true;
					$data['diff'][$key] = array($fran[$key], $lang[$key]);
				}
			}
		}

		return $data;
	}

	public function compareMultipleDataSets($fran_set, $lang_set, $ignore = array()) {
		$data = array();
		$data['out_sync'] = false; //{true|flase}
		$data['diff'] = array();
		//count the size of
		for($i = 0; $i < sizeof($lang_set)-1; $i++) {
			foreach($lang_Set[$i] as $key => $field) {
				if(!in_array($key, $ignore)) {
					if($lang_set[$i][$key] != $fran_set[$i][$key]) {
						$data['out_syn'] = true;
						$data['diff'][$i][$key] = array($fran_set[$i][$key], $lang_set[$i][$key]);
					}
				}
			}
		}

		return $data;
	} //end compareMultipleDataSets();

	/**
	 * *************************************
	 * ******only used via frangdango*******
	 * *************************************
	 *
	 * Inserts the Frandango Item into Mango
	 *
	 * @param [array] $item = array of fields
	 *
	 * @return [int] insert_id
	 */
	public function copyItemToLang($item) {
		$this->langdb =& $this->load->database('langdb01', true);
		$this->langdb->insert('inventory', $item);

		return $this->langdb->insert_id(); //int
	} //end copyItemToLang()

	/**
	 *
	 *
	 * @param [int] $lang_id
	 * @param [string] $table
	 *
	 * @return [array] = multi-dim array of table data
	 */
	public function getAllLangTableData($lang_id, $table) {
		$this->langdb =& $this->load->database('langdb01', true);
		$this->langdb->from($table);
		$this->langdb->where('item_id', $lang_id);
		$query = $this->langdb->get(); //store data;
		$this->langdb->close(); //close connection

		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data[] = $row;
			}
		}

		return $data; //array
	} //end getAllLangTableData();

	/**
	 * Returns a list of columns to ignore
	 *
	 * @return [array];
	 */
	public function getIgnoredFields() {
		$list = array('item_id', 'lang_id', 'fran_id', 'item_status');
		return $list;  //array
	} //end getIgnoredFields();

	public function getLangDataFromItemNumber($item_number) {
		$this->langdb =& $this->load->database('langdb01', true);
		$this->langdb->from('inventory');
		$this->langdb->where('item_number', $item_number);
		$this->langdb->limit(1);
		$query = $this->langdb->get(); //store data;
		$this->langdb->close(); //close connection

		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row;
				$data['image_array']['external_images'] = $this->getLangExternalImages($row['item_id']);
				$data['image_array']['internal_images'] = $this->getLangInternalImages($row['item_id']);
			}
		}

		return $data;
	} //end getLangDataFromItemNumber()

	/**
	 * Gets all of the External Images applied
	 * to a specific item
	 *
	 * @param [int] $id = item_id
	 *
	 * @return array[] = image record
	 */
	function getLangExternalImages($item_id) {
		$this->langdb =& $this->load->database('langdb01', true);
		$this->langdb->where('item_id', $item_id);
		$this->langdb->from('image_base');
		$this->langdb->order_by('ISNULL(image_seq), image_seq ASC'); //fixes the NULLs first error
		$query = $this->langdb->get(); //store data;
		$this->langdb->close(); //close connection

		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['image_class'] = 1; //external
				$row['image_copy_location'] = $row['image_location'];
				$row['image_location'] = $this->config->item('image_url') . $row['image_location'];
				$data[] = $row;
			}
		}

		return $data; //array
	} //end getLangExternalImages()

	/**
	 * Gets all of the Internal Images applied
	 * to a specific item
	 *
	 * @param [int] $id = image_id
	 *
	 * @return array[] = image record
	 */
	function getLangInternalImages($item_id) {
		$this->langdb =& $this->load->database('langdb01', true);
		$this->langdb->where('item_id', $item_id);
		$this->langdb->from('image_lang');
		$query = $this->langdb->get(); //store data
		$this->langdb->close(); //close connection

		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['image_class'] = 2; //internal
				$row['image_copy_location'] = $row['image_location'];
				$row['image_location'] = $this->config->item('image_url') . $row['image_location'];
				$data[] = $row;
			}
		}

		return $data;
	} //end getLangInternalImages();

	/**
	 * Returns inventory Data from langdb01:inventory
	 * Gets specific item based on it's
	 * fran_id (the frandango item_id)
	 *
	 * @param [int] $fran_id = frandango item_id;
	 * @param [int] $lang_id = lang id
	 *
	 * if $lang_id is not null, then it will use lang_id instead
	 *
	 * @return array
	 */
	public function getLangInventoryData($fran_id, $lang_id = null) {
		$this->langdb =& $this->load->database('langdb01', true); //set database
		$this->langdb->from('inventory');
		if($lang_id != null) {
			$this->langdb->where('item_id', $lang_id);
		}
		else {
			$this->langdb->where('lang_id', $fran_id);
		}
		$this->langdb->limit(1);

		$query = $this->langdb->get(); //store data;
		$this->langdb->close(); //close connection

		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row;
			}
		}

		return $data; //array
	} //end getLangInventoryData()

	/**
	 * Returns the value of specific field
	 *
	 * @param [int] $item_id = item_id
	 * @param [string] $column = name of the coolumn
	 *
	 * @return [string] = value of the column
	 */
	function getPushedInventoryColumnData($item_id, $column) {
		$this->langdb =& $this->load->database('langdb01', true);
		$this->langdb->select($column);
		$this->langdb->from('inventory');
		$this->langdb->where('item_id', $item_id);
		$this->langdb->limit(1);
		$query = $this->langdb->get();
		$this->langdb->close(); //close connection

		$value = null;
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$value = $row[$column];
			}
		}

		return $value;
	} //end getPushedInventoryColumnData();

	/**
	 * Returns all of the data from a specific item and table
	 *
	 * @param [int] $lang_id = lang item id
	 * @param [string] $table = name of table
	 *
	 * @return [array] = inventory data
	 */
	public function getSingleLangTableDataRecord($lang_id, $table) {
		$this->langdb =& $this->load->database('langdb01', true);
		$this->langdb->from($table);
		$this->langdb->where('item_id', $lang_id);
		$query = $this->langdb->get(); //store data;
		$this->langdb->close(); //close connection

		$data = array();

		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row;
			}
		}

		return $data; //array
	} //end getSingleLangTableDataRecord();

	/**
	 * Links Frandango Item with a Lang Item
	 *
	 * @param [int] $fran_id Fran item id
	 * @param [array] $fields
	 *
	 * @return null
	 */
	public function linkFranItem($fran_id, $fields) {
		$this->db->where('item_id', $fran_id);
		$this->db->limit(1);
		$this->db->update('inventory', $fields);

		return null;
	} //end linkFranItem()

	/**
	 * *************************************
	 * ******only used via frangdango*******
	 * *************************************
	 *
	 * @param [int] $lang_id = lang item id
	 * @param [array] $fields = array of data
	 *
	 * @return null
	 */
	public function linkLangItem($lang_id, $fields) {

		$this->langdb =& $this->load->database('langdb01', true); //set langdb01 database

		$this->langdb->where('item_id', $lang_id);
		$this->langdb->limit(1);
		$this->langdb->from('inventory');

		$this->langdb->update('inventory', $fields);

		//print_r($this->langdb->get());
		$this->langdb->close(); //close connection

		return null; //null
	} //end linkLangItem()

	/**
	 * *************************************
	 * ******only used via frangdango*******
	 * *************************************
	 *
	 * Loads the langdb01 database. Tests to make sure
	 * the number you are trying to push does not
	 * conflict with an already existing item.
	 *
	 * @param [string] $number = item_id
	 *
	 * @return [bool]
	 */
	public function testItemNumber($number) {
		$b = false;
		$this->langdb =& $this->load->database('langdb01', true); //set langdb01 database
		$this->langdb->select('item_number');
		$this->langdb->from('inventory');
		$this->langdb->where('item_number', $number);
		$query = $this->langdb->get();
		$this->langdb->close(); //close connection

		if($query->num_rows() == 0) {
			$b = true;
		}

		return $b; //bool
	} //end testItemNumber()

	/**
	 * *************************************
	 * ******only used via frangdango*******
	 * *************************************
	 *
	 * Pushes a record from one database to another database table.
	 *
	 *  @param [string] $table = table name
	 *  @param [array] $fields = array of fields and values
	 *
	 *  @return [int] insert_id
	 */
	public function pushRecord($table, $fields) {
		$this->langdb =& $this->load->database('langdb01', true); //set database
		$this->langdb->insert($table, $fields);

		return $this->langdb->insert_id(); //int
	} //end pushRecord();

	/**
	 * *************************************
	 * ******only used via frangdango*******
	 * *************************************
	 *
	 * Updates the langb01 database with new data,
	 * Seems to be only used to update the items
	 * own lang_id field with it's own id.
	 *
	 * @param [int] $lang_id = langdb01:inventory:item_id
	 * @param [string] $field = column name
	 * @param [string] $value = value of change
	 *
	 * @return null;
	 */
	public function updatePushedRecord($lang_id, $field, $value) {
		$field = array($field => $value);

		$this->langdb =& $this->load->database('langdb01', true); //set database
		$this->langdb->where('item_id', $lang_id);
		$this->langdb->limit(1);
		$this->langdb->update('inventory', $field);
		$this->langdb->close(); //close connection

		return null;

	} //end updatePushedRecord();

} //end Push_model();