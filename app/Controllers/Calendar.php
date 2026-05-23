<?php
namespace App\Controllers;
use App\Models\{Semester, Subject, Classes, Teacher, Article};

/**
 *
 * @author Rufeng Liu
 *
 */
class Calendar extends BaseController
{
  public $db = null;
  public $sess = null;

  function __construct()
  {
    $this->db = db_connect();
    $this->sess = session();
    session()->set(array('current_tab' => 'calendar'));
  }

  public function index()
  {
    $semester = new Semester();
    $data['semester'] = $semester->getSemesters();
    $semester->getCurrentSemester();

    $sql = "select *
				from calendar t
				join semester se on t.semester_id=se.semester_id
				where t.status = 1 and t.show_flag = 1 and se.show_calendar = 1
				order by t.semester_id desc, t.show_order asc, t.date asc";
    $data['calendars'] = $this->db->query($sql)->getResultArray();

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


    echo view($_SESSION['tm'].'calendar/index.php', $data);
  }
}
