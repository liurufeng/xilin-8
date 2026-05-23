<?php namespace App\Controllers\Xilin_ns_admin;

use App\Models\Pod_model;
class Podreports extends MY_Controller
{

  function __construct()
  {
    $this->_classname = 'Podreports';
    $this->_methods = array(
      'index' => 'list',
    );
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
    $sql = "select *
            from school_year
            where status = 1
            order by name desc";

    $data['school_year'] = $this->db->query($sql)->getResultArray();

    $school_year_id = $this->request->getVar('school_year_id');

    if(isset($school_year_id) && !empty($school_year_id)) {
      session()->set(array('school_year_id' => $school_year_id));
      $sql = "select *
            from school_year
            where id=".$school_year_id;
    } else {
      session()->set(array('school_year_id' => $school_year_id));
      $sql = "select *
            from school_year
            where status = 1
            order by name desc
            limit 1";
    }
    $school_year_info = $this->db->query($sql)->getResultArray();
    $semesters = '('.$school_year_info[0]['semesters'].')';

    $sql = "select distinct p.* from
            parents p
            join students s on s.parent_id = p.parent_id
            join studentclasses sc on sc.student_id = s.student_id
            join classes c on c.class_id = sc.class_id
            where sc.semester_id in {$semesters}
            and sc.deleted != 1
            and p.pod_waiver = 0
            order by p.parent_id";

    $parents = $this->db->query($sql)->getResultArray();

    $pod_m = new Pod_model();
    $pod = array();
    foreach($parents as $a) {
      $pod[$a['parent_id']] = $pod_m->getPODReport($a, $semesters);
    }

    $data['parents'] = $parents;
    $data['pod'] = $pod;
    echo view($_SESSION['tm'].'admin/podreports/index.php', $data);
  }

  function detail()
  {
    $parent_id = $this->request->getVar('parent_id');
    $school_year_id = $this->request->getVar('sy_id');
    if(isset($school_year_id) && !empty($school_year_id)) {
      session()->set(array('school_year_id' => $school_year_id));
      $sql = "select *
            from school_year
            where id=".$school_year_id;
    } else {
      session()->set(array('school_year_id' => $school_year_id));
      $sql = "select *
            from school_year
            where status = 1
            order by name desc
            limit 1";
    }

    $school_year_info = $this->db->query($sql)->getResultArray();
    $semesters = '('.$school_year_info[0]['semesters'].')';

    $sql = "select distinct p.* from
            parents p
            join students s on s.parent_id = p.parent_id
            join studentclasses sc on sc.student_id = s.student_id
            join classes c on c.class_id = sc.class_id
            where sc.semester_id in {$semesters}
            and sc.deleted != 1
            and p.pod_waiver = 0
            and p.parent_id = {$parent_id}
            ";

    $parents = $this->db->query($sql)->getResultArray();
    $this->load->model('pod_model');
    $pod_m = new Pod_model();
    $pod = array();
    $pod['registered'] =  array();
    foreach($parents as $a) {
      $pod['nums'] = $pod_m->getPODReport($a, $semesters);
      //get how many POD the parent has registered
      $sql = "SELECT *
            FROM helpers h
            JOIN events e on h.event_id = e.event_id
            WHERE e.semester_id  in {$semesters}
            AND h.parent_id = {$parent_id}";
    }
    if($res = $this->db->query($sql)->result_array() ) {
      foreach($res as $r) {
        $pod['registered'][] = $r;
      }
    }
//var_dump($pod['registered']);
    $html = "<br><div>Parent Id: {$parent_id} | Parent Name: ".$parents[0]['primary_en_name']."</div><br>";
    $html .= "<div>Need: ".$pod['nums']['need']." | Done: ".$pod['nums']['done']." | Manual record: ".$pod['nums']['manually']." | Missed: ".$pod['nums']['missed'].", Registered and undone: ".$pod['nums']['todo']." </div><br>";
    if(count($pod['registered']) > 0) {
      $html .= "<div><br>POD Registrations:<br></div>";
      foreach($pod['registered'] as $reg){
        $html .= "<div><br>Date: ".($reg['month']+1)."/".$reg['date']."/".$reg['year']." | Time: ".$reg['start_time']." - ".$reg['end_time']." | Signed In: ".($reg['signin']?$reg['signin']:'No')." | Signed Out: ".($reg['signout']?$reg['signout']:'No')."<br></div>";
      }
    } else {
      $html .= "<div><br>NO POD Registrations</div>";
    }

    echo $html;
    return;
  }

}

