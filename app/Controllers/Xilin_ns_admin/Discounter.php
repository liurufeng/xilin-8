<?php namespace App\Controllers\Xilin_ns_admin;

use App\Models\Semester;

class Discounter extends MY_Controller
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
				join parent_discounter pd on pd.parent_id = p.parent_id
				" . $where . "
				order by p.parent_id";

    $data['list'] = $this->db->query($sql)->getResultArray();
    echo view($_SESSION['tm'].'admin/discounter/index.php', $data);
  }

  /**
   * Add new teacher_discount
   */
  function add()
  {
    if ($this->request->getVar('dopost') == 'add') {

      $parent_id = $this->request->getVar('parent_id');
      $semester_id = $this->request->getVar('semester_id');

      $sqlx = "select parent_id from schooluser where parent_id = ".$parent_id." and semester_id = ".$semester_id;
      $resultx = $this->db->query($sqlx)->getRowArray();
      if(isset($resultx) && isset($resultx['parent_id']) && !empty($resultx['parent_id'])) {
        ShowMsg("Add failed! The account is already in school admin!", $_SESSION['admin_path'].'discounter/index');
      }

      $insertdata['parent_id'] = $parent_id;
      $insertdata['semester_id'] = $semester_id;
      //$insertdata['notes'] = $this->request->getVar('notes');

      $this->db->table('parent_discounter')
        ->insert($insertdata);
      ShowMsg("add success!", $_SESSION['admin_path'].'discounter/index');
    }

    $semester = new Semester();
    $data['semesters'] = $semester->getSemesters();
    $semester->getCurrentSemester();

    $sql = "select *
				from parents
				order by parent_id";

    $data['parents'] = $this->db->query($sql)->getResultArray();
    echo view($_SESSION['tm'].'admin/discounter/add.php', $data);
  }

  /**
   *  删除teacher_discount
   */
  function del()
  {
    $discount_id = (int)$this->request->getVar('discount_id');
    if (empty($discount_id)) {
      ShowMsg('param error', $_SESSION['admin_path'].'discounter/index');
    }
    $sql = " delete from  parent_discounter WHERE discount_id = $discount_id";
    $this->db->query($sql);
    ShowMsg('delete success!', $_SESSION['admin_path'].'discounter/index');
  }

}

