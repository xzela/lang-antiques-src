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
| 	example.com/class/method/id/
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

//########
//Product Routes
//########
$route['products/email-to-friend/(:any)'] = 'products/email_to_friend/$1';
$route['products/add-to-cart/(:any)'] = 'products/add_to_cart/$1';


//########
//Search Routes
//########
$route['search'] = 'search/index';
$route['search/quick-search'] = 'search/quick_search';
$route['search/quick-search/(:any)'] = 'search/quick_search/$1';

//must be the last rule
$route['the-archive'] = 'archive';
$route['the-archive/(:any)'] = 'archive/index/$1';
$route['search/(:any)'] = 'search/index/$1';


/*
$route['search/all-rings'] = 'search/all_rings/';
$route['search/all-rings/(:any)'] = 'search/all_rings/$1';

$route['search/the-archive'] = 'search/the_archive/';
$route['search/the-archive/(:any)'] = 'search/the_archive/$1';

$route['search/whats-new'] = 'search/whats_new/';
$route['search/whats-new/(:any)'] = 'search/whats_new/$1';
*/
//########
//Shopping Routes
//########
$route['shopping/view-cart'] = 'shopping/view_cart/';
$route['shopping/check-out'] = 'shopping/check_out/';
$route['shopping/thank-you'] = 'shopping/thank_you/';

//########
//Page Could Not Be Found
//########
$route['pages/page-could-not-be-found'] = 'pages/page_not_found/';

//########
//User Pages
//########
$route['user/add-favorite'] = 'user/favorite_add';
$route['user/add-note/(:any)'] = 'user/note_add/$1';
$route['user/create-account'] = 'user/create_account';
$route['user/edit-contact'] = 'user/contact_edit';
$route['user/edit-mailing-address'] = 'user/mailing_edit';
$route['user/edit-shipping-address'] = 'user/shipping_edit';
$route['user/forgot-password'] = 'user/forgot_password';
$route['user/reset-password/(:any)'] = 'user/reset_password/$1';
$route['user/remove-favorite'] = 'user/favorite_remove';
$route['user/remove-note'] = 'user/note_remove';
$route['user/share-with-friends'] = 'user/favorites_share';
$route['user/user-account'] = 'user/user_account';

//########
//Pages, static pages
//#######
$route['pages/selling-your-jewelry'] = 'pages/selling_your_jewelry/';
$route['pages/contact-us'] = 'pages/contact_us/';
$route['pages/decorative-periods'] = 'pages/decorative_periods/';
$route['pages/decorative-periods/(:any)'] = 'pages/decorative_periods/$1';
$route['pages/introduction-to-diamonds'] = 'pages/intro_diamonds/';

$route['pages/introduction-to-gemstones'] = 'pages/intro_gemstones/';
$route['pages/gemstone-information/(:any)'] = 'pages/gemstone_information/$1';
$route['pages/we-recycle-gemstones'] = 'pages/gemstone_recycle/';

$route['pages/jewelry-care'] = 'pages/jewelry_care/';
$route['pages/shipping-policies'] = 'pages/shipping_policies/';
$route['pages/greeting-cards'] = 'pages/greeting_cards/';
$route['pages/our-store'] = 'pages/our_store/';
$route['pages/our-friendly-staff'] = 'pages/our_friendly_staff/';
$route['pages/estimate-form'] = 'pages/estimate_form';

$route['error/page-not-found'] = 'error/page_not_found/';


/* End of file routes.php */
/* Location: ./system/application/config/routes.php */