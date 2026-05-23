<?php namespace App\Controllers\Xilin_ns_admin;

class Sponsorpayments extends MY_Controller
{

  function __construct()
  {

    $this->_classname = 'Sponsorpayments';
    $this->_methods = array(
      'index' => 'list',
      'add' => 'add',
      'edit' => 'edit',
      'del' => 'del',
    );
    $this->_issystem = TRUE;
    parent::_Mycontroller();
    parent::_check_login();
  }

  /**
   *
   * list
   *
   */
  function index()
  {
    $sql = "select pay.*,sp.name
				from sponsor_payments  pay
				join sponsor sp on pay.sponsor_id = sp.sponsor_id
				where pay.status = 1";
    $data['list'] = $this->db->query($sql)->getResultArray();
    echo view($_SESSION['tm'].'admin/sponsorpayments/index.htm', $data);
  }

  /**
   * add
   */
  function add()
  {
    if ($this->request->getVar('dopost'] == 'add') {
      if ($this->request->getVar('sponsor_id']) {
        $insertdata['sponsor_id'] = $this->request->getVar('sponsor_id');
        $insertdata['paid_at'] = $this->request->getVar('paid_at');
        $insertdata['paid_amount'] = $this->request->getVar('paid_amount');
        $this->db->insert('sponsor_payments', $insertdata);
        ShowMsg("add success!", $_SESSION['admin_path'].'sponsorpayments/index'));
      }
    }
    $sql = "select sponsor_id,name
				from  sponsor') . "
				where status = 1";
    $data['groupList'] = $this->db->query($sql)->getResultArray();
    echo view($_SESSION['tm'].'admin/sponsorpayments/add.htm', $data);
  }

  /**
   * edit
   */
  function edit()
  {
    if ($this->request->getVar('dopost'] == 'save') {
      // 判断用户名是否存在
      if ($this->request->getVar('sponsor_pay_id']) {
        $insertdata['sponsor_id'] = $this->request->getVar('sponsor_id');
        $insertdata['paid_at'] = $this->request->getVar('paid_at');
        $insertdata['paid_amount'] = $this->request->getVar('paid_amount');
        $wheredata['sponsor_pay_id'] = $this->request->getVar('sponsor_pay_id');
        $this->db->update('sponsor_payments', $insertdata, $wheredata);
        ShowMsg('edit success!', $_SESSION['admin_path'].'sponsorpayments/index'));
      }
    }
    $sponsor_pay_id = (int)$this->request->getVar('sponsor_pay_id');
    if (empty($sponsor_pay_id)) {
      ShowMsg('param error', $_SESSION['admin_path'].'sponsorpayments/index'));
    }
    $sql = "select sponsor_id,name
				from sponsor
				where status = 1";
    $data['groupList'] = $this->db->query($sql)->getResultArray();
    $sql = " SELECT * FROM  sponsor_payments') . " WHERE sponsor_pay_id = $sponsor_pay_id";
    $data['info'] = $this->db->query($sql)->getRowArray();
    echo view($_SESSION['tm'].'admin/sponsorpayments/edit.htm', $data);
  }

  /**
   *  del
   */
  function del()
  {

    $sponsor_pay_id = (int)$this->request->getVar('sponsor_pay_id');
    if (empty($sponsor_pay_id)) {
      ShowMsg('param error', $_SESSION['admin_path'].'sponsorpayments/index'));
    }
    $sql = " update  sponsor_payments set status = '2' WHERE sponsor_pay_id = $sponsor_pay_id";
    $this->db->query($sql);
    ShowMsg('delete success!', $_SESSION['admin_path'].'sponsorpayments/index'));
  }
}

