<html>
<head>
  <title>Chinese School</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <style>
    body, p, th, td {
      font-size: 10pt;
      color: #000;
      font-family: Arial, Helvetica, sans-serif;
      font-style: normal;
      font-weight: normal;
    }

    th {
      font-weight: bold;
    }

  </style>
</head>

<body bgcolor="#ffffff" leftmargin="20" topmargin="10" onload="window.print();">

<table width="650" border="1" cellspacing="0" cellpadding="3" align="left">
  <TR>
      <TD colspan=2 align=center style="font-size: 15pt; font-weight: bold; color: #660000;">Xilin North Shore Chinese School
        <?if(isset($receipt) && $receipt =='Yes') { ?>
        <br/>Payment Receipt
        <br/><?=session()->get('current_semester')['semester_year'] .' ' . session()->get('current_semester')['semester_name']?>
        <? } ?>

      </TD>
  </TR>
  <TR>
    <TD align="right" width="109">Email</TD>
    <TD align="left" width="500"><?= $parent['email'] ?><span
        style="float:right;">Parent ID: &nbsp;&nbsp;<?= $parent['parent_id'] ?>&nbsp;&nbsp; </span></TD>
  </TR>
  <TR>
    <TD align="right" width="109">Primary Contact</TD>
    <TD align="left" width="500"><?= $parent['primary_relationship'] ?>
      , <?= $parent['primary_en_name'] ?><?= $parent['primary_cn_name'] ? '(' . $parent['primary_cn_name'] . ')' : '' ?>
      , <?= $parent['primary_phone'] ?></TD>
  </TR>
  <TR>
    <TD align="right" width="109">Altern. Contact</TD>
    <TD align="left"
        width="500"><?= $parent['alter_relationship'] ? $parent['alter_relationship'] : '' ?><?= $parent['alter_en_name'] ? ', ' . $parent['alter_en_name'] : '' ?>
      <?= $parent['alter_cn_name'] ? '(' . $parent['alter_cn_name'] . ')' : '' ?><?= $parent['alter_phone'] ? ', ' . $parent['alter_phone'] : '' ?></TD>
  </TR>
  <TR>
    <TD align="right" width="109">Home Address</TD>
    <TD align="left" width="500"><?= $parent['address'] ?></TD>
  </TR>
</table>

<br clear=all>
<br>
<table width="650" border="1" cellspacing="0" cellpadding="5" align="left">
  <?$number = 0;
  $total_tuition = 0.00;
  $total_book_material = 0.00;
  $total = 0.00;
  foreach ($students as $student) {
    if (isset($classes[$student['student_id']]) && !empty($classes[$student['student_id']])) {
      ?>
      <tr>
        <td width=10><?= ++$number ?></td>
        <td>Student Name</td>
        <td><?= $student['en_name'] ?><?= $student['cn_name'] ? '(' . $student['cn_name'] . ')' : '' ?></td>
        <td align="center" colspan="2"><?= $student['birthday'] ?></td>
        <td align="center"><?= $student['gender'] ?></td>
      <tr>
        <th>&nbsp;</th>
        <th colspan=5>Registered Classes</th>
      </tr>
      <tr>
        <th width=10>&nbsp;</th>
        <th>Class Code</th>
        <th>Class Name</th>
        <th>Room #</th>
        <th>Class Time</th>
        <th>Tuition+Fee</th>
      </tr>
      <?
      $tuition = array();
      $book_material = array();
      $sub_total = array();
      foreach ($classes[$student['student_id']] as $class) {
      $is_late = strtotime($class['registration_time']) - strtotime(session()->get('late_date')) > 0 ? true : false;
      $fees = $is_late ? $class['late_tuition'] .'+'. $class['late_book_fee'] . '+'. $class['material_fee'] : $class['tuition'] .'+'. $class['book_fee'] . '+'. $class['material_fee'];
        $tuition[$student['student_id']] += $is_late ? $class['late_tuition'] : $class['tuition'];
        $book_material[$student['student_id']] += $is_late ? $class['late_book_fee']+$class['material_fee'] : $class['book_fee'] + $class['material_fee'];
        ?>
        <tr>
          <td width=10>&nbsp;</td>
          <td><?= $class['class_code'] ?></td>
          <td style="white-space: nowrap;"><?= $class['class_name'] ?></td>
          <td><?= $class['classroom'] ?></td>
          <td><?= $class['meeting_time'] ?></td>
          <td>$<?= $fees ?></td>
        </tr>
      <? }
      $total_tuition += $tuition[$student['student_id']];
      $total_book_material += $book_material[$student['student_id']];
      $sub_total[$student['student_id']] += $tuition[$student['student_id']] + $book_material[$student['student_id']];
      $total += $sub_total[$student['student_id']];?>
      <tr>
        <td width=10>&nbsp;</td>
        <td colspan=2 align=center>Tuition: <?= $tuition[$student['student_id']] ?>, Book &amp; Material: <?= $book_material[$student['student_id']] ?></td>
        <td align=right colspan=2>Subtotal:</td>
        <td>$<?= $sub_total[$student['student_id']] ?>&nbsp;</td>
      </tr>
    <?
    }
    $reg_fee = $total > 0 ? session()->get('current_semester')['registration_fee'] : 0;
    $this->load->model('discount_model');
    $disc = new Discount_model();
    if($_SESSION['tm'] == 'xilin7/') {
      $discount = $disc->getXilinDiscount($parent, $total_tuition, session()->get('current_semester'));
    } else {
      $discount = $disc->getDiscount($parent, $total_tuition, session()->get('current_semester'));
    }
  }?>

  <tr>
    <td colspan=6>
      <hr width=400/>
    </td>
  </tr>
  <tr>
    <td colspan=5 align=right>Non-refundable Registration fee per
      family: <?= session()->get('current_semester')['registration_fee'] ?>&nbsp;</td>

    <td>$<?= $reg_fee ?></td>
  </tr>

  <tr>
    <td colspan=5 align=right>Tuition: <?=$total_tuition?>, Book &amp; Material: <?=$total_book_material?>&nbsp;</td>

    <td>$<?=$total?>&nbsp;</td>
  </tr>
  <tr>
    <td colspan=5 align=right><b>Total: </b></td>

    <td><b>$<?=$total + $reg_fee?></b></td>
  </tr>
  <tr>
    <td colspan=5 align=right>Parents on duty charge:</td>
    <td>$<?=$pod?></td>
  </tr>
  <tr>
    <td colspan=5 align=right>Discount:</td>
    <td>$<?=$discount?></td>
  </tr>
  <tr>
    <td colspan=5 align=right>Paid Amount:</td>
    <td>$<?=$paid?></td>
  </tr>
<!--  <tr>
    <td colspan=5 align=right>Refund:</td>
    <td>$0</td>
  </tr>-->
  <? $balance = $total + $reg_fee + $pod - $discount - $paid;?>
  <tr>
    <td colspan=5 align=right><b>Balance Due: </b></td>
    <td><b>$<?=$balance?></b></td>
  </tr>
  <?if(isset($receipt) && $receipt =='Yes') { ?>
    <tr>
      <td colspan=6 align=left>
        <?php
        if($balance>0) {
          echo "<font color=red>You have unpaid balance of $"."$balance".". Please pay your balance before printing this receipt!</font>";
        } else {
          echo " The payment has been received by Xilin NS Chinese School. &nbsp; &nbsp; &nbsp; Today's date: ".date("F j, Y");
        }
        ?>
      </td>
    </tr>
  <? } else { ?>
  <tr>
    <td colspan=6 align=left>Check No: ________ Date: __________________ Received By: __________________________</td>
  </tr>
  <? } ?>
</table>

<br clear=all>
<?if(!isset($receipt) || empty($receipt)) { ?>
  <p>I want to support Xilin NS Chinese School, a 501 (c)(3) nonprofit organization, with the amount of: <br/><br/>
    $5____ $10____ $25____ $50____ $100____ Other____________
  </p>

  <p><b>Check Payable to: Xilin North Shore Chinese School.</b></p>
  <br><br>

  <table width="650" border="1" cellspacing="0" cellpadding="0" align="left">
    <tr>
      <th>TERMS AND AGREEMENT</th>
    </tr>
    <tr>
      <td class=smallBoldBlackText>
        I, ______________________, parent/guardian of _________________________, have read and agree to <br/><br/>
        (1) Grant permission for him/her/them to participate in all school activities in Xilin North Shore Chinese School
        during this school year. The date, time and location of the activities are included in school notices in written
        or oral formats. I hereby waive and release all claims against Xilin North Shore Chinese School and/or Oakton
        Community College, its governing committee, its members, teacher(s)/leader(s), parents from any injury, including
        death, loss, damage, accident, medical care, delay, or expense incurred during participation in these
        activities.<br><br>
        (2) Serve as a parent-on-duty for at least one 2-hour and 20-minute time slot for each student enrolled during
         each year. Full parent-on-duty rules are listed at <a
          href="https://xilinnschinese.org/public/uploadfiles/web_documents/XilinNS_POD_rules.pdf" target="_blank">https://xilinnschinese.org/public/uploadfiles/web_documents/XilinNS_POD_rules.pdf</a><br/><br/>
        (3) Grant permission for the school to take photographs/videos of children and use the photos/videos in the
        school’s website and printed publications. Full agreement file is listed at <a
          href="http://www.xilinnschinese.org/misc_info/photo_permission.pdf" target="_blank">http://www.xilinnschinese.org/misc_info/photo_permission.pdf</a><br/><br/>
        <br>
        Signature (签名):__________________________________________ &nbsp; Date（日期）: ______________________
        <br><br>
        Note (注)：我自愿签名并完全理解以上责任豁免表英文的全部含意。<br><br/>
        Please specify below any special medical needs or problems. <br><br>

        --------------------------------------------------------------------------------------------------------------------------
      </td>
    </tr>

  </table>
  <br clear=all>
  <br>

  <table width="600" border="0" cellspacing="0" cellpadding="0" align="left">
    <tr>
      <td class=smallBoldBlackText>
        Xilin North Shore Chinese School admits students of any race, color, national and ethnic origin. It does not
        discriminate on the basis of race, religion, color, national and ethnic origin in its admission, athletic,
        educational, hiring, scholarship, or financial aid policies and programs.
      </td>
    </tr>
  </table>
  <br><br>
<? } ?>
</body>
</html>
