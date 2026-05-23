<?php

class Site_base extends Model
{
  function Model_name()
  {
    parent::Model();
  }

  //系统基础信息
  function meta()
  {
    $this->db->select('*')->where(array('id' => 1));
    return $value = $this->db->get('cd_system_info')->getRowArray();
  }


}

/* End of file admin_login.php */
