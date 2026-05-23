<?php namespace App\Controllers\Xilin_ns_admin;

/**
 *
 * 权限节点
 * @author Rufeng Liu
 * @version
 * @link
 * @copyright
 */
class System extends MY_Controller
{

  function __construct()
  {

    $this->_classname = '权限节点管理';
    $this->_methods = array(
      'index' => '权限节点查看',
      'add_node' => '权限节点添加',
    );
    $this->_issystem = TRUE;
    parent::_Mycontroller();
    parent::_check_login();
  }

  /**
   *
   * 节点lists
   *
   */
  function index()
  {

    $this->load->model('system_note', '', TRUE);
    $result = $this->system_note->getChildListAll(0);;
    foreach ($result as $k => $v) {

      if ($v['level'] == 1) {
        $v['name'] = '<span style="font-weight:bold;color:#990000">' . $v['name'] . '</span>';
      } elseif ($v['level'] == 2) {
        $v['name'] = '<span style="font-weight:bold;color:#006600">' . $v['name'] . '</span>';
      }
      $level = str_repeat('　 ', $v['level'] - 1);
      $level .= '|—';

      if ($v['is_check'] == 1) {
        $v['check'] = '正常';
      } else {
        $v['check'] = '<span style="color:#f00">待审核</span>';
      }

      if ($v['level'] == '3') {
        $v['codes'] = $result[$v['reid']]['code'] . '_' . $v['code'];
      } else {
        $v['codes'] = $v['code'];
      }
      $v['pre'] = $level;
      $lists[] = $v;
    }
    $data['lists'] = $lists;
    //dump($data);exit;
    echo view($_SESSION['tm'].'admin/system/index.htm', $data);
  }

  /**
   *
   * 添加节点
   */
  function add_node()
  {

    $this->load->helper('cookie');
    $this->load->model('menu_note');
    $this->load->model('system_note');

    if (isset($this->request->getVar('act']) && $this->request->getVar('act'] == 'ok') {

      $data = array();
      $data['reid'] = $this->request->getVar('reid'];
      $data['name'] = trim($this->request->getVar('name']);
      $data['code'] = trim($this->request->getVar('code']);
      $data['description'] = nl2br($this->request->getVar('description']);
      $data['sort'] = $this->request->getVar('sort'];
      $data['is_check'] = $this->request->getVar('is_check'];

      if ($data['reid'] == 0) {
        $data['typeid'] = 0;
      } else {

        // 检查上级栏目是否是应用和模块 操作下面禁止添加分类
        $result = $this->system_note->getParentList($data['reid']);

        if (count($result) >= 2) {
          showmsg('错误！操作下面禁止添加分类！');
        }
        $data['typeid'] = count($result) + 1;
        // 检查同级分类有无相同的标示符
        $result = $this->db->query("SELECT id
						FROM  admin_node') . "
						WHERE reid='$data[reid]' AND code='$data[code]'")->num_rows();
        if ($result > 0) {
          showmsg('错误！存在同级别同名权限标示！');
        }
      }
      $num = $this->db->insert('admin_node', $data);
      set_cookie('nodeadd', $this->request->getVar('reid'], 3600);
      showmsg('操作节点添加成功！', $_SESSION['admin_path'].'system/index'));
      //$this->success('操作节点添加成功！');
    }


    $reid = $_GET['reid'] ? $_GET['reid'] : (int)get_cookie('nodeadd');
    $nodeSelect = $this->system_note->getNodeSelect($reid, '', 15);
    $data['nodeSelect'] = $nodeSelect;
    echo view($_SESSION['tm'].'admin/system/add_node.htm', $data);
  }

  /**
   * 清除缓存
   */
  function updatecache()
  {

    $this->load->model('system_note');

    $this->system_note->writecache();
    ShowMsg('节点缓存更新成功！!', $_SESSION['admin_path'].'system/index'), 0, 1000);
  }
}

// end system.php