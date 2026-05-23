<?php namespace App\Controllers\Xilin_ns_admin;

class Event_dates extends MY_Controller
{

  function __construct()
  {
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
    $sql = "select * from event_dates order by event_date_id desc";
    $data['event_dates'] = $this->db->query($sql)->getResultArray();
    echo view($_SESSION['tm'].'admin/event_dates/index.php', $data);
  }

  //deal with available event dates
  function addEventDate()
  {
    if ($this->request->getVar('dopost') == 'add') {
      $insertdata['year'] = $this->request->getVar('year');
      $insertdata['month'] = $this->request->getVar('month');
      $insertdata['date'] = $this->request->getVar('date');

      //$this->db->insert('event_dates', $insertdata);
      $this->db->table('event_dates')
        ->insert($insertdata);
      ShowMsg("add success!", $_SESSION['admin_path'].'event_dates/index');
    }

    echo view($_SESSION['tm'].'admin/event_dates/add.php');
  }

  function editEventDate()
  {

    if ($this->request->getVar('dopost') == 'save') {
      $wheredata['event_date_id'] = $this->request->getVar('eid');
      $insertdata['year'] = $this->request->getVar('year');
      $insertdata['month'] = $this->request->getVar('month');
      $insertdata['date'] = $this->request->getVar('date');

      //$this->db->update('event_dates', $insertdata, $wheredata);
      $this->db->table('event_dates')
        ->where($wheredata)
        ->update($insertdata);
      ShowMsg('edit success!', $_SESSION['admin_path'].'event_dates/index');
    }

    $eid = (int)$this->request->getVar('eid');
    if (empty($eid)) {
      ShowMsg('param error', $_SESSION['admin_path'].'event_dates/index');
    }
    $sql = "select * from event_dates where event_date_id = $eid";
    $data['event_date'] = $this->db->query($sql)->getRowArray();
    echo view($_SESSION['tm'].'admin/event_dates/edit.php', $data);
  }

  function deleteEventDate()
  {
    $wheredata['event_date_id'] = (int)$this->request->getVar('eid');
    //$this->db->delete('event_dates', $wheredata);
    $this->db->table('event_dates')
      ->delete($wheredata);
    ShowMsg('delete success!', $_SESSION['admin_path'].'event_dates/index');
  }


}