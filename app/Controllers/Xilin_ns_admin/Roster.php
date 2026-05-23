<?php namespace App\Controllers\Xilin_ns_admin;
use App\Models\Semester;
use App\Models\Admin\Payment_model;

class Roster extends MY_Controller
{

  function __construct()
  {
    $this->_issystem = TRUE;
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
      $where =' and se.semester_id='.$semester_id;
    } else {
      $current_semester = session()->get('current_semester');
      $current_semester_id = $current_semester['semester_id'];
      session()->set(array('semester_id' => $current_semester_id));
      $where =' and se.semester_id='.$current_semester_id;
    }

    $sql = "select c.*,s.subject_name subject,t.en_name teacher
				from classes c
				join subjects s on c.subject_id=s.subject_id
				join teachers t on t.teacher_id=c.teacher_id
				join semester se on se.semester_id=c.semester_id
				where c.status = 1 " . $where .
        " order by s.seq, c.class_code ";

    $data['list'] = $this->db->query($sql)->getResultArray();

    $payment = new Payment_model();
    $data['payment'] = $payment->getPaymentData($semester_id);

    echo view($_SESSION['tm'].'admin/roster/index.php', $data);
  }

}

