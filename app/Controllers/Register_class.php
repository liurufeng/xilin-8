<?php namespace App\Controllers;
use App\Models\{Semester, Subject, Classes, Teacher, Article, Student};
use DateTime;

/**
 *
 * register
 *
 */
class Register_class extends BaseController
{
  private $parent_id;
  private $student_id;
  private $student_info;
  private $student_classes;
  private $deleted_classes;
  public $db = null;
  public $sess = null;

  function __construct()
  {
    $this->db = db_connect();
    $this->sess = session();
    if (session()->get('userresult')) {
      $this->parent_id = session()->get('userresult')[0]['parent_id'];
    } else if (isset($_GET['pid']) && !empty($_GET['pid'])) {
      $this->parent_id = $_GET['pid'];
    } else {
      $this->parent_id = session()->get('userresult')[0]['parent_id'];
    }

    if(!$this->parent_id) {
      ShowMsg('Your session has expired!', '/signin/', 0, 1500);
    }

    if(!isset(session()->get('current_semester')['semester_id'])){
      $semester = new Semester();
      $semester->getCurrentSemester();
    }

    if (!isset($_GET['stdid']) || empty($_GET['stdid'])) {
      ShowMsg('You need to select a student to register classes!', 'account/students', 0, 1500);
    } else {
      $this->student_id = $_GET['stdid'];
    }

    $student = new Student();
    $this->student_info = $student->getStudent($this->parent_id, $this->student_id);

    if (empty($this->student_info)) {
      ShowMsg('Parent and student do not match!', '/signin/', 0, 1500);
    }

    $this->student_classes = $student->getStudentClasses($this->student_id);
    $this->deleted_classes = $student->getStudentDeletedClasses($this->student_id);

    session()->set(array('current_tab' => 'register_class'));
  }

  function index()
  {
    $data['student_info'] = $this->student_info;
    $data['student_classes'] = $this->student_classes;

    $subjects = new Subject();
    $data['subjects'] = $subjects->getSubjects();

    $classes = new Classes();
    $data['classes'] = $classes->getClasses();
    $data['late_date'] = session()->get('current_semester')['late_registration'];

    $data['is_late'] = time() - strtotime($data['late_date']) > 0 ? true : false;
    $data['late_date'] = date_format(new DateTime(date($data['late_date'])), 'M d, Y');

    echo view($_SESSION['tm'].'common/account/register_class.php', $data);
  }

  function do_register()
  {
    $act = $this->request->getVar('act');
    $current_semester_id = session()->get('current_semester')['semester_id'];
    $class_id = $this->request->getVar('class_id');

    //update parent table of agreed
    if($act !== '1' && (empty(session()->get('userresult')[0]['agreed']) || session()->get('userresult')[0]['agreed'] != $current_semester_id )) {
      $updatedata['agreed'] = $current_semester_id;
      $wheredata1['parent_id'] = $this->parent_id;
      $result = $this->db->table('parents')
        ->where($wheredata1)
        ->update($updatedata);


      $arr = session()->get('userresult');
      $arr[0]['agreed'] = $current_semester_id;
      session()->set(array('userresult'=> $arr));
    }

    // get the first day(session) of class of current semester
    $sql = "select t.date
            from calendar t
            join semester se on t.semester_id=se.semester_id
            where t.status = 1 and t.show_flag = 1 and se.show_calendar = 1
            and se.semester_status = 'current'
            and t.session = '1'";
    $cal_day = $this->db->query($sql)->getRowArray();
    $first_day = $cal_day['date'];
    $send_email = true; //time() - strtotime($first_day) > 0;

    if($act === '0'){ // new registration
      $success_msg = 'Your registration have been successfully submitted! Please click the Invoice link for your
      QuickPay payment code, copy and paste the code into the Memo field when making online payment. * Please do NOT
      include any other information in the memo!';

      if(array_key_exists($class_id, $this->student_classes)) {
        $resp = array('success' => FALSE, 'msg' => 'The class has been registered already.');
        sendJson($resp);
        return;
      }

      //prevent class time conflicts
      //1. get the class time
      $sql = "SELECT meeting_time
            FROM classes c
            WHERE  class_id = " . $class_id . "
            AND  c.semester_id=".$current_semester_id;

      $mt = $this->db->query($sql)->getRowArray();

      //2. compare the class time with existing class starting times
      if($_SESSION['check_class_conflict']) {
        $start = substr($mt['meeting_time'], 0, 5);
        $end = substr($mt['meeting_time'], -5);
        foreach($this->student_classes as $c) {
          if(strpos($c['meeting_time'], $start) !== false || strpos($c['meeting_time'], $end) !== false){
            $resp = array('success' => FALSE, 'msg' => 'The class has time conflict with other registered class, please check the class times.');
            sendJson($resp);
            return;
          }
        }
      }

      $buy_book = $this->request->getVar('buy_book');
      $buy_book = !empty($buy_book) && $buy_book > 0 ? 1 : 0;
      if(array_key_exists($class_id, $this->deleted_classes)) {
        $insertdata['buy_book'] = $buy_book;
        $insertdata['update_history'] = $this->deleted_classes[$class_id]['update_history'] . date("F j, Y, g:i a") . ': Re-Registered. <br> ';
        $insertdata['deleted'] = 0;
        $wheredata['student_id'] = $this->request->getVar('student_id');
        $wheredata['class_id'] = $class_id;
        $result = $this->db->table('studentclasses')
          ->where($wheredata)
          ->update($insertdata);

        $this->send_email($current_semester_id, $class_id,'new');
        $resp = array('success' => TRUE);
        sendJson($resp);
        return;
      }

      $insertdata['semester_id'] = $current_semester_id;
      $insertdata['student_id'] = $this->request->getVar('student_id');
      $insertdata['class_id'] = $class_id;
      $insertdata['buy_book'] = $buy_book;
      $insertdata['registration_time'] = date("Y-m-d H:i:s");
      $insertdata['update_history'] = date("F j, Y, g:i a") . ': Registered. <br> ';
      $result = $this->db->table('studentclasses')
        ->insert($insertdata);

      if ($result) {
        // send teacher email for this new registration if the semester already started
        if($send_email) {
          $this->send_email($current_semester_id, $class_id, 'new');
        }

        session()->setFlashdata('register_success', $success_msg);
        $resp = array('success' => TRUE);
        sendJson($resp);
        return;
      }

    } elseif ($act === '1') { // unregister the class
      $insertdata['update_history'] = $this->student_classes[$class_id]['update_history'] . date("F j, Y, g:i a") . ': Un-Registered. <br> ';
      $insertdata['deleted'] = 1;
      $success_msg = 'Your have been successfully unregistered the class!';
      $wheredata['student_id'] = $this->request->getVar('student_id');
      $wheredata['class_id'] = $class_id;
      $result = $this->db->table('studentclasses')
        ->where($wheredata)
        ->update($insertdata);

      //retain book fee if unregister after payment: inserting the negative value of the book fee into the checks table
      //1. is there a none zero book fee of the class?

      //2. did the parent request book?

      $sql = "SELECT (CASE WHEN( date_format(date(registration_time),'%Y-%m-%d')  < date_format(date('".session()->get('current_semester')['late_registration']."'),'%Y-%m-%d')) THEN book_fee ELSE late_book_fee END) as book_fee, sc.buy_book
            FROM classes c, studentclasses sc
            WHERE  sc.class_id=c.class_id
            AND  student_id = " . $this->request->getVar('student_id') . "
            AND  sc.class_id = " . $class_id . "
            AND  c.semester_id=".$current_semester_id;

      $cs = $this->db->query($sql)->getResultArray();

      if($cs[0]['book_fee'] > 0 && $cs[0]['buy_book'] > 0) {
        //3. has the parent paid?
        $sql = "select *
              from checks
              where parent_id = $this->parent_id
              and semester_id = " . $current_semester_id;
        $checks = $this->db->query($sql)->getResultArray();
        $paid = 0;
        foreach($checks as $check) {
          $paid += $check['check_amount'];
        }
        if($paid > 1) {
          //4. was the registration late? if yes, use late book fee
          //5. insert the book fee into the checks
          $checkdata['parent_id'] = $this->parent_id;
          $checkdata['semester_id'] = $current_semester_id;
          $checkdata['check_number'] = 'Book Fee';
          $checkdata['check_amount'] = 0.0 - $cs[0]['book_fee'];
          $checkdata['received_by'] = 'Unreg';
          $checkdata['pay_type'] = 'Bk Chrg';
          $checkdata['check_date'] = date("Y-m-d H:i:s");

          $result = $this->db->table('checks')
            ->insert($checkdata);
        }
      }

      if ($result) {
        // send teacher email for this un-registration if the semester already started
        if($send_email) {
          $this->send_email($current_semester_id, $class_id, 'unreg');
        }

        session()->setFlashdata('register_success', $success_msg);
        $resp = array('success' => TRUE);
        sendJson($resp);
        return;
      }
    } elseif ($act === '2') { // update the class
      $buy_book = $this->request->getVar('buy_book');
      $buy_book = !empty($buy_book) && $buy_book > 0 ? 1 : 0;
      $insertdata['buy_book'] = $buy_book;
      $insertdata['update_history'] = $this->student_classes[$class_id]['update_history'] . date("F j, Y, g:i a") . ': Changed buybook to ' .$buy_book .' <br>';
      $success_msg = 'Your update have been successfully submitted!';

      $wheredata['student_id'] = $this->request->getVar('student_id');
      $wheredata['class_id'] = $class_id;
      $result = $this->db->table('studentclasses')
        ->where($wheredata)
        ->update($insertdata);

      if ($result) {
        session()->setFlashdata('register_success', $success_msg);
        $resp = array('success' => TRUE);
        sendJson($resp);
        return;
      }
    } else { // no action, somthing is wrong
      $resp = array('success' => FALSE, 'msg' => 'No Action to perform.');
      sendJson($resp);
      return;
    }

  }

  /**
   * @param $current_semester_id
   * @param $config
   */
  public function send_email($current_semester_id, $class_id, $type = 'new')
  {
// get the teacher's email and name
    $sql = "SELECT c.class_name, c.meeting_time, t.en_name, t.email
                  FROM classes c 
                  JOIN teachers t on t.teacher_id = c.teacher_id 
                  WHERE class_id = " . $class_id . "
                  AND  c.semester_id=" . $current_semester_id;

    $teacher_class = $this->db->query($sql)->getRowArray();
    $student_name = $this->student_info[0]['en_name'];

    $email = \Config\Services::email();
    $config['wordWrap'] = TRUE;
    $config['mailType'] = 'html';
    $email->initialize($config);

    $msg = "<p>Dear {$teacher_class['en_name']},</p>";

    if($type == 'new') {
      $subject = 'New class registration - ' . $teacher_class['class_name'];
      $msg .= "<p>New student {$student_name} has registered your class {$teacher_class['class_name']} ({$teacher_class['meeting_time']}).</p><br>";
    } elseif ($type == 'unreg') {
      $subject = 'Class un-registered - ' . $teacher_class['class_name'];
      $msg .= "<p>Student {$student_name} has un-registered your class {$teacher_class['class_name']} ({$teacher_class['meeting_time']}).</p><br>";
    }

    $email->setFrom('ec@xilinnschinese.org', 'Xilin Northshore Chinese School');
    $email->setTo($teacher_class['email']);
    $email->setBCC('ec@xilinnschinese.org, rufeng_liu@hotmail.com');
    $email->setSubject($subject);
    $email->setMessage($msg);
    $email->send();
  }
}