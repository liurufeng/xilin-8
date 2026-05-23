<?php namespace App\Controllers\Xilin_ns_admin;

use App\Models\Admin\{Menu_note, Typeunit_model, Info_model, Modelv_model, Dmp_model};

/**
 * 信息管理
 *
 * @copyright  Copyright (c) 2002-2013
 * @version    $Id: info.php 1 2012-08-20 10:23:34 zhangjian <zhangjian1895@outlook.com> $
 */
class Info extends MY_Controller
{
  /**
   *
   * 初始化
   */
  function __construct()
  {
    $this->_classname = '信息模块';
    $this->_methods = array(
      'index' => '信息查看',
      'add' => '信息添加',
      'edit' => '信息修改',
      'del' => '信息删除',
      'recycling' => '信息回收站',
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
    $typeunit_model =  new Typeunit_model();
    $info_model = new Info_model();
    $cid = $this->request->getVar('cid') ? (int)$this->request->getVar('cid') : '';
    $cid = empty($cid) ? $this->request->getVar('reid') ? (int)$this->request->getVar('reid') : '' : $cid;
    $data['cid'] = $cid;
    $addurl = $addsql = '';

    if ($cid) {
      $addsql .= " and unitid = $cid";
      $addurl .= "&cid=" . $cid;
      $data['cname'] = "<a href='" . $_SESSION['admin_path'].'info/index' . "?cid=" . $cid . "'>" . $typeunit_model->id2name($cid) . "</a>->";
      session()->set(array('cid' => $cid));
    }
    $keyword = $this->request->getVar('keyword') ? $this->request->getVar('keyword') : '';
    if ($keyword) {
      $addsql .= " and title like '%$keyword%' ";
      $addurl .= "&keyword=" . $keyword;
    }
    $flag = $this->request->getVar('flag') ? $this->request->getVar('flag') : '';
    if ($flag) {
      $addsql .= " and  flag like '%$flag%' ";
      $addurl .= "&flag=" . $flag;
    }
    $sql = "SELECT 1 FROM archives 
	  		    WHERE status <> '2' and status <> '3' $addsql
	  		    ";
    $rst = $this->db->query($sql)->getResultArray();

    helper('page');
    isset($_GET['page']) ? $page = (int)$_GET['page'] : $page = '0';
    $base_url = $_SESSION['admin_path'].'info/index' . '?' . rand(10000, 99999) . time() . $addurl;
    $page_size = '15';
    $beginpages = $page > 1 ? ($page - 1) * $page_size : '0';
    $data['counts'] = count($rst);
    $data['pages'] = page($base_url, count($rst), $page_size, $page);

    // 根据用户uid 检索所有关联需求
    $sql = "select *
				from archives
				WHERE status <> '2' and status <> '3' $addsql
				order by senddate desc, id desc 
				LIMIT $beginpages,$page_size ";

     $rst = $this->db->query($sql);
    $data['lists'] = $rst->getResultArray();
    $statusarr = array(
      '0' => '未发布',
      '1' => '已经发布',
      '2' => '回收站',

    );
    foreach ($data['lists'] as $k => $v) {

      $v['unitname'] = $typeunit_model->id2name($v['unitid']);
      $v['falgname'] = $info_model->IsCommendArchives($v['flag']);
      $v['status'] = $statusarr[$v['status']];
      $data['lists'][$k] = $v;
    }
    $data['flaglist'] = $info_model->getarcatts();
    $data['menuSelect'] = $typeunit_model->getMenuSelect($cid);

	  //	dump($data);
    echo view($_SESSION['tm'].'admin/info/index.htm', $data);
  }

  /**
   *
   * 添加
   */
  function add()
  {

    if ( $this->request->getVar('dopost') == 'save') {
      $insertdata = array();
      $flags = $this->request->getVar('flags');
      $insertdata['unitid'] = $this->request->getVar('reid');
      $insertdata['title'] = $this->request->getVar('title');
      $insertdata['ttitle'] = $this->request->getVar('ttitle');
      $insertdata['shorttitle'] = $this->request->getVar('shorttitle');
      $insertdata['flag'] = isset($flags) ? join(',', $flags) : '';

      $insertdata['typeid2'] = $this->request->getVar('type2');
      $insertdata['typeid'] = $this->request->getVar('type1');
      //$insertdata['types'] = join(',', $this->request->getVar('types'));
      $types = $this->request->getVar('types');
      $insertdata['types'] = isset($types) ? join(',', $types) : '';
      $insertdata['picid'] = $this->request->getVar('picname') ? $this->request->getVar('picname') : '0';
      $insertdata['picid2'] = $this->request->getVar('picname2') ? $this->request->getVar('picname2') : '0';
      $insertdata['pics'] = $this->request->getVar('pics');
      $insertdata['sortrank'] = $this->request->getVar('weight') ? $this->request->getVar('weight') : '0';
      $insertdata['source'] = $this->request->getVar('source');
      $insertdata['writer'] = $this->request->getVar('writer');
      $insertdata['click'] = $this->request->getVar('click');
      $insertdata['adddate'] = time();
      $insertdata['lastupdate'] = time();
      $insertdata['keywords'] = $this->request->getVar('keywords');
      $insertdata['senddate'] = strtotime($this->request->getVar('pubdate'));
      $insertdata['company'] = 0; //',' . implode(',', $this->request->getVar('company')) . ',';
      echo $this->request->getVar('pubdate');
      $insertdata['description'] = $this->request->getVar('description');
      $editorValue = $this->request->getVar('editorValue');
      //自动提取摘要 description
      if ($insertdata['description'] == '' && !empty($editorValue)) {

        $content = stripslashes($editorValue);
        $introcude_length = '80';
        helper('common');
        $systeminfo['description'] = cn_substr(str_replace(array("\r\n", "\t", '[page]', '[/page]', '&ldquo;', '&rdquo;', '&nbsp;'), '', strip_tags($content)), $introcude_length);
        $insertdata['description'] = $systeminfo['description'] = addslashes($systeminfo['description']);
      }

      //跳转网址的文档强制为动态
      if (preg_match("#j#", $insertdata['flag'])) {

        $insertdata['isjump'] = '1';
        $insertdata['jumpurl'] = $this->request->getVar('redirecturl');
      } else {
        $insertdata['isjump'] = '0';
        $insertdata['jumpurl'] = '';
      }
      //$this->load->model('typeunit_model', '', TRUE);
      $typeunit_model = new Typeunit_model();
      $result = $typeunit_model->getChildList(0);
      $insertdata['url'] = $result[$insertdata['unitid']]['url'];
      //$this->db->insert('archives', $insertdata);
      $this->db->table('archives')
        ->insert($insertdata);

      $addarc['aid'] = $this->db->insertID();
      $addarc['body'] = preg_replace("/<h1[^>]*>/i", '', $this->request->getVar('editorValue'));
      $addarc['userip'] = get_client_ip();
      //$this->db->insert('addonarticle', $addarc);
      $this->db->table('addonarticle')
        ->insert($addarc);

      $cid = $insertdata['unitid'];
      ShowMsg('添加成功', $_SESSION['admin_path'].'info/index' . '?cid=' . $cid, 0, 1000);
    }
//    $sql = "select * from company
//    where status = 1 order by sort DESC ";
//    $data['company'] = $this->db->query($sql)->getResultArray();
    $data['company'] = 0;
    $cid = $this->request->getVar('cid');
    if (empty($cid)) {
      ShowMsg('栏目id不能为空', $_SESSION['admin_path'].'info/index', 0, 1000);
    }
    $data['cid'] = $cid;
    //$this->load->library('ueditor');
    $ueditor = new \App\Libraries\Ueditor();
    $data['c'] = $ueditor->getueditor('other', '100', '100', 'utf-8');
    //$this->load->model('typeunit_model');
    //$this->load->model('modelv_model');
    $typeunit_model = new Typeunit_model();
    $modelv_model = new Modelv_model();

    $sql = "SELECT channeltypeid FROM arctype where id = $cid";
    $arctypeinfo = $this->db->query($sql)->getRowArray();
    $data['unitname'] = $typeunit_model->id2name($cid);
    // 获取模型
    $modelv = $modelv_model->getModelvInofbyId($arctypeinfo['channeltypeid']);
    foreach ($modelv['tablelists'] as $k => $v) {
      if (empty($v['t_name'])) {
        $v['t_name'] = $v['defaultinfo'];
      }
      $modelvarr[$v['tname']] = $v;
    }
    $data['modelvarr'] = $modelvarr;
    $data['desc_id'] = $arctypeinfo['channeltypeid'];
    //$this->load->model('dmp_model', 'dmp_model');
    //$dmp_model = new Dmp_model();
    $data['dmpcartypelists'] = null; //$dmp_model->getCartype();

    echo view($_SESSION['tm'].'admin/info/add.htm', $data);
  }

  /**
   *
   * 编辑
   */
  function edit()
  {

    if ($this->request->getVar('dopost') == 'save') {
      $insertdata = array();
      $flags = $this->request->getVar('flags');
      //$insertdata['unitid'] 		= $this->request->getVar('reid');
      $insertdata['title'] = $this->request->getVar('title');
      $insertdata['shorttitle'] = $this->request->getVar('shorttitle');
      $insertdata['ttitle'] = $this->request->getVar('ttitle');
      $insertdata['flag'] = isset($flags) ? join(',', $flags) : '';
      $insertdata['typeid2'] = $this->request->getVar('type2');
      $insertdata['typeid'] = $this->request->getVar('type1');
      $types = $this->request->getVar('types');
      $insertdata['types'] = isset($types) ? join(',', $types) : '';
      $insertdata['picid'] = $this->request->getVar('picname') ? $this->request->getVar('picname') : '0';
      $insertdata['picid2'] = $this->request->getVar('picname2') ? $this->request->getVar('picname2') : '0';
      $insertdata['pics'] = $this->request->getVar('pics');
      $insertdata['sortrank'] = $this->request->getVar('weight') ? $this->request->getVar('weight') : '0';
      $insertdata['source'] = $this->request->getVar('source');
      $insertdata['writer'] = $this->request->getVar('writer');
      $insertdata['click'] = $this->request->getVar('click');
      //$insertdata['adddate'] 		= time();
      $insertdata['lastupdate'] = time();
      $insertdata['keywords'] = $this->request->getVar('keywords');
      $insertdata['senddate'] = strtotime($this->request->getVar('pubdate'));
      $insertdata['description'] = $this->request->getVar('description');
      $insertdata['company'] = 0; // ',' . implode(',', $this->request->getVar('company') . ',';
      //自动提取摘要 description
      if ($insertdata['description'] == '' && !empty($editorValue)) {

        $content = stripslashes($editorValue);
        $introcude_length = '80';
        $systeminfo['description'] = cn_substr(str_replace(array("\r\n", "\t", '[page]', '[/page]', '&ldquo;', '&rdquo;', '&nbsp;'), '', strip_tags($content)), $introcude_length);
        $insertdata['description'] = $systeminfo['description'] = addslashes($systeminfo['description']);
      }

      //跳转网址的文档强制为动态
      if (preg_match("#j#", $insertdata['flag'])) {

        $insertdata['isjump'] = '1';
        $insertdata['jumpurl'] = $this->request->getVar('redirecturl');
      } else {
        $insertdata['isjump'] = '0';
        $insertdata['jumpurl'] = '';
      }
      $where['id'] = $this->request->getVar('aid');
      //dump($insertdata);exit;
      //$this->db->update('archives', $insertdata, $where);
      $this->db->table('archives')
        ->where($where)
        ->update($insertdata);
      //dump($this->db->last_query());exit;
      $addwhere['aid'] = $this->request->getVar('aid');
      $addarc['body'] = preg_replace("/<h1[^>]*>/i", '', $this->request->getVar('editorValue'));
      $addarc['userip'] = get_client_ip();
      //$this->db->update('addonarticle', $addarc, $addwhere);
      $this->db->table('addonarticle')
        ->where($addwhere)
        ->update($addarc);

      ShowMsg('编辑成功', $_SESSION['admin_path'].'info/index' . '?cid=' . $this->request->getVar('reid'), 0, 1000);
    }
    //$aid = $this->uri->segment(3);
    $uri = service('uri');
    if($uri->getTotalSegments() > 3 ) {
      $aid = $uri->getSegment(4);
    }

    if (empty($aid)) {
      ShowMsg('id不能为空', $_SESSION['admin_path'].'info/index', 0, 1000);
    }

    $ueditor = new \App\Libraries\Ueditor();
    $typeunit_model = new Typeunit_model();
    $modelv_model = new Modelv_model();
    $info_model = new Info_model();

    $ainfo = $info_model->getInfo($aid);
    if (is_array($ainfo) && $ainfo['picid']) {
      $ainfo['picid1arr'] = $info_model->getAttinfo($ainfo['picid']);
    }
    if (is_array($ainfo) && $ainfo['picid']) {
      $ainfo['picid2arr'] = $info_model->getAttinfo($ainfo['picid2']);
    }
    if (is_array($ainfo) && $ainfo['pics']) {
      $pics = explode(',', $ainfo['pics']);
      $pics = array_filter($pics);
      foreach ($pics as $k => $v) {
        if ($v) {
          $ainfo['picsarr'][] = $info_model->getAttinfo($v);
        }
      }
    }
    $data['ainfo'] = $ainfo;

    $ainfo_body = is_array($ainfo) && $ainfo['body'] ? $ainfo['body'] : '';
    $data['c'] = $ueditor->getueditor('other', '100', '100', 'utf-8', $ainfo_body);

    $cid = is_array($ainfo) && $ainfo['unitid'] ? $ainfo['unitid'] : 0; // $ainfo['unitid'];
    $data['cid'] = $cid;
    $sql = "SELECT channeltypeid FROM arctype where id = $cid";
    $arctypeinfo = $this->db->query($sql)->getRowArray();
    $data['unitname'] = $typeunit_model->id2name($cid);
    $data['cid'] = $cid;
    // 获取模型
    $modelv = $modelv_model->getModelvInofbyId($arctypeinfo['channeltypeid']);
    foreach ($modelv['tablelists'] as $k => $v) {
      if (empty($v['t_name'])) {
        $v['t_name'] = $v['defaultinfo'];
      }
      $modelvarr[$v['tname']] = $v;
    }
    $data['modelvarr'] = $modelvarr;


    $data['action'] = 'edit';
    $data['desc_id'] = $arctypeinfo['channeltypeid'];
    //$this->load->model('dmp_model', 'dmp_model');
    //$data['dmpcartypelists'] = $this->dmp_model->getCartype();
    //dump($ainfo);exit;
    echo view($_SESSION['tm'].'admin/info/add.htm', $data);
  }

  /**
   * 内容回收站
   *
   */
  function recycling()
  {

    $this->load->model('typeunit_model');
    $this->load->model('info_model');
    $sql = "SELECT 1 FROM archives
	  		    WHERE status = '2' 
	  		    ";
     $rst = $this->db->query($sql)->getResultArray();

    helper('page');
    isset($_GET['page']) ? $page = (int)$_GET['page'] : $page = '0';
    $base_url = $_SESSION['admin_path'].'info/recycling' . '?' . rand(10000, 99999) . time();
    $page_size = '15';
    $beginpages = $page > 1 ? ($page - 1) * $page_size : '0';
    $data['counts'] = count($rst);
    $data['pages'] = page($base_url, count($rst), $page_size, $page);

    // 根据用户uid 检索所有关联需求
    $sql = "select *
				from archives
				WHERE status = '2' 
				order by id desc 
				LIMIT $beginpages,$page_size ";

     $rst = $this->db->query($sql);
    $data['lists'] = $rst->getResultArray();
    $statusarr = array(
      '0' => '未发布',
      '1' => '已经发布',
      '2' => '回收站',

    );
    foreach ($data['lists'] as $k => $v) {

      $v['unitname'] = $this->typeunit_model->id2name($v['unitid']);
      $v['falgname'] = $this->info_model->IsCommendArchives($v['flag']);
      $v['status'] = $statusarr[$v['status']];
      $data['lists'][$k] = $v;
    }
    $data['flaglist'] = $this->info_model->getarcatts();

    echo view($_SESSION['tm'].'admin/info/recycling.htm', $data);
  }

  /**
   * 静态
   */
  function dohtml()
  {
    exit();
    $this->load->add_package_path('application/', true);
    echo view($_SESSION['tm'].'admin/info/index_test.htm');
    $string = $this->output->get_output();

    $this->load->helper('file');


    if (!write_file('./en/news/index.html', $string)) {
      echo 'Unable to write the file';
    } else {
      echo 'File written!';
      exit();
    }
  }

  // 执行的操作
  function doaction()
  {
    $dopost = $this->request->getVar('do');
    $info_model = new Info_model();
    $cid = session()->get('cid');
    if ($dopost == "checkArchives") {
      $aid = $this->request->getVar('aid');
      $qstr = $this->request->getVar('qstr');

      if (!empty($aid) && empty($qstr)) $qstr = $aid;
      if ($qstr == '') {
        ShowMsg("参数无效！", $_SESSION['admin_path'].'info/index' . '?cid=' . $cid);

      }
      $arcids = preg_replace("#[^0-9,]#", '', preg_replace("#`#", ',', $qstr));
      // 发布添加搜索
      $sql = "SELECT id,unitid,title,description,senddate FROM archives
		            WHERE id in($arcids) ";
      $aids = $this->db->query($sql)->getResultArray();
      if (!empty($aids)) {
        foreach ($aids as $k => $v) {
          $info_model->search_api($v['id'], $v);
        }
      }
      $sql = "update archives
		    		set status = '1' 
		            WHERE id in($arcids) ";
      $this->db->query($sql);


      ShowMsg("成功发布指定的文档！", $_SESSION['admin_path'].'info/index' . '?cid=' . $cid);
    } elseif ($dopost == "delArchives") {

      $aid = $this->request->getVar('aid');
      $qstr = $this->request->getVar('qstr');

      if (!empty($aid) && empty($qstr)) $qstr = $aid;
      if ($qstr == '') {
        ShowMsg("参数无效！", $_SESSION['admin_path'].'info/index' . '?cid=' . $cid);

      }
      $arcids = preg_replace("#[^0-9,]#", '', preg_replace("#`#", ',', $qstr));
      // 删除去除搜索
      $sql = "SELECT id,unitid,title,description,senddate FROM archives
		            WHERE id in($arcids) ";
      $aids = $this->db->query($sql)->getResultArray();
      if (!empty($aids)) {
        foreach ($aids as $k => $v) {
          $info_model->search_api($v['id'], $v, 'delete');
        }
      }
      $sql = "update archives
		    		set status = '2' 
		            WHERE id in($arcids) ";
      $this->db->query($sql);
      ShowMsg("成功删除指定的文档！", $_SESSION['admin_path'].'info/index' . '?cid=' . $cid);

    } elseif ($dopost == "return") {

      $aid = $this->request->getVar('aid');
      $qstr = $this->request->getVar('qstr');

      if (!empty($aid) && empty($qstr)) $qstr = $aid;

      if ($qstr == '') {
        ShowMsg("参数无效！", $_SESSION['admin_path'].'info/recycling' . '?cid=' . $cid);

      }
      $arcids = preg_replace("#[^0-9,]#", '', preg_replace("#`#", ',', $qstr));

      $sql = "update archives
		    		set status = '0' 
		            WHERE id in($arcids) ";

      $this->db->query($sql);
      ShowMsg("成功还原指定的文档！", $_SESSION['admin_path'].'info/recycling' . '?cid=' . $cid);
    } elseif ($dopost == "moveArchivesview") {

      // 转移数据
      if ($this->request->getVar('dopost') == 'save') {
        if (empty($this->request->getVar('cid')) || empty($this->request->getVar('ids'))) {
          ShowMsg("参数无效！", $_SESSION['admin_path'].'info/index' . '?cid=' . $cid);
        }
        /*$this->load->model('typeunit_model');
        $this->load->model('modelv_model');
        $this->load->model('info_model');*/
        $typeunit_model = new Typeunit_model();
        $modelv_model = new Modelv_model();
        $info_model =  new Info_model();

        $cid = $this->request->getVar('cid');
        $result = $typeunit_model->getChildList(0);
        $url = $result[$cid]['url'];

        $qstr = $this->request->getVar('ids');
        $arcids = preg_replace("#[^0-9,]#", '', preg_replace("#`#", ',', $qstr));
        $sql = "select * from archives
		           		 WHERE id in($arcids) ";
        $aidslist = $this->db->query($sql)->getResultArray();
        if (!empty($aidslist)) {
          foreach ($aidslist as $k => $v) {
            // 删除搜索
            $info_model->search_api($v['id'], $v, 'delete');
            // 修改栏目id和url
            $sql = "update archives
		    					set unitid = '$cid',url = '$url' 
		            			WHERE id = " . $v['id'];
            $this->db->query($sql);
            $sql = "select * from archives
		            			WHERE id = " . $v['id'];
            // 创建搜索
            $info = $this->db->query($sql)->getRowArray();
            $info_model->search_api($info['id'], $info);
          }
        }
        ShowMsg("操作成功！", $_SESSION['admin_path'].'info/index' . '?cid=' . $cid);
      }
      $data = array();
      $aid = $this->request->getVar('aid');
      $qstr = $this->request->getVar('qstr');

      if (!empty($aid) && empty($qstr)) $qstr = $aid;

      if ($qstr == '') {
        ShowMsg("参数无效！", $_SESSION['admin_path'].'info/index' . '?cid=' . $cid);
      }

      $typeunit_model = new Typeunit_model();
      $data['result'] = $typeunit_model->getChildTree(0);

      //dump($data);exit;
      echo view($_SESSION['tm'].'admin/info/moveArchivesview.htm', $data);

    } elseif ($dopost == "del") {

      $aid = $this->request->getVar('aid');
      $qstr = $this->request->getVar('qstr');

      if (!empty($aid) && empty($qstr)) $qstr = $aid;
      if ($qstr == '') {
        ShowMsg("参数无效！", $_SESSION['admin_path'].'info/recycling');

      }

      $arcids = preg_replace("#[^0-9,]#", '', preg_replace("#`#", ',', $qstr));
      // 删除去除搜索
      $sql = "SELECT id,unitid,title,description,senddate FROM archives
		            WHERE id in($arcids) ";
      $aids = $this->db->query($sql)->getResultArray();
      if (!empty($aids)) {
        foreach ($aids as $k => $v) {
          $info_model->search_api($v['id'], $v, 'delete');
        }
      }
      $sql = "update archives
		    		set status = '3' 
		            WHERE id in($arcids) ";
      $this->db->query($sql);

      ShowMsg("成功删除指定的文档！", $_SESSION['admin_path'].'info/recycling');
    }
  }

  /**
   *  附件上传接口
   *  上传格式：Word/Excel/PDF/PPT/JPG/JPEG/PNG/GIF/Flash/RAR/ZIP上传限制：单个文件不超过100M
   *  包含报价单上传 是Excel 采用字段区分
   *  上传接口
   */
  function archives_do()
  {
    $type = $this->request->getVar('type') ? $this->request->getVar('type') : 'divpicview';
    if ($type == 'divpicview2') {
      $picname = 'picname2';
      $filename = 'litpic2';
    } elseif ($type == 'divpicsview') {
      $picname = 'pics';
      $filename = 'litpics';
    } else {
      $picname = 'picname';
      $filename = 'litpic';
    }
    if ($_FILES[$filename]['name']) {


      $config['upload_path'] = './uploadfiles/' . date('Ymd', time());
      if (!is_dir($config['upload_path'])) {
        $this->load->helper('dedefile');
        MkdirAll($config['upload_path']);
      }
      //$config['allowed_types'] = 'gif|jpg|png|pdf|tar|zip|rar|ppt|pptx|doc|docx|xls|xlsx|swf';
      $config['allowed_types'] = 'gif|jpg|png|pdf|ppt|pptx|doc|docx|xls|xlsx';
      $config['max_size'] = 204800;
      $config['file_name'] = time() . rand(1000, 9999);

      $this->load->library('upload', $config);

      if (!$this->upload->do_upload($filename)) {
        $data['up']['result'] = false;
        $a = $data['up']['result'] = $this->upload->display_errors();

        $msg = "<script language='javascript'>
               			 	parent.document.getElementById('uploadwait').style.display = 'none';
                			alert('你没指定要上传的文件或文件大小超过限制！');
            			 </script>";
        //go_404('请认真检查上传附件格式与大小','','1054');
      } else {
        $data_tem = $this->upload->data();

        $data['filename'] = '/uploadfiles/' . date('Ymd', time()) . '/' . $data_tem['file_name']; // 已上传的文件名（包括扩展名）
        $data['filetype'] = $data_tem['file_type'];
        $data['filepath'] = $data_tem['file_path'];
        $data['fullpath'] = $data_tem['full_path'];
        $data['origname'] = $data_tem['orig_name'];
        $data['fileext'] = $data_tem['file_ext'];
        $data['filesize'] = $data_tem['file_size'];
        $data['description'] = $data_tem['client_name'];
        $data['uid'] = $this->_uid;
        $data['addtime'] = time();

        $this->db->insert('attachment', $data);
        $aid = $this->db->insert_id();

        $this->load->helper('number');

        $returndata['aid'] = '' . $aid . '';
        $returndata['filename'] = base_url() . $data['filename'];
        $returndata['fileext'] = $data['fileext'];
        $returndata['name'] = $data['description'];
        $returndata['filesize'] = byte_format($data['filesize'] * 1024);

        header("Content-Type:text/html;charset=utf-8");

        if ($type == 'divpicsview') {
          $msg = "<script language='javascript'>
	                    parent.document.getElementById('uploadwait').style.display = 'none';
	                    var picsval =  parent.document.getElementById('" . $picname . "').value;
	                    var str1=picsval.substr((picsval.length-1),1); 
						if(str1 != ','){
							parent.document.getElementById('" . $picname . "').value = picsval+','+'{$returndata['aid']}';
						}else{
							parent.document.getElementById('" . $picname . "').value = picsval+'{$returndata['aid']}';
						}
	                   
	                    if(parent.document.getElementById('" . $type . "'))
	                    {
	                        //parent.document.getElementById('" . $type . "').style.width = '150px';
	                        var addhtml = parent.document.getElementById('" . $type . "').innerHTML;
	                        parent.document.getElementById('" . $type . "').innerHTML = addhtml+\"<li id='picslist_$returndata[aid]'><img src='{$returndata['filename']}?n' width='150' /><br /><a href='javascript:;' onclick='picslisthide({$returndata['aid']})'>删除</a></li>\";
	                    }
                		</script>";
        } else {
          //echo json_encode(array('code'=>1000,'data'=>$returndata,'msg'=>'上传成功'));
          $msg = "<script language='javascript'>
	                    parent.document.getElementById('uploadwait').style.display = 'none';
	                    parent.document.getElementById('" . $picname . "').value = '{$returndata['aid']}';
	                    if(parent.document.getElementById('" . $type . "'))
	                    {
	                        parent.document.getElementById('" . $type . "').style.width = '150px';
	                        parent.document.getElementById('" . $type . "').innerHTML = \"<img src='{$returndata['filename']}?n' width='150' />\";
	                        parent.document.getElementById('icon').value = '{$returndata['filename']}';
	                    }
                		</script>";
        }
        //go_200($returndata);
      }

    } else {
      $msg = "<script language='javascript'>
                parent.document.getElementById('uploadwait').style.display = 'none';
                alert('非法操作！');
            	</script>";
      //go_404('非法操作','','1051');
    }
    echo $msg;
    exit();
  }

  function  top()
  {
    if ($_GET['val'] == "999") {
      $val = "0";
    } else {
      $val = time();
    }
    $sql = "update archives set sortrank =" . $_GET['val'] . ",topdate=" . $val . " where id = " . $_GET['id'] . "";
    $id = $this->db->query($sql);
    if ($id) {
      echo "11";
    }
  }
}

// end info.php