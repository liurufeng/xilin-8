<?php
namespace App\Controllers;

/**
 *
 * @author Rufeng Liu
 *
 */
class Scheduler extends BaseController
{
  public $db = null;
  function __construct()
  {
    $this->db = db_connect();
  }
  public function index()
  {
    $p = '';
    $uri = service('uri');
    if($uri->getTotalSegments() > 1 ) {
      $p = $uri->getSegment(2);
    }
    if($p!="qDqAxn24w!0@o9!*@o15") die();
    $from = "ec@xilinnschinese.org";
    $from_header = "From: $from\r\nReply-To: $from\r\n";
    $from_header .= "Cc: ec@xilinnschinese.org,jiong.han@xilinnschinese.org\r\n";
    $from_header .= "Bcc: rufeng_liu@hotmail.com\r\n";
    $from_header .= "Content-type: text/html\r\n";
    $subject = "POD at Chinese School";

    $sql = "SELECT event_id, CONCAT((`month`+1),'/',`date`,'/',`year`) edate,start_time,end_time
    FROM events
    WHERE STR_TO_DATE(CONCAT(`year`,'-',(`month`+1),'-',`date`),'%Y-%m-%d') BETWEEN CURDATE() AND CURDATE()+INTERVAL 4 DAY";

    $events = $this->db->query($sql)->getResultArray();

      foreach($events as $e) {
        $eid = $e['event_id'];
        $sql = "SELECT IF(ISNULL(primary_en_name),'No Name?',primary_en_name) as pname,email
        FROM helpers h,parents p
        WHERE event_id=$eid AND p.parent_id=h.parent_id";

        $helpers = $this->db->query($sql)->getResultArray();

        foreach($helpers as $h) {
          $email = $h['email'];
          $mdy = $e['edate'];
          $t1 = $e['start_time'];
          $t2 = $e['end_time'];
          $msg = "<html><body>";
          $msg .= "<p>亲爱的家长：<br></p>";
          $msg .= "<p>此电邮友好提醒家长你, 注册了希林芝北中文学校这个星期天($mdy) $t1 to $t2 的POD工作. 值班家长应在当班之日按时到校。如果迟到或早退，学校将收取10 美元罚金。值班家长如因故不能到校值班，请自行找其他家长替换。如无法找到替换家长，请尽早电邮韩炯 Mr. Jiong Han at jiong.han@xilinnschinese.org, 学校将帮助安排替换家长. 如当天有急事无法前来，请速电 847-512-8389 <span style='color=red'>(请勿回复此邮件联系值班事宜.)</span> 如不通知学校，学校将按照规定在下学年注册时罚款30美金。<br></p>";
          $msg .= "<p>欲知POD政策详情，请点击下面链条: http://xilinnschinese.org/uploadfiles/web_documents/XilinNS_POD_rules.pdf <br><br></p>";
          $msg .= "<p>Dear Parents:<br></p>";
          $msg .= "<p>This is a friendly reminder that you are scheduled for parent-on-duty (POD) at Xilin North Shore Chinese School this Sunday($mdy) $t1 to $t2. Please come on time.<br></p>";
          $msg .= "<p>Parents on duty should arrive on time. If you are late or leave early, the school will charge you a $10 fine. If you already signed up the duty and are not able to come to do the duty, please inform Mr. Jiong Han at jiong.han@xilinnschinese.org as soon as possible. If you can’t come on the day you are on duty, please call Mr. Jiong Han at 847-512-8389.(Please do not reply this email for POD matters) If parent-on-duty does not inform the school in advance that they are unable to make their commitment, the school will charge the parent-on-duty a $30 fine at registration for the next school year.<br></p>";
          $msg .= "<p>To know details about POD, please check this link: http://xilinnschinese.org/uploadfiles/web_documents/XilinNS_POD_rules.pdf <br></p>";
          $msg .= "<p>Thank you,<br>";
          $msg .= "EC Committee, Xilinn NS Chinese School<br>";
          $msg .= "http://xilinnschinese.org/</p>";
          $msg .= "</body></html>";
          $to = $email;
          //$to = 'rufeng_liu@hotmail.com';
          mail($to, $subject, $msg, $from_header);
        }
      }
    echo "Success!";
  }
}
