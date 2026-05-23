<?php
namespace App\Controllers;
use App\Models\{Semester, Subject, Classes, Teacher, Student, Pod_model,Discount_model};

/**
 *
 */
class Account extends BaseController
{
  public $db = null;
  public $sess = null;
  function __construct()
  {
    $this->db = db_connect();
    $this->sess = session();
    session()->set(array('current_tab' => 'account'));
    helper('pay_code');
  }

  function index()
  {
    session()->set(array('account_tab' => 'account'));
    $userinfo = session()->get('userresult');

    if (!empty($userinfo)) {

      $type = $userinfo['usertype'];
      if ($type == 1) {
        $id = $userinfo[0]['parent_id'];

        $sql = "select * from parents where parent_id = $id";
        $userinfodata = $this->db->query($sql)->getResultArray();
        $data['parent'] = $userinfodata[0];
        $data['usertype'] = $type;

        echo view($_SESSION['tm']."common/account/index.php", $data);
      } else {
        $id = $userinfo[0]['teacher_id'];
        $data['teacher'] = $userinfo[0];
        $data['usertype'] = $type;

        $where = " where t.teacher_id = $id and c.status=1 ";
        //get the semester info
        $semester = new Semester();
        $data['semesters'] = $semester->getSemesters();
        if(!session()->get('current_semester')){
          $semester->getCurrentSemester();
        }

        //$semester_id = $this->input->get('semester_id');
        $semester_id = $this->request->getVar('semester_id');
        if(isset($semester_id) && !empty($semester_id)) {
          session()->set(array('semester_id' => $semester_id));
          $where .=' and c.semester_id='.$semester_id;
        } else {
          $current_semester = session()->get('current_semester');
          $current_semester_id = $current_semester['semester_id'];
          session()->set(array('semester_id' => $current_semester_id));
          $where .=' and c.semester_id='.$current_semester_id;
        }

        //get the selected semester's classes and students info of this teacher
        $sql = "select t.*, p.*, s.*,c.*, sc.*, c.class_id class_id
            from teachers t
            join classes c on c.teacher_id = t.teacher_id
            left join studentclasses sc on sc.class_id = c.class_id and sc.deleted < 1
            left join students s on s.student_id = sc.student_id
            left join parents p on p.parent_id = s.parent_id
            ".$where."
            order by c.subject_id, c.class_code, p.parent_id, s.student_id";

        $all_data = $this->db->query($sql)->getResultArray();
        $students = $classes = $parent_emails = array();
        foreach($all_data as $a) {
          $classes[$a['class_id']] = $a;
          if(!isset($parent_emails[$a['class_id']])) {
            $parent_emails[$a['class_id']] = '';
          }
          if(!empty($a['student_id'])) {
            $students[$a['class_id']][$a['student_id']] = $a;
            if(isset($parent_emails[$a['class_id']]) && strpos($parent_emails[$a['class_id']], $a['email'])===false) {
              $parent_emails[$a['class_id']] .= $a['email'] . ',';
            }
            if(!empty($a['alter_contact_email']) && isset($parent_emails[$a['class_id']]) && strpos($parent_emails[$a['class_id']], $a['alter_contact_email'])===false){
              $parent_emails[$a['class_id']] .= $a['alter_contact_email'].',';
            }
          } else {
            $students[$a['class_id']][0] = $a;
            $parent_emails[$a['class_id']] .= '';
          }
        }

        $data['classes'] = $classes;
        $data['students'] = $students;
        $data['parent_emails'] = $parent_emails;

        echo view($_SESSION['tm']."common/account/teacher.php", $data);
      }
    } else {
      ShowMsg('Login is not correct!', '/signin/', 0, 1500);
    }
  }

  function students()
  {
    $data['address_error'] = false;
    if(!$this->check_address()){
      $data['address_error'] = TRUE;
    }

    $userinfo = session()->get('userresult');
    session()->set(array('account_tab' => 'students'));

    if (!empty($userinfo)) {
      $type = $userinfo['usertype'];
      if ($type == 1) {
        $id = $userinfo[0]['parent_id'];

        $student = new Student();
        $data['students'] = $student->getParentStudents($id);

        $data['usertype'] = $type;

        echo view($_SESSION['tm']."common/account/my_students.php", $data);
      }
    } else {
      ShowMsg('Session expired, please login again!', '/signin/', 0, 1500);
    }
  }

  function add_student()
  {
    $userinfo = session()->get('userresult');

    if (!empty($userinfo)) {
      $type = $userinfo['usertype'];
      if ($type == 1) {
        $parent_id = $userinfo[0]['parent_id'];
        $insertdata['parent_id'] = $parent_id;
        $insertdata['en_name'] = $this->request->getVar('en_name');
        $insertdata['cn_name'] = $this->request->getVar('cn_name');
        $insertdata['birthday'] = $this->request->getVar('birthday');
        $insertdata['gender'] = $this->request->getVar('gender');
        $insertdata['race'] = $this->request->getVar('race');

        $success_msg = 'Your have successfully added a new student!';
        $result = $this->db->table('students')
          ->insert($insertdata);
        if ($result) {
          $resp = array('success' => TRUE);
          sendJson($resp);
        }
      }
    } else {
      ShowMsg('Session expired, please login again!', '/signin/', 0, 1500);
    }
  }

  function remove_student()
  {
    $userinfo = session()->get('userresult');

    if (!empty($userinfo)) {
      $type = $userinfo['usertype'];
      if ($type == 1) {
        $parent_id = $userinfo[0]['parent_id'];
        $insertdata['status'] = 2;

        //check if the student has regisetered classes
        $student = new Student();
        $student_classes = $student->getStudentClasses($this->request->getVar('stdid'));
        if(count($student_classes) > 0) {
          $resp = array('success' => FALSE, 'msg' => 'The student has registered class(es) and can not be removed!');
          sendJson($resp);
          return;
        }

        $wheredata['student_id'] = $this->request->getVar('stdid');
        $wheredata['parent_id'] = $parent_id;
        $result = $this->db->table('students')
          ->where($wheredata)
        ->update($insertdata);
        $success_msg = 'Your have successfully removed the student!';

        if ($result) {
          $resp = array('success' => TRUE);
          sendJson($resp);
          return;
        }

      }
    } else {
      ShowMsg('Session expired, please login again!', '/signin/', 0, 1500);
    }
  }

  function update_student()
  {
    $userinfo = session()->get('userresult');

    if (!empty($userinfo)) {
      $type = $userinfo['usertype'];
      if ($type == 1) {
        $parent_id = $userinfo[0]['parent_id'];
        $insertdata['en_name'] = $this->request->getVar('en_name');
        $insertdata['cn_name'] = $this->request->getVar('cn_name');
        $insertdata['birthday'] = $this->request->getVar('birthday');
        $insertdata['gender'] = $this->request->getVar('gender');
        $insertdata['race'] = $this->request->getVar('race');

        $wheredata['student_id'] = $this->request->getVar('stdid');
        $wheredata['parent_id'] = $parent_id;
        $result = $this->db->table('students')
          ->where($wheredata)
          ->update($insertdata);
        $success_msg = 'The student info has been successfully updated!';

        if ($result) {
          $resp = array('success' => TRUE);
          sendJson($resp);
          return;
        }

      }
    } else {
      ShowMsg('Session expired, please login again!', '/signin/', 0, 1500);
    }
  }

  function invoice()
  {
    $uri = service('uri');
    if($uri->getTotalSegments() > 3 ) {
      $semester_id = $uri->getSegment(3);
      $parent_id = $uri->getSegment(4);
    }

    if(isset($parent_id) && ctype_digit($parent_id)) {
      $sql = "select * from parents where parent_id = $parent_id";
      $userinfo = $this->db->query($sql)->getResultArray();
    } else {
      $userinfo = session()->get('userresult');
    }

    $semester = new Semester();
    if(!isset(session()->get('current_semester')['semester_id'])){
      $semester->getCurrentSemester();
    }

    if(isset($semester_id) && !empty($semester_id) && ctype_digit(strval($semester_id))) {
      $current_semester_id = $semester_id;
    } else {
      $current_semester_id = session()->get('current_semester')['semester_id'];
    }
    $current_semester = $semester->getSemester($current_semester_id);

    if (!empty($userinfo)) {
        $id = $userinfo[0]['parent_id'];
        $data['parent'] = $userinfo[0];

        $student = new Student();
        $data['students'] = $student->getParentStudents($id);

        $last_update = array();
        foreach($data['students'] as $s) {
          $classes[$s['student_id']] =  $student->getStudentClasses($s['student_id'], $current_semester_id);
        }

        foreach ($classes as $cls) {
          foreach ($cls as $c) {
          $last_update[] = $c['update_time'];
          }
        }
        if(empty($last_update)) {
          $invoice_date = date('m/d/Y');
        } else {
          $invoice_date = date('m/d/Y',strtotime(max($last_update)));
        }

        $data['invoice_date'] = $invoice_date;

        //get the previous semester
        $sql = "SELECT *
            FROM semester
            WHERE (semester_id) IN
            ( SELECT MAX(semester_id)
              FROM semester
              where semester_id < " . $current_semester_id . "
              and status = 1
              -- and semester_status = 'Previous'
            )";

        $prev_semester = $this->db->query($sql)->getRowArray();

        //pod
        $pod_m = new Pod_model();
        $pod = $pod_m->getPODCharge($userinfo[0], $current_semester, $prev_semester);

        //discount

        //paid
        $sql = "select *
              from checks
              where parent_id = $id
              and semester_id = " . $current_semester_id;
        $checks = $this->db->query($sql)->getResultArray();
        $paid = 0;
        $book_charge = $prorate = $payments = array();
        foreach($checks as $check) {
          if($check['pay_type'] == 'Bk Chrg'){
            $book_charge[] = -$check['check_amount'];
          } elseif($check['pay_type'] == 'Prorate'){
            $prorate[] = -$check['check_amount'];
            $paid += $check['check_amount'];
          } else {
            $paid += $check['check_amount'];
            $payments[] = $check['check_amount'];
          }
        }

        $data['book_charge'] = $book_charge;
        $data['prorate'] = $prorate;
        $data['payments'] = $payments;
        $data['classes'] = $classes;
        $data['usertype'] = 1;

        $data['pod'] = $pod;
        $data['paid'] = $paid;

      $data['pay_code'] = $id . 'X' . get_code($userinfo[0]['email']);

      $disc = new Discount_model();
      $data['disc'] = $disc;

      // get late fee info
      $data['late_fee'] = 0;
      $sql = "select *
              from late_fee
              where parent_id = $id
              and semester_id = " . $current_semester_id;
      $late_fee_arr = $this->db->query($sql)->getRowArray();
      if(isset($late_fee_arr)) {
        $data['late_fee'] = $late_fee_arr['amount'];
      }

      if($uri->getTotalSegments() >= 3 ) {
        $action = $uri->getSegment(3);
      }

        if(isset($action) && $action == 'receipt') {
          $data['receipt'] = 'Yes';
        } elseif(isset($action) && $action == 'agreement') {
          $data['agreement'] = 'Yes';
        }

        if(isset($action) && $action == 'pay_info') {
          $data['semester'] = $current_semester;
          echo view($_SESSION['tm']."account/pay_info.php", $data);
        } elseif(isset($action) && $action == 'PaymentInstruction') {
          $data['semester'] = $current_semester;

          // load the invoice pop up
          echo view($_SESSION['tm']."account/invoice_new.php", $data);

          // send invoice to parent
          $content = view($_SESSION['tm']."account/invoice_new.php", $data);
          //$this->sendInvoiceEmail($content);
        } else {
          $data['semester'] = $current_semester;
          echo view($_SESSION['tm']."account/invoice_new.php", $data);
        }
    } else {
      ShowMsg('Session expired, please login again!', '/signin/', 0, 1500);
    }
  }

  function teacher_info()
  {
    $userinfo = session()->get('userresult');

    if (!empty($userinfo)) {
      $type = $userinfo['usertype'];
      if ($type == 2) {

        $data['usertype'] = $type;
        $data['teacher'] = $userinfo[0];

        echo view($_SESSION['tm']."common/account/teacher_info.php", $data);
      }
    } else {
      ShowMsg('Session expired, please login again!', '/signin/', 0, 1500);
    }
  }

  function update_teacher()
  {
    $userinfo = session()->get('userresult');

    if (!empty($userinfo)) {
      $type = $userinfo['usertype'];
      if ($type == 2) {
        $data['usertype'] = $type;
        $data['teacher'] = $userinfo[0];
        $teacher_id = $userinfo[0]['teacher_id'];

        if(trim($this->request->getVar('en_name')) == '' || trim($this->request->getVar('passwd')) == ''
          || trim($this->request->getVar('address')) == '' || trim($this->request->getVar('phone1')) == '' || trim($this->request->getVar('phone2')) == ''){
          $data['error_msg'] = 'Missing information, please provide all required information!';
          echo view($_SESSION['tm']."common/account/teacher_info.php", $data);
        }

        $insertdata['en_name'] = $this->request->getVar('en_name');
        $insertdata['cn_name'] = $this->request->getVar('cn_name');
        //$insertdata['email'] = $this->request->getVar('email');
        $insertdata['passwd'] = $this->request->getVar('passwd');
        $insertdata['address'] = $this->request->getVar('address');
        $insertdata['phone1'] = $this->request->getVar('phone1');
        $insertdata['phone2'] = $this->request->getVar('phone2');
        $insertdata['desc_link'] = $this->request->getVar('desc_link');

        $wheredata['teacher_id'] = $teacher_id;
        $result = $this->db->table('teachers')
          ->where($wheredata)
          ->update($insertdata);

        if ($result) {
          $teacher = new Teacher();
          $result = $teacher->getTeacher($teacher_id);
          $result['usertype'] = 2;
          session()->set(array('userresult'=> ['0'=>$result, 'usertype'=> 2]));

          $data['usertype'] = $type;
          $data['teacher'] = $result;

          $data['success_msg'] = 'Your teacher info has been successfully updated!';
          echo view($_SESSION['tm']."common/account/teacher_info.php", $data);
        } else {
          $data['error_msg'] = "Sorry, but teacher's info can't be updated at this time!";
          echo view($_SESSION['tm']."common/account/teacher_info.php", $data);
        }
      }
    } else {
      ShowMsg('Session expired, please login again!', '/signin/', 0, 1500);
    }
  }

  function update_parent()
  {
    $userinfo = session()->get('userresult');

    if (!empty($userinfo)) {
      $type = $userinfo['usertype'];
      if ($type == 1) {
        $data['usertype'] = $type;
        $data['parent'] = $userinfo[0];
        $parent_id = $userinfo[0]['parent_id'];

        if(trim($this->request->getVar('primary_en_name')) == '' || trim($this->request->getVar('email')) == '' || trim($this->request->getVar('passwd')) == ''
          || trim($this->request->getVar('address')) == '' || trim($this->request->getVar('primary_phone')) == '' || trim($this->request->getVar('primary_relationship')) == ''){
          $data['error_msg'] = 'Missing information, please provide all required information!';
          echo view($_SESSION['tm']."common/account/index.php", $data);
        }

        $insertdata['email'] = $this->request->getVar('email');
        $pass = str_replace("'","[$!)", $this->request->getVar('passwd'));
        $insertdata['passwd'] = $pass;
        //$insertdata['passwd'] = $this->request->getVar('passwd');
        $insertdata['primary_en_name'] = $this->request->getVar('primary_en_name');
        $insertdata['primary_cn_name'] = $this->request->getVar('primary_cn_name');
        $insertdata['primary_phone'] = $this->request->getVar('primary_phone');
        $insertdata['primary_relationship'] = $this->request->getVar('primary_relationship');
        $insertdata['alter_en_name'] = $this->request->getVar('alter_en_name');
        $insertdata['alter_cn_name'] = $this->request->getVar('alter_cn_name');
        $insertdata['alter_phone'] = $this->request->getVar('alter_phone');
        $insertdata['alter_relationship'] = $this->request->getVar('alter_relationship');
        $insertdata['alter_contact_email'] = $this->request->getVar('alter_contact_email');
        $insertdata['address'] = $this->request->getVar('address');
        $insertdata['city'] = $this->request->getVar('city');
        $insertdata['state'] = $this->request->getVar('state');
        $insertdata['zip'] = $this->request->getVar('zip');

        $wheredata['parent_id'] = $parent_id;
        $result = $this->db->table('parents')
        ->where($wheredata)
        ->update($insertdata);


        if ($result) {
          $sql = "select * from parents
          where parent_id = $parent_id";
          $result = $this->db->query($sql)->getRowArray();
          $result['usertype'] = 1;
          session()->set(array('userresult'=> ['0'=>$result, 'usertype'=> 1]));
          $data['usertype'] = $type;
          $data['parent'] = $result;

          $data['success_msg'] = 'Your profile has been successfully updated!';
          echo view($_SESSION['tm']."common/account/index.php", $data);
        } else {
          $data['error_msg'] = "Sorry, but teacher's info can't be updated at this time!";
          echo view($_SESSION['tm']."common/account/index.php", $data);
        }
      }
    } else {
      ShowMsg('Session expired, please login again!', '/signin/', 0, 1500);
    }
  }

  function findpass()
  {
    $email = $this->request->getVar('email');
    if(!isset($email) || trim($email) == '') {
      $resp = array('success' => FALSE, 'msg' => 'Empty email!');
      sendJson($resp);
      return;
    }
    $query = "SELECT passwd from parents where email='".$email."' LIMIT 1";
    $result = $this->db->query($query)->getRowArray();
    if ($result) {
      $pass = str_replace("[$!)", "'", $result['passwd']);
      $to = $email;
      $from = $_SESSION['site_email'];
      $from_header = "From: $from";
      $subject = "Your password at ".$_SERVER['HTTP_HOST'];
      $content = "Your password to access the ".$_SERVER['HTTP_HOST']." online registration is ".$pass;
      mail($to, $subject, $content, $from_header);

      $resp = array('success' => TRUE, 'msg' => "We've sent your password to your email at ".  $email);
      sendJson($resp);
      return;
    } else {
      //check if this is at teacher's account
      $query = "SELECT passwd from teachers where email='".$email."' LIMIT 1";
      $result = $this->db->query($query)->getRowArray();
      if ($result) {
        $to = $email;
        $from = $_SESSION['site_email'];
        $from_header = "From: $from";
        $subject = "Your password at ".$_SERVER['HTTP_HOST'];
        $content = "Your password to access the ".$_SERVER['HTTP_HOST']." online registration is ".$result['passwd'];
        mail($to, $subject, $content, $from_header);

        $resp = array('success' => TRUE, 'msg' => "We've sent your password to your email at ".  $email);
        sendJson($resp);
        return;
      } else {
        $resp = array('success' => FALSE, 'msg' => "We could NOT find the account registered by your email: " . $email );
        sendJson($resp);
        return;
      }
    }
  }

  function online_payment()
  {
    $userinfo = session()->get('userresult');

    if (!empty($userinfo)) {
      $type = $userinfo['usertype'];
      if ($type == 1) {
        $data['usertype'] = $type;
        $data['parent'] = $userinfo[0];
        $parent_id = $userinfo[0]['parent_id'];

        $balance = $this->uri->segment(3);
        $fee = $this->uri->segment(4);
        $sig = $this->uri->segment(5);
        $pdate = $this->uri->segment(6);

        if($balance == '' || $fee == '' || $sig == '' || $pdate == ''){
          redirect('/account/invoice/online', 'refresh');
        }

        $sig = urldecode($sig);
        $pdate = urldecode($pdate);

        $current_semester = session()->get('current_semester');
        $current_semester_id = $current_semester['semester_id'];

        $insertdata['semester_id'] = $current_semester_id;
        $insertdata['parent_id'] = $parent_id;
        $insertdata['signature'] = $sig;
        $insertdata['pdate'] = $pdate;
        $insertdata['school_fee'] = $balance;
        $insertdata['online_fee'] = $fee;
        $insertdata['status'] = 'Pending';

        $result = $this->db->table('online_payment')
          ->insert($insertdata);

        if ($result) {
          $this->load->library('email');
          $config['wordwrap'] = TRUE;
          $config['mailtype'] = 'html';
          $this->email->initialize($config);

          $subject = $_SERVER['SERVER_NAME'] . ' Online Payment Notice';

          $msg_school = "<p>Dear Online Administrator,</p>";
          $msg_school .= "<p>There is a new online payment in the following details.
          Please login on the school's PayPal account to verify and then approve it using the school's admin tool.</p><br>";

          $message = "<p><b>Parent Id:</b> {$parent_id}</p>";
          $message .= "<p><b>Parent Name:</b> {$userinfo[0]['primary_en_name']}</p>";

          if($userinfo[0]['primary_cn_name']) {
            $message .= "<p><b>Chinese Name:</b> {$userinfo[0]['primary_cn_name']}</p>";
          }

          $message .= "<p><b>Email:</b> {$userinfo[0]['email']}</p>";

          if($userinfo[0]['primary_phone']) {
            $message .= "<p><b>Phone:</b> {$userinfo[0]['primary_phone']}</p>";
          }

          if($userinfo[0]['alter_en_name']) {
            $message .= "<p><b>Alt Parent Name:</b> {$userinfo[0]['alter_en_name']}</p>";
          }

          if($userinfo[0]['alter_contact_email']) {
            $message .= "<p><b>Alt Email:</b> {$userinfo[0]['alter_contact_email']}</p>";
          }

          if($userinfo[0]['alter_phone']) {
            $message .= "<p><b>Alt Phone:</b> {$userinfo[0]['alter_phone']}</p>";
          }

          $message .= "<br><p><b>School Fee:</b> ${$balance}</p>";
          $message .= "<p><b>Online Processing Fee:</b> ${$fee}</p>";
          $message .= "<p><b>Total Paid:</b> $". ($balance + $fee) . "</p>";
          $message .= "<p><b>Signature:</b> {$sig}</p>";
          $message .= "<p><b>Dated:</b> {$pdate}</p>";

          $message .= "<br><p>Thank you,</p><p>Ez Web Management System</p>";
          $message .= "<p><a href='http://ezwebms.com'>http://ezwebms.com</a></p>";

          $this->email->from('customer.service@ezwebms.com', 'Rufeng Liu');
          $this->email->to('rufeng06@gmail.com');
          $this->email->bcc('rufeng_liu@hotmail.com');
          $this->email->subject($subject);
          $this->email->message($msg_school . $message);
          $this->email->send();

          $msg_parent = "<p>Dear {$userinfo[0]['primary_en_name']},</p>";
          $msg_parent .= "<p>Thank you for your payment, and you should have received an email from PayPal for the transaction.
          It is pending in our system, and we will process it within 72 hours and it will be approved after we verify it.</p>";

          $this->email->from('customer.service@ezwebms.com', 'Accounting Department');
          $this->email->to($userinfo[0]['email']);
          if($userinfo[0]['alter_contact_email']) {
            $this->email->cc($userinfo[0]['alter_contact_email']);
          }
          $this->email->bcc('rufeng_liu@hotmail.com');
          $this->email->subject($subject);
          $this->email->message($msg_parent . $message);
          $this->email->send();

        } else {
          $data['error_msg'] = "Sorry, but the payment was not successful!";
          session()->set_flashdata('error_msg', 'Sorry, but the payment was not successful!');
        }

        redirect()->to('/account/invoice/online', 'refresh');
      }
    } else {
      $data['error_msg'] = "Sorry, but the payment was not successful!";
      redirect()->to('/account/invoice/online', 'refresh');
    }
  }

  function pay_instruction()
  {
    session()->set(array('account_tab' => 'payment'));
    $article = new Article();
    $data['instruction'] = $article->getArticle(118);

    echo view($_SESSION['tm'].'common/account/pay_instruction.php', $data);
  }

  function check_address()
  {
    $userinfo = session()->get('userresult');
    if (empty($userinfo)) {
      ShowMsg('Login is not correct!', '/signin/', 0, 1500);
    }
    $pid = $userinfo[0]['parent_id'];

    $query = "SELECT address, city, zip, state from parents where parent_id=".$pid." LIMIT 1";
    $result = $this->db->query($query)->getRowArray();
    if ($result) {
      $address = trim($result['address']);
      $city = trim($result['city']);
      $zip = trim($result['zip']);
      $state = trim($result['state']);

      if(empty($address) || empty($city) || empty($zip) || empty($state) ) {
        session()->set(array('account_tab' => 'account'));
        //ShowMsg('Please complete your address information!', '/account/', 0, 5000);
        return false;
      }
      return true;
    } else {
      ShowMsg('Login is not correct!', '/signin/', 0, 1500);
    }
  }

  function sendInvoiceEmail($content)
  {
    $userinfo = session()->get('userresult');

    if (!empty($userinfo)) {
      $type = $userinfo['usertype'];
      if ($type == 1) {
        $data['usertype'] = $type;
        $data['parent'] = $userinfo[0];
        $parent_id = $userinfo[0]['parent_id'];

        /*$email = \Config\Services::email();
        $config['wordWrap'] = TRUE;
        $config['mailType'] = 'html';
          $config['charset']  = 'utf-8';
        $email->initialize($config);

        $subject = 'Xilin Northshore Chinese School Invoice';

          //$msg_parent = "<p>Dear {$userinfo[0]['primary_en_name']},</p>";
        //$msg_parent .= "<p>Here is the class registration summary and payment instructions.</p><br>";

        $email->setFrom('ec@xilinnschinese.org', 'Xilin Northshore Chinese School');
        $email->setTo($userinfo[0]['email']);
        if($userinfo[0]['alter_contact_email'] && $userinfo[0]['alter_contact_email'] != $userinfo[0]['email']) {
          $email->setCC($userinfo[0]['alter_contact_email']);
        }
        $email->setBCC('rufeng_liu@hotmail.com');
        $email->setSubject($subject);
        $email->setMessage($content);
        $email->send();*/

          $msg_parent = "<p>Dear {$userinfo[0]['primary_en_name']},</p>";
          $msg_parent .= "<p>Here is the class registration summary and payment instructions.</p><br>";
          $subject = 'Xilin Northshore Chinese School Invoice';
          $to = $userinfo[0]['email'];

          $headers = "From: Xilin Northshore Chinese School <ec@xilinnschinese.org>\r\n";
          if($userinfo[0]['alter_contact_email'] && $userinfo[0]['alter_contact_email'] != $userinfo[0]['email']) {
              $headers .= "Cc: {$userinfo[0]['alter_contact_email']} " . "\r\n";
          }
          $headers .= 'Bcc: ' . implode(',', ['rufeng_liu@hotmail.com']) . "\r\n";
          $headers .= "Reply-To: ec@xilinnschinese.org\r\n";
          $headers .= "MIME-Version: 1.0\r\n";
          $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
          mail($to, $subject, $msg_parent.$content, $headers, '-f ec@xilinnschinese.org');

        return true;
      }
    }

    return false;
  }
}