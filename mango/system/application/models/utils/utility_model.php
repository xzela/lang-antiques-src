<?php
class Utility_model extends Model {
	
	var $ci;
	
	function __construct() {
		parent::Model();
	}
	
	function createDateRangeArray($strDateFrom, $strDateTo) {
		// takes two dates formatted as YYYY-MM-DD and creates an
		// inclusive array of the dates between the from and to dates.
		// could test validity of dates here but I'm already doing
		// that in the main script
		$aryRange = array();
		
		//$iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
		$iDateFrom = strtotime($strDateFrom);
		//$iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));
		$iDateTo = strtotime($strDateTo);
		if($iDateTo >= $iDateFrom) {
			array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry
			while($iDateFrom < $iDateTo) {
				$iDateFrom += 86400; // add 24 hours
				array_push($aryRange, date('Y-m-d', $iDateFrom));
			}
		}
		return $aryRange;
	}
	
	function processDates($month, $year) {
		$data = array();
		$data['start_date'] = $year . '/' . $month . '/1';
		$data['end_date'] = $year . '/' . $month . '/' . idate('d', mktime(0, 0, 0, ($month +1 ), 0, $year));
		
		return $data;
	}
	
	function processYears($month1, $year1, $month2, $year2) {
		$data = array();
		$data['start_date'] = $year1 . '/' . $month1 . '/01';
		$data['end_date'] = $year2 . '/' . $month2 . '/01';
		
		return $data;
	}	
}
?>