<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Written by theFuzzyOne
 * 
 * See Forum Thread: http://codeigniter.com/forums/viewthread/115028/
 * Extendable input class
 * 
 * @author TheFuzzyOne
 *
 */
class MY_Input extends CI_Input {
       
    function _fetch_from_array(&$array, $index = '', $xss_clean = FALSE) {
        if ($index === '') {
            $arr = array();
            
            foreach ($array as $key => $val) {
                $arr[$key] = ($xss_clean === TRUE && ! $this->use_xss_clean) ? $this->xss_clean($val) : $val;
            }
            
            return $arr;
        }
        
        else if (is_array($index)) {
            $arr = array();
            
            foreach ($index as $key) {
                if (isset($array[$key])) {
                    $arr[$key] = ($xss_clean === TRUE && ! $this->use_xss_clean) ? $this->xss_clean($array[$key]) : $array[$key];
                }
                else {
                    $arr[$key] = FALSE;
                }
            }
            
            return $arr;
        }
        
        else if ( ! isset($array[$index])) {
            return FALSE;
        }
        
        if ($xss_clean === TRUE) {
            return $this->xss_clean($array[$index]);
        }

        return $array[$index];
    }
}

/* End of file MY_Input.php */
/* Location: ./application/libraries/MY_Input.php */ 
?>