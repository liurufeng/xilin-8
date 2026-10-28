<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Index::index');

// the registration form posts to /register (before the table below, which sends /register to Register::index)
$routes->post('register', 'Register::registerinfo');

// Every page as controller/method, plus /anything after it (passed to the method, which mostly reads the URI or the
// query string). The pages read their input with getVar(), so a method answers GET and POST as under CodeIgniter 3.
// The csrf filter checks every POST (Config/Filters.php); the back office needs a signed-in admin (filter admin).
// Not routed: Homework, Program, and Xilin_ns_admin Column, System, Ueditor and Imgcheck (CodeIgniter 2/3 code that
// no longer runs).
//
// [URL name, controller class, methods, filter]
$controllers = [
    ['index', 'Index', ['index'], null],
    ['home', 'Home', ['index'], null],
    ['school', 'School', ['index', 'mission', 'rules', 'faq', 'scholarship', 'forms', 'notice', 'location', 'links', 'fundraising', 'jobs', 'newsletters', 'admins', 'intro', 'allannounce', 'allnews', 'finaid', 'contactus', 'contact', 'sponsor', 'pvsa'], null],
    ['careers', 'Careers', ['index', 'applyjob'], null],
    ['calendar', 'Calendar', ['index'], null],
    ['classinfo', 'Classinfo', ['index'], null],
    ['gallery', 'Gallery', ['index'], null],
    ['pod', 'Pod', ['index', 'getEvents', 'processRegEvent', 'processUnregEvent'], null],
    ['signin', 'Signin', ['index'], null],
    ['login', 'Login', ['index', 'check_login', 'verify', 'resend', 'logout'], null],
    ['tlogin', 'Tlogin', ['index', 'g_login'], null],
    ['register', 'Register', ['index', 'registerinfo'], null],
    ['register_class', 'Register_class', ['index', 'do_register', 'send_email'], null],
    ['account', 'Account', ['index', 'students', 'add_student', 'remove_student', 'update_student', 'invoice', 'teacher_info', 'update_teacher', 'update_parent', 'findpass', 'online_payment', 'pay_instruction', 'check_address'], null],
    ['password', 'Password', ['reset', 'save'], null],
    ['teachers', 'Teachers', ['index', 'update_sl', 'rosterEmail'], null],
    // jobs and Google sign-in for the mailbox; each checks its own token or admin sign-in
    ['scheduler', 'Scheduler', ['index'], null], // also scheduler/<token>, below
    ['pod_reminder', 'Pod_reminder', ['index'], null],
    ['imap', 'Imap', ['index'], null],
    ['payment', 'Payment', ['auth', 'callback', 'read'], null],

    ['Xilin_ns_admin/login', 'Xilin_ns_admin\Login', ['index', 'check_login', 'verify', 'resend', 'logout'], null],

    ['Xilin_ns_admin/index', 'Xilin_ns_admin\Index', ['index', 'index_menu', 'index_body', 'catalogmenu'], 'admin'],
    ['Xilin_ns_admin/calendar', 'Xilin_ns_admin\Calendar', ['index', 'add', 'edit', 'del', 'desc', 'copy'], 'admin'],
    ['Xilin_ns_admin/classes', 'Xilin_ns_admin\Classes', ['index', 'add', 'edit', 'del', 'copy', 'copyall'], 'admin'],
    ['Xilin_ns_admin/classstudents', 'Xilin_ns_admin\Classstudents', ['index'], 'admin'],
    ['Xilin_ns_admin/discounter', 'Xilin_ns_admin\Discounter', ['index', 'add', 'del'], 'admin'],
    ['Xilin_ns_admin/email_list', 'Xilin_ns_admin\Email_list', ['index'], 'admin'],
    ['Xilin_ns_admin/event_dates', 'Xilin_ns_admin\Event_dates', ['index', 'addEventDate', 'editEventDate', 'deleteEventDate'], 'admin'],
    ['Xilin_ns_admin/findpass', 'Xilin_ns_admin\Findpass', ['index', 'find', 'setpass'], 'admin'],
    ['Xilin_ns_admin/info', 'Xilin_ns_admin\Info', ['index', 'add', 'edit', 'recycling', 'dohtml', 'doaction', 'archives_do', 'top'], 'admin'],
    ['Xilin_ns_admin/manualpod', 'Xilin_ns_admin\Manualpod', ['index', 'add', 'edit', 'del'], 'admin'],
    ['Xilin_ns_admin/member', 'Xilin_ns_admin\Member', ['index', 'add', 'edit', 'del', 'group', 'groupadd', 'groupedit', 'groupdel'], 'admin'],
    ['Xilin_ns_admin/newsletters', 'Xilin_ns_admin\Newsletters', ['index', 'add', 'edit', 'del'], 'admin'],
    ['Xilin_ns_admin/payment', 'Xilin_ns_admin\Payment', ['index', 'late_fee', 'addPayment', 'online', 'update_online'], 'admin'],
    ['Xilin_ns_admin/pod', 'Xilin_ns_admin\Pod', ['index', 'getEvents', 'processAddEvent', 'processEditEvent', 'processDeleteEvent', 'processDownloadHelperInfo', 'processSigninout', 'processUnregEvent', 'addEventDate', 'editEventDate', 'deleteEventDate'], 'admin'],
    ['Xilin_ns_admin/podreports', 'Xilin_ns_admin\Podreports', ['index', 'detail'], 'admin'],
    ['Xilin_ns_admin/podwaiver', 'Xilin_ns_admin\Podwaiver', ['index', 'add', 'del'], 'admin'],
    ['Xilin_ns_admin/roster', 'Xilin_ns_admin\Roster', ['index'], 'admin'],
    ['Xilin_ns_admin/schooluser', 'Xilin_ns_admin\Schooluser', ['index', 'add', 'edit', 'del'], 'admin'],
    ['Xilin_ns_admin/semester', 'Xilin_ns_admin\Semester', ['index', 'add', 'edit', 'del', 'copy'], 'admin'],
    ['Xilin_ns_admin/sponsorinfo', 'Xilin_ns_admin\Sponsorinfo', ['index', 'add', 'edit', 'del'], 'admin'],
    ['Xilin_ns_admin/sponsorlevel', 'Xilin_ns_admin\Sponsorlevel', ['index', 'add', 'edit', 'del'], 'admin'],
    ['Xilin_ns_admin/sponsorpayments', 'Xilin_ns_admin\Sponsorpayments', ['index', 'add', 'edit', 'del'], 'admin'],
    ['Xilin_ns_admin/subjects', 'Xilin_ns_admin\Subjects', ['index', 'add', 'edit', 'del'], 'admin'],
    ['Xilin_ns_admin/teacher', 'Xilin_ns_admin\Teacher', ['index', 'add', 'edit', 'del'], 'admin'],
];

// other spellings of a URL name that links use: lower case, and the first letter of each part upper/lower case
$spellings = static function (string $name): array {
    $parts = explode('/', $name);

    return array_unique([
        $name,
        strtolower($name),
        implode('/', array_map('ucfirst', $parts)),
        implode('/', array_map('lcfirst', $parts)),
    ]);
};

$route = static function (string $from, string $to, ?string $filter) use ($routes): void {
    $routes->match(['get', 'post'], $from, $to, $filter === null ? [] : ['filter' => $filter]);
};

foreach ($controllers as [$name, $class, $methods, $filter]) {
    foreach ($spellings($name) as $prefix) {
        foreach ($methods as $method) {
            $route("{$prefix}/{$method}", "{$class}::{$method}", $filter);
            $route("{$prefix}/{$method}/(:any)", "{$class}::{$method}/$1", $filter);
        }
        if (in_array('index', $methods, true)) {
            $route($prefix, "{$class}::index", $filter);
        }
    }
}

// older names still in links, menus (table menu) and scripts
$aliases = [
    ['Xilin_ns_admin', 'Xilin_ns_admin\Index::index', 'admin'],
    ['Xilin_ns_admin/logout', 'Xilin_ns_admin\Login::logout', null],
    ['Xilin_ns_admin/payment/add', 'Xilin_ns_admin\Payment::addPayment', 'admin'],
    ['Xilin_ns_admin/pod/addEvent', 'Xilin_ns_admin\Pod::processAddEvent', 'admin'],
    ['Xilin_ns_admin/pod/editEvent', 'Xilin_ns_admin\Pod::processEditEvent', 'admin'],
    ['Xilin_ns_admin/pod/deleteEvent', 'Xilin_ns_admin\Pod::processDeleteEvent', 'admin'],
    ['Xilin_ns_admin/pod/signinout', 'Xilin_ns_admin\Pod::processSigninout', 'admin'],
    ['Xilin_ns_admin/pod/unregEvent', 'Xilin_ns_admin\Pod::processUnregEvent', 'admin'],
    ['Xilin_ns_admin/pod/downloadHelperInfo', 'Xilin_ns_admin\Pod::processDownloadHelperInfo', 'admin'],
];
foreach ($aliases as [$name, $to, $filter]) {
    foreach ($spellings($name) as $from) {
        $route($from, $to, $filter);
    }
}

// the reminder job's address carries its token: scheduler/<token>
foreach ($spellings('scheduler') as $from) {
    $route($from . '/(:segment)', 'Scheduler::index', null);
}
