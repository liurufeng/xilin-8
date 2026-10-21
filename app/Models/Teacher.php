<?php
namespace App\Models;

use CodeIgniter\Model;
class Teacher extends Model
{

  function __construct()
  {
    parent::__construct();
  }

  function getTeacher($teacher_id)
  {
    $sql = "select t.* from teachers t
				where t.status = 1
				and t.teacher_id = ?";
    $info = $this->db->query($sql, [$teacher_id])->getRowArray();

    return $info;
  }

  function getTeachers()
  {
    $sql = "select t.*,se.ename from teachers t
				join sys_enum se on t.type = se.id and se.egroup = 'teachertype'
				where t.status = 1
				order by se.disorder, t.en_name";
    $info = $this->db->query($sql)->getResultArray();
    $data = array();
    foreach ($info as $item) {
      $data[$item['ename']][] = $item;
    }

    return $data;
  }

  function getAdmins($type, $semester_id = false)
  {
    if(!$semester_id) $semester_id = session()->get('current_semester')['semester_id'];
    $sql = "select s.*
				from schooluser s
				join sys_enum se on s.type = se.id 
				and se.egroup = 'schoolusertype'
				where s.status = 1
				and s.isshow = 1
				and type=?
				and s.semester_id = ?
				order by show_order";

    return $this->db->query($sql, [$type, $semester_id])->getResultArray();
  }

  function getGroupList()
  {
    $sql = "SELECT id,ename FROM sys_enum
    		  WHERE egroup = 'teachertype' ";
    $groupList = $this->db->query($sql)->getResultArray();
    if ($groupList) {
      return $groupList;
    } else {
      return FALSE;
    }
  }
}
