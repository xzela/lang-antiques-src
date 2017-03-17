<?php
class Feeds extends Controller {
	
	public function __construct() {
		parent::Controller();	
	}
	
	/**
	 * Creates an RSS feed of our products. Used by google shopping
	 * 
	 * @param [string] $action = what to return,
	 * 
	 *  @return [string] = returns an rss string
	 */
	public function google_products_feed($action = 'everything') {
		$this->load->model('utils/api_model');
		$data = array();
		if($action == 'new') {
			$data['items'] = $this->api_model->getProductsWhatsNew();
		}
		else {
			$data['items'] = $this->api_model->getProductsData();
		}
		$rss = $this->api_model->formatGoogleProductsXml($data['items']);
		
		echo $rss; //rss xml feed
	} //end google_products_feed();
	
	/**
	 * Creates a flat file for bing shopping.
	 * 
	 * @param [string] $action = what to return
	 * 
	 * @return [file] = returns a text file for client to download
	 */
	public function bing_product_file($action = 'everything') {
		$this->load->model('utils/api_model');
		$data = array();
		$data['items'] = $this->api_model->getProductsData();
		
		$file = $this->api_model->formatBingFile($data['items']);
		
		echo $file;	//flat file
	} //end bring_product_file();
	
} //end Feeds();