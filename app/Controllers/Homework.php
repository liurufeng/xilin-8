<?php
namespace App\Controllers;
use App\Models\{Semester, Subject, Classes, Teacher, Article};

/**
 *
 */
class Homework extends BaseController
{
  function __construct()
  {
    parent::__construct();

    session()->set(array('current_tab' => 'account'));
    session()->set(array('account_tab' => 'homework'));
  }

  function index()
  {
    $userinfo = session()->get('userresult');
    $where = ' where ';
    //get the semester info
    $this->load->model('semester');
    $semester = new Semester();
    $data['semesters'] = $semester->getSemesters();

    $semester_id = $this->input->get('semester_id');
    if(isset($semester_id) && !empty($semester_id)) {
      session()->set(array('semester_id' => $semester_id));
      $where .=' w.semester_id='.$semester_id;
    } else {
      $current_semester = session()->get('current_semester');
      $current_semester_id = $current_semester['semester_id'];
      session()->set(array('semester_id' => $current_semester_id));
      $where .=' w.semester_id='.$current_semester_id;
    }

    if (!empty($userinfo)) {
      $type = $userinfo['usertype'];
      $data['usertype'] = $type;
      if ($type === 1) {
        $id = $userinfo[0]['parent_id'];

        $this->load->model('student');
        $student = new Student();
        $data['students'] = $student->getParentStudents($id);

        $student_classes = $hws = array();
        //get classes of each student of the current semester
        foreach($data['students'] as $s) {
          $student_classes[$s['student_id']] = $student->getStudentClasses($s['student_id']);
          foreach($student_classes[$s['student_id']] as $c) {
            $sql = "select hw.*, hs.link as sublink, hs.note as subnote, hs.sub_date, hs.grade, hs.comment
            from homework hw
            left join homework_submission hs on hs.homework_id = hw.homework_id and hs.student_id = " . $s['student_id'] . "
            where hw.class_id=".$c['class_id'];
            $hws[$s['student_id']][$c['class_id']] = $this->db->query($sql)->getResultArray();
          }
        }
        $data['student_classes'] = $student_classes;
        $data['hws'] = $hws;

        echo view($_SESSION['tm']."common/homework/student_hw.php", $data);

      } else {
        $id = $userinfo[0]['teacher_id'];
        $data['teacher'] = $userinfo[0];

        $where = " and t.teacher_id = $id and w.status = 1 ";
        //get the selected semester's classes and homework info of this teacher
        $sql = "select w.*, c.*
            from homework w
            join teachers t on w.teacher_id = t.teacher_id
            join classes c on c.teacher_id = t.teacher_id and c.class_id = w.class_id
            ".$where. "
            order by c.class_code, w.homework_id";

        $all_data = $this->db->query($sql)->getResultArray();
        $classes = $homework = array();
        foreach($all_data as $a) {
          $classes[$a['class_id']] = $a;
          $homework[$a['class_id']][$a['homework_id']] = $a;
        }
        $data['classes'] = $classes;
        $data['homeworks'] = $homework;
        echo view($_SESSION['tm']."common/homework/index.php", $data);
      }
    }  else {
      ShowMsg('Session expired, please login again!', 'login.php', 0, 1500);
    }
  }

  function add_homework()
  {
    $userinfo = session()->get('userresult');
    $action = $this->uri->segment(3);
    if(!$action) $action = 'Add';
    if (!empty($userinfo)) {
      $type = $userinfo['usertype'];
      $id = $userinfo[0]['teacher_id'];
      $data['teacher'] = $userinfo[0];
      $data['usertype'] = $type;

      $current_semester = session()->get('current_semester');
      $current_semester_id = $current_semester['semester_id'];
      $data['semester_id'] = $current_semester_id;

      $where = " where teacher_id = $id and semester_id = $current_semester_id ";
      //get the semester info
      $sql = "select c.*
            from classes c
            ".$where. "
            order by c.class_code";

      $all_data = $this->db->query($sql)->getResultArray();
      $classes = array();
      foreach($all_data as $a) {
        $classes[$a['class_id']] = $a;
      }
      $data['classes'] = $classes;
      $data['action'] = $action;

      if($action=='Edit') {
        $data['homework_id'] = $this->uri->segment(4);
        $sql = "select *
            from homework h
            where homework_id = ".$data['homework_id'];

        $homework = $this->db->query($sql)->getRowArray();
        $data['h'] = $homework;
      }

      echo view($_SESSION['tm']."common/homework/homework_t.php", $data);

    } else {
      ShowMsg('Session expired, please login again!', 'login.php', 0, 1500);
    }
  }

  function do_add()
  {
    $userinfo = session()->get('userresult');
    $action = $this->uri->segment(3);
    if(!$action) $action = 'Add';
    if (!empty($userinfo)) {
      $type = $userinfo['usertype'];
      if ($type == 2) {
        if($action == 'Add') {
        $insertdata['teacher_id'] = $userinfo[0]['teacher_id'];
        $insertdata['semester_id'] = $this->request->getVar('semester_id');
        $insertdata['class_id'] = $this->request->getVar('class_id');
        $insertdata['title'] = $this->request->getVar('title');
        $insertdata['note'] = $this->request->getVar('note');
        $insertdata['link'] = $this->request->getVar('link');
        $insertdata['due_date'] = $this->request->getVar('due_date');

        $success_msg = 'Your have successfully added a new homework!';
        $result = $this->db->insert('homework', $insertdata);
        } else if($action == 'Edit') {
          $insertdata['class_id'] = $this->request->getVar('class_id');
          $insertdata['title'] = $this->request->getVar('title');
          $insertdata['note'] = $this->request->getVar('note');
          $insertdata['link'] = $this->request->getVar('link');
          $insertdata['due_date'] = $this->request->getVar('due_date');
          $wheredata['homework_id'] = $this->request->getVar('homework_id');

          $success_msg = 'Your have successfully updated the homework!';
          $result = $this->db->update('homework', $insertdata, $wheredata);
        }

        if ($result) {
          $send_email = $this->request->getVar('send_email');
          if(isset($send_email) && !empty($send_email)) {
            //get the class's parent's emails
            $sql = "select p.en_name as pname, p.email, p.alter_contact_email, c.class_name, s.en_name as sname
            from parents p
            join students s on p.parent_id = s.parent_id
            join studentclasses sc on s.student_id = sc.student_id
            join classes c on c.class_id = sc.class_id
            where sc.class_id = ".$this->request->getVar('class_id');

            $all_data = $this->db->query($sql)->getResultArray();

            $this->load->library('email');
            $config['wordwrap'] = TRUE;
            $config['mailtype'] = 'html';
            $this->email->initialize($config);

            foreach($all_data as $a) {
              $message = '';
              $parent_email = $a['email'];
              $cc_parent_email = '';
              if(!empty($a['alter_contact_email'])){
                $cc_parent_email = $a['alter_contact_email'];
              }

              $subject = $a['class_name'].' Homework Assignment';

              $message .= "<p>Dear {$a['pname']},</p>";
              $message .= "<p>Please see the homework assignment for {$a['sname']}.
              If online submission is required by the teacher, you need to login the school's website and find the homework of the student.</p><br>";

              $message .= "<p><b>Class:</b> {$a['sname']}</p>";
              $message .= "<p><b>Student:</b> {$a['class_name']}</p>";
              if($this->request->getVar('title')) {
                $message .= "<p><b>Homework Name:</b> {$this->request->getVar('title')}</p>";
              }
              if($this->request->getVar('link')) {
                $message .= "<p><b>Homework Link:</b> {$this->request->getVar('link')}</p>";
              }
              if($this->request->getVar('note')) {
                $message .= "<p><b>Teacher's Notes:</b> {$this->request->getVar('note')}</p>";
              }
              if($this->request->getVar('due_date')) {
                $message .= "<p><b>Homework Due:</b> {$this->request->getVar('due_date')}</p>";
              }

              $message .= "<br><p>Thank you,</p><p>$userinfo[0]['en_name']</p>";
              $message .= "<p><a href='http://".$_SERVER['HTTP_HOST']."'>http://{$_SERVER['HTTP_HOST']}</a></p>";

              $this->email->from($userinfo[0]['email'], $userinfo[0]['en_name']);
              $this->email->to($parent_email);
              if($cc_parent_email) {
                $this->email->cc($cc_parent_email);
              }
              $this->email->bcc($userinfo[0]['email']);
              $this->email->subject($subject);
              $this->email->message($message);
              $this->email->send();
            }
          }

          $other_emails = $this->request->getVar('other_emails');
          if(isset($other_emails) && !empty($other_emails)) {
            $sql = "select c.class_name
            from classes c
            where c.class_id = ".$this->request->getVar('class_id');

            $all_data = $this->db->query($sql)->getRowArray();

            $this->load->library('email');
            $config['wordwrap'] = TRUE;
            $config['mailtype'] = 'html';
            $this->email->initialize($config);

            $subject = $all_data['class_name'].' Homework Assignment';

            $message = "<p>Dear Parents,</p>";
            $message .= "<p>Please see the homework assignment.
            If online submission is required by the teacher, you need to login the school's website and find the homework of the student.</p><br>";

            $message .= "<p><b>Class:</b> {$all_data['class_name']}</p>";
            $message .= "<p><b>Student:</b> Student Name</p>";
            if($this->request->getVar('title')) {
              $message .= "<p><b>Homework Name:</b> {$this->request->getVar('title')}</p>";
            }
            if($this->request->getVar('link')) {
              $message .= "<p><b>Homework Link:</b> {$this->request->getVar('link')}</p>";
            }
            if($this->request->getVar('note')) {
              $message .= "<p><b>Teacher's Notes:</b> {$this->request->getVar('note')}</p>";
            }
            if($this->request->getVar('due_date')) {
              $message .= "<p><b>Homework Due:</b> {$this->request->getVar('due_date')}</p>";
            }
            $message .= "<br><p>Thank you,</p><p>{$userinfo[0]['en_name']}</p>";
            $message .= "<p><a href='http://".$_SERVER['HTTP_HOST']."'>http://{$_SERVER['HTTP_HOST']}</a></p>";

            $this->email->from($userinfo[0]['email'], $userinfo[0]['en_name']);
            $this->email->to($other_emails);
            $this->email->subject($subject);
            $this->email->message($message);
            $this->email->send();
          }

          session()->set_flashdata('success_msg', $success_msg);
          redirect('/homework', 'refresh');
        } else {
          $success_msg = "There is a problem to handle the homework!";
          session()->set_flashdata('success_msg', $success_msg);
          redirect('/homework', 'refresh');
        }
      }
    } else {
      ShowMsg('Session expired, please login again!', 'login.php', 0, 1500);
    }
  }

  function remove_homework()
  {
    $userinfo = session()->get('userresult');

    if (!empty($userinfo)) {
      $type = $userinfo['usertype'];
      if ($type == 2) {

        $insertdata['status'] = 0;

        $wheredata['homework_id'] = $this->request->getVar('wid');
        $result = $this->db->update('homework', $insertdata, $wheredata);
        $success_msg = 'Your have successfully deleted the homework!';

        if ($result) {
          session()->set_flashdata('success_msg', $success_msg);
          $resp = array('success' => TRUE);
          sendJson($resp);
          return;
        }

      }
    } else {
      ShowMsg('Session expired, please login again!', 'login.php', 0, 1500);
    }
  }

  function do_homework()
  {
    $userinfo = session()->get('userresult');
    if (!empty($userinfo)) {
    $homework_id = $this->uri->segment(3);
    $student_id = $this->uri->segment(4);
    $class_id = $this->uri->segment(5);

    $this->load->model('student');
    $student = new Student();
    $student_info = $student->getStudent($userinfo[0]['parent_id'], $student_id);

    if (empty($student_info)) {
      ShowMsg('Parent and student do not match!', 'login.php', 0, 1500);
    }

      $type = $userinfo['usertype'];
      $id = $userinfo[0]['parent_id'];
      $data['parent'] = $userinfo[0];
      $data['usertype'] = $type;

      $data['homework_id'] = $homework_id;
      $data['student_id'] = $student_id;
      $data['class_id'] = $class_id;

     //get the semester info
      $sql = "select c.*
            from classes c
            where class_id = $class_id
            order by c.class_code";

      $class = $this->db->query($sql)->getRowArray();
      $data['c'] = $class;

      $sql = "select h.*
            from homework h
            where h.homework_id = " . $homework_id;

      $homework = $this->db->query($sql)->getRowArray();
      $data['h'] = $homework;

      echo view($_SESSION['tm']."common/homework/homework_s.php", $data);
    } else {
      ShowMsg('You are not logged in!', 'login.php', 0, 1500);
    }
  }

  function submit_hw()
  {
    $userinfo = session()->get('userresult');

    if (!empty($userinfo)) {
      $student_id = $this->request->getVar('student_id');
      $this->load->model('student');
      $student = new Student();
      $student_info = $student->getStudent($userinfo[0]['parent_id'], $student_id);

      if (empty($student_info)) {
        ShowMsg('Parent and student do not match!', 'login.php', 0, 1500);
      }
      $current_semester = session()->get('current_semester');
      $current_semester_id = $current_semester['semester_id'];

      $insertdata['homework_id'] = $this->request->getVar('homework_id');
      $insertdata['semester_id'] = $current_semester_id;
      $insertdata['class_id'] = $this->request->getVar('class_id');
      $insertdata['student_id'] = $student_id;
      $insertdata['link'] = $this->request->getVar('link');
      $insertdata['note'] = $this->request->getVar('note');
      $insertdata['sub_date'] = date("Y-m-d H:i:s");;

      $success_msg = 'Your have successfully submitted your homework!';
      $result = $this->db->insert('homework_submission', $insertdata);

      if ($result) {

        $sql = "select c.class_name, h.title, hs.*, t.email, t.en_name
            from homework h
            join classes c on c.class_id = h.class_id
            join homework_submission hs on hs.homework_id = h.homework_id
            join teachers t on t.teacher_id = h.teacher_id
            where h.homework_id = ".$this->request->getVar('homework_id');

        $all_data = $this->db->query($sql)->getRowArray();

        $this->load->library('email');
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html';
        $this->email->initialize($config);

        $subject = $all_data['class_name'].' Homework Submission Confirmation';

        $message = "<p>Dear Parents,</p>";
        $message .= "<p>This is to confirm that ".$student_info[0]['en_name']." has submitted the following homework.</p><br>";

        $message .= "<p><b>Student:</b> {$student_info[0]['en_name']}</p>";
        $message .= "<p><b>Class:</b> {$all_data['class_name']}</p>";
        $message .= "<p><b>Homework Name:</b> {$all_data['title']}</p>";

        if($all_data['link']) {
          $message .= "<p><b>Submission Link:</b> {$all_data['link']}</p>";
        }
        if($all_data['note']) {
          $message .= "<p><b>Student Note:</b> {$all_data['note']}</p>";
        }
        if($all_data['sub_date']) {
          $message .= "<p><b>Student Note:</b> {$all_data['sub_date']}</p>";
        }

        $message .= "<br><p>Thank you,</p><p>{$all_data['en_name']}</p>";
        $message .= "<p><a href='http://".$_SERVER['HTTP_HOST']."'>http://{$_SERVER['HTTP_HOST']}</a></p>";

        $this->email->from($all_data['email'], $all_data['en_name']);
        $this->email->to($userinfo[0]['email']);
        $this->email->bcc($all_data['email'].',rufeng_liu@hotmail.com');
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->send();

        session()->set_flashdata('success_msg', $success_msg);
        redirect('/homework', 'refresh');
      }
    } else {
      ShowMsg('You are not logged in!', 'login.php', 0, 1500);
    }
  }

  function grade()
  {
    $homework_id = $this->uri->segment(3);
    $userinfo = session()->get('userresult');
    if (!empty($userinfo)) {
      $type = $userinfo['usertype'];
      $data['usertype'] = $type;
      $id = $userinfo[0]['teacher_id'];
      $data['teacher'] = $userinfo[0];

      //get the selected classes and homework info of this teacher
      $sql = "select w.homework_id as hid, w.title, hs.*, s.en_name, s.cn_name, s.student_id as sid, sc.class_id
            from students s
            join studentclasses sc on sc.student_id = s.student_id and deleted != 1
            join homework w on w.class_id = sc.class_id and w.homework_id = $homework_id
            left join homework_submission hs on hs.student_id = s.student_id and hs.homework_id = w.homework_id
            where w.teacher_id = $id
            order by s.en_name";

      $data['hws'] = $this->db->query($sql)->getResultArray();
      echo view($_SESSION['tm']."common/homework/grading.php", $data);
    } else {
      ShowMsg('Login is not correct!', 'login.php', 0, 1500);
    }
  }

  function grade_homework()
  {
    $userinfo = session()->get('userresult');
    if (!empty($userinfo)) {
      $type = $userinfo['usertype'];
      $data['usertype'] = $type;
      $id = $userinfo[0]['teacher_id'];
      $data['teacher'] = $userinfo[0];

      $student_id = $this->uri->segment(3);
      $class_id = $this->uri->segment(4);
      $homework_id = $this->uri->segment(5);

      //if no $hw_sub_id means the student hasn't submitted homework yet
      $hw_sub_id = $this->uri->segment(6);

      $data['student_id'] = $student_id;
      $data['class_id'] = $class_id;
      $data['homework_id'] = $homework_id;
      $data['hw_sub_id'] = $hw_sub_id;

      //get the selected classes and homework info of this teacher
      $sql = "select w.title, w.note as tnote, w.link as tlink, w.due_date, hs.*, s.en_name, s.cn_name, c.*
            from homework w
            join students s on s.student_id = $student_id
            join classes c on c.class_id = w.class_id
            left join homework_submission hs on hs.student_id = s.student_id and hs.homework_id = w.homework_id
            where w.teacher_id = $id and w.homework_id = $homework_id";

      $data['h'] = $this->db->query($sql)->getRowArray();

      echo view($_SESSION['tm']."common/homework/grade_homework.php", $data);
    } else {
      ShowMsg('Login is not correct!', 'login.php', 0, 1500);
    }
  }

  function submit_grade()
  {
    $userinfo = session()->get('userresult');

    if (!empty($userinfo)) {
      $type = $userinfo['usertype'];
      $data['usertype'] = $type;
      $id = $userinfo[0]['teacher_id'];
      $data['teacher'] = $userinfo[0];

      $student_id = $this->request->getVar('student_id');
      $homework_id = $this->request->getVar('homework_id');
      $hw_sub_id = $this->request->getVar('hw_sub_id');
      //verify the teacher owns the hw and student
      $sql = "select w.title, w.note as tnote, w.link as tlink, w.due_date, hs.*, s.en_name, s.cn_name, c.*
            from homework w
            join students s on s.student_id = $student_id
            join classes c on c.class_id = w.class_id
            left join homework_submission hs on hs.student_id = s.student_id and hs.homework_id = w.homework_id
            where w.teacher_id = $id and w.homework_id = $homework_id";

      $hw = $this->db->query($sql)->getRowArray();
      if (empty($hw)) {
        ShowMsg('Login issue!', 'login.php', 0, 1500);
      }

      if($hw_sub_id) { //student has submitted hw
        $insertdata['grade'] = $this->request->getVar('grade');
        $insertdata['comment'] = $this->request->getVar('comment');
        $insertdata['updated'] = date("Y-m-d H:i:s");
        $wheredata['hw_sub_id'] = $hw_sub_id;
        $result = $this->db->update('homework_submission', $insertdata, $wheredata);
      } else {

        $current_semester = session()->get('current_semester');
        $current_semester_id = $current_semester['semester_id'];

        $insertdata['homework_id'] = $homework_id;
        $insertdata['semester_id'] = $current_semester_id;
        $insertdata['class_id'] = $this->request->getVar('class_id');
        $insertdata['student_id'] = $student_id;
        $insertdata['grade'] = $this->request->getVar('grade');
        $insertdata['comment'] = $this->request->getVar('comment');
        $insertdata['updated'] = date("Y-m-d H:i:s");
        $result = $this->db->insert('homework_submission', $insertdata);
      }

      $success_msg = 'Your have successfully graded the homework!';
      if ($result) {
        $this->load->library('email');
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html';
        $this->email->initialize($config);

        $sql = "select w.title, w.note as tnote, w.link as tlink, w.due_date, hs.*, s.en_name, s.cn_name, c.*, p.email, p.alter_contact_email
            from homework w
            join students s on s.student_id = $student_id
            join parents p on p.parent_id = s.parent_id
            join classes c on c.class_id = w.class_id
            left join homework_submission hs on hs.student_id = s.student_id and hs.homework_id = w.homework_id
            where w.teacher_id = $id and w.homework_id = $homework_id";

        $hw = $this->db->query($sql)->getRowArray();

        $subject = $hw['class_name'].' Homework Has Been Graded';

        $message = "<p>Dear Parents,</p>";
        $message .= "<p>This is to inform you that ".$hw['en_name']."'s homework has been graded as the following.</p><br>";

        $message .= "<p><b>Student:</b> {$hw['en_name']}</p>";
        $message .= "<p><b>Class:</b> {$hw['class_name']}</p>";
        $message .= "<p><b>Homework Name:</b> {$hw['title']}</p>";

        if($hw['grade']) {
          $message .= "<p><b>Grade:</b> {$hw['grade']}</p>";
        }

        if($hw['comment']) {
          $message .= "<p><b>Comments:</b> {$hw['comment']}</p>";
        }

        $message .= "<br><p>Thank you,</p><p>{$userinfo[0]['en_name']}</p>";
        $message .= "<p><a href='http://".$_SERVER['HTTP_HOST']."'>http://{$_SERVER['HTTP_HOST']}</a></p>";

        $this->email->from($userinfo[0]['email'], $userinfo[0]['en_name']);
        $this->email->to($hw['email']);
        if($hw['alter_contact_email']){
          $this->email->cc($hw['alter_contact_email']);
        }
        $this->email->bcc($userinfo[0]['email']);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->send();

        session()->set_flashdata('success_msg', $success_msg);
        redirect('/homework/grade/'.$homework_id, 'refresh');
      }
    } else {
      ShowMsg('You are not logged in!', 'login.php', 0, 1500);
    }
  }

}