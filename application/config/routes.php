<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller']            		= 'shop/pageProducts';
$route['404_override']                  		= 'backend/error_404';
$route['translate_uri_dashes']          		= FALSE;

// Frontend Routes
// ---------------------------------------------------------
$route['home']                      			  = "frontend";
$route['aboutus']                  			    = "frontend/aboutus";
$route['product']                  			    = "frontend/product";
$route['register/(:any)']						        = "frontend/register/$1";
$route['company-profile']           			  = "frontend/pageCompanyProfile";
$route['contact-us']                			  = "frontend/pageContactUs";

// Auth Routes
// ---------------------------------------------------------
$route['login']                         		= "auth/login";
$route['logout']                        		= "auth/logout";
$route['capt'] 									            = "captcha";

// Page Routes
// ---------------------------------------------------------
$route['dashboard']                     		= "backend";
$route['profile']                     			= "backend/profile";
$route['profile/(:any)']						        = "backend/profile/$1";
$route['activation']                      	= "backend/personalactivation";
$route['transfer']                      		= "backend/transferproduct";

// Member Page Routes
// ---------------------------------------------------------
$route['member/new']							          = "backend/membernew";
$route['member/lists']                      = "backend/memberlist";
$route['member/generation']                 = "backend/membergeneration";
$route['member/generation/(:any)'] 				  = "backend/membergeneration/$1";
$route['member/generationtree']             = "backend/membergenerationtree";

// Product Manage Page Routes
// ---------------------------------------------------------
$route['productmanage/productnew'] 				  = "backend/productnew";
$route['productmanage/productedit/(:any)'] 	= "backend/productedit/$1";
$route['productmanage/packagenew'] 				  = "backend/packagenew";
$route['productmanage/packageedit/(:any)'] 	= "backend/packageedit/$1";
$route['productmanage/productlist'] 			  = "backend/productlist";
$route['productmanage/packagelist'] 			  = "backend/packagelist";
$route['productmanage/categorylist'] 			  = "backend/categorylist";
$route['productmanage/productpoint'] 			  = "backend/productpoint";

// Promo Code Page Routes
// ---------------------------------------------------------
$route['promocode/global'] 						      = "backend/promocodeglobal";
$route['promocode/spesific'] 					      = "backend/promocodespesific";
$route['promocode/promogloballistsdata'] 		= "setting/promocodelistdata";
$route['promocode/promogloballistsdata/(:any)'] = "setting/promocodelistdata/$1";
$route['promocode/savepromocode'] 				  = "setting/savepromocode";
$route['promocode/savepromocode/(:any)'] 		= "setting/savepromocode/$1";
$route['promocode/promocodestatus/(:any)'] 	= "setting/promocodestatus/$1";
$route['promocode/checkcode'] 					    = "setting/checkpromocode";

// Commission Page Routes
// ---------------------------------------------------------
$route['commission/bonus'] 						      = "backend/bonus";
$route['commission/bonus/(:any)'] 				  = "backend/bonus/$1";
$route['commission/deposite'] 					    = "backend/deposite";
$route['commission/deposite/(:any)'] 			  = "backend/deposite/$1";
$route['commission/withdraw'] 					    = "backend/withdraw";

// Report Page Routes
// ---------------------------------------------------------
$route['report/registration'] 					    = "backend/registration";
$route['report/sales'] 							        = "backend/sales";
$route['report/order'] 							        = "backend/sales";
$route['report/ordercustomer'] 					    = "backend/salescustomer";
$route['report/omzet'] 							        = "backend/omzet";
$route['report/reward'] 						        = "backend/reward";
$route['report/productactive'] 					    = "backend/productactive";
$route['report/product'] 					          = "backend/product";
$route['report/product/(:any)'] 				    = "backend/product/$1";
$route['report/personalomzet'] 					    = "backend/omzetpersonal";

// Setting
// ---------------------------------------------------------
$route['setting/staff']                 		= "staff/index";
$route['switchlang'] 							          = "backend/switchlang";
$route['switchlang/(:any)']						      = "backend/switchlang/$1";

// Staff Routes
// ---------------------------------------------------------
$route['staff/new']                         = "staff/formstaff";

// Shop Routes
// ---------------------------------------------------------
$route['search']                    			  = "shop/pageSearchProduct";
$route['packageproduct/detail/(:any)'] 			= "shop/pagePackageProductDetail/$1";
$route['product/detail/(:any)']     			  = "shop/pageProductDetail/$1";
$route['check-order']               			  = "shop/pageCheckOrder";
$route['about-us-shop']                     = "shop/pageAboutUs";
$route['shop']                      			  = "shop/pageProducts";
$route['cart']                      			  = "shop/pageCart";
$route['find-agent/(:any)']        				  = "shop/pageFindSeller/$1";
$route['checkout']                  			  = "shop/pageCheckout";
$route['invoice/(:any)']            			  = "shop/pageInvoice/$1";
$route['invoicecustomer/(:any)'] 				    = "shop/pageInvoiceCustomer/$1";
$route['confirm/payment/(:any)']    			  = "shop/pageConfirmPayment/$1";
$route['confirm/paymentcustomer/(:any)'] 		= "shop/pageConfirmPaymentCustomer/$1";
