<?php
namespace App\Controllers;
use App\Models\{Semester, Subject, Classes, Teacher, Article};

/**
 *
 * @author Rufeng Liu
 *
 */
class School extends BaseController
{
  public $sponsor = NULL;
  public $db = null;
  public $sess = null;

  function __construct()
  {
    $this->db = db_connect();
    $this->sess = session();
    if(!$this->sess->get('current_semester') || !$this->sess->get('current_semester')['semester_id']) {
      $semester = new Semester();
      $data['semester'] = $semester->getSemesters();
      $semester->getCurrentSemester();
    }

    session()->set(array('current_tab' => 'school'));
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
    $sql = "select la.unitid,lac.body,la.title,la.description,la.id, FROM_UNIXTIME(adddate,'%M %D, %Y %H:%i:%s') as adddate
        from archives la
				join addonarticle lac on la.id = lac.aid
				where la.unitid = 69 and  la.status = 1
				ORDER BY sortrank ASC,topdate DESC,adddate DESC
				limit 3 ";
    $data['announcements'] = $this->db->query($sql)->getResultArray();

    $data['list'] = $nav;

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

  public function mission()
  {
    session()->set(array('current_tab' => 'mission'));
    $article = new Article();
    $data['mission'] = $article->getArticle(48);

    echo view($_SESSION['tm'].'school/mission.php', $data);
  }

  public function rules()
  {
    session()->set(array('current_tab' => 'rules'));
    $article = new Article();
    $data['rules'] = $article->getArticle(49);

    echo view($_SESSION['tm'].'school/rules.php', $data);
  }

  public function faq()
  {
    session()->set(array('current_tab' => 'faq'));

    echo view($_SESSION['tm'].'school/faq.php');
  }

  public function scholarship()
  {
    session()->set(array('current_tab' => 'scholarship'));
    $article = new Article();
    $data['scholarship'] = $article->getArticle(53);

    echo view($_SESSION['tm'].'school/scholarship.php', $data);
  }

  public function forms()
  {
    session()->set(array('current_tab' => 'forms'));
    $article = new Article();
    $data['forms'] = $article->getArticle(54);

    echo view($_SESSION['tm'].'school/forms.php', $data);
  }

  public function notice()
  {
    session()->set(array('current_tab' => 'notice'));
    $article = new Article();
    $data['notice'] = $article->getArticle(55);

    echo view($_SESSION['tm'].'school/notice.php', $data);
  }

  public function location()
  {
    session()->set(array('current_tab' => 'location'));
    $article = new Article();
    $data['location'] = $article->getArticle(84);

    echo view($_SESSION['tm'].'school/location.php', $data);
  }

  public function links()
  {
    session()->set(array('current_tab' => 'links'));
    $article = new Article();
    $data['links'] = $article->getArticle(85);

    echo view($_SESSION['tm'].'school/links.php', $data);
  }

  public function fundraising()
  {
    session()->set(array('current_tab' => 'fundraising'));
    $article = new Article();
    $data['fundraising'] = $article->getArticle(86);

    echo view($_SESSION['tm'].'school/fundraising.php', $data);
  }

  public function jobs()
  {
    session()->set(array('current_tab' => 'jobs'));
    $article = new Article();
    $data['jobs'] = $article->getArticle(87);

    echo view($_SESSION['tm'].'school/jobs.php', $data);
  }

  public function newsletters()
  {
    session()->set(array('current_tab' => 'newsletters'));
    $sql = "select * from newsletters where status = 1 and isshow order by seq, id desc";
    $data['newsletters'] = $this->db->query($sql)->getResultArray();

    echo view($_SESSION['tm'].'school/newsletters.php', $data);
  }

  public function admins()
  {
    session()->set(array('current_tab' => 'admins'));
    $teachers = new Teacher();
    //$data['teachers'] = $teachers->getTeachers();
    $data['bods'] = $teachers->getAdmins(17);
    $data['stuffs'] = $teachers->getAdmins(18);

    echo view($_SESSION['tm'].'school/admins.php', $data);
  }

  public function intro()
  {
    $teachers = new Teacher();
    $data['bods'] = $teachers->getAdmins(17);
    $data['stuffs'] = $teachers->getAdmins(18);

    echo view($_SESSION['tm'].'school/intro.php', $data);
  }

  public function allannounce()
  {
    //announcements
    $sql = "select la.unitid,lac.body,la.title,la.description,la.id, FROM_UNIXTIME(adddate,'%M %D, %Y %H:%i:%s') as adddate,FROM_UNIXTIME(lastupdate,'%M %D, %Y %H:%i:%s') as lastupdate from
      archives la
				join addonarticle lac on la.id = lac.aid
				where la.unitid = 69 and  la.status = 1
				ORDER BY sortrank ASC,lac.aid DESC
				";
    $data['announcements'] = $this->db->query($sql)->getResultArray();

    echo view($_SESSION['tm'].'school/allannounce.php', $data);
  }
  public function allnews()
  {
    //新闻
    $sql = "select la.unitid,lac.body,la.title,la.description,la.id, FROM_UNIXTIME(adddate,'%M %D, %Y %H:%i:%s') as adddate,FROM_UNIXTIME(lastupdate,'%M %D, %Y %H:%i:%s') as lastupdate from " . $this->db->dbprefix('archives') . " la
				join  " . $this->db->dbprefix('addonarticle') . " lac on la.id = lac.aid
				where la.unitid = 70 and la.status = 1
				ORDER BY sortrank ASC,lac.aid DESC
				";
    $data['news'] = $this->db->query($sql)->getResultArray();

    echo view($_SESSION['tm'].'school/allnews.php', $data);
  }

  public function finaid()
  {
    echo view($_SESSION['tm'].'school/finaid.php');
  }

  public function contactus()
  {
    session()->set(array('current_tab' => 'contactus'));
    $article = new Article();
    $data['data'] = $article->getArticle(117);

    echo view($_SESSION['tm'].'school/general.php', $data);
  }

  public function contact()
  {
    return;
    $email = trim($this->request->getVar('email'));
    $name = trim($this->request->getVar('name'));
    $msg_in = trim($this->request->getVar('msg'));
    $this->load->library('form_validation');
    $this->form_validation->set_rules(array(
      array(
        'field' => 'name',
        'label' => 'Name',
        'rules' => 'required',
      ),

      array(
        'field' => 'email',
        'label' => 'Email',
        'rules' => 'required|valid_email',
      ),
       array(
         'field' => 'msg',
         'label' => 'Message',
         'rules' => 'required',
       ),
    ));
    session()->set_flashdata('name', $name);
    session()->set_flashdata('email', $email);
    session()->set_flashdata('msg', $msg_in);

    if (!$this->form_validation->run()) {
      session()->set_flashdata('error', 'All fields are required and need to be in correct format!');
    } else {
      //send email to school
      session()->set_flashdata('error', 'Message sent successfully!');
      $mid = date("Ymdhi");

      $msg = "<html><body>";
      $msg .= "<p>School EC,<br></p>";
      $msg .= "<p>Here is a Contact message from school website";
      $msg .= "<p><b>Message ID:</b> {$mid}<br>";
      $msg .= "<p><b>Sender's Name:</b> {$name}<br>";
      $msg .= "<p><b>Sender's Email:</b> {$email}<br>";
      $msg .= "<p><b>Mssage:</b> <br> {$msg_in}<br>";
      $msg .= "</body></html>";

      $to = "ec@xilinnschinese.org";
      $from = $email;
      $from_header = "From: $from\r\nReply-To: $from\r\n";
      //$from_header .= "Cc: ec@xilinnschinese.org,jiong.han@xilinnschinese.org\r\n";
      //$from_header .= "Cc: jing.miao@xilinnschinese.org\r\n";
      $from_header .= "Bcc: rufeng_liu@hotmail.com\r\n";
      $from_header .= "Content-type: text/html\r\n";

      $subject = "Contact Us message from website. Message ID: {$mid}";
      //$to = 'rufeng_liu@hotmail.com';
      mail($to, $subject, $msg, $from_header);
    }

    redirect('/school/contactus', 'refresh');
    return;
  }

  public function sponsor()
  {
    session()->set(array('current_tab' => 'sponsor'));
    echo view($_SESSION['tm'].'school/sponsor.php');
  }

  public function pvsa()
  {
    session()->set(array('current_tab' => 'pvsa'));
    echo view($_SESSION['tm'].'school/pvsa.php');
  }

}
