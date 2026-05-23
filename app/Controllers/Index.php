<?php
namespace App\Controllers;
use App\Models\{Semester, Subject, Classes, Teacher, Article};

/**
 *
 * @author Rufeng Liu
 *
 */
class Index extends BaseController
{

  function __construct()
  {
    session()->set(array('current_tab' => 'home'));
  }

  public function index()
  {

    $sess = session();
    $db = db_connect();
    /*$current = null;
    $current_id = null;
    $uri = service('uri');
    var_dump($uri);
    if($uri->getTotalSegments() > 1 ) {
      $current = $uri->getSegment(1);
      $current_id = $uri->getSegment(2);
    }
    var_dump($uri->getQuery());
    var_dump($this->request->getVar('cs'));*/

    if($current_id = $this->request->getVar('cs')) {
      $sess->set(array('user_semester_id' => $current_id));
    } elseif ('clear' == $this->request->getVar('cs')) {
      $sess->unset('user_semester_id');
    }

    /*if($current && $current == 'cs' && $current_id && is_numeric($current_id) ){
      $sess->set(array('user_semester_id' => $current_id));
    } elseif ($current && $current == 'clear') {
      $sess->unset('user_semester_id');
    }*/

    $userinfo = $sess->get('userresult') ? $sess->get('userresult') : null;

    $sql = "select id,url
				from arctype where url <> ''";
    $urllist = $db->query($sql)->getResultArray();

    $nav = array();
    foreach ($urllist as $item) {
      $nav[$item['id']] = $item['url'];
    }

    //新闻
    $sql = "select la.unitid,lac.body,la.title,la.description,la.id, 
            FROM_UNIXTIME(adddate,'%M %D, %Y %H:%i:%s') as adddate,
            FROM_UNIXTIME(lastupdate,'%M %D, %Y %H:%i:%s') as lastupdate 
            from archives la
            join  addonarticle lac on la.id = lac.aid
            where la.unitid = 70 and la.status = 1
            ORDER BY sortrank ASC, lac.aid DESC
            limit 3 ";
    $data['news'] = $db->query($sql)->getResultArray();

    //announcements
    $sql = "select la.unitid,lac.body,la.title,la.description,la.id, 
        FROM_UNIXTIME(adddate,'%M %D, %Y %H:%i:%s') as adddate,
        FROM_UNIXTIME(lastupdate,'%M %D, %Y %H:%i:%s') as lastupdate 
        from archives la
				join  addonarticle lac on la.id = lac.aid
				where la.unitid = 69 and  la.status = 1
				ORDER BY sortrank ASC, lac.aid DESC
				limit 1 ";
    $data['announcements'] = $db->query($sql)->getResultArray();

    $data['list'] = $nav;

    $semester = new Semester();
    $data['semester'] = $semester->getSemesters();
    $semester->getCurrentSemester();

    $sql = "select s.*, sl.name as level_name
        from sponsor s
        join sponsor_level sl on s.level_id = sl.level_id
        where s.status = 1 and active > 0
        order by sl.show_order asc,s.show_order asc";

    $data['sponsor'] = $db->query($sql)->getResultArray();

    $sql = "select * from newsletters where status = 1 and isshow order by seq desc";
    $data['newsletters'] = $db->query($sql)->getResultArray();

    $subjects = new Subject();
    $data['subjects'] = $subjects->getSubjects();

    $classes = new Classes();
    $data['classes'] = $classes->getClasses();

    $teachers = new Teacher();
    $data['teachers'] = $teachers->getTeachers();
    $data['bods'] = $teachers->getAdmins(17);
    $data['stuffs'] = $teachers->getAdmins(18);

    $article = new Article();
    $data['mission'] = $article->getArticle(48);
    $data['rules'] = $article->getArticle(49);
    $data['scholarship'] = $article->getArticle(53);
    $data['forms'] = $article->getArticle(54);
    $data['notice'] = $article->getArticle(55);

    $data['location'] = $article->getArticle(84);
    $data['links'] = $article->getArticle(85);
    $data['fundraising'] = $article->getArticle(86);
    $data['jobs'] = $article->getArticle(87);

    $sql = "select *
				from calendar t
				join semester se on t.semester_id=se.semester_id
				where t.status = 1 and t.show_flag = 1 and se.show_calendar = 1
				order by t.semester_id desc, t.show_order asc, t.date asc";
    $data['calendars'] = $db->query($sql)->getResultArray();


    if (!empty($userinfo)) {

      $type = $userinfo['usertype'];
      if ($type == 1) {
        $id = $userinfo[0]['parent_id'];
        $sql = "select * from parents where parent_id = $id";
      } else {
        $id = $userinfo[0]['teacher_id'];
        $sql = "select * from teachers where teacher_id = $id";
      }
      $userinfodata = $db->query($sql)->getResultArray();
      $data['userinfodata'] = $userinfodata;
      $data['usertype'] = $type;
    }

    echo view($_SESSION['tm'].'index/index.php', $data);
  }
}
