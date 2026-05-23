<?php namespace App\Controllers\Xilin_ns_admin;

use App\Models\Semester;

class Email_list extends MY_Controller
{

  function __construct()
  {
    parent::_Mycontroller();
    parent::_check_login();
  }

  /**
   *
   *
   */
  function index()
  {
    $semester = new Semester();
    $data['semesters'] = $semester->getSemesters();
    $semester->getCurrentSemester();

    $semester_id = $this->request->getVar('semester_id');
    if(isset($semester_id) && !empty($semester_id)) {
      session()->set(array('semester_id' => $semester_id));
    } else {
      $current_semester = session()->get('current_semester');
      $semester_id = $current_semester['semester_id'];
      session()->set(array('semester_id' => $semester_id));
    }

    //get parents emails of current semester
    $sql = "SELECT DISTINCT email, alter_contact_email
              FROM parents p
              join students s on p.parent_id = s.parent_id
              join studentclasses sc on sc.student_id = s.student_id and sc.deleted = 0
              WHERE sc.semester_id= $semester_id ";

    $data['plist'] = $this->db->query($sql)->getResultArray();

    //get teacher emails of current semester
    $sql = "SELECT DISTINCT email
              FROM teachers t
              join classes c on c.teacher_id = t.teacher_id
              WHERE c.semester_id= $semester_id and c.status = 1";

    $data['tlist'] = $this->db->query($sql)->getResultArray();

    echo view($_SESSION['tm'].'admin/email_list/index.php', $data);
  }

}

