<?php namespace App\Controllers;
use App\Models\{Semester, Subject, Classes, Teacher, Article};

/**
 *
 * login
 *
 */
class Tlogin extends BaseController
{

  public $db = null;

  function __construct()
  {
    $this->db = db_connect();
    session()->set(array('current_tab' => 'login'));
  }

  function index()
  {
    echo view($_SESSION['tm'].'/signin/');
  }

  /**
   * Teachers sign in with their Google account: the page's Google button sends the ID token here (?token=).
   */
  function g_login()
  {
    $client_id = xilin_secret('google_client_id', '787488937189-p0prt8vpmf7vrg3c51681u1d7q6kchv2.apps.googleusercontent.com');

    $gClient = new \Google_Client();
    $gClient->setApplicationName('Login to Xilin');
    $gClient->setClientId($client_id);

    $email = '';
    $token = (string)$this->request->getGet('token');
    if ($token !== '') {
      try {
        // checks the signature, expiry and that the token was issued for this site (client id)
        $payload = $gClient->verifyIdToken($token);
      } catch (\Throwable $e) {
        log_message('notice', 'xilin: Google sign-in token rejected: ' . $e->getMessage());
        $payload = false;
      }
      if (!empty($payload) && !empty($payload['email']) && !empty($payload['email_verified'])) {
        $email = strtolower($payload['email']);
      }
    }

    if ($email === '') {
      return redirect()->to('/signin/index/teacher');
    } elseif (substr($email, -strlen('@xilinnschinese.org')) !== '@xilinnschinese.org' && strpos($email, 'rufeng') === false) {
      return redirect()->to('/signin/index/teacher');
    }

    $tresult = $this->db->query("select * from teachers where email = ?", [$email])->getResultArray();

    if ($tresult) {
      // a new session id on sign-in; teachers have no password (Google), so none in the session either
      session()->regenerate(true);
      unset($tresult[0]['passwd'], $tresult[0]['password']);
      session()->set(array('userresult'=> array(0 => $tresult[0], 'usertype' => 2)));
      return redirect()->to('/account/index');
    } else {
      return redirect()->to('/signin/index/teacher');
    }
  }

}

/* End of file login.php */
