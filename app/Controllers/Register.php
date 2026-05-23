<?php namespace App\Controllers;
use App\Models\{Semester, Subject, Classes, Teacher, Article};

/**
 *
 * register
 *
 */
class Register extends BaseController
{
  public $db = null;
  function __construct()
  {

    $this->db = db_connect();
    session()->set(array('current_tab' => 'register'));
  }

  function index()
  {
    echo view($_SESSION['tm'].'register/index.php');
  }

  function registerinfo()
  {
    $type = $_GET['REGISTERTYPE'];
    $insertdata['email'] = trim($this->request->getVar('email'));

    if ($type == "Parents") {

      if($_SESSION['tm'] == 'xilin7/') {
        $re_email = trim($this->request->getVar('re_email'));

        if($insertdata['email'] != $re_email) {
          ShowMsg('Your re-typed email does not match.', '/', 0, 3000);
        }
      }

      if (! filter_var($insertdata['email'], FILTER_VALIDATE_EMAIL)) {
        ShowMsg("Your Email address ".$insertdata['email']." is invalid", '/');
      }

      $sql = " SELECT parent_id FROM parents WHERE email = '{$insertdata['email']}'";
      $rst = $this->db->query($sql);
      if ($rst->getNumRows() > 0) {
        ShowMsg('The email already existed in our system. You can retrieve your password if you forgot it by clicking Find Password button.', '/', 0, 3000);
      }

      $sql = " SELECT parent_id FROM parents WHERE alter_contact_email = '{$insertdata['email']}'";
      $rst = $this->db->query($sql);
      if ($rst->getNumRows() > 0) {
        ShowMsg('Your family account already existed, please contact school admin if you need to create another account', '/', 0, 3000);
      }

      $pass = str_replace("'","[$!)", $this->request->getVar('passwd'));
      $insertdata['passwd'] = $pass;
      $insertdata['primary_en_name'] = $this->request->getVar('primary_en_name');
      $insertdata['primary_cn_name'] = $this->request->getVar('primary_cn_name');
      $insertdata['primary_phone'] = $this->request->getVar('primary_phone');

      $insertdata['primary_relationship'] = $this->request->getVar('primary_relationship');
      $insertdata['alter_en_name'] = $this->request->getVar('alter_en_name');
      $insertdata['alter_cn_name'] = $this->request->getVar('alter_cn_name');
      $insertdata['alter_phone'] = $this->request->getVar('alter_phone');

      $insertdata['alter_relationship'] = $this->request->getVar('alter_relationship');
      $insertdata['alter_contact_email'] = $this->request->getVar('alter_contact_email');
      $insertdata['heard_from'] = $this->request->getVar('heard_from');

      $insertdata['address'] = $this->request->getVar('address');
      $insertdata['city'] = $this->request->getVar('city');
      $insertdata['state'] = $this->request->getVar('state');
      $insertdata['zip'] = $this->request->getVar('zip');


      if(empty($insertdata['email']) || empty($insertdata['passwd']) || empty($insertdata['primary_en_name']) || empty($insertdata['primary_phone'])
        || empty($insertdata['primary_relationship']) || empty($insertdata['address'])) {
        ShowMsg('Please fill in all the *required information', '/', 0, 3000);
      }

      $this->db->table('parents')
        ->insert($insertdata);

      $sql = "select * from parents where email = '{$insertdata['email']}'";
      $result = $this->db->query($sql)->getResultArray();
      $result['usertype'] = 1;
      session()->set(array('userresult'=> $result));

      // insert referral info if there is referer ID
      $referrer_id = trim($this->request->getVar('referrer_id'));

      if(strlen($referrer_id) > 0 ) {
        $current_semester = session()->get('current_semester');
        $current_semester_id = $current_semester['semester_id'];
        if(!isset($current_semester_id )) {
          $current_semester_id = 100;
        }
        $this->db->table('referrals')
          ->insert(['from_id' => substr($referrer_id, 0, 99), 'to_id' => (int)$result[0]['parent_id'], 'semester_id' => (int)$current_semester_id]);
      }
    } /*else {
      $sql = " SELECT teacher_id  FROM teachers WHERE email = '{$insertdata['email']}' and status=1";
      $rst = $this->db->query($sql);
      if ($rst->getNumRows() > 0) {
        ShowMsg('email already exists', '/');
      }

      $insertdata['passwd'] = $this->request->getVar('passwd');
      $insertdata['email'] = $this->request->getVar('email');
      $insertdata['en_name'] = $this->request->getVar('en_name');
      $insertdata['type'] = $this->request->getVar('type');
      $insertdata['cn_name'] = $this->request->getVar('cn_name');
      $insertdata['phone1'] = $this->request->getVar('phone1');
      $insertdata['phone2'] = $this->request->getVar('phone2');
      $insertdata['address'] = $this->request->getVar('address');
      $insertdata['desc_link'] = $this->request->getVar('desc_link');
      $this->db->table('teachers')
        ->insert($insertdata);
      $sql = "select * from teachers where email = '{$insertdata['email']}'";
      $result = $this->db->query($sql)->getResultArray();
      $result['usertype'] = 2;
      session()->set(array('userresult'=> $result));
    }*/
    ShowMsg("Account added successfully!", '/account/index');
  }
}