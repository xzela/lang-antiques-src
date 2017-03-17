<?php

class Archive extends Controller {

	var $categories = array();
	var $types = array();
	var $controller;
	var $sorts;

	public function __construct() {
		parent::Controller();

		$this->load->model('major_class/major_class_model');
		$this->load->model('modifier/modifier_model');
		$this->load->model('menu/menu_model');
		$this->load->model('products/inventory_model');
		$this->load->library('my_pagination'); //modified pagination lib

		$this->categories = $this->major_class_model->getMajorClasses();
		$this->types = $this->modifier_model->getModifiers();
		$this->controller = 'the-archive';
		$this->sorts = $this->menu_model->getSorts();

	}


	public function index ($string = 'all', $field = 'sale_date', $direction = 'desc', $offset = null) {
		$this->load->model('products/inventory_model'); //load inventory model
		$this->load->library('my_pagination'); //modified pagination lib
		$data = array();
		$page = array();
		$data['sorts'] = $this->sorts;
		$page['direction'] = 'invoice.sales_date';
		unset($data['sorts']['newest']);
		reset($data['sorts']);

		//take the string to find it's major_class id;
		$major_class_id = $this->_find_major_class($string);

		if($major_class_id != null || $string == 'all') { //if not null,
			//test to see if a direction change has been made,
			foreach($this->sorts as $sort) {
				if($this->input->post($sort['field'] . '-' . $sort['direction'])) {
					$field = $sort['field'];
					if(isset($sort['direction'])) {
						$direction = $sort['direction'];
					}
					else {
						$direction = 'desc';
					}
				}
			}

			//$p_config = paination config array;
				//$string = url-string,
				//$offset = query offset
				//method_name = current method (for URL)
				//$field = field to sort by (URL)
				//$direction = sort direction
				//controller_name = name of controller (for URL)
			$p_config = $this->my_pagination->create_pagination_config($string, $offset, null, $field, $direction, $this->controller);

			//Get majorclass data
			$data['category_data'] = $this->major_class_model->getMajorClassData($major_class_id);
			//get items
			if($major_class_id == null) {
				$pag_data = $this->inventory_model->getTheArchive($p_config['per_page'], $offset, $field, $direction);
			}
			else {
				$pag_data = $this->inventory_model->getArchivedInventoryByMajorClass($major_class_id, $p_config['per_page'], $offset, $field, $direction);
			}
			//get total number of rows
			$p_config['total_rows'] = $pag_data['pagination']['total_rows'];
			//selected inventory data
			$data['inventory_data'] = $pag_data['inventory'];

			//load page data (title, name, etc...)

				//Check to see if a title has been set
				$h2_title = 'The Archive ';
				if($major_class_id != null) {
					if($data['category_data']['major_class_title'] == '') {
						$h2_title .= '- ' . $data['category_data']['mjr_class_name'];
					}
					else {
						$h2_title .= '- ' .$data['category_data']['major_class_title'];
					}
				}
				else {

				}

				$page['field'] = $field;
				$page['h2'] = $h2_title;
				if($major_class_id != null) {
					$page['meta']['meta_description'] = $data['category_data']['meta_description'];
					$page['page_title'] = 'The Archive - ' . $data['category_data']['mjr_class_name'];
					$page['page_paragraph'] = $data['category_data']['page_paragraph'];
					$page['breadcrumb'] =  anchor('the-archive','The Archive') . ' &gt; ' . $data['category_data']['mjr_class_name'];
				}
				else {
					$page['meta']['meta_description'] = 'Celebrating Antique and Estate Jewelry through the ages in our Archive of previously sold jewelry, one is stunned by the exquisite beauty of finely crafted Antique and Estate Jewels.';
					$page['page_title'] = 'The Archive ';
					$page['page_paragraph'] = '';
					$page['breadcrumb'] =  'The Archive';

				}
				if($offset != null) {
					if($major_class_id != null) {
						$page['meta']['meta_description'] = $data['category_data']['meta_description'] . ' Items ' . $offset . ' of ' . $p_config['total_rows'];
						$page['page_title'] = $data['category_data']['mjr_class_name'] . ' - Items ' . $offset . ' of ' . $p_config['total_rows'];
					}
					else {
						$page['meta']['meta_description'] = 'The Archive ' . ' Items ' . $offset . ' of ' . $p_config['total_rows'];
						$page['page_title'] = 'The Archive ' . ' - Items ' . $offset . ' of ' . $p_config['total_rows'];
					}
				}
				$page['total_rows'] = $p_config['total_rows'];
				//$page['all_url'] = base_url() . $this->config->item('index_page') . '/products/category/' . $string . '/' . $direction . '/all';
				$page['all_url'] = base_url() . 'the-archive/' . $string . '/' . $field . '/' . $direction . '/all';
				$page['direction'] = $direction;
				$page['offset'] = $offset;
				$page['page_paragraph'] .= '<br /> We maintain the Archive for research purposes only. These items have been sold and the sales price is <strong>confidential</strong>. Please click <a href="http://www.langantiques.com">here</a> to go to our home page to search our current collection.';

			$data['page_data'] = $page;
			$data['page_data']['offset'] = null;
			//initialize pagination
			$this->my_pagination->initialize($p_config);
			$data['page_data']['pagination'] = $this->my_pagination->create_links();
			if($offset == 'all') {
				$data['page_data']['pagination'] = null;
			}
			$this->load->view('products/products_list_view', $data);
		}
		else {
			redirect('error/page-not-found', 'refresh');
		}
	}

	/**
	 * Attempts to find the id of a major class via
	 * a string
	 *
	 * @param [string] $string = major class name;
	 *
	 * @return [int|null] id = id of the major class
	 */
	private function _find_major_class($string = null) {
		$id = null;
		if($string != null) {
			foreach($this->categories as $category) {
				if($string == $category['element_url_name']) {
					$id = $category['mjr_class_id'];
					break;
				}
			}
		}
		return $id;
	}

	/**
	 * Attempts to find the id of a modifier
	 * via a string.
	 *
	 * @param [string] $string modifier name
	 *
	 * @return [int|null] id = id of modifier
	 */
	private function _find_modifier($string = null) {
		$id = null;
		foreach($this->types as $type) {
			if($string == $type['element_url_name']) {
				$id = $type['modifier_id'];
				break;
			}
		}
		return $id;
	}
}

?>