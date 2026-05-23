<?php namespace App\Models\Admin;
use CodeIgniter\Model;
use App\Models\{Semester, Discount_model, Pod_model};

class Payment_model extends Model
{

  function __construct()
  {
    parent::__construct();
  }

  /**
   * @param $data
   * @param $semester_id
   * @return mixed
   */
  public function getPaymentData($semester_id)
  {
    $semester = new Semester();
    $data['semesters'] = $semester->getSemesters();
    $semester->getCurrentSemester();

    if (isset($semester_id) && !empty($semester_id)) {
      session()->set(array('semester_id' => $semester_id));
      $where = ' where sc.semester_id=' . $semester_id;
    } else {
      $current_semester = session()->get('current_semester');
      $semester_id = $current_semester['semester_id'];
      session()->set(array('semester_id' => $semester_id));
      $where = ' where sc.semester_id=' . $semester_id;
    }

    $data['this_semester'] = $semester->getSemester($semester_id);
    //get the previous semester
    $sql = "SELECT *
            FROM semester
            WHERE (semester_id) IN
            ( SELECT MAX(semester_id)
              FROM semester
              where semester_id < " . $data['this_semester']['semester_id'] . "
              and status = 1
              -- and semester_status = 'Previous'
            )";

    $prev_semester = $this->db->query($sql)->getRowArray();

    $sql = "select p.*, s.*,c.*, sc.*, sc.deleted as unregistered, sc.update_time, lf.amount as late_fee 
            from parents p
            join students s on s.parent_id = p.parent_id
            join studentclasses sc on sc.student_id = s.student_id
            join classes c on c.class_id = sc.class_id
            left join late_fee lf on lf.parent_id = p.parent_id and lf.semester_id = sc.semester_id
            " . $where . "
            order by p.parent_id, s.student_id, c.subject_id, c.class_code";

    $all_data = $this->db->query($sql)->getResultArray();
    $parents = $students = $classes = $total = $tuition_total = $book_total = $material_total = $pod = $discount =
    $checks = $balance = $reg_fee = $late_fee = array();
    $data['totalT'] = $data['tuition_totalT'] = $data['book_totalT'] = $data['material_totalT'] = $data['podT'] = $data['discountT'] = 0.0;
    $unregistered = 0;

    foreach ($all_data as $a) {
      $parents[$a['parent_id']] = $a;
      $students[$a['parent_id']][$a['student_id']] = $a;
      $classes[$a['parent_id']][$a['student_id']][$a['class_id']] = $a;
      $is_late = strtotime($a['registration_time']) - strtotime($data['this_semester']['late_registration']) > 0 ? true : false;

      $book_fee = $a['buy_book'] ? $a['book_fee'] : 0;
      $late_book_fee = $a['buy_book'] ? $a['late_book_fee'] : 0;

      if ($is_late) {
        $classes[$a['parent_id']][$a['student_id']][$a['class_id']]['tuition'] = $a['late_tuition'];
        $classes[$a['parent_id']][$a['student_id']][$a['class_id']]['book_fee'] = $late_book_fee;
      } else {
        $classes[$a['parent_id']][$a['student_id']][$a['class_id']]['book_fee'] = $book_fee;
      }
      if(!isset($total[$a['parent_id']])) $total[$a['parent_id']] = 0;
      if(!isset($tuition_total[$a['parent_id']])) $tuition_total[$a['parent_id']] = 0;
      if(!isset($book_total[$a['parent_id']])) $book_total[$a['parent_id']] = 0;
      if(!isset($material_total[$a['parent_id']])) $material_total[$a['parent_id']] = 0;

      if ($a['unregistered'] < 1) {

        $total[$a['parent_id']] += $classes[$a['parent_id']][$a['student_id']][$a['class_id']]['tuition'] +
          $classes[$a['parent_id']][$a['student_id']][$a['class_id']]['book_fee'] +
          $classes[$a['parent_id']][$a['student_id']][$a['class_id']]['material_fee'];

        $tuition_total[$a['parent_id']] += $classes[$a['parent_id']][$a['student_id']][$a['class_id']]['tuition'];

        $book_total[$a['parent_id']] += $classes[$a['parent_id']][$a['student_id']][$a['class_id']]['book_fee'];
        $material_total[$a['parent_id']] += $classes[$a['parent_id']][$a['student_id']][$a['class_id']]['material_fee'];
      } else {
        $total[$a['parent_id']] += 0;
        $tuition_total[$a['parent_id']] += 0;

        $book_total[$a['parent_id']] += 0;
        $material_total[$a['parent_id']] += 0;
        $unregistered++;
      }

      $late_fee[$a['parent_id']] = $a['late_fee'];
    }

    $data['classesT'] = count($all_data) - $unregistered;

    $pod_m = new Pod_model();
    $disc = new Discount_model();
    foreach ($tuition_total as $k => $t) {
      if ($_SESSION['tm'] == 'xilin7/') {
        $discount[$k] = $disc->getXilinDiscount($parents[$k], $t, $data['this_semester']);
      } else if ($_SESSION['tm'] == 'ccc/') {
        $discount[$k] = $disc->getCCCDiscount($parents[$k], $t, $data['this_semester']);
      } else {
        $discount[$k] = $disc->getDiscount($parents[$k], $t, $data['this_semester']);
      }
      $pod[$k] = $pod_m->getPODCharge($parents[$k], $data['this_semester'], $prev_semester);
      //if ($total[$k] > 0) $total[$k] += $data['this_semester']['registration_fee'];
      $reg_fee[$k] = $data['this_semester']['registration_fee'];
      //Due to covid-19, there is no POD charges, and if the parent is a waiver or has done any POD, waive reg fee
      if(($data['this_semester']['semester_year'] == '2020' || $data['this_semester']['semester_year'] == '2021') && $data['this_semester']['semester_name'] == 'Fall') {
        $pod[$k]['penalty'] = 0;
        if ($total[$k]<1.0 || $pod[$k]['waiver']=='yes' || $pod[$k]['done']>0 || $pod[$k]['manually']>0) {
          $reg_fee[$k] = 0;
        }
      } else {
        if ($total[$k] < 1.0) {
          $pod[$k]['penalty'] = 0;
          $reg_fee[$k] = 0;
        }
      }
      $total[$k] += $reg_fee[$k];
      $total[$k] += $late_fee[$k];

      //get paid check
      $sql = "select *
              from checks
              where parent_id = $k
              and semester_id = " . $data['this_semester']['semester_id'];
      $checks[$k] = $this->db->query($sql)->getResultArray();
      //Grand totals
      $data['totalT'] += $total[$k];
      $data['tuition_totalT'] += $t;
      $data['book_totalT'] += $book_total[$k];
      $data['material_totalT'] += $material_total[$k];
      $data['discountT'] += $discount[$k];

      $data['studentsT'] = $data['studentsT'] ?? 0;
      if ($t < 1.0 && count($checks[$k]) < 1) {
        $pod[$k]['penalty'] = -0;
      } else {
        $data['studentsT'] += count($students[$k]);
      }
      $data['podT'] += $pod[$k]['penalty'];
      $data['reg_feeT'] = $data['reg_feeT'] ?? 0;
      $data['reg_feeT'] += $reg_fee[$k];
      $data['late_feeT'] = $data['late_feeT'] ?? 0;
      $data['late_feeT'] += $late_fee[$k];
    }

    //get paid check

    $data['semester_id'] = $semester_id;
    $data['parents'] = $parents;
    $data['students'] = $students;
    $data['list'] = $parents;
    $data['classes'] = $classes;
    $data['total'] = $total;
    $data['discount'] = $discount;
    $data['reg_fee'] = $reg_fee;
    $data['late_fee'] = $late_fee;
    $data['pod'] = $pod;
    $data['checks'] = $checks;
    $data['pay_types'] = array('Regular', 'Refund', 'Discnt', 'FinAid', 'POD wvr', 'Prorate', 'Other');
    $data['pay_forms'] = array('Check', 'Cash', 'Online');
    return $data;
  }
}
