<?php namespace App\Controllers\Xilin_ns_admin;

class Subjects extends MY_Controller
{

  function __construct()
  {

    $this->_classname = 'subjects';
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
   * list
   *
   */
  function index()
  {
    $sql = "select *
				from subjects
				where status < 2
				order by seq asc";
    $data['list'] = $this->db->query($sql)->getResultArray();
    echo view($_SESSION['tm'].'admin/subjects/index.htm', $data);
  }

  /**
   * add
   */
  function add()
  {
    if ($this->request->getVar('dopost') == 'add') {
      // 判断用户名是否存在
      if ($this->request->getVar('subject_name')) {
        $subject_name = trim($this->request->getVar('subject_name'));
        $sql = " SELECT 1 FROM  subjects WHERE subject_name = '$subject_name' and status=1";
         $rst = $this->db->query($sql)->getResultArray();
        if (is_array($rst) && count($rst) > 0) {
          ShowMsg('subject_name already exists', $_SESSION['admin_path'].'subjects/add');
        }
        $insertdata['subject_name'] = $this->request->getVar('subject_name');
        $insertdata['seq'] = $this->request->getVar('seq');
        //$this->db->insert('subjects', $insertdata);
        $this->db->table('subjects')
          ->insert($insertdata);
        ShowMsg("add success!", $_SESSION['admin_path'].'subjects/index');
      }
    }
    echo view($_SESSION['tm'].'admin/subjects/add.htm');
  }

  /**
   * edit
   */
  function edit()
  {
    if ($this->request->getVar('dopost') == 'save') {

      // 判断用户名是否存在
      if ($this->request->getVar('subject_name')) {
        $subject_name = trim($this->request->getVar('subject_name'));
        $sql = " SELECT 1 FROM  subjects WHERE subject_name = '$subject_name' and status=1 and subject_id<>" . $this->request->getVar('subject_id');
         $rst = $this->db->query($sql)->getResultArray();
        if (is_array($rst) && count($rst) > 0) {
          ShowMsg('Subjects name already exists', $_SESSION['admin_path'].'subjects/add');
        }
        $insertdata['subject_name'] = $this->request->getVar('subject_name');
        $insertdata['seq'] = $this->request->getVar('seq');
        $insertdata['status'] = $this->request->getVar('status');
        $wheredata['subject_id'] = $this->request->getVar('subject_id');
        //$this->db->update('subjects', $insertdata, $wheredata);
        $this->db->table('subjects')
          ->where($wheredata)
          ->update($insertdata);
        ShowMsg('edit success!', $_SESSION['admin_path'].'subjects/index');
      }
    }
    $subjects_id = (int)$this->request->getVar('subject_id');
    if (empty($subjects_id)) {
      ShowMsg('param error', $_SESSION['admin_path'].'subjects/index');
    }
    $sql = " SELECT * FROM  subjects WHERE subject_id = $subjects_id";
    $data['info'] = $this->db->query($sql)->getRowArray();

    echo view($_SESSION['tm'].'admin/subjects/edit.php', $data);
  }

  /**
   *  del
   */
  function del()
  {

    $subject_id = (int)$this->request->getVar('subject_id');
    if (empty($subject_id)) {
      ShowMsg('param error', $_SESSION['admin_path'].'subjects/index');
    }
    $sql = " update   subjects set status = '2' WHERE subject_id = $subject_id";
    $this->db->query($sql);
    ShowMsg('delete success!', $_SESSION['admin_path'].'subjects/index');
  }
}

?>