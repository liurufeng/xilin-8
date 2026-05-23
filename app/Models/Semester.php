<?php
namespace App\Models;

use CodeIgniter\Model;
use DateTime;

class Semester extends Model
{

  public $db;
  function __construct()
  {
    $this->db = db_connect();
  }

  function getSemesters()
  {
    $sql = "select * from semester
              where status = 1 and show_flag = 1
              order by semester_id desc";
    return $this->db->query($sql)->getResultArray();
  }

  function getSemester($semester_id)
  {
    $sql = "select * from semester
              where semester_id = $semester_id
              limit 1";
    return $this->db->query($sql)->getRowArray();
  }

  function getCurrentSemester()
  {
    $user_semester_id = session()->get('user_semester_id');
    if($user_semester_id && $user_semester_id > 0) {
      $sql = "select * from semester
              where semester_id = ".$user_semester_id;
    } else {
      $sql = "select * from semester
              where status = 1 and semester_status = 'Current'";
    }

    $result = $this->db->query($sql)->getResultArray();
    if (empty($result)) {
      return false;
    }

    $current_semester = $result[0];
    session()->set(array('current_semester' => $current_semester));

    $late_date = $current_semester['late_registration'];
    session()->set(array('late_date_time' => $late_date));
    $is_late = time() - strtotime($late_date) > 0 ? true : false;
    $late_date = date_format(new DateTime(date($late_date)), 'M d, Y');

    session()->set(array('late_date' => $late_date));
    session()->set(array('is_late' => $is_late));

    return $this->db->query($sql)->getResultArray();
  }

  function getGroupList($where = "")
  {
    $sql = "SELECT id,ename FROM sys_enum
    		  WHERE 1=1 $where";
    $groupList = $this->db->query($sql)->getResultArray();
    if ($groupList) {
      return $groupList;
    } else {
      return FALSE;
    }
  }

}
