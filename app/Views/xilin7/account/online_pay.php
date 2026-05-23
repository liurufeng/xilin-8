<?php echo view($_SESSION['tm'].'uc/header.php') ?>

  <section>
  <div class="container-wrapper">
  <div class="container">
  <div class="row">
  <?php echo view($_SESSION['tm'].'uc/account_header.php') ?>
  <div style="clear:both;"></div>

  <div class="Profile">

  <? if (session()->flashdata('success_msg')) { ?>
    <div class="row success-flash"><b><?= session()->flashdata('success_msg'); ?></b></div>
  <? } ?>

  <? if (session()->flashdata('error_msg')) { ?>
    <div class="row error-flash"><b><?= session()->flashdata('error_msg'); ?></b></div>
  <? } ?>

    <?php if ($usertype === 1) { ?>
      <div class="row">
          <table class="table table-hover table-bordered" width="95%">
            <TR>
              <TD colspan=2 align=center style="font-size: 15pt; font-weight: bold; color: #660000;">Xilin North Shore Chinese School</TD>
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
    </div>
<br clear=all>
<br>
<table class="table table-hover table-bordered" width="95%">
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

  <tr>
    <td colspan=5 align=right><b>Balance Due: </b></td>
    <?php $balance = $total + $reg_fee + $pod - $discount - $paid?>
    <td><b>$<?=$balance?></b></td>
  </tr>

  <tr>
    <td colspan=5 align=right><b>Online Processing Fee (6%) </b></td>
    <?php $fee = $balance * 0.06?>
    <td><b>$<?=number_format($fee, 2)?></b></td>
  </tr>

  <tr>
    <td colspan=5 align=right><b>Online Payment Total </b></td>
    <?php $online_total = round(floatval($balance + $fee),2) ?>
    <td><b>$<?=number_format($online_total, 2)?></b></td>
  </tr>
</table>

<br clear=all>

<table  class="table table-hover" width="95%">
  <tr>
    <th>TERMS AND AGREEMENT</th>
  </tr>
  <tr>
    <td class=smallBoldBlackText>
      (1) Grant permission for him/her/them to participate in all school activities in Xilin North Shore Chinese School
      during this school year. The date, time and location of the activities are included in school notices in written
      or oral formats. I hereby waive and release all claims against Xilin North Shore Chinese School and/or Oakton
      Community College, its governing committee, its members, teacher(s)/leader(s), parents from any injury, including
      death, loss, damage, accident, medical care, delay, or expense incurred during participation in these
      activities.<br><br>
      (2) Serve as a parent-on-duty for at least one 2-hour and 20-minute time slot for each student enrolled during
      each semester. Full parent-on-duty rules are listed at <a
        href="http://xilinnschinese.org/uploadfiles/web_documents/XilinNS_POD_rules.pdf" target="_blank">http://xilinnschinese.org/uploadfiles/web_documents/XilinNS_POD_rules.pdf</a><br/><br/>
      (3) Grant permission for the school to take photographs/videos of children and use the photos/videos in the
      school’s website and printed publications. Full agreement file is listed at <a
        href="http://xilinnschinese.org/uploadfiles/web_documents/photo_permission.pdf" target="_blank">http://xilinnschinese.org/uploadfiles/web_documents/photo_permission.pdf</a><br/><br/>
      <br>
      Signature (签名):<input type="text" name="sig" id="sig" value="" placeholder="Type in your full name" required="" autocomplete="off"> &nbsp;
      Date（日期）: <input type="text" name="pdate" id="pdate" value="" placeholder="Today's date" required="" autocomplete="off">
      <br><br>

    </td>
  </tr>

</table>
<br clear=all>

<table  class="table table-hover" width="95%">
  <tr>
    <td class=smallBoldBlackText>
       Chinese School admits students of any race, color, national and ethnic origin. It does not
      discriminate on the basis of race, religion, color, national and ethnic origin in its admission, athletic,
      educational, hiring, scholarship, or financial aid policies and programs.
    </td>
  </tr>
</table>
<br><br>
    <div class="">
    <table class="table" width="95%">
      <tr>
        <td>
          <input type="checkbox" name="consent" id="consent" required="" autocomplete="off"> 我自愿签名并完全理解以上责任豁免表英文的全部含意，并同意网上缴纳以上的费用
          <br> &nbsp; &nbsp; &nbsp; I agree with the above TERMS AND AGREEMENT and I'm willing to make online payment for the above Online Payment Total.
        </td>
      </tr>
      <tr>
        <td>
          <form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="paypal">
            <input type="hidden" name="cmd" value="_xclick">
            <input type="hidden" name="business" value="support@6dlink.com">
            <input type="hidden" name="item_name" value="Chinese School Payment #<?= $parent['parent_id'] ?>">
            <input type="hidden" name="item_number" value="<?= $parent['parent_id'] ?>">
            <input type="hidden" name="amount" value="<?= $online_total?>">
            <input type="hidden" name="currency_code" value="USD">
            <input type="hidden" name="image_url" value="http://6dlink.com/img/logo-pay.png">
            <input type="hidden" name="no_shipping" value="1">
            <input type="hidden" name="undefined_quantity" value="0">
            <input type="hidden" name="no_note" value="1">
            <input type="hidden" name="return" id="return" value="//<?=$_SERVER['SERVER_NAME']?>/account/online_payment/<?=$balance?>/<?=$fee?>" autocomplete="off">
            <input type="hidden" name="cancel_return" value="//<?=$_SERVER['SERVER_NAME']?>/account/invoice/online">
            <input type="image" id="paypay_btn" src="/common/img/paynow.png" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!"
                   style="display: block !important;margin: auto !important;" disabled autocomplete="off" onclick="precheck(); return false;">
          </form>
        </td>
      </tr>
    </table>
    </div>
</section>

    <?php } else { ?>
    <section id="contact">
        <div class="container-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-sm-4 col-sm-offset-8">
                        <div class="contact-form">
                            <h3><a href="/register" style="float:right;">REGISTER</a>LOGIN</h3>

                            <form name="contact-form" method="POST" action="/login/check_login">
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control" placeholder="Password"
                                           required>
                                </div>
                                <button type="submit" class="btn btn-primary">LOGIN</button>
                            </form>
                            <div style="margin-top:20px;">Click <a href=''>here</a> for Financial Aid Information and
                                Discount Policy.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!--/#bottom-->
      <?php } ?>


    <?php echo view($_SESSION['tm'].'uc/footer.php') ?>
