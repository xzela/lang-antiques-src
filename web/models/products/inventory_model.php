<?php
/**
 * Inventory Model
 *
 * Used to Select product information from the database
 *
 *
 * @author user
 *
 */
class Inventory_model extends Model {

	var $ci;

	function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
	}


	function getArchivedInventoryByMajorClass($major_class_id, $limit = 12, $offset = null, $field = 'publish_date', $direction = 'DESC') {

		$this->ci->load->model('images/image_model');
		$data = array();
			$data['inventory'] = array();
			$data['pagination'] = array();

		$this->db->start_cache();
		$this->db->from('inventory');
		$this->db->join('image_base', 'image_base.item_id = inventory.item_id');
		$this->db->join('image_base', 'image_base.item_id = inventory.item_id');
		$this->db->join('invoice_items', 'invoice_items.item_id = inventory.item_id');
		$this->db->join('invoice', 'invoice.invoice_id = invoice_items.invoice_id');
		$this->db->where_in('inventory.item_status', array(0,7));
		$this->db->where('invoice.sale_date IS NOT NULL', null, true);
		$this->db->where('inventory.publish_date IS NOT NULL', null, true);
		$this->db->where('publish_date IS NOT NULL', null, true);
		//Hardcoded case for Necklaces and Drops
		if($major_class_id == '90') {
			//If Neckalces, include drops (major_class_id = 120) in the results
			$this->db->where("(mjr_class_id = 90 OR mjr_class_id = 120) ",null,false);
		}
		else {
			$this->db->where('mjr_class_id', $major_class_id);
		}
		$this->db->group_by('inventory.item_id');
		$this->db->having('count(image_base.image_id) >', 0);
		if($field == 'sale_date') {
			$field = 'invoice.sale_date';
		}
		$this->db->order_by($field, $direction);
		$this->db->stop_cache(); //stops cache,

		$temp = $this->db->get();

		$data['pagination']['total_rows'] = $temp->num_rows();

		//if $offset is set to 'all', return all data.
		if($offset == 'all') {
			//no limit
		}
		else { //if not set to all, use $offset value
			if($offset != null) {

				$this->db->limit($limit, $offset);
			}
			else {
				$this->db->limit($limit);
			}
		}

		$query = $this->db->get();

		$this->db->flush_cache(); //clear cache,
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				//get images
				$row['images'] = $this->ci->image_model->getItemWebImages($row['item_id']);
				if(sizeof($row['images']) > 0) {
					$data['inventory'][] = $row;
				}
			}
		}
		return $data; //array
	} //end getArchivedInventoryByMajorClass();
	/**
	 * Attempt to get any available inventory items
	 * based on major class
	 *
	 * used by 'category'
	 *
	 * @param [int] $major_class_id = major class id
	 * @param [int] $limit = 12, limit number of items to page
	 * @param [int] $offset = offset of query
	 * @param [string] $direction = direction to sort items
	 *
	 * @return [array] = multi-dem array of items
	 *
	 */
	function getAvailableInventoryByMajorClass($major_class_id, $limit = 12, $offset = null, $field = 'publish_date', $direction = 'DESC') {
		$this->ci->load->model('images/image_model');
		$data = array();
			$data['inventory'] = array();
			$data['pagination'] = array();

		$this->db->start_cache();
		$this->db->from('inventory');
		//Hardcoded case for Necklaces and Drops
		if($major_class_id == '90') {
			//If Neckalces, include drops (major_class_id = 120) in the results
			$this->db->where("(mjr_class_id = 90 OR mjr_class_id = 120) ",null,false);
		}
		else {
			$this->db->where('mjr_class_id', $major_class_id);
		}
		$this->db->where('item_status', 1);
		$this->db->where('web_status', 1);

		$this->db->order_by($field, $direction);
		$this->db->stop_cache(); //stops cache,

		$temp = $this->db->get();

		$data['pagination']['total_rows'] = $temp->num_rows();

		//if $offset is set to 'all', return all data.
		if($offset == 'all') {
			//no limit
		}
		else { //if not set to all, use $offset value
			if($offset != null) {

				$this->db->limit($limit, $offset);
			}
			else {
				$this->db->limit($limit);
			}
		}

		$query = $this->db->get();
		$this->db->flush_cache(); //clear cache,
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				//get
				$row['images'] = $this->ci->image_model->getItemWebImages($row['item_id']);
				$data['inventory'][] = $row;
			}
		}
		return $data; //array
	} //end getAvailableInventoryByMajorClass();

	/**
	 * Gets All of the available Rings
	 *
	 * @param [int] $limit = 12, limit query return size
	 * @param [int] $offset = offset of query
	 * @param [string] $direction = direction of sort
	 *
	 * @return  [array] = multi-dem array of inventory data
	 */
	public function getAllRings($limit = 12, $offset = null, $field = 'publish_date', $direction = 'DESC') {
		$this->ci->load->model('images/image_model');
		$data = array();

		$this->db->start_cache();
		$this->db->from('inventory');
		$this->db->join('image_base', 'image_base.item_id = inventory.item_id');
		$this->db->where_in('item_status', array(1,2,3));
		$this->db->where_in('mjr_class_id', array(10,30,110));
		$this->db->where('web_status', 1);
		$this->db->where('item_quantity >', 0);
		$this->db->group_by('inventory.item_id');
		$this->db->having('count(image_base.image_id) >', 0);
		$this->db->order_by($field, $direction);

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
	} //end getAllRings();

	/**
	 * Returns all of the available inventory items
	 * by their modifier
	 *
	 * used by 'type'
	 *
	 * @param [int] $modifier_id = modifier_id
	 * @param [int] $limit = limit number of returned
	 * @param [int] $offset - offset of query
	 * @param [string] $direction = direction of the sort
	 *
	 * @return [array] = multi-dem array of inventory data
	 *
	 */
	function getAvailableInventoryByModifier($modifier_id, $limit = 12, $offset = null, $field = 'publish_date', $direction = 'DESC') {
		$this->ci->load->model('images/image_model');
		$data = array();
			$data['inventory'] = array();
			$data['pagination'] = array();

		$this->db->start_cache();
		$this->db->from('inventory');
		$this->db->join('item_modifier', 'inventory.item_id = item_modifier.item_id');
		$this->db->where('modifier_id', $modifier_id);
		$this->db->where('item_status', 1);
		$this->db->where('web_status', 1);
		$this->db->order_by($field, $direction);
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
	} //end getAvailableInventoryByModifier();

	/**
	 * Returns everything from inventory (that is available)
	 *
	 * @param [int] $limit = limit of query
	 * @param [int] $offset = offset of query
	 * @param [string] $direction = direction of the sort
	 *
	 * @return [array] = multi-dem array of inventory data;
	 */
	public function getEverything($limit = 12, $offset = null, $field = 'publish_date', $direction = 'DESC') {
		$this->ci->load->model('images/image_model');
		$data = array();

		$this->db->start_cache();
		$this->db->from('inventory');
		$this->db->join('image_base', 'image_base.item_id = inventory.item_id');
		$this->db->where_in('item_status', array(1,2,3,4));
		$this->db->where('web_status', 1);
		$this->db->where('item_quantity >=', 1);
		$this->db->group_by('inventory.item_id');
		$this->db->having('count(image_base.image_id) >', 0);
		$this->db->order_by($field, $direction);

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
	} //end getEverything();

	/**
	 * Returns inventory data for a specific item based on
	 * item id
	 *
	 *
	 * @param [int] $item_id = item id
	 *
	 * @return [array] = array of inventory data (one row)
	 */
	public function getItemDataById($item_id) {
		$this->ci->load->model('images/image_model');
		$this->ci->load->model('user/user_model');

		$this->db->from('inventory');
		$this->db->join('image_base', 'inventory.item_id = image_base.item_id'); //@TODO rename image_base to external_images
		$this->db->where('inventory.item_id', $item_id);
		$this->db->having('count(image_base.image_id) > 0');

		$this->db->limit(1);

		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				//get web images
				$row['images'] = $this->ci->image_model->getItemWebImages($row['item_id']);
				//default is favorite to false,
				$row['is_favorite'] = false;
				//check users session, if applicable
				if($this->session->userdata('customer_id') != null) {
					//test to see if item has been favoriteded (i just made that word up)
					$row['is_favorite'] = $this->ci->user_model->testIsFavorite($this->session->userdata('customer_id'), $row['item_id']);
				}
				$data = $row;
			}
		}
		return $data; //array
	} //end getItemDataById();

	/**
	 * Attempts to get an itme based on item number
	 *
	 * @param [string] $item_number = item number
	 *
	 * @return [array] = array of inventory data (one row)
	 */
	public function getItemDataByNumber($item_number) {
		$this->ci->load->model('images/image_model');
		$this->ci->load->model('user/user_model');


		$this->db->from('inventory');
		$this->db->join('image_base', 'inventory.item_id = image_base.item_id'); //@TODO rename image_base to external_images
		$this->db->where('item_number', $item_number);
		$this->db->having('count(image_base.image_id) > 0');

		$this->db->limit(1);

		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				//get images
				$row['images'] = $this->ci->image_model->getItemWebImages($row['item_id']);
				//set is favorite to false
				$row['is_favorite'] = false;
				//test to see if item has been added to favorites
				if($this->session->userdata('customer_id') != null) {
					$row['is_favorite'] = $this->ci->user_model->testIsFavorite($this->session->userdata('customer_id'), $row['item_id']);
				}
				$data = $row;
			}
		}
		return $data; //array
	}// end getItemDataByNumber();

	/**
	 * Just returns the name of an item,
	 *
	 * used by:
	 * /web/models/shopping/invoice_model.php:getInvoiceItems();
	 *
	 * @param [int] $item_id = item id
	 *
	 * @return [string] = name of item
	 */
	public function getItemName($item_id) {
		$data = '';
		$this->db->from('inventory');
		$this->db->where('item_id', $item_id);
		$this->db->limit(1);

		$query = $this->db->get();

		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row['item_name'];
			}
		}
		return $data; //string
	} //end getItemName();

	/**
	 * A massive query which returns similar items based on
	 * the current item the client is viewing.
	 *
	 * It uses the gemstone type, modifiers, and major class
	 * to determine which items are similar.
	 *
	 * It actually doesn't work that well. Some improvements should
	 * be made.
	 *
	 * @param [int] $item_id = item id
	 */
	public function getSimilarItems($item_id) {
		$this->ci->load->model('gemstones/gemstone_model');
		$this->ci->load->model('gemstones/diamond_model');
		$this->ci->load->model('gemstones/pearl_model');
		$this->ci->load->model('gemstones/opal_model');
		$this->ci->load->model('gemstones/jadeite_model');
		$this->ci->load->model('modifier/modifier_model');
		$this->ci->load->model('images/image_model');
		$data = array();

		//this will return items which might be similar to
		//current item.
		$modifiers = $this->ci->modifier_model->getItemModifiers($item_id);
		$diamonds = $this->ci->diamond_model->getItemDiamondTypes($item_id);
		$gemstones = $this->ci->gemstone_model->getItemGemstoneTypes($item_id);
		$pearls = $this->ci->pearl_model->getItemPearlTypes($item_id);
		$opals = $this->ci->opal_model->getItemOpalTypes($item_id);
		$jadeite = $this->ci->jadeite_model->getItemJadeiteTypes($item_id);

		//get current item data
		$item_data = $this->getItemDataById($item_id);
		//$this->db->from('inventory');
		//$this->db->join('item_modifier', 'inventory.item_id = item_modifier.item_id');
		//$this->db->join('image_base', 'inventory.item_id = image_base.item_id'); //@TODO rename image_base to external_images
		//$this->db->where_in('item_status', array(1,2,3,4));
		//$this->db->where('mjr_class_id', $item_data['mjr_class_id']);
		//$this->db->where('web_status', 1);
		//$this->db->where('item_quantity >', 0);

		$sql = 'SELECT inventory.* FROM inventory ';
			$sql .= ' LEFT JOIN item_modifier ON inventory.item_id = item_modifier.item_id ';
			$sql .= ' LEFT JOIN diamond_info ON inventory.item_id = diamond_info.item_id ';
			$sql .= ' LEFT JOIN stone_info ON inventory.item_id = stone_info.item_id ';
			$sql .= ' LEFT JOIN pearl_info ON inventory.item_id = pearl_info.item_id ';
			$sql .= ' LEFT JOIN opal_info ON inventory.item_id = opal_info.item_id ';
			$sql .= ' LEFT JOIN jadeite_info ON inventory.item_id = jadeite_info.item_id ';
			$sql .= ' LEFT JOIN image_base ON inventory.item_id = image_base.item_id '; //@TODO rename image_base to external_images
		$sql .= 'WHERE ';
			$sql .= ' item_status IN (1,2,3,4) ';
			$sql .= ' AND mjr_class_id = ' . $item_data['mjr_class_id'];
			$sql .= ' AND web_status = 1 ';
			$sql .= ' AND (item_quantity > 0 ';

			//create custom sql queries based on current information
		if(sizeof($modifiers) > 0) {
			$sql .= $this->_compose_sql('modifier_id', $modifiers);
		}
		if(sizeof($diamonds) > 0) {
			$sql .= $this->_compose_sql('d_type_id', $diamonds, 'OR');
		}
		if(sizeof($gemstones) > 0) {
			$sql .= $this->_compose_sql('gem_type_id', $gemstones, 'OR');
		}
		if(sizeof($pearls) > 0) {
			$sql .= $this->_compose_sql('p_type_id', $pearls, 'OR');
		}
		if(sizeof($opals) > 0) {
			$sql .= $this->_compose_sql('o_type_id', $opals, 'OR');
		}
		if(sizeof($jadeite) > 0) {
			$sql .= $this->_compose_sql('j_type_id', $jadeite, 'OR');
		}

		$sql .= ') AND inventory.item_id != ' . $item_id . ' ';
		$sql .= ' GROUP BY inventory.item_id ';
		$sql .= ' HAVING COUNT(image_base.image_id) > 0 ';
		$sql .= ' ORDER BY RAND() '; //order by rand
		$sql .= ' LIMIT 5 '; //only return five items

		//echo $sql;
		$query = $this->db->query($sql);
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				//get images
				$row['images'] = $this->ci->image_model->getItemWebImages($row['item_id']);
				$data[] = $row;
			}
		}
		return $data; //array
	}//end getSimilarItems();

	public function getStaffComments($item_id) {
		$this->ci->load->model('staff/staff_model');
		$this->db->from('inventory_staff_comments');
		$this->db->where('item_id', $item_id);

		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0 ) {
			foreach($query->result_array() as $row) {
				$row['staff_name'] = $this->ci->staff_model->getStaffName($row['staff_id']);
				$data[] = $row;
			}
		}

		return $data;

	} //end getStaffComments();

	/**
	 * Returns all of the available inventory items
	 * by their staff pick modifier
	 *
	 * used by 'type'
	 *
	 * @param [int] $limit = limit number of returned
	 * @param [int] $offset - offset of query
	 * @param [string] $direction = direction of the sort
	 *
	 * @return [array] = multi-dem array of inventory data
	 *
	 */
	public function getStaffPicks($limit = 12, $offset = null, $field = 'publish_date', $direction = 'DESC') {
		$this->ci->load->model('images/image_model');
		$data = array();
			$data['inventory'] = array();
			$data['pagination'] = array();

		$this->db->start_cache();
		$this->db->distinct();
		$this->db->select('count(inventory.item_id), inventory.item_id, inventory.item_number, inventory.item_name, inventory.item_description, inventory.item_price, inventory.item_status, inventory.item_quantity');
		$this->db->from('inventory');
		$this->db->join('item_modifier', 'inventory.item_id = item_modifier.item_id');
		$this->db->join('modifiers', 'item_modifier.modifier_id = modifiers.modifier_id');
		$this->db->where('staff', 1);
		$this->db->where('item_status', 1);
		$this->db->where('web_status', 1);
		$this->db->group_by('inventory.item_id');
		$this->db->order_by($field, $direction);

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
	} //end getStaffPicks();

	/**
	 * Get New inventory items,
	 *
	 * New inventory items have a new publish date.
	 *
	 * @param [int] $limit = limit number of items
	 * @param [int] $offset = offset of query
	 * @param [string] $direction = direction of query
	 *
	 * @return [array] = multi-dem array of inventory data
	 */
	public function getWhatsNewInventory($limit = 12, $offset = null, $field = 'publish_date', $direction = 'DESC') {
		$this->ci->load->model('images/image_model');

		$data = array();
			$data['inventory'] = array();
		//get last month.
		$lastmonth = date("Y-m-d", mktime(0, 0, 0, date("m")-1, date("d"),   date("Y")));
		//$sql = "SELECT * FROM inventory WHERE publish_date > '$lastmonth' AND web_status = 1 AND item_status <> 0";

		$this->db->start_cache();
		$this->db->from('inventory');
		//get items where publish_date is greater than last month (new items)
		$this->db->where('publish_date >', $lastmonth);
		$this->db->where('web_status', 1); //online
		$this->db->where('item_status', 1); //available
		$this->db->order_by($field, $direction); //Order by publish date

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
	} //end getWhatsNewInventory();

	/**
	 * Get every item which has been sold.
	 * Which also have web images
	 *
	 * @param [int] $limit = limit query
	 * @param [int] $offset = offset of query
	 * @param [int] $direction = direction of query
	 *
	 * @return [array] = multi-dem array of inventory data;
	 */
	public function getTheArchive($limit = 12, $offset = null, $field = 'invoice.sale_date', $direction = 'DESC') {
		$this->ci->load->model('images/image_model');
		$data = array();

		$this->db->start_cache();
		$this->db->from('inventory');
		$this->db->join('image_base', 'image_base.item_id = inventory.item_id');
		$this->db->join('invoice_items', 'invoice_items.item_id = inventory.item_id');
		$this->db->join('invoice', 'invoice.invoice_id = invoice_items.invoice_id');
		$this->db->where_in('inventory.item_status', array(0,7));
		$this->db->where('invoice.sale_date IS NOT NULL', null, true);
		$this->db->where('inventory.publish_date IS NOT NULL', null, true);
		$this->db->group_by('inventory.item_id');
		$this->db->having('count(image_base.image_id) >', 0);
		$this->db->order_by($field, $direction);

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
	}// end getTheArchive();

	/**
	 * Updates a specific item as being sold.
	 *
	 *
	 * @param [int] $item_id = item id
	 *
	 * @return null;
	 */
	public function updateItemAsSold($item_id) {
		$fields = array('item_status'=>0,'web_status'=>0);
		$this->db->where('item_id', $item_id);
		$this->db->limit(1); //always limit one
		$this->db->update('inventory', $fields);

		return null; //null
	} //end updateItemAsSold();

	/**
	 * PRIVATE
	 *
	 * function to create some sql for us.
	 *
	 * It takes the key (primary_key)
	 *
	 * @param unknown_type $key
	 * @param unknown_type $array
	 * @param unknown_type $operator
	 */
	private function _compose_sql($key, $array, $operator = 'AND') {
		$string = '';
		if(sizeof($array) > 0) {
			$string = ' ' . $operator . ' (';
			$counter = 0;
			while(sizeof($array) > 0) {
				if(sizeof($array) == 1) {
					$string .= ' ' . $key . ' = ' . $array[$counter][$key] . ') ';
				}
				else {
					$string .= ' ' . $key . ' = ' . $array[$counter][$key] . ' OR ';
				}
				unset($array[$counter]);
				$counter++;
			}
		}
		return $string;
	} //end _compose_sql();

} //end Inventory_model();

?>