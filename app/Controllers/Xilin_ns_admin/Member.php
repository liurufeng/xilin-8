<?php namespace App\Controllers\Xilin_ns_admin;

use App\Models\Admin\Member_model;
/**
 *
 * 管理员
 * @author Rufeng Liu
 * @version
 * @link
 * @copyright
 */
class Member extends MY_Controller
{

  function __construct()
  {

    $this->_classname = '管理员模块';
    $this->_methods = array(
      'index' => '管理员查看',
      'add' => '管理员添加',
      'edit' => '管理员修改',
      'del' => '管理员删除',
      'group' => '管理组查看',
      'groupadd' => '管理组添加',
      'groupedit' => '管理组修改',
      'groupdel' => '管理组删除',
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
    $id = $this->request->getVar('id');
    $wheresql = '';
    if (!empty($id)) {

      $wheresql = ' and a.group_id = ' . $id;
    }
    $sql = "SELECT a.id as uid, a.name as username, a.realname, a.loginCount, a.lastLoginTime,a.lastLoginIp,ar.name
				FROM admin AS a
				LEFT JOIN admin_role AS ar
				ON a.group_id = ar.id
				WHERE a.state <> '2' and a.id != 88 $wheresql
				LIMIT 100
				";

    $data['list'] = $this->db->query($sql)->getResultArray();
    echo view($_SESSION['tm'].'admin/member/member_index.htm', $data);
  }

  /**
   * 添加管理员
   */
  function add()
  {
    if ($this->request->getVar('dopost') == 'add') {


      // 判断用户名是否存在
      if ($this->request->getVar('username')) {
        $username = trim($this->request->getVar('username'));
        $sql = " SELECT 1 FROM  admin WHERE name = '$username'";
         $rst = $this->db->query($sql)->getResultArray();
        if (is_array($rst) && count($rst) > 0) {
          ShowMsg('username already exists', $_SESSION['admin_path'].'member/add');
        }

        $insertdata['name'] = $this->request->getVar('username');
        $insertdata['realname'] = $this->request->getVar('realname');
        $insertdata['pass'] = $this->request->getVar('pwd');//md5($this->request->getVar('pwd'));
        $insertdata['pwd'] = $this->request->getVar('pwd');
        $insertdata['group_id'] = $this->request->getVar('groupid');
        $insertdata['email'] = $this->request->getVar('email');
        $this->db->insert('admin', $insertdata);
        ShowMsg('add success', $_SESSION['admin_path'].'member/index');
      }
    }
    $this->load->model('member_model');
    $data['groupList'] = Member_model::getGroupList();

    echo view($_SESSION['tm'].'admin/member/member_add.htm', $data);
  }

  /**
   * 编辑管理员
   */
  function edit()
  {
    if ($this->request->getVar('dopost') == 'save') {

      // 判断用户名是否存在
      if ($this->request->getVar('username')) {
        $username = trim($this->request->getVar('username'));
        $sql = " SELECT 1 FROM  admin WHERE name = '$username' and id <> " . $this->request->getVar('uid');
         $rst = $this->db->query($sql)->getResultArray();
        if (is_array($rst) && count($rst) > 0) {
          ShowMsg('username already exists', $_SESSION['admin_path'].'member/add');
        }

        $insertdata['name'] = $this->request->getVar('username');
        $insertdata['realname'] = $this->request->getVar('realname');
        $insertdata['pwd'] = $this->request->getVar('pwd');
        $pwd = $this->request->getVar('pwd');
        if (!empty($pwd)) {
          $insertdata['pass'] = $this->request->getVar('pwd');//md5($this->request->getVar('pwd'));
        }
        $insertdata['group_id'] = $this->request->getVar('groupid');
        $insertdata['email'] = $this->request->getVar('email');
        $wheredata['id'] = $this->request->getVar('uid');

        //$this->db->update('admin', $insertdata, $wheredata);
        $this->db->table('admin')
          ->where($wheredata)
          ->update($insertdata);

        ShowMsg('edit success', $_SESSION['admin_path'].'member/index');
      }
    }
    //$this->load->model('member_model');
    $member_model = new Member_model();
    $data['groupList'] = $member_model->getGroupList();

    $uid = (int)$this->request->getVar('uid');
    if (empty($uid)) {
      ShowMsg('param error', $_SESSION['admin_path'].'member/index');
    }
    $sql = " SELECT * FROM  admin WHERE id = $uid";
    $data['info'] = $this->db->query($sql)->getRowArray();
    echo view($_SESSION['tm'].'admin/member/member_edit.htm', $data);
  }

  /**
   *  删除用户
   */
  function del()
  {

    $uid = (int)$this->request->getVar('uid');
    if (empty($uid)) {
      ShowMsg('param error', $_SESSION['admin_path'].'member/index');
    }
    $sql = " update   admin set state = '2' WHERE id = $uid";
    $this->db->query($sql);
    ShowMsg('delete success', $_SESSION['admin_path'].'member/index');
  }

  /**
   * 管理组
   */
  function group()
  {
    //$this->load->model('member_model');
    $member_model = new Member_model();
    $data['groupList'] = $member_model->getGroupList();
    echo view($_SESSION['tm'].'admin/member/member_group.htm', $data);
  }

  /**
   * 添加管理组
   */
  function groupadd()
  {
    if ($this->request->getVar('dopost') == 'save') {

      $insertdata = array();
      $insertdata['nodeids'] = implode(',', $this->request->getVar('nodeids'));
      $insertdata['name'] = $this->request->getVar('groupname');
      $insertdata['is_check'] = '1';
      $insertdata['description'] = $this->request->getVar('description');
      //$this->db->insert('admin_role', $insertdata);
      $this->db->table('admin_role')
        ->insert($insertdata);
      ShowMsg('add success', $_SESSION['admin_path'].'member/group');
    }
    $sql = "select id,name from admin_node where is_check =1 and reid = 184";
    $data['gouplists'] = $this->db->query($sql)->getResultArray();
    echo view($_SESSION['tm'].'admin/member/member_grpupadd.htm', $data);
  }

  /**
   * 会员组管理
   */
  function groupedit()
  {

    $data = array();
    if ($this->request->getVar('dopost') == 'save') {
      $insertdata = $updatedata = array();
      $insertdata['nodeids'] = implode(',', $this->request->getVar('nodeids'));
      $insertdata['name'] = $this->request->getVar('groupname');
      $insertdata['is_check'] = '1';
      $insertdata['description'] = $this->request->getVar('description');
      $updatedata['id'] = $this->request->getVar('id');
      //$this->db->update('admin_role', $insertdata, $updatedata);
      $this->db->table('admin_role')
        ->where($updatedata)
        ->update($insertdata);

      ShowMsg('edit success', $_SESSION['admin_path'].'member/group');
    }
    $id = (int)$this->request->getVar('id');
    if (empty($id)) {
      ShowMsg('param error', $_SESSION['admin_path'].'member/group');
    }
    $sql = " SELECT * FROM admin_role WHERE id = $id";
    $data['info'] = $this->db->query($sql)->getRowArray();
    $data['info']['nodeidsarr'] = explode(',', $data['info']['nodeids']);

    $sql = "select id,name from admin_node where is_check =1 and reid = 184";
    $data['gouplists'] = $this->db->query($sql)->getResultArray();
    echo view($_SESSION['tm'].'admin/member/member_grpupedit.htm', $data);
  }

  /**
   *
   * 删除
   */
  function groupdel()
  {
    $id = (int)$this->request->getVar('id');
    if (empty($id)) {
      ShowMsg('param error', $_SESSION['admin_path'].'member/group');
    }
    $sql = " update   admin_role set is_check = '2' WHERE id = $id";
    $this->db->query($sql);
    ShowMsg('delete success!', $_SESSION['admin_path'].'member/group');
  }
}

// end system.php