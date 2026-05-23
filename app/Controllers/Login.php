<?php namespace App\Controllers;
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

  function check_login()
  {
    $email = trim($this->request->getVar('email'));
    $pass = $this->request->getVar('password');
    if (empty($email)) {
      ShowMsg('Email cannot be empty!', '/signin/', 0, 1500);
    } elseif (empty($pass)) {
      ShowMsg('Password cannot be empty!', '/signin/', 0, 1500);
    }

    $pass = str_replace("'","[$!)", $pass);
    $sql = "select * from parents where email = '$email' and passwd = '$pass'";
    $result = $this->db->query($sql)->getResultArray();
    $sql = "select * from teachers where email = '$email' and passwd = '$pass'";
    $tresult = $this->db->query($sql)->getResultArray();

    if (!empty($result) || !empty($tresult)) {
      if (!empty($result)) {
        $result['usertype'] = 1;
        session()->set(array('userresult'=> $result));
      } else {
        $tresult['usertype'] = 2;
        session()->set(array('userresult'=> $tresult));
      }
      $data['success'] = true;
      if(null !== session()->get('pod') && session()->get('pod')) {
        unset($_SESSION['pod']);
        return redirect()->to(base_url('/pod/index') );
      }
      return redirect()->to(base_url('/account') );
    } else {
      return redirect()->to(base_url('/signin') );
    }
  }

  function logout()
  {
    helper('cookie');
    session()->destroy();;
    delete_cookie('csrf_cookie_name', $_SERVER['HTTP_HOST'], '/');
    return redirect()->to(base_url('/index') );
  }

}

/* End of file login.php */
