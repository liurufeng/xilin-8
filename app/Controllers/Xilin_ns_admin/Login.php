<?php namespace App\Controllers\Xilin_ns_admin;

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
      redirect()->to($_SESSION['admin_path'].'index'. '?1');
    }
    echo view($_SESSION['tm'].'admin/login/login.htm');

  }

  function check_login()
  {
    //$validate = empty($this->request->getVar('captcha']) ? '' : strtolower(trim($this->request->getVar('captcha']));
    $name = trim($this->request->getVar('username'));
    $pass = $this->request->getVar('pwd');

    /*if (empty($this->request->getVar('captcha'])) {
      ShowMsg('Verify code cannot be empty!', 'login.php', 0, 1500);
    }
    $this->load->helper('validate_m');
    $svali = strtolower(GetCkVdValue());
    if (($validate == '' || $validate != $svali)) {
      ResetVdValue();
      ShowMsg('Verify code error!', 'login.php', 0, 1500);
    }*/
    if (empty($name)) {
      ShowMsg('Username cannot be empty!', $_SESSION['admin_path'].'login', 0, 1500);
    } elseif (empty($pass)) {
      ShowMsg('Password cannot be empty!', $_SESSION['admin_path'].'login', 0, 1500);
    }
    $member_model = new Member_model();
    $uid = $member_model->user_login(trim($name), $pass, TRUE);
    if ($uid > 0) {
      $userinfo = $member_model->getuserinfo($uid);
      $userinfo['authorized_nodes'] = explode(',', $userinfo['nodeids']);

      session()->set($userinfo);
      $data['success'] = true;
      //redirect($_SESSION['admin_path'].'index'),'refresh');
      header("location: ".$_SESSION['admin_path']."index");
      exit;
      //header("location: ".$_SESSION['admin_path'].'index'));
      //ShowMsg('Login success,Page is transferred to the system page!', $_SESSION['admin_path'].'index'), 0, 1500);
    } else {
      ShowMsg('Login failed!', $_SESSION['admin_path'].'login/', 0, 1500);
    }
  }

  function logout()
  {
    helper('cookie');
    session()->destroy();
    $userdata = array('id' => '', 'name' => '', 'realname' => '', 'loginCount' => '', 'group_id' => '');
    //$this->session->unset_userdata($userdata);
    delete_cookie('csrf_cookie_name', $_SERVER['HTTP_HOST'], '/');
    return redirect()->to($_SESSION['admin_path'].'login/');

  }

}

/* End of file login.php */
