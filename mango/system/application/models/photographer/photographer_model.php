<?php
Class Photographer_model extends Model {
	
	function __construct() {
		parent::Model();
		
		$this->load->database();
		$this->ci =& get_instance();
	}
	
	/**
	 * Gets all item in the Photograph Queue, depending on which params are set
	 *
	 * @param [int] $per_page = number of items to return at one time
	 * @param [int] $offset = offset to be used (based on which page you are on)
	 * @param [int] $type = which queue to show {0,1,2} See if statement below
	 * @param [string] $sort = sort by (item_number, title, description, etc..)
	 * @param [string] $direction = sort direction {asc, desc}
	 * 
	 * @return [array] = queue data
	 */
	public function getPhotographQueue($per_page, $offset, $type, $sort = FALSE, $direction = NULL) {
		$this->ci->load->model('image/image_model');
		
		$this->db->start_cache();
		if ($type == 0) { //Show Photograph queue
			$this->db->from('inventory');
			$this->db->where('photo_queue', 1);
		}
		else if ($type == 1) { //Show Edit Queue everything?
			$this->db->from('inventory');
			$this->db->where('edit_queue', 1);
		}
		else if ($type == 2) { //Show Edit Queue with only Non-photographed
			$this->db->from('inventory');
			$this->db->where('edit_queue', 1);
			$this->db->where('photo_queue', 2);
		}
		
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
		
		if($count->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$photo_status = $row['photo_queue'];
				$edit_status = $row['edit_queue'];
				
				$row['icon_status'] = $this->itemImageStatus($photo_status, $edit_status); 
				$row['image_array'] = $this->ci->image_model->getItemImages($row['item_id']);
				$data['items'][] = $row;
			}
		}
		return $data; //array
		
	} //end getPhotoQueue();
	
	/**
	 * Returns the icon
	 *
	 * @param [int] $status 
	 * 
	 * @return [string]
	 */
	public function itemPhotoStatus($status) {
		switch($status) {
			case 0:
				return 'This Item should not be in your queue'; 
				break;
			case 1:
				return  snappy_image('icons/camera_add.png', 'Needs Photogrpah') . ' Needs Photograph '; 
				break;
		}
	} //end itemPhotoStatus();
	
	/**
	 * Returns the icon status of each item
	 * Photo Status: 
	 * 		0=Never in the queue
	 * 		1=Needs to be photographed
	 * 		2=Has been photographed
	 * 
	 * Edit Status:
	 * 		0=Not in Queue
	 * 		1=Needs to be Edited
	 *
	 * @param [int] $photo = photo status {0,1,2}
	 * @param [int] $edit = edit status (0,1) 
	 * 
	 * @return [string] = html image stuff 
	 */
	public function itemImageStatus($photo, $edit) {
		$photo_text = "";
		$edit_text = "";
		switch($photo) {
			case 0:
				$photo_text = '<span class=\'warning\'>This Item was never in the Photo Queue</span> <br />'; 
				break;
			case 1:
				$photo_text = snappy_image('icons/camera_add.png', 'Needs Photograph') . ' Needs Photograph <br />'; 
				break;
			case 2:
				$photo_text = snappy_image('icons/picture.png', 'Photographed') . ' Photographed <br />';
		}
		switch($edit) {
			case 0:
				$edit_text = ''; 
				break;
			case 1:
				$edit_text = snappy_image('icons/camera_edit.png', 'Needs Edits') . ' Needs Edits <br />'; 
				break;
		}
		return $photo_text . $edit_text; //string
	} //end itemImageStatus();

	/**
	 * Updates the status of the item
	 *
	 * @param [int] $id = item id
	 * @param [int] $status = new status {1,2,3}
	 * @param [string] $queue = queue field (see database) 
	 * 
	 * @return boolean
	 */
	public function updateQueueStatus($id, $value, $column = 'edit_queue') {
		$b = false;
		$fields = array($column => $value);
		$this->db->where('item_id', $id);
		$b = $this->db->update('inventory', $fields);
		
		return $b; //bool 
	} //end updateQueueStatus();

	/**
	 * Updates the order of web images
	 * 
	 * @param [int] $item_id = item id
	 * @param [array] $order = array data with order and image id
	 * 
	 * @return null;
	 */
	public function updatePhotoSeq($item_id, $order) {
		$data = array();
		$i = 1;
		foreach($order as $photo) {
			$data['image_seq'] = $i;
			$this->db->where('image_id = ', $photo);
			$this->db->where('item_id = ', $item_id);
			$this->db->update('image_base', $data);
			$i++;
		}
		
		return null;
	} //end updatePhotoSeq();
	
} //end Photographer_model();
?>