<?php
namespace App\Controllers;
use App\Models\{Semester, Subject, Classes, Teacher, Article};

/**
 *
 * @author Rufeng Liu
 *
 */
class Signin extends BaseController
{

  function __construct()
  {
    session()->set(array('current_tab' => 'signin'));
  }

  public function index()
  {
    $uri = service('uri');
    if($uri->getTotalSegments() > 2 ) {
      if(null !== $uri->getSegment(3) && $uri->getSegment(3)=='pod') {
        session()->set('pod', true);
        session()->set(array('current_tab' => 'pod'));
      } else if ( null !== $uri->getSegment(3) && $uri->getSegment(3)=='teacher') {
        session()->set(array('current_tab' => 'teacher'));
        echo view($_SESSION['tm'].'/signin/tindex.php');
        return;
      }
    }

    echo view($_SESSION['tm'].'/signin/index.php');
  }
}
