<?php namespace App\Models\Admin;
use CodeIgniter\Model;
use App\Models\Admin\{Search_model, Typeunit_model};

// 信息模型
class Info_model extends Model
{
  function __construct()
  {
    parent::__construct();
  }

  public function getarcatts()
  {

    $sql = "Select * From arcatt order by sortid asc";
    $flaglist = $this->db->query($sql)->getResultArray();

    return $flaglist;
  }

  function IsCommendArchives($iscommend)
  {
    $arcatts = $this->getarcatts();

    $sn = '';
    foreach ($arcatts as $k => $v) {
      $sn .= (preg_match("#" . $v['att'] . "#", $iscommend) ? ' ' . $v['attname'] : '');
    }
    $sn = trim($sn);
    if ($sn == '') return '';
    else return "[<font color='red'>$sn</font>]";
  }

  /**
   * 获取信息的所有信息
   */
  function getInfo($aid)
  {

    if (empty($aid)) return;

    $sql = "SELECT * FROM archives as a
			  LEFT JOIN addonarticle as aa
			  ON a.id = aa.aid 
			  WHERE a.id = '{$aid}'
			  LIMIT 1";

    $info = $this->db
      ->query($sql)
      ->getRowArray();
    return $info;

  }

  /**
   * 根据图片id获取图片信息
   */
  function getAttinfo($aid)
  {

    if (empty($aid)) return;

    $sql = "SELECT * FROM " . $this->db->dbprefix('attachment') . "
			  WHERE aid = $aid 
			  LIMIT 1";
    $info = $this->db->query($sql)->getRowArray();
    return $info;
  }

  /**
   * 搜索
   */
  public function search_api($id = 0, $data = array(), $action = 'update')
  {

    //$this->load->model('search_model', 'search_model');
    $search_model = new Search_model();
    $typeid = $data['unitid'];

    //$this->load->model('typeunit_model', '', TRUE);
    $typeunit_model = new Typeunit_model();
    $result = $typeunit_model->getChildList(0);
    $type = $result[$typeid]['searchtype'];

    if ($action == 'update') {

      $fulltextcontent = $data['title'];
      $search_model->update_search($typeid, $type, $id, $fulltextcontent, addslashes($data['title']) . ' ' . addslashes($data['description']), $data['senddate']);

    } elseif ($action == 'delete') {

      $search_model->delete_search($typeid, $id);
    }
  }

  /**
   * 更新首页数据
   */
  function updateIndexData()
  {

    $this->cachename = md5('IndexsData');
    // 轮播图数据
    $unitid = '11';
    $sql = " SELECT * FROM " . $this->db->dbprefix('archives') . "
				WHERE status = '1' AND unitid = '" . $unitid . "'
				ORDER BY sortrank asc,senddate asc
				LIMIT 4";
    $lists = $this->db->query($sql)->getResultArray();
    foreach ($lists as $k => $v) {
      $v['picid'] = $this->getAttinfo($v['picid']);
      $v['picid2'] = $this->getAttinfo($v['picid2']);
      $data['index_pic'][$v['id']] = $v;
    }
    // 首页置顶6个活动
    $unitid = '(4,6,7,9,19,20)';
    $sql = " SELECT * FROM " . $this->db->dbprefix('archives') . "
				WHERE status = '1' AND unitid in " . $unitid . " AND  FIND_IN_SET('c',flag)
				ORDER BY sortrank asc,senddate asc
				LIMIT 6";

    $lists = $this->db->query($sql)->getResultArray();
    foreach ($lists as $k => $v) {
      $v['picid'] = $this->getAttinfo($v['picid']);
      $v['picid2'] = $this->getAttinfo($v['picid2']);
      $data['index_events'][$v['id']] = $v;
    }
    // 首页置顶4条新闻
    $unitid = '2';
    $sql = " SELECT * FROM " . $this->db->dbprefix('archives') . "
				WHERE status = '1'  AND unitid = '" . $unitid . "'  AND  FIND_IN_SET('c',flag)
				ORDER BY sortrank asc,senddate asc
				LIMIT 4";
    $lists = $this->db->query($sql)->getResultArray();
    foreach ($lists as $k => $v) {
      $v['picid'] = $this->getAttinfo($v['picid']);
      $v['picid2'] = $this->getAttinfo($v['picid2']);
      $data['index_news'][$v['id']] = $v;
    }

    $this->load->driver('cache');
    $this->cache->file->save($this->cachename, $data, 5000);
    return true;
  }
}