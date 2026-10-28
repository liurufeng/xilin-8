<?php namespace App\Controllers\Xilin_ns_admin;

use App\Libraries\{LoginCode, Passwords};
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

      // the password is right; the sign-in finishes once the code emailed to LoginCode::ADMIN_EMAIL is typed in
      if (!LoginCode::start('admin', $userinfo, LoginCode::ADMIN_EMAIL)) {
        log_message('error', 'xilin: admin sign-in code not sent for ' . $name);
      }
      return redirect()->to($_SESSION['admin_path'].'login/verify');
    } else {
      ShowMsg('Login failed!', $_SESSION['admin_path'].'login/', 0, 1500);
    }
  }

  /**
   * The second sign-in step: the code emailed by check_login.
   */
  function verify()
  {
    if (LoginCode::pending('admin') === null) {
      return redirect()->to($_SESSION['admin_path'].'login');
    }
    if (strtolower($this->request->getMethod()) !== 'post') {
      echo view($_SESSION['tm'].'admin/login/verify.htm', array('masked_email' => LoginCode::maskedTo('admin')));
      return;
    }

    $userinfo = LoginCode::finish('admin', (string)$this->request->getPost('code'));
    if ($userinfo === null) {
      if (LoginCode::pending('admin') === null) {
        ShowMsg('The code expired or was entered wrong too many times. Please sign in again.', $_SESSION['admin_path'].'login', 0, 3000);
      }
      echo view($_SESSION['tm'].'admin/login/verify.htm', array(
        'masked_email' => LoginCode::maskedTo('admin'),
        'error_msg' => 'That code is not right.',
      ));
      return;
    }

    // a new session id on sign-in
    session()->regenerate(true);
    session()->set($userinfo);
    return redirect()->to($_SESSION['admin_path'].'index');
  }

  // emails a new code (the button on the code page)
  function resend()
  {
    if (LoginCode::pending('admin') === null) {
      return redirect()->to($_SESSION['admin_path'].'login');
    }
    $sent = LoginCode::send('admin');
    echo view($_SESSION['tm'].'admin/login/verify.htm', array(
      'masked_email' => LoginCode::maskedTo('admin'),
      'success_msg' => $sent ? 'A new code has been sent.' : '',
      'error_msg' => $sent ? '' : 'Please wait a minute before asking for another code.',
    ));
  }

  function logout()
  {
    session()->destroy();
    return redirect()->to('/Xilin_ns_admin/login/');

  }

}

/* End of file login.php */
