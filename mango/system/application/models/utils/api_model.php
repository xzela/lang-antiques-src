<?php

/**
 * This returns data specificly for APIs 
 * Such as Google Product Search, BingShopping, and RSS feeds
 * 
 * @author user
 *
 */
class Api_model extends Model {
	
	public $ci;
	
	public function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
		 
	}
	
	/**
	 * Formats a flat file for Bing Shopping
	 * By the way, When you upload the file to bing, make sure 
	 * it's in a windows format. Last time I uploaded a flat
	 * file, they bitched about the file format being in Unix
	 * 
	 * @param [array] $items = an array of items
	 * 
	 * @return [string] = returns a (long-ass) string
	 */
	public function formatBingFile($items) {
		$this->ci->load->model('admin/major_class_model');
		
		$line = '';
			//header starts now;
			$line .= 'MerchantProductID' . "\t";
				$line .= 'Title' . "\t";
				$line .= 'ProductURL' . "\t";
				$line .= 'Price' . "\t";
				$line .= 'Description' . "\t";
				$line .= 'imageURL' . "\t";
				$line .= 'B_Category' . "\t";
				$line .= 'MerchantCategory' . "\t" . "\n";
		//print_r($items);
		foreach($items as $item) {
			$major_class = $this->ci->major_class_model->getMajorClassData($item['mjr_class_id']);
			$item_line = '';
				$item_line .= $item['item_number'] . "\t"; //Merchant Prodoct ID
				$item_line .= str_replace('"', '""', $item['item_name']) . "\t"; //Title
				$item_line .= str_replace('"', '""', 'http://www.langantiques.com/products/item/' . $item['item_number']) . "\t"; //ProductURL
				$item_line .= str_replace('"', '""', $item['item_price']) . "\t"; //Price
					$newstring = preg_replace("/[\n\r]/","",$item['item_description']);
				$item_line .= str_replace('"', '""', $newstring) . "\t"; //Description
				$item_line .= str_replace('"', '""', 'http://www.langantiques.com' . $item['image_array']['external_images'][0]['image_location']) . "\t"; //imageURL
				$item_line .= 'Jewelry & Watches' . "\t"; //B_Category
				$item_line .= 'Jewelry & Watches | ' . $major_class['major_class_name'] . "\t" . "\n"; //B_Category
			$line .= $item_line;
		}
		return $line; //string
	} //end FormatBingFile
	
	/**
	 * Formats an array of items into an RSS2.0 document
	 * Used for Google Product Search
	 * 
	 * @param [array] $items = an array of items to be formatted
	 * 
	 * @return [string] = a formatted rss2.0 string. 
	 */
	public function formatGoogleProductsXml($items) {
		$this->ci->load->model('inventory/modifier_model');
		$this->ci->load->model('inventory/material_model');
		$this->ci->load->model('utils/lookup_list_model');
		
		$major_classes = $this->ci->lookup_list_model->getMajorClasses();
		
		$rss = '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";
		$rss .= '<rss version="2.0"';
		$rss .= ' xmlns:g="http://base.google.com/ns/1.0" xmlns:c="http://base.google.com/ns/1.0" >' . "\n";
			$rss .= "\t" . '<channel>' . "\n";
				$rss .= "\t\t" . '<title>Google API Online Items</title>' . "\n";
				$rss .= "\t\t" . '<link>http://www.langantiques.com</link>' . "\n";
				$rss .= "\t\t" . '<description>Lang Antique Estate and Antique Jewelry</description>' . "\n";
				
				foreach($items as $item) {
					$rss .= "\t\t" . '<item>' . "\n";
						$rss .= "\t\t\t" . '<title>' . htmlspecialchars($item['item_name']) . '</title>' . "\n";
						$rss .= "\t\t\t" . '<link>http://www.langantiques.com/products/item/' . $item['item_number'] . '</link>' . "\n";
						$rss .= "\t\t\t" . '<description>' . htmlspecialchars($item['item_description']) . '</description>' . "\n";
						
						foreach($item['image_array']['external_images'] as $image) {
							$rss .= "\t\t\t" . '<g:image_link>http://www.langantiques.com' . $image['image_location'] . '</g:image_link>' . "\n";							
						}
						
						$rss .= "\t\t\t" . '<g:price>' . $item['item_price'] . '</g:price>' . "\n";
						if($item['api_new_condition'] == 1) {
							$rss .= "\t\t\t" . '<g:condition>used</g:condition>' . "\n";	
						}
						else {
							$rss .= "\t\t\t" . '<g:condition>new</g:condition>' . "\n";
						}
						
						if($item['item_size'] != null) {
							$rss .= "\t\t\t" . '<c:ring_size type="string">' . $item['item_size'] . '</c:ring_size>' . "\n";	
						}						
						$material_data = $this->ci->material_model->getAppliedMaterials($item['item_id']);
						foreach($material_data as $material) {
							$rss .= "\t\t\t" . '<g:material>' . htmlspecialchars($material['material_name']) . '</g:material>' . "\n";
						}
						//List Modifiers (search terms)
						$modifier_data = $this->ci->modifier_model->getAppliedModifiers($item['item_id']);
						$rss .= "\t\t\t" . '<g:product_type> Clothing &amp; Accessories &gt; Jewelry &gt; ' . htmlspecialchars($major_classes[$item['mjr_class_id']]['major_class_name']) . '</g:product_type>' . "\n";
						foreach($modifier_data as $modifier) {
							$rss .= "\t\t\t" . '<g:product_type> Clothing &amp; Accessories &gt; Jewelry &gt; ' . htmlspecialchars($modifier['modifier_name']) . '</g:product_type>' . "\n";
							if($modifier['modifier_id'] == 5) {
								$rss .= "\t\t\t" . '<g:occasion>Engagement</g:occasion>' . "\n";
							}
						}
						$rss .= "\t\t\t" . '<g:quantity>' . $item['item_quantity'] . '</g:quantity>' . "\n";
						
						//List payment types
						$rss .= "\t\t\t" . '<g:payment_accepted>Cash</g:payment_accepted>' . "\n";
						$rss .= "\t\t\t" . '<g:payment_accepted>Check</g:payment_accepted>' . "\n";
						$rss .= "\t\t\t" . '<g:payment_accepted>Visa</g:payment_accepted>' . "\n";
						$rss .= "\t\t\t" . '<g:payment_accepted>MasterCard</g:payment_accepted>' . "\n";
						$rss .= "\t\t\t" . '<g:payment_accepted>AmericanExpress</g:payment_accepted>' . "\n";
												
						$rss .= "\t\t\t" . '<g:id>' . $item['item_number'] . '</g:id>' . "\n";
					$rss .= "\t\t" . '</item>' . "\n";
				}
				
			$rss .= "\t" . '</channel>' . "\n";
		$rss .= '</rss>' . "\n";		
		
		return $rss; //string	
	} //end formatGoogleProductsXml()
		
	/**
	 * Returns all items for the Google Product Search
	 * 
	 * @return [array] = multi-dim array of items
	 */
	function getProductsData() {
		$this->ci->load->model('image/image_model');
		
		$this->db->from('inventory');
		$this->db->where('web_status', 1);
		$this->db->where('item_quantity <>', 0);
		$this->db->where('item_status <>', 0);
		$this->db->order_by('publish_date', 'DESC');
		//$this->db->limit(50);
		
		$query = $this->db->get();		
		$data = array();
		
		if($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$row['image_array'] = $this->ci->image_model->getItemImages($row['item_id']);
				$data[] = $row;
			}
		}
		return $data;
	} //end getProductData()
	
	/**
	 * Returns a feed of all the new products,
	 * 
	 * 
	 * @return [array] = multi-dim array of products
	 */
	function getProductsWhatsNew() {
		//"SELECT * FROM inventory WHERE publish_date > '$lastmonth' AND web_status = 1 AND item_status <> 0";
		$this->ci->load->model('image/image_model');
		$lastmonth = date("Y-m-d", mktime(0, 0, 0, date("m")-1, date("d"),   date("Y")));
		
		$this->db->from('inventory');
		$this->db->where('publish_date >', $lastmonth);
		$this->db->where('item_quantity <>', 0);
		$this->db->where('web_status', 1);
		$this->db->where('item_status <>', 0);
		$this->db->order_by('publish_date', 'DESC');
		//$this->db->limit(50);
		
		$query = $this->db->get();
		$data = array();
		
		if($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$row['image_array'] = $this->ci->image_model->getItemImages($row['item_id']);
				$data[] = $row;
			}
		}
		return $data;
	} //end getProductsWhatsNew();

} //end Api_model()