<?php
class Inventory_reports_model extends Model {

	var $ci;

	function __construct() {
		parent::Model();
		$this->ci =& get_instance();
	}
	/**
	 * Returns all items in the database
	 *
	 * @param [int] $per_page = per page (20 or so)
	 * @param [int] $offset = number to offset query by
	 * @param [string] $sort = column to sort on
	 * @param [string] $direction = direction in which sort happens
	 *
	 * @return [array] = multi-dim array
	 */
	public function getAllItems($per_page, $offset, $sort = FALSE, $direction = NULL) {
		$this->db->start_cache();
		$this->db->from('inventory');

		if ($sort != 'item_number') { //default sort
			$this->db->order_by($sort, $direction);
		}
		else {
			$this->db->order_by('mjr_class_id, min_class_id, suffix', $direction);
		}

		$this->db->stop_cache(); //Stops Cache!
		$count = $this->db->get();
		$this->db->limit($per_page, $offset);
		$query = $this->db->get();
		$this->db->flush_cache(); //flush Cache!

		$data = array();
		$data['num_rows'] = $count->num_rows();
		$data['items'] = $this->parseQuery($query);

		return $data;
	} //end

	/**
	 * Returns all items where item_status = 1|2|4
	 *
	 * @param [int] $per_page = per page (20 or so)
	 * @param [int] $offset = number to offset query by
	 * @param [string] $sort = column to sort on
	 * @param [string] $direction = direction in which sort happens
	 *
	 * @return [array] = multi-dim array
	 */
	public function getAvailableItems($per_page, $offset, $sort = FALSE, $direction = NULL) {
		$this->db->start_cache(); //Start Cache
		$this->db->from('inventory');
		$this->db->where('item_status', 1 ); //1=available,
		$this->db->or_where('item_status', 2 ); //2=Out on Job,
		$this->db->or_where('item_status', 4 ); //4=Out on Memo

		if ($sort != 'item_number') { //default sort
			$this->db->order_by($sort, $direction);
		}
		else {
			$this->db->order_by('mjr_class_id, min_class_id, suffix', $direction);
		}

		$this->db->stop_cache(); //Stops Cache!
		$count = $this->db->get();
		$this->db->limit($per_page, $offset);
		$query = $this->db->get();
		$this->db->flush_cache(); //flush Cache!

		$data = array();
		$data['num_rows'] = $count->num_rows();
		$data['items'] = $this->parseQuery($query);

		return $data; //array
	} //end getAvailbleItems();

	/**
	 * Returns all of the Online Items only
	 *
	 * @param [int] $per_page = per page (20 or so)
	 * @param [int] $offset = number to offset query by
	 * @param [string] $sort = column to sort on
	 * @param [string] $direction = direction in which sort happens
	 *
	 * @return [array] = multi-dim array
	 */
	public function getOnlineItems($per_page, $offset, $sort = FALSE, $direction = NULL) {
		$this->db->start_cache();
		$this->db->from('inventory');
		$this->db->where('web_status', 1);
		$this->db->where('item_quantity >=', 1);
		$this->db->where_in('item_status ', array(1,2,3,4)); //0=sold
		if ($sort != 'item_number') {
			$this->db->order_by($sort, $direction);
		}
		else {
			$this->db->order_by('mjr_class_id, min_class_id, suffix', $direction);
		}

		$this->db->stop_cache(); //Stops Cache!
		$count = $this->db->get();
		$this->db->limit($per_page, $offset);
		$query = $this->db->get();
		$this->db->flush_cache(); //flush Cache!

		$data = array();
		$data['num_rows'] = $count->num_rows();
		$data['items'] = $this->parseQuery($query);

		return $data; //array
	} //end getOnlineItems();

	/**
	 * Returns all of the items with web images
	 *
	 * @param [int] $per_page = per page (20 or so)
	 * @param [int] $offset = number to offset query by
	 * @param [string] $sort = column to sort on
	 * @param [string] $direction = direction in which sort happens
	 *
	 * @return [array] = multi-dim array
	 */
	public function getNonSoldWithImages($per_page, $offset, $sort = FALSE, $direction = NULL) {
		$this->db->start_cache();
		$this->db->from('inventory');
		$this->db->join('image_base', 'image_base.item_id = inventory.item_id');
		$this->db->where('inventory.web_status !=', 1);
			$nums = array(1,2,91); //1=available, 2=out on job, 91=FK import
			$this->db->where_in('item_status', $nums);
		$this->db->where('inventory.item_quantity >', 0);
		$this->db->group_by('inventory.item_id');
		$this->db->having('count(image_base.image_id) >',0, false);
		$this->db->order_by('image_base.image_date', 'DESC');

		$this->db->stop_cache(); //Stops Cache!
		$count = $this->db->get();
		$this->db->limit($per_page, $offset);
		$query = $this->db->get();
		$this->db->flush_cache(); //flush Cache!

		$data = array();
		$data['num_rows'] = $count->num_rows();
		$data['items'] = $this->parseQuery($query);

		return $data; //array
	} //end getNonSOldWithImages();

	/**
	 * Returns all of the items without web images
	 *
	 * @param [int] $per_page = per page (20 or so)
	 * @param [int] $offset = number to offset query by
	 * @param [string] $sort = column to sort on
	 * @param [string] $direction = direction in which sort happens
	 *
	 * @return [array] = multi-dim array
	 */
	function getNonSoldWithOutImages($per_page, $offset, $sort = FALSE, $direction = NULL) {
		$this->db->start_cache();
		$this->db->from('inventory');
		$this->db->where('inventory.web_status !=', 1);
			$nums = array(0,4,5,7,99); //0=sold, 4=Out on Memo, 5=Burgled, 7=Return to Consignee, 99=unavailble
			$this->db->where_not_in('item_status', $nums);
		$this->db->where('inventory.item_quantity >', 0);
			$mjr = array(4,5,15,25,80,200);
		$this->db->where_not_in('inventory.mjr_class_id', $mjr);
		$this->db->where('inventory.item_id NOT IN','(SELECT image_base.item_id FROM image_base)', false);
		$this->db->group_by('inventory.item_id');

		if ($sort != 'item_number') { //non default sort
			$this->db->order_by($sort, $direction);
		}
		else {
			$this->db->order_by('mjr_class_id, min_class_id, suffix', $direction);
		}

		$this->db->stop_cache(); //Stops Cache!
		$count = $this->db->get();
		$this->db->limit($per_page, $offset);
		$query = $this->db->get();
		$this->db->flush_cache(); //flush Cache!

		$data = array();
		$data['num_rows'] = $count->num_rows();
		$data['items'] = $this->parseQuery($query);

		return $data; //array
	} //end getNonSoldWithOutImages();

	/**
	 * Returns all of the sold items
	 *
	 * @param [int] $per_page = per page (20 or so)
	 * @param [int] $offset = number to offset query by
	 * @param [string] $sort = column to sort on
	 * @param [string] $direction = direction in which sort happens
	 *
	 * @return [array] = multi-dim array
	 */
	public function getSoldItems($per_page, $offset, $sort = FALSE, $direction = NULL) {
		$this->db->start_cache();
		$this->db->from('inventory');
		$this->db->where('item_status', 0);
		if ($sort != 'item_number') { //default sort
			$this->db->order_by($sort, $direction);
		}
		else {
			$this->db->order_by('mjr_class_id, min_class_id, suffix', $direction);
		}
		$this->db->stop_cache(); //Stops Cache!
		$count = $this->db->get();
		$this->db->limit($per_page, $offset);
		$query = $this->db->get();
		$this->db->flush_cache(); //flush Cache!

		$data = array();
		$data['num_rows'] = $count->num_rows();
		$data['items'] = $this->parseQuery($query);

		return $data; //array
	} //end getSoldItems();

	/**
	 * Returns the same thing as 'Whats New At Lang'
	 *
	 * @param [int] $per_page = per page (20 or so)
	 * @param [int] $offset = number to offset query by
	 * @param [string] $sort = column to sort on
	 * @param [string] $direction = direction in which sort happens
	 *
	 * @return [array] = multi-dim array
	 */
	public function getWhatsNew($per_page, $offset, $sort = FALSE, $direction = NULL) {
		$lastmonth = date("Y-m-d", mktime(0, 0, 0, date("m")-1, date("d"),   date("Y")));

		$this->db->start_cache();
		$this->db->from('inventory');
		$this->db->where('web_status', 1);
		$this->db->where('item_quantity >=', 1);
		$this->db->where_in('item_status ', array(1,2,3,4)); //0=sold
		$this->db->where('publish_date >', $lastmonth);

		if ($sort != 'item_number') {
			$this->db->order_by($sort, $direction);
		}
		else {
			$this->db->order_by('mjr_class_id, min_class_id, suffix', $direction);
		}

		$this->db->stop_cache(); //Stops Cache!
		$count = $this->db->get();
		$this->db->limit($per_page, $offset);
		$query = $this->db->get();
		$this->db->flush_cache(); //flush Cache!

		$data = array();
		$data['num_rows'] = $count->num_rows();
		$data['items'] = $this->parseQuery($query);

		return $data; //array
	} //end getWhatsNew();

	/**
	 * Parses the Query Results of each search
	 *
	 * @param [array] $obj = mysql results obj
	 *
	 * @return [array] = multi-dim array of inventory data
	 */
	public function parseQuery($obj) {
		$this->ci->load->model('inventory/inventory_model');
		$this->ci->load->model('image/image_model');

		$data = array();
		if($obj->num_rows() > 0) {
			foreach ($obj->result_array() as $row) {
				if (isset($row['item_status'])) {
					$row['icon_status'] = $this->ci->inventory_model->itemStatus($row['item_status']);
				}
				if (isset($row['web_status'])) {
					$row['icon_web_status'] = $this->ci->inventory_model->itemWebStatus($row['web_status']);
				}
				$row['image_array'] = $this->ci->image_model->getItemImages($row['item_id']);
				$data[$row['item_id']] = $row;
			}
		}
		return $data; //array
	} //end runQuery();

} //end Inventory_reports_model();
?>