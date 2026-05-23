<?php namespace App\Controllers\Xilin_ns_admin;

class Newsletters extends MY_Controller
{

  function __construct()
  {

    $this->_classname = 'Newsletters';
    $this->_methods = array(
      'index' => 'list',
      'add' => 'add',
      'edit' => 'edit',
      'del' => 'del',
    );
    $this->_issystem = TRUE;
    parent::_Mycontroller();
    parent::_check_login();
    define('ROOT', dirname(__FILE__) . '/');
  }

  /**
   *
   * list
   *
   */
  function index()
  {
    $sql = "select *
				from newsletters
				where status = 1";
    $data['list'] = $this->db->query($sql)->getResultArray();
    echo view($_SESSION['tm'].'admin/newsletters/index.php', $data);
  }

  /**
   * add
   */
  function add()
  {
    if ($this->request->getVar('dopost') == 'save' || $this->request->getVar('dopost') == 'add') {
      if ($this->request->getVar('name')) {
        $name = trim($this->request->getVar('name'));
        $sql = " SELECT 1 FROM newsletters WHERE name = '$name' and status=1";
        $rst = $this->db->query($sql)->getResultArray();
        if (is_array($rst) && count($rst) > 0) {
          ShowMsg("newsletters'name already exists", $_SESSION['admin_path'].'newsletters/add');
        }
        /*if (!empty($_FILES) && $_FILES['url']['tmp_name']) {
          if (!is_dir(ROOT . '../../uploadfiles/newsletters')) {
            $this->load->helper('dedefile');
            MkdirAll(ROOT . '../../uploadfiles/newsletter');
          }
          if (is_uploaded_file($_FILES['url']['tmp_name'])) {
            $stored_path = ROOT . '../../uploadfiles/newsletters/' . basename($_FILES['url']['name']);
            move_uploaded_file($_FILES['url']['tmp_name'], $stored_path);
          }
          $result = move_uploaded_file($_FILES['url']['tmp_name'], dirname(__FILE__) . "/uploadfiles/newsletters/" . $_FILES['url']['name']);
        }*/
        $insertdata['url'] = $this->request->getVar('url');
        $insertdata['img_url'] = $this->request->getVar('img_url');
        $insertdata['name'] = $this->request->getVar('name');
        //$insertdata['url'] = "https://" . $_SERVER['HTTP_HOST'] . "/uploadfiles/newsletters/" . basename($_FILES['url']['name']);
        $insertdata['isshow'] = $this->request->getVar('isshow');
        $insertdata['seq'] = $this->request->getVar('seq');
        $insertdata['desc'] = $this->request->getVar('desc');
        //$this->db->insert('newsletters', $insertdata);
        $this->db->table('newsletters')
          ->insert($insertdata);
        ShowMsg("add success!", $_SESSION['admin_path'].'newsletters/index');
      }
    }
    echo view($_SESSION['tm'].'admin/newsletters/add.php');
  }

  /**
   * edit
   */
  function edit()
  {
    if ($this->request->getVar('dopost') == 'save') {
      // 判断用户名是否存在
      if ($this->request->getVar('id')) {
        $name = trim($this->request->getVar('name'));
        $sql = " SELECT 1 FROM  newsletters WHERE name = '$name' and id <> {$this->request->getVar('id')} and status=1";
         $rst = $this->db->query($sql)->getResultArray();
        if (is_array($rst) && count($rst) > 0) {
          ShowMsg("newsletters'name already exists", $_SESSION['admin_path'].'newsletters/add');
        }
        /*if (!empty($_FILES) && $_FILES['url']['tmp_name']) {
          if (!is_dir(ROOT . '../../uploadfiles/newsletters')) {
            $this->load->helper('dedefile');
            MkdirAll(ROOT . '../../uploadfiles/newsletter');
          }
          if (is_uploaded_file($_FILES['url']['tmp_name'])) {
            $stored_path = ROOT . '../../uploadfiles/newsletters/' . basename($_FILES['url']['name']);
            move_uploaded_file($_FILES['url']['tmp_name'], $stored_path);
          }
          $result = move_uploaded_file($_FILES['url']['tmp_name'], dirname(__FILE__) . "/uploadfiles/newsletters/" . $_FILES['url']['name']);
          $insertdata['url'] = "http://" . $_SERVER['HTTP_HOST'] . "/uploadfiles/newsletters/" . basename($_FILES['url']['name']);
        }*/
        $insertdata['url'] = $this->request->getVar('url');
        $insertdata['img_url'] = $this->request->getVar('img_url');
        $insertdata['name'] = $this->request->getVar('name');
        $insertdata['isshow'] = $this->request->getVar('isshow');
        $insertdata['seq'] = $this->request->getVar('seq');
        $insertdata['desc'] = $this->request->getVar('desc');
        $wheredata['id'] = $this->request->getVar('id');
        $this->db->table('newsletters')
          ->where($wheredata)
          ->update($insertdata);
        ShowMsg('edit success!', $_SESSION['admin_path'].'newsletters/index');
      }
    }
    $id = (int)$this->request->getVar('id');
    if (empty($id)) {
      ShowMsg('param error', $_SESSION['admin_path'].'newsletters/index');
    }
    $sql = " SELECT * FROM newsletters WHERE id = $id";
    $data['info'] = $this->db->query($sql)->getRowArray();
    echo view($_SESSION['tm'].'admin/newsletters/edit.php', $data);
  }

  /**
   *  del
   */
  function del()
  {

    $id = (int)$this->request->getVar('id');
    if (empty($id)) {
      ShowMsg('param error', $_SESSION['admin_path'].'newsletters/index');
    }
    $sql = " update newsletters set status = '2' WHERE id = $id";
    $this->db->query($sql);
    ShowMsg('delete success!', $_SESSION['admin_path'].'newsletters/index');
  }
}

