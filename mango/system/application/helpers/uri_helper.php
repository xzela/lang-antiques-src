<?php
// ------------------------------------------------------------------------
// referrer_segment_array
// http://codeigniter.com/forums/viewthread/164331/

if ( ! function_exists('referrer_segment_array'))
{
  function referrer_segment_array(){
    if(!isset($_SERVER['HTTP_REFERER']))
      return false;

    $baseLength = strlen(base_url());

    $segs = explode('/', substr($_SERVER['HTTP_REFERER'], $baseLength));

    if(is_array($segs)){
      if($segs[0] == 'index.php'){
        unset($segs[0]);
        $emptyArray = array();
        return array_merge($emptyArray, $segs);
      }else
        return $segs;
    }else
      return false;
  }
}

// ------------------------------------------------------------------------

// ------------------------------------------------------------------------
// referrer_uri_string

if ( ! function_exists('referrer_uri_string'))
{
  function referrer_uri_string(){
    
    $segs = referrer_segment_array();

    if(is_array($segs))
      return implode('/', $segs);
    else
      return false;
  }
}

// ------------------------------------------------------------------------  

?>