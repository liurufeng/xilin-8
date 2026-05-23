<?php
namespace App\Models;

use CodeIgniter\Model;
class Pod_model extends Model
{

  function getEvents($year, $month, $pid=0)
  {
    return 'test';
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
                  IF(ISNULL(primary_en_name),'No EName',primary_en_name),
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
        $thisRegistered = 0;
        if ($result2) {
          foreach($result2 as $helper){
            if ($pid == $helper['parent_id']) $thisRegistered = 1;
            $helpers .= $helper['helper_id'] . '!' . $helper['parent_id'] . '!' . $helper['primary_en_name'] . '!' . $helper['DETAILS'] . '!' . $helper['signin'] . '!' . $helper['signout'] . '%';
          }
        }
        $return_string .= "^" . count($result2) . "^" . $helper['signin'] . "^" . $helper['signout'] . "^" . $thisRegistered;
        if ($helpers . length > 1) $return_string = $return_string . "^" . substr($helpers, 0, strlen($helpers) - 1);
        $return_string .= "|";
      }
    }
    return $return_string.length > 0 ? substr($return_string,0,strlen($return_string)-1) : '';
    //return substr($return_string,0,strlen($return_string)-1);
  }

  function getPODCharge($parent=array(), $current_semester=array(), $prev_semester=array()) {
    //$current_semester = session()->get('current_semester');
    if($current_semester['semester_name'] == 'Spring') {
      return array('waiver'=>'NA', 'need'=>0,'done'=>0,'manually'=>0, 'missed'=>0, 'penalty'=>0);
    } else {
      $sql = "select semester_id from semester
              where status = 1 and semester_id < " . $current_semester['semester_id']
              ." group by semester_id
               order by semester_id desc
               limit 2";
      $sids = $this->db->query($sql)->getResultArray();
      if(count($sids) == 2) {
        $sid_string = "({$sids[0]['semester_id']},{$sids[1]['semester_id']})";
      }
    }

    // if the parent was set as a pod waiver
    $sql = "select * 
            from pod_waiver pw
            where pw.semester_id in ".$sid_string."
            and pw.parent_id=".$parent['parent_id'];
    $row = $this->db->query($sql)->getResultArray();
    if (count($row) > 0) {
      return array('waiver'=>'yes', 'need'=>0,'done'=>0,'manually'=>0, 'missed'=>0, 'penalty'=>0);
    } else {
      // check if the parent is board or EC
      $sql = "select * 
            from schooluser su
            where su.semester_id in ".$sid_string."
            and su.parent_id=".$parent['parent_id']."
            and su.status = 1 ";
      $row = $this->db->query($sql)->getResultArray();
      if (count($row) > 0) {
        return array('waiver'=>'yes', 'need'=>0,'done'=>0,'manually'=>0, 'missed'=>0, 'penalty'=>0);
      }
    }
    //if($parent['pod_waiver'] > 0) return array('waiver'=>'yes', 'need'=>0,'done'=>0,'manually'=>0, 'missed'=>0, 'penalty'=>0);
    $sql = "select s.student_id from
            students s
            join parents p on p.parent_id = s.parent_id
            join studentclasses sc on sc.student_id = s.student_id
            join classes c on c.class_id = sc.class_id
            where sc.semester_id in ".$sid_string.
            " and sc.deleted != 1
            and p.parent_id = " .$parent['parent_id'].
            " and c.class_code NOT LIKE '%GRP%'
            group by s.student_id";

    $student_num = $this->db->query($sql)->getNumRows();
    if($student_num == 0) return array('waiver'=>'no', 'need'=>0,'done'=>0,'manually'=>0, 'missed'=>0, 'penalty'=>0);
    $student_num = $student_num * 2; //need 1 POD each semester and that is 2 each school year

    //get how many POD the parent has done if any in the previous semester
    $sql = "SELECT count(*) as done
            FROM helpers h
            JOIN events e on h.event_id = e.event_id
            WHERE e.semester_id  in " . $sid_string . "
            AND (h.signin IS NOT NULL
            OR h.signout IS NOT NULL
            )
            AND h.parent_id = " . $parent['parent_id'];

    $result = $this->db->query($sql)->getRowArray();
    $done_num = (int) $result['done'];

    //get how many POD the parent has done but recorded manually if any in the previous semester
    $sql = "SELECT manual_records
            FROM manual_pod_record
            WHERE semester_id  in ".$sid_string . "
            AND parent_id = " . $parent['parent_id'];

    $result = $this->db->query($sql)->getRowArray();
    $manually = $result ? (int)$result['manual_records'] : 0;
    $diff = $student_num - $done_num - $manually;
    if($diff < 1) return array('waiver'=>'no', 'need'=>$student_num,'done'=>$done_num,'manually'=>$manually, 'missed'=>0, 'penalty'=>0);

    $prev_semester['pod_charge'] = 30;
    return
    array('waiver'=>'no', 'need'=>$student_num,'done'=>$done_num,'manually'=>$manually, 'missed'=>$diff, 'penalty'=>$diff * $prev_semester['pod_charge']);
  }

  function getPODReport($parent=array(), $sid_string='') {
    //if($parent['pod_waiver'] > 0) return array('waiver'=>'yes', 'need'=>0,'done'=>0,'manually'=>0, 'missed'=>0, 'penalty'=>0);
    $sql = "select s.student_id from
            students s
            join parents p on p.parent_id = s.parent_id
            join studentclasses sc on sc.student_id = s.student_id
            join classes c on c.class_id = sc.class_id
            where sc.semester_id in ".$sid_string.
      " and sc.deleted != 1
      and p.parent_id = " .$parent['parent_id'].
      " and c.class_code NOT LIKE 'GRP%'
      group by s.student_id";

    $student_num = count($this->db->query($sql)->getResultArray());
    if($student_num == 0) return array('waiver'=>'no', 'need'=>0,'done'=>0,'manually'=>0, 'missed'=>0, 'penalty'=>0,'todo'=>0);

    //get how many POD the parent has done if any in the previous semester
    $sql = "SELECT count(*) as done
            FROM helpers h
            JOIN events e on h.event_id = e.event_id
            WHERE e.semester_id  in " . $sid_string . "
            AND (h.signin IS NOT NULL
            OR h.signout IS NOT NULL
            )
            AND h.parent_id = " . $parent['parent_id'];

    $result = $this->db->query($sql)->getRowArray();
    $done_num = (int) $result['done'];

    //get how many POD the parent has registered
    $sql = "SELECT count(*) as todo
            FROM helpers h
            JOIN events e on h.event_id = e.event_id
            WHERE e.semester_id  in " . $sid_string . "
            AND (h.signin IS NULL
            AND h.signout IS NULL
            )
            AND h.parent_id = " . $parent['parent_id'];

    $result = $this->db->query($sql)->getRowArray();
    $todo_num = (int) $result['todo'];

    //get how many POD the parent has done but recorded manually if any in the previous semester
    $sql = "SELECT sum(manual_records) as manual_records
            FROM manual_pod_record
            WHERE semester_id  in ".$sid_string . "
            AND parent_id = " . $parent['parent_id'];

    $result = $this->db->query($sql)->getRowArray();
    $manually = $result ? (int)$result['manual_records'] : 0;
    $diff = $student_num - $done_num - $manually;
    if($diff < 1) return array('waiver'=>'no', 'need'=>$student_num,'done'=>$done_num,'manually'=>$manually, 'missed'=>0, 'penalty'=>0, 'todo'=>$todo_num);

    return
      array('waiver'=>'no', 'need'=>$student_num,'done'=>$done_num,'manually'=>$manually, 'missed'=>$diff, 'penalty'=>$diff * 30, 'todo'=>$todo_num);
  }
}
