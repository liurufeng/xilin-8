<?php namespace App\Controllers\Xilin_ns_admin;

use App\Models\{Semester, Teacher as Teacher_m};

class Teacher extends MY_Controller
{

  function __construct()
  {

    $this->_classname = 'Teacher';
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
    $sql = "select t.*,se.ename type
				from teachers t
				join sys_enum se on t.type=se.id
				where status = 1
				order by t.en_name ";
    $data['list'] = $this->db->query($sql)->getResultArray();

    //$this->load->model('semester_model');
    $semester = new Semester();
    $semester->getCurrentSemester();
    $current_semester = session()->get('current_semester');
    $current_semester_id = $current_semester['semester_id'];

    $sql = "select DISTINCT t.email
            from teachers t
            join classes c on t.teacher_id=c.teacher_id
            where t.status = 1
            and c.status = 1
            and c.semester_id = {$current_semester_id}
            order by t.en_name";
    $active_email_arr = $this->db->query($sql)->getResultArray();
    $active_emails = '';
    foreach($active_email_arr as $row){
      $active_emails .= $row['email'] .',';
    }
    $data['active_emails'] = rtrim($active_emails, ',');

    echo view($_SESSION['tm'].'admin/teacher/index.php', $data);
  }

  /**
   * 添加管理员
   */
  function add()
  {
    if ($this->request->getVar('dopost') == 'add') {
      // 判断用户名是否存在
      if ($this->request->getVar('email')) {
        $email = trim($this->request->getVar('email'));
        $sql = " SELECT 1 FROM  teachers WHERE email = '$email' and status=1";
         $rst = $this->db->query($sql)->getResultArray();
        if (is_array($rst) && count($rst) > 0) {
          ShowMsg('email already exists', $_SESSION['admin_path'].'teacher/add');
        }
        $insertdata['email'] = $this->request->getVar('email');
        $insertdata['en_name'] = $this->request->getVar('en_name');
        $insertdata['password'] = $this->request->getVar('passwd');
        $insertdata['passwd'] = $this->request->getVar('passwd'); //md5($this->request->getVar('passwd'));
        $insertdata['type'] = $this->request->getVar('type');
        $insertdata['cn_name'] = $this->request->getVar('cn_name');
        $insertdata['phone1'] = $this->request->getVar('phone1');
        $insertdata['phone2'] = $this->request->getVar('phone2');
        $insertdata['address'] = $this->request->getVar('address');
        $insertdata['desc_link'] = $this->request->getVar('desc_link');
        //$this->db->insert('teachers', $insertdata);
        $this->db->table('teachers')
          ->insert($insertdata);


        ShowMsg("add success!", $_SESSION['admin_path'].'teacher/index');
      }
    }
    //$this->load->model('teacher_model');
    $teacher_m = new Teacher_m();

    $data['groupList'] = $teacher_m->getGroupList();
    echo view($_SESSION['tm'].'admin/teacher/add.php', $data);
  }

  /**
   * 编辑管理员
   */
  function edit()
  {
    if ($this->request->getVar('dopost') == 'save') {

      // 判断用户名是否存在
      if ($this->request->getVar('teacher_id')) {
        $email = trim($this->request->getVar('email'));
        $sql = " SELECT 1 FROM  teachers WHERE email = '$email' and status=1 and teacher_id<>" . $this->request->getVar('teacher_id');
         $rst = $this->db->query($sql)->getResultArray();
        if (is_array($rst) && count($rst) > 0) {
          ShowMsg('email already exists', $_SESSION['admin_path'].'teacher/add');
        }

        $insertdata['email'] = $this->request->getVar('email');
        $insertdata['en_name'] = $this->request->getVar('en_name');
        $insertdata['password'] = $this->request->getVar('passwd');
        $insertdata['passwd'] = $this->request->getVar('passwd'); //md5($this->request->getVar('passwd'));
        $insertdata['type'] = $this->request->getVar('type');
        $insertdata['cn_name'] = $this->request->getVar('cn_name');
        $insertdata['phone1'] = $this->request->getVar('phone1');
        $insertdata['phone2'] = $this->request->getVar('phone2');
        $insertdata['address'] = $this->request->getVar('address');
        $insertdata['desc_link'] = $this->request->getVar('desc_link');
        $wheredata['teacher_id'] = $this->request->getVar('teacher_id');

        //$this->db->update('teachers', $insertdata, $wheredata);
        $this->db->table('teachers')
          ->where($wheredata)
          ->update($insertdata);
        ShowMsg('edit success!', $_SESSION['admin_path'].'teacher/index');
      }
    }
    //$this->load->model('teacher_model');
    $teacher_m = new Teacher_m();
    $data['groupList'] = $teacher_m->getGroupList();

    $teacher_id = (int)$this->request->getVar('teacher_id');
    if (empty($teacher_id)) {
      ShowMsg('param error', $_SESSION['admin_path'].'teacher/index');
    }
    $sql = " SELECT * FROM teachers WHERE teacher_id = $teacher_id";
    $data['info'] = $this->db->query($sql)->getRowArray();


    echo view($_SESSION['tm'].'admin/teacher/edit.php', $data);
  }

  /**
   *  删除teacher
   */
  function del()
  {

    $teacher_id = (int)$this->request->getVar('teacher_id');
    if (empty($teacher_id)) {
      ShowMsg('param error', $_SESSION['admin_path'].'teacher/index');
    }
    $sql = " update teachers set status = '2' WHERE teacher_id = $teacher_id";
    $this->db->query($sql);
    ShowMsg('delete success!', $_SESSION['admin_path'].'teacher/index');
  }
}

// end system.php