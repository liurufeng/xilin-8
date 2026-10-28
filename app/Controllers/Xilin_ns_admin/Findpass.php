<?php namespace App\Controllers\Xilin_ns_admin;

use App\Libraries\Passwords;

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
      $like = '%' . $this->db->escapeLikeString($search) . '%';
      $sql = "SELECT * FROM parents
      WHERE email like ? ESCAPE '!'
      OR primary_en_name like ? ESCAPE '!'
      OR primary_cn_name like ? ESCAPE '!'
      OR primary_phone like ? ESCAPE '!'
      OR alter_en_name like ? ESCAPE '!'
      OR alter_cn_name like ? ESCAPE '!'
      OR alter_phone like ? ESCAPE '!'
      OR alter_contact_email like ? ESCAPE '!'
      OR address like ? ESCAPE '!'
      OR parent_id like ? ESCAPE '!' ";

      $data['matches'] = $this->db->query($sql, array_fill(0, 10, $like))->getResultArray();
      if(count($data['matches']) == 0) {
        ShowMsg('No parents found!', $_SESSION['admin_path'].'findpass/index');
      }
      echo view($_SESSION['tm'].'admin/findpass/index.php', $data);
    } else {
      ShowMsg('Nothing to search!', $_SESSION['admin_path'].'findpass/index');
    }


  }

  /**
   * Sets a parent's password (passwords are stored as hashes, so they can't be looked up any more).
   */
  function setpass()
  {
    $parent_id = (int)$this->request->getPost('parent_id');
    $pass = (string)$this->request->getPost('passwd');
    if ($parent_id <= 0) {
      ShowMsg('Please choose a parent.', '-1');
    }
    if (($problem = Passwords::problem($pass)) !== null) {
      ShowMsg($problem, '-1');
    }

    $this->db->table('parents')
      ->where('parent_id', $parent_id)
      ->update(array('passwd' => Passwords::forStorage('parents', 'passwd', $pass, Passwords::parentLegacy($pass))));
    ShowMsg('The new password of parent #' . $parent_id . ' is saved.', $_SESSION['admin_path'].'findpass/index');
  }

}

