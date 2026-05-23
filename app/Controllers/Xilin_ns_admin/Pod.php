<?php namespace App\Controllers\Xilin_ns_admin;

use App\Models\Semester;
class Pod extends MY_Controller
{

  function __construct()
  {

    $this->_classname = 'Pod';
    $this->_issystem = TRUE;
    parent::_Mycontroller();
    parent::_check_login();
  }

  /**
   *
   * index page
   *
   */
  function index()
  {
    $semester = new Semester();
    $data['semesters'] = $semester->getSemesters();
    $semester->getCurrentSemester();

    $semester_id = $this->request->getVar('semester_id');
    if(isset($semester_id) && !empty($semester_id)) {
      session()->set(array('semester_id' => $semester_id));
    } else {
      $current_semester = session()->get('current_semester');
      $current_semester_id = $current_semester['semester_id'];
      session()->set(array('semester_id' => $current_semester_id));
    }

    $cur_year = (int) date('Y');
    $sql = "select CONCAT(year,'-',month,'-',date) as edate
				from event_dates
				where year >= $cur_year";
    $event_dates = $this->db->query($sql)->getResultArray();
    $e = array();
    foreach($event_dates as $v) {
      array_push($e, $v['edate']);
    }

    $data['event_dates'] = $e;

    echo view($_SESSION['tm'].'admin/pod/index.php', $data);
  }

  /**
   * Get the events ajax handler
   */
  function getEvents()
  {
    //$this->load->model('pod_model');
    //$pod = new Pod_model();
    //echo $this->request->getVar('y');
    //$events = $pod->getEvents($this->request->getVar('y'),$this->request->getVar('m'),$this->request->getVar('pid'));
    $year = $this->request->getVar('y');
    $month = $this->request->getVar('m');
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
                  ) AS DETAILS,
                  signin,signout
                  FROM helpers h
                  join parents p on p.parent_id=h.parent_id
                  WHERE h.event_id = $eid ";

        $result2 = $this->db->query($query)->getResultArray();

        $helpers = "";
        if ($result2) {
          foreach ($result2 as $helper) {
            $helpers .= $helper['helper_id'] . '!' . $helper['parent_id'] . '!' . $helper['primary_en_name'] . '!' . $helper['DETAILS'] . '!' . $helper['signin'] . '!' . $helper['signout'] . '%';
          }
        }
        $return_string .= "^" . count($result2) . "^" . $event['helpers_needed'] . "^" . $event['cancel_in_days'] . "^" . '0';

        if (strlen($helpers) > 1)
        {
          $return_string .=  "^" . substr($helpers, 0, strlen($helpers) - 1);
        }
        $return_string .= "|";
      }
    }
    echo strlen($return_string) > 0 ? substr($return_string, 0, strlen($return_string) - 1) : '';
  }

  function processAddEvent()
  {
    $insertdata['year'] = $this->request->getVar('y');
    $insertdata['month'] = $this->request->getVar('m');
    $insertdata['date'] = $this->request->getVar('d');
    $insertdata['start_time'] = $this->request->getVar('s');
    $insertdata['end_time'] = $this->request->getVar('e');
    $insertdata['helpers_needed'] = $this->request->getVar('h');
    $insertdata['cancel_in_days'] = $this->request->getVar('c');
    $insertdata['semester_id'] = $this->request->getVar('semester_id');

    $this->db->table('events')
      ->insert($insertdata);
  }

  function processEditEvent()
  {
    $wheredata['event_id'] = $this->request->getVar('eid');
    $insertdata['start_time'] = $this->request->getVar('s');
    $insertdata['end_time'] = $this->request->getVar('e');
    $insertdata['helpers_needed'] = $this->request->getVar('h');
    $insertdata['cancel_in_days'] = $this->request->getVar('c');

    $this->db->table('events')
      ->where($wheredata)
      ->update($insertdata);
  }

  function processDeleteEvent()
  {
    $wheredata['event_id'] = $this->request->getVar('eid');
    //$this->db->delete('events', $wheredata);
    $this->db->table('events')
      ->delete($wheredata);
  }

  function processDownloadHelperInfo()
  {
    $year = $this->request->getVar('y');
    $month = $this->request->getVar('m');
    $date = $this->request->getVar('d');
    $query = "SELECT CONCAT((e.month+1),'/',e.date,'/',e.year,' ',start_time,'-',end_time) AS Event,
    CONCAT(
      IF(ISNULL(primary_en_name),'No EName',primary_en_name),', ',IF(ISNULL(primary_cn_name),'No CName',primary_cn_name),', ',email,', ',
      IF(ISNULL(primary_phone),'',primary_phone),', ',IF(ISNULL(alter_phone),'',alter_phone)
    ) AS Helper
    FROM events e,helpers h,parents p
    WHERE e.event_id=h.event_id
    AND p.parent_id=h.parent_id
    AND (e.year > " . $year . " OR (e.year=" . $year . " AND e.month > " . $month . ") OR (e.year=" . $year . " AND e.month=" . $month . " AND e.date>=" . $date . "))
    ORDER BY e.year,e.month,e.date";

    $result = $this->db->query($query)->getResultArray();
    echo "<table>";
    if($result) {
      foreach($result as $helper) {
        echo "<tr>"."<td>".$helper['Event']."</td>"."<td>".$helper['Helper']."</td>"."</tr>";
      }
    } else {
      echo "<tr><td>None signed up</td></tr>";
    }
    echo "<tr><td><a href='javascript:history.back()'>Go back</a></td></tr>";
    echo "</table>";
  }

  function processSigninout()
  {
    $act = $this->request->getVar('act');
    $wheredata['helper_id'] = $this->request->getVar('hid');
    if($act === '1') {
      $insertdata['signin'] = date("Y-m-d H:i:s");
      $this->db->table('helpers')
        ->where($wheredata)
        ->update($insertdata);

      echo($act."|".date("Y-m-d H:i:s"));
    } else if ($act === '2') {
      $insertdata['signout'] = date("Y-m-d H:i:s");
      $this->db->table('helpers')
        ->where($wheredata)
        ->update($insertdata);

      echo($act."|".date("Y-m-d H:i:s"));
    }

  }

  function processUnregEvent()
  {
    $wheredata['helper_id'] = $this->request->getVar('hid');
    //$this->db->delete('helpers', $wheredata);
    $this->db->table('helpers')
      ->delete($wheredata);
  }

  //deal with available event dates
  function addEventDate()
  {
    $insertdata['year'] = $this->request->getVar('y');
    $insertdata['month'] = $this->request->getVar('m');
    $insertdata['date'] = $this->request->getVar('d');

    $this->db->table('event_dates')
      ->insert($insertdata);
  }

  function editEventDate()
  {
    $wheredata['event_date_id'] = $this->request->getVar('eid');
    $insertdata['year'] = $this->request->getVar('y');
    $insertdata['month'] = $this->request->getVar('m');
    $insertdata['date'] = $this->request->getVar('d');

    $this->db->table('event_dates')
      ->where($wheredata)
      ->update($insertdata);
  }

  function deleteEventDate()
  {
    $wheredata['event_date_id'] = $this->request->getVar('eid');
    //$this->db->delete('event_dates', $wheredata);
    $this->db->table('event_dates')
      ->delete($wheredata);
  }


}