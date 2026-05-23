<?php namespace App\Models\Admin;
use CodeIgniter\Model;

//角色模型
class Admin_role extends Model
{

  //得到角色多选框
  public function getRoleCheckbox($p = array(), $name = '')
  {
    if (!is_array($p)) $p = explode(',', trim($p, ','));
    $name = $name ? $name : 'roleids';

//		//全部角色
//		$this->db = $this->load->database('source', TRUE);
//		$result = $this->order("sort asc")->findAll();
//		$tmp = '<div class="role"><ul>';
//		foreach ($result as $v){
//			if (in_array($v['id'],$p)) {
//				$check = ' checked="checked"';
//			}else{
//				$check = '';
//			}
//			$tmp .= '<li><input type="checkbox" name="'.$name.'[]" value="'.$v['id'].'" '.$check.' />'.$v['name'].'</li>';
//		}
//		$tmp .= '</ul></div>';
    return $tmp;
  }

}