<?php
class Photographer extends Controller {
	
	function __construct() {
		parent::Controller();
		//Check The user to see if they are logged in
		$this->load->library('authorize');
		$this->authorize->isLoggedIn();
		
	}
	
	function index() {
		$this->authorize->saveLastURL(); //saves the url
		
		$data['user_data'] = $this->authorize->getSessionData();
		$this->load->view('photographer/photographer_view', $data);
	}
	



	
	
	

	
	function list_edited($sort = 'item_number', $direction = 'asc') {
		$this->authorize->saveLastURL(); //saves the url
		
		$this->load->model('photographer/photographer_model');
		$this->load->library('pagination');
		$this->load->library('ajax');
				
		if ($this->uri->total_segments() <= 2) {
			$offset = 0;
		}
		else {
			$offset = $this->uri->segment(5, 0);
		}
		
		$config['per_page'] = '20'; //items per page
		$config['cur_page'] = $offset;
		
		$data['type'] = 1;
		$data['items'] = $this->photographer_model->getPhotographQueue($config['per_page'], $offset, $data['type'], $sort, $direction);
		
		$config['base_url'] =  '/mango/photographer/list_edited/' . $sort . '/' . $direction . '/';
		$config['total_rows'] = $data['items']['num_rows'];
		 
		$this->pagination->initialize($config);
		$data['user_data'] = $this->authorize->getSessionData();
		$data['pagination'] = $this->pagination->create_links(); //load pagination links	
		
		
		$this->load->view('photographer/photographer_list_view', $data); //load view	
	}
	
	function list_edited_only_photo($sort = 'item_number', $direction = 'asc') {
		$this->authorize->saveLastURL(); //saves the url
		
		$this->load->model('photographer/photographer_model');
		$this->load->library('pagination');
		$this->load->library('ajax');
				
		if ($this->uri->total_segments() <= 2) {
			$offset = 0;
		}
		else {
			$offset = $this->uri->segment(5, 0);
		}
		
		
		$config['per_page'] = '20'; //items per page
		$config['cur_page'] = $offset;
		$data['type'] = 2;
		
		$data['items'] = $this->photographer_model->getPhotographQueue($config['per_page'], $offset, $data['type'], $sort, $direction);	
		
		$config['base_url'] =  "/mango/photographer/list_edited_only_photo/". $sort . '/' . $direction . '/';
		$config['total_rows'] = $data['items']['num_rows'];
		 
		//initializing stuff
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links(); //load pagination links		
		$data['user_data'] = $this->authorize->getSessionData();
		
		$this->load->view('photographer/photographer_list_view', $data); //load view			
	}
	
	//@todo Need to figure out a way to merge all of these search functions into one function
	function list_photographed($sort = 'item_number', $direction = 'asc') {
		$this->authorize->saveLastURL(); //saves the url
		
		$this->load->model('photographer/photographer_model');
		$this->load->library('pagination');
		
		if ($this->uri->total_segments() <= 2) {
			$offset = 0;
		}
		else {
			$offset = $this->uri->segment(5, 0);
		}
		
		$config['per_page'] = '20'; //items per page
		$config['cur_page'] = $offset;
		$data['type'] = 0; //
		
		$data['items'] = $this->photographer_model->getPhotographQueue($config['per_page'], $offset, $data['type'], $sort, $direction);
		
		
		$config['base_url'] =  '/mango/photographer/list_photographed/' . $sort . '/' . $direction . '/';
		$config['total_rows'] = $data['items']['num_rows'];
		 
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links(); //load pagination links		
		$data['user_data'] = $this->authorize->getSessionData();
		
		$this->load->view('photographer/photographer_list_view', $data); //load view		
	}	
	
	/**
	 * Called from a Ajax Request to update the items edit status (photographer stuff)
	 *
	 * @param [int] $id = item id
	 * @param [int] $status = edit status {0=no_status,1=enqueued,2=edited}
	 * 
	 * @return null
	 */
	function AJAX_updateEditStatus($id, $status) {
		$this->load->model('photographer/photographer_model');
		$this->photographer_model->updateQueueStatus($id, $status);
	}
	
	function AJAX_updatePhotoSeq() {
		$this->load->model('photographer/photographer_model');
		
		$id = $this->input->post('item_id'); 
		$order = explode(',', $this->input->post('order'));
		
		$this->photographer_model->updatePhotoSeq($id, $order);
		
	}
	
	/**
	 * Called from a Ajax Request to update that photograph status (photorapher stuff)
	 *
	 * @param [int] $id = item id
	 * @param [int] $status = photo status {0=no_status,1=enqueue,2=photographed}
	 * 
	 * @return null 
	 */
	function AJAX_updatePhotoStatus() {
		$this->load->model('photographer/photographer_model');
		
		$id = $this->input->post('item_id');
		$value = $this->input->post('value');
		$column = $this->input->post('column');
		
		return $this->photographer_model->updateQueueStatus($id, $value, $column);
	}
}
?>