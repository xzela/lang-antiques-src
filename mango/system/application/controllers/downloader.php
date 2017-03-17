<?php
class Downloader extends Controller {
	
	var $ci;
	
	public function __construct() {
		parent::Controller();
		$this->load->library('authorize');
		$this->authorize->isLoggedIn();
		$this->ci =& get_instance();
	}
	
	public function report_inventory_check() {

		$this->load->helper('form');
		$this->load->helper('file'); 
		$this->load->library('form_validation');
		$this->load->model('admin/major_class_model');
		$this->load->model('admin/minor_class_model');
		$this->load->model('reports/report_model');
		$this->load->model('utils/lookup_list_model');
		
		
		$data['user_data'] = $this->authorize->getSessionData();
		$data['report_data'] = $this->report_model->getMajorMinorClassReportData();
		$data['major_classes'] = $this->major_class_model->getMajorClasses();
		$data['minor_classes'] = $this->minor_class_model->getMinorClasses();
		$data['status'] = $this->lookup_list_model->getItemStatus();
		
		$this->form_validation->set_rules('major_class_id', 'Major Class', 'trim');
		$this->form_validation->set_rules('minor_class_id', 'Minor Class', 'trim');
		//print_r($data['status']);
		if($this->form_validation->run() == true) {
			//print_r($this->input->post());
			$fields['major_class_id'] = $this->input->post('major_class_id');
			$fields['minor_class_id'] = $this->input->post('minor_class_id');
			$fields['status'] = array();
			unset($_POST['major_class_id']);
			unset($_POST['minor_class_id']);
			
			if($fields['major_class_id'] == 'any' || $fields['major_class_id'] == '') {
				//show me every major class
				$fields['major_class_id'] = false;
			}
			if($fields['minor_class_id'] == 'any' || $fields['minor_class_id'] == '') {
				//show me every minor class
				$fields['minor_class_id'] = false;
			}
			
			if($this->input->post('everything') == 'on' || $this->input->post('everything') == 1) {
				//show everything
				$fields['everything'] = true;
			}
			else {
				foreach($data['status'] as $status) {
					if($this->input->post($status['field_name'])) {
						$fields['status'][] = (string)$status['id'];
						$data['status'][(string)$status['id']]['checked'] = true; 
					}
				}
				$fields['quantity'] = $this->input->post('quantity');
			}
			
			$data['fields'] = $fields;
			$data['report_data'] = $this->report_model->getInventoryCheckReportData($fields);
			
			$block = ''; //block of data
			$t_cost = 0;
			$t_retail = 0;
			$total_line = '';
			foreach($data['report_data'] as $row) {
				$t_cost += $row['purchase_price'];
				$t_retail += $row['item_price'];
				
				$line = '';
				$line .= '"' . str_replace('"', '"'.'"', $row['item_number']) . '"' . ',';
				$line .= '"' . str_replace('"', '"'.'"', $row['item_name']) . '"' . ',';
				$line .= '"' . str_replace('"', '"'.'"', $row['purchase_price']) . '"' . ',';
				$line .= '"' . str_replace('"', '"'.'"', $row['item_price']) . '"' . ',';
				$line .= '"' . str_replace('"', '"'.'"', $data['status'][$row['item_status']]['name']) . '"' . ',';
				if($row['item_status'] == 0) {
					
				}
				$line .= "\r\n";
				$block .= $line;
			}
			$total_line .= '"' . str_replace('"', '"'.'"', 'Total:') . '"' . ',';
			$total_line .= '"' . str_replace('"', '"'.'"', $t_cost) . '"' . ',';
			$total_line .= '"' . str_replace('"', '"'.'"', $t_retail) . '"' . ',';
			
			$block .= $total_line; 
				
			$name = 'inventory_check_report_' . date('Y.m.d') . '.csv';
			$path = './files/csv/' . $name;
			
			if(!write_file($path, $block)) {
				echo 'help!';
				//cound not write file for some reason
			}
			else {
	     		$file = read_file($path);
				header('Content-type: application/csv');
				header('Content-Disposition: attachment; filename="'. $name . '"');
	     		
				echo $file; //needed to return the file to the browser
			}			
		}			
	}
}
?>