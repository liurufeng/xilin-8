<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Index::index');
$routes->get('/index', 'Index::index');

$routes->get('/school/intro', 'School::intro');
$routes->get('/school/rules', 'School::rules');
$routes->get('/school/faq', 'School::faq');
$routes->get('/school/newsletters', 'School::newsletters');
$routes->get('/school/pvsa', 'School::pvsa');
$routes->get('/school/scholarship', 'School::scholarship');
$routes->get('/school/fundraising', 'School::fundraising');
$routes->get('/school/sponsor', 'School::sponsor');
$routes->get('/school/contactus', 'School::contactus');

$routes->get('/calendar', 'Calendar::index');

$routes->get('/classinfo', 'Classinfo::index');
$routes->get('/pod/index', 'Pod::index');
$routes->match(['get', 'post'], 'pod/getEvents', 'Pod::getEvents');

$routes->get('/account', 'Account::index');
$routes->get('/account/students', 'Account::students');
//$routes->get('/account/invoice/PaymentInstruction', 'Account::invoice');
$routes->get('account/invoice/(:segment)', 'Account::invoice/$1');
$routes->get('/register_class', 'Register_class::index');
$routes->match(['get', 'post'], 'register_class/do_register', 'Register_class::do_register');

$routes->match(['get', 'post'], 'login/check_login', 'Login::check_login');

$routes->get('/login/logout', 'Login::logout');
$routes->get('signin/index/(:segment)', 'Signin::index/$1');
$routes->get('/signin', 'Signin::index');


$routes->group('register', function ($routes) {
  $routes->get('/', 'Register::index');
  $routes->post('/', 'Register::registerinfo');
});

/*
|--------------------------------------------------------------------------
| Xilin_ns_admin - Calendar
|--------------------------------------------------------------------------
*/
$routes->match(['get', 'post'], 'Xilin_ns_admin/calendar/index', 'Xilin_ns_admin\Calendar::index');
$routes->match(['get', 'post'], 'Xilin_ns_admin/calendar/add', 'Xilin_ns_admin\Calendar::add');
$routes->match(['get', 'post'], 'Xilin_ns_admin/calendar/edit', 'Xilin_ns_admin\Calendar::edit');
$routes->match(['get', 'post'], 'Xilin_ns_admin/calendar/del', 'Xilin_ns_admin\Calendar::del');
$routes->match(['get', 'post'], 'Xilin_ns_admin/calendar/copy', 'Xilin_ns_admin\Calendar::copy');
$routes->match(['get', 'post'], 'Xilin_ns_admin/calendar/desc', 'Xilin_ns_admin\Calendar::desc');


/*
|--------------------------------------------------------------------------
| Xilin_ns_admin - Classes
|--------------------------------------------------------------------------
*/
$routes->match(['get', 'post'], 'Xilin_ns_admin/classes/index', 'Xilin_ns_admin\Classes::index');
$routes->match(['get', 'post'], 'Xilin_ns_admin/classes/add', 'Xilin_ns_admin\Classes::add');
$routes->match(['get', 'post'], 'Xilin_ns_admin/classes/edit', 'Xilin_ns_admin\Classes::edit');
$routes->match(['get', 'post'], 'Xilin_ns_admin/classes/del', 'Xilin_ns_admin\Classes::del');
$routes->match(['get', 'post'], 'Xilin_ns_admin/classes/copy', 'Xilin_ns_admin\Classes::copy');
$routes->match(['get', 'post'], 'Xilin_ns_admin/classes/copyall', 'Xilin_ns_admin\Classes::copyall');


/*
|--------------------------------------------------------------------------
| Xilin_ns_admin - Classstudents
|--------------------------------------------------------------------------
*/
$routes->match(['get', 'post'], 'Xilin_ns_admin/classstudents/index', 'Xilin_ns_admin\Classstudents::index');

/*
|--------------------------------------------------------------------------
| Xilin_ns_admin - Column
|--------------------------------------------------------------------------
*/
$routes->match(['get', 'post'], 'Xilin_ns_admin/column', 'Xilin_ns_admin\Column::index');
$routes->match(['get', 'post'], 'Xilin_ns_admin/column/index', 'Xilin_ns_admin\Column::index');
$routes->match(['get', 'post'], 'Xilin_ns_admin/column/add', 'Xilin_ns_admin\Column::add');
$routes->match(['get', 'post'], 'Xilin_ns_admin/column/edit', 'Xilin_ns_admin\Column::edit');
$routes->match(['get', 'post'], 'Xilin_ns_admin/column/del', 'Xilin_ns_admin\Column::del');

/*
|--------------------------------------------------------------------------
| Xilin_ns_admin - Discounter
|--------------------------------------------------------------------------
*/
$routes->match(['get', 'post'], 'Xilin_ns_admin/discounter', 'Xilin_ns_admin\Discounter::index');
$routes->match(['get', 'post'], 'Xilin_ns_admin/discounter/index', 'Xilin_ns_admin\Discounter::index');
$routes->match(['get', 'post'], 'Xilin_ns_admin/discounter/add', 'Xilin_ns_admin\Discounter::add');
$routes->match(['get', 'post'], 'Xilin_ns_admin/discounter/del', 'Xilin_ns_admin\Discounter::del');

/*
|--------------------------------------------------------------------------
| Xilin_ns_admin - Email_list
|--------------------------------------------------------------------------
*/
$routes->match(['get', 'post'], 'Xilin_ns_admin/email_list', 'Xilin_ns_admin\Email_list::index');
$routes->match(['get', 'post'], 'Xilin_ns_admin/email_list/index', 'Xilin_ns_admin\Email_list::index');

/*
|--------------------------------------------------------------------------
| Xilin_ns_admin - Event_dates
|--------------------------------------------------------------------------
*/
$routes->match(['get', 'post'], 'Xilin_ns_admin/event_dates', 'Xilin_ns_admin\Event_dates::index');
$routes->match(['get', 'post'], 'Xilin_ns_admin/event_dates/index', 'Xilin_ns_admin\Event_dates::index');
$routes->match(['get', 'post'], 'Xilin_ns_admin/event_dates/addEventDate', 'Xilin_ns_admin\Event_dates::addEventDate');
$routes->match(['get', 'post'], 'Xilin_ns_admin/event_dates/editEventDate', 'Xilin_ns_admin\Event_dates::editEventDate');
$routes->match(['get', 'post'], 'Xilin_ns_admin/event_dates/deleteEventDate', 'Xilin_ns_admin\Event_dates::deleteEventDate');

/*
|--------------------------------------------------------------------------
| Xilin_ns_admin - Findpass
|--------------------------------------------------------------------------
*/
$routes->match(['get', 'post'], 'Xilin_ns_admin/findpass', 'Xilin_ns_admin\Findpass::index');
$routes->match(['get', 'post'], 'Xilin_ns_admin/findpass/index', 'Xilin_ns_admin\Findpass::index');
$routes->match(['get', 'post'], 'Xilin_ns_admin/findpass/find', 'Xilin_ns_admin\Findpass::find');

/*
|--------------------------------------------------------------------------
| Xilin_ns_admin - Imgcheck
|--------------------------------------------------------------------------
*/
$routes->match(['get', 'post'], 'Xilin_ns_admin/imgcheck', 'Xilin_ns_admin\Imgcheck::index');
$routes->match(['get', 'post'], 'Xilin_ns_admin/imgcheck/vdimgck', 'Xilin_ns_admin\Imgcheck::vdimgck');
$routes->match(['get', 'post'], 'Xilin_ns_admin/imgcheck/vimg', 'Xilin_ns_admin\Imgcheck::vimg');
$routes->match(['get', 'post'], 'Xilin_ns_admin/imgcheck/imgis', 'Xilin_ns_admin\Imgcheck::imgis');

/*
|--------------------------------------------------------------------------
| Xilin_ns_admin - Index
|--------------------------------------------------------------------------
*/
$routes->match(['get', 'post'], 'Xilin_ns_admin', 'Xilin_ns_admin\Index::index');
$routes->match(['get', 'post'], 'Xilin_ns_admin/index', 'Xilin_ns_admin\Index::index');
$routes->match(['get', 'post'], 'Xilin_ns_admin/index/index', 'Xilin_ns_admin\Index::index');
$routes->match(['get', 'post'], 'Xilin_ns_admin/index/index_menu', 'Xilin_ns_admin\Index::index_menu');
$routes->match(['get', 'post'], 'Xilin_ns_admin/index/index_body', 'Xilin_ns_admin\Index::index_body');
$routes->match(['get', 'post'], 'Xilin_ns_admin/index/catalogmenu', 'Xilin_ns_admin\Index::catalogmenu');

/*
|--------------------------------------------------------------------------
| Xilin_ns_admin - Info
|--------------------------------------------------------------------------
*/
//$routes->match(['get', 'post'], 'Xilin_ns_admin/info', 'Xilin_ns_admin\Info::index');
//$routes->match(['get', 'post'], 'Xilin_ns_admin/info/index', 'Xilin_ns_admin\Info::index');
//$routes->match(['get', 'post'], 'Xilin_ns_admin/info/add', 'Xilin_ns_admin\Info::add');
//$routes->match(['get', 'post'], 'Xilin_ns_admin/info/edit', 'Xilin_ns_admin\Info::edit');
//$routes->match(['get', 'post'], 'Xilin_ns_admin/info/recycling', 'Xilin_ns_admin\Info::recycling');
//$routes->match(['get', 'post'], 'Xilin_ns_admin/info/dohtml', 'Xilin_ns_admin\Info::dohtml');
//$routes->match(['get', 'post'], 'Xilin_ns_admin/info/doaction', 'Xilin_ns_admin\Info::doaction');
//$routes->match(['get', 'post'], 'Xilin_ns_admin/info/archives_do', 'Xilin_ns_admin\Info::archives_do');
//$routes->match(['get', 'post'], 'Xilin_ns_admin/info/top', 'Xilin_ns_admin\Info::top');

$routes->group('Xilin_ns_admin/info', function($routes) {
  $routes->get('/', 'Xilin_ns_admin\Info::index');
  $routes->get('index', 'Xilin_ns_admin\Info::index');
  $routes->match(['get','post'], 'add', 'Xilin_ns_admin\Info::add');
  $routes->match(['get','post'], 'edit', 'Xilin_ns_admin\Info::edit');
  $routes->match(['get','post'], 'recycling', 'Xilin_ns_admin\Info::recycling');
  $routes->match(['get','post'], 'dohtml', 'Xilin_ns_admin\Info::dohtml');
  $routes->match(['get','post'], 'doaction', 'Xilin_ns_admin\Info::doaction');
  $routes->match(['get','post'], 'archives_do', 'Xilin_ns_admin\Info::archives_do');
  $routes->match(['get','post'], 'top', 'Xilin_ns_admin\Info::top');
});

$routes->group('Xilin_ns_admin', function($routes) {
  // show login page
  $routes->get('login', 'Xilin_ns_admin\Login::index');
  // handle login submit
  $routes->post('login/check_login', 'Xilin_ns_admin\Login::check_login');
  // logout
  $routes->get('logout', 'Xilin_ns_admin\Login::logout');
});

$routes->group('Xilin_ns_admin/manualpod', function($routes) {

  // list page
  $routes->get('/', 'Xilin_ns_admin\Manualpod::index');
  $routes->get('index', 'Xilin_ns_admin\Manualpod::index');

  // add record
  $routes->match(['get','post'], 'add', 'Xilin_ns_admin\Manualpod::add');

  // edit record
  $routes->match(['get','post'], 'edit', 'Xilin_ns_admin\Manualpod::edit');

  // delete record
  $routes->get('del', 'Xilin_ns_admin\Manualpod::del');

});

$routes->group('Xilin_ns_admin/member', function($routes) {

  $routes->get('/', 'Member::index');

  $routes->match(['get','post'], 'create', 'Member::add');
  $routes->match(['get','post'], 'update/(:num)', 'Member::edit/$1');

  $routes->get('delete/(:num)', 'Member::del/$1');

  // groups
  $routes->get('groups', 'Member::group');
  $routes->match(['get','post'], 'groups/create', 'Member::groupadd');
  $routes->match(['get','post'], 'groups/update/(:num)', 'Member::groupedit/$1');
  $routes->get('groups/delete/(:num)', 'Member::groupdel/$1');

});

$routes->group('Xilin_ns_admin/newsletters', function($routes) {

  // list
  $routes->get('/', 'Xilin_ns_admin\Newsletters::index');
  $routes->get('index', 'Xilin_ns_admin\Newsletters::index');

  // add
  $routes->match(['get','post'], 'add', 'Xilin_ns_admin\Newsletters::add');

  // edit
  $routes->match(['get','post'], 'edit', 'Xilin_ns_admin\Newsletters::edit');

  // delete (soft delete)
  $routes->get('del', 'Xilin_ns_admin\Newsletters::del');

});

$routes->group('Xilin_ns_admin/payment', function($routes) {

  // payment dashboard
  $routes->get('/', 'Xilin_ns_admin\Payment::index');
  $routes->get('index', 'Xilin_ns_admin\Payment::index');

  // late fee processing
  $routes->get('late_fee', 'Xilin_ns_admin\Payment::late_fee');

  // add manual payment (AJAX / POST)
  $routes->post('add', 'Xilin_ns_admin\Payment::addPayment');

  // online payment list
  $routes->get('online', 'Xilin_ns_admin\Payment::online');

  // approve / decline / delete online payment (AJAX)
  $routes->post('update_online', 'Xilin_ns_admin\Payment::update_online');

});

$routes->group('Xilin_ns_admin/pod', function($routes) {

  // main calendar page
  $routes->get('/', 'Xilin_ns_admin\Pod::index');
  $routes->get('index', 'Xilin_ns_admin\Pod::index');

  // event ajax
  $routes->post('getEvents', 'Xilin_ns_admin\Pod::getEvents');

  // event CRUD
  $routes->post('addEvent', 'Xilin_ns_admin\Pod::processAddEvent');
  $routes->post('editEvent', 'Xilin_ns_admin\Pod::processEditEvent');
  $routes->post('deleteEvent', 'Xilin_ns_admin\Pod::processDeleteEvent');

  // helper registration actions
  $routes->post('signinout', 'Xilin_ns_admin\Pod::processSigninout');
  $routes->post('unregEvent', 'Xilin_ns_admin\Pod::processUnregEvent');

  // event dates management
  $routes->post('addEventDate', 'Xilin_ns_admin\Pod::addEventDate');
  $routes->post('editEventDate', 'Xilin_ns_admin\Pod::editEventDate');
  $routes->post('deleteEventDate', 'Xilin_ns_admin\Pod::deleteEventDate');

  // download helper report
  $routes->get('downloadHelperInfo', 'Xilin_ns_admin\Pod::processDownloadHelperInfo');

});

$routes->group('Xilin_ns_admin/podreports', function($routes) {

  // report list page
  $routes->get('/', 'Xilin_ns_admin\Podreports::index');
  $routes->get('index', 'Xilin_ns_admin\Podreports::index');

  // parent detail report (AJAX / popup)
  $routes->get('detail', 'Xilin_ns_admin\Podreports::detail');

});

$routes->group('Xilin_ns_admin/podwaiver', function($routes) {

  // list page
  $routes->get('/', 'Xilin_ns_admin\Podwaiver::index');
  $routes->get('index', 'Xilin_ns_admin\Podwaiver::index');

  // add waiver
  $routes->match(['get','post'], 'add', 'Xilin_ns_admin\Podwaiver::add');

  // delete waiver
  $routes->get('del', 'Xilin_ns_admin\Podwaiver::del');

});

$routes->group('Xilin_ns_admin/schooluser', ['filter' => 'auth'], function($routes) {

  $routes->get('/', 'Schooluser::index');

  $routes->match(['get','post'], 'add', 'Schooluser::add');

  $routes->match(['get','post'], 'edit', 'Schooluser::edit');

  $routes->get('delete/(:num)', 'Schooluser::del/$1');

});

$routes->group('Xilin_ns_admin/semester', function($routes) {

  $routes->get('/', 'Semester::index');

  $routes->match(['get','post'], 'add', 'Semester::add');

  $routes->match(['get','post'], 'edit/(:num)', 'Semester::edit/$1');

  $routes->get('delete/(:num)', 'Semester::del/$1');

  $routes->get('copy/(:num)', 'Semester::copy/$1');

});

$routes->group('Xilin_ns_admin/sponsorinfo', function($routes) {

  $routes->get('/', 'Sponsorinfo::index');

  $routes->match(['get','post'], 'add', 'Sponsorinfo::add');

  $routes->match(['get','post'], 'edit/(:num)', 'Sponsorinfo::edit/$1');

  $routes->get('delete/(:num)', 'Sponsorinfo::del/$1');

});

$routes->group('Xilin_ns_admin/sponsorlevel', function($routes) {

  $routes->get('/', 'Sponsorlevel::index');

  $routes->match(['get','post'], 'add', 'Sponsorlevel::add');

  $routes->match(['get','post'], 'edit/(:num)', 'Sponsorlevel::edit/$1');

  $routes->get('delete/(:num)', 'Sponsorlevel::del/$1');

});

$routes->group('Xilin_ns_admin/sponsorpayments', function($routes) {

  $routes->get('/', 'Sponsorpayments::index');

  $routes->match(['get','post'], 'add', 'Sponsorpayments::add');

  $routes->match(['get','post'], 'edit/(:num)', 'Sponsorpayments::edit/$1');

  $routes->get('delete/(:num)', 'Sponsorpayments::del/$1');

});

$routes->group('Xilin_ns_admin/subjects', function($routes) {

  $routes->get('/', 'Subjects::index');

  $routes->match(['get','post'], 'add', 'Subjects::add');

  $routes->match(['get','post'], 'edit/(:num)', 'Subjects::edit/$1');

  $routes->get('delete/(:num)', 'Subjects::del/$1');

});

$routes->group('admin/system', ['filter' => 'auth'], function($routes) {

  // Permission node list
  $routes->get('nodes', 'Admin/SystemController::index');

  // Add node page
  $routes->get('nodes/add', 'Admin/SystemController::addNode');

  // Add node submit (POST)
  $routes->post('nodes/add', 'Admin/SystemController::addNode');

  // Clear permission cache
  $routes->get('cache/update', 'Admin/SystemController::updateCache');

});

$routes->group('xilin_ns_admin/teacher', ['namespace' => 'App\Controllers\Xilin_ns_admin'], function($routes) {

  // list page
  $routes->get('/', 'Teacher::index');
  $routes->get('index', 'Teacher::index');

  // add (form + submit)
  $routes->match(['get','post'], 'add', 'Teacher::add');

  // edit (form + submit, uses getVar('teacher_id'))
  $routes->match(['get','post'], 'edit', 'Teacher::edit');

  // delete (POST only, uses getVar('teacher_id'))
  $routes->post('del', 'Teacher::del');
});

$routes->group('xilin_ns_admin/teacher', ['namespace' => 'App\Controllers\Xilin_ns_admin'], function($routes) {

  $routes->match(['get','post'], '(:num)/edit', 'Teacher::edit/$1');

  $routes->post('(:num)', 'Teacher::del/$1');
});


$routes->setAutoRoute(true);