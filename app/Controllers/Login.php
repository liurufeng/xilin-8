<?php namespace App\Controllers;
use App\Libraries\Passwords;
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

    // a new session id on sign-in; the password (hash) stays out of the session
    $pod = session()->get('pod');
    session()->regenerate(true);
    unset($parent['passwd']);
    session()->set(array('userresult'=> array(0 => $parent, 'usertype' => 1)));
    if ($pod) {
      session()->remove('pod');
      return redirect()->to(base_url('/pod/index') );
    }
    return redirect()->to(base_url('/account') );
  }

  function logout()
  {
    session()->destroy();
    return redirect()->to(base_url('/index') );
  }

}

/* End of file login.php */
