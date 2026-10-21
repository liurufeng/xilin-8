<?php namespace App\Models;

use CodeIgniter\Model;
class Student extends Model
{

  function __construct()
  {
    parent::__construct();
  }

  function getParentStudents($parent_id)
  {
    $sql = "select * from students
                  where status = 1
                  and parent_id = ?";
    return $this->db->query($sql, [$parent_id])->getResultArray();
  }

  function getStudent($parent_id, $student_id)
  {
    $sql = "select *
                from students
                where status = 1
                and student_id = ?
                and parent_id = ?";

    return $this->db->query($sql, [$student_id, $parent_id])->getResultArray();
  }

  function getStudentClasses($student_id, $semester_id = false)
  {
    if(!$semester_id) $semester_id = session()->get('current_semester')['semester_id'];
    $sql = "select *
            from studentclasses sc
            join classes c on c.class_id = sc.class_id
            where student_id = ?
            and deleted = 0
            and sc.semester_id = ?";
    $keyed = array();
    foreach ($this->db->query($sql, [$student_id, $semester_id])->getResultArray() as $key => $val) {
      $keyed[$val['class_id']] = $val;
    }

    return $keyed;
  }

  function getStudentDeletedClasses($student_id)
  {
    $sql = "select *
            from studentclasses
            where student_id = ?
            and deleted = 1
            and semester_id = ?";
    $keyed = array();
    foreach ($this->db->query($sql, [$student_id, session()->get('current_semester')['semester_id']])->getResultArray() as $key => $val) {
      $keyed[$val['class_id']] = $val;
    }

    return $keyed;
  }
}
