<?php
/**
 * Customer Menu Library 
 * 
 * This creates the side menus based on what 
 * exists in the database.
 * 
 * See Mango for details
 * 
 * 
 * @author user
 *
 */
class My_menu {
	
	var $ci;
	var $main_menu = array(); //currently hardcoded @TODO make dynamic
	var $secondary_menu = array(); //currently hardcoded
	var $url = array(); //url array
		
	function __construct() {
		$this->ci =& get_instance();
		$this->ci->load->model('menu/menu_model');
		/*
		 * Will have to change this logic when creating
		 * a dynamic menu system.
		 */
		//gets all Main menu elements
		$this->main_menu = $this->getMainMenuElements();
		//gets all Secondary menu elements 
		$this->secondary_menu = $this->getSecondaryMenuElements(2);
		
		//check the current uri segement
		if($this->ci->uri->segment(1) == null) {
		  $this->url['current'] = array();
		    $this->url['current']['url'] = null;
		    $this->url['current']['id'] = null;
		    $session = array();
		      $session['parent_id'] = null;
		    $this->ci->session->set_userdata($session);
		}
        else if($this->ci->uri->segment(2) == null) {
			//if null, then we're not within a category or type (probably at the archive).
			//we need to construct the current url
			$this->url['current'] = $this->constructCurrentUrlData($this->ci->uri->segment(1));
			
		}
		else if($this->ci->uri->segment(3) == null) {
			//if null, then we're not within a category or type.
			//we need to construct the current url
			$this->url['current'] = $this->constructCurrentUrlData($this->ci->uri->segment(1) . '/'. $this->ci->uri->segment(2));
		}
		else {
			//else, we're probably in some section.
			//but which sestion?
			if($this->ci->uri->segment(1) == 'the-archive' ) {
			   $this->url['current'] = $this->constructCurrentUrlData($this->ci->uri->segment(1) . '/'. $this->ci->uri->segment(2));
			}
			else {
			  $this->url['current'] = $this->constructCurrentUrlData($this->ci->uri->segment(1) . '/'. $this->ci->uri->segment(2) . '/' . $this->ci->uri->segment(3));
			}
		}
	}

	/**
	 * Constructs an array based on the current URL
	 * 
	 * URLs look like: product/category/diamond-rings
	 * OR: product/type/edwardian-jewelry
	 * 
	 * 
	 * 
	 * @param [string] $url = string
	 */
	public function constructCurrentUrlData($url) {
		$data = array();
		$data['url'] = $url;
		$data['id'] = $this->getCurrentURLId($url);
		return $data;
	} //end constructCurrentUrlData();
	
	/**
	 * Gets the current id of the parent 
	 * 
	 * @param [string] $url
	 * 
	 * @return [int] = menu id;
	 */
	public function getCurrentURLId($url) {
		$id = null;
		$data = array();
		$data = $this->ci->menu_model->getParentDataFromParentURL($url);
		if(sizeof($data) > 0) {
			$id = $data['element_id'];
			$session = array();
				$session['parent_id'] = $id;
			$this->ci->session->set_userdata($session);
		}
		else if($url == '/') { //close all memus when at home '/'
			$session = array();
				$session['parent_id'] = $id;
			$this->ci->session->set_userdata($session);
		}
		return $id; //int
		
	} //end getCurrentURLId();
	
	/**
	 * Gets all of the Main Menu Elements
	 * 
	 * @return [array] = array of menu elememnts;
	 */
	public function getMainMenuElements() {
		return $this->ci->menu_model->getMenuElements();
	} //end getMainMenuElements();
	
	/**
	 * Gets all of the Secondary Menu Elements
	 * 
	 * @param [int] $menu_id = id of menu
	 */
	public function getSecondaryMenuElements($menu_id) {
		return $this->ci->menu_model->getMenuElements($menu_id); //secondary menu
	} //end getSecondaryMenuElements();
	
} //end My_menu();
?>