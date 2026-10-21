<?php
namespace App\Controllers;

use App\Libraries\Passwords;

/**
 * A parent chooses a new password from the link "Find password" emailed (Account::findpass).
 */
class Password extends BaseController
{
  public $db = null;

  function __construct()
  {
    $this->db = db_connect();
    session()->set(array('current_tab' => 'signin'));
  }

  // the form, if the link still works
  public function reset($token = '')
  {
    $data['token'] = (string)$token;
    if (Passwords::findResetToken($data['token']) === null) {
      $data['error_msg'] = 'This link has expired or was already used. Please click "Find password" again to get a new one.';
      $data['token'] = '';
    }
    echo view($_SESSION['tm'] . 'signin/reset.php', $data);
  }

  public function save()
  {
    $token = (string)$this->request->getPost('token');
    $pass = (string)$this->request->getPost('passwd');
    $data['token'] = $token;

    $row = Passwords::findResetToken($token);
    if ($row === null || $row['user_type'] !== 'parent') {
      $data['error_msg'] = 'This link has expired or was already used. Please click "Find password" again to get a new one.';
      $data['token'] = '';
    } elseif (mb_strlen($pass) < Passwords::MIN_LENGTH) {
      $data['error_msg'] = 'Please use at least ' . Passwords::MIN_LENGTH . ' characters.';
    } elseif ($pass !== (string)$this->request->getPost('passwd2')) {
      $data['error_msg'] = 'The two passwords are not the same.';
    } else {
      $this->db->table('parents')
        ->where('parent_id', (int)$row['user_id'])
        ->update(array('passwd' => Passwords::forStorage('parents', 'passwd', $pass, Passwords::parentLegacy($pass))));
      Passwords::useResetToken($row);

      ShowMsg('Your password has been changed. Please sign in with your new password.', '/signin', 0, 3000);
    }

    echo view($_SESSION['tm'] . 'signin/reset.php', $data);
  }
}
