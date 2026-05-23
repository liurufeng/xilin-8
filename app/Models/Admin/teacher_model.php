<?php namespace App\Models\Admin;
use CodeIgniter\Model;

class Teacher_model extends Model
{

  function __construct()
  {
    parent::__construct();
  }

  function getGroupList()
  {
    $sql = "SELECT id,ename FROM
    		  " . $this->db->dbprefix('sys_enum') . "
    		  WHERE egroup = 'teachertype' ";
    $groupList = $this->db->query($sql)->getResultArray();
    if ($groupList) {
      return $groupList;
    } else {
      return FALSE;
    }
  }
}
