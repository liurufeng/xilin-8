<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Payments</title>
<link href="<?= base_url();?>/admin/css/base.css" rel="stylesheet" type="text/css">
  <script src="<?= base_url();?>/admin/js/jquery.js"></script>

</head>

<body background='<?= base_url();?>/admin/images/allbg.gif' leftmargin='8' topmargin='8'>
<table width="98%" border="0" align="center" cellpadding="2" cellspacing="1" bgcolor="#D6D6D6">
  <tr>
    <td height="27" colspan="14" background="<?= base_url();?>/admin/images/tbg.gif" bgcolor="#E7E7E7">
    	<table width="96%" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td width="85%" style="padding-left:5px;">
            <form action="<?= site_url('payment/online">
            <select id="semester_id" name="semester_id" onchange="submit();">
              <? foreach($semesters as $semester) {?>
              <option value="<?=$semester['semester_id']?>" <? if($semester['semester_id'] == $semester_id) echo 'selected'; ?> >
                <?=$semester['semester_year']?> <?=$semester['semester_name']?>
              </option>
              <? } ?>
            </select>
            </form>
          </td>

        </tr>
      </table>
     </td>
  </tr>

  <tr bgcolor="#FBFCE2">
    <td width="4%" height="24" align="center">PID</td>
    <td width="6%" align="center">Name</td>
    <td width="12%" align="center">Email</td>
    <td width="5%" align="center">Phone</td>
    <td width="7%" align="center">Alt name</td>
    <td width="11%" align="center">Alt email</td>
      <td width="7%" align="center">Signature</td>
      <td width="7%" align="center">Dated</td>
      <td width="5%" align="center">Balance</td>
      <td width="6%" align="center">Online Fee</td>
      <td width="5%" align="center">Status</td>
      <td width="6%" align="center">Processed</td>
      <td width="4%" align="center">By</td>
    <td width="8%" align="center">Action</td>
  </tr>
  <?
  $total_paid = 0;
  $total_balance = 0;
  $no_need_reg = 0;
  foreach($payments as $p) { ?>
    <tr align="center" bgcolor="#FFFFFF" onMouseMove="javascript:this.bgColor='#FCFDEE';" onMouseOut="javascript:this.bgColor='#FFFFFF';">
      <td><?= $p['parent_id']?></td>
      <td><?= $p['primary_en_name']?></td>
      <td><?= $p['email']?></td>
      <td><?= $p['primary_phone']?></td>
      <td><?= $p['alter_en_name']?></td>
      <td><?= $p['alter_contact_email']?></td>
      <td><?= $p['signature']?></td>
      <td><?= $p['pdate']?></td>
      <td><?= $p['school_fee']?></td>
      <td><?= $p['online_fee']?></td>
      <td id="status_<?= $p['pay_id']?>"><?= $p['status']?></td>
      <td id="dt_<?= $p['pay_id']?>"><?= $p['processed_date']?></td>
      <td id="by_<?= $p['pay_id']?>"><?= $p['processed_by']?></td>
    <td>
      <input type="submit" onclick="updatePayment('<?=$p['pay_id']?>', 'Approved');" value="Aprv" name="Aprv" style="color: green; font-weight: bold">
      <input type="submit" onclick="updatePayment('<?=$p['pay_id']?>', 'Declined');" value="Dcln" name="Dcln" style="color: #ffa655; font-weight: bold">
      <input type="submit" onclick="updatePayment('<?=$p['pay_id']?>', 'Deleted');" value="X" name="X" style="color: red; font-weight: bold">
    </td>
    </tr>
  <? } ?>
</table>

<!--<table width="97%" align="center" cellspacing="0" cellpadding="5" border="1" align="left" style="margin-top: 30px;font-weight: bold;">
  <tbody>
    <tr>
      <td>Families</td>
      <td>Students</td>
      <td>Classes</td>
      <td>Account Receivable (Tuition+Book+Material+POD+Registration-Discount)</td>
      <td>Paid</td>
      <td>Balance</td>
    </tr>

  </tbody>
</table>-->
<div id='ajax_loader' style="position: fixed; left: 40%; top: 50%; display: none; z-index: 1000">
  <img src="<?= base_url();?>/admin/images/loading-o.gif">
</div>
<script type="text/javascript">

  function updatePayment(pay_id, act){
    var realname = "<?=session()->get('realname";
    var r = confirm("Are you sure to set the status of this online payment to be " + act + "?");
    if (r == true) {
    $("#ajax_loader").show();
    $.post("<?= site_url('/payment/update_online",
      { pay_id: pay_id,
        act: act
      },
      function(result){
        if(result.success) {
          $("#ajax_loader").hide();

          $("#by_"+pay_id).text(realname);
          var today = new Date();
          var dd = today.getDate();
          var mm = today.getMonth()+1; //January is 0!

          var yyyy = today.getFullYear();
          if(dd<10){
            dd='0'+dd
          }
          if(mm<10){
            mm='0'+mm
          }
          var today = mm+'/'+dd+'/'+yyyy;
          $("#dt_"+pay_id).text(today);
          $("#status_"+pay_id).text(act);

          alert('Payment processed successfully!');
          return false;
        }
        else {
          $("#ajax_loader").hide();
          alert('Failed to update payment, please refresh and try again!');
          return false;
        }
      });
    }

  }

  function showLoading() {
    $("#ajax_loader").show();
  }

</script>
</body>
</html>