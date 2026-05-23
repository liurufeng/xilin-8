<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body style="margin:0; padding:0;">

<?php $terms =  ' <table id="terms" width="650" border="1" cellspacing="0" cellpadding="0" align="center"> ' .
  '<tr>      <th>TERMS AND AGREEMENT</th>    </tr>' .
  '<tr>      <td class=smallBoldBlackText>'.
  'By registering class/classes for my child/children or myself with Xilin North Shore Chinese School, ' .
  'I hereby agree to - <br/><br/>'.
  '(1) Grant permission for him/her/them to participate in all school activities in ' .
  'Xilin North Shore Chinese School during this school year. ' .
  'The date, time and location of the activities are included in school notices in writtenor oral formats. ' .
  'I hereby waive and release all claims against Xilin North Shore Chinese School and/or Oakton Community College, ' .
  'its governing committee, its members, teacher(s)/leader(s), parents from any injury, including death, loss, ' .
  'damage, accident, medical care, delay, or expense incurred during participation in these activities.<br><br>'.
  '((2) Grant permission for the school to take photographs/videos of children and use the photos/videos ' .
  'in the school’s website and printed publications.<br/><br/>'.
  '(3) Serve as a parent-on-duty for at least one 2-hour and 20-minute time slot for each student ' .
  'enrolled during each school semester. Full parent-on-duty rules are listed at ' .
  '<a href="https://xilinnschinese.org/public/uploadfiles/web_documents/XilinNS_POD_rules.pdf" target="_blank">https://xilinnschinese.org/public/uploadfiles/web_documents/XilinNS_POD_rules.pdf</a><br/><br/>';
?>

<?php if(isset($agreement) && $agreement =='Yes') { echo '<br><br>'.$terms . '</body></html>'; exit; } ?>

<table style="width: 980px; margin-top: 30px;">
    <tbody>
    <tr>
        <td style="width: 800px;"><strong style="margin-left: 40%; font-size: 1.5em;">Family Registration Invoice</strong></td>
        <td style="width: 180px;"><img style="float: right; position: relative; top: -12px; right: 5px;" src="<?php echo base_url();?>/<?=$_SESSION['tm']?>img/logo.png" alt="logo" width="110" height="110" /></td>
    </tr>
    </tbody>
</table>

<table style="width: 980px; border-bottom: 2px solid;">
    <tbody>
    <tr>
        <td style="width: 140px;">Invoice Date:</td>
        <td style="width: 288px;"><?= $invoice_date?></td>
        <td style="width: 275px; text-align: right;">&nbsp;Tax ID: 30-0332045</td>
    </tr>
    <tr>
        <td>Family ID:</td>
        <td><?= $parent['parent_id'] ?></td>
        <td style="text-align: right;">&nbsp;4957 Oakton Street, Suite 292</td>
    </tr>
    <tr>
        <td style="font: bold; color: red;">Payment Code:</td>
        <td style="font: bold; color: red;"><?= $pay_code ?></td>
        <td style="text-align: right;">&nbsp;Skokie, IL 60077</td>
    </tr>
    <tr>
        <td>Phone:</td>
        <td><?= $parent['primary_phone'] ?></td>
        <td style="text-align: right;">&nbsp;www.xilinnschinese.org</td>
    </tr>
    <tr>
        <td>email:</td>
        <td><?= $parent['email'] ?></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    </tbody>
</table>

<table style="width: 980px; border-bottom: 2px solid;margin-top: 15px;margin-bottom: 15px;">
    <tbody>
    <tr>
        <td style="width: 80px;"><strong>Student Name</strong></td>
        <td style="width: 55px;"><strong>Semester</strong></td>
        <td style="width: 65px;"><strong>Class Code</strong></td>
        <td style="width: 180px;"><strong>Class Name</strong></td>
        <td style="width: 50px;"><strong>Room</strong></td>
        <td style="width: 50px;"><strong>Time</strong></td>
        <td style="width: 55px;"><strong>Tuition</strong></td>
        <td style="width: 40px;"><strong>Textbook</strong></td>
        <td style="width: 30px; text-align: right;"><strong> </strong></td>
        <td style="width: 50px; text-align: right;"><strong> </strong></td>
    </tr>
    <?php
    $number = 0;
    $total_tuition = 0.00;
    $total_book_material = 0.00;
    $total = 0.00;

    foreach ($students as $student) {
    if (isset($classes[$student['student_id']]) && !empty($classes[$student['student_id']])) {
    ?>

      <?php
      $tuition = array();
      $book_material = array();
      $sub_total = array();
      foreach ($classes[$student['student_id']] as $class) {
        if(!isset($tuition[$student['student_id']])) $tuition[$student['student_id']] = 0;
        if(!isset($book_material[$student['student_id']])) $book_material[$student['student_id']] = 0;

        $is_late = strtotime($class['registration_time']) - strtotime($semester['late_registration']) > 0;
        $book_fee = $class['buy_book'] ? ($is_late ? $class['late_book_fee']: $class['book_fee']  ) : 0;
        $fees = $is_late ? $class['late_tuition'] .'+'. $book_fee : $class['tuition']
          .'+'. $book_fee;
        if ($semester['registration_fee'] > 0) {
          $fees .= '+'. $class['material_fee'];
        } else {
          $class['material_fee'] = 0;
        }
        $class_tuition = $is_late ? $class['late_tuition'] : $class['tuition'];
        $tuition[$student['student_id']] += $class_tuition;
        $book_material[$student['student_id']] += $book_fee + $class['material_fee'];
        ?>

          <tr>
              <td><?= $student['en_name'] ?></td>
              <td><?=$semester['semester_name'] .' ' . $semester['semester_year']?></td>
              <td><?= $class['class_code'] ?></td>
              <td><?= $class['class_name'] ?></td>
              <td><?= $class['classroom'] ?></td>
              <td><?= $class['meeting_time'] ?></td>
              <td><?= $class_tuition ?></td>
              <td><?= $book_fee  ?></td>
              <td style="width: 45px; text-align: right;"><?= $class['material_fee'] ? $class['material_fee'] : '' ?></td>
              <td style="width: 70px; text-align: right;"> </td>
          </tr>
      <?php }
      $total_tuition += $tuition[$student['student_id']];
      $total_book_material += $book_material[$student['student_id']];
      if(isset($sub_total[$student['student_id']])) {
        $sub_total[$student['student_id']]
          += $tuition[$student['student_id']]
          + $book_material[$student['student_id']];
      } else {
        $sub_total[$student['student_id']]
          = $tuition[$student['student_id']]
          + $book_material[$student['student_id']];
      }
      $total += $sub_total[$student['student_id']];?>

      <?php
    }
    //Due to covid-19, there is no POD charges, and if the parent is a waiver or has done any POD, waive reg fee
    $year = $semester['semester_year'];
    $sem_name = $semester['semester_name'];
    if(($year == '2020' || $year == '2021') && $sem_name == 'Fall') {
    $pod['penalty'] = 0;
    $reg_fee = ($total < 1.0 || $pod['waiver']=='yes' || $pod['done']>0 || $pod['manually']>0) ? 0 :
    $semester['registration_fee'];
    } else {
    $reg_fee = $total > 0 ? $semester['registration_fee'] : 0;
    }

    $discount = $disc->getXilinDiscount($parent, $total_tuition, $semester);
    }?>
    <tr>
        <td style="width: 57px;">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td style="width: 57px;">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="width: 50px; text-align: right;" colspan="3">Total Registration Charges</td>
        <td style="width: 45px; text-align: right;">$<?=$total_tuition + $total_book_material?></td>
        <td>&nbsp;</td>
    </tr>
    <?php if($reg_fee > 0) { ?>
        <tr>
        <td style="width: 57px;">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="width: 50px; text-align: right;" colspan="3">Registration Fee</td>
        <td style="width: 45px; text-align: right;">$<?=$reg_fee?></td>
    <td>&nbsp;</td>
    </tr>
    <?php } ?>
    <tr>
        <td style="width: 57px;">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="text-align: right;" colspan="2">POD Waiver</td>
        <td style="text-align: right;">$0</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td style="width: 57px;">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="text-align: right;">POD Fine</td>
        <td style="text-align: right;">$<?=$pod['penalty']?></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td style="width: 57px;">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="text-align: right;">&nbsp;</td>
        <td style="text-align: right;">Donation</td>
        <td style="text-align: right;">$0</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td style="width: 57px;">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="text-align: right;">Discount</td>
        <td style="text-align: right;">$<?=$discount?></td>
        <td>&nbsp;</td>
    </tr>
    <?php if(count($prorate) > 0) : ?>
    <?php foreach($prorate as $p) : ?>
        <tr>
            <td style="width: 57px;">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="text-align: right;">Proration</td>
            <td style="text-align: right;">$<?=$p?></td>
            <td>&nbsp;</td>
        </tr>
    <?php endforeach ?>
    <?php endif ?>

    <!--<tr>
        <td style="width: 57px;">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="text-align: right;" colspan="2">Previous Balance</td>
        <td style="text-align: right;">$0</td>
        <td>&nbsp;</td>
    </tr>-->
    <?php if($late_fee > 0) { ?>
        <tr>
            <td style="width: 57px;">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="text-align: right;" colspan="2">Late Fee</td>
            <td style="text-align: right;">$<?=$late_fee?></td>
            <td>&nbsp;</td>
        </tr>
    <?php } ?>

    <?php
    $total_book_chrg = 0;
    foreach($book_charge as $bk) {
      $total_book_chrg += $bk;
    }
    ?>
    <?php $balance = $total + $reg_fee + $pod['penalty'] - $discount - $paid + $total_book_chrg + $late_fee;?>
    <tr>
        <td style="width: 57px;">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="text-align: right;"><strong><span style="font: bold; color: red;">*</span>Total Due</strong></td>
        <td style="text-align: right;">$<?=$balance?></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td style="width: 57px;" colspan="10"> &nbsp; </td>
    </tr>
    <tr>
        <td style="width: 57px;" colspan="3"><strong><span style="color: #339966;">Payment Instructions:</span></strong></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="text-align: right;">&nbsp;</td>
        <td style="text-align: right;">&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td style="width: 57px;" colspan="10">1. Pay by Zelle to payment@xilinnschinese.org. Please include payment code <span style="font: bold; color: red;"><?= $pay_code ?></span> in memo.</td>
    </tr>
    <tr>
        <td style="width: 57px;" colspan="10">2. Pay by check to "Xilin North Shore Chinese School". Please include payment code in memo.</td>
    </tr>
    <tr>
        <td style="width: 57px;" colspan="10">3. For questions, contact "ec@xilinnschinese.org".</td>
    </tr>
    <tr>
        <td style="width: 57px; color:red" colspan="10">* The Total Due may be subject to change due to fee adjustments,
            including but not limited to POD charges, discounts, early bird offers, and errors. The school reserves the right to collect any unpaid amounts.</td>
    </tr>
    <tr>
        <td style="width: 57px;" colspan="10"> &nbsp; </td>
    </tr>
    </tbody>
</table>

</body>
</html>