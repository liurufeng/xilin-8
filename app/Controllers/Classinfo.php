<?php
namespace App\Controllers;
use App\Models\{Semester, Subject, Classes, Teacher, Article};

/**
 *
 * @author Rufeng Liu
 *
 */
class Classinfo extends BaseController
{
  public $db = null;
  public $sess = null;

  function __construct()
  {
    $this->db = db_connect();
    $this->sess = session();
    session()->set(array('current_tab' => 'classes'));
  }

  public function index()
  {
    $userinfo = session()->get('userresult') ? session()->get('userresult') : null;

    $semester = new Semester();
    $data['semester'] = $semester->getSemesters();
    $semester->getCurrentSemester();

    $subjects = new Subject();
    $data['subjects'] = $subjects->getSubjects();

    $classes = new Classes();
    $data['classes'] = $classes->getClasses();

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

    echo view($_SESSION['tm'].'classes/index.php', $data);
  }
}
