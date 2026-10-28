<?php namespace App\Controllers;
use App\Libraries\Passwords;
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

  /**
   * The sign-up form posts here. Every field is checked first; if any is wrong, the form comes back with the typed
   * values (not the password) and the problem shown under each field.
   */
  function registerinfo()
  {
    $type = (string)$this->request->getGet('REGISTERTYPE');

    if ($type == "Parents") {
      $fields = array('email', 're_email', 'primary_en_name', 'primary_cn_name', 'primary_phone', 'primary_relationship',
        'alter_contact_email', 'alter_en_name', 'alter_cn_name', 'alter_phone', 'alter_relationship', 'city', 'state',
        'zip', 'heard_from', 'referrer_id');
      $old = array();
      foreach ($fields as $field) {
        $old[$field] = trim((string)$this->request->getPost($field));
      }
      $plain = (string)$this->request->getPost('passwd');

      $errors = array();
      $required = array(
        'email' => 'Please enter your email address.',
        're_email' => 'Please type your email address again.',
        'primary_en_name' => "Please enter the primary contact's English name.",
        'primary_phone' => 'Please enter a phone number.',
        'primary_relationship' => 'Please enter the relationship (for example: Mother, Father).',
        'city' => 'Please enter your city.',
        'zip' => 'Please enter your zip code.',
      );
      foreach ($required as $field => $msg) {
        if ($old[$field] === '') {
          $errors[$field] = $msg;
        }
      }

      if (!isset($errors['email'])) {
        if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
          $errors['email'] = 'This is not a valid email address.';
        } elseif ($this->db->query("SELECT parent_id FROM parents WHERE email = ?", [$old['email']])->getNumRows() > 0) {
          $errors['email'] = 'This email already has an account. Please sign in, or use "Find password" if you forgot the password.';
        } elseif ($this->db->query("SELECT parent_id FROM parents WHERE alter_contact_email = ?", [$old['email']])->getNumRows() > 0) {
          $errors['email'] = 'This email is already the alternative contact of a family account. Please contact the school admin if you need another account.';
        }
      }
      if (!isset($errors['email']) && !isset($errors['re_email']) && strcasecmp($old['email'], $old['re_email']) !== 0) {
        $errors['re_email'] = 'The two email addresses are not the same.';
      }
      if (($problem = Passwords::problem($plain)) !== null) {
        $errors['passwd'] = $plain === '' ? 'Please choose a password. ' . Passwords::RULES : $problem;
      } elseif ((string)$this->request->getPost('passwd2') === '') {
        $errors['passwd2'] = 'Please type the password again.';
      } elseif ((string)$this->request->getPost('passwd2') !== $plain) {
        $errors['passwd2'] = 'The two passwords are not the same.';
      }
      if ($old['alter_contact_email'] !== '' && !filter_var($old['alter_contact_email'], FILTER_VALIDATE_EMAIL)) {
        $errors['alter_contact_email'] = 'This is not a valid email address.';
      }

      if ($errors) {
        echo view($_SESSION['tm'].'register/index.php', array('errors' => $errors, 'old' => $old));
        return;
      }

      $insertdata = array();
      foreach (array('email', 'primary_en_name', 'primary_cn_name', 'primary_phone', 'primary_relationship', 'alter_en_name',
        'alter_cn_name', 'alter_phone', 'alter_relationship', 'alter_contact_email', 'heard_from', 'city', 'state',
        'zip') as $field) {
        $insertdata[$field] = $old[$field];
      }
      // the street address is not asked at sign-up; the parent adds it on the account page
      $insertdata['address'] = '';
      $insertdata['passwd'] = Passwords::forStorage('parents', 'passwd', $plain, Passwords::parentLegacy($plain));

      $this->db->table('parents')
        ->insert($insertdata);

      $sql = "select * from parents where email = ?";
      $result = $this->db->query($sql, [$insertdata['email']])->getResultArray();
      // signed in as the new account: a new session id, and the password (hash) stays out of the session
      session()->regenerate(true);
      $parent = $result[0];
      unset($parent['passwd']);
      session()->set(array('userresult'=> array(0 => $parent, 'usertype' => 1)));

      // insert referral info if there is referer ID
      $referrer_id = $old['referrer_id'];

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