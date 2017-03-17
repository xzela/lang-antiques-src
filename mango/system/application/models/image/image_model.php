<?php
/**
 * Image Model, used for updating, delete, and inserting new iamges
 * 
 * both web images and scan images use this model
 * 
 * 
 * @author user
 *
 */
class Image_model extends Model {
	
	function __construct() {
		parent::Model();	
		$this->load->database();
	}
	
	/**
	 * Returns all of the external images applied to 
	 * a specific item
	 * @TODO: rename 'baseImages' to 'externalImages';
	 *
	 * @param [int] $item_id
	 * 
	 * @return [array] = multi-din  array of external images
	 * 
	 */
	public function getAllBaseImages($item_id) {
		//$this->db->flush_cache();
		$this->db->select('inventory.item_id, image_base.image_id, image_base.image_title, image_base.image_name, image_base.image_type, image_base.image_seq, image_base.image_size, image_base.image_location');
		$this->db->from('inventory');
		$this->db->join('image_base', 'image_base.item_id = inventory.item_id');
		$this->db->where('inventory.item_id', $item_id);
		$this->db->order_by('image_seq', 'ASC');
		
		$image_results = $this->db->get();
		$image_ids = array();
		foreach ($image_results->result_array() as $images) {
			$temp_array = array();
			$temp_array['image_id'] = $images['image_id'];
			$temp_array['image_type'] = $images['image_type'];
			$temp_array['image_name'] = $images['image_name'];
			$temp_array['image_title'] = $images['image_title'];
			$temp_array['image_size'] = $images['image_size'];
			$temp_array['image_seq'] = $images['image_seq'];
			$temp_array['image_class'] = 1; //base_image
			$temp_array['image_location'] = $this->config->item('image_url') . $images['image_location'];
			
			$image_ids[] = $temp_array;
		}
		return $image_ids; //array
	} //end getAllBaseImages();
	
	/**
	 * Returns all of the inventory/internal images 
	 * applied to a specific item
	 *
	 * @TODO: rename 'langImages' to 'internalImages'
	 * 
	 * @param [int] $item_id
	 * 
	 * @return [array] = multi-din  array of images
	 */
	public function getAllLangImages($item_id) {
		//$this->db->flush_cache();
		$this->db->select('inventory.item_id, image_lang.image_id, image_lang.image_type, image_lang.image_size, image_lang.image_location');
		$this->db->from('inventory');
		$this->db->join('image_lang', 'image_lang.item_id = inventory.item_id');
		$this->db->where('inventory.item_id', $item_id);
		
		$image_results = $this->db->get();
		$image_ids = array();
		foreach ($image_results->result_array() as $images) {
			$temp_array = array();
			$temp_array['image_id'] = $images['image_id'];
			$temp_array['image_type'] = $images['image_type'];
			$temp_array['image_size'] = $images['image_size'];
			$temp_array['image_class'] = 2; //base_image
			$temp_array['image_location'] = $this->config->item('image_url') . $images['image_location'];
			$image_ids[] = $temp_array;
		}
		return $image_ids; //array
	} //end of getAllLangImages();
	
	/**
	 * Retruns the id of the external (base) image
	 *
	 * @param [int] $item_id
	 * 
	 * @return [int] = image id
	 */
	public function getBaseImageId($item_id) {
		//$this->db->flush_cache();
		$this->db->select('inventory.item_id, image_base.image_id AS base_id');
		$this->db->from('inventory');
		$this->db->join('image_base', 'image_base.item_id = inventory.item_id');
		$this->db->where('inventory.item_id', $item_id);
		$this->db->where('image_base.image_seq', 1);
		
		$image_results = $this->db->get();
		$image_id = "";
		foreach ($image_results->result_array() as $images) {
			$image_id = $images['base_id'];
		}
		return $image_id; //int;
		
	} //end getBaseImageId();
	
	/**
	 * Gets all of the External Images applied 
	 * to a specific item
	 * 
	 * @param [int] $id = item_id
	 * 
	 * @return [array] = multi-dim array of image data;
	 */	
	public function getExternalImages($item_id) {
		$this->db->where('item_id', $item_id);
		$this->db->from('image_base');
		$this->db->order_by('ISNULL(image_seq), image_seq ASC'); //fixes the NULLs first error
		$query = $this->db->get();
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
		
	} //end getExternalImages();
	
	/**
	 * Gets the location of a SINGLE image
	 *
	 * @param [int] $type = image type || internal, external
	 * @param [int] $image_id = image id
	 * 
	 * @return [array] = array of image data
	 */
	public function getImageLocation($image_id, $type) {
		$image = array();
			$image['image_type'] = 3;
			$image['image_class'] = 3; //nothing;
			
		if($type == 'external') { //these are external images
			$this->db->where('image_id', $image_id);
			$this->db->order_by('image_seq', 'asc');
			$query = $this->db->get('image_base');
			if($query->num_rows() > 0) {
				$row = $query->row_array();
					$image['image_id'] = $row['image_id'];
					$image['image_type'] = $row['image_type'];
					$image['image_size'] = $row['image_size'];
					$image['image_class'] = 1; //base //external
					$image['image_location'] = $this->config->item('image_url') . $row['image_location'];
					$image['image_delete_location'] = $row['image_location'];
			}
		}
		else if($type == 'internal') { //internal images
				$this->db->where('image_id', $image_id);
				$this->db->limit(1); //always limit one
				$this->db->from('image_lang');
				$query = $this->db->get();
				
				if($query->num_rows() > 0) {
					$row = $query->row_array();
						$image['image_id'] = $row['image_id'];
						$image['image_type'] = $row['image_type'];
						$image['image_size'] = $row['image_size'];
						$image['image_class'] = 2; //internal
						$image['image_location'] = $this->config->item('image_url') . $row['image_location'];
						$image['image_delete_location'] = $row['image_location'];
			}
		}
		return $image; //array
	} //end getImageLocation();
	
	/**
	 * Returns an Array of images based on their type.
	 * 
	 * @param [int] $id = image_id 
	 * 
	 * @return [array] = multi-din  array of image data;
	 */
	public function getItemImages($item_id) {
		$images['external_images'] = $this->getExternalImages($item_id);
		$images['internal_images'] = $this->getInternalImages($item_id);
		
		return $images; //array;
		
	} //end getItemImages();
	
	/**
	 * Gets all of the Internal Images applied 
	 * to a specific item
	 * 
	 * @param [int] $id = image_id
	 * 
	 * @return [array] = multi-din  array of image data;
	 */
	public function getInternalImages($item_id) {
		$this->db->where('item_id', $item_id);
		$this->db->from('image_lang');
		
		$data = array();
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$row['image_class'] = 2; //internal
				$row['image_copy_location'] = $row['image_location'];
				$row['image_location'] = $this->config->item('image_url') . $row['image_location'];
				$data[] = $row;
			}
		}
		return $data; //array
		
	} //end getInternalImages();
	
	/**
	 * Returns the id of the internal (lang) image
	 *
	 * @param [int] $item_id
	 * 
	 * @return [int] = image id
	 */
	public function getLangImageId($item_id) {
		//$this->db->flush_cache(); //lets flush the cache
		$this->db->select('inventory.item_id, image_lang.image_id AS lang_id');
		$this->db->from('inventory');
		$this->db->join('image_lang', 'image_lang.item_id = inventory.item_id');
		$this->db->where('inventory.item_id', $item_id);
		$image_results = $this->db->get();
		$image_id = "";
		foreach ($image_results->result_array() as $images) {
			$image_id = $images['lang_id'];		
		}
		return $image_id; //int
		
	} //end getLangImageId();
	
	/**
	 * 
	 * @param [int] $image_id = image id
	 * @param [array] $fields = array of fields
	 * 
	 * @return null;
	 */
	public function updateImageData($image_id, $fields) {
		$this->db->where('image_id', $image_id);
		$this->db->limit(1); //always limit 1;
		$this->db->update('image_base', $fields);

		return null; //null
		
	} //end updateImageData();
	
	/**
	 * This inserts the external image data into the database
	 * We do not store the image within the database, just it's data
	 * blobs are slow...
	 *
	 * Also, check to see how many images already exist for this item
	 * and update the new images seq.
	 * 
	 * @param [int] $item_id = item id
	 * @param [array] $image = array image data;
	 *   
	 * @return [int] = image_id;
	 */
	public function uploadExternalImage($item_id, $image) {
		$data = array('item_id' => $item_id,
			'image_name' => $image['image_name'],
			'image_title' => $image['image_title'],
			'image_size' => $image['image_size'],
			'image_location' => $image['image_location'],
			'image_date' => date('Y/m/d'),
			'image_type' => $image['image_type']);
		
		$this->db->from('image_base');
		$this->db->where('item_id', $item_id);
		$query = $this->db->get();
		//find out how many images aready exists for this item
		if($query->num_rows() > 0) {
			//if more than none exits
			//set the $order to order+1;
			$seq = $query->num_rows();
			$seq = $seq + 1;
		}
		else {
			//no other images found, set $seq to 1;
			$seq = 1;
		}
		$data['image_seq'] = $seq;
		$this->db->insert('image_base', $data);
		
		return $this->db->insert_id(); //int
		
	} //end uploadExternalImage(); 
	
	/**
	 * Inserts an internal Image database record.
	 * This does not upload an image per se, but rather
	 * inserts the data corisponding to the image
	 * 
	 * @param [array] $fields = array of fields
	 * 
	 * @return [int] image id
	 */
	public function uploadInternalImage($fields) {
		$this->db->insert('image_lang', $fields);
		return $this->db->insert_id(); //int
		
	} //end uploadInternalImage();
	
	/**
	 * inserts the plot image information
	 * into the datbase
	 * 
	 * @param [array] $fields = array of fields
	 * 
	 * @return null
	 */
	public function uploadPlotImage($fields) {
		$this->db->insert('inventory_appraisel_plots', $fields);
		
		return null; //null
	} //end uploadPlotImage();

	/**
	 * Removes an External Image from the database.
	 * 
	 * @param [int] $item_id = item id
	 * @param [int] $image_id = image id
	 * 
	 * @return null
	 */
	public function removeExternalImage($item_id, $image_id) {
		$this->db->where('item_id', $item_id);
		$this->db->where('image_id', $image_id);
		$this->db->limit(1); //always limit one
		$this->db->delete('image_base');
		
		return null;
	} //end removeExternalImage()
	
	/**
	 * Removes an Internal Image from the database.
	 * 
	 * @param [int] $item_id = item id
	 * @param [int] $image_id = image id
	 * 
	 * @return null
	 */	
	public function removeInternalImage($item_id, $image_id) {
		$this->db->where('item_id', $item_id);
		$this->db->where('image_id', $image_id);
		$this->db->limit(1); //always limit one
		$this->db->delete('image_lang');
		
		return null;
		
	} //end removeInternalImage();
	
	/**
	 * Removes an Appraisal plot image from an appraisal
	 * 
	 * @param [int] $appraisal_image_id = appraisal image id
	 * 
	 * @return null
	 */
	public function removePlotImage($appraisal_image_id) {
		$this->db->where('appraisel_image_id', $appraisal_image_id); //@TODO fix appraisal database misspelling
		$this->db->limit(1); //always limit one
		$this->db->delete('inventory_appraisel_plots');
		
		return null;
	} //end removePlotImage();
	
} //end Image_model();
?>