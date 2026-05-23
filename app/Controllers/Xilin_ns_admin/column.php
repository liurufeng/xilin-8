<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 栏目管理
 * 需求：根据需求创建栏目 选择适合的模型 然后选择对应的可选字段 以此创建栏目
 * @copyright  Copyright (c) 2002-2013
 * @version    $Id:  1 2012-08-20 10:23:34 zhangjian <zhangjian1895@outlook.com> $
 */
class Column extends MY_Controller
{

  /**
   *
   * 初始化
   */
  function __construct()
  {

    $this->_classname = '栏目管理';
    $this->_methods = array(
      'index' => 'list',
      'add' => '添加',
      'edit' => '修改',
      'del' => '删除',
    );
    $this->_issystem = TRUE;

    parent::_Mycontroller();
    parent::_check_login();

  }

  /**
   * lists
   *
   */
  function index()
  {

    $do = $this->input->get('do');
    if ($do === 'upcatcache') {

      $this->load->model('typeunit_model');

      $this->typeunit_model->writecache();
      ShowMsg('栏目缓存更新成功！!', $_SESSION['admin_path'].'column/index'), 0, 1000);
    } elseif ($do === 'upRankAll') {
      $sortrank = $this->request->getVar('sortrank'];
      foreach ($sortrank as $k => $v) {

        $sql = "update  arctype') . " set sortrank = $v where id = $k";
        $this->db->query($sql);

      }
      $this->load->model('typeunit_model');

      $this->typeunit_model->writecache();
      ShowMsg('排序更新成功！!', $_SESSION['admin_path'].'column/index'), 0, 1000);
    } else {

      $sql = "SELECT id,nid,typename FROM  channeltype') . " ORDER BY id DESC";
      $dlist = $this->db->query($sql)->result_array();
      foreach ($dlist as $k => $v) {
        $data['dlist'][$v['id']] = $v;
      }
      $this->load->model('typeunit_model', '', TRUE);
      $result = $this->typeunit_model->getChildList(0);

      foreach ($result as $k => $v) {
        //if ($v['reid']==0) continue;

        if ($v['level'] > 1) {
          if ($v['level'] == 2) {
            $v['typename'] = '<span style="font-weight:bold;color:#990000">' . $v['typename'] . '</span>';
          } elseif ($v['level'] == 3) {
            $v['typename'] = '<span style="font-weight:bold;color:#006600">' . $v['typename'] . '</span>';
          }
          $level = str_repeat('　 ', $v['level'] - 2);
          $level .= '|—';
        } else {
          $level = '';
        }


        $v['pre'] = $level;
        $lists[] = $v;
      }

      $data['lists'] = $lists;
      //dump($data);exit;
      $this->load->view('column/index.htm', $data);
    }
  }

  /**
   * 栏目添加
   */
  function add()
  {

    $dopost = $this->request->getVar('dopost');
    if ($dopost == 'save') {
      $this->load->helper('dedefile');
      $data['reid'] = $this->request->getVar('reid'];
      $data['typename'] = $this->request->getVar('typename'];
      $data['ispart'] = $this->request->getVar('ispart'];
      $data['sortrank'] = $this->request->getVar('sortrank'];
      $data['channeltypeid'] = $this->request->getVar('channeltypeid'];
      $data['searchtype'] = $this->request->getVar('searchtype'];
      $data['seotitle'] = $this->request->getVar('seotitle'];
      $data['url'] = $this->request->getVar('url'];
      $data['banner'] = $this->request->getVar('picname'] ? $this->request->getVar('picname'] : '0';
      $data['content'] = $this->request->getVar('content'];
      $data['description'] = Html2Text($this->request->getVar('description'], 1);
      $data['keywords'] = Html2Text($this->request->getVar('keywords'], 1);
      // dump($data);exit;
      $this->db->insert('arctype', $data);
      $this->load->model('typeunit_model');
      $this->typeunit_model->writecache();
      ShowMsg('成功创建一个栏目', $_SESSION['admin_path'].'column/index'), 0, 1000);

    }
    $reid = $this->input->get('reid');
    if (empty($reid)) {
      $data['rename'] = '顶级栏目目录';
    } else {
      $this->load->model('typeunit_model');
      $data['rename'] = $this->typeunit_model->id2name($reid);
    }

    $this->load->model('modelv_model');
    $data['list'] = $this->modelv_model->getModelv();

    $this->load->model('search_model');
    $data['SearchTypes'] = $this->search_model->getSearchType();
    //dump($data);
    $this->load->view('column/add.htm', $data);
  }

  /**
   * 栏目编辑
   */
  function edit()
  {

    $dopost = $this->request->getVar('dopost');
    if ($dopost == 'save') {
      $this->load->helper('dedefile');
      $data['typename'] = $this->request->getVar('typename'];
      $data['ispart'] = $this->request->getVar('ispart'];
      $data['sortrank'] = $this->request->getVar('sortrank'];
      $data['channeltypeid'] = $this->request->getVar('channeltypeid'];
      $data['searchtype'] = $this->request->getVar('searchtype'];
      $data['seotitle'] = $this->request->getVar('seotitle'];
      $data['url'] = $this->request->getVar('url'];
      $data['banner'] = $this->request->getVar('picname'] ? $this->request->getVar('picname'] : '0';
      $data['content'] = $this->request->getVar('content'];
      $data['description'] = Html2Text($this->request->getVar('description'], 1);
      $data['keywords'] = Html2Text($this->request->getVar('keywords'], 1);
      // dump($data);exit;
      $where['id'] = $this->request->getVar('id'];

      $this->db->update('arctype', $data, $where);

      $this->load->model('typeunit_model');
      $this->typeunit_model->writecache();
      ShowMsg('编辑成功', $_SESSION['admin_path'].'column/index'), 0, 1000);

    }
    $id = $this->input->get('reid');
    if (empty($id)) {
      ShowMsg('参数错误', $_SESSION['admin_path'].'column/index'), 0, 1000);
    }
    $sql = "select * from  arctype') . " where id = $id";
    $data['info'] = $this->db->query($sql)->getRowArray();

    $this->load->model('modelv_model');
    $data['list'] = $this->modelv_model->getModelv();
    $this->load->model('search_model');
    $data['SearchTypes'] = $this->search_model->getSearchType();
    $data['reid'] = $id;
    $this->load->model('info_model');
    if ($data['info']['banner']) {
      $data['info']['picid1arr'] = $this->info_model->getAttinfo($data['info']['banner']);
    }
//		dump($data);
    $this->load->view('column/edit.htm', $data);
  }

  /**
   *
   * 删除
   */
  function del()
  {
    $reid = $this->input->get('reid');
    $result = $this->db->delete('arctype', array('id' => $reid));
    ShowMsg('删除成功!', $_SESSION['admin_path'].'column/index'), 0, 1500);
  }


}

// end column.php