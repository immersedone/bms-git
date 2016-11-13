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
$route['user/people/index/getPerName/(:num)'] = 'people/getPerName/$1';
$route['user/people/index/getSBName/(:num)'] = 'people/getSBName/$1';
$route['user/people/index/getPosition/(:num)'] = 'people/getPosition/$1';
$route['user/people/index/getRole/(:num)'] = 'people/getRole/$1';
$route['user/people/index/getLangName'] = 'people/getLangName';
$route['user/people/index/getDays'] = 'people/getDays';
$route['user/people/index/getNHACE'] = 'people/getNHACE';
$route['user/people/index/getBGCS'] = 'people/getBGCS';

//Routes for Volunteer Page
$route['user/volunteer'] = 'volunteer';
$route['user/volunteer/index'] = 'volunteer';
$route['user/volunteer/index/add'] = 'volunteer/volunteer';
$route['user/volunteer/index/insert'] = 'volunteer/volunteer';
$route['user/volunteer/index/insert_validation'] = 'volunteer/volunteer';
$route['user/volunteer/index/edit/(:num)'] = 'volunteer/volunteer/$1';
$route['user/volunteer/index/update/(:num)'] = 'volunteer/volunteer/$1';
$route['user/volunteer/index/update_validation/(:num)'] = 'volunteer/volunteer/$1';
$route['user/volunteer/index/pp_delete/(:num)/(:num)'] = 'volunteer/pp_delete/$1/$2';
$route['user/volunteer/index/success/(:num)'] = 'volunteer/volunteer/$1';
$route['user/volunteer/index/read/(:num)'] = 'volunteer/volunteer/$1';
$route['user/volunteer/index/delete/(:num)'] = 'volunteer/volunteer/$1';
$route['user/volunteer/index/print'] = 'volunteer/volunteer';
$route['user/volunteer/index/ajax_list'] = 'volunteer/volunteer';
$route['user/volunteer/index/ajax_list_info'] = 'volunteer/volunteer';
$route['user/volunteer/index/export'] = 'volunteer/volunteer';
//Routes for Project Volunteer
$route['user/volunteer/volproj/ajax_list/(:num)'] = 'volunteer/volproj/$1';
$route['user/volunteer/volproj/ajax_list_info/(:num)'] = 'volunteer/volproj/$1';
$route['user/volunteer/volproj/read/(:num)'] = 'volunteer/volproj/$1';
$route['user/volunteer/volproj/edit/(:num)'] = 'volunteer/volproj/$1';
$route['user/volunteer/volproj/insert_validation/(:num)'] = 'volunteer/volproj/$1';
$route['user/volunteer/volproj/insert/(:num)'] = 'volunteer/volproj/$1';
$route['user/volunteer/volproj/update/(:num)'] = 'volunteer/volproj/$1';
$route['user/volunteer/volproj/update_validation/(:num)'] = 'volunteer/volproj/$1';
$route['user/volunteer/volproj/success/(:num)'] = 'volunteer/volproj/$1';
$route['user/volunteer/volproj/export/(:num)'] = 'volunteer/volproj/$1';
$route['user/volunteer/volproj/add/(:num)'] = 'volunteer/volproj/$1';
$route['user/volunteer/volproj/print/(:num)'] = 'volunteer/volproj/$1';
$route['user/volunteer/volproj/delete/(:num)'] = 'volunteer/volproj/$1';

//Routes for Employee Page
$route['user/employee'] = 'employee';
$route['user/employee/index'] = 'employee';
$route['user/employee/index/add'] = 'employee/employee';
$route['user/employee/index/insert'] = 'employee/employee';
$route['user/employee/index/pp_insert'] = 'employee/pp_insert';
$route['user/employee/index/volunteer_add'] = 'employee/volunteer_add';
$route['user/employee/index/insert_validation'] = 'employee/employee';
$route['user/employee/index/pp_delete/(:num)/(:num)'] = 'employee/pp_delete/$1/$2';
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
//Route for Project Employee
$route['user/employee/empproj/ajax_list/(:num)'] = 'employee/empproj/$1';
$route['user/employee/empproj/ajax_list_info/(:num)'] = 'employee/empproj/$1';
$route['user/employee/empproj/read/(:num)'] = 'employee/empproj/$1';
$route['user/employee/empproj/edit/(:num)'] = 'employee/empproj/$1';
$route['user/employee/empproj/update_validation/(:num)'] = 'employee/empproj/$1';
$route['user/employee/empproj/insert_validation/(:num)'] = 'employee/empproj/$1';
$route['user/employee/empproj/add/(:num)'] = 'employee/empproj/$1';
$route['user/employee/empproj/insert/(:num)'] = 'employee/empproj/$1';
$route['user/employee/empproj/update/(:num)'] = 'employee/empproj/$1';
$route['user/employee/empproj/export/(:num)'] = 'employee/empproj/$1';
$route['user/employee/empproj/print/(:num)'] = 'employee/empproj/$1';
$route['user/employee/empproj/success/(:num)'] = 'employee/empproj/$1';
$route['user/employee/empproj/delete/(:num)'] = 'employee/empproj/$1';


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
$route['user/projects/index/setID/(:num)'] = 'projects/setID/$1';
$route['user/projects/index/projread/list'] = 'projects/projread';
$route['user/projects/index/projread/success'] = 'projects/projread';
$route['user/projects/index/projread/(:num)'] = 'projects/projread/$1';

$route['user/projects/index/projread/2/add/(:num)'] = 'milestones/mileproj/$1';
$route['user/projects/index/projread/2/insert'] = 'milestones/mile_insert';
$route['user/projects/index/projread/2/insert_validation'] = 'milestones/milestones';
$route['user/projects/index/projread/2/ajax_list_info'] = 'milestones/milestones';

$route['user/projects/index/projread/3/add/(:num)'] = 'expenditures/expendproj/$1';
$route['user/projects/index/projread/3/insert'] = 'expenditures/exp_insert';
$route['user/projects/index/projread/3/insert_validation'] = 'expenditures/expenditures';

$route['user/projects/index/projread/4/add/(:num)'] = 'funding/fundproj/$1';
$route['user/projects/index/projread/4/insert'] = 'funding/fd_insert';
$route['user/projects/index/projread/4/insert_validation'] = 'funding/funding';
$route['user/projects/index/projread/4/delete/(:num)'] = 'funding/fd_delete';

$route['user/projects/index/projread/5/add/(:num)'] = 'volunteer/volproj/$1';
$route['user/projects/index/projread/5/insert'] = 'volunteer/pp_insert';
$route['user/projects/index/projread/5/insert_validation'] = 'volunteer/volunteer';

$route['user/projects/index/projread/6/add/(:num)'] = 'employee/empproj/$1';
$route['user/projects/index/projread/6/insert'] = 'employee/pp_insert';
$route['user/projects/index/projread/6/insert_validation'] = 'employee/employee';
//$route['user/projects/index/projread'] = 'projects/projread';
$route['user/projects/index/read/(:num)'] = 'projects/projects/$1';
$route['user/projects/index/delete/(:num)'] = 'projects/projects/$1';
$route['user/projects/index/print'] = 'projects/projects';
$route['user/projects/index/ajax_list'] = 'projects/projects';
$route['user/projects/index/ajax_list_info'] = 'projects/projects';
$route['user/projects/index/export'] = 'projects/projects';
$route['user/projects/index/addProjID'] = 'projects/addProjID';
$route['user/projects/index/getProjName/(:num)'] = 'projects/getProjName/$1';

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
$route['user/milestones/index/upload_file/FilePath'] = 'milestones/milestones';
//Routes for Project Milestones
$route['user/milestones/mileproj/add/(:num)'] = 'milestones/mileproj/$1';
$route['user/milestones/mileproj/ajax_list/(:num)'] = 'milestones/mileproj/$1';
$route['user/milestones/mileproj/ajax_list_info/(:num)'] = 'milestones/mileproj/$1';
$route['user/milestones/mileproj/read/(:num)'] = 'milestones/mileproj/$1';
$route['user/milestones/mileproj/edit/(:num)'] = 'milestones/mileproj/$1';
$route['user/milestones/mileproj/update/(:num)'] = 'milestones/mileproj/$1';
$route['user/milestones/mileproj/update_validation/(:num)'] = 'milestones/mileproj/$1';
$route['user/milestones/mileproj/insert/(:num)'] = 'milestones/mileproj/$1';
$route['user/milestones/mileproj/insert_validation/(:num)'] = 'milestones/mileproj/$1';
$route['user/milestones/mileproj/export/(:num)'] = 'milestones/mileproj/$1';
$route['user/milestones/mileproj/print/(:num)'] = 'milestones/mileproj/$1';
$route['user/milestones/mileproj/success/(:num)'] = 'milestones/mileproj/$1';
$route['user/milestones/mileproj/delete/(:num)'] = 'milestones/mileproj/$1';

//Routes for Upcoming Milestones Page
$route['user/duemilestones'] = 'duemilestones';
$route['user/duemilestones/index'] = 'duemilestones';
$route['user/duemilestones/index/insert'] = 'duemilestones/duemilestones';
$route['user/duemilestones/index/insert_validation'] = 'duemilestones/duemilestones';
$route['user/duemilestones/index/edit/(:num)'] = 'duemilestones/duemilestones/$1';
$route['user/duemilestones/index/update/(:num)'] = 'duemilestones/duemilestones/$1';
$route['user/duemilestones/index/update_validation/(:num)'] = 'duemilestones/duemilestones/$1';
$route['user/duemilestones/index/success/(:num)'] = 'duemilestones/duemilestones/$1';
$route['user/duemilestones/index/read/(:num)'] = 'duemilestones/duemilestones/$1';
$route['user/duemilestones/index/delete/(:num)'] = 'duemilestones/duemilestones/$1';
$route['user/duemilestones/index/print'] = 'duemilestones/duemilestones';
$route['user/duemilestones/index/ajax_list'] = 'duemilestones/duemilestones';
$route['user/duemilestones/index/ajax_list_info'] = 'duemilestones/duemilestones';
$route['user/duemilestones/index/export'] = 'duemilestones/duemilestones';
$route['user/duemilestones/index/upload_file/FilePath'] = 'duemilestones/duemilestones';

//Routes for expenditures Page
$route['user/expenditures'] = 'expenditures';
$route['user/expenditures/index'] = 'expenditures';
$route['user/expenditures/index/add'] = 'expenditures/expenditures';
$route['user/expenditures/index/insert'] = 'reimbursements/exp_insert';
$route['user/expenditures/index/exp_insert'] = 'reimbursements/exp_insert';
$route['user/expenditures/index/expenditure_add'] = 'reimbursements/expenditure_add';
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
$route['user/expenditures/index/getExpBy/(:num)'] = 'expenditures/getExpBy/$1';
$route['user/expenditures/index/getExpName/(:num)'] = 'expenditures/getExpName/$1';
$route['user/expenditures/index/upload_file/FilePath'] = 'expenditures/expenditures';
$route['user/expenditures/index/delete_file/FilePath/(:any)'] = 'expenditures/expenditures/$1';
//Routes for Project Expenditures
$route['user/expenditures/expendproj/add/(:num)'] = 'expenditures/expendproj/$1';
$route['user/expenditures/expendproj/ajax_list_info/(:num)'] = 'expenditures/expendproj/$1';
$route['user/expenditures/expendproj/ajax_list/(:num)'] = 'expenditures/expendproj/$1';
$route['user/expenditures/expendproj/read/(:num)'] = 'expenditures/expendproj/$1';
$route['user/expenditures/expendproj/edit/(:num)'] = 'expenditures/expendproj/$1';
$route['user/expenditures/expendproj/insert/(:num)'] = 'expenditures/expendproj/$1';
$route['user/expenditures/expendproj/insert_validation/(:num)'] = 'expenditures/expendproj/$1';
$route['user/expenditures/expendproj/update/(:num)'] = 'expenditures/expendproj/$1';
$route['user/expenditures/expendproj/update_validation/(:num)'] = 'expenditures/expendproj/$1';
$route['user/expenditures/expendproj/export/(:num)'] = 'expenditures/expendproj/$1';
$route['user/expenditures/expendproj/print/(:num)'] = 'expenditures/expendproj/$1';
$route['user/expenditures/expendproj/success/(:num)'] = 'expenditures/expendproj/$1';
$route['user/expenditures/expendproj/delete/(:num)'] = 'expenditures/expendproj/$1';
$route['user/expenditures/expendproj/delete_file/FilePath/(:any)'] = 'expenditures/expendproj/$1';
$route['user/expenditures/expendproj/upload_file/FilePath/'] = 'expenditures/expendproj';


//Routes for reimbursements Page
$route['user/reimbursements'] = 'reimbursements';
$route['user/reimbursements/index'] = 'reimbursements';
$route['user/reimbursements/index/add'] = 'reimbursements/reimbursements';
$route['user/reimbursements/index/insert'] = 'reimbursements/reimb_insert';
$route['user/reimbursements/index/reimb_insert'] = 'reimbursements/reimb_insert';
$route['user/reimbursements/index/reimbursement_add'] = 'reimbursements/reimbursement_add';
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
$route['user/funding/index/insert'] = 'funding/fd_insert';
$route['user/funding/index/fd_insert'] = 'funding/fd_insert';
$route['user/funding/index/fd_delete/(:num)'] = 'funding/fd_delete/$1';
$route['user/funding/index/fd_update/(:num)'] = 'funding/fd_update/$1';
$route['user/funding/index/insert_validation'] = 'funding/funding';
$route['user/funding/index/edit/(:num)'] = 'funding/funding/$1';
$route['user/funding/index/update/(:num)'] = 'funding/fd_update/$1';
$route['user/funding/index/update_validation/(:num)'] = 'funding/funding/$1';
$route['user/funding/index/success/(:num)'] = 'funding/funding/$1';
$route['user/funding/index/read/(:num)'] = 'funding/funding/$1';
$route['user/funding/index/delete/(:num)'] = 'funding/funding/$1';
$route['user/funding/index/print'] = 'funding/funding';
$route['user/funding/index/ajax_list'] = 'funding/funding';
$route['user/funding/index/ajax_list_info'] = 'funding/funding';
$route['user/funding/index/export'] = 'funding/funding';
$route['user/funding/index/getFBName/(:num)'] = 'funding/getFBName/$1';
//Routes for Project Funding
$route['user/funding/fundproj/ajax_list/(:num)'] = 'funding/fundproj/$1';
$route['user/funding/fundproj/ajax_list_info/(:num)'] = 'funding/fundproj/$1';
$route['user/funding/fundproj/read/(:num)'] = 'funding/fundproj/$1';
$route['user/funding/fundproj/edit/(:num)'] = 'funding/fundproj/$1';
$route['user/funding/fundproj/insert/(:num)'] = 'funding/fundproj/$1';
$route['user/funding/fundproj/insert_validation/(:num)'] = 'funding/fundproj/$1';
$route['user/funding/fundproj/update/(:num)'] = 'funding/fd_update/$1';
$route['user/funding/fundproj/update_validation/(:num)'] = 'funding/fundproj/$1';
$route['user/funding/fundproj/export/(:num)'] = 'funding/fundproj/$1';
$route['user/funding/fundproj/print/(:num)'] = 'funding/fundproj/$1';
$route['user/funding/fundproj/success/(:num)'] = 'funding/fundproj/$1';
$route['user/funding/fundproj/delete/(:num)'] = 'funding/fundproj/$1';

//Routes for funding body Page
$route['user/fundingbody'] = 'fundingbody';
$route['user/fundingbody/index'] = 'fundingbody';
$route['user/fundingbody/index/add'] = 'fundingbody/fundingbody';
$route['user/fundingbody/index/insert'] = 'fundingbody/fundingbody';
$route['user/fundingbody/index/insert_validation'] = 'fundingbody/fundingbody';
$route['user/fundingbody/index/edit/(:num)'] = 'fundingbody/fundingbody/$1';
$route['user/fundingbody/index/update/(:num)'] = 'fundingbody/fundingbody/$1';
$route['user/fundingbody/index/update_validation/(:num)'] = 'fundingbody/fundingbody/$1';
$route['user/fundingbody/index/success/(:num)'] = 'fundingbody/fundingbody/$1';
$route['user/fundingbody/index/read/(:num)'] = 'fundingbody/fundingbody/$1';
$route['user/fundingbody/index/delete/(:num)'] = 'fundingbody/fundingbody/$1';
$route['user/fundingbody/index/print'] = 'fundingbody/fundingbody';
$route['user/fundingbody/index/ajax_list'] = 'fundingbody/fundingbody';
$route['user/fundingbody/index/ajax_list_info'] = 'fundingbody/fundingbody';
$route['user/fundingbody/index/export'] = 'fundingbody/fundingbody';

//Routes for reports Page
$route['user/reports'] = 'reports';
$route['user/reports/index'] = 'reports';
$route['user/reports/createReport'] = 'reports/createReport';


//Routes for Generating Page
$route['user/genreport'] = 'genreport';
$route['user/genreport/index'] = 'genreport';
$route['user/genreport/createreport'] = 'genreport/createReport';
$route['user/genreport/viewreport/(:any)'] = 'genreport/viewReport/$1';
$route['user/genreport/viewreport'] = 'genreport/viewReport';
$route['user/genreport/printreimb/(:num)/(:num)'] = 'genreport/printReimbursement/$1/$1';

//Routes for Options Page
$route['user/options'] = 'options';
$route['user/options/index'] = 'options';
$route['user/options/index/add'] = 'options/options';
$route['user/options/index/insert'] = 'options/options';
$route['user/options/index/insert_validation'] = 'options/options';
$route['user/options/index/edit/(:num)'] = 'options/options/$1';
$route['user/options/index/update/(:num)'] = 'options/options/$1';
$route['user/options/index/update_validation/(:num)'] = 'options/options/$1';
$route['user/options/index/success/(:num)'] = 'options/options/$1';
$route['user/options/index/read/(:num)'] = 'options/options/$1';
$route['user/options/index/delete/(:num)'] = 'options/options/$1';
$route['user/options/index/print'] = 'options/options';
$route['user/options/index/ajax_list'] = 'options/options';
$route['user/options/index/ajax_list_info'] = 'options/options';
$route['user/options/index/export'] = 'options/options';

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
