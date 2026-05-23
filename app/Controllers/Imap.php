<?php namespace App\Controllers;
use App\Libraries\CI_Imap;
/**
 * Imap_controller Class
 *
 * @package       CodeIgniter
 * @subpackage    Librarys
 * @category      Email
 * @version       1.0.0
 * @author        Natan Felles
 * @link          http://github.com/natanfelles/codeigniter-imap
 */

/**
 * Class Imap_controller
 *
 * @property Imap $imap
 */
class Imap extends BaseController {
  public $email = null;
  public $imap = null;
  public $db = null;
  public function __construct()
  {
    //$this->load->library('Imap');
    //$this->load->library('encrypt');
    $this->db = db_connect();
    $this->imap = new CI_Imap();
    //$config['wordwrap'] = TRUE;
    //$config['mailType'] = 'html';
    $config_send = array(
      'protocol'  => 'smtp',
      'SMTPHost' => 'ssl://smtp.googlemail.com',
      'SMTPPort' => 465,
      'SMTPUser' => 'paymentconfirmation@xilinnschinese.org',
      'SMTPPass' => 'P@ymentc0nfirm',
      'mailType'  => 'html',
      'wordWrap'  => true,
      'charset'   => 'utf-8'
    );
    $this->email = \Config\Services::email();
    $this->email->initialize($config_send);
    //$this->email->set_newline("\r\n");
    helper('pay_code');
  }
  public function index()
  {
    $config = array(
      'host'     => '{imap.gmail.com:993/imap/ssl/novalidate-cert}',
      'encrypto' => 'ssl',
      'user'     => 'auto.payment@xilinnschinese.org',
      'pass'     => '@utoPayment'
    );
    $processed = $notpay = $manual_process = [];

    try {
    $this->imap->imap_connect($config);

    /*$data['output'] = array(
      'get_folders'    => $this->imap->get_folders(),
      'select_folder'  => $this->imap->select_folder('INBOX'),
      'count_messages' => $this->imap->count_messages(),
      //'count_unread_messages'   => $this->imap->count_unread_messages(),
      //'get_all_email_addresses' => $this->imap->get_all_email_addresses(),
      //'add_folder'  => $this->imap->add_folder('Test'),
      //'move_message'   => $this->imap->move_message(1, 'Test'),
      //'count_messages' => $this->imap->count_messages(),
      //'get_messages'   => $this->imap->get_messages(5),
      //'get_message'    => $this->imap->get_message(4, TRUE),
      //'remove_folder'           => $this->imap->remove_folder('Notes'),
      'all_messages'     => $this->imap->get_messages(0, 0, 'ASC', TRUE, FALSE),
    );*/
    //$data['output']['session'] = session()->all_get();
    //var_dump($this->imap->get_folders()); exit;
    $the_mail = $this->imap->select_folder('INBOX');
    $all_msg = $this->imap->get_unread_messages(0, 0, 'ASC', TRUE);
    //var_dump($all_msg);
    $notpay = $processed = $manual_process = array();
    $to = 'ec@xilinnschinese.org';
    //$to = 'rufeng_liu@hotmail.com';
    /*$config_send = array(
      'protocol'  => 'smtp',
      'smtp_host' => 'ssl://smtp.googlemail.com',
      'smtp_port' => 465,
      'smtp_user' => 'paymentconfirmation@xilinnschinese.org',
      'smtp_pass' => 'P@ymentc0nfirm',
      'mailtype'  => 'html',
      'charset'   => 'utf-8'
    );*/
    //$this->email->initialize($config_send);
    //$this->email->set_mailtype("html");
    //$this->email->set_newline("\r\n");

    $avoid_dup = array();
    foreach($all_msg as $msg) {
      $sent_info = [];

      $subject = $msg['subject'];

      if(strpos($subject, ' sent you ') !== false) {
        $memo = stristr($msg['body'], 'Memo:');
        $memo = stristr($memo, 'Memo:</b>');
        $memo = str_ireplace('Memo:</b>', '', $memo);
        $memo = stristr($memo, '</td>', true);

        $sent_info = explode(' sent you ', $subject);
        //echo ' | Payment | ';
      } else if(strpos($subject, ' received money ') !== false) {
        //$sent_info = explode(' sent you ', $subject);
        //echo ' | Payment | ';
        $memo = stristr($msg['body'], 'Memo</td>');
        $memo = stristr($memo, 'font14">');
        $memo = str_ireplace('font14">', '', $memo);
        $memo = stristr($memo, '</td>', true);

        $amount = stristr($msg['body'], 'Amount</td>');
        $amount = stristr($amount, 'font14">');
        $amount = str_ireplace('font14">', '', $amount);
        $amount = stristr($amount, '</td>', true);
        $sent_info[1] = $amount;

        /*$serder_name = stristr($msg['body'], 'moPad">');
        $serder_name = stristr($serder_name, ' sent you money</td>', true);
        $serder_name = str_ireplace(' sent you money</td>', '', $serder_name);*/

        $serder_name = stristr($msg['body'], ' sent you money</td>', true);
        $serder_name = strrchr($serder_name, 'moPad">');
        $serder_name = str_ireplace(' sent you money</td>', '', $serder_name);
        $serder_name = $serder_name ? substr($serder_name, 7) : 'Parent';

        $sent_info[0] = $serder_name;

      } else {
        //Not a payment email, move it to NotPayment folder
        //$this->imap->move_message($msg['id'], 'NotPayment');
        //imap_mail_move($this->imap->stream, $msg['uid'], 'Processed', CP_UID);
        //$this->imap->delete_message($msg['id']);
        $notpay[] = $msg['uid'];
        //echo ' | move to NotPayment | ';
        continue;
      }

      $from = $msg['from'];

      if(isset($from['email']) && strpos($from['email'], 'chase.com') !== false && isset($from['name']) && strpos($from['name'], 'Chase') !== false) {
        //Ok, this is a payment, let's process it
        $date = $msg['date'];
        $pdate = date("Y-m-d H:i:s", $date);

        //$memo = ' heifeng_chicago123@y-ao.com:#66 ';
        $memo_err = 0;
        $parent_id = '';
        $email_chk = '';
        $parent_email = '';
        $memo = strtoupper(trim($memo));
        $original_memo = $memo;

        $sql = "select semester_id from semester where status = 1 and semester_status = 'Current'";
        $result = $this->db->query($sql)->getRowArray();
        $semester_id = (int)$result['semester_id'];

        preg_match("/[\d]{1,4}X[\d]{4}/", $memo, $matches);

        if(!empty($matches) && isset($matches[0])){
          $memo = $matches[0];

          if($memo) {
            $memo_arr = explode('X', $memo);
          } else {
            $memo_err = 1;
          }

          if(count($memo_arr) != 2) {
            $memo_err = 1;
          }

          $parent_id = intval($memo_arr[0]);
          $email_chk = intval($memo_arr[1]);

        } else {
          $memo_err = 1;
        }

        if($memo_err || empty($parent_id) || empty($email_chk)) {
          // no parent info, check if this is a donation
          if(stripos($original_memo, 'donation') !== false) {
            $this->handle_donation(null, $sent_info[0], '', $sent_info[1], $semester_id, $original_memo, '' );
            $processed[] = $msg['uid'];
            continue;
          }

          // email alert EC to process manually since there is no parent id detected
          $manual_process[] = $msg['uid'];
          $subject = 'FAILED Online Payment Notice';
          $msg_school = "<p>Dear Online Administrator,</p>";
          $msg_school .= "<p>There is a new online payment but WITHOUT parent ID or payment code, or the memo is not in correct format.
          Please login on the school's Chase account to verify and then process it manually using the school's admin tool.</p><br>";

          $message = "<p><b>Parent Name:</b> {$sent_info[0]}</p>";
          $message .= "<p><b>Memo:</b> {$original_memo}</p>";
          $message .= "<p><b>Total Paid:</b> ". $sent_info[1] . "</p>";
          $message .= "<p><b>Date & Time:</b> {$pdate}</p>";
          $message .= "<br><p>Thank you,</p><p>Web Admin</p>";

          /*$this->email->setFrom('paymentconfirmation@xilinnschinese.org', 'Xilin Online Payment');
          $this->email->setTo($to);
          $this->email->setBCC('rufeng_liu@hotmail.com');
          $this->email->setSubject($subject);
          $this->email->setMessage($msg_school . $message);
          $this->email->send();*/
          $headers = "From: Xilin Online Payment <paymentconfirmation@xilinnschinese.org>\r\n";
          $headers .= 'Bcc: ' . implode(',', ['rufeng_liu@hotmail.com']) . "\r\n";
          $headers .= "Reply-To: ec@xilinnschinese.org\r\n";
          $headers .= "MIME-Version: 1.0\r\n";
          $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
          @mail($to, $subject, $message, $headers, '-f paymentconfirmation@xilinnschinese.org');
          continue;
        } else {
          //$sql = "select * from parents where parent_id = $parent_id and (email like '".$email."%' or alter_contact_email like '".$email."%')";
          $sql = "select * from parents where parent_id = $parent_id limit 1";
          $result = $this->db->query($sql)->getRowArray();

          if($result) {
            $parent_email = $result['email'];
            $db_pay_code = get_code($parent_email);
            if($db_pay_code != $email_chk) {
              $memo_err = 1;
            }
          }
          if(!$result || $memo_err){
            // not correct parent info, check if this is a donation
            if(stripos($original_memo, 'donation') !== false) {
              $this->handle_donation(null, $sent_info[0], '', $sent_info[1], $semester_id, $original_memo, '' );
              $processed[] = $msg['uid'];
              continue;
            }

            $manual_process[] = $msg['uid'];
            $subject = 'FAILED Online Payment Notice';
            $msg_school = "<p>Dear Online Administrator,</p>";
            $msg_school .= "<p>There is a new online payment, however the parent ID and payment code can't resolve to an existing parent record.
          Please login on the school's Chase account to verify and then process it manually using the school's admin tool.</p><br>";

            $message = "<p><b>Parent Name:</b> {$sent_info[0]}</p>";
            $message .= "<p><b>Memo:</b> {$original_memo}</p>";
            $message .= "<p><b>Total Paid:</b> ". $sent_info[1] . "</p>";
            $message .= "<p><b>Date & Time:</b> {$pdate}</p>";
            $message .= "<br><p>Thank you,</p><p>Web Admin</p>";

            /*$this->email->setFrom('paymentconfirmation@xilinnschinese.org', 'Xilin Online Payment');
            $this->email->setTo($to);
            $this->email->setBCC('rufeng_liu@hotmail.com');
            $this->email->setSubject($subject);
            $this->email->setMessage($msg_school . $message);
            $this->email->send();*/

            $headers = "From: Xilin Online Payment <paymentconfirmation@xilinnschinese.org>\r\n";
            $headers .= 'Bcc: ' . implode(',', ['rufeng_liu@hotmail.com']) . "\r\n";
            $headers .= "Reply-To: ec@xilinnschinese.org\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            @mail($to, $subject, $message, $headers, '-f paymentconfirmation@xilinnschinese.org');

            continue;
          }
        }

        /*$insertdata['semester_id'] = $semester_id;
        $insertdata['parent_id'] = $parent_id;
        $insertdata['signature'] = $sent_info[0];
        $insertdata['pdate'] = $pdate;
        $insertdata['school_fee'] = floatval(preg_replace('/[^0-9\.]+/', '', $sent_info[1]));
        $insertdata['online_fee'] = 0;
        $insertdata['status'] = 'Pending';

        $result = $this->db->insert('online_payment', $insertdata);*/
        $checking = $parent_id.$pdate.$sent_info[1];
        echo $checking.'--';
        if(!in_array($checking, $avoid_dup)) {
          echo 'test 1 ';
          // good parent info, check if this is a donation
          if(stripos($original_memo, 'donation') !== false) {
            $this->handle_donation($parent_id, $sent_info[0], $parent_email, $sent_info[1], $semester_id, $original_memo, $parent_id.'X'.$email_chk );
            $processed[] = $msg['uid'];
            continue;
          }

          $avoid_dup[] = $checking;
          $check_amount = floatval(preg_replace('/[^0-9\.]+/', '', $sent_info[1]));

          $sqlx = "select check_id from checks where parent_id = ".$parent_id." and check_amount = ".$check_amount." and check_date >= NOW() - INTERVAL 30 MINUTE";
          $resultx = $this->db->query($sqlx)->getRowArray();
          if(isset($resultx) && isset($resultx['check_id']) && !empty($resultx['check_id'])) continue;

          $insertdata1['semester_id'] = $semester_id;
          $insertdata1['parent_id'] = $parent_id;
          $insertdata1['check_number'] =  $sent_info[0];
          $insertdata1['check_amount'] = $check_amount;
          $insertdata1['check_date'] = $pdate; //date("Y-m-d H:i:s");
          $insertdata1['received_by'] = 'Chase QP';
          $insertdata1['pay_type'] = 'Online';

          $result1 = $this->db->table('checks')
            ->insert($insertdata1);

          if ($result1) {
            echo 'test 2 ';
            $subject = 'Successful Online Payment Notice';
            $msg_school = "<p>Dear Online Administrator,</p>";
            $msg_school .= "<p>There is a new online payment in the following details. It's been automatically processed.
            Please login on the school's Chase account and the school's admin tool to double check.</p><br>";
            $message = "<p><b>Parent Id:</b> {$parent_id}</p>";
            $message .= "<p><b>Memo:</b> {$original_memo}</p>";
            $message .= "<p><b>Parent Name:</b> {$sent_info[0]}</p>";
            $message .= "<p><b>Total Paid:</b> ". $sent_info[1] . "</p>";
            $message .= "<p><b>Date & Time:</b> {$pdate}</p>";
            $message .= "<br><p>Thank you,</p><p>Web Admin</p>";

            /*$this->email->setFrom('paymentconfirmation@xilinnschinese.org', 'Xilin Online Payment');
            $this->email->setTo($to);
            $this->email->setBCC('rufeng_liu@hotmail.com');
            $this->email->setSubject($subject);
            $this->email->setMessage($msg_school . $message);
            $this->email->send();*/

            $headers = "From: Xilin Online Payment <paymentconfirmation@xilinnschinese.org>\r\n";
            $headers .= 'Bcc: ' . implode(',', ['rufeng_liu@hotmail.com']) . "\r\n";
            $headers .= "Reply-To: ec@xilinnschinese.org\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            @mail($to, $subject, $message, $headers, '-f paymentconfirmation@xilinnschinese.org');

            //send email to the payer
            $msg_p = "<p>Dear {$sent_info[0]},</p>";
            $msg_p .= "<p>We have processed your QuickPay by Zelle electronic payment of {$sent_info[1]} successfully, your account balance has been updated. For any payment/invoice issues, please contact ec@xilinnschinese.org.</p><br>";
            $msg_p .= "<br><p>Regards,</p><p>Xilin NS Chinese School</p>";

            /*$this->email->setFrom('paymentconfirmation@xilinnschinese.org', 'Xilin NS Online Payment');
            $this->email->setTo($parent_email);
            $this->email->setBCC('rufeng_liu@hotmail.com');
            $this->email->setSubject($subject);
            $this->email->setMessage($msg_p);
            $this->email->send();*/

            $headers = "From: Xilin Online Payment <paymentconfirmation@xilinnschinese.org>\r\n";
            $headers .= 'Bcc: ' . implode(',', ['rufeng_liu@hotmail.com']) . "\r\n";
            $headers .= "Reply-To: ec@xilinnschinese.org\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            @mail($parent_email, $subject, $msg_p, $headers, '-f paymentconfirmation@xilinnschinese.org');

            $processed[] = $msg['uid'];

          } else {
            echo 'test 3 ';
            // email alert EC to process manually since the database insertion failed
            $subject = 'FAILED Online Payment Notice';
            $msg_school = "<p>Dear Online Administrator,</p>";
            $msg_school .= "<p>There is a new online payment but failed to be inserted into the database.
            Please login on the school's Chase account to verify and then process it manually using the school's admin tool.</p><br>";

            $message = "<p><b>Parent Id:</b> {$parent_id}</p>";
            $message .= "<p><b>Memo:</b> {$original_memo}</p>";
            $message .= "<p><b>Parent Name:</b> {$sent_info[0]}</p>";
            $message .= "<p><b>Total Paid:</b> ". $sent_info[1] . "</p>";
            $message .= "<p><b>Date & Time:</b> {$pdate}</p>";
            $message .= "<br><p>Thank you,</p><p>Web Admin</p>";

            /*$this->email->setFrom('paymentconfirmation@xilinnschinese.org', 'Xilin Online Payment');
            $this->email->setTo($to);
            $this->email->setBCC('rufeng_liu@hotmail.com');
            $this->email->setSubject($subject);
            $this->email->setMessage($msg_school . $message);
            $this->email->send();*/

            $headers = "From: Xilin Online Payment <paymentconfirmation@xilinnschinese.org>\r\n";
            $headers .= 'Bcc: ' . implode(',', ['rufeng_liu@hotmail.com']) . "\r\n";
            $headers .= "Reply-To: ec@xilinnschinese.org\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            @mail($to, $subject, $message, $headers, '-f paymentconfirmation@xilinnschinese.org');

            continue;
          }
        }
      } else {
        //Not a payment email, move it to NotPayment folder
        //$this->imap->move_message($msg['id'], 'NotPayment');
        //imap_mail_move($this->imap->stream, $msg['uid'], 'NotPayment', CP_UID);
        //$this->imap->delete_message($msg['id']);
        $notpay[] = $msg['uid'];
        continue;
      }
    }
    } catch (\Exception $e) {
      var_dump($e->getMessage());
    }
    echo 'p ';
    var_dump($processed);
    echo 'n ';
    var_dump($notpay);
    echo 'm ';
    var_dump($manual_process);
    $this->payment_process($processed, $notpay, $manual_process);
    exit;
  }

  function payment_process($processed = array(), $notpay = array(), $manual_process = array()){
    $host = '{imap.gmail.com:993/imap/ssl/novalidate-cert}';
    $user = 'auto.payment@xilinnschinese.org';
    $pass = '@utoPayment';
    $inbox  = imap_open( $host, $user, $pass );
    $emails = imap_search( $inbox, 'ALL', SE_UID );

    if( $emails ) {
      foreach( $emails as $email_uid ) {
        var_dump($email_uid);
        if(in_array($email_uid, $processed)) {
          imap_mail_move($inbox, $email_uid, 'Processed', CP_UID);
        } elseif(in_array($email_uid, $notpay)) {
          imap_mail_move($inbox, $email_uid, 'NotPayment', CP_UID);
        } elseif(in_array($email_uid, $manual_process)) {
          imap_mail_move($inbox, $email_uid, 'ManuelProcess', CP_UID);
        }

      }
    }
  }

  function handle_donation($parent_id, $name, $parent_email, $amount, $semester_id, $memo, $pay_code='') {

    $amount = str_replace('$', '', $amount);
    //$date = date("Y-m-d H:i:s");
    //$to = 'rufeng_liu@hotmail.com';
    $memo_email = '';
    // first check if the demo contains email address

    preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $memo, $matches);
    if(is_array($matches) && isset($matches[0][0]) && strlen($matches[0][0]) > 5) {
      $memo_email = $matches[0][0];
    }

    if($parent_id && $parent_email) {
      // if the parent info is available
      $insertdata1['semester_id'] = $semester_id;
      $insertdata1['parent_id'] = $parent_id;
      $insertdata1['name'] =  $name;
      $insertdata1['amount'] = $amount;
      $insertdata1['email'] = $parent_email;
      $insertdata1['memo'] = $memo;

      $this->db->table('donation')
        ->insert($insertdata1);

      $insertdata1['invoice_date'] = date('m/d/Y');
      $insertdata1['pay_code'] = $pay_code;

      $content = view($_SESSION['tm']."account/donation.php", $insertdata1);
      $this->sendInvoiceEmail($content, $parent_email, $memo_email);

    } elseif($memo_email) {
      // no parent info, but demo contains email
      $insertdata1['semester_id'] = $semester_id;
      $insertdata1['parent_id'] = '';
      $insertdata1['name'] =  $name;
      $insertdata1['amount'] = $amount;
      $insertdata1['email'] = $memo_email;
      $insertdata1['memo'] = $memo;

      $this->db->table('donation')
        ->insert($insertdata1);

      $insertdata1['invoice_date'] = date('m/d/Y');
      $insertdata1['pay_code'] = '';

      $content = view($_SESSION['tm']."account/donation.php", $insertdata1);
      $this->sendInvoiceEmail($content, '', $memo_email);
    } else {
      // donation without any email info, just send to EC for record
      $insertdata1['semester_id'] = $semester_id;
      $insertdata1['parent_id'] = '';
      $insertdata1['name'] =  $name;
      $insertdata1['amount'] = $amount;
      $insertdata1['email'] = '';
      $insertdata1['memo'] = $memo;

      $this->db->table('donation')
        ->insert($insertdata1);

      $insertdata1['invoice_date'] = date('m/d/Y');
      $insertdata1['pay_code'] = '';

      $content = view("xilin7/account/donation.php", $insertdata1);
      $this->sendInvoiceEmail($content, '', '');
    }

  }

  function sendInvoiceEmail($content, $parent_email='', $memo_email = '')
  {
    $ec_email = 'ec@xilinnschinese.org';
    $headers = "From: Xilin Online Payment <paymentconfirmation@xilinnschinese.org>\r\n";
    $headers .= 'Bcc: ' . implode(',', ['rufeng_liu@hotmail.com']) . "\r\n";
    $headers .= "Reply-To: ec@xilinnschinese.org\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    $subject = 'Xilin Northshore Chinese School Donation Receipt';
    if($parent_email) {
      $to = $parent_email;
      if($memo_email && $memo_email != $parent_email ) {
        $headers .= 'Cc: ' . $memo_email . "\r\n";
      }
      $headers .= 'Cc: ' . $ec_email . "\r\n";
    } elseif($memo_email) {
      $subject .= '.';
      $to = $memo_email;
      $headers .= 'Cc: ' . $ec_email . "\r\n";
    } else {
      $subject .= '!';
      $to = $ec_email;
    }

    @mail($to, $subject, $content, $headers, '-f paymentconfirmation@xilinnschinese.org');

    /*$email = \Config\Services::email();
    $config['wordWrap'] = TRUE;
    $config['mailType'] = 'html';
    $email->initialize($config);

    var_dump($parent_email);
    var_dump($memo_email);
    $subject = 'Xilin Northshore Chinese School Donation Receipt';

    $email->setFrom('ec@xilinnschinese.org', 'Xilin Northshore Chinese School');
    if($parent_email) {
      $email->setTo($parent_email);
      if($memo_email && $memo_email != $parent_email ) {
        $email->setCC($memo_email);
      }
      //$email->setCC('ec@xilinnschinese.org');
    } elseif($memo_email) {
      $subject .= '.';
      $email->setTo($memo_email);
      echo 'sent in';
      //$email->setCC('ec@xilinnschinese.org');
    } else {
      $subject .= '!';
      //$email->setTo('ec@xilinnschinese.org');
      $email->setTo('rufeng_liu@hotmail.com');
    }

    $email->setBCC('rufeng_liu@hotmail.com');
    $email->setSubject($subject);
    $email->setMessage($content);
    $email->send();*/

    return true;
  }
}