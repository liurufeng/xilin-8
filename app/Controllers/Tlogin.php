<?php namespace App\Controllers;
use App\Models\{Semester, Subject, Classes, Teacher, Article};

/**
 *
 * login
 *
 */
class Tlogin extends BaseController
{

  function __construct()
  {
    session()->set(array('current_tab' => 'login'));
  }

  function index()
  {
    echo view($_SESSION['tm'].'/signin/');
  }

/*  function check_login()
  {
    $email = trim($this->request->getVar('email'));
    $pass = $this->request->getVar('password');
    if (empty($email)) {
      ShowMsg('Email cannot be empty!', '/signin/', 0, 1500);
    } elseif (empty($pass)) {
      ShowMsg('Password cannot be empty!', '/signin/', 0, 1500);
    }

    $sql = "select * from teachers where email = '$email' and passwd = '$pass'";
    $tresult = $this->db->query($sql)->getResultArray();
    if (!empty($tresult)) {
      $tresult['usertype'] = 2;
      session()->set(array('userresult'=> $tresult));
      $data['success'] = true;

      header("Location: ".site_url('account/index'));
    } else {
      session()->set_flashdata('login_error', 'Login Failed');
      header("Location: ".site_url('signin/index/teacher'));
    }
  }*/

  function g_login()
  {
    $_SESSION['glogin'] = 'first';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
    $client_id = '787488937189-p0prt8vpmf7vrg3c51681u1d7q6kchv2.apps.googleusercontent.com';
    $client_secret = '7bfRZiaQ1fwcL4wWfqJlU_X-';
    $redirect_url = 'http://'.$_SERVER['HTTP_HOST'].'/tlogin/g_login/';

    $gClient = new \Google_Client();
    $gClient->setApplicationName('Login to Xilin');
    $gClient->setClientId($client_id);
    $gClient->setClientSecret($client_secret);
    $gClient->setRedirectUri($redirect_url);
    $gClient->setScopes('email');
    //$gPlus = new Google_Service_Plus($gClient);

    $payload = null;
    if(isset($_GET['token'])){
      //$gClient->authenticate($_GET['code']);
      //$_SESSION['token'] = $gClient->getAccessToken();
      $payload = $gClient->verifyIdToken($_GET['token']);
    }

    if (!empty($payload)) {
      //$attr = $payload->getAttributes();
      $email = $payload['email'];
    }

    /*if ($gClient->getAccessToken()) {
      //Get user profile data from google
      $emails = $gPlus->people->get('me')->getEmails();
      $email = $emails[0]->value;
    }*/

    if (!isset($email) || empty($email)) {
      //session()->set_flashdata('teacher_login_error', 'No login email. Login Failed');
      header("Location: ".'/signin/index/teacher');
      exit;
    } elseif (strpos($email, '@xilinnschinese.org') === false && strpos($email, 'rufeng') === false) {
      //session()->set_flashdata('teacher_login_error', 'You must use xilinnschinese.org account. Login Failed. '.$email);
      header("Location: ".'/signin/index/teacher');
      exit;
    }

    $db = \Config\Database::connect();
    $sql = "select * from teachers where email = '$email'";
    $tresult = $db->query($sql)->getResultArray();

    if ($tresult) {
      $tresult['usertype'] = 2;
      session()->set(array('userresult'=> $tresult));
      header("location: ".'/account/index');
      exit();
    } else {
      //session()->set_flashdata('teacher_login_error', 'Login Failed. Please check with ec@xilinnschinese.org');
      header("location: ".'/signin/index/teacher');
      exit();
    }
  }

}

/* End of file login.php */
