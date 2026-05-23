<?php
namespace App\Models\Admin;
use CodeIgniter\Model;
// 全站搜索
class Search_model extends Model
{

  /**
   * 添加到全站搜索、修改已有内容
   * @param $typeid
   * @param $id
   * @param $data
   * @param $text 不分词的文本
   * @param $adddate 添加时间
   * @param $iscreateindex 是否是后台更新全文索引
   */
  public function update_search($typeid, $type, $id = 0, $data = '', $text = '', $adddate = 0, $iscreateindex = 0)
  {

    //$this->load->library('segment', 'segment');
    $segment = new \App\Libraries\Segment();
    //分词结果
    if ($type >= 127) {
      return true;
    }
    $fulltext_data = $segment->get_keyword($segment->split_result($data));

    $fulltext_data = $text . ' ' . $fulltext_data;

    if (!$iscreateindex) {

      //$this->db->select('searchid');
      //$r = $this->db->get_where('search', array('typeid' => $typeid, 'id' => $id));
      $sql = "select searchid 
            from search 
            where typeid = ?
            and id = ?";
      $r = $this->db->query($sql, [
        $typeid, $id
      ])->getRowArray();

    }
    if (is_array($r) && count($r) > 0) {
      $searchid = $r['searchid'];
      $this->db->table('search')
        ->where(array('typeid' => $typeid, 'id' => $id))
        ->update(array('data' => $fulltext_data, 'adddate' => $adddate, 'type' => $type));
      //$this->db->update('search', array('data' => $fulltext_data, 'adddate' => $adddate, 'type' => $type), array('typeid' => $typeid, 'id' => $id));
    } else {
      //$this->db->insert('search', array('typeid' => $typeid, 'id' => $id, 'adddate' => $adddate, 'data' => $fulltext_data, 'type' => $type));
      $this->db->table('search')
        ->insert(array('typeid' => $typeid, 'id' => $id, 'adddate' => $adddate, 'data' => $fulltext_data, 'type' => $type));
      $searchid = $this->db->insertID();
    }
    return $searchid;
  }

  /**
   * 删除全站搜索内容
   */
  public function delete_search($typeid, $id)
  {

    //$this->db->delete('search', array('typeid' => $typeid, 'id' => $id));
    $this->db->table('search')
      ->delete(array('typeid' => $typeid, 'id' => $id));
  }

  /**
   * 搜索类型
   */
  public function getSearchType()
  {
    $types = array(
      '1' => '新闻',
      '2' => '图片',
      '3' => '单页',
      '4' => '其他',
      '127' => '非搜索',
    );
    return $types;
  }
}