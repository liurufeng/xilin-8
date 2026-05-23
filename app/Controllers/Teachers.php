<?php
namespace App\Controllers;
use App\Models\{Semester, Subject, Classes, Teacher, Article};

/**
 *
 * @author Rufeng Liu
 *
 */
class Teachers extends BaseController
{

  function __construct()
  {
    session()->set(array('current_tab' => 'teacher'));
    $this->db = db_connect();
  }

  public function index()
  {
    $userinfo = session()->get('userresult') ? session()->get('userresult') : null;

    //$this->load->model('teacher');
    $teachers = new Teacher();
    $data['teachers'] = $teachers->getTeachers();

    if (!empty($userinfo)) {
      $type = $userinfo['usertype'];
      if ($type == 1) {
        $id = $userinfo[0]['parent_id'];
        $sql = "select * from parents where parent_id = $id";
      } else {
        $id = $userinfo[0]['teacher_id'];
        $sql = "select * from teachers where teacher_id = $id";
      }
      $userinfodata = $this->db->query($sql)->getResultArray();
      $data['userinfodata'] = $userinfodata;
      $data['usertype'] = $type;
    }

    echo view($_SESSION['tm'].'teachers/index.php', $data);
  }

  public function update_sl(){
    $updatedata['syl_link'] = $this->request->getVar('syl_link');
    $success_msg = 'Your update have been successfully submitted!';

    $wheredata['teacher_id'] = session()->get('userresult')[0]['teacher_id'];
    $wheredata['class_id'] = $this->request->getVar('class_id');

    //$result = $this->db->update('classes', $updatedata, $wheredata);
    $result = $this->db->table('classes')
      ->where($wheredata)
      ->update($updatedata);

    if ($result) {
      //session()->set_flashdata('register_success', $success_msg);
      $resp = array('success' => TRUE, 'id'=> $this->request->getVar('syl_link'));
      sendJson($resp);
      return;
    } else {
      //session()->set_flashdata('register_fail', 'Update failed!');
      $resp = array('success' => FALSE);
      sendJson($resp);
      return;
    }
  }

  public function rosterEmail() {
    // 0. get current semester info
    //$this->load->model('semester');
    $semester = new Semester();
    $semester->getCurrentSemester();
    $current_semester = session()->get('current_semester');
    $current_semester_id = $current_semester['semester_id'];

    // 1. get the date of the second session from calendar
    $sql = "select date 
            from calendar c
            where c.semester_id = {$current_semester_id}
            and c.session = 2
            and c.show_flag =1
            and c.status = 1
            limit 1";

    $result = $this->db->query($sql)->getRowArray();
    if(!$result) exit;
    // 2. find the class registration that have changes after the 2nd session date
    $sql = "select sc.class_id
            from studentclasses sc
            where sc.semester_id = {$current_semester_id}
            and sc.update_time >= STR_TO_DATE('{$result['date']}','%m/%d/%Y')
            group by sc.class_id";
    $result = $this->db->query($sql)->getResultArray();
    if(!$result) exit;
    // 3. send email of the roster to the teacher if there are changes
    foreach ($result as $class) {
      // get teacher's name and email, and the class roster
      $sql = "select c.*,s.subject_name subject,t.en_name teacher, t.email temail
				from classes c
				join subjects s on c.subject_id=s.subject_id
				join teachers t on t.teacher_id=c.teacher_id
				join semester se on se.semester_id=c.semester_id
				where c.status = 1 
				and se.semester_id = {$current_semester_id}
        and c.class_id = {$class['class_id']} 
        limit 1";

      $v = $this->db->query($sql)->getRowArray();
      $message = "Hi {$v['teacher']}, <br><br> 
                  The roster of class {$v['class_name']} has changed since last week, here is the updated student list. <br><br>";
      $message .= <<<HTML
<table>
    <tr bgcolor="#E4F0F9" style="text-align: center; font-weight: bold;">
      <td colspan="7">
        {$v['class_code']},
        {$v['class_name']},
        {$v['subject']},
        {$v['meeting_time']} @ {$v['classroom']}
      </td>
    </tr>

    <tr style="font-weight: bold;">
      <td width="10%">No.</td>
      <td width="20%">Student Name</td>
      <td width="20%">Parent Name</td>
      <td width="25%">Parent Email</td>
      <td width="25%">Alt Email</td>
    </tr>
HTML;
      $sql = "select p.parent_id, p.primary_en_name,  p.email, alter_contact_email, s.en_name sename,
          s.student_id sid, sc.buy_book
          from studentclasses sc
          join students s on s.student_id = sc.student_id
          join parents p on p.parent_id = s.parent_id
          where sc.deleted = 0 and sc.class_id = ".$v['class_id']
        ." order by s.en_name ";
      $students = $this->db->query($sql)->getResultArray();
      $num = 1;

      foreach($students as $s) {
        $message .= <<<HTML
        <tr>
          <td>{$num}</td>
          <td>{$s['sename']}</td>
          <td>{$s['primary_en_name']}</td>
          <td>{$s['email']}</td>
          <td>{$s['alter_contact_email']}</td>
        </tr>
HTML;
        $num++;
      }
      $message .= "</table>";
      //echo $message;
      // now send the email
      $subject = 'Xilin Northshore Chinese School Class Roster Update';
      //$this->load->library('email');
      $config['wordwrap'] = TRUE;
      $config['mailtype'] = 'html';
      $email = \Config\Services::email();
      $email->initialize($config);
      $email->setFrom('ec@xilinnschinese.org', 'Xilin Northshore Chinese School');
      $email->setTo('rufeng_liu@hotmail.com');
      $email->setSubject($subject);
      $email->setMessage($message);
      $email->send();
    }
  }
}
