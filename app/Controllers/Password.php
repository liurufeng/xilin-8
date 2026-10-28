<?php
namespace App\Controllers;

use App\Libraries\{LoginCode, Passwords};

/**
 * Forgot password: a parent enters their email (forgot), types in the 6-digit code emailed to it (code), then
 * chooses a new password (reset, save). Codes are sent like the sign-in codes (LoginCode, kind 'reset'); a link in
 * the email was filtered out on the way.
 */
class Password extends BaseController
{
  public $db = null;

  // how long the new-password form works after the right code
  private const RESET_SECONDS = 900;

  function __construct()
  {
    $this->db = db_connect();
    session()->set(array('current_tab' => 'signin'));
  }

  /**
   * Asks for the email and sends the code. The answer is the same whether or not the address has an account, so it
   * can't be used to find out who is registered.
   */
  public function forgot()
  {
    $data['email'] = '';
    if (strtolower($this->request->getMethod()) !== 'post') {
      echo view($_SESSION['tm'] . 'signin/forgot.php', $data);
      return;
    }

    $email = trim((string)$this->request->getPost('email'));
    $data['email'] = $email;
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
      $data['error_msg'] = 'Please enter the email address you registered with.';
    } elseif (!Passwords::attemptAllowed('findpass', $email)) {
      $data['error_msg'] = 'Too many requests. Please wait 10 minutes and try again.';
    } else {
      $parent = $this->db->query("SELECT parent_id from parents where email=? LIMIT 1", [$email])->getRowArray();
      if (!$parent && $this->db->query("SELECT teacher_id from teachers where email=? LIMIT 1", [$email])->getRowArray()) {
        $data['error_msg'] = 'Teachers sign in with their Google (xilinnschinese.org) account; no password is needed.';
      } else {
        session()->remove('reset_parent');
        LoginCode::start('reset', $parent ? array('parent_id' => (int)$parent['parent_id']) : array(), $email);
        return redirect()->to(base_url('/password/code'));
      }
    }
    echo view($_SESSION['tm'] . 'signin/forgot.php', $data);
  }

  // the code sent by forgot
  public function code()
  {
    if (LoginCode::pending('reset') === null) {
      return redirect()->to(base_url('/password/forgot'));
    }
    if (strtolower($this->request->getMethod()) !== 'post') {
      echo view($_SESSION['tm'] . 'signin/verify.php', $this->codePage());
      return;
    }

    $user = LoginCode::finish('reset', (string)$this->request->getPost('code'));
    if ($user === null) {
      if (LoginCode::pending('reset') === null) {
        ShowMsg('The code expired or was entered wrong too many times. Please ask for a new one.', '/password/forgot', 0, 3000);
      }
      echo view($_SESSION['tm'] . 'signin/verify.php', $this->codePage(array('error_msg' => 'That code is not right. Please check the email and try again.')));
      return;
    }

    session()->set('reset_parent', array('parent_id' => $user['parent_id'], 'until' => time() + self::RESET_SECONDS));
    return redirect()->to(base_url('/password/reset'));
  }

  // emails a new code (the button on the code page)
  public function resend()
  {
    if (LoginCode::pending('reset') === null) {
      return redirect()->to(base_url('/password/forgot'));
    }
    $sent = LoginCode::send('reset');
    echo view($_SESSION['tm'] . 'signin/verify.php', $this->codePage(array(
      'success_msg' => $sent ? 'A new code has been sent.' : '',
      'error_msg' => $sent ? '' : 'Please wait a minute before asking for another code.',
    )));
  }

  // the new-password form, after the right code
  public function reset()
  {
    if ($this->resetParentId() === null) {
      return redirect()->to(base_url('/password/forgot'));
    }
    echo view($_SESSION['tm'] . 'signin/reset.php');
  }

  public function save()
  {
    $parent_id = $this->resetParentId();
    if ($parent_id === null) {
      ShowMsg('This page has expired. Please ask for a new code.', '/password/forgot', 0, 3000);
    }

    $pass = (string)$this->request->getPost('passwd');
    $data = array();
    if (($problem = Passwords::problem($pass)) !== null) {
      $data['error_msg'] = $problem;
    } elseif ($pass !== (string)$this->request->getPost('passwd2')) {
      $data['error_msg'] = 'The two passwords are not the same.';
    } else {
      $this->db->table('parents')
        ->where('parent_id', $parent_id)
        ->update(array('passwd' => Passwords::forStorage('parents', 'passwd', $pass, Passwords::parentLegacy($pass))));
      session()->remove('reset_parent');

      ShowMsg('Your password has been changed. Please sign in with your new password.', '/signin', 0, 3000);
    }

    echo view($_SESSION['tm'] . 'signin/reset.php', $data);
  }

  // the parent whose code was right, while the new-password form still works
  private function resetParentId(): ?int
  {
    $reset = session()->get('reset_parent');
    if (!is_array($reset) || time() > $reset['until']) {
      session()->remove('reset_parent');
      return null;
    }
    return (int)$reset['parent_id'];
  }

  // signin/verify.php, set up for the reset code
  private function codePage(array $data = array()): array
  {
    return $data + array(
      'masked_email' => LoginCode::maskedTo('reset'),
      'title' => 'Enter your password reset code',
      'action' => '/password/code',
      'resend_action' => '/password/resend',
      'start_over' => '/password/forgot',
      'submit_label' => 'Continue',
    );
  }
}
