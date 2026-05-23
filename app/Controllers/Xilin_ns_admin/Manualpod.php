<?php namespace App\Controllers\Xilin_ns_admin;

use App\Models\Semester;

class Manualpod extends MY_Controller
{

  function __construct()
  {

    $this->_classname = 'Classes';
    $this->_methods = array(
      'index' => 'list',
      'add' => 'add',
      'edit' => 'edit',
      'del' => 'del',
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
    $semester = new Semester();
    $data['semesters'] = $semester->getSemesters();
    $semester->getCurrentSemester();

    $semester_id = $this->request->getVar('semester_id');
    if(isset($semester_id) && !empty($semester_id)) {
      session()->set(array('semester_id' => $semester_id));
      $where =' where m.semester_id='.$semester_id;
    } else {
      $current_semester = session()->get('current_semester');
      $current_semester_id = $current_semester['semester_id'];
      session()->set(array('semester_id' => $current_semester_id));
      $where =' where m.semester_id='.$current_semester_id;
    }

    $sql = "select m.*,p.*
				from manual_pod_record m
				join parents p on p.parent_id = m.parent_id " . $where;

    $data['list'] = $this->db->query($sql)->getResultArray();
    echo view($_SESSION['tm'].'admin/manualpod/index.php', $data);
  }

  /**
   * Add new manual_pod_record
   */
  function add()
  {
    if ($this->request->getVar('dopost') && $this->request->getVar('dopost') == 'add') {
      $insertdata['parent_id'] = $this->request->getVar('parent_id');
      $insertdata['semester_id'] = $this->request->getVar('semester_id');
      $insertdata['manual_records'] = $this->request->getVar('manual_records');
      $insertdata['notes'] = $this->request->getVar('notes');

      $this->db->table('manual_pod_record')
        ->insert($insertdata);
      ShowMsg("add success!", $_SESSION['admin_path'].'manualpod/index');
    }

    $semester = new Semester();
    $data['semesters'] = $semester->getSemesters();
    $semester->getCurrentSemester();

    $sql = "select *
				from parents
				order by parent_id";
    $data['parents'] = $this->db->query($sql)->getResultArray();

    echo view($_SESSION['tm'].'admin/manualpod/add.php', $data);
  }

  /**
   * 编辑manual_pod_record
   */
  function edit()
  {
    if ($this->request->getVar('dopost') && $this->request->getVar('dopost') == 'save') {
      $insertdata['parent_id'] = $this->request->getVar('parent_id');
      $insertdata['semester_id'] = $this->request->getVar('semester_id');
      $insertdata['manual_records'] = $this->request->getVar('manual_records');
      $insertdata['notes'] = $this->request->getVar('notes');
      $wheredata['manual_pod_record_id'] = $this->request->getVar('manual_pod_record_id');

      $this->db->table('manual_pod_record')
        ->where($wheredata)
        ->update($insertdata);

      ShowMsg('edit success!', $_SESSION['admin_path'].'manualpod/index');

    }
    $semester = new Semester();
    $data['semesters'] = $semester->getSemesters();
    $semester->getCurrentSemester();

    $sql = "select *
				from parents
				order by parent_id";
    $data['parents'] = $this->db->query($sql)->getResultArray();

    $sql = "select m.*,p.*
				from manual_pod_record m
				join parents p on p.parent_id = m.parent_id
				where manual_pod_record_id = " . $this->request->getVar('manual_pod_record_id');

    $data['list'] = $this->db->query($sql)->getRowArray();

    echo view($_SESSION['tm'].'admin/manualpod/edit.php', $data);
  }

  /**
   *  删除manual_pod_record
   */
  function del()
  {
    $manual_pod_record_id = (int)$this->request->getVar('manual_pod_record_id');
    if (empty($manual_pod_record_id)) {
      ShowMsg('param error', $_SESSION['admin_path'].'manualpod/index');
    }
    $sql = " delete from  manual_pod_record WHERE manual_pod_record_id = $manual_pod_record_id";
    $this->db->query($sql);
    ShowMsg('delete success!', $_SESSION['admin_path'].'manualpod/index');
  }

}

