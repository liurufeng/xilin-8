<?php namespace App\Controllers\Xilin_ns_admin;

use App\Libraries\Passwords;
use App\Models\Admin\{Member_model};
/**
 *
 * login
 *
 */
class Login extends MY_Controller
{

  function __construct()
  {
    parent::_Mycontroller();
    session()->set(array('tm' => 'xilin7/'));
    session()->set(array('admin_path' => '/Xilin_ns_admin/'));
  }

  function index()
  {

    $is_login = parent::_is_login();

    if ($is_login) {
      return redirect()->to($_SESSION['admin_path'].'index');
    }
    echo view($_SESSION['tm'].'admin/login/login.htm');

  }

  function check_login()
  {
    $name = trim((string)$this->request->getPost('username'));
    $pass = (string)$this->request->getPost('pwd');

    if (empty($name)) {
      ShowMsg('Username cannot be empty!', $_SESSION['admin_path'].'login', 0, 1500);
    } elseif ($pass === '') {
      ShowMsg('Password cannot be empty!', $_SESSION['admin_path'].'login', 0, 1500);
    }
    if (!Passwords::attemptAllowed('admin', $name)) {
      ShowMsg('Too many sign-in attempts. Please wait 10 minutes and try again.', $_SESSION['admin_path'].'login', 0, 3000);
    }
    $member_model = new Member_model();
    $uid = $member_model->user_login($name, $pass);
    if ($uid > 0) {
      $userinfo = $member_model->getuserinfo($uid);
      $userinfo['authorized_nodes'] = explode(',', $userinfo['nodeids']);

      // a new session id on sign-in
      session()->regenerate(true);
      session()->set($userinfo);
      return redirect()->to($_SESSION['admin_path'].'index');
    } else {
      ShowMsg('Login failed!', $_SESSION['admin_path'].'login/', 0, 1500);
    }
  }

  function logout()
  {
    session()->destroy();
    return redirect()->to('/Xilin_ns_admin/login/');

  }

}

/* End of file login.php */
