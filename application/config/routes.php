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
$route['default_controller'] = 'login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//Add Page Specific Routes and Controller Routes for Grocery CRUD
$route['user'] = 'user';

//Routes for People Page
$route['user/people'] = 'people';
$route['user/people/index'] = 'people';
$route['user/people/index/add'] = 'people/all_people';
$route['user/people/index/insert'] = 'people/all_people';
$route['user/people/index/insert_validation'] = 'people/all_people';
$route['user/people/index/edit/(:num)'] = 'people/all_people/$1';
$route['user/people/index/update/(:num)'] = 'people/all_people/$1';
$route['user/people/index/update_validation/(:num)'] = 'people/all_people/$1';
$route['user/people/index/success/(:num)'] = 'people/all_people/$1';
$route['user/people/index/read/(:num)'] = 'people/all_people/$1';
$route['user/people/index/delete/(:num)'] = 'people/all_people/$1';
$route['user/people/index/print'] = 'people/all_people';
$route['user/people/index/ajax_list'] = 'people/all_people';
$route['user/people/index/ajax_list_info'] = 'people/all_people';
$route['user/people/index/export'] = 'people/all_people';

//Routes for Volunteer Page
$route['user/volunteer'] = 'volunteer';
$route['user/volunteer/index'] = 'volunteer';
$route['user/volunteer/index/add'] = 'volunteer/volunteer';
$route['user/volunteer/index/insert'] = 'volunteer/pp_insert';
$route['user/volunteer/index/pp_insert'] = 'volunteer/pp_insert';
$route['user/volunteer/index/volunteer_add'] = 'volunteer/volunteer_add';
$route['user/volunteer/index/insert_validation'] = 'volunteer/volunteer';
$route['user/volunteer/index/edit/(:num)'] = 'volunteer/volunteer/$1';
$route['user/volunteer/index/update/(:num)'] = 'volunteer/volunteer/$1';
$route['user/volunteer/index/update_validation/(:num)'] = 'volunteer/volunteer/$1';
$route['user/volunteer/index/success/(:num)'] = 'volunteer/volunteer/$1';
$route['user/volunteer/index/read/(:num)'] = 'volunteer/volunteer/$1';
$route['user/volunteer/index/delete/(:num)'] = 'volunteer/volunteer/$1';
$route['user/volunteer/index/pp_delete/(:num)/(:num)'] = 'volunteer/pp_delete/$1/$2';
$route['user/volunteer/index/print'] = 'volunteer/volunteer';
$route['user/volunteer/index/ajax_list'] = 'volunteer/volunteer';
$route['user/volunteer/index/ajax_list_info'] = 'volunteer/volunteer';
$route['user/volunteer/index/export'] = 'volunteer/volunteer';

//Routes for Employee Page
$route['user/employee'] = 'employee';
$route['user/employee/index'] = 'employee';
$route['user/employee/index/add'] = 'employee/employee';
$route['user/employee/index/edit/(:num)'] = 'employee/employee/$1';
$route['user/employee/index/update/(:num)'] = 'employee/employee/$1';
$route['user/employee/index/update_validation/(:num)'] = 'employee/employee/$1';
$route['user/employee/index/success/(:num)'] = 'employee/employee/$1';
$route['user/employee/index/read/(:num)'] = 'employee/employee/$1';
$route['user/employee/index/delete/(:num)'] = 'employee/employee/$1';
$route['user/employee/index/print'] = 'employee/employee';
$route['user/employee/index/ajax_list'] = 'employee/employee';
$route['user/employee/index/ajax_list_info'] = 'employee/employee';
$route['user/employee/index/export'] = 'employee/employee';

//Routes for Project Page
$route['user/projects'] = 'projects';
$route['user/projects/index'] = 'projects';
$route['user/projects/index/add'] = 'projects/projects';
$route['user/projects/index/insert'] = 'projects/projects';
$route['user/projects/index/insert_validation'] = 'projects/projects';
$route['user/projects/index/edit/(:num)'] = 'projects/projects/$1';
$route['user/projects/index/update/(:num)'] = 'projects/projects/$1';
$route['user/projects/index/update_validation/(:num)'] = 'projects/projects/$1';
$route['user/projects/index/success/(:num)'] = 'projects/projects/$1';
$route['user/projects/index/read/(:num)'] = 'projects/projects/$1';
$route['user/projects/index/delete/(:num)'] = 'projects/projects/$1';
$route['user/projects/index/print'] = 'projects/projects';
$route['user/projects/index/ajax_list'] = 'projects/projects';
$route['user/projects/index/ajax_list_info'] = 'projects/projects';
$route['user/projects/index/export'] = 'projects/projects';

//Routes for Milestones Page
$route['user/milestones'] = 'milestones';
$route['user/milestones/index'] = 'milestones';
$route['user/milestones/index/add'] = 'milestones/milestones';
$route['user/milestones/index/insert'] = 'milestones/milestones';
$route['user/milestones/index/insert_validation'] = 'milestones/milestones';
$route['user/milestones/index/edit/(:num)'] = 'milestones/milestones/$1';
$route['user/milestones/index/update/(:num)'] = 'milestones/milestones/$1';
$route['user/milestones/index/update_validation/(:num)'] = 'milestones/milestones/$1';
$route['user/milestones/index/success/(:num)'] = 'milestones/milestones/$1';
$route['user/milestones/index/read/(:num)'] = 'milestones/milestones/$1';
$route['user/milestones/index/delete/(:num)'] = 'milestones/milestones/$1';
$route['user/milestones/index/print'] = 'milestones/milestones';
$route['user/milestones/index/ajax_list'] = 'milestones/milestones';
$route['user/milestones/index/ajax_list_info'] = 'milestones/milestones';
$route['user/milestones/index/export'] = 'milestones/milestones';

//Routes for expenditures Page
$route['user/expenditures'] = 'expenditures';
$route['user/expenditures/index'] = 'expenditures';
$route['user/expenditures/index/add'] = 'expenditures/expenditures';
$route['user/expenditures/index/insert'] = 'expenditures/expenditures';
$route['user/expenditures/index/insert_validation'] = 'expenditures/expenditures';
$route['user/expenditures/index/edit/(:num)'] = 'expenditures/expenditures/$1';
$route['user/expenditures/index/update/(:num)'] = 'expenditures/expenditures/$1';
$route['user/expenditures/index/update_validation/(:num)'] = 'expenditures/expenditures/$1';
$route['user/expenditures/index/success/(:num)'] = 'expenditures/expenditures/$1';
$route['user/expenditures/index/read/(:num)'] = 'expenditures/expenditures/$1';
$route['user/expenditures/index/delete/(:num)'] = 'expenditures/expenditures/$1';
$route['user/expenditures/index/print'] = 'expenditures/expenditures';
$route['user/expenditures/index/ajax_list'] = 'expenditures/expenditures';
$route['user/expenditures/index/ajax_list_info'] = 'expenditures/expenditures';
$route['user/expenditures/index/export'] = 'expenditures/expenditures';


//Routes for reimbursements Page
$route['user/reimbursements'] = 'reimbursements';
$route['user/reimbursements/index'] = 'reimbursements';
$route['user/reimbursements/index/add'] = 'reimbursements/reimbursements';
$route['user/reimbursements/index/insert'] = 'reimbursements/reimbursements';
$route['user/reimbursements/index/insert_validation'] = 'reimbursements/reimbursements';
$route['user/reimbursements/index/edit/(:num)'] = 'reimbursements/reimbursements/$1';
$route['user/reimbursements/index/update/(:num)'] = 'reimbursements/reimbursements/$1';
$route['user/reimbursements/index/update_validation/(:num)'] = 'reimbursements/reimbursements/$1';
$route['user/reimbursements/index/success/(:num)'] = 'reimbursements/reimbursements/$1';
$route['user/reimbursements/index/read/(:num)'] = 'reimbursements/reimbursements/$1';
$route['user/reimbursements/index/delete/(:num)'] = 'reimbursements/reimbursements/$1';
$route['user/reimbursements/index/print'] = 'reimbursements/reimbursements';
$route['user/reimbursements/index/ajax_list'] = 'reimbursements/reimbursements';
$route['user/reimbursements/index/ajax_list_info'] = 'reimbursements/reimbursements';
$route['user/reimbursements/index/export'] = 'reimbursements/reimbursements';


//Routes for Adding Person to Project Page
$route['user/personproject'] = 'personproject';
$route['user/personproject/index'] = 'personproject';
$route['user/personproject/index/add'] = 'personproject/personproject';
$route['user/personproject/index/insert'] = 'personproject/personproject';
$route['user/personproject/index/insert_validation'] = 'personproject/personproject';
$route['user/personproject/index/edit/(:num)'] = 'personproject/personproject/$1';
$route['user/personproject/index/update/(:num)'] = 'personproject/personproject/$1';
$route['user/personproject/index/update_validation/(:num)'] = 'personproject/personproject/$1';
$route['user/personproject/index/success/(:num)'] = 'personproject/personproject/$1';
$route['user/personproject/index/read/(:num)'] = 'personproject/personproject/$1';
$route['user/personproject/index/delete/(:num)'] = 'personproject/personproject/$1';
$route['user/personproject/index/print'] = 'personproject/personproject';
$route['user/personproject/index/ajax_list'] = 'personproject/personproject';
$route['user/personproject/index/ajax_list_info'] = 'personproject/personproject';
$route['user/personproject/index/export'] = 'personproject/personproject';


//Routes for funding Page
$route['user/funding'] = 'funding';
$route['user/funding/index'] = 'funding';
$route['user/funding/index/add'] = 'funding/funding';
$route['user/funding/index/insert'] = 'funding/funding';
$route['user/funding/index/insert_validation'] = 'funding/funding';
$route['user/funding/index/edit/(:num)'] = 'funding/funding/$1';
$route['user/funding/index/update/(:num)'] = 'funding/funding/$1';
$route['user/funding/index/update_validation/(:num)'] = 'funding/funding/$1';
$route['user/funding/index/success/(:num)'] = 'funding/funding/$1';
$route['user/funding/index/read/(:num)'] = 'funding/funding/$1';
$route['user/funding/index/delete/(:num)'] = 'funding/funding/$1';
$route['user/funding/index/print'] = 'funding/funding';
$route['user/funding/index/ajax_list'] = 'funding/funding';
$route['user/funding/index/ajax_list_info'] = 'funding/funding';
$route['user/funding/index/export'] = 'funding/funding';

//Routes for reports Page
$route['user/reports'] = 'reports';
$route['user/reports/index'] = 'reports';
$route['user/reports/index/add'] = 'reports/reports';
$route['user/reports/index/insert'] = 'reports/reports';
$route['user/reports/index/insert_validation'] = 'reports/reports';
$route['user/reports/index/edit/(:num)'] = 'reports/reports/$1';
$route['user/reports/index/update/(:num)'] = 'reports/reports/$1';
$route['user/reports/index/update_validation/(:num)'] = 'reports/reports/$1';
$route['user/reports/index/success/(:num)'] = 'reports/reports/$1';
$route['user/reports/index/read/(:num)'] = 'reports/reports/$1';
$route['user/reports/index/delete/(:num)'] = 'reports/reports/$1';
$route['user/reports/index/print'] = 'reports/reports';
$route['user/reports/index/ajax_list'] = 'reports/reports';
$route['user/reports/index/ajax_list_info'] = 'reports/reports';
$route['user/reports/index/export'] = 'reports/reports';


//Routes for statistics Page
$route['user/statistics'] = 'statistics';
$route['user/statistics/index'] = 'statistics';
$route['user/statistics/index/add'] = 'statistics/statistics';
$route['user/statistics/index/insert'] = 'statistics/statistics';
$route['user/statistics/index/insert_validation'] = 'statistics/statistics';
$route['user/statistics/index/edit/(:num)'] = 'statistics/statistics/$1';
$route['user/statistics/index/update/(:num)'] = 'statistics/statistics/$1';
$route['user/statistics/index/update_validation/(:num)'] = 'statistics/statistics/$1';
$route['user/statistics/index/success/(:num)'] = 'statistics/statistics/$1';
$route['user/statistics/index/read/(:num)'] = 'statistics/statistics/$1';
$route['user/statistics/index/delete/(:num)'] = 'statistics/statistics/$1';
$route['user/statistics/index/print'] = 'statistics/statistics';
$route['user/statistics/index/ajax_list'] = 'statistics/statistics';
$route['user/statistics/index/ajax_list_info'] = 'statistics/statistics';
$route['user/statistics/index/export'] = 'statistics/statistics';
