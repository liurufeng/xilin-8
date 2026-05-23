<?php namespace App\Controllers;
use App\Models\{Semester, Subject, Classes, Teacher, Article};

class Pod extends BaseController
{

  public $db = null;
  public $sess = null;
  function __construct()
  {
    $this->db = db_connect();
    $this->sess = session();
    session()->set(array('current_tab' => 'account'));
    session()->set(array('account_tab' => 'pod'));
  }

  /**
   *
   * index page
   *
   */
  function index()
  {
    $userinfo = session()->get('userresult');

    if (!empty($userinfo)) {
      $type = $userinfo['usertype'];
      if ($type == 1) {
        $id = $userinfo[0]['parent_id'];

        $sql = "select * from parents where parent_id = $id";
        $userinfodata = $this->db->query($sql)->getResultArray();
        $data['userinfodata'] = $userinfodata;
        $data['usertype'] = $type;
      $data['userinfodata'] = $userinfodata;
      $data['usertype'] = $type;

      echo view($_SESSION['tm'].'pod/index.php', $data);
      }
    } else {
      ShowMsg('Session expired, please login again!', '/signin/', 0, 1500);
    }
  }

  /**
   * Get the events ajax handler
   */
  function getEvents()
  {
    $year = $this->request->getVar('y');
    $month = $this->request->getVar('m');
    $pid = $this->request->getVar('pid');
    $return_string = '';
    $sql = "SELECT event_id,date,start_time,end_time,helpers_needed,cancel_in_days
    	      FROM events
    	      WHERE year=" . $year . "
    	      AND month=" . $month . "
    	      ORDER BY date ASC";
    $result = $this->db->query($sql)->getResultArray();

    if ($result) {
      foreach ($result as $event) {
        $eid = $event['event_id'];
        $date = $event['date'];
        $start_time = $event['start_time'];
        $end_time = $event['end_time'];

        $return_string .= $eid . "^" . $date . "^" . $start_time . "^" . $end_time;
        $query = "SELECT h.helper_id, p.parent_id,
                  IF(ISNULL(primary_en_name),'No EName',primary_en_name) primary_en_name,
                  CONCAT(
                    IF(ISNULL(primary_en_name),'No EName',primary_en_name),', ',IF(ISNULL(primary_cn_name),'No CName',primary_cn_name),', ',email,', ',
                    IF(ISNULL(primary_phone),'',primary_phone),', ',IF(ISNULL(alter_phone),'',alter_phone)
                  ) AS DETAILS
                  FROM helpers h
                  join parents p on p.parent_id=h.parent_id
                  WHERE h.event_id = $eid ";

        $result2 = $this->db->query($query)->getResultArray();
        $helpers = "";
        $thisRegistered = 0;
        if ($result2) {
          foreach ($result2 as $helper) {
            if ($pid == $helper['parent_id']) $thisRegistered = 1;
            $helpers .= $helper['helper_id'] . '!' . $helper['parent_id'] . '!'. $helper['primary_en_name'] ."!" . $helper['DETAILS'] . '!0' . '%';
          }
        }
        $return_string .= "^" . count($result2) . "^" . $event['helpers_needed'] . "^" . $event['cancel_in_days'] . "^" . $thisRegistered;
        if (strlen($helpers) > 0) $return_string .= "^" . substr($helpers, 0, strlen($helpers) - 1);
        $return_string .= "|";
      }
    }
    echo strlen($return_string) > 0 ? substr($return_string, 0, strlen($return_string) - 1) : '';
  }

  function processRegEvent()
  {
    $userinfo = session()->get('userresult');

    if (!empty($userinfo)) {
      $type = $userinfo['usertype'];
      if ($type == 1) {
        $parent_id = $userinfo[0]['parent_id'];
        $insertdata['parent_id'] = $parent_id;
        $insertdata['event_id'] = $this->request->getVar('eid');

        $this->db->table('helpers')
          ->insert($insertdata);
      }
    } else {
      ShowMsg('Session expired, please login again!', '/signin/', 0, 1500);
    }
  }

  function processUnregEvent()
  {
    $userinfo = session()->get('userresult');
    if (!empty($userinfo)) {
      $type = $userinfo['usertype'];
      if ($type == 1) {
        $parent_id = $userinfo[0]['parent_id'];
        $wheredata['helper_id'] = $this->request->getVar('hid');
        $wheredata['parent_id'] = $parent_id;
        //$this->db->delete('helpers', $wheredata);
        $this->db->table('helpers')
          ->delete($wheredata);
      }
    } else {
      ShowMsg('Session expired, please login again!', '/signin/', 0, 1500);
    }
  }

}