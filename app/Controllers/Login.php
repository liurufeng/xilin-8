<?php namespace App\Controllers;
use App\Libraries\{LoginCode, Passwords};
use App\Models\{Semester, Subject, Classes, Teacher, Article};

/**
 *
 * login
 *
 */
class Login extends BaseController
{
  public $db = null;
  public $sess = null;
  function __construct()
  {
    $this->db = db_connect();
    $this->sess = session();
    session()->set(array('current_tab' => 'login'));
  }

  function index()
  {
    echo view($_SESSION['tm'].'/signin/');
  }

  /**
   * Parents sign in with email and password (teachers use Google, Tlogin::g_login).
   */
  function check_login()
  {
    $email = trim((string)$this->request->getVar('email'));
    $pass = (string)$this->request->getVar('password');
    if (empty($email)) {
      ShowMsg('Email cannot be empty!', '/signin/', 0, 1500);
    } elseif ($pass === '') {
      ShowMsg('Password cannot be empty!', '/signin/', 0, 1500);
    }
    if (!Passwords::attemptAllowed('parent', $email)) {
      ShowMsg('Too many sign-in attempts. Please wait 10 minutes and try again.', '/signin/', 0, 3000);
    }

    $parent = null;
    $rows = $this->db->query("select * from parents where email = ?", [$email])->getResultArray();
    foreach ($rows as $row) {
      if (Passwords::matches($pass, $row['passwd'], Passwords::parentLegacy($pass))) {
        Passwords::upgrade('parents', 'parent_id', $row['parent_id'], 'passwd', $pass, $row['passwd']);
        $parent = $row;
        break;
      }
    }

    if ($parent === null) {
      return redirect()->to(base_url('/signin') );
    }

    // the password is right; the sign-in finishes once the code emailed to the parent is typed in (verify)
    unset($parent['passwd']);
    $pending = array('parent' => $parent, 'pod' => (bool)session()->get('pod'));
    if (!LoginCode::start('parent', $pending, (string)$parent['email'])) {
      log_message('error', 'xilin: sign-in code not sent to parent ' . $parent['parent_id']);
    }
    return redirect()->to(base_url('/login/verify'));
  }

  /**
   * The second sign-in step: the code emailed by check_login.
   */
  function verify()
  {
    if (LoginCode::pending('parent') === null) {
      return redirect()->to(base_url('/signin'));
    }
    if (strtolower($this->request->getMethod()) !== 'post') {
      echo view($_SESSION['tm'].'signin/verify.php', array('masked_email' => LoginCode::maskedTo('parent')));
      return;
    }

    $pending = LoginCode::finish('parent', (string)$this->request->getPost('code'));
    if ($pending === null) {
      if (LoginCode::pending('parent') === null) {
        ShowMsg('The code expired or was entered wrong too many times. Please sign in again.', '/signin/', 0, 3000);
      }
      echo view($_SESSION['tm'].'signin/verify.php', array(
        'masked_email' => LoginCode::maskedTo('parent'),
        'error_msg' => 'That code is not right. Please check the email and try again.',
      ));
      return;
    }

    // a new session id on sign-in; the password (hash) stays out of the session
    session()->regenerate(true);
    session()->set(array('userresult'=> array(0 => $pending['parent'], 'usertype' => 1)));
    if ($pending['pod']) {
      session()->remove('pod');
      return redirect()->to(base_url('/pod/index') );
    }
    return redirect()->to(base_url('/account') );
  }

  // emails a new code (the button on the code page)
  function resend()
  {
    if (LoginCode::pending('parent') === null) {
      return redirect()->to(base_url('/signin'));
    }
    $sent = LoginCode::send('parent');
    echo view($_SESSION['tm'].'signin/verify.php', array(
      'masked_email' => LoginCode::maskedTo('parent'),
      'success_msg' => $sent ? 'A new code has been sent.' : '',
      'error_msg' => $sent ? '' : 'Please wait a minute before asking for another code.',
    ));
  }

  function logout()
  {
    session()->destroy();
    return redirect()->to(base_url('/index') );
  }

}

/* End of file login.php */
