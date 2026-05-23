<?php
namespace App\Models;

use CodeIgniter\Model;
class Discount_model extends Model
{
  //calculate the discount of a parent for a given semester
  function getDiscount($parent = array(), $tuition_total = 0, $semester =  array())
  {
    $teacher_discount = $parent['teacher_discount'] > 0 ? $semester['teacher_discount_base'] : 0.00;
    $diff = $tuition_total - 2 * $semester['parent_discount_base'] - $teacher_discount;
    $discount = floor(($diff*0.1)+0.5);
    if ($discount < 0) $discount = 0;
    $discount += $teacher_discount;
    return $discount;
  }

  //calculate the discount of a parent for a given semester for Xilin
  function getXilinDiscount($parent = array(), $tuition_total = 0, $semester =  array())
  {
    if($tuition_total <= 0) return 0.00;
    $diff = $teacher_discount = 0.00;
    //$teacher_discount = $parent['teacher_discount'] > 0 ? $semester['teacher_discount_base'] : 0.00;
    // get teacher discount
    // if the parent was set as a discounter
    $sql = "select *
            from parent_discounter pd
            where pd.semester_id = ".$semester['semester_id']."
            and pd.parent_id=".$parent['parent_id'];

    $row = $this->db->query($sql)->getResultArray();
    if (count($row) > 0) {
      $teacher_discount = $semester['teacher_discount_base'];
    } else {
      // check if the parent is board or EC
      $sql = "select * 
            from schooluser su
            where su.semester_id = ".$semester['semester_id']."
            and su.parent_id=".$parent['parent_id']."
            and su.status = 1 ";
      $row = $this->db->query($sql)->getResultArray();
      if (count($row) > 0) {
        $teacher_discount = $semester['teacher_discount_base'];
      }
    }

    $sql = "SELECT (CASE WHEN( date_format(date(registration_time),'%Y-%m-%d')  < date_format(date('".session()->get('current_semester')['late_registration']."'),'%Y-%m-%d')) THEN tuition ELSE late_tuition END) as tuition
    FROM classes c, studentclasses sc
    WHERE  sc.class_id=c.class_id
    and ((c.subject_id in (1) and c.semester_id <= 34) or (c.subject_id in (1,5) and c.semester_id > 34))
    and sc.deleted != 1
    AND  student_id IN (SELECT student_id FROM students WHERE parent_id=".$parent['parent_id'].")
    AND  c.semester_id=".$semester['semester_id']."
    order by tuition desc";
    $mlps = $this->db->query($sql)->getResultArray();
    //var_dump($sql); exit;
    if(count($mlps) >=2) {
      $diff = $tuition_total - $mlps[0]['tuition'] - $mlps[1]['tuition'] - $teacher_discount;
    }
    $discount = floor(($diff*0.1)+0.5);
    if ($diff < 0 || $discount < 0) $discount = 0;
    $discount += $teacher_discount;
    if($tuition_total <= $discount) return $tuition_total;
    return $discount;

  }

  //calculate the discount of a parent for a given semester for CCC
  function getCCCDiscount($parent = array(), $tuition_total = 0, $semester =  array())
  {
    if ($tuition_total <= 1500) return 0;
    return $tuition_total * 0.05;
  }
}
