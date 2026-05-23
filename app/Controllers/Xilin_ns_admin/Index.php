<?php
namespace App\Controllers\Xilin_ns_admin;
use App\Models\Admin\{Menu_note, Typeunit_model};

/**
 *
 */
class Index extends MY_Controller
{

  function __construct()
  {
    parent::_Mycontroller();
    parent::_check_login();
  }

  function index()
  {
    echo view($_SESSION['tm'].'admin/index/index2.php');
  }

  function index_menu()
  {

    $data['openitem'] = (empty($data['openitem']) ? 1 : $data['openitem']);
    $menu_note = new Menu_note();
    $data['menus'] = $menu_note->getAccessMenu(1);
    if(!$data['menus']) $data['menus'] = [];
    //var_dump($data['menus']);
    /*$result = $menu_note->getChildListAll(0);
    foreach ($result as $k => $v) {
      $toplist[$v['reid']][] = $v;
    }
    $data['toplist'] = $toplist;*/

    $data['toplist'] = null;
    //$this->load->model('typeunit_model', '', TRUE);
    $this->typeunit_model = new Typeunit_model();
    $data['result'] = $this->typeunit_model->getChildTree(0);

    //var_dump($data['result'][88]);exit;
    echo view($_SESSION['tm'].'admin/index/index_menu2.php', $data);
  }

  function index_body()
  {

    echo view($_SESSION['tm'].'admin/index/index_body.php');
  }

  /**
   *
   * 发布向导
   */
  function catalogmenu()
  {
    exit();
    echo view($_SESSION['tm'].'admin/index/catalog_menu2.php');
  }
}

/* End of file index.php */
