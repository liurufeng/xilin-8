<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>classes</title>
<link href="<?php echo base_url();?>/admin/css/base.css" rel="stylesheet" type="text/css">
</head>
<body background='<?php echo base_url();?>/admin/images/allbg.gif' leftmargin='8' topmargin='8'>
<table width="98%" border="1" align="center" cellpadding="2" cellspacing="1">
  <tr>
    <td height="27" colspan="11" background="<?php echo base_url();?>/admin/images/tbg.gif">
    	<table width="96%" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td width="90%" style="padding-left:5px;">
            <form action="<?=$_SESSION['admin_path']?>roster/index">
            <select id="semester_id" name="semester_id" onchange="submit();">
              <?php foreach($semesters as $semester) {?>
              <option value="<?=$semester['semester_id']?>" <?php if($semester['semester_id'] == session()->get('semester_id')) echo 'selected'; ?> >
                <?=$semester['semester_year']?> <?=$semester['semester_name']?>
              </option>
              <?php } ?>
            </select>
            </form>
          </td>

          <td align="right">
            <button onclick="window.print();">Print</button>
          </td>
        </tr>
        <tr>
          <td height="2" colspan="11"><br>
          </td>
        </tr>
      </table>
     </td>
  </tr>

  <?php
  $all_emails = '';
  $positiveBalances = array();
  foreach($list as $k=>$v){ ?>

    <tr bgcolor="#E4F0F9" style="text-align: center; font-weight: bold;">
      <td colspan="9">
        <?php echo $v['class_code']?>,
        <?php echo $v['class_name']?>,
        <?php echo $v['teacher']?> (<?php echo $v['subject']?>),
        <?php echo $v['meeting_time']?> @ <?php echo $v['classroom']?>
      </td>
    </tr>

    <tr style="font-weight: bold;">
      <td width="3%">No.</td>
      <td width="6%">Student Id</td>
      <td width="15%">Student Name</td>
      <td width="15%">Parent Name</td>
      <td width="18%">Parent Email</td>
      <td width="18%">Alt Email</td>
      <td width="6%">Parent Id</td>
      <td width="8%">With Book</td>
      <td width="10%">Balance</td>
    </tr>
    <?php
    $db = db_connect();
    $sql = "select p.parent_id, p.primary_en_name,  p.email, alter_contact_email, s.en_name sename,
    s.student_id sid, sc.buy_book
    from studentclasses sc
    join students s on s.student_id = sc.student_id
    join parents p on p.parent_id = s.parent_id
    where sc.deleted = 0 and sc.class_id = ".$v['class_id']
    ." order by s.en_name ";
    $students = $db->query($sql)->getResultArray();
    $num = 1;

    foreach($students as $s) {
      ?>
      <tr>
        <td><?=$num?></td>
        <td><?php echo $s['sid']?></td>
        <td><?php echo $s['sename']?></td>
        <td><?php echo $s['primary_en_name']?></td>
        <td><?=$s['email']?></td>
        <td><?=$s['alter_contact_email']?></td>
        <td><?php echo $s['parent_id']?></td>
        <td><?php echo empty($s['buy_book'])?'---':'Yes' ?></td>
        <td>
          <?php $paid = 0;
          if(is_array($payment['checks'][$s['parent_id']])) {
            foreach ($payment['checks'][$s['parent_id']] as $check) {
              $paid += $check['check_amount'];
            }
          }
          $balance = $payment['total'][$s['parent_id']] + $payment['pod'][$s['parent_id']]['penalty'] - $payment['discount'][$s['parent_id']] - $paid;
          if($balance > 0) {
            $positiveBalances[$s['parent_id']]= "<tr><td>".$s['primary_en_name']."</td><td>".$s['email']."</td><td>".$s['parent_id']
              ."</td><td>".$balance."</td></tr>";
          }
          ?>
          <span style="color: <?= $balance>0?'red':''?>"><?=$balance?></span>
        </td>
      </tr>
  <?php $num++; } ?>
  <tr bgcolor="#FFFFFF" >
    <td height="5" colspan="11"><br>
    </td>
  </tr>
<?php } ?>
</table>

<div style="margin: 30px; text-align: center; font-weight: bold;">Positive Balances</div>
<table width="98%" border="1" align="center" cellpadding="2" cellspacing="1">
    <tr style="font-weight: bold;">
        <td width="15%">Parent Name</td>
        <td width="18%">Parent Email</td>
        <td width="10%">Parent Id</td>
        <td width="10%">Balance</td>
    </tr>
    <?php foreach($positiveBalances as $pb) {
      echo $pb;
    }?>
</table>

<div style="margin: 30px; text-align: center; font-weight: bold;">Book Purchase List</div>
<table width="98%" border="1" align="center" cellpadding="2" cellspacing="1">
    <tr style="font-weight: bold;">
        <td width="10%">Parent Id</td>
        <td width="15%">Parent Name</td>
        <td width="18%">Parent Email</td>
        <td width="10%">Student Name</td>
        <td width="10%">Class Code</td>
        <td width="10%">Class Name</td>
        <td width="10%">Balance</td>
    </tr>
  <?php $sql = "select p.parent_id, p.primary_en_name,  p.email, alter_contact_email, s.en_name sename,
    s.student_id sid, sc.buy_book, c.class_code, c.class_name
    from studentclasses sc
    join students s on s.student_id = sc.student_id
    join parents p on p.parent_id = s.parent_id
    join classes c on c.class_id = sc.class_id
    where sc.deleted = 0
    and sc.buy_book = 1
    and sc.semester_id = ".session()->get('semester_id')
    ." order by p.parent_id ";
  $students = $db->query($sql)->getResultArray();
  $num = 1;

  foreach($students as $s) {
    ?>
      <tr>
          <td><?php echo $s['parent_id']?></td>
          <td><?php echo $s['primary_en_name']?></td>
          <td><?=$s['email']?></td>
          <td><?php echo $s['sename']?></td>
          <td><?php echo $s['class_code']?></td>
          <td><?php echo $s['class_name']?></td>
          <td>
            <?php $paid = 0;
            foreach($payment['checks'][$s['parent_id']] as $check) {
              $paid += $check['check_amount'];
            }
            $balance = $payment['total'][$s['parent_id']] + $payment['pod'][$s['parent_id']]['penalty'] - $payment['discount'][$s['parent_id']] - $paid;
            if($balance > 0) {
              $positiveBalances[$s['parent_id']]= "<tr><td>".$s['primary_en_name']."</td><td>".$s['email']."</td><td>".$s['parent_id']
                ."</td><td>".$balance."</td></tr>";
            }
            ?>
              <span style="color: <?= $balance>0?'red':''?>"><?=$balance?></span>
          </td>
      </tr>
    <?php $num++; } ?>
    <tr bgcolor="#FFFFFF" >
        <td height="5" colspan="11"><br>
        </td>
    </tr>
</table>

</body>
</html>