<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
| 	www.your-site.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['scaffolding_trigger'] = 'scaffolding';
|
| This route lets you set a "secret" word that will trigger the
| scaffolding feature for added security. Note: Scaffolding must be
| enabled in the controller in which you intend to use it.   The reserved 
| routes must come before any wildcard or regular expression routes.
|
*/

$route['default_controller'] = "main";
$route['scaffolding_trigger'] = "";

$route['main/(:any)'] = 'main/$1';

$route['inventory/gemstone/(:any)'] = 'gemstone/gemstones/$1';
$route['inventory/diamond/(:any)'] = 'gemstone/diamond/$1';
$route['inventory/pearl/(:any)'] = 'gemstone/pearl/$1';
$route['inventory/jadeite/(:any)'] = 'gemstone/jadeite/$1';
$route['inventory/opal/(:any)'] = 'gemstone/opal/$1';

$route['admin/major_class_(:any)'] = 'major_class/major_class_$1'; //see controllers/major_class.php for more detials
$route['admin/minor_class_(:any)'] = 'minor_class/minor_class_$1'; //see controllers/minor_class.php for more detials
$route['admin/modifier_(:any)'] = 'modifier/modifier_$1'; //see controllers/modifier.php for more details
$route['admin/material_(:any)'] = 'material/material_$1'; //see controllers/material.php for more details
$route['admin/user_(:any)'] = 'user/user_$1'; //see controllers/user.php for more detials
$route['admin/stone_(:any)'] = 'gemstone/stone_$1'; //see controllers/gemstone.php for more detials
$route['admin/cuts_(:any)'] = 'gemstone/cuts_$1'; //see controllers/gemstone.php for more detials
$route['admin/diamond_(:any)'] = 'gemstone/diamond_$1'; //see controllers/gemstone.php for more detials

$route['admin/menu_(:any)'] = 'website/menu_$1'; //see controllers/website.php for more detials
$route['admin/content_(:any)'] = 'website/content_$1'; 

$route['admin/customer_(:any)'] = 'customer/customer_$1'; //see controllers/customer.php for more detials
$route['admin/vendor_(:any)'] = 'vendor/vendor_$1'; //see controllers/vendor.php for more detials
$route['admin/workshop_(:any)'] = 'workshop/workshop_$1'; //see controllers/workshop.php for more detials

$route['wormsinmybraingetthemout/(:any)'] = 'admin/PV_recall_history/$1'; //backup controller

/* End of file routes.php */
/* Location: ./system/application/config/routes.php */