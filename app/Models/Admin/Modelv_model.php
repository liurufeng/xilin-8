<?php
namespace App\Models\Admin;
use CodeIgniter\Model;

// 数据模型
class Modelv_model extends Model
{

  protected $lists;
  protected $cachename = 'modelv'; // 缓存名称

  public function __construct()
  {

    parent::__construct();
    $this->cachename = md5($this->cachename);

  }

  // 写入表字段缓存
  public function writec()
  {

    $sql = "SELECT * FROM " . $this->db->dbprefix('ttable') . " ORDER BY ismust ASC,tid ASC";
    $info = $this->db->query($sql)->result_array('array');
    foreach ($info as $k => $v) {

      $list[$v['tid']] = $v;
    }
    $this->load->driver('cache');
    $this->cache->file->save($this->cachename . '_list', $list, 100000);
    return $this->cache->file->get($this->cachename . '_list');

  }

  // 获取表字段缓存
  public function getc()
  {

    $this->load->driver('cache');
    $info = $this->cache->file->get($this->cachename . '_list');
    if (empty($info)) {
      self::writec();
    }
    return $this->cache->file->get($this->cachename . '_list');
  }

  /**
   * 根据模型id获取当前模型的数据
   */
  public function getModelvInofbyId($id)
  {

    if (empty($id)) {
      return false;
    }

    $sql = "SELECT * FROM channeltype WHERE id = $id";
    $info = $this->db->query($sql)->getRowArray();
    $thisidinfo = unserialize($info['data']);

    $sql = "SELECT * FROM ttable ORDER BY ismust ASC,tid ASC";
    $info = $this->db->query($sql)->getResultArray();
    foreach ($info as $k => $v) {
      $list[$v['tid']] = $v;
    }
    $tablelists = $list;

    foreach ($tablelists as $k => $v) {

      if (isset($thisidinfo[$k]['t_name']) && $thisidinfo[$k]['t_name']) {
        $tablelists[$k]['t_name'] = $thisidinfo[$k]['t_name'];
      } else {
        $tablelists[$k]['t_name'] = '';
      }
      if (isset($thisidinfo[$k]['egroupid']) && $thisidinfo[$k]['egroupid']) {
        $tablelists[$k]['egroupid'] = $thisidinfo[$k]['egroupid'];
      } else {
        $tablelists[$k]['egroupid'] = '';
      }

      if (isset($thisidinfo[$k]['tid']) && $thisidinfo[$k]['tid']) {
        $tablelists[$k]['checked'] = '1';
      } else {
        $tablelists[$k]['checked'] = '0';
      }
    }
    $data['tablelists'] = $tablelists;
    $data['info'] = $info;
    return $data;
  }

  /**
   * 获取所有的模型
   */
  function getModelv()
  {

    $sql = "SELECT * FROM " . $this->db->dbprefix('channeltype') . " WHERE 1 ";
    $info = $this->db->query($sql)->result_array();
    return $info;
  }
}