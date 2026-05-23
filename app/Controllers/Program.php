<?php
namespace App\Controllers;
use App\Models\{Semester, Subject, Classes, Teacher, Article};

/**
 *
 * @author Rufeng Liu
 *
 */
class Program extends BaseController
{

  public $sponsor = NULL;

  function __construct()
  {
    parent::__construct();



    $sql = "select s.*, sl.name as level_name
				from sponsor s
				join sponsor_level sl on s.level_id = sl.level_id
				where s.status = 1 and active > 0
				order by sl.show_order asc,s.show_order asc";
    $this->sponsor = $this->db->query($sql)->getResultArray();

    session()->set(array('current_tab' => 'program'));
  }

  public function index()
  {
    $userinfo = session()->get('userresult') ? session()->get('userresult') : null;
    $sql = "select id,url
				from arctype where url <> ''";
    $urllist = $this->db->query($sql)->getResultArray();
    $nav = array();
    foreach ($urllist as $item) {
      $nav[$item['id']] = $item['url'];
    }
    //新闻
    $sql = "select la.unitid,lac.body,la.title,la.description,la.id, FROM_UNIXTIME(adddate,'%M %D, %Y %H:%i:%s') as adddate from " . $this->db->dbprefix('archives') . " la
				join  " . $this->db->dbprefix('addonarticle') . " lac on la.id = lac.aid
				where la.unitid = 70 and la.status = 1
				ORDER BY sortrank ASC,topdate DESC,adddate DESC   
				limit 3 ";
    $data['news'] = $this->db->query($sql)->getResultArray();

    //announcements
    $sql = "select la.unitid,lac.body,la.title,la.description,la.id, FROM_UNIXTIME(adddate,'%M %D, %Y %H:%i:%s') as adddate from " . $this->db->dbprefix('archives') . " la
				join  " . $this->db->dbprefix('addonarticle') . " lac on la.id = lac.aid
				where la.unitid = 69 and  la.status = 1
				ORDER BY sortrank ASC,topdate DESC,adddate DESC
				limit 3 ";
    $data['announcements'] = $this->db->query($sql)->getResultArray();

    $data['list'] = $nav;

    $this->load->model('semester');
    $semester = new Semester();
    $data['semester'] = $semester->getSemesters();
    $semester->getCurrentSemester();

    $sql = "select s.*
				from sponsor s 
				join sponsor_level sl on s.level_id = sl.level_id 
				where s.status = 1 and active > 0
				order by sl.show_order asc,s.show_order asc";
    $data['sponsor'] = $this->db->query($sql)->getResultArray();

    $sql = "select * from newsletters where status = 1 and isshow order by seq desc";
    $data['newsletters'] = $this->db->query($sql)->getResultArray();

    $this->load->model('subject');
    $subjects = new Subject();
    $data['subjects'] = $subjects->getSubjects();

    $this->load->model('classes');
    $classes = new Classes();
    $data['classes'] = $classes->getClasses();

    $this->load->model('teacher');
    $teachers = new Teacher();
    $data['teachers'] = $teachers->getTeachers();
    $data['bods'] = $teachers->getAdmins(17);
    $data['stuffs'] = $teachers->getAdmins(18);

    $this->load->model('article');
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
    $data['calendars'] = $this->db->query($sql)->getResultArray();


    if (!empty($userinfo)) {

      $type = $userinfo['usertype'];
      if ($type == 1) {
        $id = $userinfo[0]['parent_id'];
        $sql = "select * from parents where parent_id = $id";
      } else {
        $id = $userinfo[0]['teacher_id'];
        $sql = "select * from teachers where teacher_id = $id";
      }
      $userinfodata = $this->db->query($sql)->getResultArray();
      $data['userinfodata'] = $userinfodata;
      $data['usertype'] = $type;
    }

    echo view($_SESSION['tm'].'index/index.php', $data);
  }

  public function details()
  {
    $program_id = $this->uri->segment(3);
    $this->load->model('article');
    $article = new Article();
    $data['program'] = $article->getArticle($program_id);
    $data['sponsor'] = $this->sponsor;

    echo view($_SESSION['tm'].'program/index.php', $data);
  }

}
