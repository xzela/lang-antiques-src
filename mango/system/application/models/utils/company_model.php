<?php
/**
 * The Company Model updates specific fields about
 * the Company, this inforamtion is used on print-outs
 * reports, and other areas...
 * 
 * @author user
 *
 */
class Company_model extends Model {
	
	public $ci;
	
	public function __construct() {
		parent::Model();
		$this->load->database();
		$this->ci =& get_instance();
	}
	
	/**
	 * Returns the Current Company Information
	 * 
	 * @return [array] = array of company data, 
	 */
	public function getCompanyInformation() {
		$this->db->from('company_info');
		$this->db->where('company_id', 1); //should only be one
		
		$data = array();
		
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row;
			}
		}
		return $data; //array
	} //end getCompanyInformation();
	
	/**
	 * Returns the Company Logo
	 * 
	 * @return [array] = array of image information
	 */
	public function getCompanyLogo() {
		$this->db->from('company_image');
		$query = $this->db->get();
		$data = array();
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row;
			}
		}
		
		return $data; //array
	} //end getCompanyLogo();
	
	/**
	 * Updates the Company Information
	 * 
	 * @param [array] $fields = array of column and values
	 * 
	 * @return null;
	 */
	public function updateCompanyInformation($fields) {
		$this->db->where('company_id', 1);
		$this->db->update('company_info', $fields);
		
		return null;
	} //end updateCompanyInformation();
	
	/**
	 * Uploads image information into the database
	 * 
	 * @param [array] $fields = array of column and values
	 * 
	 * @return null;
	 */
	public function uploadCompanyLogo($fields) {
		$this->db->insert('company_image', $fields);
		
		return null;
	} //end uploadCompanyLogo();

} //end Company_model();