<?php namespace App\Models\Admin;
use CodeIgniter\Model;
use App\Libraries\Tree;

// 菜单模型
class Typeunit_model extends Model
{

  protected $tree; //已审核子树类
  protected $tree_all; //全部子树类
  protected $all_node; //所有分类
  protected $cachename = 'type_unit';
  protected $able_name = 'arctype'; // 缓存名称

  protected $type = array('_self' => '当前窗口', '_blank' => '新窗口', 'top' => '上窗口', 'left' => '左窗口', 'main' => '右窗口');

  public function __construct()
  {

    parent::__construct();
    $this->cachename = md5($this->cachename);

  }

  // 得到菜单全部select下拉框数据
  public function getMenuSelect($p = 0, $name = '', $size = '')
  {
    $check = ($p == 0) ? ' selected="selected"' : '';
    if ($size) {
      $size = ' size="' . $size . '"';
    }
    $name = $name ? $name : 'reid';

    // 得到经过排序的全部子列表
    $nodes = $this->getChildList(0);

    $tmp = '<select name="' . $name . '" id="' . $name . '"' . $size . ' style="display:none;">';
    $tmp .= '<option value="0"' . $check . '>顶级菜单</option>';
    foreach ($nodes as $val) {
      if ($val['id'] == $p) {
        $check = ' selected="selected"';
      } else {
        $check = '';
      }
      $class = ($val['level'] == 2) ? ' style="background:#EDF9D5"' : '';
      if ($val['level'] > 1) {
        $level = str_repeat('　', $val['level'] - 1);
        $level .= '|—';
      } else {
        $val['name'] = "【" . $val['typename'] . "】";
        $level = '';
      }

      if ($val['ispart'] == '1') {
        $disabled = ' disabled="disabled" ';
      } else {
        $disabled = ' ';
      }
      $tmp .= '<option value="' . $val['id'] . '"' . $check . $class . $disabled . ' >' . $level . $val['typename'] . '</option>';
    }
    $tmp .= '</select>';

    return $tmp;
  }

  //得到弹出窗口选项的select下拉框
  public function getTargetSelect($p)
  {
    $tmp = '<select name="target" id="target">';
    foreach ($this->type as $k => $v) {
      $check = ($k == $p) ? ' selected="selected"' : '';
      $tmp .= '<option value="' . $k . '"' . $check . '>' . $v . '</option>';
    }
    $tmp .= '</select>';
    return $tmp;
  }

  //窗口类型转换成名称
  public function target2name($p)
  {
    return $this->type[$p];
  }

  // 节点id转换成名称
  public function id2name($id)
  {
    $result = $this->getAllNode();
    foreach ($result as $k => $v) {
      $lastresult[$v['id']] = $v;
    }

    if (isset($lastresult[$id]['typename'])) {
      return $lastresult[$id]['typename'];
    } else {
      return '';
    }
  }

  // 得到菜单全部分类Checkbox勾选框数据 $type菜单类型 1管理员后台 2会员后台
  public function getMenuCheckbox($p = array(), $name = '', $type = 1)
  {
    if (!is_array($p)) $p = explode(',', trim($p, ','));
    $name = $name ? $name : 'menuids';
    $typeid = $this->_type2id($type);

    // 全部菜单
    $nodes = $this->getChildTree($typeid);
    if (!$nodes) {
      return '';
    }
    $tmp = '';
    foreach ($nodes as $app) {
      $tmp .= '<div class="purview">';
      if (in_array($app['id'], $p)) {
        $check = ' checked="checked"';
      }
      $tmp .= '<div class="tit"><input type="checkbox" name="' . $name . '[]" value="' . $app['id'] . '" ' . $check . ' />' . $app['name'] . '</div>';
      unset($check);
      if ($app['child_count'] > 0) {
        foreach ($app['child'] as $module) {
          if (in_array($module['id'], $p)) {
            $check = ' checked="checked"';
          }
          $tmp .= '<div class="tit1"><input type="checkbox" name="' . $name . '[]" value="' . $module['id'] . '" ' . $check . ' />' . $module['name'] . '</div>';
          $tmp .= '<ul>';
          unset($check);
          if ($module['child_count'] > 0) {
            foreach ($module['child'] as $action) {
              if (in_array($action['id'], $p)) {
                $check = ' checked="checked"';
              }
              $tmp .= '<li><input type="checkbox" name="' . $name . '[]" value="' . $action['id'] . '" ' . $check . ' />' . $action['name'] . '</li>';
              unset($check);
            }
          }
          $tmp .= '</ul>';
        }
      }
      $tmp .= '</div>';
    }
    return $tmp;
  }

  //删除菜单
  public function deleteMenu($id)
  {
    //此节点下的所有子节点
    $sun = $this->treeAll()->getChildList($id);
    $in = '';
    if ($sun) {
      foreach ($sun as $v) {
        $in .= $v['id'] . ',';
      }
    }
    $in = $in . $id;
    $result = $this->where("id in($in)")->delete();
    $this->delCache(); //清除缓存
    return $result;
  }

  // 得到所有未排序的节点
  public function getAllNode()
  {

    if (!$this->all_node) {
      $tree = $this->tree();

      $this->all_node = $tree->getAllNode();
      return $this->all_node;
    }
    return $this->all_node;
  }

  // 得到某个节点
  public function getNode($id)
  {
    return $this->tree()->getNode($id);
  }

  // 得到父列表
  public function getParentList($id)
  {
    return $this->tree()->getParentList($id);
  }

  // 得到子列表
  public function getChildList($id)
  {

    return $this->tree()->getChildList($id);
  }

  // 得到子树
  public function getChildTree($id)
  {
    return $this->tree()->getChildTree($id);
  }

  /*
   *
   * Enter description here ...
   */
  protected function tree()
  {
    if ($this->tree) {
      return $this->tree;
    } else {
      //include_once APPPATH . '/libraries/tree.php';
      $sql = "SELECT * FROM arctype WHERE 1 ORDER BY sortrank ASC,id ASC";
      $info = $this->db->query($sql)->getResultArray();
      $this->tree = new Tree($info, 'id', 'reid');
      return $this->tree;
    }
  }

  // 写入缓存
  public function writetreec()
  {

    $sql = "SELECT * FROM " . $this->db->dbprefix($this->able_name) . " WHERE 1 ORDER BY sortrank ASC,id ASC";
    $info = $this->db->query($sql)->getResultArray();
    $this->load->driver('cache');
    $this->cache->file->save($this->cachename . '_tree', $info, 100000);
    return $this->cache->file->get($this->cachename . '_tree');
  }

  public function gettreec()
  {
    $sql = "SELECT * FROM " . $this->db->dbprefix($this->able_name) . " WHERE 1 ORDER BY sortrank ASC,id ASC";
    $info = $this->db->query($sql)->getResultArray();
    return $info;
    /*$this->load->driver('cache');
    $info = $this->cache->file->get($this->cachename . '_tree');
    if (empty($info)) {
      self::writetreec();
    }
    return $this->cache->file->get($this->cachename . '_tree');*/
  }

  // 得到全部节点包括带审核的
  public function treeAll()
  {
    if ($this->tree_all) {
      return $this->tree_all;
    } else {

      include_once APPPATH . '/libraries/tree.php';
      $info = self::getc();
      $this->tree_all = new Tree($info, 'id', 'reid');
      return $this->tree_all;
    }
  }

  // 写入缓存
  public function writec()
  {

    $sql = "SELECT * FROM " . $this->db->dbprefix($this->able_name) . " ORDER BY sortrank ASC,id ASC";
    $info = $this->db->query($sql)->getResultArray();

    $this->load->driver('cache');
    $this->cache->file->save($this->cachename . '_tree_all', $info, 100000);
    return $this->cache->file->get($this->cachename . '_tree_all');

  }

  public function getc()
  {

    $this->load->driver('cache');
    $info = $this->cache->file->get($this->cachename . '_tree_all');
    if (empty($info)) {
      self::writec();
    }
    return $this->cache->file->get($this->cachename . '_tree_all');
  }

  public function writecache()
  {
    self::writetreec();
    self::writec();
  }

}