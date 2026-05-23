<?php namespace App\Controllers\Xilin_ns_admin;
use App\Models\{Semester};

class Calendar extends MY_Controller
{

  function __construct()
  {

    $this->_classname = 'Calendar';
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
   * 管理员list
   *
   */
  function index()
  {
    $sql = "select c.*, se.*, c.show_flag as calendar_show_flag
				from calendar c
				join semester se on c.semester_id=se.semester_id
				where c.status = 1
				order by c.calendar_id desc, c.semester_id desc, c.show_order asc, c.date asc";
    $data['list'] = $this->db->query($sql)->getResultArray();
    echo view($_SESSION['tm'].'admin/calendar/index.php', $data);
  }

  /**
   * 添加calendar
   */
  function add()
  {
    if ($this->request->getVar('dopost') == 'add') {
      $insertdata['semester_id'] = $this->request->getVar('semester_id');
      $insertdata['header'] = $this->request->getVar('header');
      $insertdata['date'] = $this->request->getVar('date');
      $insertdata['session'] = $this->request->getVar('session');
      $insertdata['note'] = $this->request->getVar('note');
      $insertdata['show_flag'] = $this->request->getVar('show_flag');
      $insertdata['show_order'] = $this->request->getVar('show_order');
      $this->db->table('calendar')
        ->insert($insertdata);
      ShowMsg("add success!", $_SESSION['admin_path'].'calendar/index');
    }

    //$this->load->model('semester_model');
    $semester = new Semester();
    $data['semesters'] = $semester->getSemesters();

    echo view($_SESSION['tm'].'admin/calendar/add.php', $data);
  }

  /**
   * 编辑calendar
   */
  function edit()
  {
    if ($this->request->getVar('dopost') == 'save') {

      // 判断用户名是否存在
      if ($this->request->getVar('calendar_id')) {
        $insertdata['semester_id'] = $this->request->getVar('semester_id');
        $insertdata['header'] = $this->request->getVar('header');
        $insertdata['date'] = $this->request->getVar('date');
        $insertdata['session'] = $this->request->getVar('session');
        $insertdata['note'] = $this->request->getVar('note');
        $insertdata['show_flag'] = $this->request->getVar('show_flag');
        $insertdata['show_order'] = $this->request->getVar('show_order');
        $wheredata['calendar_id'] = $this->request->getVar('calendar_id');
        //$this->db->update('calendar', $insertdata, $wheredata);
        $this->db->table('calendar')
          ->where($wheredata)
          ->update($insertdata);

        ShowMsg('edit success!', $_SESSION['admin_path'].'calendar/index');
      }
    }
    //$this->load->model('semester_model');
    $semester = new Semester();
    $data['semesters'] = $semester->getSemesters();

    $calendar_id = (int)$this->request->getVar('calendar_id');
    if (empty($calendar_id)) {
      ShowMsg('param error', $_SESSION['admin_path'].'calendar/index');
    }
    $sql = " SELECT * FROM calendar WHERE calendar_id = $calendar_id";
    $data['info'] = $this->db->query($sql)->getRowArray();
    echo view($_SESSION['tm'].'admin/calendar/edit.php', $data);
  }

  /**
   *  删除calendar
   */
  function del()
  {

    $calendar_id = (int)$this->request->getVar('calendar_id');
    if (empty($calendar_id)) {
      ShowMsg('param error', $_SESSION['admin_path'].'calendar/index');
    }
    $sql = " update calendar set status = '2' WHERE calendar_id = $calendar_id";
    $this->db->query($sql);
    ShowMsg('delete success!', $_SESSION['admin_path'].'calendar/index');
  }

  function desc()
  {

  }

  /**
   *  copy
   */
  function copy()
  {
    $calendar_id = (int)$this->request->getVar('calendar_id');
    if (empty($calendar_id)) {
      ShowMsg('param error', $_SESSION['admin_path'].'calendar/index');
    }
    $sql = "insert calendar (semester_id,header,date,session,note,show_order)
				select semester_id,header,date,session,note,show_order
				from calendar where calendar_id = $calendar_id";
    $this->db->query($sql);
    ShowMsg('copy success!', $_SESSION['admin_path'].'calendar/index');
  }
}

// end system.php