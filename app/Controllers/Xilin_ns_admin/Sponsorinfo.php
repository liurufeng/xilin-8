<?php namespace App\Controllers\Xilin_ns_admin;

class Sponsorinfo extends MY_Controller
{

  function __construct()
  {

    $this->_classname = 'Sponsorinfo';
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
    $sql = "select spon.*,spon_le.name lename
				from sponsor spon
				join sponsor_level spon_le on spon.level_id = spon_le.level_id
				where spon.status = 1";
    $data['list'] = $this->db->query($sql)->getResultArray();
    echo view($_SESSION['tm'].'admin/sponsorinfo/index.htm', $data);
  }

  /**
   * add
   */
  function add()
  {
    if ($this->request->getVar('dopost') == 'save' || $this->request->getVar('dopost') == 'add') {
      if ($this->request->getVar('name')) {
        $name = trim($this->request->getVar('name'));
        $sql = " SELECT 1 FROM  sponsor WHERE name = '$name' and status=1";
         $rst = $this->db->query($sql)->getResultArray();
        if (is_array($rst) && count($rst) > 0) {
          ShowMsg("Sponsor already exists", $_SESSION['admin_path'].'sponsorinfo/add');
        }
        $insertdata['name'] = $this->request->getVar('name');
        $insertdata['level_id'] = $this->request->getVar('level_id');
        $insertdata['image_path'] = $this->request->getVar('icon');
        $insertdata['link'] = $this->request->getVar('link');
        $insertdata['note'] = $this->request->getVar('note');
        $insertdata['created_at'] = date("Y-m-d H:i:s");
        $insertdata['updated_at'] = date("Y-m-d H:i:s");
        $insertdata['next_pay_day'] = $this->request->getVar('next_pay_day');
        $insertdata['next_pay_amount'] = $this->request->getVar('next_pay_amount');
        $insertdata['active'] = $this->request->getVar('active');
        $insertdata['show_order'] = $this->request->getVar('show_order');
        //$this->db->insert('sponsor', $insertdata);
        $this->db->table('sponsor')
          ->insert($insertdata);
        ShowMsg("add success!", $_SESSION['admin_path'].'sponsorinfo/index');
      }
    }
    $sql = "select level_id,name
				from sponsor_level
				where status = 1";
    $data['groupList'] = $this->db->query($sql)->getResultArray();
    echo view($_SESSION['tm'].'admin/sponsorinfo/add.htm', $data);
  }

  /**
   * edit
   */
  function edit()
  {
    if ($this->request->getVar('dopost') == 'save') {
      // 判断用户名是否存在
      if ($this->request->getVar('sponsor_id')) {
        $name = trim($this->request->getVar('name'));
        $sql = " SELECT 1 FROM  sponsor WHERE name = '$name' and sponsor_id <> {$this->request->getVar('sponsor_id')} and status=1";
         $rst = $this->db->query($sql)->getResultArray();
        if (is_array($rst) && count($rst) > 0) {
          ShowMsg("sponsor Info'name already exists", $_SESSION['admin_path'].'sponsorinfo/add');
        }
        $insertdata['name'] = $this->request->getVar('name');
        $insertdata['level_id'] = $this->request->getVar('level_id');
        //$insertdata['image_path'] = $this->request->getVar('icon');
        $insertdata['link'] = $this->request->getVar('link');
        $insertdata['note'] = $this->request->getVar('note');
        $insertdata['updated_at'] = date("Y-m-d H:i:s");
        $insertdata['next_pay_day'] = $this->request->getVar('next_pay_day');
        $insertdata['next_pay_amount'] = $this->request->getVar('next_pay_amount');
        $insertdata['active'] = $this->request->getVar('active');
        $insertdata['show_order'] = $this->request->getVar('show_order');
        $insertdata['image_path'] = $this->request->getVar('icon');
        $wheredata['sponsor_id'] = $this->request->getVar('sponsor_id');

        //$this->db->update('sponsor', $insertdata, $wheredata);
        $this->db->table('sponsor')
          ->where($wheredata)
          ->update($insertdata);

        ShowMsg('edit success!', $_SESSION['admin_path'].'sponsorinfo/index');
      }
    }
    $sponsor_id = (int)$this->request->getVar('sponsor_id');
    if (empty($sponsor_id)) {
      ShowMsg('param error', $_SESSION['admin_path'].'sponsorinfo/index');
    }

    $sql = "select level_id,name
				from sponsor_level
				where status = 1";
    $data['groupList'] = $this->db->query($sql)->getResultArray();
    $sql = " SELECT * FROM  sponsor WHERE sponsor_id = $sponsor_id";
    $data['info'] = $this->db->query($sql)->getRowArray();
    echo view($_SESSION['tm'].'admin/sponsorinfo/edit.htm', $data);
  }

  /**
   *  del
   */
  function del()
  {

    $sponsor_id = (int)$this->request->getVar('sponsor_id');
    if (empty($sponsor_id)) {
      ShowMsg('param error', $_SESSION['admin_path'].'sponsorinfo/index');
    }
    $sql = " update   sponsor set status = '2' WHERE sponsor_id = $sponsor_id";
    $this->db->query($sql);
    ShowMsg('delete success!', $_SESSION['admin_path'].'sponsorinfo/index');
  }
}

