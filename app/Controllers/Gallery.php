<?php
namespace App\Controllers;
use App\Models\{Semester, Subject, Classes, Teacher, Article};

/**
 *
 * @author Rufeng Liu
 *
 */
class Gallery extends BaseController
{

  function __construct()
  {
    session()->set(array('current_tab' => 'gallery'));
  }

  public function index()
  {
    $data = array();
    echo view($_SESSION['tm'].'common/gallery/index.php', $data);
  }
}
