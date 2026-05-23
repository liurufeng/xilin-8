<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Payments</title>
<link href="<?php echo base_url();?>/admin/css/base.css" rel="stylesheet" type="text/css">
  <script src="<?php echo base_url();?>/admin/js/jquery.js"></script>
  <style type="text/css">
    a.button {
      -webkit-appearance: button;
      -moz-appearance: button;
      appearance: button;

      text-decoration: none;
      color: initial;
    }
  </style>
</head>

<body background='<?php echo base_url();?>/admin/images/allbg.gif' leftmargin='8' topmargin='8'>
<table width="98%" border="0" align="center" cellpadding="2" cellspacing="1" bgcolor="#D6D6D6">
  <tr>
    <td height="27" colspan="10" background="<?php echo base_url();?>/admin/images/tbg.gif" bgcolor="#E7E7E7">
    	<table width="96%" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td width="85%" style="padding-left:5px;">
            <form action="<?=$_SESSION['admin_path']?>payment/index">
            <select id="semester_id" name="semester_id" onchange="submit();">
              <?php foreach($semesters as $semester) {?>
              <option value="<?=$semester['semester_id']?>" <?php if($semester['semester_id'] == $semester_id) {
                echo 'selected';} ?> >
                <?=$semester['semester_year']?> <?=$semester['semester_name']?>
              </option>
              <?php } ?>
            </select>
            </form>
          </td>

          <td width="15%" align="right">
            <select id="show_option" name="show_option" onchange="filter(this);">
              <option value="all">show all payments</option>
              <option value="positive">show positive balance</option>
              <option value="negative">show negative balance</option>
              <option value="zero">show zero balance</option>
              <option value="discount">show discounts 100+</option>
            </select>
          </td>
        </tr>
      </table>
     </td>
  </tr>
  <?php
  $total_paid = 0;
  $total_balance = 0;
  $no_need_reg = 0;
  foreach($parents as $pk => $p) { ?>

    <tr align="center" bgcolor="#FFFFFF" onMouseMove="javascript:this.bgColor='#FCFDEE';" onMouseOut="javascript:this.bgColor='#FFFFFF';" class="parent_tr">
      <td colspan="10">
        <div id="<?='form_'.$p['parent_id']?>" >
          <table width="100%" cellspacing="0" cellpadding="2">
            <tbody>
            <tr>
              <td width="10%">
                <div style="padding-top: 8px;">
                  <a href="mailto:<?=$p['email']?>">Parent #<?=$p['parent_id']?><br><?=$p['primary_en_name']?></a>
                  <br><?=$p['primary_phone']?>
                  <br><?=$p['email']?>
                  <br>
                  <br>
                  <a href="/account/invoice/<?=$semester_id?>/<?=$p['parent_id']?>" class="button" target="_blank">Print Invoice</a>
                </div>
              </td>
              <td width="88%">
                <table width="100%" cellspacing="0" cellpadding="2" border="1" align="left">
                  <tbody>
                  <?php foreach($students[$pk] as $sk => $s) {?>
                    <tr>
                      <td width="10%"><?=$s['en_name']?> #<?=$s['student_id']?></td>
                      <td width="90%">
                        <table width="100%" cellspacing="0" cellpadding="2" border="1" align="left">
                          <tbody>
                          <?php foreach($classes[$pk][$sk] as $ck => $c) {?>
                          <tr id="<?=$s['student_id']?>-<?=$c['class_id']?>">
                            <td><?=$c['class_code']?> - <?=$c['class_name']?>
                              <?php if($c['unregistered'] == 1) {?>
                                <span style="color: red">Unregistered @<?=$c['update_time']?></span>
                              <?php } else {?>
                              <input type="button" onclick="unregister(<?=$s['student_id']?>,<?=$c['class_id']?>,<?=$p['parent_id']?>);" value="Unregister">
                              <?php } ?>
                            </td>
                            <td>$<?=$c['tuition']?>+$<?=$c['book_fee']?>+$<?=$c['material_fee']?></td>
                          </tr>
                          <?php }?>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                  <?php }?>
                  <tr>
                    <td width="10%">Registration</td>
                    <td>
                        <table width="100%" cellspacing="0" cellpadding="2" border="1" align="left">
                            <tr style="background-color: <?= $pod[$pk]['missed'] > 0? '#FFEE00' : ''?>">
                                <td>$<?php echo $reg_fee[$pk]; ?></td>
                                <td><?= $late_fee[$pk] > 0 ? 'Late Fee: $'. $late_fee[$pk] : '' ?></td>
                            </tr>
                        </table>
                    </td>

                  </tr>
                  <tr>
                    <td width="10%">POD Info</td>
                      <td>
                        <table width="100%" cellspacing="0" cellpadding="2" border="1" align="left">
                          <tr style="background-color: <?= $pod[$pk]['missed'] > 0? '#FFEE00' : ''?>">
                            <td>Waiver: <?=$pod[$pk]['waiver']?></td>
                            <td>Needed: <?=$pod[$pk]['need']?></td>
                            <td>Done: <?=$pod[$pk]['done']?></td>
                            <td>Manual Record: <?=$pod[$pk]['manually']?></td>
                            <td>Missed: <?=$pod[$pk]['missed']?></td>
                            <td>Penalty: <?=$pod[$pk]['penalty']?></td>
                          </tr>
                        </table>
                      </td>
                  </tr>
                  </tbody>
                </table>
              </td>
            </tr>
            <tr>
              <td width="10%">&nbsp;</td>
              <td width="90%">
                <table width="100%" cellspacing="0" cellpadding="2" border="1" align="left">
                  <tbody>
                  <tr>
                    <td width="10%">Payments</td>
                    <td>
                      <table width="100%" cellspacing="0" cellpadding="2" border="1" align="left">
                        <tbody>
                        <tr>
                          <td align="center">Amount Due</td>
                          <td align="center">POD charge</td>
                          <td align="center">Discount</td>
                          <?php foreach($checks[$pk] as $check) { ?>
                            <td align="center">
                              <?=$check['pay_type'] ? $check['pay_type'].'/' : ''?>
                              <?=$check['pay_form'] ? $check['pay_form'].'/' : ''?>
                              <?=$check['check_number'] ? $check['check_number'].'/' : ''?>
                              <?=date("m.d.y",strtotime($check['check_date'])) ?>
                            </td>
                          <?php }?>
                          <td align="center" id="balance_title_<?=$pk?>">Balance</td>
                          <td align="center">
                            <select id="pay_type_<?=$pk?>" name="pay_type_<?=$pk?>">
                              <option value="">Pay Type</option>
                              <?php foreach($pay_types as $pt) {?>
                                <option value="<?=$pt?>">
                                  <?=$pt?>
                                </option>
                              <?php } ?>
                            </select>
                          </td>
                          <td align="center">
                            <select id="pay_form_<?=$pk?>" name="pay_form_<?=$pk?>">
                              <option value="">Pay Form</option>
                              <?php foreach($pay_forms as $fm) {?>
                                <option value="<?=$fm?>">
                                  <?=$fm?>
                                </option>
                              <?php } ?>
                            </select>
                          </td>
                          <td align="center">
                            <input type="text" name="initial_<?=$pk?>" id="initial_<?=$pk?>" placeholder="Initial" size="2" >
                          </td>
                        </tr>
                        <tr>
                          <td align="center"><?=$total[$pk]?></td>
                          <td align="center"><?=$pod[$pk]['penalty']?></td>
                          <td align="center" id="discount_<?=$pk?>" class="discount"><?=$discount[$pk]?></td>

                          <?php $paid = 0;
                          foreach($checks[$pk] as $check) {
                            $paid += $check['check_amount'];
                            ?>
                            <td align="center"><?=$check['check_amount']?></td>
                          <?php }
                            $total_paid += $paid;
                          ?>
                          <td align="center" id="balance_<?=$pk?>" class="balance">
                            <?php $balance = $total[$pk] + $pod[$pk]['penalty'] - $discount[$pk] - $paid;
                            $total_balance += $balance;
                            ?>
                            <?=$balance?>
                          </td>
                          <td align="center"><input type="text" id="check_money_<?=$pk?>" name="check_money_<?=$pk?>" size="6" placeholder="Amount"></td>
                          <td align="center">
                            <input type="text" id="check_num_<?=$pk?>" name="check_num_<?=$pk?>" size="13" placeholder="Notes,Ref#">
                          </td>
                          <td align="center">

                            <input type="submit" onclick="updateCheck('<?=$pk?>', '<?=$semester_id?>');" value="Update" name="update" class="update">
                        </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                  </tbody>
                </table>
              </td>
            </tr>
            </tbody>
          </table>
        </div>
      </td>
    </tr>
  <?php } ?>
</table>

<table width="97%" align="center" cellspacing="0" cellpadding="5" border="1" align="left" style="margin-top: 30px;font-weight: bold;">
  <tbody>
    <tr>
      <td></td><td>Families</td><td>Students</td><td>Classes</td><td>Account Receivable (Tuition+Book+Material+POD+Registration+Late-Discount)</td><td>Paid</td><td>Balance</td>
    </tr>
    <tr>
      <?php
      $grandTotal = $totalT + $podT - $discountT;?>
      <td>Total</td><td><?=count($parents)?></td><td><?=$studentsT?></td><td><?=$classesT?></td><td><?=$grandTotal?> (<?=$tuition_totalT?> + <?=$book_totalT?> + <?=$material_totalT?> + <?=$podT?> + <?=$reg_feeT?> + <?=$late_feeT?> - <?=$discountT?>)</td><td><?=$total_paid?></td><td><?=$total_balance?></td>
    </tr>
  </tbody>
</table>
<div id='ajax_loader' style="position: fixed; left: 40%; top: 50%; display: none; z-index: 1000">
  <img src="<?php echo base_url();?>/admin/images/loading-o.gif">
</div>
<script type="text/javascript">

  function updateCheck(pid, sid){
    if($.trim($('#check_num_'+pid).val()) == '') {
      $('#check_num_'+pid).css("background-color", "yellow");
      alert("Please provide check number or payment note!");
      return;
    } else {
      $('#check_num_'+pid).css("background-color", "");
    }
    if($.trim($('#check_money_'+pid).val()) == '') {
      $('#check_money_'+pid).css("background-color", "yellow");
      alert("Please provide payment amount!");
      return;
    } else {
      $('#check_money_'+pid).css("background-color", "");
    }
    if($.trim($('#initial_'+pid).val()) == '') {
      $('#initial_'+pid).css("background-color", "yellow");
      alert("Please initial the payment!");
      return;
    } else {
      $('#initial_'+pid).css("background-color", "");
    }

    $("#ajax_loader").show();
    $(this).attr("disabled", true);
    $.post("<?=$_SESSION['admin_path']?>payment/addPayment",
      { pid: pid,
        sid: sid,
        check_num : $('#check_num_'+pid).val(),
        check_money : $('#check_money_'+pid).val(),
        initial : $('#initial_'+pid).val(),
        pay_type : $('#pay_type_'+pid).val(),
        pay_form : $('#pay_form_'+pid).val()
      },
      function(result){
        result = JSON.parse(result);
        //console.log(result.success);
        if(result.success) {
          $(this).attr("disabled", false);
          $("#ajax_loader").hide();

          //update balance element
          var bal =  $.trim($('#balance_'+pid).text());
          var bal_val = parseFloat(bal) - $('#check_money_'+pid).val();
          $('#balance_'+pid).text(bal_val);
          //insert the newly added payment element
          var num_td = '<td align="center">' + $('#pay_type_'+pid).val() + ': ' + $('#check_num_'+pid).val() +'</td>';
          $('#balance_title_'+pid).before(num_td);
          var money_td = '<td align="center">' + $('#check_money_'+pid).val() +'</td>';
          $('#balance_'+pid).before(money_td);
          //empty the input field
          $('#check_num_'+pid).val('');
          $('#check_money_'+pid).val('');
          $('#initial_'+pid).val('');
          alert('Payment added successfully!');
          return false;
        }
        else {
          $("#ajax_loader").hide();
          $(this).attr("disabled", false);
          alert('Failed to add payment, please refresh and try again!');
          return false;
        }
      });
  }

  function showLoading() {
    $("#ajax_loader").show();
    $('.update').attr('disabled','disabled');
  }

  function filter(param)
  {
        $('.balance').each(function(){
          $(this).parents('.parent_tr').show();
          var b = Number($(this).text());
          if(param.value == 'positive') {
            if(b <= 0) {
              $(this).parents('.parent_tr').hide();
            }
          } else if(param.value == 'negative') {
            if(b >= 0) {
              $(this).parents('.parent_tr').hide();
            }
          } else if(param.value == 'zero') {
            if(b != 0) {
              $(this).parents('.parent_tr').hide();
            }
          } else if(param.value == 'discount') {
              let discount = $(this).parents('.parent_tr').find('.discount');
              console.log(discount);
              let disc = Number(discount.text());
              console.log(disc);
              if(disc < 100) {
                  $(this).parents('.parent_tr').hide();
              }
          }
        });

  }

  function unregister(sid, cid, pid){
    if(confirm("Are you sure you want to unregister this class?")){
    $("#ajax_loader").show();
    $.post('/register_class/do_register?stdid='+sid+'&pid='+pid,
      { student_id: sid,
        class_id: cid,
        act : 1
      },
      function(result){
        result = JSON.parse(result);
        if(result.success) {
          $("#ajax_loader").hide();
          $("#"+sid+'-'+cid).hide();
          alert('Class unregistered successfully!');
          return false;
        }
        else {
          $("#ajax_loader").hide();
          alert('Failed to unregister class, please refresh and try again!');
          return false;
        }
      });
    }
  }

</script>
</body>
</html>