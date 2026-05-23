<?php namespace App\Controllers\Xilin_ns_admin;

use App\Models\Semester;

class Podwaiver extends MY_Controller
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
      $where =' where pd.semester_id='.$semester_id;
    } else {
      $current_semester = session()->get('current_semester');
      $current_semester_id = $current_semester['semester_id'];
      session()->set(array('semester_id' => $current_semester_id));
      $where =' where pd.semester_id='.$current_semester_id;
    }

    $sql = "select *
				from parents p
				join pod_waiver pd on pd.parent_id = p.parent_id
				" . $where . "
				order by p.parent_id";

    $data['list'] = $this->db->query($sql)->getResultArray();
    echo view($_SESSION['tm'].'admin/podwaiver/index.php', $data);
  }

  /**
   * Add new podwaiver
   */
  function add()
  {
    if ($this->request->getVar('dopost') == 'add') {
      $insertdata['parent_id'] = $this->request->getVar('parent_id');
      $insertdata['semester_id'] = $this->request->getVar('semester_id');
      //$insertdata['notes'] = $this->request->getVar('notes');
      $this->db->table('pod_waiver')
        ->insert($insertdata);
      ShowMsg("add success!", $_SESSION['admin_path'].'podwaiver/index');
    }
    //$this->load->model('semester_model');
    $semester = new Semester();
    $data['semesters'] = $semester->getSemesters();
    $semester->getCurrentSemester();

    $sql = "select *
				from parents
				order by parent_id";

    $data['parents'] = $this->db->query($sql)->getResultArray();
    echo view($_SESSION['tm'].'admin/podwaiver/add.php', $data);
  }

  /**
   *  删除podwaiver
   */
  function del()
  {
    $pod_waiver_id = (int)$this->request->getVar('pod_waiver_id');
    if (empty($pod_waiver_id)) {
      ShowMsg('param error', $_SESSION['admin_path'].'podwaiver/index');
    }
    $sql = " delete from  pod_waiver WHERE pod_waiver_id = $pod_waiver_id";
    $this->db->query($sql);
    ShowMsg('delete success!', $_SESSION['admin_path'].'podwaiver/index');
  }

}

