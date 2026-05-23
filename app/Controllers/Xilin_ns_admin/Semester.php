<?php namespace App\Controllers\Xilin_ns_admin;
use App\Models\{Semester as Semester_m};

class Semester extends MY_Controller
{

  function __construct()
  {

    $this->_classname = 'Semester';
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
				from semester
				where status = 1 
				order by semester_id desc";
    $data['list'] = $this->db->query($sql)->getResultArray();
    echo view($_SESSION['tm'].'admin/semester/index.php', $data);
  }

  /**
   * 添加
   */
  function add()
  {
    if ($this->request->getVar('dopost') == 'add') {
      $insertdata['semester_year'] = $this->request->getVar('semester_year');
      $insertdata['semester_name'] = $this->request->getVar('semester_name');
      $insertdata['semester_status'] = $this->request->getVar('semester_status');
      $insertdata['late_registration'] = $this->request->getVar('late_registration');
      $insertdata['registration_fee'] = $this->request->getVar('registration_fee');
      $insertdata['late_registration_fee'] = $this->request->getVar('late_registration_fee');
      $insertdata['parent_discount_base'] = $this->request->getVar('parent_discount_base');
      $insertdata['teacher_discount_base'] = $this->request->getVar('teacher_discount_base');
      $insertdata['show_flag'] = $this->request->getVar('show_flag');
      $insertdata['show_calendar'] = $this->request->getVar('show_calendar');
      //$this->db->insert('semester', $insertdata);
      $this->db->table('semester')
        ->insert($insertdata);

      ShowMsg("add success!", $_SESSION['admin_path'].'semester/index');
    }
    //$this->load->model('semester_model');
    $semester = new Semester_m();
    $data['groupList'] = $semester->getGroupList(" and egroup = 'semestername' ");
    $data['semesterstatus'] = $semester->getGroupList(" and egroup = 'semesterstatus' ");
    echo view($_SESSION['tm'].'admin/semester/add.php', $data);
  }

  /**
   * 编辑管理员
   */
  function edit()
  {
    if ($this->request->getVar('dopost') == 'save') {

      // 判断用户名是否存在
      if ($this->request->getVar('semester_id')) {
        $insertdata['semester_year'] = $this->request->getVar('semester_year');
        $insertdata['semester_name'] = $this->request->getVar('semester_name');
        $insertdata['semester_status'] = $this->request->getVar('semester_status');
        $insertdata['late_registration'] = $this->request->getVar('late_registration');
        $insertdata['registration_fee'] = $this->request->getVar('registration_fee');
        $insertdata['late_registration_fee'] = $this->request->getVar('late_registration_fee');
        $insertdata['parent_discount_base'] = $this->request->getVar('parent_discount_base');
        $insertdata['teacher_discount_base'] = $this->request->getVar('teacher_discount_base');
        $insertdata['show_flag'] = $this->request->getVar('show_flag');
        $insertdata['show_calendar'] = $this->request->getVar('show_calendar');
        $wheredata['semester_id'] = $this->request->getVar('semester_id');

        //$this->db->update('semester', $insertdata, $wheredata);
        $this->db->table('semester')
          ->where($wheredata)
          ->update($insertdata);

        ShowMsg('edit success!', $_SESSION['admin_path'].'semester/index');
      }
    }
    //$this->load->model('semester_model');
    $semester = new Semester_m();
    $data['groupList'] = $semester->getGroupList(" and egroup = 'semestername' ");
    $data['semesterstatus'] = $semester->getGroupList(" and egroup = 'semesterstatus' ");

    $semester_id = (int)$this->request->getVar('semester_id');
    if (empty($semester_id)) {
      ShowMsg('param error', $_SESSION['admin_path'].'semester/index');
    }
    $sql = " SELECT * FROM  semester WHERE semester_id = $semester_id";
    $data['info'] = $this->db->query($sql)->getRowArray();
    echo view($_SESSION['tm'].'admin/semester/edit.php', $data);
  }

  /**
   *  删除用户
   */
  function del()
  {

    $semester_id = (int)$this->request->getVar('semester_id');
    if (empty($semester_id)) {
      ShowMsg('param error', $_SESSION['admin_path'].'semester/index');
    }
    $sql = " update  semester set status = '2' WHERE semester_id = $semester_id";
    $this->db->query($sql);
    ShowMsg('delete success!', $_SESSION['admin_path'].'semester/index');
  }

  /**
   *  copy
   */
  function copy()
  {
    $semester_id = (int)$this->request->getVar('semester_id');
    if (empty($semester_id)) {
      ShowMsg('param error', $_SESSION['admin_path'].'semester/index');
    }
    $sql = "insert semester (semester_year,semester_name,semester_status,late_registration,registration_fee,late_registration_fee,parent_discount_base,teacher_discount_base,pod_charge)
				select CONCAT(semester_year, ' - COPY'),semester_name,'Future',late_registration,registration_fee,late_registration_fee,parent_discount_base,teacher_discount_base,pod_charge from  semester  where semester_id = $semester_id";
    $this->db->query($sql);
    ShowMsg('copy success!', $_SESSION['admin_path'].'semester/index');
  }
}