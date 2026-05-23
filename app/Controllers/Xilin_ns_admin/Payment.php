<?php namespace App\Controllers\Xilin_ns_admin;

use App\Models\Admin\Payment_model;

class Payment extends MY_Controller
{

  function __construct()
  {
    $this->_issystem = TRUE;
    parent::_Mycontroller();
    parent::_check_login();
    //$this->load->helper('encode');
    helper('encode');
  }

  /**
   * Function for the admin payment page
   *
   */
  function index()
  {
    $semester_id = $this->request->getVar('semester_id');

    $payment = new Payment_model();
    $data = $payment->getPaymentData($semester_id);

    echo view($_SESSION['tm'].'admin/payment/index.php', $data);
  }

  /**
   * Function for the adding late fee
   *
   */
  function late_fee()
  {
    $payment = new Payment_model();
    $data = $payment->getPaymentData('');
    $semester_id = session()->get('semester_id');
    $total = $data['total'];
    $pod = $data['pod'];
    $discount = $data['discount'];
    $checks = $data['checks'];

    foreach($data['parents'] as $pk => $p) {
      $paid = 0;
      foreach($checks[$pk] as $check) {
        $paid += $check['check_amount'];
      }
      $balance = $total[$pk] + $pod[$pk]['penalty'] - $discount[$pk] - $paid;
      if($balance > 10) { // apply late fee
        $insertdata = [
          'semester_id' => $semester_id,
          'parent_id' => $pk,
          'amount' => 20
        ];
        $this->db->table('late_fee')
          ->replace($insertdata);
      }
    }

  }

  /**
   * Add a payment
   */
  function addPayment()
  {
    $checking = $this->request->getVar('pid').$this->request->getVar('check_num').$this->request->getVar('check_money').date("Y-m-d H:i:s").$this->request->getVar('initial').$this->request->getVar('pay_type').$this->request->getVar('pay_form');
    if(!isset($_SESSION['avoid_dup']) || $_SESSION['avoid_dup'] != $checking) {
      $insertdata['parent_id'] = $this->request->getVar('pid');
      $insertdata['semester_id'] = $this->request->getVar('sid');
      $insertdata['check_number'] = $this->request->getVar('check_num');
      $insertdata['check_amount'] = $this->request->getVar('check_money');
      $insertdata['check_date'] = date("Y-m-d H:i:s");
      $insertdata['received_by'] = $this->request->getVar('initial');
      $insertdata['pay_type'] = $this->request->getVar('pay_type');
      $insertdata['pay_form'] = $this->request->getVar('pay_form');
      //$this->db->insert('checks', $insertdata);
      $this->db->table('checks')
        ->insert($insertdata);
      $_SESSION['avoid_dup'] = $checking;
    }
    $data = array('success' => TRUE);
    sendJson($data);
  }


  //show online payments
  function online()
  {
    $this->load->model('semester_model');
    $semester = new Semester_model();
    $data['semesters'] = $semester->getSemesters();
    $semester->getCurrentSemester();

    $semester_id = $this->request->getVar('semester_id');

    if(isset($semester_id) && !empty($semester_id)) {
      session()->set(array('semester_id' => $semester_id));
      $where =' where semester_id='.$semester_id;
    } else {
      $current_semester = session()->get('current_semester');
      $semester_id = $current_semester['semester_id'];
      session()->set(array('semester_id' => $semester_id));
      $where =' where semester_id='.$semester_id;
    }

    $data['semester_id'] = $semester_id;

    $data['this_semester'] = $semester->getSemester($semester_id );

    $sql = "select op.*, primary_en_name, primary_cn_name, primary_phone, email, alter_en_name, alter_contact_email
            from online_payment op
            join parents p on p.parent_id = op.parent_id
            ".$where."
            and status != 'Deleted'
            order by status DESC, processed_date, parent_id";

    $data['payments'] = $this->db->query($sql)->getResultArray();

    echo view($_SESSION['tm'].'admin/payment/online.php', $data);
  }

  //approve/decline/delete online payments
  function update_online()
  {
    $data = array('success' => FALSE);

    $wheredata['pay_id'] = $this->request->getVar('pay_id');
    $insertdata['status'] = $this->request->getVar('act');
    $insertdata['processed_by'] = session()->get('realname');
    $insertdata['processed_date'] = date("Y-m-d H:i:s");

    $result = $this->db->update('online_payment', $insertdata, $wheredata);
    if($result) {
      //now update the checks table for this online payment
      $sql = "select op.*
            from online_payment op
            where pay_id = " . $this->request->getVar('pay_id');

      $pay_info = $this->db->query($sql)->getRowArray();

      if($this->request->getVar('act') == 'Approved') {
        $insertdata1['semester_id'] = $pay_info['semester_id'];
        $insertdata1['parent_id'] = $pay_info['parent_id'];
        $insertdata1['check_number'] = $this->request->getVar('pay_id');
        $insertdata1['check_amount'] = $pay_info['school_fee'];
        $insertdata1['check_date'] = date("Y-m-d H:i:s");
        $insertdata1['received_by'] = session()->get('realname');
        $insertdata1['pay_type'] = 'Regular';
        $insertdata1['pay_form'] = 'Online';

        $result1 = $this->db->insert('checks', $insertdata1);
      } elseif ($this->request->getVar('act') == 'Declined' || $this->request->getVar('act') == 'Deleted' ) {
        $wheredata1['semester_id'] = $pay_info['semester_id'];
        $wheredata1['parent_id'] = $pay_info['parent_id'];
        $wheredata1['check_number'] = $this->request->getVar('pay_id');
        $wheredata1['check_amount'] = $pay_info['school_fee'];
        $wheredata1['pay_type'] = 'Online';

        //$result1 = $this->db->delete('checks', $wheredata1);
        $result1 = $this->db->table('checks')
          ->delete($wheredata1);
      }
      if($result1) {
        $data = array('success' => TRUE);
      }
    }

    sendJson($data);
  }
}

