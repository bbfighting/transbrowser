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
|	http://codeigniter.com/user_guide/general/routing.html
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
$route['default_controller'] = 'pages/home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
//$route['entrez'] = 'pages/entrez';

$route['entrez/search/(:any)/(:any)'] = 'pages/entrez/search/$1/$2';
$route['entrez/result/(:any)'] = 'pages/entrez/result/$1';
$route['getseq/(:any)'] = 'pages/getseq/$1';
$route['(:any)/plot/(:any)'] = 'pages/$1/plot/$2';
// $route['entrez/plot/(:any)'] = 'pages/entrez/plot/$1';
$route['plot/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'pages/plot/$1/$2/$3/$4/$5/$6';
$route['plotseq/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'pages/plotseq/$1/$2/$3/$4/$5';
//$route['plot/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'pages/plot/$1/$2/$3/$4/$5';
//$route['plotseq/(:any)/(:any)/(:any)/(:any)'] = 'pages/plotseq/$1/$2/$3/$4';
$route['(:any)'] = 'pages/$1';

$route['search/result/(:any)/(:any)'] = 'pages/search/result/$1/$2';
$route['search/plot/(:any)/(:any)'] = 'pages/search/plot/$1/$2';
$route['getseq/(:any)/(:any)'] = 'pages/getseq/$1/$2';
$route['browse/(:any)/(:any)'] = 'pages/browse/$1/$2';