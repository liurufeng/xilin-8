<?php namespace App\Controllers\Xilin_ns_admin;

class Sponsorlevel extends MY_Controller
{

  function __construct()
  {

    $this->_classname = 'Sponsorlevel';
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
				from sponsor_level
				where status = 1";
    $data['list'] = $this->db->query($sql)->getResultArray();
    echo view($_SESSION['tm'].'admin/sponsorlevel/index.htm', $data);
  }

  /**
   * add
   */
  function add()
  {
    if ($this->request->getVar('dopost') == 'save' || $this->request->getVar('dopost') == 'add') {
      if ($this->request->getVar('name')) {
        $name = trim($this->request->getVar('name'));
        $sql = " SELECT 1 FROM  sponsor_level WHERE name = '$name' and status=1";
         $rst = $this->db->query($sql)->getResultArray();
        if (is_array($rst) && count($rst) > 0) {
          ShowMsg("sponsor level'name already exists", $_SESSION['admin_path'].'sponsorlevel/add');
        }
        $insertdata['name'] = $this->request->getVar('name');
        $insertdata['show_order'] = $this->request->getVar('show_order');
        $insertdata['icon'] = $this->request->getVar('icon');
        //$this->db->insert('sponsor_level', $insertdata);
        $this->db->table('sponsor_level')
          ->insert($insertdata);
        ShowMsg("add success!", $_SESSION['admin_path'].'sponsorlevel/index');
      }
    }
    echo view($_SESSION['tm'].'admin/sponsorlevel/add.htm');
  }

  /**
   * edit
   */
  function edit()
  {
    if ($this->request->getVar('dopost') == 'save') {
      // 判断用户名是否存在
      if ($this->request->getVar('level_id')) {
        $name = trim($this->request->getVar('name'));
        $sql = " SELECT 1 FROM  sponsor_level WHERE name = '$name' and level_id <> {$this->request->getVar('level_id')} and status=1";
        $rst = $this->db->query($sql)->getResultArray();
        if (is_array($rst) && count($rst) > 0) {
          ShowMsg("sponsor level'name already exists", $_SESSION['admin_path'].'sponsorlevel/add');
        }
        $insertdata['name'] = $this->request->getVar('name');
        $insertdata['show_order'] = $this->request->getVar('show_order');
        $insertdata['icon'] = $this->request->getVar('icon');
        $wheredata['level_id'] = $this->request->getVar('level_id');

        //$this->db->update('sponsor_level', $insertdata, $wheredata);
        $this->db->table('sponsor_level')
          ->where($wheredata)
          ->update($insertdata);
        ShowMsg('edit success!', $_SESSION['admin_path'].'sponsorlevel/index');
      }
    }
    $level_id = (int)$this->request->getVar('level_id');
    if (empty($level_id)) {
      ShowMsg('param error', $_SESSION['admin_path'].'sponsorlevel/index');
    }
    $sql = " SELECT * FROM  sponsor_level WHERE level_id = $level_id";
    $data['info'] = $this->db->query($sql)->getRowArray();
    echo view($_SESSION['tm'].'admin/sponsorlevel/edit.htm', $data);
  }

  /**
   *  del
   */
  function del()
  {

    $level_id = (int)$this->request->getVar('level_id');
    if (empty($level_id)) {
      ShowMsg('param error', $_SESSION['admin_path'].'sponsorlevel/index');
    }
    $sql = " update   sponsor_level set status = '2' WHERE level_id = $level_id";
    $this->db->query($sql);
    ShowMsg('delete success!', $_SESSION['admin_path'].'sponsorlevel/index');
  }
}

