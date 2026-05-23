<?php namespace App\Controllers\Xilin_ns_admin;

class Findpass extends MY_Controller
{

  function __construct()
  {

    $this->_classname = 'Classes';
    $this->_issystem = TRUE;
    parent::_Mycontroller();
    parent::_check_login();
  }

  /**
   *
   *
   */
  function index()
  {
    echo view($_SESSION['tm'].'admin/findpass/index.php');
  }

  /**
   * Add new class
   */
  function find()
  {
    $search = $this->request->getVar('search');
    if (isset($search) && trim($search) != '') {
      $sql = "SELECT * FROM parents
      WHERE email like '%$search%'
      OR primary_en_name like '%$search%'
      OR primary_cn_name like '%$search%'
      OR primary_phone like '%$search%'
      OR alter_en_name like '%$search%'
      OR alter_cn_name like '%$search%'
      OR alter_phone like '%$search%'
      OR alter_contact_email like '%$search%'
      OR address like '%$search%'
      OR parent_id like '%$search%' ";

      $data['matches'] = $this->db->query($sql)->getResultArray();
      if(count($data['matches']) == 0) {
        ShowMsg('No parents found!', $_SESSION['admin_path'].'findpass/index');
      }
      echo view($_SESSION['tm'].'admin/findpass/index.php', $data);
    } else {
      ShowMsg('Nothing to search!', $_SESSION['admin_path'].'findpass/index');
    }


  }

}

