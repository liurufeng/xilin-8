<?php namespace App\Controllers\Xilin_ns_admin;

use App\Models\Semester;
class Schooluser extends MY_Controller
{

  function __construct()
  {

    $this->_classname = 'Schooluser';
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
    $semester = new Semester();
    $data['semesters'] = $semester->getSemesters();
    $semester->getCurrentSemester();

    $semester_id = $this->request->getVar('semester_id');
    if(isset($semester_id) && !empty($semester_id)) {
      session()->set(array('semester_id' => $semester_id));
      $where =' where s.semester_id='.$semester_id;
    } else {
      $current_semester = session()->get('current_semester');
      $current_semester_id = $current_semester['semester_id'];
      session()->set(array('semester_id' => $current_semester_id));
      $where =' where s.semester_id='.$current_semester_id;
    }

    $sql = "select s.*,se.ename
				from schooluser s
				join sys_enum se on s.type = se.id and se.egroup = 'schoolusertype'
				" . $where .
        " and s.status = 1";
    $data['list'] = $this->db->query($sql)->getResultArray();
    echo view($_SESSION['tm'].'admin/schooluser/index.php', $data);
  }

  /**
   * add
   */
  function add()
  {
    if ($this->request->getVar('dopost') == 'save' || $this->request->getVar('dopost') == 'add') {
      if ($this->request->getVar('name')) {
        $name = trim($this->request->getVar('name'));
        $parent_id = $this->request->getVar('parent_id');
        $semester_id = $this->request->getVar('semester_id');
        $sql = " SELECT 1 FROM schooluser WHERE name = '$name' and semester_id = {$semester_id} and status=1";
        $rst = $this->db->query($sql)->getResultArray();
        if (is_array($rst) && count($rst) > 0) {
          ShowMsg("school user'name already exists", $_SESSION['admin_path'].'schooluser/add');
        }

        $insertdata['semester_id'] = $semester_id;
        $insertdata['parent_id'] = $parent_id;
        $insertdata['name'] = $name;
        $insertdata['type'] = $this->request->getVar('type');
        $insertdata['phone'] = $this->request->getVar('phone');
        $insertdata['email'] = $this->request->getVar('email');
        $insertdata['desc'] = $this->request->getVar('desc');
        $insertdata['phone'] = $this->request->getVar('phone');
        $insertdata['isshow'] = $this->request->getVar('isshow');
        $insertdata['type'] = $this->request->getVar('type');
        $insertdata['image'] = $this->request->getVar('icon');
        $insertdata['show_order'] = $this->request->getVar('show_order') ? $this->request->getVar('show_order') : 0;
        //$this->db->insert('schooluser', $insertdata);
        $this->db->table('schooluser')
          ->insert($insertdata);
        ShowMsg("add success!", $_SESSION['admin_path'].'schooluser/index');
      }
    }

    $semester = new Semester();
    $data['semesters'] = $semester->getSemesters();
    $semester->getCurrentSemester();

    $sql = "select *
				from parents
				order by parent_id";
    $data['parents'] = $this->db->query($sql)->getResultArray();

    $sql = "SELECT id,ename FROM sys_enum
    	  WHERE egroup = 'schoolusertype' ";
    $data['groupList'] = $this->db->query($sql)->getResultArray();
    echo view($_SESSION['tm'].'admin/schooluser/add.php', $data);
  }

  /**
   * edit
   */
  function edit()
  {
    if ($this->request->getVar('dopost') == 'save') {
      // 判断用户名是否存在
      if ($this->request->getVar('id')) {
        /*$name = trim($this->request->getVar('name']);
        $sql = " SELECT 1 FROM schooluser WHERE name = '$name' and id <> {$this->request->getVar('id']} and status=1";
         $rst = $this->db->query($sql)->getResultArray();
        if (is_array($rst) && count($rst) > 0) {
          ShowMsg("school user'name already exists", $_SESSION['admin_path'].'schooluser/add'));
        }*/
        $name = trim($this->request->getVar('name'));
        $parent_id = $this->request->getVar('parent_id');
        $semester_id = $this->request->getVar('semester_id');
        $insertdata['parent_id'] = $parent_id;
        $insertdata['semester_id'] = $semester_id;
        $insertdata['name'] = $name;
        $insertdata['type'] = $this->request->getVar('type');
        $insertdata['phone'] = $this->request->getVar('phone');
        $insertdata['email'] = $this->request->getVar('email');
        $insertdata['desc'] = $this->request->getVar('desc');
        $insertdata['phone'] = $this->request->getVar('phone');
        $insertdata['isshow'] = $this->request->getVar('isshow');
        $insertdata['type'] = $this->request->getVar('type');
        $insertdata['image'] = $this->request->getVar('icon');
        $insertdata['show_order'] = $this->request->getVar('show_order');
        $wheredata['id'] = $this->request->getVar('id');

        //$this->db->update('schooluser', $insertdata, $wheredata);
        $this->db->table('schooluser')
          ->where($wheredata)
          ->update($insertdata);

        ShowMsg('edit success!', $_SESSION['admin_path'].'schooluser/index');
      }
    }
    $id = (int)$this->request->getVar('id');
    if (empty($id)) {
      ShowMsg('param error', $_SESSION['admin_path'].'schooluser/index');
    }
    $sql = " SELECT * FROM schooluser WHERE id = $id";
    $data['info'] = $this->db->query($sql)->getRowArray();

    $semester = new Semester();
    $data['semesters'] = $semester->getSemesters();
    $semester->getCurrentSemester();

    $sql = "select *
				from parents
				order by parent_id";
    $data['parents'] = $this->db->query($sql)->getResultArray();

    $sql = "SELECT id,ename FROM sys_enum
    	  WHERE egroup = 'schoolusertype' ";
    $data['groupList'] = $this->db->query($sql)->getResultArray();
    echo view($_SESSION['tm'].'admin/schooluser/edit.php', $data);
  }

  /**
   *  del
   */
  function del()
  {
    $id = (int)$this->request->getVar('id');
    if (empty($id)) {
      ShowMsg('param error', $_SESSION['admin_path'].'schooluser/index');
    }
    $sql = " update  schooluser set status = '2' WHERE id = $id";
    $this->db->query($sql);
    ShowMsg('delete success!', $_SESSION['admin_path'].'schooluser/index');
  }
}

